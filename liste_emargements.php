<?php
// Connexion à la base de données
require 'db.php';

// Récupération des filtres
$filiere = $_GET['filiere'] ?? '';
$matiere = $_GET['matiere'] ?? '';
$type = $_GET['type'] ?? '';
$date = $_GET['date'] ?? '';

// Construction de la requête dynamique
$conditions = [];
$params = [];

if ($filiere) {
    $conditions[] = 'f.id = :filiere';
    $params[':filiere'] = $filiere;
}
if ($matiere) {
    $conditions[] = 'm.id = :matiere';
    $params[':matiere'] = $matiere;
}
if ($type) {
    $conditions[] = 'e.type_emargement = :type';
    $params[':type'] = $type;
}
if ($date) {
    $conditions[] = 'DATE(e.date_emargement) = :date';
    $params[':date'] = $date;
}

$whereClause = $conditions ? 'WHERE ' . implode(' AND ', $conditions) : '';

$sql = "
SELECT 
    u.name AS utilisateur_nom,
    f.id AS filiere_id,
    f.name AS filiere_nom,
    m.id AS matiere_id,
    m.name AS matiere_nom,
    e.type_emargement,
    e.heure_cours,
    e.date_emargement
FROM emargement e
JOIN users u ON e.utilisateur_id = u.id
JOIN filieres f ON e.filiere_id = f.id
JOIN matieres m ON e.matiere_id = m.id
$whereClause
ORDER BY e.date_emargement DESC
";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$emargements = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Pour les filtres dropdowns
$filieres = $pdo->query("SELECT id, name FROM filieres")->fetchAll(PDO::FETCH_ASSOC);
$matieres = $pdo->query("SELECT id, name FROM matieres")->fetchAll(PDO::FETCH_ASSOC);
$types = ['Début', 'Fin'];
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

        .messaging-container {
    background: white;
    padding: 30px;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    max-width: 600px;
    margin: auto;
}

.messaging-container h2 {
    margin-bottom: 20px;
    color: #2c3e50;
    display: flex;
    align-items: center;
    gap: 10px;
}

.form-group {
    margin-bottom: 20px;
}

.form-group label {
    display: block;
    margin-bottom: 8px;
    color: #34495e;
    font-weight: bold;
}

.form-group select,
.form-group textarea {
    width: 100%;
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 6px;
    font-size: 1rem;
}

.submit-btn {
    background-color: #3498db;
    color: white;
    padding: 10px 20px;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    font-size: 1rem;
    transition: background 0.3s;
}

.submit-btn:hover {
    background-color: #2980b9;
}

.feedback {
    margin-top: 15px;
    padding: 10px;
    border-radius: 6px;
    display: none;
}

.success-message {
    background-color: #d4edda;
    color: #155724;
}

.error-message {
    background-color: #f8d7da;
    color: #721c24;
}








        h2 {
            color: #2c3e50;
            margin-bottom: 10px;
        }
        .filters {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            margin-bottom: 20px;
        }
        select, input[type="date"], input[type="text"] {
            padding: 8px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }
        .search-bar {
            flex: 1;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #eee;
        }
        th {
            background: #3498db;
            color: white;
        }
        tr:hover {
            background-color: #f1f1f1;
        }
        .top-icons {
            text-align: right;
            margin-bottom: 10px;
        }
        .top-icons i {
            margin-left: 10px;
            color: #3498db;
            cursor: pointer;
            transition: 0.3s;
        }
        .top-icons i:hover {
            color: #2980b9;
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
    <div class="top-icons">
        <i class="fas fa-file-export" title="Exporter"></i>
        <i class="fas fa-sync-alt" title="Actualiser" onclick="location.reload()"></i>
        <i class="fas fa-filter" title="Filtrer"></i>
    </div>

  <h2>Liste des Émargements</h2>

<form method="get" class="filters">
    <input type="text" id="searchInput" placeholder="Rechercher par nom d'utilisateur..." class="search-bar">

    <select name="filiere">
        <option value="">Toutes les filières</option>
        <?php foreach ($filieres as $f): ?>
            <option value="<?= $f['id'] ?>" <?= ($filiere == $f['id'] ? 'selected' : '') ?>>
                <?= htmlspecialchars($f['name']) ?>
            </option>
        <?php endforeach; ?>
    </select>

    <select name="matiere">
        <option value="">Toutes les matières</option>
        <?php foreach ($matieres as $m): ?>
            <option value="<?= $m['id'] ?>" <?= ($matiere == $m['id'] ? 'selected' : '') ?>>
                <?= htmlspecialchars($m['name']) ?>
            </option>
        <?php endforeach; ?>
    </select>

    <select name="type">
        <option value="">Tous les types</option>
        <?php foreach ($types as $t): ?>
            <option value="<?= $t ?>" <?= ($type == $t ? 'selected' : '') ?>><?= $t ?></option>
        <?php endforeach; ?>
    </select>

    <input type="date" name="date" value="<?= htmlspecialchars($date) ?>">
    <button type="submit">Filtrer</button>
</form>

<table id="emargementTable">
    <thead>
        <tr>
            <th><i class="fas fa-user icon"></i> Utilisateur</th>
            <th><i class="fas fa-graduation-cap icon"></i> Filière</th>
            <th><i class="fas fa-book icon"></i> Matière</th>
            <th><i class="fas fa-clock icon"></i> Type</th>
            <th><i class="fas fa-hourglass-half icon"></i> Heure</th>
            <th><i class="fas fa-calendar-day icon"></i> Date</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($emargements as $row): ?>
            <tr>
                <td class="user-name"><?= htmlspecialchars($row['utilisateur_nom']) ?></td>
                <td><?= htmlspecialchars($row['filiere_nom']) ?></td>
                <td><?= htmlspecialchars($row['matiere_nom']) ?></td>
                <td><?= htmlspecialchars($row['type_emargement']) ?></td>
                <td><?= htmlspecialchars($row['heure_cours']) ?>h</td>
                <td><?= htmlspecialchars($row['date_emargement']) ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<script>
    // Filtrage en direct dès la première lettre
    document.getElementById('searchInput').addEventListener('input', function () {
        const filter = this.value.toLowerCase();
        const rows = document.querySelectorAll('#emargementTable tbody tr');

        rows.forEach(row => {
            const name = row.querySelector('.user-name').textContent.toLowerCase();
            row.style.display = name.includes(filter) ? '' : 'none';
        });
    });
</script>

</html>
