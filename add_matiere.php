<?php
// Inclure la configuration de la base de données
require 'db.php';

// En-têtes pour l'API
header('Content-Type: application/json');

try {
    // Vérifier que la requête est POST
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Récupérer les données JSON envoyées
        $input = json_decode(file_get_contents('php://input'), true);

        // Vérifier si les champs nécessaires sont présents
        if (!isset($input['name']) || !isset($input['filiereId'])) {
            echo json_encode(['success' => false, 'message' => 'Données incomplètes.']);
            exit;
        }

        $name = $input['name'];
        $filiereId = $input['filiereId'];

        // Insérer la nouvelle matière dans la table `matieres`
        $stmt = $pdo->prepare("INSERT INTO matieres (name, filiere_id) VALUES (:name, :filiere_id)");
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':filiere_id', $filiereId);

        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Matière ajoutée avec succès.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Erreur lors de l\'ajout de la matière.']);
        }
    } else {
        // Si la méthode n'est pas POST, retourner une erreur
        echo json_encode(['success' => false, 'message' => 'Méthode non autorisée.']);
    }
} catch (PDOException $e) {
    // Gérer les erreurs de requête
    echo json_encode(['success' => false, 'message' => 'Erreur de requête : ' . $e->getMessage()]);
}
