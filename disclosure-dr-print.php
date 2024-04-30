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
        $customerID = $salesProposalDetails['customer_id'] ?? null;
        $comakerID = $salesProposalDetails['comaker_id'] ?? null;
        $productID = $salesProposalDetails['product_id'] ?? null;
        $productType = $salesProposalDetails['product_type'] ?? null;
        $salesProposalNumber = $salesProposalDetails['sales_proposal_number'] ?? null;
        $numberOfPayments = $salesProposalDetails['number_of_payments'] ?? null;
        $paymentFrequency = $salesProposalDetails['payment_frequency'] ?? null;
        $startDate = $salesProposalDetails['actual_start_date'] ?? null;
        $drNumber = $salesProposalDetails['dr_number'] ?? null;
        $termLength = $salesProposalDetails['term_length'] ?? null;
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
        $totalInsuranceFee = $insurancePremiumSecondYear + $insurancePremiumThirdYear + $insurancePremiumFourthYear;
    
        $totalCharges = $insurancePremium + $handlingFee + $transferFee + $transactionFee + $totalRenewalFee + $totalInsuranceFee + $docStampTax;
        
        $totalDeposit = $salesProposalModel->getSalesProposalAmountOfDepositTotal($salesProposalID);

        $totalPn = $pnAmount + $totalDeposit['total'];

        $amountInWords = new NumberFormatter("en", NumberFormatter::SPELLOUT);
    
        $customerDetails = $customerModel->getPersonalInformation($customerID);
        if(!empty($releaseTo)){
            $customerName = strtoupper($releaseTo);
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

    $amortSched = generateAmortizationSchedule($numberOfPayments, $pnAmount, $repaymentAmount, $paymentFrequency, $startDate);
    $othercharges = generateOtherCharges($databaseModel, $systemModel, $salesProposalID);

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
    $pdf->MultiCell(0, 0, '<b>DISCLOSURE STATEMENT ON CREDIT SALES TRANSACTION</b>', 0, 'C', 0, 1, '', '', true, 0, true, true, 0);
    $pdf->Ln(1);
    $pdf->MultiCell(0, 0, '(As Required under R.A. 3765, Truth in Lending Act)', 0, 'C', 0, 1, '', '', true, 0, false, true, 0);
    $pdf->Ln(3);
    $pdf->Cell(40, 8, 'NAME OF BUYER', 0, 0, 'L');
    $pdf->Cell(120, 8, strtoupper($customerName), 0, 0, 'L');
    $pdf->Ln(5);
    $pdf->Cell(40, 8, 'ADDRESS', 0, 0, 'L');
    $pdf->Cell(130, 8, strtoupper($customerAddress), 0, 0, 'L', 0, '', 1);
    $pdf->Ln(10);
    $pdf->Cell(130, 8, '1. TOTAL PRICE OF ITEM PURCHASED', 0, 0, 'L');
    $pdf->Cell(20, 8, 'P   ', 0, 0, 'L');
    $pdf->Cell(70, 8,  number_format($totalPn, 2), 0, 0, 'L');
    $pdf->Ln(8);
    $pdf->Cell(130, 8, '2. DOWNPAYMENT AND/OR TRADE-IN VALUE', 0, 0, 'L');
    $pdf->Cell(20, 8, 'P   ', 0, 0, 'L');
    $pdf->Cell(70, 8,  number_format($downpayment, 2), 0, 0, 'L');
    $pdf->Ln(8);
    $pdf->Cell(130, 8, '3. UNPAID BALANCE OF TOTAL PRICE', 0, 0, 'L');
    $pdf->Cell(20, 8, 'P   ', 0, 0, 'L');
    $pdf->Cell(70, 8, number_format($pnAmount, 2), 0, 0, 'L');
    $pdf->Ln(8);
    $pdf->Cell(130, 8, '4. OTHER CHARGES (TO BE COLLECTED SEPARATELY TO BUYER)', 0, 0, 'L');
    $pdf->Cell(70, 8, '', 0, 0, 'L');
    $pdf->Ln(8);
    $pdf->Cell(10, 8, '       '  , 0, 0, 'L');
    $pdf->Cell(80, 8, 'a. Insurance'  , 0, 0, 'L');
    $pdf->Cell(32, 8, 'P   ' . number_format($insurancePremium, 2) , 0, 0, 'L');
    $pdf->Ln(8);
    $pdf->Cell(10, 8, '       '  , 0, 0, 'L');
    $pdf->Cell(80, 8, 'b. Handling Fee'  , 0, 0, 'L');
    $pdf->Cell(32, 8, 'P   ' . number_format($handlingFee, 2) , 0, 0, 'L');
    $pdf->Ln(8);
    $pdf->Cell(10, 8, '       '  , 0, 0, 'L');
    $pdf->Cell(80, 8, 'c. Transfer Fee' , 0, 0, 'L');
    $pdf->Cell(32, 8, 'P   ' . number_format($transferFee, 2), 0, 0, 'L');
    $pdf->Ln(8);
    $pdf->Cell(10, 8, '       '  , 0, 0, 'L');
    $pdf->Cell(80, 8, 'd. Miscellaneous/Transaction Fee' , 0, 0, 'L');
    $pdf->Cell(32, 8, 'P   ' . number_format(($transactionFee + $docStampTax), 2) , 0, 0, 'L');
    $pdf->Ln(8);
    $pdf->Cell(10, 8, '       '  , 0, 0, 'L');
    $pdf->Cell(80, 8, 'e. Insurance Renewal' , 0, 0, 'L');
    $pdf->Cell(32, 8, 'P   ' . number_format($totalInsuranceFee, 2) , 0, 0, 'L');
    $pdf->Ln(8);
    $pdf->Cell(10, 8, '       '  , 0, 0, 'L');
    $pdf->Cell(80, 8, 'f. Registration Renewal' , 0, 0, 'L');
    $pdf->Cell(32, 8, 'P   ' . number_format($totalRenewalFee, 2) , 0, 0, 'L');
    $pdf->Ln(8);
    $pdf->Cell(10, 8, '       '  , 0, 0, 'L');
    $pdf->Cell(80, 8, 'TOTAL OTHER CHARGES' , 0, 0, 'L');
    $pdf->Cell(32, 8, 'P   ' . number_format($totalCharges, 2) , 0, 0, 'L');
    $pdf->Ln(8);
    $pdf->Cell(130, 8, '5. CONDITIONAL CHARGES MAYBE IMPOSED', 0, 0, 'L');
    $pdf->Cell(70, 8, '', 0, 0, 'L');
    $pdf->Ln(8);
    $pdf->Cell(10, 8, '       '  , 0, 0, 'L');
    $pdf->Cell(100, 8, 'a. Late payment penalty of straight 3% per month based on unpaid installments due.' , 0, 0, 'L');
    $pdf->Ln(8);
    $pdf->Cell(10, 8, '       '  , 0, 0, 'L');
    $pdf->Cell(80, 8, "b. Attorney's fee - 30% of total amount due; liquidated damages- further sum of 20% in addition" , 0, 0, 'L', '', 1);
    $pdf->Ln(5);
    $pdf->Cell(10, 8, '       '  , 0, 0, 'L');
    $pdf->Cell(80, 8, "to costs and other litigation expenses." , 0, 0, 'L', '', 1);
    $pdf->Ln(8);
    $pdf->Cell(10, 8, '       '  , 0, 0, 'L');
    $pdf->Cell(100, 8, "c. Liquidated damages- further sum of 20% in addition to costs and other litigation expenses." , 0, 0, 'L');
    $pdf->Ln(8);
    $pdf->Cell(130, 8, '6. OTHER CHARGES', 0, 0, 'L');
    $pdf->Ln(8);
    $pdf->writeHTML($othercharges, true, false, true, false, '');
    $pdf->Cell(130, 8, '7. SCHEDULE OF PAYMENTS', 0, 0, 'L');
    $pdf->Ln(8);
    $pdf->writeHTML($amortSched, true, false, true, false, '');
    $pdf->Ln(2);
    $pdf->MultiCell(0, 0, "<b>I ACKNOWLEDGE RECEIPT OF A COPY OF THIS STATEMENT PRIOR TO THE CONSUMMATION OF THE CREDIT SALES TRANSACTION AND THAT I UNDERSTAND AND FULLY AGREE TO THE TERMS AND CONDITIONS THEREOF.</b>", 0, 'J', 0, 1, '', '', true, 0, true, true, 0);
    $pdf->Ln(10);
    $pdf->Cell(185, 4, '', 'B', 0 , 'C');
    $pdf->Cell(10, 4, '     ', 0, 0 , 'L');
    $pdf->Ln(5);
    $pdf->Cell(185, 8, 'SIGNATURE OF BUYER OVER PRINTED NAME', 0, 0, 'C');
    $pdf->Cell(10, 4, '     ', 0, 0 , 'L');
    $pdf->Ln(10);
    $pdf->MultiCell(0, 0, "<b>This is a system-generated Disclosure Statement on Credit Sales Transaction and does not require a signature from Christian General Motors Inc.</b>", 0, 'J', 0, 1, '', '', true, 0, true, true, 0);

    // Output the PDF to the browser
    $pdf->Output('disclosure.pdf', 'I');
    ob_flush();


    function generateOtherCharges($databaseModel, $systemModel, $salesProposalID){
        $response = '<table border="0.5" width="100%" cellpadding="2" align="center">
        <thead>
            <tr>
                <th width="33.33%"><b>DUE DATE</b></th>
                <th width="33.33%"><b>AMOUNT DUE</b></th>
                <th width="33.33%"><b>PAYMENT FOR</b></th>
            </tr>
        </thead>
        <tbody>';


        $sql = $databaseModel->getConnection()->prepare('CALL generateSalesProposalPDCManualInputTable(:salesProposalID)');
        $sql->bindValue(':salesProposalID', $salesProposalID, PDO::PARAM_INT);
        $sql->execute();
        $options = $sql->fetchAll(PDO::FETCH_ASSOC);
        $sql->closeCursor();

        $resultCount = count($options);

        if($resultCount > 0){
            foreach ($options as $row) {
                $manualPDCInputID = $row['manual_pdc_input_id'] ?? null;
                $accountNumber = $row['account_number'] ?? null;
                $bankBranch = $row['bank_branch'] ?? null;
                $checkDate = $systemModel->checkDate('default', $row['check_date'], '', 'd-M-Y', '');
                $checkNumber = $row['check_number'] ?? null;
                $paymentFor = $row['payment_for'] ?? null;
                $grossAmount = number_format($row['gross_amount'] ?? null, 2);
    
                $response .= '<tr>
                                <td>'. strtoupper($checkDate) .'</td>
                                <td>'. $grossAmount .'</td>
                                <td>'. strtoupper($paymentFor) .'</td>
                            </tr>';
            }
        }
        else{
            $response .= '<tr>
            <td colspan="4">No Other Charges</td>
            </tr>';
        }

        

        $response .= '</tbody>
            </table>';

        return $response;
    }

    function generateAmortizationSchedule($numberOfPayments, $pnAmount, $repaymentAmount, $paymentFrequency, $startDate){
        $response = '<table border="0.5" width="100%" cellpadding="2" align="center">
        <thead>
            <tr>
                <th width="33.33%"><b>DUE DATE</b></th>
                <th width="33.33%"><b>AMOUNT DUE</b></th>
                <th width="33.33%"><b>OUTSTANDING BALANCE</b></th>
            </tr>
        </thead>
        <tbody>
        <tr>
            <td>--</td>
            <td>--</td>
            <td>'. number_format($pnAmount, 2) .'</td>
        </tr>';

        for ($i = 0; $i < $numberOfPayments; $i++) {
            $pnAmount = $pnAmount - $repaymentAmount;

            if($pnAmount <= 0){
                $pnAmount = 0;
            }

            $dueDate = calculateDueDate($startDate, $termLength, $paymentFrequency, $i + 1);

            $response .= '<tr>
                    <td>'. strtoupper($dueDate) .'</td>
                    <td>'. number_format($repaymentAmount, 2) .'</td>
                    <td>'. number_format($pnAmount, 2) .'</td>
                </tr>';
        }

        $response .= '</tbody>
            </table>';

        return $response;
    }

    function calculateDueDate($startDate, $termLength, $frequency, $iteration) {
        $date = new DateTime($startDate);
        switch ($frequency) {
            case 'Monthly':
                $date->modify("+$iteration months");
                break;
            case 'Quarterly':
                $date->modify("+$iteration months")->modify('+2 months');
                break;
            case 'Semi-Annual':
                $date->modify("+$iteration months")->modify('+5 months');
                break;
            case 'Lumpsum':
                $date->modify("+$termLength days");
                break;
            default:
                break;
        }
        return $date->format('d-M-Y');
    }
?>