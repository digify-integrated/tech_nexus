<?php
require_once '../session.php';
require_once '../config/config.php';
require_once '../model/database-model.php';
require_once '../model/user-model.php';
require_once '../model/security-model.php';
require_once '../model/system-model.php';
require_once '../model/supplier-model.php';

$databaseModel = new DatabaseModel();
$systemModel = new SystemModel();
$userModel = new UserModel($databaseModel, $systemModel);
$supplierModel = new SupplierModel($databaseModel);
$securityModel = new SecurityModel();

if(isset($_POST['type']) && !empty($_POST['type'])){
    $type = htmlspecialchars($_POST['type'], ENT_QUOTES, 'UTF-8');
    $response = [];
    
    switch ($type) {
        # -------------------------------------------------------------
        #
        # Type: supplier table
        # Description:
        # Generates the supplier table.
        #
        # Parameters: None
        #
        # Returns: Array
        #
        # -------------------------------------------------------------
        case 'supplier table':
            $sql = $databaseModel->getConnection()->prepare('CALL generateSupplierTable()');
            $sql->execute();
            $options = $sql->fetchAll(PDO::FETCH_ASSOC);
            $sql->closeCursor();

            $supplierDeleteAccess = $userModel->checkMenuItemAccessRights($user_id, 102, 'delete');

            foreach ($options as $row) {
                $supplierID = $row['supplier_id'];
                $supplierName = $row['supplier_name'];

                $supplierIDEncrypted = $securityModel->encryptData($supplierID);

                $delete = '';
                if($supplierDeleteAccess['total'] > 0){
                    $delete = '<button type="button" class="btn btn-icon btn-danger delete-supplier" data-supplier-id="'. $supplierID .'" title="Delete Supplier">
                                    <i class="ti ti-trash"></i>
                                </button>';
                }

                $response[] = [
                    'CHECK_BOX' => '<input class="form-check-input datatable-checkbox-children" type="checkbox" value="'. $supplierID .'">',
                    'SUPPLIER_NAME' => $supplierName,
                    'ACTION' => '<div class="d-flex gap-2">
                                    <a href="supplier.php?id='. $supplierIDEncrypted .'" class="btn btn-icon btn-primary" title="View Details">
                                        <i class="ti ti-eye"></i>
                                    </a>
                                    '. $delete .'
                                </div>'
                    ];
            }

            echo json_encode($response);
        break;
        # -------------------------------------------------------------

        # -------------------------------------------------------------
        #
        # Type: supplier reference table
        # Description:
        # Generates the supplier reference table.
        #
        # Parameters: None
        #
        # Returns: Array
        #
        # -------------------------------------------------------------
        case 'supplier reference table':
            $sql = $databaseModel->getConnection()->prepare('CALL generateSupplierTable()');
            $sql->execute();
            $options = $sql->fetchAll(PDO::FETCH_ASSOC);
            $sql->closeCursor();

            foreach ($options as $row) {
                $supplierID = $row['supplier_id'];
                $supplierName = $row['supplier_name'];

                $response[] = [
                    'SUPPLIER_ID' => $supplierID,
                    'SUPPLIER' => $supplierName
                ];
            }

            echo json_encode($response);
        break;
        # -------------------------------------------------------------
    }
}

?>