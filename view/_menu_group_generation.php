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
    $response = array();
    
    switch ($type) {
        # Menu group table
        case 'menu group table':
            $sql = $databaseModel->getConnection()->prepare('SELECT menu_group_id, menu_group_name, order_sequence FROM menu_group');
    
            if($sql->execute()){                    
                $menuGroupDeleteAccess = $userModel->checkMenuItemAccessRights($user_id, 2, 'delete');

                while($row = $sql->fetch()){
                    $menuGroupID = $row['menu_group_id'];
                    $menuGroupName = $row['menu_group_name'];
                    $orderSequence = $row['order_sequence'];
    
                    $menuGroupIDEncrypted = $securityModel->encryptData($menuGroupID);

                    if($menuGroupDeleteAccess['total'] > 0){
                        $delete = '<button type="button" class="btn btn-icon btn-danger delete-menu-group" data-menu-group-id="' . $menuGroupID . '" title="Delete Menu Group">
                                            <i class="ti ti-trash"></i>
                                        </button>';
                    }
                    else{
                        $delete = null;
                    }
    
                    $response[] = array(
                        'CHECK_BOX' => '<input class="form-check-input datatable-checkbox-children" data-delete="1" type="checkbox" value="'. $menuGroupID .'">',
                        'MENU_GROUP_ID' => $menuGroupID,
                        'MENU_GROUP_NAME' => $menuGroupName,
                        'ORDER_SEQUENCE' => $orderSequence,
                        'ACTION' => '<div class="d-flex gap-2">
                                            <a href="menu-group.php?id='. $menuGroupIDEncrypted .'" class="btn btn-icon btn-primary" title="View Details">
                                                <i class="ti ti-eye"></i>
                                            </a>
                                            '. $delete .'
                                        </div>'
                    );
                }
    
                echo json_encode($response);
            }
            else{
                echo $sql->errorInfo()[2];
            }
        break;
        # Menu group menu item table
        case 'menu group menu item table':
            if(isset($_POST['menu_group_id']) && !empty($_POST['menu_group_id'])){
                $menuGroupID = htmlspecialchars($_POST['menu_group_id'], ENT_QUOTES, 'UTF-8');

                $sql = $databaseModel->getConnection()->prepare('SELECT menu_item_id, menu_item_name, parent_id, order_sequence FROM menu_item WHERE menu_group_id = :menuGroupID');
                $sql->bindValue(':menuGroupID', $menuGroupID);
    
                if($sql->execute()){                    
                    $menuGroupDeleteAccess = $userModel->checkMenuItemAccessRights($user_id, 2, 'delete');
                    $menuGroupWriteAccess = $userModel->checkMenuItemAccessRights($user_id, 2, 'write');
                    $menuItemWriteAccess = $userModel->checkMenuItemAccessRights($user_id, 3, 'write');
                    $menuItemDeleteAccess = $userModel->checkMenuItemAccessRights($user_id, 3, 'delete');
                    $assignMenuItemRoleAccess = $userModel->checkSystemActionAccessRights($user_id, 1);                

                    while($row = $sql->fetch()){
                        $menuItemID = $row['menu_item_id'];
                        $menuItemName = $row['menu_item_name'];
                        $parentID = $row['parent_id'];
                        $orderSequence = $row['order_sequence'];
    
                        $menuItemIDEncrypted = $securityModel->encryptData($menuItemID);
                        $parentIDEncrypted = $securityModel->encryptData($parentID);
                        $menuItemDetails = $menuItemModel->getMenuItem($parentID);
                        $parentMenuItemName = $menuItemDetails['menu_item_name'] ?? null;
                        
                        if($menuItemWriteAccess['total'] > 0 && $menuGroupWriteAccess['total'] > 0){
                            $update = '<button type="button" class="btn btn-icon btn-info update-menu-item" data-menu-item-id="'. $menuItemID .'" title="Edit Menu Item">
                                            <i class="ti ti-pencil"></i>
                                        </button>';
                        }
                        else{
                            $update = null;
                        }

                        if($menuItemDeleteAccess['total'] > 0 && $menuGroupWriteAccess['total'] > 0){
                            $delete = '<button type="button" class="btn btn-icon btn-danger delete-menu-item" data-menu-item-id="'. $menuItemID .'" title="Delete Menu Item">
                                            <i class="ti ti-trash"></i>
                                        </button>';
                        }
                        else{
                            $delete = null;
                        }
    
                        if($assignMenuItemRoleAccess > 0 && $menuGroupWriteAccess['total'] > 0){
                            $assign = '<button type="button" class="btn btn-icon btn-warning assign-menu-item-role-access" data-menu-item-id="'. $menuItemID .'" title="Assign Menu Item Role Access">
                                            <i class="ti ti-user-check"></i>
                                        </button>';
                        }
                        else{
                            $assign = null;
                        }
    
                        $response[] = array(
                            'MENU_ITEM_ID' => $menuItemID,
                            'MENU_ITEM_NAME' => '<a href="menu-item-form.php?id='. $menuItemIDEncrypted .'">'. $menuItemName . '</a>',
                            'PARENT_ID' => '<a href="menu-item-form.php?id='. $parentIDEncrypted .'">'. $parentMenuItemName . '</a>',
                            'ORDER_SEQUENCE' => $orderSequence,
                            'ACTION' => '<div class="d-flex gap-2">
                                        '. $update .'
                                        '. $assign .'
                                        '. $delete .'
                                    </div>'
                        );
                    }
    
                    echo json_encode($response);
                }
                else{
                    echo $sql->errorInfo()[2];
                }
            }
        break;
    }
}

?>