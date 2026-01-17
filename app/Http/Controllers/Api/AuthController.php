<?php

namespace App\Http\Controllers\Api;

use App\Models\Role;
use App\Models\User;
use App\Models\Admin;
use App\Models\Courier;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|string|max:255',
            'email' => 'required|string|email|max:30|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'role' => 'required|in:customer,admin,courier',
            'phone_number' => 'required|string|max:12',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation Error',
                'errors' => $validator->errors()
            ], 422);
        }

        DB::beginTransaction();
        try {

            $role = Role::where('role_name', $request->role)->first();
            
            $user = User::create([
                'username' => $request->username,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'phone_number' => $request->phone_number,
                'role_role_id' => $role->role_id,
            ]);

            switch ($request->role) {
                case 'customer':
                    Customer::create([
                        'user_user_id' => $user->user_id,
                    ]);
                    break;
                case 'admin':
                    Admin::create([
                        'user_user_id' => $user->user_id,
                    ]);
                    break;
                case 'courier':
                    Courier::create([
                        'user_user_id' => $user->user_id,
                        'status' => 'Available',
                    ]);
                    break;
            }

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'User registered successfully',
                'data' => $user,
                'role_name' => $role->role_name,
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => 'Registration failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation Error',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid credentials'
            ], 401);
        }else if ($user->token) {
            return response()->json([
                'status' => false,
                'message' => 'Already Login'
            ], 401);
        }

        $user->tokens()->delete();
        
        $token = $user->createToken('auth_token')->plainTextToken;

        $user->update([
                    'token' => $token
                ]);

        return response()->json([
            'status' => true,
            'message' => 'Login successful',
            'data' => $user,
            'token' => $token
        ]);
    }
    
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        $request->user()->update([
            'token' => null
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Logged out successfully'
        ]);
    }
}