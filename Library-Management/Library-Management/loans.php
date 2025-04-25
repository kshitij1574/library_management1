<?php include 'config.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Loan Management | Library System</title>
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
                    <li class="current"><a href="loans.php">Loans</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <div class="container">
        <section class="card">
            <h2>New Loan</h2>
            <form action="add_loan.php" method="post">
                <div class="form-group">
                    <label for="book_id">Book</label>
                    <select id="book_id" name="book_id" required>
                        <option value="">Select a book</option>
                        <?php
                        $stmt = $pdo->query("SELECT book_id, title FROM books WHERE available_quantity > 0 ORDER BY title");
                        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                            echo "<option value='" . $row['book_id'] . "'>" . htmlspecialchars($row['title']) . "</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="member_id">Member</label>
                    <select id="member_id" name="member_id" required>
                        <option value="">Select a member</option>
                        <?php
                        $stmt = $pdo->query("SELECT member_id, name FROM members ORDER BY name");
                        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                            echo "<option value='" . $row['member_id'] . "'>" . htmlspecialchars($row['name']) . "</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="due_date">Due Date</label>
                    <input type="date" id="due_date" name="due_date" required>
                </div>
                <button type="submit">Create Loan</button>
            </form>
        </section>

        <section class="card">
            <h2>Active Loans</h2>
            <table>
                <thead>
                    <tr>
                        <th>Book</th>
                        <th>Member</th>
                        <th>Loan Date</th>
                        <th>Due Date</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $stmt = $pdo->query("
                        SELECT l.loan_id, b.title, m.name, l.loan_date, l.due_date, l.status 
                        FROM loans l
                        JOIN books b ON l.book_id = b.book_id
                        JOIN members m ON l.member_id = m.member_id
                        WHERE l.status = 'on loan' OR l.status = 'overdue'
                        ORDER BY l.due_date
                    ");
                    
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['title']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['name']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['loan_date']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['due_date']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['status']) . "</td>";
                        echo "<td>
                                <a href='return_book.php?id=" . $row['loan_id'] . "' class='btn'>Return</a>
                              </td>";
                        echo "</tr>";
                    }
                    ?>
                </tbody>
            </table>
        </section>

        <section class="card">
            <h2>Loan History</h2>
            <table>
                <thead>
                    <tr>
                        <th>Book</th>
                        <th>Member</th>
                        <th>Loan Date</th>
                        <th>Due Date</th>
                        <th>Return Date</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $stmt = $pdo->query("
                        SELECT l.loan_id, b.title, m.name, l.loan_date, l.due_date, l.return_date, l.status 
                        FROM loans l
                        JOIN books b ON l.book_id = b.book_id
                        JOIN members m ON l.member_id = m.member_id
                        WHERE l.status = 'returned'
                        ORDER BY l.return_date DESC
                        LIMIT 10
                    ");
                    
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['title']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['name']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['loan_date']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['due_date']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['return_date']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['status']) . "</td>";
                        echo "</tr>";
                    }
                    ?>
                </tbody>
            </table>
        </section>
    </div>
</body>
</html>