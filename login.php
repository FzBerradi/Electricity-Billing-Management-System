<?php
session_start();
require_once 'db_connect.php';

// Vérifier si le formulaire de connexion a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer les données du formulaire de connexion
    $email = $_POST['email'];
    $password = $_POST['password'];
    $cin= $_POST['cin'];
    // vérifier les informations d'identification de l'utilisateur dans la table client
    $query = "SELECT * FROM client WHERE EMAIL_CL='$email' AND PASSWORD='$password'";
    $result = mysqli_query($con, $query);
    // Vérifier si l'utilisateur est un client
    if (mysqli_num_rows($result) == 1) {
        // Récupérer le nom de l'utilisateur depuis la base de données
        $row = mysqli_fetch_assoc($result);
        $userName = $row['NOM_CL'];
        // Récupérer le CIN de l'utilisateur depuis la base de données
        $cin = $row['CIN_CL'];
        // Stocker les informations de l'utilisateur dans des variables de session
        $_SESSION['user_name'] = $userName;
        $_SESSION['user_email'] = $email;
        $_SESSION['user_type'] = 'client';
        // Stocker le CIN dans une variable de session
        $_SESSION['cin'] = $cin;
        // Rediriger vers le tableau de bord du client
        header("Location: dashboardCl.php");
        exit();
    }
    //vérifier les informations d'identification de l'utilisateur dans la table fournisseur
    $query = "SELECT * FROM fournisseur WHERE EMAIL_F='$email' AND PASSWORD_F='$password'";
    $result = mysqli_query($con, $query);
    // Vérifier si l'utilisateur est un fournisseur
    if (mysqli_num_rows($result) == 1) {
        // Récupérer le nom de l'utilisateur depuis la base de données
        $row = mysqli_fetch_assoc($result);
        $userName = $row['EMAIL_F']; // Modifier le champ en fonction de votre structure de base de données
        // Stocker les informations de l'utilisateur dans des variables de session
        $_SESSION['user_name'] = $userName;
        $_SESSION['user_email'] = $email;
        $_SESSION['user_type'] = 'fournisseur';
        // Rediriger vers le tableau de bord du fournisseur
        header("Location: fournisseurDashboard.php");
        exit();
    }
    // Si les informations d'identification sont incorrectes ou l'utilisateur n'existe pas
    $error = "Email ou mot de passe incorrects";
} else {
    // Initialiser la variable $error si le formulaire n'a pas été soumis
    $error = "";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Electricity Tool</title>
    <link rel="stylesheet" href="styleLog.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>
<body>
    <header class="header">
        <nav class="navbar">
            <a href="#">Home</a>
            <a href="#">About</a>
            <a href="#">Services</a>
            <a href="#">Contact</a>
        </nav>
        <form action="#" class="search-bar">
            <input type="text" placeholder="Search....">
            <button type="submit"><i class='bx bx-search-alt'></i>
        </button>
        </form>
    </header>
    <div class="background"></div>
    <div class="container">
        <div class="content">
            <h2 class="logo"><i class='bx bxl-firebase'></i>Electricity Tool</h2>
            <div class="text-sci">
                <h2>Welcome! <br> <span> To Electricity Tool, your 
                destination for managing your electricity 
                consumption and bills online.</span></h2>
                <p>
                Thank you for choosing Electricity Tool 
                for your electricity management needs!
                </p>
                <div class="social-icons">
                    <a href="#">
                    <i class='bx bxl-linkedin'></i>
                    </a>
                    <a href="#"><i class='bx bxl-facebook' >
                    </i></a>
                    <a href="#"><i class='bx bxl-instagram' ></i>
                </a>
                    <a href="#"><i class='bx bxl-gmail' ></i></a>
                </div>
            </div>
        </div>
            <div class="logreg-box">
                <div class="form-box login">
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                        <h2>Sign In</h2>
                        <?php if (!empty($error)): ?>
                            <div class='error-message'><?php echo $error; ?></div>
                        <?php endif; ?>
                        <div class="input-box">
                            <span class="icon">
                                <i class='bx bxs-envelope' >
                            </i></span>
                            <input type="email" name="email">
                            <label>Email</label>
                        </div>
                        <div class="input-box">
                            <span class="icon"><i class='bx bxs-lock-alt' ></i>
                        </span>
                            <input type="password" name="password" required>
                            <label>Password</label>
                        </div>
                        <div class="remember-forgot">
                            <label >
                                <input type="checkbox">
                                Remember Me
                            </label>
                            <a href="#">Forgot password </a>
                        </div>
                        <button type="submit" class="btn">Sign In</button>
                        <div class="login-register">
                            <p>Don't have an account? <a href="#" class="register-link">
                            Sign up
                            </a></p>
                        </div>
                    </form>
                </div>
            </div>
    </div>
    <script src="scriptLog.js"></script>
</body>
</html>
