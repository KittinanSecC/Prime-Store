<!DOCTYPE html>
<html lang="th">
<?php
session_start();
include("include.php");
include("structure.php");
?>

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>ส่งข้อมูลสินค้าอย่างเป็นทางการของ Prime Sneakers Store</title>
	<link href="assets/logo/Prime2.png" rel="icon">
	<link rel="stylesheet" href="style2.css">

	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">

<body>
	<!-- Top Bar -->
	<div class="top-bar">
		<a href="#">ค้นหาร้าน</a>
		<a href="#">ความช่วยเหลือ</a>
		<a href="#">เข้าร่วมกับเรา</a>
		<a href="#">ลงชื่อเข้าใช้</a>
	</div>

	<!-- Header -->
	<header>
		<div class="logo">
			<a href="main.php" class="logo">
				<img src="assets/logo/Prime2.png" alt="Logo">
			</a>
		</div>
		<nav>
			<ul>
				<li><a href="main.php">หน้าแรก</a></li>
				<li><a href="upload3.php">สินค้า</a></li>
				<li><a href="Men.php">ผู้ชาย</a></li>
				<li><a href="#">ผู้หญิง</a></li>
				<li><a href="product.php">เพิ่มสินค้า</a></li>
			</ul>
		</nav>
		<div class="search-bar">
			<button id="search-button"><i class="fa fa-search"></i></button>
			<input type="text" placeholder="ค้นหา" id="search-input">

		</div>

	</header>
	
	
	<?php
	// 1️⃣ เชื่อมต่อฐานข้อมูล
	$servername = "localhost";
	$username = "root";  // หรือที่ตั้งค่าไว้
	$password = "";       // ใส่รหัสผ่านถ้ามี
	$dbname = "prime";

	$conn = new mysqli($servername, $username, $password, $dbname);
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}

	// 2️⃣ รับค่าจากฟอร์ม
	$name = $_POST['txtName'];
	$price = $_POST['Price'];
	$category = $_POST['Category'];
	$gender = $_POST['Gender'];

	// 3️⃣ กำหนดโฟลเดอร์อัปโหลด
	$uploadDir = "myfile/";
	if (!is_dir($uploadDir)) {
		mkdir($uploadDir, 0777, true); // สร้างโฟลเดอร์ถ้ายังไม่มี
	}

	// 4️⃣ อัปโหลดไฟล์รูป
	$files = ['filUpload1', 'filUpload2', 'filUpload3', 'filUpload4'];
	$filePaths = [];

	foreach ($files as $fileKey) {
		if ($_FILES[$fileKey]['error'] === 0) {
			$fileName = basename($_FILES[$fileKey]['name']);
			$targetPath = $uploadDir . $fileName;
			if (move_uploaded_file($_FILES[$fileKey]['tmp_name'], $targetPath)) {
				$filePaths[$fileKey] = $fileName;
			} else {
				$filePaths[$fileKey] = NULL;
			}
		} else {
			$filePaths[$fileKey] = NULL;
		}
	}

	// 5️⃣ SQL บันทึกข้อมูลลงฐานข้อมูล
	$sql = "INSERT INTO product (Name, Price, Category, Gender, FilesName, FilesName2, FilesName3, FilesName4)
        VALUES ('$name', '$price', '$category', '$gender', 
                '{$filePaths['filUpload1']}', '{$filePaths['filUpload2']}', '{$filePaths['filUpload3']}', '{$filePaths['filUpload4']}')";

	if ($conn->query($sql) === TRUE) {
		echo "เพิ่มข้อมูลสำเร็จ!";
	} else {
		echo "Error: " . $sql . "<br>" . $conn->error; 
	}

	$conn->close();
	?><br>

	<a href="Upload3.php">View files</a>
	<!-- Footer -->
	<footer>
		<div class="footer-container">
			<div class="footer-column">
				<h4>เกี่ยวกับเรา</h4>
				<p>Lorem ipsum dolor sit amet,</p>
			</div>
			<div class="footer-column">
				<h4>บริการลูกค้า</h4>
				<ul>
					<li><a href="#">คำถามที่พบบ่อย</a></li>
					<li><a href="#">การคืนสินค้า</a></li>
					<li><a href="#">การจัดส่ง</a></li>
					<li><a href="#">ติดต่อเรา</a></li>
				</ul>
			</div>
			<div class="footer-column">
				<h4>ติดตามเรา</h4>
				<div class="social-links">
					<a href="#" class="social-icon"><i class="fa-brands fa-facebook"></i></a>
					<a href="#" class="social-icon"><i class="fa-brands fa-instagram"></i></a>
					<a href="#" class="social-icon"><i class="fa-brands fa-x"></i></a> <!-- สำหรับ X (Formerly Twitter) -->
					<a href="#" class="social-icon"><i class="fa-brands fa-youtube"></i></a>
				</div>
			</div>
		</div>
		<div class="footer-bottom">
			<p>&copy; 2025 Lorem Store. All Rights Reserved.</p>
		</div>
	</footer>
</body>

</html>