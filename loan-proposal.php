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

    $databaseModel = new DatabaseModel();
    $systemModel = new SystemModel();
    $userModel = new UserModel(new DatabaseModel, new SystemModel);
    $productModel = new ProductModel($databaseModel);
    $salesProposalModel = new SalesProposalModel($databaseModel);
    $customerModel = new CustomerModel($databaseModel);
    $productSubcategoryModel = new ProductSubcategoryModel($databaseModel);

    if(isset($_GET['id'])){
        if(empty($_GET['id'])){
          header('location: loan-proposal.php');
          exit;
        }

        $ciReportID = $_GET['id'];
    }

    //$summaryTable = generatePrint($ciReportID);

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
    $pdf->Cell(120, 5, 'ANTHONY GEDANG/ENVIROKONSULT', 'B', 0, 'L');
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
    $pdf->Cell(50, 5, 'Php.	5,800,800.00', 'B', 0, 'L');
    $pdf->Cell(5, 5, '', 0, 0, 'L');
    $pdf->Cell(25, 5, 'SALES EXEC:', 0, 0, 'L');
    $pdf->Cell(5, 5, '', 0, 0, 'L');
    $pdf->Cell(60, 5, 'OPERATIONS AND FINANCE HEAD', 'B', 0, 'C');
    $pdf->Ln(6);
    $pdf->Cell(30, 5, 'RATE:', 0, 0, 'L');
    $pdf->Cell(10, 5, '', 0, 0, 'L');
    $pdf->Cell(15, 5, '36.00%', 'B', 0, 'C');
    $pdf->Cell(5, 5, '', 0, 0, 'L');
    $pdf->Cell(10, 5, 'SI/DI:', 0, 0, 'C');
    $pdf->Cell(5, 5, '', 0, 0, 'L');
    $pdf->Cell(15, 5, '36.00%', 'B', 0, 'L');
    $pdf->Cell(5, 5, '', 0, 0, 'L');
    $pdf->Cell(25, 5, 'CI:', 0, 0, 'L');
    $pdf->Cell(5, 5, '', 0, 0, 'L');
    $pdf->Cell(60, 5, 'OPERATIONS AND FINANCE HEAD', 'B', 0, 'C');
    $pdf->Ln(6);
    $pdf->Cell(40, 5, 'TERM:', 0, 0, 'L');
    $pdf->Cell(15, 5, '36', 'B', 0, 'C');
    $pdf->Cell(40, 5, '',  0, 0, 'L');
    $pdf->Cell(25, 5, 'APPRAISER:', 0, 0, 'L');
    $pdf->Cell(5, 5, '', 0, 0, 'L');
    $pdf->Cell(60, 5, 'OPERATIONS AND FINANCE HEAD', 'B', 0, 'C');
    $pdf->Ln(6);
    $pdf->Cell(40, 5, 'EFFECTIVE YIELD:', 0, 0, 'L');
    $pdf->Cell(15, 5, '36', 'B', 0, 'C');
    $pdf->Cell(40, 5, '',  0, 0, 'L');
    $pdf->Cell(25, 5, 'SALES AGENT:', 0, 0, 'L');
    $pdf->Cell(5, 5, '', 0, 0, 'L');
    $pdf->Cell(60, 5, 'OPERATIONS AND FINANCE HEAD', 'B', 0, 'C');
    $pdf->Ln(6);
    $pdf->Cell(40, 5, 'PN AMOUNT:', 0, 0, 'L');
    $pdf->Cell(50, 5, '36', 'B', 0, 'C');
    $pdf->Ln(6);
    $pdf->Cell(40, 5, 'MONTHLY AMORT.:', 0, 0, 'L');
    $pdf->Cell(50, 5, '36', 'B', 0, 'C');
    $pdf->Ln(6);
    $pdf->Cell(40, 5, 'PURPOSE OF LOAN:', 0, 0, 'L');
    $pdf->MultiCell(145, 5, 'CLIENT IS BORN IN ILO ILO AND RAISED IN MANILA. GRAD OF BSBA IN MAPUA. NICKNAME - TONET', 'B', 'L');
    $pdf->Ln(2);
    $pdf->Cell(40, 5, 'CONDITION:', 0, 0, 'L');
    $pdf->MultiCell(145, 5, 'CLIENT IS BORN IN ILO ILO AND RAISED IN MANILA. GRAD OF BSBA IN MAPUA. NICKNAME - TONET', 'B', 'L');


    $pdf->Ln(5);
    $pdf->SetFont('times', 'B', 10);
    $pdf->Cell(80, 5, 'II. LOAN HISTORY', 0, 0, 'L');

    
    $pdf->Ln(10);
    $pdf->SetFont('times', 'B', 10);
    $pdf->Cell(80, 5, 'III. COLLATERAL DESCRIPTION', 0, 0, 'L');
    $pdf->Cell(5, 5, '', 1, 0, 'L');
    $pdf->Cell(3, 5, '', 0, 0, 'L');
    $pdf->Cell(40, 5, 'FINANCING', 0, 0, 'L');
    $pdf->Cell(5, 5, '', 1, 0, 'L');
    $pdf->Cell(3, 5, '', 0, 0, 'L');
    $pdf->Cell(50, 5, 'REFINANCING', 0, 0, 'L');
    $pdf->SetFont('times', '', 10);
    
    $pdf->Ln(10);
    $pdf->SetFont('times', 'B', 10);
    $pdf->Cell(80, 5, 'IV. GENERAL INFORMATION', 0, 0, 'L');
    
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

    function generatePrint($jobOrderIDs){
        
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
            
        foreach ($jobOrderIDs as $jobOrderID) {

            $salesProposalJobOrderDetails = $salesProposalModel->getSalesProposalJobOrder($jobOrderID);
           
            $jobOrder = $salesProposalJobOrderDetails['job_order'];
            $work_center_id = $salesProposalJobOrderDetails['work_center_id'];

            $workCenterDetails = $workCenterModel->getWorkCenter($work_center_id);
            $work_center_name = $workCenterDetails['work_center_name'] ?? null;

            $list .= '<tr>
                                <td>'. $jobOrder .'</td>
                                <td>'. $work_center_name .'</td>
                            </tr>';
        }

        $response = '<table border="2" width="100%" cellpadding="5" align="left">
                        <thead>
                            <tr style="text-align:center">
                                <td><b>NAME</b></td>
                                <td><b>WORK CENTER</b></td>
                            </tr>
                        </thead>
                        <tbody>
                            '.$list.'
                        </tbody>
                    </table>';

        return $response;
    }
?>