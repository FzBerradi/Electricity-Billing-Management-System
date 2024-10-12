<?php
// Inclure le fichier de connexion à la base de données
include_once("db_connect.php");
// Initialisation du message d'erreur
$error_message = "";
// Vérifier si le formulaire a été soumis pour mettre à jour la consommation actuelle
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["update_cons_act"])) {
    // Vérifier si les champs requis sont définis
    if (isset($_POST["facture_id"]) && isset($_POST["annee"]) && isset($_POST["mois"]) && isset($_POST["nouvelle_cons_act"])) {
        // Récupérer les valeurs du formulaire
        $facture_id = $_POST["facture_id"];
        $annee = $_POST["annee"];
        $mois = $_POST["mois"];
        $nouvelle_cons_act = $_POST["nouvelle_cons_act"];
        $query_facture_info = "SELECT CONS_ACT, ANOMALIE FROM FACTURE WHERE ID_FACTURE = $facture_id AND ANNEE = $annee AND MOIS = $mois";
        $result_facture_info = mysqli_query($con, $query_facture_info);
        if ($result_facture_info) {
            $row_facture_info = mysqli_fetch_assoc($result_facture_info);
            $cons_act_old = $row_facture_info['CONS_ACT'];
            $anomalie = $row_facture_info['ANOMALIE'];
            // Calculer la différence entre la nouvelle consommation actuelle et la consommation actuelle précédente
            $difference_consommation = $nouvelle_cons_act - $cons_act_old;
            // Mettre à jour CONS_ACT avec la nouvelle valeur
            $update_query = "UPDATE FACTURE SET CONS_ACT = '$nouvelle_cons_act'";
            // Mettre à jour CONSOMMATION avec la différence calculée
            $update_query .= ", CONSOMMATION = CONSOMMATION + $difference_consommation";
            // Mettre à jour ANOMALIE à 0 si la consommation est normale
            if ($anomalie == 1 && $nouvelle_cons_act >= 0 && $nouvelle_cons_act <= 1000000) {
                $update_query .= ", ANOMALIE = '0'";
            }
            // Mettre à jour les prix HT et TTC en fonction de la nouvelle consommation
            $update_query .= ", PRIX_HT = CASE 
                                    WHEN CONSOMMATION > 0 AND CONSOMMATION <= 100 THEN CONSOMMATION * 0.8
                                    WHEN CONSOMMATION > 100 AND CONSOMMATION <= 200 THEN CONSOMMATION * 0.9
                                    ELSE CONSOMMATION * 1.01
                                    END";
            $update_query .= ", PRIX_TTC = PRIX_HT * 1.14"; // Ajout de la TVA
            // Ajouter les conditions WHERE pour identifier la facture spécifique
            $update_query .= " WHERE ID_FACTURE = $facture_id AND ANNEE = $annee AND MOIS = $mois";
            // Exécuter la requête SQL pour mettre à jour la consommation actuelle et ANOMALIE
            if (mysqli_query($con, $update_query)) {
                $error_message = "The current consumption of the bill has been successfully updated.";
            } else {
                $error_message = "Error updating current consumption: " . mysqli_error($con);
            }
            // Libérer le résultat de la requête
            mysqli_free_result($result_facture_info);
        } else {
            $error_message = "Error executing query : " . mysqli_error($con);
        }
    } else {
        $error_message = "Error: Not all required fields are defined.";
    }
}
// Requête SQL pour récupérer les factures avec anomalie dans la consommation
$query = "SELECT ID_FACTURE, CIN_CL, PHOTO_COMPTEUR, CONSOMMATION, CONS_ACT, ANNEE, MOIS FROM FACTURE WHERE ANOMALIE = '1'";
// Exécuter la requête SQL
$result = mysqli_query($con, $query);

// Vérifier si la requête s'est exécutée avec succès
if (!$result) {
    die("Error executing query: " . mysqli_error($con));
}

// Si aucune anomalie n'a été trouvée
if (mysqli_num_rows($result) == 0) {
    $error_message = "There remain no anomalies in consumption.";
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <title>Treat Cunsomption</title>
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

    table {
        width: auto;
        table-layout: auto;
        border-collapse: collapse;
        margin-top: 50px;
        margin-left: 270px; 
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

    td form {
        display: flex; 
        align-items: center; 
    }
    td select {
        padding: 8px;
        border-radius: 5px;
    }

    td button[type="submit"] {
    background-color: transparent;
    color: #c4103d;
    border: 1px solid #c4103d;
    padding: 8px 15px;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s, color 0.3s;
    }
    td button[type='submit']:hover {
    background-color: #c4103d;
    color: #fff;
}
    .error-message {
            background-color: #ccffcc;
            color: #008000;
            padding: 15px;
            margin: 10px auto;
            border: 1px solid #66cc66;
            border-radius: 5px;
            width: fit-content;
            text-align: center;
            font-size: 16px;
            font-weight: bold;
        }
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
<?php if (!empty($error_message)) { ?>
        <div class="error-message"><?php echo $error_message; ?></div>
    <?php } ?>
    <h1><i class='bx bx-bulb'></i>Bills</h1> 
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
                            <a href="#" id="dark-light-mode" onclick="toggleDarkLight()">
                                <i class='bx bxs-moon'></i>
                                <span class="nav-text">Dark/Light Mode</span>
                            </a>
                        </li>
                        <li>                                 
                            <a href="statistic.php">
                            <i class='bx bx-bar-chart-alt'></i>
                                <span class="nav-text">Statistical</span>
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
    
    </header>
    <form action="treatConsommation.php" method="post" class="search-bar">
        <input type="text" placeholder="Search....">
        <button type="submit"><i class='bx bx-search-alt'></i></button>
    </form>
    <table>
    <thead>
            <tr>
                <th>ID Facture</th>
                <th>CIN Client</th>
                <th>Photo Counter</th>
                <th>Consumption</th>
                <th>Current Consumption</th>
                <th>Month</th>
                <th>Year</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
    <?php while ($row = mysqli_fetch_assoc($result)) { ?>
        <tr>
            <td><?php echo $row['ID_FACTURE']; ?></td>
            <td><?php echo $row['CIN_CL']; ?></td>

            <td>
                <?php 
                    $photoCompteurPath = $row['PHOTO_COMPTEUR'];
                    // Vérifier si le chemin de la photo est défini
                    if (!empty($photoCompteurPath)) {
                        // Afficher le chemin de la photo
                        echo $photoCompteurPath;
                ?>
                <form action="show_picture_compteur.php" method="post">
                    <input type="hidden" name="photo_compteur_path" value="<?php echo $photoCompteurPath; ?>">
                    <button type="submit" >Show</button>
                </form>
                <?php 
                    } else {
                        // Si le chemin de la photo n'est pas défini, afficher un message d'erreur ou une indication
                        echo "No photos available";
                    }
                ?>
            </td>
            <td><?php echo $row['CONSOMMATION']; ?></td>
            <td>
                <form action="treatConsommation.php" method="post">
                    <input type="hidden" name="facture_id" value="<?php echo $row['ID_FACTURE']; ?>">
                    <input type="hidden" name="annee" value="<?php echo $row['ANNEE']; ?>">
                    <input type="hidden" name="mois" value="<?php echo $row['MOIS']; ?>">
                    <input type="number" name="nouvelle_cons_act" value="<?php echo $row['CONS_ACT']; ?>">
                    <button type="submit" name="update_cons_act">Edit</button>
                </form>
            </td>
            <td><?php echo $row['MOIS']; ?></td>
            <td><?php echo $row['ANNEE']; ?></td>
            <td>
            </td>
        </tr>
    <?php } ?>
</tbody>
<?php
// Libérer les résultats
mysqli_free_result($result);
?>
    </table>
    <script>
         function toggleDarkLight() {
            var body = document.body;
            body.classList.toggle("dark-mode");
        }
    </script>
</body>
</html>
