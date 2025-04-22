// Cart state management
let cartItems = [];
let total = 0;

// Show cart only on penjualan page
document.addEventListener('DOMContentLoaded', function() {
    // Check if we're on the penjualan page by looking for menu-grid
    const isPenjualanPage = document.querySelector('.menu-grid') !== null;
    
    // Get all cart sections
    const cartSections = document.querySelectorAll('.cart-section');
    
    // Only show the cart in the penjualan page content area
    cartSections.forEach((cartSection, index) => {
        // Only show the first cart on penjualan page
        if (isPenjualanPage && index === 0) {
            cartSection.style.display = 'block';
        } else {
            cartSection.style.display = 'none';
        }
    });
    
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
        total_harga: total,
        uang_dibayar: total, // Assuming direct payment without change
        kembalian: 0,
        items: cartItems.map(item => ({
            kode: item.kodeBarang,
            nama: item.namaBarang,
            harga: item.harga,
            diskon: item.diskon,
            jumlah: item.quantity,
            subtotal: item.subtotal
        }))
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
            // Show success message
            alert('Transaction completed successfully!');
            
            // Clear cart
            cartItems = [];
            updateCart();
            
            // Optionally show receipt/struk
            showReceipt(transactionData);
        } else {
            alert('Error processing transaction. Please try again.');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error processing transaction. Please try again.');
    });
});

// Show receipt after successful transaction
function showReceipt(transactionData) {
    // Set receipt data
    document.getElementById('struk-tanggal').textContent = new Date().toLocaleString('id-ID');
    document.getElementById('struk-total').textContent = `Rp ${transactionData.total_harga.toLocaleString()}`;
    document.getElementById('struk-bayar').textContent = `Rp ${transactionData.uang_dibayar.toLocaleString()}`;
    document.getElementById('struk-kembali').textContent = `Rp ${transactionData.kembalian.toLocaleString()}`;
    
    // Generate receipt items
    let strukItems = '';
    transactionData.items.forEach(item => {
        strukItems += `
            <tr>
                <td colspan="2">${item.nama}</td>
            </tr>
            <tr>
                <td>${item.jumlah} x Rp ${(item.harga - (item.harga * item.diskon / 100)).toLocaleString()}</td>
                <td align="right">Rp ${item.subtotal.toLocaleString()}</td>
            </tr>
        `;
    });
    document.getElementById('struk-items').innerHTML = strukItems;
    
    // Show receipt modal
    new bootstrap.Modal(document.getElementById('modalStruk')).show();
}

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