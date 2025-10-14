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
    require_once 'model/parts-transaction-model.php';

    $databaseModel = new DatabaseModel();
    $systemModel = new SystemModel();
    $userModel = new UserModel(new DatabaseModel, new SystemModel);
    $partsTransactionModel = new PartsTransactionModel($databaseModel);
    $productModel = new ProductModel($databaseModel);
    $salesProposalModel = new SalesProposalModel($databaseModel);
    $customerModel = new CustomerModel($databaseModel);
    $productSubcategoryModel = new ProductSubcategoryModel($databaseModel);
    $supplierModel = new SupplierModel($databaseModel);

    if(isset($_GET['id'])){
        if(empty($_GET['id'])){
          header('location: netruck-parts-transaction.php');
          exit;
        }
        
        $partsTransactionID = $_GET['id'];

        $partTransactionDetails = $partsTransactionModel->getPartsTransaction($partsTransactionID);

        $company_id = $partTransactionDetails['company_id'] ?? '';
        $customer_id = $partTransactionDetails['customer_id'] ?? '';
        $slip_reference_no = $partTransactionDetails['slip_reference_no'] ?? '';
        $request_by = $partTransactionDetails['request_by'] ?? '';
        $customer_ref_id = $partTransactionDetails['customer_ref_id'] ?? '';
        $part_transaction_status = $partTransactionDetails['part_transaction_status'] ?? '';

        $productDetails = $productModel->getProduct($customer_id);
        $productSubategoryID = $productDetails['product_subcategory_id'] ?? '';

        $customerDetails = $customerModel->getPersonalInformation($customer_ref_id);
        $last_name = $customerDetails['last_name'] ?? '';

        $productSubcategoryDetails = $productSubcategoryModel->getProductSubcategory($productSubategoryID);
        $productSubcategoryCode = $productSubcategoryDetails['product_subcategory_code'] ?? null;

        $stockNumber = str_replace($productSubcategoryCode, '', $productDetails['stock_number'] ?? '');
        $fullStockNumber = $productSubcategoryCode . $stockNumber;

        $user = $userModel->getUserByID($user_id);
        $fileAs = $user['file_as'] ?? null;

        if($company_id == '2'){
            $title = 'NE TRUCKS BUILDERS CORP.';
        }
        else if($company_id == '1'){
            $title = 'CHRISTIAN GENERAL MOTORS INC.';
        }
        else{
            $title = 'FUSO TARLAC';
        }

        $unitNo = '';
        if($company_id != '1'){
            $unitNo = $fullStockNumber . ' - ' . strtoupper($last_name);
        }
    }

    $summaryTable = generatePrint($partsTransactionID);
    $summaryTable2 = generatePrint2($fileAs, $request_by);

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
   if($part_transaction_status == 'Cancelled'){
     $pdf->setPrintHeader(true);
   } 
   else{
    $pdf->setPrintHeader(false);
   }
    $pdf->setPrintFooter(false);
    $pdf->SetPageOrientation('P');

    // Set PDF metadata
    $pdf->SetCreator('CGMI');
    $pdf->SetAuthor('CGMI');
    $pdf->SetTitle('Issuance Slip');
    $pdf->SetSubject('Issuance Slip List');

    // Set margins and auto page break
    $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
    $pdf->SetMargins(15, 15, 15);
    $pdf->SetHeaderMargin(5);
    $pdf->SetFooterMargin(10);
    $pdf->SetAutoPageBreak(TRUE, 15);

    // Add a page
    $pdf->AddPage();

    $pdf->SetFont('times', 'B', 20);
    $pdf->Cell(45, 8, 'ISSUANCE SLIP', 0, 0, 'L');
    $pdf->Cell(80, 8, '', 0, 0, 'L');
    $pdf->SetTextColor(255, 0, 0);
    $pdf->Cell(20, 8, 'No. ' . $slip_reference_no, 0, 0, 'L');
     $pdf->SetTextColor(0, 0, 0);
    $pdf->Ln(15);
    $pdf->SetFont('times', '', 10);
    $pdf->Cell(45, 8, '', 0, 0, 'L');
    $pdf->Cell(80, 8, '', 0, 0, 'L');
    $pdf->Cell(10, 8, 'DATE:', 0, 0, 'L');
    $pdf->Cell(8, 8, '', 0, 0, 'L');
    $pdf->Cell(40, 8, strtoupper(date('F d, Y')), 'B', 0, 'C');
    $pdf->Ln(10);
    $pdf->SetFont('times', '', 10);
    $pdf->Cell(30, 8, 'CLIENT NAME:', 0, 0, 'L');
    $pdf->Cell(70, 8, $title, 'B', 0, 'L');
    $pdf->Cell(25, 8,'', 0, 0, 'L');
    $pdf->Cell(18, 8, 'OS NO:', 0, 0, 'L');
    $pdf->Cell(40, 8, '', 'B', 0, 'C');
    $pdf->Ln(10);
    $pdf->Cell(30, 8, 'UNIT NO.:', 0, 0, 'L');
    $pdf->Cell(70, 8, $unitNo, 'B', 0, 'L');
    $pdf->Cell(25, 8, '', 0, 0, 'L');
    $pdf->Cell(18, 8, 'AJO NO:', 0, 0, 'L');
    $pdf->Cell(40, 8, '', 'B', 0, 'C');
    $pdf->Ln(15);
    $pdf->writeHTML($summaryTable, true, false, true, false, '');
    $pdf->Ln(-2);
    $pdf->writeHTML($summaryTable2, true, false, true, false, '');

    // Output the PDF to the browser
    $pdf->Output('job-order-list.pdf', 'I');
    ob_end_flush();

    function generatePrint($part_transaction_id){
        
        require_once 'model/database-model.php';
        require_once 'model/pdc-management-model.php';
        require_once 'model/system-model.php';
        require_once 'model/user-model.php';
        require_once 'model/contractor-model.php';
        require_once 'model/employee-model.php';
        require_once 'model/work-center-model.php';
        require_once 'model/parts-model.php';
        require_once 'model/unit-model.php';

        $databaseModel = new DatabaseModel();
        $systemModel = new SystemModel();
        $userModel = new UserModel(new DatabaseModel, new SystemModel);
        $contractorModel = new ContractorModel($databaseModel);
        $workCenterModel = new WorkCenterModel($databaseModel);
        $partsModel = new PartsModel($databaseModel);
        $unitModel = new UnitModel($databaseModel);
    
       $sql = $databaseModel->getConnection()->prepare('SELECT * FROM part_transaction_cart WHERE part_transaction_id = :part_transaction_id ORDER BY part_id desc');
        $sql->bindValue(':part_transaction_id', $part_transaction_id, PDO::PARAM_INT);
        $sql->execute();
        $options = $sql->fetchAll(PDO::FETCH_ASSOC);
        $sql->closeCursor();

        $list='';
        $averageTotal = 0;
        foreach ($options as $row) {
            $part_id  = $row['part_id'];
            $quantity = $row['quantity'];

            $partDetails = $partsModel->getParts($part_id);
            $unitSale = $partDetails['unit_sale'] ?? null;
            $description = $partDetails['description'];
            $remarks = $partDetails['remarks'];

            $unitCode = $unitModel->getUnit($unitSale);
            $short_name = $unitCode['short_name'] ?? null;

            $list .= '<tr>
                        <td style="text-align:center">'. number_format($quantity, 2) . ' ' . strtoupper($short_name) .'</td>
                        <td style="text-align:center">'. strtoupper($description) .'</td>
                    </tr>';
        }

        $response = '<table border="1" width="100%" cellpadding="5" align="left">
                        <thead>
                            <tr style="text-align:center">
                                <td><b>Quantity</b></td>
                                <td><b>Particulars</b></td>
                            </tr>
                        </thead>
                        <tbody>
                            '.$list.'
                        </tbody>
                    </table>';

        return $response;
    }

    function generatePrint2($fileAs, $request_by){
        $response = '<table border="1" width="100%" cellpadding="5" align="left">
                        <tbody>
                            <tr>
                                <td>REQUESTED BY<br/>'. strtoupper($request_by) .'</td>
                                <td>RECEIVED BY<br/></td>
                            </tr>
                            <tr>
                                <td>PREPARED BY<br/>'. strtoupper($fileAs) .'</td>
                                <td>RELEASED BY<br/></td>
                            </tr>
                            <tr>
                                <td colspan="2">APPROVED BY<br/></td>
                            </tr>
                        </tbody>
                    </table>';

        return $response;
    }
?>