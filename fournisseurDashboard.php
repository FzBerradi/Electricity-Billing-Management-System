<?php
session_start();

// Vérifier si les variables de session sont définies
if(isset($_SESSION['user_email'])) {
    // Afficher le nom et prénom de l'utilisateur
    // Afficher l'email de l'utilisateur
    $userEmail = $_SESSION['user_email'];
} else {
    // Si les variables de session ne sont pas définies, afficher un message par défaut
    $userEmail = "Email inconnu";
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="styleDashCL.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>
<body id="body">
    
<header class="header">
        <!-- Sidebar -->
        <h2>Admin Dashboard</h2>
        <div class="sidebar">
            <nav class="main-menu">
                <div class="settings"></div>
                <div class="scrollbar" id="style-1">
                    <ul>  
                
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
                            <a href="factures.php">
                            <i class='bx bx-table'></i>
                                <span class="nav-text">Bills</span>
                            </a>
                        </li>
                        <li>                                 
                            <a href="AnnualConsumption.php">
                            <i class='bx bx-check-square' ></i>
                                <span class="nav-text">Check Consumption</span>
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
        <h1 class="logo"><i class='bx bxl-firebase'></i>Electricity Tool</h1>

        <form  class="search-bar active ">
        <input type="text" id="searchInput" onkeyup="filterTable()" placeholder="Search by admin...">
            <button type="submit"><i class='bx bx-search-alt'></i></button>
        </form>
    </header>
    
    <div class="background"></div>
    <div class="container">
        <div class="content">
            <div class="text-sci">
                <h2>Welcome!<br><span>To Admin Electricity Tool, your destination for managing your electricity consumption and bills online.</span></h2>
                
            </div>
        </div>
        
        <div class="dashboard">
            <div class="buttons">
                <button class="button" onclick="window.location.href='addClient.php'">Add Client</button>
                <button class="button" onclick="window.location.href='modifyClient.php'">Modify Client</button>
                <button class="button" onclick="window.location.href='traiterReclamation.php'">verify claim </button>
                <button class="button" onclick="window.location.href='treatConsommation.php'">treat consumption</button>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const sidebar = document.querySelector(".sidebar");
            const sidebarToggle = document.querySelector(".sidebar-toggle");

            sidebarToggle.addEventListener("click", function() {
                sidebar.classList.toggle("open");
            });
        });
        const searchBar = document.querySelector('.search-bar');
const toggleButton = document.querySelector('.toggle-search');

toggleButton.addEventListener('click', function() {
    searchBar.classList.toggle('active');
});
function toggleDarkLight() {
            var body = document.body;
            body.classList.toggle("dark-mode");
        }
    </script>
</body>
</html>
