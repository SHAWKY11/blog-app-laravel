<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use App\Models\Post;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $posts = Post::all();
        return view('dashboard.post.index',compact('posts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories=Category::all();
        $tags=Tag::all();
        return view('dashboard.post.create',compact('tags','categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
public function store(Request $request)
{
    // dd($request->all());
    try {
        $request->validate([
            'post_name' => 'required|string|max:255',
            'post_image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048', 
            'category' => 'required|exists:categories,id',
            // 'tags' => 'nullable|array',  
            // 'tags.*' => 'integer|exists:tags,id', 
            'content' => 'required|string|min:3', 
        ]);

        $data = $request->except('post_image'); 
        $data['post_image'] = $this->uploadImage($request); 

        if (!$data['post_image']) {
            return response()->json([
                'success' => false,
                'message' => 'Image upload failed.',
            ], 422);
        }

        $post = Post::create([
            'title' => $data['post_name'],
            'image' => $data['post_image'],
            'category_id' => $data['category'],
            'post_content' => $data['content'],
            'author' => auth()->user()->name,
        ]);

        if ($request->filled('tags')) {
            $tags=Tag::pluck('id');
            // dd($tags);
            $post->tags()->sync($tags);
        }

        return redirect()->route('posts.index')->with('success', 'Post created successfully.');
    } catch (\Illuminate\Validation\ValidationException $e) {
        return response()->json([
            'success' => false,
            'errors' => $e->validator->errors(),
        ], 422);
    } catch (\Exception $e) {
        \Log::error('Error creating post: ' . $e->getMessage(), ['exception' => $e]);
        
        return response()->json([
            'success' => false,
            'message' => 'An error occurred while creating the Post.',
            'error' => $e->getMessage(),
        ], 500);
    }
}



protected function uploadImage(Request $request){
        if(!$request->hasFile('post_image')){
            return;
        }
        $file=$request->file('post_image');
        $path=$file->store('uploads',[
            'disk'=>'uploads',
        ]);
        return $path;
    }

    

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        //
    }

      public function toggleActive($id, Request $request)
{
    $post = Post::findOrFail($id);
    $post->active = $request->active;
    $post->save();

    return response()->json([
        'success' => true,
        'message' => 'Post status updated successfully!',
        'new_status' => $post->active,
    ]);
}

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $post=Post::findOrFail($id);
        $tags=Tag::all();
        $categories=Category::all();
        return view('dashboard.post.edit',compact('tags','categories','post'));
    }

    /**
     * Update the specified resource in storage.
     */
   public function update(Request $request, $id)
{
    try {
        $request->validate([
            'post_name' => 'required|string|max:255',
            'post_image' => 'sometimes|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'category' => 'required|exists:categories,id',
            // 'tags' => 'required|array|min:1',
            // 'tags.*' => 'integer|exists:tags,id',
            'content' => 'required|string|min:3',
        ]);

        $post = Post::findOrFail($id);
        $old_image = $post->image;
        $data = $request->except('post_image');

        // Initialize the new_image variable
        $new_image = null;

        if ($request->hasFile('post_image')) {
            $new_image = $this->uploadImage($request);

            if (!$new_image) {
                return response()->json([
                    'success' => false,
                    'message' => 'Image upload failed.',
                ], 422);
            }

            $data['post_image'] = $new_image;
        } else {
            $data['post_image'] = $old_image;
        }

        $post->update([
            'title' => $data['post_name'],
            'category_id' => $data['category'],
            'post_content' => $data['content'],
            'author' => auth()->user()->name,
            'image' => $data['post_image'],
        ]);

        if ($new_image && $old_image) {
            Storage::disk('uploads')->delete($old_image);
        }

        $post->tags()->sync($request->tags);

        return redirect()->route('posts.index')->with('success', 'Post updated successfully.');
    } catch (\Illuminate\Validation\ValidationException $e) {
        return response()->json([
            'success' => false,
            'errors' => $e->validator->errors(),
        ], 422);
    } catch (\Exception $e) {
        \Log::error('Error updating post: ' . $e->getMessage(), ['exception' => $e]);

        return response()->json([
            'success' => false,
            'message' => 'An error occurred while updating the Post.',
            'error' => $e->getMessage(),
        ], 500);
    }
}



    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
{
    try {
        $post = Post::findOrFail($id);

        if ($post->image) {
            Storage::disk('uploads')->delete($post->image);
        }

        $post->delete();

        return response()->json([
            'success' => true,
            'message' => 'Post deleted successfully!',
        ]);
    } catch (\Exception $e) {
        \Log::error('Error deleting post: ' . $e->getMessage(), ['exception' => $e]);

        return response()->json([
            'success' => false,
            'message' => 'An error occurred while deleting the post.',
            'error' => $e->getMessage(),
        ], 500);
    }
}

public function search(Request $request)
    {
        $query = $request->input('query');

        $posts = Post::where('title', 'like', "%{$query}%")
                     ->orWhere('post_content', 'like', "%{$query}%")
                     ->paginate(10); 

        return view('dashboard.post.index', compact('posts'));
    }

}
