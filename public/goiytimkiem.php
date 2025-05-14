<?php
// public/goiytimkiem.php
if (session_status()===PHP_SESSION_NONE) session_start();
include 'includes/cauhinh.php';

$k = trim($_GET['tu_khoa'] ?? '');
if ($k==='' ) exit;

$sql = "SELECT g.id, g.ten_giay, g.don_gia, g.ti_le_giam_gia, g.hinh_anh,
               th.ten_thuong_hieu, lg.ten_loai
        FROM giay g
        JOIN thuong_hieu th ON g.thuong_hieu_id = th.id
        JOIN loai_giay   lg ON g.loai_giay_id   = lg.id
        WHERE g.ten_giay LIKE ?
        LIMIT 5";

$stmt = $conn->prepare($sql);
$like = "%{$k}%";
$stmt->bind_param('s',$like);
$stmt->execute();
$res = $stmt->get_result();

if ($res->num_rows) {
  echo '<div class="list-group">';
  while ($r = $res->fetch_assoc()) {
    $g0 = $r['don_gia'];
    $t  = $r['ti_le_giam_gia'];
    $g1 = $g0*(1-$t/100);
    ?>
    <a href="giaychitiet.php?id=<?= $r['id'] ?>"
       class="list-group-item list-group-item-action d-flex">
      <img src="../uploads/<?= $r['hinh_anh'] ?>"
           width="50" height="50" style="object-fit:cover;margin-right:8px;">
      <div>
        <div class="fw-bold"><?= htmlspecialchars($r['ten_giay']) ?></div>
        <div class="small text-muted"><?= htmlspecialchars($r['ten_thuong_hieu']) ?> • <?= htmlspecialchars($r['ten_loai']) ?></div>
        <div class="text-danger">
          <?= number_format($g1,0,',','.') ?>đ
          <?php if($t>0): ?>
            <small class="text-decoration-line-through text-muted ms-2"><?= number_format($g0,0,',','.') ?>đ</small>
          <?php endif; ?>
        </div>
      </div>
    </a>
    <?php
  }
  echo '</div>';
} else {
  echo '<div class="p-2 text-muted">Không tìm thấy</div>';
}
