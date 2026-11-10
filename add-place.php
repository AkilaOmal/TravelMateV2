<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

require_once 'config/db.php';
$place_name = '';
$location = '';
$country = '';
$description = '';
$image = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $place_name = isset($_POST['place_name']) ? trim($_POST['place_name']) : '';
    $location = isset($_POST['location']) ? trim($_POST['location']) : '';
    $country = isset($_POST['country']) ? trim($_POST['country']) : '';
    $description = isset($_POST['description']) ? trim($_POST['description']) : '';
    $image = isset($_POST['image']) ? trim($_POST['image']) : '';

    if ($place_name == '' || $location == '' || $country == '' || $description == '') {
        $error = 'Please fill in all required fields.';
    } elseif ($image != '' && (!filter_var($image, FILTER_VALIDATE_URL) || !preg_match('/^https?:\\/\\//i', $image))) {
        $error = 'Please enter a valid image URL starting with http:// or https://.';
    } else {
        $user_id = $_SESSION['user_id'];
        $sql = 'INSERT INTO places (user_id, place_name, location, country, description, image) VALUES (?, ?, ?, ?, ?, ?)';
        $statement = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($statement, 'isssss', $user_id, $place_name, $location, $country, $description, $image);

        if (mysqli_stmt_execute($statement)) {
            mysqli_stmt_close($statement);
            header('Location: my-places.php?added=1');
            exit;
        } else {
            $error = 'The place could not be saved. Please try again.';
        }
        mysqli_stmt_close($statement);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add a place | Travel Mate</title>
    <link rel="stylesheet" href="css/add-place.css">
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

    <main class="form-page">
        <section class="form-card">
            <p class="eyebrow">SHARE A DESTINATION</p>
            <h1>Add a new place</h1>
            <p class="muted">Tell other travelers what makes this place special.</p>
            <?php if ($error != '') { ?>
                <p class="message error"><?php echo htmlspecialchars($error); ?></p>
            <?php } ?>
            <form method="POST" action="add-place.php">
                <label for="place_name">Place name</label>
                <input type="text" id="place_name" name="place_name" value="<?php echo htmlspecialchars($place_name); ?>" required>

                <div class="field-row">
                    <div>
                        <label for="location">Location</label>
                        <input type="text" id="location" name="location" value="<?php echo htmlspecialchars($location); ?>" required>
                    </div>
                    <div>
                        <label for="country">Country</label>
                        <input type="text" id="country" name="country" value="<?php echo htmlspecialchars($country); ?>" required>
                    </div>
                </div>

                <label for="description">Description</label>
                <textarea id="description" name="description" rows="5" required><?php echo htmlspecialchars($description); ?></textarea>

                <label for="image">Image URL <span class="muted">(optional)</span></label>
                <input type="url" id="image" name="image" value="<?php echo htmlspecialchars($image); ?>" placeholder="https://example.com/image.jpg">

                <button class="button" type="submit">Share this place</button>
            </form>
            <p class="form-note"><a href="dashboard.php">&larr; Back to dashboard</a></p>
        </section>
    </main>
</body>
</html>
