<?php
include_once 'db_connect.php';
// Fonction pour récupérer les informations du client en fonction du CIN
function getClientByCIN($con, $cin)
{
    $sql = "SELECT * FROM client WHERE CIN_CL='$cin'";
    $result = mysqli_query($con, $sql);
    if ($result && mysqli_num_rows($result) > 0) {
        return mysqli_fetch_assoc($result);
    } else {
        return false;
    }
}
// Vérifier si le formulaire de recherche a été soumis
if (isset($_POST['search'])) {
    $search_cin = $_POST['search_cin'];
    $client = getClientByCIN($con, $search_cin);
    if ($client) {
        // Si le client est trouvé, mettre à jour les variables avec ses informations
        $cin = $client['CIN_CL'];
        $nom = $client['NOM_CL'];
        $prenom = $client['PRENOM_CL'];
        $address = $client['ADDRESS'];
        $email = $client['EMAIL_CL']; // Assurez-vous que cette ligne est présente
        $tel = $client['TEL_CL'];
    } else {
        // Si le client n'est pas trouvé, afficher un message d'erreur
        echo "<script>alert('No Client found.');</script>";
    }
}

// Vérifier si le formulaire de modification a été soumis
if (isset($_POST['update'])) {
    // Récupérer les nouvelles données du formulaire
    $cin = $_POST['cin'];
    $new_nom = $_POST['new_nom'];
    $new_prenom = $_POST['new_prenom'];
    $new_address = $_POST['new_address'];
    $new_email = $_POST['new_email'];
    $new_tel = $_POST['new_tel'];

    // Mettre à jour les informations du client dans la base de données
    $update_sql = "UPDATE client SET NOM_CL='$new_nom', PRENOM_CL='$new_prenom', ADDRESS='$new_address', EMAIL_CL='$new_email', TEL_CL='$new_tel' WHERE CIN_CL='$cin'";
    if (mysqli_query($con, $update_sql)) {
        echo "<script>alert('Information successfully updated.');</script>";
        // Rafraîchir la page pour afficher les nouvelles valeurs automatiquement
        echo "<script>window.location.reload();</script>";
    } else {
        echo "<script>alert('Update error: " . mysqli_error($con) . "');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <title>Modify Client</title>
    <style>
       body {
    font-family: Arial, sans-serif;
    background-color: rgb(228, 226, 226);
    margin: 0;
    padding: 0;
    display: flex;
    justify-content: center; 
    align-items: center; 
    min-height: 100vh; 
}

.table-container {
    width: 95%;
    background-color: #fff; 
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    overflow: hidden; 
    width: 95%;
    margin: 20px auto; 
}

table {
    width: 100%;
    border-collapse: collapse;
}

th, td {
    padding: 15px;
    border-bottom: 1px solid #ddd;
    text-align: left;
}

th {
    background-color: rgba(196, 16, 61, 0.7);
    color-interpolation-filters: #fff;
    text-align: center;
}

.search-bar {
    position: absolute; 
    top: 20px;
    right: 20px;
}

input[type="text"],
input[type="submit"] {
    padding: 8px;
    border: 1px solid #ccc;
    border-radius: 4px;
}

input[type="submit"] {
    background-color: #c4103d;
    color: #fff;
    cursor: pointer;
}

input[type="submit"]:hover {
    background-color: #a3082f;
}
        .search-bar {
            position: fixed;
            top: 10px;
            right: 10px;
        }
        input[type="text"],
        input[type="email"],
        input[type="tel"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
            box-sizing: border-box;
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
<header class="header">
        <div class="sidebar">
            <nav class="main-menu">
                <div class="settings"></div>
                <div class="scrollbar" id="style-1">
                    <ul>  
                        <li>                                 
                            <a href="#">
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
    <div class="main-content">
        <div class="search-bar">
            <!-- Barre de recherche -->
            <form action="modifyClient.php" method="post">
                <input type="text" id="search_cin" name="search_cin" placeholder="search by cin...">
                <input type="submit" name="search" value="Search">
                <i class='bx bx-search' style="margin-right: 5px; color: #c4103d;"></i>
            </form>
        </div>
    <?php if(isset($cin)): ?>
        <form action="" method="post">
            <input type="hidden" name="cin" value="<?php echo $cin; ?>">
            <label for="new_nom">New First Name:</label>
<input type="text" id="new_nom" name="new_nom" value="<?php echo isset($nom) ? $nom : ''; ?>" required><br>
<label for="new_prenom">New Last Name:</label>
<input type="text" id="new_prenom" name="new_prenom" value="<?php echo isset($prenom) ? $prenom : ''; ?>" required><br>
<label for="new_address">New Address:</label>
<input type="text" id="new_address" name="new_address" value="<?php echo isset($address) ? $address : ''; ?>" required><br>
<label for="new_tel">New Phone:</label>
<input type="text" id="new_tel" name="new_tel" value="<?php echo isset($tel) ? $tel : ''; ?>" required><br>
<label for="new_email">New Email:</label>
<input type="email" id="new_email" name="new_email" value="<?php echo isset($email) ? $email : ''; ?>" required><br>
            <input type="submit" name="update" value="Update">
        </form>
    <?php endif; ?>
        <!-- Afficher la table des clients -->
        <center><h3><i class='bx bx-edit' ></i>Modify Customer</h3></center>
            <table>
                <tr>
                    <th>CIN</th>
                    <th>Last Name</th>
                    <th>First Name</th>
                    <th>Address</th>
                    <th>Email</th>
                    <th>Phone number</th>
                </tr>
                <?php
                $sql = "SELECT * FROM client";
                $result = mysqli_query($con, $sql);
                if ($result && mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<tr>";
                        echo "<td>" . $row['CIN_CL'] . "</td>";
                        echo "<td>" . $row['NOM_CL'] . "</td>";
                        echo "<td>" . $row['PRENOM_CL'] . "</td>";
                        echo "<td>" . $row['ADDRESS'] . "</td>";
                        echo "<td>" . $row['EMAIL_CL'] . "</td>";
                        echo "<td>" . $row['TEL_CL'] . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='6'>No clients found.</td></tr>";
                }
                ?>
            </table>
        </div>
        <script>
            function toggleDarkLight() {
            var body = document.body;
            body.classList.toggle("dark-mode");
        }
    
        </script>
</body>
</html>
