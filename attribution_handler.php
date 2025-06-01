<?php
require 'db.php'; // Connexion à la base de données

// Vérifiez que la requête est POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupération des données envoyées
    $input = json_decode(file_get_contents('php://input'), true);

    $professorId = $input['professorId'] ?? null;
    $filiereId = $input['filiereId'] ?? null;
    $matiereId = $input['matiereId'] ?? null;
    $hours = $input['hours'] ?? null;

    // Vérification des données reçues
    if (!$professorId || !$filiereId || !$matiereId || !$hours) {
        echo json_encode(['success' => false, 'message' => 'Données incomplètes.']);
        exit;
    }

    try {
        // Préparation de la requête d'insertion
        $stmt = $pdo->prepare("
            INSERT INTO cours_attributions (professeur_id, filiere_id, matiere_id, heures)
            VALUES (:professor_id, :filiere_id, :matiere_id, :hours)
        ");
        $stmt->bindParam(':professor_id', $professorId);
        $stmt->bindParam(':filiere_id', $filiereId);
        $stmt->bindParam(':matiere_id', $matiereId);
        $stmt->bindParam(':hours', $hours);

        // Exécution de la requête
        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Attribution effectuée avec succès.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Échec de l\'attribution.']);
        }
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Erreur : ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Méthode non autorisée.']);
}
?>
