<?php
require 'db.php'; // Connexion à la base de données

header('Content-Type: application/json');

// Réponse par défaut
$response = ['success' => false, 'message' => 'Une erreur est survenue.'];

// Vérifier si une action est définie
if (isset($_POST['action'])) {
    $action = $_POST['action'];

    if ($action === 'ajouter') {
        $nom = $_POST['nom_complet'] ?? '';
        $matricule = $_POST['matricule'] ?? '';
        $filiere = $_POST['filiere'] ?? '';

        if (!empty($nom) && !empty($matricule) && !empty($filiere)) {
            try {
                $stmt = $pdo->prepare("INSERT INTO etudiants (nom_complet, matricule, filiere) VALUES (?, ?, ?)");
                $stmt->execute([$nom, $matricule, $filiere]);
                $response = ['success' => true, 'message' => 'Étudiant ajouté avec succès.'];
            } catch (PDOException $e) {
                if ($e->getCode() == 23000 && strpos($e->getMessage(), '1062') !== false) {
                    $response['message'] = "Un étudiant avec ce matricule existe déjà.";
                } else {
                    $response['message'] = "Erreur lors de l'ajout : " . $e->getMessage();
                }
            }
        } else {
            $response['message'] = "Veuillez remplir tous les champs.";
        }

    } elseif ($action === 'modifier') {
        $matricule = $_POST['matricule'] ?? '';
        $nom = $_POST['nom_complet'] ?? '';
        $filiere = $_POST['filiere'] ?? '';

        if (!empty($matricule) && !empty($nom) && !empty($filiere)) {
            try {
                $stmt = $pdo->prepare("UPDATE etudiants SET nom_complet = ?, filiere = ? WHERE matricule = ?");
                $stmt->execute([$nom, $filiere, $matricule]);
                $response = ['success' => true, 'message' => 'Étudiant modifié avec succès.'];
            } catch (PDOException $e) {
                $response['message'] = "Erreur lors de la modification : " . $e->getMessage();
            }
        } else {
            $response['message'] = "Champs manquants pour la modification.";
        }

    } elseif ($action === 'supprimer') {
        $matricule = $_POST['matricule'] ?? '';

        if (!empty($matricule)) {
            try {
                $stmt = $pdo->prepare("DELETE FROM etudiants WHERE matricule = ?");
                $stmt->execute([$matricule]);
                $response = ['success' => true, 'message' => 'Étudiant supprimé avec succès.'];
            } catch (PDOException $e) {
                $response['message'] = "Erreur lors de la suppression : " . $e->getMessage();
            }
        } else {
            $response['message'] = "Matricule manquant pour la suppression.";
        }

    } else {
        $response['message'] = "Action non reconnue.";
    }
} else {
    $response['message'] = "Aucune action spécifiée.";
}

echo json_encode($response);
