<?php
session_start();
include_once("db_connect.php");
$message ="";
// Traitement du formulaire lorsqu'il est soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer les valeurs du formulaire
    $consommation = $_POST['CONSOMMATION'];
    $mois = $_POST['MOIS'];
    $photo_compteur_name = $_FILES['PHOTO_COMPTEUR']['name'];
    $photo_compteur_tmp = $_FILES['PHOTO_COMPTEUR']['tmp_name'];
    $photo_compteur_type = $_FILES['PHOTO_COMPTEUR']['type'];
    // Lire le contenu de l'image
    $photo_compteur_content = file_get_contents($photo_compteur_tmp);
    // Déplacer le fichier téléchargé vers un repertoire sur le serveur
    $upload_directory = "uploads/"; 
    $target_file = $upload_directory . basename($photo_compteur_name);
    // Déplacer le fichier vers le répertoire d'upload
    if (move_uploaded_file($photo_compteur_tmp, $target_file)) {
        $photo_compteur_path = $target_file; // Chemin de l'image sur le serveur
        // Calculer le prix en fonction de la consommation 
        if ($consommation > 0 && $consommation <= 100) {
            $prix_ht = $consommation * 0.8;
        } elseif ($consommation > 100 && $consommation <= 200) {
            $prix_ht = $consommation * 0.9;
        } else {
            $prix_ht = $consommation * 1.01;
        }
        // Calculer le prix TTC en ajoutant la TVA
        $prix_ttc = $prix_ht * (1 + 0.14); // Ajout de la TVA
        // Vérifier si la clé 'cin' est définie dans la session
        if (isset($_SESSION['cin'])) {
            // Récupérer le CIN_CL de la session
            $cin_cl_session = $_SESSION['cin'];
            // Récupérer les informations du client à partir de la base de données
            $sql_select_client = "SELECT CIN_CL, ADDRESS FROM CLIENT WHERE CIN_CL = '$cin_cl_session'";
            $result_client = $con->query($sql_select_client);
            if ($result_client && $result_client->num_rows > 0) {
                $row_client = $result_client->fetch_assoc();
                $cin = $row_client['CIN_CL'];
                $address = $row_client['ADDRESS'];
                // Vérifier s'il existe déjà une consommation pour ce mois et ce client
                $sql_check_facture = "SELECT ID_FACTURE FROM FACTURE WHERE MOIS = '$mois' AND CIN_CL = '$cin'";
                $result_check_facture = $con->query($sql_check_facture);
                if ($result_check_facture && $result_check_facture->num_rows > 0) {
                    $message = "La consommation existe déjà pour ce mois.";
                } else {
                   // Récupérer la consommation du mois précédent pour ce client
                $consommation_precedente = 0; // Initialiser la variable
          // Vérifier s'il s'agit du mois de janvier
if ($mois == 1) {
    // Si c'est janvier, CONS_ANC est égal à 0
    $cons_anc = 0;
    // Consommation actuelle est égale à la valeur saisie
    $cons_act = $consommation;
    // Calculer la consommation en soustrayant la consommation précédente de la valeur saisie
    $consommation = $cons_act - $cons_anc;
} else {
    // Sinon, récupérer la consommation actuelle du mois précédent pour ce client
    $mois_precedent = $mois - 1;
    $sql_select_consommation_precedente = "SELECT CONS_ACT FROM FACTURE WHERE MOIS = '$mois_precedent' AND CIN_CL = '$cin'";
    $result_consommation_precedente = $con->query($sql_select_consommation_precedente);
    if ($result_consommation_precedente && $result_consommation_precedente->num_rows > 0) {
        $row_consommation_precedente = $result_consommation_precedente->fetch_assoc();
        $consommation_precedente = $row_consommation_precedente['CONS_ACT'];
    }
    // Dans ce cas, CONS_ANC est égal à la consommation précédente
    $cons_anc = $consommation_precedente;
    // Consommation actuelle est égale à la valeur saisie
    $cons_act = $consommation;
    // Calculer la consommation en soustrayant la consommation précédente de la valeur saisie
    $consommation = $cons_act - $cons_anc;
}
// Vérifier s'il y a une anomalie uniquement si une consommation précédente est trouvée pour le client concerne(connecte)
if (isset($consommation_precedente)) {
    $mois_precedent = $mois - 1; // Initialiser la variable $mois_precedent
    // Vérifier si les données enregistrées précédemment appartiennent au client connecté
    $sql_check_previous_data = "SELECT CIN_CL FROM FACTURE WHERE MOIS = '$mois_precedent' AND CIN_CL = '$cin'";
    $result_check_previous_data = $con->query($sql_check_previous_data);
    if ($result_check_previous_data && $result_check_previous_data->num_rows > 0) {
        // Vérifier s'il y a une anomalie
        $anomalie = ($cons_act < 0 || $cons_act < $consommation_precedente || $cons_act > 1000000 || $consommation > 1000000) ? 1 : 0;
    } else {
        // Les données enregistrées précédemment n'appartiennent pas au client connecté,dans ce cas, ne détectez pas d'anomalie car il s'agit de données pour un autre client
        $anomalie = 0;
    }
}
        if ($anomalie == 1) {
    $message = "Anomalie détectée dans la consommation. Les données ont été enregistrées avec succès, mais veuillez vérifier les informations saisies.";
}
// Insérer les données dans la table de facturation
        $sql_insert = "INSERT INTO FACTURE (CIN_CL, ADDRESS, PHOTO_COMPTEUR, CONS_ACT, CONSOMMATION, PRIX_HT, PRIX_TTC, MOIS, ANOMALIE, CONS_ANC)
            VALUES ('$cin', '$address', '$photo_compteur_path', '$cons_act', '$consommation', '$prix_ht', '$prix_ttc', '$mois', $anomalie, $cons_anc)";
// Exécuter la requête d'insertion
        if ($con->query($sql_insert) === TRUE) {
    $message = ($anomalie == 1) ? $message : "La consommation a été enregistrée avec succès.";
    } else {
    $message = "Erreur lors de l'insertion dans la table de facturation: " . $con->error;
    }
                }
            } else {
                $message = "Aucun client trouvé avec le CIN_CL '$cin_cl_session'.";
            }
        } else {
            $message = "La clé 'cin' n'est pas définie dans la session.";
        }
    } else {
        echo "Une erreur s'est produite lors du téléchargement du fichier.";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <title>Consumption</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: rgb(228, 226, 226);
            margin: 0;
            padding: 0;
        }
        .sidebar {
            background-color: #c4103d;
            color: #fff;
            width: 250px;
            height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            padding: 20px;
            box-sizing: border-box;
        }

        .container {
            margin-left: 250px; 
            padding: 20px;
            position: fixed; 
            top: 0; 
            right: 0; 
            bottom: 0; 
            overflow-y: auto; 
            width: calc(100% - 250px); 
        }
        h2 {
            color: #333;
        }
        form {
            background-color: rgba(255, 255, 255, 0.9); 
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            animation: fadeIn 0.5s ease-in-out; 
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
            font-weight: bold; 
        }
        input[type="text"],
        input[type="number"],
        select {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }
        input[type="file"] {
            width: 100%;
            margin-bottom: 10px;
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
        .button-container {
            text-align: right;
            margin-top: 20px;
        }
        .photo-preview {
            border: 2px solid #ccc;
            padding: 10px;
            margin-bottom: 20px;
            max-width: 100%;
            max-height: 200px;
            overflow: hidden;
        }
        .photo-preview img {
            max-width: 100%;
            height: auto;
            display: block;
            margin: 0 auto;
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
    h2 {
    text-align: center;
    margin-top: 7px;
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
                            <i class='bx bxs-contact'></i>
                                <span class="nav-text">Contact</span>
                            </a>
                        </li>
                        <li>                                 
                            <a href="dashboardCl.php">
                            <i class='bx bxs-dashboard' ></i>
                                <span class="nav-text">Dashboard</span>
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
    <div class="container"><br>
    <?php if (!empty($message)) : ?>
            <div class="alert alert-success" role="alert">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>
        <center><h1><i class='bx bx-paste'></i><span>Consumption</span></h1></center><br><br><br>
        <form method="post" action="consommation.php" enctype="multipart/form-data">
            <label for="CONSOMMATION">Consumption (KWH) :</label>
            <input type="number" id="CONSOMMATION" name="CONSOMMATION" placeholder="Enter your consumption....." required>
            
            <label for="MOIS">Month :</label>
            <select id="MOIS" name="MOIS" required>
                <option value="1">January</option>
                <option value="2">February </option>
                <option value="3">March</option>
                <option value="4">April</option>
                <option value="5">May</option>
                <option value="6">June</option>
                <option value="7">July</option>
                <option value="8">August</option>
                <option value="9">September</option>
                <option value="10">October</option>
                <option value="11">November</option>
                <option value="12">December</option>
            </select>
            
            <label for="PHOTO_COMPTEUR">Meter photo :</label>
            <input type="file" id="PHOTO_COMPTEUR" name="PHOTO_COMPTEUR" accept="image/*" required>
            <div class="photo-preview"></div>
            <!-- Boutons en dehors du formulaire -->
            <div class="button-container">
                <input type="submit" name="submit" value="Send">
                <button onclick="window.history.back();" >Back</button>
            </div>
        </form>
    </div>
    <!-- Script pour afficher l'image dans le cadre -->
    <script>
        document.getElementById('PHOTO_COMPTEUR').addEventListener('change', function(event) {
            var preview = document.querySelector('.photo-preview');
            var file = event.target.files[0];
            var reader = new FileReader();
            reader.onload = function(event) {
                var img = document.createElement('img');
                img.src = event.target.result;
                preview.innerHTML = '';
                preview.appendChild(img);
            };
            reader.readAsDataURL(file);
        });
        function toggleDarkLight() {
            var body = document.body;
            body.classList.toggle("dark-mode");
        }
    </script>
</body>
</html>
