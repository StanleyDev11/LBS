<?php
try {
    $pdo = new PDO('mysql:host=localhost;dbname=lbs', 'root', ''); // Mettez les bons paramètres ici
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo json_encode(["success" => false, "message" => "Connexion échouée: " . $e->getMessage()]);
    exit;
}
?>
