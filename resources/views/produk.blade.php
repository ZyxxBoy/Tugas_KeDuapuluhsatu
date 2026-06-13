<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Semua Produk - TUKU</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />

    <!-- Styles / Scripts -->
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <script src="https://cdn.tailwindcss.com"></script>
    @endif
</head>
<body class="bg-gray-50 text-gray-900 font-sans antialiased flex flex-col min-h-screen">

    <!-- Navbar Component -->
    <x-navbar />

    <!-- Main Content -->
    <main class="flex-grow max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 w-full">
        
        <div class="flex flex-col sm:flex-row sm:items-baseline justify-between border-b border-gray-200 pb-6 pt-4 gap-4">
            <h1 class="text-3xl font-bold tracking-tight text-gray-900">Semua Produk</h1>
            <div class="flex flex-col sm:flex-row items-center gap-4 w-full sm:w-auto">
                <!-- Search and Sort Form -->
                <form action="{{ route('produk') }}" method="GET" class="flex flex-col sm:flex-row items-center gap-4 w-full">
                    @if(request('category'))
                        <input type="hidden" name="category" value="{{ request('category') }}">
                    @endif
                    
                    <div class="relative w-full sm:w-64">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari produk..." class="w-full rounded-md border-gray-300 py-1.5 pl-3 pr-10 text-sm focus:border-indigo-500 focus:ring-indigo-500 shadow-sm">
                        <button type="submit" class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-indigo-600">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    </div>

                    <div class="relative inline-block text-left w-full sm:w-auto hidden sm:block">
                        <select name="sort" onchange="this.form.submit()" class="rounded-md border-gray-300 py-1.5 pl-3 pr-8 text-sm focus:border-indigo-500 focus:ring-indigo-500 shadow-sm w-full">
                            <option value="terbaru" {{ request('sort') == 'terbaru' ? 'selected' : '' }}>Terbaru</option>
                            <option value="termurah" {{ request('sort') == 'termurah' ? 'selected' : '' }}>Harga: Rendah ke Tinggi</option>
                            <option value="termahal" {{ request('sort') == 'termahal' ? 'selected' : '' }}>Harga: Tinggi ke Rendah</option>
                        </select>
                    </div>
                </form>
            </div>
        </div>

        <section aria-labelledby="products-heading" class="pb-24 pt-6">
            <div class="grid grid-cols-1 gap-x-8 gap-y-10 lg:grid-cols-4">
                
                <!-- Filters -->
                <div class="hidden lg:block">
                    <h3 class="sr-only">Kategori</h3>
                    <ul role="list" class="space-y-4 border-b border-gray-200 pb-6 text-sm font-medium text-gray-900">
                        <li>
                            <a href="{{ route('produk', ['search' => request('search'), 'sort' => request('sort')]) }}" class="{{ !request('category') ? 'text-indigo-600 font-bold' : 'text-gray-900 hover:text-indigo-600' }}">Semua Kategori</a>
                        </li>
                        @foreach($categories as $category)
                        <li>
                            <a href="{{ route('produk', ['category' => $category->slug, 'search' => request('search'), 'sort' => request('sort')]) }}" class="{{ request('category') == $category->slug ? 'text-indigo-600 font-bold' : 'text-gray-900 hover:text-indigo-600' }}">{{ $category->name }}</a>
                        </li>
                        @endforeach
                    </ul>
                </div>

                <!-- Product grid -->
                <div class="lg:col-span-3">
                    <div class="grid grid-cols-1 gap-y-10 sm:grid-cols-2 gap-x-6 lg:grid-cols-3 xl:gap-x-8">
                        @foreach ($products as $product)
                        <!-- Product Card -->
                        <div class="group relative flex flex-col bg-white rounded-xl shadow-sm hover:shadow-md transition-shadow duration-300 p-4 border border-gray-100 h-full">
                            <div class="w-full min-h-60 bg-gray-200 aspect-w-1 aspect-h-1 rounded-md overflow-hidden group-hover:opacity-75 lg:h-60 lg:aspect-none">
                                <img src="{{ asset('images/' . $product->image) }}" onerror="this.onerror=null;this.src='https://images.unsplash.com/photo-1523275335684-37898b6baf30?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80';" alt="{{ $product->name }}" class="w-full h-full object-center object-cover lg:w-full lg:h-full">
                            </div>
                            <div class="mt-4 flex flex-col flex-grow justify-between">
                                <div>
                                    <h3 class="text-sm text-gray-700 font-medium">
                                        <a href="{{ route('produk.showPublic', $product->slug) }}">
                                            <span aria-hidden="true" class="absolute inset-0"></span>
                                            {{ $product->name }}
                                        </a>
                                    </h3>
                                    <p class="mt-1 text-sm text-gray-500">{{ $product->category->name ?? 'Tanpa Kategori' }}</p>
                                </div>
                                <div class="mt-2 flex items-center justify-between">
                                    <p class="text-sm font-semibold text-gray-900">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                                    <button class="relative z-10 p-2 text-indigo-600 bg-indigo-50 hover:bg-indigo-100 rounded-full transition-colors" title="Tambah ke Keranjang">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    
                    <!-- Pagination -->
                    <div class="mt-10">
                        {{ $products->links() }}
                    </div>
                </div>
            </div>
        </section>

    </main>

    <!-- Footer Component -->
    <x-footer />

</body>
</html>
