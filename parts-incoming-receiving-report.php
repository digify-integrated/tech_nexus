<?php
    error_reporting(E_ALL);
    ini_set('display_errors', 1);

    // Load TCPDF library
    require('assets/libs/tcpdf2/tcpdf.php');

    // Load required files
    require_once 'config/config.php';
    require_once 'session.php';
    require_once 'model/database-model.php';
    require_once 'model/system-model.php';
    require_once 'model/user-model.php';
    require_once 'model/product-model.php';
    require_once 'model/product-category-model.php';
    require_once 'model/product-subcategory-model.php';
    require_once 'model/company-model.php';
    require_once 'model/body-type-model.php';
    require_once 'model/warehouse-model.php';
    require_once 'model/unit-model.php';
    require_once 'model/color-model.php';
    require_once 'model/brand-model.php';
    require_once 'model/cabin-model.php';
    require_once 'model/make-model.php';
    require_once 'model/model-model.php';
    require_once 'model/parts-incoming-model.php';

    $databaseModel = new DatabaseModel();
    $systemModel = new SystemModel();
    $userModel = new UserModel(new DatabaseModel, new SystemModel);
    $productModel = new ProductModel($databaseModel);
    $partsIncomingModel = new PartsIncomingModel($databaseModel);
    $productCategoryModel = new ProductCategoryModel($databaseModel);
    $productSubcategoryModel = new ProductSubcategoryModel($databaseModel);
    $companyModel = new CompanyModel($databaseModel);
    $bodyTypeModel = new BodyTypeModel($databaseModel);
    $warehouseModel = new WarehouseModel($databaseModel);
    $unitModel = new UnitModel($databaseModel);
    $colorModel = new ColorModel($databaseModel);
    $brandModel = new BrandModel($databaseModel);
    $cabinModel = new CabinModel($databaseModel);
    $makeModel = new MakeModel($databaseModel);
    $modelModel = new ModelModel($databaseModel);

    if(isset($_GET['id'])){
        if(empty($_GET['id'])){
          header('location: dashboard.php');
          exit;
        }
        
        $part_incoming_id = $_GET['id'];

        $partIncomingDetails = $partsIncomingModel->getPartsIncoming($part_incoming_id);
        $rrNumber = $partIncomingDetails['rr_no'] ?? 0;

         $user = $userModel->getUserByID($user_id);
         $fileAs = $user['file_as'] ?? null;
    }

    $summaryTable = generatePrint($part_incoming_id);
    $summaryTable2 = generatePrint2($part_incoming_id);
    $summaryTable3 = generatePrint3($fileAs);

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
    $pdf->SetTitle('Receiving Report Print');
    $pdf->SetSubject('Receiving Report Print');

    // Set margins and auto page break
    $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
    $pdf->SetMargins(15, 15, 15);
    $pdf->SetHeaderMargin(5);
    $pdf->SetFooterMargin(10);
    $pdf->SetAutoPageBreak(TRUE, 15);

    // Add a page
    $pdf->SetFont('times', '', 10.5);
    $pdf->AddPage();
    $pdf->MultiCell(0, 0, '<b style="color:red">PARTS RECEIVING REPORT</b>', 0, 'C', 0, 1, '', '', true, 0, true, true, 0);

    $pdf->Ln(5);
    $pdf->writeHTML($summaryTable, true, false, true, false, '');
    $pdf->Ln(2);
    $pdf->writeHTML($summaryTable2, true, false, true, false, '');
    $pdf->Ln(2);
    $pdf->MultiCell(0, 0, '<b style="color:red">PAYMENT DETAILS</b>', 0, 'L', 0, 1, '', '', true, 0, true, true, 0);
    $pdf->Ln(2);
    $pdf->writeHTML($summaryTable3, true, false, true, false, '');

    // Output the PDF to the browser
    $pdf->Output('receiving-report.pdf', 'I');
    ob_end_flush();

    function generatePrint($part_incoming_id){
        
        require_once 'model/database-model.php';
        require_once 'model/product-model.php';
        require_once 'model/parts-incoming-model.php';
        require_once 'model/product-category-model.php';
        require_once 'model/product-subcategory-model.php';
        require_once 'model/company-model.php';
        require_once 'model/body-type-model.php';
        require_once 'model/warehouse-model.php';
        require_once 'model/unit-model.php';
        require_once 'model/color-model.php';
        require_once 'model/brand-model.php';
        require_once 'model/cabin-model.php';
        require_once 'model/make-model.php';
        require_once 'model/supplier-model.php';
        require_once 'model/model-model.php';
    
        $databaseModel = new DatabaseModel();
        $systemModel = new SystemModel();
        $userModel = new UserModel(new DatabaseModel, new SystemModel);
        $productModel = new ProductModel($databaseModel);
        $partsIncomingModel = new PartsIncomingModel($databaseModel);
        $productCategoryModel = new ProductCategoryModel($databaseModel);
        $productSubcategoryModel = new ProductSubcategoryModel($databaseModel);
        $companyModel = new CompanyModel($databaseModel);
        $bodyTypeModel = new BodyTypeModel($databaseModel);
        $warehouseModel = new WarehouseModel($databaseModel);
        $unitModel = new UnitModel($databaseModel);
        $colorModel = new ColorModel($databaseModel);
        $brandModel = new BrandModel($databaseModel);
        $cabinModel = new CabinModel($databaseModel);
        $makeModel = new MakeModel($databaseModel);
        $modelModel = new ModelModel($databaseModel);
        $supplierModel = new SupplierModel($databaseModel);

        $partIncomingDetails = $partsIncomingModel->getPartsIncoming($part_incoming_id);
        $product_id = $partIncomingDetails['product_id'];
        $supplier_id = $partIncomingDetails['supplier_id'];
        $reference_number = $partIncomingDetails['reference_number'];

        $productDetails = $productModel->getProduct($product_id);
        $stock_number = $productDetails['stock_number'];

        $supplierName = $supplierModel->getSupplier($supplier_id)['supplier_name'] ?? null;

        $rrDate = $systemModel->checkDate('summary', $partIncomingDetails['rr_date'], '', 'm/d/Y', '');

        $response = '<table border="1" width="100%" cellpadding="2" align="left">
                        <tbody>
                        <tr>
                            <td>NAME OF SUPPLIER</td>
                            <td>'. $supplierName .'</td>
                            <td>PRR DATE</td>
                            <td>'. $rrDate .'</td>
                        </tr>
                        <tr>
                            <td>STOCK NO</td>
                            <td>'. $stock_number .'</td>
                            <td>PRR NO</td>
                            <td>'. $reference_number .'</td>
                        </tr>
                    </tbody>
            </table>';

        return $response;
    }

    function generatePrint2($part_incoming_id){
        
        require_once 'model/database-model.php';
        require_once 'model/parts-model.php';
        require_once 'model/product-model.php';
        require_once 'model/product-category-model.php';
        require_once 'model/product-subcategory-model.php';
        require_once 'model/company-model.php';
        require_once 'model/body-type-model.php';
        require_once 'model/warehouse-model.php';
        require_once 'model/unit-model.php';
        require_once 'model/color-model.php';
        require_once 'model/brand-model.php';
        require_once 'model/cabin-model.php';
        require_once 'model/make-model.php';
        require_once 'model/class-model.php';
        require_once 'model/mode-of-acquisition-model.php';
    
        $databaseModel = new DatabaseModel();
        $systemModel = new SystemModel();
        $userModel = new UserModel(new DatabaseModel, new SystemModel);
        $partsModel = new PartsModel($databaseModel);
        $productModel = new ProductModel($databaseModel);
        $productCategoryModel = new ProductCategoryModel($databaseModel);
        $productSubcategoryModel = new ProductSubcategoryModel($databaseModel);
        $companyModel = new CompanyModel($databaseModel);
        $bodyTypeModel = new BodyTypeModel($databaseModel);
        $warehouseModel = new WarehouseModel($databaseModel);
        $unitModel = new UnitModel($databaseModel);
        $colorModel = new ColorModel($databaseModel);
        $brandModel = new BrandModel($databaseModel);
        $cabinModel = new CabinModel($databaseModel);
        $makeModel = new MakeModel($databaseModel);
        $modelModel = new ModelModel($databaseModel);
        $classModel = new ClassModel($databaseModel);
        $modeOfAcquisitionModel = new ModeOfAcquisitionModel($databaseModel);

        $sql = $databaseModel->getConnection()->prepare('SELECT * FROM part_incoming_cart WHERE part_incoming_id = :part_incoming_id ORDER BY part_id desc');
        $sql->bindValue(':part_incoming_id', $part_incoming_id, PDO::PARAM_INT);
        $sql->execute();
        $options = $sql->fetchAll(PDO::FETCH_ASSOC);
        $sql->closeCursor();

        $total = 0;
        foreach ($options as $row) {
            $part_id  = $row['part_id'];
            $quantity = $row['quantity'];
            $cost = $row['cost'];
            $received_quantity = $row['received_quantity'];
            $remaining_quantity = $row['remaining_quantity'];

            $partDetails = $partsModel->getParts($part_id);
            $unitSale = $partDetails['unit_sale'] ?? null;
            $description = $partDetails['description'];

            $unitCode = $unitModel->getUnit($unitSale);
            $short_name = $unitCode['short_name'] ?? null;

            $total = $total + ($cost * ($received_quantity + $remaining_quantity));

            $list .= '<tr>
                        <td width="65%">'. strtoupper($description) .'</td>
                        <td width="15%" style="text-align:center">'. number_format($quantity, 2) . ' ' . strtoupper($short_name) .'</td>
                        <td width="20%" style="text-align:center">'. number_format($cost * ($received_quantity + $remaining_quantity), 2) .' PHP</td>
                    </tr>';
        }

                   $response = '<table border="1" width="100%" cellpadding="5" align="left">
                        <thead>
                            <tr style="text-align:center">
                                <td width="65%"><b style="text-align:center">PARTICULARS</b></td>
                                <td width="15%"><b style="text-align:center">QTY</b></td>
                                <td width="20%"><b style="text-align:center">AMOUNT</b></td>
                            </tr>
                        </thead>
                        <tbody>
                            '.$list.'
                            <tr>
                                <td style="text-align:right;" colspan="2"><b>TOTAL</b></td>
                                <td style="text-align:center;"><b>'. number_format($total, 2) .' PHP</b></td>
                            </tr>
                        </tbody>
                    </table>';

        return $response;
    }

    function generatePrint3($fileAs){

        $databaseModel = new DatabaseModel();
        $systemModel = new SystemModel();
        $productModel = new ProductModel($databaseModel);
       


        $response = '<table border="0.5" width="100%" cellpadding="2" align="left">
                        <tbody>
                        <tr>
                            <td width="25%">REF NO.</td>
                            <td width="25%"></td>
                            <td width="25%">PREPARED BY: </td>
                            <td width="25%">'. strtoupper($fileAs) .'</td>
                        </tr>
                        <tr>
                            <td width="25%">REF DATE</td>
                            <td width="25%"></td>
                            <td width="25%">CHECKED BY: </td>
                            <td width="25%"></td>
                        </tr>
                        <tr>
                            <td width="25%">REF AMOUNT</td>
                            <td width="25%"></td>
                            <td width="25%">NOTED BY: </td>
                            <td width="25%"></td>
                        </tr>
                    </tbody>
                </table>';

        return $response;
    }
?>