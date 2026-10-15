<?php
require_once '../session.php';
require_once '../config/config.php';
require_once '../model/database-model.php';
require_once '../model/user-model.php';
require_once '../model/security-model.php';
require_once '../model/system-model.php';
require_once '../model/insurance-category-model.php';

$databaseModel = new DatabaseModel();
$systemModel = new SystemModel();
$userModel = new UserModel($databaseModel, $systemModel);
$insuranceCategoryModel = new InsuranceCategoryModel($databaseModel);
$securityModel = new SecurityModel();

if(isset($_POST['type']) && !empty($_POST['type'])){
    $type = htmlspecialchars($_POST['type'], ENT_QUOTES, 'UTF-8');
    $response = [];
    
    switch ($type) {
        case 'insurance category table':
            $sql = $databaseModel->getConnection()->prepare('SELECT * FROM insurance_category');
            $sql->execute();
            $options = $sql->fetchAll(PDO::FETCH_ASSOC);
            $sql->closeCursor();

            $insuranceCategoryDeleteAccess = $userModel->checkMenuItemAccessRights($user_id, 207, 'delete');

            foreach ($options as $row) {
                $insuranceCategoryID = $row['insurance_category_id'];
                $insuranceCategoryName = $row['insurance_category_name'];

                $insuranceCategoryIDEncrypted = $securityModel->encryptData($insuranceCategoryID);

                $delete = '';
                if($insuranceCategoryDeleteAccess['total'] > 0){
                    $delete = '<button type="button" class="btn btn-icon btn-danger delete-insurance-category" data-insurance-category-id="'. $insuranceCategoryID .'" title="Delete Insurance Category">
                                    <i class="ti ti-trash"></i>
                                </button>';
                }

                $response[] = [
                    'CHECK_BOX' => '<input class="form-check-input datatable-checkbox-children" type="checkbox" value="'. $insuranceCategoryID .'">',
                    'INSURANCE_CATEGORY_NAME' => $insuranceCategoryName,
                    'ACTION' => '<div class="d-flex gap-2">
                                    <a href="insurance-category.php?id='. $insuranceCategoryIDEncrypted .'" class="btn btn-icon btn-primary" title="View Details">
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