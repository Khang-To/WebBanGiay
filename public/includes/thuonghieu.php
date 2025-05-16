<?php 
include 'cauhinh.php';
include_once 'includes/helpers.php';

$sql = "SELECT * FROM thuong_hieu";
$result = $conn->query($sql);
?>

<div class="container mt-5">
    <h2 class="text-light text-center">THƯƠNG HIỆU NỔI TIẾNG</h2>
    <div class="row">
        <?php while($row = $result->fetch_assoc()): ?>
            <?php
                $filename = to_filename($row['ten_thuong_hieu']) . '.webp';
                $image_path = "images/$filename";
                if (!file_exists("images/$filename")) {
                    $image_path = "images/default.webp";
                } else {
                    $image_path = "images/$filename";
                }
            ?>
            <div class="col-md-4 mb-4">
                <a href="giay.php?thuong_hieu=<?= $row['id'] ?>" class="card-link">
                    <div class="card brand-card text-white">
                        <img src="<?= $image_path ?>"
                             class="card-img"
                             style="height: 150px; object-fit: cover;"
                             alt="<?= htmlspecialchars($row['ten_thuong_hieu']) ?>">
                        <div class="overlay-text">
                            <h5 class="card-title m-0"><?= htmlspecialchars($row['ten_thuong_hieu']) ?></h5>
                        </div>
                    </div>
                </a>
            </div>
        <?php endwhile; ?>
    </div>
</div>
