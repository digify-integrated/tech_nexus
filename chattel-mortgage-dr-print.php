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
    require_once 'model/body-type-model.php';
    require_once 'model/product-model.php';
    require_once 'model/id-type-model.php';

    // Initialize database model
    $databaseModel = new DatabaseModel();

    // Initialize system model
    $systemModel = new SystemModel();

    // Initialize sales proposal model
    $productModel = new ProductModel($databaseModel);
    $bodyTypeModel = new BodyTypeModel($databaseModel);
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
        $yearModel = $otherProductDetails['year_model'] ??  '--';
        $crNo = $otherProductDetails['cr_no'] ??  '--';
        $mvFileNo = $otherProductDetails['mv_file_no'] ??  '--';
        $make = $otherProductDetails['make'] ??  '--';

        if($productType == 'Unit'){
            $productDetails = $productModel->getProduct($productID);
            $engineNumber = $productDetails['engine_number'] ?? null;
            $chassisNumber = $productDetails['chassis_number'] ?? null;
            $plateNumber = $productDetails['plate_number'] ?? null;
            $bodyTypeID = $productDetails['body_type_id'];
                
            $getBodyType = $bodyTypeModel->getBodyType($bodyTypeID);
            $bodyTypeName = $getBodyType['body_type_name'] ?? null;
  
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
            $bodyTypeName = '';
          }

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

    $chattelMortgageTable = generateChattelMortgageTable($make, $engineNumber, $chassisNumber, $plateNumber, $yearModel, $mvFileNo, $bodyTypeName);

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
    $pdf->MultiCell(0, 0, '<b>CHATTEL MORTGAGE</b>', 0, 'C', 0, 1, '', '', true, 0, true, true, 0);
    $pdf->Ln(5);
    $pdf->MultiCell(0, 0, '<b>KNOW ALL MEN BY THESE PRESENTS:</b>', 0, 'J', 0, 1, '', '', true, 0, true, true, 0);
    $pdf->Ln(5);
    $pdf->MultiCell(0, 0, 'I, <b><u>'. $customerName .'</u></b> of legal age, single/married to __________________________________________________________________with postal address at <b><u>'. strtoupper($customerAddress) .'</u></b>
    hereinafter known as the "MORTGAGOR"', 0, 'J', 0, 1, '', '', true, 0, true, true, 0);
    $pdf->Ln(2);
    $pdf->MultiCell(0, 0, 'and', 0, 'C', 0, 1, '', '', true, 0, true, true, 0);
    $pdf->Ln(2);
    $pdf->MultiCell(0, 0, '<b><u>'. $receivedFrom .'</u></b>, a corporation duly organized and existing under Philippine Laws, with principal office at KM 112, Maharlika Highway, Cabanatuan City, herein represented by <b><u>'. $receivedFromAddress .'</u></b>,
    hereinafter referred to as "MORTGAGEE"', 0, 'J', 0, 1, '', '', true, 0, true, true, 0);
    $pdf->Ln(5);
    $pdf->MultiCell(0, 0, '<b>WITHNESSETH</b>', 0, 'C', 0, 1, '', '', true, 0, true, true, 0);
    $pdf->Ln(5);
    $pdf->MultiCell(0, 0, 'That the MORTGAGOR is indepted unto the MORTGAGEE in the sum of <b><u>'. strtoupper($amountInWords->format($totalPn)) .'  (PHP '. number_format($totalPn, 2) .')</u></b>, Philippine Currency, receipt of which is acknowledged by the MORTGAGOR upon the signing of this instrument, payable within a period of <b><u>'. $termLength .' '. strtoupper($termType) .'</u></b>, with interest thereon at the rate of (_________) % per annum;', 0, 'J', 0, 1, '', '', true, 0, true, true, 0);
    $pdf->Ln(5);
    $pdf->MultiCell(0, 0, 'That for, and consideration of, this indebtedness, and to assure the performance of said obligation to pay, the MORTGAGOR hereby conveys by way of CHATTEL MORTGAGE unto the MORTGAGEE, his heirs and assigns, the following personality now in the possession of said MORTGAGOR', 0, 'J', 0, 1, '', '', true, 0, true, true, 0);
    $pdf->Ln(6);
    $pdf->writeHTML($chattelMortgageTable, true, false, true, false, '');
    $pdf->Ln(5);
    $pdf->MultiCell(0, 0, 'That the condition of this obligation is that should the MORTGAGOR perform the obligation to pay the hereinabove cited indebtedness of ____________________________________ (PHP______________) together with accrued interest thereon, this chattel mortgage shall at once become null and void and of no effect whatsoever, otherwise, it shall remain in full force and effect.', 0, 'J', 0, 1, '', '', true, 0, true, true, 0);
    $pdf->Ln(5);
    $pdf->MultiCell(0, 0, 'IN WITNESS WHEREOF, the parties have hereunto set their hands, this________, day of_______ 2016______ at ___________________________ Philippines.', 0, 'J', 0, 1, '', '', true, 0, true, true, 0);
    $pdf->Ln(5);
    $pdf->Cell(90, 4, $customerName, 'B', 0 , 'C');
    $pdf->Cell(10, 4, '     ', 0, 0 , 'L');
    $pdf->Cell(90, 4, $receivedFrom, 'B', 0, 'C');
    $pdf->Ln(5);
    $pdf->Cell(90, 8, 'MORTGAGOR', 0, 0, 'C');
    $pdf->Cell(10, 4, '     ', 0, 0 , 'L');
    $pdf->Cell(90, 8, 'MORTGAGEE', 0, 0, 'C');
    $pdf->Ln(10);
    $pdf->MultiCell(0, 0, 'With marital consent:', 0, 'J', 0, 1, '', '', true, 0, true, true, 0);
    $pdf->Ln(5);
    $pdf->Cell(90, 4, '', 'B', 0 , 'C');
    $pdf->Ln(15);

    $pdf->MultiCell(0, 0, '<b>SIGNED IN THE PRESENCE OF:</b>', 0, 'C', 0, 1, '', '', true, 0, true, true, 0);
    $pdf->Ln(8);
    $pdf->Cell(80, 4, '', 'B', 0, 'C', 0, '', 1);
    $pdf->Cell(25, 4, '', 0, 0, 'C');
    $pdf->Cell(80, 4, '', 'B', 0, 'C', 0, '', 1);
    $pdf->Cell(25, 4, '', 0, 0, 'C');

   

    
    // Output the PDF to the browser
    $pdf->Output('chattel-mortgage.pdf', 'I');
    ob_end_flush();

    function generateChattelMortgageTable($make, $engineNumber, $chassisNumber, $plateNumber, $yearModel, $mvFileNo, $bodyTypeName){
        $response = '<table border="0.5" width="100%" cellpadding="2" >
                        <tbody>
                        <tr>
                            <td>Make</td>
                            <td>'. $make .'</td>
                            <td>Motor No.</td>
                            <td>'. $engineNumber .'</td>
                        </tr>
                        <tr>
                            <td>Series</td>
                            <td></td>
                            <td>Serial/Chassis No.</td>
                            <td>'. $chassisNumber .'</td>
                        </tr>
                        <tr>
                            <td>Type of Body</td>
                            <td>'. $bodyTypeName .'</td>
                            <td>Plate No.</td>
                            <td>'. $plateNumber .'</td>
                        </tr>
                        <tr>
                            <td>Year Model</td>
                            <td>'. $yearModel .'</td>
                            <td>File No.</td>
                            <td>'. $mvFileNo .'</td>
                        </tr>
                    </tbody>
            </table>';

        return $response;
    }
?>