<?php
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $book_id = $_POST['book_id'];
    $member_id = $_POST['member_id'];
    $due_date = $_POST['due_date'];

    try {
        // Start transaction
        $pdo->beginTransaction();
        
        // Check book availability
        $stmt = $pdo->prepare("SELECT available_quantity FROM books WHERE book_id = ? FOR UPDATE");
        $stmt->execute([$book_id]);
        $book = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$book || $book['available_quantity'] <= 0) {
            throw new Exception("Book is not available for loan");
        }
        
        // Create loan
        $stmt = $pdo->prepare("INSERT INTO loans (book_id, member_id, due_date) VALUES (?, ?, ?)");
        $stmt->execute([$book_id, $member_id, $due_date]);
        
        // Update book quantity
        $stmt = $pdo->prepare("UPDATE books SET available_quantity = available_quantity - 1 WHERE book_id = ?");
        $stmt->execute([$book_id]);
        
        // Commit transaction
        $pdo->commit();
        
        header("Location: loans.php?success=Loan created successfully");
        exit();
    } catch (Exception $e) {
        $pdo->rollBack();
        header("Location: loans.php?error=" . urlencode($e->getMessage()));
        exit();
    }
} else {
    header("Location: loans.php");
    exit();
}
?>