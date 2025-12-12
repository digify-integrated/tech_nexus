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
    require_once 'model/supplier-model.php';
    require_once 'model/stock-transfer-advice-model.php';

    $databaseModel = new DatabaseModel();
    $systemModel = new SystemModel();
    $userModel = new UserModel(new DatabaseModel, new SystemModel);
    $stockTransferAdviceModel = new StockTransferAdviceModel($databaseModel);
    $productModel = new ProductModel($databaseModel);
    $salesProposalModel = new SalesProposalModel($databaseModel);
    $customerModel = new CustomerModel($databaseModel);
    $productSubcategoryModel = new ProductSubcategoryModel($databaseModel);
    $supplierModel = new SupplierModel($databaseModel);

    if(isset($_GET['id'])){
        if(empty($_GET['id'])){
          header('location: netruck-parts-return.php');
          exit;
        }
        
        $stockTransferAdviceID = $_GET['id'];

        $partsReturnDetails = $stockTransferAdviceModel->getStockTransferAdvice($stockTransferAdviceID);
        $reference_number = $partsReturnDetails['reference_no'] ?? 1;
    }

    $firstTable = generateFirst($stockTransferAdviceID);
    $summaryTable = generatePrint($stockTransferAdviceID);

    $createdByDetails = $userModel->getUserByID($_SESSION['user_id']);
    $createdByName = strtoupper($createdByDetails['file_as'] ?? null);

    ob_start();

    class MYPDF extends TCPDF {
        public function Header() {
            $width = $this->getPageWidth();
            $height = $this->getPageHeight();

            $this->SetAlpha(0.3); // faint watermark
            $this->SetFont('helvetica', 'B', 80);

            $this->StartTransform();
            $this->Rotate(45, $width/2, $height/2);
            $this->Text(60, 120, 'CANCELLED'); // your watermark text
            $this->StopTransform();

            $this->SetAlpha(1); // reset transparency
        }
    }

    // Create TCPDF instance
    $pdf = new MYPDF('P', 'mm', array(330.2, 215.9), true, 'UTF-8', false);

   // Disable header and footer
   $pdf->setPrintHeader(false);
    $pdf->setPrintFooter(false);
    $pdf->SetPageOrientation('P');

    // Set PDF metadata
    $pdf->SetCreator('CGMI');
    $pdf->SetAuthor('CGMI');
    $pdf->SetTitle('STA FORM');
    $pdf->SetSubject('STA FORM');

    // Set margins and auto page break
    $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
    $pdf->SetMargins(15, 15, 15);
    $pdf->SetHeaderMargin(5);
    $pdf->SetFooterMargin(10);
    $pdf->SetAutoPageBreak(TRUE, 15);

    // Add a page
    $pdf->AddPage();

    $pdf->SetFont('times', 'B', 20);
    $pdf->Cell(68, 8, 'STOCK TRANSFER ADVICE (STA) FORM', 0, 0, 'L');
    $pdf->Cell(80, 8, '', 0, 0, 'L');
    $pdf->SetTextColor(255, 0, 0);
    $pdf->Cell(20, 8, 'No. ' . $reference_number, 0, 0, 'L');
     $pdf->SetTextColor(0, 0, 0);
    $pdf->Ln(15);
    $pdf->SetFont('times', '', 10);
    $pdf->MultiCell(0, 0, '<b>1. JOB DETAILS</b>', 0, 'J', 0, 1, '', '', true, 0, true, true, 0);
    $pdf->Ln(2);
    $pdf->writeHTML($firstTable, true, false, true, false, '');
    $pdf->Ln(-4);
    $pdf->MultiCell(0, 0, '<b>2. PARTS & ACCESSORIES TRANSFER DETAILS</b>', 0, 'J', 0, 1, '', '', true, 0, true, true, 0);
    $pdf->Ln(2);
    $pdf->writeHTML($summaryTable, true, false, true, false, '');
    $pdf->Ln(-4);
    $pdf->MultiCell(0, 0, '<b>3. APPROVALS</b>', 0, 'J', 0, 1, '', '', true, 0, true, true, 0);
    $pdf->Ln(2);
    $pdf->MultiCell(0, 0, '<b>Prepared by (Production Assistant): __________________________________________________ &nbsp; Date: ________________</b>', 0, 'L', 0, 1, '', '', true, 0, true, true, 0);
    $pdf->Ln(4);
    $pdf->MultiCell(0, 0, '<b>Approved by (General Manager): ____________________________________________________ &nbsp; Date: ________________</b>', 0, 'L', 0, 1, '', '', true, 0, true, true, 0);
    $pdf->Ln(4);
    $pdf->MultiCell(0, 0, '<b>Verified by (Production Supervisor): _________________________________________________ &nbsp; Date: ________________</b>', 0, 'L', 0, 1, '', '', true, 0, true, true, 0);

    // Output the PDF to the browser
    $pdf->Output('sta-form.pdf', 'I');
    ob_end_flush();

    function generatePrint($stockTransferAdviceID){
        require_once 'model/database-model.php';
        require_once 'model/pdc-management-model.php';
        require_once 'model/system-model.php';
        require_once 'model/user-model.php';
        require_once 'model/contractor-model.php';
        require_once 'model/employee-model.php';
        require_once 'model/work-center-model.php';
        require_once 'model/parts-model.php';
        require_once 'model/parts-transaction-model.php';
        require_once 'model/unit-model.php';
        require_once 'model/product-model.php';
        require_once 'model/product-subcategory-model.php';
        require_once 'model/stock-transfer-advice-model.php';

        $databaseModel = new DatabaseModel();
        $systemModel = new SystemModel();
        $userModel = new UserModel(new DatabaseModel, new SystemModel);
        $contractorModel = new ContractorModel($databaseModel);
        $workCenterModel = new WorkCenterModel($databaseModel);
        $partsModel = new PartsModel($databaseModel);
        $partTransactionModel = new PartsTransactionModel($databaseModel);
        $unitModel = new UnitModel($databaseModel);
        $productModel = new ProductModel($databaseModel);
        $productSubcategoryModel  = new ProductSubcategoryModel ($databaseModel);
        $stockTransferAdviceModel = new StockTransferAdviceModel($databaseModel);

        $partsReturnDetails = $stockTransferAdviceModel->getStockTransferAdvice($stockTransferAdviceID);
        $transferred_from = $partsReturnDetails['transferred_from'] ?? null;
        $transferred_to = $partsReturnDetails['transferred_to']  ?? null;
        $sta_type = $partsReturnDetails['sta_type'] ?? null;

        $productDetails1 = $productModel->getProduct($transferred_from);
        $productName1 = $productDetails1['description'] ?? '';
         $productSubategoryID1 = $productDetails1['product_subcategory_id'] ?? '';

            $productSubcategoryDetails = $productSubcategoryModel->getProductSubcategory($productSubategoryID1);
            $productSubcategoryCode = $productSubcategoryDetails['product_subcategory_code'] ?? null;

            $stockNumber1 = str_replace($productSubcategoryCode, '', $productDetails1['stock_number'] ?? '');
            $fullStockNumber1 = $productSubcategoryCode . $stockNumber1;

        $productDetails2 = $productModel->getProduct($transferred_to);
        $productName2 = $productDetails2['description'] ?? '';
        $productSubategoryID2 = $productDetails2['product_subcategory_id'] ?? '';

            $productSubcategoryDetails = $productSubcategoryModel->getProductSubcategory($productSubategoryID2);
            $productSubcategoryCode = $productSubcategoryDetails['product_subcategory_code'] ?? null;

            $stockNumber2 = str_replace($productSubcategoryCode, '', $productDetails2['stock_number'] ?? '');
            $fullStockNumber2 = $productSubcategoryCode . $stockNumber2;   

        if($sta_type == 'Transfer'){
            $sql = $databaseModel->getConnection()->prepare('SELECT * FROM stock_transfer_advice_cart
            WHERE stock_transfer_advice_id = :stockTransferAdviceID AND part_from = "From"
            ORDER BY stock_transfer_advice_id');
            $sql->bindValue(':stockTransferAdviceID', $stockTransferAdviceID, PDO::PARAM_INT);
            $sql->execute();
            $options = $sql->fetchAll(PDO::FETCH_ASSOC);
            $sql->closeCursor();

            $list='';
            $totalCost = 0;
            foreach ($options as $row) {
                $part_id = $row['part_id'];
                $quantity = $row['quantity'];
                $price = $row['price'];

                $partDetails = $partsModel->getParts($part_id);
                $description = $partDetails['description'];
                $unitSale = $partDetails['unit_sale'] ?? null;
                $bar_code = $partDetails['bar_code'];

                $unitCode = $unitModel->getUnit($unitSale);
                $short_name = $unitCode['short_name'] ?? null;
                $totalCost = $totalCost + $price;

                $list .= '<tr>
                            <td  width="70%" style="text-align:left">'. strtoupper($description) .'</td>
                            <td  width="15%" style="text-align:center">'. number_format($quantity, 2) .' '. $short_name .'</td>
                            <td  width="15%" style="text-align:center">'. number_format($price, 2).'</td>
                        </tr>';
            }

            $response = '<table border="1" width="100%" cellpadding="5" align="left">
                        <tbody>
                            <tr>
                                <td style="text-align:center"><b>Transferred From</b></td>
                                <td colspan="4"><b>' . $fullStockNumber1 . ' - '. strtoupper($productName1) .'</b></td>
                            </tr>
                            <tr>
                                <td style="text-align:center"><b>Transferred To</b></td>
                                <td colspan="4"><b>' . $fullStockNumber2 . ' - '. strtoupper($productName2) .'</b></td>
                            </tr>
                        </tbody>
                    </table>
                    <table border="1" width="100%" cellpadding="5" align="left">
                        <tbody>
                            <tr style="text-align:center">
                                <td width="70%"><b>Parts & Accessories</b></td>
                                <td width="15%"><b>Quantity</b></td>
                                <td width="15%"><b>Price</b></td>
                            </tr>
                            '.$list.'
                            <tr>
                                <td style="text-align:right" colspan="2"><b>TOTAL</b></td>
                                <td style="text-align:center"><b>'. number_format($totalCost, 2) .'</b></td>
                            </tr>
                        </tbody>
                    </table>';
        }
        else{
            $totalCost = 0;
            $sql = $databaseModel->getConnection()->prepare('SELECT * FROM stock_transfer_advice_cart
            WHERE stock_transfer_advice_id = :stockTransferAdviceID AND part_from = "From"
            ORDER BY stock_transfer_advice_id');
            $sql->bindValue(':stockTransferAdviceID', $stockTransferAdviceID, PDO::PARAM_INT);
            $sql->execute();
            $options = $sql->fetchAll(PDO::FETCH_ASSOC);
            $sql->closeCursor();

            $fromList='';
            foreach ($options as $row) {
                $part_id = $row['part_id'];
                $quantity = $row['quantity'];
                $price = $row['price'];

                $partDetails = $partsModel->getParts($part_id);
                $description = $partDetails['description'];
                $unitSale = $partDetails['unit_sale'] ?? null;
                $bar_code = $partDetails['bar_code'];

                $unitCode = $unitModel->getUnit($unitSale);
                $short_name = $unitCode['short_name'] ?? null;

                $totalCost = $totalCost + $price;

                $fromList .= '<tr>
                            <td width="20%" style="text-align:center">'. strtoupper($fullStockNumber1) .'</td>
                            <td width="20%" style="text-align:center">'. strtoupper($fullStockNumber2) .'</td>
                            <td width="30%" style="text-align:center">'. strtoupper($description) .'</td>
                            <td width="15%" style="text-align:center">'. number_format($quantity, 2) .' '. $short_name .'</td>
                            <td width="15%" style="text-align:center">'. number_format($price, 2) .'</td>
                        </tr>';
            }
            
            $sql = $databaseModel->getConnection()->prepare('SELECT * FROM stock_transfer_advice_cart
            WHERE stock_transfer_advice_id = :stockTransferAdviceID AND part_from = "To"
            ORDER BY stock_transfer_advice_id');
            $sql->bindValue(':stockTransferAdviceID', $stockTransferAdviceID, PDO::PARAM_INT);
            $sql->execute();
            $options = $sql->fetchAll(PDO::FETCH_ASSOC);
            $sql->closeCursor();
            
            $toList='';
            foreach ($options as $row) {
                $part_id = $row['part_id'];
                $quantity = $row['quantity'];
                $price = $row['price'];

                $partDetails = $partsModel->getParts($part_id);
                $description = $partDetails['description'];
                $unitSale = $partDetails['unit_sale'] ?? null;
                $bar_code = $partDetails['bar_code'];

                $unitCode = $unitModel->getUnit($unitSale);
                $short_name = $unitCode['short_name'] ?? null;   

                $totalCost = $totalCost + $price;

                $toList .= '<tr>
                            <td width="20%" style="text-align:center">'. strtoupper($fullStockNumber2) .'</td>
                            <td width="20%" style="text-align:center">'. strtoupper($fullStockNumber1) .'</td>
                            <td width="30%" style="text-align:center">'. strtoupper($description) .'</td>
                            <td width="15%" style="text-align:center">'. number_format($quantity, 2) .' '. $short_name .'</td>
                            <td width="15%" style="text-align:center">'. number_format($price, 2) .'</td>
                        </tr>';
            }

            $response = '<table border="1" width="100%" cellpadding="5" align="left">
                        <thead>
                            <tr>
                                <td style="text-align:center"><b>Transferred From</b></td>
                                <td colspan="4"><b>' . $fullStockNumber1 . ' - '. strtoupper($productName1) .'</b></td>
                            </tr>
                            <tr>
                                <td style="text-align:center"><b>Transferred To</b></td>
                                <td colspan="4"><b>' . $fullStockNumber2 . ' - '. strtoupper($productName2) .'</b></td>
                            </tr>
                        </thead>
                        <tbody>
                            <tr style="text-align:center">
                                <td width="20%"><b>Transferred From</b></td>
                                <td width="20%"><b>Transferred To</b></td>
                                <td width="30%"><b>Parts & Accessories</b></td>
                                <td width="15%"><b>Quantity</b></td>
                                <td width="15%"><b>Price</b></td>
                            </tr>
                            '.$fromList.'
                            '.$toList.'
                            <tr>
                                <td style="text-align:right" colspan="4"><b>TOTAL</b></td>
                                <td style="text-align:center"><b>'. number_format($totalCost, 2) .'</b></td>
                            </tr>
                        </tbody>
                    </table>';
        }

        

        return $response;
    }

    function generateFirst($stockTransferAdviceID){
        require_once 'model/database-model.php';
        require_once 'model/pdc-management-model.php';
        require_once 'model/system-model.php';
        require_once 'model/user-model.php';
        require_once 'model/contractor-model.php';
        require_once 'model/employee-model.php';
        require_once 'model/work-center-model.php';
        require_once 'model/parts-model.php';
        require_once 'model/parts-transaction-model.php';
        require_once 'model/back-job-monitoring-model.php';
        require_once 'model/sales-proposal-model.php';

        $databaseModel = new DatabaseModel();
        $systemModel = new SystemModel();
        $userModel = new UserModel(new DatabaseModel, new SystemModel);
        $contractorModel = new ContractorModel($databaseModel);
        $workCenterModel = new WorkCenterModel($databaseModel);
        $partsModel = new PartsModel($databaseModel);
        $backjobMonitoringModel = new BackjobMonitoringModel($databaseModel);
        $salesProposalModel = new SalesProposalModel($databaseModel);
    
        $sql = $databaseModel->getConnection()->prepare('SELECT * FROM stock_transfer_advice_job_order WHERE stock_transfer_advice_id = :stockTransferAdviceID');
        $sql->bindValue(':stockTransferAdviceID', $stockTransferAdviceID, PDO::PARAM_INT);
        $sql->execute();
        $options = $sql->fetchAll(PDO::FETCH_ASSOC);
        $sql->closeCursor();

        $list='';
        $totalCost = 0;
        foreach ($options as $row) {
            $job_order = $row['job_order_id'];
            $type = $row['type'];

            if($type == 'job order'){
                $salesProposalJobOrderDetails = $salesProposalModel->getSalesProposalJobOrder($job_order);
                $sales_proposal_id = $salesProposalJobOrderDetails['sales_proposal_id'] ?? null;
                $job_order = $salesProposalJobOrderDetails['job_order'] ?? null;
            }
            else{
                $backJobMonitoringJobOrderDetails = $backjobMonitoringModel->getBackJobMonitoringJobOrder($job_order);
                $sales_proposal_id = $backJobMonitoringJobOrderDetails['sales_proposal_id'] ?? null;
                $job_order = $backJobMonitoringJobOrderDetails['job_order'] ?? null;
            }

            $salesProposalDetails = $salesProposalModel->getSalesProposal($sales_proposal_id);
            $salesProposalNumber = $salesProposalDetails['sales_proposal_number'] ?? null;

            $list .= '<tr>
                        <td width="80%" style="text-align:left">'. strtoupper($job_order) .'</td>
                        <td width="20%" style="text-align:center">'. strtoupper($salesProposalNumber) .'</td>
                    </tr>';
        }
    
        $sql = $databaseModel->getConnection()->prepare('SELECT * FROM stock_transfer_advice_additional_job_order WHERE stock_transfer_advice_id = :stockTransferAdviceID');
        $sql->bindValue(':stockTransferAdviceID', $stockTransferAdviceID, PDO::PARAM_INT);
        $sql->execute();
        $options = $sql->fetchAll(PDO::FETCH_ASSOC);
        $sql->closeCursor();

        foreach ($options as $row) {
            $job_order = $row['additional_job_order_id'];
            $type = $row['type'];

            if($type == 'job order'){
                $salesProposalJobOrderDetails = $salesProposalModel->getSalesProposalJobOrder($job_order);
                $sales_proposal_id = $salesProposalJobOrderDetails['sales_proposal_id'] ?? null;
                $job_order = $salesProposalJobOrderDetails['job_order'] ?? null;
            }
            else{
                $backJobMonitoringJobOrderDetails = $backjobMonitoringModel->getBackJobMonitoringJobOrder($job_order);
                $sales_proposal_id = $backJobMonitoringJobOrderDetails['sales_proposal_id'] ?? null;
                $job_order = $backJobMonitoringJobOrderDetails['job_order'] ?? null;
            }

            $salesProposalDetails = $salesProposalModel->getSalesProposal($sales_proposal_id);
            $salesProposalNumber = $salesProposalDetails['sales_proposal_number'] ?? null;

            $list .= '<tr>
                        <td width="80%" style="text-align:left">'. strtoupper($job_order) .'</td>
                        <td width="20%" style="text-align:center">'. strtoupper($salesProposalNumber) .'</td>
                    </tr>';
        }

        $response = '<table border="1" width="100%" cellpadding="5" align="left">
                        <thead>
                            <tr>
                                <td width="80%" style="text-align:center"><b>Job Order</b></td>
                                <td width="20%" style="text-align:center"><b>AJO/ OS No.</b></td>
                            </tr>
                        </thead>
                        <tbody>
                            '. $list .'
                        </tbody>
                    </table>';

        return $response;
    }
?>