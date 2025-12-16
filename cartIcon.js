// Cart Icon Manager
// This script updates the cart icon to show when items are present

function updateCartDisplay() {
    fetch('cart_handler.php')
        .then(response => response.json())
        .then(data => {
            if (data.success && data.cart) {
                const itemCount = data.cart.items.length;
                const cartBtn = document.querySelector('.cart-btn');
                
                if (cartBtn) {
                    // Remove existing badge if any
                    const existingBadge = cartBtn.querySelector('.cart-badge');
                    if (existingBadge) {
                        existingBadge.remove();
                    }
                    
                    // Add badge if items exist
                    if (itemCount > 0) {
                        const badge = document.createElement('span');
                        badge.className = 'cart-badge';
                        badge.textContent = itemCount;
                        cartBtn.appendChild(badge);
                        
                        // Change cart icon text to show it has items
                        const btnText = cartBtn.querySelector('.btn-text');
                        if (btnText) {
                            btnText.textContent = `Cart (${itemCount})`;
                        }
                    } else {
                        // Reset to empty cart
                        const btnText = cartBtn.querySelector('.btn-text');
                        if (btnText) {
                            btnText.textContent = 'Cart';
                        }
                    }
                }
            }
        })
        .catch(error => {
            console.error('Error updating cart display:', error);
        });
}

// Update cart display on page load
document.addEventListener('DOMContentLoaded', updateCartDisplay);

document.head.appendChild(style);