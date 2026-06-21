<?php
require_once 'includes/db.php';

$bookId   = (int)($_POST['book_id'] ?? 0);
$redirect = $_POST['redirect'] ?? 'books.php';

if ($bookId > 0) {
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }
    $_SESSION['cart'][$bookId] = ($_SESSION['cart'][$bookId] ?? 0) + 1;
}

header('Location: ' . $redirect);
exit;
