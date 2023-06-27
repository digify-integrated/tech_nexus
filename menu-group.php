<?php
    require('session.php');
    require('config/config.php');
    require('model/database-model.php');
    require('model/user-model.php');
    require('model/security-model.php');
  
    $databaseModel = new DatabaseModel();
    $userModel = new UserModel($databaseModel);
    $securityModel = new SecurityModel();

    $page_title = 'Menu Group';

    $user = $userModel->getUserByID($_SESSION['user_id']);

    if (!$user['is_active']) {
        header('location: logout.php?logout');
        exit;
    }

    if(isset($_GET['id']) && !empty($_GET['id'])){
      if(empty($_GET['id'])){
        header('location: menu-group.php');
        exit;
      }

      $id = $_GET['id'];
      $menu_group_id = $securityModel->decryptData($id);
    }
    else{
      $menu_group_id = null;
    }

    require('config/_interface_settings.php');
    require('config/_user_account_details.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php include_once('config/_title.php'); ?>
    <?php include_once('config/_required_css.php'); ?>
</head>

<body data-pc-preset="preset-1" data-pc-sidebar-caption="true" data-pc-direction="ltr" data-pc-theme_contrast="false" data-pc-theme="light">
    <?php 
        include_once('config/_preloader.html'); 
        include_once('config/_navbar.php'); 
        include_once('config/_header.php'); 
        include_once('config/_announcement.php'); 
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
                  <li class="breadcrumb-item" aria-current="page">Menu Groups</li>
                  <?php
                    if(!empty($menu_group_id)){
                      echo '<li class="breadcrumb-item" id="menu-group-id">'. $menu_group_id .'</li>';
                    }
                  ?>
                </ul>
              </div>
              <div class="col-md-12">
                <div class="page-header-title">
                  <h2 class="mb-0">Menu Groups</h2>
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
                      <h5>Menu Groups List</h5>
                    </div>
                    
                  </div>
                </div>
              </div>
              <div class="card-body">
                <div class="table-responsive dt-responsive">
                  <table id="menu-groups-table" class="table table-striped table-hover table-bordered nowrap w-100">
                    <thead>
                      <tr>
                        <th class="all">
                          <div class="form-check">
                            <input class="form-check-input" id="datatable-checkbox" type="checkbox">
                          </div>
                        </th>
                        <th>#</th>
                        <th>Menu Group</th>
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
        </div>
      </div>
    </section>
    
    <?php 
        include_once('config/_change_password_modal.php');
        include_once('config/_error_modal.php');
        include_once('config/_required_js.php'); 
        include_once('config/_customizer.php'); 
    ?>
    <script src="./assets/js/plugins/sweetalert2.all.min.js"></script>
    <script src="./assets/js/plugins/jquery.dataTables.min.js"></script>
    <script src="./assets/js/plugins/dataTables.bootstrap5.min.js"></script>
    <script src="./assets/js/pages/menu-group.js?v=<?php echo rand(); ?>"></script>
</body>

</html>