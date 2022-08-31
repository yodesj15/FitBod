<!-- Page pour ajouter des exercices -->

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
            top: 70%;
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
        <form class="md-form" action="" method="post" enctype="multipart/form-data">
            <div class="file-field">
                <div class="btn btn-primary btn-sm float-left" id="fichierUpload">
                    <input type="hidden" name="MAX_FILE_SIZE" value="10000000">
                    <input type="file" name="fichier" size="10000000">
                </div>
                <div id="conteneur">
                    <br>
                    <br>
                    <input type="text" name="nom" id="nom" placeholder="Nom" required>
                    <br>
                    <?php
                    CréerListe(1);
                    ?>
                    <br>
                    <input type="number" name="serie" id="serie" placeholder="Nb séries">
                    <br>
                    <input type="number" name="rep" id="rep" placeholder="Nb répétition">
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
        if (isset($_POST['nom']) && isset($_FILES['fichier']) && isset($_POST['boutonTeleverser']) && $_SERVER['REQUEST_METHOD'] == "POST") {
            $codePremierChiffre = rand(0, 9);
            $codeDeuxièmeChiffre = rand(0, 9);
            $codeTroisièmeChiffre = rand(0, 9);
            $nbAleatoire = $codePremierChiffre . $codeDeuxièmeChiffre . $codeTroisièmeChiffre;

            $file_name = $_FILES['fichier']['name'];
            $file_size = $_FILES['fichier']['size'];
            $file_tmp = $_FILES['fichier']['tmp_name'];
            $file_type = $_FILES['fichier']['type'];
            $nomFichier = $nbAleatoire . $file_name;

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
            $ajouteReussie = AjouterExercice($nomFichier, $_POST['nom'], $_POST['cat'], $_POST['serie'], $_POST['rep'], $_POST['tempo'], $_POST['temps']);
            echo '<div id="message">';
            if (EstCompatible($file_type) && $ajouteReussie) {
                $fichierTransferé = move_uploaded_file($file_tmp, "img/" . $nomFichier);
                if ($fichierTransferé) {
                    echo '<div class="p-3 mb-2 bg-success text-white" style="align-selft: center;">Le téléversement a fonctionné !</div>';
                } else {
                    SupprimerPhotoCasErreur($nomFichier);
                    echo '<div class="p-3 mb-2 bg-danger text-white" style="align-content: center;">Le téléversement n\'a pas fonctionné !</div>';
                }
            } else {
                SupprimerPhotoCasErreur($nomFichier);
                echo '<div class="p-3 mb-2 bg-danger text-white" style="align-content: center;">Le téléversement n\'a pas fonctionné !</div>';
            }
            echo '</div>';
        }
        function EstCompatible($file_type)
        {
            $extensions = array('jpeg', 'png', 'gif', 'jpg', 'GIF', 'JPEG', 'JPG', 'PNG', 'mp4', 'MP4');
            foreach ($extensions as $type) {
                if (strpos($file_type, $type)) {
                    return true;
                }
            }
            return false;
        }
        ?>
    </main>

</body>

</html>