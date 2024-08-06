<?php
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: connexion.php');
    exit();
}

try {
    $db = new PDO("mysql:host=localhost;dbname=examen", "root", "");
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Échec de la connexion : " . $e->getMessage();
    die();
}

// Suppression de l'utilisateur
if (isset($_GET['delete'])) {
    $candidatId = $_GET['delete'];
    try {
        $stmt = $db->prepare("DELETE FROM candidat WHERE id = :id");
        $stmt->bindParam(':id', $candidatId);
        $stmt->execute();
        echo "Utilisateur supprimé avec succès!";
    } catch (PDOException $e) {
        echo "Erreur lors de la suppression: " . $e->getMessage();
    }
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nom = $_POST['Nom'];
    $prenom = $_POST['Prénom'];
    $datnais = $_POST['Datnais'];
    $ville = $_POST['ville'];
    $sexe = $_POST['sexe'];
    $codefil = $_POST['codefil'];

    // Insertion dans la base de données0
    try {
        
        $stmt = $db->prepare("INSERT INTO candidat (nom, prenom, datnais, ville, sexe, codefil) VALUES (:nom, :prenom, :datnais, :ville, :sexe, :codefil)");
        $stmt->bindParam(':nom', $nom);
        $stmt->bindParam(':prenom', $prenom);
        $stmt->bindParam(':datnais', $datnais);
        $stmt->bindParam(':ville', $ville);
        $stmt->bindParam(':sexe', $sexe);
        $stmt->bindParam(':codefil', $codefil);
        $stmt->execute();
        echo "Tous les champs sont obligatoires !";
    } catch (PDOException $e) {
        echo "Erreur : " . $e->getMessage();
    }
}
try {
    $db = new PDO("mysql:host=localhost;dbname=examen", "root", "");
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Préparation de la requête SQL
    $stmt = $db->prepare("SELECT id, nom, prenom, datnais, ville, sexe, codefil FROM candidat");
    $stmt->execute();

    // Récupération des résultats
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Échec de la connexion : " . $e->getMessage();
    die(); 
}

?>



<!DOCTYPE html>
<html lang="fr"> 
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des candidats</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }


        table, th, td {
            border: 1px solid black;
            padding: 8px;
            text-align: center;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
<h2>Inscription</h2>
<p>Bienvenue, vous êtes connecté !</p>
<p><a href="deconnexion.php">Se déconnecter</a></p>
<form action="insertion.php" method="post">
    <fieldset>
        <legend><b>Les coordonnées</b></legend>
        <label for="nom">Nom:</label>
        
        <input type="text" id="nom" name="Nom" size="50" maxlength="50"><br><br>
        
        <label for="prenom">Prénom:</label>
        <input type="text" id="prenom" name="Prénom" size="50" maxlength="50"><br><br>
        
        <label for="datnais">Date de naissance:</label>
        <input type="date" id="datnais" name="Datnais"><br><br>
        
        <label for="ville">Ville:</label>
        <input type="text" id="ville" name="ville" size="50" maxlength="50"><br><br>
        <tr><td>sexe: <select name="sexe" required>
            <option value="M">Masculin</option>
            <option value="F">Feminin</option>
        </select></td></tr><br><br>
        
        
        <label for="code_filiere">Codefil :</label>
        <select id="code_filiere" name="codefil">
            <?php
            try {
                $db = new PDO("mysql:host=localhost;dbname=examen", "root", "");
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                // Requête SQL pour récupérer les codes de filière
                $stmt = $db->prepare("SELECT codefil FROM filiere");
                $stmt->execute();

                // Vérification du nombre de lignes retournées
                $rowCount = $stmt->rowCount();

                if ($rowCount > 0) {
                    // Affichage des options de la liste déroulante
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        echo "<option value='". $row["codefil"]. "'>". $row["codefil"]. "</option>";
                    }
                } else {
                    echo "<option>Aucune filière trouvée</option>";
                }
            } catch (PDOException $e) {
                echo "Échec de la connexion : " . $e->getMessage();
                die();
            }
            ?>
        </select><br><br>

        <input type="reset" name="effacer" value="Effacer" style="background-color: red; color: white;">
        <input type="submit" name="enregistrer" value="Enregistrer" style="background-color: green; color: white;">
        
    </fieldset>
</form>

<h3>Liste de tout les candidats</h3>
    <h2>Tableau des données</h2>
    <table>
        <thead>
            <tr>
                <th>Id cand</th>
                <th>Nom</th>
                <th>Prénom</th>
                <th>Date de naissance</th>
                <th>Ville</th>
                <th>Sexe</th>
                <th>codefil</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($rows as $row): ?>
                <tr>
                    <td><?= $row['id'] ?></td>
                    <td><?= $row['nom'] ?></td>
                    <td><?= $row['prenom'] ?></td>
                    <td><?= $row['datnais'] ?></td>
                    <td><?= $row['ville'] ?></td>
                    <td><?= $row['sexe'] ?></td>
                    <td><?= $row['codefil'] ?></td>
                    <td>
                    
                    <form style="display:inline;" action="insertion.php" method="get">
                    <input type="hidden" name="delete" value="<?php echo $row['id']; ?>">
                    <input type="submit" value="Supprimer" style="color: red;">
            </form>
            <form method="post" action="index.php" style="display:inline;">
                    <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                    <input type="submit" name="update" value="Modifier" style="background-color: lightblue;">
                </form>
                    </td>
                    
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>

