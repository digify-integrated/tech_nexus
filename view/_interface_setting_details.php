<div class="row">
  <div class="col-lg-12">
    <div class="card">
      <div class="card-header">
        <div class="row align-items-center">
          <div class="col-md-6">
            <h5>Interface Setting</h5>
          </div>
          <div class="col-md-6 text-sm-end mt-3 mt-sm-0">
            <?php                            
              $dropdown = '<div class="btn-group m-r-5">
                              <button type="button" class="btn btn-outline-secondary dropdown-toggle form-details" data-bs-toggle="dropdown" aria-expanded="false">
                                Action
                              </button>
                              <ul class="dropdown-menu dropdown-menu-end">';
                 
              if ($interfaceSettingDuplicateAccess['total'] > 0) {
                $dropdown .= '<li><button class="dropdown-item" type="button" id="duplicate-interface-setting">Duplicate Interface Setting</button></li>';
              }
                        
              if ($interfaceSettingDeleteAccess['total'] > 0) {
                $dropdown .= '<li><button class="dropdown-item" type="button" id="delete-interface-setting-details">Delete Interface Setting</button></li>';
              }
                      
              $dropdown .= '</ul>
                          </div>';
                  
              echo $dropdown;

              if ($interfaceSettingWriteAccess['total'] > 0) {
                echo '<button type="submit" class="btn btn-info form-details" id="edit-form">Edit</button>
                      <button type="submit" form="interface-setting-form" class="btn btn-success form-edit d-none" id="submit-data">Save</button>
                      <button type="button" id="discard-update" class="btn btn-outline-danger form-edit d-none">Discard</button>';
              }

              if ($interfaceSettingCreateAccess['total'] > 0) {
                echo '<a class="btn btn-success m-r-5 form-details" href="interface-setting.php?new">Create</a>';
              }
            ?>
          </div>
        </div>
      </div>
      <div class="card-body">
      <?php
          if($interfaceSettingWriteAccess['total'] > 0){
            echo '<form class="user-upload mb-4">
                    <img src="'. DEFAULT_AVATAR_IMAGE .'" alt="User Image" id="user_image" class="img-fluid wid-100 hei-100">
                    <label for="profile_picture" class="img-avtar-upload">
                      <i class="ti ti-camera f-24 mb-1"></i>
                      <span>Upload</span>
                    </label>
                    <input type="file" id="profile_picture" name="profile_picture" class="d-none">
                  </form>';
          }
          else{
            echo '<div class="chat-avtar d-inline-flex mx-auto mb-4">
                    <img class="img-fluid wid-100 hei-100" id="user_image" src="'. DEFAULT_AVATAR_IMAGE .'" alt="User image">
                  </div>';
          }
        ?>
        <form id="interface-setting-form" method="post" action="#">
          <?php
            if($interfaceSettingWriteAccess['total'] > 0){
              echo '<div class="form-group row">
                      <label class="col-lg-2 col-form-label">Interface Setting Name <span class="text-danger d-none form-edit">*</span></label>
                      <div class="col-lg-4">
                        <label class="col-form-label form-details fw-normal" id="interface_setting_name_label"></label>
                        <input type="text" class="form-control d-none form-edit" id="interface_setting_name" name="interface_setting_name" maxlength="100" autocomplete="off">
                      </div>
                      <label class="col-lg-2 col-form-label">Interface Setting Description <span class="text-danger d-none form-edit">*</span></label>
                      <div class="col-lg-4">
                        <label class="col-form-label form-details fw-normal" id="interface_setting_description_label"></label>
                        <input type="text" class="form-control d-none form-edit" id="interface_setting_description" name="interface_setting_description" maxlength="200" autocomplete="off">
                      </div>
                    </div>';
            }
            else{
              echo '<div class="form-group row">
                      <label class="col-lg-2 col-form-label">Interface Setting Name</label>
                      <div class="col-lg-4">
                        <label class="col-form-label form-details fw-normal" id="interface_setting_name_label"></label>
                      </div>
                      <label class="col-lg-2 col-form-label">Interface Setting Description</label>
                      <div class="col-lg-4">
                        <label class="col-form-label form-details fw-normal" id="interface_setting_description_label"></label>
                      </div>
                    </div>';
            }
          ?>
        </form>
      </div>
    </div>
  </div>
</div>
<?php
echo '<div class="row">
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
                '. $userModel->generateLogNotes('interface_setting', $interfaceSettingID) .'
              </div>
            </div>
          </div>
        </div>
      </div>';
?>