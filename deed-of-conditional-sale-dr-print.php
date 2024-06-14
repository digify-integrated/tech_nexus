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
    require_once 'model/id-type-model.php';
    require_once 'model/company-model.php';
    require_once 'model/city-model.php';
    require_once 'model/state-model.php';
    require_once 'model/country-model.php';

    // Initialize database model
    $databaseModel = new DatabaseModel();

    // Initialize system model
    $productModel = new ProductModel($databaseModel);
    $companyModel = new CompanyModel($databaseModel);
    $systemModel = new SystemModel();
    $cityModel = new CityModel($databaseModel);
    $stateModel = new StateModel($databaseModel);
    $countryModel = new CountryModel($databaseModel);

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
        $termLength = $salesProposalDetails['term_length'] ?? null;
        $termType = $salesProposalDetails['term_type'] ?? null;
        $companyID = $salesProposalDetails['company_id'] ?? null;
        $salesProposalStatus = $salesProposalDetails['sales_proposal_status'] ?? null;
        $unitImage = $systemModel->checkImage($salesProposalDetails['unit_image'], 'default');
        $salesProposalStatusBadge = $salesProposalModel->getSalesProposalStatus($salesProposalStatus);
    
        $pricingComputationDetails = $salesProposalModel->getSalesProposalPricingComputation($salesProposalID);
        $downpayment = $pricingComputationDetails['downpayment'] ?? 0;
        $amountFinanced = $pricingComputationDetails['amount_financed'] ?? 0;
        $repaymentAmount = $pricingComputationDetails['repayment_amount'] ?? 0;
        $interestRate = $pricingComputationDetails['interest_rate']/ $termLength;
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

        $companyDetails = $companyModel->getCompany($companyID);
        
        $companyName = $companyDetails['company_name'] ?? '';
        $companyCityID = $companyDetails['city_id'] ?? '';
        $companyAddress = $companyDetails['address'] ?? '';

        $cityDetails = $cityModel->getCity($companyCityID);
        $companyCityName = $cityDetails['city_name'] ?? '';
        $stateID = $cityDetails['state_id'];

        $stateDetails = $stateModel->getState($stateID);
        $companyStateName = $stateDetails['state_name'] ?? '';
        $countryID = $stateDetails['country_id'] ?? '';

        $companyCountryName = $countryModel->getCountry($countryID)['country_name'] ?? '';

        $companyFullAddress = $companyAddress . ', ' . $companyCityName . ', ' . $companyStateName . ', ' . $companyCountryName;

        $currentDateTime = new DateTime('now');
        $currentDayOfWeek = $currentDateTime->format('N'); // 1 (Monday) through 7 (Sunday)

        if ($currentDayOfWeek >= 6) {
            $currentDateTime->modify('next Monday');
        }

        $currentMonth = $currentDateTime->format('F Y');
        $currentDay = $currentDateTime->format('d');
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
    $pdf->MultiCell(0, 0, '<b>DEED OF CONDITIONAL SALE</b>', 0, 'C', 0, 1, '', '', true, 0, true, true, 0);
    $pdf->Ln(5);
    $pdf->MultiCell(0, 0, '<b>KNOW ALL MEN BY THESE PRESENTS:</b>', 0, 'J', 0, 1, '', '', true, 0, true, true, 0);
    $pdf->Ln(5);
    $pdf->MultiCell(0, 0, 'This deed, made and entered into this <b><u>'. strtoupper(formatDay(date('d', strtotime($currentDay)))) .'</b></u> day of <b><u>'. strtoupper($currentMonth) .'</b></u> at the City of Cabanatuan, Philippines, by and between:', 0, 'J', 0, 1, '', '', true, 0, true, true, 0);
    $pdf->Ln(1);
    $pdf->MultiCell(0, 0, '<b><u>'. strtoupper($companyName) .'</u></b>.', 0, 'J', 0, 1, '', '', true, 0, true, true, 0);
    $pdf->Ln(5);
    $pdf->MultiCell(0, 0, 'a corporation duly organized and existing under the laws of the Philippines with principal office at <b><u>'. strtoupper($companyFullAddress) .'</u></b>, represented herein by its <b><u>'. $receivedFrom .'</u></b>, hereinafter referred to as the "SELLER"', 0, 'J', 0, 1, '', '', true, 0, true, true, 0);
    $pdf->Ln(1);
    $pdf->MultiCell(0, 0, 'and', 0, 'C', 0, 1, '', '', true, 0, true, true, 0);
    $pdf->Ln(1);
    $pdf->MultiCell(0, 0, '<b><u>'. $customerName .'</u></b>, of legal age, Filipino citizen, married, with postal address at <b><u>'. strtoupper($customerAddress) .'</u></b>, hereinafter referred to as the "BUYER"', 0, 'J', 0, 1, '', '', true, 0, true, true, 0);
    $pdf->Ln(5);
    $pdf->MultiCell(0, 0, '<b>WITHNESSETH</b>', 0, 'C', 0, 1, '', '', true, 0, true, true, 0);
    $pdf->Ln(5);
    $pdf->MultiCell(0, 0, 'That the SELLER is the absolute owner of the following described vehicle:', 0, 'J', 0, 1, '', '', true, 0, true, true, 0);
    $pdf->Ln(0);
    $pdf->Cell(30, 8, '       '  , 0, 0, 'L');
    $pdf->Cell(40, 8, 'Make and Type'  , 0, 0, 'L');
    $pdf->Cell(32, 8, ':      ' . $make, 0, 0, 'L');
    $pdf->Ln(5);
    $pdf->Cell(30, 8, '       '  , 0, 0, 'L');
    $pdf->Cell(40, 8, 'Motor No.'  , 0, 0, 'L');
    $pdf->Cell(32, 8, ':      ' . $engineNumber, 0, 0, 'L');
    $pdf->Ln(5);
    $pdf->Cell(30, 8, '       '  , 0, 0, 'L');
    $pdf->Cell(40, 8, 'Chassis No.'  , 0, 0, 'L');
    $pdf->Cell(32, 8, ':      ' . $chassisNumber, 0, 0, 'L');
    $pdf->Ln(5);
    $pdf->Cell(30, 8, '       '  , 0, 0, 'L');
    $pdf->Cell(40, 8, 'Plate No.'  , 0, 0, 'L');
    $pdf->Cell(32, 8, ':      ' . $plateNumber, 0, 0, 'L');
    $pdf->Ln(10);
    $pdf->MultiCell(0, 0, 'covered by Certificate of Registration No . <b><u>'. strtoupper($orcrNo) .'</b></u> issued by the ______________________________;', 0, 'J', 0, 1, '', '', true, 0, true, true, 0);
    $pdf->Ln(5);
    $pdf->MultiCell(0, 0, 'That with the SELLER'. "'" .'s consent, the BUYER/ desire'. "'" .'s to acquire the said motor vehicle, subject to the following terms and conditions:', 0, 'J', 0, 1, '', '', true, 0, true, true, 0);
    $pdf->Ln(5);
    $pdf->SetFont('times', '', 9.5);
    $pdf->MultiCell(0, 0, '1. That the total purchase price is <b><u>'. strtoupper($amountInWords->format($totalPn)) .'
    (P '. number_format($totalPn, 2) .')</u></b> PESOS, Philippine currency, buyer'. "'" .'s making a down payment of  <b><u>'. strtoupper($amountInWords->format($downpayment)) .'
    (P '. number_format($downpayment, 2) .')</u></b> PESOS, Philippine currency, upon signing of this deed, receipt of which is hereby acknowledged by SELLER and the balance payable in equal installment for a period of <b><u>'. strtoupper($amountInWords->format($termLength)) .' ('. number_format($termLength) .')</u></b>  months at the rate of <b><u>'. strtoupper($amountInWords->format($repaymentAmount)) .'
    (P '. number_format($repaymentAmount, 2) .')</u></b> per <b><u>MONTH</b></u>, payable every <b><u>'. strtoupper(formatDay(date('d', strtotime($startDate)))) .'</b></u>, day of the month starting on <b><u>'. strtoupper(date('F d, Y', strtotime($startDate))) .'</b></u> until fully and completely paid;
    ', 0, 'J', 0, 1, '', '', true, 0, true, true, 0);
    $pdf->Ln(5);
    $pdf->MultiCell(0, 0, '2. That the unpaid balance of the purchase price shall be evidenced by a Promissory Note executed by the BUYER and delivered to the SELLER;', 0, 'J', 0, 1, '', '', true, 0, true, true, 0);
    $pdf->Ln(5);
    $pdf->MultiCell(0, 0, '3. If payment is not received by the SELLER on payment due date, a late payment charge equivalent to <b><u>'. strtoupper($amountInWords->format($interestRate)) .' PERCENT ('. $interestRate .'%)</u></b> per month based on the amount unpaid/in arrears shall be collected until fully paid;', 0, 'J', 0, 1, '', '', true, 0, true, true, 0);
    $pdf->Ln(5);
    $pdf->MultiCell(0, 0, '4. In case of full payment prior to full term by the BUYER, a reasonable prepayment discount shall be granted to BUYER;', 0, 'J', 0, 1, '', '', true, 0, true, true, 0);
    $pdf->Ln(5);
    $pdf->MultiCell(0, 0, '5. SELLER specially disclaims any warranties as to the physical and mechanical condition of the motor vehicle. BUYER acknowledges inspecting the motor vehicle and is purchasing it "as is;"', 0, 'J', 0, 1, '', '', true, 0, true, true, 0);
    $pdf->Ln(5);
    $pdf->MultiCell(0, 0, '6. The BUYER cannot return or exchange the vehicle delivered after acceptance thereof, for any reason whatsoever, without the consent of the SELLER;', 0, 'J', 0, 1, '', '', true, 0, true, true, 0);
    $pdf->Ln(5);
    $pdf->MultiCell(0, 0, '7. In case of breach by the BUYER of any of the terms and conditions of this deed, particularly if there is a default in payment of any of the installments, the SELLER may immediately repossess the unit and all previous payments made by the BUYER will be forfeited in favor of the SELLER, which shall be treated as rentals or compensation for the use of the subject'. "'" .'s vehicle by the BUYER. The BUYER also agrees to pay thirty percent (30%) of the total amount due as and for attorney'. "'" .'s fees and further the sum of twenty percent (20%) as liquidated damages, in addition to costs and other expenses of litigation,', 0, 'J', 0, 1, '', '', true, 0, true, true, 0);
    $pdf->Ln(5);
    $pdf->MultiCell(0, 0, '8. As regards the right to repossess the unit, SELLER may initially demand delivery of the subject vehicle at any stated address, free of all charges, and should the BUYER fail to deliver, the SELLER is hereby authorized to take possession of the unit wherever it may be found and have the same brought to its offices, warehouses or branches and all expenses incurred for locating and bringing the chattel to the SELLER shall be for the account of the BUYER;', 0, 'J', 0, 1, '', '', true, 0, true, true, 0);
    $pdf->Ln(5);
    $pdf->MultiCell(0, 0, '9. The BUYER expressly release and waive all claims and actions for trespass and/or damages against the SELLER, its agents, personnel, employees or duly authorized representatives, arising in and about such action of repossession;', 0, 'J', 0, 1, '', '', true, 0, true, true, 0);
    $pdf->Ln(5);
    $pdf->MultiCell(0, 0, '10. Loss or destruction of said motor vehicle for whatever cause including act of God or force majeure will not extinguish the BUYER'. "'" .'s liability and obligations set forth under this deed;', 0, 'J', 0, 1, '', '', true, 0, true, true, 0);

    // Output the PDF to the browser
    $pdf->Output('deed-of-conditional-sale.pdf', 'I');
    ob_end_flush();

    function formatDay($day) {
      if ($day % 10 == 1 && $day != 11) {
          return $day . 'st';
      } elseif ($day % 10 == 2 && $day != 12) {
          return $day . 'nd';
      } elseif ($day % 10 == 3 && $day != 13) {
          return $day . 'rd';
      } else {
          return $day . 'th';
      }
  }
?>