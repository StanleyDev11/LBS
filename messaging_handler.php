<?php
// Activer les erreurs pour le débogage
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Inclure le fichier de connexion à la base de données
require 'db.php';

// Définir le type de contenu comme JSON
header('Content-Type: application/json');

// Vérifier que la requête est en POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Lire les données JSON envoyées
        $data = json_decode(file_get_contents('php://input'), true);

        // Vérifier que les données nécessaires sont présentes
        if (!isset($data['recipient']) || !isset($data['message'])) {
            echo json_encode(['success' => false, 'message' => 'Données manquantes.']);
            exit;
        }

        $recipient = $data['recipient'];
        $message = trim($data['message']);
        $dateSent = date('Y-m-d H:i:s');

        // Vérifier que le message n'est pas vide
        if (empty($message)) {
            echo json_encode(['success' => false, 'message' => 'Le message ne peut pas être vide.']);
            exit;
        }

        if ($recipient === 'all') {
            // Insérer pour tous les utilisateurs
            $sql = "INSERT INTO messages (recipient_id, message, date_sent) 
                    SELECT id, :message, :dateSent FROM users";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(['message' => $message, 'dateSent' => $dateSent]);
            echo json_encode(['success' => true, 'message' => 'Message envoyé à tous les utilisateurs.']);
        } else {
            // Insérer pour un utilisateur spécifique
            $sql = "INSERT INTO messages (recipient_id, message, date_sent) 
                    VALUES (:recipient, :message, :dateSent)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(['recipient' => $recipient, 'message' => $message, 'dateSent' => $dateSent]);
            echo json_encode(['success' => true, 'message' => 'Message envoyé à l\'utilisateur spécifié.']);
        }
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Erreur : ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Méthode non autorisée.']);
}
