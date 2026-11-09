<?php
require_once 'auth.php';
require_once 'db.php';

ensure_users_table($conn);

if (isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

$error = '';
$name = '';
$email = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $password_confirmation = $_POST['password_confirmation'] ?? '';

    if (!valid_csrf_token($_POST['csrf_token'] ?? null)) {
        $error = 'Your session expired. Please submit the form again.';
    } elseif ($name === '' || strlen($name) > 255) {
        $error = 'Please enter a name of no more than 255 characters.';
    } elseif (strlen($email) > 255 || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email address.';
    } elseif (strlen($password) < 8) {
        $error = 'Password must be at least 8 characters long.';
    } elseif ($password !== $password_confirmation) {
        $error = 'Passwords do not match.';
    } else {
        $check = $conn->prepare('SELECT id FROM users WHERE email = ?');
        $check->bind_param('s', $email);
        $check->execute();
        $exists = $check->get_result()->num_rows > 0;
        $check->close();

        if ($exists) {
            $error = 'An account with that email address already exists.';
        } else {
            $password_hash = password_hash($password, PASSWORD_DEFAULT);
            $insert = $conn->prepare('INSERT INTO users (name, email, password) VALUES (?, ?, ?)');
            $insert->bind_param('sss', $name, $email, $password_hash);

            if ($insert->execute()) {
                header('Location: login.php?registered=1');
                exit;
            }

            $error = 'Unable to create your account. Please try again.';
            $insert->close();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Student Grading System</title>
</head>
<body>
    <h1>Student Grading System</h1>
    <h2>Create an account</h2>

    <?php if ($error !== ''): ?>
        <p role="alert"><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></p>
    <?php endif; ?>

    <form method="post">
        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(csrf_token(), ENT_QUOTES, 'UTF-8'); ?>">
        <label for="name">Name</label><br>
        <input id="name" name="name" type="text" maxlength="255" autocomplete="name" required
            value="<?php echo htmlspecialchars($name, ENT_QUOTES, 'UTF-8'); ?>"><br><br>

        <label for="email">Email</label><br>
        <input id="email" name="email" type="email" maxlength="255" autocomplete="email" required
            value="<?php echo htmlspecialchars($email, ENT_QUOTES, 'UTF-8'); ?>"><br><br>

        <label for="password">Password (at least 8 characters)</label><br>
        <input id="password" name="password" type="password" minlength="8" autocomplete="new-password" required><br><br>

        <label for="password_confirmation">Confirm password</label><br>
        <input id="password_confirmation" name="password_confirmation" type="password" minlength="8" autocomplete="new-password" required><br><br>

        <button type="submit">Register</button>
    </form>

    <p>Already have an account? <a href="login.php">Log in</a></p>
</body>
</html>
