<div class="row">
  <div class="col-lg-12">
    <div class="card">
      <div class="card-header">
        <div class="row align-items-center">
          <div class="col-md-6">
            <h5>Upload Setting</h5>
          </div>
          <div class="col-md-6 text-sm-end mt-3 mt-sm-0">
            <?php                            
              $dropdown = '<div class="btn-group m-r-5">
                              <button type="button" class="btn btn-outline-secondary dropdown-toggle form-details" data-bs-toggle="dropdown" aria-expanded="false">
                                Action
                              </button>
                              <ul class="dropdown-menu dropdown-menu-end">';
                 
              if ($uploadSettingDuplicateAccess['total'] > 0) {
                $dropdown .= '<li><button class="dropdown-item" type="button" id="duplicate-upload-setting">Duplicate Upload Setting</button></li>';
              }
                        
              if ($uploadSettingDeleteAccess['total'] > 0) {
                $dropdown .= '<li><button class="dropdown-item" type="button" id="delete-upload-setting-details">Delete Upload Setting</button></li>';
              }
                      
              $dropdown .= '</ul>
                          </div>';
                  
              echo $dropdown;

              if ($uploadSettingWriteAccess['total'] > 0) {
                echo '<button type="submit" class="btn btn-info form-details" id="edit-form">Edit</button>
                      <button type="submit" form="upload-setting-form" class="btn btn-success form-edit d-none" id="submit-data">Save</button>
                      <button type="button" id="discard-update" class="btn btn-outline-danger form-edit d-none">Discard</button>';
              }

              if ($uploadSettingCreateAccess['total'] > 0) {
                echo '<a class="btn btn-success m-r-5 form-details" href="upload-setting.php?new">Create</a>';
              }
            ?>
          </div>
        </div>
      </div>
      <div class="card-body">
        <form id="upload-setting-form" method="post" action="#">
          <?php
            if($uploadSettingWriteAccess['total'] > 0){
              echo '<div class="form-group row">
                      <label class="col-lg-2 col-form-label">Name <span class="text-danger d-none form-edit">*</span></label>
                      <div class="col-lg-4">
                        <label class="col-form-label form-details fw-normal" id="upload_setting_name_label"></label>
                        <input type="text" class="form-control d-none form-edit" id="upload_setting_name" name="upload_setting_name" maxlength="100" autocomplete="off">
                      </div>
                      <label class="col-lg-2 col-form-label">Max File Size <span class="text-danger d-none form-edit">*</span></label>
                      <div class="col-lg-4">
                        <label class="col-form-label form-details fw-normal" id="max_file_size_label"></label>
                        <div class="input-group d-none form-edit">
                          <input type="number" class="form-control" id="max_file_size" name="max_file_size" min="1">
                          <span class="input-group-text">mb</span>
                        </div>
                      </div>
                    </div>
                    <div class="form-group row">
                      <label class="col-lg-2 col-form-label">Description <span class="text-danger d-none form-edit">*</span></label>
                      <div class="col-lg-4">
                        <label class="col-form-label form-details fw-normal" id="upload_setting_description_label"></label>
                        <input type="text" class="form-control d-none form-edit" id="upload_setting_description" name="upload_setting_description" maxlength="200" autocomplete="off">
                      </div>
                    </div>';
            }
            else{
              echo '<div class="form-group row">
                      <label class="col-lg-2 col-form-label">Name <span class="text-danger d-none form-edit">*</span></label>
                      <div class="col-lg-4">
                        <label class="col-form-label form-details fw-normal" id="upload_setting_name_label"></label>
                      </div>
                      <label class="col-lg-2 col-form-label">Max File Size <span class="text-danger d-none form-edit">*</span></label>
                      <div class="col-lg-4">
                        <label class="col-form-label form-details fw-normal" id="max_file_size_label"></label>
                      </div>
                    </div>
                    <div class="form-group row">
                      <label class="col-lg-2 col-form-label">Description <span class="text-danger d-none form-edit">*</span></label>
                      <div class="col-lg-4">
                        <label class="col-form-label form-details fw-normal" id="upload_setting_description_label"></label>
                      </div>
                    </div>';
            }
          ?>
        </form>
      </div>
    </div>
  </div>
<?php
if($addFileExtensionToUploadSetting['total'] > 0){
  $add_file_extension = '<button type="button" class="btn btn-warning" id="add-file-extension">Add File Extension</button>';
}

echo '<div class="col-lg-12">
        <div class="card">
          <div class="card-header">
            <div class="row align-items-center">
              <div class="col-sm-6">
                <h5>File Extension</h5>
              </div>
              <div class="col-sm-6 text-sm-end mt-3 mt-sm-0">
                '. $add_file_extension .'
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
              '. $userModel->generateLogNotes('upload_setting', $uploadSettingID) .'
            </div>
          </div>
        </div>
      </div>';

    if($addFileExtensionToUploadSetting['total'] > 0){
      echo '<div id="add-file-extension-modal" class="modal fade modal-animate anim-fade-in-scale" tabindex="-1" role="dialog" aria-labelledby="add-file-extension-modal-title" aria-hidden="true">
              <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg" role="document">
                  <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="add-file-extension-modal-title">Assign Role Access</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body" id="modal-body">
                      <form id="add-file-extension-form" method="post" action="#">
                        <div class="row">
                            <div class="col-md-12">
                                <table id="add-file-extension-table" class="table table-striped table-hover table-bordered nowrap w-100 dataTable">
                                    <thead>
                                    <tr>
                                        <th class="all">File Extension</th>
                                        <th class="all">Assign</th>
                                    </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>
                      </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" id="submit-add-file-extension" form="add-file-extension-form">Submit</button>
                    </div>
                  </div>
                </div>
              </div>';
  }
?>
</div>