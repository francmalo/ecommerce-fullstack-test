<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
@auth
    <meta name="auth-check" content="true">
@endauth
    <title>E-Commerce Store</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #F9FAFB;
        }

        .notification {
            position: fixed;
            bottom: 20px;
            right: 20px;
            padding: 1rem;
            border-radius: 0.75rem;
            z-index: 50;
            color: white;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        }
        .notification.success {
            background-color: #10B981;
        }
        .notification.error {
            background-color: #EF4444;
        }
        .modal {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(4px);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 100;
        }
        .modal-content {
            background-color: white;
            padding: 2rem;
            border-radius: 1rem;
            max-width: 90%;
            max-height: 90%;
            overflow-y: auto;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
        }
        .cart-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem;
            border-bottom: 1px solid #E5E7EB;
            transition: all 0.2s ease;
        }
        .cart-item:hover {
            background-color: #F9FAFB;
        }
        .btn-primary {
            background-color: #4F46E5;
            color: white;
            transition: all 0.2s ease;
        }
        .btn-primary:hover {
            background-color: #4338CA;
            transform: translateY(-1px);
        }
        .btn-secondary {
            background-color: white;
            border: 1px solid #E5E7EB;
            color: #374151;
            transition: all 0.2s ease;
        }
        .btn-secondary:hover {
            background-color: #F9FAFB;
            border-color: #D1D5DB;
        }
    </style>
</head>
<body>
<nav class="bg-white shadow-sm sticky top-0 z-40">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex items-center">
                <a href="/" class="flex items-center space-x-2">
                    <i data-lucide="shopping-bag" class="w-6 h-6 text-indigo-600"></i>
                    <span class="text-xl font-semibold text-gray-900">Store</span>
                </a>
            </div>

            <div class="flex items-center space-x-4">
                <button id="cartBtn" class="btn-secondary px-4 py-2 rounded-lg flex items-center space-x-2">
                    <i data-lucide="shopping-cart" class="w-5 h-5"></i>
                    <span>(0)</span>
                </button>
                @auth
                    <button id="ordersBtn" class="btn-secondary px-4 py-2 rounded-lg flex items-center space-x-2">
                        <i data-lucide="package" class="w-5 h-5"></i>
                        <span>Orders</span>
                    </button>
                    <button id="logoutBtn" class="btn-secondary px-4 py-2 rounded-lg flex items-center space-x-2">
                        <i data-lucide="log-out" class="w-5 h-5"></i>
                        <span>Logout</span>
                    </button>
                @else
                    <a href="/login" class="btn-secondary px-4 py-2 rounded-lg flex items-center space-x-2">
                        <i data-lucide="log-in" class="w-5 h-5"></i>
                        <span>Login</span>
                    </a>
                    <a href="/register" class="btn-primary px-4 py-2 rounded-lg flex items-center space-x-2">
                        <i data-lucide="user-plus" class="w-5 h-5"></i>
                        <span>Register</span>
                    </a>
                @endauth
            </div>
        </div>
    </div>
</nav>

    <main class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        @yield('content')
    </main>

    <div id="cartPreview" class="hidden fixed right-4 top-20 w-96 bg-white shadow-xl rounded-xl p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-900">Shopping Cart</h3>
            <button class="text-gray-400 hover:text-gray-500">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>
        <div id="cartItems" class="space-y-4"></div>
        <div id="cartTotal" class="mt-6 text-lg font-semibold text-gray-900"></div>
        <button id="checkoutBtn" class="btn-primary w-full mt-4 px-4 py-3 rounded-lg flex items-center justify-center space-x-2">
            <i data-lucide="credit-card" class="w-5 h-5"></i>
            <span>Checkout</span>
        </button>
    </div>

    @stack('scripts')
    <script>
        // Initialize Lucide icons
        lucide.createIcons();
    </script>
</body>
</html>
