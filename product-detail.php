<?php
session_start();
include("include.php"); // เชื่อมต่อฐานข้อมูล
include("structure.php");

// ตรวจสอบค่า ID ที่รับมา
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("<h2>❌ ไม่พบสินค้า</h2>");
}
$id = intval($_GET['id']);

// ดึงข้อมูลสินค้าจากฐานข้อมูล
$sql = "SELECT Name, Price, Gender, FilesName, FilesName2, FilesName3, FilesName4, Description FROM product WHERE ID = ?";
$stmt = $conn->prepare($sql);
if (!$stmt) {
    die("เกิดข้อผิดพลาดใน SQL: " . $conn->error);
}
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$product = $result->fetch_assoc();
if (!$product) {
    die("<h2>❌ ไม่พบสินค้า</h2>");
}


// จัดการรูปภาพ
$images = array_filter([$product['FilesName'], $product['FilesName2'], $product['FilesName3'], $product['FilesName4']]);

// แปลงค่าเพศเป็นข้อความภาษาไทย
$gender_text = ($product['Gender'] === 'Men') ? "รองเท้าผู้ชาย" : "รองเท้าผู้หญิง";
?>

<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($product['Name']) ?></title>
    <link rel="stylesheet" href="style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/logo/Prime2.png" rel="icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        .main-image-container {
            background-color: #f5f5f5;
            padding: 20px;
            border-radius: 10px;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .container {
            max-width: 1000px;
        }

        .main-image {
            max-width: 100%;
            height: auto;
        }

        .thumb-gallery img {
            width: 80px;
            height: 80px;
            object-fit: cover;
            background-color: #f5f5f5;
            padding: 5px;
            border-radius: 8px;
            transition: 0.3s;
            cursor: pointer;
        }

        .thumb-gallery img:hover,
        .thumb-gallery img.active {
            filter: brightness(0.8);
        }

        .description {


            border-radius: 10px;
            margin-top: 20px;
            font-size: 1rem;
            color: black;
        }

        .cart-btn {
            background-color: black;
            color: white;
            font-size: 1.1rem;
            border-radius: 8px;
            padding: 10px;
            border: none;
        }

        .cart-btn:hover {
            background-color: #000000;
        }

        .wishlist-btn {
            background-color: white;
            color: black;
            font-size: 1.1rem;
            border-radius: 8px;
            padding: 10px;
            border: 2px solid #ddd;
        }

        .wishlist-btn:hover {
            border-color: black;
        }

        .cart-btn i,
        .wishlist-btn i {
            margin-right: 5px;
        }

        .size-btn {
            border-color: black !important;
            /* กรอบดำ */
            background-color: white !important;
            /* พื้นหลังขาว */
            color: black !important;
            /* ตัวหนังสือดำ */

            transition: 0.3s;
        }

        .size-btn:hover {
            background-color: white !important;
            /* ไม่มีสีเทาเมื่อ hover */

        }

        .size-btn.active {
            background-color: black !important;
            /* เมื่อกด เปลี่ยนเป็นดำ */
            color: white !important;
            /* ตัวหนังสือขาว */
        }
    </style>

</head>
<?php
renderHeader($conn);
?>

<body>
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-6">
                <div class="gallery">
                    <?php if (!empty($images)) : ?>
                        <div class="main-image-container">
                            <img id="mainImage" src="myfile/<?= htmlspecialchars($images[0]) ?>" alt="<?= htmlspecialchars($product['Name']) ?>" class="img-fluid main-image">
                        </div>
                        <div class="thumb-gallery mt-2 d-flex">
                            <?php foreach ($images as $img) : ?>
                                <img src="myfile/<?= htmlspecialchars($img) ?>" class="img-thumbnail mx-1 thumb-img" width="80">
                            <?php endforeach; ?>
                        </div>
                    <?php else : ?>
                        <p>ไม่มีภาพสินค้า</p>
                    <?php endif; ?>
                </div>
            </div>

            <div class="col-md-6">
                <h1><?= htmlspecialchars($product['Name']) ?></h1>
                <p class="text-muted"> <?= $gender_text ?> </p>
                <h3 class="text-danger">฿<?= number_format($product['Price']) ?></h3>

                <div class="sizes my-3">
                    <label>เลือกไซส์:</label>
                    <div class="d-flex flex-wrap">
                        <?php foreach (["US 6", "US 6.5", "US 7", "US 7.5", "US 8", "US 8.5", "US 9", "US 9.5", "US 10", "US 10.5", "US 11", "US 12"] as $size) : ?>
                            <button class="btn size-btn m-1" data-size="<?= $size ?>"> <?= $size ?> </button>
                        <?php endforeach; ?>
                    </div>
                </div>

                <form action="add_to_cart.php" method="POST">
                    <input type="hidden" name="product_id" value="<?= $id ?>">
                    <input type="hidden" name="product_name" value="<?= htmlspecialchars($product['Name']) ?>">
                    <input type="hidden" name="product_price" value="<?= $product['Price'] ?>">
                    <input type="hidden" id="selected_size" name="product_size" value="">
                    <input type="hidden" name="product_image" value="myfile/<?= htmlspecialchars($product['FilesName']) ?>">

                    <button type="submit" class="btn cart-btn w-100 my-2 btn-dark">เพิ่มในตะกร้า</button>
                </form>
                <script>
                    document.querySelectorAll('.size-btn').forEach(button => {
                        button.addEventListener('click', function() {
                            document.querySelectorAll('.size-btn').forEach(btn => btn.classList.remove('active'));
                            this.classList.add('active');
                            document.getElementById('selected_size').value = this.getAttribute('data-size');
                        });
                    });
                </script>
                <button class="btn wishlist-btn w-100 btn-light" style="border: 1px solid;">
                    รายการโปรด <i class="far fa-heart"></i>
                </button>
                <div class="description">
                    <h4>รายละเอียดสินค้า</h4>
                    <p><?= nl2br(htmlspecialchars($product['Description'])) ?></p>
                </div>
            </div>
       

                    <?php renderFooter()?>

    <script>
        document.querySelectorAll('.thumb-img').forEach(img => {
            img.addEventListener('click', function() {
                document.getElementById('mainImage').src = this.src;
                document.querySelectorAll('.thumb-img').forEach(img => img.classList.remove('active'));
                this.classList.add('active');
            });
        });
    </script>
    <script>
        document.querySelectorAll('.size-btn').forEach(button => {
            button.addEventListener('click', function() {
                // ลบ active class จากปุ่มอื่น ๆ
                document.querySelectorAll('.size-btn').forEach(btn => btn.classList.remove('active'));

                // เพิ่ม active class ให้ปุ่มที่เลือก
                this.classList.add('active');

                // อัปเดตค่า input hidden
                document.getElementById('selected_size').value = this.getAttribute('data-size');
            });
        });

        // ตรวจสอบก่อนส่งฟอร์ม ว่าได้เลือกไซส์แล้วหรือไม่
        document.querySelector("form").addEventListener("submit", function(event) {
            let selectedSize = document.getElementById("selected_size").value;
            if (!selectedSize) {
                alert("❌ โปรดเลือกไซส์ก่อนเพิ่มลงตะกร้า!");
                event.preventDefault(); // ป้องกันการ submit
            }
        });
    </script>



</body>

</html>