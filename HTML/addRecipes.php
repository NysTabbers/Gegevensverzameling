<?php

session_start();

$ingredient = $_SESSION['ingredienten'] ?? [];

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../CSS/addRecipe.css">
</head>

<body>
    <div class="wrapper-navbar">
        <div class="navbar">
            <div class="text-left">
                <a href="homePage.php">Home</a>
                <a href="addRecipes.php">Add recipes</a>
            </div>
            <div class="image-middle">
                <img class="logo" src="../IMG/Logo.png" alt="Logo gebaseerd op een pan">
            </div>
            <div class="text-right">
                <a href="aboutUs.php">About us</a>
            </div>
        </div>
    </div>
    <div class="wrapper">
        <div class="background">
            <h1>Voeg recepten toe</h1>
            <form class="form-recept" action="" method="POST">
                <input class="receptenNaam" type="text" name="receptenNaam" id="naam" placeholder="Recept Naam">
                      <?php foreach ($ingredient as $ing): ?>
                        <div class="ingredient-item">
                            <input
                                type="checkbox" name="ingredienten[]" id="ingredient-<?php echo $ing['idIngredienten']; ?>" alue="<?php echo $ing['idIngredienten']; ?>">
                            <label for="ingredient-<?php echo $ing['idIngredienten']; ?>">
                                <?php echo htmlspecialchars($ing['ingredienten']); ?>
                            </label>
                        </div>
                    <?php endforeach; ?>
                    <textarea class="instructies" name="instructies" id="instructies" placeholder="Schrijf hier de instructies voor het recept"></textarea>
            </form>
        </div>
    </div>
</body>

</html>