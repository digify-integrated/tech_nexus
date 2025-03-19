<?php
require_once '../session.php';
require_once '../config/config.php';
require_once '../model/database-model.php';
require_once '../model/user-model.php';
require_once '../model/security-model.php';
require_once '../model/system-model.php';
require_once '../model/leasing-application-model.php';
require_once '../model/tenant-model.php';
require_once '../model/property-model.php';

$databaseModel = new DatabaseModel();
$systemModel = new SystemModel();
$userModel = new UserModel($databaseModel, $systemModel);
$leasingApplicationModel = new LeasingApplicationModel($databaseModel);
$tenantModel = new TenantModel($databaseModel);
$propertyModel = new PropertyModel($databaseModel);
$securityModel = new SecurityModel();

if(isset($_POST['type']) && !empty($_POST['type'])){
    $type = htmlspecialchars($_POST['type'], ENT_QUOTES, 'UTF-8');
    $response = [];
    
    switch ($type) {
        # -------------------------------------------------------------
        #
        # Type: leasing application table
        # Description:
        # Generates the leasing application table.
        #
        # Parameters: None
        #
        # Returns: Array
        #
        # -------------------------------------------------------------
        case 'leasing application table':
            $sql = $databaseModel->getConnection()->prepare('CALL generateLeasingApplicationTable()'); 
            $sql->execute();
            $options = $sql->fetchAll(PDO::FETCH_ASSOC);
            $sql->closeCursor();

            foreach ($options as $row) {
                $leasingApplicationID = $row['leasing_application_id'];
                $leasingApplicationNumber = $row['leasing_application_number'];
                $tenantID = $row['tenant_id'];
                $propertyID = $row['property_id'];
                $leasingApplicationStatus = $leasingApplicationModel->getLeasingApplicationStatus($row['application_status']);

                $tenantDetails = $tenantModel->getTenant($tenantID);
                $tenantName = $tenantDetails['tenant_name'];

                $propertyDetails = $propertyModel->getProperty($propertyID);
                $propertyName = $propertyDetails['property_name'];

                $leasingApplicationIDEncrypted = $securityModel->encryptData($leasingApplicationID);

                $response[] = [
                    'CHECK_BOX' => '<input class="form-check-input datatable-checkbox-children" type="checkbox" value="'. $leasingApplicationID .'">',
                    'LEASING_APPLICATION_NUMBER' => '<a href="leasing-application.php?id='. $leasingApplicationIDEncrypted .'">
                                                    '. $leasingApplicationNumber .'
                                                </a>',
                    'TENANT_NAME' => $tenantName,
                    'PROPERTY_NAME' => $propertyName,
                    'STATUS' => $leasingApplicationStatus,
                    'ACTION' => '<div class="d-flex gap-2">
                                        <a href="leasing-application.php?id='. $leasingApplicationIDEncrypted .'" class="btn btn-icon btn-primary" title="View Details">
                                            <i class="ti ti-eye"></i>
                                        </a>
                                    </div>'
                ];
            }

            echo json_encode($response);
        break;
        # -------------------------------------------------------------

        # -------------------------------------------------------------
        #
        # Type: leasing summary table
        # Description:
        # Generates the leasing summary table.
        #
        # Parameters: None
        #
        # Returns: Array
        #
        # -------------------------------------------------------------
        case 'leasing summary table':
            $sql = $databaseModel->getConnection()->prepare('CALL generateLeasingSummaryTable()');
                $sql->execute();
                $options = $sql->fetchAll(PDO::FETCH_ASSOC);
                $sql->closeCursor();

                foreach ($options as $row) {
                    $leasingApplicationID = $row['leasing_application_id'];
                    $leasingApplicationNumber = $row['leasing_application_number'];
                    $tenantID = $row['tenant_id'];
                    $propertyID = $row['property_id'];
                    $floorArea = $row['floor_area'];
                    $termLength = $row['term_length'];
                    $termType = $row['term_type'];
                    $securityDeposit = $row['security_deposit'];
                    $escalationRate = $row['escalation_rate'];
                    $initialBasicRental = $row['initial_basic_rental'];
                    $contractDate = $systemModel->checkDate('summary', $row['contract_date'], '', 'm/d/Y', '');
                    $maturityDate = $systemModel->checkDate('summary', $row['maturity_date'], '', 'm/d/Y', '');
                    $leasingApplicationStatus = $leasingApplicationModel->getLeasingApplicationStatus($row['application_status']);

                    $tenantDetails = $tenantModel->getTenant($tenantID);
                    $tenantName = $tenantDetails['tenant_name'];
                    $contactPerson = $tenantDetails['contact_person'];

                    $propertyDetails = $propertyModel->getProperty($propertyID);
                    $propertyName = $propertyDetails['property_name'];

                    $leasingApplicationIDEncrypted = $securityModel->encryptData($leasingApplicationID);

                    $unpaidRental = $leasingApplicationModel->getLeasingAplicationRepaymentTotal($leasingApplicationID, date('Y-m-d'), 'Unpaid Rental')['total'];
                    $unpaidElectricity = $leasingApplicationModel->getLeasingAplicationRepaymentTotal($leasingApplicationID, date('Y-m-d'), 'Unpaid Electricity')['total'];
                    $unpaidWater = $leasingApplicationModel->getLeasingAplicationRepaymentTotal($leasingApplicationID, date('Y-m-d'), 'Unpaid Water')['total'];
                    $unpaidOtherCharges = $leasingApplicationModel->getLeasingAplicationRepaymentTotal($leasingApplicationID, date('Y-m-d'), 'Unpaid Other Charges')['total'];
                    $outstandingBalance = $leasingApplicationModel->getLeasingAplicationRepaymentTotal($leasingApplicationID, date('Y-m-d'), 'Outstanding Balance')['total'];
                    $totaloutstandingBalance = $leasingApplicationModel->getLeasingAplicationRepaymentTotal($leasingApplicationID, null, 'Total Outstanding Balance')['total'];

                    if($unpaidRental <= 0){
                        $unpaidRental = 0;
                    }

                    if($unpaidElectricity <= 0){
                        $unpaidElectricity = 0;
                    }

                    if($unpaidWater <= 0){
                        $unpaidWater = 0;
                    }

                    if($unpaidOtherCharges <= 0){
                        $unpaidOtherCharges = 0;
                    }

                    if($outstandingBalance <= 0){
                        $outstandingBalance = 0;
                    }

                    $response[] = [
                        'LEASING_APPLICATION_NUMBER' => '<a href="leasing-summary.php?id='. $leasingApplicationIDEncrypted .'">
                                         <div class="row">
                                        <div class="col">
                                        <h6 class="mb-0">'. $leasingApplicationNumber .'</h6>
                                        </div>
                                    </div>
                        </a>',
                        'TENANT_NAME' => '<a href="leasing-summary.php?id='. $leasingApplicationIDEncrypted .'">
                                         <div class="row">
                                        <div class="col">
                                        <h6 class="mb-0">'. $tenantName .'</h6>
                                        <p class="f-12 mb-0">'. $contactPerson .'</p>
                                        </div>
                                    </div>
                        </a>',
                        'PROPERTY_NAME' => $propertyName,
                        'UNPAID_RENTAL' => number_format($unpaidRental, 2),
                        'UNPAID_ELECTRICITY' => number_format($unpaidElectricity, 2),
                        'UNPAID_WATER' => number_format($unpaidWater, 2),
                        'UNPAID_OTHER_CHARGES' => number_format($unpaidOtherCharges, 2),
                        'OUTSTANDING_BALANCE' => number_format($outstandingBalance, 2),
                        'FLOOR_AREA' => number_format($floorArea, 0),
                        'TERM' => $termLength . ' ' . $termType,
                        'INCEPTION_DATE' => $contractDate,
                        'MATURITY_DATE' => $maturityDate,
                        'SECURITY_DEPOSIT' => number_format($securityDeposit, 2),
                        'ESCALATION_RATE' => number_format($escalationRate, 2) . '%',
                        'STATUS' => $leasingApplicationStatus,
                        'INITIAL_BASIC_RENTAL' => number_format($totaloutstandingBalance, 2)
                    ];
                }

                echo json_encode($response);
        break;
        # -------------------------------------------------------------

        # -------------------------------------------------------------
        #
        # Type: closed leasing summary table
        # Description:
        # Generates the closed leasing summary table.
        #
        # Parameters: None
        #
        # Returns: Array
        #
        # -------------------------------------------------------------
        case 'closed leasing summary table':
            $sql = $databaseModel->getConnection()->prepare('CALL generateClosedLeasingApplicationTable()');
                $sql->execute();
                $options = $sql->fetchAll(PDO::FETCH_ASSOC);
                $sql->closeCursor();

                foreach ($options as $row) {
                    $leasingApplicationID = $row['leasing_application_id'];
                    $leasingApplicationNumber = $row['leasing_application_number'];
                    $tenantID = $row['tenant_id'];
                    $propertyID = $row['property_id'];
                    $floorArea = $row['floor_area'];
                    $termLength = $row['term_length'];
                    $termType = $row['term_type'];
                    $securityDeposit = $row['security_deposit'];
                    $escalationRate = $row['escalation_rate'];
                    $initialBasicRental = $row['initial_basic_rental'];
                    $contractDate = $systemModel->checkDate('summary', $row['contract_date'], '', 'm/d/Y', '');
                    $maturityDate = $systemModel->checkDate('summary', $row['maturity_date'], '', 'm/d/Y', '');
                    $leasingApplicationStatus = $leasingApplicationModel->getLeasingApplicationStatus($row['application_status']);

                    $tenantDetails = $tenantModel->getTenant($tenantID);
                    $tenantName = $tenantDetails['tenant_name'];
                    $contactPerson = $tenantDetails['contact_person'];

                    $propertyDetails = $propertyModel->getProperty($propertyID);
                    $propertyName = $propertyDetails['property_name'];

                    $leasingApplicationIDEncrypted = $securityModel->encryptData($leasingApplicationID);

                    $unpaidRental = $leasingApplicationModel->getLeasingAplicationRepaymentTotal($leasingApplicationID, date('Y-m-d'), 'Unpaid Rental')['total'];
                    $unpaidElectricity = $leasingApplicationModel->getLeasingAplicationRepaymentTotal($leasingApplicationID, date('Y-m-d'), 'Unpaid Electricity')['total'];
                    $unpaidWater = $leasingApplicationModel->getLeasingAplicationRepaymentTotal($leasingApplicationID, date('Y-m-d'), 'Unpaid Water')['total'];
                    $unpaidOtherCharges = $leasingApplicationModel->getLeasingAplicationRepaymentTotal($leasingApplicationID, date('Y-m-d'), 'Unpaid Other Charges')['total'];
                    $outstandingBalance = $leasingApplicationModel->getLeasingAplicationRepaymentTotal($leasingApplicationID, date('Y-m-d'), 'Outstanding Balance')['total'];

                    if($unpaidRental <= 0){
                        $unpaidRental = 0;
                    }

                    if($unpaidElectricity <= 0){
                        $unpaidElectricity = 0;
                    }

                    if($unpaidWater <= 0){
                        $unpaidWater = 0;
                    }

                    if($unpaidOtherCharges <= 0){
                        $unpaidOtherCharges = 0;
                    }

                    $outstandingBalance = 0;

                    $response[] = [
                        'TENANT_NAME' => '<a href="leasing-summary.php?id='. $leasingApplicationIDEncrypted .'">
                                         <div class="row">
                                        <div class="col">
                                        <h6 class="mb-0">'. $tenantName .'</h6>
                                        <p class="f-12 mb-0">'. $contactPerson .'</p>
                                        </div>
                                    </div>
                        </a>',
                        'PROPERTY_NAME' => $propertyName,
                        'UNPAID_RENTAL' => '0.00',
                        'UNPAID_ELECTRICITY' => '0.00',
                        'UNPAID_WATER' => '0.00',
                        'UNPAID_OTHER_CHARGES' => '0.00',
                        'OUTSTANDING_BALANCE' => '0.00',
                        'FLOOR_AREA' => number_format($floorArea, 0),
                        'TERM' => $termLength . ' ' . $termType,
                        'INCEPTION_DATE' => $contractDate,
                        'MATURITY_DATE' => $maturityDate,
                        'SECURITY_DEPOSIT' => number_format($securityDeposit, 2),
                        'ESCALATION_RATE' => number_format($escalationRate, 2) . '%',
                        'STATUS' => $leasingApplicationStatus,
                        'INITIAL_BASIC_RENTAL' => number_format($initialBasicRental, 2)
                    ];
                }

                echo json_encode($response);
        break;
        # -------------------------------------------------------------

        # -------------------------------------------------------------
        #
        # Type: leasing repayment table
        # Description:
        # Generates the leasing repayment table.
        #
        # Parameters: None
        #
        # Returns: Array
        #
        # -------------------------------------------------------------
        case 'leasing repayment table':
            if(isset($_POST['leasing_application_id'])){
                $leasingApplicationID = htmlspecialchars($_POST['leasing_application_id'], ENT_QUOTES, 'UTF-8');
                $leasingApplicationIDEncrypted = $securityModel->encryptData($leasingApplicationID);

                $sql = $databaseModel->getConnection()->prepare('CALL generateLeasingRepaymentTable(:leasingApplicationID)');
                $sql->bindValue(':leasingApplicationID', $leasingApplicationID, PDO::PARAM_INT);
                $sql->execute();
                $options = $sql->fetchAll(PDO::FETCH_ASSOC);
                $sql->closeCursor();

                foreach ($options as $row) {
                    $leasingApplicationRepaymentID = $row['leasing_application_repayment_id'];
                    $reference = $row['reference'];
                    $unpaidRental = number_format($row['unpaid_rental'], 2);
                    $paidRental = number_format($row['paid_rental'], 2);
                    $unpaidElectricity = number_format($row['unpaid_electricity'], 2);
                    $paidElectricity = number_format($row['paid_electricity'], 2);
                    $unpaidWater = number_format($row['unpaid_water'], 2);
                    $paidWater = number_format($row['paid_water'], 2);
                    $unpaidOtherCharges = number_format($row['unpaid_other_charges'], 2);
                    $paidOtherCharges = number_format($row['paid_other_charges'], 2);
                    $outstandingBalance = number_format($row['outstanding_balance'], 2);
                    $dueDate = $systemModel->checkDate('summary', $row['due_date'], '', 'm/d/Y', '');

                    $leasingApplicationRepaymentIDEncrypted = $securityModel->encryptData($leasingApplicationRepaymentID);

                    $repaymentStatus = $leasingApplicationModel->getLoanApplicationRepaymentStatus($row['repayment_status']);

                    $response[] = [
                        'CHECK_BOX' => '<input class="form-check-input datatable-checkbox-children billing-ids" type="checkbox" value="'. $leasingApplicationRepaymentID .'">',
                        'REFERENCE' =>  '<a href="leasing-summary.php?id='. $leasingApplicationIDEncrypted .'&repayment_id='. $leasingApplicationRepaymentIDEncrypted .'">
                            '. $reference .'
                        </a>',
                        'DUE_DATE' => $dueDate,
                        'STATUS' => $repaymentStatus,
                        'PAID_RENTAL' => $paidRental,
                        'UNPAID_RENTAL' => $unpaidRental,
                        'PAID_ELECTRICITY' => $paidElectricity,
                        'UNPAID_ELECTRICITY' => $unpaidElectricity,
                        'PAID_WATER' => $paidWater,
                        'UNPAID_WATER' => $unpaidWater,
                        'PAID_OTHER_CHARGES' => $paidOtherCharges,
                        'UNPAID_OTHER_CHARGES' => $unpaidOtherCharges,
                        'OUTSTANDING_BALANCE' => $outstandingBalance,
                        'ACTION' => '<div class="d-flex gap-2">
                                        <a href="leasing-summary.php?id='. $leasingApplicationIDEncrypted .'&repayment_id='. $leasingApplicationRepaymentIDEncrypted .'" class="btn btn-icon btn-primary" title="View Details">
                                            <i class="ti ti-eye"></i>
                                        </a>
                                    </div>'
                    ];
                }

                echo json_encode($response);
            }
        break;
        # -------------------------------------------------------------

        # -------------------------------------------------------------
        #
        # Type: leasing repayment other charges table
        # Description:
        # Generates the leasing repayment other charges table.
        #
        # Parameters: None
        #
        # Returns: Array
        #
        # -------------------------------------------------------------
        case 'leasing repayment other charges table':
            if(isset($_POST['leasing_application_id'])){
                $leasingApplicationID = htmlspecialchars($_POST['leasing_application_id'], ENT_QUOTES, 'UTF-8');

                $leasingApplicationDetails = $leasingApplicationModel->getLeasingApplication($leasingApplicationID);
                $applicationStatus = $leasingApplicationDetails['application_status'] ?? null;

                $sql = $databaseModel->getConnection()->prepare('CALL generateLeasingRepaymentOtherChargesTable(:leasingApplicationID)');
                $sql->bindValue(':leasingApplicationID', $leasingApplicationID, PDO::PARAM_INT);
                $sql->execute();
                $options = $sql->fetchAll(PDO::FETCH_ASSOC);
                $sql->closeCursor();

                foreach ($options as $row) {
                    $leasingOtherChargesID = $row['leasing_other_charges_id'];
                    $otherChargesType = $row['other_charges_type'];
                    $dueAmount = number_format($row['due_amount'], 2);
                    $duePaid = number_format($row['due_paid'], 2);
                    $referenceNumber = $row['reference_number'];
                    $outstandingBalance = number_format($row['outstanding_balance'], 2);
                    $dueDate = $systemModel->checkDate('summary', $row['due_date'], '', 'm/d/Y', '');

                    $status = $leasingApplicationModel->getLoanApplicationRepaymentStatus($row['payment_status']);

                    $payment = '';
                    /*if($row['outstanding_balance'] > 0 && $applicationStatus != 'Closed'){
                        $payment = '<button type="button" class="btn btn-icon btn-success pay-leasing-other-charges" data-leasing-other-charges-id="'. $leasingOtherChargesID .'" data-leasing-other-charges-type="'. $otherChargesType .'" data-bs-toggle="offcanvas" data-bs-target="#leasing-other-charges-payment-offcanvas" aria-controls="leasing-other-charges-payment-offcanvas" title="Pay Other Charges">
                            <i class="ti ti-check"></i>
                        </button>';
                    }*/

                    $delete = '';
                    if($row['due_paid'] == 0 && $applicationStatus != 'Closed'){
                        $delete = '<button type="button" class="btn btn-icon btn-danger delete-leasing-other-charges" data-leasing-other-charges-id="'. $leasingOtherChargesID .'" title="Delete Other Charges">
                            <i class="ti ti-trash"></i>
                        </button>';
                    }

                    $response[] = [
                        'OTHER_CHARGES_TYPE' => $otherChargesType,
                        'REFERENCE_NUMBER' => $referenceNumber,
                        'DUE_DATE' => $dueDate,
                        'DUE_AMOUNT' => $dueAmount,
                        'PAID_AMOUNT' => $duePaid,
                        'OUTSTANDING_BALANCE' => $outstandingBalance,
                        'STATUS' => $status,
                        'ACTION' => '<div class="d-flex gap-2">
                                        '. $payment .'
                                        '. $delete .'
                                    </div>'
                    ];
                }

                echo json_encode($response);
            }
        break;
        # -------------------------------------------------------------

        # -------------------------------------------------------------
        #
        # Type: leasing repayment collections table
        # Description:
        # Generates the leasing repayment collections table.
        #
        # Parameters: None
        #
        # Returns: Array
        #
        # -------------------------------------------------------------
        case 'leasing repayment collections table':
            if(isset($_POST['leasing_application_id'])){
                $leasingApplicationID = htmlspecialchars($_POST['leasing_application_id'], ENT_QUOTES, 'UTF-8');
                $leasingApplicationIDEncrypted = $securityModel->encryptData($leasingApplicationID);
                
                $leasingApplicationDetails = $leasingApplicationModel->getLeasingApplication($leasingApplicationID);
                $applicationStatus = $leasingApplicationDetails['application_status'] ?? null;

                $sql = $databaseModel->getConnection()->prepare('CALL generateLeasingRepaymentCollectionsTable(:leasingApplicationID)');
                $sql->bindValue(':leasingApplicationID', $leasingApplicationID, PDO::PARAM_INT);
                $sql->execute();
                $options = $sql->fetchAll(PDO::FETCH_ASSOC);
                $sql->closeCursor();

                foreach ($options as $row) {
                    $leasingCollectionsID = $row['leasing_collections_id'];
                    $leasing_application_repayment_id = $row['leasing_application_repayment_id'];
                    $paymentFor = $row['payment_for'];
                    $referenceNumber = $row['reference_number'];
                    $paymentMode = $row['payment_mode'];
                    $paymentAmount = number_format($row['payment_amount'], 2);
                    $paymentDate = $systemModel->checkDate('summary', $row['payment_date'], '', 'm/d/Y', '');

                    $leasingApplicationRepaymentDetails = $leasingApplicationModel->getLeasingApplicationRepayment($leasing_application_repayment_id);
                    $dueDate =  $systemModel->checkDate('empty', $leasingApplicationRepaymentDetails['due_date'] ?? 0, '', 'm/d/Y', '');
                    $reference = $leasingApplicationRepaymentDetails['reference'];
                    
                    $delete = '';
                    if($applicationStatus != 'Closed'){
                        $delete = '<button type="button" class="btn btn-icon btn-danger delete-leasing-collections" data-leasing-collections-id="'. $leasingCollectionsID .'" title="Delete Collections">
                        <i class="ti ti-trash"></i>
                        </button>';
                    }

                    $response[] = [
                        'REFERENCE' => $reference,
                        'DUE_DATE' => $dueDate,
                        'PAYMENT_FOR' => $paymentFor,
                        'REFERENCE_NUMBER' => $referenceNumber,
                        'PAYMENT_MODE' => $paymentMode,
                        'PAYMENT_DATE' => $paymentDate,
                        'PAYMENT_AMOUNT' => $paymentAmount,
                        'ACTION' => '<div class="d-flex gap-2">
                                        '. $delete .'
                                    </div>'
                    ];
                }

                echo json_encode($response);
            }
        break;
        # -------------------------------------------------------------

        # -------------------------------------------------------------
        #
        # Type: repayment table
        # Description:
        # Generates the repayment table.
        #
        # Parameters: None
        #
        # Returns: Array
        #
        # -------------------------------------------------------------
        case 'repayment table':
            if(isset($_POST['leasing_application_id']) && !empty($_POST['leasing_application_id'])){
                $leasingApplicationID = htmlspecialchars($_POST['leasing_application_id'], ENT_QUOTES, 'UTF-8');

                $table = '';

                $leasingApplicationDetails = $leasingApplicationModel->getLeasingApplication($leasingApplicationID);

                $leasingApplicationNumber = $leasingApplicationDetails['leasing_application_number'];
                $startDate = $systemModel->checkDate('empty', $leasingApplicationDetails['start_date'], '', 'Y-m-d', '');
                $initialBasicRental = $leasingApplicationDetails['initial_basic_rental'] ?? null;
                $escalationRate = $leasingApplicationDetails['escalation_rate'] ?? null;
                $escalationRateDecimal = $escalationRate / 100.0;
                $termLength = $leasingApplicationDetails['term_length'] ?? null;
                $vat = $leasingApplicationDetails['vat'] ?? 'Yes';
                $witholdingTax = $leasingApplicationDetails['witholding_tax'] ?? 'Yes';
                $paymentFrequency = $leasingApplicationDetails['payment_frequency'] ?? null;

                if($vat == 'Yes' && $witholdingTax == 'Yes'){
                    $initialBasicRental = $initialBasicRental + ($initialBasicRental * (0.07));
                }
                else if($vat == 'Yes' && $witholdingTax == 'No'){
                    $initialBasicRental = $initialBasicRental + ($initialBasicRental * (0.12));
                }
                else if($vat == 'No' && $witholdingTax == 'Yes'){
                    $initialBasicRental = $initialBasicRental - ($initialBasicRental * (0.05));
                }

                for ($x = 0; $x < $termLength; $x++) {
                    if ($x % 12 == 0 && $x > 0) { // Increase the initial basic rental value every 12 months after the first year
                        $initialBasicRental *= (1 + $escalationRateDecimal);
                    }
                
                    if($x == 0){
                        $matdt = $startDate;
        
                        $table .= '<tr>
                                    <td>'. ($x + 1) .'</td>       
                                    <td>'. $matdt .'</td>       
                                    <td>'. number_format($initialBasicRental, 2) .'</td>       
                        </tr>';
                    }
                    else{
                        # Get due dates
                        $matdt = date('Y-m-d', strtotime(getNextDuedate($matdt, $startDate, $paymentFrequency)));
                
                        if(date('d', strtotime($matdt)) == '31'){
                            $maturity = date('Y-m-30', strtotime($matdt));
                        }
                        else{
                            $maturity = $matdt;
                        }
        
                        if($x >= 1 && $x <= 9){
                            $extension = '0' . ($x + 1);
                        }
                        else{
                            $extension = ($x + 1);
                        }
                
                        $table .= '<tr>
                                    <td>'. ($x + 1) .'</td>       
                                    <td>'. $maturity .'</td>       
                                    <td>'. number_format($initialBasicRental, 2) .'</td>       
                        </tr>';
                    }
                }

                $response[] = [
                    'table' => $table
                ];

                echo json_encode($response);
            }
        break;
        # -------------------------------------------------------------

    }
}

function getNextDuedate($prevdate, $maturitydate, $frequency){
    $matdteom = check_end_of_month($maturitydate);
    $maturityday = date('d', strtotime($maturitydate));

    $prevdate = getCalculatedDate($prevdate, 1);
    $prevlastday = date('t', strtotime($prevdate));

    if($prevlastday < $maturityday){
        $duedate = $prevdate;
    }
    else{
        $duedate = date('m', strtotime($prevdate)) . '/'. date('d', strtotime($maturitydate)) .'/' . date('Y', strtotime($prevdate));
    }
        
    $flag = '1';

    if($matdteom == '1' && $flag == '1'){
        $duedate = date('m/t/Y', strtotime($duedate));
    }
    
    return $duedate;
}

function check_end_of_month($date){
    //adds 1 day to date
    $Temp = date('m/d/Y',strtotime("+1 day", strtotime($date)));

    //get the month of each date
    $tempmonth = date('m', strtotime($Temp));
    $datemonth = date('m', strtotime($date));
    
    //check if the months are equal
    if($tempmonth != $datemonth){
        return '1';
    }
    else{
        return '0';
    }
}

function getCalculatedDate($d1, $months){
    $date = new DateTime($d1);

    # call add_months function to add the months
    $newDate = $date->add(add_months($months, $date));

    #formats final date to m/d/Y form
    $dateReturned = $newDate->format('m/d/Y'); 

    return $dateReturned;
}

function add_months($months, DateTime $dateObject){
    #format date to Y-m-d
    #get the last day of the given month
    $next = new DateTime($dateObject->format('Y-m-d'));
    $next->modify('last day of +'.$months.' month');

    #if $dateObject day is greater than the day of $next
    #return the difference
    #else create a new interval
    if($dateObject->format('d') > $next->format('d')) {
        return $dateObject->diff($next);
    } else {
        return new DateInterval('P'.$months.'M');
    }
}

?>