<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <title>Blue Eagle Store - Hướng dẫn sử dụng</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="icon" type="image/png" href="images/logo.png">

  <!-- Bootstrap CSS & Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">

  <!-- AOS Library -->
  <link href="https://unpkg.com/aos@2.3.4/dist/aos.css" rel="stylesheet" />
    <script src="js/dropdown-hover.js"></script>

  <!-- Custom CSS -->
  <style>
    body {
      background-color: #111;
      color: #fff;
    }
    .bg-blur {
      background: url('images/blur-bg.jpg') center/cover no-repeat;
      position: relative;
    }
    .bg-blur::before {
      content: "";
      position: absolute;
      inset: 0;
      backdrop-filter: blur(6px);
      background-color: rgba(0, 0, 0, 0.6);
      z-index: 0;
    }
    .bg-blur > * {
      position: relative;
      z-index: 1;
    }
    .guide-section {
      margin-bottom: 3rem;
    }
    .guide-section img {
      border-radius: 1rem;
      width: 100%;
      height: auto;
      object-fit: cover;
    }
  </style>
</head>

<body>
  <?php include 'includes/header.php'; ?>
  <?php include 'includes/nav.php'; ?>

  <div class="container py-5">
    <h1 class="text-center text-warning mb-5">HƯỚNG DẪN SỬ DỤNG</h1>

    <!-- Đăng ký -->
    <div class="guide-section bg-blur p-4 rounded shadow" id="dangky" data-aos="fade-right">
      <div class="row align-items-center">
        <div class="col-md-6">
            <div class="mb-3">
                <img src="images/hd/hddangky1.jpg" alt="Dang ky1" class="img-fluid rounded shadow">
            </div>
            <div>
                <img src="images/hd/hddangky2.jpg" alt="Dang ky2" class="img-fluid rounded shadow">
            </div>
            </div>
        <div class="col-md-6">
          <h4 class="text-warning"><i class="bi bi-person-plus-fill me-2"></i>Đăng ký tài khoản</h4>
          <p>Nhấn vào nút <strong>Đăng ký</strong> ở góc phải trên thanh menu, nhập đủ thông tin, sau đó nhấn <em>"Đăng ký"</em>.</p>
        </div>
      </div>
    </div>

    <!-- Đăng nhập -->
    <div class="guide-section bg-blur p-4 rounded shadow" id="dangnhap" data-aos="fade-left">
      <div class="row align-items-center flex-md-row-reverse">
        <div class="col-md-6">
            <div class="mb-3">
                <img src="images/hd/hddangnhap1.jpg" alt="Dang nhap1" class="img-fluid rounded shadow">
            </div>
            <div>
                <img src="images/hd/hddangnhap2.jpg" alt="Dang nhap2" class="img-fluid rounded shadow">
            </div>
            </div>
        <div class="col-md-6">
          <h4 class="text-warning"><i class="bi bi-box-arrow-in-right me-2"></i>Đăng nhập</h4>
          <p>Nhấn <strong>Đăng nhập</strong> và nhập tài khoản + mật khẩu đã đăng ký để truy cập tài khoản cá nhân.</p>
        </div>
      </div>
    </div>

    <!-- Chỉnh sửa hồ sơ -->
    <div class="guide-section bg-blur p-4 rounded shadow" id="hoso" data-aos="fade-right">
      <div class="row align-items-center">
         <div class="col-md-6">
            <div class="mb-3">
                <img src="images/hd/hdhoso1.jpg" alt="Ho so1" class="img-fluid rounded shadow">
            </div>
            <div>
                <img src="images/hd/hdhoso2.jpg" alt="Ho so2" class="img-fluid rounded shadow">
            </div>
            </div>
        <div class="col-md-6">
          <h4 class="text-warning"><i class="bi bi-pencil-square me-2"></i>Chỉnh sửa hồ sơ</h4>
          <p>Sau khi đăng nhập nhấn vào <strong>Icon hình người</strong> kế giỏ hàng để chỉnh sửa hồ sơ, tại đây bạn có thể chỉnh sửa họ tên, địa chỉ, số điện thoại hoặc mật khẩu khi cần.</p>
        </div>
      </div>
    </div>

    <!-- Thêm giỏ hàng -->
    <div class="guide-section bg-blur p-4 rounded shadow" id="them-gio-hang" data-aos="fade-left">
      <div class="row align-items-center flex-md-row-reverse">
        <div class="col-md-6">
            <div class="mb-3">
                <img src="images/hd/hdgiohang1.jpg" alt="Gio hang1" class="img-fluid rounded shadow">
            </div>
            <div>
                <img src="images/hd/hdgiohang2.jpg" alt="Gio hang2" class="img-fluid rounded shadow">
            </div>
            <div>
                <img src="images/hd/hdgiohang3.jpg" alt="Gio hang2" class="img-fluid rounded shadow">
            </div>
            </div>
        <div class="col-md-6">
          <h4 class="text-warning"><i class="bi bi-cart-plus me-2"></i>Thêm sản phẩm vào giỏ</h4>
          <p>Trong trang xem giày và trang chi tiết giày, ta có thể thêm giày vào giỏ hàng hãy nhấn <strong>Thêm vào giỏ</strong> và chọn size, số lượng. Sản phẩm sẽ được thêm vào giỏ hàng.</p>
        </div>
      </div>
    </div>

   <div class="guide-section bg-blur p-4 rounded shadow" id="hoso" data-aos="fade-right">
      <div class="row align-items-center">
         <div class="col-md-6">
            <div class="mb-3">
                <img src="images/hd/hddathang1.jpg" alt="Dat hang1" class="img-fluid rounded shadow">
            </div>
            <div>
                <img src="images/hd/hddathang2.jpg" alt="Dat hang" class="img-fluid rounded shadow">
            </div>
            </div>
        <div class="col-md-6">
          <h4 class="text-warning"><i class="bi bi-cart-plus me-2"></i>Đặt hàng</h4>
          <p>Trong trang giỏ hàng, hoặc ở trang giày bạn có thể đặt hàng ngay hãy nhấn <strong>Đặt hàng</strong>. Bạn sẽ được chuyển qua trang đặt hàng.</p>
          <p>Sau khi đặt hàng thành công bạn hãy chờ chúng tôi xác nhận đơn hàng và bạn có thể thanh toán. Bạn có thể tra cứu đơn hàng bạn đã đặt ở mục <strong>Tra cứu đơn hàng</strong>.</p>
        </div>
      </div>
    </div>

    <!-- Thanh toán -->
<div class="guide-section bg-blur p-4 rounded shadow" id="thanhtoan" data-aos="fade-up">
  <div class="row align-items-center">
    <div class="col-md-6">
      <div class="mb-3">
        <img src="images/hd/hdthanhtoan1.jpg" alt="Thanh toan1" class="img-fluid rounded shadow mb-3">
      </div>
      <div>
        <img src="images/hd/hdthanhtoan2.jpg" alt="Thanh toan2" class="img-fluid rounded shadow mb-3">
      </div>
      <div>
        <img src="images/hd/hdthanhtoan3.jpg" alt="Thanh toan3" class="img-fluid rounded shadow">
      </div>
    </div>
    <div class="col-md-6">
      <h4 class="text-warning"><i class="bi bi-cash-coin me-2"></i>Thanh toán</h4>
      <p>Khi chúng tôi đã duyệt đơn hàng của bạn hãy vào trang <strong>Thanh toán</strong> trên mục tài khoản, kiểm tra sản phẩm và nhấn <strong>Thanh toán</strong>. Đơn hàng sẽ được thanh toán và bộ phận chuyển hàng sẽ giao giày đến cho bạn</p>
    </div>
  </div>
</div>
  </div>

  <?php include 'includes/footer.php'; ?>

  <button type="button" class="btn btn-warning btn-lg rounded-circle shadow back-to-top" id="btn-back-to-top">
  <i class="bi bi-arrow-up"></i>
</button>
  <?php include 'includes/chat.php'; ?>

  <!-- JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
    <script src="js/back-to-top.js"></script>
  <script>AOS.init();</script>
</body>
</html>
