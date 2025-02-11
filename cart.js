document.addEventListener("DOMContentLoaded", function () {
    const updateCart = (cartId, currentQuantity, cartItem, subtotalElement, totalElement) => {
        fetch("update_cart.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded",
            },
            body: `cart_id=${cartId}&quantity=${currentQuantity}`,
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // อัปเดตราคาต่อชิ้นใหม่จากฐานข้อมูล
                const newPricePerItem = parseFloat(data.new_price_per_item); 
                const newTotalPrice = (currentQuantity * newPricePerItem).toFixed(2);

                cartItem.querySelector(".quantity-value").textContent = currentQuantity;
                cartItem.querySelector(".product-details p:nth-of-type(2)").textContent = `฿${parseFloat(newTotalPrice).toLocaleString("th-TH", { minimumFractionDigits: 2 })}`;

                // อัปเดตยอดรวม
                subtotalElement.textContent = parseFloat(data.new_subtotal).toLocaleString("th-TH", { minimumFractionDigits: 2 });
                totalElement.textContent = (parseFloat(data.new_subtotal) + 150).toLocaleString("th-TH", { minimumFractionDigits: 2 });
            } else {
                alert(data.message || "❌ อัปเดตสินค้าไม่สำเร็จ!");
            }
        })
        .catch(error => {
            console.error("Error:", error);
            alert("เกิดข้อผิดพลาดในการเชื่อมต่อกับเซิร์ฟเวอร์");
        });
    };

    document.querySelectorAll(".qty-btn").forEach((button) => {
        button.addEventListener("click", function () {
            const cartItem = this.closest(".cart-item");
            const cartId = this.getAttribute("data-cart-id");
            const quantitySpan = cartItem.querySelector(".quantity-value");
            let currentQuantity = parseInt(quantitySpan.textContent);
            const subtotalElement = document.querySelector(".subtotal");
            const totalElement = document.querySelector(".total");

            const action = this.textContent === "+" ? "increase" : "decrease";

            if (action === "increase") {
                currentQuantity++;
            } else if (action === "decrease" && currentQuantity > 1) {
                currentQuantity--;
            } else {
                return; // ไม่ทำอะไรหากไม่สามารถลดจำนวนลงได้
            }

            updateCart(cartId, currentQuantity, cartItem, subtotalElement, totalElement);
        });
    });
});

document.addEventListener("DOMContentLoaded", function () {
    const deleteCart = (cartId, cartItem, subtotalElement, totalElement) => {
        fetch("delete_cart.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded",
            },
            body: `cart_id=${cartId}`,
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // ลบสินค้าออกจาก DOM
                cartItem.remove();

                // อัปเดตยอดรวม
                subtotalElement.textContent = parseFloat(data.new_total).toLocaleString("th-TH", { minimumFractionDigits: 2 });
                totalElement.textContent = (parseFloat(data.new_total) + 150).toLocaleString("th-TH", { minimumFractionDigits: 2 });
            } else {
                alert(data.message || "❌ ลบสินค้าไม่สำเร็จ!");
            }
        })
        .catch(error => {
            console.error("Error:", error);
            alert("เกิดข้อผิดพลาดในการเชื่อมต่อกับเซิร์ฟเวอร์");
        });
    };

    document.querySelectorAll(".delete-btn").forEach((button) => {
        button.addEventListener("click", function () {
            const cartItem = this.closest(".cart-item");
            const cartId = this.getAttribute("data-cart-id");
            const subtotalElement = document.querySelector(".subtotal");
            const totalElement = document.querySelector(".total");

            if (confirm("คุณแน่ใจหรือไม่ว่าต้องการลบสินค้านี้?")) {
                deleteCart(cartId, cartItem, subtotalElement, totalElement);
            }
        });
    });
});
document.addEventListener("DOMContentLoaded", function () {
    const deleteButtons = document.querySelectorAll(".delete-btn");

    deleteButtons.forEach(button => {
        button.addEventListener("click", function () {
            const cartId = this.dataset.cartId;
            const cartItem = this.closest(".cart-item");

            fetch("delete_cart.php", {
                method: "POST",
                headers: { "Content-Type": "application/x-www-form-urlencoded" },
                body: `cart_id=${cartId}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    cartItem.remove(); // ลบสินค้าออกจาก DOM
                    document.querySelector(".subtotal").textContent = data.new_total;
                    document.querySelector(".total").textContent = data.new_total + 150;

                    // เช็คว่าตะกร้าว่างหรือไม่
                    if (document.querySelectorAll(".cart-item").length === 0) {
                        document.querySelector(".cart-container").innerHTML = `
                            <h2>ตะกร้าสินค้า</h2>
                            <p class="empty-cart">🛒 ตะกร้าของคุณว่างเปล่า</p>
                        `;
                    }
                }
            });
        });
    });
});