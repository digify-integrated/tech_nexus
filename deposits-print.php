<?php
    error_reporting(E_ALL);
    ini_set('display_errors', 1);

    // Load TCPDF library
    require('assets/libs/tcpdf2/tcpdf.php');

    // Load required files
    require_once 'config/config.php';
    require_once 'session.php';
    require_once 'model/database-model.php';
    require_once 'model/pdc-management-model.php';
    require_once 'model/system-model.php';
    require_once 'model/customer-model.php';
    require_once 'model/product-model.php';
    require_once 'model/sales-proposal-model.php';
    require_once 'model/user-model.php';

    $databaseModel = new DatabaseModel();
    $systemModel = new SystemModel();
    $pdcManagementModel = new PDCManagementModel($databaseModel);
    $customerModel = new CustomerModel($databaseModel);
    $productModel = new ProductModel($databaseModel);
    $salesProposalModel = new SalesProposalModel($databaseModel);
    $userModel = new UserModel($databaseModel, $systemModel);
    
    if(isset($_GET['date'])){
        if(empty($_GET['date'])){
          header('location: dashboard.php');
          exit;
        }
        
        $loanCollectionIDs = explode(',', $_GET['id']);

        $createdByDetails = $userModel->getUserByID($_SESSION['user_id']);
        $createdByName = strtoupper($createdByDetails['file_as'] ?? null);

        $totalPDCAmount = 0;
        $totalReversedPDCAmount = 0;

        foreach ($loanCollectionIDs as $loanCollectionID) {
            $pdcManagementDetails = $pdcManagementModel->getPDCManagement($loanCollectionID);
            $collection_status = $pdcManagementDetails['collection_status'];
            $payment_amount = $pdcManagementDetails['payment_amount'];

            $totalPDCAmount = $totalPDCAmount + $payment_amount;

            if($collection_status == 'Reversed'){
                $totalReversedPDCAmount = $totalReversedPDCAmount + $payment_amount;
            }
        }

        $netPayments = $totalPDCAmount - $totalReversedPDCAmount;
    }

    ob_start();

    // Create TCPDF instance
    $pdf = new TCPDF('P', 'mm', array( 330.2,215.9), true, 'UTF-8', false);

    // Disable header and footer
    $pdf->setPrintHeader(false);
    $pdf->setPrintFooter(false);
    $pdf->SetPageOrientation('L');

    // Set PDF metadata
    $pdf->SetCreator('CGMI');
    $pdf->SetAuthor('CGMI');
    $pdf->SetTitle('Collections Print');
    $pdf->SetSubject('Collections Print');

    // Set margins and auto page break
    $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
    $pdf->SetMargins(15, 15, 15);
    $pdf->SetHeaderMargin(5);
    $pdf->SetFooterMargin(10);
    $pdf->SetAutoPageBreak(TRUE, 15);

    // Add a page
    $pdf->AddPage();

    // Output the PDF to the browser
    $pdf->Output('deposits-print.pdf', 'I');
    ob_end_flush();

    
?>