class CheckoutApp {
    constructor(cart) {
        this.cart = cart; // Cart data from server
        this.init();
    }

    init() {
        this.renderOrderSummary();
        this.setupEventListeners();
    }

    renderOrderSummary() {
        const orderSummary = document.getElementById('orderSummary');
        const orderTotal = document.getElementById('orderTotal');

        if (!orderSummary || !orderTotal) return;

        orderSummary.innerHTML = this.cart.items
            .map(
                (item) => `
                <div class="flex justify-between">
                    <div>
                        <p class="font-medium">${item.name}</p>
                        <p class="text-sm text-gray-600">Quantity: ${item.quantity}</p>
                    </div>
                    <p class="font-medium">$${(item.price * item.quantity).toFixed(2)}</p>
                </div>
            `
            )
            .join('');

        orderTotal.textContent = `$${this.cart.total.toFixed(2)}`;
    }

    setupEventListeners() {
        const checkoutForm = document.getElementById('checkoutForm');
        const placeOrderBtn = document.getElementById('placeOrderBtn');

        if (checkoutForm) {
            checkoutForm.addEventListener('submit', (event) => {
                event.preventDefault();
                this.handleFormSubmission(checkoutForm, placeOrderBtn);
            });
        }
    }

    async handleFormSubmission(form, button) {
        button.disabled = true; // Prevent multiple submissions
        button.textContent = 'Processing...';

        const formData = new FormData(form);
        const formDataObject = Object.fromEntries(formData.entries());

        try {
            const response = await fetch(form.action, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                },
                body: JSON.stringify(formDataObject),
            });

            if (!response.ok) {
                const errorData = await response.json();
                throw new Error(errorData.message || 'Failed to place order');
            }

            const responseData = await response.json();
            this.handleSuccess(responseData);
        } catch (error) {
            this.handleError(error.message);
        } finally {
            button.disabled = false;
            button.textContent = 'Place Order';
        }
    }

    handleSuccess(responseData) {
        window.location.href = `/checkout/confirmation/${responseData.order_id}`;
    }

    handleError(errorMessage) {
        alert(`Error: ${errorMessage}`);
    }
}
