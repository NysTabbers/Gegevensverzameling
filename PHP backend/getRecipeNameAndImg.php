<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

session_start();

$name = "localhost";
$user = "root";
$password = "";
$db = "recept";

$conn = new mysqli($name, $user, $password, $db);

if ($conn->connect_error) {
    die("Connection failed" . $conn->connect_error);
}

try {
    $sql = "SELECT recepten.idRecepten, recepten.naam, recepten.idImages, images.img FROM recepten INNER JOIN images ON recepten.idImages = images.idImages";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $result = $stmt->get_result();

    $recepten = [];
    while ($row = $result->fetch_assoc()) {
        $recepten[] = $row;
    }

    $_SESSION["recepten"] = $recepten;
} catch (Exception $e) {
    die("Dit was een error: " . $e->getMessage());
} finally {
    if (isset($stmt)) $stmt->close();
    if (isset($conn)) $conn->close();
    header("Location: ../HTML/homePage.php");
}