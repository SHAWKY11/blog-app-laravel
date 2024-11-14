<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $comments = Comment::all();
        return view('dashboard.comment.index',compact('comments'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

      public function toggleActive($id, Request $request)
{
    $comment = Comment::findOrFail($id);
    $comment->active = $request->active;
    $comment->save();

    return response()->json([
        'success' => true,
        'message' => 'Comment Aprroved successfully!',
        'new_status' => $comment->active,
    ]);
}

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
        'comment' => 'required|string|max:500',
        'name' => 'required|string|max:100',
        'email' => 'required|email',
    ]);

    $comment = Comment::create($request->all());
    $post=Post::where('id',$comment->post_id);
    $post->increment('comments_count');

    return response()->json(['success' => true]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Comment $comment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Comment $comment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Comment $comment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $comment = Comment::findOrFail($id);
        $comment->delete();

    return response()->json([
        'success' => true,
        'message' => 'comment deleted successfully!',
    ]);
    }
}
