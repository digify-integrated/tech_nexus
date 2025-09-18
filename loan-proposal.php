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
    require_once 'model/relation-model.php';

    $databaseModel = new DatabaseModel();
    $systemModel = new SystemModel();
    $userModel = new UserModel(new DatabaseModel, new SystemModel);
    $ciReportModel = new CIReportModel($databaseModel);
    $productModel = new ProductModel($databaseModel);
    $salesProposalModel = new SalesProposalModel($databaseModel);
    $relationModel = new RelationModel($databaseModel);
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
        $recommendation = $ciReportDetails['recommendation'] ?? '';
        $cmap_result = $ciReportDetails['cmap_result'] ?? '';
        $crif_result = $ciReportDetails['crif_result'] ?? '';
        $times_accomodated = $ciReportDetails['times_accomodated'] ?? '0';
        $adverse = $ciReportDetails['adverse'] ?? '';
        $cgmi_client_since = $ciReportDetails['cgmi_client_since'] ?? '';

        $highestExposureDetails = $ciReportModel->getCIReportHighestExposure($ci_report_id);
        $highestPN = $highestExposureDetails['pn_amount'] ?? 0;
        $highestAvailedDate = $systemModel->checkDate('empty', $highestExposureDetails['availed_date'] ?? null, '', 'm/d/Y', '');

        $primaryResidenceDetails = $ciReportModel->getPrimaryResidence($ci_report_id);
        $primaryResidenceRemarks = $primaryResidenceDetails['remarks'] ?? '';

        if($ci_character == 'Passed'){
            $ci_character_passed = '/';
            $ci_character_failed = '';
        }
        else{
            $ci_character_passed = '';
            $ci_character_failed = '/';
        }

        if($ci_capacity == 'Passed'){
            $ci_capacity_passed = '/';
            $ci_capacity_failed = '';
        }
        else{
            $ci_capacity_passed = '';
            $ci_capacity_failed = '/';
        }

        if($ci_capital == 'Passed'){
            $ci_capital_passed = '/';
            $ci_capital_failed = '';
        }
        else{
            $ci_capital_passed = '';
            $ci_capital_failed = '/';
        }

        if($ci_collateral == 'Passed'){
            $ci_collateral_passed = '/';
            $ci_collateral_failed = '';
        }
        else{
            $ci_collateral_passed = '';
            $ci_collateral_failed = '/';
        }

        if($ci_condition == 'Passed'){
            $ci_condition_passed = '/';
            $ci_condition_failed = '';
        }
        else{
            $ci_condition_passed = '';
            $ci_condition_failed = '/';
        }

        if($cmap_result == 'Negative'){
            $cmap_result_negative = '/';
            $cmap_result_positive = '';
        }
        else{
            $cmap_result_negative = '';
            $cmap_result_positive = '/';
        }

        if($crif_result == 'Negative'){
            $crif_result_negative = '/';
            $crif_result_positive = '';
        }
        else{
            $crif_result_negative = '';
            $crif_result_positive = '/';
        }

        if($adverse == 'Adverse'){
            $adverse_positive = '/';
            $adverse_negative = '';
        }
        else{
            $adverse_positive = '';
            $adverse_negative = '/';
        }

        if($acceptability == 'Acceptable'){
            $acceptability_positive = '/';
            $acceptability_negative = '';
        }
        else{
            $acceptability_positive = '';
            $acceptability_negative = '/';
        }

        if($loanability == 'Within Loanable Amount'){
            $loanability_positive = '/';
            $loanability_negative = '';
        }
        else{
            $loanability_positive = '';
            $loanability_negative = '/';
        }

        $customerDetails = $customerModel->getPersonalInformation($contact_id);
        $customerName = $customerDetails['file_as'] ?? '';
        $birthday = $customerDetails['birthday'] ?? null;
        $customerAge = getAge($birthday);

        $customerSpouse = $customerModel->getCustomerSpouse($contact_id);
        $spouseID = $customerSpouse['comaker_id'] ?? '';
        $spouseDetails = $customerModel->getPersonalInformation($spouseID);
        $spouseBirthday = $spouseDetails['birthday'] ?? null;
        $spouseAge = getAge($spouseBirthday);

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

        $assetTotal = $ciReportModel->getCIReportAssetsTotal($ci_report_id)['total'] ?? 0;
        $assetTotal = $ciReportModel->getCIReportAssetsTotal($ci_report_id)['total'] ?? 0;
        $receivableTotal = $ciReportModel->getCIReportBusinessExpenseTotal($ci_report_id, 'receivable')['total'] ?? 0;
        $outstandingBalance = $ciReportModel->getCIReportLoanTotal($ci_report_id, 'outstanding balance')['total'] ?? 0;
        $totalAsset = $receivableTotal + $assetTotal;
        $liability = $outstanding_balance + $outstandingBalance;
        $netWorth = $totalAsset - $liability;

        $loanPNAmountTotal = $ciReportModel->getCIReportLoanPNAmount($ci_report_id)['total'] ?? 0;
        $totalMonthlyIncome = $ciReportModel->getCIReportBusinessMonthlyIncome($ci_report_id)['total'] ?? 0;
        $totalNetSalary = $ciReportModel->getCIReportEmploymentExpenseTotal($ci_report_id, 'net salary')['total'] ?? 0;
        $totalIncome = $totalMonthlyIncome + $totalNetSalary;

        // Sales proposal
        $salesProposalDetails = $salesProposalModel->getSalesProposal($sales_proposal_id);
        $termLength = $salesProposalDetails['term_length'] ?? 0;
        $term_type = $salesProposalDetails['term_type'] ?? 0;
        $created_by = $salesProposalDetails['created_by'] ?? null;
        $referred_by = $salesProposalDetails['referred_by'] ?? '--';
        $renewal_tag = $salesProposalDetails['renewal_tag'] ?? 'New';
        $comakerID = $salesProposalDetails['comaker_id'] ?? null;
        $additional_maker_id = $salesProposalDetails['additional_maker_id'] ?? null;
        $comaker_id2 = $salesProposalDetails['comaker_id2'] ?? null;
        $product_type = $salesProposalDetails['product_type'] ?? null;

        $comakerDetails = $customerModel->getPersonalInformation($comakerID);
        $comakerName = strtoupper($comakerDetails['file_as'] ?? '');   

        $comakerDetails = $customerModel->getContactComakerDetailsViaComaker($contact_id, $comakerID);
        $comakerRelation = $comakerDetails['relation_id'] ?? '';
        $comakerRelationName = $relationModel->getRelation($comakerRelation)['relation_name'] ?? '';

        $addcomakerDetails = $customerModel->getPersonalInformation( $additional_maker_id);
        $addcomakerName = strtoupper($addcomakerDetails['file_as'] ?? '');

        $addcomakerDetails = $customerModel->getContactComakerDetailsViaComaker($contact_id, $additional_maker_id);
        $addcomakerRelation = $addcomakerDetails['relation_id'] ?? '';
        $addcomakerRelationName = $relationModel->getRelation($addcomakerRelation)['relation_name'] ?? '';

        $comaker2Details = $customerModel->getPersonalInformation($comaker_id2);
        $comaker2Name = strtoupper($comaker2Details['file_as'] ?? '');  

        $comaker2Details = $customerModel->getContactComakerDetailsViaComaker($contact_id, $comaker_id2);
        $comaker2Relation = $comaker2Details['relation_id'] ?? '';
        $comaker2RelationName = $relationModel->getRelation($comaker2Relation)['relation_name'] ?? '';

        $salesExecDetails = $userModel->getUserByID($created_by);
        $salesExec = $salesExecDetails['file_as'] ?? '--';

        if($renewal_tag == 'New'){
            $renewalCheck = "";
            $newCheck = "/";
        }
        else{
            $renewalCheck = "/";
            $newCheck = "";
        }

        if($product_type == 'Refinancing'){
            $refinancingCheck = "/";
            $financingCheck = "";
        }
        else{
            $refinancingCheck = "";
            $financingCheck = "/";
        }

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
        $capitalTotal = $ciReportModel->getCIReportBusinessExpenseTotal($ci_report_id, 'capital')['total'] ?? 0;
        $loanAmort = $ciReportModel->getCIReportLoanTotal($ci_report_id, 'repayment')['total'] ?? 0;
        $expensesTotal = $totalExpenseTotal + $rentalTotal;
        $lessExpenseTotal = $expensesTotal + $loanAmort;
        $ema = ($totalIncome - $lessExpenseTotal) * 0.6;
        $ela = $ema * $termLength;
        if($termLength > 0){
            $repaymentAmount = ceil((((($interest_rate/100)*$outstanding_balance)+$outstanding_balance))/$termLength);
        }
        else{
            $repaymentAmount = 0;
        }
        $loanRepaymentTotal = $ciReportModel->getCIReportLoanTotal($ci_report_id, 'repayment')['total'] ?? 0;

        if(!empty($sales_proposal_id)){
           $monthlyRate = $ciReportModel->rate(
                $termLength,                // 12
                $repaymentAmount * -1,      // -47,917.00
                $outstanding_balance        // 500,000.00
            );

            // Match Excel's =RATE(...)*12*100
            $effectiveAnnualYield = $monthlyRate * 12 * 100;
        }
        else{
            $effectiveAnnualYield = 0;
        }

        if($loanRepaymentTotal > 0){
            $debtServiceCoverage = $totalIncome /( $loanRepaymentTotal + $repaymentAmount);
        }
        else{
            $debtServiceCoverage = 0;
        }
        
        if($totalAsset > 0){
            $debtToAsset = ($liability / $totalAsset) * 100;
        }
        else{
            $debtToAsset = 0;
        }
        
        if($capitalTotal > 0){
            $debtToEquity = ($liability / $capitalTotal) * 100;
        }
        else{
            $debtToEquity = 0;
        }

    }

    $cgmiLoanTable = generateCGMILoanTable($ci_report_id, $pn_amount, $outstanding_balance, $repaymentAmount);
    $collateralTable = generateCollateralTable($ci_report_id, $outstanding_balance);
    $businessTable = generateBusinessTable($ci_report_id);
    $employmentTable = generateEmploymentTable($ci_report_id);
    $bankStatementTable = generateBankStatementTable($ci_report_id);
    $salesIncomeTable = generateSalesIncomeTable($ci_report_id, $totalExpenseTotal, $loanAmort, $ema, $ela);
    $tradeReferenceTable = generateTradeReferenceTable($ci_report_id);
    $assetsTable = generateAssetsTable($ci_report_id, $pn_amount);
    $dependentsTable = generateDependentsTable($ci_report_id);
    $residenceTable = generateResidenceTable($ci_report_id);
    $propertyTable = generatePropertyTable($ci_report_id);
    $businessOperations = generateBusinessOperationsTable($ci_report_id);
    $exceptionsTable = generateExceptionsTable($ci_report_id);

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
    $pdf->SetTitle('Loan Proposal');
    $pdf->SetSubject('Loan Proposal');

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
    $pdf->Cell(120, 5, 'GENERAL MANAGER', 'B', 0, 'L');
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
    $pdf->Cell(5, 5, $newCheck, 1, 0, 'C');
    $pdf->Cell(3, 5, '', 0, 0, 'L');
    $pdf->Cell(40, 5, 'NEW', 0, 0, 'L');
    $pdf->Cell(5, 5, $renewalCheck, 1, 0, 'C');
    $pdf->Cell(3, 5, '', 0, 0, 'L');
    $pdf->Cell(50, 5, 'REPEAT', 0, 0, 'L');
    $pdf->SetFont('times', '', 10);
    $pdf->Ln(10);
    $pdf->Cell(40, 5, 'AMOUNT APPLIED:', 0, 0, 'L');
    $pdf->Cell(50, 5, 'PHP ' . number_format($outstanding_balance, 2), 'B', 0, 'L');
    $pdf->Cell(5, 5, '', 0, 0, 'L');
    $pdf->Cell(25, 5, 'SALES EXEC:', 0, 0, 'L');
    $pdf->Cell(5, 5, '', 0, 0, 'L');
    $pdf->Cell(60, 5, strtoupper($salesExec), 'B', 0, 'C');
    $pdf->Ln(6);
    $pdf->Cell(30, 5, 'RATE:', 0, 0, 'L');
    $pdf->Cell(10, 5, '', 0, 0, align: 'L');
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
    $pdf->Cell(15, 5, number_format($termLength, 0), 'B', 0, 'C');
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
    $pdf->MultiCell(145, 5, strtoupper($primaryResidenceRemarks), 'B', 'L');


    $pdf->Ln(5);
    $pdf->SetFont('times', 'B', 10);
    $pdf->Cell(80, 5, 'II. LOAN HISTORY', 0, 0, 'L');
    $pdf->Ln(8);
    $pdf->SetFont('times', '', 8.5);
    $pdf->writeHTML($cgmiLoanTable, true, false, true, false, '');
    
    $pdf->Ln(0);
    $pdf->SetFont('times', 'B', 10);
    $pdf->Cell(80, 5, 'III. COLLATERAL DESCRIPTION', 0, 0, 'L');
    $pdf->Cell(5, 5, $financingCheck, 1, 0, 'C');
    $pdf->Cell(3, 5, '', 0, 0, 'L');
    $pdf->Cell(40, 5, 'FINANCING', 0, 0, 'L');
    $pdf->Cell(5, 5, $refinancingCheck, 1, 0, 'C');
    $pdf->Cell(3, 5, '', 0, 0, 'L');
    $pdf->Cell(50, 5, 'REFINANCING', 0, 0, 'L');
    
    $pdf->Ln(8);
      $pdf->SetFont('times', '', 8.5);
    $pdf->writeHTML($collateralTable, true, false, true, false, '');
    
    $pdf->Ln(0);
    $pdf->SetFont('times', 'B', 10);
    $pdf->Cell(80, 5, 'IV. GENERAL INFORMATION', 0, 0, 'L');
    $pdf->Ln(8);
      $pdf->SetFont('times', '', 8.5);
    $pdf->writeHTML($businessTable, true, false, true, false, '');
    $pdf->Ln(0);
      $pdf->SetFont('times', '', 8.5);
    $pdf->writeHTML($employmentTable, true, false, true, false, '');

     $pdf->Ln(0);
    $pdf->SetFont('times', 'B', 10);
    $pdf->Cell(80, 5, "5C'S OF CREDIT", 0, 0, 'L');$pdf->Ln(0);
     $pdf->Ln(5);

    //CHARACTER

    $pdf->SetFont('times', 'B', 10);
    $pdf->Cell(80, 5, 'CHARACTER', 0, 0, 'L');
    $pdf->Cell(5, 5, $ci_character_passed, 1, 0, 'C');
    $pdf->Cell(3, 5, '', 0, 0, 'L');
    $pdf->Cell(40, 5, 'PASSED', 0, 0, 'L');
    $pdf->Cell(5, 5, $ci_character_failed, 1, 0, 'C');
    $pdf->Cell(3, 5, '', 0, 0, 'L');
    $pdf->Cell(50, 5, 'FAILED', 0, 0, 'L');
     $pdf->Ln(8);
    $pdf->SetFont('times', 'B', 10);
    $pdf->Cell(80, 5, 'CMAP RESULT', 0, 0, 'L');
    $pdf->Cell(5, 5, $cmap_result_negative, 1, 0, 'C');
    $pdf->Cell(3, 5, '', 0, 0, 'L');
    $pdf->Cell(40, 5, 'NEGATIVE', 0, 0, 'L');
    $pdf->Cell(5, 5, $cmap_result_positive, 1, 0, 'C');
    $pdf->Cell(3, 5, '', 0, 0, 'L');
    $pdf->Cell(50, 5, 'POSITIVE', 0, 0, 'L');
     $pdf->Ln(8);
    $pdf->SetFont('times', 'B', 10);
    $pdf->Cell(80, 5, 'CRIF RESULT', 0, 0, 'L');
    $pdf->Cell(5, 5, $crif_result_negative, 1, 0, 'C');
    $pdf->Cell(3, 5, '', 0, 0, 'L');
    $pdf->Cell(40, 5, 'NEGATIVE', 0, 0, 'L');
    $pdf->Cell(5, 5, $crif_result_positive, 1, 0, 'C');
    $pdf->Cell(3, 5, '', 0, 0, 'L');
    $pdf->Cell(50, 5, 'POSITIVE', 0, 0, 'L');
     $pdf->Ln(8);
    $pdf->SetFont('times', 'B', 10);
    $pdf->Cell(55, 5, 'TIMES ACCOMMODATION', 0, 0, 'L');
    $pdf->Cell(5, 5, '', 0, 0, 'L');
    $pdf->Cell(15, 5, number_format($times_accomodated, 0), 'B', 0, 'C');
    $pdf->Cell(5, 5, '', 0, 0, 'L');
    $pdf->Cell(5, 5, $adverse_positive, 1, 0, 'C');
    $pdf->Cell(3, 5, '', 0, 0, 'L');
    $pdf->Cell(40, 5, 'ADVERSE', 0, 0, 'L');
    $pdf->Cell(5, 5, $adverse_negative, 1, 0, 'C');
    $pdf->Cell(3, 5, '', 0, 0, 'L');
    $pdf->Cell(50, 5, 'NOTHING ADVERSE', 0, 0, 'L');
    
    //CAPACITY

    $pdf->Ln(10);
    $pdf->SetFont('times', 'B', 10);
    $pdf->Cell(80, 5, 'CAPACITY', 0, 0, 'L');
    $pdf->Cell(5, 5, $ci_capacity_passed, 1, 0, 'C');
    $pdf->Cell(3, 5, '', 0, 0, 'L');
    $pdf->Cell(40, 5, 'PASSED', 0, 0, 'L');
    $pdf->Cell(5, 5, $ci_capacity_failed, 1, 0, 'C');
    $pdf->Cell(3, 5, '', 0, 0, 'L');
    $pdf->Cell(50, 5, 'FAILED', 0, 0, 'L');
     $pdf->Ln(8);
    $pdf->SetFont('times', 'B', 10);
    $pdf->Cell(80, 5, "BANK STATEMENT", 0, 0, 'L');

    $pdf->Ln(8);
      $pdf->SetFont('times', '', 8.5);
    $pdf->writeHTML($bankStatementTable, true, false, true, false, '');

     $pdf->Ln(-2);
    $pdf->SetFont('times', 'B', 10);
    $pdf->Cell(80, 5, "MONTHLY SALES INCOME", 0, 0, 'L');

    $pdf->Ln(8);
      $pdf->SetFont('times',  '', 8);
    $pdf->writeHTML($salesIncomeTable, true, false, true, false, '');

    
     $pdf->Ln(-2);
    $pdf->SetFont('times', 'B', 10);
    $pdf->Cell(80, 5, "TRADE REFERENCES", 0, 0, 'L');

    $pdf->Ln(8);
      $pdf->SetFont('times',  '', 8);
    $pdf->writeHTML($tradeReferenceTable, true, false, true, false, '');

    
    //CAPITAL

    $pdf->Ln(-2);
    $pdf->SetFont('times', 'B', 10);
    $pdf->Cell(80, 5, 'CAPITAL', 0, 0, 'L');
    $pdf->Cell(5, 5, $ci_capital_passed, 1, 0, 'C');
    $pdf->Cell(3, 5, '', 0, 0, 'L');
    $pdf->Cell(40, 5, 'PASSED', 0, 0, 'L');
    $pdf->Cell(5, 5, $ci_capital_failed, 1, 0, 'C');
    $pdf->Cell(3, 5, '', 0, 0, 'L');
    $pdf->Cell(50, 5, 'FAILED', 0, 0, 'L');

    $pdf->Ln(8);
      $pdf->SetFont('times',  '', 8);
    $pdf->writeHTML($assetsTable, true, false, true, false, '');
    
    //COLLATERAL

    $pdf->Ln(-2);
    $pdf->SetFont('times', 'B', 10);
    $pdf->Cell(80, 5, 'COLLATERAL', 0, 0, 'L');
    $pdf->Cell(5, 5, $ci_collateral_passed, 1, 0, 'C');
    $pdf->Cell(3, 5, '', 0, 0, 'L');
    $pdf->Cell(40, 5, 'PASSED', 0, 0, 'L');
    $pdf->Cell(5, 5, $ci_collateral_failed, 1, 0, 'C');
    $pdf->Cell(3, 5, '', 0, 0, 'L');
    $pdf->Cell(50, 5, 'FAILED', 0, 0, 'L');
    
    $pdf->Ln(8);
    $pdf->SetFont('times', 'B', 10);
    $pdf->Cell(5, 5, $acceptability_positive, 1, 0, 'C');
    $pdf->Cell(3, 5, '', 0, 0, 'L');
    $pdf->Cell(50, 5, 'ACCEPTABLE', 0, 0, 'L');
    $pdf->SetFont('times', 'B', 10);
    $pdf->Cell(5, 5, $loanability_positive, 1, 0, 'C');
    $pdf->Cell(3, 5, '', 0, 0, 'L');
    $pdf->Cell(80, 5, 'WITHIN LOANABLE AMOUNT', 0, 0, 'L');
    $pdf->Ln(8);
    $pdf->SetFont('times', 'B', 10);
    $pdf->Cell(5, 5, $acceptability_negative, 1, 0, 'C');
    $pdf->Cell(3, 5, '', 0, 0, 'L');
    $pdf->Cell(50, 5, 'NOT ACCEPTABLE', 0, 0, 'L');
    $pdf->SetFont('times', 'B', 10);
    $pdf->Cell(5, 5, $loanability_negative, 1, 0, 'C');
    $pdf->Cell(3, 5, '', 0, 0, 'L');
    $pdf->Cell(80, 5, 'NOT WITHIN LOANABLE AMOUNT', 0, 0, 'L');
    $pdf->Ln(10);

    
    $pdf->SetFont('times', '', 8.5);
    $pdf->Cell(30, 5, 'CO-MAKER 1:', 0, 0, 'L');
    $pdf->Cell(80, 5, strtoupper($comakerName), 'B', 0, 'L');
    $pdf->Cell(5, 5, '', 0, 0, 'L');
    $pdf->Cell(30, 5, 'RELATIONSHIP:', 0, 0, 'L');
    $pdf->Cell(40, 5, strtoupper($comakerRelationName), 'B', 0, 'L');
    
    $pdf->Ln(5);
    $pdf->Cell(30, 5, 'CO-MAKER 2:', 0, 0, 'L');
    $pdf->Cell(80, 5, strtoupper($comaker2Name), 'B', 0, 'L');
    $pdf->Cell(5, 5, '', 0, 0, 'L');
    $pdf->Cell(30, 5, 'RELATIONSHIP:', 0, 0, 'L');
    $pdf->Cell(40, 5, strtoupper($comaker2RelationName), 'B', 0, 'L');

    $pdf->Ln(5);
    $pdf->Cell(50, 5, 'ADDITIONAL CO-MAKER:', 0, 0, 'L');
    $pdf->Cell(60, 5, strtoupper($addcomakerName), 'B', 0, 'L');
    $pdf->Cell(5, 5, '', 0, 0, 'L');
    $pdf->Cell(30, 5, 'RELATIONSHIP:', 0, 0, 'L');
    $pdf->Cell(40, 5, strtoupper($addcomakerRelationName), 'B', 0, 'L');

    //CONDITION
    
    $pdf->Ln(8);
    $pdf->SetFont('times', 'B', 10);
    $pdf->Cell(80, 5, 'CONDITION', 0, 0, 'L');
    $pdf->Cell(5, 5, $ci_condition_passed, 1, 0, 'C');
    $pdf->Cell(3, 5, '', 0, 0, 'L');
    $pdf->Cell(40, 5, 'PASSED', 0, 0, 'L');
    $pdf->Cell(5, 5, $ci_condition_failed, 1, 0, 'C');
    $pdf->Cell(3, 5, '', 0, 0, 'L');
    $pdf->Cell(50, 5, 'FAILED', 0, 0, 'L');

    
    $pdf->Ln(8);
    $pdf->SetFont('times',  '', 8);
    $pdf->writeHTML($dependentsTable, true, false, true, false, '');

    
    $pdf->Ln(-2);
    $pdf->Cell(40, 5, 'AGE OF APPLICANT:', 0, 0, 'L');
    $pdf->Cell(15, 5, $customerAge, 'B', 0, 'L');
    $pdf->Cell(40, 5, 'Y/O', 0, 0, 'L');

    $pdf->Ln(5);
    $pdf->Cell(40, 5, 'AGE OF SPOUSE:', 0, 0, 'L');
    $pdf->Cell(15, 5, $spouseAge, 'B', 0, 'L');
    $pdf->Cell(40, 5, 'Y/O', 0, 0, 'L');

    $pdf->Ln(8);
    $pdf->SetFont('times',  '', 8);
    $pdf->writeHTML($residenceTable, true, false, true, false, '');

    
    
    $pdf->Ln(-2);
    $pdf->Cell(50, 5, 'HIGHEST AMOUNT EXPOSURE:', 0, 0, 'L');
    $pdf->Cell(40, 5, 'PHP ' . number_format($highestPN, 2), 'B', 0, 'L');
    $pdf->Cell(20, 5, '', 0, 0, 'L');
    $pdf->Cell(15, 5, 'DATE:', 0, 0, 'L');
    $pdf->Cell(30, 5, $highestAvailedDate, 'B', 0, 'L');
    $pdf->Ln(8);
    $pdf->Cell(40, 5, 'CGMI CLIENT SINCE:', 0, 0, 'L');
    $pdf->Cell(40, 5, $cgmi_client_since, 'B', 0, 'L');
    
    $pdf->Ln(8);
    $pdf->Cell(40, 5, 'BUSINESS/EMPLOYMENT YEARS IN OPERATION:', 0, 0, 'L');
    $pdf->Ln(8);
    $pdf->writeHTML($businessOperations, true, false, true, false, '');

    $pdf->Ln(8);
    $pdf->Cell(40, 5, 'PURPOSE OF LOAN:', 0, 0, 'L');
    $pdf->MultiCell(145, 5, strtoupper($purpose_of_loan), 'B', 'L');

    $pdf->Ln(5);
    $pdf->SetFont('times', 'B', 10);
    $pdf->Cell(80, 5, 'VEHICLE/PROPERTY (S) OWNED', 0, 0, 'L');

    $pdf->Ln(8);
    $pdf->SetFont('times',  '', 8);
    $pdf->writeHTML($propertyTable, true, false, true, false, '');
    
    $pdf->Ln(-5);
    $pdf->SetFont('times', 'B', 10);
    $pdf->Cell(80, 5, 'V. EXCEPTIONS', 0, 0, 'L');
    $pdf->Ln(5);
    $pdf->Cell(80, 5, 'NUMBER EXISTING LOAN (S):', 0, 0, 'L');
    $pdf->Ln(5);
    $pdf->SetFont('times',  'B', 8);
    $pdf->writeHTML($exceptionsTable, true, false, true, false, '');

    $pdf->Ln(5);
    $pdf->SetFont('times', 'B', 10);
    $pdf->Cell(80, 5, 'VI. EVALUATION & JUSTIFICATION', 0, 0, 'L');
    $pdf->Ln(5);
    $pdf->SetFont('times',  '', 8);
    $pdf->MultiCell(145, 5, 'DEBT TO ASSET RATIO OF <b><u>'. number_format($debtToAsset, 2) .'%</u></b> (ACCEPTABLE IS 50% AND BELOW)', 0, 'L', 0, 1, '', '', true, 0, true, true, 0);
    $pdf->MultiCell(145, 5, 'DEBT TO EQUITY RATIO OF <b><u>'. number_format($debtToEquity, 2) .'%</u></b> (ACCEPTABLE IS 50% AND BELOW)', 0, 'L', 0, 1, '', '', true, 0, true, true, 0);
    $pdf->MultiCell(145, 5, 'DEBT SERVICE COVERAGE RATIO OF <b><u>'. number_format($debtServiceCoverage, 2) .'</u></b> (ACCEPTABLE IS 1.00 AND ABOVE)', 0, 'L', 0, 1, '', '', true, 0, true, true, 0);
    $pdf->Ln(5);
    $pdf->SetFont('times', 'B', 10);
    $pdf->Cell(80, 5, 'RECOMMENDATION', 0, 0, 'L');
    $pdf->SetFont('times',  '', 8);
    $pdf->Ln(5);
    $pdf->MultiCell(145, 5, strtoupper($recommendation), 0, 'L');

    //$pdf->writeHTML($summaryTable, true, false, true, false, '');

    // Output the PDF to the browser
    $pdf->Output('loan-proposal-' . $ci_report_id . '.pdf', 'I');
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
        $list2 = '';
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
            $remarks = $row['remarks'];
            $availed_date = $systemModel->checkDate('empty', $row['availed_date'], '', 'm/d/Y', '');

            
            $ob_total = $ob_total + $outstanding_balance;
           
            if($outstanding_balance > 0){
                $repayment_total = $repayment_total + $repayment;
                $pn_amount_total = $pn_amount_total + $pn_amount;
            }

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

        $repayment_total2 = 0;
        $pn_amount_total2 = 0;
        $ob_total2 = 0;

        $sql = $databaseModel->getConnection()->prepare('SELECT * FROM ci_report_loan WHERE ci_report_id = :ci_report_id AND (loan_source != "CGMI" OR loan_source IS NULL)');
        $sql->bindValue(':ci_report_id', $ci_report_id, PDO::PARAM_INT);
        $sql->execute();
        $options = $sql->fetchAll(PDO::FETCH_ASSOC);
        $sql->closeCursor();

        foreach ($options as $row) {
            $account_name = $row['account_name'];
            $pn_amount = $row['pn_amount'];
            $company = $row['company'];
            $outstanding_balance = $row['outstanding_balance'];
            $repayment = $row['repayment'];
            $term = $row['term'];
            $handling = $row['handling'];
            $remarks = $row['remarks'];
            $availed_date = $systemModel->checkDate('empty', $row['availed_date'], '', 'm/d/Y', '');

            $ob_total2 = $ob_total2 + $outstanding_balance;

            if($outstanding_balance > 0){
                $repayment_total2 = $repayment_total2 + $repayment;
                $pn_amount_total2 = $pn_amount_total2 + $pn_amount;
            }

            $list2 .= '<tr>
                        <td>'. $company .'</td>
                        <td style="text-align:center">'. $availed_date .'</td>
                        <td style="text-align:center">PHP '. number_format($pn_amount,2) .'</td>
                        <td style="text-align:center">PHP '. number_format($outstanding_balance,2) .'</td>
                        <td style="text-align:center">PHP '. number_format($repayment,2) .'</td>
                        <td style="text-align:center">'. $term .'</td>
                        <td style="text-align:center">'. $handling .'</td>
                        <td style="text-align:center">'. strtoupper($remarks)  .'</td>
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
                                <td>PHP '. number_format($ci_pn_amount,2) .'</td>
                                <td>PHP '. number_format($ci_repayment,2) .'</td>
                            </tr>
                            <tr style="text-align:center">
                                <td style="text-align:right"><b>Total CGMI Loan including new application</b></td>
                                <td>PHP '. number_format($ci_pn_amount + $pn_amount_total,2) .'</td>
                                <td>PHP '. number_format($ci_pn_amount + $ob_total,2) .'</td>
                                <td>PHP '. number_format($ci_repayment + $repayment_total,2) .'</td>
                            </tr>
                        </tbody>
                    </table><br/><br/>
                    
                    <table border="1" width="100%" cellpadding="5" align="left">
                        <thead>
                            <tr style="text-align:center">
                                <td><b>Other Loans</b></td>
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
                            '.$list2.'
                            <tr style="text-align:center">
                                <td rowspan="3"></td>
                                <td style="text-align:right"><b>Total Other Loans</b></td>
                                <td>PHP '. number_format($pn_amount_total2,2) .'</td>
                                <td>PHP '. number_format($ob_total2,2) .'</td>
                                <td>PHP '. number_format($repayment_total2,2) .'</td>
                                <td colspan="4" rowspan="3"></td>
                            </tr>
                            <tr style="text-align:center">
                                <td style="text-align:right"><b>Grand Total Existing</b></td>
                                <td>PHP '. number_format($ci_pn_amount + $pn_amount_total + $pn_amount_total2,2) .'</td>
                                <td>PHP '. number_format($ci_pn_amount + $ob_total + $ob_total2,2) .'</td>
                                <td>PHP '. number_format($ci_repayment + $repayment_total + $repayment_total2,2) .'</td>
                            </tr>
                        </tbody>
                    </table>';

        return $response;
    }

    function generateExceptionsTable($ci_report_id){
        
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
        $monthly_income_total = 0;
        $gross_monthly_sale_total = 0;
        $capital_total = 0;
        $rental_total = 0;
            
        $sql = $databaseModel->getConnection()->prepare('SELECT COUNT(*) AS total FROM ci_report_loan WHERE ci_report_id = :ci_report_id AND loan_source = "CGMI" AND outstanding_balance > 0');
        $sql->bindValue(':ci_report_id', $ci_report_id, PDO::PARAM_INT);
        $sql->execute();
        $options = $sql->fetch(PDO::FETCH_ASSOC);
        $sql->closeCursor();

        $list .= '<li><b>CGMI</b> - <u>'. $options['total'] .'</u></li>';

        $sql = $databaseModel->getConnection()->prepare('SELECT COUNT(*) AS total FROM ci_report_loan WHERE ci_report_id = :ci_report_id AND (loan_source != "CGMI" OR loan_source IS NULL) AND outstanding_balance > 0');
        $sql->bindValue(':ci_report_id', $ci_report_id, PDO::PARAM_INT);
        $sql->execute();
        $options = $sql->fetch(PDO::FETCH_ASSOC);
        $sql->closeCursor();

        $list .= '<li><b>OTHERS</b> - <u>'. $options['total'] .'</u></li>';

        $response = '<ul>
                        '. $list .'
                    </ul>';

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
        require_once 'model/brand-model.php';

        $databaseModel = new DatabaseModel();
        $systemModel = new SystemModel();
        $userModel = new UserModel(new DatabaseModel, new SystemModel);
        $contractorModel = new ContractorModel($databaseModel);
        $workCenterModel = new WorkCenterModel($databaseModel);
        $salesProposalModel = new SalesProposalModel($databaseModel);
        $brandModel = new BrandModel($databaseModel);

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
            $brand_id = $row['brand_id'];

            $brand_name = $brandModel->getBrand($brand_id)['brand_name'] ?? null;

            $appraised_value_total = $appraised_value_total + $appraised_value;
            $loannable_value_total = $loannable_value_total + $loannable_value;

            $list .= '<tr>
                        <td>'. $brand_name .' '. $description .'</td>
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
                        <td>'. strtoupper($bankHandlingTypeName) .'</td>
                        <td style="text-align:center">PHP '. number_format($average, 2) .'</td>
                    </tr>';
        }

        $response = '<table border="1" width="100%" cellpadding="5" align="left">
                        <thead>
                            <tr style="text-align:center">
                                <td><b>Bank</b></td>
                                <td><b>Account</b></td>
                                <td><b>Date Open</b></td>
                                <td><b>ADB</b></td>
                                <td><b>HANDLING</b></td>
                                <td><b>AVE. CREDIT BALANCE</b></td>
                            </tr>
                        </thead>
                        <tbody>
                            '.$list.'
                            
                            <tr style="text-align:center">
                                <td colspan="5" style="text-align:right"><b>Total</b></td>
                                <td>PHP '. number_format($averageTotal,2) .'</td>
                            </tr>
                        </tbody>
                    </table>';

        return $response;
    }

    function generateSalesIncomeTable($ci_report_id, $residenceExpenseTotal, $loanAmort, $ema, $ela){
        
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
        $monthly_income_total = 0;
        $gross_monthly_sale_total = 0;
        $capital_total = 0;
        $rental_total = 0;
            
        $sql = $databaseModel->getConnection()->prepare('SELECT * FROM ci_report_business WHERE ci_report_id = :ci_report_id');
        $sql->bindValue(':ci_report_id', $ci_report_id, PDO::PARAM_INT);
        $sql->execute();
        $options = $sql->fetchAll(PDO::FETCH_ASSOC);
        $sql->closeCursor();

        foreach ($options as $row) {
            $business_name  = $row['business_name'];
            $gross_monthly_sale  = $row['gross_monthly_sale'];
            $monthly_income  = $row['monthly_income'];
            $capital  = $row['capital'];
            $rental_amount  = $row['rental_amount'];

            $monthly_income_total = $monthly_income_total + $monthly_income;
            $gross_monthly_sale_total = $gross_monthly_sale_total + $gross_monthly_sale;
            $capital_total = $capital_total + $capital;
            
            $list .= '<tr>
                        <td>'. strtoupper($business_name) .'</td>
                        <td style="text-align:center">PHP '. number_format($monthly_income, 2) .'</td>
                        <td style="text-align:center">PHP '. number_format($gross_monthly_sale, 2) .'</td>
                        <td style="text-align:center">PHP '. number_format($capital, 2) .'</td>
                    </tr>';
        }
            
        $sql = $databaseModel->getConnection()->prepare('SELECT * FROM ci_report_employment WHERE ci_report_id = :ci_report_id');
        $sql->bindValue(':ci_report_id', $ci_report_id, PDO::PARAM_INT);
        $sql->execute();
        $options = $sql->fetchAll(PDO::FETCH_ASSOC);
        $sql->closeCursor();

        foreach ($options as $row) {
            $employment_name  = $row['employment_name'];
            $net_salary  = $row['net_salary'];
            $commission  = $row['commission'];
            $allowance  = $row['allowance'];
            $other_income  = $row['other_income'];
            $grandtotal = $net_salary + $commission + $allowance + $other_income;

            $monthly_income_total = $monthly_income_total + $grandtotal;
            $gross_monthly_sale_total = $gross_monthly_sale_total + $grandtotal;

            $list .= '<tr>
                        <td>'. strtoupper($employment_name) .'</td>
                        <td style="text-align:center">PHP '. number_format($grandtotal, 2) .'</td>
                        <td style="text-align:center">PHP '. number_format($grandtotal, 2) .'</td>
                        <td style="text-align:center">PHP '. number_format(0, 2) .'</td>
                    </tr>';
        }

        $response = '<table border="1" width="100%" cellpadding="5" align="left">
                        <thead>
                            <tr style="text-align:center">
                                <td><b>Name of Business/Employment</b></td>
                                <td><b>Monthly Sales</b></td>
                                <td><b>Gross Monthly Income</b></td>
                                <td><b>Capital</b></td>
                            </tr>
                        </thead>
                        <tbody>
                            '.$list.'
                            
                            <tr style="text-align:center">
                                <td style="text-align:right"><b>Total</b></td>
                                <td>PHP '. number_format($monthly_income_total,2) .'</td>
                                <td>PHP '. number_format($gross_monthly_sale_total,2) .'</td>
                                <td>PHP '. number_format($capital_total,2) .'</td>
                            </tr>
                            <tr style="text-align:center">
                                <td colspan="2" style="text-align:right"><b>LESS: CGMI & OTHER LOAN AMORT</b></td>
                                <td>PHP '. number_format($loanAmort,2) .'</td>
                                <td colspan="2"></td>
                            </tr>
                            <tr style="text-align:center">
                                <td colspan="2" style="text-align:right"><b>Personal Expenses</b></td>
                                <td>PHP '. number_format(($residenceExpenseTotal + $rental_total),2) .'</td>
                                <td colspan="2"></td>
                            </tr>
                            <tr style="text-align:center">
                                <td colspan="2" style="text-align:right"><b>EMA</b></td>
                                <td>'. number_format($ema,2) .'</td>
                                <td colspan="2"></td>
                            </tr>
                            <tr style="text-align:center">
                                <td colspan="2" style="text-align:right"><b>EMA</b></td>
                                <td>'. number_format($ela,2) .'</td>
                                <td colspan="2"></td>
                            </tr>
                        </tbody>
                    </table>';

        return $response;
    }

    function generateBusinessOperationsTable($ci_report_id){
        
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
        $monthly_income_total = 0;
        $gross_monthly_sale_total = 0;
        $capital_total = 0;
        $rental_total = 0;
            
        $sql = $databaseModel->getConnection()->prepare('SELECT * FROM ci_report_business WHERE ci_report_id = :ci_report_id');
        $sql->bindValue(':ci_report_id', $ci_report_id, PDO::PARAM_INT);
        $sql->execute();
        $options = $sql->fetchAll(PDO::FETCH_ASSOC);
        $sql->closeCursor();

        foreach ($options as $row) {
            $business_name  = $row['business_name'];
            $length_stay_year  = $row['length_stay_year'];
            $length_stay_month  = $row['length_stay_month'];
            $length_stay_total = formatLengthOfStay($length_stay_year, $length_stay_month);

            $list .= '<li>'. strtoupper($business_name) .' - <u>'. $length_stay_total .'</u></li>';
        }
            
        $sql = $databaseModel->getConnection()->prepare('SELECT * FROM ci_report_employment WHERE ci_report_id = :ci_report_id');
        $sql->bindValue(':ci_report_id', $ci_report_id, PDO::PARAM_INT);
        $sql->execute();
        $options = $sql->fetchAll(PDO::FETCH_ASSOC);
        $sql->closeCursor();

        foreach ($options as $row) {
            $employment_name  = $row['employment_name'];
            $length_stay_year  = $row['length_stay_year'];
            $length_stay_month  = $row['length_stay_month'];
            $length_stay_total = formatLengthOfStay($length_stay_year, $length_stay_month);

            $list .= '<li>'. strtoupper($employment_name) .' - <u>'. $length_stay_total .'</u></li>';
        }

        $response = '<ul>
                        '. $list .'
                    </ul>';

        return $response;
    }

    function formatLengthOfStay($years, $months) {
        // Convert everything to months
        $totalMonths = ($years * 12) + $months;

        // Break back into years + months
        $calcYears  = intdiv($totalMonths, 12);
        $calcMonths = $totalMonths % 12;

        $parts = [];

        if ($calcYears > 0) {
            $parts[] = $calcYears . ' ' . ($calcYears === 1 ? 'year' : 'years');
        }
        if ($calcMonths > 0) {
            $parts[] = $calcMonths . ' ' . ($calcMonths === 1 ? 'month' : 'months');
        }

        // Fallback if both are zero
        if (empty($parts)) {
            return '0 months';
        }

        return implode(' and ', $parts);
    }

    function generateTradeReferenceTable($ci_report_id){
        
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
        $monthly_income_total = 0;
        $gross_monthly_sale_total = 0;
        $capital_total = 0;
        $rental_total = 0;
            
        $sql = $databaseModel->getConnection()->prepare('SELECT * FROM ci_report_trade_reference WHERE ci_report_id = :ci_report_id ORDER BY ci_report_business_id');
        $sql->bindValue(':ci_report_id', $ci_report_id, PDO::PARAM_INT);
        $sql->execute();
        $options = $sql->fetchAll(PDO::FETCH_ASSOC);
        $sql->closeCursor();

        $averageTotal = 0;
        foreach ($options as $row) {
            $ci_report_business_id  = $row['ci_report_business_id'];
            $supplier  = $row['supplier'];
            $contact_person  = $row['contact_person'];
            $years_of_transaction  = $row['years_of_transaction'];
            $remarks  = $row['remarks'];

            $business_name = $ciReportModel->getCIReportBusiness($ci_report_business_id)['business_name'];
            
            $list .= '<tr>
                        <td>'. strtoupper($business_name) .'</td>
                        <td>'. strtoupper($supplier) .'</td>
                        <td>'. strtoupper($contact_person) .'</td>
                        <td>'. strtoupper($years_of_transaction) .'</td>
                        <td>'. strtoupper($remarks) .'</td>
                    </tr>';
        }

        $response = '<table border="1" width="100%" cellpadding="5" align="left">
                        <thead>
                            <tr style="text-align:center">
                                <td><b>Business</b></td>
                                <td><b>Supplier</b></td>
                                <td><b>Contact Person</b></td>
                                <td><b>Years of Transaction</b></td>
                                <td><b>Remarks</b></td>
                            </tr>
                        </thead>
                        <tbody>
                            '.$list.'
                        </tbody>
                    </table>';

        return $response;
    }

    function generateAssetsTable($ci_report_id, $apply_ob){
        
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

        $assetTotal = $ciReportModel->getCIReportAssetsTotal($ci_report_id)['total'] ?? 0;
        $receivableTotal = $ciReportModel->getCIReportBusinessExpenseTotal($ci_report_id, 'receivable')['total'] ?? 0;
        $inventoryTotal = $ciReportModel->getCIReportBusinessExpenseTotal($ci_report_id, 'inventory')['total'] ?? 0;
        $outstandingBalance = $ciReportModel->getCIReportLoanTotal($ci_report_id, 'outstanding with balance')['total'] ?? 0;
        $totalAsset = $receivableTotal + $inventoryTotal + $assetTotal;
        $liability = $apply_ob + $outstandingBalance;

        $response = '<table border="1" width="100%" cellpadding="5" align="left">
                        <tbody>
                            
                            <tr style="text-align:center">
                                <td style="text-align:right"><b>Total Asset</b></td>
                                <td>PHP '. number_format($assetTotal,2) .'</td>
                            </tr>
                            <tr style="text-align:center">
                                <td style="text-align:right"><b>Total Receivable</b></td>
                                <td>PHP '. number_format($receivableTotal,2) .'</td>
                            </tr>
                            <tr style="text-align:center">
                                <td style="text-align:right"><b>Total</b></td>
                                <td>PHP '. number_format($totalAsset,2) .'</td>
                            </tr>
                            <tr style="text-align:center">
                                <td style="text-align:right"><b>Less Liabilities</b></td>
                                <td>PHP '. number_format(($liability),2) .'</td>
                            </tr>
                            <tr style="text-align:center">
                                <td style="text-align:right"><b>Net Worth</b></td>
                                <td>PHP '. number_format(($totalAsset - $liability),2) .'</td>
                            </tr>
                        </tbody>
                    </table>';

        return $response;
    }

    function generateDependentsTable($ci_report_id){
        
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
        $monthly_income_total = 0;
        $gross_monthly_sale_total = 0;
        $capital_total = 0;
        $rental_total = 0;
            
        $sql = $databaseModel->getConnection()->prepare('SELECT * FROM ci_report_dependents WHERE ci_report_id = :ci_report_id ORDER BY name');
        $sql->bindValue(':ci_report_id', $ci_report_id, PDO::PARAM_INT);
        $sql->execute();
        $options = $sql->fetchAll(PDO::FETCH_ASSOC);
        $sql->closeCursor();

        $averageTotal = 0;
        foreach ($options as $row) {
            $name  = $row['name'];
            $age  = $row['age'];
            $school  = $row['school'];
            $employment  = $row['employment'];

            $list .= '<tr>
                        <td>'. strtoupper($name) .'</td>
                        <td style="text-align:center">'. strtoupper($age) .'</td>
                        <td>'. strtoupper($school) .'</td>
                        <td>'. strtoupper($employment) .'</td>
                    </tr>';
        }

        $response = '<table border="1" width="100%" cellpadding="5" align="left">
                        <thead>
                            <tr>
                                <td colspan="4"><b>CHILDREN/DEPENDENTS</b></td>
                            </tr>
                            <tr style="text-align:center">
                                <td><b>Name</b></td>
                                <td><b>Age</b></td>
                                <td><b>School</b></td>
                                <td><b>Employment</b></td>
                            </tr>
                        </thead>
                        <tbody>
                            '.$list.'
                        </tbody>
                    </table>';

        return $response;
    }

    function generateResidenceTable($ci_report_id){
        
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
        require_once 'model/structure-type-model.php';

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
        $structureTypeModel = new StructureTypeModel($databaseModel);
    
        $list = '';
        $monthly_income_total = 0;
        $gross_monthly_sale_total = 0;
        $capital_total = 0;
        $rental_total = 0;
            
        $sql = $databaseModel->getConnection()->prepare('SELECT * FROM ci_report_residence WHERE ci_report_id = :ci_report_id ORDER BY address');
        $sql->bindValue(':ci_report_id', $ci_report_id, PDO::PARAM_INT);
        $sql->execute();
        $options = $sql->fetchAll(PDO::FETCH_ASSOC);
        $sql->closeCursor();

        $averageTotal = 0;
        foreach ($options as $row) {
            $address  = $row['address'];
            $city_id  = $row['city_id'];
            $length_stay_year  = $row['length_stay_year'];
            $length_stay_month  = $row['length_stay_month'];
            $structure_type_id  = $row['structure_type_id'];
            $remarks  = $row['real_estate_owned'];
            $tct_no  = $row['tct_no'];
            $rented_from  = $row['rented_from'];

            $ownership = $structureTypeModel->getStructureType($structure_type_id)['structure_type_name'] ?? '';

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
                        <td>'. strtoupper($contactAddress) .'</td>
                        <td style="text-align:center">'. strtoupper($lenstay) .'</td>
                        <td style="text-align:center">'. strtoupper($ownership) .'</td>
                        <td>'. strtoupper($tct_no) .'</td>
                        <td>'. strtoupper($rented_from) .'</td>
                        <td>'. strtoupper($remarks) .'</td>
                    </tr>';
        }

        $response = '<table border="1" width="100%" cellpadding="5" align="left">
                        <thead>
                            <tr style="text-align:center">
                                <td><b>Address</b></td>
                                <td><b>Length of Stay</b></td>
                                <td><b>Ownership Type</b></td>
                                <td><b>TCT No.</b></td>
                                <td><b>Landlord</b></td>
                                <td><b>Remarks</b></td>
                            </tr>
                        </thead>
                        <tbody>
                            '.$list.'
                        </tbody>
                    </table>';

        return $response;
    }

    function generatePropertyTable($ci_report_id){
        
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
        require_once 'model/structure-type-model.php';

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
        $structureTypeModel = new StructureTypeModel($databaseModel);
    
        $list = '';
        $monthly_income_total = 0;
        $gross_monthly_sale_total = 0;
        $capital_total = 0;
        $rental_total = 0;
            
        $sql = $databaseModel->getConnection()->prepare('SELECT * FROM ci_report_asset WHERE ci_report_id = :ci_report_id ORDER BY value desc');
        $sql->bindValue(':ci_report_id', $ci_report_id, PDO::PARAM_INT);
        $sql->execute();
        $options = $sql->fetchAll(PDO::FETCH_ASSOC);
        $sql->closeCursor();

        $averageTotal = 0;
        foreach ($options as $row) {
            $description  = $row['description'];
            $value  = $row['value'];
            $remarks  = $row['remarks'];

            $list .= '<tr>
                        <td>'. strtoupper($description) .'</td>
                        <td style="text-align:center">PHP '. number_format($value, 2) .'</td>
                        <td>'. strtoupper($remarks) .'</td>
                    </tr>';
        }

        $response = '<table border="1" width="100%" cellpadding="5" align="left">
                        <thead>
                            <tr style="text-align:center">
                                <td><b>Description</b></td>
                                <td><b>Valuation</b></td>
                                <td><b>Remarks</b></td>
                            </tr>
                        </thead>
                        <tbody>
                            '.$list.'
                        </tbody>
                    </table>';

        return $response;
    }

    function getAge(?string $birthday): ?int {
        if (empty($birthday)) {
            return null; // no birthday available
        }

        try {
            $birthDate = new DateTime($birthday);
            $today = new DateTime();
            return $today->diff($birthDate)->y; // age in years
        } catch (Exception $e) {
            return null; // invalid date format
        }
    }
?>