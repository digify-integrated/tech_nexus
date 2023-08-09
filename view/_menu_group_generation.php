<?php
require_once '../session.php';
require_once '../config/config.php';
require_once '../model/database-model.php';
require_once '../model/user-model.php';
require_once '../model/security-model.php';
require_once '../model/system-model.php';
require_once '../model/menu-item-model.php';

$databaseModel = new DatabaseModel();
$systemModel = new SystemModel();
$userModel = new UserModel($databaseModel, $systemModel);
$menuItemModel = new MenuItemModel($databaseModel);
$securityModel = new SecurityModel();

if(isset($_POST['type']) && !empty($_POST['type'])){
    $type = htmlspecialchars($_POST['type'], ENT_QUOTES, 'UTF-8');
    $response = [];
    
    switch ($type) {
        # -------------------------------------------------------------
        #
        # Type: menu group table
        # Description:
        # Generates the menu group table.
        #
        # Parameters: None
        #
        # Returns: Array
        #
        # -------------------------------------------------------------
        case 'menu group table':
            $sql = $databaseModel->getConnection()->prepare('CALL generateMenuGroupTable()');
            $sql->execute();
            $options = $sql->fetchAll(PDO::FETCH_ASSOC);
            $sql->closeCursor();

            $menuGroupDeleteAccess = $userModel->checkMenuItemAccessRights($user_id, 2, 'delete');

            foreach ($options as $row) {
                $menuGroupID = $row['menu_group_id'];
                $menuGroupName = $row['menu_group_name'];
                $orderSequence = $row['order_sequence'];

                $menuGroupIDEncrypted = $securityModel->encryptData($menuGroupID);

                $delete = '';
                if ($menuGroupDeleteAccess['total'] > 0) {
                    $delete = '<button type="button" class="btn btn-icon btn-danger delete-menu-group" data-menu-group-id="' . $menuGroupID . '" title="Delete Menu Group">
                                    <i class="ti ti-trash"></i>
                                </button>';
                }

                $response[] = [
                    'CHECK_BOX' => '<input class="form-check-input datatable-checkbox-children" type="checkbox" value="'. $menuGroupID .'">',
                    'MENU_GROUP_NAME' => $menuGroupName,
                    'ORDER_SEQUENCE' => $orderSequence,
                    'ACTION' => '<div class="d-flex gap-2">
                                    <a href="menu-group.php?id='. $menuGroupIDEncrypted .'" class="btn btn-icon btn-primary" title="View Details">
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