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
            <a class="home" href="homePage.php">Home</a>
            <a class="addRecipes" href="addRecipes.php">Add recipes</a>
            <img class="logo" src="../IMG/Logo.svg" alt="Logo gebaseerd op een pan">
        </div>
    </div>
    <div class="wrapper">
        <div class="background">
            <form action="" method="POST">
                <input class="receptenNaam" type="text" name="receptenNaam" id="naam" placeholder="Recept Naam">
                      <?php foreach ($ingredient as $ing): ?>
                        <div class="ingredient-item">
                            <input
                                type="checkbox"
                                name="ingredienten[]"
                                id="ingredient-<?php echo $ing['idIngredienten']; ?>"
                                value="<?php echo $ing['idIngredienten']; ?>">
                            <label for="ingredient-<?php echo $ing['idIngredienten']; ?>">
                                <?php echo htmlspecialchars($ing['ingredienten']); ?>
                            </label>
                        </div>
                    <?php endforeach; ?>
                    <input type="textarea" name="" id="">
            </form>
        </div>
    </div>
</body>

</html>