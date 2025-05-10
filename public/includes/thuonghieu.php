<?php
include 'cauhinh.php';

$sql = "SELECT * FROM thuong_hieu";
$result = $conn->query($sql);
?>

<div class="container mt-5">
    <h2 class="text-dark text-center">THƯƠNG HIỆU NỔI TIẾNG</h2>
    <div class="row">
        <?php while($row = $result->fetch_assoc()): ?>
            <div class="col-md-4 mb-4">
        <a href="giay.php?thuong_hieu=<?php echo $row['id']; ?>" class="card-link">
        <div class="card brand-card text-white">
            <img src="images/<?php echo to_filename($row['ten_thuong_hieu']); ?>.webp"
                class="card-img"
                style="height: 150px; object-fit: cover;"
                alt="<?php echo $row['ten_thuong_hieu']; ?>">
            <div class="overlay-text">
                <h5 class="card-title m-0"><?php echo $row['ten_thuong_hieu']; ?></h5>
            </div>
        </div>
    </a>
</div>
        <?php endwhile; ?>
    </div>
</div>
