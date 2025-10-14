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
    require_once 'model/parts-return-model.php';

    $databaseModel = new DatabaseModel();
    $systemModel = new SystemModel();
    $userModel = new UserModel(new DatabaseModel, new SystemModel);
    $partsReturnModel = new PartsReturnModel($databaseModel);
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
        
        $partsReturnID = $_GET['id'];

        $partsReturnDetails = $partsReturnModel->getPartsReturn($partsReturnID);
        $reference_number = $partsReturnDetails['reference_number'];
        $supplier_id = $partsReturnDetails['supplier_id'];
        $ref_invoice_number = $partsReturnDetails['ref_invoice_number'];
        $ref_po_number = $partsReturnDetails['ref_po_number'];
        $prev_total_billing = $partsReturnDetails['prev_total_billing'];
        $adjusted_total_billing = $partsReturnDetails['adjusted_total_billing'];
        $purchase_date = $systemModel->checkDate('empty', $partsReturnDetails['purchase_date'], '', 'm/d/Y', '');
        $ref_po_date = $systemModel->checkDate('empty', $partsReturnDetails['ref_po_date'], '', 'm/d/Y', '');

        
        $supplierDetails = $supplierModel->getSupplier($supplier_id);
        $supplier_name = $supplierDetails['supplier_name'];

    }

    $firstTable = generateFirst($supplier_name, $ref_invoice_number, $ref_po_number, $prev_total_billing, $adjusted_total_billing, $purchase_date, $ref_po_date);
    $summaryTable = generatePrint($partsReturnID);
    $summaryTable2 = generatePrint2();
    $summaryTable3 = generatePrint3();

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
    $pdf->SetTitle('Return Slip');
    $pdf->SetSubject('Return Slip List');

    // Set margins and auto page break
    $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
    $pdf->SetMargins(15, 15, 15);
    $pdf->SetHeaderMargin(5);
    $pdf->SetFooterMargin(10);
    $pdf->SetAutoPageBreak(TRUE, 15);

    // Add a page
    $pdf->AddPage();

    $pdf->SetFont('times', 'B', 20);
    $pdf->Cell(60, 8, 'PURCHASE RETURN ORDER FORM', 0, 0, 'L');
    $pdf->Cell(80, 8, '', 0, 0, 'L');
    $pdf->SetTextColor(255, 0, 0);
    $pdf->Cell(20, 8, 'No. ' . $reference_number, 0, 0, 'L');
     $pdf->SetTextColor(0, 0, 0);
    $pdf->Ln(15);
    $pdf->SetFont('times', '', 10);
    $pdf->writeHTML($firstTable, true, false, true, false, '');
    $pdf->Ln(-4);
    $pdf->writeHTML($summaryTable, true, false, true, false, '');
    $pdf->Ln(-2);
    $pdf->SetFont('times',  '', 8);
    $pdf->writeHTML($summaryTable2, true, false, true, false, '');
    $pdf->Ln(-2);
    $pdf->SetFont('times',  '', 8);
    $pdf->writeHTML($summaryTable3, true, false, true, false, '');
    $pdf->Ln(-2);
    $pdf->SetFont('times', 'BI', 10);
    $pdf->Cell(60, 8, 'REMARKS:', 0, 0, 'L');
    $pdf->Ln(4);
    $pdf->SetFont('times', '', 10);
    $pdf->Cell(80, 8, 'PLEASE REQUEST SUPPLIER TO REPLACE INVOICE OR ISSUE CREDIT MEMO OR TO COUNTERSIGN ALL', 0, 0, 'L');
    $pdf->Ln(4);
    $pdf->SetFont('times', '', 10);
    $pdf->Cell(80, 8, 'ALTERATIONS IN THE INVOICE.', 0, 0, 'L');

    // Output the PDF to the browser
    $pdf->Output('return-slip.pdf', 'I');
    ob_end_flush();

    function generatePrint($part_return_id){
        
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

        $databaseModel = new DatabaseModel();
        $systemModel = new SystemModel();
        $userModel = new UserModel(new DatabaseModel, new SystemModel);
        $contractorModel = new ContractorModel($databaseModel);
        $workCenterModel = new WorkCenterModel($databaseModel);
        $partsModel = new PartsModel($databaseModel);
        $partTransactionModel = new PartsTransactionModel($databaseModel);
        $unitModel = new UnitModel($databaseModel);
    
        $sql = $databaseModel->getConnection()->prepare('SELECT * FROM part_return_cart WHERE part_return_id = :part_return_id ORDER BY part_transaction_cart_id desc');
        $sql->bindValue(':part_return_id', $part_return_id, PDO::PARAM_INT);
        $sql->execute();
        $options = $sql->fetchAll(PDO::FETCH_ASSOC);
        $sql->closeCursor();

        $list='';
        $totalCost = 0;
        foreach ($options as $row) {
            $part_transaction_cart_id  = $row['part_transaction_cart_id'];
            $return_quantity  = $row['return_quantity'];

            $getPartsTransaction = $partTransactionModel->getPartsTransactionCart($part_transaction_cart_id);
            $part_id = $getPartsTransaction['part_id'] ?? '';
            $cost = $getPartsTransaction['cost'] ?? 0;

            $partDetails = $partsModel->getParts($part_id);
            $description = $partDetails['description'] ?? null;
            $unitSale = $partDetails['unit_sale'] ?? null;

            $unitCode = $unitModel->getUnit($unitSale);
            $short_name = $unitCode['short_name'] ?? null;
            $lineTotal = ($cost * $return_quantity);
            $totalCost = $totalCost + $lineTotal;

            $list .= '<tr>
                        <td style="text-align:center">'. strtoupper($description) .'</td>
                        <td style="text-align:center">'. $return_quantity .'</td>
                        <td style="text-align:center">'. strtoupper($short_name) .'</td>
                        <td style="text-align:center">'. number_format($cost, 2) .' PHP</td>
                        <td style="text-align:center">'. number_format($lineTotal, 2) .' PHP</td>
                    </tr>';
        }

        $response = '<table border="1" width="100%" cellpadding="5" align="left">
                        <thead>
                            <tr style="text-align:center">
                                <td><b>PARTICULARS</b></td>
                                <td><b>QTY.</b></td>
                                <td><b>UOM</b></td>
                                <td><b>UNIT COST</b></td>
                                <td><b>TOTAL COST</b></td>
                            </tr>
                        </thead>
                        <tbody>
                            '.$list.'
                            <tr>
                                <td style="text-align:center" colspan="4">TOTAL</td>
                                <td style="text-align:center">'. number_format($totalCost, 2) .' PHP</td>
                            </tr>
                        </tbody>
                    </table>';

        return $response;
    }

    function generatePrint2(){
        $response = '<table border="1" width="100%" cellpadding="5" align="left">
                        <tbody>
                            <tr>
                                <td>PREPARED BY<br/><br/><br/></td>
                                <td>APPROVED BY<br/></td>
                                <td>CHECKED/VERIFIED BY (GUARD ON DUTY)<br/></td>
                            </tr>
                        </tbody>
                    </table>';

        return $response;
    }

    function generatePrint3(){
        $response = '<table border="1" width="100%" cellpadding="5" align="left">
                        <tbody>
                            <tr>
                                <td>RECEIVED ITEMS IN GOOD ORDER AND CONDITION<br/><br/><br/><br/>
                                SIGNATURE OVER PRINTED NAME
                                </td>
                                <td>DATE RECEIVED<br/></td>
                            </tr>
                        </tbody>
                    </table>';

        return $response;
    }

    function generateFirst($supplier_name, $ref_invoice_number, $ref_po_number, $prev_total_billing, $adjusted_total_billing, $purchase_date, $ref_po_date){


        $response = '<table border="1" width="100%" cellpadding="5" align="left">
                        <tbody>
                            <tr>
                                <td>NAME OF SUPPLIER</td>
                                <td colspan="3">'. strtoupper($supplier_name) .'</td>
                            </tr>
                            <tr>
                                <td>DATE OF PURCHASED</td>
                                <td>'. $purchase_date .'</td>
                                <td>PREVIOUS TOTAL BILLING</td>
                                <td>'. number_format($prev_total_billing, 2) .' PHP</td>
                            </tr>
                            <tr>
                                <td>REF INV NO.</td>
                                <td>'. strtoupper($ref_invoice_number) .'</td>
                                <td>ADJUSTED TOTAL BILLING</td>
                                <td>'. number_format($adjusted_total_billing, 2) .' PHP</td>
                            </tr>
                            <tr>
                                <td>REF PO NO.</td>
                                <td>'. strtoupper($ref_po_number) .'</td>
                                <td>REF PO DATE</td>
                                <td>'. $ref_po_date .'</td>
                            </tr>
                        </tbody>
                    </table>';

        return $response;
    }
?>