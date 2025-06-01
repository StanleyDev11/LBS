<?php
require 'db.php'; // Inclure votre fichier de connexion

try {
    $sql = "SELECT id, name FROM filieres";
    $stmt = $pdo->query($sql);
    $filieres = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($filieres);
} catch (Exception $e) {
    echo json_encode([]);
}
?>
