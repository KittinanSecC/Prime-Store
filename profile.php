<?php
session_start();
include("include.php");
include("structure.php");

// ตรวจสอบว่าผู้ใช้เข้าสู่ระบบหรือไม่
if (!isset($_SESSION['user_id'])) {
    die("You must be logged in to view this page.");
}

$loggedIn = $_SESSION['user_id'];
// ตรวจสอบการเชื่อมต่อ
if ($conn->connect_error) {
    die("Failed to connect to DB: " . $conn->connect_error);
}

// ดึงข้อมูลของผู้ใช้ที่เข้าสู่ระบบ
$sql = "SELECT * FROM users WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $loggedIn);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style2.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <title>โปรไฟล์อย่างเป็นทางการของ Prime Sneakers Store TH</title>
    <link href="assets/logo/Prime2.png" rel="icon">

    <title>หน้าโพรไฟล์</title>
    <style>
        
        .row {
            width: 100%;

        }

        .profile_container {
            flex: 1;
            display: flex;
            flex-direction: column;
            box-shadow: #ccc;
            box-shadow: 2px 2px 5px rgba(0, 0, 0, 0.3);
            gap: 20px;
        }

        .profile-title {
            font-size: xx-large;
            font-weight: bolder;
            margin-bottom: 0.5rem;
            text-align: left;

        }


        .proinfo {
            margin-bottom: 0.5rem;
            text-align: left;
        }

        .profile-image-container {
            display: flex;
            justify-content: center;
            /* Center horizontally */
            align-items: center;
            /* Center vertically */
        }

        .clickable {
            cursor: pointer;
            /* Make the image clickable */
        }

        .text-muted.small {
            /* Style the hint text */
            font-size: smaller;
            /* Make it smaller */
            margin-top: 5px;
            /* Add a little space above */
        }

        .profile-info {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 150px;
            width: 150px;
            border-radius: 50%;

        }

        .profile-info img {
            width: 80px;
            height: 80px;
            background-color: #fff;
            border-radius: 50%;
        }

        .profile-details {
            text-align: left;
        }

        .profile-details h3 {
            font-weight: bold;
        }

        .btn-container {
            display: flex;
            align-self: center;
            justify-content: flex-end;
            gap: 10px;
        }

        .form-label {
            text-align: left;
        }

        .margin2 {
            margin-bottom: 4rem;
        }

        .butt1 {
            background-color: black;
            padding-left: 20px;
            padding-right: 20px;
            border-radius: 20px;
        }
    </style>
</head>

<body>
    <?php
    renderHeader($conn)
    ?>
    <script>
        function previewImage(event) {
            var reader = new FileReader();
            reader.onload = function() {
                var output = document.getElementById('profileImage');
                output.src = reader.result; // Update the profile image with the selected one
            };
            reader.readAsDataURL(event.target.files[0]);
        }
    </script>
    <div class="container profile_container">

        <div class="profile-title">โปรไฟล์</div>
        <div class="proinfo">โปรไฟล์ Prime คือสิ่งที่ใช้แทนตัวคุณในการสั่งซื้อสินค้า ของเว็บ Prime</div>
        <div class="row" style="display: flex; align-items: start; justify-content: start;">

            <!-- Profile Picture Column -->
            <div class="profile-info" style="flex: 0 0 auto; margin-right: 20px; height: 200px; width: 200px; display: flex; justify-content: center; align-items: center;">
                <?php
                // Check if profile image is set and not empty, else use default default
                $profileImage = empty($user['profile_img']) ? 'default.png' : $user['profile_img'];
                ?>
                <img src="myfile/<?php echo htmlspecialchars($profileImage); ?>" alt="Profile Image" style="width: 100%; height: 100%; object-fit:cover; border-radius: 50%; border: 2px solid #ccc;">
            </div>


            <!-- Profile Details Column -->
            <div class="profile-details" style="flex: 1;">
                <h3><?= htmlspecialchars($user['firstName']) . " " . htmlspecialchars($user['lastName']) ?></h3>
                <p>username :@<?= htmlspecialchars($user['username']) ?></p>
                <p>Email: <?= htmlspecialchars($user['email']) ?></p>
                <!-- Button trigger modal -->
                <button type="button" class="btn btn-dark btn-sm margin2 butt1" data-bs-toggle="modal" data-bs-target="#editModal">
                    แก้ไขประวัติ
                </button>
                <a href="successshow.php"><button type="button" class="btn btn-dark btn-sm margin2 butt1">
                        ประวัติการสั่งซื้อ
                    </button></a>
                    <?php
                    if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == 0) 
                    echo '<a href="upload1.php"><button type="button" class="btn btn-dark btn-sm margin2 butt1">
                        เพิ่มสินค้า
                    </button></a>'
                    ?>
                
            </div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title " style="font-size: 26px; font-weight: bold;">
                            แก้ไขประวัติ
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="container mt-5">
                            <form action="updateprofile.php" method="POST" enctype="multipart/form-data">
                                <input type="hidden" name="user_id" value="<?= $user['user_id'] ?>">

                                <div class="mb-3 text-center">
                                    <div class="profile-image-container">
                                        <label for="profile_img" style="background-color: #333; border:1px solid grey;  border-radius:50%; ">
                                            <img class="profile-info clickable" id="profileImage" src="<?= 'myfile/' . $user['profile_img'] ?>" alt="Profile Picture" style="max-width: 150px; max-height: 150px; object-fit:cover;">
                                        </label>
                                    </div>
                                    <input type="file" name="profile_img" id="profile_img" onchange="previewImage(event)" style="display: none;">
                                    <p class="text-muted small">คลิกที่รูปภาพเพื่อเปลี่ยนรูปโปรไฟล์</p>
                                </div>

                                <div class="mb-3 text-start">
                                    <label for="firstName" class="form-label">ชื่อ</label>
                                    <input type="text" class="form-control" id="firstName" name="firstName" value="<?= htmlspecialchars($user['firstName']) ?>">
                                </div>

                                <div class="mb-3 text-start">
                                    <label for="lastName" class="form-label">นามสกุล</label>
                                    <input type="text" class="form-control" id="lastName" name="lastName" value="<?= htmlspecialchars($user['lastName']) ?>">
                                </div>

                                <div class="mb-3 text-start">
                                    <label for="username" class="form-label">Username</label>
                                    <input type="text" class="form-control" id="username" name="username" value="<?= htmlspecialchars($user['username']) ?>">
                                </div>

                                <div class="mb-3 text-start">
                                    <label for="email" class="form-label">อีเมล</label>
                                    <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($user['email']) ?>">
                                </div>

                                <button type="submit" class="btn btn-dark btn-sm margin2 butt1">ยืนยัน</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script>
            function previewImage(event) {
                const file = event.target.files[0];
                if (file) {
                    const reader = new FileReader();

                    reader.onload = function(e) {
                        document.getElementById("profileImage").src = e.target.result;
                    }

                    reader.readAsDataURL(file);
                } else {
                    // If no file is selected, revert to the original image or a default placeholder
                    document.getElementById("profileImage").src = "<?= 'myfile/' . $user['profile_img'] ?>"; // Or a default image path
                }
            }
        </script>
        <!-- Bootstrap JS (necessary for modal functionality) -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    </div>
    </div>

    <?php
    renderFooter();
    ?>

</body>

</html>