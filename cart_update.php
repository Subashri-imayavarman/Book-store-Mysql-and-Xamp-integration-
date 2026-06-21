<?php
require_once 'includes/db.php';

$bookId = (int)($_POST['book_id'] ?? 0);
$action = $_POST['action'] ?? 'update';

if ($bookId > 0 && isset($_SESSION['cart'][$bookId])) {
    if ($action === 'remove') {
        unset($_SESSION['cart'][$bookId]);
    } else {
        $qty = (int)($_POST['quantity'] ?? 1);
        if ($qty <= 0) {
            unset($_SESSION['cart'][$bookId]);
        } else {
            $_SESSION['cart'][$bookId] = $qty;
        }
    }
}

header('Location: cart.php');
exit;
