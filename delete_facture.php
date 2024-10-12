<?php
    include_once("db_connect.php");

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["facture_id"])) {
        $facture_id = $_POST["facture_id"];

        $delete_query = "DELETE FROM FACTURE WHERE ID_FACTURE = $facture_id";

        if (mysqli_query($con, $delete_query)) {
            echo "<script>alert('La facture a été supprimée avec succès.')</script>";
            // Rediriger ou actualiser la page après la suppression
            header("Location: factures.php");
            exit();
        } else {
            echo "Erreur lors de la suppression de la facture : " . mysqli_error($con);
        }
    }
?>
