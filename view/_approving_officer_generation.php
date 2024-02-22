<?php
require_once '../session.php';
require_once '../config/config.php';
require_once '../model/database-model.php';
require_once '../model/user-model.php';
require_once '../model/security-model.php';
require_once '../model/system-model.php';
require_once '../model/approving-officer-model.php';
require_once '../model/customer-model.php';

$databaseModel = new DatabaseModel();
$systemModel = new SystemModel();
$userModel = new UserModel($databaseModel, $systemModel);
$approvingOfficerModel = new ApprovingOfficerModel($databaseModel);
$customerModel = new CustomerModel($databaseModel);
$securityModel = new SecurityModel();

if(isset($_POST['type']) && !empty($_POST['type'])){
    $type = htmlspecialchars($_POST['type'], ENT_QUOTES, 'UTF-8');
    $response = [];
    
    switch ($type) {
        # -------------------------------------------------------------
        #
        # Type: approving officer table
        # Description:
        # Generates the approving officer table.
        #
        # Parameters: None
        #
        # Returns: Array
        #
        # -------------------------------------------------------------
        case 'approving officer table':
            $sql = $databaseModel->getConnection()->prepare('CALL generateApprovingOfficerTable()');
            $sql->execute();
            $options = $sql->fetchAll(PDO::FETCH_ASSOC);
            $sql->closeCursor();

            $approvingOfficerDeleteAccess = $userModel->checkMenuItemAccessRights($user_id, 71, 'delete');

            foreach ($options as $row) {
                $approvingOfficerID = $row['approving_officer_id'];
                $contactID = $row['contact_id'];
                $approvingOfficerType = $row['approving_officer_type'];

                $approvingOfficerIDEncrypted = $securityModel->encryptData($approvingOfficerID);

                $approverDetails = $customerModel->getPersonalInformation($contactID);
                $approverName = $approverDetails['file_as'] ?? null;

                $delete = '';
                if($approvingOfficerDeleteAccess['total'] > 0){
                    $delete = '<button type="button" class="btn btn-icon btn-danger delete-approving-officer" data-approving-officer-id="'. $approvingOfficerID .'" title="Delete File Type">
                                        <i class="ti ti-trash"></i>
                                    </button>';
                }

                $response[] = [
                    'CHECK_BOX' => '<input class="form-check-input datatable-checkbox-children" type="checkbox" value="'. $approvingOfficerID .'">',
                    'FILE_AS' => $approverName,
                    'APPROVING_OFFICER_TYPE' => $approvingOfficerType,
                    'ACTION' => '<div class="d-flex gap-2">
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