    <h1>Student Grading System</h1>

    <nav>
        <a href="index.php">Record</a> |
        <a href="add.php">Add Result</a> |
        <?php echo htmlspecialchars($_SESSION['user_name'] ?? '', ENT_QUOTES, 'UTF-8'); ?>
        <form method="post" action="logout.php" style="display:inline">
            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(csrf_token(), ENT_QUOTES, 'UTF-8'); ?>">
            <button type="submit">Log out</button>
        </form>
    </nav>

     <hr>