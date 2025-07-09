<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Load TCPDF library
require('assets/libs/tcpdf2/tcpdf.php');

// Load required files
require_once 'config/config.php';
require_once 'session.php';
require_once 'model/database-model.php';
require_once 'model/pdc-management-model.php';
require_once 'model/system-model.php';
require_once 'model/product-model.php';
ob_start();
require_once 'model/customer-model.php';
ob_end_clean();

$databaseModel = new DatabaseModel();
$systemModel = new SystemModel();
$pdcManagementModel = new PDCManagementModel($databaseModel);
$customerModel = new CustomerModel($databaseModel);
$productModel = new ProductModel($databaseModel);

// Redirect if no ID provided
if (isset($_GET['id']) && empty($_GET['id'])) {
    header('location: dashboard.php');
    exit;
}

// Create PDF
$pdf = new TCPDF('L', 'mm', 'A4', true, 'UTF-8', false);
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Your Company');
$pdf->SetTitle('Post-Dated Check - Philippines');
$pdf->SetSubject('PDC Details');
$pdf->SetMargins(10, 10, 10);

$loanCollectionIDs = explode(',', $_GET['id']);

foreach ($loanCollectionIDs as $loanCollectionID) {
    $pdcManagementDetails = $pdcManagementModel->getPDCManagement($loanCollectionID);
    $payment_amount = $pdcManagementDetails['payment_amount'];
    $check_date = $pdcManagementDetails['check_date'];
    $loan_number = $pdcManagementDetails['loan_number'] ?? '';
    $product_id = $pdcManagementDetails['product_id'] ?? '';
    $customer_id = $pdcManagementDetails['customer_id'] ?? '';
    $amountInWords = new NumberFormatter("en", NumberFormatter::SPELLOUT);

    $customerDetails = $customerModel->getPersonalInformation($customer_id);
    $customerName = strtoupper($customerDetails['file_as']) ?? '';

    $productDetails = $productModel->getProduct($product_id);
    $stock_number = $productDetails['stock_number'] ?? '';

    if (!empty($stock_number)) {
        $stock_number = ' - ' . $stock_number;
    }

    if (!empty($loan_number)) {
        $loan_number = ' - ' . $loan_number;
    }

    // Handle amount in words with decimal places
    $whole = floor($payment_amount);
    $cents = round(($payment_amount - $whole) * 100);
    $words = strtoupper($amountInWords->format($whole)) . ' PESOS';
    if ($cents > 0) {
        $words .= ' AND ' . str_pad($cents, 2, '0', STR_PAD_LEFT) . '/100';
    }
    $words .= '';

    // Extract date digits
    $date = new DateTime($check_date);
    $month = $date->format('m');
    $day = $date->format('d');
    $year = $date->format('Y');
    $dateDigits = str_split($month . $day . $year); // ['0','7','0','9','2','0','2','5']

    // Add PDF page
    $pdf->AddPage('P');
    $pdf->SetFont('Helvetica', '', 10);

    // Positioning values
    $checkX = 20;
    $checkY = 10;
    $boxWidth = 5;
    $boxHeight = 8;
    $groupSpacing = 3;
    $startX = $checkX + 128;
    $startY = $checkY;
    $currentX = $startX;

    // Print date digits individually with group spacing
    for ($i = 0; $i < count($dateDigits); $i++) {
        $pdf->SetXY($currentX, $startY);
        $pdf->Cell($boxWidth, $boxHeight, $dateDigits[$i], 0, 0, 'C');
        $currentX += $boxWidth;
        if ($i == 1 || $i == 3) {
            $currentX += $groupSpacing;
        }
    }

    // Payee and amount in figures
    $pdf->SetXY($checkX + 20, 17);
    $pdf->Cell(10, 10, '', 0, 0, 'L');
    $pdf->Cell(120, 10, 'CHRISTIAN GENERAL MOTORS INC', 0, 0, 'L');
    $pdf->Cell(40, 10, number_format($payment_amount, 2), 0, 0, 'L');

    // Amount in words
    $pdf->SetXY($checkX, 25);
    $pdf->Cell(10, 10, '', 0, 0, 'L');
    $pdf->Cell(150, 10, $words, 0, 0, 'L');

    // Customer name + stock + loan info
    $pdf->SetXY($checkX + 10, 56);
    $pdf->Cell(140, 10, strtoupper($customerName) . strtoupper($stock_number) . $loan_number, 0, 0, 'L');
}

// Output final PDF
$pdf->Output('check_' . date('Ymd_His') . '.pdf', 'I');
?>