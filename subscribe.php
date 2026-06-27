<?php
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$host = 'localhost';
$user = 'root';
$password = '';
$dbname = 'news_website';

$message = '';
$success = false;

try {
    $conn = new mysqli($host, $user, $password, $dbname);
    $conn->set_charset("utf8mb4");

    $result = $conn->query("SHOW TABLES LIKE 'newsletter_subscribers'");
    if ($result->num_rows == 0) {
        $message = "❌ Database table 'newsletter_subscribers' not found.";
    } elseif ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['email'])) {
        $email = trim($_POST['email']);

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $message = "❌ Invalid email address.";
        } else {
            $stmt = $conn->prepare("INSERT INTO newsletter_subscribers (email) VALUES (?)");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $message = "✅ Thank you for subscribing!";
            $success = true;
            $stmt->close();
        }
    }
} catch (mysqli_sql_exception $e) {
    if ($e->getCode() == 1062) {
        $message = "⚠️ You are already subscribed with this email.";
    } else {
        $message = "❌ Subscription failed. Please try again. Error: " . $e->getMessage();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Subscribe to Newsletter</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&family=Roboto:wght@300;400&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
</head>

<body class="<?php echo isset($_COOKIE['theme']) && $_COOKIE['theme'] == 'dark' ? 'dark-mode' : ''; ?>">

    <div class="subscribe-container">
        <div class="subscribe-card">
            <h2>Subscribe to Our Newsletter</h2>
            <form method="POST" action="">
                <input type="email" name="email" placeholder="Enter your email..." required />
                <button type="submit" class="btn btn-primary">Subscribe</button>
            </form>
            <a href="index.php" class="btn btn-secondary">Back to Home</a>

            <?php if (!empty($message)): ?>
                <div class="message <?php echo $success ? 'success' : 'error'; ?>">
                    <?php echo $message; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

</body>

</html>