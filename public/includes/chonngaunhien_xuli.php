<?php
include 'cauhinh.php';

$sql = "SELECT id FROM giay ORDER BY RAND() LIMIT 1";
$result = mysqli_query($conn, $sql);

if ($row = mysqli_fetch_assoc($result)) {
    header("Location: ../giaychitiet.php?id=" . $row['id']);
    exit;
} else {
    header("Location: ../index.php");
    exit;
}
