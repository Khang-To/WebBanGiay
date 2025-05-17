<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Giới thiệu - Blue Eagle</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="images/logo.png">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    <script src="js/dropdown-hover.js"></script>
    <script src="js/back-to-top.js"></script>
</head>
<body class="bg-dark text-light">
    <?php include 'includes/header.php'; ?>
    <?php include 'includes/nav.php'; ?>

    <div class="container py-5">
        <div class="text-center mb-5">
            <h1 class="fw-bold text-warning">Giới thiệu về Blue Eagle</h1>
            <p class="text-light fst-italic">Đam mê bóng đá - Tinh thần sinh viên - Khát vọng bay xa</p>
        </div>

        <div class="row align-items-center mb-5" data-aos="fade-right">
            <div class="col-md-6">
                <img src="images/aboutus.jpg" alt="Blue Eagle Team" class="img-fluid rounded-4 shadow">
            </div>
            <div class="col-md-6">
                <h3 class="fw-semibold text-info">Câu chuyện bắt đầu từ giảng đường</h3>
                <p>Blue Eagle được thành lập vào năm 2022 bởi một nhóm sinh viên đam mê bóng đá từ Trường Đại học An Giang...</p>
            </div>
        </div>

        <div class="row text-center mb-5" data-aos="fade-up">
            <div class="col-md-4">
                <i class="bi bi-people fs-1 text-primary"></i>
                <h5 class="mt-3">Đội ngũ trẻ trung</h5>
                <p>Nơi hội tụ các sinh viên tài năng đam mê bóng đá.</p>
            </div>
            <div class="col-md-4">
                <i class="bi bi-lightning-fill fs-1 text-warning"></i>
                <h5 class="mt-3">Nhiệt huyết thi đấu</h5>
                <p>Hết mình trong từng trận đấu.</p>
            </div>
            <div class="col-md-4">
                <i class="bi bi-flag fs-1 text-success"></i>
                <h5 class="mt-3">Khát vọng vươn xa</h5>
                <p>Hướng đến những giải đấu lớn trong tương lai.</p>
            </div>
        </div>

        <div class="row align-items-center mb-5" data-aos="fade-left">
            <div class="col-md-6 order-md-2">
                <img src="images/store.jpg" alt="Store" class="img-fluid rounded-4 shadow">
            </div>
            <div class="col-md-6 order-md-1">
                <h3 class="fw-semibold text-info">Từ sân cỏ đến hành trình khởi nghiệp</h3>
                <p>Blue Eagle Shoes chính thức ra đời năm 2024 tại An Giang...</p>
            </div>
        </div>

        <div class="row align-items-center mb-5" data-aos="fade-right">
            <div class="col-md-6">
                <img src="images/hinhgiay.jpg" alt="Mở rộng" class="img-fluid rounded-4 shadow">
            </div>
            <div class="col-md-6">
                <h3 class="fw-semibold text-info">Vươn tầm toàn quốc</h3>
                <p>Mở rộng chi nhánh tới Hà Nội, Đà Nẵng, Cần Thơ...</p>
            </div>
        </div>

        <div class="row align-items-center mb-5" data-aos="fade-left">
            <div class="col-md-6 order-md-2">
                <img src="images/kethop.webp" alt="Vận động viên" class="img-fluid rounded-4 shadow">
            </div>
            <div class="col-md-6 order-md-1">
                <h3 class="fw-semibold text-info">Đồng hành với tài năng trẻ</h3>
                <p>Blue Eagle tài trợ cho nhiều cầu thủ học đường, giải phong trào...</p>
            </div>
        </div>

        <div class="row align-items-center mb-5" data-aos="fade-right">
            <div class="col-md-6">
                <img src="images/banhang.jpg" alt="Bán hàng online" class="img-fluid rounded-4 shadow">
            </div>
            <div class="col-md-6">
                <h3 class="fw-semibold text-info">Chuyển mình số hóa</h3>
                <p>Ra mắt website, ứng dụng di động, tham gia Shopee, Tiki, Lazada...</p>
            </div>
        </div>

        <div class="text-center mt-5" data-aos="zoom-in">
            <h3 class="fw-bold text-warning">Blue Eagle – Không chỉ là giày, mà là tinh thần thể thao</h3>
            <p>Hành trình tiếp diễn với nhiệt huyết và khát vọng đổi mới...</p>
        </div>
    </div>

    <?php include 'includes/footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>AOS.init();</script>
    <button type="button" class="btn btn-warning btn-lg rounded-circle shadow back-to-top" id="btn-back-to-top">
        <i class="bi bi-arrow-up"></i>
    </button>
</body>
</html>
