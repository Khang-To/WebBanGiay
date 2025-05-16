<!-- Swiper CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

<style>
.swiper {
  width: 100%;
  height: 600px; /* Tăng chiều cao cho giao diện hiện đại */
  padding: 20px 0;
  overflow: hidden;
}

.swiper-slide {
  background: #000;
  display: flex;
  justify-content: center;
  align-items: center;
  overflow: hidden;
  transition: transform 0.4s ease, opacity 0.4s ease;
  position: relative;
  border-radius: 12px;
  box-shadow: 0 8px 20px rgba(0, 0, 0, 0.3);
}

.swiper-slide img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  border-radius: 12px;
  transition: transform 0.5s ease;
}

.swiper-slide-active img {
  transform: scale(1.05); /* Zoom nhẹ khi slide active */
}

.swiper-slide::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: linear-gradient(to bottom, rgba(0, 0, 0, 0.3), rgba(0, 0, 0, 0.5));
  z-index: 1;
  border-radius: 12px;
}

.swiper-slide .content {
  position: absolute;
  bottom: 40px;
  left: 40px;
  z-index: 2;
  color: #fff;
  text-align: left;
  opacity: 0;
  transform: translateY(20px);
  transition: opacity 0.5s ease, transform 0.5s ease;
}

.swiper-slide-active .content {
  opacity: 1;
  transform: translateY(0);
}

.swiper-slide .content h2 {
  font-size: 2.5rem;
  font-weight: bold;
  margin-bottom: 10px;
  text-shadow: 0 2px 4px rgba(0, 0, 0, 0.5);
}

.swiper-slide .content p {
  font-size: 1.2rem;
  margin-bottom: 20px;
}

.swiper-slide .content .btn {
  background-color: #ffc107;
  color: #000;
  font-weight: 600;
  padding: 10px 20px;
  border-radius: 25px;
  transition: background-color 0.3s ease;
}

.swiper-slide .content .btn:hover {
  background-color: #ffca2c;
}

.swiper-button-next,
.swiper-button-prev {
  color: #fff;
  background: rgba(0, 0, 0, 0.5);
  width: 50px;
  height: 50px;
  border-radius: 50%;
  transition: background 0.3s ease, transform 0.3s ease;
}

.swiper-button-next:hover,
.swiper-button-prev:hover {
  background: rgba(0, 0, 0, 0.8);
  transform: scale(1.1);
}

.swiper-button-next:after,
.swiper-button-prev:after {
  font-size: 1.5rem;
}

.swiper-pagination-bullet {
  background: rgba(255, 255, 255, 0.7);
  width: 12px;
  height: 12px;
  opacity: 0.8;
  transition: all 0.3s ease;
}

.swiper-pagination-bullet-active {
  background: #ffc107;
  width: 14px;
  height: 14px;
  opacity: 1;
}

.swiper.mySwiper3D {
  margin-top: 0 !important;
  padding-top: 0 !important;
}

@media (max-width: 768px) {
  .swiper {
    height: 400px;
  }
  .swiper-slide .content h2 {
    font-size: 1.8rem;
  }
  .swiper-slide .content p {
    font-size: 1rem;
  }
  .swiper-slide .content {
    bottom: 20px;
    left: 20px;
  }
}
</style>

<!-- Banner Swiper 3D -->
<div class="swiper mySwiper3D rounded shadow mt-4">
  <div class="swiper-wrapper">
    <div class="swiper-slide">
      <img src="images/banner1.webp" alt="Banner 1">
      <div class="content">
        <h2>Khám Phá Bộ Sưu Tập Mới</h2>
        <p>Giày bóng đá chất lượng từ Blue Eagle</p>
        <a href="giay.php" class="btn">Mua Ngay</a>
      </div>
    </div>
    <div class="swiper-slide">
      <img src="images/banner2.webp" alt="Banner 2">
      <div class="content">
        <h2>Ưu Đãi Lên Đến 30%</h2>
        <p>Nhanh tay sở hữu giày yêu thích!</p>
        <a href="giay.php?giamgia=1" class="btn">Xem Ưu Đãi</a>
      </div>
    </div>
    <div class="swiper-slide">
      <img src="images/banner3.jpg" alt="Banner 3">
      <div class="content">
        <h2>Thiết Kế Đẳng Cấp</h2>
        <p>Đồng hành cùng bạn trên sân cỏ</p>
        <a href="giay.php" class="btn">Khám Phá</a>
      </div>
    </div>
    <div class="swiper-slide">
      <img src="images/banner4.webp" alt="Banner 4">
      <div class="content">
        <h2>Blue Eagle - Phong Cách</h2>
        <p>Chất lượng vượt trội, giá cả hợp lý</p>
        <a href="giay.php" class="btn">Mua Sắm</a>
      </div>
    </div>
  </div>

  <!-- Điều hướng + chỉ số -->
  <div class="swiper-button-next"></div>
  <div class="swiper-button-prev"></div>
  <div class="swiper-pagination"></div>
</div>

<!-- Swiper JS -->
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

<script>
  const swiper = new Swiper(".mySwiper3D", {
    effect: "coverflow",
    grabCursor: true,
    centeredSlides: true,
    slidesPerView: "auto",
    loop: true,
    coverflowEffect: {
      rotate: 40, // Tăng góc xoay cho hiệu ứng 3D
      stretch: 0,
      depth: 200, // Tăng độ sâu
      modifier: 1.5,
      slideShadows: true,
    },
    autoplay: {
      delay: 5000,
      disableOnInteraction: false,
    },
    pagination: {
      el: ".swiper-pagination",
      clickable: true,
    },
    navigation: {
      nextEl: ".swiper-button-next",
      prevEl: ".swiper-button-prev",
    },
  });
</script>