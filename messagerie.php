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
        <!-- Tu peux ajouter ici ton contenu personnalisé -->
      <div class="messaging-container">
    <h2><i class="fas fa-paper-plane"></i> Envoyer un message</h2>
    <form id="messagingForm">
        <div class="form-group">
            <label for="recipient">Destinataire</label>
            <select id="recipient" required>
                <option value="">-- Sélectionnez un destinataire --</option>
                <option value="all">Tous les utilisateurs</option>
                <?php foreach ($professors as $professor): ?>
                    <option value="<?= htmlspecialchars($professor['id']) ?>">
                        <?= htmlspecialchars($professor['name']) ?> (<?= htmlspecialchars($professor['email']) ?>)
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="message">Message</label>
            <textarea id="message" rows="5" placeholder="Écris ton message ici..." required></textarea>
        </div>

        <button type="button" class="submit-btn" onclick="sendMessage()">
            <i class="fas fa-paper-plane"></i> Envoyer
        </button>
    </form>

    <div id="feedback" class="feedback"></div>
</div>


    <script>
        function sendMessage() {
            const recipient = document.getElementById("recipient").value;
            const message = document.getElementById("message").value;

            if (!recipient || !message) {
                showFeedback("Veuillez remplir tous les champs.", "error");
                return;
            }

            // Préparation des données à envoyer
            const data = {
                recipient: recipient,
                message: message
            };

            // Envoi de la requête AJAX
            fetch('messaging_handler.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(data)
            })
            .then(response => response.json())
            .then(result => {
                if (result.success) {
                    showFeedback(result.message, "success");
                    document.getElementById("messagingForm").reset();
                } else {
                    showFeedback("Erreur : " + result.message, "error");
                }
            })
            .catch(error => {
                showFeedback("Une erreur est survenue : " + error.message, "error");
            });
        }

        function showFeedback(message, type) {
            const feedback = document.getElementById("feedback");
            feedback.style.display = "block";
            feedback.className = type === "success" ? "success-message" : "error-message";
            feedback.textContent = message;
        }
    </script>

    </div>
</body>
</html>
