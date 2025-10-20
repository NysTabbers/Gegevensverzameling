<?php

$name = "localhost";
$user = "root";
$password = "";
$db = "recepten";

$conn = new mysqli($name, $user, $password, $db);
if ($conn->connect_error) {
    die("connection failed" . $conn->connect_error);
}
try{
$sql = "SELECT idIngredienten, ingredienten FROM gebruikers";
$stmt = $conn->prepare($sql);
$stmt->execute();
$result = $stmt->get_result();

$items = [];
while ($row = $result->fetch_assoc()) {
    $items[] = $row;
}

$_SESSION['items_list'] = $items;

}
catch(Exception $e){
    die("Dit was een error: " . $e->getMessage());
}
finally{
    $conn->close();
    $stmt->close();
}

?>