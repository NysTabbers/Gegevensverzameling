<?php
session_start();

if (!isset($_SESSION['error_message'])) {
    header("Location: homePage.php");
    exit;
}

$errorMessage = $_SESSION['error_message'];
unset($_SESSION['error_message']);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Document</title>
    <link rel="stylesheet" href="../CSS/errorPage.css">
</head>

<body>
    <?php require_once("navbar.php") ?>
    <div class="wrapper">
        <div class="error-container">
            <h2>Er is iets mis gegaan</h2>
            <p><?= htmlspecialchars($errorMessage) ?></p>
            <a href="addRecipes.php" class="button">Ga terug</a>
        </div>
    </div>
</body>

</html>