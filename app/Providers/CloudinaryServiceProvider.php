<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Cloudinary\Cloudinary;

class CloudinaryServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(Cloudinary::class, function () {
            $config = [
                'cloud' => [
                    'cloud_name' => config('cloudinary.cloud_name'),
                    'api_key' => config('cloudinary.api_key'),
                    'api_secret' => config('cloudinary.api_secret'),
                ],
                'url' => [
                    'secure' => config('cloudinary.secure', true),
                ],
            ];
            
            \Log::info('Cloudinary config', [
                'cloud_name' => config('cloudinary.cloud_name'),
                'has_api_key' => !empty(config('cloudinary.api_key')),
                'has_api_secret' => !empty(config('cloudinary.api_secret')),
            ]);
            
            return new Cloudinary($config);
        });

        // Register upload helper as a singleton
        $this->app->singleton('cloudinary.uploader', function () {
            return new class {
                public function uploadProfilePicture($file, $userId)
                {
                    try {
                        $cloudinary = app(Cloudinary::class);
                        
                        \Log::info('Uploading profile picture to Cloudinary', [
                            'user_id' => $userId,
                            'file_path' => $file->getRealPath()
                        ]);

                        $uploadResult = $cloudinary->uploadApi()->upload(
                            $file->getRealPath(),
                            [
                                'folder' => 'Kasih_Istimewa/Profile_Picture',
                                'public_id' => 'user_' . $userId . '_' . time(),
                                'transformation' => [
                                    ['width' => 300, 'height' => 300, 'crop' => 'fill']
                                ]
                            ]
                        );

                        \Log::info('Cloudinary upload result', [
                            'success' => true,
                            'url' => $uploadResult['secure_url'] ?? 'no_url'
                        ]);

                        return $uploadResult['secure_url'] ?? null;
                    } catch (\Exception $e) {
                        \Log::error('Profile picture upload failed: ' . $e->getMessage());
                        throw $e;
                    }
                }

                public function uploadEventPicture($file, $eventId)
                {
                    try {
                        $cloudinary = app(Cloudinary::class);

                        $uploadResult = $cloudinary->uploadApi()->upload(
                            $file->getRealPath(),
                            [
                                'folder' => 'Kasih_Istimewa/Event_Picture',
                                'public_id' => 'event_' . $eventId . '_' . time(),
                                'transformation' => [
                                    ['width' => 800, 'height' => 600, 'crop' => 'fill']
                                ]
                            ]
                        );

                        return $uploadResult['secure_url'] ?? null;
                    } catch (\Exception $e) {
                        \Log::error('Event picture upload failed: ' . $e->getMessage());
                        throw $e;
                    }
                }

                public function uploadEventDocument($file, $eventId)
                {
                    try {
                        $cloudinary = app(Cloudinary::class);
                        
                        $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                        $sanitizedName = preg_replace('/[^a-zA-Z0-9]/', '_', $originalName);
                        
                        $uploadResult = $cloudinary->uploadApi()->upload(
                            $file->getRealPath(),
                            [
                                'folder' => 'Kasih_Istimewa/Event_Documents',
                                'public_id' => 'event_doc_' . $eventId . '_' . time() . '_' . $sanitizedName,
                                'resource_type' => 'auto',
                                'type' => 'upload',
                                'access_mode' => 'public',
                                'invalidate' => true,
                            ]
                        );

                        return $uploadResult['secure_url'] ?? null;
                    } catch (\Exception $e) {
                        \Log::error('Event document upload failed: ' . $e->getMessage());
                        throw $e;
                    }
                }
            };
        });
    }

    public function boot()
    {
        //
    }
}