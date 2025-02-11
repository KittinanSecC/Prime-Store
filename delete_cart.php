<?php
session_start();
include("include.php");

if (!isset($_SESSION['email'])) {
    echo json_encode(["success" => false, "error" => "Unauthorized"]);
    exit;
}

$cart_id = $_POST['cart_id'];

// ลบสินค้าจากตะกร้า
$sql = "DELETE FROM cart WHERE cart_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $cart_id);
$stmt->execute();

// ตรวจสอบว่ายังมีสินค้าคงเหลือหรือไม่
$total_sql = "SELECT SUM(quantity * price) AS total FROM cart WHERE user_id = ?";
$stmt = $conn->prepare($total_sql);
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$total_result = $stmt->get_result();
$total = $total_result->fetch_assoc()['total'] ?? 0;

echo json_encode(["success" => true, "new_total" => number_format($total, 2)]);
?>
