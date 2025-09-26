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

    // Initialize database model
    $databaseModel = new DatabaseModel();

    // Initialize system model
    $systemModel = new SystemModel();

    $productModel = new ProductModel($databaseModel);
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

        $otherProductDetails = $salesProposalModel->getSalesProposalOtherProductDetails($salesProposalID);
        $yearModel = $otherProductDetails['year_model'] ??  '--';
        $crNo = $otherProductDetails['cr_no'] ??  '--';
        $mvFileNo = $otherProductDetails['mv_file_no'] ??  '--';
        $make = $otherProductDetails['make'] ??  '--';
        
        $customerContactInformation = $customerModel->getCustomerPrimaryContactInformation($customerID);
        $customerMobile = !empty($customerContactInformation['mobile']) ? $customerContactInformation['mobile'] : '--';
        $customerTelephone = !empty($customerContactInformation['telephone']) ? $customerContactInformation['telephone'] : '--';
        $customerEmail = !empty($customerContactInformation['email']) ? $customerContactInformation['email'] : '--';

        $productDetails = $productModel->getProduct($productID);
        $engineNumber = $productDetails['engine_number'] ?? null;
        $chassisNumber = $productDetails['chassis_number'] ?? null;

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
    $pdf->MultiCell(0, 0, '<b><u>VOLUNTARY SURRENDER OF POSSESSION OF PERSONAL PROPERTY</u></b>', 0, 'C', 0, 1, '', '', true, 0, true, true, 0);
    $pdf->Ln(5);
    $pdf->MultiCell(0, 0, 'KNOW ALL MEN BY THESE PRESENTS:', 0, 'J', 0, 1, '', '', true, 0, true, true, 0);
    $pdf->Ln(5);
    $pdf->MultiCell(0, 0, '&nbsp; &nbsp; That I, <b><u>'. $customerName .'</u></b>, of legal age and a resident of <b><u>'. strtoupper($customerAddress) .'</u></b>, have this day voluntarily surrender unto CHRISTIAN GENERAL MOTORS INC. a certain personal property which is more particularly described as follows:', 0, 'J', 0, 1, '', '', true, 0, true, true, 0);
    $pdf->Ln(5);
    $pdf->Cell(30, 8, '       '  , 0, 0, 'L');
    $pdf->Cell(40, 8, 'MAKE'  , 0, 0, 'L');
    $pdf->Cell(32, 8, ':      ' . $make, 0, 0, 'L');
    $pdf->Ln(5);
    $pdf->Cell(30, 8, '       '  , 0, 0, 'L');
    $pdf->Cell(40, 8, 'MODEL'  , 0, 0, 'L');
    $pdf->Cell(32, 8, ':      ' . $yearModel, 0, 0, 'L');
    $pdf->Ln(5);
    $pdf->Cell(30, 8, '       '  , 0, 0, 'L');
    $pdf->Cell(40, 8, 'MOTOR NO.'  , 0, 0, 'L');
    $pdf->Cell(32, 8, ':      ' . $engineNumber , 0, 0, 'L');
    $pdf->Ln(5);
    $pdf->Cell(30, 8, '       '  , 0, 0, 'L');
    $pdf->Cell(40, 8, 'SERIAL NO.'  , 0, 0, 'L');
    $pdf->Cell(32, 8, ':      ' . $chassisNumber, 0, 0, 'L');
    $pdf->Ln(15);
    $pdf->MultiCell(0, 0, 'of which I am the lawful possessor _____________________________________________________________ () Owner,', 0, 'J', 0, 1, '', '', true, 0, true, true, 0);
    $pdf->MultiCell(0, 0, '(  ) Successor-in-Interest;             (  )  Lessee;              (  ) Pledge                       (  ) Trustee                     (  ) Usufructuary
    (  ) Attorney-In-Fact;         (  ) Agent            (  ) ______________________________        (  ) (Other Capabilities)
    ', 0, 'J', 0, 1, '', '', true, 0, true, true, 0);
    $pdf->Ln(5);
    $pdf->MultiCell(0, 0, 'That I hereby acknowledge the right and authority of __________________________________________________ 
    __________________________________________ to extrajudicial foreclosure the mortgage thereon or otherwise dispose of the same;', 0, 'J', 0, 1, '', '', true, 0, true, true, 0);
    $pdf->Ln(5);
    $pdf->Cell(20, 8, '       '  , 0, 0, 'L');
    $pdf->Cell(100, 8, 'OPTION TO REDEEM: (Please check your option and affix your signature after the chosen option)'  , 0, 0, 'L');
    $pdf->Ln(5);
    $pdf->Cell(20, 8, '       '  , 0, 0, 'L');
    $pdf->Cell(100, 8, 'OPTION NO.1 - (that I agree to redeem the said property within thirty (30) days from the date'  , 0, 0, 'L');
    $pdf->Ln(5);
    $pdf->Cell(20, 8, '       '  , 0, 0, 'L');
    $pdf->Cell(100, 8, 'whereof upon full payment of the entire obligation together with interest charges and other expenses,'  , 0, 0, 'L');
    $pdf->Ln(5);
    $pdf->Cell(20, 8, '       '  , 0, 0, 'L');
    $pdf->Cell(100, 8, 'PROVIDED, that upon the expiration of the said redemption period,'  , 0, 0, 'L');
    $pdf->Ln(5);
    $pdf->Cell(20, 8, '       '  , 0, 0, 'L');
    $pdf->Cell(100, 8, '________________________________________________________________ '  , 0, 0, 'L');
    $pdf->Ln(5);
    $pdf->Cell(20, 8, '       '  , 0, 0, 'L');
    $pdf->Cell(100, 8, 'is hereby granted authority to dispose of the property in addition to its rights under the laws,'  , 0, 0, 'L');
    $pdf->Ln(5);
    $pdf->Cell(20, 8, '       '  , 0, 0, 'L');
    $pdf->Cell(100, 8, 'chattel mortgage or lease contracts, as applicable:'  , 0, 0, 'L');
    $pdf->Ln(15);
    $pdf->Cell(90, 4, '', 0, 0 , 'C');
    $pdf->Cell(10, 4, '', 0, 0 , 'L');
    $pdf->Cell(90, 4, $customerName, 'B', 0, 'C');
    $pdf->Ln(5);
    $pdf->Cell(90, 8, '', 0, 0, 'C'); // CLIENT NAME
    $pdf->Cell(10, 4, '     ', 0, 0 , 'L');
    $pdf->Cell(90, 8, 'Name and Signature of Possessor', 0, 0, 'C');
    $pdf->Ln(20);
    $pdf->Cell(20, 8, '       '  , 0, 0, 'L');
    $pdf->Cell(100, 8, 'OPTION 2- (______________________)That I am no longer interested in redeeming the property and'  , 0, 0, 'L');
    $pdf->Ln(5);
    $pdf->Cell(20, 8, '       '  , 0, 0, 'L');
    $pdf->Cell(100, 8, 'hereby waive my right title or interest thereto:'  , 0, 0, 'L');
    $pdf->Ln(15);
    $pdf->Cell(90, 4, '', 0, 0 , 'C');
    $pdf->Cell(10, 4, '', 0, 0 , 'L');
    $pdf->Cell(90, 4, '', 'B', 0, 'C');
    $pdf->Ln(5);
    $pdf->Cell(90, 8, '', 0, 0, 'C');
    $pdf->Cell(10, 4, '     ', 0, 0 , align: 'L');
    $pdf->Cell(90, 8, 'Name and Signature of Possessor', 0, 0, 'C');
    $pdf->Ln(10);
    $pdf->Cell(100, 8, '___________________, ______________, 20 ____________'  , 0, 0, 'L');
    $pdf->Ln(8);
    $pdf->Cell(100, 8, 'CONFORME'  , 0, 0, 'L');
    $pdf->Ln(8);
    $pdf->Cell(100, 8, 'By:_______________________________________________'  , 0, 0, 'L');

    // Output the PDF to the browser
    $pdf->Output('voluntary-surrender.pdf', 'I');
    ob_end_flush();
?>