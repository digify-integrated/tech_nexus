<div class="col-sm-12">
  <div class="card">
    <div class="card-body py-0">
      <ul class="nav nav-tabs profile-tabs" id="myTab" role="tablist">
        <li class="nav-item">
          <a class="nav-link active" id="profile-tab" data-bs-toggle="tab" href="#profile-tab-content" role="tab" aria-selected="true">
            <i class="ti ti-user me-2"></i>Employee Profile
          </a>
        </li>
        <?php
          if($employeeWriteAccess['total'] > 0){
            echo '<li class="nav-item">
                    <a class="nav-link" id="personal-information-tab" data-bs-toggle="tab" href="#personal-information" role="tab" aria-selected="true">
                      <i class="ti ti-file-text me-2"></i>Personal Details
                    </a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" id="employement-details-tab" data-bs-toggle="tab" href="#employement-details" role="tab" aria-selected="true">
                      <i class="ti ti-id me-2"></i>Employment Details
                    </a>
                  </li>';
          }
        ?>
      </ul>
    </div>
  </div>
  <div class="tab-content">
    <div class="tab-pane show active" id="profile-tab-content" role="tabpanel" aria-labelledby="profile-tab">
      <div class="row">
        <div class="col-lg-5 col-xxl-4">
          <div class="card">
            <div class="card-body position-relative">
              <div class="position-absolute end-0 top-0 p-3" id="employee-status-badge"></div>
              <div class="text-center mt-3">
                <div class="chat-avtar d-inline-flex mx-auto">
                  <img class="rounded-circle img-fluid wid-70 hei-70" id="employee_summary_image" src="<?php echo DEFAULT_AVATAR_IMAGE; ?>" alt="Employee image">
                </div>
                <h5 class="mb-0" id="employee_name"></h5>
                <p class="text-muted text-sm" id="job_position"></p>
              </div>
            </div>
          </div>
          <div class="card">
            <div class="card-header">
              <h5>Employment Details</h5>
            </div>
            <div class="card-body">
              <div class="row align-items-center mb-3">
                <div class="col-sm-6 mb-2 mb-sm-0">
                  <p class="mb-0">Badge ID</p>
                </div>
                <div class="col-sm-6">
                  <div class="d-flex align-items-center">
                    <div class="flex-shrink-0 w-100">
                      <p class="mb-0 text-muted text-truncate" id="employee_badge_id"></p>
                    </div>
                  </div>
                </div>
              </div>
              <div class="row align-items-center mb-3">
                <div class="col-sm-6 mb-2 mb-sm-0">
                  <p class="mb-0">Company</p>
                </div>
                <div class="col-sm-6">
                  <div class="d-flex align-items-center">
                    <div class="flex-shrink-0 w-100">
                      <p class="mb-0 text-muted text-truncate" id="employee_company"></p>
                    </div>
                  </div>
                </div>
              </div>
              <div class="row align-items-center mb-3">
                <div class="col-sm-6 mb-2 mb-sm-0">
                  <p class="mb-0">Department</p>
                </div>
                <div class="col-sm-6">
                  <div class="d-flex align-items-center">
                    <div class="flex-shrink-0 w-100">
                      <p class="mb-0 text-muted text-truncate" id="employee_department"></p>
                    </div>
                  </div>
                </div>
              </div>
              <div class="row align-items-center mb-3">
                <div class="col-sm-6 mb-2 mb-sm-0">
                  <p class="mb-0">Employee Type</p>
                </div>
                <div class="col-sm-6">
                  <div class="d-flex align-items-center">
                    <div class="flex-shrink-0 w-100">
                      <p class="mb-0 text-muted text-truncate" id="employee_type"></p>
                    </div>
                  </div>
                </div>
              </div>
              <div class="row align-items-center mb-3">
                <div class="col-sm-6 mb-2 mb-sm-0">
                  <p class="mb-0">Job Level</p>
                </div>
                <div class="col-sm-6">
                  <div class="d-flex align-items-center">
                    <div class="flex-shrink-0 w-100">
                      <p class="mb-0 text-muted text-truncate" id="employee_job_level"></p>
                    </div>
                  </div>
                </div>
              </div>
              <div class="row align-items-center mb-3">
                <div class="col-sm-6 mb-2 mb-sm-0">
                  <p class="mb-0">Branch</p>
                </div>
                <div class="col-sm-6">
                  <div class="d-flex align-items-center">
                    <div class="flex-shrink-0 w-100">
                      <p class="mb-0 text-muted text-truncate" id="employee_branch"></p>
                    </div>
                  </div>
                </div>
              </div>
              <div class="row align-items-center mb-3">
                <div class="col-sm-6 mb-2 mb-sm-0">
                  <p class="mb-0">On-Board Date</p>
                </div>
                <div class="col-sm-6">
                  <div class="d-flex align-items-center">
                    <div class="flex-shrink-0 w-100">
                      <p class="mb-0 text-muted text-truncate" id="employee_onboard_date"></p>
                    </div>
                  </div>
                </div>
              </div>
              <div class="row align-items-center mb-3">
                <div class="col-sm-6 mb-2 mb-sm-0">
                  <p class="mb-0">Permanency Date</p>
                </div>
                <div class="col-sm-6">
                  <div class="d-flex align-items-center">
                    <div class="flex-shrink-0 w-100">
                      <p class="mb-0 text-muted text-truncate" id="employee_permanency_date"></p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-lg-7 col-xxl-8">
          <div class="card">
            <div class="card-header">
              <h5>Employee Bio</h5>
            </div>
            <div class="card-body">
              <p class="mb-0" id="employee_bio"></p>
            </div>
          </div>
          <div class="card">
            <div class="card-header">
              <h5>Personal Details</h5>
            </div>
            <div class="card-body">
              <ul class="list-group list-group-flush">
                <li class="list-group-item px-0 pt-0">
                  <div class="row">
                    <div class="col-md-6">
                      <p class="mb-1 text-muted">Full Name</p>
                      <p class="mb-0" id="full_name"></p>
                    </div>
                    <div class="col-md-6">
                      <p class="mb-1 text-muted">Nickname</p>
                      <p class="mb-0" id="employee_nickname"></p>
                    </div>
                  </div>
                </li>
                <li class="list-group-item px-0">
                  <div class="row">
                    <div class="col-md-6">
                      <p class="mb-1 text-muted">Birthday</p>
                      <p class="mb-0" id="employee_birthday"></p>
                    </div>
                    <div class="col-md-6">
                      <p class="mb-1 text-muted">Birth Place</p>
                      <p class="mb-0" id="employee_birth_place"></p>
                    </div>
                  </div>
                </li>
                <li class="list-group-item px-0">
                  <div class="row">
                    <div class="col-md-6">
                      <p class="mb-1 text-muted">Gender</p>
                      <p class="mb-0" id="employee_gender"></p>
                    </div>
                    <div class="col-md-6">
                      <p class="mb-1 text-muted">Civil Status</p>
                      <p class="mb-0" id="employee_civil_status"></p>
                    </div>
                  </div>
                </li>
                <li class="list-group-item px-0">
                  <div class="row">
                    <div class="col-md-6">
                      <p class="mb-1 text-muted">Religion</p>
                      <p class="mb-0" id="employee_religion"></p>
                    </div>
                    <div class="col-md-6">
                      <p class="mb-1 text-muted">Blood type</p>
                      <p class="mb-0" id="employee_blood_type"></p>
                    </div>
                  </div>
                </li>
                <li class="list-group-item px-0">
                  <div class="row">
                    <div class="col-md-6">
                      <p class="mb-1 text-muted">Height</p>
                      <p class="mb-0" id="employee_height"></p>
                    </div>
                    <div class="col-md-6">
                      <p class="mb-1 text-muted">Weight</p>
                      <p class="mb-0" id="employee_weight"></p>
                    </div>
                  </div>
                </li>
              </ul>
            </div>
          </div>
          <div class="card">
            <div class="card-header">
              <h5>Contact Information</h5>
            </div>
            <div class="card-body">
              <ul class="list-group list-group-flush" id="contact-information-summary">
              </ul>
            </div>
          </div>
          <div class="card">
            <div class="card-header">
              <h5>Address</h5>
            </div>
            <div class="card-body">
              <ul class="list-group list-group-flush" id="contact-address-summary">
              </ul>
            </div>
          </div>
          <div class="card">
            <div class="card-header">
              <h5>Employee Identification</h5>
            </div>
            <div class="card-body">
              <ul class="list-group list-group-flush" id="contact-identification-summary">
              </ul>
            </div>
          </div>
          <div class="card">
            <div class="card-header">
              <h5>Educational Background</h5>
            </div>
            <div class="card-body">
              <ul class="list-group list-group-flush" id="contact-educational-background-summary">
              </ul>
            </div>
          </div>
          <div class="card">
            <div class="card-header">
              <h5>Family Background</h5>
            </div>
            <div class="card-body">
              <ul class="list-group list-group-flush" id="contact-family-background-summary">
              </ul>
            </div>
          </div>
          <div class="card">
            <div class="card-header">
              <h5>Emergency Contact</h5>
            </div>
            <div class="card-body">
              <ul class="list-group list-group-flush" id="contact-emergency-contact-summary">
              </ul>
            </div>
          </div>
        </div>
      </div>
    </div>
    <?php 
       if($employeeWriteAccess['total'] > 0){

        $contactInformationAdd = '';
        if($addEmployeeContactInformation['total'] > 0){
          $contactInformationAdd = '<button type="button" class="btn btn-warning" id="add-contact-information">Add</button>';
        }

        $employeeAddressAdd = '';
        if($addEmployeeAddress['total'] > 0){
          $employeeAddressAdd = '<button type="button" class="btn btn-warning" id="add-contact-address">Add</button>';
        }

        $employeeIdentificationAdd = '';
        if($addEmployeeIdentification['total'] > 0){
          $employeeIdentificationAdd = '<button type="button" class="btn btn-warning" id="add-contact-identification">Add</button>';
        }

        $employeeEducationalBackgroundAdd = '';
        if($addEmployeeEducationalBackground['total'] > 0){
          $employeeEducationalBackgroundAdd = '<button type="button" class="btn btn-warning" id="add-contact-educational-background">Add</button>';
        }

        $employeeFamilyBackgroundAdd = '';
        if($addEmployeeFamilyBackground['total'] > 0){
          $employeeFamilyBackgroundAdd = '<button type="button" class="btn btn-warning" id="add-contact-family-background">Add</button>';
        }

        $employeeEmergencyContactAdd = '';
        if($addEmployeeEmergencyContact['total'] > 0){
          $employeeEmergencyContactAdd = '<button type="button" class="btn btn-warning" id="add-contact-emergency-contact">Add</button>';
        }

        echo '<div class="tab-pane" id="personal-information" role="tabpanel" aria-labelledby="personal-information-tab">
                <div class="row">
                  <div class="col-lg-3">
                    <div class="card">
                      <div class="card-body">
                        <ul class="nav flex-column nav-pills" id="v-personal-information-edit-tab" role="tablist" aria-orientation="vertical">
                          <li><a class="nav-link active" id="v-personal-information-tab" data-bs-toggle="pill" href="#v-personal-information" role="tab" aria-controls="v-personal-information" aria-selected="true">Personal Information</a></li>
                          <li><a class="nav-link" id="v-contact-information-tab" data-bs-toggle="pill" href="#v-contact-information" role="tab" aria-controls="v-contact-information" aria-selected="false">Contact Information</a></li>
                          <li><a class="nav-link" id="v-address-tab" data-bs-toggle="pill" href="#v-address" role="tab" aria-controls="v-address" aria-selected="false">Address</a></li>
                          <li><a class="nav-link" id="v-employee-identification-tab" data-bs-toggle="pill" href="#v-employee-identification" role="tab" aria-controls="v-employee-identification" aria-selected="false">Employee Identification</a></li>
                          <li><a class="nav-link" id="v-educational-background-tab" data-bs-toggle="pill" href="#v-educational-background" role="tab" aria-controls="v-educational-background" aria-selected="false">Educational Background</a></li>
                          <li><a class="nav-link" id="v-family-background-tab" data-bs-toggle="pill" href="#v-family-background" role="tab" aria-controls="v-family-background" aria-selected="false">Family Background</a></li>
                          <li><a class="nav-link" id="v-emergency-contact-tab" data-bs-toggle="pill" href="#v-emergency-contact" role="tab" aria-controls="v-emergency-contact" aria-selected="false">Emergency Contact</a></li>
                          <li><a class="nav-link" id="v-trainings-and-seminars-tab" data-bs-toggle="pill" href="#v-trainings-and-seminars" role="tab" aria-controls="v-trainings-and-seminars" aria-selected="false">Trainings & Seminars</a></li>
                          <li><a class="nav-link" id="v-employment-history-tab" data-bs-toggle="pill" href="#v-employment-history" role="tab" aria-controls="v-employment-history" aria-selected="false">Employment History</a></li>
                          <li><a class="nav-link" id="v-employee-skills-tab" data-bs-toggle="pill" href="#v-employee-skills" role="tab" aria-controls="v-employee-skills" aria-selected="false">Skills</a></li>
                          <li><a class="nav-link" id="v-employee-talents-tab" data-bs-toggle="pill" href="#v-employee-talents" role="tab" aria-controls="v-employee-talents" aria-selected="false">Talents</a></li>
                          <li><a class="nav-link" id="v-employee-hobbies-tab" data-bs-toggle="pill" href="#v-employee-hobbies" role="tab" aria-controls="v-employee-hobbies" aria-selected="false">Hobbies</a></li>
                        </ul>
                      </div>
                    </div>
                  </div>
                  <div class="col-lg-9">
                    <div class="tab-content" id="v-personal-information-edit-tab-content">
                      <div class="tab-pane fade show active" id="v-personal-information" role="tabpanel" aria-labelledby="v-personal-information-tab">
                        <div class="row">
                          <div class="col-lg-12">
                            <div class="card">
                              <div class="card-header">
                                <div class="row align-items-center">
                                  <div class="col-md-6">
                                    <h5>Personal Information</h5>
                                  </div>
                                  <div class="col-md-6 text-sm-end mt-3 mt-sm-0">
                                  <button type="submit" form="v-personal-information" class="btn btn-success" id="submit-personal-information-data">Save</button>
                                  </div>
                                </div>
                              </div>
                              <div class="card-body">
                                <div class="row">
                                  <div class="col-sm-12 text-center mb-3">
                                    <form class="user-upload mb-4">
                                      <img src="'. DEFAULT_AVATAR_IMAGE .'" alt="Employee Image" id="emp_image" class="rounded-circle img-fluid wid-70 hei-70">
                                      <label for="employee_image" class="img-avtar-upload">
                                        <i class="ti ti-camera f-24 mb-1"></i>
                                        <span>Upload</span>
                                      </label>
                                      <input type="file" id="employee_image" name="employee_image" class="d-none">
                                    </form>
                                  </div>
                                </div>
                                <form id="v-personal-information" method="post" action="#">
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
                                            <input type="text" class="form-control regular-datepicker" id="birthday" name="birthday" autocomplete="off">
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
                                          '. $genderModel->generateGenderOptions() .'
                                        </select>
                                      </div>
                                    </div>
                                    <div class="col-sm-6">
                                      <div class="form-group">
                                        <label class="form-label">Civil Status <span class="text-danger">*</span></label>
                                        <select class="form-control select2" name="civil_status" id="civil_status">
                                          <option value="">--</option>
                                          '. $civilStatusModel->generateCivilStatusOptions() .'
                                        </select>
                                      </div>
                                    </div>
                                    <div class="col-sm-6">
                                      <div class="form-group">
                                        <label class="form-label">Religion</label>
                                        <select class="form-control select2" name="religion" id="religion">
                                          <option value="">--</option>
                                          '. $religionModel->generateReligionOptions() .'
                                        </select>
                                      </div>
                                    </div>
                                    <div class="col-sm-4">
                                      <div class="form-group">
                                        <label class="form-label">Blood Type</label>
                                        <select class="form-control select2" name="blood_type" id="blood_type">
                                          <option value="">--</option>
                                          '. $bloodTypeModel->generateBloodTypeOptions() .'
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
                        </div>
                      </div>
                      <div class="tab-pane fade" id="v-contact-information" role="tabpanel" aria-labelledby="v-contact-information-tab">
                        <div class="row">
                          <div class="col-lg-12">
                            <div class="card">
                              <div class="card-header">
                                <div class="row align-items-center">
                                  <div class="col-md-6">
                                    <h5>Contact Information</h5>
                                  </div>
                                  <div class="col-md-6 text-sm-end mt-3 mt-sm-0">
                                    '. $contactInformationAdd .'
                                  </div>
                                </div>
                              </div>
                              <div class="card-body">
                                <div class="dt-responsive table-responsive">
                                  <table id="contact-information-table" class="table table-hover table-bordered nowrap w-100 dataTable">
                                    <thead>
                                      <tr>
                                        <th>Contact Information</th>
                                        <th>Email</th>
                                        <th>Mobile</th>
                                        <th>Telephone</th>
                                        <th>Status</th>
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
                      <div class="tab-pane fade" id="v-address" role="tabpanel" aria-labelledby="v-address-tab">
                        <div class="row">
                          <div class="col-lg-12">
                            <div class="card">
                              <div class="card-header">
                                <div class="row align-items-center">
                                  <div class="col-md-6">
                                    <h5>Address</h5>
                                  </div>
                                  <div class="col-md-6 text-sm-end mt-3 mt-sm-0">
                                    '. $employeeAddressAdd .'
                                  </div>
                                </div>
                              </div>
                              <div class="card-body">
                                <div class="dt-responsive table-responsive">
                                  <table id="contact-address-table" class="table table-hover table-bordered nowrap w-100 dataTable">
                                    <thead>
                                      <tr>
                                        <th>Address Type</th>
                                        <th>Address</th>
                                        <th>Status</th>
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
                      <div class="tab-pane fade" id="v-employee-identification" role="tabpanel" aria-labelledby="v-employee-identification-tab">
                        <div class="row">
                          <div class="col-lg-12">
                            <div class="card">
                              <div class="card-header">
                                <div class="row align-items-center">
                                  <div class="col-md-6">
                                    <h5>Employee Identification</h5>
                                  </div>
                                  <div class="col-md-6 text-sm-end mt-3 mt-sm-0">
                                    '. $employeeIdentificationAdd .'
                                  </div>
                                </div>
                              </div>
                              <div class="card-body">
                                <div class="dt-responsive table-responsive">
                                  <table id="contact-identification-table" class="table table-hover table-bordered nowrap w-100 dataTable">
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
                      </div>
                      <div class="tab-pane fade" id="v-educational-background" role="tabpanel" aria-labelledby="v-educational-background-tab">
                        <div class="row">
                          <div class="col-lg-12">
                            <div class="card">
                              <div class="card-header">
                                <div class="row align-items-center">
                                  <div class="col-md-6">
                                    <h5>Educational Background</h5>
                                  </div>
                                  <div class="col-md-6 text-sm-end mt-3 mt-sm-0">
                                    '. $employeeEducationalBackgroundAdd .'
                                  </div>
                                </div>
                              </div>
                              <div class="card-body">
                                <div class="dt-responsive table-responsive">
                                  <table id="contact-educational-background-table" class="table table-hover table-bordered nowrap w-100 dataTable">
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
                      </div>
                      <div class="tab-pane fade" id="v-family-background" role="tabpanel" aria-labelledby="v-family-background-tab">
                        <div class="row">
                          <div class="col-lg-12">
                            <div class="card">
                              <div class="card-header">
                                <div class="row align-items-center">
                                  <div class="col-md-6">
                                    <h5>Family Background</h5>
                                  </div>
                                  <div class="col-md-6 text-sm-end mt-3 mt-sm-0">
                                    '. $employeeFamilyBackgroundAdd .'
                                  </div>
                                </div>
                              </div>
                              <div class="card-body">
                                <div class="dt-responsive table-responsive">
                                  <table id="contact-family-background-table" class="table table-hover table-bordered nowrap w-100 dataTable">
                                    <thead>
                                      <tr>
                                        <th>Name</th>
                                        <th>Relationship</th>
                                        <th>Birthday</th>
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
                      </div>
                      <div class="tab-pane fade" id="v-emergency-contact" role="tabpanel" aria-labelledby="v-emergency-contact-tab">
                        <div class="row">
                          <div class="col-lg-12">
                            <div class="card">
                              <div class="card-header">
                                <div class="row align-items-center">
                                  <div class="col-md-6">
                                    <h5>Emergency Contact</h5>
                                  </div>
                                  <div class="col-md-6 text-sm-end mt-3 mt-sm-0">
                                    '. $employeeEmergencyContactAdd .'
                                  </div>
                                </div>
                              </div>
                              <div class="card-body">
                                <div class="dt-responsive table-responsive">
                                  <table id="contact-emergency-contact-table" class="table table-hover table-bordered nowrap w-100 dataTable">
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
                      </div>
                      <div class="tab-pane fade" id="v-trainings-and-seminars" role="tabpanel" aria-labelledby="v-trainings-and-seminars-tab">
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
                                  <table id="tranings-and-seminars-table" class="table table-hover table-bordered nowrap w-100 dataTable">
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
                      </div>
                      <div class="tab-pane fade" id="v-employment-history" role="tabpanel" aria-labelledby="v-employment-history-tab">
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
                                  <table id="employment-history-table" class="table table-hover table-bordered nowrap w-100 dataTable">
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
                      <div class="tab-pane fade" id="v-employee-skills" role="tabpanel" aria-labelledby="v-employee-skills-tab">
                        <div class="row">
                          <div class="col-lg-12">
                            <div class="card">
                              <div class="card-header">
                                <div class="row align-items-center">
                                  <div class="col-md-6">
                                    <h5>Skills</h5>
                                  </div>
                                  <div class="col-md-6 text-sm-end mt-3 mt-sm-0">
                                    <button type="button" class="btn btn-warning" id="add-employee-skills">Add</button>
                                  </div>
                                </div>
                              </div>
                              <div class="card-body">
                                <div class="dt-responsive table-responsive">
                                  <table id="contact-skills-table" class="table table-hover table-bordered nowrap w-100 dataTable">
                                    <thead>
                                      <tr>
                                        <th>Skills</th>
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
                      <div class="tab-pane fade" id="v-employee-talents" role="tabpanel" aria-labelledby="v-employee-talents-tab">
                        <div class="row">
                          <div class="col-lg-12">
                            <div class="card">
                              <div class="card-header">
                                <div class="row align-items-center">
                                  <div class="col-md-6">
                                    <h5>Talents</h5>
                                  </div>
                                  <div class="col-md-6 text-sm-end mt-3 mt-sm-0">
                                    <button type="button" class="btn btn-warning" id="add-employee-talents">Add</button>
                                  </div>
                                </div>
                              </div>
                              <div class="card-body">
                                <div class="dt-responsive table-responsive">
                                  <table id="contact-talents-table" class="table table-hover table-bordered nowrap w-100 dataTable">
                                    <thead>
                                      <tr>
                                        <th>Talents</th>
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
                      <div class="tab-pane fade" id="v-employee-hobbies" role="tabpanel" aria-labelledby="v-employee-talents-tab">
                        <div class="row">
                          <div class="col-lg-12">
                            <div class="card">
                              <div class="card-header">
                                <div class="row align-items-center">
                                  <div class="col-md-6">
                                    <h5>Hobbies</h5>
                                  </div>
                                  <div class="col-md-6 text-sm-end mt-3 mt-sm-0">
                                    <button type="button" class="btn btn-warning" id="add-employee-hobbies">Add</button>
                                  </div>
                                </div>
                              </div>
                              <div class="card-body">
                                <div class="dt-responsive table-responsive">
                                  <table id="contact-talents-table" class="table table-hover table-bordered nowrap w-100 dataTable">
                                    <thead>
                                      <tr>
                                        <th>Hobbies</th>
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
                                  '. $companyModel->generateCompanyOptions() .'
                                </select>
                              </div>
                            </div>
                            <div class="col-sm-3">
                              <div class="form-group">
                                <label class="form-label">Department <span class="text-danger">*</span></label>
                                <select class="form-control select2" name="department_id" id="department_id">
                                  <option value="">--</option>
                                  '. $departmentModel->generateDepartmentOptions() .'
                                </select>
                              </div>
                            </div>
                            <div class="col-sm-3">
                              <div class="form-group">
                                <label class="form-label">Job Position <span class="text-danger">*</span></label>
                                <select class="form-control select2" name="job_position_id" id="job_position_id">
                                  <option value="">--</option>
                                  '. $jobPositionModel->generateJobPositionOptions() .'
                                </select>
                              </div>
                            </div>
                            <div class="col-sm-4">
                              <div class="form-group">
                                <label class="form-label">Employee Type <span class="text-danger">*</span></label>
                                <select class="form-control select2" name="employee_type_id" id="employee_type_id">
                                  <option value="">--</option>
                                  '. $employeeTypeModel->generateEmployeeTypeOptions() .'
                                </select>
                              </div>
                            </div>
                            <div class="col-sm-4">
                              <div class="form-group">
                                <label class="form-label">Job Level <span class="text-danger">*</span></label>
                                <select class="form-control select2" name="job_level_id" id="job_level_id">
                                  <option value="">--</option>
                                  '. $jobLevelModel->generateJobLevelOptions() .'
                                </select>
                              </div>
                            </div>
                            <div class="col-sm-4">
                              <div class="form-group">
                                <label class="form-label">Branch <span class="text-danger">*</span></label>
                                <select class="form-control select2" name="branch_id" id="branch_id">
                                  <option value="">--</option>
                                  '. $branchModel->generateBranchOptions() .'
                                </select>
                              </div>
                            </div>
                            <div class="col-sm-6">
                              <div class="form-group">
                                <label class="form-label">On-Board Date <span class="text-danger">*</span></label>
                                <div class="input-group date">
                                    <input type="text" class="form-control regular-datepicker" id="onboard_date" name="onboard_date" autocomplete="off">
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
                                    <input type="text" class="form-control regular-datepicker" id="permanency_date" name="permanency_date" autocomplete="off">
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
              </div>';

              if($addEmployeeContactInformation['total'] > 0 || $updateEmployeeContactInformation ['total'] > 0){
                echo '<div id="contact-information-modal" class="modal fade modal-animate anim-fade-in-scale" tabindex="-1" role="dialog" aria-labelledby="contact-information-modal-title" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg" role="document">
                          <div class="modal-content">
                            <div class="modal-header">
                              <h5 class="modal-title" id="contact-information-modal-title">Contact Information</h5>
                              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body" id="modal-body">
                              <form id="contact-information-form" method="post" action="#">
                                <div class="form-group">
                                  <label class="form-label">Contact Information Type <span class="text-danger">*</span></label>
                                  <input type="hidden" id="contact_information_id" name="contact_information_id">
                                  <select class="form-control modal-select2" name="contact_information_type_id" id="contact_information_type_id">
                                    <option value="">--</option>
                                    '. $contactInformationTypeModel->generateContactInformationTypeOptions() .'
                                  </select>
                                </div>
                                <div class="form-group">
                                  <label class="form-label" for="contact_information_email">Email</label>
                                  <input type="email" class="form-control" id="contact_information_email" name="contact_information_email" maxlength="100" autocomplete="off">
                                </div>
                                <div class="form-group">
                                  <label class="form-label" for="contact_information_mobile">Mobile</label>
                                  <input type="text" class="form-control" id="contact_information_mobile" name="contact_information_mobile" maxlength="20" autocomplete="off">
                                </div>
                                <div class="form-group">
                                  <label class="form-label" for="contact_information_telephone">Telephone</label>
                                  <input type="text" class="form-control" id="contact_information_telephone" name="contact_information_telephone" maxlength="20" autocomplete="off">
                                </div>
                              </form>
                            </div>
                            <div class="modal-footer">
                              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                              <button type="submit" class="btn btn-primary" id="submit-contact-information" form="contact-information-form">Submit</button>
                            </div>
                          </div>
                        </div>
                      </div>';
              }

              if($addEmployeeAddress['total'] > 0 || $updateEmployeeAddress['total'] > 0){
                echo '<div id="contact-address-modal" class="modal fade modal-animate anim-fade-in-scale" tabindex="-1" role="dialog" aria-labelledby="contact-address-modal-title" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg" role="document">
                          <div class="modal-content">
                            <div class="modal-header">
                              <h5 class="modal-title" id="contact-address-modal-title">Address</h5>
                              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body" id="modal-body">
                              <form id="contact-address-form" method="post" action="#">
                                <div class="form-group">
                                  <label class="form-label">Address Type <span class="text-danger">*</span></label>
                                  <input type="hidden" id="contact_address_id" name="contact_address_id">
                                  <select class="form-control modal-select2" name="address_type_id" id="address_type_id">
                                    <option value="">--</option>
                                    '. $addressTypeModel->generateAddressTypeOptions() .'
                                  </select>
                                </div>
                                <div class="form-group">
                                  <label class="form-label" for="contact_address">Address <span class="text-danger">*</span></label>
                                  <input type="text" class="form-control" id="contact_address" name="contact_address" maxlength="1000" autocomplete="off">
                                </div>
                                <div class="form-group">
                                  <label class="form-label" for="contact_information_email">City <span class="text-danger">*</span></label>
                                  <select class="form-control modal-select2" name="contact_address_city_id" id="contact_address_city_id">
                                  <option value="">--</option>
                                  '. $cityModel->generateCityOptions() .'
                                  </select>
                                </div>
                              </form>
                            </div>
                            <div class="modal-footer">
                              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                              <button type="submit" class="btn btn-primary" id="submit-contact-address" form="contact-address-form">Submit</button>
                            </div>
                          </div>
                        </div>
                      </div>';
              }

              if($addEmployeeIdentification['total'] > 0 || $updateEmployeeIdentification['total'] > 0){
                echo '<div id="contact-identification-modal" class="modal fade modal-animate anim-fade-in-scale" tabindex="-1" role="dialog" aria-labelledby="contact-identification-modal-title" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg" role="document">
                          <div class="modal-content">
                            <div class="modal-header">
                              <h5 class="modal-title" id="contact-identification-modal-title">Employe Identification</h5>
                              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body" id="modal-body">
                              <form id="contact-identification-form" method="post" action="#">
                                <div class="form-group">
                                  <label class="form-label">Address Type <span class="text-danger">*</span></label>
                                  <input type="hidden" id="contact_identification_id" name="contact_identification_id">
                                  <select class="form-control modal-select2" name="id_type_id" id="id_type_id">
                                    <option value="">--</option>
                                    '. $idTypeModel->generateIDTypeOptions() .'
                                  </select>
                                </div>
                                <div class="form-group">
                                  <label class="form-label" for="contact_id_number">ID Number <span class="text-danger">*</span></label>
                                  <input type="text" class="form-control" id="contact_id_number" name="contact_id_number" maxlength="100" autocomplete="off">
                                </div>
                              </form>
                            </div>
                            <div class="modal-footer">
                              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                              <button type="submit" class="btn btn-primary" id="submit-contact-identification" form="contact-identification-form">Submit</button>
                            </div>
                          </div>
                        </div>
                      </div>';
              }

              if($addEmployeeEducationalBackground['total'] > 0 || $updateEmployeeEducationalBackground['total'] > 0){
                echo '<div id="contact-educational-background-modal" class="modal fade modal-animate anim-fade-in-scale" tabindex="-1" role="dialog" aria-labelledby="contact-educational-background-modal-title" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg" role="document">
                          <div class="modal-content">
                            <div class="modal-header">
                              <h5 class="modal-title" id="contact-educational-background-modal-title">Employe Identification</h5>
                              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body" id="modal-body">
                              <form id="contact-educational-background-form" method="post" action="#">
                                <div class="form-group">
                                  <label class="form-label">Educational Stage <span class="text-danger">*</span></label>
                                  <input type="hidden" id="contact_educational_background_id" name="contact_educational_background_id">
                                  <select class="form-control modal-select2" name="educational_stage_id" id="educational_stage_id">
                                    <option value="">--</option>
                                    '. $educationalStageModel->generateEducationalStageOptions() .'
                                  </select>
                                </div>
                                <div class="form-group">
                                  <label class="form-label" for="contact_institution_name">Institution Name <span class="text-danger">*</span></label>
                                  <input type="text" class="form-control" id="contact_institution_name" name="contact_institution_name" maxlength="500" autocomplete="off">
                                </div>
                                <div class="form-group">
                                  <label class="form-label" for="contact_degree_earned">Degree Earned</label>
                                  <input type="text" class="form-control" id="contact_degree_earned" name="contact_degree_earned" maxlength="500" autocomplete="off">
                                </div>
                                <div class="form-group">
                                  <label class="form-label" for="contact_field_of_study">Field of Study</label>
                                  <input type="text" class="form-control" id="contact_field_of_study" name="contact_field_of_study" maxlength="500" autocomplete="off">
                                </div>
                                <div class="form-group">
                                  <label class="form-label">Start Date <span class="text-danger">*</span></label>
                                  <div class="input-group date">
                                    <input type="text" class="form-control regular-datepicker" id="contact_start_date_attended" name="contact_start_date_attended">
                                    <span class="input-group-text">
                                      <i class="feather icon-calendar"></i>
                                    </span>
                                  </div>
                                </div>
                                <div class="form-group">
                                  <label class="form-label">End Date</label>
                                  <div class="input-group date">
                                    <input type="text" class="form-control regular-datepicker" id="contact_end_date_attended" name="contact_end_date_attended">
                                    <span class="input-group-text">
                                      <i class="feather icon-calendar"></i>
                                    </span>
                                  </div>
                                </div>
                              </form>
                            </div>
                            <div class="modal-footer">
                              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                              <button type="submit" class="btn btn-primary" id="submit-contact-educational-background" form="contact-educational-background-form">Submit</button>
                            </div>
                          </div>
                        </div>
                      </div>';
              }

              if($addEmployeeFamilyBackground['total'] > 0 || $updateEmployeeFamilyBackground['total'] > 0){
                echo '<div id="contact-family-background-modal" class="modal fade modal-animate anim-fade-in-scale" tabindex="-1" role="dialog" aria-labelledby="contact-family-background-modal-title" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg" role="document">
                          <div class="modal-content">
                            <div class="modal-header">
                              <h5 class="modal-title" id="contact-family-background-modal-title">Family Background</h5>
                              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body" id="modal-body">
                              <form id="contact-family-background-form" method="post" action="#">
                                <div class="form-group">
                                  <label class="form-label" for="family_name">Family Name <span class="text-danger">*</span></label>
                                  <input type="hidden" id="contact_family_background_id" name="contact_family_background_id">
                                  <input type="text" class="form-control" id="family_name" name="family_name" maxlength="500" autocomplete="off">
                                </div>
                                <div class="form-group">
                                  <label class="form-label">Relation <span class="text-danger">*</span></label>
                                  <select class="form-control modal-select2" name="family_background_relation_id" id="family_background_relation_id">
                                    <option value="">--</option>
                                    '. $relationModel->generateRelationOptions() .'
                                  </select>
                                </div>
                                <div class="form-group">
                                  <label class="form-label">Birthday <span class="text-danger">*</span></label>
                                  <div class="input-group date">
                                    <input type="text" class="form-control regular-datepicker" id="family_background_birthday" name="family_background_birthday">
                                    <span class="input-group-text">
                                      <i class="feather icon-calendar"></i>
                                    </span>
                                  </div>
                                </div>
                                <div class="form-group">
                                  <label class="form-label" for="family_background_email">Email</label>
                                  <input type="email" class="form-control" id="family_background_email" name="family_background_email" maxlength="100" autocomplete="off">
                                </div>
                                <div class="form-group">
                                  <label class="form-label" for="family_background_mobile">Mobile</label>
                                  <input type="text" class="form-control" id="family_background_mobile" name="family_background_mobile" maxlength="20" autocomplete="off">
                                </div>
                                <div class="form-group">
                                  <label class="form-label" for="family_background_telephone">Telephone</label>
                                  <input type="text" class="form-control" id="family_background_telephone" name="family_background_telephone" maxlength="20" autocomplete="off">
                                </div>
                              </form>
                            </div>
                            <div class="modal-footer">
                              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                              <button type="submit" class="btn btn-primary" id="submit-contact-family-background" form="contact-family-background-form">Submit</button>
                            </div>
                          </div>
                        </div>
                      </div>';
              }

              if($addEmployeeEmergencyContact['total'] > 0 || $updateEmployeeEmergencyContact['total'] > 0){
                echo '<div id="contact-emergency-contact-modal" class="modal fade modal-animate anim-fade-in-scale" tabindex="-1" role="dialog" aria-labelledby="contact-emergency-contact-modal-title" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg" role="document">
                          <div class="modal-content">
                            <div class="modal-header">
                              <h5 class="modal-title" id="contact-emergency-contact-modal-title">Energency Contact</h5>
                              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body" id="modal-body">
                              <form id="contact-emergency-contact-form" method="post" action="#">
                                <div class="form-group">
                                  <label class="form-label" for="emergency_contact_name">Emergency Contact Name <span class="text-danger">*</span></label>
                                  <input type="hidden" id="contact_emergency_contact_id" name="contact_emergency_contact_id">
                                  <input type="text" class="form-control" id="emergency_contact_name" name="emergency_contact_name" maxlength="500" autocomplete="off">
                                </div>
                                <div class="form-group">
                                  <label class="form-label">Relation <span class="text-danger">*</span></label>
                                  <select class="form-control modal-select2" name="emergency_contact_relation_id" id="emergency_contact_relation_id">
                                    <option value="">--</option>
                                    '. $relationModel->generateRelationOptions() .'
                                  </select>
                                </div>
                                <div class="form-group">
                                  <label class="form-label" for="emergency_contact_email">Email</label>
                                  <input type="email" class="form-control" id="emergency_contact_email" name="emergency_contact_email" maxlength="100" autocomplete="off">
                                </div>
                                <div class="form-group">
                                  <label class="form-label" for="emergency_contact_mobile">Mobile</label>
                                  <input type="text" class="form-control" id="emergency_contact_mobile" name="emergency_contact_mobile" maxlength="20" autocomplete="off">
                                </div>
                                <div class="form-group">
                                  <label class="form-label" for="emergency_contact_telephone">Telephone</label>
                                  <input type="text" class="form-control" id="emergency_contact_telephone" name="emergency_contact_telephone" maxlength="20" autocomplete="off">
                                </div>
                              </form>
                            </div>
                            <div class="modal-footer">
                              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                              <button type="submit" class="btn btn-primary" id="submit-contact-emergency-contact" form="contact-emergency-contact-form">Submit</button>
                            </div>
                          </div>
                        </div>
                      </div>';
              }
       }
    ?>
  </div>
</div>




</div>