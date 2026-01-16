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
    require_once 'model/purchase-request-model.php';

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
    $purchaseRequestModel = new PurchaseRequestModel($databaseModel);

    if(isset($_GET['id'])){
        if(empty($_GET['id'])){
          header('location: purchase-request.php');
          exit;
        }
        
        $purchase_request_id = $_GET['id'];

        $purchaseRequestDetails = $purchaseRequestModel->getPurchaseRequest($purchase_request_id);
        $company_id = $purchaseRequestDetails['company_id'] ?? '';
        $reference_no = $purchaseRequestDetails['reference_no'] ?? '';

        $companyDetails = $companyModel->getCompany($company_id);
        
        $companyName = $companyDetails['company_name'] ?? '';
        $user = $userModel->getUserByID($user_id);
        $fileAs = $user['file_as'] ?? null;
    }

    $summaryTable = generatePrint($purchase_request_id, 1);
    $summaryTable2 = generatePrint($purchase_request_id, 2);

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
    $pdf->SetTitle('Purchase Request List');
    $pdf->SetSubject('Purchase Request List');

    // Set margins and auto page break
    $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
    $pdf->SetMargins(15, 15, 15);
    $pdf->SetHeaderMargin(5);
    $pdf->SetFooterMargin(10);
    $pdf->SetAutoPageBreak(TRUE, 15);

    $pdf->AddPage();

    $pdf->SetFont('times', '', 20);
    $pdf->MultiCell(0, 0, '<b>'. $companyName .'</b>', 0, 'C', 0, 1, '', '', true, 0, true, true, 0);
    $pdf->Ln(0);
    $pdf->SetFont('times', '', 15);
    $pdf->MultiCell(0, 0, "<b><u>PURCHASE REQUEST</u></b>", 0, 'C', 0, 1, '', '', true, 0, true, true, 0);
    $pdf->Ln(8);
    $pdf->SetFont('times', '', 10);
    $pdf->Cell(45, 8, 'REFERENCE NUMBER:', 0, 0, 'L');
    $pdf->Cell(60, 8, strtoupper($reference_no), 'B', 0, 'L');
    $pdf->Cell(5, 8, '', 0, 0, 'L');
    $pdf->Cell(15, 8, 'DATE:', 0, 0, 'L');
    $pdf->Cell(8, 8, '', 0, 0, 'L');
    $pdf->Cell(50, 8, strtoupper(date('F d, Y')), 'B', 0, 'L');
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
    $pdf->Output('job-request-list.pdf', 'I');
    ob_end_flush();

    function generatePrint($purchase_request_id, $type){
        
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

         $partsIncomingDetails = $partsIncomingModel->getPartsIncoming($purchase_request_id);

        $company_id = $partsIncomingDetails['company_id'] ?? '';
    
        $sql = $databaseModel->getConnection()->prepare('SELECT * FROM purchase_request_cart WHERE purchase_request_id = :purchase_request_id ORDER BY description');
        $sql->bindValue(':purchase_request_id', $purchase_request_id, PDO::PARAM_INT);
        $sql->execute();
        $options = $sql->fetchAll(PDO::FETCH_ASSOC);
        $sql->closeCursor();

        $list = '';
        $averageTotal = 0;
        foreach ($options as $row) {
            $description = $row['description'];
            $quantity = $row['quantity'];
            $short_name = $row['short_name'];
            $remarks = $row['remarks'];

            $list .= '<tr>
                        <td width="35%" style="text-align:left">'. strtoupper($description) .'</td>
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