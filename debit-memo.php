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

$salesProposalID = null;
if (isset($_GET['id'])) {
    $salesProposalID = (int) $_GET['id'];
}

$summaryTable = generatePrint($salesProposalID);

ob_start();

// Create TCPDF instance (custom paper size)
$pdf = new TCPDF('P', 'mm', array(330.2, 215.9), true, 'UTF-8', false);

// Disable header and footer
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);
$pdf->SetPageOrientation('P');

// Set PDF metadata
$pdf->SetCreator('CGMI');
$pdf->SetAuthor('CGMI');
$pdf->SetTitle('Credit Memo List');
$pdf->SetSubject('Credit Memo List');

// Set margins and auto page break
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
$pdf->SetMargins(15, 15, 15);
$pdf->SetHeaderMargin(5);
$pdf->SetFooterMargin(10);
$pdf->SetAutoPageBreak(TRUE, 15);

/**
 * Prints one CREDIT MEMO block (one copy) starting at the current cursor.
 */
function printCreditMemoCopy(TCPDF $pdf, string $summaryTable): void
{
    $pdf->SetFont('times', '', 20);
    $pdf->MultiCell(0, 0, '<b><u>DEBIT MEMO</u></b>', 0, 'C', 0, 1, '', '', true, 0, true, true, 0);
    $pdf->Ln(8);

    $pdf->SetFont('times', '', 10);
    $pdf->Cell(160, 8, 'DATE:', 0, 0, 'R');
    $pdf->Cell(20, 8, date('m/d/Y'), 0, 0, 'R');
    $pdf->Ln(10);

    $pdf->SetFont('times', '', 10);
    $pdf->writeHTML($summaryTable, true, false, true, false, '');
    $pdf->Ln(5);

    $pdf->SetFont('times', '', 10);
    $pdf->Cell(55, 4, '', 'B', 0, 'C', 0, '', 1);
    $pdf->Cell(10, 4, '     ', 0, 0, 'L', '', 1);
    $pdf->Cell(55, 4, '', 'B', 0, 'C', 0, '', 1);
    $pdf->Cell(10, 4, '     ', 0, 0, 'L', '', 1);
    $pdf->Cell(55, 4, '', 'B', 0, 'C', 0, '', 1);
    $pdf->Ln(5);

    $pdf->Cell(55, 8, 'RECOMMENDED BY', 0, 0, 'C');
    $pdf->Cell(10, 8, '     ', 0, 0, 'L');
    $pdf->Cell(55, 8, 'CHECKED BY', 0, 0, 'C');
    $pdf->Cell(10, 8, '     ', 0, 0, 'L');
    $pdf->Cell(55, 8, 'APPROVED BY', 0, 0, 'C');
}

/**
 * Draws a simple separator line between copies when both fit on one page.
 */
function drawCopySeparator(TCPDF $pdf): void
{
    $pdf->Ln(20);

    $left = $pdf->GetX(); // current X (should be left margin)
    $y = $pdf->GetY();
    $x1 = $pdf->GetMargins()['left'];
    $x2 = $pdf->getPageWidth() - $pdf->GetMargins()['right'];

    $pdf->Line($x1, $y, $x2, $y);
    $pdf->Ln(10);
}

// Add first page (we will decide if we can fit 2 copies in 1 page)
$pdf->AddPage();

/**
 * TRY: print 2 copies on the same page inside a transaction.
 * If TCPDF creates a new page during the second copy, rollback and print as 2 pages.
 */
$pdf->startTransaction();
$startNumPages = $pdf->getNumPages();
$startPage = $pdf->getPage();

// Attempt: first copy
printCreditMemoCopy($pdf, $summaryTable);

// Separator + second copy
drawCopySeparator($pdf);
printCreditMemoCopy($pdf, $summaryTable);

// Did TCPDF add a new page while printing the second copy?
$endNumPages = $pdf->getNumPages();
$endPage = $pdf->getPage();

$twoCopiesFitOnOnePage = ($endNumPages === $startNumPages) && ($endPage === $startPage);

if ($twoCopiesFitOnOnePage) {
    // Keep what we printed
    $pdf->commitTransaction();
} else {
    // Roll back and print as separate pages
    $pdf->rollbackTransaction(true);

    // Page 1: copy 1
    // (We are already on page 1 because rollback keeps state consistent)
    printCreditMemoCopy($pdf, $summaryTable);

    // Page 2: copy 2
    $pdf->AddPage();
    printCreditMemoCopy($pdf, $summaryTable);
}

// Output the PDF to the browser
$pdf->Output('credit-memo-list.pdf', 'I');
ob_end_flush();

function generatePrint($sales_proposal_id)
{
    require_once 'model/database-model.php';
    require_once 'model/sales-proposal-model.php';
    require_once 'model/system-model.php';
    require_once 'model/customer-model.php';
    require_once 'model/user-model.php';
    require_once 'model/product-model.php';

    $databaseModel = new DatabaseModel();
    $systemModel = new SystemModel();
    $userModel = new UserModel(new DatabaseModel, new SystemModel);
    $salesProposalModel = new SalesProposalModel($databaseModel);
    $customerModel = new CustomerModel($databaseModel);
    $productModel = new ProductModel($databaseModel);

    $salesProposalDetails = $salesProposalModel->getSalesProposal($sales_proposal_id);
    $customerID = $salesProposalDetails['customer_id'];
    $productID = $salesProposalDetails['product_id'];
    $loanNumber = $salesProposalDetails['loan_number'];

    $productDetails = $productModel->getProduct($productID);
    $productName = $productDetails['description'] ?? null;
    $stockNumber = $productDetails['stock_number'] ?? null;

    $customerDetails = $customerModel->getPersonalInformation($customerID);
    $customerName = strtoupper($customerDetails['file_as']) ?? null;

    $sql = $databaseModel->getConnection()->prepare('
        SELECT * FROM loan_collections
        WHERE sales_proposal_id = :sales_proposal_id AND mode_of_payment = "Check" AND payment_details IN ("Insurance Renewal", "Registration Renewal") AND collection_status = "Pending"
        ORDER BY check_date
    ');
    $sql->bindValue(':sales_proposal_id', $sales_proposal_id, PDO::PARAM_INT);
    $sql->execute();
    $options = $sql->fetchAll(PDO::FETCH_ASSOC);
    $sql->closeCursor();

    $list = '';
    $total = 0;
    foreach ($options as $row) {
        $transaction_date = $systemModel->checkDate('summary', $row['check_date'], '', 'm/d/Y', '');
        $deposit_amount = (float) $row['payment_amount'];
        $total += $deposit_amount;

        $list .= 'Due Date: ' . $transaction_date . ' - PHP ' . number_format($deposit_amount, 2) . '<br/>';
    }

    $response = '
        <table border="1" width="100%" cellpadding="5" align="left">
            <thead>
                <tr style="text-align:left">
                    <td width="60%"><b>Name of Customer</b> <br/> ' . $customerName . '</td>
                    <td width="20%"><b>PN No.</b> <br/> ' . $loanNumber . '</td>
                    <td width="20%"><b>Stock No.</b> <br/> ' . $stockNumber . '</td>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td width="60%" style="text-align:left">TO CHARGE INSURANCE/REGISTRATION RENEWAL TO ACCT:<br/> <br/>
                        ' . $list . '
                    </td>
                    <td width="20%" style="text-align:center"></td>
                    <td width="20%">PHP ' . number_format($total, 2) . '</td>
                </tr>
            </tbody>
        </table>
    ';

    return $response;
}
?>