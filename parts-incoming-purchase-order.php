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
    require_once 'model/parts-incoming-model.php';

    $databaseModel = new DatabaseModel();
    $systemModel = new SystemModel();
    $userModel = new UserModel(new DatabaseModel, new SystemModel);
    $partsIncomingModel = new PartsIncomingModel($databaseModel);
    $productModel = new ProductModel($databaseModel);
    $salesProposalModel = new SalesProposalModel($databaseModel);
    $customerModel = new CustomerModel($databaseModel);
    $productSubcategoryModel = new ProductSubcategoryModel($databaseModel);
    $supplierModel = new SupplierModel($databaseModel);

    if(isset($_GET['id'])){
        if(empty($_GET['id'])){
          header('location: netruck-parts-incoming.php');
          exit;
        }
        
        $part_incoming_id = $_GET['id'];

        $partsIncomingDetails = $partsIncomingModel->getPartsIncoming($part_incoming_id);

        $reference_number = $partsIncomingDetails['reference_number'] ?? '';
        $request_by = $partsIncomingDetails['request_by'] ?? '';
        $supplier_id = $partsIncomingDetails['supplier_id'] ?? '';
        $company_id = $partsIncomingDetails['company_id'] ?? '';
        $product_id = $partsIncomingDetails['product_id'] ?? '';
        $customer_ref_id = $partsIncomingDetails['customer_ref_id'] ?? '';

        $productDetails = $productModel->getProduct($product_id);
        $stock_number = $productDetails['stock_number'] ?? '';

        $customerDetails = $customerModel->getPersonalInformation($customer_ref_id);
        $last_name = $customerDetails['last_name'] ?? '';

        $user = $userModel->getUserByID($user_id);
        $fileAs = $user['file_as'] ?? null;

        if($company_id == '1'){
            $title = 'CHRISTIAN GENERAL MOTORS INC.';
        }
        else if($company_id == '2'){
            $title = 'CHRISTIAN GENERAL MOTORS INC.';
        }
        else if($company_id == '15'){
            $title = 'OMODA JAECOO';
        }
        else{
            $title = 'FUSO TARLAC';
        }

        $supplierName = $supplierModel->getSupplier($supplier_id)['supplier_name'] ?? '';
    }

    // Fetch grouped tables based on 25 max rows limit
    $tablesCopy1 = generatePrintTables($part_incoming_id, 1, $partsModel, $unitModel, $partsIncomingModel, $databaseModel);
    $tablesCopy2 = generatePrintTables($part_incoming_id, 2, $partsModel, $unitModel, $partsIncomingModel, $databaseModel);

    ob_start();

    // Create TCPDF instance
    $pdf = new TCPDF('P', 'mm', array(330.2, 215.9), true, 'UTF-8', false);

    $pdf->setPrintHeader(false);
    $pdf->setPrintFooter(false);
    $pdf->SetPageOrientation('P');

    $pdf->SetCreator('CGMI');
    $pdf->SetAuthor('CGMI');
    $pdf->SetTitle('Purchase Order List');
    $pdf->SetSubject('Purchase Order List');

    $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
    $pdf->SetMargins(15, 15, 15);
    $pdf->SetHeaderMargin(5);
    $pdf->SetFooterMargin(15);
    $pdf->SetAutoPageBreak(TRUE, 15);

    // ==================== COPY 1: ACCOUNTING COPY ====================
    $totalChunks1 = count($tablesCopy1);
    foreach ($tablesCopy1 as $index => $tableHtml) {
        $pdf->AddPage();
        
        // Header on every page for this section
        $pdf->SetFont('times', '', 20);
        $pdf->MultiCell(0, 0, '<b>'. $title .'</b>', 0, 'C', 0, 1, '', '', true, 0, true, true, 0);
        $pdf->SetFont('times', '', 15);
        $pdf->MultiCell(0, 0, '<b><u>PURCHASE ORDER (ACCOUNTING COPY)</u></b>', 0, 'C', 0, 1, '', '', true, 0, true, true, 0);
        $pdf->Ln(8);
        
        $pdf->SetFont('times', '', 10);
        $pdf->Cell(45, 8, 'REFERENCE NUMBER:', 0, 0, 'L');
        $pdf->Cell(60, 8, strtoupper($reference_number), 'B', 0, 'L');
        $pdf->Cell(5, 8, '', 0, 0, 'L');
        $pdf->Cell(15, 8, 'DATE:', 0, 0, 'L');
        $pdf->Cell(8, 8, '', 0, 0, 'L');
        $pdf->Cell(50, 8, strtoupper(date('F d, Y')), 'B', 0, 'L');
        $pdf->Ln(10);
        $pdf->Cell(25, 8, 'SUPPLIER:', 0, 0, 'L');
        $pdf->Cell(80, 8, strtoupper($supplierName), 'B', 0, 'L');
        $pdf->Cell(5, 8, '', 0, 0, 'L');
        $pdf->Cell(15, 8, 'STOCK NO.:', 0, 0, 'L');
        $pdf->Cell(8, 8, '', 0, 0, 'L');
        $pdf->Cell(50, 8, strtoupper($stock_number) . ' - ' . strtoupper($last_name), 'B', 0, 'L');
        $pdf->Ln(15);

        // Write chunk table
        $pdf->writeHTML($tableHtml, true, false, true, false, '');
        $pdf->Ln(5);

        // Append Signatories ONLY on the last chunk page
        if ($index === $totalChunks1 - 1) {
            $signatories1 = '
            <table border="0" cellpadding="2" width="100%" style="page-break-inside:avoid;" nobr="true">
                <tr>
                    <td width="30%" style="border-bottom: 1px solid black; text-align:center;"><b>'.strtoupper($fileAs).'</b></td>
                    <td width="5%"></td>
                    <td width="30%" style="border-bottom: 1px solid black; text-align:center;"><b>'.strtoupper($request_by).'</b></td>
                    <td width="5%"></td>
                    <td width="30%" style="border-bottom: 1px solid black; text-align:center;"></td>
                </tr>
                <tr>
                    <td style="text-align:center; font-size:10px;">PREPARED BY</td>
                    <td></td>
                    <td style="text-align:center; font-size:10px;">REQUESTED BY</td>
                    <td></td>
                    <td style="text-align:center; font-size:10px;">APPROVED BY</td>
                </tr>
            </table>';
            $pdf->writeHTML($signatories1, true, false, true, false, '');
        }
    }

    // ==================== COPY 2: SUPPLIER COPY ====================
    $totalChunks2 = count($tablesCopy2);
    foreach ($tablesCopy2 as $index => $tableHtml) {
        $pdf->AddPage();
        
        $pdf->SetFont('times', '', 20);
        $pdf->MultiCell(0, 0, '<b>'. $title .'</b>', 0, 'C', 0, 1, '', '', true, 0, true, true, 0);
        $pdf->SetFont('times', '', 15);
        $pdf->MultiCell(0, 0, "<b><u>PURCHASE ORDER (SUPPLIER'S COPY)</u></b>", 0, 'C', 0, 1, '', '', true, 0, true, true, 0);
        $pdf->Ln(8);
        
        $pdf->SetFont('times', '', 10);
        $pdf->Cell(45, 8, 'REFERENCE NUMBER:', 0, 0, 'L');
        $pdf->Cell(60, 8, strtoupper($reference_number), 'B', 0, 'L');
        $pdf->Cell(5, 8, '', 0, 0, 'L');
        $pdf->Cell(15, 8, 'DATE:', 0, 0, 'L');
        $pdf->Cell(8, 8, '', 0, 0, 'L');
        $pdf->Cell(50, 8, strtoupper(date('F d, Y')), 'B', 0, 'L');
        $pdf->Ln(10);
        $pdf->Cell(25, 8, 'SUPPLIER:', 0, 0, 'L');
        $pdf->Cell(80, 8, strtoupper($supplierName), 'B', 0, 'L');
        $pdf->Cell(5, 8, '', 0, 0, 'L');
        $pdf->Cell(15, 8, 'STOCK NO.:', 0, 0, 'L');
        $pdf->Cell(8, 8, '', 0, 0, 'L');
        $pdf->Cell(50, 8, strtoupper($stock_number) . ' - ' . strtoupper($last_name), 'B', 0, 'L');
        $pdf->Ln(15);

        // Write chunk table
        $pdf->writeHTML($tableHtml, true, false, true, false, '');
        $pdf->Ln(5);

        // Append Signatories ONLY on the last chunk page
        if ($index === $totalChunks2 - 1) {
            $recommended_by_val = ($company_id != '3') ? '' : 'PAOLO EDUARDO BAGUISA';
            $approved_by_val = ($company_id == '3') ? 'KRISTINE GRACE BAGUISA' : '';
            $middle_label = ($company_id == '3') ? 'RECOMMENDED BY' : 'REQUESTED BY';

            $signatories2 = '
            <table border="0" cellpadding="2" width="100%" style="page-break-inside:avoid;" nobr="true">
                <tr>
                    <td width="30%" style="border-bottom: 1px solid black; text-align:center;"><b>'.strtoupper($fileAs).'</b></td>
                    <td width="5%"></td>
                    <td width="30%" style="border-bottom: 1px solid black; text-align:center;"><b>'.$recommended_by_val.'</b></td>
                    <td width="5%"></td>
                    <td width="30%" style="border-bottom: 1px solid black; text-align:center;"><b>'.$approved_by_val.'</b></td>
                </tr>
                <tr>
                    <td style="text-align:center; font-size:10px;">PREPARED BY</td>
                    <td></td>
                    <td style="text-align:center; font-size:10px;">'.$middle_label.'</td>
                    <td></td>
                    <td style="text-align:center; font-size:10px;">APPROVED BY</td>
                </tr>
            </table>';
            $pdf->writeHTML($signatories2, true, false, true, false, '');
        }
    }

    $pdf->Output('job-order-list.pdf', 'I');
    ob_end_flush();

    // Custom chunk processing function replacing data rendering logic
    function generatePrintTables($part_incoming_id, $type, $partsModel, $unitModel, $partsIncomingModel, $databaseModel){
         require_once 'model/database-model.php';
        require_once 'model/pdc-management-model.php';
        require_once 'model/system-model.php';
        require_once 'model/user-model.php';
        require_once 'model/contractor-model.php';
        require_once 'model/employee-model.php';
        require_once 'model/work-center-model.php';
        require_once 'model/parts-model.php';
        require_once 'model/unit-model.php';
        require_once 'model/parts-incoming-model.php';

        $databaseModel = new DatabaseModel();
        $systemModel = new SystemModel();
        $userModel = new UserModel(new DatabaseModel, new SystemModel);
        $contractorModel = new ContractorModel($databaseModel);
        $workCenterModel = new WorkCenterModel($databaseModel);
        $partsModel = new PartsModel($databaseModel);
        $unitModel = new UnitModel($databaseModel);
        $partsIncomingModel = new PartsIncomingModel($databaseModel);
        
        $partsIncomingDetails = $partsIncomingModel->getPartsIncoming($part_incoming_id);
        $company_id = $partsIncomingDetails['company_id'] ?? '';
    
        if($company_id == 1){
            $sql = $databaseModel->getConnection()->prepare('SELECT part_incoming_cart.part_id AS part_id, part_incoming_cart.quantity AS quantity, part_incoming_cart.remarks AS remarks FROM part_incoming_cart LEFT JOIN part ON part_incoming_cart.part_id = part.part_id WHERE part_incoming_id = :part_incoming_id ORDER BY description');
        }
        else{
            $sql = $databaseModel->getConnection()->prepare('SELECT * FROM part_incoming_cart WHERE part_incoming_id = :part_incoming_id ORDER BY part_id desc');
        }

        $sql->bindValue(':part_incoming_id', $part_incoming_id, PDO::PARAM_INT);
        $sql->execute();
        $options = $sql->fetchAll(PDO::FETCH_ASSOC);
        $sql->closeCursor();

        // Split data into structural blocks of 25 items maximum per page
        $chunks = array_chunk($options, 25);
        $tableHTMLs = [];

        // Determine header string 
        if($type == 1){
            $header = '<tr style="text-align:center; background-color:#f2f2f2;">
                            <td width="15%"><b>Qty.</b></td>
                            <td width="15%"><b>Stock Qty.</b></td>
                            <td width="30%"><b>Particulars</b></td>
                            <td width="40%"><b>Remarks</b></td>
                        </tr>';
        } else {
            if($company_id == '3'){
                $header = '<tr style="text-align:center; background-color:#f2f2f2;">
                                <td width="15%"><b>Qty.</b></td>
                                <td width="20%"><b>Part No.</b></td>
                                <td width="35%"><b>Particulars</b></td>
                                <td width="30%"><b>Remarks</b></td>
                            </tr>';
            } else {
                $header = '<tr style="text-align:center; background-color:#f2f2f2;">
                                <td width="20%"><b>Qty.</b></td>
                                <td width="35%"><b>Particulars</b></td>
                                <td width="45%"><b>Remarks</b></td>
                            </tr>';
            }
        }

        foreach ($chunks as $chunk) {
            $list = '';
            foreach ($chunk as $row) {
                $part_id  = $row['part_id'];
                $quantity = $row['quantity'];
                $remarks = $row['remarks'];

                $partDetails = $partsModel->getParts($part_id);
                $unitSale = $partDetails['unit_sale'] ?? null;
                $part_number = $partDetails['part_number'] ?? '';
                $description = $partDetails['description'];
                $part_quantity = $partDetails['quantity'];

                $unitCode = $unitModel->getUnit($unitSale);
                $short_name = $unitCode['short_name'] ?? null;

                if($type == 1){
                    $list .= '<tr>
                            <td width="15%" style="text-align:center">'. number_format($quantity, 2) . ' ' . strtoupper($short_name) .'</td>
                            <td width="15%" style="text-align:center">'. number_format($part_quantity, 2) . ' ' . strtoupper($short_name) .'</td>
                            <td width="30%" style="text-align:center">'. strtoupper($description) .'</td>
                            <td width="40%">'. strtoupper($remarks) .'</td>
                        </tr>';
                } else {
                    if($company_id == '3'){
                        $list .= '<tr>
                            <td width="15%" style="text-align:center">'. number_format($quantity, 2) . ' ' . strtoupper($short_name) .'</td>
                            <td width="20%" style="text-align:center">'. strtoupper($part_number) .'</td>
                            <td width="35%" style="text-align:center">'. strtoupper($description) .'</td>
                            <td width="30%">'. strtoupper($remarks) .'</td>
                        </tr>';
                    } else {
                        $list .= '<tr>
                            <td width="20%" style="text-align:center">'. number_format($quantity, 2) . ' ' . strtoupper($short_name) .'</td>
                            <td width="35%" style="text-align:center">'. strtoupper($description) .'</td>
                            <td width="45%">'. strtoupper($remarks) .'</td>
                        </tr>';
                    }
                }
            }

            $tableHTMLs[] = '<table border="1" width="100%" cellpadding="5" align="left">
                                <thead>'.$header.'</thead>
                                <tbody>'.$list.'</tbody>
                            </table>';
        }

        return $tableHTMLs;
    }
?>