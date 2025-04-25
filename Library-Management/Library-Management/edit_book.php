<?php
include 'config.php';

if (!isset($_GET['id'])) {
    header("Location: books.php");
    exit();
}

$book_id = $_GET['id'];

// Fetch book details
$stmt = $pdo->prepare("SELECT * FROM books WHERE book_id = ?");
$stmt->execute([$book_id]);
$book = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$book) {
    header("Location: books.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $author = $_POST['author'];
    $isbn = $_POST['isbn'];
    $publication_year = $_POST['publication_year'];
    $category = $_POST['category'];
    $quantity = $_POST['quantity'];
    
    // Calculate new available quantity
    $original_quantity = $book['quantity'];
    $available_quantity = $book['available_quantity'];
    $new_available_quantity = $available_quantity + ($quantity - $original_quantity);

    try {
        $stmt = $pdo->prepare("UPDATE books SET title = ?, author = ?, isbn = ?, publication_year = ?, category = ?, quantity = ?, available_quantity = ? WHERE book_id = ?");
        $stmt->execute([$title, $author, $isbn, $publication_year, $category, $quantity, $new_available_quantity, $book_id]);
        
        header("Location: books.php?success=Book updated successfully");
        exit();
    } catch (PDOException $e) {
        header("Location: edit_book.php?id=$book_id&error=" . urlencode($e->getMessage()));
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Book | Library System</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <div class="container">
            <div id="branding">
                <h1><span class="highlight">Awesome</span> Library</h1>
            </div>
            <nav>
                <ul>
                    <li><a href="index.php">Home</a></li>
                    <li><a href="books.php">Books</a></li>
                    <li><a href="members.php">Members</a></li>
                    <li><a href="loans.php">Loans</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <div class="container">
        <section class="card">
            <h2>Edit Book</h2>
            
            <?php if (isset($_GET['error'])): ?>
                <div class="alert alert-danger">
                    <?php echo htmlspecialchars($_GET['error']); ?>
                </div>
            <?php endif; ?>
            
            <form action="edit_book.php?id=<?php echo $book_id; ?>" method="post">
                <div class="form-group">
                    <label for="title">Title</label>
                    <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($book['title']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="author">Author</label>
                    <input type="text" id="author" name="author" value="<?php echo htmlspecialchars($book['author']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="isbn">ISBN</label>
                    <input type="text" id="isbn" name="isbn" value="<?php echo htmlspecialchars($book['isbn']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="publication_year">Publication Year</label>
                    <input type="number" id="publication_year" name="publication_year" value="<?php echo htmlspecialchars($book['publication_year']); ?>">
                </div>
                <div class="form-group">
                    <label for="category">Category</label>
                    <input type="text" id="category" name="category" value="<?php echo htmlspecialchars($book['category']); ?>">
                </div>
                <div class="form-group">
                    <label for="quantity">Quantity</label>
                    <input type="number" id="quantity" name="quantity" value="<?php echo htmlspecialchars($book['quantity']); ?>" min="1">
                </div>
                <button type="submit">Update Book</button>
                <a href="books.php" class="btn">Cancel</a>
            </form>
        </section>
    </div>
</body>
</html>