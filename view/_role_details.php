 <div class="row">
          <div class="col-lg-12">
            <div class="card">
              <div class="card-header">
                <div class="row align-items-center">
                  <div class="col-md-6">
                    <h5>Role</h5>
                  </div>
                  <div class="col-md-6 text-sm-end mt-3 mt-sm-0">
                    <?php
                      if (!empty($roleID) && $roleWriteAccess['total'] > 0) {
                        echo '<button type="submit" class="btn btn-info form-details" id="edit-form">Edit</button>
                              <button type="submit" form="role-form" class="btn btn-success form-edit d-none" id="submit-data">Save</button>
                              <button type="button" id="discard-update" class="btn btn-outline-danger form-edit d-none">Discard</button>';
                      }
                    ?>
                  </div>
                </div>
              </div>
              <div class="card-body">
                <form id="role-form" method="post" action="#">
                      <?php
                        if(!empty($roleID) && $roleWriteAccess['total'] > 0){
                           echo '<div class="form-group row">
                                    <label class="col-lg-2 col-form-label">Role Name <span class="text-danger d-none form-edit">*</span></label>
                                    <div class="col-lg-4">
                                        <label class="col-form-label form-details fw-normal" id="role_name_label"></label>
                                        <input type="text" class="form-control d-none form-edit" id="role_name" name="role_name" maxlength="100" autocomplete="off">
                                    </div>
                                    <label class="col-lg-2 col-form-label">Role Description <span class="text-danger d-none form-edit">*</span></label>
                                    <div class="col-lg-4">
                                        <label class="col-form-label form-details fw-normal" id="role_description_label"></label>
                                        <input type="text" class="form-control d-none form-edit" id="role_description" name="role_description" maxlength="200" autocomplete="off">
                                    </div>
                                </div>';
                        }
                        else{
                          echo '<div class="form-group row">
                                    <label class="col-lg-2 col-form-label">Role Name</label>
                                    <div class="col-lg-4">
                                        <label class="col-form-label form-details fw-normal" id="role_name_label"></label>
                                    </div>
                                    <label class="col-lg-2 col-form-label">Role Description</label>
                                    <div class="col-lg-4">
                                        <label class="col-form-label form-details fw-normal" id="role_description_label"></label>
                                    </div>
                                </div>';
                        }
                      ?>
                </form>
            </div>
          </div>
          <?php
          if(!empty($roleID)){
            if($assignUserAccountToRole['total'] > 0){
              $user_account_button = '<button type="button" class="btn btn-warning" data-role-id="' . $roleID . '" id="add-role-user-account">Add User Account</button>';
            }

            echo '<div class="row">
                    <div class="col-lg-12">
                      <div class="card">
                        <div class="card-header">
                          <div class="row align-items-center">
                            <div class="col-sm-6">
                              <h5>User Account</h5>
                            </div>
                            <div class="col-md-6 text-sm-end mt-3 mt-sm-0">
                            '. $user_account_button .'
                            </div>
                          </div>
                        </div>
                        <div class="card-body">
                          <div class="dt-responsive table-responsive">
                              <table id="role-user-account-access-table" class="table table-striped table-hover table-bordered nowrap w-100 dataTable">
                                <thead>
                                  <tr>
                                    <th>User Account</th>
                                    <th class="all">Login</th>
                                    <th class="all">Last Connection</th>
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
                              <h5>Menu Item Access</h5>
                            </div>
                          </div>
                        </div>
                        <div class="card-body">
                          <div class="dt-responsive table-responsive">
                              <table id="update-menu-item-role-access-table" class="table table-striped table-hover table-bordered nowrap w-100 dataTable">
                                <thead>
                                  <tr>
                                    <th>Menu Item</th>
                                    <th class="all">Read Access</th>
                                    <th class="all">Write Access</th>
                                    <th class="all">Create Access</th>
                                    <th class="all">Delete Access</th>
                                    <th class="all">Duplicate Access</th>
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
                            <h5>System Action Access</h5>
                          </div>
                        </div>
                      </div>
                      <div class="card-body">
                          <div class="dt-responsive table-responsive">
                            <table id="update-system-action-role-access-table" class="table table-striped table-hover table-bordered nowrap w-100 dataTable">
                              <thead>
                                <tr>
                                  <th>System Action</th>
                                  <th class="all">Access</th>
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
                            '. $userModel->generateLogNotes('role', $roleID) .'
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>';

                    if($assignUserAccountToRole['total'] > 0){
                      echo '<div id="add-role-user-account-modal" class="modal fade modal-animate anim-fade-in-scale" tabindex="-1" role="dialog" aria-labelledby="add-role-user-account-modal-title" aria-hidden="true">
                              <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg" role="document">
                                  <div class="modal-content">
                                  <div class="modal-header">
                                      <h5 class="modal-title" id="add-role-user-account-modal-title">Assign User Account</h5>
                                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                  </div>
                                  <div class="modal-body" id="modal-body">
                                    <form id="add-role-user-account-form" method="post" action="#">
                                      <div class="row">
                                          <div class="col-md-12">
                                              <table id="add-role-user-account-table" class="table table-striped table-hover table-bordered nowrap w-100 dataTable">
                                                  <thead>
                                                  <tr>
                                                    <th>User Account</th>
                                                    <th class="all">Login</th>
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
                                      <button type="submit" class="btn btn-primary" id="submit-add-role-user-account" form="add-role-user-account-form">Submit</button>
                                  </div>
                                  </div>
                              </div>
                              </div>';
                  }
            }

        ?>