<?php
require('session.php');
require('config/config.php');
require('classes/api.php');

$api = new Api;
$page_title = 'Menu Items';

$check_user_status = $api->check_user_status(null, $email);
    
if($check_user_status){    
  $menu_read_access_right = $api->check_menu_access_rights($email, 2, 'read');
          
  if($menu_read_access_right > 0){            
    require('views/_interface_settings.php');
    require('views/_user_account_details.php');

    $menu_item_create_access_right = $api->check_menu_access_rights($email, 3, 'create');
    $menu_item_delete_access_right = $api->check_menu_access_rights($email, 3, 'delete');
    $assign_menu_item_role_access = $api->check_system_action_access_rights($email, 1);
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
                  <li class="breadcrumb-item"><a href="javascript: void(0)">User Interface</a></li>
                  <li class="breadcrumb-item" aria-current="page">Menu Items</li>
                </ul>
              </div>
              <div class="col-md-12">
                <div class="page-header-title">
                  <h2 class="mb-0">Menu Items</h2>
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
                    <div class="col-sm-6">
                      <h5>Menu Items List</h5>
                    </div>
                    <?php
                      if($menu_item_create_access_right > 0 || $menu_item_delete_access_right > 0){
                        $action = ' <div class="col-sm-6 text-sm-end mt-3 mt-sm-0">
                                  <button class="btn btn-warning" type="button" data-bs-toggle="offcanvas" data-bs-target="#filter-canvas" aria-controls="offcanvasRight">Filter</button>';
                        
                          if($menu_item_delete_access_right > 0){
                            $action .= '<div class="btn-group m-r-10">
                                          <button type="button" class="btn btn-outline-secondary dropdown-toggle d-none action-dropdown" data-bs-toggle="dropdown" aria-expanded="false">Action</button>
                                          <ul class="dropdown-menu dropdown-menu-end">
                                            <li><button class="dropdown-item" type="button" id="delete-menu-item">Delete Menu Item</button></li>
                                          </ul>
                                          </div>';
                          }

                          if($menu_item_create_access_right > 0){
                            $action .= '<a href="menu-item-form.php" class="btn btn-success">Create</a>';
                          }

                        $action .= '</div>';
                          
                        echo $action;
                      }
                    ?>
                  </div>
                </div>
              </div>
              <div class="card-body">
                <div class="table-responsive dt-responsive">
                  <table id="menu-items-table" class="table table-striped table-hover table-bordered nowrap w-100">
                    <thead>
                      <tr>
                        <th class="all">
                          <div class="form-check">
                            <input class="form-check-input" id="datatable-checkbox" type="checkbox">
                          </div>
                        </th>
                        <th>#</th>
                        <th>Menu Item</th>
                        <th>Menu Group</th>
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
          </div>
          <?php
            if($assign_menu_item_role_access > 0){
              echo $api->generate_modal('assign menu item role access form', 'assign-menu-item-role-access-form', 'assign-menu-item-role-access-modal', 'Assign Menu Item Role Access', 'LG');
            }
          ?>
          <div class="offcanvas offcanvas-end" tabindex="-1" id="filter-canvas" aria-labelledby="offcanvasRightLabel">
            <div class="offcanvas-header">
              <h5 id="offcanvasRightLabel">Filter Menu Item</h5>
              <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body">
              <div class="form-group">
                <label class="form-label" for="filter_menu_group_id">Menu Group</label>
                <select class="form-control filter-select2" name="filter_menu_group_id" id="filter_menu_group_id">
                  <option value="">--</option>
                  <?php echo $api->generate_menu_group_options(); ?>
                </select>
              </div>
              <div class="form-group">
                <label class="form-label" for="filter_parent_id">Parent Menu Item</label>
                <select class="form-control filter-select2" name="filter_parent_id" id="filter_parent_id">
                  <option value="">--</option>
                  <?php echo $api->generate_menu_item_options(); ?>
                </select>
              </div>
              <div class="text-end mt-4">
                <button class="btn btn-light-primary btn-sm" id="apply-filter" data-bs-dismiss="offcanvas"> Apply </button>
                <button class="btn btn-light-danger btn-sm" data-bs-dismiss="offcanvas"> Close </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <?php 
        include_once('views/_footer.php'); 
        include_once('views/_required_js.php'); 
        include_once('views/_customizer.php'); 
    ?>
    <script src="./assets/js/plugins/sweetalert2.all.min.js"></script>
    <script src="./assets/js/plugins/select2.min.js?v=<?php echo rand(); ?>"></script>
    <script src="./assets/js/plugins/jquery.dataTables.min.js"></script>
    <script src="./assets/js/plugins/dataTables.bootstrap5.min.js"></script>
    <script src="./assets/js/pages/menu-items.js?v=<?php echo rand(); ?>"></script>
</body>

</html>