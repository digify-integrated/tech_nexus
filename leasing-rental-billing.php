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
    require_once 'model/leasing-application-model.php';
    require_once 'model/tenant-model.php';
    require_once 'model/property-model.php';
    require_once 'model/company-model.php';
    require_once 'model/city-model.php';
    require_once 'model/state-model.php';
    require_once 'model/country-model.php';


    // Initialize database model
    $databaseModel = new DatabaseModel();

    // Initialize system model
    $systemModel = new SystemModel();

    // Initialize sales proposal model
    $leasingApplicationModel = new LeasingApplicationModel($databaseModel);
    $tenantModel = new TenantModel($databaseModel);
    $propertyModel = new PropertyModel($databaseModel);
    $companyModel = new CompanyModel($databaseModel);
    $cityModel = new CityModel($databaseModel);
    $stateModel = new StateModel($databaseModel);
    $countryModel = new CountryModel($databaseModel);

    if(isset($_GET['id'])){
        if(empty($_GET['id'])){
          header('location: dashboard.php');
          exit;
        }
        
        $leasingID = $_GET['leasing'];
        $repaymentID = explode(',', $_GET['id']);

        $leasingApplicationDetails = $leasingApplicationModel->getLeasingApplication($leasingID);
        $tenantID = $leasingApplicationDetails['tenant_id'];
        $propertyID = $leasingApplicationDetails['property_id'];
        $initialBasicRental = $leasingApplicationDetails['initial_basic_rental'];

        $tenantDetails = $tenantModel->getTenant($tenantID);
        $tenantName = strtoupper($tenantDetails['tenant_name'] ?? '');

        $propertyDetails = $propertyModel->getProperty($propertyID);
        $propertyName = strtoupper($propertyDetails['property_name'] ?? '');
        $address = strtoupper($propertyDetails['address'] ?? '');

        $companyID = $propertyDetails['company_id'] ?? '';
        $companyDetails = $companyModel->getCompany($companyID);
        
        $companyName = $companyDetails['company_name'] ?? '';
        $companyCityID = $companyDetails['city_id'];
        $companyAddress = $companyDetails['address'];

        $cityDetails = $cityModel->getCity($companyCityID);
        $companyCityName = $cityDetails['city_name'] ?? '';
        $stateID = $cityDetails['state_id'];

        $stateDetails = $stateModel->getState($stateID);
        $companyStateName = $stateDetails['state_name'] ?? '';
        $countryID = $stateDetails['country_id'];

        $companyCountryName = $countryModel->getCountry($countryID)['country_name'] ?? '';

        $companyFullAddress = $companyAddress . ', ' . $companyCityName . ', ' . $companyStateName . ', ' . $companyCountryName;
    }

    $summaryTable = generateBillingSummaryTable($repaymentID, $leasingID, $initialBasicRental);
    $billingTable = generateBillingTable($repaymentID, $leasingID, $initialBasicRental);

    ob_start();

    // Create TCPDF instance
    $pdf = new TCPDF('P', 'mm', array(215.9, 330.2), true, 'UTF-8', false);

    // Disable header and footer
    $pdf->setPrintHeader(false);
    $pdf->setPrintFooter(false);

    // Set PDF metadata
    $pdf->SetCreator('CGMI');
    $pdf->SetAuthor('CGMI');
    $pdf->SetTitle('Leasing Rental Billing');
    $pdf->SetSubject('Leasing Rental Billing');

    // Set margins and auto page break
    $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
    $pdf->SetMargins(15, 15, 15);
    $pdf->SetHeaderMargin(5);
    $pdf->SetFooterMargin(10);
    $pdf->SetAutoPageBreak(TRUE, 15);

    // Add a page
    $pdf->AddPage();

    // Add content
    $pdf->SetFont('times', 'B', 10.5);
    $pdf->Cell(152, 8, strtoupper($companyName) , 0, 0, 'L');
    $pdf->Ln(5);
    $pdf->SetFont('times', '', 8);
    $pdf->Cell(152, 8, strtoupper($companyFullAddress), 0, 0, 'L');
    $pdf->Ln(10);
    $pdf->SetFont('times', 'B', 10.5);
    $pdf->Cell(152, 8, 'RENTAL BILLING NOTICE'  , 0, 0, 'L');
    $pdf->SetFont('times', '', 10.5);
    $pdf->Cell(32, 8, 'DATE: ' . date('m/d/Y'), 0, 0, 'L');
    $pdf->Ln(10);
    $pdf->SetFont('times', 'B', 10.5);
    $pdf->Cell(50, 8, 'NAME OF TENANT:'  , 0, 0, 'L');
    $pdf->SetFont('times', '', 10.5);
    $pdf->Cell(32, 8, $tenantName, 0, 0, 'L');
    $pdf->Ln(7);
    $pdf->SetFont('times', 'B', 10.5);
    $pdf->Cell(50, 8, 'LEASED PREMISES:'  , 0, 0, 'L');
    $pdf->SetFont('times', '', 10.5);
    $pdf->Cell(135, 8, $propertyName . ' - ' . $address, 0, 0, 'L', 0, '', 1);
    $pdf->Ln(10);
    $pdf->SetFont('times', '', 9);
    $pdf->writeHTML($summaryTable, true, false, true, false, '');
    $pdf->Ln(0);
    $pdf->SetFont('times', '', 9);
    $pdf->writeHTML($billingTable, true, false, true, false, '');
    $pdf->Ln(0);
    $pdf->SetFont('times', '', 10.5);
    $pdf->Cell(50, 8, 'This is a computer generated document. No signature is required.'  , 0, 0, 'L');
    $pdf->Ln(10);
    $pdf->SetFont('times', '', 8);
    $pdf->Cell(50, 8, 'RECEIVED BY:'  , 0, 0, 'L');
    $pdf->SetFont('times', '', 10.5);
    $pdf->Ln(15);
    $pdf->Cell(90, 4, '', 'B', 0, 'C');
    $pdf->Ln(5);
    $pdf->Cell(90, 8, 'SIGNATURE OVER PRINTED NAME/DATE', 0, 0, 'C');


    // Output the PDF to the browser
    $pdf->Output('sales-invoice.pdf', 'I');
    ob_end_flush();

    function generateBillingSummaryTable($repaymentID, $leasingID, $initialBasicRental){
        
        require_once 'model/database-model.php';
        require_once 'model/leasing-application-model.php';

        $databaseModel = new DatabaseModel();
        $leasingApplicationModel = new LeasingApplicationModel($databaseModel);

        $repaymentIDs = is_array($repaymentID) ? $repaymentID : explode(',', $repaymentID);
        sort($repaymentIDs);

        $response = '<table border="0.5" width="40%" cellpadding="2" align="center">
                        <tbody>';

        $totalAmount = 0;

        $count = count($repaymentIDs);
        $i = 0;

        foreach ($repaymentIDs as $repaymentID) {
            $leasingApplicationRepaymentDetails = $leasingApplicationModel->getLeasingApplicationRepayment($repaymentID);
            $dueDate = $leasingApplicationRepaymentDetails['due_date'];
            $from = date('m/d/Y', strtotime($dueDate));
            $unpaidRental = $leasingApplicationRepaymentDetails['unpaid_rental'];
            $paidRental = $leasingApplicationRepaymentDetails['paid_rental'];
            $totalRental = $paidRental + $unpaidRental;

            $dueAmount = $totalRental - $paidRental;
            $totalAmount = $totalAmount + $dueAmount;

            if ($i == $count - 1) {
                $response.= '<tr>
                                    <td>DUE DATE</td>
                                    <td>'. $from.'</td>
                                </tr>
                                <tr>
                                    <td>TOTAL DUE AMOUNT</td>
                                    <td>'. number_format($totalAmount, 2).'</td>
                                </tr>';
            }
            $i++;
        }

        $response .= '
                    </tbody>
            </table>';

        return $response;
    }

    function generateBillingTable($repaymentID, $leasingID, $initialBasicRental){
        
        require_once 'model/database-model.php';
        require_once 'model/leasing-application-model.php';

        $databaseModel = new DatabaseModel();
        $leasingApplicationModel = new LeasingApplicationModel($databaseModel);

        $repaymentIDs = is_array($repaymentID) ? $repaymentID : explode(',', $repaymentID);
        sort($repaymentIDs);

        $response = '<table border="0.5" width="100%" cellpadding="2" align="center">
                        <tbody>
                        <tr>
                            <td rowspan="2">BILLING MONTH</td>
                            <td colspan="2">COVERED PERIOD</td>
                            <td rowspan="2">DUE DATE</td>
                            <td rowspan="2">MONTHLY RENTAL</td>
                            <td rowspan="2">VAT</td>
                            <td rowspan="2">W/TAX</td>
                            <td rowspan="2">NET RENTAL</td>
                            <td rowspan="2">PAID</td>
                            <td rowspan="2">DUE AMOUNT</td>
                        </tr>
                        <tr>
                            <td>FROM</td>
                            <td>TO</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>';

        $totalAmount = 0;
        foreach ($repaymentIDs as $repaymentID) {
            $leasingApplicationDetails = $leasingApplicationModel->getLeasingApplication($leasingID);
            $vat = $leasingApplicationDetails['vat'];
            $witholdingTax = $leasingApplicationDetails['witholding_tax'];
            $initialBasicRental = $leasingApplicationDetails['initial_basic_rental'];

            $leasingApplicationRepaymentDetails = $leasingApplicationModel->getLeasingApplicationRepayment($repaymentID);
            $dueDate = $leasingApplicationRepaymentDetails['due_date'];
            $month = strtoupper(date('M', strtotime($dueDate))); 
            $year = substr(strtoupper(date('Y', strtotime($dueDate))), -2);
            $billingMonth = date('m/d/Y', strtotime($dueDate));
            $from = date('m/d/Y', strtotime($dueDate));
            $to = date('m/d/Y', strtotime('+1 month', strtotime($dueDate)));
            $unpaidRental = $leasingApplicationRepaymentDetails['unpaid_rental'];
            $paidRental = $leasingApplicationRepaymentDetails['paid_rental'];
            $totalRental = $paidRental + $unpaidRental;
            
            $due = strtoupper(date('m/d/Y', strtotime($dueDate))); 

            if($vat == 'Yes' && $witholdingTax == 'Yes'){
                $baseTotalRental = $totalRental / (1.07);
            }
            else if($vat == 'Yes' && $witholdingTax == 'No'){
                $baseTotalRental = $totalRental / (1.12);
            }
            else if($vat == 'No' && $witholdingTax == 'Yes'){
                $baseTotalRental = $totalRental / (1.05);
            }
            else{
                $baseTotalRental = $totalRental;
            }

            if($vat == 'Yes'){
                $vatAmount = $baseTotalRental * (0.12);
            }
            else{
                $vatAmount = '0.00';
            }

            if($witholdingTax == 'Yes'){
                $witholdingTaxAmount = ($baseTotalRental * (0.05) * -1);
            }
            else{
                $witholdingTaxAmount = '0.00';
            }

            $dueAmount = $totalRental - $paidRental;
            $totalAmount = $totalAmount + $dueAmount;

            if(number_format($unpaidRental, 2, '.', '') > 0){
                $response .= ' <tr>
                        <td>'. $month .'-'. $year .'</td>
                        <td>'. $from .'</td>
                        <td>'. $to .'</td>
                        <td>'. $due .'</td>
                        <td>'. number_format($baseTotalRental, 2) .'</td>
                        <td>'. number_format($vatAmount, 2) .'</td>
                        <td>'. number_format($witholdingTaxAmount, 2) .'</td>
                        <td>'. number_format($totalRental, 2) .'</td>
                        <td>'. number_format($paidRental, 2) .'</td>
                        <td>'. number_format($dueAmount, 2) .'</td>
                    </tr>';
            }
        }

        $response .= ' <tr>
                            <td colspan="9" align="right"><b>TOTAL</b></td>
                            <td><b>'. number_format($totalAmount, 2) .'</b></td>
                        </tr>';

        $response .= '
                    </tbody>
            </table>';

        return $response;
    }
?>