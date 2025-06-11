<?php
require_once '../session.php';
require_once '../config/config.php';
require_once '../model/database-model.php';
require_once '../model/user-model.php';
require_once '../model/security-model.php';
require_once '../model/system-model.php';
require_once '../model/asset-type-model.php';

$databaseModel = new DatabaseModel();
$systemModel = new SystemModel();
$userModel = new UserModel($databaseModel, $systemModel);
$assetTypeModel = new AssetTypeModel($databaseModel);
$securityModel = new SecurityModel();

if(isset($_POST['type']) && !empty($_POST['type'])){
    $type = htmlspecialchars($_POST['type'], ENT_QUOTES, 'UTF-8');
    $response = [];
    
    switch ($type) {
        # -------------------------------------------------------------
        #
        # Type: asset type table
        # Description:
        # Generates the asset type table.
        #
        # Parameters: None
        #
        # Returns: Array
        #
        # -------------------------------------------------------------
        case 'asset type table':
            $sql = $databaseModel->getConnection()->prepare('CALL generateAssetTypeTable()');
            $sql->execute();
            $options = $sql->fetchAll(PDO::FETCH_ASSOC);
            $sql->closeCursor();

            $assetTypeDeleteAccess = $userModel->checkMenuItemAccessRights($user_id, 149, 'delete');

            foreach ($options as $row) {
                $assetTypeID = $row['asset_type_id'];
                $assetTypeName = $row['asset_type_name'];

                $assetTypeIDEncrypted = $securityModel->encryptData($assetTypeID);

                $delete = '';
                if($assetTypeDeleteAccess['total'] > 0){
                    $delete = '<button type="button" class="btn btn-icon btn-danger delete-asset-type" data-asset-type-id="'. $assetTypeID .'" title="Delete Asset Type">
                                    <i class="ti ti-trash"></i>
                                </button>';
                }

                $response[] = [
                    'CHECK_BOX' => '<input class="form-check-input datatable-checkbox-children" type="checkbox" value="'. $assetTypeID .'">',
                    'ASSET_TYPE_NAME' => $assetTypeName,
                    'ACTION' => '<div class="d-flex gap-2">
                                    <a href="asset-type.php?id='. $assetTypeIDEncrypted .'" class="btn btn-icon btn-primary" title="View Details">
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