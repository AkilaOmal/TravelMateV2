<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

require_once 'config/db.php';
$user_id = $_SESSION['user_id'];
$sql = 'SELECT COUNT(*) AS place_count FROM places WHERE user_id = ?';
$statement = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($statement, 'i', $user_id);
mysqli_stmt_execute($statement);
$result = mysqli_stmt_get_result($statement);
$row = mysqli_fetch_assoc($result);
$place_count = $row['place_count'];
mysqli_stmt_close($statement);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your dashboard | Travel Mate</title>
    <link rel="stylesheet" href="css/dashboard.css">
</head>
<body>
    <header class="navbar">
        <a class="brand" href="dashboard.php">Travel Mate</a>
        <nav>
            <a href="explore.php">Explore</a>
            <a href="my-places.php">My places</a>
            <a href="logout.php">Log out</a>
        </nav>
    </header>

    <main class="dashboard">
        <section class="welcome">
            <p class="eyebrow">YOUR TRAVEL SPACE</p>
            <h1>Welcome, <?php echo htmlspecialchars($_SESSION['user_name']); ?>!</h1>
            <p>Keep track of the places you love and find ideas for where to go next.</p>
        </section>

        <section class="summary">
            <p class="eyebrow">YOUR CONTRIBUTION</p>
            <strong><?php echo (int)$place_count; ?></strong>
            <p><?php echo $place_count == 1 ? 'place shared' : 'places shared'; ?></p>
        </section>

        <section class="actions">
            <a class="action-card add-card" href="add-place.php">
                <span class="action-icon">+</span>
                <h2>Add a new place</h2>
                <p>Share a destination with the community.</p>
            </a>
            <a class="action-card explore-card" href="explore.php">
                <span class="action-icon">&#8599;</span>
                <h2>Explore all places</h2>
                <p>Get inspired by destinations from other travelers.</p>
            </a>
            <a class="action-card places-card" href="my-places.php">
                <span class="action-icon">&#9825;</span>
                <h2>View my places</h2>
                <p>See and manage the places you have shared.</p>
            </a>
        </section>
    </main>
</body>
</html>
