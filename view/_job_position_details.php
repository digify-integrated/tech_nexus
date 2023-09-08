<div class="row">
  <div class="col-lg-12">
    <div class="card">
      <div class="card-header">
        <div class="row align-items-center">
          <div class="col-md-6">
            <h5>Job Position</h5>
          </div>
          <div class="col-md-6 text-sm-end mt-3 mt-sm-0">
            <?php                            
                $dropdown = '<div class="btn-group m-r-5">
                                <button type="button" class="btn btn-outline-secondary dropdown-toggle form-details" data-bs-toggle="dropdown" aria-expanded="false">
                                    Action
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end">';
                    
                if ($startJobPositionRecruitment['total'] > 0 && !$recruitmentStatus) {
                  $dropdown .= '<li><button class="dropdown-item" type="button" id="start-job-position-recruitment">Start Recruitment</button></li>';
                }
                    
                if ($stopJobPositionRecruitment['total'] > 0 && $recruitmentStatus) {
                  $dropdown .= '<li><button class="dropdown-item" type="button" id="stop-job-position-recruitment">Stop Recruitment</button></li>';
                }

                if ($jobPositionDuplicateAccess['total'] > 0) {
                    $dropdown .= '<li><button class="dropdown-item" type="button" id="duplicate-job-position">Duplicate Job Position</button></li>';
                }
                            
                if ($jobPositionDeleteAccess['total'] > 0) {
                    $dropdown .= '<li><button class="dropdown-item" type="button" id="delete-job-position-details">Delete Job Position</button></li>';
                }
                        
                $dropdown .= '</ul>
                            </div>';
                    
                echo $dropdown;

                if ($jobPositionWriteAccess['total'] > 0) {
                    echo '<button type="submit" class="btn btn-info form-details" id="edit-form">Edit</button>
                        <button type="submit" form="job-position-form" class="btn btn-success form-edit d-none" id="submit-data">Save</button>
                        <button type="button" id="discard-update" class="btn btn-outline-danger form-edit d-none">Discard</button>';
                }

                if ($jobPositionCreateAccess['total'] > 0) {
                    echo '<a class="btn btn-success m-r-5 form-details" href="job-position.php?new">Create</a>';
                }
            ?>
          </div>
        </div>
      </div>
      <div class="card-body">
        <form id="job-position-form" method="post" action="#">
          <?php
            if($jobPositionWriteAccess['total'] > 0){
              echo '<div class="form-group row">
                      <label class="col-lg-2 col-form-label">Name <span class="text-danger d-none form-edit">*</span></label>
                      <div class="col-lg-4">
                        <label class="col-form-label form-details fw-normal" id="job_position_name_label"></label>
                        <input type="text" class="form-control d-none form-edit" id="job_position_name" name="job_position_name" maxlength="100" autocomplete="off">
                      </div>
                      <label class="col-lg-2 col-form-label">Department</label>
                      <div class="col-lg-4">
                          <label class="col-form-label form-details fw-normal" id="department_id_label"></label>
                          <div class="d-none form-edit">
                              <select class="form-control select2" name="department_id" id="department_id">
                                  <option value="">--</option>
                                  '. $departmentModel->generateDepartmentOptions() .'
                              </select>
                          </div>
                      </div>
                    </div>
                    <div class="form-group row">
                      <label class="col-lg-2 col-form-label">Description <span class="text-danger d-none form-edit">*</span></label>
                      <div class="col-lg-4">
                        <label class="col-form-label form-details fw-normal" id="job_position_description_label"></label>
                        <input type="text" class="form-control d-none form-edit" id="job_position_description" name="job_position_description" maxlength="2000" autocomplete="off">
                      </div>
                    </div>';
            }
            else{
              echo '<div class="form-group row">
                      <label class="col-lg-2 col-form-label">Name</label>
                      <div class="col-lg-4">
                        <label class="col-form-label form-details fw-normal" id="job_position_name_label"></label>
                      </div>
                      <label class="col-lg-2 col-form-label">Department</label>
                      <div class="col-lg-4">
                        <label class="col-form-label form-details fw-normal" id="department_id_label"></label>
                      </div>
                    </div>
                    <div class="form-group row">
                      <label class="col-lg-2 col-form-label">Description</label>
                      <div class="col-lg-4">
                        <label class="col-form-label form-details fw-normal" id="job_position_description_label"></label>
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
                '. $userModel->generateLogNotes('job_position', $jobPositionID) .'
              </div>
            </div>
          </div>
        </div>';

  if ($startJobPositionRecruitment['total'] > 0 && !$recruitmentStatus) {
    echo '<div id="start-job-position-recruitment-modal" class="modal fade modal-animate anim-fade-in-scale" tabindex="-1" role="dialog" aria-labelledby="modal-start-job-position-recruitment-modal" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-s" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="modal-start-job-position-recruitment-modal-title">Start Job Position Recruitment</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="modal-body">
                  <form id="start-job-position-recruitment-form" method="post" action="#">
                    <div class="form-group">
                      <label class="form-label" for="menu_item_name">Expected New Employees <span class="text-danger">*</span></label>
                      <input type="number" class="form-control" id="expected_new_employees" name="expected_new_employees" min="1">
                    </div>
                  </form>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                  <button type="submit" class="btn btn-primary" id="submit-start-job-position-recruitment-form" form="start-job-position-recruitment-form">Submit</button>
                </div>
              </div>
            </div>
          </div>';
      }
?>
</div>