<?php
session_start();
include 'include.php'; // เชื่อมต่อฐานข้อมูล
include("structure.php");



// รับค่า order_id จาก URL
$order_id = $_GET['order_id'] ?? 0;
if ($order_id == 0) {
    die("<h2>ไม่พบคำสั่งซื้อ</h2>");
}

// ดึงข้อมูลคำสั่งซื้อจากฐานข้อมูล
$order_sql = "SELECT * FROM orders WHERE order_id = ?";
$order_stmt = $conn->prepare($order_sql);

if ($order_stmt === false) {
    die("Error preparing order SQL: " . $conn->error);
}

$order_stmt->bind_param("i", $order_id);
$order_stmt->execute();
$order_result = $order_stmt->get_result();
if ($order_result === false) {
    die("Error fetching order data: " . $conn->error);
}
$order = $order_result->fetch_assoc();

if (!$order) {
    die("<h2>ไม่พบคำสั่งซื้อ</h2>");
}

// ดึงข้อมูลรายการสินค้าในออเดอร์
$item_sql = "SELECT oi.*, p.Name AS product_name, p.FilesName AS product_image 
             FROM order_items oi 
             JOIN product p ON oi.product_id = p.ID 
             WHERE oi.order_id = ?";
$item_stmt = $conn->prepare($item_sql);

if ($item_stmt === false) {
    die("Error preparing item SQL: " . $conn->error);
}

$item_stmt->bind_param("i", $order_id);
$item_stmt->execute();
$item_result = $item_stmt->get_result();
if ($item_result === false) {
    die("Error fetching item data: " . $conn->error);
}
$items = $item_result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>คำสั่งซื้อสำเร็จ</title>
    
</head>
<body>
<?php
    renderHeader($conn)
    ?>
    <div class="container">
        <h1>✅ คำสั่งซื้อสำเร็จ!</h1>
        <p>ขอบคุณที่สั่งซื้อกับเรา เลขคำสั่งซื้อของคุณคือ <strong>#<?php echo $order_id; ?></strong></p>
        <p><strong>ชื่อผู้รับ:</strong> <?php echo htmlspecialchars($order['name']); ?></p>
        <p><strong>ที่อยู่จัดส่ง:</strong> <?php echo htmlspecialchars($order['shipping_address']); ?></p>
        <p><strong>ช่องทางชำระเงิน:</strong> <?php echo htmlspecialchars($order['payment_method']); ?></p>
        <p><strong>วันที่สั่งซื้อ:</strong> <?php echo $order['created_at']; ?></p>
        
        <h2>รายการสินค้า</h2>
        <table>
            <tr>
                <th>สินค้า</th>
                <th>จำนวน</th>
                <th>ราคา</th>
            </tr>
            <?php foreach ($items as $item): ?>
            <tr>
                <td><img src="myfile/<?php echo $item['product_image']; ?>" width="50"> <?php echo htmlspecialchars($item['product_name']); ?></td>
                <td><?php echo $item['quantity']; ?></td>
                <td><?php echo number_format($item['price'] * $item['quantity'], 2); ?> ฿</td>
            </tr>
            <?php endforeach; ?>
        </table>
        <h3>ยอดรวม: <?php echo number_format($order['total_price'], 2); ?> ฿</h3>
        
        <a href="main.php" class="btn">🏠 กลับหน้าแรก</a>
    </div>
</body>
</html>
