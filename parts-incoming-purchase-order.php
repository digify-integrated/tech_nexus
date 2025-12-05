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
            $title = 'NE TRUCKS BUILDERS CORP.';
        }
        else{
            $title = 'FUSO TARLAC';
        }

        $supplierName = $supplierModel->getSupplier($supplier_id)['supplier_name'] ?? '';
    }

    $summaryTable = generatePrint($part_incoming_id, 1);
    $summaryTable2 = generatePrint($part_incoming_id, 2);

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
    $pdf->SetTitle('Purchase Order List');
    $pdf->SetSubject('Purchase Order List');

    // Set margins and auto page break
    $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
    $pdf->SetMargins(15, 15, 15);
    $pdf->SetHeaderMargin(5);
    $pdf->SetFooterMargin(10);
    $pdf->SetAutoPageBreak(TRUE, 15);

    // Add a page
    $pdf->AddPage();

    $pdf->SetFont('times', '', 20);
    $pdf->MultiCell(0, 0, '<b>'. $title .'</b>', 0, 'C', 0, 1, '', '', true, 0, true, true, 0);
    $pdf->Ln(0);
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
    $pdf->writeHTML($summaryTable, true, false, true, false, '');
    $pdf->Ln(5);
    
    $pdf->SetFont('times', '', 10);
    $pdf->Cell(55, 4, strtoupper($fileAs), 'B', 0, 'C', 0, '', 1);
    $pdf->Cell(10, 4, '     ', 0, 0 , 'L', '', 1);
    $pdf->Cell(55, 4, strtoupper($request_by), 'B', 0, 'C', 0, '', 1);
    $pdf->Cell(10, 4, '     ', 0, 0 , 'L', '', 1);
    $pdf->Cell(55, 4, '', 'B', 0, 'L', 0, '', 1);
    $pdf->Ln(5);
    $pdf->Cell(55, 8, 'PREPARED BY', 0, 0, 'C');
    $pdf->Cell(10, 4, '     ', 0, 0 , 'L', '', 1);
    $pdf->Cell(55, 8, 'REQUESTED BY', 0, 0, 'C');
    $pdf->Cell(10, 4, '     ', 0, 0 , 'L', '', 1);
    $pdf->Cell(55, 8, 'APPROVED BY', 0, 0, 'C');

    $pdf->AddPage();

    $pdf->SetFont('times', '', 20);
    $pdf->MultiCell(0, 0, '<b>'. $title .'</b>', 0, 'C', 0, 1, '', '', true, 0, true, true, 0);
    $pdf->Ln(0);
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
    $pdf->writeHTML($summaryTable2, true, false, true, false, '');
    $pdf->Ln(5);
    
    $pdf->SetFont('times', '', 10);
    $pdf->Cell(55, 4, strtoupper($fileAs), 'B', 0, 'C', 0, '', 1);
    $pdf->Cell(10, 4, '     ', 0, 0 , 'L', '', 1);
    
    
    if($company_id == '3'){
       $pdf->Cell(55, 4, '', 'B', 0, 'C', 0, '', 1);
    }
    else{
       $pdf->Cell(55, 4, strtoupper($request_by), 'B', 0, 'C', 0, '', 1);
    }
    $pdf->Cell(10, 4, '     ', 0, 0 , 'L', '', 1);
    $pdf->Cell(55, 4, '', 'B', 0, 'L', 0, '', 1);
    $pdf->Ln(5);
    $pdf->Cell(55, 8, 'PREPARED BY', 0, 0, 'C');
    $pdf->Cell(10, 4, '     ', 0, 0 , 'L', '', 1);
    
    if($company_id == '3'){
        $pdf->Cell(55, 8, 'RECOMMENDED BY', 0, 0, 'C');
    }
    else{
        $pdf->Cell(55, 8, 'REQUESTED BY', 0, 0, 'C');
    }

    $pdf->Cell(10, 4, '     ', 0, 0 , 'L', '', 1);
    $pdf->Cell(55, 8, 'APPROVED BY', 0, 0, 'C');

    // Output the PDF to the browser
    $pdf->Output('job-order-list.pdf', 'I');
    ob_end_flush();

    function generatePrint($part_incoming_id, $type){
        
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
    
       $sql = $databaseModel->getConnection()->prepare('SELECT * FROM part_incoming_cart WHERE part_incoming_id = :part_incoming_id ORDER BY part_id desc');
        $sql->bindValue(':part_incoming_id', $part_incoming_id, PDO::PARAM_INT);
        $sql->execute();
        $options = $sql->fetchAll(PDO::FETCH_ASSOC);
        $sql->closeCursor();

        $list = '';
        $averageTotal = 0;
        foreach ($options as $row) {
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
            }
            else{
                if($company_id == '3'){
                    $list .= '<tr>
                        <td width="15%" style="text-align:center">'. number_format($quantity, 2) . ' ' . strtoupper($short_name) .'</td>
                        <td width="20%" style="text-align:center">'. strtoupper($part_number) .'</td>
                        <td width="35%" style="text-align:center">'. strtoupper($description) .'</td>
                        <td width="30%">'. strtoupper($remarks) .'</td>
                    </tr>';
                }
                else{
                    $list .= '<tr>
                        <td width="20%" style="text-align:center">'. number_format($quantity, 2) . ' ' . strtoupper($short_name) .'</td>
                        <td width="35%" style="text-align:center">'. strtoupper($description) .'</td>
                        <td width="45%">'. strtoupper($remarks) .'</td>
                    </tr>';
                }
                
            }
        }

        if($type == 1){
                $header = '<tr style="text-align:center">
                                <td width="15%"><b>Qty.</b></td>
                                <td width="15%"><b>Stock Qty.</b></td>
                                <td width="30%"><b>Particulars</b></td>
                                <td width="40%"><b>Remarks</b></td>
                            </tr>';
            }
            else{
                if($company_id == '3'){
                    $header = '<tr style="text-align:center">
                                <td width="15%"><b>Qty.</b></td>
                                <td width="20%"><b>Part No.</b></td>
                                <td width="35%"><b>Particulars</b></td>
                                <td width="30%"><b>Remarks</b></td>
                            </tr>';
                }
                else{
                    $header = '<tr style="text-align:center">
                                <td width="20%"><b>Qty.</b></td>
                                <td width="35%"><b>Particulars</b></td>
                                <td width="45%"><b>Remarks</b></td>
                            </tr>';
                }
            }

        $response = '<table border="1" width="100%" cellpadding="5" align="left">
                        <thead>
                            '. $header .'
                        </thead>
                        <tbody>
                            '.$list.'
                        </tbody>
                    </table>';

        return $response;
    }
?>