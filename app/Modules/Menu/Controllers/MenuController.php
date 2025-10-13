<?php

namespace App\Modules\Menu\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Menu\Models\Menu;
use App\Modules\Menu\Models\MenuItem;
use App\Modules\Page\Models\Page;
use App\Modules\Category\Models\Category;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    public function index()
    {
        $menus = Menu::withCount('items')->get();
        return view('admin.menus.index', compact('menus'));
    }

    public function create()
    {
        return view('admin.menus.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:menus,name',
            'display_name' => 'nullable|string|max:255',
            'location' => 'nullable|string|max:255',
            'description' => 'nullable|string',
        ]);

        Menu::create(array_merge($request->all(), [
            'display_name' => $request->display_name ?? $request->name,
            'location' => $request->location ?? 'primary'
        ]));

        return redirect()->route('admin.menus.index')
            ->with('success', 'Menu created successfully');
    }

    public function edit(Menu $menu)
    {
        $items = $menu->items()
            ->whereNull('parent_id')
            ->with('children')
            ->orderBy('order')
            ->get();

        $pages = Page::where('status', 'published')->get();
        $categories = Category::all();

        return view('admin.menus.edit', compact('menu', 'items', 'pages', 'categories'));
    }

    public function update(Request $request, Menu $menu)
    {
        $request->validate([
            'display_name' => 'nullable|string|max:255',
            'location' => 'nullable|string|max:255',
            'description' => 'nullable|string',
        ]);

        $menu->update($request->all());

        return redirect()->route('admin.menus.edit', $menu)
            ->with('success', 'Menu updated successfully');
    }

    public function destroy(Menu $menu)
    {
        $menu->items()->delete();
        $menu->delete();

        return response()->json([
            'success' => true,
            'message' => 'Menu deleted successfully'
        ]);
    }

    public function addItem(Request $request, Menu $menu)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'url' => 'nullable|string|max:255',
            'type' => 'required|in:custom,page,category',
            'target' => 'nullable|in:_self,_blank',
            'page_id' => 'nullable|exists:pages,id',
            'category_id' => 'nullable|exists:categories,id',
        ]);

        $order = MenuItem::where('menu_id', $menu->id)
            ->whereNull('parent_id')
            ->max('order') + 1;

        $data = [
            'menu_id' => $menu->id,
            'title' => $request->title,
            'url' => $request->url,
            'type' => $request->type,
            'target' => $request->target ?? '_self',
            'order' => $order,
            'page_id' => $request->page_id,
            'category_id' => $request->category_id,
        ];

        MenuItem::create($data);

        return response()->json([
            'success' => true,
            'message' => 'Menu item added successfully'
        ]);
    }

    public function updateItem(Request $request, MenuItem $item)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'url' => 'nullable|string|max:255',
            'target' => 'nullable|in:_self,_blank',
        ]);

        $item->update($request->only(['title', 'url', 'target']));

        return response()->json([
            'success' => true,
            'message' => 'Menu item updated successfully'
        ]);
    }

    public function deleteItem(MenuItem $item)
    {
        $item->children()->delete();
        $item->delete();

        return response()->json([
            'success' => true,
            'message' => 'Menu item deleted successfully'
        ]);
    }

    public function updateOrder(Request $request, Menu $menu)
    {
        $request->validate([
            'items' => 'required|array',
        ]);

        foreach ($request->items as $index => $itemData) {
            MenuItem::where('id', $itemData['id'])->update([
                'order' => $index,
                'parent_id' => $itemData['parent_id'] ?? null,
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Menu order updated successfully'
        ]);
    }
}
