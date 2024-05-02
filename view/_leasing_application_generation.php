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
            if(isset($_POST['leasing_application_status_filter'])){
                $leasingSummaryStatusFilter = htmlspecialchars($_POST['leasing_application_status_filter'], ENT_QUOTES, 'UTF-8');

                $sql = $databaseModel->getConnection()->prepare('CALL generateLeasingSummaryTable(:leasingSummaryStatusFilter)');
                $sql->bindValue(':leasingSummaryStatusFilter', $leasingSummaryStatusFilter, PDO::PARAM_STR);
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

                    $propertyDetails = $propertyModel->getProperty($propertyID);
                    $propertyName = $propertyDetails['property_name'];

                    $leasingApplicationIDEncrypted = $securityModel->encryptData($leasingApplicationID);

                    $unpaidRental = $leasingApplicationModel->getLeasingAplicationRepaymentTotal($leasingApplicationID, 'Unpaid Rental')['total'];
                    $unpaidElectricity = $leasingApplicationModel->getLeasingAplicationRepaymentTotal($leasingApplicationID, 'Unpaid Electricity')['total'];
                    $unpaidWater = $leasingApplicationModel->getLeasingAplicationRepaymentTotal($leasingApplicationID, 'Unpaid Water')['total'];
                    $unpaidOtherCharges = $leasingApplicationModel->getLeasingAplicationRepaymentTotal($leasingApplicationID, 'Unpaid Other Charges')['total'];
                    $outstandingBalance = $leasingApplicationModel->getLeasingAplicationRepaymentTotal($leasingApplicationID, 'Outstanding Balance')['total'];

                    $response[] = [
                        'TENANT_NAME' => $tenantName,
                        'PROPERTY_NAME' => $propertyName,
                        'FLOOR_AREA' => number_format($floorArea, 0),
                        'TERM' => $termLength . ' ' . $termType,
                        'INCEPTION_DATE' => $contractDate,
                        'MATURITY_DATE' => $maturityDate,
                        'SECURITY_DEPOSIT' => number_format($securityDeposit, 2),
                        'ESCALATION_RATE' => number_format($escalationRate, 2) . '%',
                        'INITIAL_BASIC_RENTAL' => number_format($initialBasicRental, 2),
                        'STATUS' => $leasingApplicationStatus,
                        'UNPAID_RENTAL' => number_format($unpaidRental, 2),
                        'UNPAID_ELECTRICITY' => number_format($unpaidElectricity, 2),
                        'UNPAID_WATER' => number_format($unpaidWater, 2),
                        'UNPAID_OTHER_CHARGES' => number_format($unpaidOtherCharges, 2),
                        'OUTSTANDING_BALANCE' => number_format($outstandingBalance, 2)
                    ];
                }

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