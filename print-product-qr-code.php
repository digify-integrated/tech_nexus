<?php 
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Load TCPDF library
require('assets/libs/tcpdf2/tcpdf.php');

function generateProductQRCodePDF($product_id, $description, $stock_number) {
    // Initialize TCPDF object
    $pdf = new TCPDF();
    
    // Set document information
    $pdf->SetCreator(PDF_CREATOR);
    $pdf->SetTitle('Product QR Code');
    
    // Add a page
    $pdf->AddPage();
    
    // Set font for the document
    $pdf->SetFont('helvetica', '', 12);
    
    // Insert the QR code
    $qrData = 'BEGIN:VCARD\r\n';
    $qrData .= 'VERSION:3.0\r\n';
    $qrData .= 'PRODUCT_ID:' . $product_id . '\r\n';
    $qrData .= 'DESCRIPTION:' . $description . '\r\n';
    $qrData .= 'STOCK_NUMBER:' . $stock_number . '\r\n';
    $qrData .= 'END:VCARD';
    
    // Create the QR code image
    $pdf->write2DBarcode($qrData, 'QRCODE,H', 80, 20, 50, 50); // Adjust the position and size as needed
    
    // Move to the next line below the QR code to add product information
    $pdf->Ln(60); // Adjust the distance between the QR code and the text
    
    // Set a new font for the product information
    $pdf->SetFont('helvetica', 'B', 12); // Bold for the headers
    $pdf->Cell(0, 10, 'Product Information', 0, 1, 'C');
    
    // Set a regular font for the details
    $pdf->SetFont('helvetica', '', 8);
    
    // Add the product details below the QR code
    $pdf->Cell(0, 5, 'Product ID: ' . $product_id, 0, 1, 'C');
    
    // Center the description with a fixed width and wrap text
    $pdf->SetFont('helvetica', '', 8);
    $description_width = 50;  // Set the width for the description
    $pdf->SetXY(80, $pdf->GetY()); // Adjust X position for centering
    $pdf->MultiCell($description_width, 5, 'Description: ' . $description, 0, 'C', 0, 1, '', '', true);
    
    // Add stock number below the description
    $pdf->Cell(0, 5, 'Stock Number: ' . $stock_number, 0, 1, 'C');
    
    // Output PDF to browser
    $pdf->Output('product_qr_code.pdf', 'I'); // 'I' for inline (opens in browser), 'D' for download
}

if (isset($_GET['product_id'])) {
    // Call the function with dynamic data
    generateProductQRCodePDF($_GET['product_id'], $_GET['description'], $_GET['stock_number']);
}
?>
