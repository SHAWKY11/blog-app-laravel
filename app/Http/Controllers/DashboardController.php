<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function show_details()
    {
        $totalPosts = Post::count();
        $totalComments = Post::withCount('comments')->get()->sum('comments_count');
        $posts = Post::orderBy('id', 'DESC')->limit(10)->get();
        return view('welcome',compact('totalPosts','totalComments','posts'));
    }
}
