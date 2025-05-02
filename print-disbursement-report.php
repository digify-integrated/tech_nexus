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
    require_once 'model/system-setting-model.php';

    $databaseModel = new DatabaseModel();
    $systemModel = new SystemModel();
    $userModel = new UserModel(new DatabaseModel, new SystemModel);
    $disbursementModel = new DisbursementModel($databaseModel);
    $systemSettingModel = new SystemSettingModel($databaseModel);

    if(isset($_GET['id'])){
        if(empty($_GET['id'])){
          header('location: dashboard.php');
          exit;
        }
        
        $disbursementIDs = explode(',', $_GET['id']);

        $createdByDetails = $userModel->getUserByID($_SESSION['user_id']);
        $createdByName = strtoupper($createdByDetails['file_as'] ?? null);

        $pettyCashFund = $systemSettingModel->getSystemSetting(20)['value'] ?? 0;

        $disbursementTotal = 0;
        $replenishmentTotal = 0;
        foreach ($disbursementIDs as $disbursementID) {
            $disbursementDetails = $disbursementModel->getDisbursement($disbursementID);
            $disburse_status = $disbursementDetails['disburse_status'];
            $fund_source = $disbursementDetails['fund_source'];
            $transaction_type = $disbursementDetails['transaction_type'];

            $disbursementDetails = $disbursementModel->getDisbursementTotal($disbursementID);
            if($transaction_type == 'Replenishment'){
                $replenishmentTotal = $disbursementTotal + $disbursementDetails['total'] ?? 0;
            }

            if($fund_source == 'Petty Cash' && ($disburse_status == 'Posted' || $disburse_status == 'Replenished')) {
                $disbursementTotal = $disbursementTotal + $disbursementDetails['total'] ?? 0;
            }
        }

        if($replenishmentTotal === 0){
            $replenishmentTotal = $disbursementModel->getReplenishmentTotal(date('Y-m-d'))['total'] ?? 0;
        }
    }
    $type = $_GET['type'] ?? '';

    $summaryTable = generatePrint($disbursementIDs, $type);
    $summaryTable2 = generatePrint2($createdByName);
    $summaryTable3 = generatePrint3();
    $summaryTable4 = generatePrint4($pettyCashFund, $disbursementTotal, $replenishmentTotal);
    
    $summaryTable5 = generatePrint5($disbursementIDs);


    ob_start();

    // Create TCPDF instance
    $pdf = new TCPDF('L', 'mm', 'Folio', true, 'UTF-8', false);

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
    $pdf->AddPage('L');

    $pdf->SetFont('times', '', 15);
    $pdf->MultiCell(0, 0, '<b>DISBURSEMENT REPORT (' . strtoupper(date('M d, Y')) . ')</b>', 0, 'C', 0, 1, '', '', true, 0, true, true, 0);
    $pdf->Ln(5);
    $pdf->SetFont('times', '', 8);

    $tableWidth1 = 60; // Width for the first table
    $tableWidth2 = 60; // Width for the second table
    $tableWidth3 = 60; // Width for the third table

    // Set the starting Y position
    $y = $pdf->GetY();

    if($type != 'disbursement check'){
        // First table (2 columns)
        $pdf->SetXY(15, $y);
        $pdf->writeHTML($summaryTable2, true, false, true, false, '');

        // Second table (3 columns)
        $pdf->SetXY(15 + $tableWidth1 + 25, $y);
        $pdf->writeHTML($summaryTable3, true, false, true, false, '');

        // Second table (3 columns)
        $pdf->SetXY(15 + $tableWidth1 + 120, $y);
        $pdf->writeHTML($summaryTable4, true, false, true, false, '');
    }

    $pdf->Ln(0);
    $pdf->writeHTML($summaryTable, true, false, true, false, '');

    
    $pdf->AddPage('L');
    $pdf->writeHTML($summaryTable5, true, false, true, false, '');


    // Output the PDF to the browser
    $pdf->Output('cash-disbursement-voucher.pdf', 'I');
    ob_end_flush();

    function generatePrint($disbursementIDs, $type){        
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
        require_once 'model/company-model.php';
        require_once 'model/miscellaneous-client-model.php';

        $databaseModel = new DatabaseModel();
        $systemModel = new SystemModel();
        $userModel = new UserModel(new DatabaseModel, new SystemModel);
        $disbursementModel = new DisbursementModel($databaseModel);
        $employeeModel = new EmployeeModel($databaseModel);
        $departmentModel = new DepartmentModel($databaseModel);
        $customerModel = new CustomerModel($databaseModel);
        $jobPositionModel = new JobPositionModel($databaseModel);
        $chartOfAccountModel = new ChartOfAccountModel($databaseModel);
        $companyModel = new CompanyModel($databaseModel);
        $miscellaneousClientModel = new MiscellaneousClientModel($databaseModel);

        $list = '';

        foreach ($disbursementIDs as $disbursementID) {
            $disbursementDetails = $disbursementModel->getDisbursement($disbursementID);
            $transaction_date = $systemModel->checkDate('empty', $disbursementDetails['transaction_date'], '', 'm/d/Y', '');
            $transaction_number = $disbursementDetails['transaction_number'];
            $transaction_type = $disbursementDetails['transaction_type'];
            $fund_source = $disbursementDetails['fund_source'];
            $particulars = $disbursementDetails['particulars'];
            $customer_id = $disbursementDetails['customer_id'];
            $department_id = $disbursementDetails['department_id'];
            $disburse_status = $disbursementDetails['disburse_status'];
            $payable_type = $disbursementDetails['payable_type'];

            if($payable_type === 'Customer'){
                $customerDetails = $customerModel->getPersonalInformation($customer_id);
                $customerName = $customerDetails['file_as'] ?? null;
            }
            else{
                $miscellaneousClientDetails = $miscellaneousClientModel->getMiscellaneousClient($customer_id);
                $customerName = $miscellaneousClientDetails['client_name'] ?? null;
            }

            $disbursementDetails = $disbursementModel->getDisbursementTotal($disbursementID);
            $disbursementTotal = $disbursementDetails['total'] ?? 0;

            $departmentDetails = $departmentModel->getDepartment($department_id);
            $departmentName = $departmentDetails['department_name'] ?? null;

            if(($fund_source == 'Petty Cash' || $type === 'disbursement check') && ($disburse_status == 'Posted' || $disburse_status == 'Replenished' || $disburse_status === 'Cancelled') && $transaction_type != 'Replenishment') {
                $sql = $databaseModel->getConnection()->prepare('CALL generateDisbursementParticularsTable(:disbursementID)');
                $sql->bindValue(':disbursementID', $disbursementID, PDO::PARAM_INT);
                $sql->execute();
                $options = $sql->fetchAll(PDO::FETCH_ASSOC);
                $sql->closeCursor();

                foreach ($options as $row) {
                    $disbursement_particulars_id = $row['disbursement_particulars_id'];
                    $chart_of_account_id = $row['chart_of_account_id'];
                    $company_id = $row['company_id'];
                    $remarks = $row['remarks'];
                    $particulars_amount = $row['particulars_amount'];
                    $with_vat = $row['with_vat'];
                    $with_withholding = $row['with_withholding'];
                    $vat_amount = $row['vat_amount'];
                    $withholding_amount = $row['withholding_amount'];
                    $base_amount = $row['base_amount'];
                    $total_amount = $row['total_amount'];

                    $companyDetails = $companyModel->getCompany($company_id);
                    $companyName = $companyDetails['company_name'] ?? null;

                    $chartOfAccountDetails = $chartOfAccountModel->getChartOfAccount($chart_of_account_id);
                    $chartOfAccountName = $chartOfAccountDetails['name'] ?? null;

                    if($disburse_status === 'Cancelled'){
                        $list .= '<tr>
                            <td>'. $transaction_number .'</td>
                            <td>'. $customerName .'</td>
                            <td>'. $companyName .'</td>
                            <td>'. $particulars .'</td>
                            <td>'. $chartOfAccountName .'</td>
                            <td>0.00</td>
                            <td>0.00</td>
                        </tr>
                        <tr>
                            <td>'. $transaction_number .'</td>
                            <td>'. $customerName .'</td>
                            <td>'. $companyName .'</td>
                            <td>'. $particulars .'</td>
                            <td>Petty Cash Fund</td>
                            <td>0.00</td>
                            <td>0.00</td>
                        </tr>';
                    }
                    else{
                        $base_amount = ($base_amount + $vat_amount) - $withholding_amount;

                        $list .= '<tr>
                            <td>'. $transaction_number .'</td>
                            <td>'. $customerName .'</td>
                            <td>'. $companyName .'</td>
                            <td>'. $particulars .'</td>
                            <td>'. $chartOfAccountName .'</td>
                            <td>'. number_format($base_amount, 2) .'</td>
                            <td>0.00</td>
                        </tr>
                        <tr>
                            <td>'. $transaction_number .'</td>
                            <td>'. $customerName .'</td>
                            <td>'. $companyName .'</td>
                            <td>'. $particulars .'</td>
                            <td>Petty Cash Fund</td>
                            <td>0.00</td>
                            <td>'. number_format($base_amount, 2) .'</td>
                        </tr>';

                        if($with_vat === 'Yes'){
                            $list .= '<tr>
                                <td>'. $transaction_number .'</td>
                                <td>'. $customerName .'</td>
                                <td>'. $companyName .'</td>
                                <td>'. $particulars .'</td>
                                <td>Input Tax</td>
                                <td>'. number_format($vat_amount, 2) .'</td>
                                <td>0.00</td>
                            </tr>';
                        }

                        if($with_withholding === 'Yes'){
                            $list .= '<tr>
                                    <td>'. $transaction_number .'</td>
                                    <td>'. $customerName .'</td>
                                    <td>'. $companyName .'</td>
                                    <td>'. $particulars .'</td>
                                    <td>Withholding Tax Payable Other</td>
                                    <td>'. number_format($withholding_amount * -1, 2) .'</td>
                                    <td>0.00</td>
                                </tr>';
                        }
                    }
                }
            }
        }
       
        $response = '<table border="1" width="100%" cellpadding="5" align="left">
                        <tbody>
                            <tr>
                                <td width="7%" style="text-align: center; background-color: #92CDDC"><b>CDV No</b></td>
                                <td width="15%" style="text-align: center; background-color: #92CDDC"><b>Customer</b></td>
                                <td width="10%" style="text-align: center; background-color: #92CDDC"><b>Company</b></td>
                                <td width="40%" style="text-align: center; background-color: #92CDDC"><b>Particulars</b></td>
                                <td width="12%" style="text-align: center; background-color: #92CDDC"><b>Account Title</b></td>
                                <td width="8%" style="text-align: center; background-color: #92CDDC"><b>Debit</b></td>
                                <td width="8%" style="text-align: center; background-color: #92CDDC"><b>Credit</b></td>
                            </tr>
                            '. $list .'
                        </tbody>
                    </table>';

        return $response;
    }

    function generatePrint2($createdByName): string{        
        $response = '<table border="1" width="30%" cellpadding="5" align="left">
                        <tbody>
                            <tr>
                                <td>LIQ RF YARD</td>
                                <td><b>0.00</b></td>
                            </tr>
                            <tr>
                                <td>DISB FOR LIQ - RF YARD</td>
                                <td><b>0.00</b></td>
                            </tr>
                            <tr>
                                <td>DISB FOR LIQ</td>
                                <td><b>0.00</b></td>
                            </tr>
                            <tr>
                                <td><b>PREPARED BY:</b></td>
                                <td><b>'. $createdByName .'</b></td>
                            </tr>
                            <tr>
                                <td><b>CHECKED BY:</b></td>
                                <td><b></b></td>
                            </tr>
                        </tbody>
                    </table>';

        return $response;
    }

    function generatePrint3(){        
        $response = '<table border="1" width="50%" cellpadding="5" align="left">
                        <tbody>
                            <tr>
                                <td style="background-color: #FFFFCC"><b>DENOMINATION</b></td>
                                <td style="background-color: #FFFFCC"><b>NO. OF PCS</b></td>
                                <td style="background-color: #FFFFCC"><b>TOTAL CASH</b></td>
                            </tr>
                            <tr>
                                <td>1,000</td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>500</td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>200</td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>100</td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>50</td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>20</td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td><b>FUND BALANCE</b></td>
                                <td></td>
                                <td></td>
                            </tr>
                        </tbody>
                    </table>';

        return $response;
    }

    function generatePrint4($pettyCashFund, $disbursementTotal, $replenishmentTotal){
        
        $total = ($pettyCashFund + $disbursementTotal) - $replenishmentTotal;
        $response = '<table border="1" width="100%" cellpadding="5" align="left">
                        <tbody>
                            <tr>
                                <td>FUND BALANCE - BEGINNING</td>
                                <td>'. number_format($total, 2) .'</td>
                            </tr>
                            <tr>
                                <td>ADD: REPLENISHMENT</td>
                                <td>'. number_format($replenishmentTotal, 2) .'</td>
                            </tr>
                            <tr>
                                <td>LIQUIDATION</td>
                                <td>0.00</td>
                            </tr>
                            <tr>
                                <td>LIQUIDATION RF YARD</td>
                                <td>0.00</td>
                            </tr>
                            <tr>
                                <td>LESS: DISBURSEMENT</td>
                                <td>'. number_format($disbursementTotal, 2) .'</td>
                            </tr>
                            <tr>
                                <td>DISBURSEMENT - LIQUIDATION</td>
                                <td>0.00</td>
                            </tr>
                            <tr>
                                <td>DISBURSEMENT - LIQUIDATION RF</td>
                                <td>0.00</td>
                            </tr>
                            <tr>
                                <td><b>FUND BALANCE - ENDING</b></td>
                                <td>'. number_format($pettyCashFund, 2) .'</td>
                            </tr>
                        </tbody>
                    </table>';

        return $response;
    }

    function generatePrint5($disbursementIDs){     
        
        $formattedIDs = "'" . implode("', '", $disbursementIDs) . "'";
        
        require_once 'model/database-model.php';

        $databaseModel = new DatabaseModel();

        $list = '';

        $totalDebit = 0;
        $totalCredit = 0;

        $sql = $databaseModel->getConnection()->prepare('CALL generateDisbursementParticularsEntryTable(:formattedIDs)');
        $sql->bindValue(':formattedIDs', $formattedIDs, PDO::PARAM_STR);
        $sql->execute();
        $options = $sql->fetchAll(PDO::FETCH_ASSOC);
        $sql->closeCursor();

        foreach ($options as $row) {
            $journal_item = $row['journal_item'];
            $debit = $row['debit'];
            $credit = $row['credit'];

            $totalDebit = $totalDebit + $debit;
            $totalCredit = $totalCredit + $credit;

            $list .= '<tr>
                <td>'. $journal_item .'</td>
                <td style="text-align: center;">'. number_format($debit, 2) .'</td>
                <td style="text-align: center;">'. number_format($credit, 2) .'</td>
            </tr>';
        }

        $response = '<table border="1" width="100%" cellpadding="5" align="left">
                        <tbody>
                            <tr>
                                <td width="50%" style="text-align: center; background-color: #92CDDC;"><b>Journal Item</b></td>
                                <td width="25%" style="text-align: center; background-color: #92CDDC;"><b>Debit</b></td>
                                <td width="25%" style="text-align: center; background-color: #92CDDC;"><b>Credit</b></td>
                            </tr>
                            '. $list .'
                            
                            <tr>
                                <td style="text-align: right;"><b>Total</b></td>
                                <td style="text-align: center;"><b>'. number_format($totalDebit, 2) .'</b></td>
                                <td style="text-align: center;"><b>'. number_format($totalCredit, 2) .'</b></td>
                            </tr>
                        </tbody>
                    </table>';

        return $response;
    }

?>