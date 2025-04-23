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
require_once '../model/branch-model.php';

$databaseModel = new DatabaseModel();
$systemModel = new SystemModel();
$userModel = new UserModel($databaseModel, $systemModel);
$employeeModel = new EmployeeModel($databaseModel);
$departmentModel = new DepartmentModel($databaseModel);
$customerModel = new CustomerModel($databaseModel);
$productModel = new ProductModel($databaseModel);
$salesProposalModel = new SalesProposalModel($databaseModel);
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
                $remarks = $row['remarks'];

                $attendance_date = $systemModel->checkDate('empty', $row['attendance_date'], '', 'F d, Y', '');

                $statusClasses = [
                    'Present' => 'success',
                    'Late' => 'warning',
                    'Absent' => 'danger',
                    'On-Leave' => 'info',
                    'Official Business' => 'success',
                ];

                $employeeDetails = $employeeModel->getPersonalInformation($contact_id);
                $fileAs = $employeeDetails['file_as'] ?? '';

                $employeeDetails = $employeeModel->getEmploymentInformation($contact_id);
                $departmentID = $employeeDetails['department_id'] ?? null;
                $branch_id = $employeeDetails['branch_id'] ?? null;

                $departmentName = $departmentModel->getDepartment($departmentID)['department_name'] ?? null;
                $branchName = $branchModel->getBranch($branch_id)['branch_name'] ?? null;
                
                $defaultClass = 'dark';
                
                $class = $statusClasses[$status] ?? $defaultClass;
                
                $badge = '<span class="badge bg-' . $class . '">' . $status . '</span>';

                $action = '';
                if($status != 'Present'){
                    $action = '<button type="button" class="btn btn-icon btn-success add-remarks" data-bs-toggle="offcanvas" data-bs-target="#add-remarks-offcanvas" aria-controls="add-remarks-offcanvas" data-employee-daily-status-id="'. $employee_daily_status_id .'" title="Add Remarks">
                                        <i class="ti ti-file-text"></i>
                                    </button>';
                }

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
            $filter_attendance_date = $systemModel->checkDate('empty', $_POST['filter_attendance_date'], '', 'Y-m-d', '');
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

            $sql = $databaseModel->getConnection()->prepare('CALL generateDailyEmployeeStatusTable(:filter_attendance_date, :filter_status, :filter_branch)');
            $sql->bindValue(':filter_attendance_date', $filter_attendance_date, PDO::PARAM_STR);
            $sql->bindValue(':filter_status', $filter_status, PDO::PARAM_STR);
            $sql->bindValue(':filter_branch', $filter_branch, PDO::PARAM_STR);
            $sql->execute();
            $options = $sql->fetchAll(PDO::FETCH_ASSOC);
            $sql->closeCursor();

            $list = '';
            foreach ($options as $row) {
                $employee_daily_status_id = $row['employee_daily_status_id'];
                $contact_id = $row['contact_id'];
                $status = $row['status'];
                $remarks = $row['remarks'];

                $attendance_date = $systemModel->checkDate('empty', $row['attendance_date'], '', 'F d, Y', '');

                $statusClasses = [
                    'Present' => 'success',
                    'Late' => 'warning',
                    'Absent' => 'danger',
                    'On-Leave' => 'info',
                    'Official Business' => 'success',
                ];

                $class = $statusClasses[$status] ?? $defaultClass;

                $employeeDetails = $employeeModel->getPersonalInformation($contact_id);
                $fileAs = $employeeDetails['file_as'] ?? '';
                $employeeImage = $systemModel->checkImage($employeeDetails['contact_image'], 'profile');

                $employeeDetails = $employeeModel->getEmploymentInformation($contact_id);
                $departmentID = $employeeDetails['department_id'] ?? null;
                $branch_id = $employeeDetails['branch_id'] ?? null;

                $departmentName = $departmentModel->getDepartment($departmentID)['department_name'] ?? null;

                if($status == $list_type){
                    $list .= '<div class="align-middle m-b-25"><img src="'. $employeeImage .'" alt="user image" class="img-radius align-top m-r-15">
                    <div class="d-inline-block">
                     <h6>'. $fileAs .'</h6>
                    <p class="m-b-0">'. $departmentName .'</p><span class="status '. $class .'"></span>
                    </div>
                </div>';
                }
            }

            if(empty($list)){
                $list = ' <h6 class="text-center pb-5">No Employee Found</h6>';
            }

            echo json_encode(['LIST' => $list]);
        break;
        # -------------------------------------------------------------
    }
}

?>