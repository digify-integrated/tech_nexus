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
    require_once 'model/id-type-model.php';

    // Initialize database model
    $databaseModel = new DatabaseModel();

    // Initialize system model
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
        $insurancePremium = $otherChargesDetails['insurance_premium_subtotal'] ?? 0;
        $handlingFee = $otherChargesDetails['handling_fee_subtotal'] ?? 0;
        $transferFee = $otherChargesDetails['transfer_fee_subtotal'] ?? 0;
        $transactionFee = $otherChargesDetails['transaction_fee_subtotal'] ?? 0;
        $registrationFee = $otherChargesDetails['registration_fee'] ?? 0;
        $docStampTax = $otherChargesDetails['doc_stamp_tax_subtotal'] ?? 0;
    
        $renewalAmountDetails = $salesProposalModel->getSalesProposalRenewalAmount($salesProposalID);
        $registrationSecondYear = $renewalAmountDetails['registration_second_year'] ?? 0;
        $registrationThirdYear = $renewalAmountDetails['registration_third_year'] ?? 0;
        $registrationFourthYear = $renewalAmountDetails['registration_fourth_year'] ?? 0;
        $totalRenewalFee = $registrationSecondYear + $registrationThirdYear + $registrationFourthYear;
    
        $insurancePremiumSecondYear = $renewalAmountDetails['insurance_premium_second_year'] ?? 0;
        $insurancePremiumThirdYear = $renewalAmountDetails['insurance_premium_third_year'] ?? 0;
        $insurancePremiumFourthYear = $renewalAmountDetails['insurance_premium_fourth_year'] ?? 0;
        $totalInsuranceFee = $registrationSecondYear + $registrationThirdYear + $registrationFourthYear;
    
        $totalCharges = $insurancePremium + $handlingFee + $transferFee + $registrationFee + $transactionFee + $docStampTax;
        
        $totalDeposit = $salesProposalModel->getSalesProposalAmountOfDepositTotal($salesProposalID);

        $totalPn = $pnAmount + $totalCharges;
    
        $amountInWords = new NumberFormatter("en", NumberFormatter::SPELLOUT);
    
        $customerDetails = $customerModel->getPersonalInformation($customerID);

        $customerName = strtoupper($customerDetails['file_as']) ?? null;
    
        $comakerDetails = $customerModel->getPersonalInformation($comakerID);
        $comakerName = strtoupper($comakerDetails['file_as']) ?? null;    
    
        $customerPrimaryID = $customerModel->getCustomerPrimaryContactIdentification($customerID);
        $customeridTypeID = $customerPrimaryID['id_type_name'] ?? '';
        $customeridNumber = $customerPrimaryID['id_number'] ?? '';

        $customerPrimaryAddress = $customerModel->getCustomerPrimaryAddress($customerID);
        $customerAddress = $customerPrimaryAddress['address'] . ', ' . $customerPrimaryAddress['city_name'] . ', ' . $customerPrimaryAddress['state_name'] . ', ' . $customerPrimaryAddress['country_name'];
    
        $comakerPrimaryAddress = $customerModel->getCustomerPrimaryAddress($comakerID);
    
        $comakerPrimaryID = $customerModel->getCustomerPrimaryContactIdentification($comakerID);
        $comakeridTypeID = $comakerPrimaryID['id_type_name'] ?? '';
        $comakeridNumber = $comakerPrimaryID['id_number'] ?? '';

        if(!empty($comakerID)){
          $comakerIDNumber = $comakeridTypeID . ' - ' . $comakeridNumber;
        }
        else{
          $comakerIDNumber = '';
        }
    
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
    $pdf->MultiCell(0, 0, '<b>PROMISSORY NOTE</b>', 0, 'C', 0, 1, '', '', true, 0, true, true, 0);
    $pdf->Ln(2);
    $pdf->MultiCell(0, 0, '<b>'.number_format($totalPn, 2).'</b>', 0, 'L', 0, 1, '', '', true, 0, true, true, 0);
    $pdf->Ln(5);
    $pdf->MultiCell(0, 0, 'For value received, I/we jointly and severally promise to pay without need of demand to the order of Christian General Motors Incorporated at its principal office at Km 112, Maharlika Highway, Brgy Hermogenes Concepcion, Cabanatuan City, Nueva Ecija, Philippines, the sum of <b><u>'. strtoupper($amountInWords->format($totalPn)) .' PESOS ('. number_format($totalPn, 2) .')</u></b> payable based on the <b>SCHEDULE OF PAYMENTS</b> as stipulated in the <b>Disclosure Statement on Credit Sales Transaction</b> which I/we duly received, agreed and understood.', 0, 'J', 0, 1, '', '', true, 0, true, true, 0);
    $pdf->Ln(5);
    $pdf->MultiCell(0, 0, "Time is declared of the essence hereof and in case of default in the payment of any installment due, all the other instalments shall automatically become due and demandable and shall make me liable for the additional sum equivalent to <b>THREE percent (3%)</b> per month based on the total amount due and demandable as penalty, compounded monthly until fully paid; and in case it becomes necessary to collect this note through any Attorney-at-Law, the further sum of <b>TWENTY percent (20%)</b> thereof and <b>ATTORNEY'S FEES</b> of <b>THIRTY percent (30%)</b> of total amount due , exclusive of costs and judicial/extra-judicial expenses; Moreover, I further empower the holder or any of his authorized representative(s), at his option, to hold as security therefore any real or personal property which may be in my possession or control by virtue of any other contract.", 0, 'J', 0, 1, '', '', true, 0, true, true, 0);
    $pdf->Ln(5);
    $pdf->MultiCell(0, 0, "In case of extraordinary change in value of the peso due to extraordinary inflation or deflation or any other reason, the basis of payment for this note shall be the value of the peso at the time the obligation was incurred as provided in Article 1250 of the New Civil Code.", 0, 'J', 0, 1, '', '', true, 0, true, true, 0);
    $pdf->Ln(5);
    $pdf->MultiCell(0, 0, "DEMAND AND NOTICE OF DISHONOR WAIVED. I hereby waive any diligence, presentment, demand, protests or notice of non-payment or dishonor pertaining to this note, or any extension or renewal therefore. Holder(s) may accept partial payment(s) and grant renewals or extensions of payment reserving its/their rights against each and all indorsers, and all parties to this note. Acceptance by holder(s) of any partial payment(s) after due date shall not be considered as extending the time for payment or as a modification of any conditions hereof.", 0, 'J', 0, 1, '', '', true, 0, true, true, 0);
    $pdf->Ln(5);
    $pdf->MultiCell(0, 0, "All actions arising from or connected with this note shall be brought exclusively in the proper courts of CABANATUAN CITY, Philippines; and in case of judicial execution of this obligation or any part of it, the debtor waives all rights under the provisions of Rule 39 Sec. 13, of the Rules of the Court.", 0, 'J', 0, 1, '', '', true, 0, true, true, 0);
    $pdf->Ln(5);
    $pdf->MultiCell(0, 0, "Signed this ____ day of ___________________, 20___ at_______________________________, Philippines.", 0, 'J', 0, 1, '', '', true, 0, true, true, 0);

    $pdf->Ln(10);
    $pdf->Cell(90, 4, strtoupper($customerName), 'B', 0 , 'C');
    $pdf->Cell(10, 4, '     ', 0, 0 , 'L');
    $pdf->Cell(90, 4, strtoupper($comakerName), 'B', 0, 'C');
    $pdf->Ln(5);
    $pdf->Cell(90, 8, 'MAKER', 0, 0, 'C');
    $pdf->Cell(10, 4, '     ', 0, 0 , 'L');
    $pdf->Cell(90, 8, 'CO-MAKER', 0, 0, 'C');

    $pdf->Ln(15);
    $pdf->Cell(90, 4, strtoupper($customeridTypeID) . ' - ' . strtoupper($customeridNumber), 'B', 0, 'C', 0, '', 1);
    $pdf->Cell(10, 4, '     ', 0, 0 , 'L', '', 1);
    $pdf->Cell(90, 4, strtoupper($comakerIDNumber), 'B', 0, 'C', 0, '', 1);
    $pdf->Ln(5);
    $pdf->Cell(90, 8, 'ID NUMBER', 0, 0, 'C');
    $pdf->Cell(10, 4, '     ', 0, 0 , 'L', '', 1);
    $pdf->Cell(90, 8, 'ID NUMBER', 0, 0, 'C');

    $pdf->Ln(15);
    $pdf->Cell(90, 4, strtoupper($customerAddress), 'B', 0, 'L', 0, '', 1);
    $pdf->Cell(10, 4, '     ', 0, 0 , 'L', '', 1);
    $pdf->Cell(90, 4, strtoupper($comakerAddress), 'B', 0, 'L', 0, '', 1);
    $pdf->Ln(5);
    $pdf->Cell(90, 8, 'ADDRESS', 0, 0, 'C');
    $pdf->Cell(10, 4, '     ', 0, 0 , 'L', '', 1);
    $pdf->Cell(90, 8, 'ADDRESS', 0, 0, 'C');
    $pdf->Ln(15);

    $pdf->MultiCell(0, 0, '<b>SIGNED IN THE PRESENCE OF:</b>', 0, 'C', 0, 1, '', '', true, 0, true, true, 0);
    $pdf->Ln(8);
    $pdf->Cell(80, 4, '', 'B', 0, 'C', 0, '', 1);
    $pdf->Cell(25, 4, '', 0, 0, 'C');
    $pdf->Cell(80, 4, '', 'B', 0, 'C', 0, '', 1);
    $pdf->Cell(25, 4, '', 0, 0, 'C');
    $pdf->Ln(15);

    // Output the PDF to the browser
    $pdf->Output('pn.pdf', 'I');
    ob_end_flush();
?>