<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::all();
        return view('dashboard.category.index',compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
         return view('dashboard.category.create');   
    }

    /**
     * Store a newly created resource in storage.
     */
  public function store(Request $request)
{
    
    try {
        
        $request->validate([
            'category_name' => 'required|unique:categories,name|string|max:255',
        ]);

        
        $category = Category::create([
            'name' => $request->category_name,
        ]);

        
        return response()->json([
            'success' => true,
            'message' => 'Category successfully added!',
        ]);
    } catch (\Illuminate\Validation\ValidationException $e) {
       
        return response()->json([
            'success' => false,
            'errors' => $e->validator->errors(),
        ], 422);
    } catch (\Exception $e) {
        
        return response()->json([
            'success' => false,
            'message' => 'An error occurred while creating the category.',
        ], 500);
    }
}



    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        $category = Category::findOrFail($id);
        return response()->json($category);
    }

    
    public function toggleActive($id, Request $request)
{
    $category = Category::findOrFail($id);
    $category->active = $request->active;
    $category->save();

    return response()->json([
        'success' => true,
        'message' => 'Category status updated successfully!',
        'new_status' => $category->active,
    ]);
}


    /**
     * Update the specified resource in storage.
     */
   public function update(Request $request, $id)
{
    try {
        $request->validate([
            'category_name' => 'required|string|max:255|unique:categories,name,' . $id,
        ]);

        $category = Category::findOrFail($id);
        $category->name = $request->category_name;
        $category->save();

        return response()->json([
            'success' => true,
            'message' => 'Category Updated successfully!',
        ]);
    } catch (\Illuminate\Validation\ValidationException $e) {
        return response()->json([
            'success' => false,
            'errors' => $e->validator->errors(),
        ], 422);
    } catch (\Exception $e) {
        \Log::error('Category update failed: ' . $e->getMessage());

        return response()->json([
            'success' => false,
            'message' => 'An error occurred while updating the category.',
        ], 500);
    }
}

    

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
{
    $category = Category::findOrFail($id);
    $category->delete();

    return response()->json([
        'success' => true,
        'message' => 'Category deleted successfully!',
    ]);
}

}
