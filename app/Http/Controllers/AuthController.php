<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Http\Requests\RegisterRequest;
use Spatie\Permission\Models\Role;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request): JsonResponse
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            // Get the user's role
            $role = $user->getRoleNames()->first(); // Returns the first role (single role)
            $user["role"] = $role;
//            $user["user_role"] = $user->role->name; // Assuming the 'roles' table has a 'name' column

            $token = $user->createToken('authToken')->plainTextToken;

            $user['token']=$token;
            return ResponseHelper::success("Login successfully", $user)->withHeaders([
                'Authorization' => 'Bearer ' . $token,
            ]);
        }

        return ResponseHelper::error("Invalid credentials", 401);
    }

    public function register(RegisterRequest $request): JsonResponse
    {
        $validatedData = $request->validated();

        // Assign default role (e.g., "User")
        $defaultRole = Role::where('name', 'User')->first();

        if (!$defaultRole) {
            return ResponseHelper::error("Default role 'User' not found.");
        }

        // Create the user
        $user = User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => bcrypt($validatedData['password']),
        ]);

        // Assign the default role
        $user->assignRole($defaultRole);

        // Add role to the response
        $role = $user->getRoleNames()->first(); // Returns the first role (single role)
        $user["role"] = $role;

        $token = $user->createToken('authToken')->plainTextToken;
        $user['token']=$token;

        return ResponseHelper::success("User registered successfully.", $user)->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ]);
    }
}
