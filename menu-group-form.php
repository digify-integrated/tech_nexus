<?php
require('session.php');
require('config/config.php');
require('classes/api.php');

$api = new Api;
$page_title = 'Menu Group Form';

$check_user_status = $api->check_user_status(null, $email);

if($check_user_status){    
  $menu_group_read_access_right = $api->check_menu_access_rights($email, 2, 'read');
      
  if($menu_group_read_access_right > 0){
    if(isset($_GET['id']) && !empty($_GET['id'])){
      $id = $_GET['id'];
      $menu_group_id = $api->decrypt_data($id);

      $check_menu_groups_exist = $api->check_menu_groups_exist($menu_group_id);
        
      if($check_menu_groups_exist === 0){
        header('location: 404.php');
      }
    }
    else{
      $menu_group_id = null;
    }

    $menu_group_create_access_right = $api->check_menu_access_rights($email, 2, 'create');
    $menu_group_write_access_right = $api->check_menu_access_rights($email, 2, 'write');
    $menu_group_delete_access_right = $api->check_menu_access_rights($email, 2, 'delete');
    $menu_item_create_access_right = $api->check_menu_access_rights($email, 3, 'create');
    $menu_item_write_access_right = $api->check_menu_access_rights($email, 3, 'write');
    $assign_menu_item_role_access = $api->check_system_action_access_rights($email, 1);
        
    require('views/_interface_settings.php');
    require('views/_user_account_details.php');
  }
  else{
    header('location: 404.php');
  }
}
else{
  header('location: logout.php?logout');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php include_once('views/_title.php'); ?>
    <link rel="stylesheet" href="./assets/css/plugins/select2.min.css">
    <?php include_once('views/_required_css.php'); ?>
    <link rel="stylesheet" href="./assets/css/plugins/dataTables.bootstrap5.min.css">
</head>

<body>
    <?php 
        include_once('views/_preloader.html'); 
        include_once('views/_navbar.php'); 
        include_once('views/_header.php'); 
        include_once('views/_announcement.php'); 
    ?>
    
    <section class="pc-container">
      <div class="pc-content">
        <div class="page-header">
          <div class="page-block">
            <div class="row align-items-center">
              <div class="col-md-12">
                <ul class="breadcrumb">
                  <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>
                  <li class="breadcrumb-item">User Interface</li>
                  <li class="breadcrumb-item"><a href="menu-groups.php">Menu Groups</a></li>
                  <li class="breadcrumb-item" aria-current="page">Menu Group Form</li>
                  <?php
                    if(!empty($menu_group_id)){
                      echo '<li class="breadcrumb-item" id="menu-group-id">'. $menu_group_id .'</li>';
                    }
                  ?>
                </ul>
              </div>
              <div class="col-md-12">
                <div class="page-header-title">
                  <h2 class="mb-0">Menu Group Form</h2>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-lg-12">
            <div class="card">
              <div id="sticky-action" class="sticky-action">
                <div class="card-header">
                  <div class="row align-items-center">
                    <div class="col-md-6">
                      <h5>Menu Groups Form</h5>
                    </div>
                    <div class="col-md-6 text-sm-end mt-3 mt-sm-0">
                      <?php
                        if (!empty($menu_group_id)) {
                            $dropdown = '<div class="btn-group m-r-5 ">
                                              <button type="button" class="btn btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                                Action
                                              </button>
                                              <ul class="dropdown-menu dropdown-menu-end">';
                                              
                          if ($menu_group_create_access_right > 0) {
                            $dropdown .= '<li><a class="dropdown-item" href="menu-group-form.php">Create Menu Group</a></li>
                            <li><button class="dropdown-item" type="button" data-menu-group-id="' . $menu_group_id . '" id="duplicate-menu-group">Duplicate Menu Group</button></li>';
                          }

                          if ($menu_group_delete_access_right > 0) {
                            $dropdown .= '<li><button class="dropdown-item" type="button" data-menu-group-id="' . $menu_group_id . '" id="delete-menu-group">Delete Menu Group</button></li>';
                          }

                          $dropdown .= '</ul>
                                        </div>';

                          echo $dropdown;
                        }
                            
                        if (empty($menu_group_id) && $menu_group_create_access_right > 0) {
                          echo '<button type="submit" form="menu-group-form" class="btn btn-success form-edit" id="submit-data">Save</button>
                                <button type="button" id="discard-create" class="btn btn-outline-danger form-edit">Discard</button>';
                        } 
                        else if (!empty($menu_group_id) && $menu_group_write_access_right > 0) {
                          echo '<button type="submit" class="btn btn-info form-details" id="edit-form">Edit</button>
                                <button type="submit" form="menu-group-form" class="btn btn-success form-edit d-none" id="submit-data">Save</button>
                                <button type="button" id="discard-update" class="btn btn-outline-danger form-edit d-none">Discard</button>';
                        }
                      ?>
                    </div>
                  </div>
                </div>
              </div>
              <div class="card-body">
                <?php echo $api->generate_form('menu groups form', $menu_group_id, $email); ?>
              </div>
            </div>
          </div>

          <?php
          if(!empty($menu_group_id)){
            if($menu_item_create_access_right > 0){
              $menu_item_create = '<button type="button" class="btn btn-success" id="create-menu-item">Create</button>';
            }

            echo '<div class="col-lg-12">
                    <div class="card">
                      <div id="sticky-action" class="sticky-action">
                        <div class="card-header">
                          <div class="row align-items-center">
                            <div class="col-sm-6">
                              <h5>Menu Item</h5>
                            </div>
                            <div class="col-sm-6 text-sm-end mt-3 mt-sm-0">
                              '. $menu_item_create .'
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="card-body">
                        <div class="dt-responsive table-responsive">
                          <table id="menu-item-table" class="table table-striped table-hover table-bordered nowrap w-100 dataTable">
                            <thead>
                              <tr>
                                <th>#</th>
                                <th>Menu Item</th>
                                <th>Parent Menu Item</th>
                                <th>Order Sequence</th>
                                <th>Action</th>
                              </tr>
                            </thead>
                            <tbody></tbody>
                          </table>
                        </div>
                      </div>
                    </div>
                  </div>';
          }

          if(!empty($menu_group_id)){
            echo $api->generate_log_notes('menu_groups', $menu_group_id);
          }

          if($menu_item_create_access_right > 0 || $menu_item_write_access_right > 0){
            echo $api->generate_modal('menu item form', 'menu-item-form', 'menu-item-modal', 'Menu Item');
          }

          if($assign_menu_item_role_access > 0){
            echo $api->generate_modal('assign menu item role access form', 'assign-menu-item-role-access-form', 'assign-menu-item-role-access-modal', 'Assign Menu Item Role Access', 'LG');
          }
        ?>
        </div>
      </div>
    </section>

    <?php 
        include_once('views/_footer.php'); 
        include_once('views/_required_js.php'); 
        include_once('views/_customizer.php'); 
    ?>
    <script src="./assets/js/plugins/bootstrap-maxlength.min.js"></script>
    <script src="./assets/js/plugins/jquery.dataTables.min.js"></script>
    <script src="./assets/js/plugins/dataTables.bootstrap5.min.js"></script>
    <script src="./assets/js/plugins/sweetalert2.all.min.js"></script>
    <script src="./assets/js/plugins/select2.min.js?v=<?php echo rand(); ?>"></script>
    <script src="./assets/js/pages/menu-group-form.js?v=<?php echo rand(); ?>"></script>
</body>

</html>