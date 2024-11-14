<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use Illuminate\Http\Request;

class TagController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tags = Tag::all();
        return view('dashboard.tag.index',compact('tags'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    public function storeOrFetch(Request $request)
    {
        $request->validate(['name' => 'string|nullable']);

        if ($request->has('name')) {
            $tag = Tag::firstOrCreate(['name' => $request->name]);
            return response()->json($tag);
        }

        $searchTerm = $request->input('searchTerm');
        $tags = Tag::where('name', 'like', '%' . $searchTerm . '%')->get();
        return response()->json($tags);
    }

    public function search(Request $request)
{
    $query = $request->get('query');
    $tags = Tag::where('name', 'like', "%{$query}%")->get(['id', 'name']);
    
    return response()->json($tags);
}


    /**
     * Display the specified resource.
     */
    public function show(Tag $tag)
    {
        //
    }

 public function toggleActive($id, Request $request)
{
    $tag = Tag::findOrFail($id);
    $tag->active = $request->active;
    $tag->save();

    return response()->json([
        'success' => true,
        'message' => 'Tag status updated successfully!',
        'new_status' => $tag->active,
    ]);
}


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Tag $tag)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Tag $tag)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tag $tag)
    {
        //
    }
}
