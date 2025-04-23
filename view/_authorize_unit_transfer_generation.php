<?php
require_once '../session.php';
require_once '../config/config.php';
require_once '../model/database-model.php';
require_once '../model/user-model.php';
require_once '../model/security-model.php';
require_once '../model/system-model.php';
require_once '../model/authorize-unit-transfer-model.php';
require_once '../model/warehouse-model.php';

$databaseModel = new DatabaseModel();
$systemModel = new SystemModel();
$userModel = new UserModel($databaseModel, $systemModel);
$authorizeUnitTransferModel = new AuthorizeUnitTransferModel($databaseModel);
$warehouseModel = new WarehouseModel($databaseModel);
$securityModel = new SecurityModel();

if(isset($_POST['type']) && !empty($_POST['type'])){
    $type = htmlspecialchars($_POST['type'], ENT_QUOTES, 'UTF-8');
    $response = [];
    
    switch ($type) {
        # -------------------------------------------------------------
        #
        # Type: authorize unit transfer table
        # Description:
        # Generates the authorize unit transfer table.
        #
        # Parameters: None
        #
        # Returns: Array
        #
        # -------------------------------------------------------------
        case 'authorize unit transfer table':
            $sql = $databaseModel->getConnection()->prepare('CALL generateAuthorizeUnitTransferTable()');
            $sql->execute();
            $options = $sql->fetchAll(PDO::FETCH_ASSOC);
            $sql->closeCursor();

            $authorizeUnitTransferDeleteAccess = $userModel->checkMenuItemAccessRights($user_id, 137, 'delete');

            foreach ($options as $row) {
                $authorizeUnitTransferID = $row['authorize_unit_transfer_id'];
                $warehouse_id = $row['warehouse_id'];
                $user_id = $row['user_id'];

                $warehouseDetails = $warehouseModel->getWarehouse($warehouse_id);
                $warehouse_name = $warehouseDetails['warehouse_name'] ?? null;

                $scannedBy = $userModel->getUserByID($user_id);
                $scannedByName = $scannedBy['file_as'] ?? null;

                $authorizeUnitTransferIDEncrypted = $securityModel->encryptData($authorizeUnitTransferID);

                $delete = '';
                if($authorizeUnitTransferDeleteAccess['total'] > 0){
                    $delete = '<button type="button" class="btn btn-icon btn-danger delete-authorize-unit-transfer" data-authorize-unit-transfer-id="'. $authorizeUnitTransferID .'" title="Delete Authorize Unit Transfer">
                                    <i class="ti ti-trash"></i>
                                </button>';
                }

                $response[] = [
                    'CHECK_BOX' => '<input class="form-check-input datatable-checkbox-children" type="checkbox" value="'. $authorizeUnitTransferID .'">',
                    'WAREHOUSE' => $warehouse_name,
                    'USER' => $scannedByName,
                    'ACTION' => '<div class="d-flex gap-2">
                                    <a href="authorize-unit-transfer.php?id='. $authorizeUnitTransferIDEncrypted .'" class="btn btn-icon btn-primary" title="View Details">
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