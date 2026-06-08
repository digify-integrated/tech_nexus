<?php
require_once '../session.php';
require_once '../config/config.php';
require_once '../model/database-model.php';
require_once '../model/user-model.php';
require_once '../model/security-model.php';
require_once '../model/system-model.php';
require_once '../model/lead-source-model.php';

$databaseModel = new DatabaseModel();
$systemModel = new SystemModel();
$userModel = new UserModel($databaseModel, $systemModel);
$leadSourceModel = new LeadSourceModel($databaseModel);
$securityModel = new SecurityModel();

if(isset($_POST['type']) && !empty($_POST['type'])){
    $type = htmlspecialchars($_POST['type'], ENT_QUOTES, 'UTF-8');
    $response = [];
    
    switch ($type) {
        case 'lead source table':
            $sql = $databaseModel->getConnection()->prepare('SELECT * FROM lead_source');
            $sql->execute();
            $options = $sql->fetchAll(PDO::FETCH_ASSOC);
            $sql->closeCursor();

            $leadSourceDeleteAccess = $userModel->checkMenuItemAccessRights($user_id, 194, 'delete');

            foreach ($options as $row) {
                $leadSourceID = $row['lead_source_id'];
                $leadSourceName = $row['lead_source_name'];

                $leadSourceIDEncrypted = $securityModel->encryptData($leadSourceID);

                $delete = '';
                if($leadSourceDeleteAccess['total'] > 0){
                    $delete = '<button type="button" class="btn btn-icon btn-danger delete-lead-source" data-lead-source-id="'. $leadSourceID .'" title="Delete Lead Source">
                                    <i class="ti ti-trash"></i>
                                </button>';
                }

                $response[] = [
                    'CHECK_BOX' => '<input class="form-check-input datatable-checkbox-children" type="checkbox" value="'. $leadSourceID .'">',
                    'LEAD_SOURCE_NAME' => $leadSourceName,
                    'ACTION' => '<div class="d-flex gap-2">
                                    <a href="lead-source.php?id='. $leadSourceIDEncrypted .'" class="btn btn-icon btn-primary" title="View Details">
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