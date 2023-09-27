
<?php
session_start();
unset($_SESSION['resultatInscrip']);

$host = '142.44.210.60';
$db = 'Fitbud';
$user = 'yoan';
$pass = 'yoyo0808';
$charset = 'utf8';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    throw new \PDOException($e->getMessage(), (int)$e->getCode());
}


function AjouterUserDsBd($pdo, $nom, $prenom, $pseudo, $mdp, $email)
{
    $pseudo = strtolower($pseudo);
    if (VérifierSiLongeurValide($nom, $prenom, $pseudo, $mdp, $email)) {
        try {
            $tab = EnleverEspace($nom, $prenom, $pseudo);
            $nom = $tab[0];
            $prenom = $tab[1];
            $pseudo = $tab[2];
            $hash = password_hash($mdp, PASSWORD_DEFAULT);
            $sql = "INSERT INTO Utilisateurs (nom, prenom, pseudo, mdp, email) VALUES (?,?,?,?,?)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$nom, $prenom, $pseudo, $hash, $email]);
            return true;
        } catch (Exception $e) {
            return false;
        }
    } else {
        return false;
    }
}

function VérifierSiLongeurValide($nom, $prenom, $pseudo, $mdp, $email)
{
    $tab = [$nom, $prenom, $pseudo, $mdp, $email];
    foreach ($tab as $valeur) {
        if ($valeur == " ") {
            return false;
        }
    }
    return true;
}

function EnleverEspace($nom, $prenom, $pseudo)
{
    $tab[0] = $nom;
    $tab[1] = $prenom;
    $tab[2] = $pseudo;
    for ($i = 0; $i < sizeof($tab); $i++) {
        $tab[$i] = trim($tab[$i], " ");
    }
    return $tab;
}

function VérifierSiDsBd($pseudo, $mdpEntrez)
{
    $pdo = $GLOBALS['pdo'];

    $sql = "SELECT pseudo,mdp FROM Utilisateurs WHERE pseudo = ? AND mdp = ? ";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$pseudo, $mdpEntrez]);
    $row = $stmt->fetch(PDO::FETCH_NUM);

    if (count($row) > 1) {
        return true;
    } else {
        return false;
    }
}

function RetournerID($pdo, $pseudo)
{
    $sql = "SELECT idUser FROM Utilisateurs WHERE pseudo = ? ";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$pseudo]);
    $row = $stmt->fetch(PDO::FETCH_NUM);
    return $row[0];
}

function VérifierSiPseudoEstDéjaDsBd($pdo, $pseudo)
{
    $sql = "SELECT idUser FROM Utilisateurs WHERE pseudo = ? ";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$pseudo]);
    $nbRow = VerifierNBRow($stmt);
    if ($nbRow < 1) {
        return false;
    } else {

        return true;
    }
}

function VerifierNBRow($stmt)
{
    $nb = $stmt->rowCount();
    return $nb;
}


function CréerListe($choix)
{
    $pdo = $GLOBALS['pdo'];


    if ($choix == 1) {
        $sql = "SELECT nom FROM cat ";
        $stmt = $pdo->prepare($sql);
        $stmt = $pdo->query($sql);

        echo  '<select id="cat" name="cat">';

        foreach ($stmt as $row) {
            echo '<option value="' . $row['nom'] . '">' . $row['nom'] . '</option>';
        }

        echo "</select>";
    } else if ($choix == 2) {
        $sql = "SELECT id,nom FROM Exercices order by nom";
        $stmt = $pdo->prepare($sql);
        $stmt = $pdo->query($sql);

        echo  '<select id="exercice" name="exercice">';
        echo '<option value="" selected></option>';

        foreach ($stmt as $row) {
            echo '<option value="' . $row['id'] . '">' . $row['nom'] . '</option>';
        }

        echo "</select>";
    } else if ($choix == 3) {
        $sql = "SELECT distinct(jour) FROM Entrainement order by jour";
        $stmt = $pdo->prepare($sql);
        $stmt = $pdo->query($sql);

        echo  '<select id="jour" name="jour">';
        foreach ($stmt as $row) {
            echo '<option value="' . $row['jour'] . '">Jours #' . $row['jour'] . '</option>';
        }

        echo "</select>";
    } else if ($choix == 4) {
        $sql = "SELECT distinct(numEntrainement) FROM Entrainement order by numEntrainement";
        $stmt = $pdo->prepare($sql);
        $stmt = $pdo->query($sql);
        $nb = 0;
        echo  '<select id="numTrain" name="numTrain">';
        foreach ($stmt as $row) {
            echo '<option value="' . $row['numEntrainement'] . '">NumTrain #' . $row['numEntrainement'] . '</option>';
            $nb++;
        }
        $nb += 1;
        echo '<option value="' . $nb . '">NumTrain #' . $nb . '</option>';

        echo "</select>";
    } else if ($choix == 5) {
        $sql = "SELECT distinct(numEntrainement) FROM Entrainement order by numEntrainement";
        $stmt = $pdo->prepare($sql);
        $stmt = $pdo->query($sql);

        echo  '<select id="numTrain" name="numTrain">';
        foreach ($stmt as $row) {
            echo '<option value="' . $row['numEntrainement'] . '">Entrainnement #' . $row['numEntrainement'] . '</option>';
        }

        echo "</select>";
    } else {
        echo '<span style="color: red;"  id="erreur"> Erreur valeur invalide </span>';
    }
}

function AjouterImages($chemin, $file_type)
{
    $pdo = $GLOBALS['pdo'];
    if (EstCompatible($file_type)) {
        try {

            $sql = "INSERT INTO Images (chemin ) VALUES (?)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$chemin]);
            return true;
        } catch (Exception $e) {
            return false;
        }
    }
    return false;
}

function SupprimerPhotoCasErreur($chemin)
{
    $pdo = $GLOBALS['pdo'];
    $sql = "DELETE FROM Exercices WHERE img = ? ";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$chemin]);
}

function SupprimerPhotoCasErreurId($id)
{
    $pdo = $GLOBALS['pdo'];
    $sql = "DELETE FROM Exercices WHERE id = ? ";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id]);
}

function DelExerciceTrainning($id)
{
    $pdo = $GLOBALS['pdo'];
    $sql = "DELETE FROM Entrainement WHERE id = ? ";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id]);
}
function AjouterExercice($img, $nom, $cat, $nbSeries, $nbRep, $tempo, $temps)
{
    $pdo = $GLOBALS['pdo'];
    try {
        $sql = "INSERT INTO Exercices (img,nom,cat,nbSeries,nbRep,tempo,temps) VALUES (?,?,?,?,?,?,?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$img, $nom, $cat, $nbSeries, $nbRep, $tempo, $temps]);
        return true;
    } catch (Exception $e) {
        return false;
    }
}
function AjouterEntrainement($jour, $cat, $idExercice, $nbSeries, $nbRep, $tempo, $temps, $numTrain)
{
    $pdo = $GLOBALS['pdo'];
    try {
        $sql = "INSERT INTO Entrainement (numEntrainement,idExercices,jour,muscle,series,rep,tempo,repos) VALUES (?,?,?,?,?,?,?,?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$numTrain, $idExercice, $jour, $cat, $nbSeries, $nbRep, $tempo, $temps]);
        return true;
    } catch (Exception $e) {
        return false;
    }
}

function RetourneMin()
{
    $pdo = $GLOBALS['pdo'];
    $sql = "SELECT min(id) as mi FROM Exercices ";

    $stmt = $pdo->prepare($sql);
    $stmt = $pdo->query($sql);
    foreach ($stmt as $row) {
        $t = $row['mi'];
    }
    return $t;
}

function RetourneMax()
{
    $pdo = $GLOBALS['pdo'];
    $sql = "SELECT max(id) as ma FROM Exercices ";

    $stmt = $pdo->prepare($sql);
    $stmt = $pdo->query($sql);
    foreach ($stmt as $row) {
        $t = $row['ma'];
    }
    return $t;
}
function RetourneNbCatSelect($cat)
{
    $pdo = $GLOBALS['pdo'];
    $sql = "SELECT count(id) as ma FROM Exercices where cat = ?";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([$cat]);

    foreach ($stmt as $row) {
        $t = $row['ma'];
    }
    return $t;
}
function RetourneMinCat($cat)
{
    $pdo = $GLOBALS['pdo'];
    $sql = "SELECT min(id) as mi FROM Exercices where cat = ?";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([$cat]);
    foreach ($stmt as $row) {
        $t = $row['mi'];
    }
    return $t;
}

function RetourneMaxCat($cat)
{

    $pdo = $GLOBALS['pdo'];
    $sql = "SELECT max(id) as ma FROM Exercices where cat = ? ";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([$cat]);

    foreach ($stmt as $row) {
        $t = $row['ma'];
    }
    return $t;
}
function RetourneTabIdExercice()
{
    $tab = array();
    $pdo = $GLOBALS['pdo'];
    $sql = "SELECT id FROM Exercices ";

    $stmt = $pdo->prepare($sql);
    $stmt = $pdo->query($sql);

    foreach ($stmt as $row) {
        $tab[] = $row['id'];
    }
    return $tab;
}

function RetourneTabIdExerciceSelonMuscle($muscle)
{
    $tab = array();
    $pdo = $GLOBALS['pdo'];
    if ($muscle == "bras") {
        $sql = "SELECT id FROM Exercices WHERE cat like '%bras%' or cat like '%tricep%'or cat like '%Bicep%'";
    } else if ($muscle == "dos") {
        $sql = "SELECT id FROM Exercices WHERE cat like '%Épaules%' or cat like '%Dos%'";
    } else {
        $sql = "SELECT id FROM Exercices WHERE cat like '%Jambes%'";
    }

    $stmt = $pdo->prepare($sql);
    $stmt = $pdo->query($sql);

    foreach ($stmt as $row) {
        $tab[] = $row['id'];
    }
    return $tab;
}


function EstDsTab($tab, $id)
{
    foreach ($tab as $row) {
        if ($row == $id) {

            return true;
        }
    }
    return false;
}

function AfficherExercice()
{
    $pdo = $GLOBALS['pdo'];
    $sql = "SELECT id,img,nom,cat,nbSeries,nbRep,tempo,temps FROM Exercices";

    $stmt = $pdo->prepare($sql);
    $stmt = $pdo->query($sql);

    echo '<tr>

<th>Img</th>
<th>Nom</th>
<th>Muscle</th>
</tr>';


    foreach ($stmt as $row) {
        echo "<tr>";
        echo '<td> <a href="gestimage.php?id=' . $row['id'] . '">
        <img src="img/' . $row['img'] . '" alt="image" style="width: 100px;
        height: 100px; border-radius:15px;"></a></td>';
        echo "<td>" . $row['nom'] . "</td>";

        echo "<td>" . $row['cat'] . " </td>";

        echo "</tr>";
    }
}

function AfficherExerciceRandom()
{
    $nbExercice = 8;
    $pdo = $GLOBALS['pdo'];
    $tabId = array();
    $tabId = RetourneTabIdExercice();
    $tab = array();
    echo '<tr>
    <th>Img</th>
    <th>Nom</th>
    <th>Muscle</th>
    </tr>';

    $rand_keys = array_rand($tabId, $nbExercice);

    for ($i = 0; $i <  $nbExercice; ++$i) {
        $tab[$i] = $tabId[$rand_keys[$i]];
    }
    foreach ($tab as $j) {
        $sql = "SELECT id,img,nom,cat,nbSeries,nbRep,tempo,temps FROM Exercices where id = ?";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([$j]);

        foreach ($stmt as $row) {
            echo "<tr>";
            echo '<td> <a href="gestimage.php?id=' . $row['id'] . '">
            <img src="img/' . $row['img'] . '" alt="image" style="width: 100px;
            height: 100px; border-radius:10px;"></a></td>';
            echo "<td>" . $row['nom'] . "</td>";

            echo "<td>" . $row['cat'] . " </td>";

            echo "</tr>";
        }
    }
}

function AfficherExerciceSelonCat($cat)
{
    $pdo = $GLOBALS['pdo'];
    $cat = '%' . $cat . '%';

    $sql = "SELECT id,img,nom,cat,nbSeries,nbRep,tempo,temps FROM Exercices where cat like ?";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([$cat]);

    echo '<tr>

<th>Img</th>
<th>Nom</th>
<th>Muscle</th>
</tr>';


    foreach ($stmt as $row) {
        echo "<tr>";
        echo '<td> <a href="gestimage.php?id=' . $row['id'] . '">
        <img src="img/' . $row['img'] . '" alt="image" style="width: 100px;
        height: 100px; border-radius:10px;"></a></td>';
        echo "<td>" . $row['nom'] . "</td>";

        echo "<td>" . $row['cat'] . " </td>";

        echo "</tr>";
    }
}

function AfficherExerciceRandomBras()
{

    $nbExercice = 7;
    $pdo = $GLOBALS['pdo'];
    $tabId = array();
    $tabId = RetourneTabIdExerciceSelonMuscle("bras");
    $tab = array();
    echo '<tr>
    <th>Img</th>
    <th>Nom</th>
    <th>Muscle</th>
    </tr>';

    $rand_keys = array_rand($tabId, $nbExercice);

    for ($i = 0; $i <  $nbExercice; ++$i) {
        $tab[$i] = $tabId[$rand_keys[$i]];
    }
    foreach ($tab as $j) {
        $sql = "SELECT id,img,nom,cat,nbSeries,nbRep,tempo,temps FROM Exercices where id = ?";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([$j]);

        foreach ($stmt as $row) {
            echo "<tr>";
            echo '<td> <a href="gestimage.php?id=' . $row['id'] . '">
            <img src="img/' . $row['img'] . '" alt="image" style="width: 100px;
            height: 100px; border-radius:10px;"></a></td>';
            echo "<td>" . $row['nom'] . "</td>";

            echo "<td>" . $row['cat'] . " </td>";
            echo "</tr>";
        }
    }
}

function AfficherExerciceRandomDos()
{

    $nbExercice = 7;
    $pdo = $GLOBALS['pdo'];
    $tabId = array();
    $tabId = RetourneTabIdExerciceSelonMuscle("dos");
    $tab = array();
    echo '<tr>
    <th>Img</th>
    <th>Nom</th>
    <th>Muscle</th>
    </tr>';

    $rand_keys = array_rand($tabId, $nbExercice);

    for ($i = 0; $i <  $nbExercice; ++$i) {
        $tab[$i] = $tabId[$rand_keys[$i]];
    }
    foreach ($tab as $j) {
        $sql = "SELECT id,img,nom,cat,nbSeries,nbRep,tempo,temps FROM Exercices where id = ?";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([$j]);

        foreach ($stmt as $row) {
            echo "<tr>";
            echo '<td> <a href="gestimage.php?id=' . $row['id'] . '">
            <img src="img/' . $row['img'] . '" alt="image" style="width: 100px;
            height: 100px; border-radius:10px;"></a></td>';
            echo "<td>" . $row['nom'] . "</td>";

            echo "<td>" . $row['cat'] . " </td>";
            echo "</tr>";
        }
    }
}

function AfficherExerciceRandomJambes()
{

    $nbExercice = 7;
    $pdo = $GLOBALS['pdo'];
    $tabId = array();
    $tabId = RetourneTabIdExerciceSelonMuscle(" ");
    $tab = array();
    echo '<tr>
    <th>Img</th>
    <th>Nom</th>
    <th>Muscle</th>
    </tr>';

    $rand_keys = array_rand($tabId, $nbExercice);

    for ($i = 0; $i <  $nbExercice; ++$i) {
        $tab[$i] = $tabId[$rand_keys[$i]];
    }
    foreach ($tab as $j) {
        $sql = "SELECT id,img,nom,cat,nbSeries,nbRep,tempo,temps FROM Exercices where id = ?";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([$j]);

        foreach ($stmt as $row) {
            echo "<tr>";
            echo '<td> <a href="gestimage.php?id=' . $row['id'] . '">
            <img src="img/' . $row['img'] . '" alt="image" style="width: 100px;
            height: 100px; border-radius:10px;"></a></td>';
            echo "<td>" . $row['nom'] . "</td>";

            echo "<td>" . $row['cat'] . " </td>";
            echo "</tr>";
        }
    }
}
function AfficherEntrainementSelonId($id)
{
    $pdo = $GLOBALS['pdo'];
    $sql = "SELECT * FROM Entrainement where id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id]);

    echo '<div class="gros-conteneur" >';
    foreach ($stmt as $rangee) {

        echo '  <h2>' . $rangee['nom'] . '</h2>
        <img src="img/' . $rangee['img'] . '" alt="image" class="center"> 
        <br>
            <h5 class="text-warning">' . $rangee['cat'] . '</h5>
            <table class="table table-dark table-sm">
                <tr>
                <th>Série</th>
                <th>Répétion</th>
                <th>Tempo</th>
                <th>Repos</th>
                <th>Infos</th>

                
                </tr>
                <tr>
                <td>' . $rangee['nbSeries'] . '</td>
                <td>' . $rangee['nbRep'] . '</td>
                <td>' . $rangee['tempo'] . '</td>
                <td>' . $rangee['temps'] . '</td>
    
    <td> <a href="https://www.google.ca/search?q=' . $rangee['nom'] . '+muscles+used">Muscles</a></td>

                </tr>
            </table>';
    }
}
function AfficherExerciceSelonId($id)
{
    $pdo = $GLOBALS['pdo'];
    $sql = "SELECT id,img,nom,cat,nbSeries,nbRep,tempo,temps FROM Exercices where id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id]);

    echo '<div class="gros-conteneur" >';
    foreach ($stmt as $rangee) {

        echo '  <h2>' . $rangee['nom'] . '</h2>
        <img src="img/' . $rangee['img'] . '" alt="image" class="center"> 
        <br>
            <h5 class="text-warning">' . $rangee['cat'] . '</h5>
            <table class="table table-dark table-sm">
                <tr>
                <th>Série</th>
                <th>Répétion</th>
                <th>Tempo</th>
                <th>Repos</th>
                <th>Infos</th>

                
                </tr>
                <tr>
                <td>' . $rangee['nbSeries'] . '</td>
                <td>' . $rangee['nbRep'] . '</td>
                <td>' . $rangee['tempo'] . '</td>
                <td>' . $rangee['temps'] . '</td>
    
    <td> <a href="https://www.google.ca/search?q=' . $rangee['nom'] . '+muscles+used">Muscles</a></td>

                </tr>
            </table>';
    }
}

function RechercherSelonNom($nom)
{
    $pdo = $GLOBALS['pdo'];
    $nom = $nom . '%';
    $sql = "SELECT *  FROM Exercices where nom like ?";

    $stmt = $pdo->prepare($sql);
    //$stmt->execute([]);
    $stmt->execute([$nom]);

    echo '<tr>

<th>Img</th>
<th>Nom</th>
<th>Muscle</th>
<th>Détails</th>
</tr>';


    foreach ($stmt as $row) {
        echo "<tr>";
        echo '<td> <a href="gestimage.php?id=' . $row['id'] . '">
    <img src="img/' . $row['img'] . '" alt="image" style="max-width: 100px;
    max-height: 100px;"></a></td>';
        echo "<td>" . $row['nom'] . "</td>";

        echo "<td>" . $row['cat'] . " </td>";
        echo '<td><a href="gestimage.php?id=' . $row['id'] . '">
   <i class="fa fa-ellipsis-v"></i>
    </a> </td>';


        echo "</tr>";
    }
}

function CréerListeMod($select)
{
    $pdo = $GLOBALS['pdo'];



    $sql = "SELECT nom FROM cat ";
    $stmt = $pdo->prepare($sql);
    $stmt = $pdo->query($sql);

    echo  '<select id="cat" name="cat">';


    foreach ($stmt as $row) {
        if ($select ==  $row['nom']) {
            echo '<option selected value="' . $row['nom'] . '">' . $row['nom'] . '</option>';
        } else {
            echo '<option value="' . $row['nom'] . '">' . $row['nom'] . '</option>';
        }
    }

    echo "</select>";
}

function AfficherPourModifierEntrainement($id)
{
    $pdo = $GLOBALS['pdo'];
    $sql = "SELECT * FROM Entrainement where id = ?";
    $sql = "SELECT distinct(`Exercices`.id),`Entrainement`.id as train,`Exercices`.img,`Exercices`.nom,`Exercices`.cat,`Entrainement`.series,`Entrainement`.rep,
    `Entrainement`.tempo,`Entrainement`.repos,`Entrainement`.jour,`Entrainement`.numEntrainement FROM `Exercices` INNER JOIN `Entrainement` ON `Exercices`.id = `Entrainement`.idExercices 
     where `Entrainement`.id = ?  LIMIT 0,60";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id]);
    echo '<div id="cont"><h9 style="color:#0dcaf0;">Catégorie</h9> &nbsp;&nbsp;';

    foreach ($stmt as $row) {
        CréerListeMod($row['cat']);
    }

    echo '</div>';
    echo '<table style="width: 0%;">';
    echo "<tr > <label>Num : " . $row['id'] . "</label>";

    CréerListe(2);
    CréerListe(5);

    echo '<td>Nom</td> <td> <input disabled type="text" name="nom" value="' . $row['nom'] . '"></td>';

    echo "</tr>";

    echo "<tr >";


    echo '<td>Séries</td> <td>  <input type="text"  name="nbSeries" value="' . $row['series'] . '"></td>   ';

    echo "</tr>";


    echo "<tr >";


    echo ' <td>Répétitions</td> <td> <input type="text" name="nbRep" value="' . $row['rep'] . '"></td>';

    echo "</tr>";


    echo "<tr >";


    echo ' <td>Tempo </td> <td> <input type="text"  name="tempo" value="' . $row['tempo'] . '"></td>';

    echo "</tr>";

    echo "<tr >";


    echo ' <td>Repos </td><td> <input type="text" name="temps" value="' . $row['repos'] . '"></td>';

    echo "</tr>";


    echo "<tr >";


    echo ' <td>Jours </td><td> <input type="number" name="jour" value="' . $row['jour'] . '"></td>';

    echo "</tr>";


    echo "</tr>";
    // }
    echo '</table>';
}


function AfficherPourModifier($id)
{
    $pdo = $GLOBALS['pdo'];
    $sql = "SELECT id,img,nom,cat,nbSeries,nbRep,tempo,temps FROM Exercices where id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id]);
    echo '<div id="cont"><h9 style="color:#0dcaf0;">Catégorie</h9> &nbsp;&nbsp;';
    foreach ($stmt as $row) {
        CréerListeMod($row['cat']);
    }
    echo '</div>';
    echo '<table style="width: 0%;">';

    echo "<tr >";


    echo '<td>Nom</td> <td> <input type="text" name="nom" value="' . $row['nom'] . '"></td>';

    echo "</tr>";

    echo "<tr >";


    echo '<td>Séries</td> <td>  <input type="text"  name="nbSeries" value="' . $row['nbSeries'] . '"></td>   ';

    echo "</tr>";


    echo "<tr >";


    echo ' <td>Répétitions</td> <td> <input type="text" name="nbRep" value="' . $row['nbRep'] . '"></td>';

    echo "</tr>";


    echo "<tr >";


    echo ' <td>Tempo </td> <td> <input type="text"  name="tempo" value="' . $row['tempo'] . '"></td>';

    echo "</tr>";

    echo "<tr >";


    echo ' <td>Repos </td><td> <input type="text" name="temps" value="' . $row['temps'] . '"></td>';

    echo "</tr>";


    echo "</tr>";
    echo '</table>';
}

function ModExercices($id, $cat, $nom,  $nbSeries, $nbRep, $tempo, $temps)
{
    try {
        $pdo = $GLOBALS['pdo'];
        $sql = "UPDATE Exercices set nom = ? , cat = ?,nbSeries = ? , nbRep = ? , tempo = ?, temps = ? where id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$nom, $cat, $nbSeries, $nbRep, $tempo, $temps, $id]);
    } catch (Exception $e) {
        echo $e;
        return false;
    }
}

function ModEntrainnement($id, $muscle,  $nbSeries, $nbRep, $tempo, $temps, $idExercice, $numEntrainement, $jour)
{
    try {
        $pdo = $GLOBALS['pdo'];
        $sql = "UPDATE Entrainement set muscle = ?,series = ? , rep = ? , tempo = ?, repos = ?,idExercices = ?   
        , numEntrainement = ? , jour = ? where id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$muscle, $nbSeries, $nbRep, $tempo, $temps, $idExercice, $numEntrainement, $jour, $id]);
    } catch (Exception $e) {
        echo $e;
        return false;
    }
}

function TotPages()
{
    $nbMaxImages = 6;
    $pdo = $GLOBALS['pdo'];
    $sql = "SELECT * FROM Exercices ";
    $stmt = $pdo->prepare($sql);
    $stmt = $pdo->query($sql);
    $nb = $stmt->rowCount();

    return round($nb / $nbMaxImages);
}

function AfficherEntrainementSelonJours($jours)
{


    $num = 1;
    $pdo = $GLOBALS['pdo'];


    $sql = "SELECT distinct(`Exercices`.id),`Exercices`.img,`Exercices`.nom,`Exercices`.cat,`Entrainement`.series,`Entrainement`.rep,
    `Entrainement`.tempo,`Entrainement`.repos FROM `Exercices` INNER JOIN `Entrainement` ON `Exercices`.id = `Entrainement`.idExercices 
     where `Entrainement`.jour = ? LIMIT 0,60";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([$jours]);
    foreach ($stmt as $rangee) {
        echo ' <div class="row">
<h4> #' .  $num . ' </h4>
<div class="img">

        <img src="img/' . $rangee['img'] . '" alt="' . $rangee['img'] . '">
        
    </div>
    <h3>' . $rangee['nom'] . '</h3> <br>
    <p style="color:#BCFD4C; font-size:110%;">' . $rangee['cat'] . ' </p>
    <p style="color:#0dcaf0";>' . $rangee['series'] . ' sets  | ' . $rangee['rep'] . ' rep | ' . $rangee['tempo'] . ' | ' . $rangee['repos'] . ' sec</p>
</div>';
        $num++;
    }
}

function AfficherEntrainementSelonTrainning($jours, $numEntrainement)
{

    $num = 1;
    $pdo = $GLOBALS['pdo'];


    $sql = "SELECT distinct(`Exercices`.id),`Entrainement`.id as train,`Exercices`.img,`Exercices`.nom,`Exercices`.cat,`Entrainement`.series,`Entrainement`.rep,
    `Entrainement`.tempo,`Entrainement`.repos FROM `Exercices` INNER JOIN `Entrainement` ON `Exercices`.id = `Entrainement`.idExercices 
     where `Entrainement`.numEntrainement = ? AND `Entrainement`.jour = ? LIMIT 0,60";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([$numEntrainement, $jours]);

    foreach ($stmt as $rangee) {
        echo ' <div class="row">
<h4> #' .  $num . ' </h4>
<div class="img">

<a href="gestEntrainement.php?id=' . $rangee['train'] . '">  <img src="img/' . $rangee['img'] . '" alt="' . $rangee['img'] . '"></a>
        
    </div>
    <h3>' . $rangee['nom'] . '</h3> <br>
    <p style="color:#BCFD4C; font-size:110%;">' . $rangee['cat'] . ' </p>
    <p style="color:#0dcaf0";>' . $rangee['series'] . ' sets  | ' . $rangee['rep'] . ' rep | ' . $rangee['tempo'] . ' | ' . $rangee['repos'] . ' sec</p>
</div>';
        $num++;
    }
}

function AfficherEntrainement($numEntrainement)
{

    $num = 1;
    $pdo = $GLOBALS['pdo'];


    $sql = "SELECT distinct(`Exercices`.id),`Entrainement`.id as train,`Exercices`.img,`Exercices`.nom,`Exercices`.cat,`Entrainement`.series,`Entrainement`.rep,
    `Entrainement`.tempo,`Entrainement`.repos,`Entrainement`.jour FROM `Exercices` INNER JOIN `Entrainement` ON `Exercices`.id = `Entrainement`.idExercices 
     where `Entrainement`.numEntrainement = ?   order by `Entrainement`.jour LIMIT 0,60 ";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([$numEntrainement]);
    $numJourPrec = 0;
    foreach ($stmt as $rangee) {
        if ($numJourPrec != $rangee['jour']) {
            echo '<h1 class="text-white bg-dark" >Jour ' . $rangee['jour'] . '</h1>';
            $numJourPrec = $rangee['jour'];
            $num = 1;
        }
        echo ' <div class="row">
<h4> #' .  $num . ' </h4>
<div class="img">

<a href="gestEntrainement.php?id=' . $rangee['train'] . '">  <img src="img/' . $rangee['img'] . '" alt="' . $rangee['img'] . '"> </a>
        
    </div>
    <h3>' . $rangee['nom'] . '</h3> <br>
    <p style="color:#BCFD4C; font-size:110%;">' . $rangee['cat'] . ' </p>
    <p style="color:#0dcaf0";>' . $rangee['series'] . ' sets  | ' . $rangee['rep'] . ' rep | ' . $rangee['tempo'] . ' | ' . $rangee['repos'] . ' sec</p>
</div>';
        $num++;
    }
}

?>