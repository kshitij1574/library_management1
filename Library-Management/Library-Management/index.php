<?php include 'config.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Library Management System</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <header>
        <div class="container">
            <div id="branding">
                <h1><span class="highlight"><i class="fas fa-book-open"></i> MyLibrary</span></h1>
            </div>
            <nav>
                <ul>
                    <li class="current"><a href="index.php"><i class="fas fa-home"></i> Home</a></li>
                    <li><a href="books.php"><i class="fas fa-book"></i> Books</a></li>
                    <li><a href="members.php"><i class="fas fa-users"></i> Members</a></li>
                    <li><a href="loans.php"><i class="fas fa-exchange-alt"></i> Loans</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <div class="container">
        <section class="hero">
            <div class="hero-content">
                <h2>Welcome to MyLibrary</h2>
                <p>Manage your library collection efficiently with our comprehensive system</p>
                <div class="hero-buttons">
                    <a href="books.php" class="btn btn-large"><i class="fas fa-book"></i> Browse Books</a>
                    <a href="members.php" class="btn btn-large"><i class="fas fa-user-plus"></i> Add Members</a>
                </div>
            </div>
            <div class="hero-image">
                <img src="assets/library-hero.jpg" alt="Library bookshelf">
            </div>
        </section>

        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-book"></i>
                </div>
                <div class="stat-info">
                    <h3>Total Books</h3>
                    <p>
                        <?php
                        $stmt = $pdo->query("SELECT COUNT(*) FROM books");
                        echo number_format($stmt->fetchColumn());
                        ?>
                    </p>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-users"></i>
                </div>
                <div class="stat-info">
                    <h3>Total Members</h3>
                    <p>
                        <?php
                        $stmt = $pdo->query("SELECT COUNT(*) FROM members");
                        echo number_format($stmt->fetchColumn());
                        ?>
                    </p>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-exchange-alt"></i>
                </div>
                <div class="stat-info">
                    <h3>Active Loans</h3>
                    <p>
                        <?php
                        $stmt = $pdo->query("SELECT COUNT(*) FROM loans WHERE status = 'on loan'");
                        echo number_format($stmt->fetchColumn());
                        ?>
                    </p>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="stat-info">
                    <h3>Overdue Loans</h3>
                    <p>
                        <?php
                        $stmt = $pdo->query("SELECT COUNT(*) FROM loans WHERE status = 'overdue'");
                        echo number_format($stmt->fetchColumn());
                        ?>
                    </p>
                </div>
            </div>
        </div>

        <div class="recent-activity">
            <section class="card">
                <h2><i class="fas fa-history"></i> Recent Activity</h2>
                <div class="activity-tabs">
                    <button class="tab-btn active" onclick="openTab('recent-loans')">Recent Loans</button>
                    <button class="tab-btn" onclick="openTab('recent-returns')">Recent Returns</button>
                    <button class="tab-btn" onclick="openTab('new-books')">New Books</button>
                </div>

                <div id="recent-loans" class="tab-content" style="display: block;">
                    <table>
                        <thead>
                            <tr>
                                <th>Book</th>
                                <th>Member</th>
                                <th>Loan Date</th>
                                <th>Due Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $stmt = $pdo->query("
                                SELECT b.title, m.name, l.loan_date, l.due_date 
                                FROM loans l
                                JOIN books b ON l.book_id = b.book_id
                                JOIN members m ON l.member_id = m.member_id
                                WHERE l.status = 'on loan'
                                ORDER BY l.loan_date DESC
                                LIMIT 5
                            ");
                            
                            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                echo "<tr>";
                                echo "<td>" . htmlspecialchars($row['title']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['name']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['loan_date']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['due_date']) . "</td>";
                                echo "</tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>

                <div id="recent-returns" class="tab-content">
                    <table>
                        <thead>
                            <tr>
                                <th>Book</th>
                                <th>Member</th>
                                <th>Return Date</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $stmt = $pdo->query("
                                SELECT b.title, m.name, l.return_date, l.status 
                                FROM loans l
                                JOIN books b ON l.book_id = b.book_id
                                JOIN members m ON l.member_id = m.member_id
                                WHERE l.status = 'returned'
                                ORDER BY l.return_date DESC
                                LIMIT 5
                            ");
                            
                            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                echo "<tr>";
                                echo "<td>" . htmlspecialchars($row['title']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['name']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['return_date']) . "</td>";
                                echo "<td><span class='status-returned'>" . htmlspecialchars($row['status']) . "</span></td>";
                                echo "</tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>

                <div id="new-books" class="tab-content">
                    <table>
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Author</th>
                                <th>Year</th>
                                <th>Available</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $stmt = $pdo->query("
                                SELECT title, author, publication_year, available_quantity 
                                FROM books 
                                ORDER BY book_id DESC
                                LIMIT 5
                            ");
                            
                            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                echo "<tr>";
                                echo "<td>" . htmlspecialchars($row['title']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['author']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['publication_year']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['available_quantity']) . "</td>";
                                echo "</tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </section>
        </div>
    </div>

    <footer>
        <div class="container">
            <p>MyLibrary Management System &copy; <?php echo date('Y'); ?></p>
        </div>
    </footer>

    <script>
        function openTab(tabName) {
            // Hide all tab contents
            const tabContents = document.getElementsByClassName('tab-content');
            for (let i = 0; i < tabContents.length; i++) {
                tabContents[i].style.display = 'none';
            }
            
            // Remove active class from all buttons
            const tabButtons = document.getElementsByClassName('tab-btn');
            for (let i = 0; i < tabButtons.length; i++) {
                tabButtons[i].classList.remove('active');
            }
            
            // Show the selected tab and mark button as active
            document.getElementById(tabName).style.display = 'block';
            event.currentTarget.classList.add('active');
        }
    </script>
</body>
</html>