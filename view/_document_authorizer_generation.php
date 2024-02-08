<?php
require_once '../session.php';
require_once '../config/config.php';
require_once '../model/database-model.php';
require_once '../model/user-model.php';
require_once '../model/security-model.php';
require_once '../model/system-model.php';
require_once '../model/document-authorizer-model.php';
require_once '../model/employee-model.php';
require_once '../model/department-model.php';

$databaseModel = new DatabaseModel();
$systemModel = new SystemModel();
$userModel = new UserModel($databaseModel, $systemModel);
$documentAuthorizerModel = new DocumentAuthorizerModel($databaseModel);
$employeeModel = new EmployeeModel($databaseModel);
$departmentModel = new DepartmentModel($databaseModel);
$securityModel = new SecurityModel();

if(isset($_POST['type']) && !empty($_POST['type'])){
    $type = htmlspecialchars($_POST['type'], ENT_QUOTES, 'UTF-8');
    $response = [];
    
    switch ($type) {
        # -------------------------------------------------------------
        #
        # Type: document authorizer table
        # Description:
        # Generates the document authorizer table.
        #
        # Parameters: None
        #
        # Returns: Array
        #
        # -------------------------------------------------------------
        case 'document authorizer table':
            $sql = $databaseModel->getConnection()->prepare('CALL generateDocumentAuthorizerTable()');
            $sql->execute();
            $options = $sql->fetchAll(PDO::FETCH_ASSOC);
            $sql->closeCursor();

            $documentAuthorizerDeleteAccess = $userModel->checkMenuItemAccessRights($user_id, 58, 'delete');

            foreach ($options as $row) {
                $documentAuthorizerID = $row['document_authorizer_id'];
                $departmentID = $row['department_id'];
                $authorizerID = $row['authorizer_id'];

                $documentAuthorizerIDEncrypted = $securityModel->encryptData($documentAuthorizerID);

                $departmentName = $departmentModel->getDepartment($departmentID)['department_name'] ?? null;

                $authorizerDetails = $employeeModel->getPersonalInformation($authorizerID);
                $authorizerName = $authorizerDetails['file_as'] ?? null;

                $delete = '';
                if($documentAuthorizerDeleteAccess['total'] > 0){
                    $delete = '<button type="button" class="btn btn-icon btn-danger delete-document-authorizer" data-document-authorizer-id="'. $documentAuthorizerID .'" title="Delete Document Authorizer">
                                    <i class="ti ti-trash"></i>
                                </button>';
                }

                $response[] = [
                    'CHECK_BOX' => '<input class="form-check-input datatable-checkbox-children" type="checkbox" value="'. $documentAuthorizerID .'">',
                    'AUTHORIZER_NAME' => $authorizerName,
                    'DEPARTMENT_NAME' => $departmentName,
                    'ACTION' => '<div class="d-flex gap-2">
                                    <a href="document-authorizer.php?id='. $documentAuthorizerIDEncrypted .'" class="btn btn-icon btn-primary" title="View Details">
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