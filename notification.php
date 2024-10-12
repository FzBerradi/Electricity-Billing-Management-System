<?php
session_start(); // Démarrer la session

// Vérifier si le client est connecté
if (!isset($_SESSION['cin'])) {
    // Rediriger vers la page de connexion si le client n'est pas connecté
    header("Location: login.php");
    exit;
}

// Inclure le fichier de connexion à la base de données
include 'db_connect.php';
// Récupérer l'identifiant du client connecté depuis la session
$cin_cl_session = $_SESSION['cin'];

// Requête SQL pour récupérer les réclamations du client connecté
$sql = "SELECT ID_RECLAMATION, TYPE, DESCRIPTION, STATUS, REPONSE FROM reclamation WHERE CIN_CL = '$cin_cl_session'";
$result = $con->query($sql);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <title>Notifications of Reclamations</title>
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

       
.sidebar {
    position: fixed;
    top: 0;
    left: 0;
    width: 250px;
    height: 100vh;
    background-color: #333;
    padding-top: 50px; /* Laisser de la place pour le header */
    transition: left 0.3s ease;
    background-color: #c4103d; /* Couleur de fond */
    transition: left 0.3s ease, transform 0.3s ease; /* Ajout de l'animation 3D */
    transform: perspective(800px) ; /* Initial 3D transform */
}
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
    </style>
</head>
<body>
    <h1><i class='bx bxs-chat'></i>Claim Notifications</h1>
    <div class="sidebar">
            <nav class="main-menu">
                <div class="settings"></div>
                <div class="scrollbar" id="style-1">
                    <ul>  
                        <li>                                 
                            <a href="login.php">
                            <i class='bx bxs-log-in'></i>
                                <span class="nav-text">Login</span>
                            </a>
                        </li>   
                        <li>                                 
                            <a href="dashboardCl.php">
                            <i class='bx bxs-dashboard' ></i>
                                <span class="nav-text">Dashboard</span>
                            </a>
                        </li>  
                        <li>
                            <a href="profileCl.php">
                                <i class='bx bxs-user-circle'></i>
                                <span class="nav-text">Profile</span>
                            </a>
                        </li>
                    
</li>
<li>
    <a href="#" id="dark-light-mode" onclick="toggleDarkLight()">
        <i class='bx bxs-moon'></i>
        <span class="nav-text">Dark/Light Mode</span>
    </a>
</li>
                        <li>
                            <a href="logout.php">
                            <i class='bx bxs-log-out'></i>
                                <span class="nav-text">Logout</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>
        </div>
    <table>
        <thead>
            <tr>
                <th>ID Réclamation</th>
                <th>Type</th>
                <th>Description</th>
                <th>Status</th>
                <th>Response</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result->num_rows > 0) {
                // Parcourir chaque ligne de résultats
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row["ID_RECLAMATION"] . "</td>";
                    echo "<td>" . $row["TYPE"] . "</td>";
                    echo "<td>" . $row["DESCRIPTION"] . "</td>";
                    echo "<td>" . $row["STATUS"] . "</td>";
                    echo "<td>" . $row["REPONSE"] . "</td>";
                    echo "</tr>";
                }
            } else {
                // Afficher un message si aucune réclamation n'est trouvée
                echo "<tr><td colspan='5'>No reclamations found</td></tr>";
            }
            $con->close();
            ?>
        </tbody>
    </table>
    <script>
         function toggleDarkLight() {
            var body = document.body;
            body.classList.toggle("dark-mode");
        }
    </script>
</body>
</html>
