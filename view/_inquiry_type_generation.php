<?php
require_once '../session.php';
require_once '../config/config.php';
require_once '../model/database-model.php';
require_once '../model/user-model.php';
require_once '../model/security-model.php';
require_once '../model/system-model.php';
require_once '../model/inquiry-type-model.php';

$databaseModel = new DatabaseModel();
$systemModel = new SystemModel();
$userModel = new UserModel($databaseModel, $systemModel);
$inquiryTypeModel = new InquiryTypeModel($databaseModel);
$securityModel = new SecurityModel();

if(isset($_POST['type']) && !empty($_POST['type'])){
    $type = htmlspecialchars($_POST['type'], ENT_QUOTES, 'UTF-8');
    $response = [];
    
    switch ($type) {
        case 'inquiry type table':
            $sql = $databaseModel->getConnection()->prepare('SELECT * FROM inquiry_type');
            $sql->execute();
            $options = $sql->fetchAll(PDO::FETCH_ASSOC);
            $sql->closeCursor();

            $inquiryTypeDeleteAccess = $userModel->checkMenuItemAccessRights($user_id, 193, 'delete');

            foreach ($options as $row) {
                $inquiryTypeID = $row['inquiry_type_id'];
                $inquiryTypeName = $row['inquiry_type_name'];

                $inquiryTypeIDEncrypted = $securityModel->encryptData($inquiryTypeID);

                $delete = '';
                if($inquiryTypeDeleteAccess['total'] > 0){
                    $delete = '<button type="button" class="btn btn-icon btn-danger delete-inquiry-type" data-inquiry-type-id="'. $inquiryTypeID .'" title="Delete Inquiry Type">
                                    <i class="ti ti-trash"></i>
                                </button>';
                }

                $response[] = [
                    'CHECK_BOX' => '<input class="form-check-input datatable-checkbox-children" type="checkbox" value="'. $inquiryTypeID .'">',
                    'INQUIRY_TYPE_NAME' => $inquiryTypeName,
                    'ACTION' => '<div class="d-flex gap-2">
                                    <a href="inquiry-type.php?id='. $inquiryTypeIDEncrypted .'" class="btn btn-icon btn-primary" title="View Details">
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