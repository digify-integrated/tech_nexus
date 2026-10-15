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

$quotationID = (int)$_GET['id'];
$referenceNo = str_pad($quotationID, 10, '0', STR_PAD_LEFT);
$databaseModel = new DatabaseModel();
$systemModel = new SystemModel();
$userModel = new UserModel($databaseModel, $systemModel);
$companyModel = new CompanyModel($databaseModel);

// Fetch Creator/User Details
$createdByDetails = isset($_SESSION['user_id']) ? $userModel->getUserByID($_SESSION['user_id']) : null;
$createdByName = strtoupper($createdByDetails['file_as'] ?? 'SALES REPRESENTATIVE');

// Fetch Footer Company Details (Company ID = 1)
$footerCompanyDetails = $companyModel->getCompany(1);

// Updated SQL query joining city, state, country for both Company and Lead
$query = "SELECT q.*, 
                 l.first_name, l.middle_name, l.last_name, l.corporate_name, l.email as lead_email, l.phone as lead_phone, l.address as lead_address,
                 lc.city_name as lead_city_name, ls.state_name as lead_state_name, lcn.country_name as lead_country_name,
                 pi.unit_description, pi.engine_no, pi.chassis_no, pi.color, pi.year_model,
                 c.company_name, c.company_logo, c.address as comp_address, c.phone as comp_phone, c.email as comp_email, c.website as comp_website, c.tax_id,
                 cc.city_name as comp_city_name, cs.state_name as comp_state_name, ccn.country_name as comp_country_name
          FROM quotations q
          LEFT JOIN leads l ON q.lead_id = l.lead_id
          LEFT JOIN city lc ON l.city_id = lc.city_id
          LEFT JOIN state ls ON lc.state_id = ls.state_id
          LEFT JOIN country lcn ON ls.country_id = lcn.country_id
          LEFT JOIN leads_product_inquiry pi ON q.leads_product_inquiry_id = pi.leads_product_inquiry_id
          LEFT JOIN company c ON q.company_id = c.company_id
          LEFT JOIN city cc ON c.city_id = cc.city_id
          LEFT JOIN state cs ON cc.state_id = cs.state_id
          LEFT JOIN country ccn ON cs.country_id = ccn.country_id
          WHERE q.quotation_id = :quotation_id";

$db = $databaseModel->getConnection();
$stmt = $db->prepare($query);
$stmt->execute([':quotation_id' => $quotationID]);
$data = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$data) {
    die('Quotation record not found.');
}

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

class ProfessionalTruckQuotationPDF extends TCPDF {
    public $footerCompanyName = 'Christian General Motors Inc.';
    public $footerPhone = '[Telephone]';
    public $footerEmail = '[Email]';
    public $companyLogo = null;

    // Setter method for dynamic footer details
    public function setCompanyDetails($companyDetails) {
        $this->footerCompanyName = $companyDetails['company_name'] ?? 'Christian General Motors Inc.';
        $this->companyLogo       = $companyDetails['company_logo'] ?? null;
        $this->footerPhone       = $companyDetails['phone'] ?? '[Telephone]';
        $this->footerEmail       = $companyDetails['email'] ?? '[Email]';
    }

    public function Header() {
        $this->SetFillColor(0, 0, 0);
        $this->Rect(0, 0, $this->getPageWidth(), 2, 'F');
    }

    public function Footer() {
        $this->SetY(-18);
        $this->SetFont('helvetica', '', 8);
        $this->SetTextColor(0, 0, 0);
        $this->SetDrawColor(0, 0, 0);
        $this->SetLineWidth(0.3);
        $this->Line(15, $this->GetY(), 195, $this->GetY());
        $this->Ln(2);

        $footerY = $this->GetY();

        // Footer Logo using class property
        if (!empty($this->companyLogo)) {
            $footerLogo = resolveImagePath($this->companyLogo);
            if ($footerLogo) {
                $this->Image($footerLogo, 15, $footerY, 0, 6, '', '', '', true, 300, '', false, false, 0, false, false, false);
            }
        }

        // Company Name shifted right
        $this->SetXY(25, $footerY);
        $this->SetFont('helvetica', 'B', 8);
        $this->Cell(40, 4, $this->footerCompanyName, 0, 0, 'L');

        // Dynamic telephone and email details
        $this->SetFont('helvetica', '', 8);
        $this->Cell(60, 4, ' Tel: ' . $this->footerPhone . ' Email: ' . $this->footerEmail, 0, 0, 'L');

        // Page numbering on the right
        $this->SetXY(145, $footerY);
        $this->Cell(50, 4, 'Page ' . $this->getAliasNumPage() . ' of ' . $this->getAliasNbPages(), 0, 0, 'R');
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

$quoteDate = date('M d, Y', strtotime($data['created_at']));
$expDate   = !empty($data['expiration_date']) ? date('M d, Y', strtotime($data['expiration_date'])) : 'N/A';

$resolvedPlaceholder = resolveImagePath('assets/images/default/default-logo-placeholder.png');
$resolvedPlaceholder2 = resolveImagePath('assets/images/default/quotation-placeholder.png');
$resolvedLogo = resolveImagePath($data['company_logo']);
if (!$resolvedLogo) {
    $resolvedLogo = $resolvedPlaceholder;
}

ob_start();

$pdf = new ProfessionalTruckQuotationPDF('P', 'mm', 'A4', true, 'UTF-8', false);
$pdf->setCompanyDetails($footerCompanyDetails);

$pdf->SetMargins(15, 12, 15);
$pdf->SetHeaderMargin(5);
$pdf->SetFooterMargin(18);
$pdf->SetAutoPageBreak(TRUE, 18);
$pdf->AddPage();

// 1. HEADER SECTION
$contactParts = [];
if (!empty(trim($data['comp_phone'] ?? ''))) $contactParts[] = 'Tel: ' . $data['comp_phone'];
if (!empty(trim($data['comp_email'] ?? ''))) $contactParts[] = 'Email: ' . $data['comp_email'];
$contactLine = implode('  ', $contactParts);

$tinLine = !empty(trim($data['tax_id'] ?? '')) ? 'TIN: ' . $data['tax_id'] : '';

$startY = $pdf->GetY();

// Add the placeholder logo before the company logo on the header
if ($resolvedPlaceholder2) {
    $pdf->Image($resolvedPlaceholder2, 10, $startY - 4, 0, 20, '', '', 'T', true, 300, '', false, false, 0, false, false, false);
}

// Move the company logo further to the right (X coordinate increased to 45)
if ($resolvedLogo) {
    $pdf->Image($resolvedLogo, 80, $startY, 0, 10, '', '', 'T', true, 300, '', false, false, 0, false, false, false);
} else {
    $pdf->SetFont('helvetica', 'B', 20);
    $pdf->SetXY(45, $startY);
    $pdf->Cell(60, 8, strtoupper($data['company_name'] ?? ''), 0, 0, 'L');
}

$pdf->SetFont('helvetica', 'B', 25);
$pdf->SetXY(115, $startY);
$pdf->Cell(80, 8, 'QUOTATION', 0, 1, 'R');

$subHeaderY = $startY + 15; 
$pdf->SetFont('helvetica', '', 8.5);
$pdf->SetXY(15, $subHeaderY);

$companyDetails = array_filter([$fullCompanyAddress, $contactLine, $tinLine]);
if (!empty($companyDetails)) {
    $pdf->MultiCell(100, 4, implode("\n", $companyDetails), 0, 'L', false, 0, 15, $subHeaderY, true, 0, false, true, 0, 'T');
}

// Header Meta Data (Date, Valid Until, Reference No.)
$pdf->SetXY(115, $subHeaderY);
$pdf->SetFont('helvetica', 'B', 8.5);
$pdf->Cell(55, 4, 'DATE:', 0, 0, 'R');
$pdf->SetFont('helvetica', '', 8.5);
$pdf->Cell(25, 4, $quoteDate, 0, 1, 'R');

$pdf->SetXY(115, $subHeaderY + 4.5);
$pdf->SetFont('helvetica', 'B', 8.5);
$pdf->Cell(55, 4, 'VALID UNTIL:', 0, 0, 'R');
$pdf->SetFont('helvetica', '', 8.5);
$pdf->Cell(25, 4, $expDate, 0, 1, 'R');

$pdf->SetXY(115, $subHeaderY + 9.0);
$pdf->SetFont('helvetica', 'B', 8.5);
$pdf->Cell(55, 4, 'REF NO.:', 0, 0, 'R');
$pdf->SetFont('helvetica', '', 8.5);
$pdf->Cell(25, 4, $referenceNo, 0, 1, 'R');

$pdf->SetY(max($pdf->GetY(), $subHeaderY + 15));
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

// 3. VEHICLE TABLE
$pdf->SetDrawColor(0, 0, 0);
$pdf->SetLineWidth(0.35);
$pdf->SetFillColor(0, 0, 0);
$pdf->SetTextColor(255, 255, 255);
$pdf->SetFont('helvetica', 'B', 8.5);

// Get discount early to determine column visibility and widths
$discount = (float)($data['discount'] ?? 0);

$colQtyWidth   = 12;
$colPriceWidth = 30;
$colTotalWidth = 30;
$colDiscWidth  = ($discount > 0) ? 30 : 0;
$colDescWidth  = ($discount > 0) ? 78 : 108; 

// Table Headers
$pdf->Cell($colDescWidth, 6, 'DESCRIPTION', 1, 0, 'C', true);
$pdf->Cell($colQtyWidth, 6, 'QTY', 1, 0, 'C', true);
$pdf->Cell($colPriceWidth, 6, 'UNIT PRICE', 1, 0, 'C', true);
if ($discount > 0) {
    $pdf->Cell($colDiscWidth, 6, 'DISCOUNT', 1, 0, 'C', true);
}
$pdf->Cell($colTotalWidth, 6, 'TOTAL', 1, 1, 'C', true);

$unitDescription = strtoupper($data['unit_description'] ?? 'TRUCK UNIT');
$yearModel       = $data['year_model'] ?? 'N/A';
$unitColor       = $data['color'] ?? 'N/A';
$unitPrice       = (float)($data['price'] ?? 0);
$quantity        = (int)($data['quantity'] ?? 1);
$total           = ($unitPrice - $discount) * $quantity;

$rowStartY = $pdf->GetY();
$rowHeight = 45;

// Row Rectangles (Borders)
$pdf->Rect(15, $rowStartY, $colDescWidth, $rowHeight);
$pdf->Rect(15 + $colDescWidth, $rowStartY, $colQtyWidth, $rowHeight);
$pdf->Rect(15 + $colDescWidth + $colQtyWidth, $rowStartY, $colPriceWidth, $rowHeight);

if ($discount > 0) {
    $pdf->Rect(15 + $colDescWidth + $colQtyWidth + $colPriceWidth, $rowStartY, $colDiscWidth, $rowHeight);
    $pdf->Rect(15 + $colDescWidth + $colQtyWidth + $colPriceWidth + $colDiscWidth, $rowStartY, $colTotalWidth, $rowHeight);
} else {
    $pdf->Rect(15 + $colDescWidth + $colQtyWidth + $colPriceWidth, $rowStartY, $colTotalWidth, $rowHeight);
}

$pdf->SetTextColor(0, 0, 0);

// Description Text & Details
$pdf->SetXY(16, $rowStartY + 2);
$pdf->SetFont('helvetica', 'B', 8.5);
$pdf->Cell($colDescWidth - 2, 4, $unitDescription, 0, 1, 'L');

$pdf->SetX(16);
$pdf->SetFont('helvetica', '', 8);
$pdf->Cell($colDescWidth - 2, 4, 'Year Model: ' . $yearModel . ' | Color: ' . $unitColor, 0, 1, 'L');

$resolvedTruckImage = resolveImagePath($data['quotation_image'] ?? '');
if ($resolvedTruckImage) {
    $pdf->Image($resolvedTruckImage, 17, $rowStartY + 11, $colDescWidth - 4, 31, '', '', '', false, 300, '', false, false, 0, 'CM');
}

// Dynamic Cell Placements using running X coordinates
$currentX = 15 + $colDescWidth;

$pdf->SetXY($currentX, $rowStartY + 2);
$pdf->SetFont('helvetica', '', 8.5);
$pdf->Cell($colQtyWidth, 5, $quantity, 0, 0, 'C');

$currentX += $colQtyWidth;
$pdf->SetXY($currentX, $rowStartY + 2);
$pdf->Cell($colPriceWidth - 2, 5, number_format($unitPrice, 2), 0, 0, 'C');

if ($discount > 0) {
    $currentX += $colPriceWidth;
    $pdf->SetXY($currentX, $rowStartY + 2);
    $pdf->Cell($colDiscWidth - 2, 5, number_format($discount, 2), 0, 0, 'C');
    $currentX += $colDiscWidth;
} else {
    $currentX += $colPriceWidth;
}

$pdf->SetXY($currentX, $rowStartY + 2);
$pdf->SetFont('helvetica', 'B', 8.5);
$pdf->Cell($colTotalWidth - 2, 5, number_format($total, 2), 0, 1, 'C');

$pdf->SetY($rowStartY + $rowHeight + 4);

// 4. SIMPLE HTML TABLE (Inclusions & Delivery Details)
$inclusions = !empty(trim($data['inclusions'] ?? '')) ? $data['inclusions'] : 'N/A';
$address    = !empty(trim($data['delivery_address'] ?? '')) ? $data['delivery_address'] : 'N/A';
$conditions = !empty(trim($data['delivery_conditions'] ?? '')) ? $data['delivery_conditions'] : 'N/A';
$instruc    = !empty(trim($data['delivery_instructions'] ?? '')) ? $data['delivery_instructions'] : 'N/A';
$remarks    = !empty(trim($data['remarks'] ?? '')) ? $data['remarks'] : 'None';

$detailsHtml = '
<table border="1" cellpadding="4" cellspacing="0" style="width: 100%; font-size: 8pt;">
    <tr bgcolor="#f0f0f0">
        <th width="45%" style="font-weight: bold;">INCLUSIONS & PACKAGES</th>
        <th width="55%" style="font-weight: bold;">DELIVERY DETAILS</th>
    </tr>
    <tr>
        <td rowspan="3" valign="top">' . nl2br(htmlspecialchars($inclusions)) . '</td>
        <td><b>Address:</b> ' . htmlspecialchars($address) . '</td>
    </tr>
    <tr>
        <td><b>Conditions:</b> ' . htmlspecialchars($conditions) . '</td>
    </tr>
    <tr>
        <td><b>Instructions:</b> ' . htmlspecialchars($instruc) . '</td>
    </tr>
    <tr bgcolor="#f0f0f0">
        <td colspan="2"  style="font-weight: bold;">REMARKS</td>
    </tr>
    <tr>
        <td colspan="2" height="50">' . nl2br(htmlspecialchars($remarks)) . '</td>
    </tr>
</table>';

$pdf->writeHTML($detailsHtml, true, false, true, false, '');

$pdf->Ln(4);

// 5. SIGNATURES
$sigY = $pdf->GetY();

// Prepared By Section (Left Column)
$pdf->SetXY(15, $sigY);
$pdf->SetFont('helvetica', '', 8);
$pdf->Cell(80, 4, 'PREPARED BY:', 0, 1, 'L');

$pdf->SetY($sigY + 10);
$pdf->SetFont('helvetica', 'B', 8.5);
$pdf->Cell(80, 4, $createdByName, 0, 1, 'L');

$pdf->Line(15, $pdf->GetY() + 2, 95, $pdf->GetY() + 2);
$pdf->SetY($pdf->GetY() + 3);

$pdf->SetFont('helvetica', 'B', 8.5);
$pdf->Cell(80, 4, 'Sales Representative', 0, 1, 'L');
$pdf->SetFont('helvetica', '', 8);
$pdf->Cell(80, 4, 'Christian General Motors Inc.', 0, 1, 'L');

// Conforme / Accepted By Section (Right Column)
$pdf->SetXY(115, $sigY);
$pdf->SetFont('helvetica', '', 8);
$pdf->Cell(80, 4, 'CONFORME / ACCEPTED BY:', 0, 1, 'L');

$pdf->SetY($sigY + 10);
$pdf->SetFont('helvetica', 'B', 8.5);
$pdf->SetX(115);
$pdf->Cell(80, 4, strtoupper($clientName), 0, 1, 'L');

$pdf->Line(115, $pdf->GetY() + 2, 195, $pdf->GetY() + 2);
$pdf->SetY($pdf->GetY() + 3);

$pdf->SetFont('helvetica', 'B', 8.5);
$pdf->SetX(115);
$pdf->Cell(80, 4, 'Client Signature', 0, 1, 'L');
$pdf->SetFont('helvetica', '', 8);
$pdf->SetX(115);
$pdf->Cell(80, 4, 'Date: ________________________', 0, 1, 'L');

$pdf->Output('Vehicle_Quotation.pdf', 'I');
ob_end_flush();
?>