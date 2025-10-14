<?php

namespace App\Modules\Tag\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TagController extends Controller
{
    // ...controller methods

    public function index(Request $request)
    {
        $tags = \App\Modules\Tag\Models\Tag::all();
        if ($request->wantsJson()) {
            return response()->json($tags);
        }
        return view('admin.tag', compact('tags'));
    }

    public function show($id)
    {
        return response()->json(\App\Modules\Tag\Models\Tag::findOrFail($id));
    }

    public function store(\Illuminate\Http\Request $request)
    {
        $tag = \App\Modules\Tag\Models\Tag::create($request->all());
        // Clear tag cache after create
        cache()->forget('public_tags_list');
        return response()->json($tag, 201);
    }

    public function update(\Illuminate\Http\Request $request, $id)
    {
        $tag = \App\Modules\Tag\Models\Tag::findOrFail($id);
        $tag->update($request->all());
        // Clear tag cache after update
        cache()->forget('public_tags_list');
        return response()->json($tag);
    }

    public function destroy($id)
    {
        $tag = \App\Modules\Tag\Models\Tag::findOrFail($id);
        $tag->delete();
        // Clear tag cache after delete
        cache()->forget('public_tags_list');
        return response()->json(null, 204);
    }
}
