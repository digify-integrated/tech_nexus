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
               $dropdown = '<div class="btn-group m-r-5">
                              <button type="button" class="btn btn-outline-secondary dropdown-toggle form-details" data-bs-toggle="dropdown" aria-expanded="false">
                                Action
                              </button>
                              <ul class="dropdown-menu dropdown-menu-end">';
           
              if ($roleDuplicateAccess['total'] > 0) {
                $dropdown .= '<li><button class="dropdown-item" type="button" id="duplicate-role">Duplicate Role</button></li>';
              }
                        
              if ($roleDeleteAccess['total'] > 0) {
                $dropdown .= '<li><button class="dropdown-item" type="button" id="delete-role-details">Delete Role</button></li>';
              }
                      
              $dropdown .= '</ul>
                        </div>';
                  
              echo $dropdown;

              if ($roleWriteAccess['total'] > 0) {
                echo '<button type="submit" class="btn btn-info form-details" id="edit-form">Edit</button>
                      <button type="submit" form="role-configuration-form" class="btn btn-success form-edit d-none" id="submit-data">Save</button>
                      <button type="button" id="discard-update" class="btn btn-outline-danger form-edit d-none">Discard</button>';
              }

              if ($roleCreateAccess['total'] > 0) {
                echo '<a class="btn btn-success m-r-5 form-details" href="role-configuration.php?new">Create</a>';
              }
            ?>
          </div>
        </div>
      </div>
      <div class="card-body">
        <form id="role-configuration-form" method="post" action="#">
          <?php
            if($roleWriteAccess['total'] > 0){
              echo '<div class="form-group row">
                      <label class="col-lg-2 col-form-label">Name <span class="text-danger d-none form-edit">*</span></label>
                      <div class="col-lg-4">
                        <label class="col-form-label form-details fw-normal" id="role_name_label"></label>
                        <input type="text" class="form-control d-none form-edit" id="role_name" name="role_name" maxlength="100" autocomplete="off">
                      </div>
                      <label class="col-lg-2 col-form-label">Assignable</label>
                      <div class="col-lg-4">
                        <label class="col-form-label form-details fw-normal" id="assignable_label"></label>
                        <div class="d-none form-edit">
                          <select class="form-control select2" name="assignable" id="assignable">
                            <option value="1">Yes</option>
                            <option value="0">No</option>
                          </select>
                        </div>
                      </div>
                    </div>
                    <div class="form-group row">
                      <label class="col-lg-2 col-form-label">Description <span class="text-danger d-none form-edit">*</span></label>
                      <div class="col-lg-4">
                        <label class="col-form-label form-details fw-normal" id="role_description_label"></label>
                        <input type="text" class="form-control d-none form-edit" id="role_description" name="role_description" maxlength="200" autocomplete="off">
                      </div>
                    </div>';
            }
            else{
              echo '<div class="form-group row">
                      <label class="col-lg-2 col-form-label">Name</label>
                      <div class="col-lg-4">
                        <label class="col-form-label form-details fw-normal" id="role_name_label"></label>
                      </div>
                      <label class="col-lg-2 col-form-label">Assignable</label>
                      <div class="col-lg-4">
                        <label class="col-form-label form-details fw-normal" id="assignable_label"></label>
                      </div>
                    </div>
                    <div class="form-group row">
                      <label class="col-lg-2 col-form-label">Description</label>
                      <div class="col-lg-4">
                        <label class="col-form-label form-details fw-normal" id="role_description_label"></label>
                      </div>
                    </div>';
            }
          ?>      
        </form>
      </div>
    </div>
  </div>
<?php
  if($assignUserAccountToRole['total'] > 0){
    $userAccountButton = '<button class="btn btn-warning" type="button" data-bs-toggle="offcanvas" data-bs-target="#add-role-user-account-offcanvas" aria-controls="add-role-user-account-offcanvas" id="add-role-user-account">Assign User Account</button>';
  }

  if($updateMenuItemRoleAccess['total'] > 0){
    $menuItemButton = '<button class="btn btn-warning" type="button" data-bs-toggle="offcanvas" data-bs-target="#add-menu-item-role-access-offcanvas" aria-controls="add-menu-item-role-access-offcanvas" id="add-menu-item-role-access">Assign Menu Item Access</button>';
  }

  if($updateSystemActionRoleAccess['total'] > 0){
    $systemActionButton = '<button class="btn btn-warning" type="button" data-bs-toggle="offcanvas" data-bs-target="#add-system-action-role-access-offcanvas" aria-controls="add-system-action-role-access-offcanvas" id="add-system-action-role-access">Assign System Action Access</button>';
  }

  echo '<div class="col-lg-12">
          <div class="card">
            <div class="card-header">
              <div class="row align-items-center">
                <div class="col-sm-6">
                  <h5>User Account</h5>
                </div>
                <div class="col-md-6 text-sm-end mt-3 mt-sm-0">
                  '. $userAccountButton .'
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
        <div class="col-lg-12">
          <div class="card">
            <div class="card-header">
              <div class="row align-items-center">
                <div class="col-sm-6">
                  <h5>Menu Item Access</h5>
                </div>
                <div class="col-md-6 text-sm-end mt-3 mt-sm-0">
                  '. $menuItemButton .'
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
        <div class="col-lg-12">
          <div class="card">
            <div class="card-header">
              <div class="row align-items-center">
                <div class="col-sm-6">
                  <h5>System Action Access</h5>
                </div>
                <div class="col-md-6 text-sm-end mt-3 mt-sm-0">
                  '. $systemActionButton .'
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
        </div>';

  if($assignUserAccountToRole['total'] > 0){
    echo '<div class="offcanvas offcanvas-end" tabindex="-1" id="add-role-user-account-offcanvas" aria-labelledby="add-role-user-account-offcanvas-label">
            <div class="offcanvas-header">
              <h2 id="add-role-user-account-offcanvas-label" style="margin-bottom:-0.5rem">Assign User Account</h2>
              <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body">
              <div class="alert alert-success alert-dismissible mb-4" role="alert">
                This table serves as a central repository for assigning user accounts to specific roles, ensuring controlled access and permissions within an organization\'s systems and applications.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              </div>
              <div class="row">
                <div class="col-lg-12">
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
            </div>
            <div class="row mt-4">
              <div class="col-lg-12">
                <button type="submit" class="btn btn-primary" id="submit-add-role-user-account" form="add-role-user-account-form">Submit</button>
                <button class="btn btn-light-danger" data-bs-dismiss="offcanvas"> Close </button>
              </div>
            </div>
          </div>
        </div>';
  }

  if($updateMenuItemRoleAccess['total'] > 0){
    echo '<div class="offcanvas offcanvas-end" tabindex="-1" id="add-menu-item-role-access-offcanvas" aria-labelledby="add-menu-item-role-access-offcanvas-label">
            <div class="offcanvas-header">
              <h2 id="add-menu-item-role-access-offcanvas-label" style="margin-bottom:-0.5rem">Assign Menu Item Role Access</h2>
              <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body">
              <div class="alert alert-success alert-dismissible mb-4" role="alert">
                This table serves as a central repository for assigning user accounts to specific roles, ensuring controlled access and permissions within an organization\'s systems and applications.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              </div>
              <div class="row">
                <div class="col-lg-12">
                  <form id="add-menu-item-role-access-form" method="post" action="#">
                    <div class="row">
                      <div class="col-md-12">
                        <table id="add-menu-item-role-access-table" class="table table-striped table-hover table-bordered nowrap w-100 dataTable">
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
            </div>
            <div class="row mt-4">
              <div class="col-lg-12">
                <button type="submit" class="btn btn-primary" id="submit-add-menu-item-role-access" form="add-menu-item-role-access-form">Submit</button>
                <button class="btn btn-light-danger" data-bs-dismiss="offcanvas"> Close </button>
              </div>
            </div>
          </div>
        </div>';
  }

  if($updateSystemActionRoleAccess['total'] > 0){
    echo '<div class="offcanvas offcanvas-end" tabindex="-1" id="add-system-action-role-access-offcanvas" aria-labelledby="add-system-action-role-access-offcanvas-label">
            <div class="offcanvas-header">
              <h2 id="add-system-action-role-access-offcanvas-label" style="margin-bottom:-0.5rem">Assign System Action Role Access</h2>
              <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body">
              <div class="alert alert-success alert-dismissible mb-4" role="alert">
                This table serves as a central repository for assigning user accounts to specific roles, ensuring controlled access and permissions within an organization\'s systems and applications.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              </div>
              <div class="row">
                <div class="col-lg-12">
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
            </div>
            <div class="row mt-4">
              <div class="col-lg-12">
                <button type="submit" class="btn btn-primary" id="submit-add-system-action-role-access" form="add-system-action-role-access-form">Submit</button>
                <button class="btn btn-light-danger" data-bs-dismiss="offcanvas"> Close </button>
              </div>
            </div>
          </div>
        </div>';
   }
?>
</div>