<?php
// Inclure le fichier de connexion à la base de données
include 'db_connect.php';
// Initialiser une variable pour stocker le message de succès
$message = '';
// Vérifier si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Vérifier si toutes les données nécessaires sont présentes
    if (isset($_POST["cin"]) && isset($_POST["type"]) && isset($_POST["description"])) {
        // Récupérer les données du formulaire
        $cin = $_POST["cin"];
        $type = $_POST["type"];
        $description = $_POST["description"];
        // Préparer et exécuter la requête SQL pour insérer les données de réclamation dans la base de données
        $sql = "INSERT INTO reclamation (CIN_CL, TYPE, DESCRIPTION, STATUS) VALUES (?, ?, ?, '')";

        if ($stmt = $con->prepare($sql)) {
            // Liaison des paramètres et exécution de la requête
            $stmt->bind_param("sss", $cin, $type, $description);
            if ($stmt->execute()) {
                $message = "Your complaint has been successfully registered.";
            } else {
                $message = "Error executing query: " . $stmt->error;
            }
            // Fermer le statement
            $stmt->close();
        } else {
            $message = "Error preparing query : " . $con->error;
        }
    } else {
        $message = "All form fields are required.";
    }
} 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/boxicons@2.1.1/css/boxicons.min.css">
    <title>Client Reclamation</title>
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

/* Styles du formulaire */
form {
    width: 50%;
    margin: 0 auto;
    padding: 20px;
    border-radius: 5px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    background-color: rgba(255, 255, 255, 0.9); /* Fond transparent */
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    animation: fadeIn 0.5s ease-in-out; /* Animation d'apparition */
}
.success-message {
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    background-color: #dff0d8;
    color: #3c763d;
    border: 1px solid #d6e9c6;
    border-radius: 5px;
    padding: 15px;
    z-index: 9999; /* Assure que le message apparaît au-dessus de tout le reste */
}
@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(-20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

label {
    display: block;
    margin-bottom: 5px;
    font-weight: bold; /* Texte en gras */
}

form label {
    display: block;
    margin-bottom: 10px;
}

form input[type="text"],
form select,
form textarea {
    width: 100%;
    padding: 10px;
    margin-bottom: 15px;
    border: 1px solid #ccc;
    border-radius: 5px;
}

input[type="submit"] {
    background-color: #c4103d;
    color: #fff;
    border: none;
    cursor: pointer;
    transition: background-color 0.3s;
}

input[type="submit"]:hover {
    background-color: #a3082f;
}
.container {
            margin-left: 250px; 
            padding: 20px;
            top: 0; 
            right: 0; 
            bottom: 0; 
            overflow-y: auto; 
            width: calc(100% - 250px); 
        }
.button-container {
    text-align: right; 
    margin-top: 20px;
}

input[type="submit"],
button {
    background-color: #c4103d;
    color: #fff;
    border: none;
    padding: 10px 20px;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s;
}

input[type="submit"]:hover,
button:hover {
    background-color: #a3082f;
}

button {
    margin-right: 10px; 
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
    transform: perspective(800px) ;
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
    <!-- Afficher le message de succès s'il existe -->
    <?php if (!empty($message)): ?>
        <div class="success-message"><?php echo $message; ?></div>
    <?php endif; ?>
    <h1>Reclamation📤</h1>
    <div class="sidebar">
            <nav class="main-menu">
                <div class="settings"></div>
                <div class="scrollbar" id="style-1">
                    <ul>   
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
    <form action="reclamation.php" method="post">
        <label for="cin">CIN  :</label><br>
        <input type="text" id="cin" name="cin" ><br>
        
        <label for="type">Type of Reclamation :</label><br>
        <select id="type" name="type" onchange="showCustomType()" required>
            <option value="Fuite externe">External leak</option>
            <option value="Fuite interne">Internal leak</option>
            <option value="Facture">Bills</option>
            <option value="Autres">Others</option>
        </select><br>
        
        <div id="customTypeInput" style="display: none;">
            <label for="customType">Type of reclamation (other) :</label><br>
            <input type="text" id="customType" name="customType"><br>
        </div>
        
        <label for="description">Description :</label><br>
        <textarea id="description" name="description" rows="4" required></textarea><br>
        <div class="button-container">
        <input type="submit" name="submit" value="Send">
                <button onclick="window.history.back();" >Back</button>
            </div>
    </form>
    <script>
        function toggleDarkLight() {
            var body = document.body;
            body.classList.toggle("dark-mode");
        }
        
        function showCustomType() {
            var typeSelect = document.getElementById("type");
            var customTypeInput = document.getElementById("customTypeInput");

            if (typeSelect.value === "Autres") {
                customTypeInput.style.display = "block";
            } else {
                customTypeInput.style.display = "none";
            }
        }
    </script>
</body>
</html>
