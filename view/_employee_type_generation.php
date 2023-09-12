<?php
require_once '../session.php';
require_once '../config/config.php';
require_once '../model/database-model.php';
require_once '../model/user-model.php';
require_once '../model/security-model.php';
require_once '../model/system-model.php';
require_once '../model/employee-type-model.php';

$databaseModel = new DatabaseModel();
$systemModel = new SystemModel();
$userModel = new UserModel($databaseModel, $systemModel);
$employeeTypeModel = new EmployeeTypeModel($databaseModel);
$securityModel = new SecurityModel();

if(isset($_POST['type']) && !empty($_POST['type'])){
    $type = htmlspecialchars($_POST['type'], ENT_QUOTES, 'UTF-8');
    $response = [];
    
    switch ($type) {
        # -------------------------------------------------------------
        #
        # Type: employee type table
        # Description:
        # Generates the employee type table.
        #
        # Parameters: None
        #
        # Returns: Array
        #
        # -------------------------------------------------------------
        case 'employee type table':
            $sql = $databaseModel->getConnection()->prepare('CALL generateEmployeeTypeTable()');
            $sql->execute();
            $options = $sql->fetchAll(PDO::FETCH_ASSOC);
            $sql->closeCursor();

            $employeeTypeDeleteAccess = $userModel->checkMenuItemAccessRights($user_id, 30, 'delete');

            foreach ($options as $row) {
                $employeeTypeID = $row['employee_type_id'];
                $employeeTypeName = $row['employee_type_name'];

                $employeeTypeIDEncrypted = $securityModel->encryptData($employeeTypeID);

                $delete = '';
                if($employeeTypeDeleteAccess['total'] > 0){
                    $delete = '<button type="button" class="btn btn-icon btn-danger delete-employee-type" data-employee-type-id="'. $employeeTypeID .'" title="Delete Employee Type">
                                    <i class="ti ti-trash"></i>
                                </button>';
                }

                $response[] = [
                    'CHECK_BOX' => '<input class="form-check-input datatable-checkbox-children" type="checkbox" value="'. $employeeTypeID .'">',
                    'EMPLOYEE_TYPE_NAME' => $employeeTypeName,
                    'ACTION' => '<div class="d-flex gap-2">
                                    <a href="employee-type.php?id='. $employeeTypeIDEncrypted .'" class="btn btn-icon btn-primary" title="View Details">
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