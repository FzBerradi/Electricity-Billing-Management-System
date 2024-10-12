<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <title>Statistical</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            grid-gap: 20px;
        }

        .stats-card {
            background-color: #f9f9f9;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            padding: 20px;
            transition: transform 0.3s;
            text-align: center;
        }

        .stats-card:hover {
            transform: translateY(-5px);
        }

        .stats-card h2 {
            color: #333;
            margin-bottom: 10px;
        }

        .stats-card p {
            font-size: 24px;
            color: #555;
            margin: 0;
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
                            <a href="treatConsommation.php">
                            <i class='bx bx-error-alt'></i>
                                <span class="nav-text">Anomalie</span>
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
    <div class="stats-grid">
        <?php
        include_once("db_connect.php");

        $result = $con->query("SELECT COUNT(*) AS nb_factures_non_payees FROM facture WHERE STATUS = 'unpaid'");
        $row = $result->fetch_assoc();
        $nb_factures_non_payees = $row['nb_factures_non_payees'];

        $result = $con->query("SELECT COUNT(*) AS nb_consommations FROM Facture");
        $row = $result->fetch_assoc();
        $nb_consommations = $row['nb_consommations'];

        $result = $con->query("SELECT COUNT(*) AS nb_reclamations FROM reclamation");
        $row = $result->fetch_assoc();
        $nb_reclamations = $row['nb_reclamations'];

        $result = $con->query("SELECT COUNT(*) AS nb_clients FROM client");
        $row = $result->fetch_assoc();
        $nb_clients = $row['nb_clients'];
        $con->close();
        echo "<div class='stats-card'><h2>Unpaid Bills</h2><p>$nb_factures_non_payees</p></div>";
        echo "<div class='stats-card'><h2>Number of Consumptions</h2><p>$nb_consommations</p></div>";
        echo "<div class='stats-card'><h2>Number of reclamations</h2><p>$nb_reclamations</p></div>";
        echo "<div class='stats-card'><h2>Number of clients</h2><p>$nb_clients</p></div>";
        ?>
    </div>
</div>
<script>function toggleDarkLight() {
            var body = document.body;
            body.classList.toggle("dark-mode");
        }</script>
</body>
</html>
