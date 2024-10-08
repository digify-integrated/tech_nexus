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
    require_once 'model/color-model.php';
    require_once 'model/product-subcategory-model.php';

    // Initialize database model
    $databaseModel = new DatabaseModel();

    // Initialize system model
    $systemModel = new SystemModel();

    // Initialize sales proposal model
    $salesProposalModel = new SalesProposalModel($databaseModel);

    // Initialize customer model
    $customerModel = new CustomerModel($databaseModel);
    $colorModel = new ColorModel($databaseModel);
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
        $startDate = date('F d, Y', strtotime($salesProposalDetails['actual_start_date'] ?? null));
        $drNumber = $salesProposalDetails['dr_number'] ?? null;
        $releaseTo = $salesProposalDetails['release_to'] ?? null;
        $salesProposalStatus = $salesProposalDetails['sales_proposal_status'] ?? null;
        $unitImage = $systemModel->checkImage($salesProposalDetails['unit_image'], 'default');
        $salesProposalStatusBadge = $salesProposalModel->getSalesProposalStatus($salesProposalStatus);
        $createdDate = $systemModel->checkDate('default', $salesProposalDetails['created_date'] ?? null, '', 'd-M-Y', '');
        $termLength = $salesProposalDetails['term_length'];
        $termType = $salesProposalDetails['term_type'];
        $maturityDate =  date('F d, Y', strtotime("+". $termLength ." " . $termType , strtotime($salesProposalDetails['actual_start_date'])));

        $extension = pathinfo($unitImage, PATHINFO_EXTENSION);

        $otherChargesDetails = $salesProposalModel->getSalesProposalOtherCharges($salesProposalID);
        $insuranceCoverage = $otherChargesDetails['insurance_coverage'] ?? 0;
        $insurancePremium = $otherChargesDetails['insurance_premium_subtotal'] ?? 0;
        
        $otherProductDetails = $salesProposalModel->getSalesProposalOtherProductDetails($salesProposalID);
        $productDescription = $otherProductDetails['product_description'] ?? null;
        $yearModel = $otherProductDetails['year_model'] ??  '--';
        $crNo = $otherProductDetails['cr_no'] ??  '--';
        $mvFileNo = $otherProductDetails['mv_file_no'] ??  '--';
        $make = $otherProductDetails['make'] ??  '--';

        $salesProposalPricingComputationDetails = $salesProposalModel->getSalesProposalPricingComputation($salesProposalID);
        $totalDeliveryPrice = $salesProposalPricingComputationDetails['total_delivery_price'] ?? 0;
        
        if($productType == 'Unit'){
            $productDetails = $productModel->getProduct($productID);
            $productSubategoryID = $productDetails['product_subcategory_id'] ?? null;
            $colorID = $productDetails['color_id'] ??  '--';

            $getColor = $colorModel->getColor($colorID);
            $colorName = $getColor['color_name'] ??  '--';

            $productSubcategoryDetails = $productSubcategoryModel->getProductSubcategory($productSubategoryID);
            $productSubcategoryCode = $productSubcategoryDetails['product_subcategory_code'] ??  '--';
            $productCategoryID = $productSubcategoryDetails['product_category_id'] ??  '--';
            
            $stockNumber = str_replace($productSubcategoryCode, '', $productDetails['stock_number'] ?? null);
            $fullStockNumber = $productSubcategoryCode . $stockNumber;

            $stockNumber = $stockNumber;
            $engineNumber = $productDetails['engine_number'] ??  '--';
            $chassisNumber = $productDetails['chassis_number'] ??  '--';
            $plateNumber = $productDetails['plate_number'] ?? '--';

            if($productCategoryID == '1' || $productCategoryID == '3'){
                $odRate = 2.4;
            }
            else{
                $odRate = 1.25;
            }
        }
        else if($productType == 'Refinancing'){
            $stockNumber = $salesProposalDetails['ref_stock_no'] ??  '--';
            $engineNumber = $salesProposalDetails['ref_engine_no'] ??  '--';
            $chassisNumber = $salesProposalDetails['ref_chassis_no'] ??  '--';
            $plateNumber = $salesProposalDetails['ref_plate_no'] ??  '--';
            $fullStockNumber ='';
            $colorName ='--';
            $odRate = 1.25;
        }
        else{
            $stockNumber = '--';
            $engineNumber = '--';
            $chassisNumber = '--';
            $plateNumber = '--';
            $fullStockNumber = '--';
            $colorName = '--';
            $odRate = 1.25;
        }

        $odTheftPremium = $totalDeliveryPrice * ($odRate/100);
            $vatPremium = $odTheftPremium * (12/100);
            $docStamps = $odTheftPremium * (12.5/100);
            $localGovtTax = $odTheftPremium * (0.5/100);
            $gross = $odTheftPremium + $vatPremium + $docStamps + $localGovtTax;
    
        $salesProposalRenewalAmountDetails = $salesProposalModel->getSalesProposalRenewalAmount($salesProposalID);
        $insuranceCoverageSecondYear = $salesProposalRenewalAmountDetails['insurance_coverage_second_year'] ?? 0;
        $insuranceCoverageThirdYear = $salesProposalRenewalAmountDetails['insurance_coverage_third_year'] ?? 0;
        $insuranceCoverageFourthYear = $salesProposalRenewalAmountDetails['insurance_coverage_fourth_year'] ?? 0;
        $insurancePremiumSecondYear = $salesProposalRenewalAmountDetails['insurance_premium_second_year'] ?? 0;
        $insurancePremiumThirdYear = $salesProposalRenewalAmountDetails['insurance_premium_third_year'] ?? 0;
        $insurancePremiumFourthYear = $salesProposalRenewalAmountDetails['insurance_premium_fourth_year'] ?? 0;

        if($insuranceCoverageSecondYear > 0 && $insurancePremiumSecondYear > 0){
            $secondYearInsuranceDate = date('F d, Y', strtotime("+1 year" , strtotime($salesProposalDetails['actual_start_date'])));
        }
        else{
            $secondYearInsuranceDate = '';
        }

        if($insuranceCoverageThirdYear > 0 && $insurancePremiumThirdYear > 0){
            $thirdYearInsuranceDate = date('F d, Y', strtotime("+2 year" , strtotime($salesProposalDetails['actual_start_date'])));
        }
        else{
            $thirdYearInsuranceDate = '';
        }

        if($insuranceCoverageFourthYear > 0 && $insurancePremiumFourthYear > 0){
            $fourthYearInsuranceDate = date('F d, Y', strtotime("+3 year" , strtotime($salesProposalDetails['actual_start_date'])));
        }
        else{
            $fourthYearInsuranceDate = '';
        }

        $customerDetails = $customerModel->getPersonalInformation($customerID);
        $customerName = strtoupper($customerDetails['file_as']) ?? null;
        $customerPrimaryAddress = $customerModel->getCustomerPrimaryAddress($customerID);
        $address = $customerPrimaryAddress['address'] ?? null ;
        
        if(!empty($address)){
            $customerAddress = $address . ', ' . $customerPrimaryAddress['city_name'] ?? null . ', ' . $customerPrimaryAddress['state_name'] ?? null . ', ' . $customerPrimaryAddress['country_name'] ?? null;
        }
        else{
            $customerAddress = '';
        }
    
    }

    $insuranceRequestTable2 = generateInsuranceRequestTable2($startDate, $insurancePremium, $insuranceCoverage, $insuranceCoverageSecondYear, $insurancePremiumSecondYear, $secondYearInsuranceDate, $insuranceCoverageThirdYear, $insurancePremiumThirdYear, $thirdYearInsuranceDate, $insuranceCoverageFourthYear, $insurancePremiumFourthYear, $fourthYearInsuranceDate);

    ob_start();

    // Create TCPDF instance
    $pdf = new TCPDF('P', 'mm', array(215.9, 330.2), true, 'UTF-8', false);

    // Disable header and footer
    $pdf->setPrintHeader(false);
    $pdf->setPrintFooter(false);

    // Set PDF metadata
    $pdf->SetCreator('CGMI');
    $pdf->SetAuthor('CGMI');
    $pdf->SetTitle('Insurance Request');
    $pdf->SetSubject('Insurance Request');

    // Set margins and auto page break
    $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
    $pdf->SetMargins(12, 12, 12);
    $pdf->SetHeaderMargin(5);
    $pdf->SetFooterMargin(10);
    $pdf->SetAutoPageBreak(TRUE, 15);

    // Add a page
    $pdf->AddPage();

    // Add content
    $pdf->SetFont('times', '', 10);
    $pdf->Cell(40, 4, 'STM NUMBER : ', 0, 0, 'L', 0, '', 1);
    $pdf->Cell(60, 4, strtoupper($drNumber), 0, 0, 'L', 0, '', 1);
    $pdf->Ln(6);
    $pdf->Cell(100, 4, 'CHRISTIAN GENERAL MOTORS, INC.', 0, 0, 'L', 0, '', 1);
    $pdf->Ln(10);
    $pdf->Cell(40, 4, 'NAME : ', 0, 0, 'L', 0, '', 1);
    $pdf->Cell(60, 4, strtoupper($customerName), 0, 0, 'L', 0, '', 1);
    $pdf->Ln(6);
    $pdf->Cell(40, 4, 'ADDRESS : ', 0, 0, 'L', 0, '', 1);
    $pdf->Cell(100, 4, strtoupper($customerAddress), 0, 0, 'L', 0, '', 1);
    $pdf->Ln(6);
    $pdf->Cell(40, 4, 'TERM : ', 0, 0, 'L', 0, '', 1);
    $pdf->Cell(60, 4, $termLength . ' ' . strtoupper($termType), 0, 0, 'L', 0, '', 1);
    $pdf->Ln(6);
    $pdf->Cell(40, 4, 'INCEPTION : ', 0, 0, 'L', 0, '', 1);
    $pdf->Cell(60, 4, strtoupper(date('F d, Y', strtotime($startDate))), 0, 0, 'L', 0, '', 1);
    $pdf->Ln(6);
    $pdf->Cell(40, 4, 'MATURITY : ', 0, 0, 'L', 0, '', 1);
    $pdf->Cell(60, 4, strtoupper(date('F d, Y', strtotime($maturityDate))), 0, 0, 'L', 0, '', 1);
    $pdf->Ln(6);
    $pdf->Cell(40, 4, 'UNIT NO. : ', 0, 0, 'L', 0, '', 1);
    $pdf->Cell(60, 4, $fullStockNumber, 0, 0, 'L', 0, '', 1);
    $pdf->Ln(6);
    $pdf->Cell(40, 4, 'YR/MODEL : ', 0, 0, 'L', 0, '', 1);
    $pdf->Cell(60, 4, $yearModel, 0, 0, 'L', 0, '', 1);
    $pdf->Ln(6);
    $pdf->Cell(40, 4, 'COLOR : ', 0, 0, 'L', 0, '', 1);
    $pdf->Cell(60, 4, strtoupper($colorName), 0, 0, 'L', 0, '', 1);
    $pdf->Ln(6);
    $pdf->Cell(40, 4, 'MAKE/TYPE : ', 0, 0, 'L', 0, '', 1);
    $pdf->Cell(60, 4, $make, 0, 0, 'L', 0, '', 1);
    $pdf->Ln(6);
    $pdf->Cell(40, 4, 'PLATE NO. : ', 0, 0, 'L', 0, '', 1);
    $pdf->Cell(60, 4, $plateNumber, 0, 0, 'L', 0, '', 1);
    $pdf->Ln(6);
    $pdf->Cell(40, 4, 'CHASSIS NO. : ', 0, 0, 'L', 0, '', 1);
    $pdf->Cell(60, 4, $chassisNumber, 0, 0, 'L', 0, '', 1);
    $pdf->Ln(6);
    $pdf->Cell(40, 4, 'ENGINE NO. : ', 0, 0, 'L', 0, '', 1);
    $pdf->Cell(60, 4, $engineNumber, 0, 0, 'L', 0, '', 1);
    $pdf->Ln(6);
    $pdf->Cell(40, 4, 'MV FILE NO. : ', 0, 0, 'L', 0, '', 1);
    $pdf->Cell(60, 4, $mvFileNo, 0, 0, 'L', 0, '', 1);
    $pdf->Ln(6);
    $pdf->Cell(40, 4, 'MORTGAGEE : ', 0, 0, 'L', 0, '', 1);
    $pdf->Cell(60, 4, 'CGMI', 0, 0, 'L', 0, '', 1);
    $pdf->Ln(10);
    $pdf->SetFont('times', 'B', 10);
    $pdf->Cell(100, 4, 'CHRISTIAN GENERAL MOTORS, INC.', 0, 0, 'L', 0, '', 1);
    $pdf->SetFont('times', '', 10);
    $pdf->Ln(10);
    #$pdf->writeHTML($insuranceRequestTable1, true, false, true, false, '');
    $pdf->writeHTML($insuranceRequestTable2, true, false, true, false, '');

    // Output the PDF to the browser
    $pdf->Output('insurance-request.pdf', 'I');
    ob_flush();

    function generateInsuranceRequestTable1($odTheft, $insuranceCoverage){
        $response = '<table border="0.5" width="100%" cellpadding="2" align="center">
                        <thead>
                            <tr>
                                <th width="25%"><b>RISK</b></th>
                                <th width="25%"><b>COVERAGE</b></th>
                                <th width="25%"><b>RATE</b></th>
                                <th width="25%"><b>PREMIUM</b></th>
                            </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>CTPL</td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>OD/THEFT</td>
                            <td>'. $odTheft .'</td>
                            <td>'. $odRate .'</td>
                            <td>'. $odTheftPremium .'</td>
                        </tr>
                        <tr>
                            <td>AON</td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>TPBI</td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>TPPD</td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>PAR</td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td>TOTAL PREMIUM</td>
                            <td></td>
                            <td>'. $odTheftPremium .'</td>
                        </tr>
                        <tr>
                            <td></td>
                            <td>VAT/PREMIUM TAX</td>
                            <td></td>
                            <td>'. $vatPremium .'</td>
                        </tr>
                        <tr>
                            <td></td>
                            <td>DOC. STAMPS</td>
                            <td></td>
                            <td>'. $docStamps .'</td>
                        </tr>
                        <tr>
                            <td></td>
                            <td>LOCAL GOV'. "'" .'T TAX</td>
                            <td></td>
                            <td>'. $localGovtTax .'</td>
                        </tr>
                        <tr>
                            <td></td>
                            <td><b>GROSS</b></td>
                            <td></td>
                            <td>'. $gross .'</td>
                        </tr>
                    </tbody>
            </table>';

        return $response;
    }

    function generateInsuranceRequestTable2($startDate, $insurancePremium, $insuranceCoverage, $insuranceCoverageSecondYear, $insurancePremiumSecondYear, $secondYearInsuranceDate, $insuranceCoverageThirdYear, $insurancePremiumThirdYear, $thirdYearInsuranceDate, $insuranceCoverageFourthYear, $insurancePremiumFourthYear, $fourthYearInsuranceDate){
        $response = '<table border="0.5" width="100%" cellpadding="2" align="center">
                        <thead>
                            <tr>
                                <td></td>
                                <td><b>INSURANCE COVERAGE</b></td>
                                <td><b>INSURANCE PREMIUM</b></td>
                                <td><b>RENEWAL DATE</b></td>
                            </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>1ST YEAR COV</td>
                            <td>'. number_format($insuranceCoverage, 2) .'</td>
                            <td>'. number_format($insurancePremium, 2) .'</td>
                            <td>'. strtoupper($startDate) .'</td>
                        </tr>
                        <tr>
                            <td>2ND YEAR COV</td>
                            <td>'. number_format($insuranceCoverageSecondYear, 2) .'</td>
                            <td>'. number_format($insurancePremiumSecondYear, 2) .'</td>
                            <td>'. strtoupper($secondYearInsuranceDate) .'</td>
                        </tr>
                        <tr>
                            <td>3RD YEAR COV</td>
                            <td>'. number_format($insuranceCoverageThirdYear, 2) .'</td>
                            <td>'. number_format($insurancePremiumThirdYear, 2) .'</td>
                            <td>'. strtoupper($thirdYearInsuranceDate) .'</td>
                        </tr>
                        <tr>
                            <td>4TH YEAR COV</td>
                            <td>'. number_format($insuranceCoverageFourthYear, 2) .'</td>
                            <td>'. number_format($insurancePremiumFourthYear, 2) .'</td>
                            <td>'. strtoupper($fourthYearInsuranceDate) .'</td>
                        </tr>
                    </tbody>
            </table>';

        return $response;
    }
?>