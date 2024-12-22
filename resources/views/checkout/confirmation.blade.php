@extends('layouts.app')

@section('content')
<div class="h-[calc(100vh-4rem)] flex items-center justify-center p-4">
    <div class="w-full max-w-2xl bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Left Column: Success Message and Order Details -->
            <div>
                <!-- Success Message -->
                <div class="flex items-center space-x-3 mb-4">
                    <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center flex-shrink-0">
                        <i data-lucide="check" class="w-6 h-6 text-green-500"></i>
                    </div>
                    <div>
                        <h1 class="text-xl font-bold text-gray-900">Order Confirmed!</h1>
                        <p class="text-sm text-gray-600">Thank you for your purchase</p>
                    </div>
                </div>

                <!-- Order Details -->
                <div class="space-y-2">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Order Number:</span>
                        <span class="font-medium text-gray-900">#{{ $order->id }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Total Amount:</span>
                        <span class="font-medium text-gray-900">${{ number_format($order->total_amount, 2) }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Status:</span>
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            <i data-lucide="check-circle" class="w-3 h-3 mr-1"></i>
                            {{ ucfirst($order->status) }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Right Column: Shipping Details -->
            <div class="border-t md:border-t-0 md:border-l border-gray-100 pt-4 md:pt-0 md:pl-6">
                <h2 class="text-sm font-semibold text-gray-900 flex items-center mb-3">
                    <i data-lucide="truck" class="w-4 h-4 mr-1 text-indigo-600"></i>
                    Shipping Details
                </h2>
                <div class="space-y-1 text-sm text-gray-600">
                    <p class="font-medium text-gray-900">{{ $order->shipping_name }}</p>
                    <p>{{ $order->shipping_address }}</p>
                    <p>{{ $order->shipping_city }}, {{ $order->shipping_state }} {{ $order->shipping_zip }}</p>
                    <p class="flex items-center">
                        <i data-lucide="phone" class="w-3 h-3 mr-1 text-gray-400"></i>
                        {{ $order->shipping_phone }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Continue Shopping Button -->
        <div class="mt-6 text-center">
            <a href="/" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 transition-colors">
                <i data-lucide="shopping-bag" class="w-4 h-4 mr-1"></i>
                Continue Shopping
            </a>
        </div>
    </div>
</div>
@endsection
