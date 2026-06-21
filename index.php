<?php
require_once 'includes/db.php';
$pageTitle  = 'Dreamy Pages Bookstore';
$activePage = 'home';

// 4 featured books
$featured = $pdo->query('SELECT * FROM books ORDER BY id LIMIT 4')->fetchAll();

// Bestsellers: 4 more books
$bestsellers = $pdo->query('SELECT * FROM books ORDER BY id DESC LIMIT 4')->fetchAll();

// Book counts per genre for stats
$stats = $pdo->query('SELECT genre, COUNT(*) as cnt FROM books GROUP BY genre')->fetchAll();
$totalBooks = $pdo->query('SELECT COUNT(*) FROM books')->fetchColumn();

require 'includes/header.php';
?>

<!-- ===== HERO ===== -->
<section class="hero">
  <img src="assets/images/hero.jpg" alt="Dreamy Pages Bookstore">
  <div class="hero-overlay">
    <h2>Welcome to <span>Dreamy Pages</span></h2>
    <p>A cozy corner of the internet for book lovers. Curated reads, pastel vibes.</p>
    <div class="hero-btns">
      <a href="books.php" class="btn">Browse All Books</a>
      <a href="register.php" class="btn btn-outline">Join the Club</a>
    </div>
  </div>
</section>

<!-- ===== STATS STRIP ===== -->
<section class="stats-strip">
  <div class="stat-item">📚 <strong><?= $totalBooks ?>+</strong> Books</div>
  <div class="stat-item">🎭 <strong>6</strong> Genres</div>
  <div class="stat-item">🚚 <strong>Fast</strong> Delivery</div>
  <div class="stat-item">⭐ <strong>Curated</strong> Picks</div>
</section>

<!-- ===== FEATURED BOOKS ===== -->
<section class="home-section">
  <div class="section-header">
    <h2>✨ Featured Books</h2>
    <a href="books.php" class="see-all">See all →</a>
  </div>
  <div class="book-container">
    <?php foreach ($featured as $book): ?>
      <?php
        $imgFile = 'assets/images/' . $book['image'];
        $imgSrc  = file_exists($imgFile) && $book['image'] !== 'placeholder.svg'
            ? $imgFile
            : 'cover.php?title=' . urlencode($book['title'])
              . '&author=' . urlencode($book['author'])
              . '&genre='  . urlencode($book['genre']);
      ?>
      <div class="book-card">
        <img src="<?= $imgSrc ?>" alt="<?= htmlspecialchars($book['title']) ?>">
        <h3><?= htmlspecialchars($book['title']) ?></h3>
        <p class="author"><?= htmlspecialchars($book['author']) ?></p>
        <p class="price">₹<?= $book['price'] ?></p>
        <form action="cart_add.php" method="post">
          <input type="hidden" name="book_id"  value="<?= $book['id'] ?>">
          <input type="hidden" name="redirect" value="index.php">
          <button type="submit" class="btn">Add to Cart</button>
        </form>
      </div>
    <?php endforeach; ?>
  </div>
</section>

<!-- ===== WHY READ BANNER ===== -->
<section class="why-read-section">
  <div class="why-read-inner">
    <img src="assets/images/why-read.jpg" alt="Why You Should Read A Book">
    <div class="why-read-text">
      <h2>Why Read Books?</h2>
      <ul>
        <li>📖 Improves focus and memory</li>
        <li>🧠 Expands vocabulary and knowledge</li>
        <li>😌 Reduces stress and anxiety</li>
        <li>🌍 Builds empathy and perspective</li>
        <li>💡 Sparks creativity and imagination</li>
      </ul>
      <a href="books.php" class="btn" style="margin-top:16px;">Start Reading Today</a>
    </div>
  </div>
</section>

<!-- ===== BROWSE BY GENRE ===== -->
<section class="home-section">
  <div class="section-header"><h2>📂 Browse by Genre</h2></div>
  <div class="genre-grid">
    <?php
    $genreInfo = [
      'fiction'        => ['emoji'=>'📝','label'=>'Fiction'],
      'nonfiction'     => ['emoji'=>'🔍','label'=>'Non-Fiction'],
      'mystery'        => ['emoji'=>'🕵️','label'=>'Mystery'],
      'sciencefiction' => ['emoji'=>'🚀','label'=>'Sci-Fi'],
      'fantasy'        => ['emoji'=>'🧙','label'=>'Fantasy'],
      'selfhelp'       => ['emoji'=>'💪','label'=>'Self-Help'],
    ];
    foreach ($genreInfo as $slug => $info):
    ?>
      <a href="category.php?genre=<?= $slug ?>" class="genre-card genre-<?= $slug ?>">
        <span class="genre-emoji"><?= $info['emoji'] ?></span>
        <span class="genre-label"><?= $info['label'] ?></span>
      </a>
    <?php endforeach; ?>
  </div>
</section>

<!-- ===== NEW ARRIVALS ===== -->
<section class="home-section">
  <div class="section-header">
    <h2>🆕 New Arrivals</h2>
    <a href="books.php" class="see-all">See all →</a>
  </div>
  <div class="book-container">
    <?php foreach ($bestsellers as $book): ?>
      <?php
        $imgFile = 'assets/images/' . $book['image'];
        $imgSrc  = file_exists($imgFile) && $book['image'] !== 'placeholder.svg'
            ? $imgFile
            : 'cover.php?title=' . urlencode($book['title'])
              . '&author=' . urlencode($book['author'])
              . '&genre='  . urlencode($book['genre']);
      ?>
      <div class="book-card">
        <img src="<?= $imgSrc ?>" alt="<?= htmlspecialchars($book['title']) ?>">
        <h3><?= htmlspecialchars($book['title']) ?></h3>
        <p class="author"><?= htmlspecialchars($book['author']) ?></p>
        <p class="price">₹<?= $book['price'] ?></p>
        <form action="cart_add.php" method="post">
          <input type="hidden" name="book_id"  value="<?= $book['id'] ?>">
          <input type="hidden" name="redirect" value="index.php">
          <button type="submit" class="btn">Add to Cart</button>
        </form>
      </div>
    <?php endforeach; ?>
  </div>
</section>

<?php require 'includes/footer.php'; ?>
