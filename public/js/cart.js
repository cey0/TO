// Cart state management
let cartItems = [];
let total = 0;

// Show cart on page load
document.addEventListener('DOMContentLoaded', function() {
    const cartSection = document.querySelector('.cart-section');
    if (cartSection) {
        cartSection.style.display = 'block';
    }
    updateCart();
});

// Add item to cart
function addToCart(kodeBarang, namaBarang, harga, diskon) {
    const existingItem = cartItems.find(item => item.kodeBarang === kodeBarang);
    
    if (existingItem) {
        existingItem.quantity++;
        existingItem.subtotal = calculateSubtotal(existingItem.harga, existingItem.diskon, existingItem.quantity);
    } else {
        cartItems.push({
            kodeBarang,
            namaBarang,
            harga,
            diskon,
            quantity: 1,
            subtotal: calculateSubtotal(harga, diskon, 1)
        });
    }
    
    updateCart();
}

// Remove item from cart
function removeFromCart(kodeBarang) {
    cartItems = cartItems.filter(item => item.kodeBarang !== kodeBarang);
    updateCart();
}

// Update item quantity
function updateQuantity(kodeBarang, change) {
    const item = cartItems.find(item => item.kodeBarang === kodeBarang);
    if (item) {
        item.quantity = Math.max(1, item.quantity + change);
        item.subtotal = calculateSubtotal(item.harga, item.diskon, item.quantity);
        updateCart();
    }
}

// Calculate subtotal for an item
function calculateSubtotal(harga, diskon, quantity) {
    const discountAmount = (harga * diskon) / 100;
    return (harga - discountAmount) * quantity;
}

// Update cart display
function updateCart() {
    const cartItemsContainer = document.querySelector('.cart-items');
    cartItemsContainer.innerHTML = '';
    
    total = 0;
    
    cartItems.forEach(item => {
        total += item.subtotal;
        
        const itemElement = document.createElement('div');
        itemElement.className = 'cart-item';
        itemElement.innerHTML = `
            <img src="/uploads/barang/${item.kodeBarang}.jpg" onerror="this.src='/img/default.jpg'">
            <div class="cart-item-details">
                <h4 class="cart-item-title">${item.namaBarang}</h4>
                <span class="cart-item-price">Rp ${item.harga.toLocaleString()}</span>
                <div class="quantity-controls">
                    <button class="quantity-btn" onclick="updateQuantity('${item.kodeBarang}', -1)">-</button>
                    <span>${item.quantity}</span>
                    <button class="quantity-btn" onclick="updateQuantity('${item.kodeBarang}', 1)">+</button>
                </div>
            </div>
            <button class="btn btn-danger btn-sm" onclick="removeFromCart('${item.kodeBarang}')">
                <i class="fas fa-trash"></i>
            </button>
        `;
        
        cartItemsContainer.appendChild(itemElement);
    });
    
    // Update total display
    document.querySelector('.cart-total span:last-child').textContent = `Rp ${total.toLocaleString()}`;
}

// Handle checkout
document.querySelector('.checkout-btn').addEventListener('click', function() {
    if (cartItems.length === 0) {
        alert('Please add items to cart before checkout');
        return;
    }
    
    // Prepare transaction data
    const transactionData = {
        items: cartItems,
        total: total
    };
    
    // Send to server
    fetch('/penjualan/save', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify(transactionData)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Transaction completed successfully!');
            cartItems = [];
            updateCart();
        } else {
            alert('Error processing transaction. Please try again.');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error processing transaction. Please try again.');
    });
});

// Search functionality
document.getElementById('searchInput').addEventListener('input', function(e) {
    const searchTerm = e.target.value.toLowerCase();
    const menuItems = document.querySelectorAll('.menu-item');
    
    menuItems.forEach(item => {
        const title = item.querySelector('.menu-item-title').textContent.toLowerCase();
        if (title.includes(searchTerm)) {
            item.style.display = 'block';
        } else {
            item.style.display = 'none';
        }
    });
});

// Category filtering
document.querySelectorAll('.category-icon').forEach(icon => {
    icon.addEventListener('click', function() {
        // Remove active class from all icons
        document.querySelectorAll('.category-icon').forEach(i => i.classList.remove('active'));
        // Add active class to clicked icon
        this.classList.add('active');
        
        // Implement category filtering logic here
        // This would depend on how categories are assigned to menu items
    });
});