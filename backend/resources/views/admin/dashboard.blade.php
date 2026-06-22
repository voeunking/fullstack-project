<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-100 text-slate-800">
    <div class="min-h-screen flex">
        <aside class="w-72 bg-slate-900 text-white p-6">
            <h2 class="text-2xl font-bold mb-8">Admin Panel</h2>
            <nav class="space-y-3">
                <a href="#" class="block px-4 py-2 rounded bg-slate-700">Dashboard</a>
                <a href="#" class="block px-4 py-2 rounded hover:bg-slate-800">Products</a>
                <a href="#" class="block px-4 py-2 rounded hover:bg-slate-800">Categories</a>
                <a href="#" class="block px-4 py-2 rounded hover:bg-slate-800">Users</a>
            </nav>
        </aside>

        <main class="flex-1 p-8">
            <div class="flex justify-between items-center mb-6">
                <div>
                    <p class="text-sm text-slate-500">Overview</p>
                    <h1 class="text-3xl font-semibold">Dashboard</h1>
                </div>
                <button class="bg-blue-600 text-white px-4 py-2 rounded-lg">+ Add New</button>
            </div>

            <section class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                <div class="bg-white p-5 rounded-xl shadow">
                    <p class="text-sm text-slate-500">Users</p>
                    <h3 class="text-2xl font-bold" id="total_users">{{ $stats['total_users'] }}</h3>
                </div>
                <div class="bg-white p-5 rounded-xl shadow">
                    <p class="text-sm text-slate-500">Products</p>
                    <h3 class="text-2xl font-bold" id="total_products">{{ $stats['total_products'] }}</h3>
                </div>
                <div class="bg-white p-5 rounded-xl shadow">
                    <p class="text-sm text-slate-500">Categories</p>
                    <h3 class="text-2xl font-bold" id="total_categories">{{ $stats['total_categories'] }}</h3>
                </div>
                <div class="bg-white p-5 rounded-xl shadow">
                    <p class="text-sm text-slate-500">Sales</p>
                    <h3 class="text-2xl font-bold" id="total_sales">{{ $stats['total_sales'] }}</h3>
                </div>
            </section>

            <section class="bg-white rounded-xl shadow p-6">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-xl font-semibold">Recent Products</h2>
                    <a href="#" class="text-blue-600 text-sm">View all</a>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead>
                            <tr class="text-left text-slate-500 border-b">
                                <th class="pb-2">ID</th>
                                <th class="pb-2">Name</th>
                                <th class="pb-2">Price</th>
                                <th class="pb-2">Created</th>
                            </tr>
                        </thead>
                        <tbody id="recent_products_body">
                            @foreach($stats['recent_products'] ?? [] as $product)
                                <tr class="border-b">
                                    <td class="py-2">{{ $product->id }}</td>
                                    <td class="py-2">{{ $product->name }}</td>
                                    <td class="py-2">${{ number_format($product->price, 2) }}</td>
                                    <td class="py-2">{{ $product->created_at->format('Y-m-d') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </section>
        </main>
    </div>

    <script>
        fetch('/admin/dashboard/stats')
            .then(response => response.json())
            .then(data => {
                document.getElementById('total_users').textContent = data.total_users;
                document.getElementById('total_products').textContent = data.total_products;
                document.getElementById('total_categories').textContent = data.total_categories;
                document.getElementById('total_sales').textContent = data.total_sales;

                const tbody = document.getElementById('recent_products_body');
                tbody.innerHTML = '';
                data.recent_products.forEach(product => {
                    tbody.innerHTML += `
                        <tr class="border-b">
                            <td class="py-2">${product.id}</td>
                            <td class="py-2">${product.name}</td>
                            <td class="py-2">$${Number(product.price).toFixed(2)}</td>
                            <td class="py-2">${product.created_at}</td>
                        </tr>
                    `;
                });
            });
    </script>
</body>
</html>
