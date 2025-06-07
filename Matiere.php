<?php
require 'db.php'; // Connexion à la base de données

// Récupération des matières avec leur filière associée
$sql = "
SELECT m.id, m.name AS matiere, m.filiere_id, f.name AS filiere, m.created_at
FROM matieres m
JOIN filieres f ON m.filiere_id = f.id
ORDER BY m.created_at DESC
";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$matieres = $stmt->fetchAll(PDO::FETCH_ASSOC);

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
      /* SECTION TITRE & BOUTON AJOUT */
.section-title {
  color: #2c3e50;
  margin-bottom: 20px;
}

.add-button-container {
  text-align: right;
  margin-bottom: 20px;
}

/* GRILLE DE MATIERES */
.matiere-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
  gap: 20px;
}

.matiere-card {
  background-color: white;
  border-radius: 12px;
  box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
  padding: 20px;
}

.matiere-header {
  display: flex;
  align-items: center;
  margin-bottom: 15px;
}

.matiere-icon {
  font-size: 2rem;
  color: #007bff;
  margin-right: 15px;
}

.matiere-title {
  margin: 0;
  font-size: 1.2rem;
  color: #34495e;
}

.matiere-filiere {
  color: #888;
}

.matiere-date {
  color: #666;
  font-size: 0.9rem;
}

.matiere-actions {
  margin-top: 15px;
  display: flex;
  justify-content: space-between;
}

/* BOUTONS */
.btn {
  border: none;
  padding: 8px 12px;
  border-radius: 6px;
  cursor: pointer;
  color: white;
  font-size: 0.9rem;
  display: inline-flex;
  align-items: center;
}

.btn i {
  margin-right: 6px;
}

.btn-add    { background-color: #28a745; }
.btn-admin  { background-color: #dc3545; }
.btn-edit   { background-color: #ffc107; }
.btn-delete { background-color: #dc3545; }
.btn-save   { background-color: #007bff; }
.btn-cancel { background-color: #6c757d; }

/* MODALS */
.modal {
  position: fixed;
  top: 0; left: 0;
  width: 100%; height: 100%;
  background-color: rgba(0, 0, 0, 0.5);
  display: none;
  align-items: center;
  justify-content: center;
  z-index: 999;
}

.modal-content {
  background: #fff;
  padding: 20px;
  width: 90%;
  max-width: 500px;
  border-radius: 10px;
  box-shadow: 0 5px 15px rgba(0,0,0,0.3);
  position: relative;
}

.modal-actions {
  margin-top: 15px;
  text-align: right;
}

.form-group {
  margin-bottom: 15px;
}

input[type="text"],
select {
  width: 100%;
  padding: 8px 10px;
  border: 1px solid #ccc;
  border-radius: 6px;
  font-size: 1rem;
}

  

  .grouped-controls {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap; /* Pour responsive */
    gap: 10px;
    margin-bottom: 20px;
  }

  .search-container input {
    padding: 8px 12px;
    width: 250px;
    border: 1px solid #ccc;
    border-radius: 6px;
  }

  .buttons-container {
    display: flex;
    gap: 10px;
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
      <h2 class="section-title">Liste des Matières</h2>

<!-- Bouton Ajouter -->
<!-- Conteneur regroupé : recherche à gauche, boutons à droite -->
<div class="add-button-container grouped-controls">
  <!-- Barre de recherche à gauche -->
  <div class="search-container">
    <input type="text" id="search-matiere" placeholder="Rechercher une matière..." onkeyup="filtrerMatieres()" />
  </div>

  <!-- Boutons à droite -->
  <div class="buttons-container">
    <button class="btn btn-add" onclick="ouvrirModal('modal-ajouter')">
      <i class="fas fa-plus"></i> Ajouter une matière
    </button>

    <button class="btn btn-admin" onclick="checkFilierePassword()">
      <i class="fas fa-user-shield"></i> Admin CRUD
    </button>
  </div>
</div>



<!-- Grille de cartes -->
<div class="matiere-grid">
  <?php foreach ($matieres as $matiere): ?>
    <div class="matiere-card">
      <div class="matiere-header">
        <div class="matiere-icon">
          <i class="fas fa-book-open"></i>
        </div>
        <div class="matiere-info">
          <h3 class="matiere-title"><?= htmlspecialchars($matiere['matiere']) ?></h3>
          <small class="matiere-filiere">Filière : <?= htmlspecialchars($matiere['filiere']) ?></small>
        </div>
      </div>
      <p class="matiere-date">Créé le <?= date('d/m/Y à H:i', strtotime($matiere['created_at'])) ?></p>

      <div class="matiere-actions admin-actions" style="display:none;">
        <button 
          onclick="modifierMatiere(
            <?= $matiere['id'] ?>, 
            '<?= addslashes($matiere['matiere']) ?>', 
            <?= $matiere['filiere_id'] ?>
          )" 
          class="btn btn-edit">
          <i class="fas fa-edit"></i> Modifier
        </button>

        <button 
          onclick="supprimerMatiere(<?= $matiere['id'] ?>)" 
          class="btn btn-delete">
          <i class="fas fa-trash-alt"></i> Supprimer
        </button>
      </div>
    </div>
  <?php endforeach; ?>
</div>



<!-- Modal Ajouter une matière -->
<div id="modal-ajouter" class="modal">
  <div class="modal-content">
    <h2>Ajouter une matière</h2>
    <form id="form-ajouter" method="POST">
      <input type="hidden" name="action" value="ajouter">

      <div class="form-group">
        <label for="matiere">Nom de la matière</label>
        <input type="text" name="matiere" id="matiere" required>
      </div>

      <div class="form-group">
        <label for="filiere_id">Filière</label>
        <select name="filiere_id" id="filiere_id" required>
          <?php foreach ($filieres as $filiere): ?>
            <option value="<?= $filiere['id'] ?>"><?= htmlspecialchars($filiere['name']) ?></option>
          <?php endforeach; ?>
        </select>
      </div>

      <div class="modal-actions">
        <button type="submit" class="btn btn-add">Ajouter</button>
        <button type="button" class="btn btn-cancel" onclick="fermerModal('modal-ajouter')">Annuler</button>
      </div>
    </form>
  </div>
</div>

<!-- Modal Modifier une matière -->
<div id="modal-modifier" class="modal">
  <div class="modal-content">
    <h2>Modifier la matière</h2>
    <form id="form-modifier" method="POST">
      <input type="hidden" name="action" value="modifier">
      <input type="hidden" name="id" id="edit-id">

      <div class="form-group">
        <label for="edit-matiere">Nom de la matière</label>
        <input type="text" name="matiere" id="edit-matiere" required>
      </div>

      <div class="form-group">
        <label for="edit-filiere">Filière</label>
        <select name="filiere_id" id="edit-filiere" required>
          <?php foreach ($filieres as $filiere): ?>
            <option value="<?= $filiere['id'] ?>"><?= htmlspecialchars($filiere['name']) ?></option>
          <?php endforeach; ?>
        </select>
      </div>

      <div class="modal-actions">
        <button type="submit" class="btn btn-save">Enregistrer</button>
        <button type="button" class="btn btn-cancel" onclick="fermerModal('modal-modifier')">Annuler</button>
      </div>
    </form>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
  // Ouvrir un modal
  function ouvrirModal(id) {
    document.getElementById(id).style.display = 'flex';
  }

  // Fermer un modal
  function fermerModal(id) {
    document.getElementById(id).style.display = 'none';
  }

  // Fermer modal en cliquant à l’extérieur
  document.querySelectorAll('.modal').forEach(modal => {
    modal.addEventListener('click', function(e) {
      if (e.target === modal) {
        modal.style.display = 'none';
      }
    });
  });

  // Remplir et afficher le modal de modification
  function modifierMatiere(id, nom, filiere_id) {
    document.getElementById('edit-id').value = id;
    document.getElementById('edit-matiere').value = nom;
    document.getElementById('edit-filiere').value = filiere_id;
    ouvrirModal('modal-modifier');
  }

  // Confirmation et suppression d’une matière
  function supprimerMatiere(id) {
    Swal.fire({
      title: 'Êtes-vous sûr ?',
      text: "Cette action est irréversible.",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#dc3545',
      cancelButtonColor: '#6c757d',
      confirmButtonText: 'Oui, supprimer',
      cancelButtonText: 'Annuler'
    }).then((result) => {
      if (result.isConfirmed) {
        // Envoi du formulaire via AJAX
        const formData = new FormData();
        formData.append('action', 'supprimer');
        formData.append('id', id);

        fetch('matiereHandler.php', {
          method: 'POST',
          body: formData
        })
        .then(response => {
          if (response.ok) {
            Swal.fire('Supprimé !', 'La matière a été supprimée.', 'success')
              .then(() => location.reload());
          } else {
            Swal.fire('Erreur', 'Une erreur est survenue.', 'error');
          }
        })
        .catch(() => {
          Swal.fire('Erreur', 'Impossible de supprimer la matière.', 'error');
        });
      }
    });
  }

  // Gestion de l'ajout via AJAX
  document.getElementById('form-ajouter')?.addEventListener('submit', function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    formData.append('action', 'ajouter');

    fetch('matiereHandler.php', {
      method: 'POST',
      body: formData
    })
    .then(res => {
      if (res.ok) {
        Swal.fire('Ajoutée !', 'La matière a été ajoutée.', 'success')
          .then(() => location.reload());
      } else {
        Swal.fire('Erreur', 'Impossible d’ajouter la matière.', 'error');
      }
    });
  });

  // Gestion de la modification via AJAX
  document.getElementById('form-modifier')?.addEventListener('submit', function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    formData.append('action', 'modifier');

    fetch('matiereHandler.php', {
      method: 'POST',
      body: formData
    })
    .then(res => {
      if (res.ok) {
        Swal.fire('Modifiée !', 'La matière a été modifiée.', 'success')
          .then(() => location.reload());
      } else {
        Swal.fire('Erreur', 'Impossible de modifier la matière.', 'error');
      }
    });
  });

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
        if (password === 'admin123') { // 🔐 Change le mot de passe ici
          const actions = document.querySelectorAll('.admin-actions');
          actions.forEach(div => div.style.display = 'flex');
          Swal.fire('Mode CRUD activé', 'Les options admin sont maintenant visibles.', 'success');
        } else {
          Swal.fire('Accès refusé', 'Mot de passe incorrect.', 'error');
        }
      }
    });
  }

  function filtrerMatieres() {
  const searchValue = document.getElementById('search-matiere').value.toLowerCase();
  const cards = document.querySelectorAll('.matiere-card');

  cards.forEach(card => {
    const title = card.querySelector('.matiere-title').textContent.toLowerCase();
    if (title.includes(searchValue)) {
      card.style.display = 'block';
    } else {
      card.style.display = 'none';
    }
  });
}


  
</script>

      
</body>
</html>
