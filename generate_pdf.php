<?php
include "db_connect.php";
require_once('tcpdf/tcpdf.php');

$error_message = ""; // Initialisez la variable $error_message

if(isset($_GET['id_facture']) && isset($_GET['download_pdf'])) {
    // Récupérer l'ID de la facture à partir de la requête GET
    $idFacture = $_GET['id_facture'];

    // Récupérer les données de la facture à partir de la base de données
    $sql = "SELECT * FROM facture WHERE ID_FACTURE = $idFacture";
    $result = mysqli_query($con, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);

        // Récupérer les données du client à partir de la base de données
        $cinClient = $row['CIN_CL'];
        $sqlClient = "SELECT * FROM client WHERE CIN_CL = '$cinClient'";
        $resultClient = mysqli_query($con, $sqlClient);
        
        if ($resultClient && mysqli_num_rows($resultClient) > 0) {
            $rowClient = mysqli_fetch_assoc($resultClient);
            $nomClient = $rowClient['NOM_CL'];
            $prenomClient = $rowClient['PRENOM_CL'];
            $adress = $rowClient['ADDRESS'];
            $photoCompteur = $row['PHOTO_COMPTEUR'];
            // Récupérer les données de la facture
            $consommationActuelle = $row['CONS_ACT']; // Consommation actuelle
            $moisActuel = $row['MOIS'];
            
            // Convertir le numéro du mois en nom du mois
            $moisNoms = [
                1 => 'Janvier',
                2 => 'Février',
                3 => 'Mars',
                4 => 'Avril',
                5 => 'Mai',
                6 => 'Juin',
                7 => 'Juillet',
                8 => 'Août',
                9 => 'Septembre',
                10 => 'Octobre',
                11 => 'Novembre',
                12 => 'Décembre'
            ];

            $moisActuelNom = $moisNoms[$moisActuel];
            // Vérifier si le mois actuel est janvier
            if ($moisActuel == 1) {
                // Si le mois actuel est janvier, permettre la génération sans vérifier la consommation du mois précédent
                $consommation = $consommationActuelle;
            } else {
                // Récupérer les données du mois précédent
                $moisPrecedent = ($moisActuel == 1) ? 12 : ($moisActuel - 1);
                $sqlMoisPrecedent = "SELECT CONS_ACT FROM facture WHERE CIN_CL = '$cinClient' AND MOIS = $moisPrecedent";
                $resultMoisPrecedent = mysqli_query($con, $sqlMoisPrecedent);

                if ($resultMoisPrecedent && mysqli_num_rows($resultMoisPrecedent) > 0) {
                    $rowMoisPrecedent = mysqli_fetch_assoc($resultMoisPrecedent);
                    $consommationPrecedente = $rowMoisPrecedent['CONS_ACT']; // Consommation précédente

                    // Calculer la consommation
                    $consommation = $consommationActuelle - $consommationPrecedente;

                    // Vérifier si la consommation actuelle est inférieure à la consommation du mois précédent
                    if ($consommation < 0) {
                        $error_message = "Error: Current consumption is lower than previous month's consumption.";
                        // Redirection vers consulter.php avec le message d'erreur
                        header("Location: consulter.php?error_message=" . urlencode($error_message));
                        exit();
                    }
                } else {
                    $error_message = "Error: No consumption found for the previous month.";
                    // Redirection vers consulter.php avec le message d'erreur
                    header("Location: consulter.php?error_message=" . urlencode($error_message));
                    exit();
                }
            }
            // Vérifier s'il y a des anomalies dans la consommation
            $anomalie = false;
            if ($consommationActuelle < 0 || $consommationActuelle > 10000 || $consommation < 0) {
                $anomalie = true;
            }
            // Si aucune anomalie n'est détectée, générer le PDF
            if (!$anomalie) {
                // Créer une nouvelle instance TCPDF
                $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
                // Définir les informations du document
                $pdf->SetCreator(PDF_CREATOR);
                $pdf->SetAuthor('Votre Nom');
                $pdf->SetTitle('Facture Électricité');
                $pdf->SetSubject('Facture Électricité');
                $pdf->SetKeywords('Facture, Électricité');
                // Ajouter une page
                $pdf->AddPage();

             /// Définir la position et la taille de la barre d'informations du client
            $barX = 15; // Position X de la barre
            $barY = 15; // Position Y de la barre
            $barWidth = 150; // Largeur de la barre
            $barHeight = 40; // Hauteur de la barre

// Ajouter les informations du client
        $pdf->SetFont('helvetica', '', 10);
        $pdf->SetXY(15, 15);
        $pdf->Cell(0, 10, 'Nom: ' . $nomClient, 0, 1, 'L');
        $pdf->Cell(0, 10, 'Prénom: ' . $prenomClient, 0, 1, 'L');
        $pdf->Cell(0, 10, 'Adresse: ' . $adress, 0, 1, 'L');

// Définir la position et la taille du cadre pour le code QR
$qrCodeFrameX = $barX + $barWidth + 10; // Position X du cadre
$qrCodeFrameY = $barY + 5; // Position Y du cadre
$qrCodeFrameWidth = $barHeight - 10; // Largeur du cadre (pour rendre le code QR carré)
$qrCodeFrameHeight = $barHeight - 10; // Hauteur du cadre (pour rendre le code QR carré)

// Ajouter le code QR à partir de votre image
$qrCodeImagePath = 'img/qr_code.png'; // Spécifiez le chemin de votre image de code QR
$pdf->Image($qrCodeImagePath, $qrCodeFrameX, $qrCodeFrameY, $qrCodeFrameWidth, $qrCodeFrameHeight, '', '', '', false, 300, '', false, false, 0);
// Définir la position pour le reste du contenu
$pdf->SetXY(20, 60);

                // Ajouter l'en-tête avec le logo et la date de téléchargement
                $pdf->Image('img/power_energy_bolt_thunderbolt_electricity_icon_191388 (1).png', 85, 25, 40, '', 'PNG', '', '', false, 300, '', false, false, 0);
                $pdf->SetFont('helvetica', '', 10);
                $pdf->Cell(0, 10, 'Date de téléchargement : ' . date('d/m/Y'), 0, 1, 'R');
                
                // Ajouter le titre de la facture
                $pdf->SetFont('helvetica', 'B', 16);
                $pdf->Cell(0, 10, 'Facture d\'électricité', 0, 1, 'C');
                $pdf->Ln(10);

                // Créer un tableau pour afficher les données de la facture
                $pdf->SetFont('helvetica', '', 12);
                $pdf->SetFillColor(0, 128, 255); // Couleur de fond bleue
                $pdf->SetTextColor(255, 255, 255); // Couleur du texte blanc
                $pdf->SetDrawColor(0, 0, 0); // Couleur de bordure noire
                $pdf->SetLineWidth(0.3); // Épaisseur de la ligne de bordure
                $pdf->Cell(190, 10, 'Informations de la facture', 1, 1, 'C', true);

                $pdf->SetFillColor(240, 240, 240); // Couleur de fond grise claire
                $pdf->SetTextColor(0, 0, 0); // Couleur du texte noir
                $pdf->Cell(95, 10, 'ID Facture', 1, 0, 'C', true);
                $pdf->Cell(95, 10, $idFacture, 1, 1, 'C');

                $pdf->Cell(95, 10, 'Consommation (KWH)', 1, 0, 'C', true);
                $pdf->Cell(95, 10, $consommation, 1, 1, 'C');
                $pdf->Cell(95, 10, 'Prix HT', 1, 0, 'C', true);
                $pdf->Cell(95, 10, $row['PRIX_HT'], 1, 1, 'C');
                $pdf->Cell(95, 10, 'Prix TTC', 1, 0, 'C', true);
                $pdf->Cell(95, 10, $row['PRIX_TTC'], 1, 1, 'C');
                
                $pdf->Cell(95, 10, 'Année', 1, 0, 'C', true);
                $pdf->Cell(95, 10, $row['ANNEE'], 1, 1, 'C');
                
                $pdf->Cell(95, 10, 'Mois', 1, 0, 'C', true);
                $pdf->Cell(95, 10, $moisActuelNom, 1, 1, 'C');
                $cellWidth = 95;
$cellHeight = 40;

// Position actuelle du PDF
$currentX = $pdf->GetX();
$currentY = $pdf->GetY();

// Définir la couleur de fond de la cellule
$pdf->SetFillColor(240, 240, 240); // Couleur de fond grise claire

// Dessiner le cadre de la cellule avec la couleur de fond
$pdf->Rect($currentX, $currentY, $cellWidth, $cellHeight, 'F');

// Positionner le titre au centre de la cellule
$pdf->SetXY($currentX, $currentY);
$pdf->Cell($cellWidth, 10, 'Photo du Compteur', 0, 1, 'C');

// Positionner l'image à l'intérieur de la cellule
$imageWidth = $cellWidth - 10; 
$imageHeight = $cellHeight - 20; 
$imageX = $currentX + 5; 
$imageY = $currentY + 15; 
$pdf->Image($photoCompteur, $imageX, $imageY, $imageWidth, $imageHeight);
$lineSpacing = 5;
$pdf->Ln($lineSpacing);
$pdf->Ln($lineSpacing);
$pdf->Ln($lineSpacing);
$pdf->Ln($lineSpacing);

$pdf->SetXY($currentX + $cellWidth, $currentY);
$pdf->SetFont('helvetica', 'I', 8);

$currentX = $pdf->GetX();
$currentY = $pdf->GetY();

$pdf->Cell(0, 10, 'Visitez notre site web : www.electricitytool.com', 0, 1, 'L');

$pdf->Cell(0, 10, 'Merci pour votre confiance!', 0, 1, 'R');

$pdf->SetLineWidth(0.5);
$pdf->Line($currentX, $currentY, $pdf->GetPageWidth() - $currentX, $currentY);
$pdf->Ln($lineSpacing);
$pdf->Ln($lineSpacing);
                
    $pdf->Output('facture_electricite_' . $idFacture . '.pdf', 'D'); 

                exit();
            } else {
                $error_message = "Error: Anomaly detected in consumption. Invoice cannot be generated.";
            }
        } else {
            $error_message = "Error: No customer information found.";
        }
    } else {
        $error_message = "Error: No bills found with this ID.";
    }
}

header("Location: consulter.php?error_message=" . urlencode($error_message));
exit();
?>
