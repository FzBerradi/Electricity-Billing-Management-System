<?php
session_start();
require_once 'db_connect.php';

// Vérifier si l'utilisateur est connecté en tant que client
if (!isset($_SESSION['user_name']) || $_SESSION['user_type'] !== 'client') {
    // Rediriger vers la page de connexion si l'utilisateur n'est pas connecté en tant que client
    header("Location: login.php");
    exit();
}

// Vérifier si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer les données du formulaire
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $cin = $_POST['cin'];
    $address = $_POST['address'];
    $tel = $_POST['tel'];

    // Récupérer l'email de l'utilisateur à partir de la session
    $email = $_SESSION['user_email'];

    // Requête SQL pour mettre à jour les informations du client dans la base de données
    $query = "UPDATE client SET NOM_CL='$nom', PRENOM_CL='$prenom', CIN_CL='$cin', ADDRESS='$address', TEL_CL='$tel' WHERE EMAIL_CL='$email'";

    if (mysqli_query($con, $query)) {
        // Rediriger vers la page de profil avec un message de succès
        header("Location: profileCl.php?success=1");
        exit();
    } else {
        // En cas d'erreur lors de la mise à jour, afficher un message d'erreur
        echo "Erreur lors de la mise à jour des données. Veuillez réessayer.";
    }
}

$email = $_SESSION['user_email'];
$query = "SELECT * FROM client WHERE EMAIL_CL='$email'";
$result = mysqli_query($con, $query);

if (mysqli_num_rows($result) == 1) {
    $row = mysqli_fetch_assoc($result);
    $cin = $row['CIN_CL'];
    $nom = $row['NOM_CL'];
    $prenom = $row['PRENOM_CL'];
    $address = $row['ADDRESS'];
    $tel = $row['TEL_CL'];
} else {
    $cin = "N/A";
    $nom = "N/A";
    $prenom = "N/A";
    $address = "N/A";
    $tel = "N/A";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
    <style>
        body {
    font-family: Arial, sans-serif;
    background-color: rgb(228, 226, 226);
    margin: 0;
    padding: 0;
}

.container {
    max-width: 500px;
    margin: 50px auto;
    padding: 20px;
    background-color: #fff;
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

h2 {
    text-align: center;
    margin-bottom: 20px;
}

.form-group {
    margin-bottom: 20px;
}

label {
    display: block;
    font-weight: bold;
}

input[type="text"] {
    width: 100%;
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 5px;
}

button[type="submit"], button {
    width: 48%;
    padding: 10px;
    background-color: #c4103d;
    color: #fff;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s;
}

button[type="submit"]:hover, button:hover {
    background-color: #a3082f;
}

button[type="submit"] {
    float: left;
    margin-right: 4%;
}
    </style>
</head>
<body>
    <div class="background"></div>
    <div class="container">
        <h2>Edit Profile <i class='bx bxs-edit' ></i></h2>
        <form action="editProfileCl.php" method="post">
            <div class="form-group">
                <label for="nom">Last Name:</label>
                <input type="text" id="nom" name="nom" value="<?php echo $nom; ?>">
            </div>
            <div class="form-group">
                <label for="prenom">First Name:</label>
                <input type="text" id="prenom" name="prenom" value="<?php echo $prenom; ?>">
            </div>
            <div class="form-group">
                <label for="cin">CIN:</label>
                <input type="text" id="cin" name="cin" value="<?php echo $cin; ?>">
            </div>
            <div class="form-group">
                <label for="address">Address:</label>
                <input type="text" id="address" name="address" value="<?php echo $address; ?>">
            </div>
            <div class="form-group">
                <label for="tel">Phone number:</label>
                <input type="text" id="tel" name="tel" value="<?php echo $tel; ?>">
            </div>
            <button type="submit">Save</button>
            <button onclick="window.history.back();" >Back</button>

        </form>
    </div>
</body>
</html>
