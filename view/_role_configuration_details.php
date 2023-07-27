 <div class="row">
          <div class="col-lg-12">
            <div class="card">
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
            if($updateMenuItemRoleAccess['total'] > 0){
              $menu_item_button = '<button type="button" class="btn btn-warning" data-role-id="' . $roleID . '" id="add-menu-item-access">Add Menu Item</button>
                            <button type="submit" class="btn btn-info edit-menu-item-access-details" id="edit-menu-item-access">Edit</button>
                            <button type="submit" form="update-menu-item-access-form" class="btn btn-success update-menu-item-access d-none" id="submit-update-menu-item-access">Save</button>
                            <button type="button" id="discard-menu-item-access-update" class="btn btn-outline-danger update-menu-item-access d-none">Discard</button>';
            }

            if($updateSystemActionRoleAccess['total'] > 0){
              $system_action_button = '<button type="button" class="btn btn-warning" data-role-id="' . $roleID . '" id="add-system-action-access">Add System Action</button>
                            <button type="submit" class="btn btn-info edit-system-action-access-details" id="edit-system-action-access">Edit</button>
                            <button type="submit" form="update-system-action-access-form" class="btn btn-success update-system-action-access d-none" id="submit-update-system-action-access">Save</button>
                            <button type="button" id="discard-system-action-access-update" class="btn btn-outline-danger update-system-action-access d-none">Discard</button>';
            }

            echo '<div class="row">
                    <div class="col-lg-12">
                      <div class="card">
                        <div class="card-header">
                          <div class="row align-items-center">
                            <div class="col-sm-6">
                              <h5>Menu Item Access</h5>
                            </div>
                            <div class="col-md-6 text-sm-end mt-3 mt-sm-0">
                            '. $menu_item_button .'
                            </div>
                          </div>
                        </div>
                        <div class="card-body">
                        <form id="update-menu-item-access-form" method="post" action="#">
                          <div class="dt-responsive table-responsive">
                              <table id="update-menu-item-access-table" class="table table-striped table-hover table-bordered nowrap w-100 dataTable">
                                <thead>
                                  <tr>
                                    <th>Menu Item</th>
                                    <th class="all">Read Access</th>
                                    <th class="all">Write Access</th>
                                    <th class="all">Create Access</th>
                                    <th class="all">Delete Access</th>
                                    <th class="all">Duplicate Access</th>
                                    <th class="all">Action</th>
                                  </tr>
                                </thead>
                                <tbody></tbody>
                              </table>
                          </div>
                          </form>
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
                              <div class="col-md-6 text-sm-end mt-3 mt-sm-0">
                                '. $system_action_button .'
                              </div>
                            </div>
                          </div>
                          <div class="card-body">
                            <form id="update-system-action-access-form" method="post" action="#">
                              <div class="dt-responsive table-responsive">
                                <table id="update-system-action-access-table" class="table table-striped table-hover table-bordered nowrap w-100 dataTable">
                                  <thead>
                                    <tr>
                                        <th>System Action</th>
                                        <th class="all">Access</th>
                                        <th class="all">Action</th>
                                      </tr>
                                    </thead>
                                    <tbody></tbody>
                                  </table>
                              </div>
                            </form>
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

                    if($updateMenuItemRoleAccess['total'] > 0){
                      echo '<div id="add-menu-item-access-modal" class="modal fade modal-animate anim-fade-in-scale" tabindex="-1" role="dialog" aria-labelledby="modal-add-menu-item-access-modal" aria-hidden="true">
                              <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg" role="document">
                                  <div class="modal-content">
                                  <div class="modal-header">
                                      <h5 class="modal-title" id="modal-add-role-access-modal-title">Assign Menu Item Access</h5>
                                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                  </div>
                                  <div class="modal-body" id="modal-body">
                                    <form id="add-menu-item-access-form" method="post" action="#">
                                      <div class="row">
                                          <div class="col-md-12">
                                              <table id="add-menu-item-access-table" class="table table-striped table-hover table-bordered nowrap w-100 dataTable">
                                                  <thead>
                                                  <tr>
                                                      <th class="all">Menu Item</th>
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
                                      <button type="submit" class="btn btn-primary" id="submit-menu-item-access" form="add-menu-item-access-form">Submit</button>
                                  </div>
                                  </div>
                              </div>
                              </div>';
                      }
        
                      if($updateSystemActionRoleAccess['total'] > 0){
                        echo '<div id="add-system-action-access-modal" class="modal fade modal-animate anim-fade-in-scale" tabindex="-1" role="dialog" aria-labelledby="modal-add-system-action-access-modal" aria-hidden="true">
                          <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg" role="document">
                              <div class="modal-content">
                              <div class="modal-header">
                                  <h5 class="modal-title" id="modal-add-role-access-modal-title">Assign System Action Access</h5>
                                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                              </div>
                              <div class="modal-body" id="modal-body">
                                <form id="add-system-action-access-form" method="post" action="#">
                                  <div class="row">
                                      <div class="col-md-12">
                                          <table id="add-system-action-access-table" class="table table-striped table-hover table-bordered nowrap w-100 dataTable">
                                              <thead>
                                              <tr>
                                                  <th class="all">System Action</th>
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
                                  <button type="submit" class="btn btn-primary" id="submit-system-action-access" form="add-system-action-access-form">Submit</button>
                              </div>
                              </div>
                          </div>
                        </div>';
                      }
            }

        ?>