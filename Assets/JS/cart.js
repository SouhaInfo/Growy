document.addEventListener('DOMContentLoaded', () => {
    // Function to update the total price
    function updateTotalPrice() {
        let total = 0;
        const prices = document.querySelectorAll('.cart-price');
        prices.forEach(price => {
            total += parseFloat(price.textContent.replace(' DZD', ''));
        });
        document.querySelector('.total-price table td:last-child').textContent = total.toFixed(2) + ' DZD';
    }

    // Function to update the cart item count in the header
    function updateCartCount() {
        // Get the number of items currently in the cart
        const itemCount = document.querySelectorAll('.cart-info').length;
        const cartCountElement = document.querySelector('.cartt-btn::after');
        
        // Dynamically update the cart icon's badge with the item count
        const cartLink = document.querySelector('.cartt-btn');
        if (cartLink) {
            // If itemCount is 0, hide the notification badge, otherwise, show it with updated count
            if (itemCount > 0) {
                cartLink.setAttribute('data-count', itemCount); // You could use data-* attribute for the count
            } else {
                cartLink.removeAttribute('data-count');
            }
        }
    }

    // Add event listeners to all remove links
    const removeLinks = document.querySelectorAll('.remove-item');
    removeLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault(); // Prevent default link behavior

            // Find the closest row to the clicked link
            const row = this.closest('tr');
            row.remove(); // Remove the row from the table

            // Update the total price after the row is removed
            updateTotalPrice();

            // Update the cart item count in the header
            updateCartCount();
        });
    });

    // Initial total price calculation and cart count update
    updateTotalPrice();
    updateCartCount();
});
