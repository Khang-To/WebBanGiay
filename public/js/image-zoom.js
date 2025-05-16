document.addEventListener('DOMContentLoaded', () => {
  const images = document.querySelectorAll('[data-zoom-image]');
  const overlay = document.getElementById('imageZoomOverlay');
  const zoomedImage = document.getElementById('zoomedImage');
  const imageContainer = document.getElementById('imageContainer');
  let currentScale = 1;
  let isDragging = false;
  let targetX = 0, targetY = 0;
  let currentX = 0, currentY = 0;
  let velocityX = 0, velocityY = 0;
  const damping = 0.1;
  const friction = 0.85;
  let animationFrame;

  if (!overlay || !zoomedImage || !imageContainer) return;

  // Thêm CSS cho hiệu ứng hover của nút
  const style = document.createElement('style');
  style.textContent = `
    .btn-warning:hover {
      background-color: #ffca2c !important;
      transform: scale(1.1);
      box-shadow: 0 0 8px rgba(255, 193, 7, 0.5);
    }
  `;
  document.head.appendChild(style);

  // Mở overlay khi nhấn ảnh
  images.forEach(img => {
    img.addEventListener('click', () => {
      zoomedImage.src = img.src;
      overlay.classList.remove('d-none');
      document.body.style.overflow = 'hidden';
    });
  });

  // Đóng overlay khi nhấn ngoài ảnh
  overlay.addEventListener('click', (e) => {
    if (e.target === overlay) {
      closeImageOverlay();
    }
  });

  // Đóng overlay khi nhấn phím Esc
  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape' && !overlay.classList.contains('d-none')) {
      closeImageOverlay();
    }
  });

  // Hàm đóng overlay
  window.closeImageOverlay = function() {
    overlay.classList.add('d-none');
    document.body.style.overflow = '';
    currentScale = 1;
    targetX = 0;
    targetY = 0;
    currentX = 0;
    currentY = 0;
    velocityX = 0;
    velocityY = 0;
    zoomedImage.style.transform = `scale(${currentScale}) translate(0, 0)`;
    imageContainer.style.cursor = 'grab';
    cancelAnimationFrame(animationFrame);
  };

  // Hàm phóng to/thu nhỏ
  window.zoomImage = function(delta) {
    currentScale = Math.max(0.5, Math.min(currentScale + delta, 3));
    updateTransform();
    imageContainer.style.cursor = currentScale > 1 ? 'move' : 'grab';
  };

  // Cập nhật vị trí và scale
  function updateTransform() {
    zoomedImage.style.transform = `scale(${currentScale}) translate(${currentX}px, ${currentY}px)`;
  }

  // Phóng to/thu nhỏ bằng scroll chuột
  zoomedImage.addEventListener('wheel', (e) => {
    e.preventDefault();
    const delta = e.deltaY > 0 ? -0.1 : 0.1;
    window.zoomImage(delta);
  });

  // Kéo thả mượt mà
  function animate() {
    currentX += (targetX - currentX) * damping;
    currentY += (targetY - currentY) * damping;
    velocityX *= friction;
    velocityY *= friction;
    currentX += velocityX;
    currentY += velocityY;

    // Giới hạn kéo
    const maxTranslate = 200 * currentScale;
    currentX = Math.max(-maxTranslate, Math.min(maxTranslate, currentX));
    currentY = Math.max(-maxTranslate, Math.min(maxTranslate, currentY));

    updateTransform();
    animationFrame = requestAnimationFrame(animate);
  }

  // Kéo bằng chuột
  imageContainer.addEventListener('mousedown', (e) => {
    if (currentScale <= 1) return;
    e.preventDefault();
    isDragging = true;
    const rect = imageContainer.getBoundingClientRect();
    startX = e.clientX - currentX;
    startY = e.clientY - currentY;
    imageContainer.style.cursor = 'grabbing';
    cancelAnimationFrame(animationFrame);
    animationFrame = requestAnimationFrame(animate);
  });

  imageContainer.addEventListener('mousemove', (e) => {
    if (!isDragging) return;
    targetX = e.clientX - startX;
    targetY = e.clientY - startY;
    velocityX = (targetX - currentX) * 0.1;
    velocityY = (targetY - currentY) * 0.1;
  });

  imageContainer.addEventListener('mouseup', () => {
    isDragging = false;
    imageContainer.style.cursor = currentScale > 1 ? 'move' : 'grab';
  });

  imageContainer.addEventListener('mouseleave', () => {
    isDragging = false;
    imageContainer.style.cursor = currentScale > 1 ? 'move' : 'grab';
  });

  // Kéo bằng cảm ứng
  imageContainer.addEventListener('touchstart', (e) => {
    if (currentScale <= 1) return;
    e.preventDefault();
    const touch = e.touches[0];
    startX = touch.clientX - currentX;
    startY = touch.clientY - currentY;
    isDragging = true;
    cancelAnimationFrame(animationFrame);
    animationFrame = requestAnimationFrame(animate);
  });

  imageContainer.addEventListener('touchmove', (e) => {
    if (!isDragging) return;
    e.preventDefault();
    const touch = e.touches[0];
    targetX = touch.clientX - startX;
    targetY = touch.clientY - startY;
    velocityX = (targetX - currentX) * 0.1;
    velocityY = (targetY - currentY) * 0.1;
  });

  imageContainer.addEventListener('touchend', () => {
    isDragging = false;
  });
});