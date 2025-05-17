<?php
    include 'includes/auth_admin.php';
    include 'includes/db.php';
    include 'includes/thongbao.php';

    $don_hang_id = $_GET['id'] ?? null;
    if (!$don_hang_id) {
        header('Location: danhsachdonhang.php');
        exit;
    }

    // Xử lý xác nhận/hủy đơn hàng
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $redirectUrl = ($_POST['back'] ?? '') === 'khach'
            ? 'donhang_khachhang.php?id=' . ($_POST['id_khach'] ?? 0)
            : 'danhsachdonhang.php';

        if (isset($_POST['confirm'])) {
            $stmt = $conn->prepare("UPDATE don_hang SET trang_thai = 'da_xac_nhan' WHERE id = ?");
            $stmt->bind_param('i', $don_hang_id);
            $stmt->execute();
            $stmt->close();
            flashMessage('success', 'Xác nhận đơn hàng thành công!');
            header("Location: $redirectUrl");
            exit;
        } elseif (isset($_POST['cancel'])) {
            $stmt = $conn->prepare("DELETE FROM chi_tiet_don_hang WHERE don_hang_id = ?");
            $stmt->bind_param('i', $don_hang_id);
            $stmt->execute();

            $stmt = $conn->prepare("DELETE FROM don_hang WHERE id = ?");
            $stmt->bind_param('i', $don_hang_id);
            $stmt->execute();
            $stmt->close();
            flashMessage('success', 'Đơn hàng đã được hủy.');
            header("Location: $redirectUrl");
            exit;
        }
    }

    // Lấy thông tin đơn hàng + khách hàng
    $stmt = $conn->prepare("SELECT dh.*, kh.ho_ten, kh.dia_chi, kh.so_dien_thoai, kh.email
                            FROM don_hang dh
                            JOIN khach_hang kh ON dh.khach_hang_id = kh.id
                            WHERE dh.id = ?");
    $stmt->bind_param('i', $don_hang_id);
    $stmt->execute();
    $order = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    if (!$order) {
        echo "Đơn hàng không tồn tại.";
        exit;
    }

    // Lấy chi tiết đơn hàng
    $stmt = $conn->prepare("SELECT ctdh.*, g.ten_giay, g.don_gia, g.ti_le_giam_gia, sg.size
                            FROM chi_tiet_don_hang ctdh
                            JOIN size_giay sg ON ctdh.size_giay_id = sg.id
                            JOIN giay g ON sg.giay_id = g.id
                            WHERE ctdh.don_hang_id = ?");
    $stmt->bind_param('i', $don_hang_id);
    $stmt->execute();
    $items = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmt->close();

    include 'includes/header.php';
?>

<div class="container mt-4">
    <h4 class="mb-3">Chi tiết đơn hàng #<?= $order['id'] ?></h4>

    <div class="mb-4">
        <h5>Thông tin khách hàng</h5>
        <p><strong>Họ tên:</strong> <?= htmlspecialchars($order['ho_ten']) ?></p>
        <p><strong>Địa chỉ:</strong> <?= htmlspecialchars($order['dia_chi']) ?></p>
        <p><strong>Số điện thoại:</strong> <?= htmlspecialchars($order['so_dien_thoai']) ?></p>
        <p><strong>Email:</strong> <?= htmlspecialchars($order['email']) ?></p>
    </div>

    <div class="mb-4">
        <h5>Sản phẩm đặt mua</h5>
        <table class="table table-bordered text-center align-middle">
            <thead class="table-light">
                <tr>
                    <th>Tên giày</th>
                    <th>Size</th>
                    <th>Đơn giá</th>
                    <th>Số lượng</th>
                    <th>Thành tiền</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $tong_tien = 0;
                foreach ($items as $item):
                    $gia_sau_giam = $item['don_gia'] * (1 - $item['ti_le_giam_gia'] / 100);
                    $thanh_tien = $gia_sau_giam * $item['so_luong_ban'];
                    $tong_tien += $thanh_tien;
                ?>
                <tr>
                    <td style="text-align:left"><?= htmlspecialchars($item['ten_giay']) ?></td>
                    <td><?= $item['size'] ?></td>
                    <td><?= number_format($gia_sau_giam, 0, ',', '.') ?> đ</td>
                    <td><?= $item['so_luong_ban'] ?></td>
                    <td><?= number_format($thanh_tien, 0, ',', '.') ?> đ</td>
                </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="4" class="text-end">Tổng cộng:</th>
                    <th><?= number_format($tong_tien, 0, ',', '.') ?> đ</th>
                </tr>
            </tfoot>
        </table>
    </div>

    <div class="mb-4">
        <h5>Ghi chú đơn hàng</h5>
        <textarea class="form-control" readonly rows="3"><?= htmlspecialchars($order['ghi_chu'] ?? '') ?: 'Không có ghi chú' ?></textarea>
    </div>

    <form method="post" class="d-flex gap-2">
        <input type="hidden" name="back" value="<?= $_GET['back'] ?? '' ?>">
        <input type="hidden" name="id_khach" value="<?= $_GET['id_khach'] ?? '' ?>">

        <?php
        $trang_thai = $order['trang_thai'];
        $isDaXacNhan = $trang_thai === 'da_xac_nhan';
        $isDaThanhToan = $trang_thai === 'da_thanh_toan';
        ?>

        <button type="submit" name="confirm" class="btn btn-success" <?= ($trang_thai !== 'cho_xac_nhan') ? 'disabled' : '' ?>>
            Xác nhận đơn hàng
        </button>

        <button type="submit" name="cancel" id="cancelBtn" class="btn btn-danger"
            <?= $isDaThanhToan ? 'disabled' : '' ?>>
            Hủy đơn hàng
        </button>

        <a href="<?= ($_GET['back'] ?? '') === 'khach' ? 'donhang_khachhang.php?id=' . ($_GET['id_khach'] ?? 0) : 'danhsachdonhang.php' ?>" class="btn btn-secondary">
            Quay lại
        </a>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const cancelBtn = document.getElementById('cancelBtn');
        if (cancelBtn && !cancelBtn.disabled) {
            cancelBtn.closest('form').addEventListener('submit', function (e) {
                if (e.submitter === cancelBtn && !confirm('Bạn có chắc chắn muốn hủy đơn hàng này?')) {
                    e.preventDefault();
                }
            });
        }
    });
</script>

<?php include 'includes/footer.php'; ?>
