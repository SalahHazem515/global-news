<?php
require_once 'config/database.php';

if (!isset($_SESSION['user_id']) || $_SERVER['REQUEST_METHOD'] != 'POST') {
    header('Location: index.php');
    exit;
}

$article_id = (int)$_POST['article_id'];

if (!function_exists('sanitizeInput')) {
    function sanitizeInput($data) {
        return htmlspecialchars(trim($data));
    }
}
$comment_text = sanitizeInput($_POST['comment_text']);
$user_id = $_SESSION['user_id'];

if (!empty($comment_text) && $article_id > 0) {
    $conn = getDBConnection();
    
    if ($conn) {
        $query = "INSERT INTO comments (article_id, user_id, comment_text, timestamp) VALUES (?, ?, ?, NOW())";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("iis", $article_id, $user_id, $comment_text);
        
        if ($stmt->execute()) {
            header("Location: article.php?id={$article_id}#comments");
        } else {
            header("Location: article.php?id={$article_id}?error=comment_failed");
        }
        
        $stmt->close();
        $conn->close();
    } else {
        header("Location: article.php?id={$article_id}?error=database_connection_failed");
    }
} else {
    header("Location: article.php?id={$article_id}?error=invalid_comment");
}
exit;
?>