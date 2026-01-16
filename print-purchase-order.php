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
    require_once 'model/company-model.php';
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
    require_once 'model/purchase-order-model.php';

    $databaseModel = new DatabaseModel();
    $systemModel = new SystemModel();
    $userModel = new UserModel(new DatabaseModel, new SystemModel);
    $partsIncomingModel = new PartsIncomingModel($databaseModel);
    $productModel = new ProductModel($databaseModel);
    $companyModel = new CompanyModel($databaseModel);
    $salesProposalModel = new SalesProposalModel($databaseModel);
    $customerModel = new CustomerModel($databaseModel);
    $productSubcategoryModel = new ProductSubcategoryModel($databaseModel);
    $supplierModel = new SupplierModel($databaseModel);
    $purchaseOrderModel = new PurchaseOrderModel($databaseModel);

    if(isset($_GET['id'])){
        if(empty($_GET['id'])){
          header('location: unit-purchase-order.php');
          exit;
        }
        
        $purchase_order_id = $_GET['id'];

        $purchaseOrderDetails = $purchaseOrderModel->getPurchaseOrder($purchase_order_id);
        $company_id = $purchaseOrderDetails['company_id'] ?? '';
        $supplier_id = $purchaseOrderDetails['supplier_id'] ?? '';
        $reference_no = $purchaseOrderDetails['reference_no'] ?? '';

        $companyDetails = $companyModel->getCompany($company_id);
        
        $companyName = $companyDetails['company_name'] ?? '';
        $user = $userModel->getUserByID($user_id);
         $fileAs = $user['file_as'] ?? null;

        $supplierName = $supplierModel->getSupplier($supplier_id)['supplier_name'] ?? '';
    }

    $summaryTable = generatePrint($purchase_order_id, 1);
    $summaryTable2 = generatePrint($purchase_order_id, 2);

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
    $pdf->MultiCell(0, 0, '<b>'. $companyName .'</b>', 0, 'C', 0, 1, '', '', true, 0, true, true, 0);
    $pdf->Ln(0);
    $pdf->SetFont('times', '', 15);
    $pdf->MultiCell(0, 0, '<b><u>PURCHASE ORDER (ACCOUNTING COPY)</u></b>', 0, 'C', 0, 1, '', '', true, 0, true, true, 0);
    $pdf->Ln(8);
    $pdf->SetFont('times', '', 10);
    $pdf->Cell(45, 8, 'REFERENCE NUMBER:', 0, 0, 'L');
    $pdf->Cell(60, 8, strtoupper($reference_no), 'B', 0, 'L');
    $pdf->Cell(5, 8, '', 0, 0, 'L');
    $pdf->Cell(15, 8, 'DATE:', 0, 0, 'L');
    $pdf->Cell(8, 8, '', 0, 0, 'L');
    $pdf->Cell(50, 8, strtoupper(date('F d, Y')), 'B', 0, 'L');
    $pdf->Ln(10);
    $pdf->Cell(25, 8, 'SUPPLIER:', 0, 0, 'L');
    $pdf->Cell(100, 8, strtoupper($supplierName), 'B', 0, 'L');
    $pdf->Ln(15);
    $pdf->writeHTML($summaryTable, true, false, true, false, '');
    $pdf->Ln(5);
    
    $pdf->SetFont('times', '', 10);
    $pdf->Cell(55, 4, strtoupper($fileAs), 'B', 0, 'C', 0, '', 1);
    $pdf->Cell(10, 4, '     ', 0, 0 , 'L', '', 1);
    $pdf->Cell(55, 4, '', 'B', 0, 'C', 0, '', 1);
    $pdf->Ln(5);
    $pdf->Cell(55, 8, 'PREPARED BY', 0, 0, 'C');
    $pdf->Cell(10, 4, '     ', 0, 0 , 'L', '', 1);
    $pdf->Cell(55, 8, 'APPROVED BY', 0, 0, 'C');

    $pdf->AddPage();

    $pdf->SetFont('times', '', 20);
    $pdf->MultiCell(0, 0, '<b>'. $companyName .'</b>', 0, 'C', 0, 1, '', '', true, 0, true, true, 0);
    $pdf->Ln(0);
    $pdf->SetFont('times', '', 15);
    $pdf->MultiCell(0, 0, "<b><u>PURCHASE ORDER (SUPPLIER'S COPY)</u></b>", 0, 'C', 0, 1, '', '', true, 0, true, true, 0);
    $pdf->Ln(8);
    $pdf->SetFont('times', '', 10);
    $pdf->Cell(45, 8, 'REFERENCE NUMBER:', 0, 0, 'L');
    $pdf->Cell(60, 8, strtoupper($reference_no), 'B', 0, 'L');
    $pdf->Cell(5, 8, '', 0, 0, 'L');
    $pdf->Cell(15, 8, 'DATE:', 0, 0, 'L');
    $pdf->Cell(8, 8, '', 0, 0, 'L');
    $pdf->Cell(50, 8, strtoupper(date('F d, Y')), 'B', 0, 'L');
    $pdf->Ln(10);
    $pdf->Cell(25, 8, 'SUPPLIER:', 0, 0, 'L');
    $pdf->Cell(100, 8, strtoupper($supplierName), 'B', 0, 'L');
    $pdf->Cell(5, 8, '', 0, 0, 'L');
    $pdf->Ln(15);
    $pdf->writeHTML($summaryTable2, true, false, true, false, '');
    $pdf->Ln(5);
    
    
    $pdf->SetFont('times', '', 10);
    $pdf->Cell(55, 4, strtoupper($fileAs), 'B', 0, 'C', 0, '', 1);
    $pdf->Cell(10, 4, '     ', 0, 0 , 'L', '', 1);
    $pdf->Cell(55, 4, '', 'B', 0, 'C', 0, '', 1);
    $pdf->Ln(5);
    $pdf->Cell(55, 8, 'PREPARED BY', 0, 0, 'C');
    $pdf->Cell(10, 4, '     ', 0, 0 , 'L', '', 1);
    $pdf->Cell(55, 8, 'APPROVED BY', 0, 0, 'C');


    // Output the PDF to the browser
    $pdf->Output('job-order-list.pdf', 'I');
    ob_end_flush();

    function generatePrint($purchase_order_id, $type){
        
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
        require_once 'model/company-model.php';
        require_once 'model/body-type-model.php';
        require_once 'model/color-model.php';
        require_once 'model/supplier-model.php';
        require_once 'model/brand-model.php';
        require_once 'model/cabin-model.php';
        require_once 'model/model-model.php';
        require_once 'model/make-model.php';
        require_once 'model/class-model.php';
        require_once 'model/product-subcategory-model.php';

        $databaseModel = new DatabaseModel();
        $systemModel = new SystemModel();
        $userModel = new UserModel(new DatabaseModel, new SystemModel);
        $contractorModel = new ContractorModel($databaseModel);
        $workCenterModel = new WorkCenterModel($databaseModel);
        $partsModel = new PartsModel($databaseModel);
        $unitModel = new UnitModel($databaseModel);
        $bodyTypeModel = new BodyTypeModel($databaseModel);
        $colorModel = new ColorModel($databaseModel);
        $supplierModel = new SupplierModel($databaseModel);
        $brandModel = new BrandModel($databaseModel);
        $cabinModel = new CabinModel($databaseModel);
        $modelModel = new ModelModel($databaseModel);
        $makeModel = new MakeModel($databaseModel);
        $classModel = new ClassModel($databaseModel);
        $productSubcategoryModel = new ProductSubcategoryModel($databaseModel);
        $partsIncomingModel = new PartsIncomingModel($databaseModel);

         $partsIncomingDetails = $partsIncomingModel->getPartsIncoming($purchase_order_id);

        $company_id = $partsIncomingDetails['company_id'] ?? '';
    
        $sql = $databaseModel->getConnection()->prepare('SELECT * FROM purchase_order_unit WHERE purchase_order_id = :purchase_order_id ORDER BY created_date desc');
        $sql->bindValue(':purchase_order_id', $purchase_order_id, PDO::PARAM_INT);
        $sql->execute();
        $options = $sql->fetchAll(PDO::FETCH_ASSOC);
        $sql->closeCursor();

        $list = '';
        $averageTotal = 0;
        foreach ($options as $row) {
            $quantity = $row['quantity'];
            $unit_id = $row['unit_id'];
            $brand_id = $row['brand_id'];
                $model_id = $row['model_id'];
                $body_type_id = $row['body_type_id'];
                $class_id = $row['class_id'];
                $color_id = $row['color_id'];
                $make_id = $row['make_id'];
                $year_model = $row['year_model'];
                 $product_category_id = $row['product_category_id'];
                $length = $row['length'];
                $length_unit = $row['length_unit'];
                $cabin_id = $row['cabin_id'];
                $price = $row['price'];
                $remarks = $row['remarks'];

                 $bodyTypeName = $brandModel->getBrand($body_type_id)['body_type_name'] ?? '';
                $brandName = $brandModel->getBrand($brand_id)['brand_name'] ?? '';
                $modelName = $modelModel->getModel($model_id)['model_name'] ?? '';
                $class_name = $classModel->getClass($class_id)['class_name'] ?? '';
                $colorName = $colorModel->getColor($color_id)['color_name'] ?? '';
                $makeName = $makeModel->getMake($make_id)['make_name'] ?? '';
                $cabinName = $cabinModel->getCabin($cabin_id)['cabin_name'] ?? '';
                $short_name = $unitModel->getUnit($unit_id)['short_name'] ?? '';
                $length_unit_short_name = $unitModel->getUnit($length_unit)['short_name'] ?? '';

                $unit = '';
                if(!empty($productSubcategoryName)){
                    $unit .= 'Product Category: ' . $productSubcategoryName. '<br/>';
                }
                if(!empty($brandName)){
                    $unit .= 'Brand: ' . $brandName . '<br/>';
                }
                if(!empty($year_model)){
                    $unit .= 'Year Model: ' . $year_model . '<br/>';
                }
                if(!empty($modelName)){
                    $unit .= 'Model: ' . $modelName . '<br/>';
                }
                if(!empty($class_name)){
                    $unit .= 'Class: ' . $class_name . '<br/>';
                }
                if(!empty($colorName)){
                    $unit .= 'Color: ' . $colorName . '<br/>';
                }
                if(!empty($bodyTypeName)){
                    $unit .= 'Body Type: ' . $bodyTypeName . '<br/>';
                }
                if(!empty($makeName)){
                    $unit .= 'Make: ' . $makeName . '<br/>';
                }
                if(!empty($cabinName)){
                    $unit .= 'Cabin: ' . $cabinName . '<br/>';
                }
                if(!empty($length)){
                    $unit .= 'Length: ' . $length . ' ' . $length_unit_short_name . '';
                }

           

            $list .= '<tr>
                        <td width="35%" style="text-align:left">'. strtoupper($unit) .'</td>
                        <td width="20%" style="text-align:center">'. number_format($quantity, 2) . ' ' . strtoupper($short_name) .'</td>
                        <td width="45%">'. strtoupper($remarks) .'</td>
                    </tr>';
        }

        $response = '<table border="1" width="100%" cellpadding="5" align="left">
                        <thead>
                            <tr style="text-align:center">
                                <td width="35%"><b>Particulars</b></td>
                                <td width="20%"><b>Qty.</b></td>
                                <td width="45%"><b>Remarks</b></td>
                            </tr>
                        </thead>
                        <tbody>
                            '.$list.'
                        </tbody>
                    </table>';

        return $response;
    }
?>