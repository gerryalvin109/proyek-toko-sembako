<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function cek_login() {
    if (!isset($_SESSION['iduser'])) {
        header("Location: " . BASE_URL . "/login.php");
        exit;
    }
}
?>
