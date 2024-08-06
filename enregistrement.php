
<?php 
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
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page d'accueil</title>
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
    <h3>Tous nos candidats</h3>
    <h2>Tableau des données</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
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
                    <td>Action à définir</td> <!-- Insérez ici des liens ou boutons pour les actions -->
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>