<div class="row">
  <div class="col-lg-12">
    <div class="card">
      <div class="card-header">
        <div class="row align-items-center">
          <div class="col-md-6">
            <h5>File Type</h5>
          </div>
          <div class="col-md-6 text-sm-end mt-3 mt-sm-0">
            <?php
             $dropdown = '<div class="btn-group m-r-5">
                            <button type="button" class="btn btn-outline-secondary dropdown-toggle form-details" data-bs-toggle="dropdown" aria-expanded="false">Action</button>
                            <ul class="dropdown-menu dropdown-menu-end">';
           
            if ($fileTypeDuplicateAccess['total'] > 0) {
              $dropdown .= '<li><button class="dropdown-item" type="button" id="duplicate-file-type">Duplicate File Type</button></li>';
            }
                      
            if ($fileTypeDeleteAccess['total'] > 0) {
              $dropdown .= '<li><button class="dropdown-item" type="button" id="delete-file-type-details">Delete File Type</button></li>';
            }
                    
            $dropdown .= '</ul>
                    </div>';
                
            echo $dropdown;

            if ($fileTypeWriteAccess['total'] > 0) {
              echo '<button type="submit" class="btn btn-info form-details" id="edit-form">Edit</button>
                  <button type="submit" form="file-type-form" class="btn btn-success form-edit d-none" id="submit-data">Save</button>
                  <button type="button" id="discard-update" class="btn btn-outline-danger form-edit d-none">Discard</button>';
            }

            if ($fileTypeCreateAccess['total'] > 0) {
              echo '<a class="btn btn-success m-r-5 form-details" href="file-type.php?new">Create</a>';
            }
            ?>
          </div>
        </div>
      </div>
      <div class="card-body">
        <form id="file-type-form" method="post" action="#">
        <?php
          if($fileTypeWriteAccess['total'] > 0){
            echo '<div class="form-group row">
                    <label class="col-lg-2 col-form-label">Name <span class="text-danger d-none form-edit">*</span></label>
                    <div class="col-lg-10">
                      <label class="col-form-label form-details fw-normal" id="file_type_name_label"></label>
                      <input type="text" class="form-control d-none form-edit" id="file_type_name" name="file_type_name" maxlength="100" autocomplete="off">
                    </div>
                  </div>';
          }
          else{
            echo '<div class="form-group row">
                    <label class="col-lg-2 col-form-label">Name</label>
                    <div class="col-lg-10">
                      <label class="col-form-label form-details fw-normal" id="file_type_name_label"></label>
                    </div>
                  </div>';
          }
        ?>
        </form>
      </div>
    </div>
  </div>
<?php
  if($fileExtensionCreateAccess['total'] > 0){
    $fileExtensionCreate = '<button class="btn btn-warning" type="button" data-bs-toggle="offcanvas" data-bs-target="#file-extension-offcanvas" aria-controls="file-extension-offcanvas" id="add-file-extension">Add File Extension</button>';
  }
            
  echo '<div class="col-lg-12">
          <div class="card">
            <div class="card-header">
              <div class="row align-items-center">
                <div class="col-sm-6">
                  <h5>File Extension</h5>
                </div>
                <div class="col-sm-6 text-sm-end mt-3 mt-sm-0">
                  '. $fileExtensionCreate .'
                </div>
              </div>
            </div>
            <div class="card-body">
              <div class="dt-responsive table-responsive">
                <table id="file-extension-table" class="table table-striped table-hover table-bordered nowrap w-100 dataTable">
                  <thead>
                    <tr>
                      <th>File Extension</th>
                      <th>Actions</th>
                    </tr>
                  </thead>
                  <tbody></tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
        <div class="col-lg-12">
          <div class="card">
            <div class="card-header">
              <div class="row align-items-center">
                <div class="col-sm-6">
                  <h5>Log Notes</h5>
                </div>
              </div>
            </div>
            <div class="log-notes-scroll" style="max-height: 450px; position: relative;">
              <div class="card-body p-b-0">
                '. $userModel->generateLogNotes('file_type', $fileTypeID) .'
              </div>
            </div>
          </div>
        </div>';

  if($fileExtensionCreateAccess['total'] > 0 || $fileExtensionWriteAccess['total'] > 0){
    echo '<div class="offcanvas offcanvas-end" tabindex="-1" id="file-extension-offcanvas" aria-labelledby="file-extension-offcanvas-label">
            <div class="offcanvas-header">
              <h2 id="file-extension-offcanvas-label" style="margin-bottom:-0.5rem">File Extension</h2>
              <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body">
              <div class="alert alert-success alert-dismissible mb-4" role="alert">
                This file extension is used to document and categorize various file extensions, aiding in the organization and management of digital files
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              </div>
              <div class="row">
                <div class="col-lg-12">
                  <form id="file-extension-form" method="post" action="#">
                    <div class="form-group">
                      <label class="form-label" for="file_extension_name">File Extension Name <span class="text-danger">*</span></label>
                      <input type="hidden" id="file_extension_id" name="file_extension_id">
                      <input type="text" class="form-control" id="file_extension_name" name="file_extension_name" maxlength="100" autocomplete="off">
                    </div>
                  </form>
              </div>
            </div>
            <div class="row">
              <div class="col-lg-12">
                <button type="submit" class="btn btn-primary" id="submit-file-extension-form" form="file-extension-form">Submit</button>
                <button class="btn btn-light-danger" data-bs-dismiss="offcanvas"> Close </button>
              </div>
            </div>
          </div>
        </div>';
  }
?>
</div>