<?php
    error_reporting(E_ALL);
    ini_set('display_errors', 1);

    // Load TCPDF library
    require('assets/libs/tcpdf2/tcpdf.php');

    // Load required files
    require_once 'config/config.php';
    require_once 'session.php';
    require_once 'model/database-model.php';
    require_once 'model/system-model.php';
    require_once 'model/customer-model.php';
    require_once 'model/internal-dr-model.php';

    // Initialize database model
    $databaseModel = new DatabaseModel();

    // Initialize system model
    $systemModel = new SystemModel();

    // Initialize sales proposal model
    $internalDRModel = new InternalDRModel($databaseModel);

    if(isset($_GET['id'])){
        if(empty($_GET['id'])){
          header('location: internal-dr.php');
          exit;
        }
        
        $internalDRID = $_GET['id'];

        $checkInternalDRExist = $internalDRModel->checkInternalDRExist($internalDRID);
        $total = $checkInternalDRExist['total'] ?? 0;

        if($total == 0){
            header('location: 404.php');
            exit;
        }

        $internalDRDetails = $internalDRModel->getInternalDR($internalDRID); 
        $releaseTo = $internalDRDetails['release_to'];
        $releaseMobile = $internalDRDetails['release_mobile'];
        $releaseAddress = $internalDRDetails['release_address'];
        $drNumber = $internalDRDetails['dr_number'];
        $drType = $internalDRDetails['dr_type'];
        $stockNumber = $internalDRDetails['stock_number'];
        $productDescription = $internalDRDetails['product_description'];
        $engineNumber = $internalDRDetails['engine_number'];
        $chassisNumber = $internalDRDetails['chassis_number'];
        $plateNumber = $internalDRDetails['plate_number'];
        $unitImage = $systemModel->checkImage($internalDRDetails['unit_image'], 'default');
        $createdDate = $systemModel->checkDate('default', $internalDRDetails['created_date'] ?? null, '', 'd-M-Y', '');

        $extension = pathinfo($unitImage, PATHINFO_EXTENSION);
        
    
    }

    ob_start();

    // Create TCPDF instance
    $pdf = new TCPDF('P', 'mm', array(215.9, 330.2), true, 'UTF-8', false);

    // Disable header and footer
    $pdf->setPrintHeader(false);
    $pdf->setPrintFooter(false);

    // Set PDF metadata
    $pdf->SetCreator('CGMI');
    $pdf->SetAuthor('CGMI');
    $pdf->SetTitle('DR Receipt');
    $pdf->SetSubject('DR Receipt');

    // Set margins and auto page break
    $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
    $pdf->SetMargins(12, 12, 12);
    $pdf->SetHeaderMargin(5);
    $pdf->SetFooterMargin(10);
    $pdf->SetAutoPageBreak(TRUE, 15);

    // Add a page
    $pdf->AddPage();

    // Add content
    $pdf->SetFont('times', '', 11);
    $pdf->Ln(25);
    $pdf->Cell(18, 4, '', 0, 0, 'L', 0, '', 1);
    $pdf->Cell(100, 4, strtoupper($releaseTo), 0, 0, 'L', 0, '', 1);
    $pdf->Cell(25, 4, '     ', 0, 0 , 'L', '', 1);
    $pdf->Cell(30, 4, strtoupper(date('d-M-Y')), 0, 0, 'L', 0, '', 1);
    $pdf->Cell(1, 4, '     ', 0, 0 , 'L', '', 1);
    $pdf->Cell(10, 4, $drNumber, 0, 0, 'L', 0, '', 1);
    $pdf->Ln(8);
    $pdf->Cell(18, 4, '', 0, 0, 'L', 0, '', 1);
    $pdf->Cell(100, 4, '-', 0, 0, 'L', 0, '', 1);
    $pdf->Cell(25, 4, '     ', 0, 0 , 'L', '', 1);
    $pdf->Cell(30, 4, strtoupper(date('d-M-Y')), 0, 0, 'L', 0, '', 1);
    $pdf->Ln(8);
    $pdf->Cell(18, 4, '', 0, 0, 'L', 0, '', 1);
    $pdf->Cell(100, 4, strtoupper($releaseAddress), 0, 0, 'L', 0, '', 1);
    $pdf->Ln(18);
    $pdf->SetTextColor(255, 0, 0);
    $pdf->MultiCell(100, 4, '<b><u>'. $stockNumber .'</u></b>', 0, 'L', false, 0, '', '', true, 0, true, true, 0);
    $pdf->SetTextColor(0, 0, 0);
    $pdf->Image($unitImage, 80, 67, 45, 45, '', '', '', true, 150, '', false, false, 1, false, false, false, false);
    $pdf->Ln(5);
    $pdf->SetFont('times', '', 8);
    $pdf->MultiCell(60, 4, $productDescription, 0, 'L', false, 0, '', '', true);
    $pdf->SetFont('times', '', 11);
    $pdf->MultiCell(20, 4, '', 0, 'L', false, 0, '', '', true);
    $pdf->Cell(35, 4, '', 0, 0, 'L', false);
    $pdf->MultiCell(20, 4, $engineNumber, 0, 'L', false, 0, '', '', true);
    $pdf->Cell(2, 4, '', 0, 0, 'L', false);
    $pdf->MultiCell(20, 4, $chassisNumber, 0, 'L', false, 0, '', '', true);
    $pdf->Cell(2, 4, '', 0, 0, 'L', false);
    $pdf->MultiCell(20, 4, $plateNumber, 0, 'L', false, 1, '', '', true);


    
    $pdf->Ln(27);
    $pdf->Cell(100, 4, '', 0, 0, 'L', 0, '', 1);
    $pdf->Cell(15, 4, '', 0, 0, 'L', 0, '', 1);
    $pdf->Cell(80, 4, 'CHECKED BY: ______________________', 0, 0, 'L', 0, '', 1);
    $pdf->Ln(18);
    $pdf->SetFont('times', '', 7);
    $pdf->SetTextColor(255, 0, 0);
    $pdf->Cell(67, 4, '', 0, 0, 'L', 0, '', 1);
    $pdf->MultiCell(136, 4, '<b><i>REMARKS: THE COMPANY IS ACTING AS A SELLING AGENT (CONSIGNEE) ONLY OF</i></b>', 0, 'L', false, 0, '', '', true, 0, true, true, 0);
    $pdf->Ln(5);
    $pdf->Cell(67, 4, '', 0, 0, 'L', 0, '', 1);
    $pdf->MultiCell(136, 4, '<b><i>THE CONSIGNOR, THEREFORE, SALES SHALL BE CLAIMED AGAINST THE</i></b>', 0, 'L', false, 0, '', '', true, 0, true, true, 0);
    $pdf->Ln(5);
    $pdf->Cell(67, 4, '', 0, 0, 'L', 0, '', 1);
    $pdf->MultiCell(136, 4, '<b><i>CONSIGNOR</i></b>', 0, 'L', false, 0, '', '', true, 0, true, true, 0);

    // Output the PDF to the browser
    $pdf->Output('dr-receipt.pdf', 'I');
    ob_flush();
?>