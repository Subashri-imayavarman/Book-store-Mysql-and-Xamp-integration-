<?php
require_once 'includes/db.php';
$pageTitle = 'Contact Us - Dreamy Pages';
$activePage = 'contact';

$success = false;
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name    = trim($_POST['name'] ?? '');
    $email   = trim($_POST['email'] ?? '');
    $phone   = trim($_POST['phone'] ?? '');
    $subject = trim($_POST['subject'] ?? '');
    $message = trim($_POST['message'] ?? '');

    if ($name === '' || $email === '' || $message === '') {
        $error = 'Please fill in your name, email, and message.';
    } else {
        $stmt = $pdo->prepare(
            'INSERT INTO contact_messages (name, email, phone, subject, message) VALUES (?, ?, ?, ?, ?)'
        );
        $stmt->execute([$name, $email, $phone, $subject, $message]);
        $success = true;
    }
}

require 'includes/header.php';
?>
<div class="contact-container">
    <h2>Get in Touch</h2>

    <?php if ($success): ?>
        <div class="alert alert-success">Message sent successfully!</div>
    <?php endif; ?>
    <?php if ($error): ?>
        <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="post">
        <input type="text" name="name" placeholder="Your Name" required>
        <input type="email" name="email" placeholder="Your Email" required>
        <input type="tel" name="phone" placeholder="Your Phone Number (Optional)">
        <select name="subject">
            <option value="">Select Inquiry Type</option>
            <option>Order Issue</option>
            <option>Book Recommendation</option>
            <option>Partnership</option>
            <option>Other</option>
        </select>
        <textarea name="message" placeholder="Your Message" rows="5" required></textarea>
        <button type="submit" class="btn">Send Message</button>
    </form>

    <div class="social-links" style="margin-top:20px;">
        <a href="#">Facebook</a> &middot; <a href="#">Twitter</a> &middot; <a href="#">Instagram</a>
    </div>
</div>
<?php require 'includes/footer.php'; ?>
