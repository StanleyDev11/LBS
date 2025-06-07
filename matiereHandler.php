<?php
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    // AJOUTER
    if ($action === 'ajouter') {
        $matiere = $_POST['matiere'] ?? '';
        $filiere_id = $_POST['filiere_id'] ?? null;

        if ($matiere && $filiere_id) {
            $stmt = $pdo->prepare("INSERT INTO matieres (name, filiere_id, created_at) VALUES (?, ?, NOW())");
            $stmt->execute([$matiere, $filiere_id]);
        }
    }

    // MODIFIER
    elseif ($action === 'modifier') {
        $id = $_POST['id'] ?? null;
        $matiere = $_POST['matiere'] ?? '';
        $filiere_id = $_POST['filiere_id'] ?? null;

        if ($id && $matiere && $filiere_id) {
            $stmt = $pdo->prepare("UPDATE matieres SET name = ?, filiere_id = ? WHERE id = ?");
            $stmt->execute([$matiere, $filiere_id, $id]);
        }
    }

    // SUPPRIMER
    elseif ($action === 'supprimer') {
        $id = $_POST['id'] ?? null;

        if ($id) {
            $stmt = $pdo->prepare("DELETE FROM matieres WHERE id = ?");
            $stmt->execute([$id]);
        }
    }

    // Redirection après traitement
    header("Location: " . $_SERVER['HTTP_REFERER']);
    exit;
}
