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
    $pdf->MultiCell(0, 0, '<b>DEED OF ABSOLUTE SALE</b>', 0, 'C', 0, 1, '', '', true, 0, true, true, 0);
    $pdf->Ln(5);
    $pdf->MultiCell(0, 0, '<b>KNOW ALL MEN BY THESE PRESENTS:</b>', 0, 'J', 0, 1, '', '', true, 0, true, true, 0);
    $pdf->Ln(5);
    $pdf->MultiCell(0, 0, 'That I_____________________________________________________________________, with postal at _________________________________________________________________for and in consideration 
    the amount of_________________________________________________________________________  (PHP____________________) PESOS, Philippine Currency, receipt of which is hereby acknowledgement have sold, transferred, conveyed and by these presents do sell, transfer and convey unto ___________________________________________________________________with postal address at __________________________________________________________________his/her successors and assigns that certain motor vehicle which is particularly described as follows:
    ', 0, 'J', 0, 1, '', '', true, 0, true, true, 0);
    $pdf->Ln(5);
    $pdf->Cell(20, 8, 'MAKE'  , 0, 0, 'L');
    $pdf->Cell(32, 8, ':      ____________________________', 0, 0, 'L');
    $pdf->Cell(40, 8, '       '  , 0, 0, 'L');
    $pdf->Cell(30, 8, 'CHASSIS NO.'  , 0, 0, 'L');
    $pdf->Cell(32, 8, ':      ____________________________', 0, 0, 'L');
    $pdf->Cell(30, 8, '       '  , 0, 0, 'L');
    $pdf->Ln(5);
    $pdf->Cell(20, 8, 'SERIES'  , 0, 0, 'L');
    $pdf->Cell(32, 8, ':      ____________________________', 0, 0, 'L');
    $pdf->Cell(40, 8, '       '  , 0, 0, 'L');
    $pdf->Cell(30, 8, 'PLATE NO.'  , 0, 0, 'L');
    $pdf->Cell(32, 8, ':      ____________________________', 0, 0, 'L');
    $pdf->Cell(30, 8, '       '  , 0, 0, 'L');
    $pdf->Ln(5);
    $pdf->Cell(25, 8, 'ENGINE NO.'  , 0, 0, 'L');
    $pdf->Cell(32, 8, ':      ____________________________', 0, 0, 'L');
    $pdf->Cell(35, 8, '       '  , 0, 0, 'L');
    $pdf->Cell(30, 8, 'MV FILE NO.'  , 0, 0, 'L');
    $pdf->Cell(32, 8, ':      ____________________________', 0, 0, 'L');
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
    $pdf->SetFont('times', '', 10.5);
    $pdf->Ln(20);
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