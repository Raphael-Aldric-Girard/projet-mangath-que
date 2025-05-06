<?php
require_once 'connexion/form.php';

// Récupérer l'ID du manga depuis l'URL
$mangaId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

try {
    // Requête SQL pour récupérer les détails du manga en fonction de l'ID
    $sql = "SELECT manga.*, auteur.*, maison_edition.*, dessinateur.*
    FROM manga
    INNER JOIN auteur ON manga.idAuteur = auteur.idAuteur
    INNER JOIN maison_edition ON manga.idMaisonEdition = maison_edition.idMaisonEdition
    INNER JOIN dessinateur ON manga.idDessinateur = dessinateur.idDessinateur
    WHERE manga.idManga = :id;
";    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':id', $mangaId, PDO::PARAM_INT);
    $stmt->execute();
    $mangaDetails = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$mangaDetails) {
        echo "Manga non trouvé.";
        exit;
    }

} catch (PDOException $e) {
    $msg = 'ERREUR PDO dans ' . $e->getFile() . ' : ' . $e->getLine() . ' : ' . $e->getMessage();
    die($msg);
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="asset/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Comic+Neue:wght@400;700&display=swap" rel="stylesheet">
    <title>Détails du Manga - <?php echo htmlspecialchars($mangaDetails['nomManga']); ?></title>
</head>
<body>
    <div class="container">
        <h1><?php echo htmlspecialchars($mangaDetails['nomManga']); ?></h1>
        <div class="manga-details">
            <div class="image-container">
                <img src="<?php echo htmlspecialchars($mangaDetails['url']); ?>" alt="<?php echo htmlspecialchars($mangaDetails['nomManga']); ?>" class="manga-image">
                <button class="buy-button">Acheter</button>
                <h2>Prix : <?php echo htmlspecialchars($mangaDetails['prix']); ?>€</h2>
                <h2>Laissez un avis : </h2>
                <textarea id="avis" name="avis" rows="4" cols="25" maxlength="500" placeholder="Entrez votre avis ici..."></textarea>
            </div>
            <div class="manga-text">
                <h2>Résumé court : <?php echo htmlspecialchars($mangaDetails['resume_court']); ?></h2>
                <h2>Résumé long : <?php echo htmlspecialchars($mangaDetails['resume_long']); ?></h2>
                <h2>Date de parution : <?php echo htmlspecialchars($mangaDetails['date_parution']); ?></h2>
                <h2>
                    Auteur : <a href="auteur.php?id=<?php echo htmlspecialchars($mangaDetails['idAuteur']); ?>">
                    <?php echo htmlspecialchars($mangaDetails['nom']); ?> <?php echo htmlspecialchars($mangaDetails['prenom']); ?>
                    </a>
                </h2>
                <button id="showMoreButton">Plus de caractéristiques</button>
                <div id="characteristicsList" class="characteristics-list">
                    <ul>
                        <li>Maison d'édition : <?php echo htmlspecialchars($mangaDetails['nomEdit']); ?></li>
                        <li>Adresse maison d'édition : <?php echo htmlspecialchars($mangaDetails['adresse']); ?></li>
                        <li>Site internet maison d'édition : <?php echo htmlspecialchars($mangaDetails['site_internet']); ?></li>
                        <li>Présentation maison d'édition : <?php echo htmlspecialchars($mangaDetails['presentation']); ?></li>
                        <li>Dessinateur : <a href="dessinateur.php?id=<?php echo htmlspecialchars($mangaDetails['idDessinateur']); ?>">
                    <?php echo htmlspecialchars($mangaDetails['nomDessi']); ?> <?php echo htmlspecialchars($mangaDetails['prenomDessi']); ?>
                    </a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('showMoreButton').addEventListener('click', function() {
            var characteristicsList = document.getElementById('characteristicsList');
            if (characteristicsList.style.display === 'none' || characteristicsList.style.display === '') {
                characteristicsList.style.display = 'block';
            } else {
                characteristicsList.style.display = 'none';
            }
        });
    </script>
</body>
<style>
body {
    font-family: 'Comic Neue', sans-serif;
    background-color: #f4f4f9;
    color: #333;
    margin: 0;
    padding: 0;
}

.container {
    max-width: 1200px;
    margin: 20px auto;
    padding: 20px;
    background-color: #fff;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    border-radius: 8px;
}

h1 {
    text-align: center;
    color: black;
}

.manga-details {
    display: flex;
    align-items: flex-start;
    gap: 20px;
}

.image-container {
    text-align: center;
}

.manga-image {
    max-width: 300px;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
}

.buy-button {
    display: block;
    margin-top: 10px;
    padding: 10px 74px;
    background-color: #4CAF50;
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-size: 16px;
}

.buy-button:hover {
    background-color: #45a049;
}

.manga-text {
    flex: 1;
}

.manga-text h2, .manga-text h3, .manga-text h4 {
    margin-top: 10px;
}

.characteristics-list {
    display: none;
    margin-top: 10px;
}

.characteristics-list ul {
    list-style-type: none;
    padding: 0;
}

.characteristics-list li {
    margin: 5px 0;
}
</style>
</html>
