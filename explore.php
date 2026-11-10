<?php
session_start();
require_once 'config/db.php';

$sql = 'SELECT places.*, users.name AS added_by FROM places JOIN users ON places.user_id = users.id ORDER BY places.created_at DESC';
$result = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Explore places | Travel Mate</title>
    <link rel="stylesheet" href="css/explore.css">
</head>
<body>
    <header class="navbar">
        <a class="brand" href="index.php">Travel Mate</a>
        <nav>
            <?php if (isset($_SESSION['user_id'])) { ?>
                <a href="dashboard.php">Dashboard</a>
                <a href="my-places.php">My places</a>
                <a href="logout.php">Log out</a>
            <?php } else { ?>
                <a href="login.php">Log in</a>
                <a class="button button-small" href="register.php">Join Travel Mate</a>
            <?php } ?>
        </nav>
    </header>

    <main class="page-content">
        <section class="page-heading">
            <p class="eyebrow">COMMUNITY DESTINATIONS</p>
            <h1>Explore places</h1>
            <p>Find your next favorite destination, shared by travelers like you.</p>
        </section>

        <section class="place-grid">
            <?php if (mysqli_num_rows($result) > 0) { ?>
                <?php while ($place = mysqli_fetch_assoc($result)) { ?>
                    <?php
                    $image_url = $place['image'];
                    if ($image_url == '') {
                        $image_url = 'https://images.unsplash.com/photo-1464822759023-fed622ff2c3b?auto=format&fit=crop&w=900&q=80';
                    }
                    ?>
                    <article class="place-card">
                        <img src="<?php echo htmlspecialchars($image_url); ?>" alt="<?php echo htmlspecialchars($place['place_name']); ?>">
                        <div class="place-info">
                            <p class="place-country"><?php echo htmlspecialchars($place['country']); ?></p>
                            <h2><?php echo htmlspecialchars($place['place_name']); ?></h2>
                            <p class="place-location"><?php echo htmlspecialchars($place['location']); ?></p>
                            <p class="description"><?php echo nl2br(htmlspecialchars($place['description'])); ?></p>
                            <p class="added-by">Shared by <?php echo htmlspecialchars($place['added_by']); ?></p>
                        </div>
                    </article>
                <?php } ?>
            <?php } else { ?>
                <div class="empty-state">
                    <h2>No places have been shared yet</h2>
                    <p>Be the first to share somewhere you love.</p>
                    <a class="button" href="<?php echo isset($_SESSION['user_id']) ? 'add-place.php' : 'register.php'; ?>">Share a place</a>
                </div>
            <?php } ?>
        </section>
    </main>
</body>
</html>
