<?php 
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Load TCPDF library
require('assets/libs/tcpdf2/tcpdf.php');

function generateProductQRCodePDF($product_id, $description, $stock_number) {
    // Initialize TCPDF object
    $pdf = new TCPDF();
    $pdf->setPrintHeader(false);
    $pdf->setPrintFooter(false);
    
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
    
    // Position and size for QR code
    $qrX = 20; // X position for QR code
    $qrY = 20; // Y position for QR code
    $qrSize = 50; // Size of the QR code
    
    // Create the QR code image
    $pdf->write2DBarcode($qrData, 'QRCODE,H', $qrX, $qrY, $qrSize, $qrSize); // Adjust the position and size as needed
    
    // Set the width for the description box
    $description_width = 120;
    
    // Position for description and stock number to the right of the QR code
    $descriptionX = $qrX + $qrSize + 5; // X position to the right of the QR code
    $descriptionY = $qrY + ($qrSize / 2) - 2.5; // Y position to center the text vertically with the QR code
    
    // Set font for the description and stock number
    $pdf->SetFont('helvetica', '', 10);
    
    // Position and add the stock number below the description, keeping it centered
    $pdf->SetXY($descriptionX, $descriptionY); // Set position for the description
    $pdf->Cell(0, 5, 'Stock Number: ' . $stock_number, 0, 1, 'L');
    // Set the description text, wrapping it inside the box
    $pdf->SetXY($descriptionX, $pdf->GetY()); // Y position for the stock number (below the description)
    $pdf->MultiCell($description_width, 5, 'Description: ' . $description, 0, 'L', 0, 1, '', '', true);
    
    
    // Output PDF to browser
    $pdf->Output('product_qr_code.pdf', 'I'); // 'I' for inline (opens in browser), 'D' for download
}

if (isset($_GET['product_id'])) {
    // Call the function with dynamic data
    generateProductQRCodePDF($_GET['product_id'], $_GET['description'], $_GET['stock_number']);
}
?>
