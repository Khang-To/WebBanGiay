document.addEventListener('DOMContentLoaded', () => {
  const images = document.querySelectorAll('[data-zoom-image]');
  const overlay = document.getElementById('imageZoomOverlay');
  const zoomedImage = document.getElementById('zoomedImage');
  const imageContainer = document.getElementById('imageContainer');

  let currentScale = 1;
  let currentX = 0, currentY = 0;
  let startX = 0, startY = 0;
  let isDragging = false;

  if (!overlay || !zoomedImage || !imageContainer) return;

  // CSS nút hover
  const style = document.createElement('style');
  style.textContent = `
    .btn-warning:hover {
      background-color: #ffca2c !important;
      transform: scale(1.1);
      box-shadow: 0 0 8px rgba(255, 193, 7, 0.5);
    }
  `;
  document.head.appendChild(style);

  // Mở overlay
  images.forEach(img => {
    img.addEventListener('click', () => {
      zoomedImage.src = img.src;
      overlay.classList.remove('d-none');
      document.body.style.overflow = 'hidden';
    });
  });

  // Đóng overlay
  function closeImageOverlay() {
    overlay.classList.add('d-none');
    document.body.style.overflow = '';
    currentScale = 1;
    currentX = currentY = 0;
    zoomedImage.style.transform = 'scale(1) translate(0px, 0px)';
    imageContainer.style.cursor = 'grab';
  }

  overlay.addEventListener('click', (e) => {
    if (e.target === overlay) closeImageOverlay();
  });

  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') closeImageOverlay();
  });

  window.closeImageOverlay = closeImageOverlay;

  // Zoom bằng cuộn chuột
  zoomedImage.addEventListener('wheel', (e) => {
    e.preventDefault();
    const delta = e.deltaY > 0 ? -0.1 : 0.1;
    currentScale = Math.min(Math.max(0.5, currentScale + delta), 3);
    imageContainer.style.cursor = currentScale > 1 ? 'move' : 'grab';
    updateTransform();
  });

function updateTransform() {
  zoomedImage.style.transform = `translate(${currentX}px, ${currentY}px) scale(${currentScale})`;
}

zoomedImage.style.transition = 'none';


  // Kéo bằng chuột
  imageContainer.addEventListener('mousedown', (e) => {
    if (currentScale <= 1) return;
    e.preventDefault();
    isDragging = true;
    startX = e.clientX - currentX;
    startY = e.clientY - currentY;
    imageContainer.style.cursor = 'grabbing';
  });

  document.addEventListener('mousemove', (e) => {
    if (!isDragging) return;
    currentX = e.clientX - startX;
    currentY = e.clientY - startY;
    updateTransform();
  });

  document.addEventListener('mouseup', () => {
    isDragging = false;
    imageContainer.style.cursor = currentScale > 1 ? 'move' : 'grab';
  });

  // Kéo bằng cảm ứng
  imageContainer.addEventListener('touchstart', (e) => {
    if (currentScale <= 1 || e.touches.length !== 1) return;
    const touch = e.touches[0];
    isDragging = true;
    startX = touch.clientX - currentX;
    startY = touch.clientY - currentY;
  });

  imageContainer.addEventListener('touchmove', (e) => {
    if (!isDragging || e.touches.length !== 1) return;
    const touch = e.touches[0];
    currentX = touch.clientX - startX;
    currentY = touch.clientY - startY;
    updateTransform();
  });

  imageContainer.addEventListener('touchend', () => {
    isDragging = false;
  });
});
