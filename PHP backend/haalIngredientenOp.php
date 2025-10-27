<?php

session_start();

$name = "localhost";
$user = "root";
$password = "";
$db = "recept";

$conn = new mysqli($name, $user, $password, $db);
if ($conn->connect_error) {
    die("connection failed" . $conn->connect_error);
}
try {
    $sql = "SELECT idIngredienten, ingredienten FROM ingredienten";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $result = $stmt->get_result();

    $ingredienten = [];
    while ($row = $result->fetch_assoc()) {
        $ingredienten[] = $row;
    }

    $_SESSION['ingredienten'] = $ingredienten;
} catch (Exception $e) {
    die("Dit was een error: " . $e->getMessage());
} finally {
    $conn->close();
    $stmt->close();
    header("Location: ../HTML/addRecipes.php");
}
