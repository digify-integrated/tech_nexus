<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Load TCPDF library
require_once('assets/libs/tcpdf2/tcpdf.php');

// Load required configuration and models
require_once 'config/config.php';
require_once 'session.php';
require_once 'model/database-model.php';
require_once 'model/system-model.php';
require_once 'model/company-model.php';
require_once 'model/user-model.php';

// Data Fetching
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header('location: dashboard.php');
    exit;
}
$spafID = (int)$_GET['id'];
$databaseModel = new DatabaseModel();
$systemModel = new SystemModel();
$userModel = new UserModel($databaseModel, $systemModel);

$createdByDetails = isset($_SESSION['user_id']) ? $userModel->getUserByID($_SESSION['user_id']) : null;
$createdByName = strtoupper($createdByDetails['file_as'] ?? 'SALES REPRESENTATIVE');


// Updated SQL query joining product_subcategory to get the subcategory name
$query = "SELECT q.*, 
                 spaf.*,
                 l.first_name, l.middle_name, l.last_name, l.corporate_name, l.email as lead_email, l.phone as lead_phone, l.address as lead_address,
                 lc.city_name as lead_city_name, ls.state_name as lead_state_name, lcn.country_name as lead_country_name,
                 pi.unit_description, pi.engine_no, pi.chassis_no, pi.color, pi.year_model,
                 psub.product_subcategory_name,
                 s.supplier_name,
                 c.company_name, c.company_logo, c.address as comp_address, c.phone as comp_phone, c.email as comp_email, c.website as comp_website, c.tax_id,
                 cc.city_name as comp_city_name, cs.state_name as comp_state_name, ccn.country_name as comp_country_name
          FROM spaf spaf
          INNER JOIN cif q ON spaf.cif_id = q.cif_id
          LEFT JOIN supplier s ON spaf.supplier_id = s.supplier_id
          LEFT JOIN leads l ON q.lead_id = l.lead_id
          LEFT JOIN city lc ON l.city_id = lc.city_id
          LEFT JOIN state ls ON lc.state_id = ls.state_id
          LEFT JOIN country lcn ON ls.country_id = lcn.country_id
          LEFT JOIN leads_product_inquiry pi ON q.leads_product_inquiry_id = pi.leads_product_inquiry_id
          LEFT JOIN product_subcategory psub ON pi.product_subcategory_id = psub.product_subcategory_id
          LEFT JOIN company c ON q.company_id = c.company_id
          LEFT JOIN city cc ON c.city_id = cc.city_id
          LEFT JOIN state cs ON cc.state_id = cs.state_id
          LEFT JOIN country ccn ON cs.country_id = ccn.country_id
          WHERE spaf.spaf_id = :spaf_id";

$db = $databaseModel->getConnection();
$stmt = $db->prepare($query);
$stmt->execute([':spaf_id' => $spafID]);
$data = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$data) {
    die('Special Price Approval Form record not found.');
}

$referenceNo = !empty($data['spaf_reference_no']) ? $data['spaf_reference_no'] : $spafID;

// Build Formatted Company Address
$compAddressParts = array_filter([
    $data['comp_address'] ?? null,
    $data['comp_city_name'] ?? null,
    $data['comp_state_name'] ?? null,
    $data['comp_country_name'] ?? null
]);
$fullCompanyAddress = !empty($compAddressParts) ? implode(', ', $compAddressParts) : '';

// Build Formatted Customer/Lead Address
$leadAddressParts = array_filter([
    $data['lead_address'] ?? null,
    $data['lead_city_name'] ?? null,
    $data['lead_state_name'] ?? null,
    $data['lead_country_name'] ?? null
]);
$fullLeadAddress = !empty($leadAddressParts) ? implode(', ', $leadAddressParts) : 'N/A';

class ProfessionalClientInquiryPDF extends TCPDF {
    public $companyName = 'COMPANY NAME';

    public function Header() {
        $this->SetFillColor(0, 0, 0);
        $this->Rect(0, 0, $this->getPageWidth(), 2, 'F');
    }

    public function Footer() {
        $this->SetY(-15);
        $this->SetFont('helvetica', '', 8);
        $this->SetTextColor(0, 0, 0);
        $this->SetDrawColor(0, 0, 0);
        $this->SetLineWidth(0.3);
        $this->Line(15, $this->GetY(), 195, $this->GetY());
        $this->Ln(2);
        $this->Cell(100, 4, strtoupper($this->companyName), 0, 0, 'L');
        $this->Cell(80, 4, 'Page ' . $this->getAliasNumPage() . ' of ' . $this->getAliasNbPages(), 0, 0, 'R');
    }
}

function resolveImagePath($relativePath) {
    if (empty($relativePath)) return null;
    $cleanPath = ltrim(str_replace('\\', '/', $relativePath), '/');
    if (isset($_SERVER['DOCUMENT_ROOT']) && !empty($_SERVER['DOCUMENT_ROOT'])) {
        $fullPath = rtrim($_SERVER['DOCUMENT_ROOT'], '/') . '/' . $cleanPath;
        if (file_exists($fullPath)) return $fullPath;
    }
    if (file_exists($cleanPath)) return $cleanPath;
    return null;
}

$clientName = trim($data['first_name'] . ' ' . $data['middle_name'] . ' ' . $data['last_name']);
if (!empty($data['corporate_name'])) {
    $clientName = $data['corporate_name'] . ' (' . $clientName . ')';
}

$formDate = date('M d, Y', strtotime($data['created_at']));

$resolvedLogo = resolveImagePath($data['company_logo']);
$resolvedTruckImage = resolveImagePath($data['spaf_image']);

ob_start();

$pdf = new ProfessionalClientInquiryPDF('P', 'mm', 'A4', true, 'UTF-8', false);
$pdf->companyName = $data['company_name'] ?? 'COMPANY NAME';

$pdf->SetMargins(15, 12, 15);
$pdf->SetHeaderMargin(5);
$pdf->SetFooterMargin(15);
$pdf->SetAutoPageBreak(TRUE, 18);
$pdf->AddPage();

// 1. HEADER SECTION
$contactParts = [];
if (!empty(trim($data['comp_phone'] ?? ''))) $contactParts[] = 'Tel: ' . $data['comp_phone'];
if (!empty(trim($data['comp_email'] ?? ''))) $contactParts[] = 'Email: ' . $data['comp_email'];
$contactLine = implode('  ', $contactParts);

$tinLine = !empty(trim($data['tax_id'] ?? '')) ? 'TIN: ' . $data['tax_id'] : '';

$startY = $pdf->GetY();
$leftBottomY = $startY;

if ($resolvedLogo) {
    $pdf->Image($resolvedLogo, 15, $startY, 0, 10, '', '', 'T', true, 300, '', false, false, 0, false, false, false);
    $leftBottomY = $startY + 10;
} else {
    $pdf->SetFont('helvetica', 'B', 16);
    $pdf->SetXY(15, $startY);
    $pdf->MultiCell(95, 6, strtoupper($data['company_name'] ?? ''), 0, 'L', false, 0, 15, $startY, true, 0, false, true, 0, 'T');
    $leftBottomY = $startY + $pdf->getLastH();
}

$pdf->SetFont('helvetica', 'B', 14);
$pdf->SetXY(115, $startY);
$pdf->Cell(80, 8, 'SPECIAL PRICE APPROVAL FORM', 0, 1, 'R');

$subHeaderY = $leftBottomY + 3; 
$pdf->SetFont('helvetica', '', 8.5);
$pdf->SetXY(15, $subHeaderY);

$companyDetailsBottomY = $subHeaderY;
$companyDetails = array_filter([$fullCompanyAddress, $contactLine, $tinLine]);
if (!empty($companyDetails)) {
    $pdf->MultiCell(100, 4, implode("\n", $companyDetails), 0, 'L', false, 0, 15, $subHeaderY, true, 0, false, true, 0, 'T');
    $companyDetailsBottomY = $pdf->GetY();
}

// Header Meta Data (Date, Reference No.)
$pdf->SetXY(100, $startY + 10);
$pdf->SetFont('helvetica', 'B', 8.5);
$pdf->Cell(55, 4, 'REF NO.:', 0, 0, 'R');
$pdf->SetFont('helvetica', '', 8.5);
$pdf->Cell(40, 4, $referenceNo, 0, 1, 'R');

$pdf->SetXY(115, $startY + 14.5);
$pdf->SetFont('helvetica', 'B', 8.5);
$pdf->Cell(55, 4, 'DATE:', 0, 0, 'R');
$pdf->SetFont('helvetica', '', 8.5);
$pdf->Cell(25, 4, $formDate, 0, 1, 'R');

$rightMetaBottomY = $startY + 20;
$separatorY = max(($companyDetailsBottomY - 10), $rightMetaBottomY) + 2;

$pdf->SetY($separatorY);
$pdf->SetDrawColor(0, 0, 0);
$pdf->SetLineWidth(0.35);
$pdf->Line(15, $pdf->GetY(), 195, $pdf->GetY());
$pdf->Ln(4);

// 2. CUSTOMER DETAILS
$pdf->SetFont('helvetica', 'B', 9);
$pdf->Cell(180, 5, 'CUSTOMER DETAILS', 0, 1, 'L');
$pdf->Ln(1);

$pdf->SetFont('helvetica', 'B', 8.5);
$pdf->Cell(16, 5, 'Name: ', 0, 0, 'L');
$pdf->SetFont('helvetica', '', 8.5);
$pdf->Cell(100, 5, $clientName, 'B', 0, 'L');
$pdf->Cell(4, 5, '', 0, 0);
$pdf->SetFont('helvetica', 'B', 8.5);
$pdf->Cell(14, 5, 'Phone: ', 0, 0, 'L');
$pdf->SetFont('helvetica', '', 8.5);
$pdf->Cell(46, 5, $data['lead_phone'] ?? '', 'B', 1, 'L');

$pdf->Ln(2);

$pdf->SetFont('helvetica', 'B', 8.5);
$pdf->Cell(18, 5, 'Address: ', 0, 0, 'L');
$pdf->SetFont('helvetica', '', 8.5);
$pdf->Cell(98, 5, $fullLeadAddress, 'B', 0, 'L');
$pdf->Cell(4, 5, '', 0, 0);
$pdf->SetFont('helvetica', 'B', 8.5);
$pdf->Cell(12, 5, 'Email: ', 0, 0, 'L');
$pdf->SetFont('helvetica', '', 8.5);
$pdf->Cell(48, 5, $data['lead_email'] ?? '', 'B', 1, 'L');

$pdf->Ln(6);

// 3. VEHICLE & UNIT PRICE TABLE (3 Columns: Product Class, Description, Unit Price)
$pdf->SetDrawColor(0, 0, 0);
$pdf->SetLineWidth(0.35);
$pdf->SetFillColor(0, 0, 0);
$pdf->SetTextColor(255, 255, 255);
$pdf->SetFont('helvetica', 'B', 8.5);

$colClassWidth  = 35;
$colDescWidth   = 95;  // Widened description column
$colPriceWidth  = 50;  // Unit price column width (Total = 180mm)

// Table Headers ('PRODUCT CLASS' and 'UNIT PRICE')
$pdf->Cell($colClassWidth, 6, 'PRODUCT CLASS', 1, 0, 'C', true);
$pdf->Cell($colDescWidth, 6, 'DESCRIPTION', 1, 0, 'C', true);
$pdf->Cell($colPriceWidth, 6, 'UNIT PRICE', 1, 1, 'C', true);

$vehicleClass    = !empty($data['product_subcategory_name']) ? strtoupper($data['product_subcategory_name']) : 'VEHICLE UNIT';
$unitDescription = strtoupper($data['unit_description'] ?? 'TRUCK UNIT');
$yearModel       = $data['year_model'] ?? 'N/A';
$unitColor       = $data['color'] ?? 'N/A';
$engineNo        = $data['engine_no'] ?? 'N/A';
$chassisNo       = $data['chassis_no'] ?? 'N/A';
$remarks         = !empty(trim($data['remarks'] ?? '')) ? $data['remarks'] : 'N/A';

// Format Unit Price value
$rawUnitPrice    = $data['unit_price'] ?? ($data['price'] ?? 0);
$unitPriceVal    = is_numeric($rawUnitPrice) ? number_format((float)$rawUnitPrice, 2) : '0.00';

$rowStartY = $pdf->GetY();
$rowHeight = 45;

// Row Rectangles (Borders) for all 3 columns
$pdf->Rect(15, $rowStartY, $colClassWidth, $rowHeight);
$pdf->Rect(15 + $colClassWidth, $rowStartY, $colDescWidth, $rowHeight);
$pdf->Rect(15 + $colClassWidth + $colDescWidth, $rowStartY, $colPriceWidth, $rowHeight);

$pdf->SetTextColor(0, 0, 0);

// Class Column Text
$pdf->SetXY(16, $rowStartY + 4);
$pdf->SetFont('helvetica', 'B', 8.5);
$pdf->MultiCell($colClassWidth - 2, 4, $vehicleClass, 0, 'C', false, 0, '', '', true, 0, false, true, 0, 'M');

// Description Text & Details Column with MultiCell wrapping for unit description
$pdf->SetXY(15 + $colClassWidth + 2, $rowStartY + 2);
$pdf->SetFont('helvetica', 'B', 8.5);
$pdf->MultiCell($colDescWidth - 4, 4, $unitDescription, 0, 'L', false, 1);

$pdf->SetX(15 + $colClassWidth + 2);
$pdf->Cell($colDescWidth - 4, 4, $remarks, 0, 1, 'L');

if ($resolvedTruckImage) {
    $pdf->Image($resolvedTruckImage, 15 + $colClassWidth + 4, $rowStartY + 18, 45, 24, '', '', '', false, 300, '', false, false, 0, 'CM');
}

// Unit Price Column Text
$pdf->SetXY(15 + $colClassWidth + $colDescWidth + 2, $rowStartY + 2);
$pdf->SetFont('helvetica', '', 8);
$pdf->MultiCell($colPriceWidth - 4, $rowHeight - 9, $unitPriceVal, 0, 'L', false, 0, '', '', true, 0, false, true, 0, 'T');

// Move cursor past the item row block
$pdf->SetY($rowStartY + $rowHeight);

// --- ADDITIONAL ROWS: Dealer's Discount & Total ---
$summaryRowHeight = 6;
$mergedLabelWidth = $colClassWidth + $colDescWidth; // 35 + 95 = 130mm

// Calculate / Fetch Discount and Total
$rawDiscount = $data['dealers_discount'] ?? ($data['dealer_discount'] ?? ($data['discount'] ?? 0));
$discountVal = is_numeric($rawDiscount) ? number_format((float)$rawDiscount, 2) : '0.00';

$numericUnitPrice = is_numeric($rawUnitPrice) ? (float)$rawUnitPrice : 0.00;
$numericDiscount  = is_numeric($rawDiscount) ? (float)$rawDiscount : 0.00;
$rawTotal         = $data['total'] ?? ($data['total_price'] ?? ($numericUnitPrice - $numericDiscount));
$totalVal         = is_numeric($rawTotal) ? number_format((float)$rawTotal, 2) : number_format($numericUnitPrice - $numericDiscount, 2);

// 1. Dealer's Discount Row
$pdf->SetFont('helvetica', 'B', 8.5);
$pdf->Cell($mergedLabelWidth, $summaryRowHeight, "DEALER'S DISCOUNT:", 1, 0, 'R');
$pdf->SetFont('helvetica', '', 8.5);
$pdf->Cell($colPriceWidth, $summaryRowHeight, $discountVal, 1, 1, 'R');

// 2. Total Row
$pdf->SetFont('helvetica', 'B', 8.5);
$pdf->Cell($mergedLabelWidth, $summaryRowHeight, 'TOTAL:', 1, 0, 'R');
$pdf->SetFont('helvetica', 'B', 8.5);
$pdf->Cell($colPriceWidth, $summaryRowHeight, $totalVal, 1, 1, 'R');

$pdf->Ln(4);

// --- DISCLOSURE / REMARKS SECTION ---
$pdf->SetX(15);
$pdf->SetFont('helvetica', 'B', 8);
$pdf->Cell(180, 4, 'Remarks:', 0, 1, 'L');

$pdf->SetFont('helvetica', '', 7.5);
$pdf->SetX(15);
$pdf->Cell(180, 3.5, '(1) Prices are subject to change without prior notice', 0, 1, 'L');
$pdf->SetX(15);
$pdf->Cell(180, 3.5, '(2) Validity: Up to 15 days from date of quotation', 0, 1, 'L');
$pdf->SetX(15);
$pdf->Cell(180, 3.5, '(3) Additional items required by client not included in the quotation shall be charged accordingly', 0, 1, 'L');

$pdf->Ln(4);

// 4. REVISED SIGNATURES SECTION (Prepared By Column Only)
$sigY = $pdf->GetY();
$tableHeight = 26; // Total height for the signature block
$colWidth = 80;    // Width for the single "Prepared By" signature box

$pdf->SetDrawColor(0, 0, 0);
$pdf->SetLineWidth(0.35);


// Column Content: Prepared By Header
$pdf->SetXY(15 + 3, $sigY + 2);
$pdf->SetFont('helvetica', '', 8);
$pdf->Cell($colWidth - 6, 4, 'PREPARED BY:', 0, 1, 'L');

$pdf->SetXY(15 + 3, $sigY + 11);
$pdf->SetFont('helvetica', 'B', 8.5);
$pdf->Cell($colWidth - 6, 4, $createdByName, 0, 0, 'C');

// Signature Line inside the box
$pdf->Line(15 + 4, $sigY + 16, 15 + $colWidth - 4, $sigY + 16);

// Move cursor below the signature block
$pdf->SetY($sigY + $tableHeight + 6);

$pdf->Output('Special_Price_Approval_Form.pdf', 'I');
ob_end_flush();
?>