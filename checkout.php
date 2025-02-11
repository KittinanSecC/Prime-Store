<?php
session_start();
include("include.php"); // เชื่อมต่อฐานข้อมูล
include("structure.php"); // เชื่อมต่อฐานข้อมูล


$user_id = $_SESSION['user_id'] ?? 1; // ใช้ session ถ้ามี ไม่งั้นใช้ user_id 1 (ชั่วคราว)

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
    <title>Checkout</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">

    <style>

    </style>
</head>

<body>
    <?php
    renderHeader($conn)
    ?>
    <div class="container mt-5">
        <h2>สรุปคำสั่งซื้อ</h2>
        <form method="post">
            <table class="table">
                <thead>
                    <tr>
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
                        <tr>
                            <td><?php echo htmlspecialchars($item['pro_name']); ?></td>
                            <td><img src="myfile/<?php echo htmlspecialchars($item['pro_image']); ?>" width="80"></td>
                            <td><?php echo htmlspecialchars($item['size'] ?? '-'); ?></td>
                            <td><?php echo htmlspecialchars($item['quantity']); ?></td>
                            <td>฿<?php echo number_format($item['price'], 2); ?></td>
                        </tr>
                        <?php $total += ($item['price'] * $item['quantity']); ?>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <h4>รวมทั้งหมด: ฿<?php echo number_format($total, 2); ?></h4>
            <input type="hidden" name="total_price" value="<?php echo $total; ?>">

            <h3>ที่อยู่จัดส่ง</h3>
            <input type="text" name="name" class="form-control" placeholder="ชื่อ-นามสกุล" required>
            <textarea name="address" class="form-control mt-2" placeholder="ที่อยู่" required></textarea>

            <h3 class="mt-3">ช่องทางชำระเงิน</h3>
            <select name="payment_method" class="form-control" required>
                <option value="โอนผ่านธนาคาร">โอนผ่านธนาคาร</option>
                <option value="พร้อมเพย์">พร้อมเพย์</option>
                <option value="บัตรเครดิต">บัตรเครดิต</option>
            </select>

            <button type="submit" class="button mt-3">ยืนยันการชำระเงิน</button>
        </form>
    </div>
</body>

</html>