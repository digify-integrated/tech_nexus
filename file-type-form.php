<?php
require('session.php');
require('config/config.php');
require('classes/api.php');

$api = new Api;
$page_title = 'File Type Form';

$check_user_status = $api->check_user_status(null, $email);

if($check_user_status){    
  $file_type_read_access_right = $api->check_menu_access_rights($email, 6, 'read');
      
  if($file_type_read_access_right > 0){
    if(isset($_GET['id']) && !empty($_GET['id'])){
      $id = $_GET['id'];
      $file_type_id = $api->decrypt_data($id);

      $check_file_types_exist = $api->check_file_types_exist($file_type_id);
        
      if($check_file_types_exist === 0){
        header('location: 404.php');
      }
    }
    else{
      $file_type_id = null;
    }

    $file_type_create_access_right = $api->check_menu_access_rights($email, 6, 'create');
    $file_type_write_access_right = $api->check_menu_access_rights($email, 6, 'write');
    $file_type_delete_access_right = $api->check_menu_access_rights($email, 6, 'delete');
    $file_extension_create_access_right = $api->check_menu_access_rights($email, 7, 'create');
    $file_extension_write_access_right = $api->check_menu_access_rights($email, 7, 'write');
        
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
                  <li class="breadcrumb-item">Configurations</li>
                  <li class="breadcrumb-item"><a href="file-types.php">File Types</a></li>
                  <li class="breadcrumb-item" aria-current="page">File Type Form</li>
                  <?php
                    if(!empty($file_type_id)){
                      echo '<li class="breadcrumb-item" id="file-type-id">'. $file_type_id .'</li>';
                    }
                  ?>
                </ul>
              </div>
              <div class="col-md-12">
                <div class="page-header-title">
                  <h2 class="mb-0">File Type Form</h2>
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
                      <h5>File Types Form</h5>
                    </div>
                    <div class="col-md-6 text-sm-end mt-3 mt-sm-0">
                      <?php
                        if (!empty($file_type_id)) {
                            $dropdown = '<div class="btn-group m-r-5 ">
                                              <button type="button" class="btn btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                                Action
                                              </button>
                                              <ul class="dropdown-menu dropdown-menu-end">';
                                              
                          if ($file_type_create_access_right > 0) {
                            $dropdown .= '<li><a class="dropdown-item" href="file-type-form.php">Create File Type</a></li>
                            <li><button class="dropdown-item" type="button" data-file-type-id="' . $file_type_id . '" id="duplicate-file-type">Duplicate File Type</button></li>';
                          }

                          if ($file_type_delete_access_right > 0) {
                            $dropdown .= '<li><button class="dropdown-item" type="button" data-file-type-id="' . $file_type_id . '" id="delete-file-type">Delete File Type</button></li>';
                          }

                          $dropdown .= '</ul>
                                        </div>';

                          echo $dropdown;
                        }
                            
                        if (empty($file_type_id) && $file_type_create_access_right > 0) {
                          echo '<button type="submit" form="file-type-form" class="btn btn-success form-edit" id="submit-data">Save</button>
                                <button type="button" id="discard-create" class="btn btn-outline-danger form-edit">Discard</button>';
                        } 
                        else if (!empty($file_type_id) && $file_type_write_access_right > 0) {
                          echo '<button type="submit" class="btn btn-info form-details" id="edit-form">Edit</button>
                                <button type="submit" form="file-type-form" class="btn btn-success form-edit d-none" id="submit-data">Save</button>
                                <button type="button" id="discard-update" class="btn btn-outline-danger form-edit d-none">Discard</button>';
                        }
                      ?>
                    </div>
                  </div>
                </div>
              </div>
              <div class="card-body">
                <?php echo $api->generate_form('file type form', $file_type_id, $email); ?>
              </div>
            </div>
          </div>

          <?php
          if(!empty($file_type_id)){
            if($file_extension_create_access_right > 0){
              $file_extension_create = '<button type="button" class="btn btn-success" id="create-file-extension">Create</button>';
            }

            echo '<div class="col-lg-12">
                    <div class="card">
                      <div id="sticky-action" class="sticky-action">
                        <div class="card-header">
                          <div class="row align-items-center">
                            <div class="col-sm-6">
                              <h5>File Extension</h5>
                            </div>
                            <div class="col-sm-6 text-sm-end mt-3 mt-sm-0">
                              '. $file_extension_create .'
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="card-body">
                        <div class="dt-responsive table-responsive">
                          <table id="file-extension-table" class="table table-striped table-hover table-bordered nowrap w-100 dataTable">
                            <thead>
                              <tr>
                                <th>#</th>
                                <th>File Extension</th>
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

          if(!empty($file_type_id)){
            echo $api->generate_log_notes('file_types', $file_type_id);
          }

          if($file_extension_create_access_right > 0 || $file_extension_write_access_right > 0){
            echo $api->generate_modal('file extension form', 'file-extension-form', 'file-extension-modal', 'File Extension');
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
    <script src="./assets/js/pages/file-type-form.js?v=<?php echo rand(); ?>"></script>
</body>

</html>