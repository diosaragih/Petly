<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class LogoutController extends Controller
{
    public function logout()
    {
        $apiToken = session('api_token');
        $response = Http::withToken($apiToken)
            ->post('http://petly.test:8080/api/logout');
                
        if ($response->successful()) {
            session()->flush('api_token');
            return redirect()->route('login')->with('message', 'You have been logged out successfully');
        }
    }
}