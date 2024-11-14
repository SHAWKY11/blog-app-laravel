<?php

namespace App\Http\Controllers\website;

use App\Models\Tag;
use App\Models\Post;
use App\Models\User;
use App\Models\Comment;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    public function index()
    {
        $posts=Post::paginate(10);
        $categories= Category::where('active',"1")->get();
        // dd($categories);
        $tags=Tag::where('active',"1")->get();
        return view('website.index',compact('posts','categories','tags'));
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

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    public function showCategory($id)
    {
        $tags=Tag::where('active',1);
        $category=Category::findOrFail($id);
        $categories=Category::where('active',1);
        $posts = Post::where('category_id', $category->id)->paginate(10);
        return view('website.index',compact('category','posts','tags','categories'));
    }

     public function showTag($id)
    {
        $tags=Tag::where('active',"1")->get();
        $tag=Tag::findOrFail($id);
        $categories=Category::where('active',"1")->get();
        $posts = $tag->posts()->paginate(10);
        return view('website.index',compact('posts','tags','categories','tag'));
    }

        public function showPost($id)
        {
            $post=Post::findOrFail($id);
            $post->increment('views');
            $categories=Category::where('active',"1")->get();
            $tags=Tag::where('active',"1")->get();
            $comments = $post->comments->where('active', 1);
            // dd($comments);
            return view('website.partials._partialPost',compact('post','categories','tags','comments'));
        }

         public function showAuthorPost($author)
        {
            $posts = Post::where('author', $author)->paginate(10); 
            $categories=Category::where('active',"1")->get();
            $tags=Tag::where('active',"1")->get();
            return view('website.index', compact('posts', 'author','categories','tags'));
        }



        public function searchPosts(Request $request)
{
    $request->validate([
        'query' => 'required|string|min:3',
    ]);

    $query = $request->input('query');
    $tags=Tag::where('active',"1")->get();
    $categories=Category::where('active',"1")->get();
    $posts = Post::where('title', 'LIKE', '%' . $query . '%')
        ->orWhere('post_content', 'LIKE', '%' . $query . '%')
        ->paginate(10);

    return view('website.index', compact('posts', 'query','categories','tags'));
}


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
