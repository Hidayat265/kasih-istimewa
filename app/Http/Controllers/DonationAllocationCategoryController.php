<?php

namespace App\Http\Controllers;

use App\Models\DonationAllocationCategory;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class DonationAllocationCategoryController extends Controller
{
    /**
     * Display a listing of all categories (AJAX/API)
     */
    public function index(Request $request)
    {
        $query = DonationAllocationCategory::query();

        // Search functionality
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('alc_cat_name', 'like', "%{$search}%")
                  ->orWhere('alc_cat_id', 'like', "%{$search}%");
            });
        }

        // Filter by active status
        if ($request->has('is_active') && $request->is_active !== '') {
            $query->where('alc_cat_is_active', $request->is_active);
        }

        // Sorting
        $sort = $request->get('sort', 'alc_cat_name');
        $direction = $request->get('direction', 'asc');
        $query->orderBy($sort, $direction);

        $categories = $query->paginate(15);

        // Return JSON response for AJAX requests
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'data' => $categories,
                'message' => 'Categories retrieved successfully'
            ]);
        }

        // For non-AJAX requests, return the view
        return view('admin.donations.categories.index', compact('categories'));
    }

    /**
     * Get all active categories for dropdowns
     */
    public function getActiveCategories(Request $request)
    {
        $categories = DonationAllocationCategory::where('alc_cat_is_active', true)
            ->orderBy('alc_cat_name')
            ->get(['alc_cat_id', 'alc_cat_name', 'alc_cat_color', 'alc_cat_icon']);

        return response()->json([
            'success' => true,
            'data' => $categories
        ]);
    }

    /**
     * Store a newly created category
     */
    public function store(Request $request)
    {
        $request->validate([
            'alc_cat_name' => 'required|string|max:255|unique:donation_allocation_categories,alc_cat_name',
            'icon' => 'nullable|string|max:50',
            'color' => 'nullable|string|max:7|regex:/^#[a-fA-F0-9]{6}$/',
        ]);

        // Generate category ID
        $maxId = DonationAllocationCategory::where('alc_cat_id', 'like', 'ALC-CAT-%')
            ->orderByDesc('alc_cat_id')
            ->value('alc_cat_id');

        $nextNumber = 1;
        if ($maxId) {
            $nextNumber = intval(str_replace('ALC-CAT-', '', $maxId)) + 1;
        }

        $category = DonationAllocationCategory::create([
            'alc_cat_id' => sprintf('ALC-CAT-%03d', $nextNumber),
            'alc_cat_name' => $request->alc_cat_name,
            'alc_cat_icon' => $request->icon ?? 'fas fa-heart',
            'alc_cat_color' => $request->color ?? '#554994',
            'alc_cat_is_active' => true,
        ]);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'data' => $category,
                'message' => 'Category created successfully!'
            ], 201);
        }

        return redirect()->route('admin.donations.allocations')
            ->with('success', 'Category "' . $category->alc_cat_name . '" created successfully!');
    }

    /**
     * Display the specified category
     */
    public function show($id)
    {
        $category = DonationAllocationCategory::findOrFail($id);

        if (request()->ajax() || request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'data' => $category
            ]);
        }

        return view('admin.donations.categories.show', compact('category'));
    }

    /**
     * Update the specified category
     */
    public function update(Request $request, $id)
    {
        $category = DonationAllocationCategory::findOrFail($id);

        $request->validate([
            'alc_cat_name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('donation_allocation_categories', 'alc_cat_name')->ignore($category->alc_cat_id, 'alc_cat_id')
            ],
            'alc_cat_icon' => 'nullable|string|max:50',
            'alc_cat_color' => 'nullable|string|max:7|regex:/^#[a-fA-F0-9]{6}$/',
            'alc_cat_is_active' => 'boolean',
        ]);

        $category->update([
            'alc_cat_name' => $request->alc_cat_name,
            'alc_cat_icon' => $request->alc_cat_icon ?? $category->alc_cat_icon,
            'alc_cat_color' => $request->alc_cat_color ?? $category->alc_cat_color,
            'alc_cat_is_active' => $request->alc_cat_is_active ?? $category->alc_cat_is_active,
        ]);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'data' => $category,
                'message' => 'Category updated successfully!'
            ]);
        }

        return redirect()->route('admin.donations.allocations')
            ->with('success', 'Category "' . $category->alc_cat_name . '" updated successfully!');
    }

    /**
     * Remove the specified category
     */
    public function destroy($id)
    {
        $category = DonationAllocationCategory::findOrFail($id);

        // Check if category has allocations
        $hasAllocations = $category->allocations()->exists();

        if ($hasAllocations) {
            if (request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot delete category with existing allocations. Deactivate it instead.'
                ], 422);
            }

            return redirect()->back()
                ->with('error', 'Cannot delete category with existing allocations. Deactivate it instead.');
        }

        $categoryName = $category->alc_cat_name;
        $category->delete();

        if (request()->ajax() || request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Category "' . $categoryName . '" deleted successfully!'
            ]);
        }

        return redirect()->route('admin.donations.allocations')
            ->with('success', 'Category "' . $categoryName . '" deleted successfully!');
    }

    /**
     * Toggle category active status
     */
    public function toggleActive($id)
    {
        try {
            $category = DonationAllocationCategory::findOrFail($id);
            $category->alc_cat_is_active = !$category->alc_cat_is_active;
            $category->save();

            $status = $category->alc_cat_is_active ? 'activated' : 'deactivated';

            if (request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'data' => $category,
                    'message' => 'Category "' . $category->alc_cat_name . '" ' . $status . ' successfully!'
                ]);
            }

            return redirect()->back()
                ->with('success', 'Category "' . $category->alc_cat_name . '" ' . $status . ' successfully!');
        } catch (\Exception $e) {
            if (request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to toggle category status: ' . $e->getMessage()
                ], 500);
            }
            return redirect()->back()->with('error', 'Failed to toggle category status.');
        }
    }

    /**
     * Bulk delete categories (soft delete for those without allocations)
     */
    public function bulkDestroy(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:donation_allocation_categories,alc_cat_id'
        ]);

        $deleted = 0;
        $failed = 0;
        $failedNames = [];

        foreach ($request->ids as $id) {
            $category = DonationAllocationCategory::find($id);
            if ($category) {
                $hasAllocations = $category->allocations()->exists();
                if (!$hasAllocations) {
                    $category->delete();
                    $deleted++;
                } else {
                    $failed++;
                    $failedNames[] = $category->alc_cat_name;
                }
            }
        }

        $message = "{$deleted} categories deleted successfully.";
        if ($failed > 0) {
            $message .= " {$failed} categories skipped (have allocations): " . implode(', ', $failedNames);
        }

        if (request()->ajax() || request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'deleted' => $deleted,
                'failed' => $failed,
                'failed_names' => $failedNames,
                'message' => $message
            ]);
        }

        return redirect()->back()->with('success', $message);
    }
}