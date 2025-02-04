<?php
    error_reporting(E_ALL);
    ini_set('display_errors', 1);

    // Load TCPDF library
    require('assets/libs/tcpdf2/tcpdf.php');

    //Load required files
    require_once 'config/config.php';
    require_once 'session.php';
    require_once 'model/database-model.php';
    require_once 'model/disbursement-model.php';
    require_once 'model/system-model.php';
    require_once 'model/product-model.php';
    ob_start();
    require_once 'model/customer-model.php';
    require_once 'model/miscellaneous-client-model.php';
    ob_end_clean();
    
    $databaseModel = new DatabaseModel();
    $systemModel = new SystemModel();
    $disbursementModel = new DisbursementModel($databaseModel);    
    $customerModel = new CustomerModel($databaseModel);
    $productModel = new ProductModel($databaseModel);
    $miscellaneousClientModel = new MiscellaneousClientModel($databaseModel);
    
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
$pdf->SetAuthor('CGMI');
$pdf->SetTitle('Post-Dated Check - Philippines');
$pdf->SetSubject('PDC Details');

// Set margins
$pdf->SetMargins(10, 10, 10);

$pdcManagementDetails = $disbursementModel->getDisbursementCheck($_GET['id']);
    $check_amount = $pdcManagementDetails['check_amount'] ?? '';
    $check_date = $pdcManagementDetails['check_date'] ?? '';
    $check_number = $pdcManagementDetails['check_number'] ?? 0;
    $disbursement_id = $pdcManagementDetails['disbursement_id'] ?? 0;
    $amountInWords = new NumberFormatter("en", NumberFormatter::SPELLOUT);

    $date = new DateTime($check_date);

    $disbursementDetails = $disbursementModel->getDisbursement($disbursement_id);
    $customer_id = $disbursementDetails['customer_id'];
    $payable_type = $disbursementDetails['payable_type'];

    if($payable_type === 'Customer'){
        $customerDetails = $customerModel->getPersonalInformation($customer_id);
        $customerName = $customerDetails['file_as'] ?? null;
    }
    else{
        $miscellaneousClientDetails = $miscellaneousClientModel->getMiscellaneousClient($customer_id);
        $customerName = $miscellaneousClientDetails['client_name'] ?? null;
    }

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
    $checkY = 8;

    // Print date (numeric format)
    $pdf->SetXY($checkX + 145, $checkY);
    $pdf->Cell(5, 10, $month, 0, 0, 'L');
    $pdf->Cell(5, 10, '-', 0, 0, 'C');
    $pdf->Cell(5, 10, $day, 0, 0, 'L');
    $pdf->Cell(5, 10, '-', 0, 0, 'C');
    $pdf->Cell(5, 10, $year, 0, 0, 'L');

    // Print payee and amount aligned horizontally
    $pdf->SetXY($checkX, 15);
    $pdf->Cell(10, 10,  '', 0, 0, 'L');
    $pdf->Cell(140, 10,  strtoupper($customerName), 0, 0, 'L');
    $pdf->Cell(40, 10, number_format($check_amount,2), 0, 0, 'L');

    // Print amount in 30
    $pdf->SetXY($checkX,24);
    $pdf->Cell(10, 10,  '', 0, 0, 'L');
    $pdf->Cell(150, 10, strtoupper($amountInWords->format($check_amount)) . ' ONLY', 0, 0, 'L');

// Output PDF document
$pdf->Output('check.pdf', 'I');
?>