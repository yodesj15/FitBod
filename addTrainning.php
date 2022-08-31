<!-- Page pour ajouter des entrainements personnalisés -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fitbud</title>

    <?php
    include 'logo.php';
    include 'boostrap.php';
    ?>
    <style>
        body {
            background-color: lightslategray;
            width: auto;
        }

        input {
            margin: 5px 0px;
        }

        #message {
            position: absolute;
            top: 90%;
            left: 17%;
        }

        #conteneur {
            position: absolute;

            left: 25%;
        }

        #fichierUpload {
            position: absolute;

            left: 8%;
        }
    </style>
</head>

<body>
    <?php
    require 'bd.php';
    require 'nav.php';

    ?>
    <main id="conteneurFichier" class="mainFichier">
        <br>

        <h2>Ajouter un exercice dans un entrainement </h2>
        <form class="md-form" action="" method="post" enctype="multipart/form-data">
            <div class="file-field">

                <div id="conteneur">
                    <br>

                    <input type="number" min="1" max="6" name="jour" id="jour" placeholder="Jour de 1 à 6" required>
                    <br>
                    <?php
                    CréerListe(2);
                    echo '<br>';
                    CréerListe(1);

                    ?>
                    <br>
                    <?php
                    CréerListe(4);
                    ?>
                    <br>
                    <input type="text" name="serie" id="serie" placeholder="Nb séries">

                    <br>
                    <input type="text" name="rep" id="rep" placeholder="Nb répétition">
                    <br>
                    <input type="text" name="tempo" id="tempo" placeholder="Tempo">
                    <br>
                    <input type="text" name="temps" id="temps" placeholder="Repos">

                    <div class="btn-sm btn-sm" id="boutonUpl">
                        <input type="submit" value="Téléverser" name="boutonTeleverser" style="background-color: grey;">
                    </div>
                </div>
            </div>
        </form>
        <?php
        if (isset($_POST['jour']) && isset($_POST['boutonTeleverser']) && $_SERVER['REQUEST_METHOD'] == "POST") {

            //entrainement par défaut
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
            $ajouteReussie = AjouterEntrainement($_POST['jour'], $_POST['cat'], $_POST['exercice'], $_POST['serie'], $_POST['rep'], $_POST['tempo'], $_POST['temps'], $_POST['numTrain']);
            echo '<div id="message">';
            if ($ajouteReussie) {
                echo '<div class="p-3 mb-2 bg-success text-white" style="align-selft: center;">Le téléversement a fonctionné !</div>';
            } else {
                SupprimerPhotoCasErreur($nomFichier);
                echo '<div class="p-3 mb-2 bg-danger text-white" style="align-content: center;">Le téléversement n\'a pas fonctionné !</div>';
            }
            echo '</div>';
        }

        ?>
    </main>

</body>

</html>