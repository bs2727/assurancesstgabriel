<?php
// compte/contrat_pdf.php

// Activer l'affichage des erreurs (à utiliser uniquement en développement)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Inclure FPDF
require_once "../backend/fpdf/fpdf.php";

// Démarrer la session si elle n'est pas déjà démarrée
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Inclure les fonctions backend
require_once "../backend/functions.php";

// Vérifier si l'utilisateur est connecté
if (!isUserLoggedIn()) {
    include_once("../backend/redirect.php");
    exit();
}

// Récupérer l'ID du contrat depuis l'URL
$contratId = $_GET['id'] ?? null;

if (!$contratId) {
    echo "Contrat introuvable.";
    exit();
}

// Récupérer le contrat correspondant
$contrat = getContractById($contratId);

// Vérifier que le contrat existe et appartient à l'utilisateur connecté
if (!$contrat || $contrat['user_id'] != getCurrentUserID()) {
    echo "Vous n'avez pas accès à ce contrat.";
    exit();
}

// Créer une instance de FPDF
$pdf = new FPDF();
$pdf->AddPage();

// Définir la police
$pdf->SetFont('Arial', 'B', 16);

// Ajouter le titre
$pdf->Cell(0, 10, 'Contrat d\'Assurance', 0, 1, 'C');
$pdf->Ln(10);

// Informations de l'entreprise
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(0, 6, 'Assurances Saint-Gabriel', 0, 1, 'C');
$pdf->Cell(0, 6, 'Adresse de l\'entreprise | Téléphone | Email', 0, 1, 'C');
$pdf->Ln(10);

// Informations du client
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(50, 6, 'Client :', 0, 0);
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(0, 6, htmlspecialchars($contrat['first_name'] . ' ' . $contrat['last_name']), 0, 1);

$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(50, 6, 'Date de création :', 0, 0);
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(0, 6, date('d/m/Y', strtotime($contrat['date_creation'])), 0, 1);
$pdf->Ln(5);

// Types d'assurance
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(50, 6, 'Type d\'Assurance :', 0, 0);
$pdf->SetFont('Arial', '', 12);
$typesAssurance = json_decode($contrat['types_assurance'], true);
$pdf->MultiCell(0, 6, htmlspecialchars(implode(', ', $typesAssurance)));
$pdf->Ln(5);

// Informations détaillées
$informations = json_decode($contrat['informations'], true);
foreach ($informations as $type => $infos) {
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(0, 6, htmlspecialchars($type) . " :", 0, 1);
    $pdf->SetFont('Arial', '', 12);
    foreach ($infos as $key => $value) {
        $pdf->Cell(50, 6, htmlspecialchars(ucfirst($key)) . " :", 0, 0);
        $pdf->Cell(0, 6, htmlspecialchars($value), 0, 1);
    }
    $pdf->Ln(5);
}

// Footer
$pdf->SetFont('Arial', 'I', 10);
$pdf->Cell(0, 10, 'Merci de votre confiance.', 0, 1, 'C');

// Générer le PDF
$pdf->Output('I', 'Contrat_' . $contrat['id'] . '.pdf');
?>
