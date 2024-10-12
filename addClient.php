<?php
// Inclure le fichier de configuration de la base de données
include_once 'db_connect.php';

// Vérifier si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer les données du formulaire et les stocker dans des variables
    $cin = $_POST['cin'];
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $address = $_POST['address'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $tel = $_POST['tel'];

    // Vérifier si le client existe déjà en fonction de son CIN ou de son adresse e-mail
    $check_query = "SELECT * FROM client WHERE CIN_CL = '$cin' OR EMAIL_CL = '$email'";
    $check_result = mysqli_query($con, $check_query);
    if (mysqli_num_rows($check_result) > 0) {
        // Le client existe déjà, afficher un message d'erreur
        echo "<script>alert('Client already exists.');</script>";
    } else {
        // Le client n'existe pas encore, insérer les données dans la base de données
        // Préparer la requête SQL d'insertion
        $insert_query = "INSERT INTO client (CIN_CL, NOM_CL, PRENOM_CL, ADDRESS, EMAIL_CL, TEL_CL, PASSWORD) 
                         VALUES ('$cin', '$nom', '$prenom', '$address', '$email', '$tel', '$password')";

        // Exécuter la requête et vérifier si l'insertion a réussi
        if (mysqli_query($con, $insert_query)) {
            echo "<script>alert('Customer added successfully.');</script>";
        } else {
            echo "<script>alert('Error: " . $insert_query . "<br>" . mysqli_error($con) . "');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <title>Add Client</title>
    <style>
body {
    font-family: Arial, sans-serif;
    background-color: rgb(228, 226, 226);
    margin: 0;
    padding: 0;
}

h2 {
    text-align: center;
    margin-top: 7px;
}

form {
    position: fixed;
    top: 52%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: 50%;
    padding: 20px;
    border-radius: 5px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    background-color: #fff;
}

label {
    display: block;
    margin-bottom: 5px;
    font-weight: bold; 
    font-size: 16px; 
    font-family: Arial, sans-serif; 
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


.sidebar ul li:hover {
    background-color: #555;
}

.sidebar ul li a:hover {
    color: #c4103d;
}

.sidebar.open {
    left: -250px;
}

input[type="text"],
input[type="email"],
input[type="tel"],
input[type="password"] {
    width: 97%;
    padding: 10px;
    margin-bottom: 0px;
    border: 1px solid #ccc;
    border-radius: 5px;
    font-size: 14px; 
    font-family: Arial, sans-serif; 
}

input[type="submit"] {
    background-color: #c4103d;
    color: #fff;
    border: none;
    padding: 10px 20px;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s;
}

input[type="submit"]:hover {
    background-color: #a3082f;
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
    margin-left: 10px;
}

/* Aligner les boutons à droite */
.button-container {
    text-align: right;
    margin-top: 20px;
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
    <h2><i class='bx bx-user-plus'></i>Add Client</h2>  
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
                            <a href="fournisseurDashboard.php">
                            <i class='bx bxs-dashboard' ></i>
                                <span class="nav-text">Dashboard</span>
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
    <div class="container">  
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <label for="cin"><i class='bx bx-id-card'></i>CIN Client:</label>
        <input type="text" id="cin" name="cin" required><br><br>

        <label for="nom"><i class='bx bx-user-circle' ></i>Last Name:</label>
        <input type="text" id="nom" name="nom" required><br><br>

        <label for="prenom"><i class='bx bx-user-circle' ></i>First Name:</label>
        <input type="text" id="prenom" name="prenom" required><br><br>

        <label for="address"><i class='bx bx-globe'></i>Address:</label>
        <input type="text" id="address" name="address" required><br><br>

        <label for="email"><i class='bx bxs-envelope'></i>Email:</label>
        <input type="email" id="email" name="email" required><br><br>
        <label for="email"><i class='bx bxs-lock-alt' ></i>Password:</label>
        <input type="password" id="password" name="password" required><br><br>

        <label for="tel"><i class='bx bxs-contact'></i>Phone number:</label>
        <input type="tel" id="tel" name="tel" required><br><br>
        <input type="submit" name="submit" value="Add">
        <button onclick="window.history.back();" >Back</button>
    </form>
    
    </div>
    <script>
        function toggleDarkLight() {
            var body = document.body;
            body.classList.toggle("dark-mode");
        }
    </script>
</body>
</html>
