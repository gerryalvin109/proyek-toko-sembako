<?php
require_once '../config/database.php';
require_once '../includes/auth.php';
cek_login();
$id = (int)($_GET['id'] ?? 0);
if ($id > 0) {
    mysqli_query($conn, "UPDATE margin_penjualan SET status = CASE WHEN idmargin_penjualan = $id THEN 1 ELSE 0 END");
}
header("Location: index.php");
exit;
