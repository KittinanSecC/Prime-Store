<?php
session_start();
include("include.php"); // เชื่อมต่อฐานข้อมูล
include("structure.php");
// ตรวจสอบว่าผู้ใช้ล็อกอินหรือยัง
if (!isset($_SESSION['email'])) {
    die("You must be logged in to view this page.");
}

$loggedInEmail = $_SESSION['email'];

// ดึง user_id จาก email
$sql = "SELECT user_id FROM users WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $loggedInEmail);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user) {
    die("User not found.");
}

$user_id = $user['user_id'];

// ดึงข้อมูลสินค้าจากตะกร้า
$sql = "SELECT c.cart_id, p.Name, c.size, c.quantity, c.price, c.image, 
               (c.quantity * c.price) AS total_price 
        FROM cart c
        JOIN product p ON c.product_id = p.ID 
        WHERE c.user_id = ?";

$stmt = $conn->prepare($sql);
if (!$stmt) {
    die("❌ SQL Error: " . $conn->error);
}

$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$total = 0;
?>


<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ตะกร้า Prime Store TH</title>
    <link href="assets/logo/Prime2.png" rel="icon">
    <link rel="stylesheet" href="styles6.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
</head>

<body>
    <?php renderHeader($conn) ?>
    <div class="cart-container">
        <h2>ตะกร้าสินค้า</h2>

        <?php if ($result->num_rows > 0): ?>
            <div class="cart-items">
                <?php while ($row = $result->fetch_assoc()): ?>
                    <div class="cart-item" data-cart-id="<?= $row['cart_id'] ?>">
                        <img src="http://localhost/PHP/<?= htmlspecialchars($row['image']) ?>" width="100">
                        <div class="product-details">
                            <h3><?= htmlspecialchars($row['Name']) ?></h3>
                            <p>ไซส์: <?= htmlspecialchars($row['size']) ?></p>
                            <p>฿<?= number_format($row['price']) ?></p>
                            <div class="quantity-controls">
                                <button class="qty-btn" data-cart-id="<?= $row['cart_id'] ?>">-</button>
                                <span class="quantity-value"><?= $row['quantity'] ?></span>
                                <button class="qty-btn" data-cart-id="<?= $row['cart_id'] ?>">+</button>
                            </div>
                        </div>
                        <div class="cart-actions">
                            <button class="delete-btn" data-cart-id="<?= $row['cart_id'] ?>">🗑</button>
                        </div>
                    </div>
                    <?php $total += $row['total_price']; ?>
                <?php endwhile; ?>
            </div>

            <div class="cart-summary">
                <h3>สรุป</h3>
                <p>ยอดรวมย่อย: ฿<span class="subtotal"><?= number_format($total, 2) ?></span></p>
                <p>ค่าธรรมเนียมและค่าจัดส่ง: ฿150.00</p>
                <hr>
                <p><strong>ยอดรวม: ฿<span class="total"><?= number_format($total + 150, 2) ?></span></strong></p>
                <form action="checkout.php" method="POST">
                    <button type="submit" class="checkout-btn">สั่งซื้อเลย</button>
                </form>

                <button class="paypal-btn">
                    <a href=upload3.php >กลับหน้าสินค้า</a></button>
            </div>
            <script src="cart.js"></script>
        <?php else: ?>
            <p class="empty-cart">🛒 ตะกร้าของคุณว่างเปล่า</p>
        <?php endif; ?>
    </div>

    <?php renderFooter(); ?>
</body>

</html>