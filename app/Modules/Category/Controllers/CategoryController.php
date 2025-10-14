<?php

namespace App\Modules\Category\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Category\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Support\CacheHelper;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $query = Category::with(['parent', 'children'])->withCount(['articles']);

        if ($request->filled('search')) {
            $query->search($request->search);
        }

        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->active();
            } elseif ($request->status === 'inactive') {
                $query->where('is_active', false);
            }
        }

        // Cache category tree for 5 minutes (only for tree view, no search/filter)
        if ($request->get('view') === 'tree' || !$request->filled('search')) {
            $categories = CacheHelper::remember('admin_categories_tree', 'category', 300, function() use ($query) {
                return $query->root()->ordered()->get();
            });
            $viewType = 'tree';
        } else {
            $categories = $query->ordered()->get();
            $viewType = 'list';
        }

        $allCategories = Category::ordered()->get();

        if ($request->wantsJson()) {
            return response()->json(['categories' => $categories, 'view_type' => $viewType]);
        }

        return view('admin.categories.index', compact('categories', 'allCategories', 'viewType'));
    }

    public function create()
    {
        $categories = Category::root()->ordered()->get();
        return view('admin.categories.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|unique:categories,slug',
            'description' => 'nullable|string',
            'parent_id' => 'nullable|exists:categories,id',
            'color' => 'nullable|string|max:7',
            'icon' => 'nullable|image|mimes:png,jpg,jpeg,svg|max:1024',
            'is_active' => 'boolean',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'meta_keywords' => 'nullable|string',
        ]);

        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        if ($request->hasFile('icon')) {
            $validated['icon'] = $request->file('icon')->store('categories/icons', 'public');
        }

        if ($request->filled('parent_id')) {
            $maxOrder = Category::where('parent_id', $request->parent_id)->max('order') ?? 0;
            $validated['order'] = $maxOrder + 1;
        } else {
            $maxOrder = Category::whereNull('parent_id')->max('order') ?? 0;
            $validated['order'] = $maxOrder + 1;
        }

    Category::create($validated);
    // Clear category cache after create
    cache()->forget('admin_categories_tree');
    cache()->forget('public_categories_homepage');
    cache()->forget('public_categories_list');
    return redirect()->route('admin.categories.index')->with('success', 'Category created successfully!');
    }

    public function edit($id)
    {
        $category = Category::findOrFail($id);
        $categories = Category::where('id', '!=', $id)->root()->ordered()->get();
        return view('admin.categories.edit', compact('category', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|unique:categories,slug,' . $id,
            'description' => 'nullable|string',
            'parent_id' => 'nullable|exists:categories,id',
            'color' => 'nullable|string|max:7',
            'icon' => 'nullable|image|mimes:png,jpg,jpeg,svg|max:1024',
            'is_active' => 'boolean',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'meta_keywords' => 'nullable|string',
        ]);

        if ($request->filled('parent_id') && $request->parent_id == $id) {
            return back()->withErrors(['parent_id' => 'Category cannot be its own parent.']);
        }

        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        if ($request->hasFile('icon')) {
            if ($category->icon) {
                Storage::disk('public')->delete($category->icon);
            }
            $validated['icon'] = $request->file('icon')->store('categories/icons', 'public');
        }

    $category->update($validated);
    // Clear category cache after update
    cache()->forget('admin_categories_tree');
    cache()->forget('public_categories_homepage');
    cache()->forget('public_categories_list');
    return redirect()->route('admin.categories.index')->with('success', 'Category updated successfully!');
    }

    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        
        if ($category->articles()->count() > 0) {
            return back()->withErrors(['delete' => 'Cannot delete category with existing articles.']);
        }

        if ($category->children()->count() > 0) {
            return back()->withErrors(['delete' => 'Cannot delete category with subcategories.']);
        }

        if ($category->icon) {
            Storage::disk('public')->delete($category->icon);
        }

    $category->delete();
    // Clear category cache after delete
    cache()->forget('admin_categories_tree');
    cache()->forget('public_categories_homepage');
    cache()->forget('public_categories_list');
    return redirect()->route('admin.categories.index')->with('success', 'Category deleted successfully!');
    }

    public function updateOrder(Request $request)
    {
        $request->validate([
            'categories' => 'required|array',
            'categories.*.id' => 'required|exists:categories,id',
            'categories.*.order' => 'required|integer',
            'categories.*.parent_id' => 'nullable|exists:categories,id',
        ]);

        DB::beginTransaction();
        try {
            foreach ($request->categories as $categoryData) {
                Category::where('id', $categoryData['id'])->update([
                    'order' => $categoryData['order'],
                    'parent_id' => $categoryData['parent_id'] ?? null,
                ]);
            }
            DB::commit();

            // Clear category cache after order update
            cache()->forget('admin_categories_tree');
            cache()->forget('public_categories_homepage');
            cache()->forget('public_categories_list');
            return response()->json(['success' => true, 'message' => 'Order updated successfully!']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Failed: ' . $e->getMessage()], 500);
        }
    }

    public function toggleStatus($id)
    {
        $category = Category::findOrFail($id);
    $category->is_active = !$category->is_active;
    $category->save();
    // Clear category cache after status toggle
    cache()->forget('admin_categories_tree');
    cache()->forget('public_categories_homepage');
    cache()->forget('public_categories_list');
    return response()->json(['success' => true, 'is_active' => $category->is_active]);
    }
}
