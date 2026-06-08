<?php
require_once '../session.php';
require_once '../config/config.php';
require_once '../model/database-model.php';
require_once '../model/user-model.php';
require_once '../model/security-model.php';
require_once '../model/system-model.php';
require_once '../model/insurance-type-model.php';

$databaseModel = new DatabaseModel();
$systemModel = new SystemModel();
$userModel = new UserModel($databaseModel, $systemModel);
$insuranceTypeModel = new InsuranceTypeModel($databaseModel);
$securityModel = new SecurityModel();

if(isset($_POST['type']) && !empty($_POST['type'])){
    $type = htmlspecialchars($_POST['type'], ENT_QUOTES, 'UTF-8');
    $response = [];
    
    switch ($type) {
        case 'insurance type table':
            $sql = $databaseModel->getConnection()->prepare('SELECT * FROM insurance_type');
            $sql->execute();
            $options = $sql->fetchAll(PDO::FETCH_ASSOC);
            $sql->closeCursor();

            $insuranceTypeDeleteAccess = $userModel->checkMenuItemAccessRights($user_id, 197, 'delete');

            foreach ($options as $row) {
                $insuranceTypeID = $row['insurance_type_id'];
                $insuranceTypeName = $row['insurance_type_name'];

                $insuranceTypeIDEncrypted = $securityModel->encryptData($insuranceTypeID);

                $delete = '';
                if($insuranceTypeDeleteAccess['total'] > 0){
                    $delete = '<button type="button" class="btn btn-icon btn-danger delete-insurance-type" data-insurance-type-id="'. $insuranceTypeID .'" title="Delete Insurance Type">
                                    <i class="ti ti-trash"></i>
                                </button>';
                }

                $response[] = [
                    'CHECK_BOX' => '<input class="form-check-input datatable-checkbox-children" type="checkbox" value="'. $insuranceTypeID .'">',
                    'INQUIRY_TYPE_NAME' => $insuranceTypeName,
                    'ACTION' => '<div class="d-flex gap-2">
                                    <a href="insurance-type.php?id='. $insuranceTypeIDEncrypted .'" class="btn btn-icon btn-primary" title="View Details">
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