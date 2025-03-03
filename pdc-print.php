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
    require_once 'model/bank-model.php';

    $databaseModel = new DatabaseModel();
    $systemModel = new SystemModel();
    $pdcManagementModel = new PDCManagementModel($databaseModel);
    $customerModel = new CustomerModel($databaseModel);
    $productModel = new ProductModel($databaseModel);
    $salesProposalModel = new SalesProposalModel($databaseModel);
    $bankModel = new BankModel($databaseModel);
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
        $uniqueCheckNumbers = array();
        $depositTotals = array();
        
        foreach ($loanCollectionIDs as $loanCollectionID) {
            $pdcManagementDetails = $pdcManagementModel->getPDCManagement($loanCollectionID);
            $collection_status = $pdcManagementDetails['collection_status'];
            $payment_amount = $pdcManagementDetails['payment_amount'];
            $check_number = $pdcManagementDetails['check_number'];
            $deposit_to = $pdcManagementDetails['deposited_to'] ?? null;
        
            $bankDetails = $bankModel->getBank($deposit_to);
            $bank = $bankDetails['bank_name'] ?? null;
        
            $totalPDCAmount = $totalPDCAmount + $payment_amount;
        
            if($collection_status == 'Reversed'){
                $totalReversedPDCAmount = $totalReversedPDCAmount + $payment_amount;
            }
        
            if(!in_array($check_number, $uniqueCheckNumbers)){
                $uniqueCheckNumbers[] = $check_number;
            }
        
            if(!isset($depositTotals[$bank])){
                $depositTotals[$bank] = 0;
            }
        
            $depositTotals[$bank] += $payment_amount;
        }
        
        $uniqueCheckCount = count($uniqueCheckNumbers);
        
        $netPayments = $totalPDCAmount - $totalReversedPDCAmount;
    }

    $summaryTable = generatePDC($loanCollectionIDs);

    ob_start();

    // Create TCPDF instance
    $pdf = new TCPDF('P', 'mm', array( 215.9, 330.2), true, 'UTF-8', false);

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
    $pdf->Cell(80, 8, 'TOTAL NUMBER OF PDC', 1, 0, 'L'); // 1 = border
    $pdf->Cell(80, 8, number_format($uniqueCheckCount, 0), 1, 0, 'L'); // 1 = border
    $pdf->Ln(8);

    foreach($depositTotals as $bank => $total){
        $pdf->Cell(80, 8, $bank, 1, 0, 'L'); // 1 = border
        $pdf->Cell(80, 8, number_format($total, 2), 1, 0, 'L'); // 1 = border
        $pdf->Ln(8);
    }
    

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
        require_once 'model/leasing-application-model.php';
        require_once 'model/miscellaneous-client-model.php';
        require_once 'model/sales-proposal-model.php';
        require_once 'model/tenant-model.php';

        $databaseModel = new DatabaseModel();
        $systemModel = new SystemModel();
        $pdcManagementModel = new PDCManagementModel($databaseModel);
        $customerModel = new CustomerModel($databaseModel);
        $productModel = new ProductModel($databaseModel);
        $salesProposalModel = new SalesProposalModel($databaseModel);
        $leasingApplicationModel = new LeasingApplicationModel($databaseModel);
        $miscellaneousClientModel = new MiscellaneousClientModel($databaseModel);
        $tenantModel = new TenantModel($databaseModel);

        $loanCollectionIDs = is_array($loanCollectionID) ? $loanCollectionID : explode(',', $loanCollectionID);
        sort($loanCollectionIDs);

        $pdcData = array();

        $response = '<table border="0.5" width="100%" cellpadding="2" align="center">
                        <thead>
                            <tr>
                                <td style="background-color: rgba(220, 38, 38, .8);"><b>REF DATE</b></td>
                                <td style="background-color: rgba(220, 38, 38, .8);"><b>REF NO.</b></td>
                                <td style="background-color: rgba(220, 38, 38, .8);"><b>REF AMOUNT</b></td>
                                <td style="background-color: rgba(220, 38, 38, .8);"><b>CHECK NO.</b></td>
                                <td style="background-color: rgba(220, 38, 38, .8);"><b>CHECK DATE</b></td>
                                <td style="background-color: rgba(220, 38, 38, .8);"><b>CUSTOMER</b></td>
                                <td style="background-color: rgba(220, 38, 38, .8);"><b>STOCK NO.</b></td>
                                <td style="background-color: rgba(220, 38, 38, .8);"><b>LOAN NO.</b></td>
                                <td style="background-color: rgba(220, 38, 38, .8);"><b>PYMT DETAILS</b></td>
                                <td style="background-color: rgba(220, 38, 38, .8);"><b>BANK / BRANCH</b></td>
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
            $leasing_application_id = $pdcManagementDetails['leasing_application_id'];
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
            else if($pdc_type == 'Leasing'){
                $leasingApplicationDetails = $leasingApplicationModel->getLeasingApplication($leasing_application_id);

                $loan_number = $leasingApplicationDetails['leasing_application_number'];
                $tenant_id = $leasingApplicationDetails['tenant_id'];

                $tenantDetails = $tenantModel->getTenant($tenant_id);
                $customerName = strtoupper($tenantDetails['tenant_name'] ?? '');
                $stockNumber = '';
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

            $pdcData[] = array(
                'ref_date' => $or_date,
                'ref_no' => $or_number,
                'ref_amount' => $payment_amount,
                'check_no' => $check_number,
                'check_date' => $check_date,
                'customer' => $customerName,
                'stock_no' => $stockNumber,
                'loan_no' => $loan_number,
                'pymt_details' => $payment_details,
                'bank_branch' => $bank_branch,
                'check_status' => $collection_status,
                'for_deposit_on' => $new_deposit_date,
                'remarks' => $remarks
            );
            $i++;
        }

        usort($pdcData, function($a, $b) {
            if ($a['ref_no'] == '' && $b['ref_no'] != '') {
                return 1;
            } elseif ($a['ref_no'] != '' && $b['ref_no'] == '') {
                return -1;
            } elseif ($a['ref_no'] == '' && $b['ref_no'] == '') {
                return strcmp($a['check_no'], $b['check_no']);
            } else {
                if ($a['ref_no'] < $b['ref_no']) {
                    return -1;
                } elseif ($a['ref_no'] > $b['ref_no']) {
                    return 1;
                } else {
                    return strcmp($a['check_no'], $b['check_no']);
                }
            }
        });

        foreach ($pdcData as $row) {
            $response .= ' <tr>
                                <td>'. $row['ref_date'] .'</td>
                                <td>'. $row['ref_no'] .'</td>
                                <td>'. number_format($row['ref_amount'], 2) .'</td>
                                <td>'. $row['check_no'] .'</td>
                                <td>'. $row['check_date'] .'</td>
                                <td>'. $row['customer'] .'</td>
                                <td>'. $row['stock_no'] .'</td>
                                <td>'. $row['loan_no'] .'</td>
                                <td>'. $row['pymt_details'] .'</td>
                                <td>'. $row['bank_branch'] .'</td>
                                <td>'. $row['check_status'] .'</td>
                                <td>'. $row['for_deposit_on'] .'</td>
                                <td>'. $row['remarks'] .'</td>
                            </tr>';
        }

        $response .= '
                    </tbody>
            </table>';

        return $response;
    }
?>