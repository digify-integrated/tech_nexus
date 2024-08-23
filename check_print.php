<?php
    error_reporting(E_ALL);
    ini_set('display_errors', 1);

    // Load TCPDF library
    require('assets/libs/tcpdf2/tcpdf.php');

    //Load required files
    require_once 'config/config.php';
    require_once 'session.php';
    require_once 'model/database-model.php';
    require_once 'model/pdc-management-model.php';
    require_once 'model/system-model.php';

    $databaseModel = new DatabaseModel();
    $systemModel = new SystemModel();
    $pdcManagementModel = new PDCManagementModel($databaseModel);
    
    if(isset($_GET['id'])){
        if(empty($_GET['id'])){
          header('location: dashboard.php');
          exit;
        }
    }
    
    // Example usage
   // create new PDF document
$pdf = new TCPDF('L', 'mm', 'A4', true, 'UTF-8', false);
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);
// Set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Your Company');
$pdf->SetTitle('Post-Dated Check - Philippines');
$pdf->SetSubject('PDC Details');

// Set margins
$pdf->SetMargins(10, 10, 10);

$loanCollectionIDs = explode(',', $_GET['id']);

foreach ($loanCollectionIDs as $loanCollectionID) {
    $pdcManagementDetails = $pdcManagementModel->getPDCManagement($loanCollectionID);
    $payment_amount = $pdcManagementDetails['payment_amount'];
    $check_date = $pdcManagementDetails['check_date'];
    $amountInWords = new NumberFormatter("en", NumberFormatter::SPELLOUT);

    $date = new DateTime($check_date);

    // Extract month, day, and year
    $month = $date->format('m');
    $day = $date->format('d');
    $year = $date->format('Y');

    // Add a page
    $pdf->AddPage('P');

    // Set font for the check
    $pdf->SetFont('Helvetica', '', 10);

    // Check details variables
    $date = '08-15-2024';  // Date in numeric format (MM/DD/YYYY)
    $amountText = 'Seven Hundred Seventy-Seven Thousand Eight Hundred Eighty-Eight Pesos Only';  // Amount in words

    // Positioning variables (adjust based on your check layout)
    $checkX = 20;
    $checkY = 2;

    // Print date (numeric format)
    $pdf->SetXY($checkX + 145, $checkY);
    $pdf->Cell(5, 10, $month, 0, 0, 'L');
    $pdf->Cell(5, 10, '-', 0, 0, 'C');
    $pdf->Cell(5, 10, $day, 0, 0, 'L');
    $pdf->Cell(5, 10, '-', 0, 0, 'C');
    $pdf->Cell(5, 10, $year, 0, 0, 'L');

    // Print payee and amount aligned horizontally
    $pdf->SetXY($checkX, 10);
    $pdf->Cell(10, 10,  '', 0, 0, 'L');
    $pdf->Cell(140, 10,  'CHRISTIAN GENERAL MOTORS INC', 0, 0, 'L');
    $pdf->Cell(40, 10, number_format($payment_amount,2), 0, 0, 'L');

    // Print amount in words
    $pdf->SetXY($checkX,20);
    $pdf->Cell(140, 10, strtoupper($amountInWords->format($payment_amount)) . ' ONLY', 0, 0, 'L');
}

// Output PDF document
$pdf->Output('check.pdf', 'I');
?>