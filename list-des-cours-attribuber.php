<?php
require 'db.php';

// Récupérer les filtres depuis l'URL
$filiere = $_GET['filiere'] ?? '';
$matiere = $_GET['matiere'] ?? '';
$date = $_GET['date'] ?? '';

// Requête de base
$sql = "
SELECT 
    ca.id,
    u.name AS professeur_nom,
    f.name AS filiere_nom,
    m.name AS matiere_nom,
    ca.heures,
    ca.date_creation
FROM cours_attributions ca
JOIN users u ON ca.professeur_id = u.id
JOIN filieres f ON ca.filiere_id = f.id
JOIN matieres m ON ca.matiere_id = m.id
WHERE 1 = 1
";

$params = [];

if (!empty($filiere)) {
    $sql .= " AND f.name = :filiere";
    $params['filiere'] = $filiere;
}

if (!empty($matiere)) {
    $sql .= " AND m.name = :matiere";
    $params['matiere'] = $matiere;
}

if (!empty($date)) {
    $sql .= " AND DATE(ca.date_creation) = :date";
    $params['date'] = $date;
}

$sql .= " ORDER BY ca.date_creation DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$cours = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
    background: #fff;
    padding: 20px;
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    max-width: 100%;
    overflow-x: auto;
    margin: auto;
}

.messaging-container h2 {
    font-size: 1.8rem;
    margin-bottom: 20px;
    display: flex;
    align-items: center;
    gap: 10px;
    color: #333;
}

.search-bar {
    border: 1px solid #ccc;
    border-radius: 8px;
    padding: 10px;
    font-size: 1rem;
    width: 100%;
    max-width: 400px;
    box-sizing: border-box;
}

form select, form input[type="date"], form button {
    padding: 10px;
    font-size: 1rem;
    border-radius: 8px;
    border: 1px solid #ccc;
    box-sizing: border-box;
}

form button {
    background-color: #3498db;
    color: white;
    border: none;
    cursor: pointer;
    transition: background 0.3s;
}

form button:hover {
    background-color: #2980b9;
}

table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 10px;
}

table thead {
    background-color: #f5f5f5;
}

table th, table td {
    text-align: left;
    padding: 12px;
    border-bottom: 1px solid #ddd;
}

table th {
    font-weight: 600;
    color: #555;
}

table tbody tr:hover {
    background-color: #f0f8ff;
}

@media (max-width: 768px) {
    form {
        flex-direction: column;
        align-items: stretch;
    }

    form select, form input[type="date"], form button {
        width: 100%;
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
                <a href="index.html" class="menu-item">
                    <i class="fas fa-tachometer-alt"></i>
                    <span>Dashboard</span>
                </a>
                <a href="etudiants.php" class="menu-item">
                    <i class="fas fa-users"></i>
                    <span>Étudiants</span>
                </a>
                <a href="Gestion_prof.php" class="menu-item">
                    <i class="fas fa-chalkboard-teacher"></i>
                    <span>Professeurs</span>
                </a>
                <a href="Cla_Matiere.html" class="menu-item">
                    <i class="fas fa-calendar-alt"></i>
                    <span>Filières&Matières</span>
                </a>
                <a href="calendrier.php" class="menu-item">
                    <i class="fas fa-calendar-alt"></i>
                    <span>Calendrier</span>
                </a>
            </div>
            <div class="menu-section">
                <h3>Administration</h3>
                <a href="cours.php" class="menu-item">
                    <i class="fas fa-book"></i>
                    <span>Cours</span>
                </a>
                <a href="finances.php" class="menu-item">
                    <i class="fas fa-file-invoice-dollar"></i>
                    <span>Finances</span>
                </a>
                <a href="parametres.php" class="menu-item">
                    <i class="fas fa-cog"></i>
                    <span>Paramètres</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Contenu principal vide -->
    <div class="main-content">
    <div class="messaging-container">
    <h2><i class="fas fa-book"></i> Cours Attribués</h2>

    <!-- Barre de recherche -->
    <input type="text" id="searchInput" placeholder="Rechercher par nom de professeur..." class="search-bar" style="margin-bottom: 15px; width: 100%; padding: 10px;">

    <!-- Filtres -->
    <form method="get" style="margin-bottom: 20px; display: flex; gap: 10px; flex-wrap: wrap;">
        <select name="filiere">
            <option value="">Toutes les filières</option>
            <?php
            $res = $pdo->query("SELECT name FROM filieres");
            foreach ($res as $f) {
                $selected = ($filiere == $f['name']) ? 'selected' : '';
                echo '<option value="' . htmlspecialchars($f['name']) . "\" $selected>" . htmlspecialchars($f['name']) . '</option>';
            }
            ?>
        </select>

        <select name="matiere">
            <option value="">Toutes les matières</option>
            <?php
            $res = $pdo->query("SELECT name FROM matieres");
            foreach ($res as $m) {
                $selected = ($matiere == $m['name']) ? 'selected' : '';
                echo '<option value="' . htmlspecialchars($m['name']) . "\" $selected>" . htmlspecialchars($m['name']) . '</option>';
            }
            ?>
        </select>

        <input type="date" name="date" value="<?= htmlspecialchars($date) ?>">

        <button type="submit">Filtrer</button>
    </form>

    <table id="coursTable">
        <thead>
            <tr>
                <th><i class="fas fa-user"></i> Professeur</th>
                <th><i class="fas fa-graduation-cap"></i> Filière</th>
                <th><i class="fas fa-book"></i> Matière</th>
                <th><i class="fas fa-hourglass-half"></i> Heures</th>
                <th><i class="fas fa-calendar-alt"></i> Date</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($cours as $row): ?>
                <tr>
                    <td class="prof-name"><?= htmlspecialchars($row['professeur_nom']) ?></td>
                    <td><?= htmlspecialchars($row['filiere_nom']) ?></td>
                    <td><?= htmlspecialchars($row['matiere_nom']) ?></td>
                    <td><?= htmlspecialchars($row['heures']) ?>h</td>
                    <td><?= htmlspecialchars($row['date_creation']) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>


<script>
    document.getElementById('searchInput').addEventListener('input', function () {
        const filter = this.value.toLowerCase();
        const rows = document.querySelectorAll('#coursTable tbody tr');

        rows.forEach(row => {
            const profName = row.querySelector('.prof-name').textContent.toLowerCase();
            row.style.display = profName.includes(filter) ? '' : 'none';
        });
    });
</script>

    
</body>
</html>
