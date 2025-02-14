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
    <title>สินค้า Prime Sneakers Store</title>
    <link href="assets/logo/Prime2.png" rel="icon">
    <link rel="stylesheet" href="style2.css">
    <link rel="stylesheet" href="style4.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <!-- script ---->
    <style>
        /* Filter and Sort */
        .filter-sort {
            margin: 20px;
            text-align: center;
        }

        #sort-options {
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
    </style>
</head>

<body>
    <?php
    renderHeader($conn)
    ?>
    <?php
    include("include.php");
    $filter = isset($_GET['filter']) ? $_GET['filter'] : ''; // รับตัวกรอง
    $strSQL = "SELECT * FROM product";
    if ($filter) {
        $strSQL .= " WHERE Name LIKE '%$filter%'"; // ตัวกรองด้วยชื่อสินค้า
    }
    $objQuery = mysqli_query($conn, $strSQL) or die("Error Query [" . $strSQL . "]");
    ?>

    <!-- ฟอร์มตัวกรอง -->
    <!-- Filter and Sort Section -->
    <div class="filter-sort">
        <select id="sort-options">
            <option value="name-asc">ชื่อ A-Z</option>
            <option value="name-desc">ชื่อ Z-A</option>
            <option value="price-asc">ราคาต่ำไปสูง</option>
            <option value="price-desc">ราคาสูงไปต่ำ</option>

        </select>
    </div>



    <!-- ตารางสินค้ารูปแบบกริด -->
    <div class="product-list">
        <?php
        while ($objResult = mysqli_fetch_array($objQuery)) {
            //var_dump($objResult); // เช็กว่ามีค่าถูกต้องไหม
        ?>
            <div class="product-card">
                <a href="product-detail.php?id=<?= $objResult['ID']; ?>">
                    <img src="myfile/<?= $objResult["FilesName"]; ?>" alt="<?= htmlspecialchars($objResult["Name"]); ?>" class="product-image">
                    <h3><?= htmlspecialchars($objResult["Name"]); ?></h3>
                    <p>฿<?= htmlspecialchars($objResult["Price"]); ?></p>
                </a>
            </div>
        <?php } ?>
    </div>

    <?php // mysqli_close($conn); 
    ?>
    <script src="script.js"></script>
    <!-- Footer -->
    <?php
    renderFooter();
    ?>
</body>

</html>