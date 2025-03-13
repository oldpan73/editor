<?php
require 'config.php';

$user_id = 1; // Modifica in base all'utente

// Utilizziamo SUBSTR per estrarre l'anno dal percorso (assumendo che il percorso sia "uploads/YYYY/MM/...")
$stmt = $pdo->prepare("SELECT DISTINCT SUBSTR(filename, 9, 4) AS year FROM multimedia WHERE user_id = ?");
$stmt->execute([$user_id]);
$years = $stmt->fetchAll(PDO::FETCH_COLUMN);

sort($years);
foreach ($years as $year) {
    echo '<option value="' . $year . '">' . $year . '</option>';
}
?>
