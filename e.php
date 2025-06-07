<?php
// Connexion à la base de données
require 'db.php';

// Récupération des professeurs
$sqlProfessors = "SELECT id, name, email FROM users WHERE role = 'prof'";
$stmtProfessors = $pdo->prepare($sqlProfessors);
$stmtProfessors->execute();
$professors = $stmtProfessors->fetchAll(PDO::FETCH_ASSOC);
?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page Vide - UniDashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* ... mêmes styles CSS que ta page précédente ... */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            display: flex;
            background-color: #f5f7fa;
        }

        .sidebar {
            width: 250px;
            background: #2c3e50;
            color: white;
            height: 100vh;
            padding: 20px 0;
            position: fixed;
        }

        .logo {
            text-align: center;
            padding: 0 20px 20px;
            border-bottom: 1px solid #34495e;
        }

        .logo h1 {
            font-size: 1.5rem;
            margin-bottom: 5px;
        }

        .menu {
            padding: 20px;
        }

        .menu-section {
            margin-bottom: 30px;
        }

        .menu-section h3 {
            color: #7f8c8d;
            font-size: 0.9rem;
            margin-bottom: 15px;
            text-transform: uppercase;
        }

        .menu-item {
            padding: 10px 0;
            display: flex;
            align-items: center;
            cursor: pointer;
            transition: 0.3s;
            color: white;
            text-decoration: none;
        }

        .menu-item:hover {
            color: #3498db;
        }

        .menu-item i {
            margin-right: 10px;
            width: 20px;
            text-align: center;
        }

        .main-content {
            margin-left: 250px;
            padding: 30px;
            width: calc(100% - 250px);
        }

        @media (max-width: 768px) {
            .sidebar {
                width: 80px;
            }

            .sidebar .logo h1,
            .menu-section h3,
            .menu-item span {
                display: none;
            }

            .menu-item {
                justify-content: center;
            }

            .main-content {
                margin-left: 80px;
                width: calc(100% - 80px);
                padding: 15px;
            }
        }

        

    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
    <div class="logo">
        <h1>UniDashboard</h1>
        <p>Gestion Universitaire</p>
    </div>
    <div class="menu">
        <div class="menu-section">
            <h3>Navigation</h3>
            <a href="index.html" class="menu-item active">
                <i class="fas fa-tachometer-alt"></i>
                <span>Dashboard</span>
            </a>
            <a href="etudiants.php" class="menu-item">
                <i class="fas fa-user-graduate"></i>
                <span>Étudiants</span>
            </a>
            <a href="Gestion_prof.php" class="menu-item">
                <i class="fas fa-chalkboard-teacher"></i>
                <span>Professeurs</span>
            </a>
            <a href="Cla_Matiere.html" class="menu-item">
                <i class="fas fa-layer-group"></i>
                <span>Filières</span>
            </a>
            <a href="calendrier.php" class="menu-item">
                <i class="fas fa-calendar-alt"></i>
                <span>Calendrier</span>
            </a>
        </div>
        <div class="menu-section">
            <h3>Administration</h3>
            <a href="list-des-cours-attribuber.php" class="menu-item">
                <i class="fas fa-book-open"></i>
                <span>Cours</span>
            </a>
            <a href="finances.php" class="menu-item">
                <i class="fas fa-wallet"></i>
                <span>Finances</span>
            </a>
            <a href="messagerie.php" class="menu-item">
                <i class="fas fa-envelope"></i>
                <span>Messagerie</span>
            </a>
            <a href="liste_emargements.php" class="menu-item">
                <i class="fas fa-clipboard-list"></i>
                <span>Emargement</span>
            </a>
            <a href="Matiere.php" class="menu-item">
                <i class="fas fa-book"></i>
                <span>Matière</span>
            </a>
            <a href="parametres.php" class="menu-item">
                <i class="fas fa-cogs"></i>
                <span>Paramètres</span>
            </a>
        </div>
    </div>
</div>


    <!-- Contenu principal vide -->
    <div class="main-content">
        <!-- Tu peux ajouter ici ton contenu personnalisé -->
      

    </div>
</body>
</html>
