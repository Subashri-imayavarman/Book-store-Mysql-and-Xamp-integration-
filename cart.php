<?php
require_once 'includes/db.php';
$pageTitle = 'Your Cart - Dreamy Pages';
$activePage = 'cart';

$cartItems = [];
$grandTotal = 0;

if (!empty($_SESSION['cart'])) {
    $ids = array_keys($_SESSION['cart']);
    $placeholders = implode(',', array_fill(0, count($ids), '?'));
    $stmt = $pdo->prepare("SELECT * FROM books WHERE id IN ($placeholders)");
    $stmt->execute($ids);
    $books = $stmt->fetchAll();

    foreach ($books as $book) {
        $qty = $_SESSION['cart'][$book['id']];
        $lineTotal = $qty * $book['price'];
        $grandTotal += $lineTotal;
        $cartItems[] = array_merge($book, ['qty' => $qty, 'line_total' => $lineTotal]);
    }
}

require 'includes/header.php';
?>
<section class="cart-container">
    <h2>Your Cart</h2>

    <?php if (empty($cartItems)): ?>
        <p>Your cart is empty. <a href="books.php">Browse books</a> to add something.</p>
    <?php else: ?>
        <div id="cart-items">
            <?php foreach ($cartItems as $item): ?>
                <div class="cart-item">
                    <img src="assets/images/<?= htmlspecialchars($item['image']) ?>"
                         onerror="this.src='assets/images/placeholder.svg'" alt="<?= htmlspecialchars($item['title']) ?>">
                    <div class="cart-item-details">
                        <strong><?= htmlspecialchars($item['title']) ?></strong><br>
                        ₹<?= htmlspecialchars($item['price']) ?> each &middot; Subtotal: ₹<?= number_format($item['line_total'], 2) ?>
                    </div>
                    <form action="cart_update.php" method="post" style="display:flex; align-items:center; gap:6px;">
                        <input type="hidden" name="book_id" value="<?= $item['id'] ?>">
                        <input class="qty-input" type="number" name="quantity" min="1" value="<?= $item['qty'] ?>">
                        <button type="submit" class="btn btn-secondary">Update</button>
                        <button type="submit" name="action" value="remove" class="btn btn-danger">Remove</button>
                    </form>
                </div>
            <?php endforeach; ?>
        </div>

        <p class="cart-total">Total: ₹<?= number_format($grandTotal, 2) ?></p>

        <div class="cart-actions">
            <a href="books.php" class="btn btn-secondary">Continue Shopping</a>
            <form action="checkout.php" method="post">
                <button type="submit" class="btn">Proceed to Checkout</button>
            </form>
        </div>
    <?php endif; ?>
</section>
<?php require 'includes/footer.php'; ?>
