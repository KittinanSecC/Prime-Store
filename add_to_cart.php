<?php
include("include.php");
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // ตรวจสอบว่าผู้ใช้ล็อกอินหรือยัง
    if (!isset($_SESSION['email'])) {
        $_SESSION['login_message'] = "กรุณาเข้าสู่ระบบก่อนเพิ่มสินค้าในตะกร้า";
        header("Location: login.php");
        exit();
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
        die("❌ Error: ไม่พบผู้ใช้");
    }

    $user_id = $user['user_id']; // Now you can safely use the user ID
    $product_id = $_POST['product_id'];
    $quantity = 1;

    if (!isset($_POST['product_size']) || empty(trim($_POST['product_size']))) {
        die("❌ Error: โปรดเลือกไซส์ก่อนเพิ่มลงตะกร้า");
    }

    $size = trim($_POST['product_size']);

    if (!isset($_POST['product_price']) || !is_numeric($_POST['product_price'])) {
        die("❌ Error: ไม่พบราคาสินค้า");
    }

    $price = floatval($_POST['product_price']);

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
