<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

session_start();

try {
    $servername = "localhost";
    $username   = "root";
    $password   = "";
    $dbname     = "recept";

    $conn = new mysqli($servername, $username, $password, $dbname);

    $receptNaam = htmlspecialchars($_POST['receptenNaam']);
    $receptDuur = htmlspecialchars($_POST['receptenDuur']);
    $beschrijving = htmlspecialchars($_POST['instructies']);
    $gekozenIngredienten = isset($_POST['ingredienten']) ? $_POST['ingredienten'] : [];
    $nieuwIngredient = htmlspecialchars(trim($_POST['nieuwIngredient']));

    if (!empty($nieuwIngredient)) {
        $check = $conn->prepare("SELECT idIngredienten FROM ingredienten WHERE LOWER(ingredienten) = LOWER(?)");
        $check->bind_param("s", $nieuwIngredient);
        $check->execute();
        $result = $check->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $idNieuwIngredient = $row['idIngredienten'];
        } else {
            $insertIng = $conn->prepare("INSERT INTO ingredienten (ingredienten) VALUES (?)");
            $insertIng->bind_param("s", $nieuwIngredient);
            $insertIng->execute();
            $idNieuwIngredient = $insertIng->insert_id;
        }

        $gekozenIngredienten[] = $idNieuwIngredient;
    }

    $idImage = NULL;
    if (isset($_FILES['myfile']) && $_FILES['myfile']['error'] === UPLOAD_ERR_OK) {
        $imageData = file_get_contents($_FILES['myfile']['tmp_name']); // binary data
        $insertImg = $conn->prepare("INSERT INTO images (img) VALUES (?)");
        $insertImg->bind_param("b", $imageData);
        $insertImg->send_long_data(0, $imageData);
        $insertImg->execute();
        $idImage = $insertImg->insert_id;
    }

    $insertDesc = $conn->prepare("INSERT INTO beschrijving (beschrijving) VALUES (?)");
    $insertDesc->bind_param("s", $beschrijving);
    $insertDesc->execute();
    $idBeschrijving = $insertDesc->insert_id;

    $insertRecept = $conn->prepare("INSERT INTO recepten (naam, duur, idImages, idBeschrijving) VALUES (?, ?, ?, ?)");
    $insertRecept->bind_param("ssii", $receptNaam, $receptDuur, $idImage, $idBeschrijving);
    $insertRecept->execute();
    $idRecept = $insertRecept->insert_id;

    if (!empty($gekozenIngredienten)) {
        foreach ($gekozenIngredienten as $idIngredient) {
            $insertLink = $conn->prepare("
                INSERT INTO recepten_ingredienten (idRecepten, idIngredienten)
                VALUES (?, ?)
            ");
            $insertLink->bind_param("ii", $idRecept, $idIngredient);
            $insertLink->execute();
        }
    }

} catch (mysqli_sql_exception $e) {
    echo "Database error: " . $e->getMessage();
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
} finally {
    $conn->close();
    header("Location: getRecipeNameAndImg.php");
}
