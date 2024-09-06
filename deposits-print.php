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
    
    if(isset($_GET['date'])){
        if(empty($_GET['date'])){
          header('location: dashboard.php');
          exit;
        }

        $reportDate = $_GET['date'];

        if(isset($_GET['id'])){
            $company_ids = $_GET['id'];

            if(!empty($company_ids)){

                $company_ids_array = explode(',', $company_ids);
    
                $quoted_company_ids_array = array_map(function($value) {
                    return "'" . $value . "'";
                }, $company_ids_array);
    
                $quoted_company_ids_string = implode(', ', $quoted_company_ids_array);
    
                $filterCompanyID = $quoted_company_ids_string;
            }
            else{
                $filterCompanyID = null;
            }
        }
        else{
            $filterCompanyID = null;
        }

        $dateAsOf = $systemModel->checkDate('empty', $reportDate, '', 'F d, Y', '');
    }

    $collectionsSummaryTable = generateCollectionsSummaryTable($dateAsOf, $filterCompanyID);
    $collectionsTotalSummaryTable = generateCollectionsTotalSummaryTable($dateAsOf, $filterCompanyID);
    $depositsSummaryTable = generateDepositsSummaryTable($dateAsOf, $filterCompanyID);
    $depositsTotalSummaryTable = generateDepositsTotalPrintSummaryTable($dateAsOf, $filterCompanyID);
    $onhandSummaryTable = generateOnhandSummaryTable($dateAsOf, $filterCompanyID);

    ob_start();

    // Create TCPDF instance
    $pdf = new TCPDF('L', 'mm', array(215.9, 330.2), true, 'UTF-8', false);

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
    $pdf->SetFont('times', '', 12);

    $pdf->MultiCell(0, 0, '<b>SUMMARY AS OF '. strtoupper($dateAsOf) .'</b>', 0, 'C', 0, 1, '', '', true, 0, true, true, 0);
    $pdf->Ln(10);
    $pdf->writeHTML($collectionsTotalSummaryTable, true, false, true, false, '');
    $pdf->writeHTML($depositsTotalSummaryTable, true, false, true, false, '');
    $pdf->writeHTML($onhandSummaryTable, true, false, true, false, '');
    $pdf->Ln(10);
    
    $pdf->MultiCell(0, 0, '<b>COLLECTIONS AS OF '. strtoupper($dateAsOf) .'</b>', 0, 'C', 0, 1, '', '', true, 0, true, true, 0);
    $pdf->Ln(10);
    $pdf->writeHTML($collectionsSummaryTable, true, false, true, false, '');

    
    $pdf->AddPage();
    $pdf->MultiCell(0, 0, '<b>DEPOSITS AS OF '. strtoupper($dateAsOf) .'</b>', 0, 'C', 0, 1, '', '', true, 0, true, true, 0);
    $pdf->Ln(10);
    $pdf->writeHTML($depositsSummaryTable, true, false, true, false, '');


    // Output the PDF to the browser
    $pdf->Output('deposits-print.pdf', 'I');
    ob_end_flush();

    function generateCollectionsSummaryTable($dateAsOf, $filterCompanyID){
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
        
        $dateAsOf = $systemModel->checkDate('empty', $dateAsOf, '', 'Y-m-d', '');

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
            </tr>
        </thead>
        <tbody style="font-weight: normal;">';

        $sql = $databaseModel->getConnection()->prepare('CALL generatePrintCollectionsTable(:dateAsOf, :filterCompanyID)');
        $sql->bindValue(':dateAsOf', $dateAsOf, PDO::PARAM_STR);
        $sql->bindValue(':filterCompanyID', $filterCompanyID, PDO::PARAM_STR);
        $sql->execute();
        $options = $sql->fetchAll(PDO::FETCH_ASSOC);
        $sql->closeCursor();


        foreach ($options as $row) {
            $collection_status = $row['collection_status'];
            $payment_amount = $row['payment_amount'];
            $customer_id = $row['customer_id'];
            $sales_proposal_id = $row['sales_proposal_id'];
            $loan_number = $row['loan_number'];
            $payment_details = $row['payment_details'];
            $bank_branch = $row['bank_branch'];
            $mode_of_payment = $row['mode_of_payment'];
            $check_number = $row['check_number'];
            $remarks = $row['remarks'];
            $pdc_type = $row['pdc_type'];
            $collected_from = $row['collected_from'];
            $deposited_to = $row['deposited_to'];
            $payment_date = $systemModel->checkDate('empty', $row['payment_date'], '', 'm/d/Y', '');
            $transaction_date = $systemModel->checkDate('empty', $row['transaction_date'], '', 'm/d/Y', '');

            $getBankDetails = $bankModel->getBank($deposited_to);
            $bank_name = $getBankDetails['bank_name'];

            $customerDetails = $customerModel->getPersonalInformation($customer_id);
            $customerName = $customerDetails['file_as'] ?? null;

            if($pdc_type == 'Product'){
                $product_id = $row['product_id'];

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
                $or_number = $row['reversal_reference_number'];
                $or_date = $systemModel->checkDate('empty', $row['reversal_reference_date'], '', 'm/d/Y', '');
            }
            else{
                $or_number = $row['or_number'];
                $or_date = $systemModel->checkDate('empty', $row['or_date'], '', 'm/d/Y', '');
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
            
                        
        }

        $response .= '
                </tbody>
        </table>';

        return $response;
    }

    function generateCollectionsTotalSummaryTable($dateAsOf, $filterCompanyID){
        require_once 'model/database-model.php';
        require_once 'model/pdc-management-model.php';
        require_once 'model/system-model.php';
        require_once 'model/customer-model.php';
        require_once 'model/product-model.php';
        require_once 'model/sales-proposal-model.php';
        require_once 'model/bank-model.php';
        require_once 'model/company-model.php';

        $databaseModel = new DatabaseModel();
        $systemModel = new SystemModel();
        $pdcManagementModel = new PDCManagementModel($databaseModel);
        $customerModel = new CustomerModel($databaseModel);
        $productModel = new ProductModel($databaseModel);
        $salesProposalModel = new SalesProposalModel($databaseModel);
        $bankModel = new BankModel($databaseModel);
        $companyModel = new CompanyModel($databaseModel);
        
        $dateAsOf = $systemModel->checkDate('empty', $dateAsOf, '', 'Y-m-d', '');

        $response = '<table border="0.5" width="40%" cellpadding="2" align="center">
        <thead style="font-weight: bold; background-color: red;">
            <tr>
                <td style="background-color: rgba(220, 38, 38, .8);"><b>COMPANY</b></td>
                <td style="background-color: rgba(220, 38, 38, .8);"><b>TOTAL COLLECTIONS</b></td>
            </tr>
        </thead>
        <tbody style="font-weight: normal;">';

        $sql = $databaseModel->getConnection()->prepare('CALL generatePrintCollectionsCompanyTotal(:dateAsOf, :filterCompanyID)');
        $sql->bindValue(':dateAsOf', $dateAsOf, PDO::PARAM_STR);
        $sql->bindValue(':filterCompanyID', $filterCompanyID, PDO::PARAM_STR);
        $sql->execute();
        $options = $sql->fetchAll(PDO::FETCH_ASSOC);
        $sql->closeCursor();

        $total_amount = 0;
        foreach ($options as $row) {
            $company_id = $row['company_id'];
            $payment_amount = $row['payment_amount'];

            $total_amount = $total_amount + $payment_amount;

            $companyName = $companyModel->getCompany($company_id)['company_name'] ?? null;

            $response.= ' <tr>
                                <td>'. $companyName .'</td>
                                <td>'. number_format($payment_amount, 2) .'</td>
                            </tr>';
            
                        
        }

        $response .= '<tr>
                                <td><b>TOTAL</b></td>
                                <td>'. number_format($total_amount, 2) .'</td>
                            </tr>
                </tbody>
        </table>';

        return $response;
    }

    function generateDepositsSummaryTable($dateAsOf, $filterCompanyID){
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
        
        $dateAsOf = $systemModel->checkDate('empty', $dateAsOf, '', 'Y-m-d', '');

        $response = '<table border="0.5" width="100%" cellpadding="2" align="center">
        <thead style="font-weight: bold; background-color: red;">
            <tr>
                <td style="background-color: rgba(220, 38, 38, .8);"><b>DEPOSIT AMOUNT</b></td>
                <td style="background-color: rgba(220, 38, 38, .8);"><b>DEPOSIT DATE</b></td>
                <td style="background-color: rgba(220, 38, 38, .8);"><b>DEPOSITED TO</b></td>
                <td style="background-color: rgba(220, 38, 38, .8);"><b>REFERENCE NUMBER</b></td>
                <td style="background-color: rgba(220, 38, 38, .8);"><b>TRANSACTION DATE</b></td>
            </tr>
        </thead>
        <tbody style="font-weight: normal;">';

        $sql = $databaseModel->getConnection()->prepare('CALL generatePrintDepositsTable(:dateAsOf, :filterCompanyID)');
        $sql->bindValue(':dateAsOf', $dateAsOf, PDO::PARAM_STR);
        $sql->bindValue(':filterCompanyID', $filterCompanyID, PDO::PARAM_STR);
        $sql->execute();
        $options = $sql->fetchAll(PDO::FETCH_ASSOC);
        $sql->closeCursor();


        foreach ($options as $row) {
            $depositAmount = $row['deposit_amount'];
            $depositedTo = $row['deposited_to'];
            $referenceNumber = $row['reference_number'];
            $depositDate = $systemModel->checkDate('empty', $row['deposit_date'], '', 'm/d/Y', '');
            $transactionDate = $systemModel->checkDate('empty', $row['transaction_date'], '', 'm/d/Y', '');

            $bankDetails = $bankModel->getBank($depositedTo);
            $bankName = $bankDetails['bank_name'];

            $response.= ' <tr>
                                <td>'. number_format($depositAmount, 2) .'</td>
                                <td>'. $depositDate .'</td>
                                <td>'. $bankName .'</td>
                                <td>'. $referenceNumber .'</td>
                                <td>'. $transactionDate .'</td>
                            </tr>';
            
                        
        }

        $response .= '
                </tbody>
        </table>';

        return $response;
    }

    function generateDepositsTotalPrintSummaryTable($dateAsOf, $filterCompanyID){
        require_once 'model/database-model.php';
        require_once 'model/pdc-management-model.php';
        require_once 'model/system-model.php';
        require_once 'model/customer-model.php';
        require_once 'model/product-model.php';
        require_once 'model/sales-proposal-model.php';
        require_once 'model/bank-model.php';
        require_once 'model/company-model.php';

        $databaseModel = new DatabaseModel();
        $systemModel = new SystemModel();
        $pdcManagementModel = new PDCManagementModel($databaseModel);
        $customerModel = new CustomerModel($databaseModel);
        $productModel = new ProductModel($databaseModel);
        $salesProposalModel = new SalesProposalModel($databaseModel);
        $bankModel = new BankModel($databaseModel);
        $companyModel = new CompanyModel($databaseModel);
        
        $dateAsOf = $systemModel->checkDate('empty', $dateAsOf, '', 'Y-m-d', '');

        $response = '<table border="0.5" width="40%" cellpadding="2" align="center">
        <thead style="font-weight: bold; background-color: red;">
            <tr>
                <td style="background-color: rgba(220, 38, 38, .8);"><b>COMPANY</b></td>
                <td style="background-color: rgba(220, 38, 38, .8);"><b>TOTAL DEPOSITS</b></td>
            </tr>
        </thead>
        <tbody style="font-weight: normal;">';

        $sql = $databaseModel->getConnection()->prepare('CALL generatePrintDepositsCompanyTotal(:dateAsOf, :filterCompanyID)');
        $sql->bindValue(':dateAsOf', $dateAsOf, PDO::PARAM_STR);
        $sql->bindValue(':filterCompanyID', $filterCompanyID, PDO::PARAM_STR);
        $sql->execute();
        $options = $sql->fetchAll(PDO::FETCH_ASSOC);
        $sql->closeCursor();

        $total_amount = 0;
        foreach ($options as $row) {

            $company_id = $row['company_id'];
            $deposit_amount = $row['deposit_amount'];

            $total_amount = $total_amount + $deposit_amount;

            $companyName = $companyModel->getCompany($company_id)['company_name'] ?? null;

            $response.= ' <tr>
                                <td>'. $companyName .'</td>
                                <td>'. number_format($deposit_amount, 2) .'</td>
                            </tr>';
            
                        
        }

        $response .= '<tr>
                                <td><b>TOTAL</b></td>
                                <td>'. number_format($total_amount, 2) .'</td>
                            </tr>
                </tbody>
        </table>';

        return $response;
    }

    function generateOnhandSummaryTable($dateAsOf, $filterCompanyID){
        require_once 'model/database-model.php';
        require_once 'model/pdc-management-model.php';
        require_once 'model/system-model.php';
        require_once 'model/customer-model.php';
        require_once 'model/product-model.php';
        require_once 'model/sales-proposal-model.php';
        require_once 'model/bank-model.php';
        require_once 'model/company-model.php';
        require_once 'model/collections-model.php';
        require_once 'model/deposits-model.php';
        require_once 'model/system-setting-model.php';

        $databaseModel = new DatabaseModel();
        $systemModel = new SystemModel();
        $pdcManagementModel = new PDCManagementModel($databaseModel);
        $customerModel = new CustomerModel($databaseModel);
        $productModel = new ProductModel($databaseModel);
        $salesProposalModel = new SalesProposalModel($databaseModel);
        $bankModel = new BankModel($databaseModel);
        $companyModel = new CompanyModel($databaseModel);
        $systemSettingModel = new SystemSettingModel($databaseModel);

        $collectionsModel = new CollectionsModel($databaseModel);
        $depositsModel = new DepositsModel($databaseModel);
        
        $dateAsOf = $systemModel->checkDate('empty', $dateAsOf, '', 'Y-m-d', '');

        $response = '<table border="0.5" width="40%" cellpadding="2" align="center">
        <thead style="font-weight: bold; background-color: red;">
            <tr>
                <td style="background-color: rgba(220, 38, 38, .8);"><b>COMPANY</b></td>
                <td style="background-color: rgba(220, 38, 38, .8);"><b>TOTAL ON-HAND</b></td>
            </tr>
        </thead>
        <tbody style="font-weight: normal;">';

        /*if(!empty($filterCompanyID)){
            $today = date("Y-m-d");
            if ($dateAsOf != $today) {
                if (strpos($filterCompanyID, "1") !== false) {
                    $company1Onhand = $systemSettingModel->getSystemSetting(11)['value'];

                    $company1OnhandCollectionsGreaterThanToday = $collectionsModel->getTotalCollectionsGreaterThanTodayByCompany($dateAsOf, 1);
                    $company1OnhandDepositsGreaterThanToday = $depositsModel->getTotalDepositsGreaterThanTodayByCompany($dateAsOf, 1);

                    $company1OnhandTotal = 
                } else {
                    $company1Onhand = 0;
                }
        
                if (strpos($filterCompanyID, "2") !== false) {
                    $company2Onhand = $systemSettingModel->getSystemSetting(12)['value'];
                } else {
                    $company2Onhand = 0;
                }
        
                if (strpos($filterCompanyID, "3") !== false) {
                    $company3Onhand = $systemSettingModel->getSystemSetting(13)['value'];
                } else {
                    $company3Onhand = 0;
                }
            }
        }
        else{
            $today = date("Y-m-d");
            if ($dateAsOf != $today) {
                
            }
            else{
                $company1Onhand = $systemSettingModel->getSystemSetting(11)['value'];
                $company2Onhand = $systemSettingModel->getSystemSetting(12)['value'];
                $company3Onhand = $systemSettingModel->getSystemSetting(13)['value'];
            }
           
        }*/

        if(!empty($filterCompanyID)){
            $company1Onhand = 0;
            if (strpos($filterCompanyID, "1") !== false) {
                $company1Name = $companyModel->getCompany(1)['company_name'] ?? null;
                $company1Onhand = $systemSettingModel->getSystemSetting(11)['value'];

                $response.= ' <tr>
                                <td>'. $company1Name .'</td>
                                <td>'. number_format($company1Onhand, 2) .'</td>
                            </tr>';
            } 

            $company2Onhand = 0;
            if (strpos($filterCompanyID, "2") !== false) {
                $company2Name = $companyModel->getCompany(2)['company_name'] ?? null;
                $company2Onhand = $systemSettingModel->getSystemSetting(12)['value'];

                $response.= ' <tr>
                                <td>'. $company2Name .'</td>
                                <td>'. number_format($company2Onhand, 2) .'</td>
                            </tr>';
            } 

            $company3Onhand = 0;
            if (strpos($filterCompanyID, "3") !== false) {
                $company3Name = $companyModel->getCompany(3)['company_name'] ?? null;
                $company3Onhand = $systemSettingModel->getSystemSetting(13)['value'];

                $response.= ' <tr>
                                <td>'. $company3Name .'</td>
                                <td>'. number_format($company3Onhand, 2) .'</td>
                            </tr>';
            }

            $total_amount = $company1Onhand + $company2Onhand + $company3Onhand;
        }
        else{
            $company1Onhand = $systemSettingModel->getSystemSetting(11)['value'];
            $company2Onhand = $systemSettingModel->getSystemSetting(12)['value'];
            $company3Onhand = $systemSettingModel->getSystemSetting(13)['value'];

            $company1Name = $companyModel->getCompany(1)['company_name'] ?? null;
            $company2Name = $companyModel->getCompany(2)['company_name'] ?? null;
            $company3Name = $companyModel->getCompany(3)['company_name'] ?? null;

            $total_amount = $company1Onhand + $company2Onhand + $company3Onhand;

            $response.= ' <tr>
                            <td>'. $company1Name .'</td>
                            <td>'. number_format($company1Onhand, 2) .'</td>
                        </tr>
                        <tr>
                            <td>'. $company2Name .'</td>
                            <td>'. number_format($company2Onhand, 2) .'</td>
                        </tr>
                        <tr>
                            <td>'. $company3Name .'</td>
                            <td>'. number_format($company3Onhand, 2) .'</td>
                        </tr>';
        }

       

        $response .= '<tr>
                                <td><b>TOTAL</b></td>
                                <td>'. number_format($total_amount, 2) .'</td>
                            </tr>
                </tbody>
        </table>';

        return $response;
    }
    
?>