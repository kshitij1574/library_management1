<?php
include 'config.php';

if (!isset($_GET['id'])) {
    header("Location: books.php");
    exit();
}

$book_id = $_GET['id'];

try {
    // Check if the book is currently on loan
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM loans WHERE book_id = ? AND status = 'on loan'");
    $stmt->execute([$book_id]);
    $active_loans = $stmt->fetchColumn();
    
    if ($active_loans > 0) {
        header("Location: books.php?error=Cannot delete book with active loans");
        exit();
    }
    
    // Delete the book
    $stmt = $pdo->prepare("DELETE FROM books WHERE book_id = ?");
    $stmt->execute([$book_id]);
    
    header("Location: books.php?success=Book deleted successfully");
    exit();
} catch (PDOException $e) {
    header("Location: books.php?error=" . urlencode($e->getMessage()));
    exit();
}
?>