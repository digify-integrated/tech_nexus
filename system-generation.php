<?php
require('./config/config.php');
require('./classes/api.php');

if(isset($_POST['type']) && !empty($_POST['type']) && isset($_POST['email_account']) && !empty($_POST['email_account'])){
    $api = new Api;
    $type = $_POST['type'];
    $email_account = $_POST['email_account'];
    $system_date = date('Y-m-d');
    $current_time = date('H:i:s');
    $response = array();

    switch ($type) {
        # -------------------------------------------------------------
        #   Generate table functions
        # -------------------------------------------------------------

        # Menu groups table
        case 'menu groups table':
            if ($api->databaseConnection()) {
                $sql = $api->db_connection->prepare('SELECT menu_group_id, menu_group_name, order_sequence FROM menu_groups');
    
                if($sql->execute()){                    
                    $menu_group_delete_access_right = $api->check_menu_access_rights($email_account, 2, 'delete');

                    while($row = $sql->fetch()){
                        $menu_group_id = $row['menu_group_id'];
                        $menu_group_name = $row['menu_group_name'];
                        $order_sequence = $row['order_sequence'];
    
                        $menu_group_id_encrypted = $api->encrypt_data($menu_group_id);

                        if($menu_group_delete_access_right > 0){
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
                                            <a href="menu-group-form.php?id='. $menu_group_id_encrypted .'" class="btn btn-icon btn-primary" title="View Menu Group">
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
            }
        break;

        # Menu item table
        case 'menu group menu item table':
            if(isset($_POST['menu_group_id']) && !empty($_POST['menu_group_id'])){
                if ($api->databaseConnection()) {
                    $menu_group_id = $_POST['menu_group_id'];
                    $menu_group_write_access_right = $api->check_menu_access_rights($email_account, 2, 'write');
                    $menu_item_write_access_right = $api->check_menu_access_rights($email_account, 3, 'write');
                    $menu_item_delete_access_right = $api->check_menu_access_rights($email_account, 3, 'delete');
                    $assign_menu_item_role_access = $api->check_system_action_access_rights($email_account, 1);

                    $sql = $api->db_connection->prepare('SELECT menu_item_id, menu_item_name, parent_id, order_sequence FROM menu_item WHERE menu_group_id = :menu_group_id');
                    $sql->bindValue(':menu_group_id', $menu_group_id);
        
                    if($sql->execute()){
                        while($row = $sql->fetch()){
                            $menu_item_id = $row['menu_item_id'];
                            $menu_item_name = $row['menu_item_name'];
                            $parent_id = $row['parent_id'];
                            $order_sequence = $row['order_sequence'];

                            $parent_menu_item_details = $api->get_menu_item_details($parent_id);
                            $parent_menu_item_name = $parent_menu_item_details[0]['MENU_ITEM_NAME'] ?? null;

                            $menu_item_id_encrypted = $api->encrypt_data($menu_item_id); 

                            if($menu_item_write_access_right > 0 && $menu_group_write_access_right > 0){
                                $update = '<button type="button" class="btn btn-icon btn-info update-menu-item" data-menu-item-id="'. $menu_item_id .'" title="Edit Menu Item">
                                                <i class="ti ti-pencil"></i>
                                            </button>';
                            }
                            else{
                                $update = null;
                            }
    
                            if($menu_item_delete_access_right > 0 && $menu_group_write_access_right > 0){
                                $delete = '<button type="button" class="btn btn-icon btn-danger delete-menu-item" data-menu-item-id="'. $menu_item_id .'" title="Delete Menu Item">
                                                <i class="ti ti-trash"></i>
                                            </button>';
                            }
                            else{
                                $delete = null;
                            }
        
                            if($assign_menu_item_role_access > 0){
                                $assign = '<button type="button" class="btn btn-icon btn-warning assign-menu-item-role-access" data-menu-item-id="'. $menu_item_id .'" title="Assign Menu Item Role Access">
                                                <i class="ti ti-user-check"></i>
                                            </button>';
                            }
                            else{
                                $assign = null;
                            }
        
                            $response[] = array(
                                'MENU_ITEM_ID' => $menu_item_id,
                                'MENU_ITEM_NAME' => '<a href="menu-item-form.php?id='. $menu_item_id_encrypted .'">'. $menu_item_name . '</a>',
                                'PARENT_ID' => $parent_menu_item_name,
                                'ORDER_SEQUENCE' => $order_sequence,
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
            }
        break;

        # Menu item table
        case 'menu item table':
            if(isset($_POST['filter_menu_group_id']) && isset($_POST['filter_parent_id'])){
                if ($api->databaseConnection()) {
                    $filter = [];
                    $filter_menu_group_id = $_POST['filter_menu_group_id'];
                    $filter_parent_id = $_POST['filter_parent_id'];

                    $menu_group_write_access_right = $api->check_menu_access_rights($email_account, 2, 'write');
                    $menu_item_write_access_right = $api->check_menu_access_rights($email_account, 3, 'write');
                    $menu_item_delete_access_right = $api->check_menu_access_rights($email_account, 3, 'delete');
                    $assign_menu_item_role_access = $api->check_system_action_access_rights($email_account, 1);

                    $query = 'SELECT menu_item_id, menu_item_name, menu_group_id, parent_id, order_sequence FROM menu_item';

                    if(!empty($filter_menu_group_id)){
                        $filter[] = 'menu_group_id = :filter_menu_group_id';
                    }

                    if(!empty($filter_parent_id)){
                        $filter[] = 'parent_id = :filter_parent_id';
                    }

                    if(!empty($filter)) {
                        $query .= ' WHERE ' . implode(' AND ', $filter);
                    }
    
                    $sql = $api->db_connection->prepare($query);

                    if(!empty($filter_menu_group_id)){
                        $sql->bindValue(':filter_menu_group_id', $filter_menu_group_id);
                    }

                    if(!empty($filter_parent_id)){
                        $sql->bindValue(':filter_parent_id', $filter_parent_id);
                    }
        
                    if($sql->execute()){
                        while($row = $sql->fetch()){
                            $menu_item_id = $row['menu_item_id'];
                            $menu_item_name = $row['menu_item_name'];
                            $menu_group_id = $row['menu_group_id'];
                            $parent_id = $row['parent_id'];
                            $order_sequence = $row['order_sequence'];
        
                            $menu_item_id_encrypted = $api->encrypt_data($menu_item_id);
    
                            $menu_groups_details = $api->get_menu_groups_details($menu_group_id);
                            $menu_group_name = $menu_groups_details[0]['MENU_GROUP_NAME'] ?? null;
    
                            $parent_menu_item_details = $api->get_menu_item_details($parent_id);
                            $parent_menu_item_name = $parent_menu_item_details[0]['MENU_ITEM_NAME'] ?? null;
    
                            if($menu_item_delete_access_right > 0 && $menu_group_write_access_right > 0){
                                $delete = '<button type="button" class="btn btn-icon btn-danger delete-menu-item" data-menu-item-id="'. $menu_item_id .'" title="Delete Menu Item">
                                                <i class="ti ti-trash"></i>
                                            </button>';
                            }
                            else{
                                $delete = null;
                            }
        
                            if($assign_menu_item_role_access > 0){
                                $assign = '<button type="button" class="btn btn-icon btn-warning assign-menu-item-role-access" data-menu-item-id="'. $menu_item_id .'" title="Assign Menu Item Role Access">
                                                <i class="ti ti-user-check"></i>
                                            </button>';
                            }
                            else{
                                $assign = null;
                            }
        
                            $response[] = array(
                                'CHECK_BOX' => '<input class="form-check-input datatable-checkbox-children" data-delete="1" type="checkbox" value="'. $menu_item_id .'">',
                                'MENU_ITEM_ID' => $menu_item_id,
                                'MENU_ITEM_NAME' => $menu_item_name,
                                'MENU_GROUP_NAME' => $menu_group_name,
                                'PARENT_ID' => $parent_menu_item_name,
                                'ORDER_SEQUENCE' => $order_sequence,
                                'ACTION' => '<div class="d-flex gap-2">
                                                <a href="menu-item-form.php?id='. $menu_item_id_encrypted .'" class="btn btn-icon btn-primary" title="View Menu Item">
                                                    <i class="ti ti-eye"></i>
                                                </a>
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
            }
        break;

        # Assign menu item role access table
        case 'assign menu item role access table':
            if ($api->databaseConnection()) {
                if(isset($_POST['menu_item_id']) && !empty($_POST['menu_item_id'])){
                    $menu_item_id = htmlspecialchars($_POST['menu_item_id'], ENT_QUOTES, 'UTF-8');

                    $sql = $api->db_connection->prepare('SELECT role_id, role_name FROM role');
    
                    if($sql->execute()){
                        while($row = $sql->fetch()){
                            $role_id = $row['role_id'];
                            $role_name = $row['role_name'];

                            $role_menu_access_rights = $api->get_role_menu_access_rights($menu_item_id, $role_id);

                            $read_access = $role_menu_access_rights[0]['READ_ACCESS'] ?? 0;
                            $write_access = $role_menu_access_rights[0]['WRITE_ACCESS'] ?? 0;
                            $create_access = $role_menu_access_rights[0]['CREATE_ACCESS'] ?? 0;
                            $delete_access = $role_menu_access_rights[0]['DELETE_ACCESS'] ?? 0;

                            if($read_access == 1){
                                $read_checked = 'checked';
                            }
                            else{
                                $read_checked = null;
                            }

                            if($write_access == 1){
                                $write_checked = 'checked';
                            }
                            else{
                                $write_checked = null;
                            }

                            if($create_access == 1){
                                $create_checked = 'checked';
                            }
                            else{
                                $create_checked = null;
                            }

                            if($delete_access == 1){
                                $delete_checked = 'checked';
                            }
                            else{
                                $delete_checked = null;
                            }
        
                            $response[] = array(
                                'ROLE_NAME' => $role_name,
                                'READ_ACCESS' => '<div class="form-check form-switch mb-2"><input class="form-check-input role-access" type="checkbox" value="'. $role_id .'-read" '. $read_checked .'></div>',
                                'WRITE_ACCESS' => '<div class="form-check form-switch mb-2"><input class="form-check-input role-access" type="checkbox" value="'. $role_id .'-write" '. $write_checked .'></div>',
                                'CREATE_ACCESS' => '<div class="form-check form-switch mb-2"><input class="form-check-input role-access" type="checkbox" value="'. $role_id .'-create" '. $create_checked .'></div>',
                                'DELETE_ACCESS' => '<div class="form-check form-switch mb-2"><input class="form-check-input role-access" type="checkbox" value="'. $role_id .'-delete" '. $delete_checked .'></div>'
                            );
                        }
        
                        echo json_encode($response);
                    }
                    else{
                        echo $sql->errorInfo()[2];
                    }
                }
            }
        break;

        # Submenu item table
        case 'submenu item table':
            if(isset($_POST['menu_item_id']) && !empty($_POST['menu_item_id'])){
                if ($api->databaseConnection()) {
                    $menu_item_id = $_POST['menu_item_id'];

                    $sql = $api->db_connection->prepare('SELECT menu_item_id, menu_item_name FROM menu_item WHERE parent_id = :menu_item_id');
                    $sql->bindValue(':menu_item_id', $menu_item_id);
        
                    if($sql->execute()){
                        while($row = $sql->fetch()){
                            $menu_item_id = $row['menu_item_id'];
                            $menu_item_name = $row['menu_item_name'];

                            $menu_item_id_encrypted = $api->encrypt_data($menu_item_id);
        
                            $response[] = array(
                                'MENU_ITEM_ID' => $menu_item_id,
                                'MENU_ITEM_NAME' => '<a href="menu-item-form.php?id='. $menu_item_id_encrypted .'">'. $menu_item_name .'</a>',
                            );
                        }
        
                        echo json_encode($response);
                    }
                    else{
                        echo $sql->errorInfo()[2];
                    }
                }
            }
        break;

        # File types table
        case 'file types table':
            if ($api->databaseConnection()) {
                $sql = $api->db_connection->prepare('SELECT file_type_id, file_type_name FROM file_types');
    
                if($sql->execute()){                    
                    $menu_group_delete_access_right = $api->check_menu_access_rights($email_account, 2, 'delete');

                    while($row = $sql->fetch()){
                        $file_type_id = $row['file_type_id'];
                        $file_type_name = $row['file_type_name'];
    
                        $file_type_id_encrypted = $api->encrypt_data($file_type_id);

                        if($menu_group_delete_access_right > 0){
                            $delete = '<button type="button" class="btn btn-icon btn-danger delete-file-type" data-menu-group-id="' . $file_type_id . '" title="Delete File Type">
                                            <i class="ti ti-trash"></i>
                                        </button>';
                        }
                        else{
                            $delete = null;
                        }
    
                        $response[] = array(
                            'CHECK_BOX' => '<input class="form-check-input datatable-checkbox-children" data-delete="1" type="checkbox" value="'. $file_type_id .'">',
                            'FILE_TYPE_ID' => $file_type_id,
                            'FILE_TYPE_NAME' => $file_type_name,
                            'ACTION' => '<div class="d-flex gap-2">
                                            <a href="file-type-form.php?id='. $file_type_id_encrypted .'" class="btn btn-icon btn-primary" title="View File Type">
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
            }
        break;
    }
}

?>