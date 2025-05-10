<?php
include 'cauhinh.php';

$sql_loai = "SELECT * FROM loai_giay";
$result_loai = $conn->query($sql_loai);
function to_filename($str) {
    $str = strtolower(trim($str));
    $str = iconv('UTF-8', 'ASCII//TRANSLIT', $str); // bỏ dấu tiếng Việt
    $str = preg_replace('/[^a-z0-9]+/', '_', $str); // thay ký tự lạ bằng _
    $str = trim($str, '_'); // bỏ dấu _ đầu/cuối
    return $str;
}
?>

<div class="container mt-5">
    <h2 class="text-dark text-center mb-4">BẠN ĐANG TÌM</h2>
    <div class="row">
        <?php while($row = $result_loai->fetch_assoc()): ?>
            <div class="col-md-3 mb-4">
                <a href="giay.php?loai_giay=<?= $row['id'] ?>" class="text-decoration-none text-white">
                    <div class="card bg-dark h-100 border-0">
                        <img src="images/<?php echo to_filename($row['ten_loai']); ?>.webp"
                            class="card-img"
                            style="height: 150px; object-fit: cover;"
                            alt="<?php echo $row['ten_loai']; ?>">
                        <div class="card-body px-3 py-2">
                            <h6 class="mb-1 text-uppercase fw-bold"><?= $row['ten_loai'] ?></h6>
                        </div>
                    </div>
                </a>
            </div>
        <?php endwhile; ?>
    </div>
</div>
