<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Http\Resources\ProductResource;
use App\Models\Role;
use Illuminate\Support\Facades\Validator;

class loginController extends Controller
{
    // Menampilkan halaman login
    public function showLoginForm()
    {
        return view('login');
    }

    // Proses login untuk web
    public function login(Request $request)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|max:255',
            'password' => 'required|string|min:8',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator);
        }

        try {
            // Make API request to the registration endpoint
            $response = Http::post('http://petly.test:8080/api/login', [
                'email' => $request->email,
                'password' => $request->password,
            ]);

            $data = $response->json();
            session(['api_token' => $data['token'], 'user_id' => $data['data']['user_id']]);

            // $apiToken = session('api_token'); -> untuk ambil token global
            
            // Check if the registration was successful
            if ($response->successful()) {
                // You might want to handle login automatically or redirect with a success message               
                if ($data['data']['role_role_id'] == 1) {
                    return redirect()->route('home')->with('success', 'Login successful!');
                }else if ($data['data']['role_role_id'] == 2) {
                    return redirect()->route('home-courier')->with('success', 'Login successful!');
                }else {
                    return redirect()->route('home-admin')->with('success', 'Login successful!');
                } 
            }

            return redirect()->back()
                ->withErrors($data['errors'] ?? ['Something went wrong with login.' . json_encode($data)]);
        } catch (\Exception $e) {
            return redirect()->back()
                ->withErrors(['api_error' => $e->getMessage()])
            ;
        }
    }
}
