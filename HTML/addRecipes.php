<?php

session_start();

$ingredient = $_SESSION['ingredienten'];

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../CSS/addRecipe.css">
    <link rel="stylesheet" href="../CSS/navbar.css">
</head>

<body>
    <?php require "required.php" ?>
    <div class="wrapper">
        <div class="background">
            <h1>Voeg recepten toe</h1>
            <form class="form-recept" action="" method="POST" enctype="multipart/form-data">
                <input class=" receptenNaam" type="text" name="receptenNaam" id="naam" placeholder="Recept Naam" required>
                <input class="receptenDuur" type="text" name="receptenDuur" id="duur" placeholder="Recept Duur" required>
                <?php foreach ($ingredient as $ing): ?>
                    <div class="ingredient-item">
                        <input required type="checkbox" name="ingredienten" id="ingredient-<?php echo $ing['idIngredienten']; ?>" value="<?php echo $ing['idIngredienten']; ?>">
                        <label for="ingredient-<?php echo $ing['idIngredienten']; ?>">
                            <?php echo htmlspecialchars($ing['ingredienten']); ?>
                        </label>
                    </div>
                <?php endforeach; ?>
                <input type="text" name="nieuwIngredient" id="nieuwIngredient" class="nieuwIngredient" placeholder="Ingredient staat er niet in. Voeg een nieuwe toe hier">
                <div class="file-upload-wrapper">
                    <label for="file-upload" class="custom-file-upload">
                        Kies een bestand
                    </label>
                    <input class="hide-file" id="file-upload" type="file" name="myfile" multiple accept="image/*"/>
                    <span id="file-name">Geen bestand gekozen</span>
                </div>
                <textarea class="instructies" name="instructies" id="instructies" placeholder="Schrijf hier de instructies voor het recept" required></textarea>
                <button class="knop">Verstuur</button>
            </form>
        </div>
    </div>
</body>

</html>