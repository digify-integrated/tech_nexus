<?php    // Include TCPDF library
date_default_timezone_set('Asia/Manila');
    require('assets/libs/tcpdf2/tcpdf.php');

    // Create TCPDF instance
    $pdf = new TCPDF('P', 'mm', array(210, 297), true, 'UTF-8', false);

    // Disable header and footer
    $pdf->setPrintHeader(false);
    $pdf->setPrintFooter(false);

    // Set PDF metadata
    $pdf->SetCreator('CGMI');
    $pdf->SetAuthor('CGMI');
    $pdf->SetTitle('Sales Proposal');
    $pdf->SetSubject('Sales Proposal');

    // Set margins and auto page break
    $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
    $pdf->SetMargins(15, 15, 15);
    $pdf->SetHeaderMargin(5);
    $pdf->SetFooterMargin(10);
    $pdf->SetAutoPageBreak(TRUE, 15);

    // Add a page
    $pdf->AddPage();

    // Add content
    $pdf->SetFont('times', '', 12);
    $pdf->MultiCell(0, 0, '<b>THE MANAGER</b>', 0, 'L', 0, 1, '', '', true, 0, true, true, 0);
    $pdf->Ln(2);
    $pdf->MultiCell(0, 0, '<b>CHRISTIAN GENERAL MOTORS, INC.</b>', 0, 'L', 0, 1, '', '', true, 0, true, true, 0);
    $pdf->Ln(2);
    $pdf->MultiCell(0, 0, '<b>CABANATUAN CITY</b>', 0, 'L', 0, 1, '', '', true, 0, true, true, 0);
    $pdf->Ln(10);
    $pdf->MultiCell(0, 0, '<b>AUTHORIZATION AND UNDERTAKING</b>', 0, 'C', 0, 1, '', '', true, 0, true, true, 0);
    $pdf->Ln(5);
    $pdf->MultiCell(0, 0, 'This is to authorize your company to deposit any of the checks I issued upon due date of my monthly installment. In case the check is dishonored by the drawee bank for whatever reason, I agreed to pay', 0, 'J', 0, 1, '', '', true, 0, false, true, 0);
    $pdf->MultiCell(0, 0, 'additional 3% late payment penalty.', 0, 'L', 0, 1, '', '', true, 0, false, true, 0);
    $pdf->Ln(5);
    $pdf->MultiCell(0, 0, 'If my check is dishonored due to "Account Closed", I hereby undertake to replace the remaining checks to cover my monthly installment without need of demand or notice, otherwise, my entire outstanding', 0, 'J', 0, 1, '', '', true, 0, false, true, 0);
    $pdf->MultiCell(0, 0, 'obligation will become due and demandable. I also agreed to pay additional 3% late payment penalty.', 0, 'L', 0, 1, '', '', true, 0, false, true, 0);
    $pdf->Ln(5);
    $pdf->MultiCell(0, 0, 'Finally, I hereby authorize the company to indicate any details on the face of the checks provided it is', 0, 'J', 0, 1, '', '', true, 0, false, true, 0);
    $pdf->MultiCell(0, 0, 'based on the agreed terms and conditions as stipulated in the Deed of Conditional Sale.', 0, 'L', 0, 1, '', '', true, 0, false, true, 0);
    $pdf->Ln(10);
    $pdf->Cell(80, 4, '', 'B', 0, 'C', 0, '', 1);
    $pdf->Cell(25, 4, '', 0, 0, 'C');
    $pdf->Ln(5);
    $pdf->SetFont('times', '', 10);
    $pdf->Cell(80, 5, 'NAME OF CUSTOMER OVER PRINTED NAME', 0, 0, 'C');

    // Output the PDF to the browser
    $pdf->Output('authorization.pdf', 'I');
?>