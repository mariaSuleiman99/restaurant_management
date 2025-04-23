<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Http\Requests\RegisterRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
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
            $expiryTime = now()->addHours(24); // Token expires in 24 hours
            $token = $user->createToken('authToken', ["*"], $expiryTime)->plainTextToken;
//            $token = $user->createToken('authToken')->plainTextToken;

            $user['token'] = $token;
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
        $user['token'] = $token;

        return ResponseHelper::success("User registered successfully.", $user)->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ]);
    }

    /**
     * Change the authenticated user's password.
     */
    public function changePassword(Request $request): JsonResponse
    {
        // Validate the request
        $validator = Validator::make($request->all(), [
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed.',
                'errors' => $validator->errors(),
            ], 422);
        }

        // Get the authenticated user
        $user = $request->user();

        // Check if the current password is correct
        if (!Hash::check($request->input('current_password'), $user->password)) {
            throw ValidationException::withMessages([
                'current_password' => ['The current password is incorrect.'],
            ]);
        }

        // Update the user's password
        $user->password = Hash::make($request->input('new_password'));
        $user->save();

        // Return success response
        return ResponseHelper::success('Password changed successfully.');
    }
}
