<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Blue Eagle Store - Giày là đam mê</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="icon" type="image/png" href="images/logo.png">
    <script src="js/dropdown-hover.js"></script>


    <!-- Bootstrap CSS & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Your custom CSS -->
    <link rel="stylesheet" href="css/style.css">

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/back-to-top.js"></script>
    <script src="js/carousel-controls.js"></script>
</head>
<body class="bg-gradient-gray-black">
    <?php include 'includes/header.php'; ?>
    <?php include 'includes/nav.php'; ?>
    <?php include 'includes/banner.php'; ?>

    <div class="my-4"><?php include 'hangmoive.php'; ?></div>
    <div class="my-4"><?php include 'giamgia.php'; ?></div>
    <div class="my-4"><?php include 'giaycaocap.php'; ?></div>
    <div class="my-4"><?php include 'includes/loaigiay.php'; ?></div>
    <div class="my-4"><?php include 'includes/thuonghieu.php'; ?></div>
    <div class="my-4"><?php include 'includes/chonngaunhien.php'; ?></div>
    <div class="my-4"><?php include 'includes/tintuc.php'; ?></div>

    <?php include 'includes/footer.php'; ?>

    <button type="button" class="btn btn-warning btn-lg rounded-circle shadow back-to-top" id="btn-back-to-top">
        <i class="bi bi-arrow-up"></i>
    </button>

    <?php include 'includes/chat.php'; ?>
</body>
</html>
