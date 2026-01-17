<x-main>
    <section class="py-10 relative">
        <div class="lg:flex lg:items-start xl:gap-16">
            <div class="min-w-0 flex-1 space-y-8">
                <div class="space-y-4">
                    <h2 class="font-semibold text-2xl text-black mb-5">Delivery Details</h2>

                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <div>
                            <label for="your_name"
                                class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Your Name
                            </label>
                            <input type="text" name="your_name" id="your_name" value="{{ $user['username'] }}" disabled
                                class="block w-full p-2.5 text-sm rounded-lg border border-gray-300 bg-gray-50 text-gray-900 outline-none transition focus:border-blue-500 focus:ring focus:ring-blue-500" />
                        </div>

                        <div>
                            <label for="your_email"
                                class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Your
                                email</label>
                            <input type="email" name="your_email" id="your_email" value="{{ $user['email'] }}"
                                disabled
                                class="block w-full p-2.5 text-sm rounded-lg border border-gray-300 bg-gray-50 text-gray-900 outline-none transition focus:border-blue-500 focus:ring focus:ring-blue-500" />
                        </div>

                        <div>
                            <label for="your_num"
                                class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Phone
                                Number</label>
                            <input type="tel" name="your_num" id="your_num" value="{{ $user['phone_number'] }}"
                                disabled
                                class="block w-full p-2.5 text-sm rounded-lg border border-gray-300 bg-gray-50 text-gray-900 outline-none transition focus:border-blue-500 focus:ring focus:ring-blue-500" />
                        </div>
                        @php
                            $addressParts = explode(',', $userDetail['address']);
                            $city = trim(end($addressParts));
                            $fullAddress = trim($addressParts[0]);
                        @endphp
                        <div>
                            <label for="your_city"
                                class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">City</label>
                            <input type="text" name="your_city" id="your_city" value="{{ trim(end($addressParts)) }}"
                                disabled
                                class="block w-full p-2.5 text-sm rounded-lg border border-gray-300 bg-gray-50 text-gray-900 outline-none transition focus:border-blue-500 focus:ring focus:ring-blue-500" />
                        </div>
                    </div>

                    <div>
                        <label for="address"
                            class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">Address</label>
                        <input type="text" name="address_input" id="address_input"
                            value="{{ trim($addressParts[0]) }}" disabled
                            class="block w-full p-2.5 text-sm rounded-lg border border-gray-300 bg-gray-50 text-gray-900 outline-none transition focus:border-blue-500 focus:ring focus:ring-blue-500" />
                    </div>
                </div>

                {{-- Shipping in the checkout view --}}
                <div class="space-y-4">
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white">Delivery Methods</h3>

                    <form method="POST" action="{{ route('checkout.update-shipping') }}">
                        <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                            @csrf
                            <div class="rounded-lg border border-gray-200 p-7 ps-4 {{ $shippingFee == 15000 }}">
                                <div class="flex items-start">
                                    <div class="flex h-5 items-center">
                                        <input id="regular" aria-describedby="regular-text" type="radio"
                                            name="shipping_fee" value="15000" onchange="this.form.submit()"
                                            {{ $shippingFee == 15000 ? 'checked' : '' }}
                                            class="h-4 w-4 border-gray-300 bg-white text-primary-600 focus:ring-2 focus:ring-primary-600 dark:border-gray-600 dark:bg-gray-700 dark:ring-offset-gray-800 dark:focus:ring-primary-600" />
                                    </div>

                                    <div class="ms-4 text-sm">
                                        <label for="regular"
                                            class="font-medium leading-none text-gray-900 dark:text-white">
                                            15.000 - Regular Delivery
                                        </label>
                                        <p id="regular-text"
                                            class="mt-1 text-xs font-normal text-gray-500 dark:text-gray-400">
                                            Get it by 2 up to 4 days
                                        </p>
                                    </div>
                                </div>
                            </div>

                            {{-- <div class="rounded-lg border border-gray-200 p-7 ps-4 {{ $shippingFee == 50000 }}">
                                <div class="flex items-start">
                                    <div class="flex h-5 items-center">
                                        <input id="express" aria-describedby="express-text" type="radio"
                                            name="shipping_fee" value="50000" onchange="this.form.submit()"
                                            {{ $shippingFee == 50000 ? 'checked' : '' }}
                                            class="h-4 w-4 border-gray-300 bg-white text-primary-600 focus:ring-2 focus:ring-primary-600 dark:border-gray-600 dark:bg-gray-700 dark:ring-offset-gray-800 dark:focus:ring-primary-600" />
                                    </div>

                                    <div class="ms-4 text-sm">
                                        <label for="express"
                                            class="font-medium leading-none text-gray-900 dark:text-white">
                                            50.000 - Express Delivery
                                        </label>
                                        <p id="express-text"
                                            class="mt-1 text-xs font-normal text-gray-500 dark:text-gray-400">
                                            Get it by 1 up to 2 days
                                        </p>
                                    </div>
                                </div>
                            </div> --}}
                        </div>
                    </form>
                </div>
                {{-- Payment in the checkout view --}}
                <div class="space-y-4">
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white">Payment Methods</h3>
                    <form method="POST" action="{{ route('checkout.update-payment') }}">
                        @csrf
                        <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                            <div class="rounded-lg border border-gray-200 p-7 ps-4">
                                <div class="flex items-start">
                                    <div class="flex h-5 items-center">
                                        <input id="credit-card" aria-describedby="credit-card-text" type="radio"
                                            name="payment_method" value="credit_card" onchange="this.form.submit()"
                                            {{ $paymentMethod == 'credit_card' ? 'checked' : '' }}
                                            class="h-4 w-4 border-gray-300 bg-white text-primary-600 focus:ring-2 focus:ring-primary-600 dark:border-gray-600 dark:bg-gray-700 dark:ring-offset-gray-800 dark:focus:ring-primary-600" />
                                    </div>

                                    <div class="ms-4 text-sm">
                                        <label for="credit-card"
                                            class="font-medium leading-none text-gray-900 dark:text-white"> Credit Card
                                        </label>
                                        <p id="credit-card-text"
                                            class="mt-1 text-xs font-normal text-gray-500 dark:text-gray-400">Pay with
                                            your credit card</p>
                                    </div>
                                </div>

                            </div>

                            {{-- <div
                                class="rounded-lg border border-gray-200 p-7 ps-4">
                                <div class="flex items-start">
                                    <div class="flex h-5 items-center">
                                        <input id="pay-on-delivery" aria-describedby="pay-on-delivery-text"
                                            type="radio" name="payment_method" value="cod"
                                            onchange="this.form.submit()"
                                            {{ $paymentMethod == 'cod' ? 'checked' : '' }}
                                            class="h-4 w-4 border-gray-300 bg-white text-primary-600 focus:ring-2 focus:ring-primary-600 dark:border-gray-600 dark:bg-gray-700 dark:ring-offset-gray-800 dark:focus:ring-primary-600" />
                                    </div>

                                    <div class="ms-4 text-sm">
                                        <label for="pay-on-delivery"
                                            class="font-medium leading-none text-gray-900 dark:text-white"> Payment on
                                            delivery </label>
                                        <p id="pay-on-delivery-text"
                                            class="mt-1 text-xs font-normal text-gray-500 dark:text-gray-400">Pay with
                                            cash on delivery</p>
                                    </div>
                                </div>
                            </div> --}}

                        </div>
                    </form>
                </div>

                {{-- <div class="space-y-4">
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white">Delivery Methods</h3>

                    <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                        <div
                            class="rounded-lg border border-gray-200 p-7 ps-4">
                            <div class="flex items-start">
                                <div class="flex h-5 items-center">
                                    <input id="dhl" aria-describedby="dhl-text" type="radio"
                                        name="delivery-method" value=""
                                        class="h-4 w-4 border-gray-300 bg-white text-primary-600 focus:ring-2 focus:ring-primary-600 dark:border-gray-600 dark:bg-gray-700 dark:ring-offset-gray-800 dark:focus:ring-primary-600"
                                        checked />
                                </div>

                                <div class="ms-4 text-sm">
                                    <label for="dhl"
                                        class="font-medium leading-none text-gray-900 dark:text-white">
                                        15.000 -
                                        Fast
                                        Delivery </label>
                                    <p id="dhl-text"
                                        class="mt-1 text-xs font-normal text-gray-500 dark:text-gray-400">
                                        Get it by
                                        2 up to 4 days</p>
                                </div>
                            </div>
                        </div>

                        <div
                            class="rounded-lg border border-gray-200 bg-gray-50 p-7 ps-4 dark:border-gray-700 dark:bg-gray-800">
                            <div class="flex items-start">
                                <div class="flex h-5 items-center">
                                    <input id="express" aria-describedby="express-text" type="radio"
                                        name="delivery-method" value=""
                                        class="h-4 w-4 border-gray-300 bg-white text-primary-600 focus:ring-2 focus:ring-primary-600 dark:border-gray-600 dark:bg-gray-700 dark:ring-offset-gray-800 dark:focus:ring-primary-600" />
                                </div>

                                <div class="ms-4 text-sm">
                                    <label for="express"
                                        class="font-medium leading-none text-gray-900 dark:text-white">
                                        50.000 -
                                        Express Delivery </label>
                                    <p id="express-text"
                                        class="mt-1 text-xs font-normal text-gray-500 dark:text-gray-400">Get it by
                                        1 up to 2 days</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> --}}

            </div>

            <div class="bg-white rounded-lg border border-gray-200 overflow-hidden p-6 max-w-1/3 w-full">
                <p class="text-xl font-semibold text-gray-900 dark:text-white">Product Details</p>
                <div class="mt-6 space-y-6 sm:mt-8">
                    <div class="flow-root">
                        <div id="checkout-items-container" class="-my-3 divide-y divide-gray-200 dark:divide-gray-800">
                            <!-- Cart items will be loaded here -->
                            <div class="flex items-center">
                                <div
                                    class="mr-4 h-28 w-28 flex-shrink-0 overflow-hidden rounded-md border border-gray-200">
                                    <img src="{{ $product['product_image'] }}" alt="${item.name}"
                                        class="h-full w-full object-cover object-center" />
                                </div>
                                <div class="item-details">
                                    <h3 class="text-base font-medium text-gray-900 dark:text-white">
                                        {{ $product['product_name'] }}
                                    </h3>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Quantity:
                                        {{ $cart['quantity'] }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="order-summary" class="divide-y divide-gray-200 dark:divide-gray-800">
                        <!-- Subtotal -->
                        <dl class="flex items-center justify-between gap-2 py-3">
                            <dt class="text-base font-normal text-gray-500 dark:text-gray-400">Subtotal</dt>
                            <dd class="text-base font-medium text-gray-900 dark:text-white">IDR
                                {{ number_format($subtotal) }}</dd>
                        </dl>

                        <!-- Shipping -->
                        <dl class="flex items-center justify-between gap-2 py-3">
                            <dt class="text-base font-normal text-gray-500 dark:text-gray-400">Shipping Fee</dt>
                            <dd class="text-base font-medium text-gray-900 dark:text-white"> IDR
                                {{ number_format($shippingFee) }}</dd>
                        </dl>

                        <!-- Taxes -->
                        <dl class="flex items-center justify-between gap-2 py-3">
                            <dt class="text-base font-normal text-gray-500 dark:text-gray-400">Flat Tax</dt>
                            <dd class="text-base font-medium text-gray-900 dark:text-white">IDR
                                {{ number_format($taxAmount) }}</dd>
                        </dl>

                        <!-- Total -->
                        <dl class="flex items-center justify-between gap-2 py-3">
                            <dt class="text-base font-bold text-gray-900 dark:text-white">Total</dt>
                            <dd class="text-base font-bold text-gray-900 dark:text-white">IDR
                                {{ number_format($total) }}</dd>
                        </dl>
                    </div>
                    @if (session('failed'))
                        <div class="mt-4 text-red-600">❌ {{ session('failed') }}</div>
                    @endif


                    <!-- Order buttons -->
                    {{-- @if (session('alert'))
                        <script>
                            if (confirm('{{ session('alert') }}')) {
                                // User clicked YES
                                window.location.href = "{{ route('profile') }}";
                            } else {
                                // User clicked NO
                                console.log('User canceled!');
                            }
                        </script>
                    @endif --}}
                    <div class="space-y-2">
                        <form method="POST" action="{{ route('checkout.payment') }}">
                            @csrf
                            <input type="hidden" name="payment_method" value="{{ $paymentMethod }}">
                            <input type="hidden" name="delivery_class"
                                value="{{ $shippingFee == 50000 ? 'express' : 'standard' }}">
                            <input type="hidden" name="transaction_id" value="{{ $transactionId }}">
                            <button type="submit"
                                class="flex w-full items-center justify-center rounded-lg bg-[#FE9494] px-5 py-2 text-base font-medium text-white">Confirm
                                order</button>
                        </form>
                        <form method="POST" action="{{ route('checkout.cancel') }}">
                            @csrf
                            <input type="hidden" name="transaction_id" value="{{ $transactionId }}">
                            <button type="cancel" onclick="route('cart.index')"
                                class="flex w-full items-center justify-center rounded-lg border border-red-400 bg-white px-5 py-2 text-base font-medium text-red-500 hover:bg-gray-50 focus:outline-none focus:ring-4 focus:ring-gray-200">Cancel
                                order</button>
                        </form>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</x-main>
