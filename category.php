<?php
require_once 'includes/db.php';

$labels = [
    'fiction'        => 'Fiction',
    'nonfiction'     => 'Non-Fiction',
    'mystery'        => 'Mystery',
    'sciencefiction' => 'Science Fiction',
    'fantasy'        => 'Fantasy',
    'selfhelp'       => 'Self-Help',
];

$genre = $_GET['genre'] ?? '';
if (!array_key_exists($genre, $labels)) {
    header('Location: books.php');
    exit;
}

$pageTitle = $labels[$genre] . ' Books - Dreamy Pages';
$activePage = 'books';

$stmt = $pdo->prepare('SELECT * FROM books WHERE genre = :genre ORDER BY id');
$stmt->execute(['genre' => $genre]);
$books = $stmt->fetchAll();

require 'includes/header.php';
?>
<div class="page-header"><?= htmlspecialchars($labels[$genre]) ?> Collection - Dreamy Pages</div>

<section>
    <div class="book-container">
        <?php foreach ($books as $book): ?>
            <?php
          $imgFile = 'assets/images/' . $book['image'];
          $imgSrc  = (file_exists($imgFile) && $book['image'] !== 'placeholder.svg')
              ? $imgFile
              : 'cover.php?title=' . urlencode($book['title'])
                . '&author=' . urlencode($book['author'])
                . '&genre='  . urlencode($book['genre']);
        ?>
      <div class="book-card">
                <img src="<?= $imgSrc ?>" alt="<?= htmlspecialchars($book['title']) ?>">
                <h3><?= htmlspecialchars($book['title']) ?></h3>
                <p class="author"><?= htmlspecialchars($book['author']) ?></p>
                <p class="price">₹<?= htmlspecialchars($book['price']) ?></p>
                <?php if ($book['stock'] <= 0): ?>
                    <p class="stock-note">Out of stock</p>
                <?php else: ?>
                    <form action="cart_add.php" method="post">
                        <input type="hidden" name="book_id" value="<?= $book['id'] ?>">
                        <input type="hidden" name="redirect" value="category.php?genre=<?= urlencode($genre) ?>">
                        <button type="submit" class="btn">Add to Cart</button>
                    </form>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
        <?php if (empty($books)): ?>
            <p>No books found in this category yet.</p>
        <?php endif; ?>
    </div>
    <a href="books.php" class="back-link">Back to Books</a>
</section>
<?php require 'includes/footer.php'; ?>
