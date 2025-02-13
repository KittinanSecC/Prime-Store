<?php
include("include.php");
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // ตรวจสอบว่าผู้ใช้ล็อกอินหรือยัง
    if (!isset($_SESSION['user_id'])) {
        $_SESSION['login_message'] = "กรุณาเข้าสู่ระบบก่อนเพิ่มสินค้าในตะกร้า";
        header("Location: login.php");
        exit();
    }

    // ดึง user_id จาก session
    $user_id = $_SESSION['user_id'];  // Now we use user_id directly from session
    $product_id = $_POST['product_id'];
    $quantity = 1;

    // ตรวจสอบไซส์
    if (!isset($_POST['product_size']) || empty(trim($_POST['product_size']))) {
        die("❌ Error: โปรดเลือกไซส์ก่อนเพิ่มลงตะกร้า");
    }

    $size = trim($_POST['product_size']);

    // ตรวจสอบราคาสินค้า
    if (!isset($_POST['product_price']) || !is_numeric($_POST['product_price'])) {
        die("❌ Error: ไม่พบราคาสินค้า");
    }

    $price = floatval($_POST['product_price']);

    // ตรวจสอบรูปภาพสินค้า
    if (!isset($_POST['product_image']) || empty($_POST['product_image'])) {
        die("❌ Error: ไม่พบรูปภาพสินค้า");
    }

    $image = $_POST['product_image']; // รับค่าชื่อไฟล์รูป

    // ✅ INSERT พร้อมเก็บชื่อรูป
    $stmt = $conn->prepare("INSERT INTO cart (user_id, product_id, size, quantity, price, image) VALUES (?, ?, ?, ?, ?, ?)");
    if (!$stmt) {
        die("❌ SQL Error: " . $conn->error);
    }

    $stmt->bind_param("iisids", $user_id, $product_id, $size, $quantity, $price, $image);

    if ($stmt->execute()) {
        echo "<script>alert('✅ เพิ่มสินค้าในตะกร้าสำเร็จ!'); window.location='cart.php';</script>";
    } else {
        die("❌ Error: ไม่สามารถเพิ่มสินค้าได้");
    }

    $stmt->close();
    $conn->close();
}
?>
