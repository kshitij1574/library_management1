<?php
include 'config.php';

if (!isset($_GET['id'])) {
    header("Location: loans.php");
    exit();
}

$loan_id = $_GET['id'];

try {
    // Start transaction
    $pdo->beginTransaction();
    
    // Get loan details
    $stmt = $pdo->prepare("SELECT book_id FROM loans WHERE loan_id = ? AND status = 'on loan' FOR UPDATE");
    $stmt->execute([$loan_id]);
    $loan = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$loan) {
        throw new Exception("Loan not found or already returned");
    }
    
    // Update loan status
    $stmt = $pdo->prepare("UPDATE loans SET return_date = CURDATE(), status = 'returned' WHERE loan_id = ?");
    $stmt->execute([$loan_id]);
    
    // Update book quantity
    $stmt = $pdo->prepare("UPDATE books SET available_quantity = available_quantity + 1 WHERE book_id = ?");
    $stmt->execute([$loan['book_id']]);
    
    // Commit transaction
    $pdo->commit();
    
    header("Location: loans.php?success=Book returned successfully");
    exit();
} catch (Exception $e) {
    $pdo->rollBack();
    header("Location: loans.php?error=" . urlencode($e->getMessage()));
    exit();
}
?>