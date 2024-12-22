@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto py-8">
    <h1 class="text-2xl font-semibold text-gray-900 flex items-center mb-8">
        <i data-lucide="credit-card" class="w-6 h-6 mr-2 text-indigo-600"></i>
        Checkout
    </h1>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <!-- Order Summary -->
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
            <h2 class="text-lg font-semibold text-gray-900 flex items-center mb-6">
                <i data-lucide="shopping-bag" class="w-5 h-5 mr-2 text-indigo-600"></i>
                Order Summary
            </h2>
            <div class="space-y-4">
                @foreach ($cart['items'] as $item)
                <div class="flex items-center justify-between py-3 border-b border-gray-100 last:border-0">
                    <div class="flex-1">
                        <p class="font-medium text-gray-900">{{ $item['name'] }}</p>
                        <p class="text-sm text-gray-500 mt-1">Quantity: {{ $item['quantity'] }}</p>
                    </div>
                    <p class="font-semibold text-gray-900 ml-4">${{ number_format($item['price'] * $item['quantity'], 2) }}</p>
                </div>
                @endforeach
            </div>
            <div class="mt-6 pt-4 border-t border-gray-100">
                <div class="flex justify-between items-center">
                    <p class="text-lg font-semibold text-gray-900">Total</p>
                    <p class="text-xl font-bold text-indigo-600">${{ number_format($cart['total'], 2) }}</p>
                </div>
            </div>
        </div>

        <!-- Shipping Form -->
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
            <h2 class="text-lg font-semibold text-gray-900 flex items-center mb-6">
                <i data-lucide="truck" class="w-5 h-5 mr-2 text-indigo-600"></i>
                Shipping Details
            </h2>
            <form action="{{ route('checkout.process') }}" method="POST">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
                        <div class="relative">
                            <i data-lucide="user" class="w-5 h-5 text-gray-400 absolute left-3 top-1/2 transform -translate-y-1/2"></i>
                            <input type="text" name="shipping_name" class="pl-10 w-full rounded-lg border-gray-200 focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Address</label>
                        <div class="relative">
                            <i data-lucide="map-pin" class="w-5 h-5 text-gray-400 absolute left-3 top-1/2 transform -translate-y-1/2"></i>
                            <input type="text" name="shipping_address" class="pl-10 w-full rounded-lg border-gray-200 focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">City</label>
                        <div class="relative">
                            <i data-lucide="building" class="w-5 h-5 text-gray-400 absolute left-3 top-1/2 transform -translate-y-1/2"></i>
                            <input type="text" name="shipping_city" class="pl-10 w-full rounded-lg border-gray-200 focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">State</label>
                            <input type="text" name="shipping_state" class="w-full rounded-lg border-gray-200 focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">ZIP Code</label>
                            <input type="text" name="shipping_zip" class="w-full rounded-lg border-gray-200 focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
                        <div class="relative">
                            <i data-lucide="phone" class="w-5 h-5 text-gray-400 absolute left-3 top-1/2 transform -translate-y-1/2"></i>
                            <input type="tel" name="shipping_phone" class="pl-10 w-full rounded-lg border-gray-200 focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                        </div>
                    </div>

                    @if ($errors->any())
                        <div class="bg-red-50 text-red-500 p-4 rounded-lg">
                            <ul class="list-disc list-inside space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <button type="submit" class="w-full px-6 py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors flex items-center justify-center">
                        <i data-lucide="check-circle" class="w-5 h-5 mr-2"></i>
                        Place Order
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
