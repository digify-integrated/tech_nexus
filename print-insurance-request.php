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
    require_once 'model/user-model.php';
    require_once 'model/insurance-policy-model.php';
    require_once 'model/insurance-request-model.php';
    require_once 'model/insurance-provider-model.php';
    require_once 'model/insurance-type-model.php';
    require_once 'model/sales-proposal-model.php';
    require_once 'model/customer-model.php';
    require_once 'model/product-model.php';
    require_once 'model/miscellaneous-client-model.php';

    $databaseModel = new DatabaseModel();
    $systemModel = new SystemModel();
    $userModel = new UserModel(new DatabaseModel, new SystemModel);
    $insurancePolicyModel = new InsurancePolicyModel($databaseModel);
    $insuranceRequestModel = new InsuranceRequestModel($databaseModel);
    $insuranceProviderModel = new InsuranceProviderModel($databaseModel);
    $insuranceTypeModel = new InsuranceTypeModel($databaseModel);
    $salesProposalModel = new SalesProposalModel($databaseModel);
    $customerModel = new CustomerModel($databaseModel);
    $miscellaneousClientModel = new MiscellaneousClientModel($databaseModel);
    $productModel = new ProductModel($databaseModel);

    if(isset($_GET['id'])){
        if(empty($_GET['id'])){
          header('location: insurance-request.php');
          exit;
        }
        
        $insuranceRequestID = $_GET['id'];

        $insuranceRequestDetails = $insuranceRequestModel->getInsuranceRequest($insuranceRequestID);
        $customer_type = $insuranceRequestDetails['customer_type'] ?? '';
        $customer_id = $insuranceRequestDetails['customer_id'] ?? '';
        $sales_proposal_id = $insuranceRequestDetails['sales_proposal_id'] ?? '';
        $insurance_type_id = $insuranceRequestDetails['insurance_type_id'] ?? '';
        $insurance_provider = $insuranceRequestDetails['insurance_provider'] ?? '';
        $year_model = $insuranceRequestDetails['year_model'] ?? '';
        $color = $insuranceRequestDetails['color'] ?? '';
        $make = $insuranceRequestDetails['make'] ?? '';
        $plate_number = $insuranceRequestDetails['plate_number'] ?? '';
        $chassis_number = $insuranceRequestDetails['chassis_number'] ?? '';
        $engine_number = $insuranceRequestDetails['engine_number'] ?? '';
        $mv_file_number = $insuranceRequestDetails['mv_file_number'] ?? '';

        if ($customer_type == 'Customer') {
            $customerDetails = $customerModel->getPersonalInformation($customer_id);
            $customerName = $customerDetails['file_as'] ?? '';

            $customerPrimaryAddress = $customerModel->getCustomerPrimaryAddress($customer_id);
            $address = $customerPrimaryAddress['address'] . ', ' . $customerPrimaryAddress['city_name'] . ', ' . $customerPrimaryAddress['state_name'] . ', ' . $customerPrimaryAddress['country_name'];
            $stockNumber = '';

        } elseif ($customer_type == 'Miscellaneous') {
            $miscellaneousClientDetails = $miscellaneousClientModel->getMiscellaneousClient($customer_id);
            $customerName = $miscellaneousClientDetails['client_name'] ?? '';
            $address = $miscellaneousClientDetails['address'] ?? '';
            $stockNumber = '';

        } else {
            $salesProposalDetails = $salesProposalModel->getSalesProposal($sales_proposal_id);
            $customer_id = $salesProposalDetails['customer_id'];
            $productID = $salesProposalDetails['product_id'] ?? '';

            $productDetails = $productModel->getProduct($productID);
            $stockNumber = $productDetails['stock_number']?? '';

            $customerDetails = $customerModel->getPersonalInformation($customer_id);
            $customerName = $customerDetails['file_as'] ?? '';

            $customerPrimaryAddress = $customerModel->getCustomerPrimaryAddress($customer_id);
            $address = $customerPrimaryAddress['address'] . ', ' . $customerPrimaryAddress['city_name'] . ', ' . $customerPrimaryAddress['state_name'] . ', ' . $customerPrimaryAddress['country_name'];
        }

        $inceptionDate = $systemModel->checkDate('empty', $insuranceRequestDetails['inception_date'], '', 'm/d/Y', '');

    }

    $summaryTable = generatePrint($insuranceRequestID);
    $summaryTable2 = generatePrint2($insuranceRequestID);
    $summaryTable3 = generatePrint3($insuranceRequestID);

    ob_start();

    // Create TCPDF instance
    $pdf = new TCPDF('P', 'mm', array(330.2, 215.9), true, 'UTF-8', false);

   // Disable header and footer
    $pdf->setPrintHeader(false);
    $pdf->setPrintFooter(false);
    $pdf->SetPageOrientation('P');

    // Set PDF metadata
    $pdf->SetCreator('CGMI');
    $pdf->SetAuthor('CGMI');
    $pdf->SetTitle('Insurance Request');
    $pdf->SetSubject('Insurance Request');

    // Set margins and auto page break
    $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
    $pdf->SetMargins(15, 15, 15);
    $pdf->SetHeaderMargin(5);
    $pdf->SetFooterMargin(10);
    $pdf->SetAutoPageBreak(TRUE, 15);

    // Add a page
    $pdf->AddPage();

    $pdf->SetFont('times', '', 20);
    $pdf->MultiCell(0, 0, '<b>Christian General Motors Inc.</b>', 0, 'l', 0, 1, '', '', true, 0, true, true, 0);
    $pdf->Ln(5);
    $pdf->SetFont('times', '', 10);
    $pdf->Cell(40, 8, 'NAME:', 0, 0, 'L');
    $pdf->Cell(140, 8, strtoupper($customerName), 'B', 0, 'L');
    $pdf->Ln(8);
    $pdf->Cell(40, 8, 'ADDRESS:', 0, 0, 'L');

    $maxWidth = 140;
    $fontSize = 10;

    $pdf->SetFont('times', '', $fontSize);

    while ($pdf->GetStringWidth(strtoupper($address)) > ($maxWidth - 2) && $fontSize > 5) {
        $fontSize--;
        $pdf->SetFont('times', '', $fontSize);
    }

    $pdf->Cell($maxWidth, 8, strtoupper($address), 'B', 0, 'L');
    $pdf->SetFont('times', '', 10);
    $pdf->Ln(8);
    $pdf->Cell(40, 8, 'INCEPTION DATE:', 0, 0, 'L');
    $pdf->Cell(140, 8, $inceptionDate, 'B', 0, 'L');
    $pdf->Ln(8);
    $pdf->Cell(40, 8, 'UNIT NO:', 0, 0, 'L');
    $pdf->Cell(140, 8, $stockNumber, 'B', 0, 'L');
    $pdf->Ln(8);
    $pdf->Cell(40, 8, 'YEAR MODEL:', 0, 0, 'L');
    $pdf->Cell(140, 8, $year_model, 'B', 0, 'L');
    $pdf->Ln(8);
    $pdf->Cell(40, 8, 'COLOR:', 0, 0, 'L');
    $pdf->Cell(140, 8, strtoupper($color), 'B', 0, 'L');
    $pdf->Ln(8);
    $pdf->Cell(40, 8, 'MAKE/TYPE:', 0, 0, 'L');
    $pdf->Cell(140, 8, strtoupper($make), 'B', 0, 'L');
    $pdf->Ln(8);
    $pdf->Cell(40, 8, 'PLATE NUMBER:', 0, 0, 'L');
    $pdf->Cell(140, 8, $plate_number, 'B', 0, 'L');
    $pdf->Ln(8);
    $pdf->Cell(40, 8, 'CHASSIS NUMBER:', 0, 0, 'L');
    $pdf->Cell(140, 8, $chassis_number, 'B', 0, 'L');
    $pdf->Ln(8);
    $pdf->Cell(40, 8, 'ENGINE NUMBER:', 0, 0, 'L');
    $pdf->Cell(140, 8, $engine_number, 'B', 0, 'L');
    $pdf->Ln(8);
    $pdf->Cell(40, 8, 'MV FILE NUMBER:', 0, 0, 'L');
    $pdf->Cell(140, 8, $mv_file_number, 'B', 0, 'L');
    $pdf->Ln(8);
    $pdf->Cell(40, 8, 'MORTGAGEE:', 0, 0, 'L');
    $pdf->Cell(140, 8, 'CHRISTIAN GENERAL MOTORS INC.', 'B', 0, 'L');
    $pdf->Ln(12);
    $pdf->SetFont('times', '', 9);
    $html = '
        <table cellpadding="2" cellspacing="0" border="0" width="100%">
            <tr>
                <td width="55%" valign="top">
                    ' . $summaryTable . '
                </td>
                <td width="45%" valign="top">
                    ' . $summaryTable2 . '
                </td>
            </tr>
        </table>';

    $pdf->writeHTML($html, true, false, true, false, '');
    $pdf->writeHTML($summaryTable3, true, false, true, false, '');
    $pdf->Output('insurance-request.pdf', 'I');
    ob_end_flush();

    function generatePrint($insuranceRequestID){
        require_once 'model/database-model.php';
        require_once 'model/pdc-management-model.php';
        require_once 'model/system-model.php';
        require_once 'model/user-model.php';
        require_once 'model/insurance-policy-model.php';
        require_once 'model/insurance-request-model.php';
        require_once 'model/insurance-provider-model.php';
        require_once 'model/insurance-type-model.php';
        require_once 'model/sales-proposal-model.php';
        require_once 'model/customer-model.php';
        require_once 'model/product-model.php';
        require_once 'model/miscellaneous-client-model.php';

        $databaseModel = new DatabaseModel();
        $systemModel = new SystemModel();
        $userModel = new UserModel(new DatabaseModel, new SystemModel);
        $insurancePolicyModel = new InsurancePolicyModel($databaseModel);
        $insuranceRequestModel = new InsuranceRequestModel($databaseModel);
        $insuranceProviderModel = new InsuranceProviderModel($databaseModel);
        $insuranceTypeModel = new InsuranceTypeModel($databaseModel);
        $salesProposalModel = new SalesProposalModel($databaseModel);
        $customerModel = new CustomerModel($databaseModel);
        $miscellaneousClientModel = new MiscellaneousClientModel($databaseModel);
        $productModel = new ProductModel($databaseModel);

        $insuranceRequestDetails = $insuranceRequestModel->getInsuranceRequest($insuranceRequestID);
        $insurance_category = $insuranceRequestDetails['insurance_category'];
        $ctpl_coverage = $insuranceRequestDetails['ctpl_coverage'];
        $ctpl_rate = $insuranceRequestDetails['ctpl_rate'];
        $od_theft_coverage = $insuranceRequestDetails['od_theft_coverage'];
        $od_theft_rate = $insuranceRequestDetails['od_theft_rate'];
        $all_risk_coverage = $insuranceRequestDetails['all_risk_coverage'];
        $all_risk_rate = $insuranceRequestDetails['all_risk_rate'];
        $aon_coverage = $insuranceRequestDetails['aon_coverage'];
        $aon_rate = $insuranceRequestDetails['aon_rate'];
        $tpbi_coverage = $insuranceRequestDetails['tpbi_coverage'];
        $tppd_coverage = $insuranceRequestDetails['tppd_coverage'];
        $par_coverage = $insuranceRequestDetails['par_coverage'];
        $ctpl_premium = $insuranceRequestDetails['ctpl_premium'];
        $od_theft_premium = $insuranceRequestDetails['od_theft_premium'];
        $all_risk_premium = $insuranceRequestDetails['all_risk_premium'];
        $aon_premium = $insuranceRequestDetails['aon_premium'];
        $tpbi_premium = $insuranceRequestDetails['tpbi_premium'];
        $tppd_premium = $insuranceRequestDetails['tppd_premium'];
        $par_premium = $insuranceRequestDetails['par_premium'];
        $par_rate = $insuranceRequestDetails['par_rate'];
        $total_premium = $insuranceRequestDetails['total_premium'];
        $vat_premium_tax = $insuranceRequestDetails['vat_premium_tax'];
        $docstamp = $insuranceRequestDetails['docstamp'];
        $local_tax = $insuranceRequestDetails['local_tax'];
        $gross = $insuranceRequestDetails['gross'];
        $premium_commission = $insuranceRequestDetails['premium_commission'];
        $aon_commission = $insuranceRequestDetails['aon_commission'];
        $tpbi_commission = $insuranceRequestDetails['tpbi_commission'];
        $tppd_commission = $insuranceRequestDetails['tppd_commission'];
        $par_commission = $insuranceRequestDetails['par_commission'];
        $commission_subtotal = $insuranceRequestDetails['commission_subtotal'];
        $commission_discount = $insuranceRequestDetails['commission_discount'];
        $net_commission = $insuranceRequestDetails['net_commission'];
        $net_premium = $insuranceRequestDetails['net_premium'];

        if($od_theft_coverage > 0){
            $coverage_type = '<tr>
                                <td>OD/THEFT</td>
                                <td>'. number_format($od_theft_coverage, 2) .'</td>
                                <td>'. number_format($od_theft_rate, 2) .'%</td>
                                <td>'. number_format($od_theft_premium, 2) .'</td>
                            </tr>';
        }
        else{
            $coverage_type = '<tr>
                                <td>ALL RISK</td>
                                <td>'. number_format($all_risk_coverage, 2) .'</td>
                                <td>'. number_format($all_risk_rate, 2) .'%</td>
                                <td>'. number_format($all_risk_premium, 2) .'</td>
                            </tr>';
        }

    
        $response = '<table border="1" width="100%" cellpadding="5" align="left">
                        <thead>
                            <tr style="text-align:center">
                                <td><b>RISK</b></td>
                                <td><b>COVERAGE</b></td>
                                <td><b>RATE</b></td>
                                <td><b>PREMIUM</b></td>
                            </tr>
                        </thead>
                        <tbody>
                            '. $coverage_type .'
                            <tr>
                                <td>CTPL</td>
                                <td>'. number_format($ctpl_coverage, 2) .'</td>
                                <td>'. number_format($ctpl_rate, 2) .'%</td>
                                <td>'. number_format($ctpl_premium, 2) .'</td>
                            </tr>
                            <tr>
                                <td>AON</td>
                                <td>'. number_format($aon_coverage, 2) .'</td>
                                <td>'. number_format($aon_rate, 2) .'%</td>
                                <td>'. number_format($aon_premium, 2) .'</td>
                            </tr>
                            <tr>
                                <td>TPBI</td>
                                <td>'. number_format($tpbi_coverage, 2) .'</td>
                                <td></td>
                                <td>'. number_format($tpbi_premium, 2) .'</td>
                            </tr>
                            <tr>
                                <td>TPPD</td>
                                <td>'. number_format($tppd_coverage, 2) .'</td>
                                <td></td>
                                <td>'. number_format($tppd_premium, 2) .'</td>
                            </tr>
                            <tr>
                                <td>PAR</td>
                                <td>'. number_format($par_coverage, 2) .'</td>
                                <td>'. number_format($par_rate, 2) .'%</td>
                                <td>'. number_format($par_premium, 2) .'</td>
                            </tr>
                            <tr>
                                <td colspan="3">TOTAL PREMIUM</td>
                                <td>'. number_format($total_premium, 2) .'</td>
                            </tr>
                            <tr>
                                <td colspan="3">VAT/PREMIUM TAX</td>
                                <td>'. number_format($vat_premium_tax, 2) .'</td>
                            </tr>
                            <tr>
                                <td colspan="3">DOC. STAMPS</td>
                                <td>'. number_format($docstamp, 2) .'</td>
                            </tr>
                            <tr>
                                <td colspan="3">LOCAL GOVERNMENT TAX</td>
                                <td>'. number_format($local_tax, 2) .'</td>
                            </tr>
                            <tr>
                                <td colspan="3">GROSS</td>
                                <td>'. number_format($gross, 2) .'</td>
                            </tr>
                        </tbody>
                    </table>';

        return $response;
    }

    function generatePrint2($insuranceRequestID){
        require_once 'model/database-model.php';
        require_once 'model/pdc-management-model.php';
        require_once 'model/system-model.php';
        require_once 'model/user-model.php';
        require_once 'model/insurance-policy-model.php';
        require_once 'model/insurance-request-model.php';
        require_once 'model/insurance-provider-model.php';
        require_once 'model/insurance-type-model.php';
        require_once 'model/sales-proposal-model.php';
        require_once 'model/customer-model.php';
        require_once 'model/product-model.php';
        require_once 'model/miscellaneous-client-model.php';

        $databaseModel = new DatabaseModel();
        $systemModel = new SystemModel();
        $userModel = new UserModel(new DatabaseModel, new SystemModel);
        $insurancePolicyModel = new InsurancePolicyModel($databaseModel);
        $insuranceRequestModel = new InsuranceRequestModel($databaseModel);
        $insuranceProviderModel = new InsuranceProviderModel($databaseModel);
        $insuranceTypeModel = new InsuranceTypeModel($databaseModel);
        $salesProposalModel = new SalesProposalModel($databaseModel);
        $customerModel = new CustomerModel($databaseModel);
        $miscellaneousClientModel = new MiscellaneousClientModel($databaseModel);
        $productModel = new ProductModel($databaseModel);

        $insuranceRequestDetails = $insuranceRequestModel->getInsuranceRequest($insuranceRequestID);
        $insurance_category = $insuranceRequestDetails['insurance_category'];
        $ctpl_coverage = $insuranceRequestDetails['ctpl_coverage'];
        $od_theft_coverage = $insuranceRequestDetails['od_theft_coverage'];
        $aon_coverage = $insuranceRequestDetails['aon_coverage'];
        $tpbi_coverage = $insuranceRequestDetails['tpbi_coverage'];
        $tppd_coverage = $insuranceRequestDetails['tppd_coverage'];
        $par_coverage = $insuranceRequestDetails['par_coverage'];
        $ctpl_premium = $insuranceRequestDetails['ctpl_premium'];
        $od_theft_premium = $insuranceRequestDetails['od_theft_premium'];
        $aon_premium = $insuranceRequestDetails['aon_premium'];
        $tpbi_premium = $insuranceRequestDetails['tpbi_premium'];
        $tppd_premium = $insuranceRequestDetails['tppd_premium'];
        $par_premium = $insuranceRequestDetails['par_premium'];
        $par_premium = $insuranceRequestDetails['par_premium'];
        $total_premium = $insuranceRequestDetails['total_premium'];
        $vat_premium_tax = $insuranceRequestDetails['vat_premium_tax'];
        $docstamp = $insuranceRequestDetails['docstamp'];
        $local_tax = $insuranceRequestDetails['local_tax'];
        $gross = $insuranceRequestDetails['gross'];
        $premium_commission = $insuranceRequestDetails['premium_commission'];
        $aon_commission = $insuranceRequestDetails['aon_commission'];
        $tpbi_commission = $insuranceRequestDetails['tpbi_commission'];
        $tppd_commission = $insuranceRequestDetails['tppd_commission'];
        $par_commission = $insuranceRequestDetails['par_commission'];
        $commission_subtotal = $insuranceRequestDetails['commission_subtotal'];
        $commission_discount = $insuranceRequestDetails['commission_discount'];
        $net_commission = $insuranceRequestDetails['net_commission'];
        $net_premium = $insuranceRequestDetails['net_premium'];
        $premium_rate = $insuranceRequestDetails['premium_rate'];
        $par_commission_rate = $insuranceRequestDetails['par_commission_rate'];
        $tppd_commission_rate = $insuranceRequestDetails['tppd_commission_rate'];
        $aon_commission_rate = $insuranceRequestDetails['aon_commission_rate'];
        $commission_discount_rate = $insuranceRequestDetails['commission_discount_rate'];
        $premium_commission_rate = $insuranceRequestDetails['premium_commission_rate'];

        $response = '<table border="1" width="100%" cellpadding="5" align="left">
                        <thead>
                            <tr style="text-align:center">
                                <td colspan="3"><b>COMMISSION</b></td>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>PREMIUM</td>
                                <td>'. number_format($premium_commission_rate, 2) .'% </td>
                                <td>'. number_format($premium_commission, 2) .'</td>
                            </tr>
                            <tr>
                                <td>AON</td>
                                <td>'. number_format($aon_commission_rate, 2) .'%</td>
                                <td>'. number_format($aon_commission, 2) .'</td>
                            </tr>
                            <tr>
                                <td>TPBI</td>
                                <td>'. number_format($tpbi_commission_rate, 2) .'%</td>
                                <td>'. number_format($tpbi_commission, 2) .'</td>
                            </tr>
                            <tr>
                                <td>TPPD</td>
                                <td>'. number_format($tppd_commission_rate, 2) .'%</td>
                                <td>'. number_format($tppd_commission, 2) .'</td>
                            </tr>
                            <tr>
                                <td>PAR</td>
                                <td>'. number_format($par_commission_rate, 2) .'%</td>
                                <td>0.00</td>
                            </tr>
                            <tr>
                                <td>LESS 10% WTP</td>
                                <td>'. number_format($commission_discount_rate, 2) .'%</td>
                                <td>'. number_format($commission_discount, 2) .'</td>
                            </tr>
                            <tr>
                                <td colspan="2">NET COMMISSION</td>
                                <td>'. number_format($net_commission, 2) .'</td>
                            </tr>
                        </tbody>
                    </table>';

        return $response;
    }

    function generatePrint3($insuranceRequestID){
        require_once 'model/database-model.php';
        require_once 'model/pdc-management-model.php';
        require_once 'model/system-model.php';
        require_once 'model/user-model.php';
        require_once 'model/insurance-policy-model.php';
        require_once 'model/insurance-request-model.php';
        require_once 'model/insurance-provider-model.php';
        require_once 'model/insurance-type-model.php';
        require_once 'model/sales-proposal-model.php';
        require_once 'model/customer-model.php';
        require_once 'model/product-model.php';
        require_once 'model/miscellaneous-client-model.php';

        $databaseModel = new DatabaseModel();
        $systemModel = new SystemModel();
        $userModel = new UserModel(new DatabaseModel, new SystemModel);
        $insurancePolicyModel = new InsurancePolicyModel($databaseModel);
        $insuranceRequestModel = new InsuranceRequestModel($databaseModel);
        $insuranceProviderModel = new InsuranceProviderModel($databaseModel);
        $insuranceTypeModel = new InsuranceTypeModel($databaseModel);
        $salesProposalModel = new SalesProposalModel($databaseModel);
        $customerModel = new CustomerModel($databaseModel);
        $miscellaneousClientModel = new MiscellaneousClientModel($databaseModel);
        $productModel = new ProductModel($databaseModel);

        $insuranceRequestDetails = $insuranceRequestModel->getInsuranceRequest($insuranceRequestID);
        $insurance_category = $insuranceRequestDetails['insurance_category'];
        $ctpl_coverage = $insuranceRequestDetails['ctpl_coverage'];
        $od_theft_coverage = $insuranceRequestDetails['od_theft_coverage'];
        $aon_coverage = $insuranceRequestDetails['aon_coverage'];
        $tpbi_coverage = $insuranceRequestDetails['tpbi_coverage'];
        $tppd_coverage = $insuranceRequestDetails['tppd_coverage'];
        $par_coverage = $insuranceRequestDetails['par_coverage'];
        $ctpl_premium = $insuranceRequestDetails['ctpl_premium'];
        $od_theft_premium = $insuranceRequestDetails['od_theft_premium'];
        $aon_premium = $insuranceRequestDetails['aon_premium'];
        $tpbi_premium = $insuranceRequestDetails['tpbi_premium'];
        $tppd_premium = $insuranceRequestDetails['tppd_premium'];
        $par_premium = $insuranceRequestDetails['par_premium'];
        $par_premium = $insuranceRequestDetails['par_premium'];
        $total_premium = $insuranceRequestDetails['total_premium'];
        $vat_premium_tax = $insuranceRequestDetails['vat_premium_tax'];
        $docstamp = $insuranceRequestDetails['docstamp'];
        $local_tax = $insuranceRequestDetails['local_tax'];
        $gross = $insuranceRequestDetails['gross'];
        $premium_commission = $insuranceRequestDetails['premium_commission'];
        $aon_commission = $insuranceRequestDetails['aon_commission'];
        $tpbi_commission = $insuranceRequestDetails['tpbi_commission'];
        $tppd_commission = $insuranceRequestDetails['tppd_commission'];
        $par_commission = $insuranceRequestDetails['par_commission'];
        $commission_subtotal = $insuranceRequestDetails['commission_subtotal'];
        $commission_discount = $insuranceRequestDetails['commission_discount'];
        $net_commission = $insuranceRequestDetails['net_commission'];
        $net_premium = $insuranceRequestDetails['net_premium'];

        if($insurance_category == 'Truck'){
            $od_theft_rate = '2.40%';
            $premium_rate = '26.00%';

        }
        else if($insurance_category == 'Equipment'){
            $od_theft_rate = '1.50%';
            $premium_rate = '20.00%';
        }
        else if($insurance_category == 'Equipment with AON'){
            $od_theft_rate = '1.85%';
            $premium_rate = '20.00%';
        }
        else{
            $od_theft_rate = '0.00%';
            $premium_rate = '0.00%';
        }    
    
        $response = '<table border="1" width="50%" cellpadding="5" align="left">
                        <tbody>
                            <tr>
                                <td>GROSS</td>
                                <td>'. number_format($gross, 2) .'</td>
                            </tr>
                            <tr>
                                <td>LESS COMMISSION</td>
                                <td>'. number_format($net_commission, 2) .'</td>
                            </tr>
                            <tr>
                                <td>NET PREMIUM</td>
                                <td>'. number_format($net_premium, 2) .'</td>
                            </tr>
                        </tbody>
                    </table>';

        return $response;
    }
?>