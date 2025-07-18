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
    require_once 'model/sales-proposal-model.php';
    require_once 'model/product-model.php';
    require_once 'model/product-subcategory-model.php';
    require_once 'model/ci-report-model.php';

    $databaseModel = new DatabaseModel();
    $systemModel = new SystemModel();
    $userModel = new UserModel(new DatabaseModel, new SystemModel);
    $ciReportModel = new CIReportModel($databaseModel);
    $productModel = new ProductModel($databaseModel);
    $salesProposalModel = new SalesProposalModel($databaseModel);
    $customerModel = new CustomerModel($databaseModel);
    $productSubcategoryModel = new ProductSubcategoryModel($databaseModel);

    if(isset($_GET['id'])){
        if(empty($_GET['id'])){
          header('location: loan-proposal.php');
          exit;
        }

        $ci_report_id = $_GET['id'];

        // CI Report
        $ciReportDetails = $ciReportModel->getCIReport($ci_report_id);
        $sales_proposal_id = $ciReportDetails['sales_proposal_id'] ?? '';
        $contact_id = $ciReportDetails['contact_id'] ?? '';
        $appraiser = $ciReportDetails['appraiser'] ?? '';
        $investigator = $ciReportDetails['investigator'] ?? '';
        $narrative_summary = $ciReportDetails['narrative_summary'] ?? '';
        $purpose_of_loan = $ciReportDetails['purpose_of_loan'] ?? '';
        $ci_character = $ciReportDetails['ci_character'] ?? '';
        $ci_capacity = $ciReportDetails['ci_capacity'] ?? '';
        $ci_capital = $ciReportDetails['ci_capital'] ?? '';
        $ci_collateral = $ciReportDetails['ci_collateral'] ?? '';
        $ci_condition = $ciReportDetails['ci_condition'] ?? '';
        $acceptability = $ciReportDetails['acceptability'] ?? '';
        $loanability = $ciReportDetails['loanability'] ?? '';
        $recommendation	 = $ciReportDetails['recommendation	'] ?? '';

        $customerDetails = $customerModel->getPersonalInformation($contact_id);
        $customerName = $customerDetails['file_as'] ?? null;

        $appraiserDetails = $userModel->getUserByID($appraiser);
        $appraiserName = $appraiserDetails['file_as'] ?? '';
        $investigatorDetails = $userModel->getUserByID($investigator);
        $investigatorName = $investigatorDetails['file_as'] ?? '';

        // Sale proposal pricing computation
        $salesProposalPricingComputationDetails = $salesProposalModel->getSalesProposalPricingComputation($sales_proposal_id);
        $repaymentAmount = $salesProposalPricingComputationDetails['repayment_amount'] ?? 0;
        $interest_rate = $salesProposalPricingComputationDetails['interest_rate'] ?? 0;
        $pn_amount = $salesProposalPricingComputationDetails['pn_amount'] ?? 0;
        $outstanding_balance = $salesProposalPricingComputationDetails['outstanding_balance'] ?? 0;

        // Sales proposal
        $salesProposalDetails = $salesProposalModel->getSalesProposal($sales_proposal_id);
        $termLength = $salesProposalDetails['term_length'] ?? 0;
        $term_type = $salesProposalDetails['term_type'] ?? 0;
        $created_by = $salesProposalDetails['created_by'] ?? null;
        $referred_by = $salesProposalDetails['referred_by'] ?? '--';
        $renewal_tag = $salesProposalDetails['renewal_tag'] ?? 'New';

        $salesExecDetails = $userModel->getUserByID($created_by);
        $salesExec = $salesExecDetails['file_as'] ?? '--';

        // Sales proposa other charges
        $salesProposalOtherChargesDetails = $salesProposalModel->getSalesProposalOtherProductDetails($sales_proposal_id);
        $si = $salesProposalOtherChargesDetails['si'] ?? 0;
        $di = $salesProposalOtherChargesDetails['di'] ?? 0;

        // Computations
        $monthlyIncomeTotal = $ciReportModel->getCIReportBusinessExpenseTotal($ci_report_id, 'monthly income')['total'] ?? 0;
        $grandTotal = $ciReportModel->getCIReportEmploymentExpenseTotal($ci_report_id, 'grand total')['total'] ?? 0;
        $totalIncome = $monthlyIncomeTotal + $grandTotal;

        $totalExpenseTotal = $ciReportModel->getCIReportResidenceExpenseTotal($ci_report_id, 'total')['total'] ?? 0;
        $rentalTotal = $ciReportModel->getCIReportBusinessExpenseTotal($ci_report_id, 'rental')['total'] ?? 0;
        $loanAmort = $ciReportModel->getCIReportLoanTotal($ci_report_id, 'repayment')['total'] ?? 0;
        $expensesTotal = $totalExpenseTotal + $rentalTotal;
        $lessExpenseTotal = $expensesTotal + $loanAmort;
        $ema = ($totalIncome - $lessExpenseTotal) * 0.6;
        $ela = $ema * $termLength;

        if(!empty($sales_proposal_id)){
            $monthlyRate = $ciReportModel->rate(
                $termLength,
                -$repaymentAmount,
                $outstanding_balance
            );

            $effectiveAnnualYield = (pow(1 + $monthlyRate, 12) - 1) * 100;
        }
        else{
            $effectiveAnnualYield = 0;
        }

    }

    $cgmiLoanTable = generateCGMILoanTable($ci_report_id, $pn_amount, $outstanding_balance, $repaymentAmount);
    $cgmiLoanTable2 = generateCGMIOtherLoansTable($ci_report_id, $pn_amount, $outstanding_balance, $repaymentAmount);
    $collateralTable = generateCollateralTable($ci_report_id, $outstanding_balance);
    $businessTable = generateBusinessTable($ci_report_id);
    $employmentTable = generateEmploymentTable($ci_report_id);
    $bankStatementTable = generateBankStatementTable($ci_report_id);

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
    $pdf->SetTitle('Job Order List');
    $pdf->SetSubject('Job Order List');

    // Set margins and auto page break
    $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
    $pdf->SetMargins(15, 15, 15);
    $pdf->SetHeaderMargin(5);
    $pdf->SetFooterMargin(10);
    $pdf->SetAutoPageBreak(TRUE, 15);

    // Add a page
    $pdf->AddPage();

    $pdf->SetFont('times', '', 10);
    $pdf->Cell(20, 5, 'FOR:', 0, 0, 'L');
    $pdf->Cell(120, 5, 'OPERATIONS AND FINANCE HEAD', 'B', 0, 'L');
    $pdf->Ln(6);
    $pdf->Cell(20, 5, 'FROM:', 0, 0, 'L');
    $pdf->Cell(120, 5, 'CI & COLLECTION HEAD', 'B', 0, 'L');
    $pdf->Ln(6);
    $pdf->Cell(20, 5, 'SUBJECT:', 0, 0, 'L');
    $pdf->Cell(120, 5, strtoupper($customerName), 'B', 0, 'L');
    $pdf->Ln(6);
    $pdf->Cell(20, 5, 'DEALER:', 0, 0, 'L');
    $pdf->Cell(120, 5, 'CGMI', 'B', 0, 'L');
    $pdf->Ln(6);
    $pdf->Cell(20, 5, 'DATE:', 0, 0, 'L');
    $pdf->Cell(120, 5, strtoupper(date('F d, Y')), 'B', 0, 'L');
    $pdf->Ln(5);
    $pdf->Cell(185, 5, '', 'B', 0, 'L');
    $pdf->Ln(10);


    $pdf->SetFont('times', 'B', 10);
    $pdf->Cell(80, 5, 'I. LOAN APPLICATION', 0, 0, 'L');
    $pdf->Cell(5, 5, '', 1, 0, 'L');
    $pdf->Cell(3, 5, '', 0, 0, 'L');
    $pdf->Cell(40, 5, 'NEW', 0, 0, 'L');
    $pdf->Cell(5, 5, '', 1, 0, 'L');
    $pdf->Cell(3, 5, '', 0, 0, 'L');
    $pdf->Cell(50, 5, 'REPEAT', 0, 0, 'L');
    $pdf->SetFont('times', '', 10);
    $pdf->Ln(10);
    $pdf->Cell(40, 5, 'AMOUNT APPLIED:', 0, 0, 'L');
    $pdf->Cell(50, 5, 'PHP ' . number_format($outstanding_balance, 2), 'B', 0, 'L');
    $pdf->Cell(5, 5, '', 0, 0, 'L');
    $pdf->Cell(25, 5, 'SALES EXEC:', 0, 0, 'L');
    $pdf->Cell(5, 5, '', 0, 0, 'L');
    $pdf->Cell(60, 5, $salesExec, 'B', 0, 'C');
    $pdf->Ln(6);
    $pdf->Cell(30, 5, 'RATE:', 0, 0, 'L');
    $pdf->Cell(10, 5, '', 0, 0, 'L');
    $pdf->Cell(15, 5, number_format($interest_rate, 2). '%', 'B', 0, 'C');
    $pdf->Cell(5, 5, '', 0, 0, 'L');
    $pdf->Cell(2, 5, 'SI:', 0, 0, 'C');
    $pdf->Cell(5, 5, '', 0, 0, 'L');
    $pdf->Cell(22, 5, 'PHP '. number_format($si, 2), 'B', 0, 'L');
    $pdf->Cell(5, 5, '', 0, 0, 'L');
    $pdf->Cell(25, 5, 'CI:', 0, 0, 'L');
    $pdf->Cell(5, 5, '', 0, 0, 'L');
    $pdf->Cell(60, 5, strtoupper($investigatorName), 'B', 0, 'C');
    $pdf->Ln(6);
    $pdf->Cell(30, 5, 'TERM:', 0, 0, 'L');
    $pdf->Cell(10, 5, '', 0, 0, 'L');
    $pdf->Cell(15, 5, number_format($termLength, 2), 'B', 0, 'C');
    $pdf->Cell(5, 5, '', 0, 0, 'L');
    $pdf->Cell(2, 5, 'DI:', 0, 0, 'C');
    $pdf->Cell(5, 5, '', 0, 0, 'L');
    $pdf->Cell(22, 5, 'PHP '. number_format($di, 2), 'B', 0, 'L');
    $pdf->Cell(5, 5, '', 0, 0, 'L');
    $pdf->Cell(25, 5, 'APPRAISER:', 0, 0, 'L');
    $pdf->Cell(5, 5, '', 0, 0, 'L');
    $pdf->Cell(60, 5, strtoupper($appraiserName), 'B', 0, 'C');
    $pdf->Ln(6);
    $pdf->Cell(40, 5, 'EFFECTIVE YIELD:', 0, 0, 'L');
    $pdf->Cell(15, 5, number_format($effectiveAnnualYield, 2) . '%', 'B', 0, 'C');
    $pdf->Cell(40, 5, '',  0, 0, 'L');
    $pdf->Cell(25, 5, 'SALES AGENT:', 0, 0, 'L');
    $pdf->Cell(5, 5, '', 0, 0, 'L');
    $pdf->Cell(60, 5, '', 'B', 0, 'C');
    $pdf->Ln(6);
    $pdf->Cell(40, 5, 'PN AMOUNT:', 0, 0, 'L');
    $pdf->Cell(50, 5, 'PHP ' . number_format($pn_amount, 2), 'B', 0, 'L');
    $pdf->Ln(6);
    $pdf->Cell(40, 5, 'MONTHLY AMORT.:', 0, 0, 'L');
    $pdf->Cell(50, 5, 'PHP ' . number_format($repaymentAmount, 2), 'B', 0, 'L');
    $pdf->Ln(6);
    $pdf->Cell(40, 5, 'PURPOSE OF LOAN:', 0, 0, 'L');
    $pdf->MultiCell(145, 5, strtoupper($purpose_of_loan), 'B', 'L');
    $pdf->Ln(2);
    $pdf->Cell(40, 5, 'CONDITION:', 0, 0, 'L');
    $pdf->MultiCell(145, 5, 'CLIENT IS BORN IN ILO ILO AND RAISED IN MANILA. GRAD OF BSBA IN MAPUA. NICKNAME - TONET', 'B', 'L');


    $pdf->Ln(5);
    $pdf->SetFont('times', 'B', 10);
    $pdf->Cell(80, 5, 'II. LOAN HISTORY', 0, 0, 'L');
    $pdf->Ln(8);
      $pdf->SetFont('times', '', 8);
    $pdf->writeHTML($cgmiLoanTable, true, false, true, false, '');
    $pdf->Ln(0);
      $pdf->SetFont('times', '', 8);
    $pdf->writeHTML($cgmiLoanTable2, true, false, true, false, '');
    
    $pdf->Ln(0);
    $pdf->SetFont('times', 'B', 10);
    $pdf->Cell(80, 5, 'III. COLLATERAL DESCRIPTION', 0, 0, 'L');
    $pdf->Cell(5, 5, '', 1, 0, 'L');
    $pdf->Cell(3, 5, '', 0, 0, 'L');
    $pdf->Cell(40, 5, 'FINANCING', 0, 0, 'L');
    $pdf->Cell(5, 5, '', 1, 0, 'L');
    $pdf->Cell(3, 5, '', 0, 0, 'L');
    $pdf->Cell(50, 5, 'REFINANCING', 0, 0, 'L');
    
    $pdf->Ln(8);
      $pdf->SetFont('times', '', 8);
    $pdf->writeHTML($collateralTable, true, false, true, false, '');
    $pdf->SetFont('times', '', 10);
    
    $pdf->Ln(10);
    $pdf->SetFont('times', 'B', 10);
    $pdf->Cell(80, 5, 'IV. GENERAL INFORMATION', 0, 0, 'L');
    $pdf->Ln(8);
      $pdf->SetFont('times', '', 8);
    $pdf->writeHTML($businessTable, true, false, true, false, '');
    $pdf->Ln(0);
      $pdf->SetFont('times', '', 8);
    $pdf->writeHTML($employmentTable, true, false, true, false, '');

     $pdf->Ln(0);
    $pdf->SetFont('times', 'B', 10);
    $pdf->Cell(80, 5, "5C'S OF CREDIT", 0, 0, 'L');$pdf->Ln(0);
     $pdf->Ln(5);
    $pdf->SetFont('times', 'B', 10);
    $pdf->Cell(80, 5, 'CHARACTER', 0, 0, 'L');
    $pdf->Cell(5, 5, '', 1, 0, 'L');
    $pdf->Cell(3, 5, '', 0, 0, 'L');
    $pdf->Cell(40, 5, 'PASSED', 0, 0, 'L');
    $pdf->Cell(5, 5, '', 1, 0, 'L');
    $pdf->Cell(3, 5, '', 0, 0, 'L');
    $pdf->Cell(50, 5, 'FAILED', 0, 0, 'L');
     $pdf->Ln(5);
    $pdf->SetFont('times', 'B', 10);
    $pdf->Cell(80, 5, 'CMAP RESULT', 0, 0, 'L');
    $pdf->Cell(5, 5, '', 1, 0, 'L');
    $pdf->Cell(3, 5, '', 0, 0, 'L');
    $pdf->Cell(40, 5, 'PASSED', 0, 0, 'L');
    $pdf->Cell(5, 5, '', 1, 0, 'L');
    $pdf->Cell(3, 5, '', 0, 0, 'L');
    $pdf->Cell(50, 5, 'FAILED', 0, 0, 'L');
     $pdf->Ln(5);
    $pdf->SetFont('times', 'B', 10);
    $pdf->Cell(80, 5, 'CRIF RESULT', 0, 0, 'L');
    $pdf->Cell(5, 5, '', 1, 0, 'L');
    $pdf->Cell(3, 5, '', 0, 0, 'L');
    $pdf->Cell(40, 5, 'PASSED', 0, 0, 'L');
    $pdf->Cell(5, 5, '', 1, 0, 'L');
    $pdf->Cell(3, 5, '', 0, 0, 'L');
    $pdf->Cell(50, 5, 'FAILED', 0, 0, 'L');
     $pdf->Ln(5);
    $pdf->SetFont('times', 'B', 10);
    $pdf->Cell(55, 5, 'TIMES ACCOMMODATION', 0, 0, 'L');
    $pdf->Cell(5, 5, '', 0, 0, 'L');
    $pdf->Cell(15, 5, '', 'B', 0, 'L');
    $pdf->Cell(5, 5, '', 0, 0, 'L');
    $pdf->Cell(5, 5, '', 1, 0, 'L');
    $pdf->Cell(3, 5, '', 0, 0, 'L');
    $pdf->Cell(40, 5, 'ADVERSE', 0, 0, 'L');
    $pdf->Cell(5, 5, '', 1, 0, 'L');
    $pdf->Cell(3, 5, '', 0, 0, 'L');
    $pdf->Cell(50, 5, 'NOTHING ADVERSE', 0, 0, 'L');
    
    $pdf->Ln(10);
    $pdf->SetFont('times', 'B', 10);
    $pdf->Cell(80, 5, 'CAPACITY', 0, 0, 'L');
    $pdf->Cell(5, 5, '', 1, 0, 'L');
    $pdf->Cell(3, 5, '', 0, 0, 'L');
    $pdf->Cell(40, 5, 'PASSED', 0, 0, 'L');
    $pdf->Cell(5, 5, '', 1, 0, 'L');
    $pdf->Cell(3, 5, '', 0, 0, 'L');
    $pdf->Cell(50, 5, 'FAILED', 0, 0, 'L');
     $pdf->Ln(8);
    $pdf->SetFont('times', 'B', 10);
    $pdf->Cell(80, 5, "BANK STATEMENT", 0, 0, 'L');

    $pdf->Ln(10);
      $pdf->SetFont('times', '', 8);
    $pdf->writeHTML($bankStatementTable, true, false, true, false, '');

     $pdf->Ln(0);
    $pdf->SetFont('times', 'B', 10);
    $pdf->Cell(80, 5, "MONTHLY SALES INCOME", 0, 0, 'L');

    $pdf->Ln(10);
      $pdf->SetFont('times', '', 8);
    $pdf->writeHTML($bankStatementTable, true, false, true, false, '');
    
    $pdf->Ln(10);
    $pdf->SetFont('times', 'B', 10);
    $pdf->Cell(80, 5, 'V. EXCEPTIONS', 0, 0, 'L');

    $pdf->Ln(10);
    $pdf->SetFont('times', 'B', 10);
    $pdf->Cell(80, 5, 'VI. EVALUATION & JUSTIFICATION', 0, 0, 'L');

    //$pdf->writeHTML($summaryTable, true, false, true, false, '');

    // Output the PDF to the browser
    $pdf->Output('job-order-list.pdf', 'I');
    ob_end_flush();

    function generateCGMILoanTable($ci_report_id, $ci_pn_amount, $ci_outstanding_balance, $ci_repayment){
        
        require_once 'model/database-model.php';
        require_once 'model/pdc-management-model.php';
        require_once 'model/system-model.php';
        require_once 'model/user-model.php';
        require_once 'model/contractor-model.php';
        require_once 'model/employee-model.php';
        require_once 'model/work-center-model.php';
        require_once 'model/sales-proposal-model.php';

        $databaseModel = new DatabaseModel();
        $systemModel = new SystemModel();
        $userModel = new UserModel(new DatabaseModel, new SystemModel);
        $contractorModel = new ContractorModel($databaseModel);
        $workCenterModel = new WorkCenterModel($databaseModel);
        $salesProposalModel = new SalesProposalModel($databaseModel);
    
        $list = '';
        $pn_amount_total = 0;
        $ob_total = 0;
        $repayment_total = 0;
            
        $sql = $databaseModel->getConnection()->prepare('SELECT * FROM ci_report_loan WHERE ci_report_id = :ci_report_id AND loan_source = "CGMI"');
        $sql->bindValue(':ci_report_id', $ci_report_id, PDO::PARAM_INT);
        $sql->execute();
        $options = $sql->fetchAll(PDO::FETCH_ASSOC);
        $sql->closeCursor();

        foreach ($options as $row) {
            $account_name = $row['account_name'];
            $pn_amount = $row['pn_amount'];
            $outstanding_balance = $row['outstanding_balance'];
            $repayment = $row['repayment'];
            $term = $row['term'];
            $handling = $row['handling'];
            $handliremarksng = $row['remarks'];
            $availed_date = $systemModel->checkDate('empty', $row['availed_date'], '', 'm/d/Y', '');

            $pn_amount_total = $pn_amount_total + $pn_amount;
            $ob_total = $ob_total + $outstanding_balance;
            $repayment_total = $repayment_total + $repayment;

            $list .= '<tr>
                        <td>'. $account_name .'</td>
                        <td style="text-align:center">'. $availed_date .'</td>
                        <td style="text-align:center">PHP '. number_format($pn_amount,2) .'</td>
                        <td style="text-align:center">PHP '. number_format($outstanding_balance,2) .'</td>
                        <td style="text-align:center">PHP '. number_format($repayment,2) .'</td>
                        <td style="text-align:center">'. $term .'</td>
                        <td style="text-align:center">'. $handling .'</td>
                        <td>'. $remarks .'</td>
                    </tr>';
        }

        $response = '<table border="1" width="100%" cellpadding="5" align="left">
                        <thead>
                            <tr style="text-align:center">
                                <td><b>CGMI Loan Number</b></td>
                                <td><b>Date Availed</b></td>
                                <td><b>PN Amount</b></td>
                                <td><b>OB Amount</b></td>
                                <td><b>Monthly Amortization</b></td>
                                <td><b>Terms Months</b></td>
                                <td><b>Handling</b></td>
                                <td><b>Remarks</b></td>
                            </tr>
                        </thead>
                        <tbody>
                            '.$list.'
                            <tr style="text-align:center">
                                <td rowspan="3"></td>
                                <td style="text-align:right"><b>Total</b></td>
                                <td>PHP '. number_format($pn_amount_total,2) .'</td>
                                <td>PHP '. number_format($ob_total,2) .'</td>
                                <td>PHP '. number_format($repayment_total,2) .'</td>
                                <td colspan="3" rowspan="3"></td>
                            </tr>
                            <tr style="text-align:center">
                                <td style="text-align:right"><b>Add this application</b></td>
                                <td>PHP '. number_format($ci_pn_amount,2) .'</td>
                                <td>PHP '. number_format($ci_outstanding_balance,2) .'</td>
                                <td>PHP '. number_format($ci_repayment,2) .'</td>
                            </tr>
                            <tr style="text-align:center">
                                <td style="text-align:right"><b>Total CGMI Loan including new application</b></td>
                                <td>PHP '. number_format($ci_pn_amount + $pn_amount_total,2) .'</td>
                                <td>PHP '. number_format($ci_outstanding_balance + $ob_total,2) .'</td>
                                <td>PHP '. number_format($ci_repayment + $repayment_total,2) .'</td>
                            </tr>
                        </tbody>
                    </table>';

        return $response;
    }

    function generateCGMIOtherLoansTable($ci_report_id, $ci_pn_amount, $ci_outstanding_balance, $ci_repayment){
        
        require_once 'model/database-model.php';
        require_once 'model/pdc-management-model.php';
        require_once 'model/system-model.php';
        require_once 'model/user-model.php';
        require_once 'model/contractor-model.php';
        require_once 'model/employee-model.php';
        require_once 'model/work-center-model.php';
        require_once 'model/sales-proposal-model.php';

        $databaseModel = new DatabaseModel();
        $systemModel = new SystemModel();
        $userModel = new UserModel(new DatabaseModel, new SystemModel);
        $contractorModel = new ContractorModel($databaseModel);
        $workCenterModel = new WorkCenterModel($databaseModel);
        $salesProposalModel = new SalesProposalModel($databaseModel);
    
        $list = '';
        $pn_amount_total = 0;
        $ob_total = 0;
        $repayment_total = 0;
            
        $sql = $databaseModel->getConnection()->prepare('SELECT * FROM ci_report_loan WHERE ci_report_id = :ci_report_id AND (loan_source != "CGMI" OR loan_source IS NULL)');
        $sql->bindValue(':ci_report_id', $ci_report_id, PDO::PARAM_INT);
        $sql->execute();
        $options = $sql->fetchAll(PDO::FETCH_ASSOC);
        $sql->closeCursor();

        foreach ($options as $row) {
            $account_name = $row['account_name'];
            $pn_amount = $row['pn_amount'];
            $outstanding_balance = $row['outstanding_balance'];
            $repayment = $row['repayment'];
            $term = $row['term'];
            $handling = $row['handling'];
            $availed_date = $systemModel->checkDate('empty', $row['availed_date'], '', 'm/d/Y', '');

            $pn_amount_total = $pn_amount_total + $pn_amount;
            $ob_total = $ob_total + $outstanding_balance;
            $repayment_total = $repayment_total + $repayment;

            $list .= '<tr>
                        <td>'. $account_name .'</td>
                        <td style="text-align:center">'. $availed_date .'</td>
                        <td style="text-align:center">PHP '. number_format($pn_amount,2) .'</td>
                        <td style="text-align:center">PHP '. number_format($outstanding_balance,2) .'</td>
                        <td style="text-align:center">PHP '. number_format($repayment,2) .'</td>
                        <td style="text-align:center">'. $term .'</td>
                        <td style="text-align:center">'. $handling .'</td>
                    </tr>';
        }

        $response = '<table border="1" width="100%" cellpadding="5" align="left">
                        <thead>
                            <tr style="text-align:center">
                                <td><b>Other Loans</b></td>
                                <td><b>Date Availed</b></td>
                                <td><b>PN Amount</b></td>
                                <td><b>OB Amount</b></td>
                                <td><b>Monthly Amortization</b></td>
                                <td><b>Terms Months</b></td>
                                <td><b>Handling</b></td>
                            </tr>
                        </thead>
                        <tbody>
                            '.$list.'
                            <tr style="text-align:center">
                                <td rowspan="3"></td>
                                <td style="text-align:right"><b>Total Other Loans</b></td>
                                <td>PHP '. number_format($pn_amount_total,2) .'</td>
                                <td>PHP '. number_format($ob_total,2) .'</td>
                                <td>PHP '. number_format($repayment_total,2) .'</td>
                                <td colspan="3" rowspan="3"></td>
                            </tr>
                            <tr style="text-align:center">
                                <td style="text-align:right"><b>Grand Total Existing</b></td>
                                <td>PHP '. number_format($ci_pn_amount + $pn_amount_total,2) .'</td>
                                <td>PHP '. number_format($ci_outstanding_balance + $ob_total,2) .'</td>
                                <td>PHP '. number_format($ci_repayment + $repayment_total,2) .'</td>
                            </tr>
                        </tbody>
                    </table>';

        return $response;
    }

    function generateCollateralTable($ci_report_id, $ci_outstanding_balance){
        
        require_once 'model/database-model.php';
        require_once 'model/pdc-management-model.php';
        require_once 'model/system-model.php';
        require_once 'model/user-model.php';
        require_once 'model/contractor-model.php';
        require_once 'model/employee-model.php';
        require_once 'model/work-center-model.php';
        require_once 'model/sales-proposal-model.php';

        $databaseModel = new DatabaseModel();
        $systemModel = new SystemModel();
        $userModel = new UserModel(new DatabaseModel, new SystemModel);
        $contractorModel = new ContractorModel($databaseModel);
        $workCenterModel = new WorkCenterModel($databaseModel);
        $salesProposalModel = new SalesProposalModel($databaseModel);
    
        $list = '';
        $appraised_value_total = 0;
        $loannable_value_total = 0;
            
        $sql = $databaseModel->getConnection()->prepare('SELECT * FROM ci_report_collateral WHERE ci_report_id = :ci_report_id');
        $sql->bindValue(':ci_report_id', $ci_report_id, PDO::PARAM_INT);
        $sql->execute();
        $options = $sql->fetchAll(PDO::FETCH_ASSOC);
        $sql->closeCursor();

        foreach ($options as $row) {
            $description = $row['description'];
            $year_model = $row['year_model'];
            $appraised_value = $row['appraised_value'];
            $loannable_value = $row['loannable_value'];
            $remarks = $row['remarks'];

            $appraised_value_total = $appraised_value_total + $appraised_value;
            $loannable_value_total = $loannable_value_total + $loannable_value;

            $list .= '<tr>
                        <td>'. $description .'</td>
                        <td>'. $year_model .'</td>
                        <td style="text-align:center">PHP '. number_format($appraised_value,2) .'</td>
                        <td style="text-align:center">PHP '. number_format($loannable_value,2) .'</td>
                        <td style="text-align:center">PHP '. number_format($ci_outstanding_balance,2) .'</td>
                        <td>'. $remarks .'</td>
                    </tr>';
        }

        $response = '<table border="1" width="100%" cellpadding="5" align="left">
                        <thead>
                            <tr style="text-align:center">
                                <td><b>Unit</b></td>
                                <td><b>Year Model</b></td>
                                <td><b>Appraised Value</b></td>
                                <td><b>Loanable</b></td>
                                <td><b>Cash Out</b></td>
                            </tr>
                        </thead>
                        <tbody>
                            '.$list.'
                            <tr style="text-align:center">
                                <td colspan="2" style="text-align:right"><b>Total</b></td>
                                <td>PHP '. number_format($appraised_value_total,2) .'</td>
                                <td>PHP '. number_format($loannable_value_total,2) .'</td>
                                <td>PHP '. number_format($ci_outstanding_balance,2) .'</td>
                            </tr>
                        </tbody>
                    </table>';

        return $response;
    }

    function generateBusinessTable($ci_report_id){
        
        require_once 'model/database-model.php';
        require_once 'model/pdc-management-model.php';
        require_once 'model/system-model.php';
        require_once 'model/user-model.php';
        require_once 'model/contractor-model.php';
        require_once 'model/employee-model.php';
        require_once 'model/work-center-model.php';
        require_once 'model/sales-proposal-model.php';
        require_once 'model/state-model.php';
        require_once 'model/city-model.php';
        require_once 'model/country-model.php';

        $databaseModel = new DatabaseModel();
        $systemModel = new SystemModel();
        $userModel = new UserModel(new DatabaseModel, new SystemModel);
        $contractorModel = new ContractorModel($databaseModel);
        $workCenterModel = new WorkCenterModel($databaseModel);
        $salesProposalModel = new SalesProposalModel($databaseModel);
        $stateModel = new StateModel($databaseModel);
        $cityModel = new CityModel($databaseModel);
        $countryModel = new CountryModel($databaseModel);
    
        $list = '';
        $appraised_value_total = 0;
        $loannable_value_total = 0;
            
        $sql = $databaseModel->getConnection()->prepare('SELECT * FROM ci_report_business WHERE ci_report_id = :ci_report_id');
        $sql->bindValue(':ci_report_id', $ci_report_id, PDO::PARAM_INT);
        $sql->execute();
        $options = $sql->fetchAll(PDO::FETCH_ASSOC);
        $sql->closeCursor();

        foreach ($options as $row) {
            $business_name = $row['business_name'];
            $description = $row['description'];
            $address = $row['address'];
            $city_id = $row['city_id'];
            $length_stay_year = $row['length_stay_year'];
            $length_stay_month = $row['length_stay_month'];
            $registered_with = $row['registered_with'];
            $remarks = $row['remarks'];

            $cityDetails = $cityModel->getCity($city_id);
            $cityName = $cityDetails['city_name'];
            $stateID = $cityDetails['state_id'];
    
            $stateDetails = $stateModel->getState($stateID);
            $stateName = $stateDetails['state_name'];
            $countryID = $stateDetails['country_id'];
    
            $countryName = $countryModel->getCountry($countryID)['country_name'];

            $contactAddress = $address . ', ' . $cityName . ', ' . $stateName . ', ' . $countryName;


            $lenstayYear = '';
            $lenstayMonth = '';
            $lenstay = '';
            
            if($length_stay_year > 0) {
                if($length_stay_year == 1){
                    $lenstayYear .= '1 YEAR';
                }
                else{
                    $lenstayYear .= $length_stay_year . ' YEARS';
                }
            }
            
            if($length_stay_month > 0) {
                if($length_stay_month == 1){
                    $lenstayMonth .= '1 MONTH';
                }
                else{
                    $lenstayMonth .= $length_stay_month . ' MONTHS';
                }
            }

            if(!empty($lenstayYear) && !empty($lenstayMonth)){
                $lenstay = $lenstayYear . ' AND ' . $lenstayMonth;
            }
            else if(empty($lenstayYear) && !empty($lenstayMonth)){
                $lenstay = $lenstayMonth;
            }
            else if(!empty($lenstayYear) && empty($lenstayMonth)){
                $lenstay = $lenstayYear;
            }

            $list .= '<tr>
                        <td>'. strtoupper($business_name) .'</td>
                        <td>'. strtoupper($description) .'</td>
                        <td>'. strtoupper($contactAddress) .'</td>
                        <td>'. $lenstay .'</td>
                        <td>'. strtoupper($registered_with) .'</td>
                        <td>'. strtoupper($remarks) .'</td>
                    </tr>';
        }

        $response = '<table border="1" width="100%" cellpadding="5" align="left">
                        <thead>
                            <tr style="text-align:center">
                                <td><b>Name of Business</b></td>
                                <td><b>Business Description</b></td>
                                <td><b>Address</b></td>
                                <td><b>Years of Existence</b></td>
                                <td><b>Source/ Business Registration/ Facility</b></td>
                                <td><b>Remarks</b></td>
                            </tr>
                        </thead>
                        <tbody>
                            '.$list.'
                        </tbody>
                    </table>';

        return $response;
    }

    function generateEmploymentTable($ci_report_id){
        
        require_once 'model/database-model.php';
        require_once 'model/pdc-management-model.php';
        require_once 'model/system-model.php';
        require_once 'model/user-model.php';
        require_once 'model/contractor-model.php';
        require_once 'model/employee-model.php';
        require_once 'model/work-center-model.php';
        require_once 'model/sales-proposal-model.php';
        require_once 'model/state-model.php';
        require_once 'model/city-model.php';
        require_once 'model/country-model.php';

        $databaseModel = new DatabaseModel();
        $systemModel = new SystemModel();
        $userModel = new UserModel(new DatabaseModel, new SystemModel);
        $contractorModel = new ContractorModel($databaseModel);
        $workCenterModel = new WorkCenterModel($databaseModel);
        $salesProposalModel = new SalesProposalModel($databaseModel);
        $stateModel = new StateModel($databaseModel);
        $cityModel = new CityModel($databaseModel);
        $countryModel = new CountryModel($databaseModel);
    
        $list = '';
        $appraised_value_total = 0;
        $loannable_value_total = 0;
            
        $sql = $databaseModel->getConnection()->prepare('SELECT * FROM ci_report_employment WHERE ci_report_id = :ci_report_id');
        $sql->bindValue(':ci_report_id', $ci_report_id, PDO::PARAM_INT);
        $sql->execute();
        $options = $sql->fetchAll(PDO::FETCH_ASSOC);
        $sql->closeCursor();

        foreach ($options as $row) {
            $employment_name = $row['employment_name'];
            $description = $row['description'];
            $address = $row['address'];
            $city_id = $row['city_id'];
            $length_stay_year = $row['length_stay_year'];
            $length_stay_month = $row['length_stay_month'];
            $pres_length_stay_year = $row['pres_length_stay_year'];
            $pres_length_stay_month = $row['pres_length_stay_month'];
            $department = $row['department'];
            $rank = $row['rank'];
            $position = $row['position'];
            $status = $row['status'];
            $remarks = $row['remarks'];

            $cityDetails = $cityModel->getCity($city_id);
            $cityName = $cityDetails['city_name'];
            $stateID = $cityDetails['state_id'];
    
            $stateDetails = $stateModel->getState($stateID);
            $stateName = $stateDetails['state_name'];
            $countryID = $stateDetails['country_id'];
    
            $countryName = $countryModel->getCountry($countryID)['country_name'];

            $contactAddress = $address . ', ' . $cityName . ', ' . $stateName . ', ' . $countryName;


            $lenstayYear = '';
            $lenstayMonth = '';
            $lenstay = '';
            
            if($length_stay_year > 0) {
                if($length_stay_year == 1){
                    $lenstayYear .= '1 YEAR';
                }
                else{
                    $lenstayYear .= $length_stay_year . ' YEARS';
                }
            }
            
            if($length_stay_month > 0) {
                if($length_stay_month == 1){
                    $lenstayMonth .= '1 MONTH';
                }
                else{
                    $lenstayMonth .= $length_stay_month . ' MONTHS';
                }
            }

            if(!empty($lenstayYear) && !empty($lenstayMonth)){
                $lenstay = $lenstayYear . ' AND ' . $lenstayMonth;
            }
            else if(empty($lenstayYear) && !empty($lenstayMonth)){
                $lenstay = $lenstayMonth;
            }
            else if(!empty($lenstayYear) && empty($lenstayMonth)){
                $lenstay = $lenstayYear;
            }

            $preslenstayYear = '';
            $preslenstayMonth = '';
            $preslenstay = '';
            
            if($pres_length_stay_year > 0) {
                if($pres_length_stay_year == 1){
                    $preslenstayYear .= '1 YEAR';
                }
                else{
                    $preslenstayYear .= $pres_length_stay_year . ' YEARS';
                }
            }
            
            if($pres_length_stay_month > 0) {
                if($pres_length_stay_month == 1){
                    $preslenstayMonth .= '1 MONTH';
                }
                else{
                    $preslenstayMonth .= $pres_length_stay_month . ' MONTHS';
                }
            }

            if(!empty($preslenstayYear) && !empty($preslenstayMonth)){
                $preslenstay = $preslenstayYear . ' AND ' . $preslenstayMonth;
            }
            else if(empty($preslenstayYear) && !empty($preslenstayMonth)){
                $preslenstay = $preslenstayMonth;
            }
            else if(!empty($preslenstayYear) && empty($preslenstayMonth)){
                $preslenstay = $preslenstayYear;
            }

            $list .= '<tr>
                        <td>'. strtoupper($employment_name) .'</td>
                        <td>'. strtoupper($description) .'</td>
                        <td>'. strtoupper($contactAddress) .'</td>
                        <td>'. $lenstay .'</td>
                        <td>'. $preslenstay .'</td>
                        <td>'. strtoupper($department) .'</td>
                        <td>'. strtoupper($rank) .'</td>
                        <td>'. strtoupper($position) .'</td>
                        <td>'. strtoupper($status) .'</td>
                        <td>'. strtoupper($remarks) .'</td>
                    </tr>';
        }

        $response = '<table border="1" width="100%" cellpadding="5" align="left">
                        <thead>
                            <tr style="text-align:center">
                                <td><b>Name of Employment</b></td>
                                <td><b>Employment Description</b></td>
                                <td><b>Address</b></td>
                                <td><b>Length Stay</b></td>
                                <td><b>Present Stay</b></td>
                                <td><b>Department</b></td>
                                <td><b>Rank</b></td>
                                <td><b>Position</b></td>
                                <td><b>Status</b></td>
                                <td><b>Remarks</b></td>
                            </tr>
                        </thead>
                        <tbody>
                            '.$list.'
                        </tbody>
                    </table>';

        return $response;
    }

    function generateBankStatementTable($ci_report_id){
        
        require_once 'model/database-model.php';
        require_once 'model/pdc-management-model.php';
        require_once 'model/system-model.php';
        require_once 'model/user-model.php';
        require_once 'model/contractor-model.php';
        require_once 'model/employee-model.php';
        require_once 'model/work-center-model.php';
        require_once 'model/sales-proposal-model.php';
        require_once 'model/state-model.php';
        require_once 'model/city-model.php';
        require_once 'model/country-model.php';
        require_once 'model/bank-adb-model.php';
        require_once 'model/bank-model.php';
        require_once 'model/bank-account-type-model.php';
        require_once 'model/ci-report-model.php';
        require_once 'model/bank-handling-type-model.php';

        $databaseModel = new DatabaseModel();
        $systemModel = new SystemModel();
        $userModel = new UserModel(new DatabaseModel, new SystemModel);
        $contractorModel = new ContractorModel($databaseModel);
        $workCenterModel = new WorkCenterModel($databaseModel);
        $salesProposalModel = new SalesProposalModel($databaseModel);
        $stateModel = new StateModel($databaseModel);
        $cityModel = new CityModel($databaseModel);
        $countryModel = new CountryModel($databaseModel);
        $bankADBModel = new BankADBModel($databaseModel);
        $bankModel = new BankModel($databaseModel);
        $bankAccountTypeModel = new BankAccountTypeModel($databaseModel);
        $ciReportModel = new CIReportModel($databaseModel);
        $bankHandlingTypeModel = new BankHandlingTypeModel($databaseModel);
    
        $list = '';
        $appraised_value_total = 0;
        $loannable_value_total = 0;
            
        $sql = $databaseModel->getConnection()->prepare('SELECT * FROM ci_report_bank WHERE ci_report_id = :ci_report_id');
        $sql->bindValue(':ci_report_id', $ci_report_id, PDO::PARAM_INT);
        $sql->execute();
        $options = $sql->fetchAll(PDO::FETCH_ASSOC);
        $sql->closeCursor();

        $averageTotal = 0;
        foreach ($options as $row) {
            $ci_report_bank_id  = $row['ci_report_bank_id'];
            $bank_id = $row['bank_id'];
            $account_number = $row['account_number'];
            $bank_account_type_id = $row['bank_account_type_id'];
            $bank_handling_type_id = $row['bank_handling_type_id'];
            $date_open = $systemModel->checkDate('empty', $row['date_open'], '', 'm/d/Y', '');
            $bank_adb_id = $row['bank_adb_id'];

            $bankADBName = $bankADBModel->getBankADB($bank_adb_id)['bank_adb_name'] ?? null;
            $bankHandlingTypeName = $bankHandlingTypeModel->getBankHandlingType($bank_handling_type_id)['bank_handling_type_name'] ?? null;
            $bankAccountTypeName = $bankAccountTypeModel->getBankAccountType($bank_account_type_id)['bank_account_type_name'] ?? null;

            $average = $ciReportModel->getBankDepositAverage($ci_report_bank_id)['total'] ?? 0;

            $averageTotal = $averageTotal + $average;
            
            $list .= '<tr>
                        <td>'. strtoupper($bank_id) .'</td>
                        <td>'. strtoupper($bankAccountTypeName) .'</td>
                        <td style="text-align:center">'. $date_open .'</td>
                        <td style="text-align:center">'. strtoupper($bankADBName) .'</td>
                        <td style="text-align:center">PHP '. number_format($average, 2) .'</td>
                        <td>'. strtoupper($bankHandlingTypeName) .'</td>
                    </tr>';
        }

        $response = '<table border="1" width="100%" cellpadding="5" align="left">
                        <thead>
                            <tr style="text-align:center">
                                <td><b>Bank</b></td>
                                <td><b>Account</b></td>
                                <td><b>Date Open</b></td>
                                <td><b>ADB</b></td>
                                <td><b>AVE. CREDIT BALANCE</b></td>
                                <td><b>HANDLING</b></td>
                            </tr>
                        </thead>
                        <tbody>
                            '.$list.'
                            
                            <tr style="text-align:center">
                                <td colspan="3"></td>
                                <td style="text-align:right"><b>Total</b></td>
                                <td>PHP '. number_format($averageTotal,2) .'</td>
                            </tr>
                        </tbody>
                    </table>';

        return $response;
    }

    function generateSalesIncomeTable($ci_report_id){
        
        require_once 'model/database-model.php';
        require_once 'model/pdc-management-model.php';
        require_once 'model/system-model.php';
        require_once 'model/user-model.php';
        require_once 'model/contractor-model.php';
        require_once 'model/employee-model.php';
        require_once 'model/work-center-model.php';
        require_once 'model/sales-proposal-model.php';
        require_once 'model/state-model.php';
        require_once 'model/city-model.php';
        require_once 'model/country-model.php';
        require_once 'model/bank-adb-model.php';
        require_once 'model/bank-model.php';
        require_once 'model/bank-account-type-model.php';
        require_once 'model/ci-report-model.php';
        require_once 'model/bank-handling-type-model.php';

        $databaseModel = new DatabaseModel();
        $systemModel = new SystemModel();
        $userModel = new UserModel(new DatabaseModel, new SystemModel);
        $contractorModel = new ContractorModel($databaseModel);
        $workCenterModel = new WorkCenterModel($databaseModel);
        $salesProposalModel = new SalesProposalModel($databaseModel);
        $stateModel = new StateModel($databaseModel);
        $cityModel = new CityModel($databaseModel);
        $countryModel = new CountryModel($databaseModel);
        $bankADBModel = new BankADBModel($databaseModel);
        $bankModel = new BankModel($databaseModel);
        $bankAccountTypeModel = new BankAccountTypeModel($databaseModel);
        $ciReportModel = new CIReportModel($databaseModel);
        $bankHandlingTypeModel = new BankHandlingTypeModel($databaseModel);
    
        $list = '';
        $appraised_value_total = 0;
        $loannable_value_total = 0;
            
        $sql = $databaseModel->getConnection()->prepare('SELECT * FROM ci_report_business WHERE ci_report_id = :ci_report_id');
        $sql->bindValue(':ci_report_id', $ci_report_id, PDO::PARAM_INT);
        $sql->execute();
        $options = $sql->fetchAll(PDO::FETCH_ASSOC);
        $sql->closeCursor();

        $averageTotal = 0;
        foreach ($options as $row) {
            $business_name  = $row['business_name'];
            $gross_monthly_sale  = $row['gross_monthly_sale'];
            $monthly_income  = $row['monthly_income'];
            $capital  = $row['capital'];

            $averageTotal = $averageTotal + $average;
            
            $list .= '<tr>
                        <td>'. strtoupper($business_name) .'</td>
                        <td>PHP '. number_format($monthly_income, 2) .'</td>
                        <td>PHP '. number_format($gross_monthly_sale, 2) .'</td>
                        <td>PHP '. number_format($capital, 2) .'</td>
                    </tr>';
        }

        $response = '<table border="1" width="100%" cellpadding="5" align="left">
                        <thead>
                            <tr style="text-align:center">
                                <td><b>Name of Business</b></td>
                                <td><b>Monthly Sales</b></td>
                                <td><b>Gross Monthly Income</b></td>
                                <td><b>Capital</b></td>
                            </tr>
                        </thead>
                        <tbody>
                            '.$list.'
                            
                            <tr style="text-align:center">
                                <td colspan="3"></td>
                                <td style="text-align:right"><b>Total</b></td>
                                <td>PHP '. number_format($averageTotal,2) .'</td>
                            </tr>
                        </tbody>
                    </table>';

        return $response;
    }
?>