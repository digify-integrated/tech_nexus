 <div class="row">
          <div class="col-lg-12">
            <div class="card">
              <div class="card-header">
                <div class="row align-items-center">
                  <div class="col-md-6">
                    <h5>User Account</h5>
                  </div>
                  <div class="col-md-6 text-sm-end mt-3 mt-sm-0">
                    <?php                            
                       if (!empty($userAccountID)) {
                          $dropdown = '<div class="btn-group m-r-5">
                                  <button type="button" class="btn btn-outline-secondary dropdown-toggle form-details" data-bs-toggle="dropdown" aria-expanded="false">
                                      Action
                                  </button>
                                  <ul class="dropdown-menu dropdown-menu-end">';
                            
                            if ($activateUserAccount['total'] > 0 && !$isActive) {
                                $dropdown .= '<li><button class="dropdown-item" type="button" data-user-account-id="' . $userAccountID . '" id="activate-user-account-details">Activate User Account</button></li>';
                            }
                            
                            if ($deactivateUserAccount['total'] > 0 && $isActive) {
                                $dropdown .= '<li><button class="dropdown-item" type="button" data-user-account-id="' . $userAccountID . '" id="deactivate-user-account-details">Deactivate User Account</button></li>';
                            }
                            
                            if ($lockUserAccount['total'] > 0 && !$isLocked) {
                                $dropdown .= '<li><button class="dropdown-item" type="button" data-user-account-id="' . $userAccountID . '" id="lock-user-account-details">Lock User Account</button></li>';
                            }
                            
                            if ($unlockUserAccount['total'] > 0 && $isLocked) {
                                $dropdown .= '<li><button class="dropdown-item" type="button" data-user-account-id="' . $userAccountID . '" id="unlock-user-account-details">Unlock User Account</button></li>';
                            }
                            
                            if ($userAccountDeleteAccess['total'] > 0) {
                                $dropdown .= '<li><button class="dropdown-item" type="button" data-user-account-id="' . $userAccountID . '" id="delete-user-account-details">Delete User Account</button></li>';
                            }
                          
                          $dropdown .= '</ul>
                              </div>';
                      
                          echo $dropdown;
                      }

                      if (!empty($userAccountID) && $userAccountWriteAccess['total'] > 0) {
                        echo '<button type="submit" class="btn btn-info form-details" id="edit-form">Edit</button>
                              <button type="submit" form="user-account-form" class="btn btn-success form-edit d-none" id="submit-data">Save</button>
                              <button type="button" id="discard-update" class="btn btn-outline-danger form-edit d-none">Discard</button>';
                      }

                      if (!empty($userAccountID) && $userAccountCreateAccess['total'] > 0) {
                        echo '<a class="btn btn-success m-r-5 form-details" href="user-account.php?new">Create</a>';
                      }
                    ?>
                  </div>
                </div>
              </div>
              <div class="card-body">
                <div class="row">
                  <div class="col-lg-12">
                    <form id="user-account-form" method="post" action="#">
                      <?php
                        if(!empty($userAccountID) && $userAccountWriteAccess['total'] > 0){
                           echo '<div class="form-group row">
                                                <input type="hidden" id="user_account_id" name="user_account_id" value="'. $userAccountID .'">
                                                <label class="col-lg-2 col-form-label">Name <span class="text-danger d-none form-edit">*</span></label>
                                                <div class="col-lg-4">
                                                    <label class="col-form-label form-details fw-normal" id="file_as_label"></label>
                                                    <input type="text" class="form-control d-none form-edit" id="file_as" name="file_as" maxlength="300" autocomplete="off">
                                                </div>
                                                <label class="col-lg-2 col-form-label">Email <span class="text-danger d-none form-edit">*</span></label>
                                                <div class="col-lg-4">
                                                    <label class="col-form-label form-details fw-normal" id="email_label"></label>
                                                    <input type="email" class="form-control d-none form-edit" id="email" name="email" maxlength="100" autocomplete="off">
                                                </div>
                                            </div>';
                        }
                        else{
                          echo '<div class="form-group row">
                                                <label class="col-lg-2 col-form-label">Name</label>
                                                <div class="col-lg-4">
                                                    <label class="col-form-label form-details fw-normal" id="file_as_label"></label>
                                                </div>
                                                <label class="col-lg-2 col-form-label">Email</label>
                                                <div class="col-lg-4">
                                                    <label class="col-form-label form-details fw-normal" id="email_label"></label>
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
                  if(!empty($userAccountID)){
                      if($assignRoleToUserAccount['total'] > 0){
                        $role_button = '<button type="button" class="btn btn-warning" data-user-account-id="' . $userAccountID . '" id="add-user-account-role">Add Role</button>';
                      }

                      echo '<div class="row">
                              <div class="col-lg-4">
                                <div class="card">
                                  <div class="card-header">
                                    <h5>User Details</h5>
                                  </div>
                                  <div class="card-body">
                                    <div class="d-flex align-items-center justify-content-between mb-1">
                                      <div>
                                        <p class="text-muted mb-0">Status</p>
                                      </div>
                                      <div class="form-check form-switch p-0">
                                        <label class="col-form-label fw-normal" id="status_label"></label>
                                      </div>
                                    </div>
                                    <div class="d-flex align-items-center justify-content-between mb-1">
                                      <div>
                                        <p class="text-muted mb-0">Locked</p>
                                      </div>
                                      <div class="form-check form-switch p-0">
                                        <label class="col-form-label fw-normal" id="locked_label"></label>
                                      </div>
                                    </div>                                    
                                    <div class="d-flex align-items-center justify-content-between mb-1">
                                      <div>
                                        <p class="text-muted mb-0">Account Lock Duration</p>
                                      </div>
                                      <div class="form-check form-switch p-0">
                                        <label class="col-form-label fw-normal" id="account_lock_duration_label"></label>
                                      </div>
                                    </div>
                                    <div class="d-flex align-items-center justify-content-between mb-1">
                                      <div>
                                        <p class="text-muted mb-0">Last Connection Date</p>
                                      </div>
                                      <div class="form-check form-switch p-0">
                                        <label class="col-form-label fw-normal" id="last_connection_date_label"></label>
                                      </div>
                                    </div>
                                    <div class="d-flex align-items-center justify-content-between mb-1">
                                      <div>
                                        <p class="text-muted mb-0">Password Expiry Date</p>
                                      </div>
                                      <div class="form-check form-switch p-0">
                                        <label class="col-form-label fw-normal" id="password_expiry_date_label"></label>
                                      </div>
                                    </div>
                                    <div class="d-flex align-items-center justify-content-between mb-1">
                                      <div>
                                        <p class="text-muted mb-0">Last Failed Login Attempt</p>
                                      </div>
                                      <div class="form-check form-switch p-0">
                                        <label class="col-form-label fw-normal" id="last_failed_login_attempt_label"></label>
                                      </div>
                                    </div>
                                    <div class="d-flex align-items-center justify-content-between mb-1">
                                      <div>
                                        <p class="text-muted mb-0">Last Password Reset</p>
                                      </div>
                                      <div class="form-check form-switch p-0">
                                        <label class="col-form-label fw-normal" id="last_password_reset_label"></label>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <div class="col-lg-8">
                                <div class="card">
                                  <div class="card-header">
                                    <div class="row align-items-center">
                                      <div class="col-sm-6">
                                        <h5>Role</h5>
                                      </div>
                                      <div class="col-md-6 text-sm-end mt-3 mt-sm-0">
                                      '. $role_button .'
                                      </div>
                                    </div>
                                  </div>
                                  <div class="card-body">
                                    <div class="dt-responsive table-responsive">
                                        <table id="user-account-role-access-table" class="table table-striped table-hover table-bordered nowrap w-100 dataTable">
                                          <thead>
                                            <tr>
                                              <th>Role</th>
                                              <th class="all">Actions</th>
                                            </tr>
                                          </thead>
                                          <tbody></tbody>
                                        </table>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
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
                                  '. $userModel->generateLogNotes('users', $userAccountID) .'
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>';

                          if($assignRoleToUserAccount['total'] > 0){
                            echo '<div id="add-user-account-role-modal" class="modal fade modal-animate anim-fade-in-scale" tabindex="-1" role="dialog" aria-labelledby="add-user-account-role-modal-title" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg" role="document">
                                        <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="add-user-account-role-modal-title">Assign User Account</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body" id="modal-body">
                                          <form id="add-user-account-role-form" method="post" action="#">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <table id="add-user-account-role-table" class="table table-striped table-hover table-bordered nowrap w-100 dataTable">
                                                        <thead>
                                                        <tr>
                                                          <th>Role</th>
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
                                            <button type="submit" class="btn btn-primary" id="submit-add-user-account-role" form="add-user-account-role-form">Submit</button>
                                        </div>
                                        </div>
                                    </div>
                                    </div>';
                        }
                  }
                ?>