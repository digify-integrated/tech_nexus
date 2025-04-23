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
    require_once 'model/miscellaneous-client-model.php';
    require_once 'model/user-model.php';

    $databaseModel = new DatabaseModel();
    $systemModel = new SystemModel();
    $pdcManagementModel = new PDCManagementModel($databaseModel);
    $customerModel = new CustomerModel($databaseModel);
    $productModel = new ProductModel($databaseModel);
    $salesProposalModel = new SalesProposalModel($databaseModel);
    $userModel = new UserModel($databaseModel, $systemModel);
    $miscellaneousClientModel = new MiscellaneousClientModel($databaseModel);
    
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
            $collection_status = $pdcManagementDetails['collection_status'] ?? null;
            $payment_amount = $pdcManagementDetails['payment_amount'] ?? 0;

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
    $pdf->Cell(80, 8, 'TOTAL COLLECTION PAYMENTS', 1, 0, 'L'); // 1 = border
    $pdf->Cell(80, 8, number_format($totalPDCAmount, 2), 1, 0, 'L'); // 1 = border
    $pdf->Ln(8);
    $pdf->Cell(80, 8, 'TOTAL RETURNED COLLECTIONS', 1, 0, 'L'); // 1 = border
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
        require_once 'model/miscellaneous-client-model.php';
        require_once 'model/leasing-application-model.php';
        require_once 'model/tenant-model.php';
        
        $databaseModel = new DatabaseModel();
        $systemModel = new SystemModel();
        $pdcManagementModel = new PDCManagementModel($databaseModel);
        $tenantModel = new TenantModel($databaseModel);
        $leasingApplicationModel = new LeasingApplicationModel($databaseModel);
        $customerModel = new CustomerModel($databaseModel);
        $productModel = new ProductModel($databaseModel);
        $salesProposalModel = new SalesProposalModel($databaseModel);
        $bankModel = new BankModel($databaseModel);
        $miscellaneousClientModel = new MiscellaneousClientModel($databaseModel);

        $loanCollectionIDs = is_array($loanCollectionID) ? $loanCollectionID : explode(',', $loanCollectionID);
        sort($loanCollectionIDs);

        $response = '<table border="0.5" width="100%" cellpadding="2" align="center">
                       <thead style="font-weight: bold; background-color: red;">
                            <tr>
                                <td style="background-color: rgba(220, 38, 38, .8);"><b>OR DATE</b></td>
                                <td style="background-color: rgba(220, 38, 38, .8);"><b>OR NO.</b></td>
                                <td style="background-color: rgba(220, 38, 38, .8);"><b>PAYMENT AMOUNT</b></td>
                                <td style="background-color: rgba(220, 38, 38, .8);"><b>PAYMENT DATE</b></td>
                                <td style="background-color: rgba(220, 38, 38, .8);"><b>TRANSACTION DATE</b></td>
                                <td style="background-color: rgba(220, 38, 38, .8);"><b>CUSTOMER</b></td>
                                <td style="background-color: rgba(220, 38, 38, .8);"><b>STOCK NO.</b></td>
                                <td style="background-color: rgba(220, 38, 38, .8);"><b>LOAN NO.</b></td>
                                <td style="background-color: rgba(220, 38, 38, .8);"><b>COLLECTED FROM</b></td>
                                <td style="background-color: rgba(220, 38, 38, .8);"><b>DEPOSITED TO</b></td>
                                <td style="background-color: rgba(220, 38, 38, .8);"><b>PYMT DETAILS</b></td>
                                <td style="background-color: rgba(220, 38, 38, .8);"><b>MODE OF PAYMENT</b></td>
                                <td style="background-color: rgba(220, 38, 38, .8);"><b>COLLECTION STATUS</b></td>
                                <td style="background-color: rgba(220, 38, 38, .8);"><b>REMARKS</b></td>
                                <td style="background-color: rgba(220, 38, 38, .8);"><b>PAYMENT ADVICE</b></td>
                            </tr>
                        </thead>
                        <tbody style="font-weight: normal;">';

        $totalAmount = 0;

        $count = count($loanCollectionIDs);
        $i = 0;

        foreach ($loanCollectionIDs as $loanCollectionID) {
            $pdcManagementDetails = $pdcManagementModel->getPDCManagement($loanCollectionID);
            $collection_status = $pdcManagementDetails['collection_status'] ?? null;
            $payment_amount = $pdcManagementDetails['payment_amount'] ?? null;
            $customer_id = $pdcManagementDetails['customer_id'] ?? null;
            $sales_proposal_id = $pdcManagementDetails['sales_proposal_id'] ?? null;
            $loan_number = $pdcManagementDetails['loan_number'] ?? null;
            $payment_details = $pdcManagementDetails['payment_details'] ?? null;
            $bank_branch = $pdcManagementDetails['bank_branch'] ?? null;
            $mode_of_payment = $pdcManagementDetails['mode_of_payment'] ?? null;
            $check_number = $pdcManagementDetails['check_number'] ?? null;
            $remarks = $pdcManagementDetails['remarks'] ?? null;
            $pdc_type = $pdcManagementDetails['pdc_type'] ?? null;
            $collected_from = $pdcManagementDetails['collected_from'] ?? null;
            $deposited_to = $pdcManagementDetails['deposited_to'] ?? null;
            $payment_advice = $pdcManagementDetails['payment_advice'] ?? null;
            $leasing_application_repayment_id = $pdcManagementDetails['leasing_application_repayment_id'] ?? null;
            $leasing_other_charges_id = $pdcManagementDetails['leasing_other_charges_id'] ?? null;
            $payment_date = $systemModel->checkDate('empty', $pdcManagementDetails['payment_date'] ?? null, '', 'm/d/Y', '');
            $transaction_date = $systemModel->checkDate('empty', $pdcManagementDetails['transaction_date'] ?? null, '', 'm/d/Y', '');

            $getBankDetails = $bankModel->getBank($deposited_to);
            $bank_name = $getBankDetails['bank_name'] ?? null;

            $miscellaneousClientDetails = $miscellaneousClientModel->getMiscellaneousClient($collected_from);
            $customerName2 = $miscellaneousClientDetails['client_name'] ?? null;

            $customerDetails = $customerModel->getPersonalInformation($customer_id);
            $customerName = $customerDetails['file_as'] ?? null;

            if($pdc_type == 'Product'){
                $product_id = $pdcManagementDetails['product_id'] ?? null;

                $productDetails = $productModel->getProduct($product_id);
                $stockNumber = $productDetails['stock_number'] ?? null;
            }
            else if($pdc_type == 'Loan'){
                $salesProposalDetails = $salesProposalModel->getSalesProposal($sales_proposal_id);
                $product_id = $salesProposalDetails['product_id'] ?? null;

                $productDetails = $productModel->getProduct($product_id);
                $stockNumber = $productDetails['stock_number'] ?? null;
            }
            else if($pdc_type == 'Leasing' || $pdc_type === 'Leasing Other'){

                if($pdc_type === 'Leasing'){
                    $leasingApplicationRepaymentDetails = $leasingApplicationModel->getLeasingApplicationRepayment($leasing_application_repayment_id);
                    $leasing_application_id = $leasingApplicationRepaymentDetails['leasing_application_id'] ?? '';
                }
                else{
                    $leasingApplicationOtherChargesDetails = $leasingApplicationModel->getLeasingOtherCharges($leasing_other_charges_id);
                    $leasing_application_id = $leasingApplicationOtherChargesDetails['leasing_application_id'] ?? '';
                }
                
                $leasingApplicationDetails = $leasingApplicationModel->getLeasingApplication($leasing_application_id);

                $loan_number = $leasingApplicationDetails['leasing_application_number'] ?? '';
                $tenant_id = $leasingApplicationDetails['tenant_id'] ?? '';

                $tenantDetails = $tenantModel->getTenant($tenant_id);
                $customerName = strtoupper($tenantDetails['tenant_name'] ?? '');
                $stockNumber = '';
            }
            else{
                $stockNumber = '';
            }

            if($collection_status == 'Reversed'){
                $or_number = $pdcManagementDetails['reversal_reference_number'] ?? null;
                $or_date = $systemModel->checkDate('empty', $pdcManagementDetails['reversal_reference_date'], '', 'm/d/Y', '');
            }
            else{
                $or_number = $pdcManagementDetails['or_number'] ?? null;
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
                                <td>'. $customerName2 .'</td>
                                <td>'. $bank_name .'</td>
                                <td>'. $payment_details .'</td>
                                <td>'. $mode_of_payment .'</td>
                                <td>'. $collection_status .'</td>
                                <td>'. $remarks .'</td>
                                <td>'. $payment_advice .'</td>
                            </tr>';
            $i++;
        }

        $response .= '
                    </tbody>
            </table>';

        return $response;
    }
?>