<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
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


    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (auth()->attempt($credentials)) {
                                // Attempt to log the user in
                if (auth()->attempt($credentials)) {
                    // Login successful, redirect to the landing page
                    return redirect()->route('home'); // Replace 'landing.page' with your actual route name
                }
            //return response()->json(['message' => 'Login successful'], 200);

        }
        $user = User::where('email', $request->email)->first();

        //$registeredemail = $request->only('email');
        if($user){
            return response()->json(['message' => 'Invalid credentials'], 401);

        }
        // If the user is not registered, redirect to the registration page
        return redirect()->route('register'); // Replace 'registration.page' with your actual route name
        


    
}
}

