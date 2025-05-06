<?php
    require_once 'connexion/form.php';

    // Récupérer l'ID du manga depuis l'URL
    $auteurID = isset($_GET['id']) ? (int)$_GET['id'] : 0;

    try {
        // Requête SQL pour récupérer les détails du manga en fonction de l'ID
        $sql = "SELECT auteur.* FROM auteur WHERE idAuteur = :id;";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':id', $auteurID, PDO::PARAM_INT);
        $stmt->execute();
        $auteurDetails = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$auteurDetails) {
            echo "Auteur non trouvé.";
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
        <title>Détails de l'Auteur - <?php echo htmlspecialchars($auteurDetails['nom']); ?></title>
    </head>
    <body>
        <div class="container">
            <h1><?php echo htmlspecialchars($auteurDetails['nom']); ?> <?php echo htmlspecialchars($auteurDetails['prenom']); ?></h1>
            <div class="auteur-details">
                <h2>Biographie : <?php echo htmlspecialchars($auteurDetails['boigraphie_courte']); ?></>
                <h2>Nationalité : <?php echo htmlspecialchars($auteurDetails['nationnalite']); ?></>
                <h2>Date de naissance : <?php echo htmlspecialchars($auteurDetails['date_naissance']); ?></>
                <h2>Date de décès : <?php echo htmlspecialchars($auteurDetails['date_deces']); ?></>
                <h2>Biographie longue : <?php echo htmlspecialchars($auteurDetails['biographie_longue']); ?></>
                <h2>Site internet de l'auteur : <?php echo htmlspecialchars($auteurDetails['sit_internet']); ?></>
                <!-- Ajoutez ici d'autres détails de l'auteur si nécessaire -->
            </div>
        </div>
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
    
    .auteur-details {
        margin-top: 20px;
    }
    
    .auteur-details p {
        margin: 10px 0;
    }
    </style>
    </html>
