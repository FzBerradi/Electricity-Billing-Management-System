<?php
session_start();
// Vérifier si les variables de session sont définies
if(isset($_SESSION['user_name']) && isset($_SESSION['user_email'])) {
    // Afficher le nom et prénom de l'utilisateur
    $userName = $_SESSION['user_name'];
    // Afficher l'email de l'utilisateur
    $userEmail = $_SESSION['user_email'];
} else {
    
    $userName = "Nom Prénom inconnu";
    $userEmail = "Email inconnu";
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Customer</title>
    <link rel="stylesheet" href="styleDashCL.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <style>
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
<body id="body">
<header class="header">
        <!-- Sidebar -->
        <h2>Dashboard Client</h2>
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
                            <a href="notification.php">
                            <i class='bx bxs-bell-ring'></i>
                                <span class="nav-text">Notification</span>
                            </a>
                        </li>
                        <li>
                            <a href="profileCl.php"><i class='bx bxs-user-circle'></i>
                                <span class="nav-text">Profile</span>
                            </a>
                        </li>
                        <li>
                            <a href="editProfileCl.php">
                            <i class='bx bxs-cog'></i>
                                <span class="nav-text">Edit Profile</span>
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
            <input type="text" placeholder="Rechercher...">
            <button type="submit"><i class='bx bx-search-alt'></i></button>
        </form>
    </header>

    <div class="background"></div>
    <div class="container">
        <div class="content">
            <div class="text-sci">
                <h2>Welcome!<br><span>At Electricity Tool, your destination to manage your electricity consumption and bills online.</span></h2>
                <div class="social-icons">
                    <a href="#"><i class='bx bxl-linkedin'></i></a>
                    <a href="#"><i class='bx bxl-facebook'></i></a>
                    <a href="#"><i class='bx bxl-instagram'></i></a>
                    <a href="#"><i class='bx bxl-gmail'></i></a>
                </div>
            </div>
        </div>
        <div class="dashboard">
            <div class="buttons">
                <button class="button" onclick="window.location.href='consommation.php'">Monthly Consumption</button>
                <button class="button" onclick="window.location.href='consulter.php'">Invoice Consultations</button>
                <button class="button" onclick="window.location.href='reclamation.php'">Reclamations</button>
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
