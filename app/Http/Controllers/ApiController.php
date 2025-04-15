<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ApiController extends Controller
{



    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'dob' => 'required|date',
            'address' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'phone' => 'required|digits:10|unique:users',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'password' => [
                'required',
                'string',
                'min:8',
                'regex:/[A-Z]/', // At least one uppercase letter
                'regex:/[a-z]/', // At least one lowercase letter
                'regex:/[!@#$%^&*(),.?":{}|<>]/', // At least one special character
            ],
        ]);
    
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
    
        $photoPath = null;
        if ($request->hasFile('photo')) {
            // Check if the file is valid
            if ($request->file('photo')->isValid()) {
                $photoPath = $request->file('photo')->store('photos', 'public');
            } else {
                return response()->json(['error' => 'Uploaded file is not valid'], 422);
            }
        }
    
        $user = new User();
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->dob = $request->dob;
        $user->address = $request->address;
        $user->email = $request->email;
        $user->phone = $request->phone;
        if ($request->hasFile('photo')) {
            $user->photo = $request->file('photo')->store('photos', 'public');
        }
        $user->password = Hash::make($request->password);
        $user->save();
    
        return response()->json(['message' => 'User registered successfully'], 201);
    }
    

    
    
  // Get all users
public function index()
{
    return response()->json(User::all(), 200);
}

// Get a single user by ID
public function show($id)
{
    $user = User::find($id);
    if (!$user) {
        return response()->json(['message' => 'User not found'], 404);
    }
    return response()->json($user, 200);
}

// Update a user
public function update(Request $request, $id)
{
    $user = User::find($id);
    if (!$user) {
        return response()->json(['message' => 'User not found'], 404);
    }

    $validator = Validator::make($request->all(), [
        'first_name' => 'sometimes|required|string|max:255',
        'last_name' => 'sometimes|required|string|max:255',
        'dob' => 'sometimes|required|date',
        'address' => 'sometimes|required|string|max:255',
        'email' => 'sometimes|required|email|unique:users,email,' . $user->id,
        'phone' => 'sometimes|required|digits:10|unique:users,phone,' . $user->id,
        'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        'password' => 'sometimes|required|string|min:8|regex:/[A-Z]/|regex:/[a-z]/|regex:/[@$!%*?&]/',
    ]);

    if ($validator->fails()) {
        return response()->json($validator->errors(), 422);
    }

    $user->first_name = $request->first_name ?? $user->first_name;
    $user->last_name = $request->last_name ?? $user->last_name;
    $user->dob = $request->dob ?? $user->dob;
    $user->address = $request->address ?? $user->address;
    $user->email = $request->email ?? $user->email;
    $user->phone = $request->phone ?? $user->phone;

    if ($request->hasFile('photo')) {
        // Delete old photo if exists
        if ($user->photo) {
            Storage::disk('public')->delete($user->photo);
        }
        $path = $request->file('photo')->store('photos', 'public');
        $user->photo = $path;
    }

    if ($request->password) {
        $user->password = Hash::make($request->password);
    }

    $user->save();

    return response()->json(['message' => 'User updated successfully'], 200);
}

// Delete a user
public function destroy($id)
{
    $user = User::find($id);
    if (!$user) {
        return response()->json(['message' => 'User not found'], 404);
    }

    // Delete photo if exists
    if ($user->photo) {
        Storage::disk('public')->delete($user->photo);
    }

    $user->delete();
    return response()->json(['message' => 'User deleted successfully'], 200);
}

}
