<?php
require_once 'includes/db.php';

if (empty($_SESSION['cart'])) {
    header('Location: cart.php');
    exit;
}

if (!is_logged_in()) {
    // Send the user to log in first, then bounce them back here
    $_SESSION['after_login_redirect'] = 'checkout.php';
    header('Location: login.php');
    exit;
}

$userId = $_SESSION['user_id'];
$ids = array_keys($_SESSION['cart']);
$placeholders = implode(',', array_fill(0, count($ids), '?'));
$stmt = $pdo->prepare("SELECT * FROM books WHERE id IN ($placeholders)");
$stmt->execute($ids);
$books = $stmt->fetchAll();

$total = 0;
foreach ($books as $book) {
    $total += $book['price'] * $_SESSION['cart'][$book['id']];
}

try {
    $pdo->beginTransaction();

    $orderStmt = $pdo->prepare('INSERT INTO orders (user_id, total_amount, status) VALUES (?, ?, ?)');
    $orderStmt->execute([$userId, $total, 'placed']);
    $orderId = $pdo->lastInsertId();

    $itemStmt = $pdo->prepare('INSERT INTO order_items (order_id, book_id, quantity, price) VALUES (?, ?, ?, ?)');
    $stockStmt = $pdo->prepare('UPDATE books SET stock = GREATEST(stock - ?, 0) WHERE id = ?');

    foreach ($books as $book) {
        $qty = $_SESSION['cart'][$book['id']];
        $itemStmt->execute([$orderId, $book['id'], $qty, $book['price']]);
        $stockStmt->execute([$qty, $book['id']]);
    }

    $pdo->commit();
    $_SESSION['cart'] = [];
    header('Location: orders.php?success=1');
    exit;
} catch (Exception $e) {
    $pdo->rollBack();
    die('Checkout failed: ' . $e->getMessage());
}
