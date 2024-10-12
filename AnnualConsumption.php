<?php
// Inclusion du fichier de connexion à la base de données
require_once 'db_connect.php';
$message = '';
$showTable = false;
// Traitement du formulaire d'upload
if (isset($_POST['submit'])) {
    if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = 'uploads/';
        $uploadFile = $uploadDir . basename($_FILES['file']['name']);
        $fileExtension = pathinfo($uploadFile, PATHINFO_EXTENSION);
        if ($fileExtension !== 'txt') {
            echo "Seuls les fichiers .txt sont autorisés.";
            exit();
        }
        if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadFile)) {
            $fileContent = file($uploadFile, FILE_IGNORE_NEW_LINES);

            foreach ($fileContent as $line) {
                $data = explode(',', $line);
                $cinCl = trim($data[0]);
                // Vérifier d'abord si les valeurs existent dans la table client avant d'effectuer l'insertion dans la table consommation_annuelle
                $sqlCheckClient = "SELECT CIN_CL FROM client WHERE CIN_CL = '{$cinCl}'";
                $resultCheckClient = $con->query($sqlCheckClient);
                if ($resultCheckClient->num_rows > 0) {
                    $consommation = trim($data[1]);
                    $annee = trim($data[2]);
                    $dateSaisie = trim($data[3]);
                    // Insérer les données dans la table consommation_annuelle
                    $sqlInsertConsommation = "INSERT INTO consommation_annuelle (CIN_CL, CONSOMMATION, ANNEE, DATE_SAISIE) VALUES ('{$cinCl}', '{$consommation}', '{$annee}', '{$dateSaisie}')";
                    if ($con->query($sqlInsertConsommation) === TRUE) {
                        $message .= "Data inserted successfully into the annual consumption table for CIN_CL {$cinCl}.<br>";
                    } else {
                        $message .= "Error when inserting data into the annual consumption table for CIN_CL {$cinCl}: " . $con->error . "<br>";
                    }
                } 
            }
            $showTable = true;
        } else {
            $message = "Error downloading file.";
        }
    } else {
        $message = "No files have been uploaded.";
    }
}
if (isset($_POST['update_cons_act'])) {
    $cinCl = $_POST['cin_cl'];
    $newConsAct = $_POST['new_cons_act'];
    // Récupérer la consommation actuelle du mois précédent pour ce client
    $mois_precedent = 12; // Mois précédent
    $sql_select_consommation_precedente = "SELECT CONS_ACT FROM facture WHERE MOIS = '$mois_precedent' AND CIN_CL = '$cinCl'";
    $result_consommation_precedente = $con->query($sql_select_consommation_precedente);

    if ($result_consommation_precedente && $result_consommation_precedente->num_rows > 0) {
        $row_consommation_precedente = $result_consommation_precedente->fetch_assoc();
        $cons_anc = $row_consommation_precedente['CONS_ACT'];

        // Mettre à jour la consommation actuelle dans la table facture
        $sqlUpdateConsAct = "UPDATE facture SET CONS_ACT = '{$newConsAct}' WHERE CIN_CL = '{$cinCl}' AND MOIS = '12'";
        if ($con->query($sqlUpdateConsAct) === TRUE) {
            $message .= "Current consumption successfully updated for CIN_CL {$cinCl}.<br>";

            // Calculer la différence entre la nouvelle consommation actuelle et la consommation précédente
            $difference = $newConsAct - $cons_anc;
            // Mettre à jour la colonne consommation avec la différence calculée
            $sqlUpdateDifference = "UPDATE facture SET consommation = '{$difference}' WHERE CIN_CL = '{$cinCl}' AND MOIS = '12'";
            if ($con->query($sqlUpdateDifference) === TRUE) {
                $message .= "Consumption difference successfully updated for CIN_CL {$cinCl}.<br>";

                // Calculer le prix HT en fonction de la différence de consommation
                if ($difference > 0 && $difference <= 100) {
                    $prix_ht = $difference * 0.8;
                } elseif ($difference > 100 && $difference <= 200) {
                    $prix_ht = $difference * 0.9;
                } else {
                    $prix_ht = $difference * 1.01;
                }

                // Calculer le prix TTC en ajoutant la TVA
                $prix_ttc = $prix_ht * (1 + 0.14); // Ajout de la TVA

                // Mettre à jour les colonnes prix_ht et prix_ttc avec les nouveaux calculs
                $sqlUpdatePrices = "UPDATE facture SET prix_ht = '{$prix_ht}', prix_ttc = '{$prix_ttc}' WHERE CIN_CL = '{$cinCl}' AND MOIS = '12'";
                if ($con->query($sqlUpdatePrices) === TRUE) {
                    $message .= "Price HT and TTC successfully updated for CIN_CL {$cinCl}.<br>";
                } else {
                    $message .= "Error updating prices for CIN_CL {$cinCl}: " . $con->error . "<br>";
                }
            } else {
                $message .= "Error updating consumption difference for CIN_CL {$cinCl}: " . $con->error . "<br>";
            }
        } else {
            $message .= "Error updating current consumption for CIN_CL {$cinCl}: " . $con->error . "<br>";
        }
    } else {
        $message .= "No previous consumption found for CIN_CL {$cinCl}.<br>";
    }
}


$difference = 0;

// Affichage des données de la table consommation_annuelle et facture
if ($showTable) {
    $sqlFetchData = "SELECT fa.CIN_CL, fa.CONS_ACT, co.CONSOMMATION 
                    FROM facture fa
                    INNER JOIN consommation_annuelle co ON fa.CIN_CL = co.CIN_CL
                    WHERE fa.MOIS = '12'";
    $result = $con->query($sqlFetchData);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
<title>Upload  Consumption Annuel</title>
<style>
    body {
    font-family: Arial, sans-serif;
    background-color: #f4f4f4;
}

.container {
    max-width: 600px;
    margin: 50px auto;
    padding: 20px;
    background-color: #fff;
    border-radius: 5px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    position: absolute;
    top: 80px;
    left: 50%;
    transform: translateX(-50%);
}

.form-group {
    margin-bottom: 20px;
}

label {
    display: block;
    margin-bottom: 5px;
}

input[type="file"] {
    width: 100%;
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 3px;
}

button {
    padding: 10px 20px;
    background-color: #007bff;
    color: #fff;
    border: none;
    border-radius: 3px;
    cursor: pointer;
}

button:hover {
    background-color: #0056b3;
}
/* Styles de la barre latérale */
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

/* Effet de survol sur les options du menu */
.sidebar ul li:hover {
    background-color: #555;
}

.sidebar ul li a:hover {
    color: #c4103d;
}
.sidebar.open {
    left: -250px;
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
    table {
    width: 70%;
    margin: auto;
    border-collapse: collapse;
    position: absolute;
    top:70%;
    bottom: 20px;
    left: 60%;
    transform: translateX(-50%);
}
th, td {
    padding: 10px;
    text-align: center;
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

/* Styles CSS pour le bouton "Modifier" */
button[type="submit"] {
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
</style>
</head>
<body>
<?php if (!empty($message)) { ?>
        <div class="error-message"><?php echo $message; ?></div>
    <?php } ?>
<h1>Réclamation📤</h1>
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
                            <a href="fournisseurDashboard.php">
                            <i class='bx bxs-dashboard' ></i>
                                <span class="nav-text">Dashboard</span>
                            </a>
                        </li>  
                        <li>
                            <a href="treatConsommation.php">
                            <i class='bx bx-error-alt'></i>
                                <span class="nav-text">Anomalie</span>
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
                            <i class='bx bxs-log-out'></i>
                                <span class="nav-text">Logout</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>
        </div>
<div class="container">
    <h2><i class='bx bx-cloud-upload' ></i>Annual Consumption Upload</h2>
    <form action="AnnualConsumption.php" method="post" enctype="multipart/form-data">
        <div class="form-group">
            <label for="file">Select file :</label>
            <input type="file" name="file" id="file" accept=".txt">
        </div>
        <div class="form-group">
            <button type="submit" name="submit">Upload</button>
        </div>
    </form>
</div>
<?php if ($showTable) { ?>
        <table>
    <thead>
        <tr>
            <th>CIN_CL</th>
            <th>Current Consumption (kWh)</th>
            <th>Annual Consumption (kWh)</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($row = $result->fetch_assoc()) {
            $difference = $row['CONS_ACT'] - $row['CONSOMMATION'];
            ?>
            <tr>
                <td><?php echo $row['CIN_CL']; ?></td>
                <td><?php echo $row['CONS_ACT']; ?></td>
                <td><?php echo $row['CONSOMMATION']; ?></td>
                <!-- Colonne pour le bouton de modification -->
                <td>
                    <?php if ($difference > 50) { ?>
                        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                            <input type="hidden" name="cin_cl" value="<?php echo $row['CIN_CL']; ?>">
                            <input type="hidden" name="cons_act" value="<?php echo $row['CONS_ACT']; ?>">
                            <input type="number" name="new_cons_act" value="<?php echo $row['CONS_ACT']; ?>" required>
                            <button type="submit" name="update_cons_act">Modify</button>
                        </form>
                    <?php } else { ?>
                        <p>Difference less than or equal to 50 kWh!No Problem.</p>
                    <?php } ?>
                </td>
            </tr>
        <?php } ?>
    </tbody>
</table>
    <?php } ?>
<script>
    function toggleDarkLight() {
            var body = document.body;
            body.classList.toggle("dark-mode");
        }
</script>
</body>
</html>
