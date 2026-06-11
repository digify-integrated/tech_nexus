<?php
require_once '../session.php';
require_once '../config/config.php';
require_once '../model/database-model.php';
require_once '../model/user-model.php';
require_once '../model/security-model.php';
require_once '../model/system-model.php';
require_once '../model/insurance-request-model.php';

$databaseModel = new DatabaseModel();
$systemModel = new SystemModel();
$userModel = new UserModel($databaseModel, $systemModel);
$insuranceRequestModel = new InsuranceRequestModel($databaseModel);
$securityModel = new SecurityModel();

if(isset($_POST['type']) && !empty($_POST['type'])){
    $type = htmlspecialchars($_POST['type'], ENT_QUOTES, 'UTF-8');
    $response = [];
    
    switch ($type) {
        case 'insurance request table':
            $sql = $databaseModel->getConnection()->prepare('SELECT * FROM inquiry_type');
            $sql->execute();
            $options = $sql->fetchAll(PDO::FETCH_ASSOC);
            $sql->closeCursor();

            $insuranceRequestDeleteAccess = $userModel->checkMenuItemAccessRights($user_id, 193, 'delete');

            foreach ($options as $row) {
                $insuranceRequestID = $row['inquiry_type_id'];
                $insuranceRequestName = $row['inquiry_type_name'];

                $insuranceRequestIDEncrypted = $securityModel->encryptData($insuranceRequestID);

                $delete = '';
                if($insuranceRequestDeleteAccess['total'] > 0){
                    $delete = '<button type="button" class="btn btn-icon btn-danger delete-insurance-request" data-insurance-request-id="'. $insuranceRequestID .'" title="Delete Insurance Request">
                                    <i class="ti ti-trash"></i>
                                </button>';
                }

                $response[] = [
                    'CHECK_BOX' => '<input class="form-check-input datatable-checkbox-children" type="checkbox" value="'. $insuranceRequestID .'">',
                    'INQUIRY_TYPE_NAME' => $insuranceRequestName,
                    'ACTION' => '<div class="d-flex gap-2">
                                    <a href="insurance-request.php?id='. $insuranceRequestIDEncrypted .'" class="btn btn-icon btn-primary" title="View Details">
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