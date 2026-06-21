<?php
require_once 'includes/db.php';
require_login();

$pageTitle = 'My Orders - Dreamy Pages';
$activePage = 'orders';

$ordersStmt = $pdo->prepare('SELECT * FROM orders WHERE user_id = ? ORDER BY created_at DESC');
$ordersStmt->execute([$_SESSION['user_id']]);
$orders = $ordersStmt->fetchAll();

$itemsStmt = $pdo->prepare(
    'SELECT oi.*, b.title FROM order_items oi JOIN books b ON b.id = oi.book_id WHERE oi.order_id = ?'
);

require 'includes/header.php';
?>
<section class="categories">
    <h2>My Orders</h2>

    <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success">Order placed successfully! Thank you for shopping with Dreamy Pages.</div>
    <?php endif; ?>

    <?php if (empty($orders)): ?>
        <p>You haven't placed any orders yet. <a href="books.php">Start browsing</a>.</p>
    <?php else: ?>
        <?php foreach ($orders as $order): ?>
            <?php
                $itemsStmt->execute([$order['id']]);
                $items = $itemsStmt->fetchAll();
            ?>
            <div class="order-card">
                <strong>Order #<?= $order['id'] ?></strong>
                &middot; <?= htmlspecialchars($order['status']) ?>
                &middot; <?= htmlspecialchars($order['created_at']) ?>
                <table>
                    <tr><th>Book</th><th>Qty</th><th>Price</th></tr>
                    <?php foreach ($items as $item): ?>
                        <tr>
                            <td><?= htmlspecialchars($item['title']) ?></td>
                            <td><?= $item['quantity'] ?></td>
                            <td>₹<?= number_format($item['price'], 2) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </table>
                <p style="text-align:right; font-weight:bold;">Total: ₹<?= number_format($order['total_amount'], 2) ?></p>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</section>
<?php require 'includes/footer.php'; ?>
