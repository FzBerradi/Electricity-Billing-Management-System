<?php
session_start();
include_once("db_connect.php");
if (isset($_GET['error_message'])) {
    $error_message = $_GET['error_message'];
    echo "<div class='alert alert-danger' role='alert'>$error_message</div>";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <title>Customer Invoices</title>
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
        background-color: #c4103d;
        color: #fff;
        border: none;
        padding: 8px 15px; /* Ajustez le rembourrage selon vos besoins */
        border-radius: 5px;
        cursor: pointer;
        transition: background-color 0.3s;
    }

    /* Style du bouton "Enregistrer" au survol */
    td button[type="submit"]:hover {
        background-color: #a3082f;
    }

    .button-container {
        text-align: right;
        padding: 15px;
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
    .alert {
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    padding: 15px;
    border-radius: 5px;
    background-color: #f8d7da;
    color: #721c24;
    border: 1px solid #f5c6cb;
    z-index: 9999; 
}
    </style>
</head>
<body>
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
                            <a href="dashboardCl.php">
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
                            <a href="#">
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
    <form action="#" class="search-bar">
        <input type="text" placeholder="Search....">
        <button type="submit"><i class='bx bx-search-alt'></i></button>
    </form>
    <table>
        <thead>
            <tr>
                <th>ID Facture</th>
                <th>CIN Client</th>
                <th>Consumption (KWH)</th>
                <th>TTC Price</th>
                <th>Status</th>
                <th>Year</th>
                <th>Month</th>
                <th>Download Bills</th>
            </tr>
        </thead>
        <tbody>
        <?php

// Inclure la connexion à la base de données
     include_once("db_connect.php");
// Vérifier si l'identifiant du client est stocké dans la session
     if (isset($_SESSION['cin'])) {
    // Récupérer l'identifiant du client à partir de la session
    $cin = $_SESSION['cin'];
    // Requête SQL pour sélectionner les factures du client connecté
    $sql = "SELECT * FROM facture WHERE CIN_CL = '$cin'";
    $result = mysqli_query($con, $sql);
    // Vérifier si des factures sont trouvées
    if ($result && mysqli_num_rows($result) > 0) {
        // Afficher les factures dans le tableau
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>";
            echo "<td>" . $row['ID_FACTURE'] . "</td>";
            echo "<td>" . $row['CIN_CL'] . "</td>";
            echo "<td>" . $row['CONSOMMATION'] . "</td>";
            echo "<td>" . $row['PRIX_TTC'] . "</td>";
            echo "<td>" . $row['STATUS'] . "</td>";
            echo "<td>" . $row['ANNEE'] . "</td>";
            echo "<td>" . $row['MOIS'] . "</td>";
            echo "<td><a href='generate_pdf.php?id_facture=" . $row['ID_FACTURE'] . "&download_pdf=true'>Download</a></td>";
            echo "</tr>";
        }
    } else {
        // Si aucune facture n'est trouvée, afficher un message
        echo "<tr><td colspan='8'>No bills found.</td></tr>";
    }
} else {
    // Si l'identifiant du client n'est pas stocké dans la session, afficher un message d'erreur
    echo "Error: Client ID is not available.";
}

// Vérifier si le formulaire de modification du statut a été soumis
if (isset($_POST['save_status'])) {
    // Récupérer l'ID de la facture à partir du formulaire
    $factureId = $_POST['facture_id'];
    // Récupérer le nouveau statut à partir du formulaire
    $newStatus = $_POST['statut'];
    // Mettre à jour le statut de la facture dans la base de données
    $updateStatusQuery = "UPDATE facture SET STATUS = '$newStatus' WHERE ID_FACTURE = $factureId";
    if (mysqli_query($con, $updateStatusQuery)) {
        // Succès de la mise à jour du statut, maintenant mettre à jour le statut visible par le client
        $newVisibleStatus = $newStatus;
        $updateVisibleStatusQuery = "UPDATE facture SET VISIBLE_STATUS = '$newVisibleStatus' WHERE ID_FACTURE = $factureId";

        if (!mysqli_query($con, $updateVisibleStatusQuery)) {
            echo "Error updating client  status: " . mysqli_error($con);
        }
    } else {
        // Erreur lors de la mise à jour du statut
        echo "Error updating status : " . mysqli_error($con);
    }
}
?>
        </tbody>
    </table>
    <br><br><br><br><br><br><br>
    <div class="button-container">
        <button onclick="window.history.back();" >Back</button>
    </div>
    <script>
        function toggleDarkLight() {
            var body = document.body;
            body.classList.toggle("dark-mode");
        }
        // Récupère le message d'erreur s'il est présent dans l'URL
    const urlParams = new URLSearchParams(window.location.search);
    const error_message = urlParams.get('error_message');

    // Affiche le message d'erreur dans une div avec la classe alert s'il existe
    if (error_message) {
        const errorMessageDiv = document.createElement('div');
        errorMessageDiv.classList.add('alert');
        errorMessageDiv.textContent = error_message;
        document.body.appendChild(errorMessageDiv);
    }
    </script>
</body>
</html>
