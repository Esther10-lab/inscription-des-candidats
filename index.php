<?php
session_start();
try {
    $db = new PDO("mysql:host=localhost;dbname=examen", "root", "");
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Échec de la connexion : " . $e->getMessage();
    die();
}


// Vérification si l'identifiant du candidat à modifier est passé en paramètre
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update'])) {
    // Récupération de l'ID du candidat à modifier
    $id = $_POST['id'];

    // Requête pour récupérer les données du candidat avec l'ID spécifié
    $stmt = $db->prepare("SELECT * FROM candidat WHERE id = :id");
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    $candidat = $stmt->fetch(PDO::FETCH_ASSOC);

    // Vérifier si le candidat a été trouvé
    if ($candidat) {
        // Remplir les variables avec les données du candidat pour pré-remplir le formulaire
        $nom = $candidat['nom'];
        $prenom = $candidat['prenom'];
        $datnais = $candidat['datnais'];
        $ville = $candidat['ville'];
        $sexe = $candidat['sexe'];
        $codefil = $candidat['codefil'];
    } else {
        echo "Aucun candidat trouvé avec l'ID spécifié.";
    }
}

// Si le formulaire de mise à jour est soumis
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
    // Récupération des nouvelles données du formulaire
    $id = $_POST['id'];
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $datnais = $_POST['datnais'];
    $ville = $_POST['ville'];
    $sexe = $_POST['sexe'];
    $codefil = $_POST['codefil'];

    // Requête pour mettre à jour les données du candidat
    $stmt_update = $db->prepare("UPDATE candidat SET nom = :nom, prenom = :prenom, datnais = :datnais, ville = :ville, sexe = :sexe, codefil = :codefil WHERE id = :id");

    try {
        // Exécution de la requête de mise à jour
        $stmt_update->execute([
            'nom' => $nom,
            'prenom' => $prenom,
            'datnais' => $datnais,
            'ville' => $ville,
            'sexe' => $sexe,
            'codefil' => $codefil,
            'id' => $id
        ]);
        echo "Données mises à jour avec succès.";


        $_SESSION['update_success'] = true;
        $_SESSION['nom'] = $nom;
        $_SESSION['prenom'] = $prenom;
        $_SESSION['datnais'] = $datnais;
        $_SESSION['ville'] = $ville;
        $_SESSION['sexe'] = $sexe;
        $_SESSION['codefil'] = $codefil;

        // Redirection vers insertion.php
        header("Location: insertion.php");
        exit();
    } catch (PDOException $e) {
        die("Erreur lors de la mise à jour : " . $e->getMessage());
    }
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modification des informations du candidat</title>
</head>
<body>
    <h2>Modification des informations du candidat</h2>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <input type="hidden" name="id" value="<?php echo htmlspecialchars($id); ?>">
        
        <label for="nom">Nom:</label>
        <input type="text" id="nom" name="nom" value="<?php echo htmlspecialchars($nom); ?>"><br><br>
        
        <label for="prenom">Prénom:</label>
        <input type="text" id="prenom" name="prenom" value="<?php echo htmlspecialchars($prenom); ?>"><br><br>
        
        <label for="datnais">Date de naissance:</label>
        <input type="date" id="datnais" name="datnais" value="<?php echo htmlspecialchars($datnais); ?>"><br><br>
        
        <label for="ville">Ville:</label>
        <input type="text" id="ville" name="ville" value="<?php echo htmlspecialchars($ville); ?>"><br><br>
        
        <label for="sexe">Sexe:</label>
        <select name="sexe" id="sexe">
            <option value="M" <?php if ($sexe == 'M') echo 'selected'; ?>>Masculin</option>
            <option value="F" <?php if ($sexe == 'F') echo 'selected'; ?>>Féminin</option>
        </select><br><br>
        
        <label for="codefil">Codefil:</label>
        <select name="codefil" id="codefil">
            
        <option value="" <?php if ($codefil == '') echo 'selected'; ?>></option>
            <option value="AGE" <?php if ($codefil == 'AGE') echo 'selected'; ?>>AGE</option>
            <option value="AGRO" <?php if ($codefil == 'AGRO') echo 'selected'; ?>>AGRO</option>
            <option value="RIT" <?php if ($codefil == 'RIT') echo 'selected'; ?>>RIT</option>
            <option value="SIL" <?php if ($codefil == 'SIL') echo 'selected'; ?>>SIL</option>

        </select><br><br>
        
        
        <input type="submit" name="submit" value="Mettre à jour">
    </form>
</body>
</html>

