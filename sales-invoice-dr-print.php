<?php
    error_reporting(E_ALL);
    ini_set('display_errors', 1);

    // Load TCPDF library
    require('assets/libs/tcpdf2/tcpdf.php');

    // Load required files
    require_once 'config/config.php';
    require_once 'session.php';
    require_once 'model/database-model.php';
    require_once 'model/system-model.php';
    require_once 'model/customer-model.php';
    require_once 'model/sales-proposal-model.php';
    require_once 'model/product-model.php';
    require_once 'model/product-subcategory-model.php';

    // Initialize database model
    $databaseModel = new DatabaseModel();

    // Initialize system model
    $systemModel = new SystemModel();

    // Initialize sales proposal model
    $salesProposalModel = new SalesProposalModel($databaseModel);

    // Initialize customer model
    $customerModel = new CustomerModel($databaseModel);
    $productModel = new ProductModel($databaseModel);
    $productSubcategoryModel = new ProductSubcategoryModel($databaseModel);

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
        $comakerName = strtoupper($comakerDetails['file_as']) ?? null;    
    
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
        }
    }

    $chattelMortgageTable = generateChattelMortgageTable($fullStockNumber, $productDescription, $engineNumber, $chassisNumber, $plateNumber, $pnAmount);

    ob_start();

    // Create TCPDF instance
    $pdf = new TCPDF('P', 'mm', array(215.9, 330.2), true, 'UTF-8', false);

    // Disable header and footer
    $pdf->setPrintHeader(false);
    $pdf->setPrintFooter(false);

    // Set PDF metadata
    $pdf->SetCreator('CGMI');
    $pdf->SetAuthor('CGMI');
    $pdf->SetTitle('Sales Proposal');
    $pdf->SetSubject('Sales Proposal');

    // Set margins and auto page break
    $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
    $pdf->SetMargins(15, 15, 15);
    $pdf->SetHeaderMargin(5);
    $pdf->SetFooterMargin(10);
    $pdf->SetAutoPageBreak(TRUE, 15);

    // Add a page
    $pdf->AddPage();

    // Add content
    $pdf->SetFont('times', 'B', 10.5);
    $pdf->Cell(155, 8, 'CHRISTIAN GENERAL MOTORS INCORPORATED'  , 0, 0, 'L');
    $pdf->Cell(32, 8, 'SALES INVOICE', 0, 0, 'L');
    $pdf->Ln(5);
    $pdf->SetFont('times', '', 9);
    $pdf->Cell(155, 8, 'Sta. Rosa Road, San Jose City of Tarlac 2300, Tarlac, Philippines'  , 0, 0, 'L');
    $pdf->Cell(32, 8, 'No.', 0, 0, 'L');
    $pdf->Ln(5);
    $pdf->Cell(155, 8, 'VAT Reg. TIN: 479-157-216-00001'  , 0, 0, 'L');
    $pdf->SetFont('times', '', 10.5);
    $pdf->Ln(10);
    $pdf->Cell(20, 8, 'SOLD TO:'  , 0, 0, 'L', 0, '', 0, false, 'T', 'B');
    $pdf->Cell(105, 8, $customerName  , 'B', 0, 'L', 0, '', 0, false, 'T', 'B');
    $pdf->Cell(5, 8, '', 0, 0, 'L');
    $pdf->Cell(20, 8, 'Date:'  , 0, 0, 'L', 0, '', 0, false, 'T', 'B');
    $pdf->Cell(36, 8, strtoupper(date('M d, Y')), 'B', 0, 'L', 0, '', 0, false, 'T', 'B');
    $pdf->Ln(8);
    $pdf->Cell(20, 8, 'TIN:'  , 0, 0, 'L', 0, '', 0, false, 'T', 'B');
    $pdf->Cell(105, 8, $tin, 'B', 0, 'L', 0, '', 0, false, 'T', 'B');
    $pdf->Cell(5, 8, '', 0, 0, 'L');
    $pdf->Cell(20, 8, 'Terms:'  , 0, 0, 'L', 0, '', 0, false, 'T', 'B');
    $pdf->Cell(36, 8, '', 'B', 0, 'L', 0, '', 0, false, 'T', 'B');
    $pdf->Ln(8);
    $pdf->Cell(30, 8, 'ADDRESS:'  , 0, 0, 'L', 0, '', 0, false, 'T', 'B');
    $pdf->Cell(156, 8, strtoupper($customerAddress), 'B', 0, 'L', 0, '', 0, false, 'T', 'B');
    $pdf->Ln(8);
    $pdf->Cell(30, 8, 'Business Style:'  , 0, 0, 'L', 0, '', 0, false, 'T', 'B');
    $pdf->Cell(156, 8, $businessStyle, 'B', 0, 'L', 0, '', 0, false, 'T', 'B');
    $pdf->Ln(15);
    $pdf->SetFont('times', '', 9);
    $pdf->writeHTML($chattelMortgageTable, true, false, true, false, '');
    $pdf->Ln(0);
    $pdf->SetFont('times', '', 8);
    $pdf->Cell(120, 8, 'Permanent Accreditation No.: 23BMP20240000000002'  , 0, 0, 'L');
    $pdf->Cell(100, 8, '_________________________________________'  , 0, 0, 'L');
    $pdf->Ln(5);
    $pdf->Cell(120, 8, 'Date issued: Jaunary 30, 2024.'  , 0, 0, 'L');
    $pdf->SetFont('times', '', 9);
    $pdf->Cell(62, 8, 'Cashier/Authorized Representative'  , 0, 0, 'C');
    $pdf->Ln(5);
    $pdf->SetFont('times', '', 8);
    $pdf->Cell(120, 8, '10 Bklts. (50x2) 0001 - 0500'  , 0, 0, 'L');
    $pdf->Ln(5);
    $pdf->Cell(120, 8, 'BIR Authority to Print No. OCN:17AAU20240000002322'  , 0, 0, 'L');
    $pdf->Ln(5);
    $pdf->Cell(120, 8, 'Date Issued: April 26, 2024'  , 0, 0, 'L');
    $pdf->Ln(5);
    

    // Output the PDF to the browser
    $pdf->Output('sales-invoice.pdf', 'I');
    ob_end_flush();

    function generateChattelMortgageTable($fullStockNumber, $productDescription, $engineNumber, $chassisNumber, $plateNumber, $pnAmount){
        
        $vat = ($pnAmount / 1.12) * .12;
        $total = $pnAmount - $vat;
        
        $response = '<table border="0.5" width="100%" cellpadding="2" align="center">
                        <tbody>
                        <tr>
                            <td width="12.5%">Unit Number</td>
                            <td width="22%">Description</td>
                            <td width="12.5%">Engine No</td>
                            <td width="12.5%">Chassis No</td>
                            <td width="12.5%">Plate No</td>
                            <td width="6.5%">Qty</td>
                            <td width="11%">Unit Price</td>
                            <td width="11%">Total Price</td>
                        </tr>
                        <tr>
                            <td>'. $fullStockNumber .'<br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/></td>
                            <td>'. $productDescription .'</td>
                            <td>'. $engineNumber .'</td>
                            <td>'. $chassisNumber .'</td>
                            <td>'. $plateNumber .'</td>
                            <td>1</td>
                            <td>'. number_format($pnAmount, 2) .'</td>
                            <td>'. number_format($pnAmount, 2) .'</td>
                        </tr>
                        <tr align="left">
                            <td colspan="2">VATable Sales</td>
                            <td colspan="2">'. number_format($total, 2) .'</td>
                            <td colspan="3">Total Sales (VAT Inclusive)</td>
                            <td>'. number_format($pnAmount, 2) .'</td>
                        </tr>
                        <tr align="left">
                            <td colspan="2">VAT-Exempt Sales</td>
                            <td colspan="2">0.00</td>
                            <td colspan="3">Less: VAT</td>
                            <td>'. number_format($vat, 2) .'</td>
                        </tr>
                        <tr align="left">
                            <td colspan="2">Zero Rated Sales</td>
                            <td colspan="2">0.00</td>
                            <td colspan="3">Amount: Net of VAT</td>
                            <td>'. number_format($total, 2) .'</td>
                        </tr>
                        <tr align="left">
                            <td colspan="2">VAT AMOUNT</td>
                            <td colspan="2">'. number_format($vat, 2) .'</td>
                            <td colspan="3">Document Reference No</td>
                            <td></td>
                        </tr>
                    </tbody>
            </table>';

        return $response;
    }
?>