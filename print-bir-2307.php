<?php
// Include FPDF and FPDI (use the correct paths)
require_once('assets/libs/fpdf/fpdf.php');
require_once('assets/libs/fpdi/autoload.php');

use setasign\Fpdi\Fpdi;

// Create a new FPDI instance with 8.5 x 13 page size
$pdf = new Fpdi();
$pdf->AddPage('P', [216, 330]); // Set to 8.5 x 13 inches

// Load the official BIR Form 2307 PDF
$pdfPath = 'assets/libs/2307-form.pdf';
$pdf->setSourceFile($pdfPath);

// Import Page 1
$tplIdx = $pdf->importPage(1);
$pdf->useTemplate($tplIdx, 0, 0, 216);


// Your existing code for filling out fields (unchanged)

// Set font for text fields
$pdf->SetFont('Arial', '', size: 7.5);

// Fill in Payee TIN
$payee_tin = '00798001200312';

$payee_tin_positions = [
    [73.5, 46],  // 0
    [78, 46],    // 0
    [82.5, 46],  // 7
    [92, 46],    // 9
    [96.5, 46],  // 8
    [101, 46],    // 0
    [110, 46],   // 0
    [114, 46],   // 1
    [119, 46], // 2
    [128.5, 46], // 0
    [134, 46],   // 0
    [139, 46],   // 3
    [144.5, 46], // 3 (For 13-digit TIN)
    [149.5, 46],   // 3 (For 14-digit TIN)
];

// Loop through each digit and place it at the assigned position
for ($i = 0; $i < strlen($payee_tin); $i++) {
    if (isset($payee_tin_positions[$i])) { // Ensure the position exists
        $pdf->SetXY($payee_tin_positions[$i][0], $payee_tin_positions[$i][1]);
        $pdf->Cell(100, 10, $payee_tin[$i], 0, 1);
    }
}


// Fill in Payee
$pdf->SetXY(12, 56);  // Adjust X, Y as needed
$pdf->Cell(100, 10, 'MOTORHUB INC.', 0, 1);

// Fill in Payee Address
$pdf->SetXY(12, 66.5);
$pdf->Cell(100, 10, 'BRGY H. CONCEPCION CABANATUAN CITY', 0, 1);

// Fill in Payee's Zip Code
$pdf->SetXY(191.5, 66);
$pdf->Cell(100, 10, '3', 0, 1);
$pdf->SetXY(196, 66);
$pdf->Cell(100, 10, '1', 0, 1);
$pdf->SetXY(201, 66);
$pdf->Cell(100, 10, '0', 0, 1);
$pdf->SetXY(205, 66);
$pdf->Cell(100, 10, '0', 0, 1);

$payor_tin = '007980012003';

$payor_tin_positions = [
    [73.5, 87],  // 0
    [78, 87],    // 0
    [82.5, 87],  // 7
    [92, 87],    // 9
    [96.5, 87],  // 8
    [101, 87],    // 0
    [110, 87],   // 0
    [114, 87],   // 1
    [119, 87], // 2
    [128.5, 87], // 0
    [134, 87],   // 0
    [139, 87],   // 3
    [144.5, 87], // 3 (For 13-digit TIN)
    [149.5, 87],   // 3 (For 14-digit TIN)
];

// Loop through each digit and place it at the assigned position
for ($i = 0; $i < strlen($payor_tin); $i++) {
    if (isset($payor_tin_positions[$i])) { // Ensure the position exists
        $pdf->SetXY($payor_tin_positions[$i][0], $payor_tin_positions[$i][1]);
        $pdf->Cell(100, 10, $payor_tin[$i], 0, 1);
    }
}

// Fill in Payor's Name
$pdf->SetXY(12, 97);
$pdf->Cell(100, 10, 'N E TRUCK BUILDERS CORPORATION', 0, 1);

// Fill in Payor's Address
$pdf->SetXY(12, 107);
$pdf->Cell(100, 10, 'SAN JUAN ACCFA CABANATUAN CITY', 0, 1);

// Fill in Payor's Zip Code
$pdf->SetXY(191.5, 107);
$pdf->Cell(100, 10, '3', 0, 1);
$pdf->SetXY(196, 107);
$pdf->Cell(100, 10, '1', 0, 1);
$pdf->SetXY(201, 107);
$pdf->Cell(100, 10, '0', 0, 1);
$pdf->SetXY(205, 107);
$pdf->Cell(100, 10, '0', 0, 1);

$pdf->SetFont('Arial', '', size: 6.5);

// Fill in Income Payments
// Base Y position
$baseY = 127;
$spacing1 = 4.5; // First row spacing
$spacing2 = 5;   // Second row spacing
$currentSpacing = $spacing1; // Start with 4.5

// Sample Income Data
$incomes = [
    ['PAYMENT TO LOCAL SUPPLIERS-GOODS', 'WC158', '-', '22,321.43', '-', '22,321.43', '223.21'],
    ['PAYMENT TO LOCAL SUPPLIERS-SERVICES', 'WC159', '-', '10,000.00', '-', '10,000.00', '100.00'],
    ['PAYMENT TO FOREIGN SUPPLIERS', 'WC160', '-', '50,500.25', '-', '50,500.25', '505.00'],
    ['OTHER INCOME CATEGORY', 'WC161', '-', '30,750.75', '-', '30,750.75', '307.50'],
    ['OTHER INCOME CATEGORY', 'WC161', '-', '30,750.75', '-', '30,750.75', '307.50'],
    ['OTHER INCOME CATEGORY', 'WC161', '-', '30,750.75', '-', '30,750.75', '307.50'],
    ['OTHER INCOME CATEGORY', 'WC161', '-', '30,750.75', '-', '30,750.75', '307.50'],
    ['OTHER INCOME CATEGORY', 'WC161', '-', '30,750.75', '-', '30,750.75', '307.50'],
    ['OTHER INCOME CATEGORY', 'WC161', '-', '30,750.75', '-', '30,750.75', '307.50'],
    ['OTHER INCOME CATEGORY', 'WC161', '-', '30,750.75', '-', '30,750.75', '307.50'],
];

// Column positions (X-coordinates)
$columns = [8, 65, 89, 110.5, 141, 161.5, 190];

// Loop through each income row and set positions dynamically
$y = $baseY; // Initial Y position
foreach ($incomes as $index => $income) {
    foreach ($income as $colIndex => $text) {
        $pdf->SetXY($columns[$colIndex], $y);
        $pdf->Cell(100, 10, $text, 0, 1);
    }

    // Alternate spacing for the next row
    $y += $currentSpacing;
    $currentSpacing = ($currentSpacing == $spacing1) ? $spacing2 : $spacing1;
}

//TOTAL

$pdf->SetXY(89, 175);
$pdf->Cell(100, 10, '-', 0, 1);
$pdf->SetXY(110.5,  175);
$pdf->Cell(100, 10, '22,321.43', 0, 1);
$pdf->SetXY(141,  175);
$pdf->Cell(100, 10, '-', 0, 1);
$pdf->SetXY(161.5, 175);
$pdf->Cell(100, 10, '22,321.43', 0, 1);
$pdf->SetXY(190, 175);
$pdf->Cell(100, 10, '223.21', 0, 1);


//SIGNATURE
$pdf->SetFont('Arial', '', size: 9);

$pdf->SetXY(75, 257);
$pdf->Cell(100, 10, 'JOSEFINA MENDOZA/193-346-270', 0, 1);

// Import Page 2 (if applicable)
if ($pdf->setSourceFile($pdfPath) >= 2) { // Check if a second page exists
    $pdf->AddPage('P', [216, 330]);
    $tplIdx2 = $pdf->importPage(2);
    $pdf->useTemplate($tplIdx2, 0, 0, 216);
}

// Output the modified PDF
$pdf->Output('I', 'BIR_2307_filled.pdf');
?>
