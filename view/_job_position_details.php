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
                  $dropdown .= '<li><button class="dropdown-item" type="button" data-bs-toggle="offcanvas" data-bs-target="#start-job-position-recruitment-offcanvas" aria-controls="start-job-position-recruitment-offcanvas" id="start-job-position-recruitment">Start Recruitment</button></li>';
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
      $jobPositionResponsibilityAdd = '<button class="btn btn-warning" type="button" data-bs-toggle="offcanvas" data-bs-target="#job-position-responsibility-offcanvas" aria-controls="job-position-responsibility-offcanvas" id="add-job-position-responsibility">Add Responsibility</button>';
    }

    if($addJobPositionRequirement['total'] > 0){
      $jobPositionRequirementAdd = '<button class="btn btn-warning" type="button" data-bs-toggle="offcanvas" data-bs-target="#job-position-requirement-offcanvas" aria-controls="job-position-requirement-offcanvas" id="add-job-position-requirement">Add Requirement</button>';
    }

    if($addJobPositionQualification['total'] > 0){
      $jobPositionQualificationAdd = '<button class="btn btn-warning" type="button" data-bs-toggle="offcanvas" data-bs-target="#job-position-qualification-offcanvas" aria-controls="job-position-qualification-offcanvas" id="add-job-position-qualification">Add Qualification</button>';
    }

    echo '<div class="col-lg-12">
            <div class="card">
              <div class="card-header">
                <div class="row align-items-center">
                  <div class="col-sm-6">
                    <h5>Responsibility</h5>
                  </div>
                  <div class="col-sm-6 text-sm-end mt-3 mt-sm-0">
                    '. $jobPositionResponsibilityAdd .'
                  </div>
                </div>
              </div>
              <div class="card-body">
                <div class="dt-responsive table-responsive">
                  <table id="job-position-responsibility-table" class="table table-hover table-bordered nowrap w-100 dataTable">
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
                    '. $jobPositionRequirementAdd .'
                  </div>
                </div>
              </div>
              <div class="card-body">
                <div class="dt-responsive table-responsive">
                  <table id="job-position-requirement-table" class="table table-hover table-bordered nowrap w-100 dataTable">
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
                    '. $jobPositionQualificationAdd .'
                  </div>
                </div>
              </div>
              <div class="card-body">
                <div class="dt-responsive table-responsive">
                  <table id="job-position-qualification-table" class="table table-hover table-bordered nowrap w-100 dataTable">
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
    echo '<div class="offcanvas offcanvas-end" tabindex="-1" id="job-position-responsibility-offcanvas" aria-labelledby="job-position-responsibility-offcanvas-label">
            <div class="offcanvas-header">
              <h2 id="job-position-responsibility-offcanvas-label" style="margin-bottom:-0.5rem">Responsibility</h2>
              <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body">
              <div class="alert alert-success alert-dismissible mb-4" role="alert">
                The responsibility records and communicates individual job responsibilities, ensuring clarity and alignment between employees and their roles within the organization.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              </div>
              <div class="row">
                <div class="col-lg-12">
                  <form id="job-position-responsibility-form" method="post" action="#">
                    <div class="form-group">
                      <label class="form-label" for="responsibility">Responsibility <span class="text-danger">*</span></label>
                      <input type="hidden" id="job_position_responsibility_id" name="job_position_responsibility_id">
                      <textarea class="form-control" id="responsibility" name="responsibility" maxlength="1000" rows="5"></textarea>
                    </div>
                  </form>
                </div>
              </div>
              <div class="row">
                <div class="col-lg-12">
                  <button type="submit" class="btn btn-primary" id="submit-job-position-responsibility-form" form="job-position-responsibility-form">Submit</button>
                  <button class="btn btn-light-danger" data-bs-dismiss="offcanvas"> Close </button>
                </div>
              </div>
            </div>
          </div>';
  }

  if($addJobPositionRequirement['total'] > 0 && $updateJobPositionRequirement['total'] > 0){
    echo '<div class="offcanvas offcanvas-end" tabindex="-1" id="job-position-requirement-offcanvas" aria-labelledby="job-position-requirement-offcanvas-label">
            <div class="offcanvas-header">
              <h2 id="job-position-requirement-offcanvas-label" style="margin-bottom:-0.5rem">Requirement</h2>
              <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body">
              <div class="alert alert-success alert-dismissible mb-4" role="alert">
                The requirement records and communicates individual job requirements, ensuring clarity and alignment between employees and their roles within the organization.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              </div>
              <div class="row">
                <div class="col-lg-12">
                  <form id="job-position-requirement-form" method="post" action="#">
                    <div class="form-group">
                      <label class="form-label" for="requirement">Requirement <span class="text-danger">*</span></label>
                      <input type="hidden" id="job_position_requirement_id" name="job_position_requirement_id">
                      <textarea class="form-control" id="requirement" name="requirement" maxlength="1000" rows="5"></textarea>
                    </div>
                  </form>
                </div>
              </div>
              <div class="row">
                <div class="col-lg-12">
                  <button type="submit" class="btn btn-primary" id="submit-job-position-requirement-form" form="job-position-requirement-form">Submit</button>
                  <button class="btn btn-light-danger" data-bs-dismiss="offcanvas"> Close </button>
                </div>
              </div>
            </div>
          </div>';
  }

  if($addJobPositionQualification['total'] > 0 && $updateJobPositionQualification['total'] > 0){
    echo '<div class="offcanvas offcanvas-end" tabindex="-1" id="job-position-qualification-offcanvas" aria-labelledby="job-position-qualification-offcanvas-label">
            <div class="offcanvas-header">
              <h2 id="job-position-qualification-offcanvas-label" style="margin-bottom:-0.5rem">Qualification</h2>
              <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body">
              <div class="alert alert-success alert-dismissible mb-4" role="alert">
                The qualification records and communicates individual job qualifications, ensuring clarity and alignment between employees and their roles within the organization.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              </div>
              <div class="row">
                <div class="col-lg-12">
                  <form id="job-position-qualification-form" method="post" action="#">
                    <div class="form-group">
                      <label class="form-label" for="qualification">Qualification <span class="text-danger">*</span></label>
                      <input type="hidden" id="job_position_qualification_id" name="job_position_qualification_id">
                      <textarea class="form-control" id="qualification" name="qualification" maxlength="1000" rows="5"></textarea>
                    </div>
                  </form>
                </div>
              </div>
              <div class="row">
                <div class="col-lg-12">
                  <button type="submit" class="btn btn-primary" id="submit-job-position-qualification-form" form="job-position-qualification-form">Submit</button>
                  <button class="btn btn-light-danger" data-bs-dismiss="offcanvas"> Close </button>
                </div>
              </div>
            </div>
          </div>';
  }

  if ($startJobPositionRecruitment['total'] > 0 && !$recruitmentStatus) {
    echo '<div class="offcanvas offcanvas-end" tabindex="-1" id="start-job-position-recruitment-offcanvas" aria-labelledby="start-job-position-recruitment-offcanvas-label">
            <div class="offcanvas-header">
              <h2 id="start-job-position-recruitment-offcanvas-label" style="margin-bottom:-0.5rem">Start Job Position Recruitment</h2>
              <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body">
              <div class="alert alert-success alert-dismissible mb-4" role="alert">
                The start job position recruitment designed to initiate the hiring process by specifying the number of new employees needed for a particular job position within the organization.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              </div>
              <div class="row">
                <div class="col-lg-12">
                  <form id="start-job-position-recruitment-form" method="post" action="#">
                    <div class="form-group">
                      <label class="form-label" for="menu_item_name">Expected New Employees <span class="text-danger">*</span></label>
                      <input type="number" class="form-control" id="expected_new_employees" name="expected_new_employees" min="1">
                    </div>
                  </form>
                </div>
              </div>
              <div class="row">
                <div class="col-lg-12">
                  <button type="submit" class="btn btn-primary" id="submit-start-job-position-recruitment-form" form="start-job-position-recruitment-form">Submit</button>
                  <button class="btn btn-light-danger" data-bs-dismiss="offcanvas"> Close </button>
                </div>
              </div>
            </div>
          </div>';
  }
?>
</div>