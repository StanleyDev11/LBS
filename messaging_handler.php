<?php
// Connexion à la base de données MySQL
$host = "localhost";
$dbname = "votre_base";
$username = "votre_utilisateur";
$password = "votre_mot_de_passe";

$conn = new mysqli($host, $username, $password, $dbname);

// Vérifie la connexion
if ($conn->connect_error) {
    die("Erreur de connexion : " . $conn->connect_error);
}

// Récupère l'action via POST (add, edit, delete)
$action = $_POST['action'] ?? '';

// Gère les actions selon la valeur
switch ($action) {

    // Ajouter un professeur
    case 'add':
        $name = $_POST['name'] ?? '';
        $email = $_POST['email'] ?? '';

        // Vérifie que les champs sont remplis
        if ($name && $email) {
            $stmt = $conn->prepare("INSERT INTO professeurs (nom, email) VALUES (?, ?)");
            $stmt->bind_param("ss", $name, $email);
            $stmt->execute();
            echo "Professeur ajouté avec succès.";
        } else {
            echo "Nom et Email requis.";
        }
        break;

    // Modifier un professeur
    case 'edit':
        $id = $_POST['id'] ?? '';
        $name = $_POST['name'] ?? '';
        $email = $_POST['email'] ?? '';

        if ($id && $name && $email) {
            $stmt = $conn->prepare("UPDATE professeurs SET nom = ?, email = ? WHERE id = ?");
            $stmt->bind_param("ssi", $name, $email, $id);
            $stmt->execute();
            echo "Professeur modifié avec succès.";
        } else {
            echo "Tous les champs sont requis pour la modification.";
        }
        break;

    // Supprimer un professeur
    case 'delete':
        $id = $_POST['id'] ?? '';

        if ($id) {
            $stmt = $conn->prepare("DELETE FROM professeurs WHERE id = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            echo "Professeur supprimé avec succès.";
        } else {
            echo "ID requis pour supprimer.";
        }
        break;

    // Action non reconnue
    default:
        echo "Action invalide.";
}

// Ferme la connexion
$conn->close();
?>
