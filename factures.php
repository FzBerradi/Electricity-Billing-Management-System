<?php
    // Inclure le fichier de connexion à la base de données
    include_once("db_connect.php");

    // Initialisation du message d'erreur
    $error_message = "";

    // Vérifier si le formulaire a été soumis pour mettre à jour le statut de la facture
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["save_status"])) {
        if (isset($_POST["facture_id"]) && isset($_POST["statut"]) && isset($_POST["annee"]) && isset($_POST["mois"])) {
            // Récupérer les valeurs du formulaire
            $facture_id = $_POST["facture_id"];
            $statut = $_POST["statut"];
            $annee = $_POST["annee"];
            $mois = $_POST["mois"];

            // Préparer la requête SQL pour mettre à jour le statut de la facture
            $update_query = "UPDATE FACTURE SET STATUS = '$statut' WHERE ID_FACTURE = $facture_id AND ANNEE = $annee AND MOIS = $mois";

            // Exécuter la requête SQL
            if (mysqli_query($con, $update_query)) {
                $error_message ="Bill status has been successfully updated.";
            } else {
                $error_message = "Error updating bill status : " . mysqli_error($con);
            }
        } 
    }

    // Vérifier si le formulaire a été soumis pour supprimer une facture
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["delete_facture"])) {
        // Vérifier si le champ facture_id est défini
        if (isset($_POST["facture_id"])) {
            // Récupérer l'ID de la facture à supprimer
            $facture_id = $_POST["facture_id"];

            // Préparer et exécuter la requête SQL pour supprimer la facture
            $delete_query = "DELETE FROM FACTURE WHERE ID_FACTURE = $facture_id";

            if (mysqli_query($con, $delete_query)) {
                echo "<script>alert('La facture a été supprimée avec succès.')</script>";
            } else {
                $error_message = "Erreur lors de la suppression de la facture : " . mysqli_error($con);
            }
        } else {
            $error_message = "Erreur : Le champ facture_id n'est pas défini.";
        }
    }

    // Requête SQL pour récupérer les données de la table FACTURE
    $query = "SELECT ID_FACTURE, CIN_CL, PHOTO_COMPTEUR, CONSOMMATION, STATUS, ANNEE, MOIS FROM FACTURE";

    // Exécuter la requête SQL
    $result = mysqli_query($con, $query);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <title>Status Bills(paid/unpaid)</title>
    <link rel="stylesheet" href="styles.css"> <!-- Lien vers votre fichier CSS personnalisé -->
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

    /* Sidebar */
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
        transform: perspective(800px); /* Initial 3D transform */
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

    /* Effet de déplacement latéral du menu */
    .sidebar.open {
        left: -250px;
    }

    /* Table */
    table {
        width: auto;
        table-layout: auto;
        border-collapse: collapse;
        margin-top: 50px;
        margin-left: 270px; /* Ajout de marge à gauche pour laisser de l'espace à la sidebar */
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
        display: flex; /* Aligner les éléments horizontalement */
        align-items: center; /* Aligner les éléments verticalement */
    }

    /* Style du select */
    td select {
        padding: 8px;
        border-radius: 5px;
    }

    /* Style du bouton "Enregistrer" */
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
                    </ul>
                </div>
            </nav>
        </div>
    
    </header>
    <form action="factures.php" method="post" class="search-bar">
        <input type="text" placeholder="Search....">
        <button type="submit"><i class='bx bx-search-alt'></i></button>
    </form>
    <table>
        <thead>
            <tr>
                <th>ID Facture</th>
                <th>CIN Client</th>
                <th>Photo Counter</th>
                <th>Cunsomption</th>
                <th>Month</th>
                <th>Year</th>
                <th>Status</th>
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
        // Récupérer le chemin de la photo du compteur
        $photoCompteurPath = $row['PHOTO_COMPTEUR'];
        
        // Vérifier si le chemin de la photo est défini
        if (!empty($photoCompteurPath)) {
            // Afficher le chemin de la photo
            echo $photoCompteurPath;
    ?>
    <form action="show_picture_compteur.php" method="post">
        <!-- Champ caché pour envoyer le chemin de la photo -->
        <input type="hidden" name="photo_compteur_path" value="<?php echo $photoCompteurPath; ?>">
        <!-- Bouton pour afficher la photo -->
        <button type="submit" >Show</button>
    </form>
    <?php 
        } else {
            // Si le chemin de la photo n'est pas défini, afficher un message d'erreur ou une indication
            echo "No photo available";
        }
    ?>
</td>
            <td><?php echo $row['CONSOMMATION']; ?></td>
            <td><?php echo $row['MOIS']; ?></td>
            <td><?php echo $row['ANNEE']; ?></td>
            <td>
                <form action="factures.php" method="post">
                    <input type="hidden" name="facture_id" value="<?php echo $row['ID_FACTURE']; ?>">
                    <input type="hidden" name="annee" value="<?php echo $row['ANNEE']; ?>">
                    <input type="hidden" name="mois" value="<?php echo $row['MOIS']; ?>">
                    <select name="statut">
                        <option value="paid" <?php echo ($row['STATUS'] == 'paid') ? 'selected' : ''; ?>>Paid</option>
                        <option value="unpaid" <?php echo ($row['STATUS'] == 'Unpaid') ? 'selected' : ''; ?>>Unpaid</option>
                    </select>
                    <button type="submit" name="save_status">Save</button>
                </form>
            </td>
            <td>
               <!-- <form action="delete_facture.php" method="post">
                    <input type="hidden" name="facture_id" value="<//?php echo $row['ID_FACTURE']; ?>">
                    <button type="submit" name="delete_facture">Delete</button>
                </form>-->
                <form action="generate_pdf.php" method="GET">
                        <input type="hidden" name="id_facture" value="<?php echo $row['ID_FACTURE']; ?>">
                        <input type="hidden" name="download_pdf" value="true">
                        <button type="submit" >Download</button>
    </form>
            </td>
        </tr>
    <?php } ?>
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
