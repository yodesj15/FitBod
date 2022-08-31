<!-- Page entrainement aléatoire selon partie du corps -->

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Entrainement aléatoire </title>

    <?php
    include 'logo.php';
    include 'boostrap.php';
    ?>

    <link rel="stylesheet" href="css/style.css">

    <style>
        body {
            background-color: lightgray;
            width: auto;
        }

        table {
            margin-bottom: 100px;
        }

        h1 {
            color: #3333ff;
            display: inline-block;
            border-radius: 100px;
            padding: 30px 50px;

        }

        #form2 {
            top: 5px;
            position: relative;
            bottom: 5px;
        }

        #but {
            border: 2px solid;
            border-radius: 10px;

        }
    </style>
</head>

<body class="text-center">
    <?php
    include 'bd.php';
    include 'nav.php';
    ?>
    <main>

        <table class="table table-dark table-sm">

            <?php
            if ($_SERVER['REQUEST_METHOD'] == 'GET') {
                if (isset($_GET['choix'])) {


                    $choix = $_GET['choix'];

                    if ($choix == 1) {
                        echo '<h1 class="text-light bg-dark">Haut du corps</h1> <br><br> ';

                        echo '<a href="entrainement.php?choix=1" id="but" class="btn btn-info btn-lg">
                    <span class="glyphicon glyphicon-refresh">&#xe031;</span>  Refresh
                  </a> <br> <br>';
                        AfficherExerciceRandomBras();
                    } else if ($choix == 2) {
                        echo '<h1  class="text-light bg-dark">Dos</h1><br> <br>';
                        echo '<a href="entrainement.php?choix=2" id="but" class="btn btn-info btn-lg">
                    <span class="glyphicon glyphicon-refresh">&#xe031;</span>  Refresh
                  </a> <br> <br>';
                        AfficherExerciceRandomDos();
                    } else {
                        echo '<h1  class="text-light bg-dark">Jambes</h1><br> <br>';
                        echo '<a href="entrainement.php?choix=3" id="but" class="btn btn-info btn-lg">
                    <span class="glyphicon glyphicon-refresh">&#xe031;</span>  Refresh
                  </a> <br> <br>';
                        AfficherExerciceRandomJambes();
                    }
                }
            }

            ?>
        </table>
    </main>


    <?php
    require 'footer.php';
    function AffichageForm()
    {
        echo ' <form action="" method="POST">
        <fieldset>
            <label>Filtrer selon la catégorie: </label>';


        CréerListe(1);


        echo ' &nbsp; &nbsp;<input type="submit" name="filtre" value="Afficher" />

        </fieldset>
    </form>
       ';
    }
    ?>