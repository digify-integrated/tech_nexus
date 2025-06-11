<?php
require_once '../session.php';
require_once '../config/config.php';
require_once '../model/database-model.php';
require_once '../model/user-model.php';
require_once '../model/security-model.php';
require_once '../model/system-model.php';
require_once '../model/business-premises-model.php';

$databaseModel = new DatabaseModel();
$systemModel = new SystemModel();
$userModel = new UserModel($databaseModel, $systemModel);
$businessPremisesModel = new BusinessPremisesModel($databaseModel);
$securityModel = new SecurityModel();

if(isset($_POST['type']) && !empty($_POST['type'])){
    $type = htmlspecialchars($_POST['type'], ENT_QUOTES, 'UTF-8');
    $response = [];
    
    switch ($type) {
        # -------------------------------------------------------------
        #
        # Type: business premises table
        # Description:
        # Generates the business premises table.
        #
        # Parameters: None
        #
        # Returns: Array
        #
        # -------------------------------------------------------------
        case 'business premises table':
            $sql = $databaseModel->getConnection()->prepare('CALL generateBusinessPremisesTable()');
            $sql->execute();
            $options = $sql->fetchAll(PDO::FETCH_ASSOC);
            $sql->closeCursor();

            $businessPremisesDeleteAccess = $userModel->checkMenuItemAccessRights($user_id, 153, 'delete');

            foreach ($options as $row) {
                $businessPremisesID = $row['business_premises_id'];
                $businessPremisesName = $row['business_premises_name'];

                $businessPremisesIDEncrypted = $securityModel->encryptData($businessPremisesID);

                $delete = '';
                if($businessPremisesDeleteAccess['total'] > 0){
                    $delete = '<button type="button" class="btn btn-icon btn-danger delete-business-premises" data-business-premises-id="'. $businessPremisesID .'" title="Delete Business Premises">
                                    <i class="ti ti-trash"></i>
                                </button>';
                }

                $response[] = [
                    'CHECK_BOX' => '<input class="form-check-input datatable-checkbox-children" type="checkbox" value="'. $businessPremisesID .'">',
                    'BUSINESS_PREMISES_NAME' => $businessPremisesName,
                    'ACTION' => '<div class="d-flex gap-2">
                                    <a href="business-premises.php?id='. $businessPremisesIDEncrypted .'" class="btn btn-icon btn-primary" title="View Details">
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