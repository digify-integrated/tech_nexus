<?php
require_once '../session.php';
require_once '../config/config.php';
require_once '../model/database-model.php';
require_once '../model/user-model.php';
require_once '../model/security-model.php';

$databaseModel = new DatabaseModel();
$userModel = new UserModel($databaseModel);
$securityModel = new SecurityModel();

if(isset($_POST['type']) && !empty($_POST['type'])){
    $type = htmlspecialchars($_POST['type'], ENT_QUOTES, 'UTF-8');
    
    switch ($type) {
        # Menu group table
        case 'menu group table':
            $sql = $databaseModel->getConnection()->prepare('SELECT menu_group_id, menu_group_name, order_sequence FROM menu_group');
    
                if($sql->execute()){                    
                    $menuGroupDeleteAccess = $userModel->checkMenuItemAccessRights($user_id, 2, 'delete');

                    while($row = $sql->fetch()){
                        $menu_group_id = $row['menu_group_id'];
                        $menu_group_name = $row['menu_group_name'];
                        $order_sequence = $row['order_sequence'];
    
                        $menu_group_id_encrypted = $securityModel->encryptData($menu_group_id);

                        if($menuGroupDeleteAccess['total'] > 0){
                            $delete = '<button type="button" class="btn btn-icon btn-danger delete-menu-group" data-menu-group-id="' . $menu_group_id . '" title="Delete Menu Group">
                                            <i class="ti ti-trash"></i>
                                        </button>';
                        }
                        else{
                            $delete = null;
                        }
    
                        $response[] = array(
                            'CHECK_BOX' => '<input class="form-check-input datatable-checkbox-children" data-delete="1" type="checkbox" value="'. $menu_group_id .'">',
                            'MENU_GROUP_ID' => $menu_group_id,
                            'MENU_GROUP_NAME' => $menu_group_name,
                            'ORDER_SEQUENCE' => $order_sequence,
                            'ACTION' => '<div class="d-flex gap-2">
                                            <a href="menu-group.php?id='. $menu_group_id_encrypted .'" class="btn btn-icon btn-primary" title="View Details">
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
    }
}

?>