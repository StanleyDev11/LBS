<?php
require 'db.php'; // Connexion via PDO

header('Content-Type: application/json');

// Lire les données JSON
$data = json_decode(file_get_contents("php://input"), true);
if (!$data || !isset($data['action'])) {
    echo json_encode(["success" => false, "message" => "Requête invalide."]);
    exit;
}

$action = $data['action'];

if ($action === 'add') {
    $name = $data['name'] ?? '';
    $email = $data['email'] ?? '';
    $password = password_hash($data['password'] ?? '123456', PASSWORD_DEFAULT);

    if (!$name || !$email || !$password) {
        echo json_encode(["success" => false, "message" => "Champs manquants."]);
        exit;
    }

    // Vérification email existant
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$email]);
    if ($stmt->rowCount() > 0) {
        echo json_encode(["success" => false, "message" => "Cet email est déjà utilisé."]);
        exit;
    }

    // Insertion
    $stmt = $pdo->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, 'prof')");
    if ($stmt->execute([$name, $email, $password])) {
        echo json_encode(["success" => true, "message" => "Professeur ajouté avec succès."]);
    } else {
        echo json_encode(["success" => false, "message" => "Erreur lors de l'ajout."]);
    }
    exit;
}

if ($action === 'update') {
    $id = intval($data['id']);
    $name = $data['name'] ?? '';
    $email = $data['email'] ?? '';

    if (!$id || !$name || !$email) {
        echo json_encode(["success" => false, "message" => "Champs manquants."]);
        exit;
    }

    $stmt = $pdo->prepare("UPDATE users SET name = ?, email = ? WHERE id = ? AND role = 'prof'");
    if ($stmt->execute([$name, $email, $id])) {
        echo json_encode(["success" => true, "message" => "Professeur modifié avec succès."]);
    } else {
        echo json_encode(["success" => false, "message" => "Erreur lors de la modification."]);
    }
    exit;
}

if ($action === 'delete') {
    $id = intval($data['id']);
    if (!$id) {
        echo json_encode(["success" => false, "message" => "ID invalide."]);
        exit;
    }

    $stmt = $pdo->prepare("DELETE FROM users WHERE id = ? AND role = 'prof'");
    if ($stmt->execute([$id])) {
        echo json_encode(["success" => true, "message" => "Professeur supprimé avec succès."]);
    } else {
        echo json_encode(["success" => false, "message" => "Erreur lors de la suppression."]);
    }
    exit;
}

echo json_encode(["success" => false, "message" => "Action non reconnue."]);
