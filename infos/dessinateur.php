<?php
    require_once 'connexion/form.php';

    // Récupérer l'ID du manga depuis l'URL
    $dessinateurID = isset($_GET['id']) ? (int)$_GET['id'] : 0;

    try {
        // Requête SQL pour récupérer les détails du manga en fonction de l'ID
        $sql = "SELECT dessinateur.* FROM dessinateur WHERE idDessinateur = :id;";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':id', $dessinateurID, PDO::PARAM_INT);
        $stmt->execute();
        $dessinateurDetails = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$dessinateurDetails) {
            echo "Dessinateur non trouvé.";
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
        <link rel="stylesheet" href="style.css">
        <link href="https://fonts.googleapis.com/css2?family=Comic+Neue:wght@400;700&display=swap" rel="stylesheet">
        <title>Détails de l'Auteur - <?php echo htmlspecialchars($dessinateurDetails['nom']); ?></title>
    </head>
    <body>
        <div class="container">
            <h1><?php echo htmlspecialchars($dessinateurDetails['nomDessi']); ?> <?php echo htmlspecialchars($dessinateurDetails['prenomDessi']); ?></h1>
            <div class="auteur-details">
                <h2>Biographie : <?php echo htmlspecialchars($dessinateurDetails['biographie']); ?></>
                <h2>Nationalité : <?php echo htmlspecialchars($dessinateurDetails['nationalite']); ?></>
                <h2>Date de naissance : <?php echo htmlspecialchars($dessinateurDetails['date_naissance']); ?></>
                <h2>Date de décès : <?php echo htmlspecialchars($dessinateurDetails['date_deces']); ?></>
                <h2>Site internet de l'auteur : <?php echo htmlspecialchars($dessinateurDetails['site_internet']); ?></>
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
