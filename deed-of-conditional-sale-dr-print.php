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

    // Initialize database model
    $databaseModel = new DatabaseModel();

    // Initialize system model
    $systemModel = new SystemModel();

    // Initialize sales proposal model
    $salesProposalModel = new SalesProposalModel($databaseModel);

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

        if(!empty($releaseTo)){
          $customerName = strtoupper($releaseTo) ?? null;
        }
        else{
          $customerName = strtoupper($customerDetails['file_as']) ?? null;
        }
    
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
    $pdf->MultiCell(0, 0, 'This deed, made and entered into this _______ day of ____________ at the City of Cabanatuan, Philippines, by and between:', 0, 'J', 0, 1, '', '', true, 0, true, true, 0);
    $pdf->Ln(5);
    $pdf->MultiCell(0, 0, '______________________________________________________________________________________________.', 0, 'J', 0, 1, '', '', true, 0, true, true, 0);
    $pdf->Ln(5);
    $pdf->MultiCell(0, 0, 'a corporation duly organized and existing under the laws of the Philippines with principal office at ________________________________________________________________, represented herein by its ________________________________________________________________, hereinafter referred to as the "SELLER"', 0, 'J', 0, 1, '', '', true, 0, true, true, 0);
    $pdf->Ln(5);
    $pdf->MultiCell(0, 0, 'and', 0, 'C', 0, 1, '', '', true, 0, true, true, 0);
    $pdf->Ln(5);
    $pdf->MultiCell(0, 0, '_________________________________________, of legal age, Filipino citizen, married, with postal address at _________________________________________________________, hereinafter referred to as the "BUYER"', 0, 'J', 0, 1, '', '', true, 0, true, true, 0);
    $pdf->Ln(5);
    $pdf->MultiCell(0, 0, '<b>WITHNESSETH</b>', 0, 'C', 0, 1, '', '', true, 0, true, true, 0);
    $pdf->Ln(5);
    $pdf->MultiCell(0, 0, 'That the SELLER is the absolute owner of the following described vehicle:', 0, 'J', 0, 1, '', '', true, 0, true, true, 0);
    $pdf->Ln(0);
    $pdf->Cell(30, 8, '       '  , 0, 0, 'L');
    $pdf->Cell(40, 8, 'Make and Type'  , 0, 0, 'L');
    $pdf->Cell(32, 8, ':      ___________________________________', 0, 0, 'L');
    $pdf->Ln(5);
    $pdf->Cell(30, 8, '       '  , 0, 0, 'L');
    $pdf->Cell(40, 8, 'Motor No.'  , 0, 0, 'L');
    $pdf->Cell(32, 8, ':      ___________________________________', 0, 0, 'L');
    $pdf->Ln(5);
    $pdf->Cell(30, 8, '       '  , 0, 0, 'L');
    $pdf->Cell(40, 8, 'Chassis No.'  , 0, 0, 'L');
    $pdf->Cell(32, 8, ':      ___________________________________', 0, 0, 'L');
    $pdf->Ln(5);
    $pdf->Cell(30, 8, '       '  , 0, 0, 'L');
    $pdf->Cell(40, 8, 'Plate No.'  , 0, 0, 'L');
    $pdf->Cell(32, 8, ':      ___________________________________', 0, 0, 'L');
    $pdf->Ln(10);
    $pdf->MultiCell(0, 0, 'covered by Certificate of Registration No.______________________ issued by the______________________________;', 0, 'J', 0, 1, '', '', true, 0, true, true, 0);
    $pdf->Ln(5);
    $pdf->MultiCell(0, 0, 'That with the SELLER'. "'" .'s consent, the BUYER/ desire'. "'" .'s to acquire the said motor vehicle, subject to the following terms and conditions:', 0, 'J', 0, 1, '', '', true, 0, true, true, 0);
    $pdf->Ln(5);
    $pdf->MultiCell(0, 0, '1. That the total purchase price is_______________________________________________________________________
    (P___________________) PESOS, Philippine currency, buyer'. "'" .'s making a down payment of_________________________ ____________________________(P_____________________) PESOS, Philippine currency, upon signing of this deed, receipt of which is hereby acknowledged by SELLER and the balance payable in equal installment for a period of _______ _______________(        ) months at the rate of ____________________________________________________________ (P__________________) per__________________, payable every_________, day of the month starting on___________ _____________________________until fully and completely paid;
    ', 0, 'J', 0, 1, '', '', true, 0, true, true, 0);
    $pdf->Ln(5);
    $pdf->MultiCell(0, 0, '2. That the unpaid balance of the purchase price shall be evidenced by a Promissory Note executed by the BUYER and delivered to the SELLER;', 0, 'J', 0, 1, '', '', true, 0, true, true, 0);
    $pdf->Ln(5);
    $pdf->MultiCell(0, 0, '3. If payment is not received by the SELLER on payment due date, a late payment charge equivalent to ________________ (_________) per month based on the amount unpaid/in arrears shall be collected until fully paid;', 0, 'J', 0, 1, '', '', true, 0, true, true, 0);
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
?>