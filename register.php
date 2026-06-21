<?php
require_once 'includes/db.php';
$pageTitle = 'Register - Dreamy Pages';
$activePage = 'register';

if (is_logged_in()) {
    header('Location: index.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name     = trim($_POST['name'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm  = $_POST['confirm'] ?? '';

    if ($name === '' || $email === '' || $password === '') {
        $error = 'Please fill in all fields.';
    } elseif ($password !== $confirm) {
        $error = 'Passwords do not match.';
    } elseif (strlen($password) < 6) {
        $error = 'Password must be at least 6 characters.';
    } else {
        $check = $pdo->prepare('SELECT id FROM users WHERE email = ?');
        $check->execute([$email]);
        if ($check->fetch()) {
            $error = 'An account with that email already exists.';
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $insert = $pdo->prepare('INSERT INTO users (name, email, password) VALUES (?, ?, ?)');
            $insert->execute([$name, $email, $hash]);

            $_SESSION['user_id']   = $pdo->lastInsertId();
            $_SESSION['user_name'] = $name;

            header('Location: index.php');
            exit;
        }
    }
}

require 'includes/header.php';
?>
<div class="auth-wrap">
    <div class="auth-card">
        <h2>Create Account</h2>
        <?php if ($error): ?><div class="alert alert-error"><?= htmlspecialchars($error) ?></div><?php endif; ?>
        <form method="post">
            <input type="text" name="name" placeholder="Full Name" value="<?= htmlspecialchars($_POST['name'] ?? '') ?>" required>
            <input type="email" name="email" placeholder="Email" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required>
            <input type="password" name="password" placeholder="Password" required>
            <input type="password" name="confirm" placeholder="Confirm Password" required>
            <button type="submit" class="btn">Register</button>
        </form>
        <p class="switch-link">Already have an account? <a href="login.php">Login</a></p>
    </div>
</div>
<?php require 'includes/footer.php'; ?>
