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
              $dropdown = '<div class="btn-group m-r-5">
              <button type="button" class="btn btn-outline-secondary dropdown-toggle form-details" data-bs-toggle="dropdown" aria-expanded="false">
                  Action
              </button>
              <ul class="dropdown-menu dropdown-menu-end">';
          
              if ($systemActionDuplicateAccess['total'] > 0) {
                $dropdown .= '<li><button class="dropdown-item" type="button" id="duplicate-system-action">Duplicate System Action</button></li>';
              }
                        
              if ($systemActionDeleteAccess['total'] > 0) {
                $dropdown .= '<li><button class="dropdown-item" type="button" id="delete-system-action-details">Delete System Action</button></li>';
              }
                      
              $dropdown .= '</ul>
                        </div>';
                  
              echo $dropdown;

              if ($systemActionWriteAccess['total'] > 0) {
                echo '<button type="submit" class="btn btn-info form-details" id="edit-form">Edit</button>
                      <button type="submit" form="system-action-form" class="btn btn-success form-edit d-none" id="submit-data">Save</button>
                      <button type="button" id="discard-update" class="btn btn-outline-danger form-edit d-none">Discard</button>';
              }

              if ($systemActionCreateAccess['total'] > 0) {
                echo '<a class="btn btn-success m-r-5 form-details" href="system-action.php?new">Create</a>';
              }
            ?>
          </div>
        </div>
      </div>
      <div class="card-body">
        <form id="system-action-form" method="post" action="#">
          <?php
            if($systemActionWriteAccess['total'] > 0){
              echo '<div class="form-group row">
                      <label class="col-lg-2 col-form-label">Name <span class="text-danger d-none form-edit">*</span></label>
                      <div class="col-lg-10">
                        <label class="col-form-label form-details fw-normal" id="system_action_name_label"></label>
                        <input type="text" class="form-control d-none form-edit" id="system_action_name" name="system_action_name" maxlength="100" autocomplete="off">
                      </div>
                    </div>';
            }
            else{
              echo '<div class="form-group row">
                      <label class="col-lg-2 col-form-label">Name</label>
                      <div class="col-lg-10">
                        <label class="col-form-label form-details fw-normal" id="system_action_name_label"></label>
                      </div>
                    </div>';
            }
          ?>
        </form>
      </div>
    </div>
  </div>
<?php
    if($updateSystemActionRoleAccess['total'] > 0){
      $systemActionButton = '<button class="btn btn-warning" type="button" data-bs-toggle="offcanvas" data-bs-target="#add-system-action-role-access-offcanvas" aria-controls="add-system-action-role-access-offcanvas" id="add-system-action-role-access">Assign Role</button>';
    }

    echo '<div class="col-lg-12">
            <div class="card">
              <div class="card-header">
                <div class="row align-items-center">
                  <div class="col-sm-6">
                    <h5>Role Access</h5>
                  </div>
                  <div class="col-md-6 text-sm-end mt-3 mt-sm-0">
                    '. $systemActionButton .'
                  </div>
                </div>
              </div>
              <div class="card-body">
                <div class="dt-responsive table-responsive">
                  <table id="update-system-action-role-access-table" class="table table-hover nowrap w-100 dataTable">
                    <thead>
                      <tr>
                        <th>Role</th>
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
                  '. $userModel->generateLogNotes('system_action', $systemActionID) .'
                </div>
              </div>
            </div>
          </div>';

  if($updateSystemActionRoleAccess['total'] > 0){
    echo '<div class="offcanvas offcanvas-end" tabindex="-1" id="add-system-action-role-access-offcanvas" aria-labelledby="add-system-action-role-access-offcanvas-label">
              <div class="offcanvas-header">
                <h2 id="add-system-action-role-access-offcanvas-label" style="margin-bottom:-0.5rem">Assign Role</h2>
                <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
              </div>
              <div class="offcanvas-body">
                <div class="alert alert-success alert-dismissible mb-4" role="alert">
                  This table is used to specify and assign authorized roles for various system actions, ensuring controlled access and permissions within the system.
                  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <div class="row">
                  <div class="col-lg-12">
                    <form id="add-system-action-role-access-form" method="post" action="#">
                      <div class="row">
                        <div class="col-md-12">
                          <table id="add-system-action-role-access-table" class="table table-hover nowrap w-100 dataTable">
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