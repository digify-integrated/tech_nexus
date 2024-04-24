<?php
    // Include TCPDF library
    require('assets/libs/tcpdf2/tcpdf.php');

    // Create TCPDF instance
    $pdf = new TCPDF('P', 'mm', array(215.9, 330.2), true, 'UTF-8', false);

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

    // Set font
    $pdf->SetFont('times', '', 12);

    // Add a page
    $pdf->AddPage();

    // Add content
    $pdf->SetTextColor(255, 0, 0);
    $pdf->MultiCell(0, 0, '<b>MAHALAGANG PAALALA</b>', 0, 'C', 0, 1, '', '', true, 0, true, true, 0);
    $pdf->Ln(5);
    $pdf->MultiCell(0, 0, '<b>PAGBAYAD SA BANGKO</b>', 0, 'L', 0, 1, '', '', true, 0, true, true, 0);
    $pdf->Ln(5);
    $pdf->SetTextColor(0, 0, 0);
    $pdf->MultiCell(0, 0, 'IDEPOSITO ANG INYONG BAYAD SA ITINAKDANG ACCOUNT NG AMING KUMPANYA NA NAKAPANGALAN SA “CHRISTIAN GENERAL MOTORS INC", "N E TRUCK BUILDERS CORPORATION", O “GRACE C. BAGUISA”. PAKITINGNAN PO NG MABUTI KUNG ITO ANG ', 0, 'J', 0, 1, '', '', true, 0, false, true, 0);
    $pdf->MultiCell(0, 0, 'NAKASULAT NA PANGALAN SA INYONG DEPOSIT SLIP.', 0, 'L', 0, 1, '', '', true, 0, false, true, 0);
    $pdf->Ln(5);
    $pdf->SetTextColor(255, 0, 0);
    $pdf->MultiCell(0, 0, 'ANUMANG BAYAD SA PAMAMAGITAN NG LBC, JRS, O IBA PANG KATULAD NA KUMPANYA', 0, 'J', 0, 1, '', '', true, 0, false, true, 0);
    $pdf->MultiCell(0, 0, 'AY HINDI PINAHIHINTULUTAN NG AMING KUMPANYA.', 0, 'L', 0, 1, '', '', true, 0, false, true, 0);
    $pdf->Ln(5);
    $pdf->SetTextColor(0, 0, 0);
    $pdf->MultiCell(0, 0, 'KUNIN LAMANG PO ANG AMING BANK ACCOUNTS KAY MR. CHRISTIAN EDWARD', 0, 'J', 0, 1, '', '', true, 0, false, true, 0);
    $pdf->MultiCell(0, 0, 'BAGUISA SA CELLPHONE NO. 0919-062-6563.', 0, 'L', 0, 1, '', '', true, 0, false, true, 0);
    $pdf->Ln(5);
    $pdf->SetTextColor(255, 0, 0);
    $pdf->MultiCell(0, 0, '<b>TAMANG PARAAN NG PAGBABAYAD SA BANGKO</b>', 0, 'L', 0, 1, '', '', true, 0, true, true, 0);
    $pdf->Ln(5);
    $pdf->SetTextColor(0, 0, 0);
    $pdf->MultiCell(0, 0, 'ISULAT SA DEPOSIT SLIP, INYONG KOPYA AT KOPYA NG BANGKO, ANG INYONG', 0, 'J', 0, 1, '', '', true, 0, false, true, 0);
    $pdf->MultiCell(0, 0, 'PANGALAN PARA MAIPROSESO NAMIN NG TAMA ANG INYONG BAYAD.', 0, 'L', 0, 1, '', '', true, 0, false, true, 0);
    $pdf->Ln(5);
    $pdf->MultiCell(0, 0, 'PAGKATAPOS I-TEXT, PAKI-SCAN AT PAKI-EMAIL ANG DEPOSIT SLIP SA', 0, 'J', 0, 1, '', '', true, 0, false, true, 0);
    $pdf->MultiCell(0, 0, 'collections@christianmotors.ph SA ARAW NG PAGKAKADEPOSITO O SA SUSUNOD NA ARAW.', 0, 'L', 0, 1, '', '', true, 0, false, true, 0);
    $pdf->Ln(5);
    $pdf->MultiCell(0, 0, 'PAKITAGO ANG DEPOSIT SLIP AT PAKIDALA ANG ORIHINAL NA KOPYA SA AMING', 0, 'J', 0, 1, '', '', true, 0, false, true, 0);
    $pdf->MultiCell(0, 0, 'KUMPANYA. MANGHINGI PO NG OFFICIAL RECEIPT SA AMING KAHERA KAPALIT ANG ORIHINAL NA DEPOSIT SLIP.', 0, 'L', 0, 1, '', '', true, 0, false, true, 0);
    $pdf->Ln(5);
    $pdf->MultiCell(0, 0, 'KUNG SAKALI PO NA HINDI KAYO MAKAKAPUNTA SA AMING OPISINA, SIGURADUHIN PO NA ANG INYONG DEPOSIT SLIPS AY NAKATAGO PARA MAGAMIT NA BATAYAN NG', 0, 'J', 0, 1, '', '', true, 0, false, true, 0);
    $pdf->MultiCell(0, 0, 'INYONG PAGKAKABAYAD SA HINAHARAP.', 0, 'L', 0, 1, '', '', true, 0, false, true, 0);
    $pdf->Ln(5);
    $pdf->SetTextColor(255, 0, 0);
    $pdf->MultiCell(0, 0, '<b>BABALA</b>', 0, 'L', 0, 1, '', '', true, 0, true, true, 0);
    $pdf->Ln(5);
    $pdf->SetTextColor(0, 0, 0);
    $pdf->MultiCell(0, 0, 'MANGYARI PO LAMANG NA IPAGBIGAY ALAM SA AMING KUMPANYA KUNG MAYROONG MAG-UUTOS SA INYO NA BAYARAN ANG INYONG ACCOUNT MALIBAN SA ITINAKDANG', 0, 'J', 0, 1, '', '', true, 0, false, true, 0);
    $pdf->MultiCell(0, 0, 'DEPOSIT ACCOUNTS NG AMING KUMPANYA SA CELLPHONE NOS. 0919-062-6563/0962-098-4672.', 0, 'L', 0, 1, '', '', true, 0, false, true, 0);
    $pdf->Ln(5);
    $pdf->MultiCell(0, 0, 'ANG AMING KUMPANYA AY WALANG PANANAGUTAN SA ANUMANG BAYAD NA INYONG', 0, 'J', 0, 1, '', '', true, 0, false, true, 0);
    $pdf->MultiCell(0, 0, 'IPINADALA SA IBANG PARAAN.', 0, 'L', 0, 1, '', '', true, 0, false, true, 0);
    $pdf->Ln(5);
    $pdf->MultiCell(0, 0, 'MARAMING SALAMAT PO SA INYONG PATULOY NA PAGTANGKILIK.', 0, 'L', 0, 1, '', '', true, 0, false, true, 0);
    $pdf->Ln(5);
    $pdf->MultiCell(0, 0, 'TINANGGAP AT LUBOS NA NAUUNAWAAN:', 0, 'L', 0, 1, '', '', true, 0, false, true, 0);
    $pdf->Ln(10);
    $pdf->Cell(80, 4, '', 'B', 0, 'C', 0, '', 1);
    $pdf->Cell(25, 4, '', 0, 0, 'C');
    $pdf->Ln(5);
    $pdf->Cell(80, 5, 'LAGDA / PETSA', 0, 0, 'C');

    // Add a page
    $pdf->AddPage();

    // Add content
    $pdf->SetTextColor(255, 0, 0);
    $pdf->MultiCell(0, 0, '<b>IMPORTANT REMINDERS</b>', 0, 'C', 0, 1, '', '', true, 0, true, true, 0);
    $pdf->Ln(5);
    $pdf->MultiCell(0, 0, '<b>ON-LINE DEPOSITS OR PAYMENTS</b>', 0, 'L', 0, 1, '', '', true, 0, true, true, 0);
    $pdf->Ln(5);
    $pdf->SetTextColor(0, 0, 0);
    $pdf->MultiCell(0, 0, 'FOR ALL ON-LINE PAYMENTS, KINDLY ENSURE THAT ONLY THE DESIGNATED BANK ACCOUNTS WITH ACCOUNT NAMES “CHRISTIAN GENERAL MOTORS, INC.", "N E TRUCK ', 0, 'J', 0, 1, '', '', true, 0, false, true, 0);
    $pdf->MultiCell(0, 0, 'BUILDERS CORPORATION” OR “GRACE BAGUISA” WILL BE REFLECTED IN THE BANK DEPOSIT SLIPS.', 0, 'L', 0, 1, '', '', true, 0, false, true, 0);
    $pdf->Ln(5);
    $pdf->SetTextColor(255, 0, 0);
    $pdf->MultiCell(0, 0, 'OTHER MODE OF PAYMENTS SUCH AS BUT NOT LIMITED TO LBC, JRS OR ANY OTHER', 0, 'J', 0, 1, '', '', true, 0, false, true, 0);
    $pdf->MultiCell(0, 0, 'MONEY TRANSFER COMPANIES ARE NOT AUTHORIZED BY THE COMPANY.', 0, 'L', 0, 1, '', '', true, 0, false, true, 0);
    $pdf->Ln(5);
    $pdf->SetTextColor(0, 0, 0);
    $pdf->MultiCell(0, 0, 'KINDLY SECURE THE ACCOUNT NUMBER OF THE COMPANY’S DESIGNATED BANK', 0, 'J', 0, 1, '', '', true, 0, false, true, 0);
    $pdf->MultiCell(0, 0, 'ACCOUNTS ONLY FROM MR. CHRISTIAN EDWARD BAGUISA WITH CONTACT NO. 0919-062-6563.', 0, 'L', 0, 1, '', '', true, 0, false, true, 0);
    $pdf->Ln(5);
    $pdf->SetTextColor(255, 0, 0);
    $pdf->MultiCell(0, 0, '<b>PROCEDURES IN MAKING ON-LINE PAYMENTS THRU BANKS</b>', 0, 'L', 0, 1, '', '', true, 0, true, true, 0);
    $pdf->Ln(5);
    $pdf->SetTextColor(0, 0, 0);
    $pdf->MultiCell(0, 0, 'KINDLY INDICATE IN THE BANK DEPOSIT SLIP, BOTH IN DEPOSITOR’S COPY AND BANK', 0, 'J', 0, 1, '', '', true, 0, false, true, 0);
    $pdf->MultiCell(0, 0, 'COPY YOUR NAME FOR PROPER POSTING OF PAYMENT.', 0, 'L', 0, 1, '', '', true, 0, false, true, 0);
    $pdf->Ln(5);
    $pdf->MultiCell(0, 0, 'PLEASE TEXT YOUR NAME, NAME OF BANK AND BRANCH WHERE THE ON-LINE DEPOSIT ,', 0, 'J', 0, 1, '', '', true, 0, false, true, 0);
    $pdf->MultiCell(0, 0, 'WAS MADE AMOUNT OF DEPOSIT AND DATE OF PAYMENT TO CELLPHONE NOS. 0916-062-6563/0962-098-4672.', 0, 'L', 0, 1, '', '', true, 0, false, true, 0);
    $pdf->Ln(5);
    $pdf->MultiCell(0, 0, 'AFTER TEXTING, KINDLY SCAN AND E-MAIL THE DEPOSIT SLIP TO collections@christianmotors.ph', 0, 'J', 0, 1, '', '', true, 0, false, true, 0);
    $pdf->MultiCell(0, 0, 'ON THE SAME DAY THE DEPOSIT IS MADE OR THE FOLLOWING DAY.', 0, 'L', 0, 1, '', '', true, 0, false, true, 0);
    $pdf->Ln(5);
    $pdf->MultiCell(0, 0, 'KINDLY SAFEKEEP THE BANK DEPOSIT SLIP AND BRING THE ORIGINAL COPY UPON VISIT TO OUR COMPANY. REQUEST THE CORRESPONDING OFFICIAL RECEIPT ONLY TO OUR', 0, 'J', 0, 1, '', '', true, 0, false, true, 0);
    $pdf->MultiCell(0, 0, 'DESIGNATED CASHIER UPON SURRENDER OF BANK DEPOSIT SLIP.', 0, 'L', 0, 1, '', '', true, 0, false, true, 0);
    $pdf->Ln(5);
    $pdf->MultiCell(0, 0, 'IN CASE YOU WILL NOT BE ABLE TO VISIT OUR COMPANY, PLEASE ENSURE THAT ALL', 0, 'J', 0, 1, '', '', true, 0, false, true, 0);
    $pdf->MultiCell(0, 0, 'YOUR BANK DEPOSIT SLIPS ARE SAFEKEPT FOR FUTURE REFERENCE.', 0, 'L', 0, 1, '', '', true, 0, false, true, 0);
    $pdf->Ln(5);
    $pdf->SetTextColor(255, 0, 0);
    $pdf->MultiCell(0, 0, '<b>WARNINGS</b>', 0, 'L', 0, 1, '', '', true, 0, true, true, 0);
    $pdf->Ln(5);
    $pdf->SetTextColor(0, 0, 0);
    $pdf->MultiCell(0, 0, 'PLEASE ADVISE OUR COMPANY IMMEDIATELY IF THERE IS AN ATTEMPT TO REQUEST YOU TO ON-LINE YOUR PAYMENT TO ACCOUNTS OTHER THAN OUR', 0, 'J', 0, 1, '', '', true, 0, false, true, 0);
    $pdf->MultiCell(0, 0, 'DESIGNATED BANK ACCOUNTS THRU CELLPHONE NOS. 0916-062-6563/0962-098-4672.', 0, 'L', 0, 1, '', '', true, 0, false, true, 0);
    $pdf->Ln(5);
    $pdf->MultiCell(0, 0, 'OUR COMPANY HAS NO RESPONSILIBITY OR LIABILITY FOR ALL YOUR PAYMENTS', 0, 'J', 0, 1, '', '', true, 0, false, true, 0);
    $pdf->MultiCell(0, 0, 'COURSED THRU OTHER COMPANIES OR INDIVIDUALS.', 0, 'L', 0, 1, '', '', true, 0, false, true, 0);
    $pdf->Ln(5);
    $pdf->MultiCell(0, 0, 'THANK YOU VERY MUCH FOR YOUR CONTINUED SUPPORT TO OUR COMPANY.', 0, 'L', 0, 1, '', '', true, 0, false, true, 0);
    $pdf->Ln(5);
    $pdf->MultiCell(0, 0, 'RECEIVED AND CLEARLY UNDERSTOOD BY:', 0, 'L', 0, 1, '', '', true, 0, false, true, 0);
    $pdf->Ln(10);
    $pdf->Cell(80, 4, '', 'B', 0, 'C', 0, '', 1);
    $pdf->Cell(25, 4, '', 0, 0, 'C');
    $pdf->Ln(5);
    $pdf->Cell(80, 5, 'SIGNATURE / DATE', 0, 0, 'C');

    // Output the PDF to the browser
    $pdf->Output('online.pdf', 'I');
?>