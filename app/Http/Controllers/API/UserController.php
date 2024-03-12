<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Resources\UserCollection;
use App\Http\Resources\UserResource;
use App\Http\Requests\UpdateUserRequest;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();
        if (!empty($users)) {
            return response()->json($users);
        }
        else {
            return response()->json([
                'status' => false,
                'message' => "Users not found",
            ],404);
        }
    }

    public function show($id)
    {
        $user = User::find($id);
        if (!empty($user)) {
            return response()->json($user);
        }
        else {
            return response()->json([
                'status' => false,
                'message' => "User not found",
            ],404);
        }
    }

    public function update(UpdateUserRequest $request, $id)
    {
        $user = User::find($id);

        $user->update([
            'name' => $request->name,
            'last_name' => $request->last_name,
            'phone' => $request->phone,
            'email'=> $request->email,
            'password'=> Hash::make($request->password)
        ]);
        if (!empty($user)) {
            return response()->json([
                'data' => $user,
                'status' => true,
                'message' => 'User updated successfully'
            ], 200);
        }
        else {
            return response()->json([
                'status' => false,
                'message' => "User not updated",
            ],404);
        }
    }

    public function destroy($id)
    {
        $user = User::find($id);
        $user->delete();
        return response()->json([
            'status' => true,
            'message' => "User deleted successfully",
        ],200);
        
    }

}
