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
    require_once 'model/sales-proposal-model.php';
    require_once 'model/product-model.php';
    require_once 'model/id-type-model.php';

    // Initialize database model
    $databaseModel = new DatabaseModel();

    // Initialize system model
    $productModel = new ProductModel($databaseModel);
    $systemModel = new SystemModel();

    // Initialize sales proposal model
    $salesProposalModel = new SalesProposalModel($databaseModel);

    // Initialize customer model
    $customerModel = new CustomerModel($databaseModel);
    $idTypeModel = new IDTypeModel($databaseModel);

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
    
        $comakerDetails = $customerModel->getPersonalInformation($comakerID);
        $comakerName = strtoupper($comakerDetails['file_as']) ?? null;    
    
        $customerPrimaryID = $customerModel->getCustomerPrimaryContactIdentification($customerID);
        $customerIDTypeID = $customerPrimaryID['id_type_id'] ?? '';
        $customerIDTypeName = $idTypeModel->getIDType($customerIDTypeID)['id_type_name'] ?? '';
        $customerIDNumber = $customerPrimaryID['id_number'] ?? '';

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
        $yearModel = $otherProductDetails['year_model'] ??  '--';
        $crNo = $otherProductDetails['cr_no'] ??  '--';
        $mvFileNo = $otherProductDetails['mv_file_no'] ??  '--';
        $make = $otherProductDetails['make'] ??  '--';

        if($productType == 'Unit'){
          $productDetails = $productModel->getProduct($productID);
          $engineNumber = $productDetails['engine_number'] ?? null;
          $chassisNumber = $productDetails['chassis_number'] ?? null;
          $plateNumber = $productDetails['plate_number'] ?? null;

          $orcrNo = $productDetails['orcr_no'] ?? '';
          $receivedFrom = $productDetails['received_from'] ?? '';
          $receivedFromAddress = $productDetails['received_from_address'] ?? '';
          $receivedFromIDType = $productDetails['received_from_id_type'] ?? '';
          $receivedFromIDNumber = $productDetails['received_from_id_number'] ?? '';
          $receivedFromIDTypeName = $idTypeModel->getIDType($receivedFromIDType)['id_type_name'] ?? '';
          $unitDescription = $productDetails['unit_description'] ?? '';
          $orcrDate =  $systemModel->checkDate('empty', $productDetails['orcr_date'], '', 'm/d/Y', '');
          $orcrExpiryDate =  $systemModel->checkDate('empty', $productDetails['orcr_expiry_date'], '', 'm/d/Y', '');
        }
        else if($productType == 'Refinancing' || $productType == 'Brand New'){
          $orcrNo = $salesProposalDetails['orcr_no'] ?? '';
          $receivedFrom = $salesProposalDetails['received_from'] ?? '';
          $receivedFromAddress = $salesProposalDetails['received_from_address'] ?? '';
          $receivedFromIDType = $salesProposalDetails['received_from_id_type'] ?? '';
          $receivedFromIDTypeName = $idTypeModel->getIDType($receivedFromIDType)['id_type_name'] ?? '';
          $receivedFromIDNumber = $salesProposalDetails['received_from_id_number'] ?? '';
          $unitDescription = $salesProposalDetails['unit_description'] ?? '';
          $orcrDate =  $systemModel->checkDate('empty', $salesProposalDetails['orcr_date'], '', 'm/d/Y', '');
          $orcrExpiryDate =  $systemModel->checkDate('empty', $salesProposalDetails['orcr_expiry_date'], '', 'm/d/Y', '');
          $engineNumber = $salesProposalDetails['ref_engine_no'] ?? null;
          $chassisNumber = $salesProposalDetails['ref_chassis_no'] ?? null;
          $plateNumber = $salesProposalDetails['ref_plate_no'] ?? null;
        }

        $currentDateTime = new DateTime('now');
        $currentDayOfWeek = $currentDateTime->format('N'); // 1 (Monday) through 7 (Sunday)

        if ($currentDayOfWeek >= 6) {
            $currentDateTime->modify('next Monday');
        }

        $currentDate = $currentDateTime->format('F j, Y');

    }

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
    $pdf->SetFont('times', '', 10.5);
    $pdf->MultiCell(0, 0, '<b>DEED OF ABSOLUTE SALE</b>', 0, 'C', 0, 1, '', '', true, 0, true, true, 0);
    $pdf->Ln(5);
    $pdf->MultiCell(0, 0, '<b>KNOW ALL MEN BY THESE PRESENTS:</b>', 0, 'J', 0, 1, '', '', true, 0, true, true, 0);
    $pdf->Ln(5);
    #$pdf->MultiCell(0, 0, 'That I <b><u>'. strtoupper($receivedFrom) .'</u></b>, with postal at <b><u>'. strtoupper($receivedFromAddress) .'</u></b> for and in consideration the amount of <b><u>'. strtoupper($amountInWords->format($totalPn)) .'  (PHP '. number_format($totalPn, 2) .')</u></b> PESOS, Philippine Currency, receipt of which is hereby acknowledgement have sold, transferred, conveyed and by these presents do sell, transfer and convey unto <b><u>'. $customerName .'</u></b> with postal address at <b><u>'. strtoupper($customerAddress) .'</u></b> his/her successors and assigns that certain motor vehicle which is particularly described as follows:', 0, 'J', 0, 1, '', '', true, 0, true, true, 0);
    $pdf->MultiCell(0, 0, 'That I _______________________________________________________________________, with postal at ___________________________________________________________________ for and in consideration the amount of __________________  (PHP ___________) PESOS, Philippine Currency, receipt of which is hereby acknowledgement have sold, transferred, conveyed and by these presents do sell, transfer and convey unto _______________________________________________________________ with postal address at ______________________________________________________________________ his/her successors and assigns that certain motor vehicle which is particularly described as follows:', 0, 'J', 0, 1, '', '', true, 0, true, true, 0);
    $pdf->Ln(5);
    /*$pdf->Cell(20, 8, 'MAKE'  , 0, 0, 'L');
    $pdf->Cell(32, 8, ':      ' . $make , 0, 0, 'L');
    $pdf->Cell(40, 8, '       '  , 0, 0, 'L');
    $pdf->Cell(30, 8, 'CHASSIS NO.'  , 0, 0, 'L');
    $pdf->Cell(32, 8, ':      ' . $chassisNumber, 0, 0, 'L');
    $pdf->Cell(30, 8, '       '  , 0, 0, 'L');
    $pdf->Ln(5);
    $pdf->Cell(20, 8, 'SERIES'  , 0, 0, 'L');
    $pdf->Cell(32, 8, ':      ', 0, 0, 'L');
    $pdf->Cell(40, 8, '       '  , 0, 0, 'L');
    $pdf->Cell(30, 8, 'PLATE NO.'  , 0, 0, 'L');
    $pdf->Cell(32, 8, ':      ' . $plateNumber, 0, 0, 'L');
    $pdf->Cell(30, 8, '       '  , 0, 0, 'L');
    $pdf->Ln(5);
    $pdf->Cell(25, 8, 'ENGINE NO.'  , 0, 0, 'L');
    $pdf->Cell(32, 8, ':      ' . $engineNumber, 0, 0, 'L');
    $pdf->Cell(35, 8, '       '  , 0, 0, 'L');
    $pdf->Cell(30, 8, 'MV FILE NO.'  , 0, 0, 'L');
    $pdf->Cell(32, 8, ':      ' . $mvFileNo, 0, 0, 'L');
    $pdf->Cell(30, 8, '       '  , 0, 0, 'L');*/
    $pdf->Cell(20, 8, 'MAKE'  , 0, 0, 'L');
    $pdf->Cell(32, 8, ':      ' , 0, 0, 'L');
    $pdf->Cell(40, 8, '       '  , 0, 0, 'L');
    $pdf->Cell(30, 8, 'CHASSIS NO.'  , 0, 0, 'L');
    $pdf->Cell(32, 8, ':      ' , 0, 0, 'L');
    $pdf->Cell(30, 8, '       '  , 0, 0, 'L');
    $pdf->Ln(5);
    $pdf->Cell(20, 8, 'SERIES'  , 0, 0, 'L');
    $pdf->Cell(32, 8, ':      ', 0, 0, 'L');
    $pdf->Cell(40, 8, '       '  , 0, 0, 'L');
    $pdf->Cell(30, 8, 'PLATE NO.'  , 0, 0, 'L');
    $pdf->Cell(32, 8, ':      ' , 0, 0, 'L');
    $pdf->Cell(30, 8, '       '  , 0, 0, 'L');
    $pdf->Ln(5);
    $pdf->Cell(25, 8, 'ENGINE NO.'  , 0, 0, 'L');
    $pdf->Cell(32, 8, ':      ' , 0, 0, 'L');
    $pdf->Cell(35, 8, '       '  , 0, 0, 'L');
    $pdf->Cell(30, 8, 'MV FILE NO.'  , 0, 0, 'L');
    $pdf->Cell(32, 8, ':      ' , 0, 0, 'L');
    $pdf->Cell(30, 8, '       '  , 0, 0, 'L');
    $pdf->Ln(10);
    $pdf->MultiCell(0, 0, 'Of which the property said corporation is/l am the absolute owner:', 0, 'J', 0, 1, '', '', true, 0, true, true, 0);
    $pdf->Ln(5);
    $pdf->MultiCell(0, 0, 'That the buyer shall take and assume all civil and/or criminal liabilities in the use of said vehicle either for private or business use.', 0, 'J', 0, 1, '', '', true, 0, true, true, 0);
    $pdf->Ln(5);
    $pdf->MultiCell(0, 0, 'That the buyer has full knowledge of the condition of the vehicle upon purchase thereof, that the unit subject of sale is not covered with warranty and conveyed on AS-IS basis only.', 0, 'J', 0, 1, '', '', true, 0, true, true, 0);
    $pdf->Ln(5);
    $pdf->MultiCell(0, 0, 'That the seller is not liable for whatever unseen defects that may be discovered after execution of the Deed of Sale and delivery of unit.', 0, 'J', 0, 1, '', '', true, 0, true, true, 0);
    $pdf->Ln(5);
    $pdf->MultiCell(0, 0, 'IN WITNESS WHEREOF, I have here unto affixed my hand this______________________________ day of
    _________, 20_______ in______________________________________________, Philippines.', 0, 'J', 0, 1, '', '', true, 0, true, true, 0);
    $pdf->Ln(5);
    /*$pdf->Cell(90, 4, $customerName, 'B', 0 , 'C');
    $pdf->Cell(10, 4, '     ', 0, 0 , 'L');
    $pdf->Cell(90, 4, strtoupper($receivedFrom), 'B', 0, 'C');*/
    $pdf->Cell(90, 4, '', 'B', 0 , 'C');
    $pdf->Cell(10, 4, '     ', 0, 0 , 'L');
    $pdf->Cell(90, 4, '', 'B', 0, 'C');
    $pdf->Ln(5);
    $pdf->Cell(90, 8, 'VENDEE', 0, 0, 'C');
    $pdf->Cell(10, 4, '     ', 0, 0 , 'L');
    $pdf->Cell(90, 8, 'VENDOR', 0, 0, 'C');
    $pdf->Ln(10);
    $pdf->MultiCell(0, 0, 'SIGNED IN THE PRESENCE OF:', 0, 'C', 0, 1, '', '', true, 0, true, true, 0);
    $pdf->Ln(5);
    $pdf->Cell(90, 4, '', 'B', 0 , 'C');
    $pdf->Cell(10, 4, '     ', 0, 0 , 'L');
    $pdf->Cell(90, 4, '', 'B', 0, 'C');
    $pdf->Ln(10);
    $pdf->MultiCell(0, 0, '<b>ACKNOWLEDGEMENT</b>', 0, 'C', 0, 1, '', '', true, 0, true, true, 0);
    $pdf->Ln(0);
    $pdf->MultiCell(0, 0, 'REPUBLIC OF THE PHILIPPINES) ', 0, 'J', 0, 1, '', '', true, 0, true, true, 0);
    $pdf->Ln(3);
    $pdf->MultiCell(0, 0, '________________________) S.S.', 0, 'J', 0, 1, '', '', true, 0, true, true, 0);
    $pdf->Ln(3);
    $pdf->MultiCell(0, 0, 'BEFORE ME, a notary Public for and in the city of___________________________, personally appeared:', 0, 'J', 0, 1, '', '', true, 0, true, true, 0);
    $pdf->Ln(3);
    $pdf->SetFont('times', 'U', 10.5);
    $pdf->Cell(40, 8, 'Name', 0, 0, 'C');
    $pdf->SetFont('times', '', 10.5);
    $pdf->Cell(20, 4, '     ', 0, 0 , 'L');
    $pdf->SetFont('times', 'U', 10.5);
    $pdf->Cell(40, 8, 'Competent Proof of identity', 0, 0, 'C');
    $pdf->SetFont('times', '', 10.5);
    $pdf->Cell(20, 4, '     ', 0, 0 , 'L');
    $pdf->SetFont('times', 'U', 10.5);
    $pdf->Cell(40, 8, 'Date/Place Issued', 0, 0, 'C');
    $pdf->Ln(5);
    $pdf->SetFont('times', '', 10.5);
    #$pdf->Cell(40, 8, strtoupper($receivedFrom), 0, 0, 'C');
    $pdf->Cell(40, 8, '', 0, 0, 'C');
    $pdf->SetFont('times', '', 10.5);
    $pdf->Cell(20, 4, '     ', 0, 0 , 'L');
    #$pdf->Cell(40, 8, strtoupper($receivedFromIDTypeName), 0, 0, 'C');
    $pdf->Cell(40, 8, '', 0, 0, 'C');
    $pdf->SetFont('times', '', 10.5);
    $pdf->Cell(20, 4, '     ', 0, 0 , 'L');
    #$pdf->Cell(40, 8, strtoupper($receivedFromIDNumber), 0, 0, 'C');
    $pdf->Cell(40, 8, '', 0, 0, 'C');
    $pdf->SetFont('times', '', 10.5);
    $pdf->Ln(5);
    $pdf->SetFont('times', '', 10.5);
    #$pdf->Cell(40, 8, strtoupper($customerName), 0, 0, 'C');
    $pdf->Cell(40, 8, '', 0, 0, 'C');
    $pdf->SetFont('times', '', 10.5);
    $pdf->Cell(20, 4, '     ', 0, 0 , 'L');
    #$pdf->Cell(40, 8, strtoupper($customerIDTypeName), 0, 0, 'C');
    $pdf->Cell(40, 8, '', 0, 0, 'C');
    $pdf->SetFont('times', '', 10.5);
    $pdf->Cell(20, 4, '     ', 0, 0 , 'L');
    #$pdf->Cell(40, 8, strtoupper($customerIDNumber), 0, 0, 'C');
    $pdf->Cell(40, 8, '', 0, 0, 'C');
    $pdf->SetFont('times', '', 10.5);
    $pdf->Ln(15);
    $pdf->MultiCell(0, 0, 'Known to me and to me known to be the same persons who executed the foregoing instrument and acknowledgement to me that the same are their fee act and voluntary deed.', 0, 'J', 0, 1, '', '', true, 0, true, true, 0);
    $pdf->Ln(5);
    $pdf->MultiCell(0, 0, 'This instrument, consisting of (__) pages, including the page on which this acknowledgement is written has been signed on the left margin of each and every page thereof by the concerned parties and their witnesses, and seal with my notarial seal.', 0, 'J', 0, 1, '', '', true, 0, true, true, 0);
    $pdf->Ln(5);
    $pdf->MultiCell(0, 0, 'WITNESS MY HAND AND SEAL on this day of______________________20 ___at_____________ Notary Public', 0, 'J', 0, 1, '', '', true, 0, true, true, 0);
    $pdf->Ln(5);
    $pdf->MultiCell(0, 0, 'Doc. No. ______________', 0, 'J', 0, 1, '', '', true, 0, true, true, 0);
    $pdf->Ln(3);
    $pdf->MultiCell(0, 0, 'Page No. ______________', 0, 'J', 0, 1, '', '', true, 0, true, true, 0);
    $pdf->Ln(3);
    $pdf->MultiCell(0, 0, 'Book No. ______________', 0, 'J', 0, 1, '', '', true, 0, true, true, 0);
    $pdf->Ln(3);
    $pdf->MultiCell(0, 0, 'Series of ______________', 0, 'J', 0, 1, '', '', true, 0, true, true, 0);

    // Output the PDF to the browser
    $pdf->Output('deed-of-absolute-sale.pdf', 'I');
    ob_end_flush();
?>