<!-- Page pour modifier exercices -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détails exercices</title>
    <?php
    include 'logo.php';
    include 'boostrap.php';
    ?>
<link rel="stylesheet" href="css/style.css">

</head>


<style>
    body {
        background-color: #708090;
    }

    table {
        margin: auto;
        max-width: 90%;
    }

    #cont{
        display: inline-block;
        margin-left: 15%;
    }

    .center {
        display: block;
        margin-left: auto;
        margin-right: auto;
        max-width: 350px;
        max-height: 300px;
        
   
        border-radius:10px;
    }

    h2 {
        color: #ADFF2F;
        text-align: center;

    }

    h5 {
        text-align: center;
    }
    #butConfirm{
        display: block;
        margin-left: auto;
        margin-right: auto;

    }
</style>
</head>

<body>
    <?php
    include 'bd.php';
    include 'nav.php';
    ?>

    <?php
    if ($_SERVER['REQUEST_METHOD'] == "GET" and isset($_GET['id'])) {
        $id = $_GET['id'];
        AfficherExerciceSelonId($id);
    }
    if(isset($_POST['del'])){
        SupprimerPhotoCasErreurId($_POST['id']);    
        header("Location: index.php");
    }

    if(isset($_POST['mod'])){
        echo'<table>';
        echo '<form method="POST"  style="text-align:center;">';
        AfficherPourModifier($_POST['id']);    
        echo'</table> <br>';

        echo'<input type="submit"  value="Confirmer" name="modConfirm" class="btn btn-warning" id="butConfirm">
        <input type="hidden" name="id" value='. $_POST['id'] . ' >
    </form>';
        
    }
    if(isset($_POST['modConfirm'])){
 


        if (isset($_POST['serie'])) {
            if ($_POST['serie'] == "") {
                $_POST['serie'] = "4";
            }
        }
        if (isset($_POST['rep'])) {
            if ($_POST['rep'] == "") {
                $_POST['rep'] = "10";
            }
        }
        if (isset($_POST['tempo'])) {
            if ($_POST['tempo'] == "") {
                $_POST['tempo'] = "3-1-0-3";
            }
        }
        if (isset($_POST['temps'])) {
            if ($_POST['temps'] == "") {
                $_POST['temps'] = "60";
            }
        }

        $rep = ModExercices($_POST['id'],$_POST['cat'],$_POST['nom'],$_POST['nbSeries'],$_POST['nbRep'],$_POST['tempo'],$_POST['temps']);
        echo'<div style="margin-left: 20%; display: inline-block;"> <h3 class="text-white bg-dark">Changement réussie ! </h3> <br>
        <a style="margin-left: 30%; " class="text-primary" href="index.php"> Retour </a> </div>';

    }
    else{
        if(!isset($_POST['id'])){
            echo'  <form method="POST" style="text-align:center;">
            <input type="submit" value="Modifier" name="mod" class="btn btn-warning">
            <input type="hidden" name="id" value="'. $id .'" >
        </form>
        <br>
        <form method="POST" style="text-align:center;">
            <input type="submit" value="Supprimer" name="del" class="btn btn-danger">
            <input type="hidden" name="id" value="'. $id .'"  >
        </form>';

        }
    }
    ?>
    
</body>
<?php
require 'footer.php';
?>

</html>