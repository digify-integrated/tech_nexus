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
                       if (!empty($uploadSettingID)) {
                          $dropdown = '<div class="btn-group m-r-5">
                                  <button type="button" class="btn btn-outline-secondary dropdown-toggle form-details" data-bs-toggle="dropdown" aria-expanded="false">
                                      Action
                                  </button>
                                  <ul class="dropdown-menu dropdown-menu-end">';
                            
                            if ($uploadSettingDuplicateAccess['total'] > 0) {
                                $dropdown .= '<li><button class="dropdown-item" type="button" data-upload-setting-id="' . $uploadSettingID . '" id="duplicate-upload-setting">Duplicate Upload Setting</button></li>';
                            }
                            
                            if ($uploadSettingDeleteAccess['total'] > 0) {
                                $dropdown .= '<li><button class="dropdown-item" type="button" data-upload-setting-id="' . $uploadSettingID . '" id="delete-upload-setting-details">Delete Upload Setting</button></li>';
                            }
                          
                          $dropdown .= '</ul>
                              </div>';
                      
                          echo $dropdown;
                      }

                      if (!empty($uploadSettingID) && $uploadSettingWriteAccess['total'] > 0) {
                        echo '<button type="submit" class="btn btn-info form-details" id="edit-form">Edit</button>
                              <button type="submit" form="upload-setting-form" class="btn btn-success form-edit d-none" id="submit-data">Save</button>
                              <button type="button" id="discard-update" class="btn btn-outline-danger form-edit d-none">Discard</button>';
                      }

                      if (!empty($uploadSettingID) && $uploadSettingCreateAccess['total'] > 0) {
                        echo '<a class="btn btn-success m-r-5 form-details" href="upload-setting.php?new">Create</a>';
                      }
                    ?>
                  </div>
                </div>
              </div>
              <div class="card-body">
                <form id="upload-setting-form" method="post" action="#">
                      <?php
                        if(!empty($uploadSettingID) && $uploadSettingWriteAccess['total'] > 0){
                           echo '<div class="form-group row">
                                    <input type="hidden" id="upload_setting_id" name="upload_setting_id" value="'. $uploadSettingID .'">
                                    <label class="col-lg-2 col-form-label">Upload Setting Name <span class="text-danger d-none form-edit">*</span></label>
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
                                    <label class="col-lg-2 col-form-label">Upload Setting Description <span class="text-danger d-none form-edit">*</span></label>
                                    <div class="col-lg-4">
                                        <label class="col-form-label form-details fw-normal" id="upload_setting_description_label"></label>
                                        <input type="text" class="form-control d-none form-edit" id="upload_setting_description" name="upload_setting_description" maxlength="200" autocomplete="off">
                                    </div>
                                </div>';
                        }
                        else{
                          echo '<div class="form-group row">
                                    <label class="col-lg-2 col-form-label">Upload Setting Name <span class="text-danger d-none form-edit">*</span></label>
                                    <div class="col-lg-4">
                                        <label class="col-form-label form-details fw-normal" id="upload_setting_name_label"></label>
                                    </div>
                                    <label class="col-lg-2 col-form-label">Max File Size <span class="text-danger d-none form-edit">*</span></label>
                                    <div class="col-lg-4">
                                        <label class="col-form-label form-details fw-normal" id="max_file_size_label"></label>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-lg-2 col-form-label">Upload Setting Description <span class="text-danger d-none form-edit">*</span></label>
                                    <div class="col-lg-4">
                                        <label class="col-form-label form-details fw-normal" id="upload_setting_description_label"></label>
                                    </div>
                                </div>';
                        }
                      ?>
                </form>
            </div>
          </div>
          <?php
           if(!empty($uploadSettingID)){
              
                echo '
                    <div class="row">
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
                      </div>
                    </div>';
            }
        ?>