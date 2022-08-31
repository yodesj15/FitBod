Page d'acceuil avec les différentes partie du corps comme filtre

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fitbud</title>
    <?php
    include 'logo.php';
    include 'boostrap.php';
    ?>
    <link rel="stylesheet" href="css/style.css">

</head>

<body class="text-center">
    <?php
    include 'bd.php';
    include 'nav.php';
    ?>
    <main>
        <?php
        AffichageForm2();
        echo '<br>';
        AffichageForm();
        ?>
        <br>

        <!-- Tableau contenant les exercices -->

        <table class="table table-dark table-sm">

            <?php
            if (isset($_POST['cat']) && isset($_POST['filtre'])) {
                AfficherExerciceSelonCat($_POST['cat']);
            } else if (isset($_POST['recherche'])) {
                RechercherSelonNom($_POST['nomEntrez']);
            } else {
                AfficherExercice();
            }
            ?>
        </table>
    </main>


    <?php
    require 'footer.php';

    //Formulaire pour les catégories
    function AffichageForm()
    {
        echo ' <form action="" method="POST">
        <fieldset>
            <label>Filtrer selon la catégorie: </label> &nbsp;';


        CréerListe(1);

        echo '&nbsp;<input type="submit" name="filtre" class="fa fa-search" value="&#xf002" />

        </fieldset>
    </form>
       ';
    }


    //Barre de recherche pour un nom d"exercice
    function AffichageForm2()
    {
        echo ' <form method="POST" id="form2" name="form2">
        <fieldset>
            <label>Rechercher: </label> 
            <input type="text" name="nomEntrez" id="nomEntrez" value="" size="10">';


        echo ' &nbsp;<input type="submit" name="recherche" class="fa fa-search" value="&#xf002"  />
        </fieldset>
        </form> ';
    }
    ?>