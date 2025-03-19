<?php
require_once '../session.php';
require_once '../config/config.php';
require_once '../model/database-model.php';
require_once '../model/user-model.php';
require_once '../model/security-model.php';
require_once '../model/system-model.php';
require_once '../model/contractor-model.php';

$databaseModel = new DatabaseModel();
$systemModel = new SystemModel();
$userModel = new UserModel($databaseModel, $systemModel);
$contractorModel = new ContractorModel($databaseModel);
$securityModel = new SecurityModel();

if(isset($_POST['type']) && !empty($_POST['type'])){
    $type = htmlspecialchars($_POST['type'], ENT_QUOTES, 'UTF-8');
    $response = [];
    
    switch ($type) {
        # -------------------------------------------------------------
        #
        # Type: contractor table
        # Description:
        # Generates the contractor table.
        #
        # Parameters: None
        #
        # Returns: Array
        #
        # -------------------------------------------------------------
        case 'contractor table':
            $sql = $databaseModel->getConnection()->prepare('CALL generateContractorTable()');
            $sql->execute();
            $options = $sql->fetchAll(PDO::FETCH_ASSOC);
            $sql->closeCursor();

            $contractorDeleteAccess = $userModel->checkMenuItemAccessRights($user_id,  131, 'delete');

            foreach ($options as $row) {
                $contractorID = $row['contractor_id'];
                $contractorName = $row['contractor_name'];

                $contractorIDEncrypted = $securityModel->encryptData($contractorID);

                $delete = '';
                if($contractorDeleteAccess['total'] > 0){
                    $delete = '<button type="button" class="btn btn-icon btn-danger delete-contractor" data-contractor-id="'. $contractorID .'" title="Delete Contractor">
                                    <i class="ti ti-trash"></i>
                                </button>';
                }

                $response[] = [
                    'CHECK_BOX' => '<input class="form-check-input datatable-checkbox-children" type="checkbox" value="'. $contractorID .'">',
                    'CONTRACTOR_NAME' => $contractorName,
                    'ACTION' => '<div class="d-flex gap-2">
                                    <a href="contractor.php?id='. $contractorIDEncrypted .'" class="btn btn-icon btn-primary" title="View Details">
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