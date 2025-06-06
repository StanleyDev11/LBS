<?php
require 'db.php';


$sqlProfessors = "SELECT id, name, email FROM users WHERE role = 'prof'";
$stmtProfessors = $pdo->prepare($sqlProfessors);
$stmtProfessors->execute();
$professors = $stmtProfessors->fetchAll(PDO::FETCH_ASSOC);

$sqlFilieres = "SELECT id, name FROM filieres";
$stmtFilieres = $pdo->prepare($sqlFilieres);
$stmtFilieres->execute();
$filieres = $stmtFilieres->fetchAll(PDO::FETCH_ASSOC);

$sqlMatieres = "SELECT id, name, filiere_id FROM matieres";
$stmtMatieres = $pdo->prepare($sqlMatieres);
$stmtMatieres->execute();
$matieres = $stmtMatieres->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Professeurs</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Styles du dashboard */
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

        /* Sidebar (identique au dashboard) */
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

        /* Main Content */
        .main-content {
            margin-left: 250px;
            padding: 30px;
            width: calc(100% - 250px);
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }

        .header h2 {
            color: #2c3e50;
            font-size: 1.8rem;
        }

        /* Tableau des professeurs */
        .table-container {
            background: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #ecf0f1;
        }

        th {
            background-color: #3498db;
            color: white;
        }

        tr:hover {
            background-color: #f8f9fa;
        }

        .btn {
            padding: 8px 15px;
            border-radius: 4px;
            cursor: pointer;
            border: none;
            font-weight: 500;
            transition: all 0.3s;
        }

        .btn-primary {
            background-color: #3498db;
            color: white;
        }

        .btn-primary:hover {
            background-color: #2980b9;
        }

        /* Modal */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            justify-content: center;
            align-items: center;
            z-index: 1000;
        }

        .modal-content {
            background-color: white;
            padding: 25px;
            border-radius: 10px;
            width: 450px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 1px solid #eee;
        }

        .modal-header h3 {
            color: #2c3e50;
            font-size: 1.3rem;
        }

        .close-btn {
            background: none;
            border: none;
            font-size: 1.5rem;
            cursor: pointer;
            color: #7f8c8d;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #2c3e50;
            font-weight: 500;
        }

        .form-control {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 1rem;
        }

        .form-control:focus {
            border-color: #3498db;
            outline: none;
            box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.2);
        }

        .modal-footer {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            margin-top: 20px;
            padding-top: 15px;
            border-top: 1px solid #eee;
        }

        .btn-success {
            background-color: #2ecc71;
            color: white;
        }

        .btn-success:hover {
            background-color: #27ae60;
        }

        .btn-danger {
            background-color: #e74c3c;
            color: white;
        }

        .btn-danger:hover {
            background-color: #c0392b;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .sidebar {
                width: 80px;
            }
            .sidebar .logo h1, .menu-section h3, .menu-item span {
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
    <!-- Sidebar identique au dashboard -->
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
                <a href="Gestion_prof.php" class="menu-item active">
                    <i class="fas fa-chalkboard-teacher"></i>
                    <span>Professeurs</span>
                </a>
                <a href="Cla_Matiere.html" class="menu-item">
                    <i class="fas fa-layer-group"></i>
                    <span>Filières & Matières</span>
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

    <!-- Main Content -->
    <div class="main-content">
        <div class="header">
    <h2>Gestion des Professeurs</h2>
    <div>
        <button class="btn btn-success" onclick="openAddModal()"><i class="fas fa-plus"></i> Ajouter</button>
        <button class="btn btn-success" onclick="openAddModal()"><i class="fas fa-plus"></i> Activer Le CRUD</button>

        
    </div>
</div>

      <div class="table-container">
    <!-- Barre de recherche -->
    <div style="display: flex; flex-wrap: wrap; justify-content: space-between; margin-bottom: 20px;">
        <input type="text" id="searchInput" oninput="filterProfessors()" placeholder="Rechercher un professeur..." style="padding: 10px; border: 1px solid #ccc; border-radius: 5px; width: 45%;">
    </div>

    <div id="professorCards" style="display: flex; flex-wrap: wrap; gap: 20px;">
        <?php foreach ($professors as $professor): ?>
            <div class="prof-card" data-name="<?= strtolower($professor['name']) ?>" style="background: #fff; border-radius: 10px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); padding: 20px; width: 280px; display: flex; flex-direction: column; align-items: center;">
                <i class="fas fa-chalkboard-teacher" style="font-size: 50px; color: #3498db; margin-bottom: 15px;"></i>
                <h3 style="color: #2c3e50; margin-bottom: 5px;"><?= htmlspecialchars($professor['name']) ?></h3>
                <p style="color: #7f8c8d; font-size: 0.9rem; margin-bottom: 15px;"><?= htmlspecialchars($professor['email']) ?></p>
                <button class="btn btn-primary" onclick="openModal(<?= $professor['id'] ?>, '<?= htmlspecialchars($professor['name']) ?>')">
                    <i class="fas fa-book"></i> Attribuer un Cours
                </button>

                <div style="margin-top: 10px; display: flex; gap: 10px;">
        <button class="btn btn-warning" onclick="openEditModal(<?= $professor['id'] ?>, '<?= htmlspecialchars($professor['name']) ?>', '<?= htmlspecialchars($professor['email']) ?>')">
            <i class="fas fa-edit"></i>
        </button>
        <button class="btn btn-danger" onclick="deleteProfessor(<?= $professor['id'] ?>)">
            <i class="fas fa-trash-alt"></i>
        </button>
        </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

</div>


    </div>

    <!-- Modal -->
    <div class="modal" id="assignModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Attribuer un Cours</h3>
                <button class="close-btn" onclick="closeModal()">&times;</button>
            </div>
            <form id="assignForm">
                <div class="form-group">
                    <label for="professorName">Professeur</label>
                    <input type="text" class="form-control" id="professorName" disabled>
                </div>
                <div class="form-group">
                    <label for="filiere">Filière</label>
                    <select class="form-control" id="filiere" onchange="filterMatieres()" required>
                        <option value="">-- Sélectionnez une filière --</option>
                        <?php foreach ($filieres as $filiere): ?>
                            <option value="<?= $filiere['id'] ?>"><?= htmlspecialchars($filiere['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="matiere">Matière</label>
                    <select class="form-control" id="matiere" required>
                        <option value="">-- Sélectionnez une matière --</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="hours">Nombre d'heures</label>
                    <input type="number" class="form-control" id="hours" min="1" max="50" placeholder="Ex: 10" required>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success" onclick="submitForm()">
                        <i class="fas fa-check"></i> Attribuer
                    </button>
                    <button type="button" class="btn btn-danger" onclick="closeModal()">
                        <i class="fas fa-times"></i> Annuler
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="modal" id="editModal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Modifier le Professeur</h3>
            <button class="close-btn" onclick="closeEditModal()">&times;</button>
        </div>
        <form id="editForm">
            <input type="hidden" id="editId">
            <div class="form-group">
                <label for="editName">Nom</label>
                <input type="text" id="editName" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="editEmail">Email</label>
                <input type="email" id="editEmail" class="form-control" required>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" onclick="submitEdit()">
                    <i class="fas fa-check"></i> Enregistrer
                </button>
                <button type="button" class="btn btn-danger" onclick="closeEditModal()">
                    <i class="fas fa-times"></i> Annuler
                </button>
            </div>
        </form>
    </div>
</div>


<!-- MODAL AJOUT PROFESSEUR -->
<div class="modal" id="addModal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Ajouter un Professeur</h3>
            <button class="close-btn" onclick="closeAddModal()">&times;</button>
        </div>
        <form id="addForm">
            <div class="form-group">
                <label for="addName">Nom</label>
                <input type="text" id="addName" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="addEmail">Email</label>
                <input type="email" id="addEmail" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="addPassword">Mot de passe</label>
                <input type="password" id="addPassword" class="form-control" required>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" onclick="submitAdd()">
                    <i class="fas fa-check"></i> Ajouter
                </button>
                <button type="button" class="btn btn-danger" onclick="closeAddModal()">
                    <i class="fas fa-times"></i> Annuler
                </button>
            </div>
        </form>
    </div>
</div>
<!-- SweetAlert2 CDN -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>



    <script>
       let currentProfessorId = null;
const matieres = <?= json_encode($matieres) ?>;

// === MODAL ATTRIBUTION ===
function openModal(professorId, professorName) {
    currentProfessorId = professorId;
    document.getElementById("professorName").value = professorName;
    document.getElementById("assignModal").style.display = "flex";
}

function closeModal() {
    currentProfessorId = null;
    document.getElementById("assignForm").reset();
    document.getElementById("matiere").innerHTML = '<option value="">-- Sélectionnez une matière --</option>';
    document.getElementById("assignModal").style.display = "none";
}

function filterMatieres() {
    const filiereId = document.getElementById("filiere").value;
    const matiereSelect = document.getElementById("matiere");
    matiereSelect.innerHTML = '<option value="">-- Sélectionnez une matière --</option>';

    matieres
        .filter(matiere => matiere.filiere_id == filiereId)
        .forEach(matiere => {
            const option = document.createElement("option");
            option.value = matiere.id;
            option.textContent = matiere.name;
            matiereSelect.appendChild(option);
        });
}

function submitForm() {
    const filiereId = document.getElementById("filiere").value;
    const matiereId = document.getElementById("matiere").value;
    const hours = document.getElementById("hours").value;

    if (!filiereId || !matiereId || !hours) {
        alert("Veuillez remplir tous les champs.");
        return;
    }

    fetch('attribution_handler.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
            professorId: currentProfessorId,
            filiereId,
            matiereId,
            hours
        })
    })
    .then(res => res.json())
    .then(result => {
        if (result.success) {
            alert(result.message);
            closeModal();
        } else {
            alert("Erreur : " + result.message);
        }
    })
    .catch(error => alert("Erreur serveur : " + error.message));
}

// === RECHERCHE ===
function filterProfessors() {
    const searchValue = document.getElementById("searchInput").value.toLowerCase();
    const cards = document.querySelectorAll(".prof-card");

    cards.forEach(card => {
        const name = card.dataset.name;
        card.style.display = name.includes(searchValue) ? "flex" : "none";
    });
}

// === AJOUT PROFESSEUR ===
function openAddModal() {
    document.getElementById("addModal").style.display = "flex";
}

function closeAddModal() {
    document.getElementById("addForm").reset();
    document.getElementById("addModal").style.display = "none";
}

function submitAdd() {
    const name = document.getElementById('addName').value;
    const email = document.getElementById('addEmail').value;
    const password = document.getElementById('addPassword').value;

    fetch('professor_handler.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
            action: 'add',
            name: name,
            email: email,
            password: password
        })
    })
    .then(response => response.json())
    .then(data => {
        Swal.fire({
            icon: data.success ? 'success' : 'error',
            title: data.success ? 'Succès' : 'Erreur',
            text: data.message,
            timer: 2000,
            showConfirmButton: false
        });

        if (data.success) {
            setTimeout(() => location.reload(), 2000);
        }
    });
}


// === MODIFICATION PROFESSEUR ===
function openEditModal(id, name, email) {
    document.getElementById("editId").value = id;
    document.getElementById("editName").value = name;
    document.getElementById("editEmail").value = email;
    document.getElementById("editModal").style.display = "flex";
}

function closeEditModal() {
    document.getElementById("editForm").reset();
    document.getElementById("editModal").style.display = "none";
}

function submitEdit() {
    const id = document.getElementById('editId').value;
    const name = document.getElementById('editName').value;
    const email = document.getElementById('editEmail').value;

    fetch('professor_handler.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
            action: 'update',
            id: id,
            name: name,
            email: email
        })
    })
    .then(response => response.json())
    .then(data => {
        Swal.fire({
            icon: data.success ? 'success' : 'error',
            title: data.success ? 'Modifié' : 'Erreur',
            text: data.message,
            timer: 2000,
            showConfirmButton: false
        });

        if (data.success) {
            setTimeout(() => location.reload(), 2000);
        }
    });
}



    </script>
</body>
</html>