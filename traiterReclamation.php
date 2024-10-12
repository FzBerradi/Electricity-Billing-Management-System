<?php
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["reclamation_id"]) && isset($_POST["statut"]) && isset($_POST["reponse"])) {
    // Inclure le fichier de connexion à la base de données
    include 'db_connect.php';
    $message ='';
    // Récupérer les données du formulaire
    $reclamation_id = $_POST["reclamation_id"];
    $statut = $_POST["statut"];
    $reponse = $_POST["reponse"];

    // Mettre à jour le statut et la réponse de la réclamation dans la base de données
    $sql = "UPDATE reclamation SET STATUS = ?, REPONSE = ? WHERE ID_RECLAMATION = ?";
    $stmt = $con->prepare($sql);
    
    // Vérifier si la préparation de la requête a réussi
    if ($stmt === false) {
        die("Erreur de préparation de la requête : " . $con->error);
    }
    $stmt->bind_param("ssi", $statut, $reponse, $reclamation_id);
    if ($stmt->execute()) {
        // Réussite de la mise à jour
        $message = "Mise à jour de la réclamation effectuée avec succès";
    } else {
        // En cas d'erreur lors de l'exécution de la requête
        $message = "Erreur lors de la mise à jour de la réclamation : " . $stmt->error;
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Claim Treatment</title>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
            margin: 0;
            padding: 0;
        }
        h1 {
            text-align: center;
            margin-bottom: 20px;
        }
        table {
            width: calc(98% - 270px);
            margin-left: 270px; 
            table-layout: auto;
            border-collapse: collapse;
            margin-top: 50px;
            margin-right: 260px; 
        }
        th,
        td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #c4103d;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        tr:hover {
            background-color: #ddd;
        }

        a {
            color: #c4103d;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }

        .button-container {
            text-align: right;
            padding: 15px;
            margin-top: 20px;
        }

        .button-container button {
            background-color: #c4103d;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .button-container button:hover {
            background-color: #a3082f;
        }

        #reponseForm {
            display: none; 
            margin: 0 auto;
            max-width: 500px; 
            padding: 20px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-top: 50px;
        }

        #reponseForm textarea {
            width: 100%;
            margin-bottom: 10px;
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
            box-sizing: border-box;
            resize: vertical; 
        }

        #reponseForm button {
            background-color: #c4103d;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        #reponseForm button:hover {
            background-color: #a3082f;
        }
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: 250px;
            height: 100vh;
            background-color: #333;
            padding-top: 50px; 
            transition: left 0.3s ease;
            background-color: #c4103d; 
            transition: left 0.3s ease, transform 0.3s ease;
            transform: perspective(800px); 
        }

        /* Styles des options du menu */
        .sidebar ul {
            list-style-type: none;
            padding: 0;
        }

        .sidebar ul li {
            padding: 15px;
            border-bottom: 1px solid #555;
            transition: background-color 0.3s ease;
            transform-style: preserve-3d;
        }

        .sidebar ul li:last-child {
            border-bottom: none;
        }

        .sidebar ul li a {
            color: #fff;
            text-decoration: none;
            transition: color 0.3s ease;
        }

       
        .sidebar ul li:hover {
            background-color: #555;
        }

        .sidebar ul li a:hover {
            color: #c4103d;
        }

        .sidebar.open {
            left: -250px;
        }
         /* Styles for Dark/Light Mode toggle */
    .dark-light-mode-container {
        text-align: right;
        padding: 15px;
    }

    #dark-light-mode {
        color: #fff;
        text-decoration: none;
        transition: color 0.3s ease;
    }

    #dark-light-mode:hover {
        color: #c4103d;
    }

    #dark-light-mode i {
        margin-right: 10px;
    }

    .dark-mode {
        background-color: #222;
        color: #fff;
    }
    /* Style pour les boutons de statut */
select[name='statut'] {
    background-color: transparent;
    color: #c4103d;
    border: 1px solid #c4103d;
    padding: 8px 15px;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s, color 0.3s;
}

select[name='statut']:hover {
    background-color: #c4103d;
    color: #fff;
}

/* Style pour le champ de réponse */
input[name='reponse'] {
    background-color: transparent;
    color: #333;
    border: 1px solid #ccc;
    padding: 8px 15px;
    border-radius: 5px;
}

/* Style pour le bouton de notification */
button[type='submit'] {
    background-color: transparent;
    color: #c4103d;
    border: 1px solid #c4103d;
    padding: 8px 15px;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s, color 0.3s;
}

button[type='submit']:hover {
    background-color: #c4103d;
    color: #fff;
}
.success-message {
    position: fixed;
    top: 80px;
    left: 50%;
    transform: translate(-50%, -50%);
    background-color: #dff0d8;
    color: #3c763d;
    border: 1px solid #d6e9c6;
    border-radius: 5px;
    padding: 15px;
    z-index: 9999; 
}
    </style>
</head>
<body>
<?php if (!empty($message)): ?>
        <div class="success-message"><?php echo $message; ?></div>
    <?php endif; ?>
    <h1><i class='bx bxs-bell' ></i>Claim</h1> 
    <header class="header">
        <div class="sidebar">
            <nav class="main-menu">
                <div class="settings"></div>
                <div class="scrollbar" id="style-1">
                    <ul>  
                        <li>                                 
                            <a href="login.php">
                            <i class='bx bxs-log-in' ></i>
                                <span class="nav-text">Login</span>
                            </a>
                        </li>  
                        <li>                                 
                            <a href="fournisseurDashboard.php">
                            <i class='bx bxs-dashboard' ></i>
                                <span class="nav-text">Dashboard</span>
                            </a>
                        </li>  
                        <li>                                 
                            <a href="#">
                            <i class='bx bxs-contact'></i>
                                <span class="nav-text">Contact</span>
                            </a>
                        </li>
                        <li>                                 
                            <a href="statistic.php">
                            <i class='bx bx-bar-chart-alt'></i>
                                <span class="nav-text">Statistical</span>
                            </a>
                        </li>
                        <li>
                            <a href="#" id="dark-light-mode" onclick="toggleDarkLight()">
                                <i class='bx bxs-moon'></i>
                                <span class="nav-text">Dark/Light Mode</span>
                            </a>
                        </li>
                        <li>
                            <a href="logout.php">
                                <i class='bx bxs-log-out' ></i>
                                <span class="nav-text">Logout</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>
        </div>
    </header>
        <table>
    <thead>
        <tr>
            <th>ID Reclamation</th>
            <th>CIN Client</th>
            <th>Type</th>
            <th>Description</th>
            <th>Status</th>
            <th>Response</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php
        include 'db_connect.php';
        $sql = "SELECT ID_RECLAMATION, CIN_CL, TYPE, DESCRIPTION, STATUS, REPONSE FROM reclamation";
        $result = $con->query($sql);
        // Vérifier s'il y a des résultats
        if ($result->num_rows > 0) {
            // Parcourir chaque ligne de résultats
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row["ID_RECLAMATION"] . "</td>";
                echo "<td>" . $row["CIN_CL"] . "</td>";
                echo "<td>" . $row["TYPE"] . "</td>";
                echo "<td>" . $row["DESCRIPTION"] . "</td>";
                echo "<td>";
                // Formulaire pour changer le statut
                echo "<form action='traiterReclamation.php' method='post'>";
                echo "<input type='hidden' name='reclamation_id' value='" . $row["ID_RECLAMATION"] . "'>";
                echo "<select name='statut'>";
                echo "<option value='On hold'>On hold</option>";
                echo "<option value='In progress'>In progress</option>";
                echo "<option value='Resolved'>Resolved</option>";
                echo "</select>";
                echo "</td>";
                echo "<td>";
                echo "<input type='text' name='reponse' placeholder='Response...'>";
                echo "</td>";
                echo "<td>";
                // Bouton pour notifier
                echo "<button type='submit' >Notify</button>";
                echo "</form>";
                echo "</td>";
                echo "</tr>";
            }
        } else {
            // Afficher un message si aucune réclamation n'est trouvée
            echo "<tr><td colspan='6'>No reclamation found</td></tr>";
        }
        // Fermer la connexion à la base de données
        $con->close();
        ?>
    </tbody>
</table>
    <!-- Boutons en bas de la page -->
    <div class="button-container">
        <button onclick="window.history.back();">Back</button>
    </div>
  
    <!-- Script pour le mode sombre/lumineux -->
    <script>
        function toggleDarkLight() {
            var body = document.body;
            body.classList.toggle("dark-mode");
        }
      
        function toggleReponse() {
            var reponseForm = document.getElementById("reponseForm");
            if (reponseForm.style.display === "block") {
                reponseForm.style.display = "none";
            } else {
                reponseForm.style.display = "block";
            }
        }
    </script>
</body>
</html>
