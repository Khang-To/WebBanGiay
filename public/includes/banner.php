<!-- Swiper CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

<style>
.swiper {
  width: 100%;
  height: 500px; /* hoặc vh nếu muốn full màn hình dọc */
  padding-top: 50px;
  padding-bottom: 50px;
  /* XÓA max-width để không bị bó giữa */
}

.swiper-slide {
  background: #000;
  display: flex;
  justify-content: center;
  align-items: center;
  overflow: hidden;
  transition: transform 0.3s;
}

.swiper-slide img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  border-radius: 0;
}

.swiper-button-next,
.swiper-button-prev {
  color: white;
  transform: scale(1.3);
}

.swiper-pagination-bullet {
  background: white;
  opacity: 0.7;
}

.swiper-pagination-bullet-active {
  background: #007bff;
}

.swiper.mySwiper3D {
    margin-top: 0 !important;
    padding-top: 0 !important;
}

</style>



<!-- Banner Swiper 3D -->
<div class="swiper mySwiper3D rounded shadow mt-4">
  <div class="swiper-wrapper">
    <div class="swiper-slide">
      <img src="images/banner1.webp" class="w-100 rounded" alt="Banner 1">
    </div>
    <div class="swiper-slide">
      <img src="images/banner2.webp" class="w-100 rounded" alt="Banner 2">
    </div>
    <div class="swiper-slide">
      <img src="images/banner3.jpg" class="w-100 rounded" alt="Banner 3">
    </div>
    <div class="swiper-slide">
      <img src="images/banner4.webp" class="w-100 rounded" alt="Banner 4">
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
      rotate: 30,
      stretch: 0,
      depth: 150,
      modifier: 1.2,
      slideShadows: true,
    },
    autoplay: {
      delay: 4000,
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
