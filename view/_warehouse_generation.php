<?php
require_once '../session.php';
require_once '../config/config.php';
require_once '../model/database-model.php';
require_once '../model/user-model.php';
require_once '../model/security-model.php';
require_once '../model/system-model.php';
require_once '../model/warehouse-model.php';

$databaseModel = new DatabaseModel();
$systemModel = new SystemModel();
$userModel = new UserModel($databaseModel, $systemModel);
$warehouseModel = new WarehouseModel($databaseModel);
$securityModel = new SecurityModel();

if(isset($_POST['type']) && !empty($_POST['type'])){
    $type = htmlspecialchars($_POST['type'], ENT_QUOTES, 'UTF-8');
    $response = [];
    
    switch ($type) {
        # -------------------------------------------------------------
        #
        # Type: warehouse table
        # Description:
        # Generates the warehouse table.
        #
        # Parameters: None
        #
        # Returns: Array
        #
        # -------------------------------------------------------------
        case 'warehouse table':
            $sql = $databaseModel->getConnection()->prepare('CALL generateWarehouseTable()');
            $sql->execute();
            $options = $sql->fetchAll(PDO::FETCH_ASSOC);
            $sql->closeCursor();

            $warehouseDeleteAccess = $userModel->checkMenuItemAccessRights($user_id, 64, 'delete');

            foreach ($options as $row) {
                $warehouseID = $row['warehouse_id'];
                $warehouseName = $row['warehouse_name'];

                $warehouseIDEncrypted = $securityModel->encryptData($warehouseID);

                $delete = '';
                if($warehouseDeleteAccess['total'] > 0){
                    $delete = '<button type="button" class="btn btn-icon btn-danger delete-warehouse" data-warehouse-id="'. $warehouseID .'" title="Delete Warehouse">
                                    <i class="ti ti-trash"></i>
                                </button>';
                }

                $response[] = [
                    'CHECK_BOX' => '<input class="form-check-input datatable-checkbox-children" type="checkbox" value="'. $warehouseID .'">',
                    'WAREHOUSE_NAME' => $warehouseName,
                    'ACTION' => '<div class="d-flex gap-2">
                                    <a href="warehouse.php?id='. $warehouseIDEncrypted .'" class="btn btn-icon btn-primary" title="View Details">
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