<?php
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];

    try {
        $stmt = $pdo->prepare("INSERT INTO members (name, email, phone, address) VALUES (?, ?, ?, ?)");
        $stmt->execute([$name, $email, $phone, $address]);
        
        header("Location: members.php?success=Member added successfully");
        exit();
    } catch (PDOException $e) {
        header("Location: members.php?error=" . urlencode($e->getMessage()));
        exit();
    }
} else {
    header("Location: members.php");
    exit();
}
?>