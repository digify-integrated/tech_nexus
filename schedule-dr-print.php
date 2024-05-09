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
        $comakerID = $salesProposalDetails['comaker_id'];
        $productID = $salesProposalDetails['product_id'] ?? null;
        $productType = $salesProposalDetails['product_type'] ?? null;
        $salesProposalNumber = $salesProposalDetails['sales_proposal_number'] ?? null;
        $numberOfPayments = $salesProposalDetails['number_of_payments'] ?? null;
        $paymentFrequency = $salesProposalDetails['payment_frequency'] ?? null;
        $startDate = $salesProposalDetails['actual_start_date'] ?? null;
        $drNumber = $salesProposalDetails['dr_number'] ?? null;
        $termLength = $salesProposalDetails['term_length'] ?? null;
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

        $totalPn = $pnAmount + $totalDeposit['total'];

        $amountInWords = new NumberFormatter("en", NumberFormatter::SPELLOUT);
    
        $customerDetails = $customerModel->getPersonalInformation($customerID);
        $customerName = strtoupper($customerDetails['file_as']) ?? null;
    
        $comakerDetails = $customerModel->getPersonalInformation($comakerID);
        $comakerName = strtoupper($comakerDetails['file_as']) ?? null;
    
        if(!empty($comakerName)){
            $comakerLabel = '<p class="text-center mb-0 text-white"></p>';
        }
        else{
            $comakerLabel = '<p class="text-center mb-0">'. $comakerName .'</p>';
        }
    
        $customerPrimaryAddress = $customerModel->getCustomerPrimaryAddress($customerID);
        $customerAddress = $customerPrimaryAddress['address'] . ', ' . $customerPrimaryAddress['city_name'] . ', ' . $customerPrimaryAddress['state_name'] . ', ' . $customerPrimaryAddress['country_name'];
    
        if(!empty($comakerName)){
            $comakerAddressLabel = '<p class="text-center mb-0 text-white"></p>';
        }
        else{
            $comakerAddressLabel = '<p class="text-center mb-0">'. $comakerName .'</p>';
        }
    
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

    $amortSched = generateAmortizationSchedule($numberOfPayments, $pnAmount, $repaymentAmount, $paymentFrequency, $termLength, $startDate);
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
    $pdf->SetFont('times', '', 12);
    $pdf->Cell(130, 8, 'OTHER CHARGES', 0, 0, 'L');
    $pdf->Ln(8);
    $pdf->writeHTML($othercharges, true, false, true, false, '');
    
    $pdf->AddPage();
    $pdf->Cell(130, 8, 'SCHEDULE OF PAYMENTS', 0, 0, 'L');
    $pdf->Ln(8);
    $pdf->writeHTML($amortSched, true, false, true, false, '');

    // Output the PDF to the browser
    $pdf->Output('repayment.pdf', 'I');
    ob_flush();


    function generateOtherCharges($databaseModel, $systemModel, $salesProposalID){
        $response = '<table border="0.5" width="100%" align="center">
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
                $manualPDCInputID = $row['manual_pdc_input_id'];
                $accountNumber = $row['account_number'];
                $bankBranch = $row['bank_branch'];
                $checkDate = $systemModel->checkDate('summary', $row['check_date'], '', 'd-M-Y', '');
                $checkNumber = $row['check_number'];
                $paymentFor = $row['payment_for'];
                $grossAmount = number_format($row['gross_amount'], 2);
    
                $response .= '<tr>
                                <td>'. strtoupper($checkDate) .'</td>
                                <td>'. $grossAmount .'</td>
                                <td>'. strtoupper($paymentFor) .'</td>
                            </tr>';
            }
        }
        else{
            $response .= '<tr>
            <td colspan="3">No Other Charges</td>
            </tr>';
        }

        

        $response .= '</tbody>
            </table>';

        return $response;
    }

    function generateAmortizationSchedule($numberOfPayments, $pnAmount, $repaymentAmount, $paymentFrequency, $termLength, $startDate){
        $response = '<table border="0.5" width="100%" align="center">
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