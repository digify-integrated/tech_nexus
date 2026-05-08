<?php
require_once '../session.php';
require_once '../config/config.php';
require_once '../model/database-model.php';
require_once '../model/user-model.php';
require_once '../model/security-model.php';
require_once '../model/system-model.php';
require_once '../model/lead-model.php';

$databaseModel = new DatabaseModel();
$systemModel = new SystemModel();
$userModel = new UserModel($databaseModel, $systemModel);
$leadModel = new LeadModel($databaseModel);
$securityModel = new SecurityModel();

if(isset($_POST['type']) && !empty($_POST['type'])){
    $type = $_POST['type'];
    $response = [];
    
    switch ($type) {

        case 'lead table':
            $leads = $leadModel->generateLeadTable();

            $leadDeleteAccess = $userModel->checkMenuItemAccessRights($user_id, 191, 'delete');

            foreach ($leads as $row) {
                $leadID = $row['lead_id'];

                $leadIDEncrypted = $securityModel->encryptData($leadID);

                $delete = '';
                if($leadDeleteAccess['total'] > 0){
                    $delete = '<button type="button" class="btn btn-icon btn-danger delete-lead" data-lead-id="'. $leadID .'">
                                    <i class="ti ti-trash"></i>
                                </button>';
                }

                $response[] = [
                    'CHECK_BOX' => '<input class="form-check-input datatable-checkbox-children" type="checkbox" value="'. $leadID .'">',
                    'LEAD_NAME' => $row['lead_name'],
                    'EMAIL' => $row['email'],
                    'PHONE' => $row['phone'],
                    'STATUS' => $row['lead_status_name'],
                    'ASSIGNED_TO' => $row['assigned_to_name'],
                    'ACTION' => '<div class="d-flex gap-2">
                                    <a href="lead-monitoring.php?id='. $leadIDEncrypted .'" class="btn btn-icon btn-primary">
                                        <i class="ti ti-eye"></i>
                                    </a>
                                    '. $delete .'
                                </div>'
                ];
            }

            echo json_encode($response);
        break;
    }
}
?>