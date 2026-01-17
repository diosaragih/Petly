<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

use function Laravel\Prompts\confirm;

class CheckoutController extends Controller
{
    public function storeCheckout(Request $request)
    {
        $apiToken = session('api_token');
        $customerID = session('user_id');
        $transactionDate = Carbon::now()->toDateTimeString();
        $selectedItem = $request->input('selected_item');

        try {
            $response = Http::withToken($apiToken)
                ->post('http://petly.test:8080/api/customer/transaction', [
                    'user_id' => $customerID,
                    'status_name' => "pending",
                    'cart_id' => $selectedItem,
                    'transaction_date' => $transactionDate,
                ]);

            $answer = $response->json('data');

            // Store checkout data in session for later use
            Session::put('checkout_data', $answer);

            // Get shipping fee from session or default
            $payMethod = session('payment_method', 'credit_card');
            $shippingFee = session('shipping_fee', 15000);
            $taxAmount = 25000;
            $total = $taxAmount + $answer['cart']['total_price'] + $shippingFee;

            return view('checkout', [
                'transactionId' => $answer['transaction']['transaction_id'],
                'subtotal' => $answer['cart']['total_price'],
                'product' => $answer['product'],
                'user' => $answer['user'],
                'shippingFee' => $shippingFee,
                'paymentMethod' => $payMethod,
                'userDetail' => $answer['user_detail'],
                'cart' => $answer['cart'],
                'taxAmount' => $taxAmount,
                'total' => $total,
            ]);
        } catch (\Throwable $th) {
            return 'sada '. $th->getMessage();
        }
    }
    

    public function updatePaymentMethod(Request $request)
    {
        $paymentMethod = (string) $request->input('payment_method', 'credit_card');

        // Validate the shipping fee
        if (!in_array($paymentMethod, ['credit_card', 'cod'])) {
            $paymentMethod = 'credit_card'; // Default if invalid
        }

        // Store in session
        Session::put('payment_method', $paymentMethod);

        // Get checkout data from session
        $checkoutData = Session::get('checkout_data');
        $shippingFee = Session::get('shipping_fee');

        // Recalculate total with new shipping fee
        $taxAmount = 25000;
        $total = $taxAmount + $checkoutData['cart']['total_price'] + $shippingFee;

        return view('checkout', [
            'transactionId' => $checkoutData['transaction']['transaction_id'],
            'subtotal' => $checkoutData['cart']['total_price'],
            'product' => $checkoutData['product'],
            'user' => $checkoutData['user'],
            'shippingFee' => $shippingFee,
            'paymentMethod' => $paymentMethod,
            'userDetail' => $checkoutData['user_detail'],
            'cart' => $checkoutData['cart'],
            'taxAmount' => $taxAmount,
            'total' => $total,
        ]);
    }

    public function updateShipping(Request $request)
    {
        $shippingFee = (int) $request->input('shipping_fee', 15000);

        // Validate the shipping fee
        if (!in_array($shippingFee, [15000, 50000])) {
            $shippingFee = 15000; // Default if invalid
        }

        // Store in session
        Session::put('shipping_fee', $shippingFee);

        // Get checkout data from session
        $checkoutData = Session::get('checkout_data');
        $payMethod = Session::get('payment_method');

        // Recalculate total with new shipping fee
        $taxAmount = 25000;
        $total = $taxAmount + $checkoutData['cart']['total_price'] + $shippingFee;

        return view('checkout', [
            'transactionId' => $checkoutData['transaction']['transaction_id'],
            'subtotal' => $checkoutData['cart']['total_price'],
            'product' => $checkoutData['product'],
            'user' => $checkoutData['user'],
            'shippingFee' => $shippingFee,
            'paymentMethod' => $payMethod,
            'userDetail' => $checkoutData['user_detail'],
            'cart' => $checkoutData['cart'],
            'taxAmount' => $taxAmount,
            'total' => $total,
        ]);
    }

    public function showCheckout()
    {
        // Get checkout data from session
        $checkoutData = Session::get('checkout_data');

        // Get shipping fee from session or default
        $shippingFee = session('shipping_fee', 15000);
        $payMethod = session('payment_method', 'Credit Card');
        $taxAmount = 25000;

        $total = $taxAmount + $checkoutData['cart']['total_price'] + $shippingFee;

        return view('checkout', [
            'transactionId' => $checkoutData['transaction']['transaction_id'],
            'subtotal' => $checkoutData['cart']['total_price'],
            'product' => $checkoutData['product'],
            'user' => $checkoutData['user'],
            'shippingFee' => $shippingFee,
            'paymentMethod' => $payMethod,
            'userDetail' => $checkoutData['user_detail'],
            'cart' => $checkoutData['cart'],
            'taxAmount' => $taxAmount,
            'total' => $total,
        ]);
    }

    public function storePayment(Request $request)
    {
        $apiToken = session('api_token');
        $checkoutData = Session::get('checkout_data');

        // Check if address exists in form data
        if (!$checkoutData['user_detail']['address']) {
            return redirect()->back()->with('alert', 'Input your address first!!!');
        }
        $paymentMethod = $request->payment_method == 'cod' ? 'COD': 'Credit Card';

        // // // Process payment
        $response = Http::withToken($apiToken)->post('http://petly.test:8080/api/customer/payment', [
            'payment_method' => $paymentMethod,
            'delivery_class' => $request->delivery_class,
            'transaction_id' => $request->transaction_id,
        ]);
        $value = $response->body();
        if (!$value) {
            return redirect()->back();
        }else{
            return redirect()->route('history')->with('status', 'Payment Success!!!');
        }
        
    }

    public function finish(Request $request)
    {
        $apiToken = session('api_token');
        $courierID = session('user_id');
        Http::withToken($apiToken)
            ->post('http://petly.test:8080/api/customer/transaction-update', [
                'transaction_id' => $request->transaction_id,
                'status_name' => 'canceled',
            ]);

            return redirect()->route('history')->with('status');
    }
    // public function getHistory($id)
    // {       
    //     $apiToken = session('api_token');
    //     $response = Http::withToken($apiToken)
    //                     ->get('http://petly.test:8080/api/customer/transaction/' . $id);
        
    //     // Decode the JSON response
    //     $responseData = json_decode($response->body(), true);
        
    //     // Extract the data portion of the response
    //     $data = $responseData['data'];
    //     // dd($data['cart']['total_price']);
    //     // Calculate the total
    //     $taxAmount = 25000;
    //     $shippingFee = 15000;
    //     $total = $taxAmount + $data['cart']['total_price'] + $shippingFee;
    
    //     return redirect()->route('checkout', [    
    //         'transactionId' => $data['transaction_id'],
    //         'subtotal' => $data['cart']['total_price'],
    //         'product' => $data['cart']['products'],
    //         'user' => $data['user'],
    //         'shippingFee' => 'standard',
    //         'shippingFeeAmount' => $shippingFee,
    //         'paymentMethod' => 'credit_card',
    //         'userDetail' => $data['user'],
    //         'cart' => $data['cart'],
    //         'taxAmount' => $taxAmount,
    //         'total' => $total,
    //     ]);
    // }
}
