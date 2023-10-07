<div class="col-sm-12">
  <div class="card">
    <div class="card-body py-0">
      <ul class="nav nav-tabs profile-tabs" id="myTab" role="tablist">
        <li class="nav-item">
          <a class="nav-link active" id="profile-tab" data-bs-toggle="tab" href="#profile-tab-content" role="tab" aria-selected="true">
            <i class="ti ti-user me-2"></i>Profile
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" id="personal-information-tab" data-bs-toggle="tab" href="#personal-information" role="tab" aria-selected="true">
            <i class="ti ti-file-text me-2"></i>Personal Details
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" id="employement-details-tab" data-bs-toggle="tab" href="#employement-details" role="tab" aria-selected="true">
            <i class="ti ti-id me-2"></i>Employment Details
          </a>
        </li>
      </ul>
    </div>
  </div>
  <div class="tab-content">
    <div class="tab-pane show active" id="profile-tab-content" role="tabpanel" aria-labelledby="profile-tab">
      <div class="row">

      </div>
    </div>
    <div class="tab-pane" id="personal-information" role="tabpanel" aria-labelledby="personal-information-tab">
      <div class="row">
        <div class="col-lg-6">
          <div class="card">
            <div class="card-header">
              <div class="row align-items-center">
                <div class="col-md-6">
                  <h5>Personal Information</h5>
                </div>
                <div class="col-md-6 text-sm-end mt-3 mt-sm-0">
                 <button type="submit" form="personal-information-form" class="btn btn-success" id="submit-personal-information-data">Save</button>
                </div>
              </div>
            </div>
            <div class="card-body">
              <div class="row">
                <div class="col-sm-12 text-center mb-3">
                  <form class="user-upload mb-4">
                    <img src="<?php echo DEFAULT_AVATAR_IMAGE; ?>" alt="Employee Image" id="emp_image" class="rounded-circle img-fluid wid-70 hei-70">
                    <label for="employee_image" class="img-avtar-upload">
                      <i class="ti ti-camera f-24 mb-1"></i>
                      <span>Upload</span>
                     </label>
                    <input type="file" id="employee_image" name="employee_image" class="d-none">
                  </form>
                </div>
              </div>
              <form id="personal-information-form" method="post" action="#">
                <div class="row">
                  <div class="col-sm-6">
                    <div class="form-group">
                      <label class="form-label">First Name <span class="text-danger">*</span></label>
                      <input type="text" class="form-control" id="first_name" name="first_name" maxlength="300" autocomplete="off">
                    </div>
                  </div>
                  <div class="col-sm-6">
                    <div class="form-group">
                      <label class="form-label">Last Name <span class="text-danger">*</span></label>
                      <input type="text" class="form-control" id="last_name" name="last_name" maxlength="300" autocomplete="off">
                    </div>
                  </div>
                  <div class="col-sm-6">
                    <div class="form-group">
                      <label class="form-label">Middle Name</label>
                      <input type="text" class="form-control" id="middle_name" name="middle_name" maxlength="300" autocomplete="off">
                    </div>
                  </div>
                  <div class="col-sm-6">
                    <div class="form-group">
                      <label class="form-label">Suffix</label>
                      <input type="text" class="form-control" id="suffix" name="suffix" maxlength="10" autocomplete="off">
                    </div>
                  </div>
                  <div class="col-sm-6">
                    <div class="form-group">
                      <label class="form-label">Nickname</label>
                      <input type="text" class="form-control" id="nickname" name="nickname" maxlength="100" autocomplete="off">
                    </div>
                  </div>
                  <div class="col-sm-6">
                    <div class="form-group">
                      <label class="form-label">Birthday <span class="text-danger">*</span></label>
                      <div class="input-group date">
                          <input type="text" class="form-control regular-datepicker" id="birthday" name="birthday">
                          <span class="input-group-text">
                            <i class="feather icon-calendar"></i>
                          </span>
                        </div>
                    </div>
                  </div>
                  <div class="col-sm-6">
                    <div class="form-group">
                      <label class="form-label">Birth Place</label>
                      <input type="text" class="form-control" id="birth_place" name="birth_place" maxlength="1000" autocomplete="off">
                    </div>
                  </div>
                  <div class="col-sm-6">
                    <div class="form-group">
                      <label class="form-label">Gender <span class="text-danger">*</span></label>
                      <select class="form-control select2" name="gender" id="gender">
                        <option value="">--</option>
                        <?php echo $genderModel->generateGenderOptions(); ?>
                      </select>
                    </div>
                  </div>
                  <div class="col-sm-6">
                    <div class="form-group">
                      <label class="form-label">Civil Status <span class="text-danger">*</span></label>
                      <select class="form-control select2" name="civil_status" id="civil_status">
                        <option value="">--</option>
                        <?php echo $civilStatusModel->generateCivilStatusOptions(); ?>
                      </select>
                    </div>
                  </div>
                  <div class="col-sm-6">
                    <div class="form-group">
                      <label class="form-label">Religion</label>
                      <select class="form-control select2" name="religion" id="religion">
                        <option value="">--</option>
                        <?php echo $religionModel->generateReligionOptions(); ?>
                      </select>
                    </div>
                  </div>
                  <div class="col-sm-4">
                    <div class="form-group">
                      <label class="form-label">Blood Type</label>
                      <select class="form-control select2" name="blood_type" id="blood_type">
                        <option value="">--</option>
                        <?php echo $bloodTypeModel->generateBloodTypeOptions(); ?>
                      </select>
                    </div>
                  </div>
                  <div class="col-sm-4">
                    <div class="form-group">
                      <label class="form-label">Height</label>
                      <div class="input-group">
                        <input type="number" min="0" step="0.01" class="form-control" id="height" name="height">
                        <span class="input-group-text">cm</span>
                      </div>
                    </div>
                  </div>
                  <div class="col-sm-4">
                    <div class="form-group">
                      <label class="form-label">Weight</label>
                      <div class="input-group">
                        <input type="number" min="0" step="0.01" class="form-control" id="weight" name="weight">
                        <span class="input-group-text">kg</span>
                      </div>
                    </div>
                  </div>
                  <div class="col-sm-12">
                    <div class="form-group">
                      <label class="form-label">Bio</label>
                      <textarea class="form-control" id="bio" name="bio" maxlength="1000" rows="5"></textarea>
                    </div>
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>
        <div class="col-lg-6">
          <div class="card">
            <div class="card-header">
              <div class="row align-items-center">
                <div class="col-md-6">
                  <h5>Contact Information</h5>
                </div>
                <div class="col-md-6 text-sm-end mt-3 mt-sm-0">
                  <button type="button" class="btn btn-warning" id="add-contact-information">Add</button>
                </div>
              </div>
            </div>
            <div class="card-body">
              <div class="dt-responsive table-responsive">
                <table id="contact-information-table" class="table table-striped table-hover table-bordered nowrap w-100 dataTable">
                  <thead>
                    <tr>
                      <th>Contact Information Type</th>
                      <th>Email</th>
                      <th>Mobile</th>
                      <th>Telephone</th>
                      <th>Actions</th>
                    </tr>
                  </thead>
                  <tbody></tbody>
                </table>
              </div>
            </div>
          </div>
          <div class="card">
            <div class="card-header">
              <div class="row align-items-center">
                <div class="col-md-6">
                  <h5>Employee Address</h5>
                </div>
                <div class="col-md-6 text-sm-end mt-3 mt-sm-0">
                  <button type="button" class="btn btn-warning" id="add-employee-address">Add</button>
                </div>
              </div>
            </div>
            <div class="card-body">
              <div class="dt-responsive table-responsive">
                <table id="employee-address-table" class="table table-striped table-hover table-bordered nowrap w-100 dataTable">
                  <thead>
                    <tr>
                      <th>Address Type</th>
                      <th>Address</th>
                      <th>Actions</th>
                    </tr>
                  </thead>
                  <tbody></tbody>
                </table>
              </div>
            </div>
          </div>
          <div class="card">
            <div class="card-header">
              <div class="row align-items-center">
                <div class="col-md-6">
                  <h5>Employee Identification</h5>
                </div>
                <div class="col-md-6 text-sm-end mt-3 mt-sm-0">
                  <button type="button" class="btn btn-warning" id="add-employee-identification">Add</button>
                </div>
              </div>
            </div>
            <div class="card-body">
              <div class="dt-responsive table-responsive">
                <table id="employee-identification-table" class="table table-striped table-hover table-bordered nowrap w-100 dataTable">
                  <thead>
                    <tr>
                      <th>ID Type</th>
                      <th>ID Number</th>
                      <th>ID Classification</th>
                      <th>Actions</th>
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
                <div class="col-md-6">
                  <h5>Educational Background</h5>
                </div>
                <div class="col-md-6 text-sm-end mt-3 mt-sm-0">
                  <button type="button" class="btn btn-warning" id="add-educational-background">Add</button>
                </div>
              </div>
            </div>
            <div class="card-body">
              <div class="dt-responsive table-responsive">
                <table id="educational-background-table" class="table table-striped table-hover table-bordered nowrap w-100 dataTable">
                  <thead>
                    <tr>
                      <th>Educational Stage</th>
                      <th>Institution Name</th>
                      <th>Degree Earned</th>
                      <th>Field of Study</th>
                      <th>Year Attended</th>
                      <th>Actions</th>
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
        <div class="col-lg-6">
          <div class="card">
            <div class="card-header">
              <div class="row align-items-center">
                <div class="col-md-6">
                  <h5>Family Details</h5>
                </div>
                <div class="col-md-6 text-sm-end mt-3 mt-sm-0">
                  <button type="button" class="btn btn-warning" id="add-family-details">Add</button>
                </div>
              </div>
            </div>
            <div class="card-body">
              <div class="dt-responsive table-responsive">
                <table id="family-details-table" class="table table-striped table-hover table-bordered nowrap w-100 dataTable">
                  <thead>
                    <tr>
                      <th>Name</th>
                      <th>Relationship</th>
                      <th>Birthday</th>
                      <th>School</th>
                      <th>Employment</th>
                      <th>Email</th>
                      <th>Mobile</th>
                      <th>Telephone</th>
                      <th>Actions</th>
                    </tr>
                  </thead>
                  <tbody></tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
        <div class="col-lg-6">
          <div class="card">
            <div class="card-header">
              <div class="row align-items-center">
                <div class="col-md-6">
                  <h5>Emergency Contact</h5>
                </div>
                <div class="col-md-6 text-sm-end mt-3 mt-sm-0">
                  <button type="button" class="btn btn-warning" id="add-emergency-contact">Add</button>
                </div>
              </div>
            </div>
            <div class="card-body">
              <div class="dt-responsive table-responsive">
                <table id="emergency-contact-table" class="table table-striped table-hover table-bordered nowrap w-100 dataTable">
                  <thead>
                    <tr>
                      <th>Name</th>
                      <th>Relationship</th>
                      <th>Email</th>
                      <th>Mobile</th>
                      <th>Telephone</th>
                      <th>Actions</th>
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
                <div class="col-md-6">
                  <h5>Trainings & Seminars</h5>
                </div>
                <div class="col-md-6 text-sm-end mt-3 mt-sm-0">
                  <button type="button" class="btn btn-warning" id="add-tranings-and-seminars">Add</button>
                </div>
              </div>
            </div>
            <div class="card-body">
              <div class="dt-responsive table-responsive">
                <table id="tranings-and-seminars-table" class="table table-striped table-hover table-bordered nowrap w-100 dataTable">
                  <thead>
                    <tr>
                      <th>Traning Name</th>
                      <th>Training Date</th>
                      <th>Training Location</th>
                      <th>Training Provider</th>
                      <th>Actions</th>
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
                <div class="col-md-6">
                  <h5>Employment History</h5>
                </div>
                <div class="col-md-6 text-sm-end mt-3 mt-sm-0">
                  <button type="button" class="btn btn-warning" id="add-employment-history">Add</button>
                </div>
              </div>
            </div>
            <div class="card-body">
              <div class="dt-responsive table-responsive">
                <table id="employment-history-table" class="table table-striped table-hover table-bordered nowrap w-100 dataTable">
                  <thead>
                    <tr>
                      <th>Company</th>
                      <th>Address</th>
                      <th>Contact Numbers</th>
                      <th>Last Position Held</th>
                      <th>Period of Employment</th>
                      <th>Basic Function</th>
                      <th>Starting Salary</th>
                      <th>Final Salary</th>
                      <th>Reason for Separation</th>
                      <th>Immediate Supervisor</th>
                      <th>Position of Immediate Supervisor</th>
                      <th>Actions</th>
                    </tr>
                  </thead>
                  <tbody></tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="tab-pane" id="employement-details" role="tabpanel" aria-labelledby="employement-details-tab">
      <div class="row">
        <div class="col-lg-12"> 
          <div class="card">
            <div class="card-header">
              <div class="row align-items-center">
                <div class="col-md-6">
                  <h5>Employment Details</h5>
                </div>
                <div class="col-md-6 text-sm-end mt-3 mt-sm-0">
                 <button type="submit" form="employment-information-form" class="btn btn-success" id="submit-employment-information-data">Save</button>
                </div>
              </div>
            </div>
            <div class="card-body">
              <form id="employment-information-form" method="post" action="#">
                <div class="row">
                  <div class="col-sm-3">
                    <div class="form-group">
                      <label class="form-label">Badge ID <span class="text-danger">*</span></label>
                      <input type="text" class="form-control" id="badge_id" name="badge_id" maxlength="300" autocomplete="off">
                    </div>
                  </div>
                  <div class="col-sm-3">
                    <div class="form-group">
                      <label class="form-label">Company <span class="text-danger">*</span></label>
                      <select class="form-control select2" name="company_id" id="company_id">
                        <option value="">--</option>
                        <?php echo $companyModel->generateCompanyOptions(); ?>
                      </select>
                    </div>
                  </div>
                  <div class="col-sm-3">
                    <div class="form-group">
                      <label class="form-label">Department <span class="text-danger">*</span></label>
                      <select class="form-control select2" name="department_id" id="department_id">
                        <option value="">--</option>
                        <?php echo $departmentModel->generateDepartmentOptions(); ?>
                      </select>
                    </div>
                  </div>
                  <div class="col-sm-3">
                    <div class="form-group">
                      <label class="form-label">Job Position <span class="text-danger">*</span></label>
                      <select class="form-control select2" name="job_position_id" id="job_position_id">
                        <option value="">--</option>
                        <?php echo $jobPositionModel->generateJobPositionOptions(); ?>
                      </select>
                    </div>
                  </div>
                  <div class="col-sm-4">
                    <div class="form-group">
                      <label class="form-label">Employee Type <span class="text-danger">*</span></label>
                      <select class="form-control select2" name="employee_type_id" id="employee_type_id">
                        <option value="">--</option>
                        <?php echo $employeeTypeModel->generateEmployeeTypeOptions(); ?>
                      </select>
                    </div>
                  </div>
                  <div class="col-sm-4">
                    <div class="form-group">
                      <label class="form-label">Job Level <span class="text-danger">*</span></label>
                      <select class="form-control select2" name="job_level_id" id="job_level_id">
                        <option value="">--</option>
                        <?php echo $jobLevelModel->generateJobLevelOptions(); ?>
                      </select>
                    </div>
                  </div>
                  <div class="col-sm-4">
                    <div class="form-group">
                      <label class="form-label">Branch <span class="text-danger">*</span></label>
                      <select class="form-control select2" name="branch_id" id="branch_id">
                        <option value="">--</option>
                        <?php echo $branchModel->generateBranchOptions(); ?>
                      </select>
                    </div>
                  </div>
                  <div class="col-sm-6">
                    <div class="form-group">
                      <label class="form-label">On-Board Date <span class="text-danger">*</span></label>
                      <div class="input-group date">
                          <input type="text" class="form-control regular-datepicker" id="onboard_date" name="onboard_date">
                          <span class="input-group-text">
                            <i class="feather icon-calendar"></i>
                          </span>
                        </div>
                    </div>
                  </div>
                  <div class="col-sm-6">
                    <div class="form-group">
                      <label class="form-label">Permanency Date</label>
                      <div class="input-group date">
                          <input type="text" class="form-control regular-datepicker" id="permanency_date" name="permanency_date">
                          <span class="input-group-text">
                            <i class="feather icon-calendar"></i>
                          </span>
                        </div>
                    </div>
                  </div>
                </div>
              </form>
            </div>
          </div>
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
                '. $userModel->generateLogNotes('contact', $employeeID) .'
              </div>
            </div>
          </div>
        </div>';
?>
</div>