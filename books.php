<?php
require_once 'includes/db.php';
$pageTitle = 'Books - Dreamy Pages';
$activePage = 'books';

$categories = [
    'fiction'        => 'Fiction',
    'nonfiction'     => 'Non-Fiction',
    'mystery'        => 'Mystery',
    'sciencefiction' => 'Science Fiction',
    'fantasy'        => 'Fantasy',
    'selfhelp'       => 'Self-Help',
];

require 'includes/header.php';
?>
<section class="categories">
    <h2>Book Categories</h2>
    <div class="category-list">
        <?php foreach ($categories as $slug => $label): ?>
            <div class="category-item">
                <a href="category.php?genre=<?= urlencode($slug) ?>"><?= htmlspecialchars($label) ?></a>
            </div>
        <?php endforeach; ?>
    </div>
</section>
<?php require 'includes/footer.php'; ?>
