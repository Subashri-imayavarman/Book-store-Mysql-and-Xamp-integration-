<?php
require_once 'includes/db.php';
$pageTitle = 'Login - Dreamy Pages';
$activePage = 'login';

if (is_logged_in()) {
    header('Location: index.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    $stmt = $pdo->prepare('SELECT * FROM users WHERE email = ?');
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id']   = $user['id'];
        $_SESSION['user_name'] = $user['name'];

        $redirect = $_SESSION['after_login_redirect'] ?? 'index.php';
        unset($_SESSION['after_login_redirect']);

        header('Location: ' . $redirect);
        exit;
    } else {
        $error = 'Invalid email or password.';
    }
}

require 'includes/header.php';
?>
<div class="auth-wrap">
    <div class="auth-card">
        <h2>Welcome Back</h2>
        <?php if ($error): ?><div class="alert alert-error"><?= htmlspecialchars($error) ?></div><?php endif; ?>
        <form method="post">
            <input type="email" name="email" placeholder="Email" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit" class="btn">Login</button>
        </form>
        <p class="switch-link">No account yet? <a href="register.php">Register</a></p>
    </div>
</div>
<?php require 'includes/footer.php'; ?>
