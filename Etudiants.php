<?php
require 'db.php';

// Récupération des étudiants
$sql = "SELECT nom_complet, matricule, filiere FROM etudiants";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$etudiants = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Récupération de toutes les filières (pour les selects)
$sqlFilieres = "SELECT id, name FROM filieres ORDER BY name ASC";
$stmtFilieres = $pdo->prepare($sqlFilieres);
$stmtFilieres->execute();
$filieres = $stmtFilieres->fetchAll(PDO::FETCH_ASSOC);
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
.etudiants-container {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
  gap: 20px;
  margin-top: 30px;
}

.card-etudiant {
  background-color: #ffffff;
  border-radius: 20px;
  box-shadow: 0 4px 10px rgba(0, 0, 0, 0.08);
  padding: 20px;
  transition: transform 0.2s ease, box-shadow 0.2s ease;
  display: flex;
  flex-direction: column; /* empile verticalement */
  align-items: flex-start; /* aligne à gauche */
  gap: 15px;
}

.card-etudiant:hover {
  transform: translateY(-5px);
  box-shadow: 0 8px 16px rgba(0, 0, 0, 0.12);
}

.card-icon {
  font-size: 28px;
  color: #2c3e50;
  flex-shrink: 0;
}

.card-info h3 {
  font-size: 1.2rem;
  margin-bottom: 8px;
  color: #2c3e50;
}

.card-info p {
  font-size: 0.95rem;
  color: #555;
  margin: 3px 0;
}

.card-info i {
  margin-right: 8px;
  color: #3498db;
}

.card-actions {
  margin-top: 10px;
  display: flex;
  gap: 10px;
}

.card-actions button {
  cursor: pointer;
  padding: 6px 12px;
  border: none;
  border-radius: 5px;
  font-size: 14px;
  display: flex;
  align-items: center;
  gap: 6px;
  transition: background-color 0.3s ease;
}

.btn-modifier {
  background-color: #4a90e2;
  color: white;
}

.btn-modifier:hover {
  background-color: #357ABD;
}

.btn-supprimer {
  background-color: #E94B3C;
  color: white;
}

.btn-supprimer:hover {
  background-color: #C4372F;
}



/* Modal */
.modal {
  display: none;
  position: fixed;
  top: 0; left: 0;
  width: 100%; height: 100%;
  background: rgba(0,0,0,0.4);
  justify-content: center; 
  align-items: center;
  z-index: 1000;
}

.modal-content {
  background: white;
  padding: 30px;
  border-radius: 20px;
  width: 400px;
  max-width: 90%;
  box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
  animation: slideDown 0.3s ease;
}

@keyframes slideDown {
  from {
    transform: translateY(-30px);
    opacity: 0;
  }
  to {
    transform: translateY(0);
    opacity: 1;
  }
}

.modal-content form {
  display: flex;
  flex-direction: column;
  gap: 15px;
}

.modal-content input[type="text"],
.modal-content select[name="filiere"] {
  width: 100%;
  padding: 12px;
  margin-bottom: 15px;
  border: 1px solid #ccc;
  border-radius: 12px;
  font-size: 16px;
  box-sizing: border-box;
  background-color: #fff;
  color: #333;
  appearance: none;
  -webkit-appearance: none;
  -moz-appearance: none;
  background-image: url("data:image/svg+xml;charset=US-ASCII,%3Csvg%20width%3D%2210%22%20height%3D%226%22%20viewBox%3D%220%200%2010%206%22%20fill%3D%22none%22%20xmlns%3D%22http://www.w3.org/2000/svg%22%3E%3Cpath%20d%3D%22M1%201L5%205L9%201%22%20stroke%3D%22%23666%22%20stroke-width%3D%222%22%20stroke-linecap%3D%22round%22%20stroke-linejoin%3D%22round%22/%3E%3C/svg%3E");
  background-repeat: no-repeat;
  background-position: right 12px center;
  background-size: 16px 16px;
  transition: border-color 0.2s ease-in-out;
}

.modal-content select[name="filiere"]:focus,
.modal-content input[type="text"]:focus {
  border-color: #4a90e2;
  outline: none;
  box-shadow: 0 0 0 3px rgba(74, 144, 226, 0.2);
}

/* Boutons */
.modal-content button {
  padding: 10px 20px;
  margin-right: 10px;
  border: none;
  border-radius: 10px;
  cursor: pointer;
  font-weight: bold;
  font-size: 14px;
}

.modal-content button[type="submit"] {
  background-color: #007bff;
  color: white;
  transition: background-color 0.2s ease;
}
.modal-content button[type="submit"]:hover {
  background-color: #0056b3;
}

.modal-content button[type="button"] {
  background-color: #ccc;
  color: #333;
}
.modal-content button[type="button"]:hover {
  background-color: #999;
}

/* Search input */
#search-input {
  flex: 1;
  min-width: 260px;
  padding: 10px;
  border-radius: 10px;
  border: 1px solid #ccc;
  margin-right: 20px;
}

/* Filtre filière */
#filter-filiere {
  padding: 8px 12px;
  font-size: 1rem;
  border-radius: 20px;
  border: 1px solid #ccc;
  background-color: white;
  cursor: pointer;
  transition: border-color 0.3s ease, box-shadow 0.3s ease;
  min-width: 180px;
}

#filter-filiere:hover {
  border-color: #2980b9;
}

#filter-filiere:focus {
  border-color: #3498db;
  box-shadow: 0 0 5px rgba(52, 152, 219, 0.5);
  outline: none;
}

#filter-filiere option {
  padding: 8px 12px;
  font-size: 1rem;
  cursor: pointer;
}

/* Action buttons */
.action-buttons button {
  margin: 5px;
  padding: 10px 15px;
  border: none;
  background-color: #3498db;
  color: white;
  border-radius: 10px;
  cursor: pointer;
  transition: 0.3s;
}

.action-buttons button:hover {
  background-color: #2980b9;
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



   <div class="etudiant-actions">
  <input type="text" id="search-input" placeholder="🔍 Rechercher par nom, matricule ou filière..." onkeyup="filtrerEtudiants()">

  <!-- Filtre filière -->
  <select id="filter-filiere" onchange="filtrerParFiliere()">
    <option value="">Toutes les filières</option>
    <!-- Options générées en PHP -->
    <?php
      // Récupérer toutes les filières uniques de la liste $etudiants
      $filieres_options = array_unique(array_map(fn($e) => $e['filiere'], $etudiants));
      sort($filieres_options);
      foreach ($filieres_options as $filiere) {
        echo '<option value="' . htmlspecialchars($filiere) . '">' . htmlspecialchars($filiere) . '</option>';
      }
    ?>
  </select>

  <div class="action-buttons">
    <button class="btn-add" onclick="ouvrirModal()">➕ Ajouter un étudiant</button>
    <button class="btn-export" onclick="exportToPDF()">📄 Export PDF</button>
    <button class="btn-export" onclick="exportToExcel()">📊 Export Excel</button>
    <button class="btn-admin" onclick="checkFilierePassword()"><i class="fas fa-user-shield"></i> Admin CRUD</button>
  </div>
</div>


<!-- Contenu principal -->
<div class="etudiants-container">
  <?php foreach ($etudiants as $etudiant): ?>
    <div class="card-etudiant"
         data-nom="<?= htmlspecialchars($etudiant['nom_complet']) ?>"
         data-matricule="<?= htmlspecialchars($etudiant['matricule']) ?>"
         data-filiere="<?= htmlspecialchars($etudiant['filiere']) ?>">
         
      <div class="card-icon"><i class="fas fa-user"></i></div>
      <div class="card-info">
        <h3><?= htmlspecialchars($etudiant['nom_complet']) ?></h3>
        <p><i class="fas fa-id-badge"></i> <?= htmlspecialchars($etudiant['matricule']) ?></p>
        <p><i class="fas fa-graduation-cap"></i> <?= htmlspecialchars($etudiant['filiere']) ?></p>
      </div>

      <div class="card-actions admin-actions" style="display: none;">
  <button class="btn-modifier" onclick="modifierEtudiant('<?= htmlspecialchars($etudiant['matricule']) ?>')">
    <i class="fas fa-edit"></i> Modifier
  </button>
  <button class="btn-supprimer" onclick="supprimerEtudiant('<?= htmlspecialchars($etudiant['matricule']) ?>')">
    <i class="fas fa-trash-alt"></i> Supprimer
  </button>
</div>

    </div>
  <?php endforeach; ?>
</div>


      
<!-- Modal Ajouter Étudiant -->
<!-- Modal Ajouter Étudiant -->
<div class="modal" id="ajouterModal">
  <div class="modal-content">
    <h2>Ajouter un étudiant</h2>
    <form method="POST" action="ajouter_etudiant.php">
      <input type="text" name="nom_complet" placeholder="Nom complet" required>
      <input type="text" name="matricule" placeholder="Matricule" required>

      <select name="filiere" required>
        <option value="">-- Sélectionner une filière --</option>
        <?php foreach ($filieres as $filiere): ?>
          <option value="<?= htmlspecialchars($filiere['name']) ?>">
            <?= htmlspecialchars($filiere['name']) ?>
          </option>
        <?php endforeach; ?>
      </select>

      <button type="submit">Ajouter</button>
      <button type="button" onclick="fermerModal()">Annuler</button>
    </form>
  </div>
</div>


<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.25/jspdf.plugin.autotable.min.js"></script>

<script>
function ouvrirModal() {
  document.getElementById('ajouterModal').style.display = 'flex';
}

function fermerModal() {
  document.getElementById('ajouterModal').style.display = 'none';
}

function filtrerEtudiants() {
  const val = document.getElementById('search-input').value.toLowerCase().trim();
  const filiereFilter = document.getElementById('filter-filiere').value.toLowerCase().trim();

  const cards = document.querySelectorAll('.card-etudiant');
  cards.forEach(card => {
    const nom = card.dataset.nom.toLowerCase();
    const matricule = card.dataset.matricule.toLowerCase();
    const filiere = card.dataset.filiere.toLowerCase();

    const textMatch = 
      nom.includes(val) || 
      matricule.includes(val) || 
      filiere.includes(val);

    const filiereMatch = 
      filiereFilter === "" || 
      filiere === filiereFilter;

    card.style.display = (textMatch && filiereMatch) ? 'block' : 'none';
  });
}

function filtrerParFiliere() {
  filtrerEtudiants();
}


function exportToPDF() {
  const { jsPDF } = window.jspdf;
  const doc = new jsPDF();

  // En-têtes du tableau
  const headers = [['Nom complet', 'Matricule', 'Filière']];

  // Récupération des données depuis les cards
  const data = [];
  document.querySelectorAll('.card-etudiant').forEach(card => {
    // Tu peux utiliser dataset pour récupérer proprement les infos
    const nom = card.dataset.nom;
    const matricule = card.dataset.matricule;
    const filiere = card.dataset.filiere;
    data.push([nom, matricule, filiere]);
  });

  // Ajout d'un titre centré en haut
  doc.setFontSize(18);
  doc.text("Liste des étudiants", doc.internal.pageSize.getWidth() / 2, 15, { align: 'center' });

  // Création du tableau avec styles
  doc.autoTable({
    startY: 25,
    head: headers,
    body: data,
    styles: { fontSize: 12 },
    headStyles: { fillColor: [41, 128, 185], textColor: 255 }, // bleu foncé avec texte blanc
    alternateRowStyles: { fillColor: [245, 245, 245] }, // gris clair alterné
    margin: { left: 14, right: 14 },
  });

  // Sauvegarde
  doc.save("etudiants.pdf");
}


function exportToExcel() {
  const data = [['Nom', 'Matricule', 'Filière']];
  document.querySelectorAll('.card-etudiant').forEach(card => {
    const infos = card.innerText.trim().split('\n');
    const ligne = infos.map(i => i.split(':')[1]?.trim() || '');
    ligne[0] = infos[0]; // nom
    data.push(ligne);
  });
  const wb = XLSX.utils.book_new();
  const ws = XLSX.utils.aoa_to_sheet(data);
  XLSX.utils.book_append_sheet(wb, ws, "Etudiants");
  XLSX.writeFile(wb, "etudiants.xlsx");
}




function ouvrirModal() {
  document.getElementById('ajouterModal').style.display = 'flex';
}

function fermerModal() {
  document.getElementById('ajouterModal').style.display = 'none';
}

// AJOUT Étudiant via AJAX
document.querySelector('#ajouterModal form').addEventListener('submit', function(e) {
  e.preventDefault();

  const formData = new FormData(this);
  formData.append('action', 'ajouter');

  fetch('etudiants_handler.php', {
    method: 'POST',
    body: formData
  })
  .then(r => r.json())
  .then(data => {
    if (data.success) {
      Swal.fire('Succès', data.message, 'success').then(() => location.reload());
    } else {
      Swal.fire('Erreur', data.message, 'error');
    }
  })
  .catch(err => {
    Swal.fire('Erreur', 'Une erreur est survenue.', 'error');
    console.error(err);
  });
});


// MODAL MODIFIER
function modifierEtudiant(matricule) {
  const card = document.querySelector(`.card-etudiant[data-matricule="${matricule}"]`);
  const nom = card.dataset.nom;
  const filiere = card.dataset.filiere;

  Swal.fire({
    title: 'Modifier étudiant',
    html:
      `<input id="swal-nom" class="swal2-input" placeholder="Nom complet" value="${nom}">
       <input id="swal-matricule" class="swal2-input" value="${matricule}" readonly>
       <input id="swal-filiere" class="swal2-input" placeholder="Filière" value="${filiere}">`,
    showCancelButton: true,
    confirmButtonText: 'Modifier',
    cancelButtonText: 'Annuler',
    preConfirm: () => {
      return {
        nom_complet: document.getElementById('swal-nom').value,
        matricule: document.getElementById('swal-matricule').value,
        filiere: document.getElementById('swal-filiere').value
      }
    }
  }).then(result => {
    if (result.isConfirmed) {
      const formData = new FormData();
      formData.append('action', 'modifier');
      formData.append('nom_complet', result.value.nom_complet);
      formData.append('matricule', result.value.matricule);
      formData.append('filiere', result.value.filiere);

      fetch('etudiants_handler.php', {
        method: 'POST',
        body: formData
      })
      .then(r => r.json())
      .then(data => {
        if (data.success) {
          Swal.fire('Succès', data.message, 'success').then(() => location.reload());
        } else {
          Swal.fire('Erreur', data.message, 'error');
        }
      })
      .catch(err => {
        Swal.fire('Erreur', 'Une erreur est survenue.', 'error');
        console.error(err);
      });
    }
  });
}


// SUPPRESSION Étudiant
function supprimerEtudiant(matricule) {
  Swal.fire({
    title: 'Êtes-vous sûr ?',
    text: "Cette action est irréversible !",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonText: 'Oui, supprimer',
    cancelButtonText: 'Annuler'
  }).then(result => {
    if (result.isConfirmed) {
      const formData = new FormData();
      formData.append('action', 'supprimer');
      formData.append('matricule', matricule);

      fetch('etudiants_handler.php', {
        method: 'POST',
        body: formData
      })
      .then(r => r.json())
      .then(data => {
        if (data.success) {
          Swal.fire('Supprimé', data.message, 'success').then(() => location.reload());
        } else {
          Swal.fire('Erreur', data.message, 'error');
        }
      })
      .catch(err => {
        Swal.fire('Erreur', 'Une erreur est survenue.', 'error');
        console.error(err);
      });
    }
  });
}

function checkFilierePassword() {
  Swal.fire({
    title: 'Authentification Admin',
    input: 'password',
    inputLabel: 'Entrez le mot de passe admin',
    inputPlaceholder: 'Mot de passe',
    inputAttributes: {
      maxlength: 20,
      autocapitalize: 'off',
      autocorrect: 'off'
    },
    showCancelButton: true,
    confirmButtonText: 'Valider',
    cancelButtonText: 'Annuler'
  }).then(result => {
    if (result.isConfirmed) {
      const password = result.value;
      if (password === 'admin123') { // 🔐 Tu peux changer ce mot de passe ici
        const actions = document.querySelectorAll('.admin-actions');
        actions.forEach(div => div.style.display = 'flex');

        Swal.fire({
          icon: 'success',
          title: 'Mode CRUD activé',
          text: 'Les options admin sont maintenant visibles.'
        });
      } else {
        Swal.fire({
          icon: 'error',
          title: 'Accès refusé',
          text: 'Mot de passe incorrect.'
        });
      }
    }
  });
}
</script>

</script>



</body>
</html>
