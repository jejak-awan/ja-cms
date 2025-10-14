<?php

namespace App\Modules\Page\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Page\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PageController extends Controller
{
    public function index(Request $request)
    {
        $query = Page::with('user')->orderBy('order', 'asc');

        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('template')) {
            $query->where('template', $request->template);
        }

        $pages = $query->paginate(20);

        $stats = [
            'total' => Page::count(),
            'published' => Page::where('status', 'published')->count(),
            'draft' => Page::where('status', 'draft')->count(),
        ];

        return view('admin.pages.index', compact('pages', 'stats'));
    }

    public function create()
    {
        $templates = Page::templates();
        return view('admin.pages.create', compact('templates'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|max:255',
            'slug' => 'nullable|unique:pages,slug|max:255',
            'content' => 'required',
            'excerpt' => 'nullable',
            'template' => 'required|in:' . implode(',', array_keys(Page::templates())),
            'featured_image' => 'nullable|image|max:2048',
            'status' => 'required|in:draft,published',
            'is_homepage' => 'nullable|boolean',
            'meta_title' => 'nullable|max:255',
            'meta_description' => 'nullable',
            'meta_keywords' => 'nullable|max:255',
        ]);

        $validated['slug'] = $validated['slug'] ?? Str::slug($validated['title']);
        $validated['user_id'] = auth()->id();
        $validated['order'] = Page::max('order') + 1;

        if ($request->hasFile('featured_image')) {
            $validated['featured_image'] = $request->file('featured_image')->store('pages', 'public');
        }

        if ($validated['status'] === 'published' && !$request->published_at) {
            $validated['published_at'] = now();
        }

        // If set as homepage, unset others
        if ($request->is_homepage) {
            Page::where('is_homepage', true)->update(['is_homepage' => false]);
        }

        Page::create($validated);

        return redirect()->route('admin.pages.index')
            ->with('success', 'Page created successfully!');
    }

    public function edit(Page $page)
    {
        $templates = Page::templates();
        return view('admin.pages.edit', compact('page', 'templates'));
    }

    public function update(Request $request, Page $page)
    {
        $validated = $request->validate([
            'title' => 'required|max:255',
            'slug' => 'nullable|unique:pages,slug,' . $page->id . '|max:255',
            'content' => 'required',
            'excerpt' => 'nullable',
            'template' => 'required|in:' . implode(',', array_keys(Page::templates())),
            'featured_image' => 'nullable|image|max:2048',
            'status' => 'required|in:draft,published',
            'is_homepage' => 'nullable|boolean',
            'meta_title' => 'nullable|max:255',
            'meta_description' => 'nullable',
            'meta_keywords' => 'nullable|max:255',
        ]);

        $validated['slug'] = $validated['slug'] ?? Str::slug($validated['title']);

        if ($request->hasFile('featured_image')) {
            if ($page->featured_image) {
                Storage::disk('public')->delete($page->featured_image);
            }
            $validated['featured_image'] = $request->file('featured_image')->store('pages', 'public');
        }

        if ($validated['status'] === 'published' && !$page->published_at) {
            $validated['published_at'] = now();
        }

        // If set as homepage, unset others
        if ($request->is_homepage) {
            Page::where('id', '!=', $page->id)
                ->where('is_homepage', true)
                ->update(['is_homepage' => false]);
        }

                $page->update($validated);

        // Clear cached page content
        cache()->forget("public_page_{$page->slug}");

        return redirect()
            ->route('admin.pages.index')
            ->with('success', 'Page updated successfully!');
    }

    public function destroy(Page $page)
    {
        if ($page->featured_image) {
            Storage::disk('public')->delete($page->featured_image);
        }

        $page->delete();

        return redirect()->route('admin.pages.index')
            ->with('success', 'Page deleted successfully!');
    }

    public function updateOrder(Request $request)
    {
        $pages = $request->input('pages', []);

        foreach ($pages as $index => $id) {
            Page::where('id', $id)->update(['order' => $index]);
        }

        return response()->json(['success' => true]);
    }

    public function toggleStatus($id)
    {
        $page = Page::findOrFail($id);
        $page->status = $page->status === 'published' ? 'draft' : 'published';
        if ($page->status === 'published' && !$page->published_at) {
            $page->published_at = now();
        }
        $page->save();
        // Clear page cache after status toggle
        cache()->forget("public_page_{$page->slug}");
        return response()->json([
            'success' => true,
            'status' => $page->status
        ]);
    }
}
