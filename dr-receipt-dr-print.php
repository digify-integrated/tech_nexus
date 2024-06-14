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
    require_once 'model/product-model.php';
    require_once 'model/product-subcategory-model.php';

    // Initialize database model
    $databaseModel = new DatabaseModel();

    // Initialize system model
    $systemModel = new SystemModel();

    // Initialize sales proposal model
    $salesProposalModel = new SalesProposalModel($databaseModel);

    // Initialize customer model
    $customerModel = new CustomerModel($databaseModel);
    $productModel = new ProductModel($databaseModel);
    $productSubcategoryModel = new ProductSubcategoryModel($databaseModel);

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
        $comakerID = $salesProposalDetails['comaker_id'];
        $productID = $salesProposalDetails['product_id'] ?? null;
        $productType = $salesProposalDetails['product_type'] ?? null;
        $salesProposalNumber = $salesProposalDetails['sales_proposal_number'] ?? null;
        $numberOfPayments = $salesProposalDetails['number_of_payments'] ?? null;
        $paymentFrequency = $salesProposalDetails['payment_frequency'] ?? null;
        $startDate = $salesProposalDetails['actual_start_date'] ?? null;
        $drNumber = $salesProposalDetails['dr_number'] ?? null;
        $releaseTo = $salesProposalDetails['release_to'] ?? null;
        $salesProposalStatus = $salesProposalDetails['sales_proposal_status'] ?? null;
        $unitImage = $systemModel->checkImage($salesProposalDetails['unit_image'], 'default');
        $salesProposalStatusBadge = $salesProposalModel->getSalesProposalStatus($salesProposalStatus);
        $createdDate = $systemModel->checkDate('default', $salesProposalDetails['created_date'] ?? null, '', 'd-M-Y', '');

        $extension = pathinfo($unitImage, PATHINFO_EXTENSION);
        
        $otherProductDetails = $salesProposalModel->getSalesProposalOtherProductDetails($salesProposalID);
        $productDescription = $otherProductDetails['product_description'] ?? null;
        
        if($productType == 'Unit'){
            $productDetails = $productModel->getProduct($productID);
            $productSubategoryID = $productDetails['product_subcategory_id'] ?? null;

            $productSubcategoryDetails = $productSubcategoryModel->getProductSubcategory($productSubategoryID);
            $productSubcategoryCode = $productSubcategoryDetails['product_subcategory_code'] ?? null;
            $productCategoryID = $productSubcategoryDetails['product_category_id'] ?? null;

            $stockNumber = str_replace($productSubcategoryCode, '', $productDetails['stock_number'] ?? null);
            $fullStockNumber = $productSubcategoryCode . $stockNumber;

            $stockNumber = $stockNumber;
            $engineNumber = $productDetails['engine_number'] ??  '--';
            $chassisNumber = $productDetails['chassis_number'] ??  '--';
            $plateNumber = $productDetails['plate_number'] ?? '--';
        }
        else if($productType == 'Refinancing'){
            $stockNumber = $salesProposalDetails['ref_stock_no'] ??  '--';
            $engineNumber = $salesProposalDetails['ref_engine_no'] ??  '--';
            $chassisNumber = $salesProposalDetails['ref_chassis_no'] ??  '--';
            $plateNumber = $salesProposalDetails['ref_plate_no'] ??  '--';
            $fullStockNumber ='';
        }
        else{
            $stockNumber = '';
            $engineNumber = '';
            $chassisNumber = '';
            $plateNumber = '-';
            $fullStockNumber = '';
        }
    
       
        if(!empty($releaseTo)){
            $customerName = strtoupper($releaseTo);
          }
          else{
            $customerDetails = $customerModel->getPersonalInformation($customerID);
            $customerName = strtoupper($customerDetails['file_as']) ?? null;
          }
    
        $customerPrimaryAddress = $customerModel->getCustomerPrimaryAddress($customerID);
        $address = $customerPrimaryAddress['address'] ?? null ;
        
        if(!empty($address)){
            $customerAddress = $address . ', ' . $customerPrimaryAddress['city_name'] ?? null . ', ' . $customerPrimaryAddress['state_name'] ?? null . ', ' . $customerPrimaryAddress['country_name'] ?? null;
        }
        else{
$customerAddress = '';
        }
    
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
    $pdf->Cell(100, 4, strtoupper($customerName), 0, 0, 'L', 0, '', 1);
    $pdf->Cell(25, 4, '     ', 0, 0 , 'L', '', 1);
    $pdf->Cell(30, 4, strtoupper(date('d-M-Y')), 0, 0, 'L', 0, '', 1);
    $pdf->Cell(1, 4, '     ', 0, 0 , 'L', '', 1);
    $pdf->Cell(10, 4, $drNumber, 0, 0, 'L', 0, '', 1);
    $pdf->Ln(8);
    $pdf->Cell(18, 4, '', 0, 0, 'L', 0, '', 1);
    $pdf->Cell(100, 4, '-', 0, 0, 'L', 0, '', 1);
    $pdf->Cell(25, 4, '     ', 0, 0 , 'L', '', 1);
    $pdf->Cell(30, 4, strtoupper($createdDate), 0, 0, 'L', 0, '', 1);
    $pdf->Ln(8);
    $pdf->Cell(18, 4, '', 0, 0, 'L', 0, '', 1);
    $pdf->Cell(100, 4, strtoupper($customerAddress), 0, 0, 'L', 0, '', 1);
    $pdf->Ln(22);
    $pdf->SetTextColor(255, 0, 0);
    $pdf->MultiCell(5, 4, '', 0, 'L', false, 0, '', '', true);
    $pdf->MultiCell(100, 4, '<b><u>'. $fullStockNumber .'</u></b>', 0, 'L', false, 0, '', '', true, 0, true, true, 0);
    $pdf->SetTextColor(0, 0, 0);
    $pdf->Image($unitImage, 80, 67, 45, 45, $extension, '', '', true, 150, '', false, false, 1, false, false, false);
    $pdf->Ln(5);
    $pdf->SetFont('times', '', 8);
    $pdf->MultiCell(5, 4, '', 0, 'L', false, 0, '', '', true);
    $pdf->MultiCell(60, 4, strtoupper($productDescription), 0, 'L', false, 0, '', '', true);
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