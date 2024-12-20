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

    $databaseModel = new DatabaseModel();
    $systemModel = new SystemModel();
    $userModel = new UserModel(new DatabaseModel, new SystemModel);
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

    if(isset($_GET['id'])){
        if(empty($_GET['id'])){
          header('location: dashboard.php');
          exit;
        }
        
        $productID = $_GET['id'];

        $productDetails = $productModel->getProduct($productID);
        $rrNumber = $productDetails['rr_no'];
    }

    $summaryTable = generatePrint($productID);
    $summaryTable2 = generatePrint2($productID);
    $summaryTable3 = generatePrint3($productID);

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
    $pdf->MultiCell(0, 0, '<b style="color:red">RECEIVING REPORT</b>', 0, 'C', 0, 1, '', '', true, 0, true, true, 0);
    $pdf->Ln(2);
    $pdf->MultiCell(0, 0, '<b>RR NO &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b>' . ' <b style="color:red">'. $rrNumber .'</b>', 0, 'R', 0, 1, '', '', true, 0, true, true, 0);

    $pdf->Ln(2);
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

    function generatePrint($productID){
        
        require_once 'model/database-model.php';
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
        require_once 'model/supplier-model.php';
        require_once 'model/model-model.php';
    
        $databaseModel = new DatabaseModel();
        $systemModel = new SystemModel();
        $userModel = new UserModel(new DatabaseModel, new SystemModel);
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
        $supplierModel = new SupplierModel($databaseModel);

        $productDetails = $productModel->getProduct($productID);
        $stock_number = $productDetails['stock_number'];
        $supplier_id = $productDetails['supplier_id'];
        $ref_no = $productDetails['ref_no'];

        $supplierName = $supplierModel->getSupplier($supplier_id)['supplier_name'] ?? null;

        $rrDate = $systemModel->checkDate('summary', $productDetails['rr_date'], '', 'm/d/Y', '');

        $response = '<table border="0.5" width="100%" cellpadding="2" align="left">
                        <tbody>
                        <tr>
                            <td>NAME OF SUPPLIER</td>
                            <td>'. $supplierName .'</td>
                            <td>RR DATE</td>
                            <td>'. $rrDate .'</td>
                        </tr>
                        <tr>
                            <td>STOCK NO</td>
                            <td>'. $stock_number .'</td>
                            <td>REF NO</td>
                            <td>'. $ref_no .'</td>
                        </tr>
                    </tbody>
            </table>';

        return $response;
    }

    function generatePrint2($productID){
        
        require_once 'model/database-model.php';
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

        $productDetails = $productModel->getProduct($productID);
        $brand_id = $productDetails['brand_id'];
        $cabin_id = $productDetails['cabin_id'];
        $model_id = $productDetails['model_id'];
        $make_id = $productDetails['make_id'];
        $body_type_id = $productDetails['body_type_id'];
        $length = $productDetails['length'];
        $length_unit = $productDetails['length_unit'];
        $class_id = $productDetails['class_id'];
        $mode_of_acquisition_id = $productDetails['mode_of_acquisition_id'];
        $engine_number = $productDetails['engine_number'];
        $chassis_number = $productDetails['chassis_number'];
        $plate_number = $productDetails['plate_number'];
        $color_id = $productDetails['color_id'];
        $year_model = $productDetails['year_model'];
        $description = $productDetails['description'];
        $total_landed_cost = $productDetails['total_landed_cost'];
        $arrival_date = $systemModel->checkDate('summary', $productDetails['arrival_date'], '', 'm/d/Y', '');
        $checklist_date = $systemModel->checkDate('summary', $productDetails['checklist_date'], '', 'm/d/Y', '');

        $brand_name = $brandModel->getBrand($brand_id)['brand_name'] ?? null;
        $cabin_name = $cabinModel->getCabin($cabin_id)['cabin_name'] ?? null;
        $model_name = $modelModel->getModel($model_id)['model_name'] ?? null;
        $make_name = $makeModel->getMake($make_id)['make_name'] ?? null;
        $body_type_name = $bodyTypeModel->getBodyType($body_type_id)['body_type_name'] ?? null;
        $unit_name = $unitModel->getUnit($length_unit)['short_name'] ?? null;
        $class_name = $classModel->getClass($class_id)['class_name'] ?? null;
        $color_name = $colorModel->getColor($color_id)['color_name'] ?? null;
        $mode_of_acquisition_name = $modeOfAcquisitionModel->getModeOfAcquisition($mode_of_acquisition_id)['mode_of_acquisition_name'] ?? null;

        $response = '<table border="0.5" width="100%" cellpadding="2" align="left">
                        <tbody>
                        <tr>
                            <td width="80%"><b style="text-align:center">PARTICULARS</b></td>
                            <td width="20%"><b style="text-align:center">AMOUNT</b></td>
                        </tr>
                        <tr>
                            <td><br/><br/>
                                BRAND: '. $brand_name .' <br/>
                                CABIN: '. $cabin_name.' <br/>
                                MODEL: '. $model_name.' <br/>
                                MAKE: '. $make_name.' <br/>
                                BODY: '. $body_type_name.' <br/>
                                BODY LENGTH: '. $length .'  '. $unit_name .'<br/>
                                CLASS: '. $class_name .'<br/>
                                MODE OF ACQ: '. $mode_of_acquisition_name .'<br/>
                                ENGINE NO: '. $engine_number .'<br/>
                                CHASSIS NO: '. $chassis_number .'<br/>
                                PLATE NO: '. $plate_number .'<br/>
                                COLOR: '. $color_name .'<br/>
                                YEAR MODEL: '. $year_model .'<br/>
                                ARRIVAL DATE: '. $arrival_date .'<br/>
                                CHECKLIST DATE: '. $checklist_date .'<br/>
                                PARTICULARS: '. $description .'<br/>
                            </td>
                            <td style="text-align:center;">'. number_format($total_landed_cost, 2) .'</td>
                        </tr>
                        <tr>
                            <td><b>TOTAL</b></td>
                            <td style="text-align:center;"><b>'. number_format($total_landed_cost, 2) .'</b></td>
                        </tr>
                    </tbody>
                </table>';

        return $response;
    }

    function generatePrint3($productID){

        $databaseModel = new DatabaseModel();
        $systemModel = new SystemModel();
        $productModel = new ProductModel($databaseModel);

        $productDetails = $productModel->getProduct($productID);
        $payment_ref_no = $productDetails['payment_ref_no'] ?? '';
        $payment_ref_amount = $productDetails['payment_ref_amount'] ?? 0;
        $payment_ref_date = $systemModel->checkDate('summary', $productDetails['payment_ref_date'], '', 'm/d/Y', '');

        $response = '<table border="0.5" width="100%" cellpadding="2" align="left">
                        <tbody>
                        <tr>
                            <td width="20%">REF NO.</td>
                            <td width="40%">'. $payment_ref_no .'</td>
                            <td width="20%">PREPARED BY: </td>
                            <td width="20%"></td>
                        </tr>
                        <tr>
                            <td width="20%">REF DATE</td>
                            <td width="40%">'. $payment_ref_date .'</td>
                            <td width="20%">CHECKED BY: </td>
                            <td width="20%"></td>
                        </tr>
                        <tr>
                            <td width="20%">REF AMOUNT</td>
                            <td width="40%">'. number_format($payment_ref_amount, 2) .'</td>
                            <td width="20%">NOTED BY: </td>
                            <td width="20%"></td>
                        </tr>
                    </tbody>
                </table>';

        return $response;
    }
?>