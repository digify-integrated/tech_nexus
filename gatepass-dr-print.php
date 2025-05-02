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

        $customerContactInformation = $customerModel->getCustomerPrimaryContactInformation($customerID);
        $customerMobile = !empty($customerContactInformation['mobile']) ? $customerContactInformation['mobile'] : '--';

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
        
        if($productType == 'Unit' || $productType == 'Rental'){
            $productDetails = $productModel->getProduct($productID);
            $productSubategoryID = $productDetails['product_subcategory_id'] ?? null;

            $productSubcategoryDetails = $productSubcategoryModel->getProductSubcategory($productSubategoryID);
            $productSubcategoryCode = $productSubcategoryDetails['product_subcategory_code'] ?? null;
            $productCategoryID = $productSubcategoryDetails['product_category_id'] ?? null;

            $stockNumber = str_replace($productSubcategoryCode, '', $productDetails['stock_number'] ?? null);
            $fullStockNumber = $productSubcategoryCode . $stockNumber;

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

    
    $gatePassTable = generateGatePassTable($customerName, $unitImage, $customerMobile, $customerAddress, $drNumber, $productDescription, $fullStockNumber);

    ob_start();

    // Create TCPDF instance
    $pdf = new TCPDF('P', 'mm', array(215.9, 330.2), true, 'UTF-8', false);

    // Disable header and footer
    $pdf->setPrintHeader(false);
    $pdf->setPrintFooter(false);

    // Set PDF metadata
    $pdf->SetCreator('CGMI');
    $pdf->SetAuthor('CGMI');
    $pdf->SetTitle('Gate Pass');
    $pdf->SetSubject('Gate Pass');

    // Set margins and auto page break
    $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
    $pdf->SetMargins(12, 12, 12);
    $pdf->SetHeaderMargin(5);
    $pdf->SetFooterMargin(10);
    $pdf->SetAutoPageBreak(TRUE, 15);

    // Add a page
    $pdf->AddPage();

    // Add content
    $pdf->SetFont('times', 'B', 11);
    $pdf->Cell(60, 4, 'GATE PASS - UNIT', 0, 0, 'C', 0, '', 1);
    $pdf->Cell(60, 4, 'NO : ' . $drNumber, 0, 0, 'C', 0, '', 1);
    $pdf->Cell(60, 4, 'DATE : ' . strtoupper(date('d-M-Y')), 0, 0, 'C', 0, '', 1);
    $pdf->Ln(10);
    $pdf->SetFont('times', '', 11);
    $pdf->writeHTML($gatePassTable, true, false, true, false, '');
    $pdf->Ln(5);
    $pdf->SetFont('times', 'B', 11);
    $pdf->Cell(60, 4, 'GATE PASS - UNIT', 0, 0, 'C', 0, '', 1);
    $pdf->Cell(60, 4, 'NO : ' . $drNumber, 0, 0, 'C', 0, '', 1);
    $pdf->Cell(60, 4, 'DATE : ' . strtoupper(date('d-M-Y')), 0, 0, 'C', 0, '', 1);
    $pdf->Ln(10);
    $pdf->SetFont('times', '', 11);
    $pdf->writeHTML($gatePassTable, true, false, true, false, '');

    // Output the PDF to the browser
    $pdf->Output('gate-pass.pdf', 'I');
    ob_flush();

    function generateGatePassTable($customerName, $unitImage, $customerMobile, $customerAddress, $drNumber, $productDescription, $stockNumber){
        $response = '<table border="0.5" width="100%" cellpadding="2">
                        <tbody>
                            <tr>
                                <td><small>Customer Name</small></td>
                                <td colspan="4"><small>'. $customerName .'</small></td>
                                <td rowspan="4" class="text-center">
                                    <img src="'. $unitImage .'" width="100">
                                </td>
                            </tr>
                            <tr>
                                <td><small>Contact Number</small></td>
                                <td><small>'. $customerMobile .'</small></td>
                                <td><small>Stock Number</small></td>
                                <td colspan="2"><small>'. $stockNumber .'</small></td>
                            </tr>
                            <tr>
                                <td><small>Address</small></td>
                                <td colspan="4"><small>'. strtoupper($customerAddress) .'</small></td>
                            </tr>
                            <tr>
                                <td><small>DR No.</small></td>
                                <td><small>'. $drNumber .'</small></td>
                                <td><small>DR Date</small></td>
                                <td colspan="2"><small>'. strtoupper(date('d-M-Y')) .'</small></td>
                            </tr>
                            <tr>
                                <td colspan="6" style="text-align:center;border-bottom: 0px transparent #fff;">
                                    <small >PARTICULARS</small>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="6" style="border-top: 0px transparent #fff;">
                                    <small>'. strtoupper($productDescription) .'</small><br/><br/>
                                    <small style="color: #dc2626;">REMINDER:</small><br/>
                                    <small>GATE PASS SHALL ALWAYS BE ACCOMPANIED BY DULY APPROVED DELIVERY RECEIPT</small>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2" style="vertical-align: top !important;" class="text-nowrap">
                                    <small>RELEASED BY:</small><br/><br/><br/>
                                    <small>CUSTOMER/AUTHORIZED REPRESENTATIVE</small>
                                </td>
                                <td colspan="3" style="vertical-align: top !important;" class="text-nowrap">
                                    <small>RELEASED APPROVED BY:</small><br/><br/><br/>
                                    <small>CHRISTIAN EDWARD C. BAGUISA &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; PAOLO EDUARDO C. BAGUISA</small>
                                </td>
                                <td style="vertical-align: top !important;" class="text-wrap">
                                    <small>INSPECTED BY/DATE:</small><br/><br/><br/>
                                    <small>GUARD ON DUTY</small>
                                </td>
                            </tr>
                        </tbody>
                    </table>';

        return $response;
    }
?>