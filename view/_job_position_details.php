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
  if(!empty($jobPositionID)){
    if($addJobPositionResponsibility['total'] > 0){
      $job_position_responsibility_add = '<button type="button" class="btn btn-warning" id="add-job-position-responsibility">Add Responsibility</button>';
    }

    if($addJobPositionRequirement['total'] > 0){
      $job_position_requirement_add = '<button type="button" class="btn btn-warning" id="add-job-position-requirement">Add Requirement</button>';
    }

    if($addJobPositionQualification['total'] > 0){
      $job_position_qualification_add = '<button type="button" class="btn btn-warning" id="add-job-position-qualification">Add Qualification</button>';
    }

    echo '<div class="col-lg-12">
            <div class="card">
              <div class="card-header">
                <div class="row align-items-center">
                  <div class="col-sm-6">
                    <h5>Responsibility</h5>
                  </div>
                  <div class="col-sm-6 text-sm-end mt-3 mt-sm-0">
                    '. $job_position_responsibility_add .'
                  </div>
                </div>
              </div>
              <div class="card-body">
                <div class="dt-responsive table-responsive">
                  <table id="job-position-responsibility-table" class="table table-striped table-hover table-bordered nowrap w-100 dataTable">
                    <thead>
                      <tr>
                        <th>Responsibility</th>
                        <th>Actions</th>
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
                    <h5>Requirement</h5>
                  </div>
                  <div class="col-sm-6 text-sm-end mt-3 mt-sm-0">
                    '. $job_position_requirement_add .'
                  </div>
                </div>
              </div>
              <div class="card-body">
                <div class="dt-responsive table-responsive">
                  <table id="job-position-requirement-table" class="table table-striped table-hover table-bordered nowrap w-100 dataTable">
                    <thead>
                      <tr>
                        <th>Requirement</th>
                        <th>Actions</th>
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
                    <h5>Qualification</h5>
                  </div>
                  <div class="col-sm-6 text-sm-end mt-3 mt-sm-0">
                    '. $job_position_qualification_add .'
                  </div>
                </div>
              </div>
              <div class="card-body">
                <div class="dt-responsive table-responsive">
                  <table id="job-position-qualification-table" class="table table-striped table-hover table-bordered nowrap w-100 dataTable">
                    <thead>
                      <tr>
                        <th>Qualification</th>
                        <th>Actions</th>
                      </tr>
                    </thead>
                    <tbody></tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>';
  }

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

  if($addJobPositionResponsibility['total'] > 0 && $updateJobPositionResponsibility['total'] > 0){
    echo '<div id="job-position-responsibility-modal" class="modal fade modal-animate anim-fade-in-scale" tabindex="-1" role="dialog" aria-labelledby="job-position-responsibility-modal" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-s" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="job-position-responsibility-modal-title">Responsibility</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="modal-body">
                  <form id="job-position-responsibility-form" method="post" action="#">
                    <div class="form-group">
                      <label class="form-label" for="responsibility">Responsibility <span class="text-danger">*</span></label>
                      <input type="hidden" id="job_position_responsibility_id" name="job_position_responsibility_id">
                      <textarea class="form-control" id="responsibility" name="responsibility" maxlength="1000" rows="5"></textarea>
                    </div>
                  </form>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                  <button type="submit" class="btn btn-primary" id="submit-job-position-responsibility-form" form="job-position-responsibility-form">Submit</button>
                </div>
              </div>
            </div>
          </div>';
  }

  if($addJobPositionRequirement['total'] > 0 && $updateJobPositionRequirement['total'] > 0){
    echo '<div id="job-position-requirement-modal" class="modal fade modal-animate anim-fade-in-scale" tabindex="-1" role="dialog" aria-labelledby="job-position-requirement-modal" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-s" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="job-position-requirement-modal-title">Requirement</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="modal-body">
                  <form id="job-position-requirement-form" method="post" action="#">
                    <div class="form-group">
                      <label class="form-label" for="requirement">Requirement <span class="text-danger">*</span></label>
                      <input type="hidden" id="job_position_requirement_id" name="job_position_requirement_id">
                      <textarea class="form-control" id="requirement" name="requirement" maxlength="1000" rows="5"></textarea>
                    </div>
                  </form>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                  <button type="submit" class="btn btn-primary" id="submit-job-position-requirement-form" form="job-position-requirement-form">Submit</button>
                </div>
              </div>
            </div>
          </div>';
  }

  if($addJobPositionQualification['total'] > 0 && $updateJobPositionQualification['total'] > 0){
    echo '<div id="job-position-qualification-modal" class="modal fade modal-animate anim-fade-in-scale" tabindex="-1" role="dialog" aria-labelledby="job-position-qualification-modal" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-s" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="job-position-qualification-modal-title">Qualification</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="modal-body">
                  <form id="job-position-qualification-form" method="post" action="#">
                    <div class="form-group">
                      <label class="form-label" for="qualification">Qualification <span class="text-danger">*</span></label>
                      <input type="hidden" id="job_position_qualification_id" name="job_position_qualification_id">
                      <textarea class="form-control" id="qualification" name="qualification" maxlength="1000" rows="5"></textarea>
                    </div>
                  </form>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                  <button type="submit" class="btn btn-primary" id="submit-job-position-qualification-form" form="job-position-qualification-form">Submit</button>
                </div>
              </div>
            </div>
          </div>';
  }

  if ($startJobPositionRecruitment['total'] > 0 && !$recruitmentStatus) {
    echo '<div id="start-job-position-recruitment-modal" class="modal fade modal-animate anim-fade-in-scale" tabindex="-1" role="dialog" aria-labelledby="start-job-position-recruitment-modal" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-s" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="start-job-position-recruitment-modal-title">Start Job Position Recruitment</h5>
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