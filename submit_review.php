<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['userId'])) {
    $file = __DIR__ . "/reviews.json";
    $reviews = file_exists($file) ? json_decode(file_get_contents($file), true) : [];
    $reviews[] = [
        'pid' => $_POST['pid'],
        'user' => $_SESSION['username'] ?? 'Customer',
        'rating' => (int)$_POST['rating'],
        'comment' => htmlspecialchars($_POST['comment']),
        'date' => date('Y-m-d')
    ];
    file_put_contents($file, json_encode($reviews, JSON_PRETTY_PRINT));
    header("Location: product.php?pid=" . $_POST['pid']);
    exit;
}