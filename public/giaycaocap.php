<?php include 'includes/cauhinh.php'; ?>

<section class="container py-5 text-light" style="background-color: #1a1a1a;">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-warning mb-0">⭐️ GIÀY CAO CẤP</h2>
        <a href="giay.php?gia=4" class="btn btn-outline-light">Xem tất cả</a>
    </div>

    <div id="caoCapCarousel" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-inner">
            <?php
            $sql = "SELECT g.*, th.ten_thuong_hieu 
                    FROM giay g
                    JOIN thuong_hieu th ON g.thuong_hieu_id = th.id
                    WHERE g.don_gia >= 2000000
                    ORDER BY g.don_gia DESC
                    LIMIT 8";
            $result = mysqli_query($conn, $sql);

            $count = 0;
            $active = true;

            while ($row = mysqli_fetch_assoc($result)) {
                $gia_goc = $row['don_gia'];
                $giam = $row['ti_le_giam_gia'];
                $gia_moi = $gia_goc * (1 - $giam / 100);

                if ($count % 4 == 0) {
                    if ($count > 0) echo '</div></div>';
                    echo '<div class="carousel-item '.($active ? 'active' : '').'"><div class="row g-4">';
                    $active = false;
                }
            ?>
                <div class="col-md-3">
                    <a href="giaychitiet.php?id=<?= $row['id'] ?>" class="text-decoration-none">
                        <div class="card bg-dark text-white h-100 shadow-sm border-0 position-relative">
                            <span class="badge bg-warning text-dark position-absolute top-0 start-0 m-2">Cao Cấp</span>
                            <img src="../uploads/<?= htmlspecialchars($row['hinh_anh']) ?>"  
                            class="card-img-top" 
                            alt="<?= htmlspecialchars($row['ten_giay']) ?>" 
                            style="height:200px; object-fit:cover;">
                            <div class="card-body">
                                <h6 class="card-subtitle text-muted"><?= $row['ten_thuong_hieu'] ?></h6>
                                <h5 class="card-title fw-bold text-light" style="
                                    overflow: hidden;
                                    text-overflow: ellipsis;
                                    display: -webkit-box;
                                    -webkit-box-orient: vertical;
                                    -webkit-line-clamp: 2;
                                    height: 3em;
                                    line-height: 1.5em;
                                ">
                                    <?= htmlspecialchars($row['ten_giay']) ?>
                                </h5>
                                <?php if ($giam > 0): ?>
                                    <p class="text-decoration-line-through text-muted small mb-1">
                                        <?= number_format($gia_goc, 0, ',', '.') ?>đ
                                    </p>
                                    <p class="fw-bold text-warning mb-0">
                                        <?= number_format($gia_moi, 0, ',', '.') ?>đ
                                        <span class="badge bg-danger ms-2">-<?= $giam ?>%</span>
                                    </p>
                                <?php else: ?>
                                    <p class="fw-bold text-warning mb-0">
                                        <?= number_format($gia_goc, 0, ',', '.') ?>đ
                                    </p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </a>
                </div>
            <?php
                $count++;
            }

            if ($count > 0) echo '</div></div>';
            ?>
        </div>

        <!-- Nút điều khiển -->
        <button class="carousel-control-prev" type="button" data-bs-target="#caoCapCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon bg-dark rounded-circle p-2" aria-hidden="true"></span>
            <span class="visually-hidden">Trước</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#caoCapCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon bg-dark rounded-circle p-2" aria-hidden="true"></span>
            <span class="visually-hidden">Sau</span>
        </button>
    </div>
</section>
