function initProductPreview(options = {}) {
    const nameInput = document.querySelector(options.nameSelector || '[name="ten_giay"]');
    const priceInput = document.querySelector(options.priceSelector || '[name="don_gia"]');
    const discountInput = document.querySelector(options.discountSelector || '[name="ti_le_giam_gia"]');
    const brandSelect = document.querySelector(options.brandSelector || '[name="thuong_hieu_id"]');
    const typeSelect = document.querySelector(options.typeSelector || '[name="loai_giay_id"]');
    const imageInput = document.querySelector(options.imageSelector || '[name="hinh_anh"]');

    const img = document.getElementById(options.imgId || 'preview-img');
    const title = document.getElementById(options.nameId || 'preview-name');
    const extra = document.getElementById(options.extraId || 'preview-extra');
    const priceOld = document.getElementById(options.oldPriceId || 'preview-original-price');
    const priceNew = document.getElementById(options.newPriceId || 'preview-discounted-price');

    function updatePrice() {
        const priceVal = parseFloat(priceInput && priceInput.value ? priceInput.value : 0);
        const discount = parseFloat(discountInput && discountInput.value ? discountInput.value : 0);
        const finalPrice = priceVal - (priceVal * discount / 100);

        if (priceOld) {
            priceOld.innerText = priceVal.toLocaleString('vi-VN') + ' đ';
            priceOld.style.display = discount > 0 ? 'inline' : 'none';
        }

        if (priceNew) {
            priceNew.innerText = finalPrice.toLocaleString('vi-VN') + ' đ';
        }
    }

    if (nameInput && title) {
        nameInput.addEventListener('input', () => {
            title.innerText = nameInput.value || 'Tên giày';
        });
    }

    if (brandSelect && typeSelect && extra) {
        function updateExtra() {
            const brand = brandSelect.options[brandSelect.selectedIndex]?.text || '--';
            const type = typeSelect.options[typeSelect.selectedIndex]?.text || '--';
            extra.innerText = `${brand} | ${type}`;
        }
        brandSelect.addEventListener('change', updateExtra);
        typeSelect.addEventListener('change', updateExtra);
        updateExtra();
    }

    if (imageInput && img) {
        imageInput.addEventListener('change', () => {
            const file = imageInput.files[0];
            if (file) {
                img.src = URL.createObjectURL(file);
            }
        });
    }

    priceInput?.addEventListener('input', updatePrice);
    discountInput?.addEventListener('input', updatePrice);
    updatePrice(); // gọi lúc khởi tạo
}
