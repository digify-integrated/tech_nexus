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
    $pdf->SetTitle('Collections Print');
    $pdf->SetSubject('Collections Print');

    // Set margins and auto page break
    $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
    $pdf->SetMargins(15, 15, 15);
    $pdf->SetHeaderMargin(5);
    $pdf->SetFooterMargin(10);
    $pdf->SetAutoPageBreak(TRUE, 15);

    // Add a page
    $pdf->AddPage();

    $pdf->SetFont('times', 'B', 10.5);
    $pdf->Cell(80, 8, 'TOTAL COLLECTION PAYMENTS', 0, 0, 'L');
    $pdf->Cell(80, 8, number_format($totalPDCAmount, 2), 0, 0, 'L');
    $pdf->Ln(8);
    $pdf->Cell(80, 8, 'TOTAL RETURNED COLLECTION', 0, 0, 'L');
    $pdf->Cell(80, 8, number_format($totalReversedPDCAmount, 2), 0, 0, 'L');
    $pdf->Ln(8);
    $pdf->Cell(80, 8, 'NET PAYMENTS', 0, 0, 'L');
    $pdf->Cell(80, 8, number_format($netPayments, 2), 0, 0, 'L');
    $pdf->Ln(12);
  
    $pdf->Cell(80, 8, 'PREPARED BY', 0, 0, 'L', 0, '', 1);
    $pdf->Cell(80, 8, $createdByName, 0, 0, 'L', 0, '', 1);
    $pdf->Ln(15);

    $pdf->writeHTML($summaryTable, true, false, true, false, '');


    // Output the PDF to the browser
    $pdf->Output('collection-print.pdf', 'I');
    ob_end_flush();

    function generatePDC($loanCollectionID){
        
        require_once 'model/database-model.php';
        require_once 'model/pdc-management-model.php';
        require_once 'model/system-model.php';
        require_once 'model/customer-model.php';
        require_once 'model/product-model.php';
        require_once 'model/sales-proposal-model.php';
        require_once 'model/bank-model.php';

        $databaseModel = new DatabaseModel();
        $systemModel = new SystemModel();
        $pdcManagementModel = new PDCManagementModel($databaseModel);
        $customerModel = new CustomerModel($databaseModel);
        $productModel = new ProductModel($databaseModel);
        $salesProposalModel = new SalesProposalModel($databaseModel);
        $bankModel = new BankModel($databaseModel);

        $loanCollectionIDs = is_array($loanCollectionID) ? $loanCollectionID : explode(',', $loanCollectionID);
        sort($loanCollectionIDs);

        $response = '<table border="0.5" width="100%" cellpadding="2" align="center">
                        <thead>
                            <tr>
                                <td>REF DATE</td>
                                <td>REF NO</td>
                                <td>REF AMOUNT</td>
                                <td>PAYMENT DATE</td>
                                <td>TRANSACTION DATE</td>
                                <td>CUSTOMER</td>
                                <td>STOCK NO.</td>
                                <td>LOAN NO</td>
                                <td>COLLECTED FROM</td>
                                <td>DEPOSITED TO</td>
                                <td>PYMT DETAILS</td>
                                <td>MODE OF PAYMENT</td>
                                <td>COLLECTION STATUS</td>
                                <td>REMARKS</td>
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
            $mode_of_payment = $pdcManagementDetails['mode_of_payment'];
            $check_number = $pdcManagementDetails['check_number'];
            $remarks = $pdcManagementDetails['remarks'];
            $pdc_type = $pdcManagementDetails['pdc_type'];
            $collected_from = $pdcManagementDetails['collected_from'];
            $deposited_to = $pdcManagementDetails['deposited_to'];
            $payment_date = $systemModel->checkDate('empty', $pdcManagementDetails['payment_date'], '', 'm/d/Y', '');
            $transaction_date = $systemModel->checkDate('empty', $pdcManagementDetails['transaction_date'], '', 'm/d/Y', '');

            $getBankDetails = $bankModel->getBank($deposited_to);
            $bank_name = $getBankDetails['bank_name'];

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
                                <td>'. $payment_date .'</td>
                                <td>'. $transaction_date .'</td>
                                <td>'. $customerName .'</td>
                                <td>'. $stockNumber .'</td>
                                <td>'. $loan_number .'</td>
                                <td>'. $collected_from .'</td>
                                <td>'. $bank_name .'</td>
                                <td>'. $payment_details .'</td>
                                <td>'. $mode_of_payment .'</td>
                                <td>'. $collection_status .'</td>
                                <td>'. $remarks .'</td>
                            </tr>';
            $i++;
        }

        $response .= '
                    </tbody>
            </table>';

        return $response;
    }
?>