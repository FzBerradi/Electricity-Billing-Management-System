<?php
session_start();
require_once 'db_connect.php';

// Vérifier si l'utilisateur est connecté en tant que client
if (!isset($_SESSION['user_name']) || $_SESSION['user_type'] !== 'client') {
    // Rediriger vers la page de connexion si l'utilisateur n'est pas connecté en tant que client
    header("Location: login.php");
    exit();
}
// Récupérer l'email de l'utilisateur à partir de la session
$email = $_SESSION['user_email'];

// Requête SQL pour récupérer les informations du client à partir de la base de données
$query = "SELECT * FROM client WHERE EMAIL_CL='$email'";
$result = mysqli_query($con, $query);

// Vérifier si l'utilisateur existe dans la base de données
if (mysqli_num_rows($result) == 1) {
    // Récupérer les données du client
    $row = mysqli_fetch_assoc($result);
    $cin = $row['CIN_CL'];
    $nom = $row['NOM_CL'];
    $prenom = $row['PRENOM_CL'];
    $address = $row['ADDRESS'];
    $tel= $row['TEL_CL'];
    // Vous pouvez récupérer d'autres champs de la même manière
} else {
    // Gérer le cas où l'utilisateur n'existe pas dans la base de données
    $cin = "N/A";
    $nom = "N/A";
    $prenom = "N/A";
    $address = "N/A";
    $tel = "N/A";
    // Vous pouvez définir d'autres champs sur "N/A" ou tout autre message approprié
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil  <?php echo $nom . ' ' . $prenom; ?></title>
    <link rel="stylesheet" href="styProfileCl.css">

</head>
<body>
    <div class="background"></div>
    <div class="container">
        <h2>Profile <i class='bx bxs-user-account user-icon'></i><?php echo $nom . ' ' . $prenom; ?></h2>
        <table>
            <tr>
                <th>Last Name</th>
                <td><?php echo $nom; ?><span></span></td>
            </tr>
            <tr>
                <th>First Name</th>
                <td><?php echo $prenom; ?><span></span></td>
            </tr>
            <tr>
                <th>CIN</th>
                <td><?php echo $cin; ?><span></span></td>
            </tr>
            <tr>
                <th>Address</th>
                <td><?php echo $address; ?><span></span></td>
            </tr>
            <tr>
                <th>Phone number</th>
                <td><?php echo $tel; ?><span></span></td>
            </tr>
        </table>
    </div>
</body>
</html>
