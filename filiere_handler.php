<?php
header('Content-Type: application/json');
require 'db.php'; // Fichier de connexion PDO (assure-toi que $pdo est défini dedans)

try {
    $data = json_decode(file_get_contents('php://input'), true);

    if (!isset($data['action'])) {
        throw new Exception("Action manquante");
    }

    $action = $data['action'];

    switch ($action) {
        case 'add':
            if (empty($data['name'])) {
                throw new Exception("Le nom de la filière est requis");
            }

            $stmt = $pdo->prepare("INSERT INTO filieres (name, created_at) VALUES (?, NOW())");
            $success = $stmt->execute([$data['name']]);
            echo json_encode(['success' => $success]);
            break;

        case 'update':
            if (empty($data['id']) || empty($data['name'])) {
                throw new Exception("ID et nom sont requis");
            }

            $stmt = $pdo->prepare("UPDATE filieres SET name = ? WHERE id = ?");
            $success = $stmt->execute([$data['name'], $data['id']]);
            echo json_encode(['success' => $success]);
            break;

        case 'delete':
            if (empty($data['id'])) {
                throw new Exception("ID requis pour la suppression");
            }

            $stmt = $pdo->prepare("DELETE FROM filieres WHERE id = ?");
            $success = $stmt->execute([$data['id']]);
            echo json_encode(['success' => $success]);
            break;

        case 'list':
            $stmt = $pdo->query("SELECT * FROM filieres ORDER BY created_at DESC");
            $filieres = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode(['success' => true, 'filieres' => $filieres]);
            break;

        default:
            throw new Exception("Action invalide");
    }

} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
