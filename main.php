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
    <title>ร้านค้าอย่างเป็นทางการของ Prime Sneakers Store TH
    </title>
    <link rel="stylesheet" href="style2.css">
    <link href="assets/logo/Prime2.png" rel="icon">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <!--animate.css-->
    <link rel="stylesheet" href="assets/css/animate.css">

    <!--owl.carousel.css-->
    <link rel="stylesheet" href="assets/css/owl.carousel.min.css">
    <link rel="stylesheet" href="assets/css/owl.theme.default.min.css">
    <!-- React & ReactDOM CDN -->
    <script src="https://unpkg.com/react@18/umd/react.development.js"></script>
    <script src="https://unpkg.com/react-dom@18/umd/react-dom.development.js"></script>
    <script src="https://unpkg.com/babel-standalone@6/babel.min.js"></script>
    <style>
        .slider {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 20px;
        }

        .shoe {
            transition: 0.5s;
            opacity: 0.5;
        }

        .shoe.active {
            opacity: 1;
            transform: scale(1.2);
        }

        #promo-banner {
            background: #f5f5f5;
            color: black;
            text-align: center;
            padding: 10px;
            font-size: 16px;

            position: relative;
            /* เปลี่ยนจาก fixed เป็น relative */
            width: 100%;
            z-index: 1000;
        }
    </style>
</head>

<body>
    <?php
    renderHeader($conn)
    ?>
    <div id="promo-banner">
        <p id="promo-text"></p>
    </div>
    <script>
        const promos = [
            "ยินด้อนรับ !",
            "Prime Sneakers Store",
            "Welcome to Prime"
        ];

        let index = 0;

        function updatePromo() {
            document.getElementById("promo-text").textContent = promos[index];
            index = (index + 1) % promos.length;
        }

        setInterval(updatePromo, 3000); // เปลี่ยนข้อความทุก 3 วินาที
        updatePromo(); // แสดงข้อความแรกทันที
    </script>
    <!-- billbord -->
    <section id="video-background" class="video-container">
        <a href="upload3.php">
            <video autoplay loop muted playsinline class="video-background">
                <source src="assets/video/nike.mp4" type="video/mp4">
                Your browser does not support the video tag.
            </video>
        </a>
    </section>
    <!--brand strat -->
    <section id="brand" class="brand">
        <div class="container">
            <div class="brand-area">
                <div class="owl-carousel owl-theme brand-item">
                    <div class="item">
                        <a href="#">
                            <img src="assets/brand/nike.png" alt="brand-image" />
                        </a>
                    </div><!--/.item-->
                    <div class="item">
                        <a href="#">
                            <img src="assets/brand/adidas.png" alt="brand-image" />
                        </a>
                    </div><!--/.item-->
                    <div class="item">
                        <a href="#">
                            <img src="assets/brand/puma.png" alt="brand-image" />
                        </a>
                    </div><!--/.item-->
                    <div class="item">
                        <a href="#">
                            <img src="assets/brand/nbb.png" alt="brand-image" />
                        </a>
                    </div><!--/.item-->


                </div><!--/.owl-carousel-->
            </div><!--/.clients-area-->
        </div><!--/.container-->
    </section><!--/brand-->
    <!--brand end -->

    <style>
        /* ตั้งค่า container ของวีดีโอ */
        .video-container {
            position: relative;
            width: 100%;
            height: 100vh;
            overflow: hidden;
        }

        /* วีดีโอขยายเต็มพื้นที่ */
        .video-background {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
            /* ให้ลิงก์ครอบวิดีโอทั้งหมด */
        }
    </style>

    <!-- Hero Section -->
    <section class="hero">

        <img src="assets/font/prime.png" alt="font">
        <!-- ปุ่มดูข้อมูล -->
        <a href="upload3.php" class="btn btn-dark">ดูข้อมูล</a>


    </section>
    <!--brand strat -->
    <section id="brand" class="brand">
        <div class="container">
            <div class="brand-area">
                <div class="owl-carousel owl-theme brand-item">
                    <div class="item">
                        <a href="#">
                            <img src="assets/brand/nike.png" alt="brand-image" />
                        </a>
                    </div><!--/.item-->
                    <div class="item">
                        <a href="#">
                            <img src="assets/brand/adidas.png" alt="brand-image" />
                        </a>
                    </div><!--/.item-->
                    <div class="item">
                        <a href="#">
                            <img src="assets/brand/puma.png" alt="brand-image" />
                        </a>
                    </div><!--/.item-->
                    <div class="item">
                        <a href="#">
                            <img src="assets/brand/nbb.png" alt="brand-image" />
                        </a>
                    </div><!--/.item-->


                </div><!--/.owl-carousel-->
            </div><!--/.clients-area-->
        </div><!--/.container-->
    </section><!--/brand-->
    <!--brand end -->
    <div class="container">
        <h2>สินค้าล่าสุด</h2>
        <div class="products">
            <div class="product">
                <img src="assets/cont/1.jpg" alt="Lifestyle Running Vomero">
                <p>Lifestyle Running Vomero</p>
            </div>
            <div class="product">
                <img src="assets/cont/2.png" alt="United Pack">
                <p>United Pack</p>
            </div>
            <div class="product">
                <img src="assets/cont/3.png" alt="Air Force 1 สำหรับเด็ก">
                <p>Air Force 1 สำหรับเด็ก</p>
            </div>
        </div>
    </div>
    <h1>เลือกซื้อตามสินค้าไอคอน</h1>
    <div id="root"></div>

    <script type="text/babel">
        function ShoeSlider() {
            const shoes = [
                { name: "Nike AF 1", image: "myfile/af1.png" },
                { name: "Nike PANDA", image: "myfile/pd1.png" },
                { name: "Jordan", image: "myfile/Air Jordan 1 Low.png" },
                

            ];
            const [index, setIndex] = React.useState(0);

            const prevSlide = () => setIndex((prev) => (prev === 0 ? shoes.length - 1 : prev - 1));
            const nextSlide = () => setIndex((prev) => (prev === shoes.length - 1 ? 0 : prev + 1));

            return (
                <div>
                    <div className="slider">
                        <button onClick={prevSlide}>⬅</button>
                        {shoes.map((shoe, i) => (
                            <div key={i} className={`shoe ${i === index ? "active" : ""}`}>
                                <img src={shoe.image} alt={shoe.name} width="200" />
                                <p>{shoe.name}</p>
                            </div>
                        ))}
                        <button onClick={nextSlide}>➡</button>
                    </div>
                </div>
            );
        }

        ReactDOM.createRoot(document.getElementById("root")).render(<ShoeSlider />);
    </script>
    <div class="container">
        <h2>สินค้าล่าสุด</h2>
        <div class="products">
            <div class="product">
                <img src="assets/cont/1.jpg" alt="Lifestyle Running Vomero">
                <p>Lifestyle Running Vomero</p>
            </div>
            <div class="product">
                <img src="assets/cont/2.png" alt="United Pack">
                <p>United Pack</p>
            </div>
            <div class="product">
                <img src="assets/cont/3.png" alt="Air Force 1 สำหรับเด็ก">
                <p>Air Force 1 สำหรับเด็ก</p>
            </div>
        </div>
    </div>
    <div class="container">
        <h2>สินค้าล่าสุด</h2>
        <div class="products">
            <div class="product">
                <img src="assets/cont/1.jpg" alt="Lifestyle Running Vomero">
                <p>Lifestyle Running Vomero</p>
            </div>
            <div class="product">
                <img src="assets/cont/2.png" alt="United Pack">
                <p>United Pack</p>
            </div>
            <div class="product">
                <img src="assets/cont/3.png" alt="Air Force 1 สำหรับเด็ก">
                <p>Air Force 1 สำหรับเด็ก</p>
            </div>
        </div>
    </div>


    <!-- Shoe Carousel -->
    <!--
    <section class="carousel-section">
        <h2>Lorem</h2>
        <div class="carousel">
            <div class="carousel-item">
                <img src="assets/af1png.png" alt="Nike Pegasus">
                <p>PEGASUS</p>
                <p>ราคาพิเศษ: ฿3,500</p>

            </div>
            <div class="carousel-item">
                <img src="assets/pandapng.png" alt="Nike Pegasus">
                <p>PEGASUS</p>
                <p>ราคาพิเศษ: ฿3,500</p>


            </div>
            <div class="carousel-item">
                <img src="assets/af1png.png" alt="Nike Pegasus">
                <p>PEGASUS</p>
                <p>ราคาพิเศษ: ฿3,500</p>

            </div>
            <div class="carousel-item">
                <img src="assets/pandapng.png" alt="Nike Pegasus">
                <p>PEGASUS</p>
                <p>ราคาพิเศษ: ฿3,500</p>

            </div>

        </div>
    </section>
    -->








    <!-- Sports Carousel -->
    <!--<section class="carousel-section">
        <h2>Lorem</h2>
        <div class="carousel">
            <div class="carousel-item">
                <img src="assets/af1png.png" alt="Football">
                <p>Football</p>
            </div>
            <div class="carousel-item">
                <img src="assets/af1png.png" alt="Football">
                <p>Football</p>
            </div>
            <div class="carousel-item">
                <img src="assets/af1png.png" alt="Football">
                <p>Football</p>
            </div>
            <div class="carousel-item">
                <img src="assets/af1png.png" alt="Football">
                <p>Football</p>

            </div>
        </div>
    </section>
    -->

    <!--brand end -->
    <!-- Include all js compiled plugins (below), or include individual files as needed -->

    <script src="assets/js/jquery.js"></script>

    <!--modernizr.min.js-->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/modernizr/2.8.3/modernizr.min.js"></script>

    <!--bootstrap.min.js-->
    <script src="assets/js/bootstrap.min.js"></script>

    <!-- bootsnav js -->
    <script src="assets/js/bootsnav.js"></script>

    <!--owl.carousel.js-->
    <script src="assets/js/owl.carousel.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.4.1/jquery.easing.min.js"></script>

    <!--Custom JS-->
    <script src="assets/js/custom.js"></script>

    <!-- Footer -->
    <?php
    renderFooter();
    ?>


</body>

</html>