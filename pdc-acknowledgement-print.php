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
    require_once 'model/tenant-model.php';
    require_once 'model/city-model.php';
    require_once 'model/state-model.php';
    require_once 'model/country-model.php';
    require_once 'model/leasing-application-model.php';

    $databaseModel = new DatabaseModel();
    $systemModel = new SystemModel();
    $pdcManagementModel = new PDCManagementModel($databaseModel);
    $customerModel = new CustomerModel($databaseModel);
    $productModel = new ProductModel($databaseModel);
    $salesProposalModel = new SalesProposalModel($databaseModel);
    $userModel = new UserModel($databaseModel, $systemModel);
    $tenantModel = new TenantModel($databaseModel);
    $cityModel = new CityModel($databaseModel);
    $stateModel = new StateModel($databaseModel);
    $countryModel = new CountryModel($databaseModel);
    $leasingApplicationModel = new LeasingApplicationModel($databaseModel);
    
    if(isset($_GET['id'])){
        if(empty($_GET['id'])){
          header('location: dashboard.php');
          exit;
        }
        
        $loanCollectionIDs = explode(',', $_GET['id']);

        $defaultID = $loanCollectionIDs[0];
        $pdcManagementDetails = $pdcManagementModel->getPDCManagement($defaultID);

        $customer_id = $pdcManagementDetails['customer_id'];
        $sales_proposal_id = $pdcManagementDetails['sales_proposal_id'];
        $pdc_type = $pdcManagementDetails['pdc_type'];
        $leasingApplicationID = $pdcManagementDetails['leasing_application_id'];

        $customerPrimaryAddress = $customerModel->getCustomerPrimaryAddress($customer_id);
        $customerAddress = strtoupper($customerPrimaryAddress['address'] . ', ' . $customerPrimaryAddress['city_name'] . ', ' . $customerPrimaryAddress['state_name'] . ', ' . $customerPrimaryAddress['country_name']);

        $salesProposalDetails = $salesProposalModel->getSalesProposal($sales_proposal_id);
        $product_id = $salesProposalDetails['product_id'] ?? null;
        $loan_number = $salesProposalDetails['loan_number'] ?? null;

        $productDetails = $productModel->getProduct($product_id);
        $stock_number = $productDetails['stock_number'] ?? null;
        $plateNumber = $productDetails['plate_number'] ?? null;

        $customerDetails = $customerModel->getPersonalInformation($customer_id);
        $customerName = $customerDetails['file_as'] ?? null;

        if($pdc_type == 'Leasing'){
            $leasingApplicationDetails = $leasingApplicationModel->getLeasingApplication($leasingApplicationID);
            $tenant_id = $leasingApplicationDetails['tenant_id'] ?? null;

            $tenantDetails = $tenantModel->getTenant($tenant_id);
            $customerName = strtoupper($tenantDetails['tenant_name'] ?? '');
            $address = $tenantDetails['address'] ?? null;

            $cityID = $tenantDetails['city_id'] ?? null;

            $cityDetails = $cityModel->getCity($cityID);
            $cityName = $cityDetails['city_name'] ?? null;
            $stateID = $cityDetails['state_id'] ?? null;

            $stateDetails = $stateModel->getState($stateID);
            $stateName = $stateDetails['state_name'] ?? null;
            $countryID = $stateDetails['country_id'] ?? null;

            $countryName = $countryModel->getCountry($countryID)['country_name'] ?? null;

            $customerAddress = strtoupper($address . ', ' . $cityName . ', ' . $stateName . ', ' . $countryName);
        }

        $totalPDCAmount = 0;
        $count = 0;

        foreach ($loanCollectionIDs as $loanCollectionID) {
            $pdcManagementDetails = $pdcManagementModel->getPDCManagement($loanCollectionID);
            $collection_status = $pdcManagementDetails['collection_status'];
            $payment_amount = $pdcManagementDetails['payment_amount'];

            $totalPDCAmount = $totalPDCAmount + $payment_amount;

            $count = $count + 1;
        }
    }

    $summaryTable = generatePDC($loanCollectionIDs, $totalPDCAmount, $count, $customerName, $plateNumber, $loan_number, $stock_number, $customerAddress);

    ob_start();

    // Create TCPDF instance
    $pdf = new TCPDF('P', 'mm', array(215.9, 330.2), true, 'UTF-8', false);

    // Disable header and footer
    $pdf->setPrintHeader(false);
    $pdf->setPrintFooter(false);
    $pdf->SetPageOrientation('P');

    // Set PDF metadata
    $pdf->SetCreator('CGMI');
    $pdf->SetAuthor('CGMI');
    $pdf->SetTitle('PDC Print');
    $pdf->SetSubject('PDC Print');

    // Set margins and auto page break
    $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
    $pdf->SetMargins(10, 10, 10);
    $pdf->SetHeaderMargin(5);
    $pdf->SetFooterMargin(10);
    $pdf->SetAutoPageBreak(TRUE, 15);

    // Add a page
    $pdf->AddPage();

    $pdf->SetFont('times', '', 8);
    $pdf->writeHTML($summaryTable, true, false, true, false, '');


    // Output the PDF to the browser
    $pdf->Output('apdc-print.pdf', 'I');
    ob_end_flush();


    function generatePDC($loanCollectionID, $totalPDCAmount, $count, $customerName, $plateNumber, $loan_number, $stock_number, $customerAddress){

        require_once 'model/database-model.php';
        require_once 'model/pdc-management-model.php';
        require_once 'model/system-model.php';
        require_once 'model/customer-model.php';
        require_once 'model/product-model.php';
        require_once 'model/sales-proposal-model.php';

        $databaseModel = new DatabaseModel();
        $systemModel = new SystemModel();
        $pdcManagementModel = new PDCManagementModel($databaseModel);
        $customerModel = new CustomerModel($databaseModel);
        $productModel = new ProductModel($databaseModel);
        $salesProposalModel = new SalesProposalModel($databaseModel);

        $loanCollectionIDs = is_array($loanCollectionID) ? $loanCollectionID : explode(',', $loanCollectionID);

        $response = '<table border="0.5" width="100%" cellpadding="3" align="center">
                        <thead>
                            <tr>
                                <td colspan="6" style="text-align:center;background-color: #131920;color: #fff">
                                    <b>ACKNOWLEDGMENT ADVICE FOR POST-DATED CHECKS</b>
                                </td>
                            </tr>
                        </thead>
                        <tbody>
                            <tr style="font-size:8px; text-align:left">
                                <td colspan="6" style="text-align:right;">
                                    <b>DATE '. strtoupper(date('d-M-Y')) .'</b>
                                </td>
                            </tr>
                            <tr style="font-size:8; text-align:left">
                                <td style="text-align:left;">RECEIVED FROM</td>
                                <td colspan="5" style="text-align:left;">'. strtoupper($customerName) .'</td>
                            </tr>
                            <tr style="font-size:8; text-align:left">
                                <td style="text-align:left;">ADDRESS</td>
                                <td colspan="3" style="text-align:left;">'. $customerAddress .'</td>
                                <td style="text-align:left;">STOCK NO.</td>
                                <td style="text-align:left;">'. $stock_number .'</td>
                            </tr>
                            <tr style="font-size:8; text-align:left">
                                <td style="text-align:left;">TOTAL AMT OF CHECKS</td>
                                <td colspan="3" style="text-align:left;">'. number_format($totalPDCAmount, 2) .'</td>
                                <td style="text-align:left;">LOAN NO.</td>
                                <td style="text-align:left;">'. $loan_number .'</td>
                            </tr>
                            <tr style="font-size:8; text-align:left">
                                <td style="text-align:left;">NO. OF CHECKS</td>
                                <td colspan="3" style="text-align:left;">'. $count .'</td>
                                <td style="text-align:left;">PLATE NO.</td>
                                <td style="text-align:left;">'. $plateNumber .'</td>
                            </tr>
                            <tr>
                                <td colspan="6" style="text-align:center;background-color: #131920;color: #fff"></td>
                            </tr>
                            <tr style="font-size:8; text-align:left">
                                <td style="text-align:left;">RECEIVED BY:</td>
                                <td></td>
                                <td style="text-align:left;">CHECKED BY:</td>
                                <td></td>
                                <td style="text-align:left;">POSTED BY:</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td style="text-align:center;background-color: #131920;color: #fff;" width="150">BANK/BRANCH</td>
                                <td style="text-align:center;background-color: #131920;color: #fff;" width="80">CHECK DATE</td>
                                <td style="text-align:center;background-color: #131920;color: #fff;" width="80">CHECK NO.</td>
                                <td style="text-align:center;background-color: #131920;color: #fff;" width="80">AMOUNT</td>
                                <td style="text-align:center;background-color: #131920;color: #fff;" width="80">PAYMENT FOR</td>
                                <td style="text-align:center;background-color: #131920;color: #fff;" width="85">REMARKS</td>
                            </tr>';

        $pdcRecords = [];

        foreach ($loanCollectionIDs as $loanCollectionID) {

            $pdcManagementDetails = $pdcManagementModel->getPDCManagement($loanCollectionID);

            $pdcRecords[] = [
                'bank_branch'     => $pdcManagementDetails['bank_branch'] ?? null,
                'check_number'    => $pdcManagementDetails['check_number'] ?? null,
                'check_date_raw'  => $pdcManagementDetails['check_date'] ?? null,
                'check_date'      => $systemModel->checkDate(
                                        'empty',
                                        $pdcManagementDetails['check_date'] ?? null,
                                        '',
                                        'm/d/Y',
                                        ''
                                    ),
                'payment_amount'  => $pdcManagementDetails['payment_amount'] ?? null,
                'payment_details' => $pdcManagementDetails['payment_details'] ?? null,
                'remarks'         => $pdcManagementDetails['remarks'] ??''
            ];
        }

        /*
        * Sort by:
        * 1. Check Number ASC
        * 2. Check Date ASC
        */
        usort($pdcRecords, function ($a, $b) {

            $dateCompare = strtotime($a['check_date_raw']) <=> strtotime($b['check_date_raw']);

            if ($dateCompare !== 0) {
                return $dateCompare;
            }

            return (int)$a['check_number'] <=> (int)$b['check_number'];
        });

        foreach ($pdcRecords as $record) {
            $response .= '
                <tr style="font-size:8px; text-align:center">
                    <td>'. htmlspecialchars($record['bank_branch']) .'</td>
                    <td>'. $record['check_date'] .'</td>
                    <td>'. htmlspecialchars($record['check_number']) .'</td>
                    <td>'. number_format($record['payment_amount'], 2) .'</td>
                    <td>'. htmlspecialchars($record['payment_details']) .'</td>
                    <td>'. htmlspecialchars($record['remarks']) .'</td>
                </tr>';
        }

        $response .= '
                        </tbody>
                    </table>';
                    

        return $response;
    }


?>