<?php
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $author = $_POST['author'];
    $isbn = $_POST['isbn'];
    $publication_year = $_POST['publication_year'];
    $category = $_POST['category'];
    $quantity = $_POST['quantity'];

    try {
        $stmt = $pdo->prepare("INSERT INTO books (title, author, isbn, publication_year, category, quantity, available_quantity) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$title, $author, $isbn, $publication_year, $category, $quantity, $quantity]);
        
        header("Location: books.php?success=Book added successfully");
        exit();
    } catch (PDOException $e) {
        header("Location: books.php?error=" . urlencode($e->getMessage()));
        exit();
    }
} else {
    header("Location: books.php");
    exit();
}
?>