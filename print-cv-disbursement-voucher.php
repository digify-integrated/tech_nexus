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
    require_once 'model/user-model.php';
    require_once 'model/customer-model.php';
    require_once 'model/employee-model.php';
    require_once 'model/department-model.php';
    require_once 'model/job-position-model.php';
    require_once 'model/travel-form-model.php';
    require_once 'model/disbursement-model.php';

    $databaseModel = new DatabaseModel();
    $systemModel = new SystemModel();
    $userModel = new UserModel(new DatabaseModel, new SystemModel);
    $disbursementModel = new DisbursementModel($databaseModel);

    if(isset($_GET['id'])){
        if(empty($_GET['id'])){
          header('location: dashboard.php');
          exit;
        }
        
        $disbursementID = $_GET['id'];

        $disbursementDetails = $disbursementModel->getDisbursement($disbursementID);
        $transaction_number = $disbursementDetails['transaction_number'] ?? '--';

        $checkCount = $disbursementModel->getDisbursementCheckCount($disbursementID)['total'];
        $checkTotal = $disbursementModel->getDisbursementCheckTotal($disbursementID)['total'];
    }

    $summaryTable = generatePrint($disbursementID);
    $summaryTable2 = generatePrintCheck($disbursementID);
    $summaryTable3 = generatePrint2($disbursementID);
    $summaryTable4 = generatePrintCheck2($disbursementID);

    ob_start();

    // Create TCPDF instance
    $pdf = new TCPDF('P', 'mm', array(330.2, 215.9), true, 'UTF-8', false);

   // Disable header and footer
    $pdf->setPrintHeader(false);
    $pdf->setPrintFooter(false);
    $pdf->SetPageOrientation('P');

    // Set PDF metadata
    $pdf->SetCreator('CGMI');
    $pdf->SetAuthor('CGMI');
    $pdf->SetTitle('Cash Disbursement Voucher');
    $pdf->SetSubject('Cash Disbursement Voucher');

    // Set margins and auto page break
    $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
    $pdf->SetMargins(15, 15, 15);
    $pdf->SetHeaderMargin(5);
    $pdf->SetFooterMargin(10);
    $pdf->SetAutoPageBreak(TRUE, 15);

    // Add a page
    $pdf->AddPage();

    $pdf->SetFont('times', '', 10);
    $pdf->writeHTML($summaryTable, true, false, true, false, '');
    $pdf->Cell(90, 4, 'NO. OF CHECKS ISSUED: ' . $checkCount, '', 0 , 'C');
    $pdf->Cell(10, 4, '     ', 0, 0 , 'L');
    $pdf->Cell(90, 4, 'TOTAL CHECKS ISSUED: ' . number_format($checkTotal, 2), '', 0, 'C');
    $pdf->Ln(10);
    $pdf->writeHTML($summaryTable2, true, false, true, false, '');
    // Add a page
    $pdf->AddPage();

    $pdf->SetFont('times', '', 10);
    $pdf->writeHTML($summaryTable3, true, false, true, false, '');
    $pdf->SetFillColor(255, 255, 197); // Light yellow
    $pdf->Cell(90, 4, 'NO. OF CHECKS ISSUED: ' . $checkCount, '', 0 , 'C');
    $pdf->Cell(10, 4, '     ', 0, 0 , 'L');
    $pdf->Cell(90, 4, 'TOTAL CHECKS ISSUED: ' . number_format($checkTotal, 2), '', 0, 'C');
    $pdf->Ln(10);
    $pdf->writeHTML($summaryTable4, true, false, true, false, '');
    

    // Output the PDF to the browser
    $pdf->Output('cash-disbursement-voucher.pdf', 'I');
    ob_end_flush();

    function generatePrint($disbursementID){
        
        require_once 'model/database-model.php';
        require_once 'model/pdc-management-model.php';
        require_once 'model/system-model.php';
        require_once 'model/user-model.php';
        require_once 'model/customer-model.php';
        require_once 'model/employee-model.php';
        require_once 'model/department-model.php';
        require_once 'model/job-position-model.php';
        require_once 'model/travel-form-model.php';
        require_once 'model/disbursement-model.php';
        require_once 'model/chart-of-account-model.php';

        $databaseModel = new DatabaseModel();
        $systemModel = new SystemModel();
        $userModel = new UserModel(new DatabaseModel, new SystemModel);
        $disbursementModel = new DisbursementModel($databaseModel);
        $employeeModel = new EmployeeModel($databaseModel);
        $departmentModel = new DepartmentModel($databaseModel);
        $customerModel = new CustomerModel($databaseModel);
        $jobPositionModel = new JobPositionModel($databaseModel);
        $chartOfAccountModel = new ChartOfAccountModel($databaseModel);
        
        $disbursementDetails = $disbursementModel->getDisbursement($disbursementID);
        $createdBy = $disbursementDetails['created_by'];
        $particulars = $disbursementDetails['particulars'];
        $customer_id = $disbursementDetails['customer_id'];
        $transaction_number = $disbursementDetails['transaction_number'];

        $disbursementTotal = $disbursementModel->getDisbursementTotal($disbursementID)['total'] ?? 0;

        $customerDetails = $customerModel->getPersonalInformation($customer_id);

        $customerName = strtoupper($customerDetails['file_as']) ?? null;
        $transaction_date = $systemModel->checkDate('summary', $disbursementDetails['transaction_date'], '', 'm/d/Y', '');

        // Created By
        $contactDetails = $userModel->getContactByID($createdBy);
        $createdByContactID = $contactDetails['contact_id'] ?? null;
        $createdByName = !empty($createdByContactID) ? ($employeeModel->getPersonalInformation($createdByContactID)['file_as'] ?? '--') : '--';

        $list = '';
        $sql = $databaseModel->getConnection()->prepare('CALL generateDisbursementParticularsTable(:disbursement_id)');
        $sql->bindValue(':disbursement_id', $disbursementID, PDO::PARAM_INT);
        $sql->execute();
        $options = $sql->fetchAll(PDO::FETCH_ASSOC);
        $sql->closeCursor();
            
        foreach ($options as $row) {
            $disbursement_particulars_id = $row['disbursement_particulars_id'];
            $chart_of_account_id = $row['chart_of_account_id'];
            $remarks = $row['remarks'];
            $particulars_amount = $row['particulars_amount'];

            $chartOfAccountDetails = $chartOfAccountModel->getChartOfAccount($chart_of_account_id);
            $chartOfAccountName = $chartOfAccountDetails['name'] ?? null;

            $list .= trim($chartOfAccountName) . ' (' . number_format($particulars_amount, 2) . ')';
        }

        $response = '<table border="2" width="100%" cellpadding="5" align="left">
                        <tbody>
                            <tr>
                                <td colspan="3" style="text-align: center; ; color: red"><b>CHECK VOUCHER</b></td>
                                <td style="text-align: center"><b style="color: red">NO.</b> '. $transaction_number .'</td>
                            </tr>
                            <tr>
                                <td colspan="3" style="text-align: left; "><b style="color: red">NAME OF SUPPLIER / PAYEE</b><br/>'. $customerName .'</td>
                                <td style="text-align: center"><b style="color: red">DATE</b><br/>'. $transaction_date .'</td>
                            </tr>
                            <tr>
                                <td colspan="3" style="text-align: center; color: red"><b>PARTICULARS</b></td>
                                <td style="text-align: center"></td>
                            </tr>
                            <tr>
                                <td colspan="3" style="text-align: left">
                                    '. $list .'
                                    <br/><br/><br/>'. $particulars .'
                                </td>
                                <td style="text-align: center"></td>
                            </tr>
                            <tr>
                                <td colspan="3" style="text-align: right; color: red">
                                    <b  style="color: red">TOTAL AMOUNT DUE</b>
                                </td>
                                <td style="text-align: center"><b>'. number_format($disbursementTotal, 2) .'</b></td>
                            </tr>
                            <tr>
                                <td style="text-align: left"><b>Prepared by:</b><br/>'. $createdByName .'</td>
                                <td style="text-align: left"><b>Checked by:</b><br/></td>
                                <td style="text-align: left"><b>Approved by:</b><br/></td>
                                <td style="text-align: left"><b>Checks Received by:</b><br/></td>
                            </tr>
                        </tbody>
                    </table>';

        return $response;
    }

    function generatePrintCheck($disbursementID){
        
        require_once 'model/database-model.php';
        require_once 'model/pdc-management-model.php';
        require_once 'model/system-model.php';
        require_once 'model/user-model.php';
        require_once 'model/customer-model.php';
        require_once 'model/employee-model.php';
        require_once 'model/department-model.php';
        require_once 'model/job-position-model.php';
        require_once 'model/travel-form-model.php';
        require_once 'model/disbursement-model.php';
        require_once 'model/chart-of-account-model.php';

        $databaseModel = new DatabaseModel();
        $systemModel = new SystemModel();
        $userModel = new UserModel(new DatabaseModel, new SystemModel);
        $disbursementModel = new DisbursementModel($databaseModel);
        $employeeModel = new EmployeeModel($databaseModel);
        $departmentModel = new DepartmentModel($databaseModel);
        $customerModel = new CustomerModel($databaseModel);
        $jobPositionModel = new JobPositionModel($databaseModel);
        $chartOfAccountModel = new ChartOfAccountModel($databaseModel);
        
        $disbursementDetails = $disbursementModel->getDisbursement($disbursementID);
        $createdBy = $disbursementDetails['created_by'];
        $particulars = $disbursementDetails['particulars'];
        $customer_id = $disbursementDetails['customer_id'];
        $transaction_number = $disbursementDetails['transaction_number'];

        $disbursementTotal = $disbursementModel->getDisbursementTotal($disbursementID)['total'] ?? 0;

        $customerDetails = $customerModel->getPersonalInformation($customer_id);

        $customerName = strtoupper($customerDetails['file_as']) ?? null;
        $transaction_date = $systemModel->checkDate('summary', $disbursementDetails['transaction_date'], '', 'm/d/Y', '');

        // Created By
        $contactDetails = $userModel->getContactByID($createdBy);
        $createdByContactID = $contactDetails['contact_id'] ?? null;
        $createdByName = !empty($createdByContactID) ? ($employeeModel->getPersonalInformation($createdByContactID)['file_as'] ?? '--') : '--';

        $list = '';
        $sql = $databaseModel->getConnection()->prepare('CALL generateDisbursementCheckTable(:disbursement_id)');
        $sql->bindValue(':disbursement_id', $disbursementID, PDO::PARAM_INT);
        $sql->execute();
        $options = $sql->fetchAll(PDO::FETCH_ASSOC);
        $sql->closeCursor();
            
        foreach ($options as $row) {
            $bank_branch = $row['bank_branch'];
            $check_number = $row['check_number'];
            $check_date = $systemModel->checkDate('empty', $row['check_date'], '', 'm/d/Y', '');
            $check_amount = $row['check_amount'];

            $list .= '<tr>
                <td>'. $bank_branch .'</td>
                <td style="text-align: center">'. $check_date .'</td>
                <td style="text-align: center">'. $check_number .'</td>
                <td style="text-align: center">'. number_format($check_amount, 2) .'</td>
            </tr>';
        }

        $response = '<table border="1" width="100%" cellpadding="5" align="left">
                        <tbody>
                            <tr>
                                <td style="text-align: center; color:white ; background-color: #963634"><b>BANK / BRANCH</b></td>
                                <td style="text-align: center; color:white ; background-color: #963634"><b>CHECK DATE</b></td>
                                <td style="text-align: center; color:white ; background-color: #963634"><b>CHECK NO.</b></td>
                                <td style="text-align: center; color:white ; background-color: #963634"><b>CHECK AMOUNT</b></td>
                            </tr>
                            '. $list .'
                        </tbody>
                    </table>';

        return $response;
    }

    function generatePrint2($disbursementID){
        
        require_once 'model/database-model.php';
        require_once 'model/pdc-management-model.php';
        require_once 'model/system-model.php';
        require_once 'model/user-model.php';
        require_once 'model/customer-model.php';
        require_once 'model/employee-model.php';
        require_once 'model/department-model.php';
        require_once 'model/job-position-model.php';
        require_once 'model/travel-form-model.php';
        require_once 'model/disbursement-model.php';
        require_once 'model/chart-of-account-model.php';

        $databaseModel = new DatabaseModel();
        $systemModel = new SystemModel();
        $userModel = new UserModel(new DatabaseModel, new SystemModel);
        $disbursementModel = new DisbursementModel($databaseModel);
        $employeeModel = new EmployeeModel($databaseModel);
        $departmentModel = new DepartmentModel($databaseModel);
        $customerModel = new CustomerModel($databaseModel);
        $jobPositionModel = new JobPositionModel($databaseModel);
        $chartOfAccountModel = new ChartOfAccountModel($databaseModel);
        
        $disbursementDetails = $disbursementModel->getDisbursement($disbursementID);
        $createdBy = $disbursementDetails['created_by'];
        $particulars = $disbursementDetails['particulars'];
        $customer_id = $disbursementDetails['customer_id'];
        $transaction_number = $disbursementDetails['transaction_number'];

        $disbursementTotal = $disbursementModel->getDisbursementTotal($disbursementID)['total'] ?? 0;

        $customerDetails = $customerModel->getPersonalInformation($customer_id);

        $customerName = strtoupper($customerDetails['file_as']) ?? null;
        $transaction_date = $systemModel->checkDate('summary', $disbursementDetails['transaction_date'], '', 'm/d/Y', '');

        // Created By
        $contactDetails = $userModel->getContactByID($createdBy);
        $createdByContactID = $contactDetails['contact_id'] ?? null;
        $createdByName = !empty($createdByContactID) ? ($employeeModel->getPersonalInformation($createdByContactID)['file_as'] ?? '--') : '--';

        $list = '';
        $sql = $databaseModel->getConnection()->prepare('CALL generateDisbursementParticularsTable(:disbursement_id)');
        $sql->bindValue(':disbursement_id', $disbursementID, PDO::PARAM_INT);
        $sql->execute();
        $options = $sql->fetchAll(PDO::FETCH_ASSOC);
        $sql->closeCursor();
            
        foreach ($options as $row) {
            $disbursement_particulars_id = $row['disbursement_particulars_id'];
            $chart_of_account_id = $row['chart_of_account_id'];
            $remarks = $row['remarks'];
            $particulars_amount = $row['particulars_amount'];

            $chartOfAccountDetails = $chartOfAccountModel->getChartOfAccount($chart_of_account_id);
            $chartOfAccountName = $chartOfAccountDetails['name'] ?? null;

            $list .= trim($chartOfAccountName) . ' (' . number_format($particulars_amount, 2) . ')';
        }

        $response = '<table style="background-color: rgb(255, 255, 197)" border="2" width="100%" cellpadding="5" align="left">
                        <tbody>
                            <tr>
                                <td colspan="3" style="text-align: center; ; color: red"><b>CHECK VOUCHER</b></td>
                                <td style="text-align: center"><b style="color: red">NO.</b> '. $transaction_number .'</td>
                            </tr>
                            <tr>
                                <td colspan="3" style="text-align: left; "><b style="color: red">NAME OF SUPPLIER / PAYEE</b><br/>'. $customerName .'</td>
                                <td style="text-align: center"><b style="color: red">DATE</b><br/>'. $transaction_date .'</td>
                            </tr>
                            <tr>
                                <td colspan="3" style="text-align: center; color: red"><b>PARTICULARS</b></td>
                                <td style="text-align: center"></td>
                            </tr>
                            <tr>
                                <td colspan="3" style="text-align: left">
                                    '. $list .'
                                    <br/><br/><br/>'. $particulars .'
                                </td>
                                <td style="text-align: center"></td>
                            </tr>
                            <tr>
                                <td colspan="3" style="text-align: right; color: red">
                                    <b  style="color: red">TOTAL AMOUNT DUE</b>
                                </td>
                                <td style="text-align: center"><b>'. number_format($disbursementTotal, 2) .'</b></td>
                            </tr>
                            <tr>
                                <td style="text-align: left"><b>Prepared by:</b><br/>'. $createdByName .'</td>
                                <td style="text-align: left"><b>Checked by:</b><br/></td>
                                <td style="text-align: left"><b>Approved by:</b><br/></td>
                                <td style="text-align: left"><b>Checks Received by:</b><br/></td>
                            </tr>
                        </tbody>
                    </table>';

        return $response;
    }

    function generatePrintCheck2($disbursementID){
        
        require_once 'model/database-model.php';
        require_once 'model/pdc-management-model.php';
        require_once 'model/system-model.php';
        require_once 'model/user-model.php';
        require_once 'model/customer-model.php';
        require_once 'model/employee-model.php';
        require_once 'model/department-model.php';
        require_once 'model/job-position-model.php';
        require_once 'model/travel-form-model.php';
        require_once 'model/disbursement-model.php';
        require_once 'model/chart-of-account-model.php';

        $databaseModel = new DatabaseModel();
        $systemModel = new SystemModel();
        $userModel = new UserModel(new DatabaseModel, new SystemModel);
        $disbursementModel = new DisbursementModel($databaseModel);
        $employeeModel = new EmployeeModel($databaseModel);
        $departmentModel = new DepartmentModel($databaseModel);
        $customerModel = new CustomerModel($databaseModel);
        $jobPositionModel = new JobPositionModel($databaseModel);
        $chartOfAccountModel = new ChartOfAccountModel($databaseModel);
        
        $disbursementDetails = $disbursementModel->getDisbursement($disbursementID);
        $createdBy = $disbursementDetails['created_by'];
        $particulars = $disbursementDetails['particulars'];
        $customer_id = $disbursementDetails['customer_id'];
        $transaction_number = $disbursementDetails['transaction_number'];

        $disbursementTotal = $disbursementModel->getDisbursementTotal($disbursementID)['total'] ?? 0;

        $customerDetails = $customerModel->getPersonalInformation($customer_id);

        $customerName = strtoupper($customerDetails['file_as']) ?? null;
        $transaction_date = $systemModel->checkDate('summary', $disbursementDetails['transaction_date'], '', 'm/d/Y', '');

        // Created By
        $contactDetails = $userModel->getContactByID($createdBy);
        $createdByContactID = $contactDetails['contact_id'] ?? null;
        $createdByName = !empty($createdByContactID) ? ($employeeModel->getPersonalInformation($createdByContactID)['file_as'] ?? '--') : '--';

        $list = '';
        $sql = $databaseModel->getConnection()->prepare('CALL generateDisbursementCheckTable(:disbursement_id)');
        $sql->bindValue(':disbursement_id', $disbursementID, PDO::PARAM_INT);
        $sql->execute();
        $options = $sql->fetchAll(PDO::FETCH_ASSOC);
        $sql->closeCursor();
            
        foreach ($options as $row) {
            $bank_branch = $row['bank_branch'];
            $check_number = $row['check_number'];
            $check_date = $systemModel->checkDate('empty', $row['check_date'], '', 'm/d/Y', '');
            $check_amount = $row['check_amount'];

            $list .= '<tr>
                <td>'. $bank_branch .'</td>
                <td style="text-align: center">'. $check_date .'</td>
                <td style="text-align: center">'. $check_number .'</td>
                <td style="text-align: center">'. number_format($check_amount, 2) .'</td>
            </tr>';
        }

        $response = '<table style="background-color: rgb(255, 255, 197)" border="1" width="100%" cellpadding="5" align="left">
                        <tbody>
                            <tr>
                                <td style="text-align: center; color:white ; background-color: #963634"><b>BANK / BRANCH</b></td>
                                <td style="text-align: center; color:white ; background-color: #963634"><b>CHECK DATE</b></td>
                                <td style="text-align: center; color:white ; background-color: #963634"><b>CHECK NO.</b></td>
                                <td style="text-align: center; color:white ; background-color: #963634"><b>CHECK AMOUNT</b></td>
                            </tr>
                            '. $list .'
                        </tbody>
                    </table>';

        return $response;
    }
?>