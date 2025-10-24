<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "recepten";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$naam = $_POST["receptenNaam"] ?? '';
$duur = $_POST["receptenDuur"] ?? '';
$instructies = $_POST["instructies"] ?? '';
$gekozenIngredienten = $_POST["ingredienten"] ?? [];

if (!$naam || !$duur || !$instructies) {
    header("Location: ../HTML/addRecipes.php");
}

$imgId = null;
if (isset($_FILES['myfile']) && $_FILES['myfile']['error'] === 0) {
    $imgData = file_get_contents($_FILES['myfile']['tmp_name']);
    
    $stmt = $conn->prepare("INSERT INTO images (img) VALUES (?)");
    if (!$stmt) {
        die("Prepare failed for images: " . $conn->error);
    }

    $null = NULL;
    $stmt->bind_param("b", $null);
    $stmt->send_long_data(0, $imgData);
    if (!$stmt->execute()) {
        die("Execute failed for images: " . $stmt->error);
    }
    $imgId = $stmt->insert_id;
    $stmt->close();
}

// DEBUG: verbeterde insert + uitgebreide foutmelding voor 'beschrijving'
$stmt = $conn->prepare("INSERT INTO beschrijving (beschrijving) VALUES (?)");
if (!$stmt) {
    // direct DB-structuur tonen voor troubleshooting
    error_log("Prepare failed for beschrijving: " . $conn->error);
    // optioneel echo voor development (niet in productie)
    die("Prepare failed for beschrijving: " . $conn->error);
}

$stmt->bind_param("s", $instructies);
if (!$stmt->execute()) {
    // uitgebreide foutinformatie
    $errStmt = $stmt->error;
    $errConn = $conn->error;
    error_log("Execute failed for beschrijving: stmt error='{$errStmt}' conn error='{$errConn}'");

    // toon relevante tabel-definities en triggers (help bij oorzaak 'idIngredienten' fout)
    $tables = ['beschrijving','ingredienten','recepten_ingredienten'];
    foreach ($tables as $t) {
        $res = $conn->query("SHOW CREATE TABLE `{$t}`");
        if ($res && $row = $res->fetch_assoc()) {
            error_log("SHOW CREATE TABLE {$t}: " . $row['Create Table']);
        } else {
            error_log("Kon SHOW CREATE TABLE {$t} niet uitvoeren: " . $conn->error);
        }
    }
    // controleer triggers die op INSERT op 'beschrijving' reageren
    $res = $conn->query("SHOW TRIGGERS");
    if ($res) {
        while ($r = $res->fetch_assoc()) {
            error_log("Trigger: " . json_encode($r));
        }
    } else {
        error_log("SHOW TRIGGERS faalde: " . $conn->error);
    }

    // Geef duidelijke foutmelding terug en stop (development)
    die("Execute failed for beschrijving: {$errStmt} / {$errConn}. Zie error_log voor details.");
}

$beschrijvingId = $stmt->insert_id;
$stmt->close();

if (!$beschrijvingId) {
    die("Failed to retrieve idBeschrijving after insert.");
}

$stmt = $conn->prepare("INSERT INTO recepten (naam, duur, idImages, idBeschrijving) VALUES (?, ?, ?, ?)");
if (!$stmt) {
    die("Prepare failed for recepten: " . $conn->error);
}
$stmt->bind_param("ssii", $naam, $duur, $imgId, $beschrijvingId);
if (!$stmt->execute()) {
    die("Execute failed for recepten: " . $stmt->error);
}
$receptId = $stmt->insert_id;
$stmt->close();

// controleer receptId
if (empty($receptId) || $receptId <= 0) {
    error_log('FOUT: ongeldig receptId na insert: ' . var_export($receptId, true));
    die('Kon recept niet aanmaken (controleer DB-schema).');
}

if (empty($receptId)) {
    error_log('Geen receptId beschikbaar, kan ingrediënten niet koppelen.');
} else {
    $gekozenIngredienten = $_POST['ingredienten'] ?? [];
    if (!is_array($gekozenIngredienten)) {
        $gekozenIngredienten = [$gekozenIngredienten];
    }
    $gekozenIngredienten = array_filter($gekozenIngredienten, function($v) {
        return $v !== '' && $v !== null;
    });

    $nieuwIngredient = trim($_POST['nieuwIngredient'] ?? '');
    if ($nieuwIngredient !== '') {
        $check = $conn->prepare("SELECT idIngredienten FROM ingredienten WHERE ingredienten = ? LIMIT 1");
        if ($check) {
            $check->bind_param("s", $nieuwIngredient);
            $check->execute();
            $check->bind_result($existingId);
            if ($check->fetch()) {
                $gekozenIngredienten[] = (int)$existingId;
                $check->close();
            } else {
                $check->close();
                $ins = $conn->prepare("INSERT INTO ingredienten (ingredienten) VALUES (?)");
                if ($ins) {
                    $ins->bind_param("s", $nieuwIngredient);
                    if ($ins->execute()) {
                        $gekozenIngredienten[] = (int)$ins->insert_id;
                    } else {
                        error_log("Insert nieuwIngredient failed: " . $ins->error);
                    }
                    $ins->close();
                } else {
                    error_log("Prepare failed for inserting nieuwIngredient: " . $conn->error);
                }
            }
        } else {
            error_log("Prepare failed for checking nieuwIngredient: " . $conn->error);
        }
    }

    if (count($gekozenIngredienten) > 0) {
        $stmt = $conn->prepare("INSERT INTO recepten_ingredienten (idRecepten, idIngredienten) VALUES (?, ?)");
        if (!$stmt) {
            error_log("Prepare failed for recepten_ingredienten: " . $conn->error);
        } else {
            foreach ($gekozenIngredienten as $ingredientId) {
                $ingredientId = (int)$ingredientId;
                if ($ingredientId <= 0) {
                    error_log("Skip invalid ingredient id: " . var_export($ingredientId, true));
                    continue;
                }
                $stmt->bind_param("ii", $receptId, $ingredientId);
                if (!$stmt->execute()) {
                    error_log("Failed to insert into recepten_ingredienten: " . $stmt->error);
                }
            }
            $stmt->close();
        }
    } else {
        error_log('Geen ingredienten geselecteerd voor recept id ' . $receptId);
    }
}

$conn->close();
exit;
?>
