 <div class="row">
          <div class="col-lg-12">
            <div class="card">
              <div class="card-header">
                <div class="row align-items-center">
                  <div class="col-md-6">
                    <h5>System Action</h5>
                  </div>
                  <div class="col-md-6 text-sm-end mt-3 mt-sm-0">
                    <?php                            
                       if (!empty($systemActionID)) {
                          $dropdown = '<div class="btn-group m-r-5">
                                  <button type="button" class="btn btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                      Action
                                  </button>
                                  <ul class="dropdown-menu dropdown-menu-end">';
                          
                            if ($systemActionCreateAccess['total'] > 0) {
                                $dropdown .= '<li><a class="dropdown-item" href="system-action.php?new">Create System Action</a></li>';
                            }
                            
                            if ($systemActionDuplicateAccess['total'] > 0) {
                                $dropdown .= '<li><button class="dropdown-item" type="button" data-system-action-id="' . $systemActionID . '" id="duplicate-system-action">Duplicate System Action</button></li>';
                            }
                            
                            if ($systemActionDeleteAccess['total'] > 0) {
                                $dropdown .= '<li><button class="dropdown-item" type="button" data-system-action-id="' . $systemActionID . '" id="delete-system-action-details">Delete System Action</button></li>';
                            }
                          
                          $dropdown .= '</ul>
                              </div>';
                      
                          echo $dropdown;
                      }

                      if (!empty($systemActionID) && $systemActionWriteAccess['total'] > 0) {
                        echo '<button type="submit" class="btn btn-info form-details" id="edit-form">Edit</button>
                              <button type="submit" form="system-action-form" class="btn btn-success form-edit d-none" id="submit-data">Save</button>
                              <button type="button" id="discard-update" class="btn btn-outline-danger form-edit d-none">Discard</button>';
                      }          
                    ?>
                  </div>
                </div>
              </div>
              <div class="card-body">
                <form id="system-action-form" method="post" action="#">
                      <?php
                        if(!empty($systemActionID) && $systemActionWriteAccess['total'] > 0){
                           echo '<div class="form-group row">
                                                <input type="hidden" id="system_action_id" name="system_action_id" value="'. $systemActionID .'">
                                                <label class="col-lg-2 col-form-label">System Action Name <span class="text-danger d-none form-edit">*</span></label>
                                                <div class="col-lg-10">
                                                    <label class="col-form-label form-details fw-normal" id="system_action_name_label"></label>
                                                    <input type="text" class="form-control d-none form-edit" id="system_action_name" name="system_action_name" maxlength="100" autocomplete="off">
                                                </div>
                                            </div>';
                        }
                        else{
                          echo '<div class="form-group row">
                                                <label class="col-lg-2 col-form-label">System Action Name</label>
                                                <div class="col-lg-10">
                                                    <label class="col-form-label form-details fw-normal" id="system_action_name_label"></label>
                                                </div>
                                            </div>';
                        }
                      ?>
                </form>
            </div>
          </div>
          <?php
           if(!empty($systemActionID)){
              if($updateSystemActionRoleAccess['total'] > 0){
                $system_action_button = '<button type="button" class="btn btn-warning" data-system-action-id="' . $systemActionID . '" id="add-system-action-role-access">Add Role</button>';
              }

              echo '<div class="row">
                    <div class="col-lg-12">
                      <div class="card">
                        <div class="card-header">
                          <div class="row align-items-center">
                            <div class="col-sm-6">
                              <h5>Role Access</h5>
                            </div>
                            <div class="col-md-6 text-sm-end mt-3 mt-sm-0">
                            '. $system_action_button .'
                            </div>
                          </div>
                        </div>
                        <div class="card-body">
                          <div class="dt-responsive table-responsive">
                            <table id="update-system-action-role-access-table" class="table table-striped table-hover table-bordered nowrap w-100 dataTable">
                              <thead>
                                <tr>
                                  <th>Role</th>
                                  <th class="all">Access</th>
                                  <th class="all">Action</th>
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
                            '. $userModel->generateLogNotes('system_action', $systemActionID) .'
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>';

                    if($updateSystemActionRoleAccess['total'] > 0){
                      echo '<div id="add-system-action-role-access-modal" class="modal fade modal-animate anim-fade-in-scale" tabindex="-1" role="dialog" aria-labelledby="add-system-action-role-access-modal-title" aria-hidden="true">
                      <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg" role="document">
                          <div class="modal-content">
                          <div class="modal-header">
                              <h5 class="modal-title" id="add-system-action-role-access-modal-title">Assign Role Access</h5>
                              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                          </div>
                          <div class="modal-body" id="modal-body">
                            <form id="add-system-action-role-access-form" method="post" action="#">
                              <div class="row">
                                  <div class="col-md-12">
                                      <table id="add-system-action-role-access-table" class="table table-striped table-hover table-bordered nowrap w-100 dataTable">
                                          <thead>
                                          <tr>
                                              <th class="all">Role</th>
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
                              <button type="submit" class="btn btn-primary" id="submit-add-system-action-role-access" form="add-system-action-role-access-form">Submit</button>
                          </div>
                          </div>
                      </div>
                      </div>';
                  }
            }
        ?>