<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Show Counter Picture</title>
<style>
    body {
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
        margin: 0;
        background: rgb(228, 226, 226);
    }

    img {
        max-width: 100%;
        max-height: 100%;
    }
</style>
</head>
<body>
    <h2>Counter</h2>
    <?php
    include_once("db_connect.php");
    // Vérifier si le chemin de la photo du compteur est envoyé via POST
    if (isset($_POST['photo_compteur_path'])) {
        // Récupérer le chemin de la photo du compteur depuis la variable POST
        $photoCompteurPath = $_POST['photo_compteur_path'];

        // Vérifier si le fichier existe
        if (file_exists($photoCompteurPath)) {
            // Afficher l'image
            echo '<img src="' . $photoCompteurPath . '" alt="Photo du compteur">';
        } else {
            // Afficher un message d'erreur si le fichier n'existe pas
            echo "The photo of the meter does not exist.";
        }
    } else {
        // Afficher un message d'erreur si le chemin de la photo du compteur n'est pas envoyé
        echo "The path to the counter photo is not specified.";
    }
    ?>
</body>
</html>
