<?php
require_once '../session.php';
require_once '../config/config.php';
require_once '../model/database-model.php';
require_once '../model/user-model.php';
require_once '../model/security-model.php';
require_once '../model/system-model.php';
require_once '../model/employee-model.php';
require_once '../model/employee-contact-information-model.php';
require_once '../model/system-setting-model.php';

$databaseModel = new DatabaseModel();
$systemModel = new SystemModel();
$userModel = new UserModel($databaseModel, $systemModel);
$employeeModel = new EmployeeModel($databaseModel);
$systemSettingModel = new SystemSettingModel($databaseModel);
$securityModel = new SecurityModel();

if(isset($_POST['type']) && !empty($_POST['type'])){
    $type = htmlspecialchars($_POST['type'], ENT_QUOTES, 'UTF-8');
    $response = [];
    
    switch ($type) {
        # -------------------------------------------------------------
        #
        # Type: employee shortcut contact information table
        # Description:
        # Generates the employee shortcut contact information table.
        #
        # Parameters: None
        #
        # Returns: Array
        #
        # -------------------------------------------------------------
        case 'employee shortcut contact information table':
            if(isset($_POST['employee_id'])){
                $employeeID = htmlspecialchars($_POST['employee_id'], ENT_QUOTES, 'UTF-8');

                $sql = $databaseModel->getConnection()->prepare('CALL generateEmployeeShortcutContactInformationTable(:employeeID)');
                $sql->bindValue(':employeeID', $employeeID, PDO::PARAM_INT);
                $sql->execute();
                $options = $sql->fetchAll(PDO::FETCH_ASSOC);
                $sql->closeCursor();
                
                $employeeUpdateAccess = $userModel->checkMenuItemAccessRights($user_id, 48, 'update');
                $contactInformationDeleteAccess = $userModel->checkMenuItemAccessRights($user_id, 49, 'delete');

                foreach ($options as $row) {
                    $contactInformationID = $row['contact_information_id'];
                    $contactInformationTypeID = $row['contact_information_type_id'];
                    $mobile = $row['mobile'];
                    $telephone = $row['telephone'];
                    $email = $row['email'];
                    $is_primary = $row['is_primary'];

                    $departmentName = $departmentModel->getDepartment($departmentID)['department_name'] ?? null;
                   
                    $employeeIDEncrypted = $securityModel->encryptData($employeeID);

                    $delete = '';
                    if($employeeUpdateAccess['total'] > 0 && $employeeDeleteAccess['total'] > 0){
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