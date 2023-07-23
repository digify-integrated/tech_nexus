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
        ?>