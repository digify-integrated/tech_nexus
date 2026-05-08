<?php
require_once '../session.php';
require_once '../config/config.php';
require_once '../model/database-model.php';
require_once '../model/user-model.php';
require_once '../model/security-model.php';
require_once '../model/system-model.php';
require_once '../model/lead-status-model.php';

$databaseModel = new DatabaseModel();
$systemModel = new SystemModel();
$userModel = new UserModel($databaseModel, $systemModel);
$leadStatusModel = new LeadStatusModel($databaseModel);
$securityModel = new SecurityModel();

if(isset($_POST['type']) && !empty($_POST['type'])){
    $type = htmlspecialchars($_POST['type'], ENT_QUOTES, 'UTF-8');
    $response = [];
    
    switch ($type) {
        case 'lead status table':
            $sql = $databaseModel->getConnection()->prepare('SELECT * FROM lead_status');
            $sql->execute();
            $options = $sql->fetchAll(PDO::FETCH_ASSOC);
            $sql->closeCursor();

            $leadStatusDeleteAccess = $userModel->checkMenuItemAccessRights($user_id, 192, 'delete');

            foreach ($options as $row) {
                $leadStatusID = $row['lead_status_id'];
                $leadStatusName = $row['lead_status_name'];

                $leadStatusIDEncrypted = $securityModel->encryptData($leadStatusID);

                $delete = '';
                if($leadStatusDeleteAccess['total'] > 0){
                    $delete = '<button type="button" class="btn btn-icon btn-danger delete-lead-status" data-lead-status-id="'. $leadStatusID .'" title="Delete Lead Status">
                                    <i class="ti ti-trash"></i>
                                </button>';
                }

                $response[] = [
                    'CHECK_BOX' => '<input class="form-check-input datatable-checkbox-children" type="checkbox" value="'. $leadStatusID .'">',
                    'LEAD_STATUS_NAME' => $leadStatusName,
                    'ACTION' => '<div class="d-flex gap-2">
                                    <a href="lead-status.php?id='. $leadStatusIDEncrypted .'" class="btn btn-icon btn-primary" title="View Details">
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