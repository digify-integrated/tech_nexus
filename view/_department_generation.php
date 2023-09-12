<?php
require_once '../session.php';
require_once '../config/config.php';
require_once '../model/database-model.php';
require_once '../model/user-model.php';
require_once '../model/security-model.php';
require_once '../model/system-model.php';
require_once '../model/department-model.php';

$databaseModel = new DatabaseModel();
$systemModel = new SystemModel();
$userModel = new UserModel($databaseModel, $systemModel);
$departmentModel = new DepartmentModel($databaseModel);
$securityModel = new SecurityModel();

if(isset($_POST['type']) && !empty($_POST['type'])){
    $type = htmlspecialchars($_POST['type'], ENT_QUOTES, 'UTF-8');
    $response = [];
    
    switch ($type) {
        # -------------------------------------------------------------
        #
        # Type: department table
        # Description:
        # Generates the department table.
        #
        # Parameters: None
        #
        # Returns: Array
        #
        # -------------------------------------------------------------
        case 'department table':
            $sql = $databaseModel->getConnection()->prepare('CALL generateDepartmentTable()');
            $sql->execute();
            $options = $sql->fetchAll(PDO::FETCH_ASSOC);
            $sql->closeCursor();

            $departmentDeleteAccess = $userModel->checkMenuItemAccessRights($user_id, 27, 'delete');

            foreach ($options as $row) {
                $departmentID = $row['department_id'];
                $departmentName = $row['department_name'];
                $parentDepartment = $row['parent_department'];
                $manager = $row['manager'];

                $departmentDetails = $departmentModel->getDepartment($parentDepartment);
                $parentDepartmentName = $departmentDetails['department_name'] ?? null;

                $departmentIDEncrypted = $securityModel->encryptData($departmentID);

                $delete = '';
                if($departmentDeleteAccess['total'] > 0){
                    $delete = '<button type="button" class="btn btn-icon btn-danger delete-department" data-department-id="'. $departmentID .'" title="Delete Department">
                                        <i class="ti ti-trash"></i>
                                    </button>';
                }

                $response[] = [
                    'CHECK_BOX' => '<input class="form-check-input datatable-checkbox-children" type="checkbox" value="'. $departmentID .'">',
                    'DEPARTMENT_NAME' => $departmentName,
                    'PARENT_DEPARTMENT' => $parentDepartmentName,
                    'MANAGER' => null,
                    'EMPLOYEES' => 0,
                    'ACTION' => '<div class="d-flex gap-2">
                                    <a href="department.php?id='. $departmentIDEncrypted .'" class="btn btn-icon btn-primary" title="View Details">
                                        <i class="ti ti-eye"></i>
                                    </a>
                                    '. $delete .'
                                </div>'
                ];
            }

            echo json_encode($response);
        break;
        # -------------------------------------------------------------
    }
}

?>