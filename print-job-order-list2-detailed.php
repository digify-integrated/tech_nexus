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
    $productSubcategoryModel = new ProductSubcategoryModel($databaseModel);

    if(isset($_GET['id'])){
        if(empty($_GET['id'])){
          header('location: job-order-monitoring.php');
          exit;
        }

        $jobOrderIDs = explode(',', $_GET['id']);
        
        $product_id = $_GET['product_id'];

        $productDetails = $productModel->getProduct($product_id);
        $description = $productDetails['description'] ?? null;
        $productSubategoryID = $productDetails['product_subcategory_id'] ?? '';

        $productSubcategoryDetails = $productSubcategoryModel->getProductSubcategory($productSubategoryID);
        $productSubcategoryCode = $productSubcategoryDetails['product_subcategory_code'] ?? null;

        $stockNumber = str_replace($productSubcategoryCode, '', $productDetails['stock_number'] ?? '');
        $fullStockNumber = $productSubcategoryCode . $stockNumber;
    }

    $summaryTable = generatePrint($jobOrderIDs);

    ob_start();

    // Create TCPDF instance
    $pdf = new TCPDF('L', 'mm', array(330.2, 215.9), true, 'UTF-8', false);

   // Disable header and footer
    $pdf->setPrintHeader(false);
    $pdf->setPrintFooter(false);
    $pdf->SetPageOrientation('L');

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

    $pdf->SetFont('times', '', 20);
    $pdf->MultiCell(0, 0, '<b>INTERNAL REPAIR JOB ORDER REPORT</b>', 0, 'l', 0, 1, '', '', true, 0, true, true, 0);
    $pdf->Ln(5);
    $pdf->SetFont('times', '', 10);
    $pdf->Cell(60, 8, 'PRODUCT:', 0, 0, 'L');
    $pdf->Cell(200, 8, $description, 'B', 0, 'L');
    $pdf->Ln(8);
    $pdf->Cell(60, 8, 'STOCK NUMBER:', 0, 0, 'L');
    $pdf->Cell(200, 8, $fullStockNumber, 'B', 0, 'L');
    $pdf->Ln(12);
    $pdf->writeHTML($summaryTable, true, false, true, false, '');
    $pdf->Ln(5);

    $pdf->SetFont('times', '', size: 8);
    $pdf->Cell(20, 4, 'RATING:', '', 0, 'L', 0, '', 1);
    $pdf->Cell(90, 4, '1 EXCELLENT - QUALITY OF WORK IS EXCELLENT', '', 0, 'L', 0, '', 1);
    $pdf->Ln(5);
    $pdf->Cell(20, 4, '', '', 0, 'L', 0, '', 1);
    $pdf->Cell(90, 4, '2 GOOD - QUALITY OF WORK CAN BE IMPROVED', '', 0, 'L', 0, '', 1);
    $pdf->Ln(5);
    $pdf->Cell(20, 4, '', '', 0, 'L', 0, '', 1);
    $pdf->Cell(90, 4, '3 NEEDS IMPROVEMENT - RETURN TO CONTRACTOR', '', 0, 'L', 0, '', 1);
    $pdf->Ln(20);
    
    $pdf->SetFont('times', '', 10);
    $pdf->Cell(90, 4, '', 'B', 0, 'L', 0, '', 1);
    $pdf->Cell(10, 4, '     ', 0, 0 , 'L', '', 1);
    $pdf->Cell(90, 4, '', 'B', 0, 'L', 0, '', 1);
    $pdf->Cell(10, 4, '     ', 0, 0 , 'L', '', 1);
    $pdf->Cell(90, 4, '', 'B', 0, 'L', 0, '', 1);
    $pdf->Ln(5);
    $pdf->Cell(90, 8, 'PREPARED BY', 0, 0, 'C');
    $pdf->Cell(10, 4, '     ', 0, 0 , 'L', '', 1);
    $pdf->Cell(90, 8, 'CONTRACTOR', 0, 0, 'C');
    $pdf->Cell(10, 4, '     ', 0, 0 , 'L', '', 1);
    $pdf->Cell(90, 8, 'CHECKED BY', 0, 0, 'C');


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
        require_once 'model/back-job-monitoring-model.php';

        $databaseModel = new DatabaseModel();
        $systemModel = new SystemModel();
        $userModel = new UserModel(new DatabaseModel, new SystemModel);
        $contractorModel = new ContractorModel($databaseModel);
        $workCenterModel = new WorkCenterModel($databaseModel);
        $backJobMonitoringModel = new BackJobMonitoringModel($databaseModel);
    
        $list = '';
            
        foreach ($jobOrderIDs as $jobOrderID) {

            $salesProposalJobOrderDetails = $backJobMonitoringModel->getBackJobMonitoringJobOrder($jobOrderID);
           
            $jobOrder = $salesProposalJobOrderDetails['job_order'];
            $work_center_id = $salesProposalJobOrderDetails['work_center_id'];

            $workCenterDetails = $workCenterModel->getWorkCenter($work_center_id);
            $work_center_name = $workCenterDetails['work_center_name'] ?? null;

            $contractorDetails = $contractorModel->getContractor($contractor_id);
            $contractor_name = $contractorDetails['contractor_name'] ?? null;

            $planned_start_date = $systemModel->checkDate('summary', $salesProposalJobOrderDetails['planned_start_date'], '', 'm/d/Y', '');
            $planned_finish_date = $systemModel->checkDate('summary', $salesProposalJobOrderDetails['planned_finish_date'], '', 'm/d/Y', '');
            $date_started = $systemModel->checkDate('summary', $salesProposalJobOrderDetails['date_started'], '', 'm/d/Y', '');
            $completion_date = $systemModel->checkDate('summary', $salesProposalJobOrderDetails['completion_date'], '', 'm/d/Y', '');

            $list .= '<tr>
                                <td>'. $jobOrder .'</td>
                                <td>'. $work_center_name .'</td>
                                <td>'. $contractor_name .'</td>
                                <td>'. $planned_start_date .'</td>
                                <td>'. $planned_finish_date .'</td>
                                <td>'. $date_started .'</td>
                                <td>'. $completion_date .'</td>
                                <td></td>
                            </tr>';
        }

        $response = '<table border="2" width="100%" cellpadding="5" align="left">
                        <thead>
                            <tr style="text-align:center">
                                <td><b>NAME</b></td>
                                <td><b>WORK CENTER</b></td>
                                <td><b>CONTRACTOR</b></td>
                                <td><b>PLANNED START DATE</b></td>
                                <td><b>PLANNED FINISH DATE</b></td>
                                <td><b>DATE STARTED</b></td>
                                <td><b>COMPLETION DATE</b></td>
                                <td><b>RATING</b></td>
                            </tr>
                        </thead>
                        <tbody>
                            '.$list.'
                        </tbody>
                    </table>';

        return $response;
    }
?>