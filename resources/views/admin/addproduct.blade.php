<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Produk</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.5.0/fonts/remixicon.css" rel="stylesheet">
</head>

<body class="bg-gray-100 h-full">
    <div class="flex min-h-screen bg-gray-100">
        <!-- Sidebar -->
        <div class="w-16 bg-white flex flex-col items-center py-4 shadow-sm">
            <div class="mb-8">
                <img src="/img/logopet.png" alt="Petty Logo" class="w-10 h-7">
            </div>
            <div class="flex flex-col items-center gap-8">
                {{-- <a href="/admin/dashboard" class="p-2 rounded-lg hover:bg-gray-100 transition-colors">
                    <i class="ri-pie-chart-line text-gray-400 text-xl"></i>
                </a> --}}
                <a href="/admin/product" class="p-2 rounded-lg bg-pink-50 hover:bg-pink-100 transition-colors">
                    <i class="ri-shopping-bag-3-line text-pink-400 text-xl"></i>
                </a>
                <a href="/admin/order" class="p-2 rounded-lg hover:bg-gray-100 transition-colors">
                    <i class="ri-shopping-cart-line text-gray-400 text-xl"></i>
                </a>
                <a href="/admin/user" class="p-2 rounded-lg hover:bg-gray-100 transition-colors">
                    <i class="ri-group-line text-gray-400 text-xl"></i>
                </a>
            </div>
            <div class="mt-auto">
                <a href="/" class="p-2 rounded-lg hover:bg-gray-100 transition-colors">
                    <i class="ri-settings-line text-gray-400 text-xl"></i>
                </a>
            </div>
        </div>

        <div class="flex-1 px-4 py-8">
            <div class="max-w-3xl mx-auto">
                <div class="flex items-center justify-between mb-6">
                    <h1 class="text-2xl font-bold text-gray-800">Add Product</h1>
                    <a href="/admin/product"
                        class="px-4 py-2 bg-gray-300 text-gray-800 rounded-lg shadow-sm hover:bg-gray-400 transition">
                        ← Previous
                    </a>
                </div>

                <!-- Form -->
                <div class="p-6 bg-white rounded-xl shadow-md">
                    <form action="{{ route('product.store') }}" method="POST">
                        @csrf

                        <div class="mb-4">
                            <label class="block font-semibold">Nama Product</label>
                            <input type="text" name="product_name" class="w-full border rounded p-2" required>
                        </div>

                        <div class="mb-4">
                            <label class="block font-semibold">URL Foto Product</label>
                            <input type="url" name="product_image" class="w-full border rounded p-2"
                                placeholder="https://example.com/image.jpg" required>
                        </div>

                        <div class="mb-4">
                            <label class="block font-semibold">Deskripsi Product</label>
                            <textarea name="product_desc" class="w-full border rounded p-2" rows="4" required></textarea>
                        </div>

                        <div class="mb-4">
                            <label class="block font-semibold">Tipe Produk</label>
                            <select name="product_type" class="w-full border rounded p-2" required>
                                <option value="">- Pilih Tipe Produk -</option>
                                <option value="food">Food</option>
                                <option value="medicine">Medicine</option>
                                <option value="accesories">Accessories</option>
                            </select>
                        </div>

                        <div class="mb-4">
                            <label class="block font-semibold">Jenis Hewan</label>
                            <select name="pet_type" class="w-full border rounded p-2" required>
                                <option value="">- Pilih Jenis Hewan -</option>
                                <option value="dog">Dog</option>
                                <option value="cat">Cat</option>
                                <option value="rabbit">Rabbit</option>
                                <option value="turtle">Turtle</option>
                            </select>
                        </div>

                        <div class="mb-4">
                            <label class="block font-semibold">Harga Product</label>
                            <div class="flex items-center">
                                <span class="px-3 py-2 border border-r-0 rounded-l bg-gray-100">Rp</span>
                                <input type="number" name="product_price" class="w-full border rounded-r p-2" required>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="block font-semibold">Stok Product</label>
                            <input type="number" name="product_stock" class="w-full border rounded p-2" required>
                        </div>

                        <div class="mb-4">
                            <label class="block font-semibold">Rating Produk</label>
                            <input type="number" name="product_rating" min="0" max="10" step="0.1"
                                class="w-full border rounded p-2" placeholder="Contoh: 4.5">
                        </div>

                        <div class="mb-4">
                            <label class="block font-semibold">Status Product</label>
                            <select class="w-full border rounded p-2">
                                <option value="available">Available</option>
                                <option value="unavailable">Unavailable</option>
                            </select>
                        </div>

                        <div class="text-right">
                            <button type="submit"
                                class="px-4 py-2 bg-red-400 text-white rounded-lg shadow-sm hover:bg-red-500">
                                Simpan Produk
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
