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
require_once 'model/employee-model.php';
require_once 'model/user-model.php';

// Data Fetching
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header('location: dashboard.php');
    exit;
}

$quotationID = (int)$_GET['id'];
$referenceNo = 'QT-' . str_pad($quotationID, 6, '0', STR_PAD_LEFT);
$databaseModel = new DatabaseModel();
$systemModel = new SystemModel();
$userModel = new UserModel($databaseModel, $systemModel);
$companyModel = new CompanyModel($databaseModel);
$employeeModel = new EmployeeModel($databaseModel);

// Fetch Creator/User Details
$createdByDetails = isset($_SESSION['user_id']) ? $userModel->getUserByID($_SESSION['user_id']) : null;
$createdByName = ucwords(strtolower($createdByDetails['file_as'] ?? 'Sales Representative'));

// Fetch Footer Company Details (Company ID = 1)
$footerCompanyDetails = $companyModel->getCompany(1);

// SQL query joining city, state, country for both Company and Lead
$query = "SELECT q.*, 
                 l.assigned_to as assigned_to, l.first_name, l.middle_name, l.last_name, l.corporate_name, l.email as lead_email, l.phone as lead_phone, l.address as lead_address,
                 lc.city_name as lead_city_name, ls.state_name as lead_state_name, lcn.country_name as lead_country_name,
                 pi.unit_description, pi.engine_no, pi.chassis_no, pi.color, pi.year_model, pi.product_id, pi.product_subcategory_id,
                 ps.default_image as subcategory_default_image,
                 c.company_name, c.company_logo, c.address as comp_address, c.phone as comp_phone, c.email as comp_email, c.website as comp_website, c.tax_id,
                 cc.city_name as comp_city_name, cs.state_name as comp_state_name, ccn.country_name as comp_country_name
          FROM quotations q
          LEFT JOIN leads l ON q.lead_id = l.lead_id
          LEFT JOIN city lc ON l.city_id = lc.city_id
          LEFT JOIN state ls ON lc.state_id = ls.state_id
          LEFT JOIN country lcn ON ls.country_id = lcn.country_id
          LEFT JOIN leads_product_inquiry pi ON q.leads_product_inquiry_id = pi.leads_product_inquiry_id
          LEFT JOIN product p ON pi.product_id = p.product_id
          LEFT JOIN product_subcategory ps ON ps.product_subcategory_id = CASE 
              WHEN pi.product_id IS NOT NULL AND pi.product_id != '' THEN p.product_subcategory_id 
              ELSE pi.product_subcategory_id 
          END
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

// Build Formatted Company Name & Address from Company Table
$companyNameData = !empty(trim($data['company_name'] ?? '')) ? $data['company_name'] : 'Christian General Motors Inc.';
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


$user = $userModel->getUserByID($data['assigned_to']);
$createdByName = $user['file_as'] ?? null;

$getEmployeeWorkContactInformation = $userModel->getContactByID($data['assigned_to']);
$employeeID = $getEmployeeWorkContactInformation['contact_id'] ?? null;

$getEmployeeWorkContactInformation = $employeeModel->getEmployeeWorkContactInformation($employeeID);
$workEmail = $getEmployeeWorkContactInformation['email'] ?? null;
$workMobile = $getEmployeeWorkContactInformation['mobile'] ?? null;

class BlackThemedQuotationPDF extends TCPDF {
    public $footerCompanyName = 'Christian General Motors Inc.';
    public $footerPhone = '[Telephone]';
    public $footerEmail = '[Email]';
    public $companyLogo = null;
    public $resolvedPlaceholder2 = null;

    // Setter method for company details
    public function setCompanyDetails($companyDetails) {
        $this->footerCompanyName = $companyDetails['company_name'] ?? 'Christian General Motors Inc.';
        $this->companyLogo       = $companyDetails['company_logo'] ?? null;
        $this->footerPhone       = $companyDetails['telephone'] ?? '[Telephone]';
        $this->footerEmail       = $companyDetails['email'] ?? '[Email]';
    }

    public function setPlaceholderImage($path) {
        $this->resolvedPlaceholder2 = $path;
    }

    public function Header() {
        // Solid Deep Black top accent stripe
        $this->SetFillColor(15, 15, 15);
        $this->Rect(0, 0, $this->getPageWidth(), 2.5, 'F');
    }

    public function Footer() {
        $this->SetY(-25);
        $this->SetFont('helvetica', '', 8);
        $this->SetTextColor(120, 120, 120);
        $this->SetDrawColor(220, 220, 220);
        $this->SetLineWidth(0.3);
        $this->Line(15, $this->GetY(), 195, $this->GetY());
        $this->Ln(2);

        $footerY = $this->GetY();

        // Placeholder Image beside the thank you note
        if ($this->resolvedPlaceholder2) {
           $this->Image($this->resolvedPlaceholder2, 10, $footerY - 6, 0, 25, '', '', '', false, 300, '', false, false, 0, false, false, false);
        }

        // Footer Thank You Note on the left
        $this->SetXY(65, $footerY + 2);
        $this->SetFont('helvetica', 'B', 12);
        $this->SetTextColor(20, 20, 20);
        $this->Cell(52, 4, 'Thank you for your business interest!', 0, 0, 'L');

        // Company Details below the placeholder image
        $this->SetXY(64.5, $footerY + 8);
        $this->SetFont('helvetica', '', 8);
        $this->SetTextColor(100, 100, 100);
        $this->Cell(75, 4, ' ' . $this->footerCompanyName . ' Tel: ' . $this->footerPhone . ' Email: ' . $this->footerEmail, 0, 0, 'L');

        // Page Numbering right-aligned
        $this->SetXY(145, $footerY + 2);
        $this->SetFont('helvetica', '', 8);
        $this->SetTextColor(120, 120, 120);
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

$quoteDate = date('F d, Y', strtotime($data['created_at']));
$expDate   = !empty($data['expiration_date']) ? date('F d, Y', strtotime($data['expiration_date'])) : 'Valid for 30 Days';

$resolvedPlaceholder = resolveImagePath('assets/images/default/default-logo-placeholder.png');
$resolvedPlaceholder2 = resolveImagePath('assets/images/default/quotation-placeholder.png');
$resolvedLogo = resolveImagePath($data['company_logo']);
if (!$resolvedLogo) {
    $resolvedLogo = $resolvedPlaceholder;
}

ob_start();

$pdf = new BlackThemedQuotationPDF('P', 'mm', 'A4', true, 'UTF-8', false);
$pdf->setCompanyDetails($footerCompanyDetails);
$pdf->setPlaceholderImage($resolvedPlaceholder2);

$pdf->SetMargins(15, 12, 15);
$pdf->SetHeaderMargin(5);
$pdf->SetFooterMargin(18);
$pdf->SetAutoPageBreak(TRUE, 18);
$pdf->AddPage();

// ==========================================
// HEADER SECTION
// ==========================================
$startY = $pdf->GetY();

// Company Logo on the Left
$logoY = $startY;
if ($resolvedLogo) {
    $pdf->Image($resolvedLogo, 15, $logoY, 0, 10, '', '', 'T', true, 300, '', false, false, 0, false, false, false);
} else {
    $pdf->SetFont('helvetica', 'B', 20);
    $pdf->SetTextColor(15, 15, 15);
    $pdf->SetXY(15, $logoY);
    $pdf->Cell(60, 8, strtoupper($companyNameData), 0, 0, 'L');
}

$companyDetailsText = trim($fullCompanyAddress) . (!empty($data['comp_phone']) ? ' | Tel: ' . $data['comp_phone'] : '');
$pdf->SetXY(15, $logoY + 11);
$pdf->SetFont('helvetica', '', 8);
$pdf->SetTextColor(100, 100, 100);
$pdf->MultiCell(90, 3.5, $companyDetailsText, 0, 'L', false, 0, 15, $logoY + 11, true, 0, false, true, 0, 'T');

// Minimalist Clean Quotation Header Block on the Right Side
$pdf->SetFont('helvetica', 'B', 20);
$pdf->SetTextColor(15, 15, 15); // Solid Black
$pdf->SetXY(110, $logoY);
$pdf->Cell(85, 7, 'QUOTATION', 0, 1, 'R');

$pdf->SetFont('helvetica', '', 8.5);
$pdf->SetTextColor(100, 100, 100);
$pdf->SetXY(110, $logoY + 8);
$pdf->Cell(85, 4, 'Reference: ' . $referenceNo, 0, 1, 'R');

$headerBottomY = $logoY + 18;
$pdf->SetDrawColor(200, 200, 200);
$pdf->SetLineWidth(0.4);
$pdf->Line(15, $headerBottomY, 195, $headerBottomY);

// ==========================================
// SIDE-BY-SIDE SUMMARY PANELS
// ==========================================
$panelY = $headerBottomY + 4;
$panelWidth = 88;
$panelHeight = 32;

// --- CARD 1: PREPARED FOR (CLIENT) ---
$pdf->SetFillColor(250, 250, 250);
$pdf->SetDrawColor(210, 210, 210);
$pdf->Rect(15, $panelY, $panelWidth, $panelHeight, 'DF');

$pdf->SetXY(19, $panelY + 3);
$pdf->SetFont('helvetica', 'B', 8);
$pdf->SetTextColor(15, 15, 15);
$pdf->Cell($panelWidth - 8, 4, 'PREPARED FOR:', 0, 1, 'L');

$pdf->SetXY(19, $panelY + 8);
$pdf->SetFont('helvetica', 'B', 9);
$pdf->SetTextColor(20, 20, 20);
$pdf->Cell($panelWidth - 8, 4, $clientName, 0, 1, 'L');

// Separated address, phone, and email on each line
$pdf->SetXY(19, $panelY + 13);
$pdf->SetFont('helvetica', '', 8);
$pdf->SetTextColor(100, 100, 100);
$clientDetailsText = 'Address: ' . $fullLeadAddress . "\n" . 
                     'Phone: ' . ($data['lead_phone'] ?? 'N/A') . "\n" . 
                     'Email: ' . ($data['lead_email'] ?? 'N/A');
$pdf->MultiCell($panelWidth - 8, 3.5, $clientDetailsText, 0, 'L', false, 0, 19, $panelY + 13, true, 0, false, true, 0, 'T');

// --- CARD 2: ISSUED BY / DATE DETAILS ---
$card2X = 15 + $panelWidth + 4; 
$pdf->Rect($card2X, $panelY, $panelWidth, $panelHeight, 'DF');

$pdf->SetXY($card2X + 4, $panelY + 4);
$pdf->SetFont('helvetica', 'B', 8);
$pdf->SetTextColor(100, 100, 100);
$pdf->Cell(30, 5, 'DATE ISSUED:', 0, 0, 'L');
$pdf->SetFont('helvetica', '', 8.5);
$pdf->SetTextColor(20, 20, 20);
$pdf->Cell(45, 5, $quoteDate, 0, 1, 'L');

$pdf->SetXY($card2X + 4, $panelY + 8);
$pdf->SetFont('helvetica', 'B', 8);
$pdf->SetTextColor(100, 100, 100);
$pdf->Cell(30, 5, 'VALID UNTIL:', 0, 0, 'L');
$pdf->SetFont('helvetica', '', 8.5);
$pdf->SetTextColor(20, 20, 20);
$pdf->Cell(45, 5, $expDate, 0, 1, 'L');

// Added space before the pricing table
$pdf->Ln(20);

// ==========================================
// PRICING TABLE (Solid Black Theme)
// ==========================================
$rowStartY = $pdf->GetY();

$pdf->SetY($rowStartY + 4);

$pdf->SetDrawColor(200, 200, 200);
$pdf->SetLineWidth(0.35);
$pdf->SetFillColor(15, 15, 15);
$pdf->SetTextColor(255, 255, 255);
$pdf->SetFont('helvetica', 'B', 8.5);

$discount = (float)($data['discount'] ?? 0);

$colQtyWidth   = 14;
$colPriceWidth = 32;
$colTotalWidth = 32;
$colDiscWidth  = ($discount > 0) ? 30 : 0;
$colDescWidth  = ($discount > 0) ? 72 : 102; 

// Table Headers
$pdf->Cell($colDescWidth, 7, 'UNIT DETAILS', 1, 0, 'L', true);
$pdf->Cell($colQtyWidth, 7, 'QTY', 1, 0, 'C', true);
$pdf->Cell($colPriceWidth, 7, 'UNIT PRICE', 1, 0, 'R', true);
if ($discount > 0) {
    $pdf->Cell($colDiscWidth, 7, 'DISCOUNT', 1, 0, 'R', true);
}
$pdf->Cell($colTotalWidth, 7, 'AMOUNT', 1, 1, 'R', true);

$unitDescription = strtoupper($data['unit_description'] ?? 'TRUCK UNIT');
$yearModel       = $data['year_model'] ?? 'N/A';
$unitColor       = $data['color'] ?? 'N/A';
$unitPrice       = (float)($data['price'] ?? 0);
$quantity        = (int)($data['quantity'] ?? 1);
$total           = ($unitPrice - $discount) * $quantity;

$rowStartY = $pdf->GetY();
// Dynamically increase row height when discount column is visible to accommodate wrapped description and image
$rowHeight = ($discount > 0) ? 48 : 42;

$pdf->SetFillColor(255, 255, 255);
$pdf->Rect(15, $rowStartY, $colDescWidth, $rowHeight, 'DF');
$pdf->Rect(15 + $colDescWidth, $rowStartY, $colQtyWidth, $rowHeight, 'DF');
$pdf->Rect(15 + $colDescWidth + $colQtyWidth, $rowStartY, $colPriceWidth, $rowHeight, 'DF');

if ($discount > 0) {
    $pdf->Rect(15 + $colDescWidth + $colQtyWidth + $colPriceWidth, $rowStartY, $colDiscWidth, $rowHeight, 'DF');
    $pdf->Rect(15 + $colDescWidth + $colQtyWidth + $colPriceWidth + $colDiscWidth, $rowStartY, $colTotalWidth, $rowHeight, 'DF');
} else {
    $pdf->Rect(15 + $colDescWidth + $colQtyWidth + $colPriceWidth, $rowStartY, $colTotalWidth, $rowHeight, 'DF');
}

$pdf->SetTextColor(20, 20, 20);

// Description Texts with MultiCell Wrapping Support
$pdf->SetXY(17, $rowStartY + 3);
$pdf->SetFont('helvetica', 'B', 9);
$pdf->SetTextColor(15, 15, 15);
$pdf->MultiCell($colDescWidth - 4, 4.5, $unitDescription, 0, 'L', false, 1, 17, $rowStartY + 3, true);

$pdf->SetXY(17, $pdf->GetY() + 1);
$pdf->SetFont('helvetica', '', 8);
$pdf->SetTextColor(100, 100, 100);
$pdf->Cell($colDescWidth - 4, 4, 'Model Year: ' . $yearModel . '   |   Exterior Color: ' . $unitColor, 0, 1, 'L');

$resolvedTruckImage = resolveImagePath($data['quotation_image'] ?? '');

if (!$resolvedTruckImage && !empty($data['subcategory_default_image'])) {
    $resolvedTruckImage = resolveImagePath($data['subcategory_default_image']);
}

if ($resolvedTruckImage) {
    // Position image dynamically below the text to prevent any overlap
    $imageY = max($rowStartY + 17, $pdf->GetY() + 2);
    $pdf->Image($resolvedTruckImage, 17, $imageY, 42, 20, '', '', '', false, 300, '', false, false, 0, 'CM');
}

$currentX = 15 + $colDescWidth;

$pdf->SetXY($currentX, $rowStartY + 4);
$pdf->SetFont('helvetica', '', 8.5);
$pdf->SetTextColor(20, 20, 20);
$pdf->Cell($colQtyWidth, 5, $quantity, 0, 0, 'C');

$currentX += $colQtyWidth;
$pdf->SetXY($currentX, $rowStartY + 4);
$pdf->Cell($colPriceWidth - 3, 5, number_format($unitPrice, 2), 0, 0, 'R');

if ($discount > 0) {
    $currentX += $colPriceWidth;
    $pdf->SetXY($currentX, $rowStartY + 4);
    $pdf->Cell($colDiscWidth - 3, 5, number_format($discount, 2), 0, 0, 'R');
    $currentX += $colDiscWidth;
} else {
    $currentX += $colPriceWidth;
}

$pdf->SetXY($currentX, $rowStartY + 4);
$pdf->SetFont('helvetica', 'B', 9);
$pdf->SetTextColor(15, 15, 15);
$pdf->Cell($colTotalWidth - 3, 5, number_format($total, 2), 0, 1, 'R');

$pdf->SetY($rowStartY + $rowHeight + 4);

// ==========================================
// INCLUSIONS & DELIVERY TERMS GRID
// ==========================================
$inclusions = !empty(trim($data['inclusions'] ?? '')) ? $data['inclusions'] : 'Standard factory inclusions apply.';
$address    = !empty(trim($data['delivery_address'] ?? '')) ? $data['delivery_address'] : 'As specified per client requirement';
$conditions = !empty(trim($data['delivery_conditions'] ?? '')) ? $data['delivery_conditions'] : 'Standard delivery terms apply';
$instruc    = !empty(trim($data['delivery_instructions'] ?? '')) ? $data['delivery_instructions'] : 'Coordinate prior to dispatch';
$remarks    = !empty(trim($data['remarks'] ?? '')) ? $data['remarks'] : 'None';

$termsHtml = '
<table border="1" cellpadding="5" cellspacing="0" style="width: 100%; font-size: 8pt; border-color: #dcdcdc;">
    <tr bgcolor="#f5f5f5" style="color: #0f0f0f;">
        <th width="45%" style="font-weight: bold; color: #0f0f0f;">INCLUSIONS & PACKAGES</th>
        <th width="55%" style="font-weight: bold; color: #0f0f0f;">DELIVERY & FULFILLMENT DETAILS</th>
    </tr>
    <tr>
        <td rowspan="3" valign="top" style="color: #333333;">' . nl2br(htmlspecialchars($inclusions)) . '</td>
        <td style="color: #333333;"><b>Delivery Address:</b> ' . htmlspecialchars($address) . '</td>
    </tr>
    <tr>
        <td style="color: #333333;"><b>Conditions:</b> ' . htmlspecialchars($conditions) . '</td>
    </tr>
    <tr>
        <td style="color: #333333;"><b>Instructions:</b> ' . htmlspecialchars($instruc) . '</td>
    </tr>
    <tr bgcolor="#f5f5f5">
        <td colspan="2" style="font-weight: bold; color: #0f0f0f;">SPECIAL REMARKS & NOTES</td>
    </tr>
    <tr>
        <td colspan="2" height="35" style="color: #333333;">' . nl2br(htmlspecialchars($remarks)) . '</td>
    </tr>
</table>';

$pdf->writeHTML($termsHtml, true, false, true, false, '');

$pdf->Ln(4);

// ==========================================
// SIGNATURE BLOCK
// ==========================================
$sigY = $pdf->GetY();

// Prepared By (Left Block)
$pdf->SetXY(15, $sigY);
$pdf->SetFont('helvetica', '', 8);
$pdf->SetTextColor(120, 120, 120);
$pdf->Cell(80, 4, 'PREPARED & SUBMITTED BY:', 0, 1, 'L');

$pdf->SetY($sigY + 9);
$pdf->SetFont('helvetica', 'B', 8.5);
$pdf->SetTextColor(15, 15, 15);
$pdf->Cell(80, 4, $createdByName, 0, 1, 'L');

$pdf->SetDrawColor(15, 15, 15);
$pdf->SetLineWidth(0.4);
$pdf->Line(15, $pdf->GetY() + 2, 95, $pdf->GetY() + 2);
$pdf->SetY($pdf->GetY() + 3);

$pdf->SetFont('helvetica', 'B', 8);
$pdf->SetTextColor(15, 15, 15);
$pdf->Cell(80, 4, 'Sales Representative', 0, 1, 'L');
$pdf->SetFont('helvetica', '', 8);
$pdf->SetTextColor(120, 120, 120);
$pdf->Cell(80, 4, $companyNameData, 0, 1, 'L');

if (!empty($workMobile)) {
    $pdf->Cell(80, 4, 'Mobile: ' . $workMobile, 0, 1, 'L');
}
if (!empty($workEmail)) {
    $pdf->Cell(80, 4, 'Email: ' . $workEmail, 0, 1, 'L');
}

// Conforme / Acceptance (Right Block)
$pdf->SetXY(115, $sigY);
$pdf->SetFont('helvetica', '', 8);
$pdf->SetTextColor(120, 120, 120);
$pdf->Cell(80, 4, 'CONFORME / CLIENT ACCEPTANCE:', 0, 1, 'L');

$pdf->SetY($sigY + 9);
$pdf->SetFont('helvetica', 'B', 8.5);
$pdf->SetX(115);
$pdf->SetTextColor(15, 15, 15);
$pdf->Cell(80, 4, strtoupper($clientName), 0, 1, 'L');

$pdf->SetDrawColor(15, 15, 15);
$pdf->Line(115, $pdf->GetY() + 2, 195, $pdf->GetY() + 2);
$pdf->SetY($pdf->GetY() + 3);

$pdf->SetFont('helvetica', 'B', 8);
$pdf->SetX(115);
$pdf->SetTextColor(15, 15, 15);
$pdf->Cell(80, 4, 'Authorized Signature over Printed Name', 0, 1, 'L');
$pdf->SetFont('helvetica', '', 8);
$pdf->SetX(115);
$pdf->SetTextColor(120, 120, 120);
$pdf->Cell(80, 4, 'Date Accepted: ____________________', 0, 1, 'L');

$pdf->Output('Vehicle_Quotation_Black.pdf', 'I');
ob_end_flush();
?>