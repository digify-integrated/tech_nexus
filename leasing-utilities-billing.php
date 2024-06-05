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
        $companyCityID = $companyDetails['cityID'] ?? null;
        $companyAddress = $companyDetails['address'] ?? null;

        $cityDetails = $cityModel->getCity($companyCityID);
        $companyCityName = $cityDetails['city_name'] ?? null;
        $stateID = $cityDetails['state_id'] ?? null;

        $stateDetails = $stateModel->getState($stateID);
        $companyStateName = $stateDetails['state_name'] ?? null;
        $countryID = $stateDetails['country_id'] ?? null;

        $companyCountryName = $countryModel->getCountry($countryID)['country_name'] ?? null;

        $companyFullAddress = $companyAddress . ', ' . $companyCityName . ', ' . $companyStateName . ', ' . $companyCountryName;
    }

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
    $pdf->SetFont('times', 'B', 10.5);
    $pdf->Cell(152, 8, strtoupper($companyName) , 0, 0, 'L');
    $pdf->Ln(5);
    $pdf->SetFont('times', '', 8);
    $pdf->Cell(152, 8, strtoupper($companyFullAddress), 0, 0, 'L');
    $pdf->Ln(10);
    $pdf->SetFont('times', 'B', 10.5);
    $pdf->Cell(152, 8, 'UTILITIES BILLING NOTICE'  , 0, 0, 'L');
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
    $pdf->writeHTML($billingTable, true, false, true, false, '');
    $pdf->Ln(0);
    $pdf->SetFont('times', '', 10.5);
    $pdf->Cell(50, 8, 'This is a computer document. No signature is required.'  , 0, 0, 'L');
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
                            <td rowspan="2">TYPE</td>
                            <td colspan="2">COVERED PERIOD</td>
                            <td rowspan="2">BILLED AMOUNT</td>
                            <td rowspan="2">DUE PAID</td>
                            <td rowspan="2">DUE AMOUNT</td>
                        </tr>
                        <tr>
                            <td>FROM</td>
                            <td>TO</td>
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

            $otherChargesDetails = $leasingApplicationModel->getAllLeasingOtherCharges($repaymentID);

            foreach($otherChargesDetails as $otherCharges){
                $dueAmount = $otherCharges['due_amount'];
                $duePaid = $otherCharges['due_paid'];
                $otherChargesType = $otherCharges['other_charges_type'];
                $coverageStartDate = $otherCharges['coverage_start_date'];
                $coverageEndDate = $otherCharges['coverage_end_date'];
                $total = $dueAmount + $duePaid;

                $totalAmount = $totalAmount + $dueAmount;


                $from = date('m/d/Y', strtotime($coverageStartDate));
                $to = date('m/d/Y', strtotime($coverageEndDate));

                if(number_format($dueAmount, 2, '.', '') > 0){
                    $response .= ' <tr>
                            <td>'. strtoupper($otherChargesType) .'</td>
                            <td>'. $from .'</td>
                            <td>'. $to .'</td>
                            <td>'. number_format($total, 2) .'</td>
                            <td>'. number_format($duePaid, 2) .'</td>
                            <td>'. number_format($dueAmount, 2) .'</td>
                        </tr>';
                }
            }
        }

        $response .= ' <tr>
                            <td colspan="5" align="right"><b>TOTAL</b></td>
                            <td><b>'. number_format($totalAmount, 2) .'</b></td>
                        </tr>';

        $response .= '
                    </tbody>
            </table>';

        return $response;
    }
?>