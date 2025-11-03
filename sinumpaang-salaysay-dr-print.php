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
    require_once 'model/product-model.php';
    require_once 'model/sales-proposal-model.php';

    // Initialize database model
    $databaseModel = new DatabaseModel();

    // Initialize system model
    $systemModel = new SystemModel();

    // Initialize sales proposal model
    $salesProposalModel = new SalesProposalModel($databaseModel);
    $productModel = new ProductModel($databaseModel);

    // Initialize customer model
    $customerModel = new CustomerModel($databaseModel);

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
        $comakerID = $salesProposalDetails['comaker_id'] ?? '';
        $productID = $salesProposalDetails['product_id'] ?? '';
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
          $releasedDate =  $systemModel->checkDate('default', $salesProposalDetails['released_date'], '', 'm/d/Y', '');
    
        $pricingComputationDetails = $salesProposalModel->getSalesProposalPricingComputation($salesProposalID);
        $downpayment = $pricingComputationDetails['downpayment'] ?? 0;
        $amountFinanced = $pricingComputationDetails['amount_financed'] ?? 0;
        $repaymentAmount = $pricingComputationDetails['repayment_amount'] ?? 0;
        $pnAmount = $repaymentAmount * $numberOfPayments;
    
        $otherChargesDetails = $salesProposalModel->getSalesProposalOtherCharges($salesProposalID);
        $insurancePremium = $otherChargesDetails['insurance_premium'] ?? 0;
        $handlingFee = $otherChargesDetails['handling_fee'] ?? 0;
        $transferFee = $otherChargesDetails['transfer_fee'] ?? 0;
        $transactionFee = $otherChargesDetails['transaction_fee'] ?? 0;
        $docStampTax = $otherChargesDetails['doc_stamp_tax'] ?? 0;
    
        $renewalAmountDetails = $salesProposalModel->getSalesProposalRenewalAmount($salesProposalID);
        $registrationSecondYear = $renewalAmountDetails['registration_second_year'] ?? 0;
        $registrationThirdYear = $renewalAmountDetails['registration_third_year'] ?? 0;
        $registrationFourthYear = $renewalAmountDetails['registration_fourth_year'] ?? 0;
        $totalRenewalFee = $registrationSecondYear + $registrationThirdYear + $registrationFourthYear;
    
        $insurancePremiumSecondYear = $renewalAmountDetails['insurance_premium_second_year'] ?? 0;
        $insurancePremiumThirdYear = $renewalAmountDetails['insurance_premium_third_year'] ?? 0;
        $insurancePremiumFourthYear = $renewalAmountDetails['insurance_premium_fourth_year'] ?? 0;
        $totalInsuranceFee = $registrationSecondYear + $registrationThirdYear + $registrationFourthYear;
    
        $totalCharges = $insurancePremium + $handlingFee + $transferFee + $transactionFee + $totalRenewalFee + $totalInsuranceFee + $docStampTax;
        
        $totalDeposit = $salesProposalModel->getSalesProposalAmountOfDepositTotal($salesProposalID);

        $totalPn = $pnAmount + $totalCharges;

        $otherProductDetails = $salesProposalModel->getSalesProposalOtherProductDetails($salesProposalID);
        $mortgagee = $otherProductDetails['mortgagee'] ??  '';

        if(empty($mortgagee)){
            $mortgagee = 'LALAINE P. PENACILLA';
        }
    
        $amountInWords = new NumberFormatter("en", NumberFormatter::SPELLOUT);
    
        $customerDetails = $customerModel->getPersonalInformation($customerID);

        $customerName = strtoupper($customerDetails['file_as'] ?? '');
    
        $comakerDetails = $customerModel->getPersonalInformation($comakerID);
        $comakerName = strtoupper($comakerDetails['file_as'] ?? '');    
    
        $customerPrimaryAddress = $customerModel->getCustomerPrimaryAddress($customerID);
        $customerAddress = $customerPrimaryAddress['address'] . ', ' . $customerPrimaryAddress['city_name'] . ', ' . $customerPrimaryAddress['state_name'] . ', ' . $customerPrimaryAddress['country_name'];
    
        $comakerPrimaryAddress = $customerModel->getCustomerPrimaryAddress($comakerID);
    
        if(!empty($comakerPrimaryAddress['address'])){
          $comakerAddress = $comakerPrimaryAddress['address'] . ', ' . $comakerPrimaryAddress['city_name'] . ', ' . $comakerPrimaryAddress['state_name'] . ', ' . $comakerPrimaryAddress['country_name'];
        }
        else{
          $comakerAddress = '';
        }
        
        $customerContactInformation = $customerModel->getCustomerPrimaryContactInformation($customerID);
        $customerMobile = !empty($customerContactInformation['mobile']) ? $customerContactInformation['mobile'] : '--';
        $customerTelephone = !empty($customerContactInformation['telephone']) ? $customerContactInformation['telephone'] : '--';
        $customerEmail = !empty($customerContactInformation['email']) ? $customerContactInformation['email'] : '--';

        if($productType == 'Unit'){
          $productDetails = $productModel->getProduct($productID);

          $orcrNo = $productDetails['orcr_no'];
          $receivedFrom = $productDetails['received_from'];
          $receivedFromAddress = $productDetails['received_from_address'];
          $receivedFromIDType = $productDetails['received_from_id_type'];
          $receivedFromIDNumber = $productDetails['received_from_id_number'];
          $unitDescription = $productDetails['unit_description'];
          $orcrDate =  $systemModel->checkDate('empty', $productDetails['orcr_date'], '', 'm/d/Y', '');
          $orcrExpiryDate =  $systemModel->checkDate('empty', $productDetails['orcr_expiry_date'], '', 'm/d/Y', '');
          $plateNumber = $productDetails['plate_number'] ?? null;
        }
        else if($productType == 'Refinancing' || $productType == 'Brand New'){
          $orcrNo = $salesProposalDetails['orcr_no'];
          $receivedFrom = $salesProposalDetails['received_from'];
          $receivedFromAddress = $salesProposalDetails['received_from_address'];
          $receivedFromIDType = $salesProposalDetails['received_from_id_type'];
          $receivedFromIDNumber = $salesProposalDetails['received_from_id_number'];
          $unitDescription = $salesProposalDetails['unit_description'];
          $orcrDate =  $systemModel->checkDate('empty', $salesProposalDetails['orcr_date'], '', 'm/d/Y', '');
          $orcrExpiryDate =  $systemModel->checkDate('empty', $salesProposalDetails['orcr_expiry_date'], '', 'm/d/Y', '');
          $plateNumber = $salesProposalDetails['ref_plate_no'] ?? null;
        }

        $timestamp = time();

        // Check if today is Saturday (6) or Sunday (0)
        $dayOfWeek = date('w', $timestamp);
        if ($dayOfWeek == 6) {
            // Saturday → move to next Monday (add 2 days)
            $timestamp = strtotime('+2 days', $timestamp);
        } elseif ($dayOfWeek == 0) {
            // Sunday → move to next Monday (add 1 day)
            $timestamp = strtotime('+1 day', $timestamp);
        }

        // Format the date outputs
        $fullDate = date('F j, Y', $timestamp);  // October 22, 2025
        $currentMonth = date('F Y', $timestamp);    // October 2025
        $currentDay = date('jS', $timestamp); // 22nd
        $releasedDate = date('m/d/Y', strtotime($releasedDate));
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

    // Add a page
    $pdf->AddPage();

    // Add content
    $pdf->SetFont('times', '', 10.5);
    $pdf->MultiCell(0, 0, 'REPUBLIKA NG PILIPINAS ', 0, 'J', 0, 1, '', '', true, 0, true, true, 0);
    $pdf->Ln(3);
    $pdf->MultiCell(0, 0, 'LUNGSOD NG CABANATUAN) S.S.', 0, 'J', 0, 1, '', '', true, 0, true, true, 0);
    $pdf->Ln(3);
    $pdf->MultiCell(0, 0, 'X-----------------------------------------X', 0, 'J', 0, 1, '', '', true, 0, true, true, 0);
    $pdf->Ln(5);
    $pdf->MultiCell(0, 0, 'SINUMPAANG SALAYSAY', 0, 'C', 0, 1, '', '', true, 0, true, true, 0);
    $pdf->Ln(0);
    $pdf->MultiCell(0, 0, '(Buyer'. "'" .'s Undertaking)', 0, 'C', 0, 1, '', '', true, 0, true, true, 0);
    $pdf->Ln(5);
    $pdf->MultiCell(0, 0, 'Ako, si  <b><u>'. $customerName .'</u></b> may sapat na gulang, Pilipino,
    binata/may-asawa, at naninirahan sa <b><u>'. strtoupper($customerAddress) .'</u></b>,
    matapos na makapanumpa nang naayon sa batas, ay nagsasaad ng mga sumusunod:', 0, 'J', 0, 1, '', '', true, 0, true, true, 0);
    $pdf->Ln(5);
    $pdf->MultiCell(0, 0, '1.Na ako ay pumasok at nakipagkasundo sa CHRISTIAN MOTOR SALES CORPORATION ng Cabanatuan City (kinakatawan ni <b><u>'. $mortgagee .'</b></u>) sa Conditional Sales Agreement ng isang <b><u>'. $unitDescription .'</b></u>
    na may plakang: <b><u>'. $plateNumber .'</b></u> Petsang: <b><u>'. $releasedDate .'</b></u>.', 0, 'J', 0, 1, '', '', true, 0, true, true, 0);
    $pdf->Ln(3);
    $pdf->MultiCell(0, 0, '2. Na bilang NAKABILI (Buyer) ako ang hahawak at gagamit ng nasabing truck habang hinuhulugan ko ang pagbabayad dito.', 0, 'J', 0, 1, '', '', true, 0, true, true, 0);
    $pdf->Ln(3);
    $pdf->MultiCell(0, 0, '3. Na bilang tagagamit ng nasabing truck, ako ang magpaparenew ng rehistro'. "'" .'ng nasabing track sa Land Transportation Office, at ako ang magbabayad ng insurance;', 0, 'J', 0, 1, '', '', true, 0, true, true, 0);
    $pdf->Ln(3);
    $pdf->MultiCell(0, 0, '4. Na bilang tagapangalaga at tagagamit ng masabing truck, hindi ko gagamitin and nasabing truck sa mga tiwaling gawain at anumang ilegal na gawain;', 0, 'J', 0, 1, '', '', true, 0, true, true, 0);
    $pdf->Ln(3);
    $pdf->MultiCell(0, 0, '5. Na gagamitin ko ang nasabing truck nang may pag-iingat;', 0, 'J', 0, 1, '', '', true, 0, true, true, 0);
    $pdf->Ln(3);
    $pdf->MultiCell(0, 0, '6. Na ako ang magmementina (maintenance) sa nasabing truck;', 0, 'J', 0, 1, '', '', true, 0, true, true, 0);
    $pdf->Ln(3);
    $pdf->MultiCell(0, 0, '7. Na sa anumang pangyayari na masangkot ang nasabing truck sa ilalim ng aking pangangalaga sa mga aksidenteng magbubunga ng kasiraan sa nasabing truck; kasiraan/kapinsalaan ng ibang tao at mga pag-aari nito, ako ang mananagot sa nasabing pinsala:', 0, 'J', 0, 1, '', '', true, 0, true, true, 0);
    $pdf->Ln(3);
    $pdf->MultiCell(0, 0, '8. Na ang NAGBENTA (Seller) ay walang pananagutan sa anumang pinsala sa tao o ari- arian ng iba pa na matatamo dahil sa pagkakagamit ng nasabing truck sa ilalim ng aking pangangalaga o pag-iingat habang nakailalim sa Conditional Sales Agreement;', 0, 'J', 0, 1, '', '', true, 0, true, true, 0);
    $pdf->Ln(3);
    $pdf->MultiCell(0, 0, '9. Na ang aking obligasyon na nakasaad dito ay aking isinasagawa nang may-kusang loob at wala sinomang nananakit o nananakot sa akin;', 0, 'J', 0, 1, '', '', true, 0, true, true, 0);
    $pdf->Ln(3);
    $pdf->MultiCell(0, 0, '10. Na ako ay nagsagawa ng salaysay na ito upang patotohanan ang mga nasasaad dito at sa anumang legal na gamit na maaring gamitan nito;', 0, 'J', 0, 1, '', '', true, 0, true, true, 0);
    $pdf->Ln(10);
    $pdf->MultiCell(0, 0, 'BILANG PAGPAPATUNAY, ako ay lumalagda sa ibaba nito ngayong ika- ___________________, ng
    ____________________ dito sa _____________________________________________.', 0, 'J', 0, 1, '', '', true, 0, true, true, 0);
    $pdf->Ln(10);
    $pdf->Cell(90, 4, '', 0, 0 , 'C');
    $pdf->Cell(10, 4, '', 0, 0 , 'L');
    $pdf->Cell(90, 4, $customerName, 'B', 0, 'C');
    $pdf->Ln(5);
    $pdf->Cell(90, 8, '', 0, 0, 'C');
    $pdf->Cell(10, 4, '     ', 0, 0 , 'L');
    $pdf->Cell(90, 8, 'Nagsalaysay', 0, 0, 'C');
    $pdf->Ln(10);
    $pdf->MultiCell(0, 0, '         PINANUMPAAN AT NILAGDAAN SA AKING HARAPAN ngayong ika-_______________________, ng
    ________________________ dito sa ___________________________________________________.', 0, 'J', 0, 1, '', '', true, 0, true, true, 0);$pdf->Ln(10);
    $pdf->Cell(90, 4, '', 0, 0 , 'C');
    $pdf->Cell(10, 4, '', 0, 0 , 'L');
    $pdf->Cell(90, 4, '', 'B', 0, 'C');
    $pdf->Ln(5);
    $pdf->Cell(90, 8, '', 0, 0, 'C');
    $pdf->Cell(10, 4, '     ', 0, 0 , 'L');
    $pdf->Cell(90, 8, 'Notary Public', 0, 0, 'C');
    $pdf->Ln(10);
    $pdf->MultiCell(0, 0, 'Doc. No. ______________', 0, 'J', 0, 1, '', '', true, 0, true, true, 0);
    $pdf->Ln(3);
    $pdf->MultiCell(0, 0, 'Page No. ______________', 0, 'J', 0, 1, '', '', true, 0, true, true, 0);
    $pdf->Ln(3);
    $pdf->MultiCell(0, 0, 'Book No. ______________', 0, 'J', 0, 1, '', '', true, 0, true, true, 0);
    $pdf->Ln(3);
    $pdf->MultiCell(0, 0, 'Series of ______________', 0, 'J', 0, 1, '', '', true, 0, true, true, 0);

    // Output the PDF to the browser
    $pdf->Output('sinumpaang-salaysay.pdf', 'I');
    ob_end_flush();
?>