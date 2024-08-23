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
    require_once 'model/sales-proposal-model.php';
    require_once 'model/id-type-model.php';

    // Initialize database model
    $databaseModel = new DatabaseModel();

    // Initialize system model
    $systemModel = new SystemModel();

    // Initialize sales proposal model
    $salesProposalModel = new SalesProposalModel($databaseModel);

    // Initialize customer model
    $customerModel = new CustomerModel($databaseModel);
    $idTypeModel = new IDTypeModel($databaseModel);
   

    if(isset($_GET['id'])){
        if(empty($_GET['id'])){
          header('location: sales-proposal-for-dr.php');
          exit;
        }
        
        $salesProposalID = $_GET['id'];

        $checkSalesProposalExist = $salesProposalModel->checkSalesProposalExist($salesProposalID);
        $total = $checkSalesProposalExist['total'] ?? 0;

        if($total == 0){
            header('location: 404.php');
            exit;
        }

        $salesProposalDetails = $salesProposalModel->getSalesProposal($salesProposalID); 
        $customerID = $salesProposalDetails['customer_id'];

        $customerDetails = $customerModel->getPersonalInformation($customerID);

        $customerName = strtoupper($customerDetails['file_as']) ?? null;

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
    $pdf->SetTitle('Sales Proposal');
    $pdf->SetSubject('Sales Proposal');

    // Set margins and auto page break
    $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
    $pdf->SetMargins(15, 15, 15);
    $pdf->SetHeaderMargin(5);
    $pdf->SetFooterMargin(10);
    $pdf->SetAutoPageBreak(TRUE, 15);

    // Set font
    $pdf->SetFont('times', '', 11);

    // Add a page
    $pdf->AddPage();

    // Add content
    $pdf->SetTextColor(255, 0, 0);
    $pdf->MultiCell(0, 0, '<b>MAHALAGANG PAALALA</b>', 0, 'C', 0, 1, '', '', true, 0, true, true, 0);
    $pdf->Ln(5);
    $pdf->MultiCell(0, 0, '<b>PAGBAYAD SA BANGKO</b>', 0, 'L', 0, 1, '', '', true, 0, true, true, 0);
    $pdf->Ln(5);
    $pdf->SetTextColor(0, 0, 0);
    $pdf->MultiCell(0, 0, 'IDEPOSITO ANG INYONG BAYAD SA BDO BILLS PAYMENT SA KAHIT SAANG BANCO DE ORO (BDO) BRANCH.', 0, 'J', 0, 1, '', '', true, 0, false, true, 0);
    $pdf->Ln(5);
    $pdf->SetTextColor(255, 0, 0);
    $pdf->MultiCell(0, 0, 'TAMANG PARAAN NG PAGBABAYAD SA BANGKO', 0, 'L', 0, 1, '', '', true, 0, false, true, 0);
    $pdf->SetTextColor(0, 0, 0);
    $pdf->MultiCell(0, 0, 'ISULAT SA DEPOSIT SLIP ANG MGA  SUMUSUNOD:', 0, 'L', 0, 1, '', '', true, 0, false, true, 0);
    $pdf->Ln(10);
    $pdf->Cell(60, 0, 'COMPANY NAME: ', 0, 0, 'L');
    $pdf->SetTextColor(255, 0, 0); // set text color to red
    $pdf->Cell(0, 0, 'CHRISTIAN GENERAL MOTORS INC.', 0, 1, 'L');
    $pdf->SetTextColor(0, 0, 0); // reset text color to black
    $pdf->Ln(3);
    $pdf->Cell(60, 0, 'SUBSCRIBER NAME: ', 0, 0, 'L');
    $pdf->SetTextColor(255, 0, 0); // set text color to red
    $pdf->Cell(0, 0, $customerName, 0, 1, 'L');
    $pdf->SetTextColor(0, 0, 0); // reset text color to black
    $pdf->Ln(3);
    $pdf->Cell(60, 0, 'SUBSCRIBER ACCOUNT NO: ', 0, 0, 'L');
    $pdf->SetTextColor(255, 0, 0); // set text color to red
    $pdf->Cell(0, 0, '(REFERENCE NO. IS THE PN NO.)', 0, 1, 'L');
    $pdf->SetTextColor(0, 0, 0); // reset text color to black
    $pdf->Ln(3);
    $pdf->Cell(60, 0, 'INSTITUTION CODE: ', 0, 0, 'L');
    $pdf->SetTextColor(255, 0, 0); // set text color to red
    $pdf->Cell(0, 0, '2633', 0, 1, 'L');
    $pdf->SetTextColor(0, 0, 0); // reset text color to black
    $pdf->Ln(3);
    $pdf->Cell(60, 0, 'FOR CASH TRANSACTIONS: ', 0, 0, 'L');
    $pdf->SetTextColor(255, 0, 0); // set text color to red
    $pdf->Cell(0, 0, 'INDICATE DENOMINATION, PIECES, AMOUNT & TOTAL AMOUNT', 0, 1, 'L');
    $pdf->SetTextColor(0, 0, 0); // reset text color to black
    $pdf->Ln(3);
    $pdf->Cell(60, 0, 'BANK SERVICE CHARGE: ', 0, 0, 'L');
    $pdf->SetTextColor(255, 0, 0); // set text color to red
    $pdf->Cell(0, 0, 'P30.00 WILL BE ADDED TO THE TOTAL AMOUNT DUE', 0, 1, 'L');
    $pdf->SetTextColor(0, 0, 0); // reset text color to black
    $pdf->Ln(10);
    $pdf->MultiCell(0, 0, 'I-TEXT ANG IYONG PANGALAN, BANK BRANCH KUNG SAAN NAG DEPOSITO, AMOUNT NG', 0, 'J', 0, 1, '', '', true, 0, false, true, 0);
    $pdf->MultiCell(0, 0, 'DINEPOSITO AT PETSA NG PAGBABAYAD SA CELLPHONE NUMBER 0968-099-2871.', 0, 'L', 0, 1, '', '', true, 0, false, true, 0);
    $pdf->Ln(5);
    $pdf->MultiCell(0, 0, 'PAGKATAPOS, PAKI-SCAN AT PAKI-EMAIL ANG DEPOSIT SLIP	SA v.reyes@christianmotors.ph SA ARAW ', 0, 'J', 0, 1, '', '', true, 0, false, true, 0);
    $pdf->MultiCell(0, 0, 'NG PAGKAKADEPOSITO O SA SUSUNOD NA ARAW.', 0, 'L', 0, 1, '', '', true, 0, false, true, 0);
    $pdf->Ln(5);
    $pdf->MultiCell(0, 0, 'HUMINGI NG COLLECTION RECEIPT PARA SA IPINADALANG DEPOSIT SLIP AT  SIGURADUHIN PO NA ANG INYONG DEPOSIT SLIPS AY NAKATAGO PARA MAGAMIT NA BATAYAN NG INYONG', 0, 'J', 0, 1, '', '', true, 0, false, true, 0);
    $pdf->MultiCell(0, 0, 'PAGKAKABAYAD SA HINAHARAP.', 0, 'L', 0, 1, '', '', true, 0, false, true, 0);
    $pdf->Ln(10);
    $pdf->SetTextColor(255, 0, 0);
    $pdf->MultiCell(0, 0, 'ANUMANG BAYAD SA PAMAMAGITAN NG LBC, JRS, O IBA PANG KATULAD NA KUMPANYA AY', 0, 'J', 0, 1, '', '', true, 0, false, true, 0);
    $pdf->MultiCell(0, 0, 'HINDI PINAHIHINTULUTAN NG AMING KUMPANYA.', 0, 'L', 0, 1, '', '', true, 0, false, true, 0);
    $pdf->Ln(10);
    $pdf->MultiCell(0, 0, '<b>BABALA</b>', 0, 'L', 0, 1, '', '', true, 0, true, true, 0);
    $pdf->Ln(5);
    $pdf->SetTextColor(0, 0, 0);
    $pdf->MultiCell(0, 0, 'MANGYARI PO LAMANG NA IPAGBIGAY ALAM SA AMING KUMPANYA KUNG MAYROONG MAG-UUTOS SA INYO NA BAYARAN ANG INYONG ACCOUNT MALIBAN SA ITINAKDANG DEPOSIT ', 0, 'J', 0, 1, '', '', true, 0, false, true, 0);
    $pdf->MultiCell(0, 0, 'ACCOUNTS NG AMING KUMPANYA SA CELLPHONE NO. 0968-099-2871.', 0, 'L', 0, 1, '', '', true, 0, false, true, 0);
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
    $pdf->MultiCell(0, 0, '<b>ONLINE DEPOSITS OR PAYMENTS</b>', 0, 'L', 0, 1, '', '', true, 0, true, true, 0);
    $pdf->Ln(5);
    $pdf->SetTextColor(0, 0, 0);
    $pdf->MultiCell(0, 0, 'FOR ALL ONLINE PAYMENTS, KINDLY DEPOSIT THROUGH BILLS PAYMENT TO ANY BANCO DE ORO', 0, 'J', 0, 1, '', '', true, 0, false, true, 0);
    $pdf->MultiCell(0, 0, '(BDO) BRANCHES ANYWHERE IN THE PHILIPPINES.', 0, 'L', 0, 1, '', '', true, 0, false, true, 0);
    $pdf->Ln(5);
    $pdf->SetTextColor(255, 0, 0);
    $pdf->MultiCell(0, 0, 'PROCEDURES FOR MAKING ONLINE PAYMENTS THROUGH BANKS', 0, 'L', 0, 1, '', '', true, 0, false, true, 0);
    $pdf->SetTextColor(0, 0, 0);
    $pdf->MultiCell(0, 0, 'KINDLY INDICATE IN THE BANK DEPOSIT SLIP THE FOLLOWING:', 0, 'L', 0, 1, '', '', true, 0, false, true, 0);
    $pdf->Ln(10);
    $pdf->Cell(60, 0, 'COMPANY NAME: ', 0, 0, 'L');
    $pdf->SetTextColor(255, 0, 0); // set text color to red
    $pdf->Cell(0, 0, 'CHRISTIAN GENERAL MOTORS INC.', 0, 1, 'L');
    $pdf->SetTextColor(0, 0, 0); // reset text color to black
    $pdf->Ln(3);
    $pdf->Cell(60, 0, 'SUBSCRIBER NAME: ', 0, 0, 'L');
    $pdf->SetTextColor(255, 0, 0); // set text color to red
    $pdf->Cell(0, 0, $customerName, 0, 1, 'L');
    $pdf->SetTextColor(0, 0, 0); // reset text color to black
    $pdf->Ln(3);
    $pdf->Cell(60, 0, 'SUBSCRIBER ACCOUNT NO: ', 0, 0, 'L');
    $pdf->SetTextColor(255, 0, 0); // set text color to red
    $pdf->Cell(0, 0, '(REFERENCE NO. IS THE PN NO.)', 0, 1, 'L');
    $pdf->SetTextColor(0, 0, 0); // reset text color to black
    $pdf->Ln(3);
    $pdf->Cell(60, 0, 'INSTITUTION CODE: ', 0, 0, 'L');
    $pdf->SetTextColor(255, 0, 0); // set text color to red
    $pdf->Cell(0, 0, '2633', 0, 1, 'L');
    $pdf->SetTextColor(0, 0, 0); // reset text color to black
    $pdf->Ln(3);
    $pdf->Cell(60, 0, 'FOR CASH TRANSACTIONS: ', 0, 0, 'L');
    $pdf->SetTextColor(255, 0, 0); // set text color to red
    $pdf->Cell(0, 0, 'INDICATE DENOMINATION, PIECES, AMOUNT & TOTAL AMOUNT', 0, 1, 'L');
    $pdf->SetTextColor(0, 0, 0); // reset text color to black
    $pdf->Ln(3);
    $pdf->Cell(60, 0, 'BANK SERVICE CHARGE: ', 0, 0, 'L');
    $pdf->SetTextColor(255, 0, 0); // set text color to red
    $pdf->Cell(0, 0, 'P30.00 WILL BE ADDED TO THE TOTAL AMOUNT DUE', 0, 1, 'L');
    $pdf->SetTextColor(0, 0, 0); // reset text color to black
    $pdf->Ln(10);
    $pdf->MultiCell(0, 0, 'PLEASE TEXT YOUR NAME, THE NAME OF THE BANK AND BRANCH WHERE THE ONLINE DEPOSIT WAS MADE, THE AMOUNT OF THE DEPOSIT, AND THE DATE OF PAYMENT TO CELLPHONE NO.0968-099-287', 0, 'J', 0, 1, '', '', true, 0, false, true, 0);
    $pdf->Ln(5);
    $pdf->MultiCell(0, 0, 'AFTER TEXTING, KINDLY SCAN AND E-MAIL THE	DEPOSIT	SLIP TO v.reyes@christianmotors.ph ON THE', 0, 'J', 0, 1, '', '', true, 0, false, true, 0);
    $pdf->MultiCell(0, 0, 'SAME DAY THE DEPOSIT IS MADE OR THE FOLLOWING DAY.', 0, 'L', 0, 1, '', '', true, 0, false, true, 0);
    $pdf->Ln(5);
    $pdf->MultiCell(0, 0, 'REQUEST A COLLECTION RECEIPT FOR THE DEPOSIT SLIP YOU EMAILED, AND ENSURE THAT ALL', 0, 'J', 0, 1, '', '', true, 0, false, true, 0);
    $pdf->MultiCell(0, 0, 'YOUR BANK DEPOSIT SLIPS ARE SAFEKEPT FOR FUTURE REFERENCE.', 0, 'L', 0, 1, '', '', true, 0, false, true, 0);
    $pdf->Ln(10);
    $pdf->SetTextColor(255, 0, 0);
    $pdf->MultiCell(0, 0, 'OTHER MODES OF PAYMENTS SUCH AS BUT NOT LIMITED TO LBC, JRS, OR ANY OTHER MONEY', 0, 'J', 0, 1, '', '', true, 0, false, true, 0);
    $pdf->MultiCell(0, 0, 'TRANSFER COMPANIES ARE NOT AUTHORIZED BY THE COMPANY.', 0, 'L', 0, 1, '', '', true, 0, false, true, 0);
    $pdf->Ln(10);
    $pdf->MultiCell(0, 0, '<b>WARNINGS</b>', 0, 'L', 0, 1, '', '', true, 0, true, true, 0);
    $pdf->Ln(5);
    $pdf->SetTextColor(0, 0, 0);
    $pdf->MultiCell(0, 0, 'PLEASE ADVISE OUR COMPANY IMMEDIATELY IF THERE IS AN ATTEMPT TO REQUEST YOU  TO ONLINE YOUR  PAYMENT  TO  ACCOUNTS  OTHER  THAN  OUR DESIGNATED BANK ACCOUNTS', 0, 'J', 0, 1, '', '', true, 0, false, true, 0);
    $pdf->MultiCell(0, 0, 'THROUGH CELLPHONE NOS. 0968-099-287.', 0, 'L', 0, 1, '', '', true, 0, false, true, 0);
    $pdf->Ln(5);
    $pdf->MultiCell(0, 0, 'OUR COMPANY HAS NO RESPONSIBILITY OR LIABILITY FOR ALL YOUR PAYMENTS COURSED', 0, 'J', 0, 1, '', '', true, 0, false, true, 0);
    $pdf->MultiCell(0, 0, 'THROUGH OTHER COMPANIES OR INDIVIDUALS.', 0, 'L', 0, 1, '', '', true, 0, false, true, 0);
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