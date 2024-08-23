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

        $defaultID = $loanCollectionIDs[0];
        $pdcManagementDetails = $pdcManagementModel->getPDCManagement($defaultID);

        $customer_id = $pdcManagementDetails['customer_id'];
        $sales_proposal_id = $pdcManagementDetails['sales_proposal_id'];

        $customerPrimaryAddress = $customerModel->getCustomerPrimaryAddress($customer_id);
        $customerAddress = strtoupper($customerPrimaryAddress['address'] . ', ' . $customerPrimaryAddress['city_name'] . ', ' . $customerPrimaryAddress['state_name'] . ', ' . $customerPrimaryAddress['country_name']);

        $salesProposalDetails = $salesProposalModel->getSalesProposal($sales_proposal_id);
        $product_id = $salesProposalDetails['product_id'];
        $loan_number = $salesProposalDetails['loan_number'];

        $productDetails = $productModel->getProduct($product_id);
        $stock_number = $productDetails['stock_number'] ?? null;
        $plateNumber = $productDetails['plate_number'] ?? null;

        $customerDetails = $customerModel->getPersonalInformation($customer_id);
        $customerName = $customerDetails['file_as'] ?? null;

        $totalPDCAmount = 0;
        $count = 0;

        foreach ($loanCollectionIDs as $loanCollectionID) {
            $pdcManagementDetails = $pdcManagementModel->getPDCManagement($loanCollectionID);
            $collection_status = $pdcManagementDetails['collection_status'];
            $payment_amount = $pdcManagementDetails['payment_amount'];

            $totalPDCAmount = $totalPDCAmount + $payment_amount;

            $count = $count + 1;
        }
    }

    $summaryTable = generatePDC($loanCollectionIDs, $totalPDCAmount, $count, $customerName, $plateNumber, $loan_number, $stock_number, $customerAddress);

    ob_start();

    // Create TCPDF instance
    $pdf = new TCPDF('P', 'mm', array(215.9, 330.2), true, 'UTF-8', false);

    // Disable header and footer
    $pdf->setPrintHeader(false);
    $pdf->setPrintFooter(false);
    $pdf->SetPageOrientation('P');

    // Set PDF metadata
    $pdf->SetCreator('CGMI');
    $pdf->SetAuthor('CGMI');
    $pdf->SetTitle('PDC Print');
    $pdf->SetSubject('PDC Print');

    // Set margins and auto page break
    $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
    $pdf->SetMargins(10, 10, 10);
    $pdf->SetHeaderMargin(5);
    $pdf->SetFooterMargin(10);
    $pdf->SetAutoPageBreak(TRUE, 15);

    // Add a page
    $pdf->AddPage();

    $pdf->SetFont('times', '', 8);
    $pdf->writeHTML($summaryTable, true, false, true, false, '');


    // Output the PDF to the browser
    $pdf->Output('apdc-print.pdf', 'I');
    ob_end_flush();

    function generatePDC($loanCollectionID, $totalPDCAmount, $count, $customerName, $plateNumber, $loan_number, $stock_number, $customerAddress){
        
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

        $response = '<table border="0.5" width="100%" cellpadding="3" align="center">
                        <thead>
                            <tr>
                                <td colspan="6" style="text-align:center;background-color: #131920;color: #fff"><b>ACKNOWLEDGMENT ADVICE FOR POST-DATED CHECKS</b></td>
                            </tr>
                        </thead>
                        <tbody>
                            <tr style="font-size:8px; text-align:left">
                                <td colspan="6" style="text-align:right;"><b>DATE '. strtoupper(date('d-M-Y')) .'</b></td>
                            </tr>
                             <tr style="font-size:8; text-align:left">
                                <td style="text-align:left;">RECEIVED FROM</td>
                                <td colspan="5" style="text-align:left;">'. strtoupper($customerName) .'</td>
                            </tr>
                            <tr style="font-size:8; text-align:left">
                                <td style="text-align:left;">ADDRESS</td>
                                <td colspan="3" style="text-align:left;">'. $customerAddress .'</td>
                                <td style="text-align:left;">STOCK NO.</td>
                                <td colspan="1" style="text-align:left;">'. $stock_number .'</td>
                            </tr>
                            <tr style="font-size:8; text-align:left">
                                <td style="text-align:left;">TOTAL AMT OF CHECKS</td>
                                <td colspan="3" style="text-align:left;">'. number_format($totalPDCAmount, 2) .'</td>
                                <td style="text-align:left;">LOAN NO.</td>
                                <td colspan="1" style="text-align:left;">'. $loan_number .'</td>
                            </tr>
                            <tr style="font-size:8; text-align:left">
                                <td style="text-align:left;">NO. OF CHECKS</td>
                                <td colspan="3" style="text-align:left;">'. $count .'</td>
                                <td style="text-align:left;">PLATE NO.</td>
                                <td colspan="1" style="text-align:left;">'. $plateNumber .'</td>
                            </tr>
                            <tr>
                                <td colspan="6" style="text-align:center;background-color: #131920;color: #fff"></td>
                            </tr>
                            <tr style="font-size:8; text-align:left">
                                <td style="text-align:left;">RECEIVED BY:</td>
                                <td style="text-align:left;"></td>
                                <td style="text-align:left;">CHECKED BY:</td>
                                <td style="text-align:left;"></td>
                                <td style="text-align:left;">POSTED BY:</td>
                                <td style="text-align:left;"></td>
                            </tr>
                            <tr>
                                <td colspan="6" style="text-align:left"><p style="text-align:center"><b>AUTHORIZATION AND UNDERTAKING</b></p>
                                
                                    <p style="text-align:justify">This is to authorize the company to deposit any of the following checks upon due date of my monthly installment. In case the check is dishonored by the drawee bank for whatever reason or I requested to "HOLD" the check for deposit, I agreed to pay additional 3% per month late payment penalty or returned check charges.</p>
                                    
                                    <p style="text-align:justify">If my check is dishonored due to "Account Closed", I hereby undertake to replace the remaining checks to cover my monthly installment without need of demand or notice, otherwise, my entire outstanding obligation will become due and demandable. I also agreed to pay additional 3% per month late payment penalty or returned check charges.</p>

                                    <p style="text-align:justify">Finally, I hereby authorize the company to indicate any details on the face of the following checks based on the agreed terms and conditions as stipulated in the Deed of Conditional Sale.</p>

                                     <p style="text-align:center">______________________________________________<br/> Signature over printed name/Date</p>
                                </td>
                            </tr>
                            <tr>
                                <td style="text-align:center;background-color: #131920;color: #fff;" width="150">BANK/BRANCH</td>
                                <td style="text-align:center;background-color: #131920;color: #fff;" width="80">CHECK DATE</td>
                                <td style="text-align:center;background-color: #131920;color: #fff;" width="80">CHECK NO.</td>
                                <td style="text-align:center;background-color: #131920;color: #fff;" width="80">AMOUNT</td>
                                <td style="text-align:center;background-color: #131920;color: #fff;" width="80"> PAYMENT FOR</td>
                                <td style="text-align:center;background-color: #131920;color: #fff;" width="85">REMARKS</td>
                            </tr>
                            ';

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

            $response.= ' <tr style="font-size:8px; text-align:center">
                                <td>'. $bank_branch .'</td>
                                <td>'. $check_date .'</td>
                                <td>'. $check_number .'</td>
                                <td>'. number_format($payment_amount, 2) .'</td>
                                <td>'. $payment_details .'</td>
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