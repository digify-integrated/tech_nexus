<div class="row">
  <div class="col-lg-12">
    <div class="card">
      <div class="card-header">
        <div class="row align-items-center">
          <div class="col-md-6">
            <h5>Department</h5>
          </div>
          <div class="col-md-6 text-sm-end mt-3 mt-sm-0">
            <?php                            
              $dropdown = '<div class="btn-group m-r-5">
                              <button type="button" class="btn btn-outline-secondary dropdown-toggle form-details" data-bs-toggle="dropdown" aria-expanded="false">
                                Action
                              </button>
                              <ul class="dropdown-menu dropdown-menu-end">';
                 
              if ($departmentDuplicateAccess['total'] > 0) {
                $dropdown .= '<li><button class="dropdown-item" type="button" id="duplicate-department">Duplicate Department</button></li>';
              }
                        
              if ($departmentDeleteAccess['total'] > 0) {
                $dropdown .= '<li><button class="dropdown-item" type="button" id="delete-department-details">Delete Department</button></li>';
              }
                      
              $dropdown .= '</ul>
                          </div>';
                  
              echo $dropdown;

              if ($departmentWriteAccess['total'] > 0) {
                echo '<button type="submit" class="btn btn-info form-details" id="edit-form">Edit</button>
                      <button type="submit" form="department-form" class="btn btn-success form-edit d-none" id="submit-data">Save</button>
                      <button type="button" id="discard-update" class="btn btn-outline-danger form-edit d-none">Discard</button>';
              }

              if ($departmentCreateAccess['total'] > 0) {
                echo '<a class="btn btn-success m-r-5 form-details" href="department.php?new">Create</a>';
              }
            ?>
          </div>
        </div>
      </div>
      <div class="card-body">
        <div class="row">
          <div class="col-md-12">
            <form id="department-form" method="post" action="#">
              <?php
                if($departmentWriteAccess['total'] > 0){
                  echo '<div class="form-group row">
                          <label class="col-lg-2 col-form-label">Name <span class="text-danger d-none form-edit">*</span></label>
                          <div class="col-lg-4">
                            <label class="col-form-label form-details fw-normal" id="department_name_label"></label>
                            <input type="text" class="form-control d-none form-edit" id="department_name" name="department_name" maxlength="100" autocomplete="off">
                          </div>
                          <label class="col-lg-2 col-form-label">Parent Department</label>
                            <div class="col-lg-4">
                                <label class="col-form-label form-details fw-normal" id="parent_department_label"></label>
                                <div class="d-none form-edit">
                                    <select class="form-control select2" name="parent_department" id="parent_department">
                                        <option value="">--</option>
                                        '. $departmentModel->generateDepartmentOptions() .'
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-2 col-form-label">Manager</label>
                            <div class="col-lg-4">
                                <label class="col-form-label form-details fw-normal" id="manager_label"></label>
                                <div class="d-none form-edit">
                                    <select class="form-control select2" name="manager" id="manager">
                                        <option value="">--</option>
                                    </select>
                                </div>
                            </div>
                        </div>';
                }
                else{
                  echo '<div class="form-group row">
                            <label class="col-lg-2 col-form-label">Name</label>
                            <div class="col-lg-4">
                                <label class="col-form-label form-details fw-normal" id="department_name_label"></label>
                            </div>
                            <label class="col-lg-2 col-form-label">Parent Department</label>
                            <div class="col-lg-4">
                                <label class="col-form-label form-details fw-normal" id="parent_department_label"></label>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-2 col-form-label">Manager</label>
                            <div class="col-lg-4">
                                <label class="col-form-label form-details fw-normal" id="manager_label"></label>
                            </div>
                        </div>';
                }
              ?>
            </form>
          </div>
        </div>
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
              '. $userModel->generateLogNotes('department', $departmentID) .'
            </div>
          </div>
        </div>
      </div>';
?>
</div>