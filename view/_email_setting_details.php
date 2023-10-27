<div class="row">
  <div class="col-lg-12">
    <div class="card">
      <div class="card-header">
        <div class="row align-items-center">
          <div class="col-md-6">
            <h5>Email Setting</h5>
          </div>
          <div class="col-md-6 text-sm-end mt-3 mt-sm-0">
            <?php                            
                $dropdown = '<div class="btn-group m-r-5">
                              <button type="button" class="btn btn-outline-secondary dropdown-toggle form-details" data-bs-toggle="dropdown" aria-expanded="false">
                                Action
                              </button>
                              <ul class="dropdown-menu dropdown-menu-end">';

                if ($emailSettingWriteAccess['total'] > 0) {
                    $dropdown .= '<li><button class="dropdown-item" type="button" data-bs-toggle="offcanvas" data-bs-target="#change-mail-password-offcanvas" aria-controls="change-mail-password-offcanvas" id="change-mail-password">Change Password</button></li>';
                }
                    
                if ($emailSettingDuplicateAccess['total'] > 0) {
                    $dropdown .= '<li><button class="dropdown-item" type="button" id="duplicate-email-setting">Duplicate Email Setting</button></li>';
                }
                            
                if ($emailSettingDeleteAccess['total'] > 0) {
                    $dropdown .= '<li><button class="dropdown-item" type="button" id="delete-email-setting-details">Delete Email Setting</button></li>';
                }
                        
                $dropdown .= '</ul>
                            </div>';
                    
                echo $dropdown;

                if ($emailSettingWriteAccess['total'] > 0) {
                    echo '<button type="submit" class="btn btn-info form-details" id="edit-form">Edit</button>
                        <button type="submit" form="email-setting-form" class="btn btn-success form-edit d-none" id="submit-data">Save</button>
                        <button type="button" id="discard-update" class="btn btn-outline-danger form-edit d-none">Discard</button>';
                }

                if ($emailSettingCreateAccess['total'] > 0) {
                    echo '<a class="btn btn-success m-r-5 form-details" href="email-setting.php?new">Create</a>';
                }
            ?>
          </div>
        </div>
      </div>
      <div class="card-body">
        <form id="email-setting-form" method="post" action="#">
          <?php
            if($emailSettingWriteAccess['total'] > 0){
              echo '<div class="form-group row">
                      <label class="col-lg-2 col-form-label">Name <span class="text-danger d-none form-edit">*</span></label>
                      <div class="col-lg-4">
                        <label class="col-form-label form-details fw-normal" id="email_setting_name_label"></label>
                        <input type="text" class="form-control d-none form-edit" id="email_setting_name" name="email_setting_name" maxlength="100" autocomplete="off">
                      </div>
                      <label class="col-lg-2 col-form-label">Mail Host <span class="text-danger d-none form-edit">*</span></label>
                      <div class="col-lg-4">
                        <label class="col-form-label form-details fw-normal" id="mail_host_label"></label>
                        <input type="text" class="form-control d-none form-edit" id="mail_host" name="mail_host" maxlength="100" autocomplete="off">
                      </div>
                    </div>
                    <div class="form-group row">
                      <label class="col-lg-2 col-form-label">Description <span class="text-danger d-none form-edit">*</span></label>
                      <div class="col-lg-4">
                        <label class="col-form-label form-details fw-normal" id="email_setting_description_label"></label>
                        <input type="text" class="form-control d-none form-edit" id="email_setting_description" name="email_setting_description" maxlength="200" autocomplete="off">
                      </div>
                      <label class="col-lg-2 col-form-label">Mail Username <span class="text-danger d-none form-edit">*</span></label>
                      <div class="col-lg-4">
                        <label class="col-form-label form-details fw-normal" id="mail_username_label"></label>
                        <input type="text" class="form-control d-none form-edit" id="mail_username" name="mail_username" maxlength="100" autocomplete="off">
                      </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-2 col-form-label">Mail Encryption <span class="text-danger d-none form-edit">*</span></label>
                        <div class="col-lg-4">
                            <label class="col-form-label form-details fw-normal" id="mail_encryption_label"></label>
                            <div class="d-none form-edit">
                                <select class="form-control select2" name="mail_encryption" id="mail_encryption">
                                  <option value="none">none</option>
                                  <option value="ssl">ssl</option>
                                  <option value="starttls">starttls</option>
                                  <option value="tls">tls</option>
                                </select>
                            </div>
                        </div>
                        <label class="col-lg-2 col-form-label">SMTP Authentication <span class="text-danger d-none form-edit">*</span></label>
                        <div class="col-lg-4">
                            <label class="col-form-label form-details fw-normal" id="smtp_auth_label"></label>
                            <div class="d-none form-edit">
                                <select class="form-control select2" name="smtp_auth" id="smtp_auth">
                                    <option value="0">False</option>
                                    <option value="1">True</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                      <label class="col-lg-2 col-form-label">Mail From Name <span class="text-danger d-none form-edit">*</span></label>
                      <div class="col-lg-4">
                        <label class="col-form-label form-details fw-normal" id="mail_from_name_label"></label>
                        <input type="text" class="form-control d-none form-edit" id="mail_from_name" name="mail_from_name" maxlength="200" autocomplete="off">
                      </div>
                      <label class="col-lg-2 col-form-label">Port <span class="text-danger d-none form-edit">*</span></label>
                      <div class="col-lg-4">
                        <label class="col-form-label form-details fw-normal" id="port_label"></label>
                        <input type="number" class="form-control d-none form-edit" id="port" name="port" autocomplete="off">
                      </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-2 col-form-label">Mail From Email <span class="text-danger d-none form-edit">*</span></label>
                        <div class="col-lg-4">
                        <label class="col-form-label form-details fw-normal" id="mail_from_email_label"></label>
                        <input type="email" class="form-control d-none form-edit" id="mail_from_email" name="mail_from_email" maxlength="200" autocomplete="off">
                        </div>
                        <label class="col-lg-2 col-form-label">SMTP Auto TLS <span class="text-danger d-none form-edit">*</span></label>
                        <div class="col-lg-4">
                            <label class="col-form-label form-details fw-normal" id="smtp_auto_tls_label"></label>
                            <div class="d-none form-edit">
                                <select class="form-control select2" name="smtp_auto_tls" id="smtp_auto_tls">
                                    <option value="0">False</option>
                                    <option value="1">True</option>
                                </select>
                            </div>
                        </div>
                    </div>';
            }
            else{
              echo '<div class="form-group row">
                      <label class="col-lg-2 col-form-label">Name</label>
                      <div class="col-lg-4">
                        <label class="col-form-label form-details fw-normal" id="email_setting_name_label"></label>
                      </div>
                      <label class="col-lg-2 col-form-label">Mail Host</label>
                      <div class="col-lg-4">
                        <label class="col-form-label form-details fw-normal" id="mail_host_label"></label>
                      </div>
                    </div>
                    <div class="form-group row">
                      <label class="col-lg-2 col-form-label">Description</label>
                      <div class="col-lg-4">
                        <label class="col-form-label form-details fw-normal" id="email_setting_description_label"></label>
                      </div>
                      <label class="col-lg-2 col-form-label">Mail Username</label>
                      <div class="col-lg-4">
                        <label class="col-form-label form-details fw-normal" id="mail_username_label"></label>
                      </div>
                    </div>
                    <div class="form-group row">
                      <label class="col-lg-2 col-form-label">Mail Encryption</label>
                      <div class="col-lg-4">
                        <label class="col-form-label form-details fw-normal" id="mail_encryption_label"></label>
                      </div>
                      <label class="col-lg-2 col-form-label">SMTP Authentication</label>
                      <div class="col-lg-4">
                        <label class="col-form-label form-details fw-normal" id="smtp_auth_label"></label>
                      </div>
                    </div>
                    <div class="form-group row">
                      <label class="col-lg-2 col-form-label">Mail From Name</label>
                      <div class="col-lg-4">
                        <label class="col-form-label form-details fw-normal" id="mail_from_name_label"></label>
                      </div>
                      <label class="col-lg-2 col-form-label">Port</label>
                      <div class="col-lg-4">
                        <label class="col-form-label form-details fw-normal" id="port_label"></label>
                      </div>
                    </div>
                    <div class="form-group row">
                      <label class="col-lg-2 col-form-label">Mail From Email</label>
                      <div class="col-lg-4">
                        <label class="col-form-label form-details fw-normal" id="mail_from_email_label"></label>
                      </div>
                      <label class="col-lg-2 col-form-label">SMTP Auto TLS</label>
                      <div class="col-lg-4">
                        <label class="col-form-label form-details fw-normal" id="smtp_auto_tls_label"></label>
                      </div>
                    </div>';
            }
          ?>
        </form>
      </div>
    </div>
  </div>
<?php
    echo '<div class="col-lg-12">
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
                '. $userModel->generateLogNotes('email_setting', $emailSettingID) .'
                </div>
            </div>
            </div>
        </div>';
    
    if($emailSettingWriteAccess['total'] > 0){
      echo '<div class="offcanvas offcanvas-end" tabindex="-1" id="change-mail-password-offcanvas" aria-labelledby="change-mail-password-offcanvas-label">
              <div class="offcanvas-header">
                <h2 id="change-mail-password-offcanvas-label" style="margin-bottom:-0.5rem">Change Password</h2>
                <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
              </div>
              <div class="offcanvas-body">
                <div class="alert alert-success alert-dismissible mb-4" role="alert">
                  This form allows users to change the email setting password, ensuring it meets security requirements, including a minimum length and a combination of lowercase, uppercase letters, numbers, and special characters.
                  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <div class="row">
                  <div class="col-lg-12">
                    <form id="change-mail-password-form" method="post" action="#">
                      <div class="row">
                        <div class="col-sm-12">
                          <div class="form-group">
                            <label class="form-label">New Password <span class="text-danger">*</span></label>
                            <input type="password" class="form-control" id="new_password" name="new_password">
                          </div>
                          <div class="form-group">
                            <label class="form-label">Confirm Password <span class="text-danger">*</span></label>
                            <input type="password" class="form-control" id="confirm_password" name="confirm_password">
                          </div>
                        </div>
                      </div>
                    </form>
                  </div>
                </div>
                <div class="row">
                  <div class="col-lg-12">
                    <button type="submit" class="btn btn-primary" id="submit-change-mail-password-form" form="change-mail-password-form">Update Password</button>
                    <button class="btn btn-light-danger" data-bs-dismiss="offcanvas"> Close </button>
                  </div>
                </div>
              </div>
            </div>';
    }
?>
</div>