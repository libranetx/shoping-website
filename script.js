function showSidebar() {
    document.querySelector('.sidebar').classList.add('show');
}

function hideSidebar() {
    document.querySelector('.sidebar').classList.remove('show');
}

function toggleMobileDropdown() {
    const dropdown = document.querySelector('.dropdown-mobile');
    dropdown.style.display = dropdown.style.display === 'block' ? 'none' : 'block';
}


//for cart

document.addEventListener("DOMContentLoaded", () => {
    const cartIcon = document.querySelector(".cart");
    const cartCount = document.querySelector(".cart-count");
    const cartPage = document.querySelector(".cart-page");
    const cartItemsList = document.querySelector(".cart-items");
    const closeCartButton = document.querySelector(".close-cart");
    const purchaseCartButton = document.querySelector(".purchase-cart");
    const purchaseForm = document.querySelector(".purchase-form");
    const totalAmountSpan = document.querySelector(".total-amount");
    const form = document.getElementById("purchase-form");

    let cart = [];

    // Update cart count
    function updateCartCount() {
        const totalItems = cart.reduce((total, item) => total + item.quantity, 0);
        cartCount.textContent = totalItems;
    }

    // Render cart items
    function renderCartItems() {
        cartItemsList.innerHTML = ""; // Clear previous items
        cart.forEach(item => {
            const li = document.createElement("li");
            li.innerHTML = `
                <span>
                    <img src="${item.image}" alt="${item.name}" width="30">
                    ${item.name} - ${item.price} ETB 
                    <strong>(${item.quantity})</strong>
                </span>
                <button class="remove-item" data-id="${item.id}">Remove</button>
            `;
            cartItemsList.appendChild(li);
        });
    }

    // Calculate total amount
    function calculateTotalAmount() {
        return cart.reduce((total, item) => total + item.price * item.quantity, 0);
    }

    // Add product to cart
    document.querySelectorAll(".add-to-cart").forEach(button => {
        button.addEventListener("click", (e) => {
            const product = e.target.closest(".product");
            const productData = {
                id: product.dataset.id,
                name: product.dataset.name,
                price: parseFloat(product.dataset.price),
                image: product.dataset.image,
                quantity: 1
            };

            // Check if item is already in cart
            const existingItem = cart.find(item => item.id === productData.id);
            if (existingItem) {
                existingItem.quantity += 1; // Increment quantity
            } else {
                cart.push(productData); // Add new item
            }

            updateCartCount();
        });
    });

    // Show cart page
    cartIcon.addEventListener("click", () => {
        renderCartItems();
        cartPage.classList.remove("hidden");
        window.scrollTo({
            top: 0,
            behavior: "smooth" // Smooth scrolling animation
        });
    });

    // Close cart page
    closeCartButton.addEventListener("click", () => {
        cartPage.classList.add("hidden");
        purchaseForm.classList.add("hidden");
    });

    // Show purchase form
    purchaseCartButton.addEventListener("click", () => {
        const totalAmount = calculateTotalAmount();

        totalAmountSpan.textContent = totalAmount.toFixed(2);

        purchaseCartButton.style.display = "inline-block";


    });

    // Handle form submission
    form.addEventListener("submit", (e) => {
        e.preventDefault();


        alert(`THANK YOU FOR CHOOSING MARAKI\nPurchase successful!\nTotal: ${calculateTotalAmount().toFixed(2)} ETB`);
        // Reset cart and form
        cart = [];
        updateCartCount();
        renderCartItems();
        form.reset();
        purchaseForm.classList.add("hidden");
        cartPage.classList.add("hidden");
    });

    // Remove item from cart
    cartItemsList.addEventListener("click", (e) => {
        if (e.target.classList.contains("remove-item")) {
            const itemId = e.target.dataset.id;
            cart = cart.filter(item => item.id !== itemId);
            updateCartCount();
            renderCartItems();
        }
    });

});
