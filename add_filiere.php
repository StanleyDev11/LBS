<?php
require 'db.php'; // Inclure votre fichier de connexion à la base de données

// Récupération des données JSON
$data = json_decode(file_get_contents("php://input"), true);
$name = $data['name'] ?? '';

if (empty($name)) {
    echo json_encode(['success' => false, 'message' => 'Le nom de la filière est requis.']);
    exit;
}

try {
    // Insertion dans la table `filieres`
    $sql = "INSERT INTO filieres (name) VALUES (?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$name]);

    echo json_encode(['success' => true, 'message' => 'Filière ajoutée avec succès.']);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Erreur lors de l\'ajout de la filière.']);
}
