<?php
require_once '../config/database.php';
require_once '../includes/auth.php';
cek_login();
$id = (int)($_GET['id'] ?? 0);
if ($id > 0) mysqli_query($conn, "DELETE FROM margin_penjualan WHERE idmargin_penjualan=$id");
header("Location: index.php");
exit;
