<?php 
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Load TCPDF library
require('assets/libs/tcpdf2/tcpdf.php');
require_once 'config/config.php';
require_once 'session.php';
require_once 'model/database-model.php';
require_once 'model/product-model.php';
require_once 'model/product-subcategory-model.php';

$databaseModel = new DatabaseModel();
$productModel = new ProductModel($databaseModel);
$productSubcategoryModel = new ProductSubcategoryModel($databaseModel);
$productIDs = explode(',', $_GET['id']);

// Define a function to truncate text
function truncateText($text, $maxLength) {
    return (mb_strlen($text) > $maxLength) ? mb_substr($text, 0, $maxLength) . '...' : $text;
}

// Define a function to create the QR code PDF
function generateProductQRCodePDF($products) {
    // Initialize TCPDF object
    $pdf = new TCPDF();
    $pdf->setPrintHeader(false);
    $pdf->setPrintFooter(false);
    
    // Set document information
    $pdf->SetCreator(PDF_CREATOR);
    $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
    $pdf->SetTitle('Product QR Codes');
    
    // Add a page
    $pdf->AddPage();
    
    // Set font
    $pdf->SetFont('helvetica', '', 10);
    $pdf->SetAutoPageBreak(TRUE, 15);
    
    // Page dimensions
    $pageWidth = $pdf->getPageWidth();
    $pageHeight = $pdf->getPageHeight();
    $margin = 10;
    
    // Grid settings
    $columns = 3; // Number of QR codes per row
    $rows = 3; // Number of QR codes per column
    $qrSize = ($pageWidth - 2 * $margin) / $columns; // Adjust size to fit columns
    $cellPadding = 10; // Padding inside each cell
    $rowSpacing = 17; // Extra spacing between rows
    
    // Positioning
    $xStart = $margin;
    $yStart = $margin;
    
    $currentRow = 0;
    $currentCol = 0;

    foreach ($products as $product) {
        // Extract product details
        $product_id = $product['id'];
        $description = truncateText($product['description'], 100); // Limit description to 30 characters
        $stock_number = $product['stock_number'];
        
        // QR code data
        $qrData = "BEGIN:VCARD\n";
        $qrData .= "VERSION:3.0\n";
        $qrData .= "PRODUCT_ID:$product_id\n";
        $qrData .= "DESCRIPTION:$description\n";
        $qrData .= "STOCK_NUMBER:$stock_number\n";
        $qrData .= "END:VCARD";
        
        // Calculate position
        $x = $xStart + $currentCol * $qrSize;
        $y = ($yStart + $currentRow * ($qrSize + $cellPadding + $rowSpacing));
        
        // Draw QR code
        $pdf->write2DBarcode($qrData, 'QRCODE,H', $x, $y, $qrSize - $cellPadding, $qrSize - $cellPadding);
        
        // Add product details below QR code
        $pdf->SetXY($x, ($y + $qrSize) - 5);
        $pdf->SetFont('times', '', 10);
        $pdf->MultiCell($qrSize - $cellPadding, 5, "Stock: $stock_number\nDesc: $description", 0, 'L', 0, 1, '', '', true);
        
        // Update grid position
        $currentCol++;
        if ($currentCol >= $columns) {
            $currentCol = 0;
            $currentRow++;
        }
        
        // Add a new page if grid is filled
        if ($currentRow >= $rows) {
            $pdf->AddPage();
            $currentRow = 0;
        }
    }
    
    // Output the PDF
    $pdf->Output('product_qr_codes.pdf', 'I'); // 'I' for inline viewing
}

// Example input
$products = [];
foreach ($productIDs as $id) {
    $productDetails = $productModel->getProduct($id);
    $description = $productDetails['description'];
    $productSubategoryID = $productDetails['product_subcategory_id'];

    $productSubcategoryDetails = $productSubcategoryModel->getProductSubcategory($productSubategoryID);
    $productSubcategoryCode = $productSubcategoryDetails['product_subcategory_code'] ?? null;

    $stockNumber = str_replace($productSubcategoryCode, '', $productDetails['stock_number']);
    $fullStockNumber = $productSubcategoryCode . $stockNumber;

    $products[] = [
        'id' => $id,
        'description' => $description,
        'stock_number' => $fullStockNumber
    ];
}

// Generate the PDF
generateProductQRCodePDF($products);
?>
