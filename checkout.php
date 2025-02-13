<?php
session_start();
include("include.php"); // เชื่อมต่อฐานข้อมูล
include("structure.php"); // เชื่อมต่อฐานข้อมูล

// ตรวจสอบว่าผู้ใช้เข้าสู่ระบบหรือไม่
if (!isset($_SESSION['user_id'])) {
    die("You must be logged in to view this page.");
}

$user_id = $_SESSION['user_id']; // ใช้ user_id จาก session โดยตรง

// ตรวจสอบการเชื่อมต่อกับฐานข้อมูล
if ($conn->connect_error) {
    die("Failed to connect to DB: " . $conn->connect_error);
}

// ดึงข้อมูลของผู้ใช้ที่เข้าสู่ระบบจากฐานข้อมูล
$sql = "SELECT * FROM users WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id); // ใช้ user_id จาก session
$stmt->execute();
$result = $stmt->get_result();

// เช็คว่ามีข้อมูลผู้ใช้หรือไม่
if ($user = $result->fetch_assoc()) {
    // ถ้ามีข้อมูลผู้ใช้ ให้เก็บไว้ในตัวแปร $user
    // ตอนนี้คุณสามารถใช้ข้อมูลของผู้ใช้ที่ดึงมาในส่วนอื่นๆ ได้
} else {
    die("User not found.");
}

// ดึงข้อมูลสินค้าจากตะกร้า พร้อมชื่อและรูปสินค้า
$sql = "SELECT cart.*, product.Name AS pro_name, product.FilesName AS pro_image
FROM cart
JOIN product ON cart.product_id = product.ID
WHERE cart.user_id = ?";

$stmt = $conn->prepare($sql);
if (!$stmt) {
    die("SQL Error: " . $conn->error); // ตรวจสอบ Error SQL
}

$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$cart_items = [];
while ($row = $result->fetch_assoc()) {
    $cart_items[] = $row;
}

// เช็คว่ามีการกด submit ไหม
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!empty($_POST['name']) && !empty($_POST['address']) && !empty($_POST['payment_method']) && isset($_POST['total_price'])) {
        $name = $conn->real_escape_string($_POST['name']);
        $address = $conn->real_escape_string($_POST['address']);
        $payment_method = $conn->real_escape_string($_POST['payment_method']);
        $total_price = floatval($_POST['total_price']);

        // บันทึกลงตาราง orders
        $order_sql = "INSERT INTO orders (user_id, total_price, shipping_address, payment_method, name)
VALUES (?, ?, ?, ?, ?)";

        $order_stmt = $conn->prepare($order_sql);
        if (!$order_stmt) {
            die("Order Prepare Failed: " . $conn->error);
        }

        $shipping_address = $address; // แก้ไขให้ใช้ตัวแปรที่ถูกต้อง
        $order_stmt->bind_param("sdsss", $user_id, $total_price, $shipping_address, $payment_method, $name);
        if ($order_stmt->execute()) {
            $order_id = $conn->insert_id;

            // ย้ายข้อมูลจาก cart ไป order_items
            $item_sql = "INSERT INTO order_items (order_id, product_id, quantity, price, size) VALUES (?, ?, ?, ?, ?)";
            $item_stmt = $conn->prepare($item_sql);
            if (!$item_stmt) {
                die("Order Items Prepare Failed: " . $conn->error);
            }

            foreach ($cart_items as $item) {
                $size = $item['size'] ?? ''; // กัน error หาก size เป็น null
                $item_stmt->bind_param("iiids", $order_id, $item['product_id'], $item['quantity'], $item['price'], $size);
                $item_stmt->execute();
            }

            // ล้าง cart
            $delete_cart_sql = "DELETE FROM cart WHERE user_id = ?";
            $delete_cart_stmt = $conn->prepare($delete_cart_sql);
            if (!$delete_cart_stmt) {
                die("Delete Cart Prepare Failed: " . $conn->error);
            }

            $delete_cart_stmt->bind_param("i", $user_id);
            $delete_cart_stmt->execute();

            // Redirect ไปหน้าสำเร็จ
            header("Location: success.php?order_id=$order_id");
            exit;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>คำสั่งซื้อ</title>
    <link href="assets/logo/Prime2.png" rel="icon">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">

    <style>
        body {
            background-color: #f8f9fa;
        }
        .checkout-container {
            max-width: 800px;
            margin: auto;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
        .product-table img {
            border-radius: 5px;
        }
        .form-control, .btn {
            border-radius: 8px;
        }
    </style>
</head>

<body>
    <?php
    renderHeader($conn)
    ?>
    <div class="container mt-5 checkout-container">
    <h2 class="text-center">สรุปคำสั่งซื้อ</h2>
        <form method="post">
        <table class="table product-table mt-3">
                <thead>
                <tr class="table-dark text-center">
                        <th>สินค้า</th>
                        <th>รูป</th>
                        <th>ไซส์</th>
                        <th>จำนวน</th>
                        <th>ราคา</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $total = 0; ?>
                    <?php foreach ($cart_items as $item): ?>
                        <tr class="text-center">
                            <td><?php echo htmlspecialchars($item['pro_name']); ?></td>
                            <td><img src="myfile/<?php echo htmlspecialchars($item['pro_image']); ?>" width="80"></td>
                            <td><?php echo htmlspecialchars($item['size'] ?? '-'); ?></td>
                            <td><?php echo htmlspecialchars($item['quantity']); ?></td>
                            <td>฿<?php echo number_format($item['price'], 2); ?></td>
                        </tr>
                        <?php $total += ($item['price'] * $item['quantity']); ?>
                    <?php endforeach; ?>
                
                    <tr class="fw-bold text-end">
                    <td colspan="5">รวมทั้งหมด: ฿<?php echo number_format($total, 2); ?></td>
                    
            <input type="hidden" name="total_price" value="<?php echo $total; ?>">
            </tr>
            </tbody>
        </table>

        <h4 class="mt-4">ที่อยู่จัดส่ง</h4>
            <input type="text" class="form-control mb-2" id="name" name="name" placeholder="ชื่อ-นามสกุล" value="<?= htmlspecialchars($user['firstName'] . ' ' . $user['lastName']) ?>" required>
            <textarea name="address" class="form-control" placeholder="ที่อยู่" required></textarea>

            <h4 class="mt-3">ช่องทางชำระเงิน</h4>
            <select name="payment_method" class="form-control" required>
                <option value="โอนผ่านธนาคาร">โอนผ่านธนาคาร</option>
                <option value="พร้อมเพย์">พร้อมเพย์</option>
                <option value="บัตรเครดิต">บัตรเครดิต</option>
                <option value="เก็บเงินปลายทาง">เก็บเงินปลายทาง</option>
            </select>

            <div class="d-flex justify-content-center mt-4">
    <button type="submit" class="btn btn-dark">ยืนยันการชำระเงิน</button>
</div>
        </form>
    </div>
    <?php
    renderFooter();
    ?>
</body>

</html>