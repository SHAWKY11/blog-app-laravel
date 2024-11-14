<?php

namespace App\Http\Controllers;

use App\Models\Media;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MediaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $medias=Media::all();
        return view('dashboard.media.index',compact('medias'));
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
         try {
        
        $request->validate([
            'image_desc' => 'nullable|max:255',
            'image_file' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048', 
        ]);

        $data = $request->except('post_image'); 
        $data['image_file'] = $this->uploadImage($request); 

        if (!$data['image_file']) {
            return response()->json([
                'success' => false,
                'message' => 'Image upload failed.',
            ], 422);
        }

        $post = Media::create([
            'description' => $data['image_desc'],
            'image' => $data['image_file'],
        ]);

        
    //    return response()->json([
    //     'success' => true,
    //     'message' => 'Media successfully added!',
    // ]);
        return redirect()->route('posts.create')->with('success', 'Post created successfully.');
    } catch (\Illuminate\Validation\ValidationException $e) {
       
        return response()->json([
            'success' => false,
            'errors' => $e->validator->errors(),
        ], 422);
    } catch (\Exception $e) {
        
        return response()->json([
            'success' => false,
            'message' => 'An error occurred while creating the media.',
            'error' => $e->getMessage(),
        ], 500);
    }
    }

    /**
     * Display the specified resource.
     */
    public function show(Media $media)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Media $media)
    {
        //
    }

    protected function uploadImage(Request $request){
        if(!$request->hasFile('image_file')){
            return;
        }
        $file=$request->file('image_file');
        $path=$file->store('uploads',[
            'disk'=>'uploads',
        ]);
        return $path;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Media $media)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
         try {
        $media = Media::findOrFail($id);

        if ($media->image) {
            Storage::disk('uploads')->delete($media->image);
        }

        $media->delete();

        return response()->json([
            'success' => true,
            'message' => 'media deleted successfully!',
        ]);
    } catch (\Exception $e) {
        \Log::error('Error deleting media: ' . $e->getMessage(), ['exception' => $e]);

        return response()->json([
            'success' => false,
            'message' => 'An error occurred while deleting the media.',
            'error' => $e->getMessage(),
        ], 500);
    }
}
    }

