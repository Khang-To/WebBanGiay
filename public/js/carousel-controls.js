document.addEventListener("DOMContentLoaded", function () {
    const carousels = document.querySelectorAll('.carousel');

    carousels.forEach(carousel => {
        const prevBtn = carousel.querySelector('.carousel-control-prev');
        const nextBtn = carousel.querySelector('.carousel-control-next');
        const items = carousel.querySelectorAll('.carousel-item');

        if (!items.length) return;

        const bsCarousel = new bootstrap.Carousel(carousel);

        function updateNavButtons() {
            const activeIndex = [...items].findIndex(item => item.classList.contains('active'));
            if (prevBtn) prevBtn.style.display = activeIndex === 0 ? 'none' : 'flex';
            if (nextBtn) nextBtn.style.display = activeIndex === items.length - 1 ? 'none' : 'flex';
        }

        carousel.addEventListener('slid.bs.carousel', updateNavButtons);
        updateNavButtons();
    });
});
