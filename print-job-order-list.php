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

    $databaseModel = new DatabaseModel();
    $systemModel = new SystemModel();
    $userModel = new UserModel(new DatabaseModel, new SystemModel);
    $productModel = new ProductModel($databaseModel);
    $salesProposalModel = new SalesProposalModel($databaseModel);

    if(isset($_GET['id'])){
        if(empty($_GET['id'])){
          header('location: job-order-monitoring.php');
          exit;
        }
        
        $salesProposalID = $_GET['id'];

        $salesProposalDetails = $salesProposalModel->getSalesProposal($salesProposalID); 
        $salesProposalNumber = $salesProposalDetails['sales_proposal_number'] ?? null;
        $productID = $salesProposalDetails['product_id'] ?? null;

        $productDetails = $productModel->getProduct($productID);
        $description = $productDetails['description'] ?? null;

        $stockNumber = str_replace($productSubcategoryCode, '', $productDetails['stock_number'] ?? null);


    }

    $summaryTable = generatePrint($salesProposalID);

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

    $pdf->SetFont('times', '', 20);
    $pdf->MultiCell(0, 0, '<b>JOB ORDER REPORT</b>', 0, 'l', 0, 1, '', '', true, 0, true, true, 0);
    $pdf->Ln(5);
    $pdf->SetFont('times', '', 10);
    $pdf->Cell(60, 8, 'PRODUCT:', 0, 0, 'L');
    $pdf->Cell(120, 8, $description, 'B', 0, 'L');
    $pdf->Ln(8);
    $pdf->Cell(60, 8, 'MANUFACTURING ORDER:', 0, 0, 'L');
    $pdf->Cell(120, 8, $salesProposalNumber, 'B', 0, 'L');
    $pdf->Ln(8);
    $pdf->Cell(60, 8, 'STOCK NUMBER:', 0, 0, 'L');
    $pdf->Cell(120, 8, $stockNumber, 'B', 0, 'L');
    $pdf->Ln(12);
    $pdf->writeHTML($summaryTable, true, false, true, false, '');

    // Output the PDF to the browser
    $pdf->Output('job-order-list.pdf', 'I');
    ob_end_flush();

    function generatePrint($salesProposalID){
        
        require_once 'model/database-model.php';
        require_once 'model/pdc-management-model.php';
        require_once 'model/system-model.php';
        require_once 'model/user-model.php';
        require_once 'model/contractor-model.php';
        require_once 'model/employee-model.php';
        require_once 'model/work-center-model.php';

        $databaseModel = new DatabaseModel();
        $systemModel = new SystemModel();
        $userModel = new UserModel(new DatabaseModel, new SystemModel);
        $contractorModel = new ContractorModel($databaseModel);
        $workCenterModel = new WorkCenterModel($databaseModel);
        
        
        $list = '';
        $sql = $databaseModel->getConnection()->prepare('CALL generateSalesProposalJobOrderTable(:salesProposalID)');
        $sql->bindValue(':salesProposalID', $salesProposalID, PDO::PARAM_INT);
        $sql->execute();
        $options = $sql->fetchAll(PDO::FETCH_ASSOC);
        $sql->closeCursor();
            
        foreach ($options as $row) {
           
            $jobOrder = $row['job_order'];
            $work_center_id = $row['work_center_id'];

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