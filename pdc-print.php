<?php
    error_reporting(E_ALL);
    ini_set('display_errors', 1);

    // Load TCPDF library
    require('assets/libs/tcpdf2/tcpdf.php');

    // Load required files
    require_once 'config/config.php';
    require_once 'session.php';
    require_once 'model/database-model.php';
    require_once 'model/pdc-management-model.php';
    require_once 'model/system-model.php';
    require_once 'model/customer-model.php';
    require_once 'model/product-model.php';
    require_once 'model/sales-proposal-model.php';
    require_once 'model/user-model.php';

    $databaseModel = new DatabaseModel();
    $systemModel = new SystemModel();
    $pdcManagementModel = new PDCManagementModel($databaseModel);
    $customerModel = new CustomerModel($databaseModel);
    $productModel = new ProductModel($databaseModel);
    $salesProposalModel = new SalesProposalModel($databaseModel);
    $userModel = new UserModel($databaseModel, $systemModel);
    
    if(isset($_GET['id'])){
        if(empty($_GET['id'])){
          header('location: dashboard.php');
          exit;
        }
        
        $loanCollectionIDs = explode(',', $_GET['id']);

        $createdByDetails = $userModel->getUserByID($_SESSION['user_id']);
        $createdByName = strtoupper($createdByDetails['file_as'] ?? null);

        $totalPDCAmount = 0;
        $totalReversedPDCAmount = 0;

        foreach ($loanCollectionIDs as $loanCollectionID) {
            $pdcManagementDetails = $pdcManagementModel->getPDCManagement($loanCollectionID);
            $collection_status = $pdcManagementDetails['collection_status'];
            $payment_amount = $pdcManagementDetails['payment_amount'];

            $totalPDCAmount = $totalPDCAmount + $payment_amount;

            if($collection_status == 'Reversed'){
                $totalReversedPDCAmount = $totalReversedPDCAmount + $payment_amount;
            }
        }

        $netPayments = $totalPDCAmount - $totalReversedPDCAmount;
    }

    $summaryTable = generatePDC($loanCollectionIDs);

    ob_start();

    // Create TCPDF instance
    $pdf = new TCPDF('P', 'mm', array( 330.2,215.9), true, 'UTF-8', false);

    // Disable header and footer
    $pdf->setPrintHeader(false);
    $pdf->setPrintFooter(false);
    $pdf->SetPageOrientation('L');

    // Set PDF metadata
    $pdf->SetCreator('CGMI');
    $pdf->SetAuthor('CGMI');
    $pdf->SetTitle('PDC Print');
    $pdf->SetSubject('PDC Print');

    // Set margins and auto page break
    $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
    $pdf->SetMargins(15, 15, 15);
    $pdf->SetHeaderMargin(5);
    $pdf->SetFooterMargin(10);
    $pdf->SetAutoPageBreak(TRUE, 15);

    // Add a page
    $pdf->AddPage();

    $pdf->SetFont('times', 'B', 10.5);
    $pdf->Cell(80, 8, 'TOTAL CHECK PAYMENTS', 1, 0, 'L'); // 1 = border
    $pdf->Cell(80, 8, number_format($totalPDCAmount, 2), 1, 0, 'L'); // 1 = border
    $pdf->Ln(8);
    $pdf->Cell(80, 8, 'TOTAL RETURNED CHECKS', 1, 0, 'L'); // 1 = border
    $pdf->Cell(80, 8, number_format($totalReversedPDCAmount, 2), 1, 0, 'L'); // 1 = border
    $pdf->Ln(8);
    $pdf->Cell(80, 8, 'NET PAYMENTS', 1, 0, 'L'); // 1 = border
    $pdf->Cell(80, 8, number_format($netPayments, 2), 1, 0, 'L'); // 1 = border
    $pdf->Ln(8);

    $pdf->Cell(80, 8, 'PREPARED BY', 1, 0, 'L', 0, '', 1); // 1 = border
    $pdf->Cell(80, 8, $createdByName, 1, 0, 'L', 0, '', 1); // 1 = border
    $pdf->Ln(15);

    $pdf->SetFont('times', '', 10.5);
    $pdf->writeHTML($summaryTable, true, false, true, false, '');


    // Output the PDF to the browser
    $pdf->Output('pdc-print.pdf', 'I');
    ob_end_flush();

    function generatePDC($loanCollectionID){
        
        require_once 'model/database-model.php';
        require_once 'model/pdc-management-model.php';
        require_once 'model/system-model.php';
        require_once 'model/customer-model.php';
        require_once 'model/product-model.php';
        require_once 'model/sales-proposal-model.php';

        $databaseModel = new DatabaseModel();
        $systemModel = new SystemModel();
        $pdcManagementModel = new PDCManagementModel($databaseModel);
        $customerModel = new CustomerModel($databaseModel);
        $productModel = new ProductModel($databaseModel);
        $salesProposalModel = new SalesProposalModel($databaseModel);

        $loanCollectionIDs = is_array($loanCollectionID) ? $loanCollectionID : explode(',', $loanCollectionID);
        sort($loanCollectionIDs);

        $response = '<table border="0.5" width="100%" cellpadding="2" align="center">
                        <thead>
                            <tr>
                                <td style="background-color: rgba(220, 38, 38, .8);"><b>REF DATE</b></td>
                                <td style="background-color: rgba(220, 38, 38, .8);"><b>REF NO.</b></td>
                                <td style="background-color: rgba(220, 38, 38, .8);"><b>REF AMOUNT</b></td>
                                <td style="background-color: rgba(220, 38, 38, .8);"><b>CHECK DATE</b></td>
                                <td style="background-color: rgba(220, 38, 38, .8);"><b>CUSTOMER</b></td>
                                <td style="background-color: rgba(220, 38, 38, .8);"><b>STOCK NO.</b></td>
                                <td style="background-color: rgba(220, 38, 38, .8);"><b>LOAN NO.</b></td>
                                <td style="background-color: rgba(220, 38, 38, .8);"><b>PYMT DETAILS</b></td>
                                <td style="background-color: rgba(220, 38, 38, .8);"><b>BANK / BRANCH</b></td>
                                <td style="background-color: rgba(220, 38, 38, .8);"><b>CHECK NO.</b></td>
                                <td style="background-color: rgba(220, 38, 38, .8);"><b>CHECK STATUS</b></td>
                                <td style="background-color: rgba(220, 38, 38, .8);"><b>FOR DEPOSIT ON</b></td>
                                <td style="background-color: rgba(220, 38, 38, .8);"><b>REMARKS</b></td>
                            </tr>
                        </thead>
                        <tbody>';

        $totalAmount = 0;

        $count = count($loanCollectionIDs);
        $i = 0;

        foreach ($loanCollectionIDs as $loanCollectionID) {
            $pdcManagementDetails = $pdcManagementModel->getPDCManagement($loanCollectionID);
            $collection_status = $pdcManagementDetails['collection_status'];
            $payment_amount = $pdcManagementDetails['payment_amount'];
            $customer_id = $pdcManagementDetails['customer_id'];
            $sales_proposal_id = $pdcManagementDetails['sales_proposal_id'];
            $loan_number = $pdcManagementDetails['loan_number'];
            $payment_details = $pdcManagementDetails['payment_details'];
            $bank_branch = $pdcManagementDetails['bank_branch'];
            $check_number = $pdcManagementDetails['check_number'];
            $remarks = $pdcManagementDetails['remarks'];
            $pdc_type = $pdcManagementDetails['pdc_type'];
            $new_deposit_date = $systemModel->checkDate('empty', $pdcManagementDetails['new_deposit_date'], '', 'm/d/Y', '');
            $for_deposit_date = $systemModel->checkDate('empty', $pdcManagementDetails['for_deposit_date'], '', 'm/d/Y', '');
            $deposit_date = $systemModel->checkDate('empty', $pdcManagementDetails['deposit_date'], '', 'm/d/Y', '');
            $check_date = $systemModel->checkDate('empty', $pdcManagementDetails['check_date'], '', 'm/d/Y', '');

            $customerDetails = $customerModel->getPersonalInformation($customer_id);
            $customerName = $customerDetails['file_as'] ?? null;

            if($pdc_type == 'Product'){
                $product_id = $pdcManagementDetails['product_id'];

                $productDetails = $productModel->getProduct($product_id);
                $stockNumber = $productDetails['stock_number'] ?? null;
            }
            else if($pdc_type == 'Loan'){
                $salesProposalDetails = $salesProposalModel->getSalesProposal($sales_proposal_id);
                $product_id = $salesProposalDetails['product_id'];

                $productDetails = $productModel->getProduct($product_id);
                $stockNumber = $productDetails['stock_number'] ?? null;
            }
            else{
                $stockNumber = '';
            }

            if($collection_status == 'Reversed'){
                $or_number = $pdcManagementDetails['reversal_reference_number'];
                $or_date = $systemModel->checkDate('empty', $pdcManagementDetails['reversal_reference_date'], '', 'm/d/Y', '');
            }
            else{
                $or_number = $pdcManagementDetails['or_number'];
                $or_date = $systemModel->checkDate('empty', $pdcManagementDetails['or_date'], '', 'm/d/Y', '');
            }

            $response.= ' <tr>
                                <td>'. $or_date .'</td>
                                <td>'. $or_number .'</td>
                                <td>'. number_format($payment_amount, 2) .'</td>
                                <td>'. $check_date .'</td>
                                <td>'. $customerName .'</td>
                                <td>'. $stockNumber .'</td>
                                <td>'. $loan_number .'</td>
                                <td>'. $payment_details .'</td>
                                <td>'. $bank_branch .'</td>
                                <td>'. $check_number .'</td>
                                <td>'. $collection_status .'</td>
                                <td>'. $new_deposit_date .'</td>
                                <td>'. $remarks .'</td>
                            </tr>';

            /*if ($i == $count - 1) {
                $response.= '<tr>
                                    <td>DUE DATE</td>
                                    <td>'. $from.'</td>
                                </tr>
                                <tr>
                                    <td>TOTAL DUE AMOUNT</td>
                                    <td>'. number_format($totalAmount, 2).'</td>
                                </tr>';
            }*/
            $i++;
        }

        $response .= '
                    </tbody>
            </table>';

        return $response;
    }

    /*function generateBillingTable($loanCollectionID, $leasingID, $initialBasicRental){
        
        require_once 'model/database-model.php';
        require_once 'model/leasing-application-model.php';

        $databaseModel = new DatabaseModel();
        $leasingApplicationModel = new LeasingApplicationModel($databaseModel);

        $loanCollectionIDs = is_array($loanCollectionID) ? $loanCollectionID : explode(',', $loanCollectionID);
        sort($loanCollectionIDs);

        $response = '<table border="0.5" width="100%" cellpadding="2" align="center">
                        <tbody>
                        <tr>
                            <td rowspan="2">BILLING MONTH</td>
                            <td colspan="2">COVERED PERIOD</td>
                            <td rowspan="2">MONTHLY RENTAL</td>
                            <td rowspan="2">VAT</td>
                            <td rowspan="2">W/TAX</td>
                            <td rowspan="2">NET RENTAL</td>
                            <td rowspan="2">PAID</td>
                            <td rowspan="2">DUE AMOUNT</td>
                        </tr>
                        <tr>
                            <td>FROM</td>
                            <td>TO</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>';

        $totalAmount = 0;
        foreach ($loanCollectionIDs as $loanCollectionID) {
            $leasingApplicationDetails = $leasingApplicationModel->getLeasingApplication($leasingID);
            $vat = $leasingApplicationDetails['vat'];
            $witholdingTax = $leasingApplicationDetails['witholding_tax'];
            $initialBasicRental = $leasingApplicationDetails['initial_basic_rental'];

            $leasingApplicationRepaymentDetails = $leasingApplicationModel->getLeasingApplicationRepayment($loanCollectionID);
            $dueDate = $leasingApplicationRepaymentDetails['due_date'];
            $month = strtoupper(date('M', strtotime($dueDate))); 
            $year = substr(strtoupper(date('Y', strtotime($dueDate))), -2);
            $billingMonth = date('m/d/Y', strtotime($dueDate));
            $from = date('m/d/Y', strtotime($dueDate));
            $to = date('m/d/Y', strtotime('+1 month', strtotime($dueDate)));
            $unpaidRental = $leasingApplicationRepaymentDetails['unpaid_rental'];
            $paidRental = $leasingApplicationRepaymentDetails['paid_rental'];
            $totalRental = $paidRental + $unpaidRental;

            if($vat == 'Yes' && $witholdingTax == 'Yes'){
                $baseTotalRental = $totalRental / (1.07);
            }
            else if($vat == 'Yes' && $witholdingTax == 'No'){
                $baseTotalRental = $totalRental / (1.12);
            }
            else if($vat == 'No' && $witholdingTax == 'Yes'){
                $baseTotalRental = $totalRental / (1.05);
            }
            else{
                $baseTotalRental = $totalRental;
            }

            if($vat == 'Yes'){
                $vatAmount = $baseTotalRental * (0.12);
            }
            else{
                $vatAmount = '0.00';
            }

            if($witholdingTax == 'Yes'){
                $witholdingTaxAmount = ($baseTotalRental * (0.05) * -1);
            }
            else{
                $witholdingTaxAmount = '0.00';
            }

            $dueAmount = $totalRental - $paidRental;
            $totalAmount = $totalAmount + $dueAmount;

            if(number_format($unpaidRental, 2, '.', '') > 0){
                $response .= ' <tr>
                        <td>'. $month .'-'. $year .'</td>
                        <td>'. $from .'</td>
                        <td>'. $to .'</td>
                        <td>'. number_format($baseTotalRental, 2) .'</td>
                        <td>'. number_format($vatAmount, 2) .'</td>
                        <td>'. number_format($witholdingTaxAmount, 2) .'</td>
                        <td>'. number_format($totalRental, 2) .'</td>
                        <td>'. number_format($paidRental, 2) .'</td>
                        <td>'. number_format($dueAmount, 2) .'</td>
                    </tr>';
            }
        }

        $response .= ' <tr>
                            <td colspan="8" align="right"><b>TOTAL</b></td>
                            <td><b>'. number_format($totalAmount, 2) .'</b></td>
                        </tr>';

        $response .= '
                    </tbody>
            </table>';

        return $response;
    }*/
?>