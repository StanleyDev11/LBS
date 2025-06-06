<?php
// Connexion à la base de données
require 'db.php';


// Récupération des professeurs
$sqlProfessors = "SELECT id, name, email FROM users WHERE role = 'prof'";
$stmtProfessors = $pdo->prepare($sqlProfessors);
$stmtProfessors->execute();
$professors = $stmtProfessors->fetchAll(PDO::FETCH_ASSOC);

// Récupération des filières
$sqlFilieres = "SELECT id, name FROM filieres";
$stmtFilieres = $pdo->prepare($sqlFilieres);
$stmtFilieres->execute();
$filieres = $stmtFilieres->fetchAll(PDO::FETCH_ASSOC);

// Récupération des matières (associées à une filière)
$sqlMatieres = "SELECT id, name, filiere_id FROM matieres";
$stmtMatieres = $pdo->prepare($sqlMatieres);
$stmtMatieres->execute();
$matieres = $stmtMatieres->fetchAll(PDO::FETCH_ASSOC);
?>
