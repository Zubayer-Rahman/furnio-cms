// Cart functionality
class Cart {
    constructor() {
        this.items = JSON.parse(localStorage.getItem('cart')) || [];
        this.observers = [];
        this.updateCartCounter();
    }

    // Observer pattern to handle real-time updates
    addObserver(callback) {
        this.observers.push(callback);
    }

    notifyObservers() {
        this.observers.forEach(callback => callback(this.items));
    }

    addItem(product, quantity = 1) {
        const existingItem = this.items.find(item => item.name === product.name);
        
        if (existingItem) {
            existingItem.quantity += quantity;
        } else {
            this.items.push({
                ...product,
                quantity: quantity
            });
        }
        
        this.saveCart();
        this.updateCartCounter();
        this.notifyObservers();
    }

    removeItem(productName) {
        this.items = this.items.filter(item => item.name !== productName);
        this.saveCart();
        this.updateCartCounter();
        this.notifyObservers();
    }

    updateQuantity(productName, quantity) {
        const item = this.items.find(item => item.name === productName);
        if (item) {
            item.quantity = parseInt(quantity);
            if (item.quantity <= 0) {
                this.removeItem(productName);
            } else {
                this.saveCart();
                this.updateCartCounter();
                this.notifyObservers();
            }
        }
    }

    getTotal() {
        return this.items.reduce((total, item) => total + (item.price * item.quantity), 0);
    }

    getItemCount() {
        return this.items.reduce((count, item) => count + parseInt(item.quantity), 0);
    }

    saveCart() {
        localStorage.setItem('cart', JSON.stringify(this.items));
    }

    updateCartCounter() {
        const count = this.getItemCount();
        
        // First, ensure counter exists on the page
        const cartIconContainer = document.querySelector('.side-nav a:last-child');
        if (cartIconContainer) {
            let counter = cartIconContainer.querySelector('.cart-counter');
            
            // Create counter if it doesn't exist
            if (!counter) {
                counter = document.createElement('span');
                counter.className = 'cart-counter';
                cartIconContainer.appendChild(counter);
            }
            
            // Always show counter if there are items, hide only when count is 0
            counter.textContent = count;
            counter.style.display = count > 0 ? 'block' : 'none';
        }
    }
}

// Initialize cart
const cart = new Cart();

// Add cart counter to any page that includes cart.js and keep it updated
document.addEventListener('DOMContentLoaded', () => {
    // Initial update
    cart.updateCartCounter();
    
    // Update counter periodically to ensure it's always visible
    setInterval(() => {
        cart.updateCartCounter();
    }, 1000);
});