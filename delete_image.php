<?php
require 'config.php';
header('Content-Type: application/json');

if(isset($_POST['image'])) {
    $image = $_POST['image'];
    $baseDir = realpath(__DIR__ . '/uploads');
    $imagePath = realpath(__DIR__ . '/' . $image);
    
    // Verifica che il file esista e sia all'interno della cartella uploads
    if ($imagePath && strpos($imagePath, $baseDir) === 0 && file_exists($imagePath)) {
        if (unlink($imagePath)) {
            // Cancella anche la thumbnail, se esiste
            $pathParts = pathinfo($imagePath);
            $thumbPath = $pathParts['dirname'] . '/thumbs_' . $pathParts['basename'];
            if(file_exists($thumbPath)) {
                unlink($thumbPath);
            }
            // Rimuove il record dal database
            $stmt = $pdo->prepare("DELETE FROM multimedia WHERE filename = ? AND user_id = ?");
            $stmt->execute([$image, 1]); // Sostituisci 1 con l'user_id corrente
            echo json_encode(['status' => 'success']);
        } else {
            http_response_code(500);
            echo json_encode(['status' => 'error', 'message' => 'Errore durante la cancellazione del file.']);
        }
    } else {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'Immagine non trovata o percorso non valido.']);
    }
} else {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Nessuna immagine specificata.']);
}
?>
