<?php
    include 'form.php';

    // Nombre de mangas à afficher par page
    $mangasPerPage = 12;

    // Récupérer le numéro de la page actuelle depuis l'URL
    $currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;

    // Récupérer le texte de recherche depuis le formulaire
    $searchQuery = isset($_POST['search']) ? $_POST['search'] : (isset($_GET['search']) ? $_GET['search'] : '');

    // Calculer l'offset pour la requête SQL
    $offset = ($currentPage - 1) * $mangasPerPage;

    try {
        // Préparer la requête SQL en fonction de la présence d'une recherche
        if (!empty($searchQuery)) {
            // Requête SQL avec filtre de recherche
            $sql = "SELECT idManga, nomManga, url FROM manga WHERE nomManga LIKE :searchQuery LIMIT :limit OFFSET :offset";
            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':searchQuery', '%' . $searchQuery . '%', PDO::PARAM_STR);
        } else {
            // Requête SQL sans filtre de recherche
            $sql = "SELECT idManga, nomManga, url FROM manga LIMIT :limit OFFSET :offset";
            $stmt = $pdo->prepare($sql);
        }

        $stmt->bindValue(':limit', $mangasPerPage, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        $mangas = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Requête SQL pour compter le nombre total de mangas
        if (!empty($searchQuery)) {
            $countSql = "SELECT COUNT(*) FROM manga WHERE nomManga LIKE :searchQuery";
            $countStmt = $pdo->prepare($countSql);
            $countStmt->bindValue(':searchQuery', '%' . $searchQuery . '%', PDO::PARAM_STR);
        } else {
            $countSql = "SELECT COUNT(*) FROM manga";
            $countStmt = $pdo->query($countSql);
        }

        $countStmt->execute();
        $totalMangas = $countStmt->fetchColumn();
        $totalPages = ceil($totalMangas / $mangasPerPage);

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
    <title>Mangathèque - Mille Sabords</title>
</head>
<body>
    <!-- Image ajoutée en haut à gauche -->
    <img src="logo.webp" alt="Logo" class="top-left-image">

    <div class="container">
        <h1>Mangathèque</h1>
        <form id="rechercheManga" method="POST" action="">
            <div class="search-bar">
                <input type="text" name="search" placeholder="Rechercher un titre..." value="<?php echo htmlspecialchars($searchQuery); ?>">
                <button type="submit" name="submit">Envoyer</button>
            </div>
        </form>

        <div class="manga-category">
            <h2>Manga Type XYZ</h2>
            <div class="manga-list">
                <?php if (empty($mangas)): ?>
                    <p>Aucun manga trouvé.</p>
                <?php else: ?>
                    <?php foreach ($mangas as $manga): ?>
                        <div class="manga-item">
                            <a href="search.php?id=<?php echo urlencode($manga['idManga']); ?>">
                                <img src="<?php echo htmlspecialchars($manga['url']); ?>" alt="<?php echo htmlspecialchars($manga['nomManga']); ?>">
                                <p><?php echo htmlspecialchars($manga['nomManga']); ?></p>
                            </a>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>

        <!-- Pagination -->
        <div class="pagination">
            <?php if ($currentPage > 1): ?>
                <a href="?page=<?php echo $currentPage - 1; ?><?php if (!empty($searchQuery)) echo '&search=' . urlencode($searchQuery); ?>">Précédent</a>
            <?php endif; ?>

            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <a href="?page=<?php echo $i; ?><?php if (!empty($searchQuery)) echo '&search=' . urlencode($searchQuery); ?>" <?php if ($i == $currentPage) echo 'class="active"'; ?>><?php echo $i; ?></a>
            <?php endfor; ?>

            <?php if ($currentPage < $totalPages): ?>
                <a href="?page=<?php echo $currentPage + 1; ?><?php if (!empty($searchQuery)) echo '&search=' . urlencode($searchQuery); ?>">Suivant</a>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
