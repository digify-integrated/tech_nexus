<?php
// Include FPDF and FPDI (use the correct paths)
require_once('assets/libs/fpdf/fpdf.php');
require_once('assets/libs/fpdi/autoload.php');

    require_once 'config/config.php';
    require_once 'session.php';
    require_once 'model/database-model.php';
    require_once 'model/user-model.php';
    require_once 'model/system-model.php';
    require_once 'model/customer-model.php';
    require_once 'model/sales-proposal-model.php';
    require_once 'model/product-model.php';
    require_once 'model/product-subcategory-model.php';

    $databaseModel = new DatabaseModel();

    // Initialize system model
    $systemModel = new SystemModel();

    // Initialize sales proposal model
    $salesProposalModel = new SalesProposalModel($databaseModel);
    $productModel = new ProductModel($databaseModel);
    $productSubcategoryModel = new ProductSubcategoryModel($databaseModel);

    // Initialize customer model
    $customerModel = new CustomerModel($databaseModel);

if(isset($_GET['id'])){
        if(empty($_GET['id'])){
          header('location: sales-proposal-for-dr.php');
          exit;
        }
        
        $salesProposalID = $_GET['id'];

        $checkSalesProposalExist = $salesProposalModel->checkSalesProposalExist($salesProposalID);
        $total = $checkSalesProposalExist['total'] ?? 0;

        if($total == 0){
            header('location: 404.php');
            exit;
        }

        $salesProposalDetails = $salesProposalModel->getSalesProposal($salesProposalID); 
        $customerID = $salesProposalDetails['customer_id'];
        $comakerID = $salesProposalDetails['comaker_id'] ?? null;
        $productID = $salesProposalDetails['product_id'] ?? null;
        $productType = $salesProposalDetails['product_type'] ?? null;
        $salesProposalNumber = $salesProposalDetails['sales_proposal_number'] ?? null;
        $numberOfPayments = $salesProposalDetails['number_of_payments'] ?? null;
        $paymentFrequency = $salesProposalDetails['payment_frequency'] ?? null;
        $startDate = $salesProposalDetails['actual_start_date'] ?? null;
        $drNumber = $salesProposalDetails['dr_number'] ?? null;
        $releaseTo = $salesProposalDetails['release_to'] ?? null;
        $termLength = $salesProposalDetails['term_length'] ?? null;
        $termType = $salesProposalDetails['term_type'] ?? null;
        $salesProposalStatus = $salesProposalDetails['sales_proposal_status'] ?? null;
        $unitImage = $systemModel->checkImage($salesProposalDetails['unit_image'], 'default');
        $salesProposalStatusBadge = $salesProposalModel->getSalesProposalStatus($salesProposalStatus);
    
        $pricingComputationDetails = $salesProposalModel->getSalesProposalPricingComputation($salesProposalID);
        $downpayment = $pricingComputationDetails['downpayment'] ?? 0;
        $amountFinanced = $pricingComputationDetails['amount_financed'] ?? 0;
        $repaymentAmount = $pricingComputationDetails['repayment_amount'] ?? 0;
        $pnAmount = $repaymentAmount * $numberOfPayments;
    
        $otherChargesDetails = $salesProposalModel->getSalesProposalOtherCharges($salesProposalID);
        $insurancePremium = $otherChargesDetails['insurance_premium'] ?? 0;
        $handlingFee = $otherChargesDetails['handling_fee'] ?? 0;
        $transferFee = $otherChargesDetails['transfer_fee'] ?? 0;
        $transactionFee = $otherChargesDetails['transaction_fee'] ?? 0;
        $docStampTax = $otherChargesDetails['doc_stamp_tax'] ?? 0;
    
        $renewalAmountDetails = $salesProposalModel->getSalesProposalRenewalAmount($salesProposalID);
        $registrationSecondYear = $renewalAmountDetails['registration_second_year'] ?? 0;
        $registrationThirdYear = $renewalAmountDetails['registration_third_year'] ?? 0;
        $registrationFourthYear = $renewalAmountDetails['registration_fourth_year'] ?? 0;
        $totalRenewalFee = $registrationSecondYear + $registrationThirdYear + $registrationFourthYear;
    
        $insurancePremiumSecondYear = $renewalAmountDetails['insurance_premium_second_year'] ?? 0;
        $insurancePremiumThirdYear = $renewalAmountDetails['insurance_premium_third_year'] ?? 0;
        $insurancePremiumFourthYear = $renewalAmountDetails['insurance_premium_fourth_year'] ?? 0;
        $totalInsuranceFee = $registrationSecondYear + $registrationThirdYear + $registrationFourthYear;
    
        $totalCharges = $insurancePremium + $handlingFee + $transferFee + $transactionFee + $totalRenewalFee + $totalInsuranceFee + $docStampTax;
        
        $totalDeposit = $salesProposalModel->getSalesProposalAmountOfDepositTotal($salesProposalID);

        $totalPn = $pnAmount + $totalCharges;
    
        $amountInWords = new NumberFormatter("en", NumberFormatter::SPELLOUT);
    
        $customerDetails = $customerModel->getPersonalInformation($customerID);

        $customerName = strtoupper($customerDetails['file_as']) ?? null;
        $tin = strtoupper($customerDetails['tin'] ?? '');
    
        $comakerDetails = $customerModel->getPersonalInformation($comakerID);
        $comakerName = strtoupper($comakerDetails['file_as'] ?? null) ;    
    
        $customerPrimaryAddress = $customerModel->getCustomerPrimaryAddress($customerID);
        $customerAddress = $customerPrimaryAddress['address'] . ', ' . $customerPrimaryAddress['city_name'] . ', ' . $customerPrimaryAddress['state_name'] . ', ' . $customerPrimaryAddress['country_name'];
    
        $comakerPrimaryAddress = $customerModel->getCustomerPrimaryAddress($comakerID);
    
        if(!empty($comakerPrimaryAddress['address'])){
          $comakerAddress = $comakerPrimaryAddress['address'] . ', ' . $comakerPrimaryAddress['city_name'] . ', ' . $comakerPrimaryAddress['state_name'] . ', ' . $comakerPrimaryAddress['country_name'];
        }
        else{
          $comakerAddress = '';
        }
        
        $customerContactInformation = $customerModel->getCustomerPrimaryContactInformation($customerID);
        $customerMobile = !empty($customerContactInformation['mobile']) ? $customerContactInformation['mobile'] : '--';
        $customerTelephone = !empty($customerContactInformation['telephone']) ? $customerContactInformation['telephone'] : '--';
        $customerEmail = !empty($customerContactInformation['email']) ? $customerContactInformation['email'] : '--';
        
        $otherProductDetails = $salesProposalModel->getSalesProposalOtherProductDetails($salesProposalID);
        $productDescription = $otherProductDetails['product_description'] ?? null;
        $businessStyle = $otherProductDetails['business_style'] ?? null;

        if($productType == 'Unit'){
            $productDetails = $productModel->getProduct($productID);
            $productSubategoryID = $productDetails['product_subcategory_id'] ?? null;

            $productSubcategoryDetails = $productSubcategoryModel->getProductSubcategory($productSubategoryID);
            $productSubcategoryCode = $productSubcategoryDetails['product_subcategory_code'] ?? null;
            $productCategoryID = $productSubcategoryDetails['product_category_id'] ?? null;

            $stockNumber = str_replace($productSubcategoryCode, '', $productDetails['stock_number'] ?? null);
            $fullStockNumber = $productSubcategoryCode . $stockNumber;

            $stockNumber = $stockNumber;
            $engineNumber = $productDetails['engine_number'] ??  '--';
            $chassisNumber = $productDetails['chassis_number'] ??  '--';
            $plateNumber = $productDetails['plate_number'] ?? '--';
        }
        else if($productType == 'Refinancing'){
            $stockNumber = $salesProposalDetails['ref_stock_no'] ??  '--';
            $engineNumber = $salesProposalDetails['ref_engine_no'] ??  '--';
            $chassisNumber = $salesProposalDetails['ref_chassis_no'] ??  '--';
            $plateNumber = $salesProposalDetails['ref_plate_no'] ??  '--';
            $fullStockNumber ='';
        };

    }

use setasign\Fpdi\Fpdi;

// Create a new FPDI instance with 8.5 x 13 page size
$pdf = new Fpdi();
$pdf->AddPage('P', 'letter'); // Set to 8.5 x 13 inches

// Load the official BIR Form 2307 PDF
/*$pdfPath = 'assets/libs/sales-invoice.pdf';
$pdf->setSourceFile($pdfPath);

// Import Page 1
$tplIdx = $pdf->importPage(1);
$pdf->useTemplate($tplIdx, 0, 0, 216);*/

$pdf->SetFont('times', '', 10.5);

$pdf->SetXY(25,25);
$pdf->Cell(100,5, $customerName);

$pdf->SetXY(165,25);
$pdf->Cell(105,5, strtoupper(date('d-M-Y')));

$pdf->SetXY(15,31);
$pdf->Cell(153,5, $tin. 'TIN');

$pdf->SetXY(165,31);
$pdf->Cell(100,5, $termLength);

$pdf->SetXY(35, 38);
$address = strtoupper($customerAddress);

while ($pdf->GetStringWidth($address) > 165) {
    $address = substr($address, 0, -1);
}

$pdf->Cell(135, 5, $address . '...');

$pdf->SetXY(35, 44);
$pdf->Cell(150, 5, $businessStyle. 'BUSINESS STYLE');

drawChattelMortgageTable(
    $pdf,
    $fullStockNumber,
    $productDescription,
    $engineNumber,
    $chassisNumber,
    $plateNumber,
    $pnAmount
);


function drawChattelMortgageTable($pdf, $fullStockNumber, $productDescription, $engineNumber, $chassisNumber, $plateNumber, $pnAmount)
{
    $vat = ($pnAmount / 1.12) * 0.12;
    $net = $pnAmount - $vat;

    $colUnit   = 8;
    $colDesc   = 27;
    $colEngine    = 77;
    $colChassis   = 100;
    $colPlate     = 124;
    $colQty    = 144;
    $colUnitP  = 147;
    $colTotalP = 172;

    $baseY = 54;
    $rowY  = $baseY + 6;

    $pdf->SetFont('Arial', '', 8);

    $pdf->SetXY($colUnit, $rowY);
    $pdf->MultiCell(30, 4, $fullStockNumber, 0);

    $pdf->SetXY($colDesc, $rowY);
    $pdf->MultiCell(45, 4, $productDescription, 0);

    $pdf->SetXY($colEngine, $rowY);
    $pdf->MultiCell(18, 4, $engineNumber . 'Engine', 0);

    $pdf->SetXY($colChassis, $rowY);
    $pdf->MultiCell(20, 4, $chassisNumber . 'Chassis', 0);

    $pdf->SetXY($colPlate, $rowY);
    $pdf->MultiCell(15, 4, $plateNumber . 'Platessssssssss', 0);

    $pdf->SetXY($colQty, $rowY);
    $pdf->Cell(20, 5, '1', 0);

    $pdf->SetXY($colUnitP, $rowY);
    $pdf->Cell(25, 5, number_format($pnAmount, 2), 0, 0, 'R');

    $pdf->SetXY($colTotalP, $rowY);
    $pdf->Cell(25, 5, number_format($pnAmount, 2), 0, 0, 'R');

    // Totals
    $pdf->SetXY(30, 207);
    $pdf->Cell(25, 5, number_format($net, 2), 0, 0, 'R');

    $pdf->SetXY(170, 208);
    $pdf->Cell(25, 5, number_format($pnAmount, 2), 0, 0, 'R');

    $pdf->SetXY(30, 227);
    $pdf->Cell(25, 5, number_format($vat, 2), 0, 0, 'R');

    $pdf->SetXY(170, 221);
    $pdf->Cell(25, 5, number_format($net, 2), 0, 0, 'R');
}


// Your existing code for filling out fields (unchanged)

// Set font for text fields
$pdf->SetFont('Arial', '', size: 7.5);

// Output the modified PDF
$pdf->Output('I', 'BIR_2307_filled.pdf');
?>
