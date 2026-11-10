<?php
session_start();
require_once 'config/db.php';

$email = '';
$error = '';

if (isset($_SESSION['user_id'])) {
    header('Location: dashboard.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';

    $sql = 'SELECT id, name, password FROM users WHERE email = ?';
    $statement = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($statement, 's', $email);
    mysqli_stmt_execute($statement);
    $result = mysqli_stmt_get_result($statement);

    if (mysqli_num_rows($result) == 1) {
        $user = mysqli_fetch_assoc($result);
        if (password_verify($password, $user['password'])) {
            session_regenerate_id(true);
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            header('Location: dashboard.php');
            exit;
        }
    }
    $error = 'Email or password is incorrect.';
    mysqli_stmt_close($statement);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log in | Travel Mate</title>
    <link rel="stylesheet" href="css/login.css">
</head>
<body>
    <header class="navbar">
        <a class="brand" href="index.php">Travel Mate</a>
        <a href="register.php">Create an account</a>
    </header>

    <main class="form-page">
        <section class="form-card">
            <p class="eyebrow">WELCOME BACK</p>
            <h1>Log in to Travel Mate</h1>
            <p class="muted">Your next great destination is waiting.</p>

            <?php if (isset($_GET['registered'])) { ?>
                <p class="message success">Your account is ready. Please log in.</p>
            <?php } ?>
            <?php if ($error != '') { ?>
                <p class="message error"><?php echo htmlspecialchars($error); ?></p>
            <?php } ?>

            <form method="POST" action="login.php">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required>

                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>

                <button class="button" type="submit">Log in</button>
            </form>
            <p class="form-note">New to Travel Mate? <a href="register.php">Create an account</a></p>
        </section>
    </main>
</body>
</html>
