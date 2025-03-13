<?php
require 'config.php';

$user_id = 1; // Modifica in base all'utente corrente

$filterYear  = isset($_GET['year']) && !empty($_GET['year']) ? $_GET['year'] : null;
$filterMonth = isset($_GET['month']) && !empty($_GET['month']) ? $_GET['month'] : null;

// Recupera le immagini dal database per l'utente
$stmt = $pdo->prepare("SELECT * FROM multimedia WHERE user_id = ?");
$stmt->execute([$user_id]);
$rows = $stmt->fetchAll();

$images = [];
foreach ($rows as $row) {
    $filename = $row['filename']; // es. uploads/2025/02/123456_nome.jpg
    $include = true;
    if ($filterYear && strpos($filename, '/' . $filterYear . '/') === false) {
        $include = false;
    }
    if ($filterMonth && strpos($filename, '/' . $filterMonth . '/') === false) {
        $include = false;
    }
    if ($include) {
        $images[] = $filename;
    }
}

// Genera l'HTML per ciascuna immagine (mostra la thumbnail e imposta data-url con il percorso originale)
foreach ($images as $img) {
    $pathParts = pathinfo($img);
    $thumbPath = $pathParts['dirname'] . '/thumbs_' . $pathParts['basename'];
    echo '<div class="col-md-3 mb-3 position-relative">';
    echo '<img src="' . $thumbPath . '" class="img-thumbnail selectable-image" style="cursor:pointer;" data-url="' . $img . '">';
    echo '<button class="btn btn-danger btn-sm delete-image" data-url="' . $img . '" style="position: absolute; top: 5px; right: 5px;">&times;</button>';
    echo '</div>';
}
?>
