<?php
require_once '../session.php';
require_once '../config/config.php';
require_once '../model/database-model.php';
require_once '../model/user-model.php';
require_once '../model/security-model.php';
require_once '../model/system-model.php';
require_once '../model/employee-model.php';
require_once '../model/department-model.php';
require_once '../model/job-position-model.php';
require_once '../model/job-level-model.php';
require_once '../model/branch-model.php';
require_once '../model/employee-type-model.php';
require_once '../model/system-setting-model.php';

$databaseModel = new DatabaseModel();
$systemModel = new SystemModel();
$userModel = new UserModel($databaseModel, $systemModel);
$employeeModel = new EmployeeModel($databaseModel);
$departmentModel = new DepartmentModel($databaseModel);
$jobPositionModel = new JobPositionModel($databaseModel);
$jobLevelModel = new JobLevelModel($databaseModel);
$branchModel = new BranchModel($databaseModel);
$employeeTypeModel = new EmployeeTypeModel($databaseModel);
$systemSettingModel = new SystemSettingModel($databaseModel);
$securityModel = new SecurityModel();

if(isset($_POST['type']) && !empty($_POST['type'])){
    $type = htmlspecialchars($_POST['type'], ENT_QUOTES, 'UTF-8');
    $response = [];
    
    switch ($type) {
        # -------------------------------------------------------------
        #
        # Type: employee table
        # Description:
        # Generates the employee table.
        #
        # Parameters: None
        #
        # Returns: Array
        #
        # -------------------------------------------------------------
        case 'employee table':
            if(isset($_POST['filter_employment_status']) && isset($_POST['filter_company']) && isset($_POST['filter_department']) && isset($_POST['filter_job_position']) && isset($_POST['filter_job_level']) && isset($_POST['filter_branch']) && isset($_POST['filter_employee_type'])){
                $filterEmployeeStatus = htmlspecialchars($_POST['filter_employment_status'], ENT_QUOTES, 'UTF-8');
                $filterCompany = htmlspecialchars($_POST['filter_company'], ENT_QUOTES, 'UTF-8');
                $filterDepartment = htmlspecialchars($_POST['filter_department'], ENT_QUOTES, 'UTF-8');
                $filterJobPosition = htmlspecialchars($_POST['filter_job_position'], ENT_QUOTES, 'UTF-8');
                $filterJobLevel = htmlspecialchars($_POST['filter_job_level'], ENT_QUOTES, 'UTF-8');
                $filterBranch = htmlspecialchars($_POST['filter_branch'], ENT_QUOTES, 'UTF-8');
                $filterEmployeeType = htmlspecialchars($_POST['filter_employee_type'], ENT_QUOTES, 'UTF-8');

                $sql = $databaseModel->getConnection()->prepare('CALL generateEmployeeTable(:filterEmployeeStatus, :filterCompany, :filterDepartment, :filterJobPosition, :filterJobLevel, :filterBranch, :filterEmployeeType)');
                $sql->bindValue(':filterEmployeeStatus', $filterEmployeeStatus, PDO::PARAM_STR);
                $sql->bindValue(':filterCompany', $filterCompany, PDO::PARAM_INT);
                $sql->bindValue(':filterDepartment', $filterDepartment, PDO::PARAM_INT);
                $sql->bindValue(':filterJobPosition', $filterJobPosition, PDO::PARAM_INT);
                $sql->bindValue(':filterJobLevel', $filterJobLevel, PDO::PARAM_INT);
                $sql->bindValue(':filterBranch', $filterBranch, PDO::PARAM_INT);
                $sql->bindValue(':filterEmployeeType', $filterEmployeeType, PDO::PARAM_INT);
                $sql->execute();
                $options = $sql->fetchAll(PDO::FETCH_ASSOC);
                $sql->closeCursor();
                
                $employeeDeleteAccess = $userModel->checkMenuItemAccessRights($user_id, 48, 'delete');

                foreach ($options as $row) {
                    $employeeID = $row['contact_id'];
                    $firstName = $row['first_name'];
                    $middleName = $row['middle_name'];
                    $lastName = $row['last_name'];
                    $suffix = $row['suffix'];
                    $departmentID = $row['department_id'];
                    $jobpPositionID = $row['job_position_id'];
                    $branchID = $row['branch_id'];
                    $employeeImage = $systemModel->checkImage($row['contact_image'], 'profile');

                    $employeeName = $systemSettingModel->getSystemSetting(4)['value'];
                    $employeeName = str_replace('{last_name}', $lastName, $employeeName);
                    $employeeName = str_replace('{first_name}', $firstName, $employeeName);
                    $employeeName = str_replace('{suffix}', $suffix, $employeeName);
                    $employeeName = str_replace('{middle_name}', $middleName, $employeeName);


                    $departmentName = $departmentModel->getDepartment($departmentID)['department_name'] ?? null;
                    $jobPositionName = $jobPositionModel->getJobPosition($departmentID)['job_position_name'] ?? null;
                    $branchName = $branchModel->getBranch($branchID)['branch_name'] ?? null;
                   
                    $employeeIDEncrypted = $securityModel->encryptData($employeeID);

                    $delete = '';
                    if($employeeDeleteAccess['total'] > 0){
                        $delete = '<button type="button" class="btn btn-icon btn-danger delete-employee" data-employee-id="'. $employeeID .'" title="Delete Employee">
                                            <i class="ti ti-trash"></i>
                                        </button>';
                    }
    
                    $response[] = [
                        'CHECK_BOX' => '<input class="form-check-input datatable-checkbox-children" type="checkbox" value="'. $employeeID .'">',
                        'EMPLOYEE' => '<div class="row">
                                        <div class="col-auto pe-0">
                                        <img src="'. $employeeImage .'" alt="employee-image" class="wid-40 hei-40 rounded-circle">
                                        </div>
                                        <div class="col">
                                        <h6 class="mb-0">'. $employeeName .'</h6>
                                        <p class="text-muted f-12 mb-0">'. $jobPositionName .'</p>
                                        </div>
                                    </div>',
                        'DEPARTMENT' => $departmentName,
                        'BRANCH' => $branchName,
                        'ACTION' => '<div class="d-flex gap-2">
                                    <a href="employee.php?id='. $employeeIDEncrypted .'" class="btn btn-icon btn-primary" title="View Details">
                                        <i class="ti ti-eye"></i>
                                    </a>
                                    '. $delete .'
                                </div>'
                    ];
                }
    
                echo json_encode($response);
            }
        break;
        # -------------------------------------------------------------
    }
}

?>