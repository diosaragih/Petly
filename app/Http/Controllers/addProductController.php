<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;

class addProductController extends Controller
{
    public function showForm()
    {
        return view('admin.addproduct');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_name'     => 'required|string|max:255',
            'product_desc'     => 'required|string',
            'product_type'     => 'required|in:food,medicine,accesories',
            'pet_type'         => 'required|in:dog,cat,rabbit,turtle',
            'product_image'    => 'required|url',
            'product_stock'    => 'required|integer|min:0',
            'product_rating'   => 'nullable|numeric|min:0|max:10',
            'product_price'    => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $response = Http::post('http://petly.test:8080/api/products', [
                'product_name'     => $request->product_name,
                'product_desc'     => $request->product_desc,
                'product_type'     => $request->product_type,
                'pet_type'         => $request->pet_type,
                'product_image'    => $request->product_image,
                'product_stock'    => $request->product_stock,
                'product_rating'   => $request->product_rating,
                'product_price'    => $request->product_price,
            ]);

            if ($response->successful()) {
                return redirect()->route('product.index')
                    ->with('success', 'Produk berhasil ditambahkan!');
            }

            $errors = $response->json();
            return redirect()->back()
                ->withErrors($errors['errors'] ?? ['Terjadi kesalahan saat menambahkan produk.'])
                ->withInput();

        } catch (\Exception $e) {
            return redirect()->back()
                ->withErrors(['api_error' => 'Tidak dapat terhubung ke layanan produk.'])
                ->withInput();
        }
    }
}

