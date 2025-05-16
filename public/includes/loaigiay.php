<?php
include 'cauhinh.php';
include_once 'includes/helpers.php'; // Chứa hàm to_filename()

$sql_loai = "SELECT * FROM loai_giay";
$result_loai = $conn->query($sql_loai);
?>

<div class="container mt-5">
    <h2 class="text-light text-center mb-4">BẠN ĐANG TÌM</h2>
    <div class="row">
        <?php while($row = $result_loai->fetch_assoc()): ?>
            <?php
                $filename = to_filename($row['ten_loai']) . '.webp';
                $image_path = "images/$filename";
                if (!file_exists("images/$filename")) {
                    $image_path = "images/default.webp";
                } else {
                    $image_path = "images/$filename";
                }
            ?>
            <div class="col-md-3 mb-4">
            <a href="giay.php?loai_giay=<?= $row['id'] ?>" class="card-link">
            <div class="brand-card">
            <img src="<?= $image_path ?>"
                 class="card-img"
                 style="height: 250px; object-fit: cover;"
                 alt="<?= htmlspecialchars($row['ten_loai']) ?>">
            <div class="overlay-text text-white fw-bold text-uppercase">
                <?= htmlspecialchars($row['ten_loai']) ?>
            </div>
        </div>
    </a>
</div>

        <?php endwhile; ?>
    </div>
</div>
