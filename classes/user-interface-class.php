<?php

# -------------------------------------------------------------
#
# Name       : user-interface-class
# Purpose    : This is to handle all of the generation on the user interface.
# Installer  : Default
#
# -------------------------------------------------------------

class User_Interface_Class {
    private $global;
    private $administrator;

    public function __construct() {
        $this->global = new Global_Class();
        $this->administrator = new Administrator_Class();
    }

    # -------------------------------------------------------------
    #   Check methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : check_modal_scrollable
    # Purpose    : Check if the modal to be generated
    #              is scrollable or not.
    #
    # Returns    : String
    #
    # -------------------------------------------------------------
    public function check_modal_scrollable($scrollable){
        return $scrollable ? 'modal-dialog-scrollable' : null;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Get details methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : get_menu_groups_details
    # Purpose    : Gets the menu groups details.
    #
    # Returns    : Array
    #
    # -------------------------------------------------------------
    public function get_menu_groups_details($p_external_id){
        if (!$this->global->database_connection()) {
            return 'Database Connection Error';
        }

        $response = [];

        $sql = $this->global->db_connection->prepare('CALL get_menu_groups_details(:p_external_id)');
        $sql->bindValue(':p_external_id', $p_external_id);

        if($sql->execute()){
            while($row = $sql->fetch()){
                $response[] = array(
                    'MENU_GROUP_NAME' => $row['menu_group_name'],
                    'ORDER_SEQUENCE' => $row['order_sequence'],
                    'LAST_LOG_BY' => $row['last_log_by']
                );
            }

            return $response;
        }
        else{
            return $sql->errorInfo()[2];
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : get_menu_item_details
    # Purpose    : Gets the menu item details.
    #
    # Returns    : Array
    #
    # -------------------------------------------------------------
    public function get_menu_item_details($p_external_id){
        if (!$this->global->database_connection()) {
            return 'Database Connection Error';
        }

        $response = [];

        $sql = $this->global->db_connection->prepare('CALL get_menu_item_details(:p_external_id)');
        $sql->bindValue(':p_external_id', $p_external_id);

        if($sql->execute()){
            while($row = $sql->fetch()){
                $response[] = array(
                    'MENU_ITEM_NAME' => $row['menu_item_name'],
                    'MENU_GROUP_ID' => $row['menu_group_id'],
                    'MENU_ITEM_URL' => $row['menu_item_url'],
                    'PARENT_ID' => $row['parent_id'],
                    'MENU_ITEM_ICON' => $row['menu_item_icon'],
                    'ORDER_SEQUENCE' => $row['order_sequence'],
                    'LAST_LOG_BY' => $row['last_log_by']
                );
            }

            return $response;
        }
        else{
            return $sql->errorInfo()[2];
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Get methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : get_role_menu_access_rights
    # Purpose    : Gets the role details.
    #
    # Returns    : Array
    #
    # -------------------------------------------------------------
    public function get_role_menu_access_rights($p_menu_item_id, $p_role_id){
        if (!$this->global->database_connection()) {
            return 'Database Connection Error';
        }

        $response = [];

        $sql = $this->global->db_connection->prepare('CALL get_role_menu_access_rights(:p_menu_item_id, :p_role_id)');
        $sql->bindValue(':p_menu_item_id', $p_menu_item_id);
        $sql->bindValue(':p_role_id', $p_role_id);

        if($sql->execute()){
            while($row = $sql->fetch()){
                $response[] = array(
                    'READ_ACCESS' => $row['read_access'],
                    'WRITE_ACCESS' => $row['write_access'],
                    'CREATE_ACCESS' => $row['create_access'],
                    'DELETE_ACCESS' => $row['delete_access']
                );
            }

            return $response;
        }
        else{
            return $sql->errorInfo()[2];
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #   Generate methods
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : generate_log_notes
    # Purpose    : generates log notes block.
    #
    # Returns    : String
    #
    # -------------------------------------------------------------
    public function generate_log_notes($p_table_name, $p_reference_id) {
        if (!$this->global->database_connection()) {
            return 'Database Connection Error';
        }

        $sql = $this->global->db_connection->prepare('SELECT log, changed_by, changed_at FROM audit_log WHERE table_name = :p_table_name AND reference_id = :p_reference_id ORDER BY changed_at DESC');
        $sql->bindValue(':p_table_name', $p_table_name);
        $sql->bindValue(':p_reference_id', $p_reference_id);

        if($sql->execute()){
            $count = $sql->rowCount();

            if($count > 0){
                $log_notes = '<div class="col-lg-12">
                                    <div class="card">
                                        <div id="sticky-action" class="sticky-action">
                                            <div class="card-header">
                                                <div class="row align-items-center">
                                                    <div class="col-sm-6">
                                                        <h5>Log Notes</h5>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="log-notes-scroll" style="height: 415px; position: relative;">
                                            <div class="card-body p-b-0">
                                                <div class="comment-block">';

                while($row = $sql->fetch()){
                    $log = $row['log'];
                    $changed_by = $row['changed_by'];
                    $time_elapsed = $this->global->time_elapsed_string($row['changed_at']);

                    $user_details = $this->administrator->get_user_details($changed_by, null);
                    $file_as = $user_details[0]['FILE_AS'] ?? null;

                    $log_notes .= '<div class="comment">
                                        <div class="media align-items-start">
                                            <div class="chat-avtar flex-shrink-0">
                                                <img class="rounded-circle img-fluid wid-40" src="./assets/images/default/default-avatar.png" alt="User image" />
                                            </div>
                                            <div class="media-body ms-3">
                                                <h5 class="mb-0">'. $file_as .'</h5>
                                                <span class="text-sm text-muted">'. $time_elapsed .'</span>
                                            </div>
                                        </div>
                                        <div class="comment-content">
                                            <p class="mb-0">
                                                '. $log .'
                                            </p>
                                        </div>
                                    </div>';

                }

                $log_notes .= '     </div>
                                </div>
                            </div>';
            }
            else{
                $log_notes = null;
            }
               
            return $log_notes;
        }
        else{
            return $sql->errorInfo()[2];
        }
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : generate_form
    # Purpose    : generates form based on type.
    #
    # Returns    : String
    #
    # -------------------------------------------------------------
    public function generate_form($form_type, $reference_id, $email) {
        $form = '';

        switch ($form_type){
            case 'menu groups form':
                $menu_group_create_access_right = $this->administrator->check_menu_access_rights($email, 2, 'create');
                $menu_group_write_access_right = $this->administrator->check_menu_access_rights($email, 2, 'write');

                if(empty($reference_id) && $menu_group_create_access_right > 0){
                    $form_fields = '<div class="form-group row">
                                        <label class="col-lg-2 col-form-label">Menu Group Name <span class="text-danger">*</span></label>
                                        <div class="col-lg-4">
                                            <input type="text" class="form-control" id="menu_group_name" name="menu_group_name" maxlength="100" autocomplete="off">
                                        </div>
                                        <label class="col-lg-2 col-form-label">Order Sequence <span class="text-danger">*</span></label>
                                        <div class="col-lg-4">
                                            <input type="number" class="form-control" id="menu_group_order_sequence" name="menu_group_order_sequence" min="0">
                                        </div>
                                    </div>';
                }
                else if(!empty($reference_id) && $menu_group_write_access_right > 0){
                    $form_fields = '<div class="form-group row">
                                        <label class="col-lg-2 col-form-label">Menu Group Name <span class="text-danger d-none form-edit">*</span></label>
                                        <div class="col-lg-4">
                                            <label class="col-form-label form-details fw-normal" id="menu_group_name_label"></label>
                                            <input type="text" class="form-control d-none form-edit" id="menu_group_name" name="menu_group_name" maxlength="100" autocomplete="off">
                                        </div>
                                        <label class="col-lg-2 col-form-label">Order Sequence <span class="text-danger d-none form-edit">*</span></label>
                                        <div class="col-lg-4">
                                            <label class="col-form-label form-details fw-normal" id="order_sequence_label"></label>
                                            <input type="number" class="form-control d-none form-edit" id="menu_group_order_sequence" name="menu_group_order_sequence" min="0">
                                        </div>
                                    </div>';
                }
                else{
                    $form_fields = '<div class="form-group row">
                                        <label class="col-lg-2 col-form-label">Menu Group Name</label>
                                        <div class="col-lg-4">
                                            <label class="col-form-label form-details fw-normal" id="menu_group_name_label"></label>
                                        </div>
                                        <label class="col-lg-2 col-form-label">Order Sequence</label>
                                        <div class="col-lg-4">
                                            <label class="col-form-label form-details fw-normal" id="order_sequence_label"></label>
                                        </div>
                                    </div>';
                }
                
                $form .= '<form id="menu-group-form" method="post" action="#">
                            <input type="hidden" id="menu_group_id" name="menu_group_id" value="'. $reference_id .'">
                            '. $form_fields .'
                        </form>';
            break;
            case 'menu item form':
                $menu_item_create_access_right = $this->administrator->check_menu_access_rights($email, 3, 'create');
                $menu_item_write_access_right = $this->administrator->check_menu_access_rights($email, 3, 'write');

                if(empty($reference_id) && $menu_item_create_access_right > 0){
                    $form_fields = '<div class="form-group row">
                                        <label class="col-lg-2 col-form-label">Menu Item Name <span class="text-danger">*</span></label>
                                        <div class="col-lg-4">
                                            <input type="text" class="form-control" id="menu_item_name" name="menu_item_name" maxlength="100" autocomplete="off">
                                        </div>
                                        <label class="col-lg-2 col-form-label">Order Sequence <span class="text-danger">*</span></label>
                                        <div class="col-lg-4">
                                            <input type="number" class="form-control" id="menu_item_order_sequence" name="menu_item_order_sequence" min="0">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-lg-2 col-form-label">Menu Group <span class="text-danger">*</span></label>
                                        <div class="col-lg-4">
                                            <select class="form-control select2" name="menu_group_id" id="menu_group_id">
                                                <option value="">--</option>
                                                '. $this->administrator->generate_menu_group_options() .'
                                            </select>
                                        </div>
                                        <label class="col-lg-2 col-form-label">URL</label>
                                        <div class="col-lg-4">
                                            <input type="text" class="form-control" id="menu_item_url" name="menu_item_url" maxlength="50" autocomplete="off">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-lg-2 col-form-label">Menu Item Icon</label>
                                        <div class="col-lg-4">
                                            <input type="text" class="form-control" id="menu_item_icon" name="menu_item_icon" maxlength="150" autocomplete="off">
                                        </div>
                                        <label class="col-lg-2 col-form-label">Parent Menu Item</label>
                                        <div class="col-lg-4">
                                            <select class="form-control select2" name="parent_id" id="parent_id">
                                                <option value="">--</option>
                                                '. $this->generate_menu_item_options() .'
                                            </select>
                                        </div>
                                    </div>';
                }
                else if(!empty($reference_id) && $menu_item_write_access_right > 0){
                    $form_fields = '<div class="form-group row">
                                        <label class="col-lg-2 col-form-label">Menu Item Name <span class="text-danger d-none form-edit">*</span></label>
                                        <div class="col-lg-4">
                                            <label class="col-form-label form-details fw-normal" id="menu_item_name_label"></label>
                                            <input type="text" class="form-control d-none form-edit" id="menu_item_name" name="menu_item_name" maxlength="100" autocomplete="off">
                                        </div>
                                        <label class="col-lg-2 col-form-label">Order Sequence <span class="text-danger d-none form-edit">*</span></label>
                                        <div class="col-lg-4">
                                            <label class="col-form-label form-details fw-normal" id="order_sequence_label"></label>
                                            <input type="number" class="form-control d-none form-edit" id="menu_item_order_sequence" name="menu_item_order_sequence" min="0">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-lg-2 col-form-label">Menu Group <span class="text-danger d-none form-edit">*</span></label>
                                        <div class="col-lg-4">
                                            <div class="col-form-label form-details fw-normal" id="menu_group_id_label"></div>
                                            <div class="d-none form-edit">
                                                <select class="form-control select2" name="menu_group_id" id="menu_group_id">
                                                    <option value="">--</option>
                                                    '. $this->administrator->generate_menu_group_options() .'
                                                </select>
                                            </div>
                                        </div>
                                        <label class="col-lg-2 col-form-label">URL</label>
                                        <div class="col-lg-4">
                                            <div class="col-form-label form-details fw-normal" id="menu_item_url_label"></div>
                                            <input type="text" class="form-control d-none form-edit" id="menu_item_url" name="menu_item_url" maxlength="50" autocomplete="off">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-lg-2 col-form-label">Menu Item Icon</label>
                                        <div class="col-lg-4">
                                            <label class="col-form-label form-details fw-normal" id="menu_item_icon_label"></label>
                                            <input type="text" class="form-control d-none form-edit" id="menu_item_icon" name="menu_item_icon" maxlength="150" autocomplete="off">
                                        </div>
                                        <label class="col-lg-2 col-form-label">Parent Menu Item</label>
                                        <div class="col-lg-4">
                                            <label class="col-form-label form-details fw-normal" id="parent_id_label"></label>
                                            <div class="d-none form-edit">
                                                <select class="form-control select2 d-none form-edit" name="parent_id" id="parent_id">
                                                    <option value="">--</option>
                                                    '. $this->administrator->generate_menu_item_options() .'
                                                </select>
                                            </div>
                                        </div>
                                    </div>';
                }
                else{
                    $form_fields = '<div class="form-group row">
                                        <label class="col-lg-2 col-form-label">Menu Item Name</label>
                                        <div class="col-lg-4">
                                            <label class="col-form-label form-details fw-normal" id="menu_item_label"></label>
                                        </div>
                                        <label class="col-lg-2 col-form-label">Order Sequence</label>
                                        <div class="col-lg-4">
                                            <label class="col-form-label form-details fw-normal" id="order_sequence_label"></label>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-lg-2 col-form-label">Menu Group</label>
                                        <div class="col-lg-4">
                                            <div class="col-form-label form-details fw-normal" id="menu_group_id_label"></div>
                                        </div>
                                        <label class="col-lg-2 col-form-label">URL</label>
                                        <div class="col-lg-4">
                                            <div class="col-form-label form-details fw-normal" id="menu_item_url_label"></div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-lg-2 col-form-label">Menu Item Icon</label>
                                        <div class="col-lg-4">
                                            <label class="col-form-label form-details fw-normal" id="menu_item_icon_label"></label>
                                        </div>
                                        <label class="col-lg-2 col-form-label">Parent Menu Item</label>
                                        <div class="col-lg-4">
                                            <label class="col-form-label form-details fw-normal" id="parent_id_label"></label>
                                        </div>
                                    </div>';
                }
                
                $form .= '<form id="menu-item-form" method="post" action="#">
                            <input type="hidden" id="menu_item_id" name="menu_item_id" value="'. $reference_id .'">
                            '. $form_fields .'
                        </form>';
            break;
            case 'file type form':
                $file_type_create_access_right = $this->administrator->check_menu_access_rights($email, 6, 'create');
                $file_type_write_access_right = $this->administrator->check_menu_access_rights($email, 6, 'write');

                if(empty($reference_id) && $file_type_create_access_right > 0){
                    $form_fields = '<div class="form-group row">
                                        <label class="col-lg-2 col-form-label">File Type Name <span class="text-danger">*</span></label>
                                        <div class="col-lg-10">
                                            <input type="text" class="form-control" id="file_type_name" name="file_type_name" maxlength="100" autocomplete="off">
                                        </div>
                                    </div>';
                }
                else if(!empty($reference_id) && $file_type_write_access_right > 0){
                    $form_fields = '<div class="form-group row">
                                        <label class="col-lg-2 col-form-label">File Type Name <span class="text-danger d-none form-edit">*</span></label>
                                        <div class="col-lg-10">
                                            <label class="col-form-label form-details fw-normal" id="file_type_name_label"></label>
                                            <input type="text" class="form-control d-none form-edit" id="file_type_name" name="file_type_name" maxlength="100" autocomplete="off">
                                        </div>
                                    </div>';
                }
                else{
                    $form_fields = '<div class="form-group row">
                                        <label class="col-lg-2 col-form-label">File Type Name</label>
                                        <div class="col-lg-10">
                                            <label class="col-form-label form-details fw-normal" id="file_type_name_label"></label>
                                        </div>
                                    </div>';
                }
                
                $form .= '<form id="file-type-form" method="post" action="#">
                            <input type="hidden" id="file_type_id" name="file_type_id" value="'. $reference_id .'">
                            '. $form_fields .'
                        </form>';
            break;
            case 'file extension form':
                $file_extension_create_access_right = $this->administrator->check_menu_access_rights($email, 7, 'create');
                $file_extension_write_access_right = $this->administrator->check_menu_access_rights($email, 7, 'write');

                if(empty($reference_id) && $file_extension_create_access_right > 0){
                    $form_fields = '<div class="form-group row">
                                        <label class="col-lg-2 col-form-label">File Extension Name <span class="text-danger">*</span></label>
                                        <div class="col-lg-4">
                                            <input type="text" class="form-control" id="file_extension_name" name="file_extension_name" maxlength="100" autocomplete="off">
                                        </div>
                                        <label class="col-lg-2 col-form-label">File Type <span class="text-danger">*</span></label>
                                        <div class="col-lg-4">
                                            <select class="form-control select2" name="file_type_id" id="file_type_id">
                                                <option value="">--</option>
                                                '. $this->administrator->generate_file_type_options() .'
                                            </select>
                                        </div>
                                    </div>';
                }
                else if(!empty($reference_id) && $file_extension_write_access_right > 0){
                    $form_fields = '<div class="form-group row">
                                        <label class="col-lg-2 col-form-label">File Extension Name <span class="text-danger d-none form-edit">*</span></label>
                                        <div class="col-lg-4">
                                            <label class="col-form-label form-details fw-normal" id="file_extension_name_label"></label>
                                            <input type="text" class="form-control d-none form-edit" id="file_extension_name" name="file_extension_name" maxlength="100" autocomplete="off">
                                        </div>
                                        <label class="col-lg-2 col-form-label">File Type <span class="text-danger d-none form-edit">*</span></label>
                                        <div class="col-lg-4">
                                            <div class="col-form-label form-details fw-normal" id="file_type_id_label"></div>
                                            <div class="d-none form-edit">
                                                <select class="form-control select2" name="file_type_id" id="file_type_id">
                                                    <option value="">--</option>
                                                    '. $this->administrator->generate_file_type_options() .'
                                                </select>
                                            </div>
                                        </div>
                                    </div>';
                }
                else{
                    $form_fields = '<div class="form-group row">
                                        <label class="col-lg-2 col-form-label">File Extension Name</label>
                                        <div class="col-lg-4">
                                            <label class="col-form-label form-details fw-normal" id="file_extension_name_label"></label>
                                        </div>
                                        <label class="col-lg-2 col-form-label">File Type</label>
                                        <div class="col-lg-4">
                                            <div class="col-form-label form-details fw-normal" id="file_type_id_label"></div>
                                        </div>
                                    </div>';
                }
                
                $form .= '<form id="file-extension-form" method="post" action="#">
                            <input type="hidden" id="file_extension_id" name="file_extension_id" value="'. $reference_id .'">
                            '. $form_fields .'
                        </form>';
            break;
            case 'upload settings form':
                $upload_setting_create_access_right = $this->administrator->check_menu_access_rights($email, 5, 'create');
                $upload_setting_write_access_right = $this->administrator->check_menu_access_rights($email, 5, 'write');

                if(empty($reference_id) && $upload_setting_create_access_right > 0){
                    $form_fields = '<div class="form-group row">
                                        <label class="col-lg-2 col-form-label">Upload Setting Name <span class="text-danger">*</span></label>
                                        <div class="col-lg-4">
                                            <input type="text" class="form-control" id="upload_setting_name" name="upload_setting_name" maxlength="100" autocomplete="off">
                                        </div>
                                        <label class="col-lg-2 col-form-label">Max Upload Size <span class="text-danger">*</span></label>
                                        <div class="col-lg-4">
                                            <div class="col-lg-4 input-group">
                                                <input type="number" class="form-control" id="max_upload_size" name="max_upload_size" min="0">
                                                <span class="input-group-text">mb</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-lg-2 col-form-label">Upload Setting Description <span class="text-danger">*</span></label>
                                        <div class="col-lg-4">
                                            <input type="text" class="form-control" id="upload_setting_description" name="upload_setting_description" maxlength="200" autocomplete="off">
                                        </div>
                                    </div>';
                }
                else if(!empty($reference_id) && $upload_setting_write_access_right > 0){
                    $form_fields = '<div class="form-group row">
                                        <label class="col-lg-2 col-form-label">Upload Setting Name <span class="text-danger d-none form-edit">*</span></label>
                                        <div class="col-lg-4">
                                            <label class="col-form-label form-details fw-normal" id="upload_setting_name_label"></label>
                                            <input type="text" class="form-control d-none form-edit" id="upload_setting_name" name="upload_setting_name" maxlength="100" autocomplete="off">
                                        </div>
                                        <label class="col-lg-2 col-form-label">Max Upload Size <span class="text-danger d-none form-edit">*</span></label>
                                        <div class="col-lg-4">
                                            <label class="col-form-label form-details fw-normal" id="max_upload_size_label"></label>
                                            <div class="col-lg-4 input-group d-none form-edit">
                                                <input type="number" class="form-control" id="max_upload_size" name="max_upload_size" min="0">
                                                <span class="input-group-text">mb</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-lg-2 col-form-label">Upload Setting Description</label>
                                        <div class="col-lg-4">
                                            <div class="col-form-label form-details fw-normal" id="upload_setting_description_label"></div>
                                            <input type="text" class="form-control d-none form-edit" id="upload_setting_description" name="upload_setting_description" maxlength="200" autocomplete="off">
                                        </div>
                                    </div>';
                }
                else{
                    $form_fields = '<div class="form-group row">
                                        <label class="col-lg-2 col-form-label">Upload Setting Name</label>
                                        <div class="col-lg-4">
                                            <label class="col-form-label form-details fw-normal" id="upload_setting_name_label"></label>
                                        </div>
                                        <label class="col-lg-2 col-form-label">Max Upload Size</label>
                                        <div class="col-lg-4">
                                            <label class="col-form-label form-details fw-normal" id="max_upload_size_label"></label>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-lg-2 col-form-label">Upload Setting Description</label>
                                        <div class="col-lg-4">
                                            <div class="col-form-label form-details fw-normal" id="upload_setting_description_label"></div>
                                        </div>
                                    </div>';
                }
                
                $form .= '<form id="upload-setting-form" method="post" action="#">
                            <input type="hidden" id="upload_setting_id" name="upload_setting_id" value="'. $reference_id .'">
                            '. $form_fields .'
                        </form>';
            break;
        }

        return $form;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : generate_modal
    # Purpose    : generates modal based on generation type.
    #
    # Returns    : String
    #
    # -------------------------------------------------------------
    public function generate_modal($generation_type, $form_id, $modal_id, $modal_title, $modal_size = 'R', $is_scrollable = true, $has_submit_button = true){
        $modal_size = $this->get_modal_size($modal_size);
        $is_scrollable = $this->check_modal_scrollable($is_scrollable);
        $modal_content = $this->generate_modal_content($generation_type, $form_id);

        if($has_submit_button == 1){
            $button = '<button type="submit" class="btn btn-primary" id="submit-form" form="'. $form_id .'">Submit</button>';
        }
        else{
            $button = '';
        }

        $modal = '<div id="'. $modal_id .'" class="modal fade modal-animate anim-fade-in-scale" tabindex="-1" role="dialog" aria-labelledby="modal-'. $modal_id .'" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered '. $is_scrollable .' '. $modal_size .'" role="document">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title" id="modal-'. $modal_id .'-title">'. $modal_title .'</h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body" id="modal-body">'. $modal_content .'</div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                      '. $button .'
                    </div>
                  </div>
                </div>
              </div>';

        return $modal;
    }
    # -------------------------------------------------------------

    # -------------------------------------------------------------
    #
    # Name       : generate_modal_content
    # Purpose    : generates modal content based on generation type.
    #
    # Returns    : String
    #
    # -------------------------------------------------------------
    public function generate_modal_content($generation_type, $form_id) {
        $form = '';

        switch ($generation_type){
            case 'menu item form':
                $form .= '<form id="'. $form_id .'" method="post" action="#">
                            <div class="form-group">
                                <label class="form-label" for="menu_item_name">Menu Item Name <span class="text-danger">*</span></label>
                                <input type="hidden" id="menu_item_id" name="menu_item_id">
                                <input type="text" class="form-control" id="menu_item_name" name="menu_item_name" maxlength="100" autocomplete="off">
                            </div>
                            <div class="form-group">
                                <label class="form-label" for="menu_item_order_sequence">Order Sequence <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" id="menu_item_order_sequence" name="menu_item_order_sequence" min="0">
                            </div>
                            <div class="form-group">
                                <label class="form-label" for="menu_item_url">URL</label>
                                <input type="text" class="form-control" id="menu_item_url" name="menu_item_url" maxlength="50" autocomplete="off">
                            </div>
                            <div class="form-group">
                                <label class="form-label" for="menu_item_icon">Menu Item Icon</label>
                                <input type="text" class="form-control" id="menu_item_icon" name="menu_item_icon" maxlength="150" autocomplete="off">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Parent Menu Item</label>
                                <select class="form-control modal-select2" name="parent_id" id="parent_id">
                                    <option value="">--</option>
                                    '. $this->generate_menu_item_options() .'
                                </select>
                            </div>
                        </form>';
            break;
            case 'assign menu item role access form':
                $form .= '<form id="'. $form_id .'" method="post" action="#">
                            <div class="row">
                                <div class="col-md-12">
                                    <table id="assign-menu-item-role-access-table" class="table table-striped table-hover table-bordered nowrap w-100 dataTable">
                                        <thead>
                                        <tr>
                                            <th class="all">Role</th>
                                            <th class="all">Read</th>
                                            <th class="all">Write</th>
                                            <th class="all">Create</th>
                                            <th class="all">Delete</th>
                                        </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                            </div>
                        </form>';
            break;
            case 'file extension form':
                $form .= '<form id="'. $form_id .'" method="post" action="#">
                            <div class="form-group">
                                <label class="form-label" for="file_extension_name">File Extension Name <span class="text-danger">*</span></label>
                                <input type="hidden" id="file_extension_id" name="file_extension_id">
                                <input type="text" class="form-control" id="file_extension_name" name="file_extension_name" maxlength="100" autocomplete="off">
                            </div>
                        </form>';
            break;
        }

        return $form;
    }
    # -------------------------------------------------------------
}

?>