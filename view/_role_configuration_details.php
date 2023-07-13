 <div class="row">
          <div class="col-lg-12">
            <div class="card">
              <div class="sticky-action">
                <div class="card-header">
                  <div class="row align-items-center">
                    <div class="col-md-6">
                      <h5>Role Configuration</h5>
                    </div>
                    <div class="col-md-6 text-sm-end mt-3 mt-sm-0">
                    <?php                            
                       if (!empty($roleID)) {
                          $dropdown = '<div class="btn-group m-r-5">
                                  <button type="button" class="btn btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                      Action
                                  </button>
                                  <ul class="dropdown-menu dropdown-menu-end">';
                          
                            if ($roleCreateAccess['total'] > 0) {
                                $dropdown .= '<li><a class="dropdown-item" href="role-configuration.php?new">Create Role</a></li>';
                            }
                            
                            if ($roleDuplicateAccess['total'] > 0) {
                                $dropdown .= '<li><button class="dropdown-item" type="button" data-role-id="' . $roleID . '" id="duplicate-role">Duplicate Role</button></li>';
                            }
                            
                            if ($roleDeleteAccess['total'] > 0) {
                                $dropdown .= '<li><button class="dropdown-item" type="button" data-role-id="' . $roleID . '" id="delete-role-details">Delete Role</button></li>';
                            }

                            if($assignMenuItemRoleAccess > 0 || $assignSystemActionRoleAccess > 0){
                              $dropdown .= '<li><div class="dropdown-divider"></div></li>';

                              if ($assignMenuItemRoleAccess > 0) {
                                $dropdown .= '<li><button class="dropdown-item" type="button" data-role-id="' . $roleID . '" id="assign-menu-item-access">Menu Item Access</button></li>';
                              }
                              
                              if ($assignSystemActionRoleAccess > 0) {
                                  $dropdown .= '<li><button class="dropdown-item" type="button" data-role-id="' . $roleID . '" id="assign-system-action-access">System Action Access</button></li>';
                              }
                            }
                          
                            $dropdown .= '</ul>
                              </div>';
                      
                          echo $dropdown;
                      }

                      if (!empty($roleID) && $roleWriteAccess['total'] > 0) {
                        echo '<button type="submit" class="btn btn-info form-details" id="edit-form">Edit</button>
                              <button type="submit" form="role-configuration-form" class="btn btn-success form-edit d-none" id="submit-data">Save</button>
                              <button type="button" id="discard-update" class="btn btn-outline-danger form-edit d-none">Discard</button>';
                      }          
                    ?>
                    </div>
                  </div>
                </div>
              </div>
              <div class="card-body">
                <form id="role-configuration-form" method="post" action="#">
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
                                        <input type="text" class="form-control d-none form-edit" id="role_description" name="role_description" maxlength="100" autocomplete="off">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-lg-2 col-form-label">Assignable</label>
                                    <div class="col-lg-4">
                                        <div class="col-form-label form-details fw-normal" id="assignable_label"></div>
                                        <div class="d-none form-edit">
                                            <select class="form-control select2" name="assignable" id="assignable">
                                              <option value="1">Yes</option>
                                              <option value="0">No</option>
                                            </select>
                                        </div>
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
                                </div>
                                <div class="form-group row">
                                    <label class="col-lg-2 col-form-label">Assignable</label>
                                    <div class="col-lg-4">
                                        <div class="col-form-label form-details fw-normal" id="assignable_label"></div>
                                    </div>
                                </div>';
                        }
                      ?>
                </form>
            </div>
          </div>
          <?php
          if(!empty($roleID)){
            if($roleCreateAccess['total'] > 0){
                $menu_item_create = '<button type="button" class="btn btn-success" id="create-role">Create</button>';
            }

            echo '<div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                          <div>
                            <div class="card-header">
                              <div class="row align-items-center">
                                <div class="col-sm-6">
                                  <h5>Log Notes</h5>
                                </div>
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
            }

            if($assignMenuItemRoleAccess > 0){
                echo '<div id="assign-menu-item-access-modal" class="modal fade modal-animate anim-fade-in-scale" tabindex="-1" role="dialog" aria-labelledby="modal-assign-role-role-access-modal" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg" role="document">
                            <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="modal-assign-role-role-access-modal-title">Assign Menu Item Access</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body" id="modal-body"><form id="assign-role-role-access-form" method="post" action="#">
                            <div class="row">
                                <div class="col-md-12">
                                    <table id="assign-menu-item-access-table" class="table table-striped table-hover table-bordered nowrap w-100 dataTable">
                                        <thead>
                                        <tr>
                                            <th class="all">Menu Item</th>
                                            <th class="all">Read</th>
                                            <th class="all">Write</th>
                                            <th class="all">Create</th>
                                            <th class="all">Delete</th>
                                            <th class="all">Duplicate</th>
                                        </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                            </div>
                        </form></div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary" id="submit-menu-item-access-form" form="assign-menu-item-access-form">Submit</button>
                            </div>
                            </div>
                        </div>
                        </div>';
            }

            if($assignSystemActionRoleAccess > 0){
              echo '<div id="assign-system-action-access-modal" class="modal fade modal-animate anim-fade-in-scale" tabindex="-1" role="dialog" aria-labelledby="modal-assign-menu-item-role-access-modal" aria-hidden="true">
                  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg" role="document">
                      <div class="modal-content">
                      <div class="modal-header">
                          <h5 class="modal-title" id="modal-assign-system-action-access-modal-title">Assign System Action Role Access</h5>
                          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                      </div>
                      <div class="modal-body" id="modal-body"><form id="assign-system-action-access-form" method="post" action="#">
                      <div class="row">
                          <div class="col-md-12">
                              <table id="assign-system-action-access-table" class="table table-striped table-hover table-bordered nowrap w-100 dataTable">
                                  <thead>
                                  <tr>
                                      <th class="all">Access</th>
                                      <th class="all">System Action</th>
                                  </tr>
                                  </thead>
                                  <tbody></tbody>
                              </table>
                          </div>
                      </div>
                  </form></div>
                      <div class="modal-footer">
                          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                          <button type="submit" class="btn btn-primary" id="submit-system-action-access-form" form="assign-system-action-access-form">Submit</button>
                      </div>
                      </div>
                  </div>
                  </div>';
          }
        ?>