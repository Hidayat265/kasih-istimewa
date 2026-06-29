<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Cloudinary\Cloudinary;

class DocumentController extends Controller
{
    public function downloadDocument($eventId)
    {
        Log::info('DocumentController@downloadDocument - Start', ['event_id' => $eventId]);
        
        try {
            $event = Event::where('event_id', $eventId)->firstOrFail();
            
            // Check authorization - only creator or admin
            if ($event->event_created_by_id != auth()->user()->user_id && !auth()->user()->is_admin) {
                abort(403, 'Unauthorized action.');
            }
            
            if (!$event->event_document) {
                abort(404, 'Document not found.');
            }
            
            // For now, just redirect - this should work if PDF is public
            Log::info('Redirecting to Cloudinary URL', ['url' => $event->event_document]);
            return redirect($event->event_document);
            
        } catch (\Exception $e) {
            Log::error('Error in downloadDocument', [
                'event_id' => $eventId,
                'error_message' => $e->getMessage()
            ]);
            abort(500, 'Error: ' . $e->getMessage());
        }
    }
    
    // Alternative: Fetch and serve the PDF through Laravel
    public function downloadDocumentSecure($eventId)
    {
        Log::info('DocumentController@downloadDocumentSecure - Start', ['event_id' => $eventId]);
        
        try {
            $event = Event::where('event_id', $eventId)->firstOrFail();
            
            if ($event->event_created_by_id != auth()->user()->user_id && !auth()->user()->is_admin) {
                abort(403, 'Unauthorized action.');
            }
            
            if (!$event->event_document) {
                abort(404, 'Document not found.');
            }
            
            // Method 1: Try to fetch using Http client with proper headers
            $response = Http::timeout(30)
                ->withOptions(['verify' => false])
                ->get($event->event_document);
            
            if ($response->successful()) {
                Log::info('Successfully fetched PDF from Cloudinary');
                return response($response->body())
                    ->header('Content-Type', 'application/pdf')
                    ->header('Content-Disposition', 'inline; filename="event_document_' . $event->event_id . '.pdf"');
            }
            
            // Method 2: Try using cURL directly
            $ch = curl_init($event->event_document);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            $pdfContent = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
            
            if ($httpCode === 200 && $pdfContent) {
                Log::info('Successfully fetched PDF via cURL');
                return response($pdfContent)
                    ->header('Content-Type', 'application/pdf')
                    ->header('Content-Disposition', 'inline; filename="event_document_' . $event->event_id . '.pdf"');
            }
            
            Log::error('Failed to fetch PDF', ['http_code' => $httpCode]);
            
            // Last resort: Try to add Cloudinary flags to force download
            $modifiedUrl = $event->event_document . '?flags=attachment';
            return redirect($modifiedUrl);
            
        } catch (\Exception $e) {
            Log::error('Error in downloadDocumentSecure', [
                'event_id' => $eventId,
                'error_message' => $e->getMessage()
            ]);
            abort(500, 'Error downloading document: ' . $e->getMessage());
        }
    }
    
    public function viewDocument($eventId)
    {
        return $this->downloadDocument($eventId);
    }
}