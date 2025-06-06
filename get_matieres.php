<?php
require 'db.php'; // Connexion à la base de données

header('Content-Type: application/json');

// Vérifie si l'ID de la filière est présent et valide
$filiereId = isset($_GET['filiereId']) ? intval($_GET['filiereId']) : 0;

if ($filiereId <= 0) {
    echo json_encode(["success" => false, "message" => "ID de filière invalide."]);
    exit;
}

try {
    // Requête pour récupérer les matières liées à la filière
    $stmt = $pdo->prepare("SELECT id, name FROM matieres WHERE filiere_id = ?");
    $stmt->execute([$filiereId]);
    $matieres = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        "success" => true,
        "matieres" => $matieres
    ]);
} catch (PDOException $e) {
    echo json_encode([
        "success" => false,
        "message" => "Erreur lors de la récupération des matières : " . $e->getMessage()
    ]);
}
?>
