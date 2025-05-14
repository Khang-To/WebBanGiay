// js/timkiem.js
document.addEventListener('DOMContentLoaded', function() {
  const input = document.getElementById('tu_khoa');
  const box   = document.getElementById('suggestion-box');

  // kiểm tra sự tồn tại
  if (!input || !box) {
    console.error('Không tìm thấy #tu_khoa hoặc #suggestion-box');
    return;
  }

  input.addEventListener('input', function() {
    const q = this.value.trim();
    if (!q) return box.innerHTML = '';
    fetch(`goiytimkiem.php?tu_khoa=${encodeURIComponent(q)}`)
      .then(r => r.text())
      .then(html => box.innerHTML = html)
      .catch(err => console.error(err));
  });

  document.addEventListener('click', e => {
    if (!input.contains(e.target) && !box.contains(e.target)) {
      box.innerHTML = '';
    }
  });
});

document.addEventListener("DOMContentLoaded", function () {
    const input = document.getElementById("tu_khoa");
    const form = document.getElementById("form-timkiem");

    // Bấm Enter thì tìm kiếm
    input.addEventListener("keypress", function (e) {
        if (e.key === "Enter") {
            e.preventDefault(); // ngăn reload mặc định
            form.submit();
        }
    });

    // Click nút tìm kiếm
    document.getElementById("btn-timkiem").addEventListener("click", function () {
        form.submit();
    });
});