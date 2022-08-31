<!-- Page exercices aléatoire sans filtre  -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exercice aléatoire</title>
  
<?php
include 'logo.php';
include 'boostrap.php';
?>
<link rel="stylesheet" href="css/style.css">

    <style>
        body{
            background-color: lightgray;
        }
        table {
            margin-bottom: 100px;
        }

        img {
            max-width: 100px;
            max-height: 150px;
        }
    
    </style>
</head>
<body>
<?php
        include 'bd.php';
        include 'nav.php';
        ?>

    <table class="table table-dark table-sm">
        <?php
        AfficherExerciceRandom();
    
    ?>
    </table>
</body>
<?php
require 'footer.php';
?>
</html>