<?php
// Include FPDF and FPDI (use the correct paths)
require_once('assets/libs/fpdf/fpdf.php');
require_once('assets/libs/fpdi/autoload.php');

require_once 'config/config.php';
    require_once 'session.php';
    require_once 'model/database-model.php';
    require_once 'model/disbursement-model.php';
    require_once 'model/system-model.php';
    require_once 'model/user-model.php';
    require_once 'model/customer-model.php';
    require_once 'model/miscellaneous-client-model.php';
    require_once 'model/company-model.php';
    require_once 'model/city-model.php';
    require_once 'model/state-model.php';
    require_once 'model/country-model.php';

    $databaseModel = new DatabaseModel();
    $systemModel = new SystemModel();
    $userModel = new UserModel(new DatabaseModel, new SystemModel);
    $disbursementModel = new DisbursementModel($databaseModel);
    $customerModel = new CustomerModel($databaseModel);
    $miscellaneousClientModel = new MiscellaneousClientModel($databaseModel);
    $companyModel = new CompanyModel($databaseModel);
    $cityModel = new CityModel($databaseModel);
    $stateModel = new StateModel($databaseModel);
    $countryModel = new CountryModel($databaseModel);

if(isset($_GET['id'])){
    if(empty($_GET['id'])){
      header('location: disbursement.php');
      exit;
    }

    $disbursementID = $_GET['id'];
    $transaction_start_date = $systemModel->checkDate('empty', $_GET['start_date'], '', 'm/d/Y', '');
    $transaction_end_date = $systemModel->checkDate('empty', $_GET['end_date'], '', 'm/d/Y', '');
    $witholding_amount = $_GET['amount'] ?? 0;

    $disbursementDetails = $disbursementModel->getDisbursement($disbursementID);
    $payable_type = $disbursementDetails['payable_type'] ?? '';
    $customer_id = $disbursementDetails['customer_id'] ?? '';
    $company_id = $disbursementDetails['company_id'] ?? '';
    
    $companyDetails = $companyModel->getCompany($company_id);
    $company_name = $companyDetails['company_name'] ?? null;
    $tax_id = $companyDetails['tax_id'] ?? null;
    $address = $companyDetails['address'] ?? null;
    $cityID = $companyDetails['city_id'];

    $cityDetails = $cityModel->getCity($cityID);
    $cityName = $cityDetails['city_name'];
    $stateID = $cityDetails['state_id'];
    
    $stateDetails = $stateModel->getState($stateID);
    $stateName = $stateDetails['state_name'];
    $countryID = $stateDetails['country_id'];
    
    $countryName = $countryModel->getCountry($countryID)['country_name'];
    
    $companyAddress = $address . ', ' . $cityName . ', ' . $stateName . ', ' . $countryName;
 
    $customerAddress = '';
    $customerName = '';
    $tin = '';
    if($payable_type === 'Customer'){
        $customerDetails = $customerModel->getPersonalInformation($customer_id);
        $customerName = $customerDetails['file_as'] ?? null;

        $customerPrimaryAddress = $customerModel->getCustomerPrimaryAddress($customer_id);
        $customerAddress = $customerPrimaryAddress['address'] ?? '' . ', ' . $customerPrimaryAddress['city_name'] ?? '' . ', ' . $customerPrimaryAddress['state_name'] ?? '' . ', ' . $customerPrimaryAddress['country_name'] ?? '';
    }
    else{
        $miscellaneousClientDetails = $miscellaneousClientModel->getMiscellaneousClient($customer_id);
        $customerName = $miscellaneousClientDetails['client_name'] ?? null;
        $customerAddress = $miscellaneousClientDetails['address'] ?? null;
        $tin = $miscellaneousClientDetails['tin'] ?? null;
    }

    if($company_id == 2 || $customer_id == 4){
        $signature = 'JOSEFINA MENDOZA / FINANCE HEAD / 193-346-270';
    }
    else{
        $signature = 'LALAINE PENACILLA / FINANCE HEAD / 124-031-432';
    }

}

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
$payee_tin = $tin;

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


// Remove separators so we only deal with digits
$transaction_start_date_digits = str_replace('/', '', $transaction_start_date);

// Define positions for MMDDYYYY (adjust coordinates as needed)
$transaction_start_date_positions = [
    [54, 35], // M
    [58.5, 35], // M
    [63.5, 35], // D
    [68, 35], // D
    [73, 35], // Y
    [77, 35], // Y
    [82, 35], // Y
    [87, 35], // Y
];

// Loop through each digit and place it in the correct cell
for ($i = 0; $i < strlen($transaction_start_date_digits); $i++) {
    if (isset($transaction_start_date_positions[$i])) {
        $pdf->SetXY(
            $transaction_start_date_positions[$i][0],
            $transaction_start_date_positions[$i][1]
        );
        $pdf->Cell(10, 10, $transaction_start_date_digits[$i], 0, 0);
    }
}

// Remove separators so we only deal with digits
$transaction_end_date_digits = str_replace('/', '', $transaction_end_date);

// Define positions for MMDDYYYY (adjust coordinates as needed)
$transaction_end_date_positions = [
    [141.5, 35], // M
    [146, 35], // M
    [151, 35], // D
    [155, 35], // D
    [160, 35], // Y
    [164.5, 35], // Y
    [169.5, 35], // Y
    [174, 35], // Y
];

// Loop through each digit and place it in the correct cell
for ($i = 0; $i < strlen($transaction_end_date_digits); $i++) {
    if (isset($transaction_end_date_positions[$i])) {
        $pdf->SetXY(
            $transaction_end_date_positions[$i][0],
            $transaction_end_date_positions[$i][1]
        );
        $pdf->Cell(10, 10, $transaction_end_date_digits[$i], 0, 0);
    }
}


// Fill in Payee
$pdf->SetXY(12, 56);  // Adjust X, Y as needed
$pdf->Cell(100, 10, $customerName, 0, 1);

// Fill in Payee Address
$pdf->SetXY(12, 66.5);
$pdf->Cell(100, 10, $customerAddress, 0, 1);

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
$pdf->Cell(100, 10, $company_name, 0, 1);

// Fill in Payor's Address
$pdf->SetXY(12, 107);
$pdf->Cell(100, 10, $address, 0, 1);

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

$goodsTotal = 0;
$goods1stQuarterTotal = 0;
$goods2ndQuarterTotal = 0;
$goods3rdQuarterTotal = 0;
$goodsWithholdingTotal = 0;

$serviceTotal = 0;
$service1stQuarterTotal = 0;
$service2ndQuarterTotal = 0;
$service3rdQuarterTotal = 0;
$serviceWithholdingTotal = 0;

$sql = $databaseModel->getConnection()->prepare('SELECT * from disbursement_particulars where disbursement_id  = :disbursement_id');
$sql->bindValue(':disbursement_id', $disbursementID, PDO::PARAM_INT);
$sql->execute();
$options = $sql->fetchAll(PDO::FETCH_ASSOC);
$sql->closeCursor();

foreach ($options as $row) {
    $disbursement_particulars_id = $row['disbursement_particulars_id'];
    $base_amount = $row['base_amount'];
    $tax_quarter = $row['tax_quarter'];
    $withholding_amount = $witholding_amount;
    $with_withholding = $row['with_withholding'];

    if($with_withholding == '1'){
        $goodsTotal = $withholding_amount / 0.01;
        $goodsWithholdingTotal = $witholding_amount;

        if($tax_quarter == '1'){
            $goods1stQuarterTotal = $goodsTotal;
        }
        else if($tax_quarter == '2'){
            $goods2ndQuarterTotal = $goodsTotal;
        }
        else{
            $goods3rdQuarterTotal = $goodsTotal;
        }
    }
    else if($with_withholding == '2'){
        $serviceTotal = $withholding_amount / 0.02;
        $serviceWithholdingTotal = $witholding_amount;

        if($tax_quarter == '1'){
            $service1stQuarterTotal = $serviceTotal;
        }
        else if($tax_quarter == '2'){
            $service2ndQuarterTotal = $serviceTotal;
        }
        else{
            $service3rdQuarterTotal = $serviceTotal;
        }
    }
}

$total1stQuarter = $goods1stQuarterTotal + $service1stQuarterTotal;
$total2ndQuarter = $goods2ndQuarterTotal + $service2ndQuarterTotal;
$total3rdQuarter = $goods3rdQuarterTotal + $service3rdQuarterTotal;
$totalAmount = $total1stQuarter + $total2ndQuarter + $total3rdQuarter;
$totalWithholding = $goodsWithholdingTotal + $serviceWithholdingTotal;

$incomes = []; // Initialize the array

if ($goodsTotal > 0) {
    $incomes[] = [
        'PAYMENT TO LOCAL SUPPLIERS-GOODS', 
        'WC158', 
        number_format($goods1stQuarterTotal, 2), 
        number_format($goods2ndQuarterTotal, 2), 
        number_format($goods3rdQuarterTotal, 2), 
        number_format($goodsTotal, 2), 
        number_format($goodsWithholdingTotal, 2)
    ];
}

if ($serviceTotal > 0) {
    $incomes[] = [
        'PAYMENT TO LOCAL SUPPLIERS-SERVICES', 
        'WC159', 
        number_format($service1stQuarterTotal, 2), 
        number_format($service2ndQuarterTotal, 2), 
        number_format($service3rdQuarterTotal, 2), 
        number_format($serviceTotal, 2), 
        number_format($totalWithholding, 2)
    ];
}

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
$pdf->Cell(100, 10, number_format($total1stQuarter, 2), 0, 1);
$pdf->SetXY(110.5,  175);
$pdf->Cell(100, 10, number_format($total2ndQuarter, 2), 0, 1);
$pdf->SetXY(141,  175);
$pdf->Cell(100, 10, number_format($total3rdQuarter, 2), 0, 1);
$pdf->SetXY(161.5, 175);
$pdf->Cell(100, 10, number_format($totalAmount, 2), 0, 1);
$pdf->SetXY(190, 175);
$pdf->Cell(100, 10, number_format($totalWithholding, 2), 0, 1);


//SIGNATURE
$pdf->SetFont('Arial', '', size: 9);

$pdf->SetXY(70, 257);
$pdf->Cell(100, 10, $signature, 0, 1);

// Import Page 2 (if applicable)
if ($pdf->setSourceFile($pdfPath) >= 2) { // Check if a second page exists
    $pdf->AddPage('P', [216, 330]);
    $tplIdx2 = $pdf->importPage(2);
    $pdf->useTemplate($tplIdx2, 0, 0, 216);
}

// Output the modified PDF
$pdf->Output('I', 'BIR_2307_filled.pdf');
?>
