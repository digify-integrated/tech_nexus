<?php
require('session.php');
require('config/config.php');
require('classes/api.php');

$api = new Api;
$page_title = 'Menu Item Form';

$check_user_status = $api->check_user_status(null, $email);

if($check_user_status){    
  $menu_item_read_access_right = $api->check_menu_access_rights($email, 3, 'read');
      
  if($menu_item_read_access_right > 0){
    if(isset($_GET['id']) && !empty($_GET['id'])){
      $id = $_GET['id'];
      $menu_item_id = $api->decrypt_data($id);

      $check_menu_item_exist = $api->check_menu_item_exist($menu_item_id);
        
      if($check_menu_item_exist === 0){
        header('location: 404.php');
      }
    }
    else{
      $menu_item_id = null;
    }

    $menu_item_create_access_right = $api->check_menu_access_rights($email, 3, 'create');
    $menu_item_write_access_right = $api->check_menu_access_rights($email, 3, 'write');
    $menu_item_delete_access_right = $api->check_menu_access_rights($email, 3, 'delete');
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
                  <li class="breadcrumb-item"><a href="menu-items.php">Menu Item</a></li>
                  <li class="breadcrumb-item" aria-current="page">Menu Item Form</li>
                  <?php
                    if(!empty($menu_item_id)){
                      echo '<li class="breadcrumb-item" id="menu-item-id">'. $menu_item_id .'</li>';
                    }
                  ?>
                </ul>
              </div>
              <div class="col-md-12">
                <div class="page-header-title">
                  <h2 class="mb-0">Menu Item Form</h2>
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
                      <h5>Menu Item Form</h5>
                    </div>
                    <div class="col-md-6 text-sm-end mt-3 mt-sm-0">
                      <?php
                        if (!empty($menu_item_id)) {
                            $dropdown = '<div class="btn-group m-r-5 ">
                                              <button type="button" class="btn btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                                Action
                                              </button>
                                              <ul class="dropdown-menu dropdown-menu-end">';
                                              
                          if ($menu_item_create_access_right > 0) {
                            $dropdown .= '<li><a class="dropdown-item" href="menu-item-form.php">Create Menu Item</a></li>
                            <li><button class="dropdown-item" type="button" data-menu-item-id="' . $menu_item_id . '" id="duplicate-menu-item">Duplicate Menu Item</button></li>';
                          }

                          if ($assign_menu_item_role_access > 0) {
                            $dropdown .= '<li><button class="dropdown-item" type="button" data-menu-item-id="' . $menu_item_id . '" id="assign-menu-item-role-access">Assign Menu Item Role Access</button></li>';
                          }

                          if ($menu_item_delete_access_right > 0) {
                            $dropdown .= '<li><button class="dropdown-item" type="button" data-menu-item-id="' . $menu_item_id . '" id="delete-menu-item">Delete Menu Item</button></li>';
                          }

                          $dropdown .= '</ul>
                                        </div>';

                          echo $dropdown;
                        }
                            
                        if (empty($menu_item_id) && $menu_item_create_access_right > 0) {
                          echo '<button type="submit" form="menu-item-form" class="btn btn-success form-edit" id="submit-data">Save</button>
                                <button type="button" id="discard-create" class="btn btn-outline-danger form-edit">Discard</button>';
                        } 
                        else if (!empty($menu_item_id) && $menu_item_write_access_right > 0) {
                          echo '<button type="submit" class="btn btn-info form-details" id="edit-form">Edit</button>
                                <button type="submit" form="menu-item-form" class="btn btn-success form-edit d-none" id="submit-data">Save</button>
                                <button type="button" id="discard-update" class="btn btn-outline-danger form-edit d-none">Discard</button>';
                        }
                      ?>
                    </div>
                  </div>
                </div>
              </div>
              <div class="card-body">
                <?php echo $api->generate_form('menu item form', $menu_item_id, $email); ?>
              </div>
            </div>
          </div>
            <?php
                if(!empty($menu_item_id)){  
                  echo '<div class="col-lg-12">
                          <div class="card">
                            <div id="sticky-action" class="sticky-action">
                              <div class="card-header">
                                <div class="row align-items-center">
                                  <div class="col-sm-12">
                                    <h5>Submenu Item</h5>
                                  </div>
                                </div>
                              </div>
                            </div>
                            <div class="card-body">
                              <div class="dt-responsive table-responsive">
                                <table id="submenu-item-table" class="table table-striped table-hover table-bordered nowrap w-100 dataTable">
                                  <thead>
                                    <tr>
                                      <th>#</th>
                                      <th>Menu Item</th>
                                    </tr>
                                  </thead>
                                  <tbody></tbody>
                                </table>
                              </div>
                            </div>
                          </div>
                        </div>';
                }

                if($assign_menu_item_role_access > 0){
                  echo $api->generate_modal('assign menu item role access form', 'assign-menu-item-role-access-form', 'assign-menu-item-role-access-modal', 'Assign Menu Item Role Access', 'LG');
                }

                if(!empty($menu_item_id)){
                    echo $api->generate_log_notes('menu_item', $menu_item_id);
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
    <script src="./assets/js/pages/menu-item-form.js?v=<?php echo rand(); ?>"></script>
</body>

</html>