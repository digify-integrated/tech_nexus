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
    }

    $summaryTable = generatePrint($disbursementID);

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

    $pdf->SetFont('times', '', 11);
    $pdf->writeHTML($summaryTable, true, false, true, false, '');
    
    $pdf->Cell(140, 8, '', 0, 0, 'L');
    $pdf->Cell(22, 8, 'CDV NO.', 0, 0, 'L');
    $pdf->SetFont('times', 'U', 11);
    $pdf->Cell(60, 8, $transaction_number, 0, 0, 'L');

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

        $databaseModel = new DatabaseModel();
        $systemModel = new SystemModel();
        $userModel = new UserModel(new DatabaseModel, new SystemModel);
        $disbursementModel = new DisbursementModel($databaseModel);
        $employeeModel = new EmployeeModel($databaseModel);
        $departmentModel = new DepartmentModel($databaseModel);
        $customerModel = new CustomerModel($databaseModel);
        $jobPositionModel = new JobPositionModel($databaseModel);
        
        $disbursementDetails = $disbursementModel->getDisbursement($disbursementID);
        $createdBy = $disbursementDetails['created_by'];
        $particulars = $disbursementDetails['particulars'];
        $disbursement_amount = $disbursementDetails['disbursement_amount'];
        $customer_id = $disbursementDetails['customer_id'];

        $customerDetails = $customerModel->getPersonalInformation($customer_id);

        $customerName = strtoupper($customerDetails['file_as']) ?? null;
        $transaction_date = $systemModel->checkDate('summary', $disbursementDetails['transaction_date'], '', 'm/d/Y', '');

        // Created By
        $contactDetails = $userModel->getContactByID($createdBy);
        $createdByContactID = $contactDetails['contact_id'] ?? null;
        $createdByName = !empty($createdByContactID) ? ($employeeModel->getPersonalInformation($createdByContactID)['file_as'] ?? '--') : '--';

        $response = '<table border="2" width="100%" cellpadding="5" align="left">
                        <tbody>
                        <tr>
                            <td colspan="3" style="text-align: left"><b>PAYEE</b><br/>'. $customerName .'</td>
                            <td style="text-align: center"><b>DATE</b><br/>'. $transaction_date .'</td>
                        </tr>
                        <tr>
                            <td colspan="3" style="text-align: center"><b>PARTICULARS</b></td>
                            <td style="text-align: center"><b>AMOUNT</b></td>
                        </tr>
                        <tr>
                            <td colspan="3" style="text-align: left">'. $particulars .'<br/><br/><br/><br/><br/><br/><br/><br/><br/></td>
                            <td style="text-align: center">'. number_format($disbursement_amount, 2) .'</td>
                        </tr>
                        <tr>
                            <td style="text-align: left"><b>Prepared by:</b><br/>'. $createdByName .'</td>
                            <td style="text-align: left"><b>Checked by:</b><br/></td>
                            <td style="text-align: left"><b>Approved by:</b><br/></td>
                            <td style="text-align: left"><b>Received by:</b><br/></td>
                        </tr>
                    </tbody>
            </table>';

        return $response;
    }
?>