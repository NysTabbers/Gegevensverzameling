<?php
session_start();

if(isset($_SESSION["recepten"])){
    $recepten = $_SESSION["recepten"];
}

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../CSS/homePage.css">
    <link rel="stylesheet" href="../CSS/navbar.css">
</head>

<body>
    <?php require "navbar.php" ?>
    <div class="wrapper-image">
        <img class="big-img" src="../IMG/pexels-ash-craig-122861-376464.jpg" alt="">
    </div>
    <div class="wrapper-animation">
        <div class="img-animation">
            <div class="scroll-track">
                <img class="images" src="../IMG/pexels-valeriya-842571.jpg" alt="">
                <img class="images" src="../IMG/pexels-jang-699953.jpg" alt="">
                <img class="images" src="../IMG/pexels-julieaagaard-2097090.jpg" alt="">
                <img class="images" src="../IMG/pexels-lum3n-44775-1410235.jpg" alt="">
                <img class="images" src="../IMG/pexels-valeriya-1199957.jpg" alt="">
                <img class="images" src="../IMG/pexels-vanmalidate-769289.jpg" alt="">

                <img class="images" src="../IMG/pexels-valeriya-842571.jpg" alt="">
                <img class="images" src="../IMG/pexels-jang-699953.jpg" alt="">
                <img class="images" src="../IMG/pexels-julieaagaard-2097090.jpg" alt="">
                <img class="images" src="../IMG/pexels-lum3n-44775-1410235.jpg" alt="">
                <img class="images" src="../IMG/pexels-valeriya-1199957.jpg" alt="">
                <img class="images" src="../IMG/pexels-vanmalidate-769289.jpg" alt="">
            </div>
        </div>
    </div>
<div class="wrapper-recepten">
    <?php foreach ($recepten as $recept): ?>
        <a class="recepten-link" href="recepten.php?id=<?= $recept['idRecepten'] ?>">
            <div class="recepten">
                <img class="img" src="data:image/jpeg;base64,<?= base64_encode($recept['img']) ?>" alt="<?= $recept['naam'] ?>">
                <p><?=$recept['naam'] ?></p>
            </div>
        </a>
    <?php endforeach; ?>
</div>
</body>

</html>