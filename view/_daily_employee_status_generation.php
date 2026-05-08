<?php
require_once '../session.php';
require_once '../config/config.php';
require_once '../model/database-model.php';
require_once '../model/user-model.php';
require_once '../model/employee-model.php';
require_once '../model/sales-proposal-model.php';
require_once '../model/customer-model.php';
require_once '../model/product-model.php';
require_once '../model/security-model.php';
require_once '../model/miscellaneous-client-model.php';
require_once '../model/system-model.php';
require_once '../model/leasing-application-model.php';
require_once '../model/tenant-model.php';
require_once '../model/department-model.php';
require_once '../model/company-model.php';
require_once '../model/branch-model.php';

$databaseModel = new DatabaseModel();
$systemModel = new SystemModel();
$userModel = new UserModel($databaseModel, $systemModel);
$employeeModel = new EmployeeModel($databaseModel);
$departmentModel = new DepartmentModel($databaseModel);
$customerModel = new CustomerModel($databaseModel);
$productModel = new ProductModel($databaseModel);
$salesProposalModel = new SalesProposalModel($databaseModel);
$companyModel = new CompanyModel($databaseModel);
$securityModel = new SecurityModel();
$miscellaneousClientModel = new MiscellaneousClientModel($databaseModel);
$tenantModel = new TenantModel($databaseModel);
$leasingApplicationModel = new LeasingApplicationModel($databaseModel);
$branchModel = new BranchModel($databaseModel);

if(isset($_POST['type']) && !empty($_POST['type'])){
    $type = htmlspecialchars($_POST['type'], ENT_QUOTES, 'UTF-8');
    $response = [];
    
    switch ($type) {
        case 'daily employee status table':
            $filter_attendance_date = $systemModel->checkDate('empty', $_POST['filter_attendance_date'], '', 'Y-m-d', '');
            
            $filter_attendance_status = $_POST['filter_attendance_status'] ?? '';
            $branch_filter = $_POST['branch_filter'] ?? '';

            if (!empty($filter_attendance_status)) {
                // Convert string to array and trim each value
                $values_array = array_filter(array_map('trim', explode(',', $filter_attendance_status)));

                // Quote each value safely
                $quoted_values_array = array_map(function($value) {
                    return "'" . addslashes($value) . "'";
                }, $values_array);

                // Implode into comma-separated string
                $filter_status = implode(', ', $quoted_values_array);
            } else {
                $filter_status = null;
            }

            if (!empty($branch_filter)) {
                // Convert string to array and trim each value
                $values_array = array_filter(array_map('trim', explode(',', $branch_filter)));

                // Quote each value safely
                $quoted_values_array = array_map(function($value) {
                    return "'" . addslashes($value) . "'";
                }, $values_array);

                // Implode into comma-separated string
                $filter_branch = implode(', ', $quoted_values_array);
            } else {
                $filter_branch = null;
            }

            $sql = $databaseModel->getConnection()->prepare('CALL generateDailyEmployeeStatusTable(:filter_attendance_date, :filter_status, :filter_branch)');
            $sql->bindValue(':filter_attendance_date', $filter_attendance_date, PDO::PARAM_STR);
            $sql->bindValue(':filter_status', $filter_status, PDO::PARAM_STR);
            $sql->bindValue(':filter_branch', $filter_branch, PDO::PARAM_STR);
            $sql->execute();
            $options = $sql->fetchAll(PDO::FETCH_ASSOC);
            $sql->closeCursor();

            foreach ($options as $row) {
                $employee_daily_status_id = $row['employee_daily_status_id'];
                $contact_id = $row['contact_id'];
                $status = $row['status'];
                $is_late = $row['is_late'];
                $is_undertime = $row['is_undertime'];
                $is_present = $row['is_present'];
                $is_on_paid_leave = $row['is_on_paid_leave'];
                $is_on_unpaid_leave = $row['is_on_unpaid_leave'];
                $is_on_official_business = $row['is_on_official_business'];
                $late_minutes = $row['late_minutes'];
                $undertime_minutes = $row['undertime_minutes'];
                $unpaid_leave_minutes = $row['unpaid_leave_minutes'];
                $remarks = $row['remarks'];

                $attendance_date = $systemModel->checkDate('empty', $row['attendance_date'], '', 'F d, Y', '');

                $employeeDetails = $employeeModel->getPersonalInformation($contact_id);
                $fileAs = $employeeDetails['file_as'] ?? '';

                $employeeDetails = $employeeModel->getEmploymentInformation($contact_id);
                $departmentID = $employeeDetails['department_id'] ?? null;
                $branch_id = $employeeDetails['branch_id'] ?? null;

                $departmentName = $departmentModel->getDepartment($departmentID)['department_name'] ?? null;
                $branchName = $branchModel->getBranch($branch_id)['branch_name'] ?? null;

                $badge = '';
                
                if($is_present === 'Yes'){
                    $badge .= '<span class="badge bg-success">Present</span><br/>';
                }
                
                if($is_on_paid_leave === 'Yes'){
                    $badge .= '<span class="badge bg-success">On Paid Leave</span><br/>';
                }
                
                if($is_on_unpaid_leave === 'Yes' && $unpaid_leave_minutes > 0){
                    $badge .= '<span class="badge bg-danger">On Unpaid Leave: '. ($unpaid_leave_minutes / 60) .' hour(s)</span><br/>';
                }

                if($is_on_official_business === 'Yes'){
                    $badge .= '<span class="badge bg-info">On Official Business</span><br/>';
                }

                if($is_late === 'Yes' && $late_minutes){
                    $badge .= '<span class="badge bg-warning">Late: '. $late_minutes .' minute(s)</span><br/>';
                }

                if($is_undertime === 'Yes' && $undertime_minutes > 0){
                    $badge .= '<span class="badge bg-warning">Undertime: '. $undertime_minutes .' minute(s)</span><br/>';
                }

                $action = '<button type="button" class="btn btn-icon btn-primary add-remarks" data-bs-toggle="offcanvas" data-bs-target="#add-remarks-offcanvas" aria-controls="add-remarks-offcanvas" data-employee-daily-status-id="'. $employee_daily_status_id .'" title="Add Remarks">
                                        <i class="ti ti-file-text"></i> 
                                    </button>';

                $response[] = [
                    'CHECK_BOX' => '<input class="form-check-input datatable-checkbox-children pdc-id" type="checkbox" value="'. $employee_daily_status_id .'">',
                    'EMPLOYEE' => '<a href="javascript:void(0);" title="View Details">
                                        <div class="col">
                                            <h6 class="mb-0">'. $fileAs .'</h6>
                                            <p class="f-12 mb-0">'. $departmentName .'</p>
                                        </div>
                                    </a>',
                    'BRANCH' => $branchName,
                    'STATUS' => $badge,
                    'ATTENDANCE_DATE' => $attendance_date,
                    'REMARKS' => $remarks,
                    'ACTION' => $action
                    ];
            }

            echo json_encode($response);
        break;
        case 'employee status dashboard list':
           $filter_attendance_date = $systemModel->checkDate(
                'empty',
                $_POST['filter_attendance_date'],
                '',
                'Y-m-d',
                ''
            );
            $filter_status = null;
            $filter_attendance_status = $_POST['filter_attendance_status'] ?? '';
            $branch_filter = $_POST['branch_filter'] ?? '';
            $list_type = $_POST['list_type'] ?? '';

            if (!empty($branch_filter)) {
                // Convert string to array and trim each value
                $values_array = array_filter(array_map('trim', explode(',', $branch_filter)));

                // Quote each value safely
                $quoted_values_array = array_map(function($value) {
                    return "'" . addslashes($value) . "'";
                }, $values_array);

                // Implode into comma-separated string
                $filter_branch = implode(', ', $quoted_values_array);
            } else {
                $filter_branch = null;
            }

            $sql = $databaseModel->getConnection()->prepare('CALL generateDailyEmployeeStatusTable2(:filter_attendance_date, :list_type, :filter_branch)');
            $sql->bindValue(':filter_attendance_date', $filter_attendance_date, PDO::PARAM_STR);
            $sql->bindValue(':list_type', $list_type, PDO::PARAM_STR);
            $sql->bindValue(':filter_branch', $filter_branch, PDO::PARAM_STR);
            $sql->execute();
            $options = $sql->fetchAll(PDO::FETCH_ASSOC);
            $sql->closeCursor();

            $list = '';
            foreach ($options as $row) {
                $employee_daily_status_id = $row['employee_daily_status_id'];
                $contact_id = $row['contact_id'];
                $remarks = $row['remarks'];

                $attendance_date = $systemModel->checkDate('empty', $row['attendance_date'], '', 'F d, Y', '');

                $employeeDetails = $employeeModel->getPersonalInformation($contact_id);
                $fileAs = $employeeDetails['file_as'] ?? '';
                $employeeImage = $systemModel->checkImage($employeeDetails['contact_image'], 'profile');

                $employeeDetails = $employeeModel->getEmploymentInformation($contact_id);
                $departmentID = $employeeDetails['department_id'] ?? null;
                $branch_id = $employeeDetails['branch_id'] ?? null;

                $departmentName = $departmentModel->getDepartment($departmentID)['department_name'] ?? null;
                
                $list .= '<div class="align-middle m-b-25"><img src="'. $employeeImage .'" alt="user image" class="img-radius align-top m-r-15">
                    <div class="d-inline-block">
                     <h6>'. $fileAs .'</h6>
                    <p class="m-b-0">'. $departmentName .'</p>
                    </div>
                </div>';
            }

            if(empty($list)){
                $list = ' <h6 class="text-center pb-5">No Employee Found</h6>';
            }

            echo json_encode(['LIST' => $list]);
        break;
        case 'employee attendance late summary table':

    $month  = (int) ($_POST['month'] ?? 0);
    $year   = (int) ($_POST['year'] ?? 0);
    $cutoff = (int) ($_POST['cutoff'] ?? 1);

    // Determine day range
    if ($cutoff === 1) {
        $startDay = 1;
        $endDay   = 15;
    } else {
        $startDay = 16;
        $endDay   = cal_days_in_month(CAL_GREGORIAN, $month, $year);
    }

    /*
    |--------------------------------------------------------------------------
    | STEP 1: GET DISTINCT EMPLOYEES FROM employee_daily_status
    |--------------------------------------------------------------------------
    */
    $sqlEmployees = $databaseModel->getConnection()->prepare("
        SELECT DISTINCT contact_id
        FROM employee_daily_status
        WHERE 
            MONTH(attendance_date) = :month
            AND YEAR(attendance_date) = :year
    ");
    $sqlEmployees->bindValue(':month', $month, PDO::PARAM_INT);
    $sqlEmployees->bindValue(':year', $year, PDO::PARAM_INT);
    $sqlEmployees->execute();

    $employeeRows = $sqlEmployees->fetchAll(PDO::FETCH_ASSOC);
    $sqlEmployees->closeCursor();

    $employees = [];

    foreach ($employeeRows as $emp) {
        $contact_id = $emp['contact_id'];

        // Employee info
        $personal   = $employeeModel->getPersonalInformation($contact_id);
        $employment = $employeeModel->getEmploymentInformation($contact_id);

        $companyId = $employment['company_id'] ?? null;
        $companyName = $companyModel->getCompany($companyId)['company_name'] ?? 'Unknown';

        $departmentName = $departmentModel->getDepartment(
            $employment['department_id'] ?? null
        )['department_name'] ?? '';

        // Initialize all days with 0
        $days = [];
        for ($d = $startDay; $d <= $endDay; $d++) {
            $days[$d] = 0;
        }

        $employees[$contact_id] = [
            'COMPANY' => $companyName,
            'EMPLOYEE' => '
                <div>
                    <strong>' . htmlspecialchars($personal['file_as'] ?? '') . '</strong>
                </div>
            ',
            'DAYS'  => $days,
            'TOTAL' => 0
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | STEP 2: GET LATE + UNDERTIME TOTALS
    |--------------------------------------------------------------------------
    */
    $sql = $databaseModel->getConnection()->prepare("
        SELECT 
            contact_id,
            DAY(attendance_date) AS attendance_day,
            SUM(late_minutes + undertime_minutes) AS total_minutes
        FROM employee_daily_status
        WHERE 
            MONTH(attendance_date) = :month
            AND YEAR(attendance_date) = :year
            AND DAY(attendance_date) BETWEEN :start_day AND :end_day
            AND (is_late = 'Yes' OR is_undertime = 'Yes')
        GROUP BY contact_id, attendance_day
    ");

    $sql->bindValue(':month', $month, PDO::PARAM_INT);
    $sql->bindValue(':year', $year, PDO::PARAM_INT);
    $sql->bindValue(':start_day', $startDay, PDO::PARAM_INT);
    $sql->bindValue(':end_day', $endDay, PDO::PARAM_INT);
    $sql->execute();

    $rows = $sql->fetchAll(PDO::FETCH_ASSOC);
    $sql->closeCursor();

    /*
    |--------------------------------------------------------------------------
    | STEP 3: OVERLAY LATE / UNDERTIME DATA
    |--------------------------------------------------------------------------
    */
    foreach ($rows as $row) {
        $contact_id = $row['contact_id'];
        $day        = (int) $row['attendance_day'];
        $minutes    = (int) $row['total_minutes'];

        if (isset($employees[$contact_id])) {
            $employees[$contact_id]['DAYS'][$day] = $minutes;
            $employees[$contact_id]['TOTAL'] += $minutes;
        }
    }

    /*
    |--------------------------------------------------------------------------
    | STEP 4: BUILD DATATABLE RESPONSE
    |--------------------------------------------------------------------------
    */
    $response = [];

    foreach ($employees as $employee) {

        $row = [];
        $row['COMPANY']  = $employee['COMPANY'];
        $row['EMPLOYEE'] = $employee['EMPLOYEE'];

        for ($d = $startDay; $d <= $endDay; $d++) {
            $row['DAY_' . $d] = $employee['DAYS'][$d];
        }

        $row['TOTAL'] = $employee['TOTAL'];

        $response[] = $row;
    }

    echo json_encode($response);
    break;

    case 'employee attendance absentism summary table':

    $month  = (int) ($_POST['month'] ?? 0);
    $year   = (int) ($_POST['year'] ?? 0);
    $cutoff = (int) ($_POST['cutoff'] ?? 1);

    if ($cutoff === 1) {
        $startDay = 1;
        $endDay   = 15;
    } else {
        $startDay = 16;
        $endDay   = cal_days_in_month(CAL_GREGORIAN, $month, $year);
    }

    // STEP 1: DISTINCT EMPLOYEES
    $sqlEmployees = $databaseModel->getConnection()->prepare("
        SELECT DISTINCT contact_id
        FROM employee_daily_status
        WHERE MONTH(attendance_date) = :month
          AND YEAR(attendance_date) = :year
    ");
    $sqlEmployees->execute([
        ':month' => $month,
        ':year'  => $year
    ]);

    $employees = [];

    foreach ($sqlEmployees->fetchAll(PDO::FETCH_ASSOC) as $emp) {

        $contact_id = $emp['contact_id'];

        $personal   = $employeeModel->getPersonalInformation($contact_id);
        $employment = $employeeModel->getEmploymentInformation($contact_id);

        $companyName = $companyModel->getCompany(
            $employment['company_id'] ?? null
        )['company_name'] ?? 'Unknown';

        $departmentName = $departmentModel->getDepartment(
            $employment['department_id'] ?? null
        )['department_name'] ?? '';

        for ($d = $startDay; $d <= $endDay; $d++) {
            $days[$d] = 0;
        }

        $employees[$contact_id] = [
            'COMPANY'  => $companyName,
            'EMPLOYEE' => "
                <strong>{$personal['file_as']}</strong>
            ",
            'DAYS'  => $days,
            'TOTAL' => 0
        ];
    }

    // STEP 2: UNPAID LEAVE MINUTES
    $sql = $databaseModel->getConnection()->prepare("
        SELECT 
            contact_id,
            DAY(attendance_date) AS attendance_day,
            SUM(unpaid_leave_minutes) AS minutes
        FROM employee_daily_status
        WHERE 
            is_on_unpaid_leave = 'Yes'
            AND MONTH(attendance_date) = :month
            AND YEAR(attendance_date) = :year
            AND DAY(attendance_date) BETWEEN :start AND :end
        GROUP BY contact_id, attendance_day
    ");

    $sql->execute([
        ':month' => $month,
        ':year'  => $year,
        ':start' => $startDay,
        ':end'   => $endDay
    ]);

    foreach ($sql->fetchAll(PDO::FETCH_ASSOC) as $row) {
        if (isset($employees[$row['contact_id']])) {
            $day = (int) $row['attendance_day'];
            $min = (int) $row['minutes'];

            $employees[$row['contact_id']]['DAYS'][$day] = $min;
            $employees[$row['contact_id']]['TOTAL'] += $min;
        }
    }

    // STEP 3: RESPONSE
    $response = [];
    foreach ($employees as $emp) {
        $row = [
            'COMPANY'  => $emp['COMPANY'],
            'EMPLOYEE' => $emp['EMPLOYEE']
        ];

        for ($d = $startDay; $d <= $endDay; $d++) {
            $row['DAY_'.$d] = $emp['DAYS'][$d];
        }

        $row['TOTAL'] = $emp['TOTAL'];
        $response[] = $row;
    }

    echo json_encode($response);
    break;



        # -------------------------------------------------------------
    }
}

?>