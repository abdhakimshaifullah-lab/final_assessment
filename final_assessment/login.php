<?php
require_once 'auth.php';
require_once 'db.php';

ensure_users_table($conn);

if (isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

$error = '';
$email = '';
$registered = isset($_GET['registered']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (!valid_csrf_token($_POST['csrf_token'] ?? null)) {
        $error = 'Your session expired. Please submit the form again.';
    } else {
        $sql = $conn->prepare('SELECT id, name, password FROM users WHERE email = ?');
        $sql->bind_param('s', $email);
        $sql->execute();
        $user = $sql->get_result()->fetch_assoc();
        $sql->close();

        if ($user && password_verify($password, $user['password'])) {
            session_regenerate_id(true);
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            unset($_SESSION['csrf_token']);

            header('Location: index.php');
            exit;
        }

        $error = 'Invalid username or password.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log in - Student Grading System</title>
</head>
<body>
    <h1>Student Grading System</h1>
    <h2>Log in</h2>

    <?php if ($registered): ?>
        <p>Your account has been created. Please log in.</p>
    <?php endif; ?>
    <?php if ($error !== ''): ?>
        <p role="alert"><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></p>
    <?php endif; ?>

    <form method="post">
        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(csrf_token(), ENT_QUOTES, 'UTF-8'); ?>">
        <label for="email">Email</label><br>
        <input id="email" name="email" type="email" autocomplete="email" required
            value="<?php echo htmlspecialchars($email, ENT_QUOTES, 'UTF-8'); ?>"><br><br>

        <label for="password">Password</label><br>
        <input id="password" name="password" type="password" autocomplete="current-password" required><br><br>

        <button type="submit">Log in</button>
    </form>

    <p>Don't have an account? <a href="register.php">Register</a></p>
</body>
</html>
