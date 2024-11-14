<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class AdminController extends Controller
{
    
 public function index()
{
    $users = User::with('userRoles')
                 ->whereIn('id', DB::table('role_user')->pluck('user_id'))
                 ->get();

    $roles = Role::all();
    // $currentAdminRoleId = auth()->user()->roles()->first()->id ?? null;
    // dd($currentAdminRoleId);
    return view('dashboard.admins.index', compact('users', 'roles'));
}


    public function create()
    {
        //
    }

  
   public function store(Request $request)
{
    // dd($request->all());
    try {
        $request->validate([
            'admin' => 'required|exists:roles,id', 
            'admin_name' => 'required|unique:users,name|string|max:255', 
            'password' => 'required|string|min:8', 
        ]);

        $user = User::create([
            'name' => $request->admin_name, 
            'password' => bcrypt($request->password), 
        ]);

        $user->addRole($request->admin);

        return response()->json([
            'success' => true,
            'message' => 'Admin successfully added!',
        ]);
    } catch (ValidationException $e) {
        return response()->json([
            'success' => false,
            'errors' => $e->validator->errors(),
        ], 422);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'An error occurred while creating the admin.',
        ], 500);
    }
}


    public function show(string $id)
    {
        //
    }

 
    public function edit(string $id)
    {
        //
    }

     public function toggleActive($id, Request $request)
{
    $user = User::findOrFail($id);
    $user->active = $request->active;
    $user->save();

    return response()->json([
        'success' => true,
        'message' => 'user status updated successfully!',
        'new_status' => $user->active,
    ]);
}

   public function update(Request $request, $id)
{
    try {
        $request->validate([
            'admin' => 'required|exists:roles,id',
            'admin_name' => 'required|string|max:255|unique:users,name,' . $id, 
            'password' => 'nullable|string|min:8', 
        ]);

        $user = User::findOrFail($id);

        $user->name = $request->admin_name;

        if ($request->filled('password')) {
            $user->password = bcrypt($request->password);
        }

        $user->save();

        $user->syncRoles([$request->admin]);

        return response()->json([
            'success' => true,
            'message' => 'Admin successfully updated!',
        ]);
    } catch (ValidationException $e) {
        return response()->json([
            'success' => false,
            'errors' => $e->validator->errors(),
        ], 422);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'An error occurred while updating the admin.',
        ], 500);
    }
}

    
    public function destroy($id)
{
    $admin = User::findOrFail($id);
    $admin->roles()->detach();
    $admin->delete();
    return response()->json([
        'success' => true,
        'message' => 'Admin deleted successfully!',
    ]);
}
}
