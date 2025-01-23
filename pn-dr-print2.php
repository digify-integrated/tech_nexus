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
    $pdf->MultiCell(0, 0, '&nbsp; &nbsp; &nbsp; FOR VALUE RECEIVED, I/We jointly and severally promise to pay without need of demand to the order of <b>CHRISTIAN GENERAL MOTORS INCORPORATED</b> (“CGMI” for brevity), at its office at <b>KM 112 MAHARLIKA HIGHWAY BARANGAY HERMOGENES CONCEPCION CABANATUAN CITY, NUEVA ECIJA, PHILIPPINES</b>, the sum of PESOS: <b>'. strtoupper($amountInWords->format($totalPn)) .' (Php '. number_format($totalPn, 2) .')</b> payable as follows: ', 0, 'J', 0, 1, '', '', true, 0, true, true, 0);
    $pdf->Ln(5);
    $pdf->Cell(10, 8, '       '  , 0, 0, 'L');
    $pdf->Cell(80, 8, 'Initial Payment'  , 0, 0, 'L');
    $pdf->Cell(32, 8, 'Php 40,000.00 payable upon signing of this note' , 0, 0, 'L');
    $pdf->Ln(10);
    $pdf->Cell(10, 8, '       '  , 0, 0, 'L');
    $pdf->Cell(80, 8, 'Monthly Installment'  , 0, 0, 'L');
    $pdf->Cell(32, 8, 'Php 120,960.00 per month payable every 1st day of the month' , 0, 0, 'L');
    $pdf->Ln(5);
    $pdf->Cell(10, 8, '       '  , 0, 0, 'L');
    $pdf->Cell(80, 8, ''  , 0, 0, 'L');
    $pdf->Cell(32, 8, 'starting on 01 March 2025 until fully and completely paid' , 0, 0, 'L');
    $pdf->Ln(10);
    $pdf->Cell(10, 8, '       '  , 0, 0, 'L');
    $pdf->Cell(80, 8, 'Transaction Fee'  , 0, 0, 'L');
    $pdf->Cell(32, 8, 'Php 5,000.00 per month payable upon signing on this note' , 0, 0, 'L');
    $pdf->Ln(10);
    $pdf->Cell(10, 8, '       '  , 0, 0, 'L');
    $pdf->Cell(80, 8, 'Insurance Renewal'  , 0, 0, 'L');
    $pdf->Cell(32, 8, 'Php86,101.00 payable on 24 June 2025 and P70,444.00 payable' , 0, 0, 'L');
    $pdf->Ln(5);
    $pdf->Cell(10, 8, '       '  , 0, 0, 'L');
    $pdf->Cell(80, 8, ''  , 0, 0, 'L');
    $pdf->Cell(32, 8, 'on 24 June 2026' , 0, 0, 'L');
    $pdf->Ln(10);
    $pdf->MultiCell(0, 0, '&nbsp; &nbsp; &nbsp; I/We further agree JOINTLY and SEVERALLY that for late payment in the amount above written or any portion thereof, as when the same becomes due and payable, we shall pay penalty charge equivalent to FIVE PERCENT (5%) of the unpaid amount per month of delay to be computed from the date due until full payment thereof.  A fraction of a month being deemed to be an entire month.', 0, 'J', 0, 1, '', '', true, 0, true, true, 0);
    $pdf->Ln(5);
    $pdf->MultiCell(0, 0, '&nbsp; &nbsp; &nbsp; I/We hereby waive notice of demand for payment and agree that any legal action which may arise in relation to this note may be instituted in the proper court of Cabanatuan City.', 0, 'J', 0, 1, '', '', true, 0, true, true, 0);
    $pdf->Ln(5);
    $pdf->MultiCell(0, 0, '&nbsp; &nbsp; &nbsp; In all applicable cases, I/We jointly and severally waive and renounce presentment for acceptance, notice of dishonor and/or protest and notice of non – payment of this promissory note. Acceptance by the holder thereof of payment of any installment or any part thereof after due date shall not be considered as extending the time for the payment of this note or any installment thereof, nor shall be it construed as a renunciation or any right which may have already accrued in favor of the holder nor an amendment or modification of any of the terms herein stipulated.', 0, 'J', 0, 1, '', '', true, 0, true, true, 0);
    $pdf->Ln(5);
    $pdf->MultiCell(0, 0, '&nbsp; &nbsp; &nbsp; This note shall likewise be subjected to the following terms and conditions:', 0, 'J', 0, 1, '', '', true, 0, true, true, 0);
    $pdf->Ln(5);
    $pdf->MultiCell(0, 0, '&nbsp; &nbsp; &nbsp; 1.	Payments made by the Maker(s) and/or Co–maker(s) after the due date shall be applied in the following order of priorities: Attorney’s Fee – First; Other charges – Second; Penalties – Third; Interest – Fourth; Capitalized interest, penalty and other charges – Fifth; Principal – Sixth', 0, 'J', 0, 1, '', '', true, 0, true, true, 0);
    $pdf->Ln(5);
    $pdf->MultiCell(0, 0, '&nbsp; &nbsp; &nbsp; 2. The Maker(s) and/or Co–maker(s) shall be considered in default in case one or more of the following occur:', 0, 'J', 0, 1, '', '', true, 0, true, true, 0);
    $pdf->Ln(2);
    $pdf->Cell(10, 8, '       '  , 0, 0, 'L');
    $pdf->Cell(80, 8, 'a. Failure the pay this Note or any installments thereof on its due date'  , 0, 0, 'L');
    $pdf->Ln(6);
    $pdf->Cell(10, 8, '       '  , 0, 0, 'L');
    $pdf->Cell(80, 8, 'b. Bankruptcy or closeout; declaration of bankruptcy or close – out by the Maker(s).'  , 0, 0, 'L');
    $pdf->Ln(6);
    $pdf->Cell(10, 8, '       '  , 0, 0, 'L');
    $pdf->Cell(80, 8, 'c.	Deterioration, alienation, or diminution of guarantee or collateral.'  , 0, 0, 'L');
    $pdf->Ln(6);
    $pdf->Cell(10, 8, '       '  , 0, 0, 'L');
    $pdf->Cell(80, 8, 'd.	Vehicle acquired or amount financed is diverted to unstipulated purposes.'  , 0, 0, 'L');
    $pdf->Ln(6);
    $pdf->Cell(10, 8, '       '  , 0, 0, 'L');
    $pdf->Cell(80, 8, 'e.	Any misrepresentation made by the Maker(s) in any of the documents executed in connection herewith or'  , 0, 0, 'L');
    $pdf->Ln(6);
    $pdf->Cell(10, 8, '       '  , 0, 0, 'L');
    $pdf->Cell(80, 8, 'when information provided for credit analysis has been verifiably withheld or altered.'  , 0, 0, 'L');
    $pdf->Ln(6);
    $pdf->Cell(10, 8, '       '  , 0, 0, 'L');
    $pdf->Cell(80, 8, 'f.	Any violation made by the Maker(s) of existing policies promulgated by CGMI.'  , 0, 0, 'L');
    
    $pdf->Ln(10);
    $pdf->MultiCell(0, 0, '&nbsp; &nbsp; &nbsp; 3.	In the event that this note is referred to the counsel for collection, the Maker(s) and Co–maker(s) jointly and severally agree to pay 20% of the unpaid amount as attorney’s fee if collection is made extra – judicially which shall not be less than P5,000.00 and 25% of the unpaid amount if judicial action is availed of to enforce collection.', 0, 'J', 0, 1, '', '', true, 0, true, true, 0);
    
    $pdf->Ln(5);
    $pdf->MultiCell(0, 0, '&nbsp; &nbsp; &nbsp; 4. The right of CGMI contained in this note are assignable, discountable and negotiable at the sole option of CGMI, and/or its successors and I/we recognized our liabilities in full to its successors and assignees;', 0, 'J', 0, 1, '', '', true, 0, true, true, 0);
    
    $pdf->Ln(5);
    $pdf->MultiCell(0, 0, '&nbsp; &nbsp; &nbsp; 5.	The Maker(s) shall abide by all the applicable policies the CGMI may promulgate from time to time such as but not limited to increases in penalty rate, collection fees, other charges and interest rate;', 0, 'J', 0, 1, '', '', true, 0, true, true, 0);
    
    $pdf->Ln(5);
    $pdf->MultiCell(0, 0, '&nbsp; &nbsp; &nbsp; 6.	I/We hereby waive our rights over the properties exempted from execution under the provisions of Rule 39, Section 13 of the Rules of Court, as amended.', 0, 'J', 0, 1, '', '', true, 0, true, true, 0);
    
    $pdf->Ln(5);
    $pdf->MultiCell(0, 0, '&nbsp; &nbsp; &nbsp; 7.	By virtue of this Note and the assignment of assets to CGMI, I/We hereby waive my/our rights under existing laws in relation to confidentiality of accounts and privacy of credit and other data or information and hereby consent and authorize CGMI, its subsidiaries or affiliates, to gather, collect, share, report or disclose basic credit data and other information pertaining to me/us or my/our accounts as may be necessary or for CGMI to comply with RA 9510 (Credit Information System Act), RA 10173 (Data Privacy Act) and their implementing rules and regulations as well as other pertinent laws and issuances.', 0, 'J', 0, 1, '', '', true, 0, true, true, 0);
    
    $pdf->Ln(5);
    $pdf->MultiCell(0, 0, '&nbsp; &nbsp; &nbsp; 8.	Upon full and final settlement of the obligation under this Note as determined by CGMI, the parties hereto shall be deemed to have mutually, irrevocably, freely and voluntarily released and forever discharged one another from any and all causes of action, claims and demands whatsoever in law or equity, arising, directly or indirectly from this note all of which claims or causes of action by these presents the parties hereby abandon and waive.', 0, 'J', 0, 1, '', '', true, 0, true, true, 0);
    
    $pdf->Ln(5);
    $pdf->MultiCell(0, 0, '&nbsp; &nbsp; &nbsp; <b>9.	This Promissory Note shall be binding upon the heirs, executors, administrators, successors and assigns of the Maker(s) and/or Co-maker(s) and inure to the benefit of the CGMI and its permitted successors, endorsees and assigns.</b>', 0, 'J', 0, 1, '', '', true, 0, true, true, 0);
    
    $pdf->Ln(5);
    $pdf->MultiCell(0, 0, '&nbsp; &nbsp; &nbsp; 10.	I/We hereby affirm that I/We have carefully read and understood the terms and conditions of this Note and I/WE agree to be bound by them, acknowledge receipt of a copy of this Note.', 0, 'J', 0, 1, '', '', true, 0, true, true, 0);

    $pdf->Ln(5);
    $pdf->MultiCell(0, 0, "Signed this ____ day of ___________________, 20___ at_______________________________, Philippines.", 0, 'J', 0, 1, '', '', true, 0, true, true, 0);

    $pdf->Ln(10);
    $pdf->Cell(90, 4, strtoupper($customerName), 'B', 0 , 'C');
    $pdf->Cell(10, 4, '     ', 0, 0 , 'L');
    $pdf->Cell(90, 4, strtoupper($comakerName), 'B', 0, 'C');
    $pdf->Ln(5);
    $pdf->Cell(90, 8, 'MAKER', 0, 0, 'C');
    $pdf->Cell(10, 4, '     ', 0, 0 , 'L');
    $pdf->Cell(90, 8, 'MAKER', 0, 0, 'C');
    
    $pdf->Ln(15);
    $pdf->Cell(90, 4, strtoupper($customerAddress), 'B', 0, 'L', 0, '', 1);
    $pdf->Cell(10, 4, '     ', 0, 0 , 'L', '', 1);
    $pdf->Cell(90, 4, strtoupper($comakerAddress), 'B', 0, 'L', 0, '', 1);
    $pdf->Ln(5);
    $pdf->Cell(90, 8, 'ADDRESS', 0, 0, 'C');
    $pdf->Cell(10, 4, '     ', 0, 0 , 'L', '', 1);
    $pdf->Cell(90, 8, 'ADDRESS', 0, 0, 'C');
    $pdf->Ln(15);
    $pdf->MultiCell(0, 0, 'NAME AND SIGNATURE OF CO-MAKER: __________________________________________', 0, 'J', 0, 1, '', '', true, 0, true, true, 0);
    $pdf->Ln(5);
    $pdf->MultiCell(0, 0, 'ADDRESS: __________________________________________', 0, 'J', 0, 1, '', '', true, 0, true, true, 0);
    $pdf->Ln(15);
    $pdf->MultiCell(0, 0, 'NAME AND SIGNATURE OF CO-MAKER: __________________________________________', 0, 'J', 0, 1, '', '', true, 0, true, true, 0);
    $pdf->Ln(5);
    $pdf->MultiCell(0, 0, 'ADDRESS: __________________________________________', 0, 'J', 0, 1, '', '', true, 0, true, true, 0);

    $pdf->Ln(15);

    $pdf->MultiCell(0, 0, '<b>SIGNED IN THE PRESENCE OF:</b>', 0, 'C', 0, 1, '', '', true, 0, true, true, 0);
    $pdf->Ln(8);
    $pdf->Cell(80, 4, '', 'B', 0, 'C', 0, '', 1);
    $pdf->Cell(25, 4, '', 0, 0, 'C');
    $pdf->Cell(80, 4, '', 'B', 0, 'C', 0, '', 1);
    $pdf->Cell(25, 4, '', 0, 0, 'C');

    // Output the PDF to the browser
    $pdf->Output('pn.pdf', 'I');
    ob_end_flush();
?>