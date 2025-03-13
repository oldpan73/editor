<?php
require 'config.php';

// In un'applicazione reale l'user_id verrebbe preso dalla sessione
$user_id = 1;

if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
    // Directory base per i caricamenti
    $baseDir = __DIR__ . '/uploads/';
    if (!is_dir($baseDir)) {
        mkdir($baseDir, 0777, true);
    }
    
    // Crea la sottocartella per anno e mese (es. uploads/2025/02/)
    $year = date("Y");
    $month = date("m");
    $subDir = $baseDir . $year . '/' . $month . '/';
    if (!is_dir($subDir)) {
        mkdir($subDir, 0777, true);
    }
    
    // Nome file sicuro
    $originalName = basename($_FILES['file']['name']);
    $safeName = time() . '_' . preg_replace('/[^A-Za-z0-9_\-\.]/', '', $originalName);
    $uploadFile = $subDir . $safeName;
    
    if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadFile)) {
        // Genera la thumbnail con larghezza 300 px (altezza proporzionale)
        $ext = strtolower(pathinfo($uploadFile, PATHINFO_EXTENSION));
        switch($ext) {
            case 'jpg':
            case 'jpeg':
                $img = imagecreatefromjpeg($uploadFile);
                break;
            case 'png':
                $img = imagecreatefrompng($uploadFile);
                break;
            case 'gif':
                $img = imagecreatefromgif($uploadFile);
                break;
            default:
                $img = false;
        }
        if ($img) {
            $width = imagesx($img);
            $height = imagesy($img);
            $newWidth = 300;
            $newHeight = intval($height * $newWidth / $width);
            $thumb = imagecreatetruecolor($newWidth, $newHeight);
            
            // Gestione della trasparenza per PNG e GIF
            if ($ext == 'png' || $ext == 'gif') {
                imagecolortransparent($thumb, imagecolorallocatealpha($thumb, 0, 0, 0, 127));
                imagealphablending($thumb, false);
                imagesavealpha($thumb, true);
            }
            
            imagecopyresampled($thumb, $img, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
            // Nome file della thumbnail: prefisso "thumbs_"
            $thumbFile = $subDir . 'thumbs_' . $safeName;
            switch($ext) {
                case 'jpg':
                case 'jpeg':
                    imagejpeg($thumb, $thumbFile);
                    break;
                case 'png':
                    imagepng($thumb, $thumbFile);
                    break;
                case 'gif':
                    imagegif($thumb, $thumbFile);
                    break;
            }
            imagedestroy($img);
            imagedestroy($thumb);
        }
        
        // Percorso relativo della immagine originale
        $relativePath = 'uploads/' . $year . '/' . $month . '/' . $safeName;
        
        // Inserisce il record nella tabella multimedia
        $stmt = $pdo->prepare("INSERT INTO multimedia (user_id, filename) VALUES (?, ?)");
        $stmt->execute([$user_id, $relativePath]);
        
        // Restituisce il percorso originale per l'inserimento nell'editor
        echo $relativePath;
    } else {
        http_response_code(500);
        echo "Errore durante il caricamento dell'immagine.";
    }
} else {
    http_response_code(400);
    echo "Nessun file inviato o errore nel caricamento.";
}
?>
