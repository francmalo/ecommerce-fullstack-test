@extends('layouts.app')

@section('content')
<div id="productsContainer" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
    <!-- Products will be loaded here by JavaScript -->
</div>

<div id="cartPreview" class="hidden fixed right-4 top-20 w-96 bg-white shadow-lg rounded-xl p-6 border border-gray-100">
    <div class="flex items-center justify-between mb-4">
        <h3 class="text-lg font-semibold text-gray-900">Shopping Cart</h3>
        <button class="text-gray-400 hover:text-gray-500" onclick="app.toggleCart()">
            <i data-lucide="x" class="w-5 h-5"></i>
        </button>
    </div>
    <div id="cartItems" class="space-y-4 max-h-96 overflow-y-auto"></div>
    <div id="cartTotal" class="mt-6 pt-4 border-t border-gray-100 font-semibold text-lg text-gray-900"></div>
    <button id="checkoutBtn" class="mt-4 w-full px-4 py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors flex items-center justify-center">
        <i data-lucide="credit-card" class="w-5 h-5 mr-2"></i>
        <span>Checkout</span>
    </button>
</div>

@push('scripts')
<script>
    // Global app variable
    let app;

    document.addEventListener('DOMContentLoaded', function() {
        class ECommerceApp {
            constructor() {
                this.cart = [];
                this.products = [];
                this.setupEventListeners();
                this.loadProducts();
                this.loadCart();
            }

            setupEventListeners() {
                const cartBtn = document.getElementById('cartBtn');
                const ordersBtn = document.getElementById('ordersBtn');
                const checkoutBtn = document.getElementById('checkoutBtn');
                const logoutBtn = document.getElementById('logoutBtn');

                if (cartBtn) cartBtn.addEventListener('click', () => this.toggleCart());
                if (ordersBtn) ordersBtn.addEventListener('click', () => this.showOrders());
                if (checkoutBtn) checkoutBtn.addEventListener('click', () => this.checkout());
                if (logoutBtn) logoutBtn.addEventListener('click', () => this.logout());

                // Close cart when clicking outside
                document.addEventListener('click', (e) => {
                    const cartPreview = document.getElementById('cartPreview');
                    const cartBtn = document.getElementById('cartBtn');
                    if (!cartPreview.contains(e.target) && !cartBtn.contains(e.target)) {
                        cartPreview.classList.add('hidden');
                    }
                });
            }

            async loadProducts() {
                try {
                    const response = await fetch('/api/products');
                    if (!response.ok) throw new Error('Failed to load products');
                    this.products = await response.json();
                    this.renderProducts();
                } catch (error) {
                    this.showNotification('Error loading products', 'error');
                }
            }

            async loadCart() {
                try {
                    const response = await fetch('/api/cart');
                    if (!response.ok) throw new Error('Failed to load cart');
                    const cart = await response.json();
                    this.updateCartDisplay(cart);
                } catch (error) {
                    this.showNotification('Error loading cart', 'error');
                }
            }

           // Update the renderProducts method in your ECommerceApp class
    renderProducts() {
        const container = document.getElementById('productsContainer');
        if (!container) return;

        container.innerHTML = this.products.map(product => `
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition-shadow">
                <div class="aspect-w-3 aspect-h-2">
                    <img src="${product.image_url}" alt="${product.name}" class="w-full h-70 object-cover">
                </div>
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900">${product.name}</h3>
                    <p class="mt-2 text-gray-600 text-sm line-clamp-2">${product.description}</p>
                    <div class="mt-4 flex items-center justify-between">
                        <p class="text-xl font-semibold text-gray-900">$${product.price}</p>
                        <button
                            class="flex items-center px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors disabled:bg-gray-300 disabled:cursor-not-allowed"
                            data-product-id="${product.id}"
                            ${product.stock < 1 ? 'disabled' : ''}
                        >
                            <i data-lucide="${product.stock < 1 ? 'x' : 'shopping-cart'}" class="w-5 h-5 mr-2"></i>
                            <span>${product.stock < 1 ? 'Out of Stock' : 'Add to Cart'}</span>
                        </button>
                    </div>
                </div>
            </div>
        `).join('');

        // Reinitialize Lucide icons for new content
        lucide.createIcons();

        // Add event listeners for Add to Cart buttons
        container.querySelectorAll('button[data-product-id]').forEach(button => {
            button.addEventListener('click', () => {
                const productId = parseInt(button.dataset.productId);
                this.addToCart(productId);
            });
        });
    }

    // Update the updateCartDisplay method
    updateCartDisplay(cart) {
        const cartItems = document.getElementById('cartItems');
        const cartTotal = document.getElementById('cartTotal');
        const cartBtn = document.getElementById('cartBtn');

        if (cartItems && cartTotal && cartBtn) {
            cartItems.innerHTML = cart.items.map(item => `
                <div class="cart-item">
                    <div class="flex-1">
                        <h4 class="font-medium text-gray-900">${item.name}</h4>
                        <div class="flex items-center mt-2 space-x-2">
                            <button
                                class="p-1 rounded border border-gray-300 hover:bg-gray-50"
                                onclick="app.updateQuantity(${item.product_id}, Math.max(1, ${item.quantity - 1}))"
                            >
                                <i data-lucide="minus" class="w-4 h-4"></i>
                            </button>
                            <span class="px-2 py-1 min-w-[2rem] text-center">${item.quantity}</span>
                            <button
                                class="p-1 rounded border border-gray-300 hover:bg-gray-50"
                                onclick="app.updateQuantity(${item.product_id}, ${item.quantity + 1})"
                            >
                                <i data-lucide="plus" class="w-4 h-4"></i>
                            </button>
                        </div>
                    </div>
                    <div class="flex flex-col items-end ml-4">
                        <span class="font-medium">$${(item.price * item.quantity).toFixed(2)}</span>
                        <button
                            class="mt-2 text-red-600 hover:text-red-700 flex items-center"
                            onclick="app.removeFromCart(${item.product_id})"
                        >
                            <i data-lucide="trash-2" class="w-4 h-4 mr-1"></i>
                            <span>Remove</span>
                        </button>
                    </div>
                </div>
            `).join('');

            cartTotal.innerHTML = `Total: $${cart.total.toFixed(2)}`;
            cartBtn.innerHTML = `
                <i data-lucide="shopping-cart" class="w-5 h-5 mr-2"></i>
                <span> (${cart.items.length})</span>
            `;

            // Reinitialize Lucide icons for new content
            lucide.createIcons();
        }
    }

            async addToCart(productId) {
                try {
                    const response = await fetch('/api/cart/add', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify({
                            product_id: productId,
                            quantity: 1
                        })
                    });

                    if (!response.ok) throw new Error('Failed to add item to cart');

                    const cart = await response.json();
                    this.updateCartDisplay(cart);
                    this.showNotification('Item added to cart', 'success');
                } catch (error) {
                    this.showNotification(error.message, 'error');
                }
            }

            async removeFromCart(productId) {
                try {
                    const response = await fetch(`/api/cart/remove/${productId}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        }
                    });

                    if (!response.ok) throw new Error('Failed to remove item from cart');
                    const cart = await response.json();
                    this.updateCartDisplay(cart);
                    this.showNotification('Item removed from cart', 'success');
                } catch (error) {
                    this.showNotification(error.message, 'error');
                }
            }

            {{--  updateCartDisplay(cart) {
                const cartItems = document.getElementById('cartItems');
                const cartTotal = document.getElementById('cartTotal');
                const cartBtn = document.getElementById('cartBtn');

                if (cartItems && cartTotal && cartBtn) {
                    cartItems.innerHTML = cart.items.map(item => `
                        <div class="cart-item">
                            <span>${item.name} (${item.quantity})</span>
                            <span>$${(item.price * item.quantity).toFixed(2)}</span>
                            <button
                                class="ml-2 px-2 py-1 text-red-500 hover:text-red-700"
                                onclick="app.removeFromCart(${item.product_id})"
                            >
                                ×
                            </button>
                        </div>
                    `).join('');

                    cartTotal.innerHTML = `Total: $${cart.total.toFixed(2)}`;
                    cartBtn.textContent = `Cart (${cart.items.length})`;
                }
            }  --}}



async updateQuantity(productId, newQuantity) {
    try {
        const response = await fetch(`/api/cart/update-quantity/${productId}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                quantity: newQuantity
            })
        });

        if (!response.ok) {
            const error = await response.json();
            throw new Error(error.error || 'Failed to update quantity');
        }

        const cart = await response.json();
        this.updateCartDisplay(cart);
    } catch (error) {
        this.showNotification(error.message, 'error');
    }
}

// In your ECommerceApp class, update the checkout method:
async checkout() {
    try {
        const response = await fetch('/api/cart');
        const cart = await response.json();

        if (!cart.items.length) {
            this.showNotification('Your cart is empty', 'error');
            return;
        }

        // Check if user is authenticated
        const isAuthenticated = document.querySelector('meta[name="auth-check"]') !== null;

        if (!isAuthenticated) {
            // Store return URL in session storage
            sessionStorage.setItem('checkout_redirect', '/checkout');
            window.location.href = '/login';
            return;
        }

        // If authenticated, redirect to checkout
        window.location.href = '/checkout';
    } catch (error) {
        this.showNotification('Error processing checkout', 'error');
    }
}




            async showOrders() {
                try {
                    const response = await fetch('/api/orders');
                    if (!response.ok) throw new Error('Failed to load orders');

                    const orders = await response.json();
                    const modalHtml = `
                        <div class="modal">
                            <div class="modal-content">
                                <h2 class="text-2xl font-bold mb-4">Your Orders</h2>
                                ${orders.map(order => `
                                    <div class="border rounded-lg p-4 mb-4">
                                        <h3 class="text-lg font-semibold">Order #${order.id}</h3>
                                        <p>Date: ${new Date(order.created_at).toLocaleDateString()}</p>
                                        <p>Total: $${order.total_amount}</p>
                                        <p>Status: ${order.status}</p>
                                        <div class="mt-2">
                                            ${order.items.map(item => `
                                                <div class="text-sm text-gray-600">
                                                    ${item.product.name} x ${item.quantity}
                                                </div>
                                            `).join('')}
                                        </div>
                                    </div>
                                `).join('')}
                            </div>
                        </div>
                    `;

                    document.body.insertAdjacentHTML('beforeend', modalHtml);

                    document.querySelector('.modal').addEventListener('click', (e) => {
                        if (e.target.classList.contains('modal')) {
                            e.target.remove();
                        }
                    });
                } catch (error) {
                    this.showNotification('Error loading orders', 'error');
                }
            }

            showNotification(message, type) {
                const notification = document.createElement('div');
                notification.className = `notification ${type}`;
                notification.textContent = message;
                document.body.appendChild(notification);

                setTimeout(() => {
                    notification.remove();
                }, 3000);
            }

            toggleCart() {
                const cartPreview = document.getElementById('cartPreview');
                if (cartPreview) {
                    cartPreview.classList.toggle('hidden');
                }
            }

            async logout() {
                try {
                    const response = await fetch('/logout', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        }
                    });

                    if (!response.ok) throw new Error('Logout failed');
                    window.location.href = '/login';
                } catch (error) {
                    this.showNotification('Logout failed', 'error');
                }
            }
        }

        // Initialize the application
        app = new ECommerceApp();
    });
</script>
@endpush
@endsection





