<?php
  $employmentDetails = $employeeModel->getEmploymentInformation($employeeID);
  $employmentStatus = $employmentDetails['employment_status'];

  $deactivateEmployee = $userModel->checkSystemActionAccessRights($user_id, 180);
  $portalAccessButton = '';
  if($grantPortalAccess['total'] > 0 && !$portalAccess){
    $portalAccessButton = '<button type="button" class="btn btn-outline-success w-100 text-center mb-2" id="grant-portal-access">Grant Portal Access</button>';
  }

  if($revokePortalAccess['total'] > 0 && $portalAccess){
    $portalAccessButton = '<button type="button" class="btn btn-outline-danger w-100 text-center mb-2" id="revoke-portal-access">Revoke Portal Access</button>';
  }

  $deactivateButton = '';
  if($deactivateEmployee['total'] > 0 && $employmentStatus){
    $deactivateButton = '<button type="button" class="btn btn-outline-warning w-100 text-center mb-2" data-bs-toggle="offcanvas" data-bs-target="#archive-employee-offcanvas" aria-controls="archive-employee-offcanvas" id="deactivate-employee">Archive Employee</button>';
  }

  $sendWelcomeEmail = '';
  if($employmentStatus){
    $sendWelcomeEmail = '<button type="button" class="btn btn-outline-success w-100 text-center mb-2" id="send-welcome-email">Send Welcome Email</button>';
  }

  if($employeeWriteAccess['total'] > 0){
    $employeePersonalInformationUpdate = '<button class="btn btn-icon btn-link-success" type="button" data-bs-toggle="offcanvas" data-bs-target="#personal-information-offcanvas" aria-controls="personal-information-offcanvas" id="update-personal-information"><i class="ti ti-pencil"></i></button>';
    $employeeEmploymentInformationUpdate = '<button class="btn btn-icon btn-link-success" type="button" data-bs-toggle="offcanvas" data-bs-target="#employment-information-offcanvas" aria-controls="employment-information-offcanvas" id="update-employment-information"><i class="ti ti-pencil"></i></button>';

    $contactInformationAdd = '';
    if($addEmployeeContactInformation['total'] > 0){
      $contactInformationAdd = '<button class="btn btn-icon btn-link-success" type="button" data-bs-toggle="offcanvas" data-bs-target="#contact-information-offcanvas" aria-controls="contact-information-offcanvas" id="add-contact-information"><i class="ti ti-plus"></i></button>';
    }

    $employeeAddressAdd = '';
    if($addEmployeeAddress['total'] > 0){
      $employeeAddressAdd = '<button class="btn btn-icon btn-link-success" type="button" data-bs-toggle="offcanvas" data-bs-target="#contact-address-offcanvas" aria-controls="contact-address-offcanvas" id="add-contact-address"><i class="ti ti-plus"></i></button>';
    }

    $employeeIdentificationAdd = '';
    if($addEmployeeIdentification['total'] > 0){
      $employeeIdentificationAdd = '<button class="btn btn-icon btn-link-success" type="button" data-bs-toggle="offcanvas" data-bs-target="#contact-identification-offcanvas" aria-controls="contact-identification-offcanvas" id="add-contact-identification"><i class="ti ti-plus"></i></button>';
    }

    $employeeEducationalBackgroundAdd = '';
    if($addEmployeeEducationalBackground['total'] > 0){
      $employeeEducationalBackgroundAdd = '<button class="btn btn-icon btn-link-success" type="button" data-bs-toggle="offcanvas" data-bs-target="#contact-educational-background-offcanvas" aria-controls="contact-educational-background-offcanvas" id="add-educational-background"><i class="ti ti-plus"></i></button>';
    }

    $employeeFamilyBackgroundAdd = '';
    if($addEmployeeFamilyBackground['total'] > 0){
      $employeeFamilyBackgroundAdd = '<button class="btn btn-icon btn-link-success" type="button" data-bs-toggle="offcanvas" data-bs-target="#contact-family-background-offcanvas" aria-controls="contact-family-background-offcanvas" id="add-contact-family-background"><i class="ti ti-plus"></i></button>';
    }

    $employeeEmergencyContactAdd = '';
    if($addEmployeeEmergencyContact['total'] > 0){
      $employeeEmergencyContactAdd = '<button class="btn btn-icon btn-link-success" type="button" data-bs-toggle="offcanvas" data-bs-target="#contact-emergency-contact-offcanvas" aria-controls="contact-emergency-contact-offcanvas" id="add-contact-emergency-contact"><i class="ti ti-plus"></i></button>';
    }

    $employeeTrainingsSeminarsAdd = '';
    if($addEmployeeTrainingsSeminars['total'] > 0){
      $employeeTrainingsSeminarsAdd = '<button class="btn btn-icon btn-link-success" type="button" data-bs-toggle="offcanvas" data-bs-target="#contact-training-offcanvas" aria-controls="contact-training-offcanvas" id="add-contact-training"><i class="ti ti-plus"></i></button>';
    }

    $employeeSkillsAdd = '';
    if($addEmployeeSkills['total'] > 0){
      $employeeSkillsAdd = '<button class="btn btn-icon btn-link-success" type="button" data-bs-toggle="offcanvas" data-bs-target="#contact-skills-offcanvas" aria-controls="contact-skills-offcanvas" id="add-contact-skills"><i class="ti ti-plus"></i></button>';
    }

    $employeeTalentsAdd = '';
    if($addEmployeeTalents['total'] > 0){
      $employeeTalentsAdd = '<button class="btn btn-icon btn-link-success" type="button" data-bs-toggle="offcanvas" data-bs-target="#contact-talents-offcanvas" aria-controls="contact-talents-offcanvas" id="add-contact-talents"><i class="ti ti-plus"></i></button>';
    }

    $employeeHobbyAdd = '';
    if($addEmployeeHobby['total'] > 0){
      $employeeHobbyAdd = '<button class="btn btn-icon btn-link-success" type="button" data-bs-toggle="offcanvas" data-bs-target="#contact-hobby-offcanvas" aria-controls="contact-hobby-offcanvas" id="add-contact-hobby"><i class="ti ti-plus"></i></button>';
    }

    $employeeEmploymentHistoryAdd = '';
    if($addEmploymentHistory['total'] > 0){
      $employeeEmploymentHistoryAdd = '<button class="btn btn-icon btn-link-success" type="button" data-bs-toggle="offcanvas" data-bs-target="#contact-employment-history-offcanvas" aria-controls="contact-employment-history-offcanvas" id="add-contact-employment-history"><i class="ti ti-plus"></i></button>';
    }

    $employeeLicenseAdd = '';
    if($addEmployeeLicense['total'] > 0){
      $employeeLicenseAdd = '<button class="btn btn-icon btn-link-success" type="button" data-bs-toggle="offcanvas" data-bs-target="#contact-employee-license-offcanvas" aria-controls="contact-employee-license-offcanvas" id="add-contact-employee-license"><i class="ti ti-plus"></i></button>';
    }

    $employeeLanguageAdd = '';
    if($addEmployeeLanguage['total'] > 0){
      $employeeLanguageAdd = '<button class="btn btn-icon btn-link-success" type="button" data-bs-toggle="offcanvas" data-bs-target="#contact-employee-language-offcanvas" aria-controls="contact-employee-language-offcanvas" id="add-contact-employee-language"><i class="ti ti-plus"></i></button>';
    }

    $employeeBankAdd = '';
    if($addEmployeeBank['total'] > 0){
      $employeeBankAdd = '<button class="btn btn-icon btn-link-success" type="button" data-bs-toggle="offcanvas" data-bs-target="#contact-employee-bank-offcanvas" aria-controls="contact-employee-bank-offcanvas" id="add-contact-employee-bank"><i class="ti ti-plus"></i></button>';
    }

    $employeeDocumentAdd = '';
    if($addEmployeeDocument['total'] > 0){
      $employeeDocumentAdd = '<button class="btn btn-icon btn-link-success" type="button" data-bs-toggle="offcanvas" data-bs-target="#contact-employee-document-offcanvas" aria-controls="contact-employee-document-offcanvas" id="add-contact-employee-document"><i class="ti ti-plus"></i></button>';
    }
  }
?>
<div class="row">
  <div class="col-lg-3">
    <div class="card">
      <div class="card-body position-relative">
        <div class="position-absolute end-0 top-0 p-3" id="employee-status-badge"></div>
        <div class="mt-3">
          <div class="chat-avtar d-inline-flex mx-auto">
            <?php
              if($employeeWriteAccess['total'] > 0){
                echo '<form class="user-upload mb-4">
                        <img src="'. DEFAULT_AVATAR_IMAGE .'" alt="Employee Image" id="emp_image" class="rounded-circle img-fluid wid-70 hei-70">
                        <label for="employee_image" class="img-avtar-upload"><i class="ti ti-camera f-24 mb-1"></i><span>Upload</span></label>
                        <input type="file" id="employee_image" name="employee_image" class="d-none">
                      </form>';
              }
              else{
                echo '<img src="'. DEFAULT_AVATAR_IMAGE .'" alt="Employee Image" id="emp_image" class="rounded-circle img-fluid wid-70 hei-70">';
              }
            ?>
          </div>
          <h5 class="mb-1 text-primary" id="employee_name"></h5>
          <p class="text-sm" id="job_position"></p>
          <p class="mb-4" id="employee_bio"></p>
          <?php echo $portalAccessButton; ?>
          <?php echo $deactivateButton; ?>
          <?php echo $sendWelcomeEmail; ?>
          <button type="button" class="btn btn-outline-info w-100 text-center mb-0" data-bs-toggle="offcanvas" data-bs-target="#contact-employee-qr-code-offcanvas" aria-controls="contact-employee-qr-code-offcanvas" id="generate-employee-qr-code"><i class="ti ti-qrcode me-1"></i>QR Code</button>
        </div>
      </div>
    </div>
    <div class="card">
      <div class="card-header">
        <div class="d-flex align-items-center justify-content-between">
          <h5>Employment Info</h5>
          <?php echo $employeeEmploymentInformationUpdate; ?>
        </div>
      </div>
      <div class="card-body" id="employment-information-summary"></div>
    </div>
  </div>
  <div class="col-lg-9">
    <div class="card">
      <div class="card-body">
        <ul class="nav nav-pills" id="pills-tab" role="tablist">
          <li class="nav-item">
            <a class="nav-link active" id="profile-tab" data-bs-toggle="tab" href="#profile-tab-content" role="tab" aria-selected="true">
              Employee Profile
            </a>
          </li>
        </ul>
      </div>
    </div>
    <div class="tab-content">
      <div class="tab-pane show active" id="profile-tab-content" role="tabpanel" aria-labelledby="profile-tab">
        <div class="row">
          <div class="col-md-4 col-sm-12">
            <div class="card">
              <div class="card-body">
                <div class="row">
                  <div class="col-md-12">
                    <ul class="nav flex-column nav-pills" id="v-employee-profile-tab" role="tablist" aria-orientation="vertical">
                      <li><a class="nav-link active" id="v-employee-profile-basic-information-tab" data-bs-toggle="pill" href="#v-employee-profile-basic-information" role="tab" aria-controls="v-employee-profile-basic-information" aria-selected="true">Personal Information</a></li>
                      <li><a class="nav-link" id="v-employee-profile-address-tab" data-bs-toggle="pill" href="#v-employee-profile-address" role="tab" aria-controls="v-employee-profile-address" aria-selected="false">Address</a></li>
                      <li><a class="nav-link" id="v-employee-profile-contact-information-tab" data-bs-toggle="pill" href="#v-employee-profile-contact-information" role="tab" aria-controls="v-employee-profile-contact-information" aria-selected="false">Contact Information</a></li>
                      <li><a class="nav-link" id="v-employee-profile-employee-identification-tab" data-bs-toggle="pill" href="#v-employee-profile-employee-identification" role="tab" aria-controls="v-employee-profile-employee-identification" aria-selected="false">Employee Identification</a></li>
                      <li><a class="nav-link" id="v-employee-profile-employee-document-tab" data-bs-toggle="pill" href="#v-employee-profile-employee-document" role="tab" aria-controls="v-employee-profile-employee-document" aria-selected="false">Employee Document</a></li>
                      <li><a class="nav-link" id="v-employee-profile-bank-account-tab" data-bs-toggle="pill" href="#v-employee-profile-bank-account" role="tab" aria-controls="v-employee-profile-bank-account" aria-selected="false">Bank Account</a></li>
                      <li><a class="nav-link" id="v-employee-profile-emergency-contact-tab" data-bs-toggle="pill" href="#v-employee-profile-emergency-contact" role="tab" aria-controls="v-employee-profile-emergency-contact" aria-selected="false">Emergency Contact</a></li>
                      <li><a class="nav-link" id="v-employee-profile-family-background-tab" data-bs-toggle="pill" href="#v-employee-profile-family-background" role="tab" aria-controls="v-employee-profile-family-background" aria-selected="false">Family Background</a></li>
                      <li><a class="nav-link" id="v-employee-profile-educational-background-tab" data-bs-toggle="pill" href="#v-employee-profile-educational-background" role="tab" aria-controls="v-employee-profile-educational-background" aria-selected="false">Educational Background</a></li>
                      <li><a class="nav-link" id="v-employee-profile-employment-history-tab" data-bs-toggle="pill" href="#v-employee-profile-employment-history" role="tab" aria-controls="v-employee-profile-employment-history" aria-selected="false">Employment History</a></li>
                      <li><a class="nav-link" id="v-employee-profile-training-seminars-tab" data-bs-toggle="pill" href="#v-employee-profile-training-seminars" role="tab" aria-controls="v-employee-profile-training-seminars" aria-selected="false">Trainings & Seminars</a></li>
                      <li><a class="nav-link" id="v-employee-profile-licenses-certifications-tab" data-bs-toggle="pill" href="#v-employee-profile-licenses-certifications" role="tab" aria-controls="v-employee-profile-licenses-certifications" aria-selected="false">Licenses & Certifications</a></li>
                      <li><a class="nav-link" id="v-employee-profile-languages-tab" data-bs-toggle="pill" href="#v-employee-profile-languages" role="tab" aria-controls="v-employee-profile-languages" aria-selected="false">Languages</a></li>
                      <li><a class="nav-link" id="v-employee-profile-skills-tab" data-bs-toggle="pill" href="#v-employee-profile-skills" role="tab" aria-controls="v-employee-profile-skills" aria-selected="false">Skills</a></li>
                      <li><a class="nav-link" id="v-employee-profile-talents-tab" data-bs-toggle="pill" href="#v-employee-profile-talents" role="tab" aria-controls="v-employee-profile-talents" aria-selected="false">Talents</a></li>
                      <li><a class="nav-link" id="v-employee-profile-hobbies-tab" data-bs-toggle="pill" href="#v-employee-profile-hobbies" role="tab" aria-controls="v-employee-profile-hobbies" aria-selected="false">Hobbies</a></li>
                    </ul>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-md-8 col-sm-12">
            <div class="tab-content" id="v-pills-employee-profile-basic-information">
              <div class="tab-pane fade show active" id="v-employee-profile-basic-information" role="tabpanel" aria-labelledby="v-employee-profile-basic-information-tab">
                <div class="row">
                  <div class="col-md-12">
                    <div class="card">
                      <div class="card-header">
                        <div class="d-flex align-items-center justify-content-between">
                          <h5>Personal Details</h5>
                          <?php echo $employeePersonalInformationUpdate; ?>
                        </div>
                      </div>
                      <div class="card-body" id="personal-information-summary"></div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="tab-pane fade" id="v-employee-profile-address" role="tabpanel" aria-labelledby="v-employee-profile-address-tab">
                <div class="card">
                  <div class="card-header">
                    <div class="d-flex align-items-center justify-content-between">
                      <h5>Address</h5>
                      <?php echo $employeeAddressAdd; ?>
                    </div>
                  </div>
                  <div class="card-body">
                    <ul class="list-group list-group-flush border-top-0" id="contact-address-summary">
                    </ul>
                  </div>
                </div>
              </div>
              <div class="tab-pane fade" id="v-employee-profile-contact-information" role="tabpanel" aria-labelledby="v-employee-profile-contact-information-tab">
                <div class="card">
                  <div class="card-header">
                    <div class="d-flex align-items-center justify-content-between">
                      <h5>Contact Information</h5>
                      <?php echo $contactInformationAdd; ?>
                    </div>
                  </div>
                  <div class="card-body">
                    <ul class="list-group list-group-flush" id="contact-information-summary"></ul>
                  </div>
                </div>
              </div>
              <div class="tab-pane fade" id="v-employee-profile-employee-identification" role="tabpanel" aria-labelledby="v-employee-profile-employee-identification-tab">
                <div class="card">
                  <div class="card-header">
                    <div class="d-flex align-items-center justify-content-between">
                      <h5>Employee Identification</h5>
                      <?php echo $employeeIdentificationAdd; ?>
                    </div>
                  </div>
                  <div class="card-body">
                    <ul class="list-group list-group-flush" id="contact-identification-summary">
                    </ul>
                  </div>
                </div>
              </div>
              <div class="tab-pane fade" id="v-employee-profile-employee-document" role="tabpanel" aria-labelledby="v-employee-profile-employee-document-tab">
                <div class="card">
                  <div class="card-header">
                    <div class="d-flex align-items-center justify-content-between">
                      <h5>Employee Document</h5>
                      <?php echo $employeeDocumentAdd; ?>
                    </div>
                  </div>
                  <div class="card-body">
                    <ul class="list-group list-group-flush" id="contact-document-summary">
                    </ul>
                  </div>
                </div>
              </div>
              <div class="tab-pane fade" id="v-employee-profile-bank-account" role="tabpanel" aria-labelledby="v-employee-profile-bank-account-tab">
                <div class="card">
                  <div class="card-header">
                    <div class="d-flex align-items-center justify-content-between">
                      <h5>Bank Account</h5>
                      <?php echo $employeeBankAdd; ?>
                    </div>
                  </div>
                  <div class="card-body">
                    <ul class="list-group list-group-flush" id="contact-employee-bank-summary"></ul>
                  </div>
                </div>
              </div>
              <div class="tab-pane fade" id="v-employee-profile-emergency-contact" role="tabpanel" aria-labelledby="v-employee-profile-emergency-contact-tab">
                <div class="card">
                  <div class="card-header">
                    <div class="d-flex align-items-center justify-content-between">
                      <h5>Emergency Contact</h5>
                      <?php echo $employeeEmergencyContactAdd; ?>
                    </div>
                  </div>
                  <div class="card-body">
                    <ul class="list-group list-group-flush" id="contact-emergency-contact-summary">
                    </ul>
                  </div>
                </div>
              </div>
              <div class="tab-pane fade" id="v-employee-profile-family-background" role="tabpanel" aria-labelledby="v-employee-profile-family-background-tab">
                <div class="card">
                  <div class="card-header">
                    <div class="d-flex align-items-center justify-content-between">
                      <h5>Family Background</h5>
                      <?php echo $employeeFamilyBackgroundAdd; ?>
                    </div>
                  </div>
                  <div class="card-body">
                    <ul class="list-group list-group-flush" id="contact-family-background-summary">
                    </ul>
                  </div>
                </div>
              </div>
              <div class="tab-pane fade" id="v-employee-profile-educational-background" role="tabpanel" aria-labelledby="v-employee-profile-educational-background-tab">
                <div class="card">
                  <div class="card-header">
                    <div class="d-flex align-items-center justify-content-between">
                      <h5>Educational Background</h5>
                      <?php echo $employeeEducationalBackgroundAdd; ?>
                    </div>
                  </div>
                  <div class="card-body">
                    <ul class="list-group list-group-flush" id="contact-educational-background-summary">
                    </ul>
                  </div>
                </div>
              </div>
              <div class="tab-pane fade" id="v-employee-profile-employment-history" role="tabpanel" aria-labelledby="v-employee-profile-employment-history-tab">
                <div class="card">
                  <div class="card-header">
                    <div class="d-flex align-items-center justify-content-between">
                      <h5>Employment History</h5>
                      <?php echo $employeeEmploymentHistoryAdd; ?>
                    </div>
                  </div>
                  <div class="card-body">
                    <ul class="list-group list-group-flush" id="contact-employment-history-summary"></ul>
                  </div>
                </div>
              </div>
              <div class="tab-pane fade" id="v-employee-profile-training-seminars" role="tabpanel" aria-labelledby="v-employee-profile-training-seminars-tab">
                <div class="card">
                  <div class="card-header">
                    <div class="d-flex align-items-center justify-content-between">
                      <h5>Trainings & Seminars</h5>
                      <?php echo $employeeTrainingsSeminarsAdd; ?>
                    </div>
                  </div>
                  <div class="card-body">
                    <ul class="list-group list-group-flush" id="contact-training-summary">
                    </ul>
                  </div>
                </div>
              </div>
              <div class="tab-pane fade" id="v-employee-profile-licenses-certifications" role="tabpanel" aria-labelledby="v-employee-profile-licenses-certifications-tab">
                <div class="card">
                  <div class="card-header">
                    <div class="d-flex align-items-center justify-content-between">
                      <h5>Licenses & Certifications</h5>
                      <?php echo $employeeLicenseAdd; ?>
                    </div>
                  </div>
                  <div class="card-body">
                    <ul class="list-group list-group-flush" id="contact-employee-license-summary">
                    </ul>
                  </div>
                </div>
              </div>
              <div class="tab-pane fade" id="v-employee-profile-languages" role="tabpanel" aria-labelledby="v-employee-profile-languages-tab">
                <div class="card">
                  <div class="card-header">
                    <div class="d-flex align-items-center justify-content-between">
                      <h5>Languages</h5>
                      <?php echo $employeeLanguageAdd; ?>
                    </div>
                  </div>
                  <div class="card-body">
                    <ul class="list-group list-group-flush" id="contact-employee-language-summary"></ul>              
                  </div>
                </div>
              </div>
              <div class="tab-pane fade" id="v-employee-profile-skills" role="tabpanel" aria-labelledby="v-employee-profile-skills-tab">
                <div class="card">
                  <div class="card-header">
                    <div class="d-flex align-items-center justify-content-between">
                      <h5>Skills</h5>
                      <?php echo $employeeSkillsAdd; ?>
                    </div>
                  </div>
                  <div class="card-body">
                    <ul class="list-group list-group-flush" id="contact-skills-summary"></ul>
                  </div>
                </div>
              </div>
              <div class="tab-pane fade" id="v-employee-profile-talents" role="tabpanel" aria-labelledby="v-employee-profile-talents-tab">
                <div class="card">
                  <div class="card-header">
                    <div class="d-flex align-items-center justify-content-between">
                      <h5>Talents</h5>
                      <?php echo $employeeTalentsAdd; ?>
                    </div>
                  </div>
                  <div class="card-body">
                    <ul class="list-group list-group-flush" id="contact-talents-summary"></ul>
                  </div>
                </div>
              </div>
              <div class="tab-pane fade" id="v-employee-profile-hobbies" role="tabpanel" aria-labelledby="v-employee-profile-hobbies-tab">
                <div class="card">
                  <div class="card-header">
                    <div class="d-flex align-items-center justify-content-between">
                      <h5>Hobbies</h5>
                      <?php echo $employeeHobbyAdd; ?>
                    </div>
                  </div>
                  <div class="card-body">
                    <ul class="list-group list-group-flush" id="contact-hobby-summary"></ul>              
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <?php 
        if($employeeWriteAccess['total'] > 0){
          echo '<div class="offcanvas offcanvas-end" tabindex="-1" id="personal-information-offcanvas" aria-labelledby="personal-information-offcanvas-label">
                  <div class="offcanvas-header">
                    <h2 id="personal-information-offcanvas-label" style="margin-bottom:-0.5rem">Personal Information</h2>
                    <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                  </div>
                  <div class="offcanvas-body">
                    <div class="alert alert-success alert-dismissible mb-4" role="alert">
                      This form is used to collect and record essential personal details, ensuring accuracy and completeness in an individual\'s profile within an organization or database. Users can update information such as name, date of birth, and physical attributes.
                      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    <div class="row">
                      <div class="col-lg-12">
                        <form id="personal-information-form" method="post" action="#">
                          <div class="form-group row">
                            <div class="col-lg-6">
                              <label class="form-label">First Name <span class="text-danger">*</span></label>
                              <input type="text" class="form-control" id="first_name" name="first_name" maxlength="300" autocomplete="off">
                            </div>
                            <div class="col-lg-6 mt-3 mt-lg-0">
                              <label class="form-label">Last Name <span class="text-danger">*</span></label>
                              <input type="text" class="form-control" id="last_name" name="last_name" maxlength="300" autocomplete="off">
                            </div>
                          </div>
                          <div class="form-group row">
                            <div class="col-lg-6">
                              <label class="form-label">Middle Name</label>
                              <input type="text" class="form-control" id="middle_name" name="middle_name" maxlength="300" autocomplete="off">
                            </div>
                            <div class="col-lg-6 mt-3 mt-lg-0">
                              <label class="form-label">Suffix</label>
                              <input type="text" class="form-control" id="suffix" name="suffix" maxlength="10" autocomplete="off">
                            </div>
                          </div>
                          <div class="form-group row">
                            <div class="col-lg-6">
                              <label class="form-label">Nickname</label>
                              <input type="text" class="form-control" id="nickname" name="nickname" maxlength="100" autocomplete="off">
                            </div>
                            <div class="col-lg-6 mt-3 mt-lg-0">
                              <label class="form-label">Birthday <span class="text-danger">*</span></label>
                              <div class="input-group date">
                                <input type="text" class="form-control regular-datepicker" id="birthday" name="birthday" autocomplete="off">
                                <span class="input-group-text">
                                  <i class="feather icon-calendar"></i>
                                </span>
                              </div>
                            </div>
                          </div>
                          <div class="form-group row">
                            <div class="col-lg-6">
                              <label class="form-label">Birth Place</label>
                              <input type="text" class="form-control" id="birth_place" name="birth_place" maxlength="1000" autocomplete="off">
                            </div>
                            <div class="col-lg-6 mt-3 mt-lg-0">
                              <label class="form-label">Gender <span class="text-danger">*</span></label>
                              <select class="form-control offcanvas-select2" name="gender" id="gender">
                                <option value="">--</option>
                                '. $genderModel->generateGenderOptions() .'
                              </select>
                            </div>
                          </div>
                          <div class="form-group row">
                            <div class="col-lg-6">
                              <label class="form-label">Civil Status <span class="text-danger">*</span></label>
                              <select class="form-control offcanvas-select2" name="civil_status" id="civil_status">
                                <option value="">--</option>
                                '. $civilStatusModel->generateCivilStatusOptions() .'
                              </select>
                            </div>
                            <div class="col-lg-6 mt-3 mt-lg-0">
                              <label class="form-label">Religion</label>
                              <select class="form-control offcanvas-select2" name="religion" id="religion">
                                <option value="">--</option>
                                '. $religionModel->generateReligionOptions() .'
                              </select>
                            </div>
                          </div>
                          <div class="form-group row">
                            <div class="col-lg-4">
                              <label class="form-label">Blood Type</label>
                              <select class="form-control offcanvas-select2" name="blood_type" id="blood_type">
                                <option value="">--</option>
                                '. $bloodTypeModel->generateBloodTypeOptions() .'
                              </select>
                            </div>
                            <div class="col-lg-4 mt-3 mt-lg-0">
                              <label class="form-label">Height</label>
                              <div class="input-group">
                                <input type="number" min="0" step="0.01" class="form-control" id="height" name="height">
                                <span class="input-group-text">cm</span>
                              </div>
                            </div>
                            <div class="col-lg-4 mt-3 mt-lg-0">
                              <label class="form-label">Weight</label>
                              <div class="input-group">
                                <input type="number" min="0" step="0.01" class="form-control" id="weight" name="weight">
                                <span class="input-group-text">kg</span>
                              </div>
                            </div>
                          </div>
                          <div class="form-group row">
                            <div class="col-lg-12">
                              <label class="form-label">Personal Summary</label>
                              <textarea class="form-control" id="bio" name="bio" maxlength="1000" rows="5"></textarea>
                            </div>
                          </div>
                        </form>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-lg-12">
                        <button type="submit" class="btn btn-primary" id="submit-personal-information-data" form="personal-information-form">Submit</button>
                        <button class="btn btn-light-danger" data-bs-dismiss="offcanvas"> Close </button>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="offcanvas offcanvas-end" tabindex="-1" id="employment-information-offcanvas" aria-labelledby="employment-information-offcanvas-label">
                  <div class="offcanvas-header">
                    <h2 id="employment-information-offcanvas-label" style="margin-bottom:-0.5rem">Employment Information</h2>
                    <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                  </div>
                  <div class="offcanvas-body">
                    <div class="alert alert-success alert-dismissible mb-4" role="alert">
                      This form is used to collect and update essential employee details, such as ID number, company, department, job position, employee type, job level, branch, and on-board date, to ensure accurate and current records within the organization.
                      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    <div class="row">
                      <div class="col-lg-12">
                        <form id="employment-information-form" method="post" action="#">
                          <div class="form-group row">
                            <div class="col-lg-6">
                              <label class="form-label">ID Number <span class="text-danger">*</span></label>
                              <input type="text" class="form-control" id="badge_id" name="badge_id" maxlength="300" autocomplete="off">
                            </div>
                            <div class="col-lg-6 mt-3 mt-lg-0">
                              <label class="form-label">Company <span class="text-danger">*</span></label>
                              <select class="form-control offcanvas-select2" name="company_id" id="company_id">
                                <option value="">--</option>
                                '. $companyModel->generateCompanyOptions() .'
                              </select>
                            </div>
                          </div>
                          <div class="form-group row">
                            <div class="col-lg-6">
                              <label class="form-label">Department <span class="text-danger">*</span></label>
                              <select class="form-control offcanvas-select2" name="department_id" id="department_id">
                                <option value="">--</option>
                                '. $departmentModel->generateDepartmentOptions() .'
                              </select>
                            </div>
                            <div class="col-lg-6 mt-3 mt-lg-0">
                              <label class="form-label">Job Position <span class="text-danger">*</span></label>
                              <select class="form-control offcanvas-select2" name="job_position_id" id="job_position_id">
                                <option value="">--</option>
                                '. $jobPositionModel->generateJobPositionOptions() .'
                              </select>
                            </div>
                          </div>
                          <div class="form-group row">
                            <div class="col-lg-6">
                              <label class="form-label">Employee Type <span class="text-danger">*</span></label>
                              <select class="form-control offcanvas-select2" name="employee_type_id" id="employee_type_id">
                                <option value="">--</option>
                                '. $employeeTypeModel->generateEmployeeTypeOptions() .'
                              </select>
                            </div>
                            <div class="col-lg-6 mt-3 mt-lg-0">
                              <label class="form-label">Job Level</label>
                              <select class="form-control offcanvas-select2" name="job_level_id" id="job_level_id">
                                <option value="">--</option>
                                '. $jobLevelModel->generateJobLevelOptions() .'
                              </select>
                            </div>
                          </div>
                          <div class="form-group row">
                            <div class="col-lg-6">
                              <label class="form-label">Branch <span class="text-danger">*</span></label>
                              <select class="form-control offcanvas-select2" name="branch_id" id="branch_id">
                                <option value="">--</option>
                                '. $branchModel->generateBranchOptions() .'
                              </select>
                            </div>
                            <div class="col-lg-6 mt-3 mt-lg-0">
                              <label class="form-label">On-Board Date <span class="text-danger">*</span></label>
                              <div class="input-group date">
                                <input type="text" class="form-control regular-datepicker" id="onboard_date" name="onboard_date" autocomplete="off">
                                <span class="input-group-text">
                                  <i class="feather icon-calendar"></i>
                                </span>
                              </div>
                            </div>
                          </div>
                          <div class="form-group row">
                            <div class="col-lg-6">
                              <label class="form-label">Manager</label>
                              <select class="form-control offcanvas-select2" name="manager_id" id="manager_id">
                                <option value="">--</option>
                                '. $employeeModel->generateEmployeeOptions('all') .'
                              </select>
                            </div>
                            <div class="col-lg-6">
                              <label class="form-label">Leave Approver <span class="text-danger">*</span></label>
                              <select class="form-control offcanvas-select2" name="leave_approver_id" id="leave_approver_id">
                                <option value="">--</option>
                                '. $employeeModel->generateEmployeeOptions('all') .'
                              </select>
                            </div>
                          </div>
                          <div class="form-group row">
                            <div class="col-lg-6 mt-3 mt-lg-0">
                              <label class="form-label">Work Schedule <span class="text-danger">*</span></label>
                              <select class="form-control offcanvas-select2" name="work_schedule_id" id="work_schedule_id">
                                <option value="">--</option>
                                '. $workScheduleModel->generateWorkScheduleOptions() .'
                              </select>
                            </div>
                            <div class="col-lg-6">
                              <label class="form-label">Biometrics ID</label>
                              <input type="text" class="form-control" id="biometrics_id" name="biometrics_id" maxlength="300" autocomplete="off">
                            </div>
                          </div>
                          <div class="form-group row">
                            <div class="col-lg-6">
                              <label class="form-label">Kiosk Pin Code</label>
                              <input type="text" class="form-control" id="kiosk_pin_code" name="kiosk_pin_code" maxlength="6" autocomplete="off">
                            </div>
                          </div>
                        </form>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-lg-12">
                        <button type="submit" class="btn btn-primary" id="submit-employment-information-data" form="employment-information-form">Submit</button>
                        <button class="btn btn-light-danger" data-bs-dismiss="offcanvas"> Close </button>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="offcanvas offcanvas-end" tabindex="-1" id="contact-employee-qr-code-offcanvas" aria-labelledby="contact-employee-qr-code-offcanvas-label">
                  <div class="offcanvas-header">
                    <h2 id="contact-employee-qr-code-offcanvas-label" style="margin-bottom:-0.5rem">QR Code</h2>
                    <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                  </div>
                  <div class="offcanvas-body">
                    <div class="alert alert-success alert-dismissible mb-4" role="alert">
                      This QR code serves as a secure and efficient means of identity verification and access control within our organization. Its primary purpose is to enhance the overall security and streamline various operational processes.
                      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    <div class="row mb-4 text-center">
                      <div class="col-lg-12" id="employee-qr-code-container"></div>
                    </div>
                    <div class="row">
                      <div class="col-lg-12">
                        <button class="btn btn-light-danger" data-bs-dismiss="offcanvas"> Close </button>
                      </div>
                    </div>
                  </div>
                </div>';

                if($addEmployeeContactInformation['total'] > 0 || $updateEmployeeContactInformation ['total'] > 0){
                  echo '<div class="offcanvas offcanvas-end" tabindex="-1" id="contact-information-offcanvas" aria-labelledby="contact-information-offcanvas-label">
                          <div class="offcanvas-header">
                            <h2 id="contact-information-offcanvas-label" style="margin-bottom:-0.5rem">Contact Information</h2>
                            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                          </div>
                          <div class="offcanvas-body">
                            <div class="alert alert-success alert-dismissible mb-4" role="alert">
                              The Employee Contact Information collects essential contact details, including Email, Mobile, and Telephone numbers, to maintain accurate and up-to-date communication records for employees.
                              <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                            <div class="row">
                              <div class="col-lg-12">
                                <form id="contact-information-form" method="post" action="#">
                                  <div class="form-group row">
                                    <div class="col-lg-12">
                                      <label class="form-label">Contact Information Type <span class="text-danger">*</span></label>
                                      <input type="hidden" id="contact_information_id" name="contact_information_id">
                                      <select class="form-control offcanvas-select2" name="contact_information_type_id" id="contact_information_type_id">
                                        <option value="">--</option>
                                        '. $contactInformationTypeModel->generateContactInformationTypeOptions() .'
                                      </select>
                                    </div>
                                  </div>
                                  <div class="form-group row">
                                    <div class="col-lg-12">
                                      <label class="form-label" for="contact_information_facebook">Facebook <span class="text-danger">*</span></label>
                                      <input type="text" class="form-control" id="contact_information_facebook" name="contact_information_facebook" maxlength="300" autocomplete="off">
                                    </div>
                                  </div>
                                  <div class="form-group row">
                                    <div class="col-lg-12">
                                      <label class="form-label" for="contact_information_email">Email</label>
                                      <input type="email" class="form-control" id="contact_information_email" name="contact_information_email" maxlength="100" autocomplete="off">
                                    </div>
                                  </div>
                                  <div class="form-group row">
                                    <div class="col-lg-6">
                                      <label class="form-label" for="contact_information_mobile">Mobile</label>
                                      <input type="text" class="form-control" id="contact_information_mobile" name="contact_information_mobile" maxlength="11" autocomplete="off">
                                    </div>
                                    <div class="col-lg-6 mt-3 mt-lg-0">
                                      <label class="form-label" for="contact_information_telephone">Telephone</label>
                                      <input type="text" class="form-control" id="contact_information_telephone" name="contact_information_telephone" maxlength="20" autocomplete="off">
                                    </div>
                                  </div>
                                </form>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-lg-12">
                                <button type="submit" class="btn btn-primary" id="submit-contact-information" form="contact-information-form">Submit</button>
                                <button class="btn btn-light-danger" data-bs-dismiss="offcanvas"> Close </button>
                              </div>
                            </div>
                          </div>
                        </div>';
                }

                if($addEmployeeAddress['total'] > 0 || $updateEmployeeAddress['total'] > 0){
                  echo '<div class="offcanvas offcanvas-end" tabindex="-1" id="contact-address-offcanvas" aria-labelledby="contact-address-offcanvas-label">
                          <div class="offcanvas-header">
                            <h2 id="contact-address-offcanvas-label" style="margin-bottom:-0.5rem">Address</h2>
                            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                          </div>
                          <div class="offcanvas-body">
                            <div class="alert alert-success alert-dismissible mb-4" role="alert">
                              The Employee Address collects essential address information, including Address Type (e.g., home or work), the Address itself, and the associated City, to maintain accurate records for employee address.
                              <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                            <div class="row">
                              <div class="col-lg-12">
                                <form id="contact-address-form" method="post" action="#">
                                  <div class="form-group row">
                                    <div class="col-lg-12">
                                      <label class="form-label">Address Type <span class="text-danger">*</span></label>
                                      <input type="hidden" id="contact_address_id" name="contact_address_id">
                                      <select class="form-control offcanvas-select2" name="address_type_id" id="address_type_id">
                                        <option value="">--</option>
                                        '. $addressTypeModel->generateAddressTypeOptions() .'
                                      </select>
                                    </div>
                                  </div>
                                  <div class="form-group row">
                                    <div class="col-lg-12">
                                      <label class="form-label" for="contact_address">Address <span class="text-danger">*</span></label>
                                      <input type="text" class="form-control" id="contact_address" name="contact_address" maxlength="1000" autocomplete="off">
                                    </div>
                                  </div>
                                  <div class="form-group row">
                                    <div class="col-lg-12">
                                      <label class="form-label" for="city_id">City <span class="text-danger">*</span></label>
                                      <select class="form-control offcanvas-select2" name="city_id" id="city_id">
                                      <option value="">--</option>
                                      '. $cityModel->generateCityOptions() .'
                                      </select>
                                    </div>
                                  </div>
                                </form>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-lg-12">
                                <button type="submit" class="btn btn-primary" id="submit-contact-address" form="contact-address-form">Submit</button>
                                <button class="btn btn-light-danger" data-bs-dismiss="offcanvas"> Close </button>
                              </div>
                            </div>
                          </div>
                        </div>';
                }

                if($addEmployeeDocument['total'] > 0 ){
                  echo '<div class="offcanvas offcanvas-end" tabindex="-1" id="contact-employee-document-offcanvas" aria-labelledby="contact-employee-document-offcanvas-label">
                          <div class="offcanvas-header">
                            <h2 id="contact-employee-document-offcanvas-label" style="margin-bottom:-0.5rem">Employee Document</h2>
                            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                          </div>
                          <div class="offcanvas-body">
                            <div class="row">
                              <div class="col-lg-12">
                                <form id="contact-document-form" method="post" action="#">
                                <div class="form-group row">
                                  <div class="col-lg-12">
                                    <label class="form-label">Document Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="employee_document_name" name="employee_document_name" maxlength="100" autocomplete="off">
                                  </div>
                                </div>
                                  <div class="form-group row">
                                    <div class="col-lg-12">
                                      <label class="form-label">Document Type <span class="text-danger">*</span></label>
                                      <select class="form-control offcanvas-select2" name="employee_document_type" id="employee_document_type">
                                        <option value="">--</option>
                                        <option value="Hiring Documents">Hiring Documents</option>
                                        <option value="Employment-Related Agreements">Employment-Related Agreements</option>
                                        <option value="Employment-Related Agreements">Employment-Related Agreements</option>
                                        <option value="Performance and Development">Performance and Development</option>
                                        <option value="Payroll & Compensation">Payroll & Compensation</option>
                                        <option value="Benefits and Insurance">Benefits and Insurance</option>
                                        <option value="Attendance and Leave">Attendance and Leave</option>
                                        <option value="Safety and Compliance">Safety and Compliance</option>
                                        <option value="Incident Report">Incident Report</option>
                                        <option value="Miscellaneous">Miscellaneous</option>
                                      </select>
                                    </div>
                                  </div>
                                  <div class="form-group row">
                                    <div class="col-lg-12">
                                      <label class="form-label" for="employee_document">Employee Document (PDF only) <span class="text-danger">*</span></label>
                                      <input type="file" class="form-control" id="employee_document" name="employee_document">
                                    </div>
                                  </div>
                                </form>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-lg-12">
                                <button type="submit" class="btn btn-primary" id="submit-contact-document" form="contact-document-form">Submit</button>
                                <button class="btn btn-light-danger" data-bs-dismiss="offcanvas"> Close </button>
                              </div>
                            </div>
                          </div>
                        </div>';
                }

                if($addEmployeeEducationalBackground['total'] > 0 || $updateEmployeeEducationalBackground['total'] > 0){
                  echo '<div class="offcanvas offcanvas-end" tabindex="-1" id="contact-educational-background-offcanvas" aria-labelledby="contact-educational-background-offcanvas-label">
                          <div class="offcanvas-header">
                            <h2 id="contact-educational-background-offcanvas-label" style="margin-bottom:-0.5rem">Educational Background</h2>
                            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                          </div>
                          <div class="offcanvas-body">
                            <div class="alert alert-success alert-dismissible mb-4" role="alert">
                              The Educational Background captures essential information about an individual\'s academic history. It includes fields for Educational Stage, Institution Name, Degree Earned, Field of Study, Start Date, and End Date, providing a concise summary of their educational qualifications and achievements.
                              <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                            <div class="row">
                              <div class="col-lg-12">
                                <form id="contact-educational-background-form" method="post" action="#">
                                  <div class="form-group row">
                                    <div class="col-lg-12">
                                      <label class="form-label">Educational Stage <span class="text-danger">*</span></label>
                                      <input type="hidden" id="contact_educational_background_id" name="contact_educational_background_id">
                                      <select class="form-control offcanvas-select2" name="educational_stage_id" id="educational_stage_id">
                                        <option value="">--</option>
                                        '. $educationalStageModel->generateEducationalStageOptions() .'
                                      </select>
                                    </div>
                                  </div>
                                  <div class="form-group row">
                                    <div class="col-lg-12">
                                      <label class="form-label" for="institution_name">Institution Name <span class="text-danger">*</span></label>
                                      <input type="text" class="form-control" id="institution_name" name="institution_name" maxlength="500" autocomplete="off">
                                    </div>
                                  </div>
                                  <div class="form-group row">
                                    <div class="col-lg-12">
                                      <label class="form-label" for="degree_earned">Degree Earned</label>
                                      <input type="text" class="form-control" id="degree_earned" name="degree_earned" maxlength="500" autocomplete="off">
                                    </div>
                                  </div>
                                  <div class="form-group row">
                                    <div class="col-lg-12">
                                      <label class="form-label" for="field_of_study">Field of Study</label>
                                      <input type="text" class="form-control" id="field_of_study" name="field_of_study" maxlength="500" autocomplete="off">
                                    </div>
                                  </div>
                                  <div class="form-group row mb-0">
                                    <div class="col-lg-12">
                                      <label class="form-label">Started <span class="text-danger">*</span></label>
                                    </div>
                                  </div>
                                  <div class="form-group row">
                                    <div class="col-lg-6">
                                      <select class="form-control offcanvas-select2" name="educational_background_start_month" id="educational_background_start_month">
                                        <option value="">--</option>
                                        '. $systemModel->generateMonthOptions() .'
                                      </select>
                                    </div>
                                    <div class="col-lg-6 mt-3 mt-lg-0">
                                      <select class="form-control offcanvas-select2" name="educational_background_start_year" id="educational_background_start_year">
                                        <option value="">--</option>
                                        '. $systemModel->generateYearOptions(date('Y', strtotime('-100 years')), date('Y')) .'
                                      </select>
                                    </div>
                                  </div>
                                  <div class="form-group row mb-0">
                                    <div class="col-lg-12">
                                      <label class="form-label">Ended</label>
                                    </div>
                                  </div>
                                  <div class="form-group row">
                                    <div class="col-lg-6">
                                      <select class="form-control offcanvas-select2" name="educational_background_end_month" id="educational_background_end_month">
                                        <option value="">--</option>
                                        '. $systemModel->generateMonthOptions() .'
                                      </select>
                                    </div>
                                    <div class="col-lg-6 mt-3 mt-lg-0">
                                      <select class="form-control offcanvas-select2" name="educational_background_end_year" id="educational_background_end_year">
                                        <option value="">--</option>
                                        '. $systemModel->generateYearOptions(date('Y', strtotime('-100 years')), date('Y')) .'
                                      </select>
                                    </div>
                                  </div>
                                  <div class="form-group row">
                                    <div class="col-lg-12">
                                      <label class="form-label">Course Highlights</label>
                                      <textarea class="form-control" id="course_highlights" name="course_highlights" maxlength="5000" rows="5"></textarea>
                                    </div>
                                  </div>
                                </form>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-lg-12">
                                <button type="submit" class="btn btn-primary" id="submit-contact-educational-background" form="contact-educational-background-form">Submit</button>
                                <button class="btn btn-light-danger" data-bs-dismiss="offcanvas"> Close </button>
                              </div>
                            </div>
                          </div>
                        </div>';
                }

                if($addEmployeeFamilyBackground['total'] > 0 || $updateEmployeeFamilyBackground['total'] > 0){
                  echo '<div class="offcanvas offcanvas-end" tabindex="-1" id="contact-family-background-offcanvas" aria-labelledby="contact-family-background-offcanvas-label">
                          <div class="offcanvas-header">
                            <h2 id="contact-family-background-offcanvas-label" style="margin-bottom:-0.5rem">Family Background</h2>
                            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                          </div>
                          <div class="offcanvas-body">
                            <div class="alert alert-success alert-dismissible mb-4" role="alert">
                              The Family Background collects essential information about an individual\'s family members, including their names, relationships, birthdays, and contact details. This helps organizations and institutions better understand an employee or individual\'s personal background and enables efficient communication and emergency contact.
                              <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                            <div class="row">
                              <div class="col-lg-12">
                                <form id="contact-family-background-form" method="post" action="#">
                                  <div class="form-group row">
                                    <div class="col-lg-12">
                                      <label class="form-label" for="family_name">Family Name <span class="text-danger">*</span></label>
                                      <input type="hidden" id="contact_family_background_id" name="contact_family_background_id">
                                      <input type="text" class="form-control" id="family_name" name="family_name" maxlength="500" autocomplete="off">
                                    </div>
                                  </div>
                                  <div class="form-group row">
                                    <div class="col-lg-6">
                                      <label class="form-label">Relation <span class="text-danger">*</span></label>
                                      <select class="form-control offcanvas-select2" name="family_background_relation_id" id="family_background_relation_id">
                                        <option value="">--</option>
                                        '. $relationModel->generateRelationOptions() .'
                                      </select>
                                    </div>
                                    <div class="col-lg-6 mt-3 mt-lg-0">
                                      <label class="form-label">Birthday <span class="text-danger">*</span></label>
                                      <div class="input-group date">
                                        <input type="text" class="form-control regular-datepicker" id="family_background_birthday" name="family_background_birthday" autocomplete="off">
                                        <span class="input-group-text">
                                          <i class="feather icon-calendar"></i>
                                        </span>
                                      </div>
                                    </div>
                                  </div>
                                  <div class="form-group row">
                                    <div class="col-lg-12">
                                      <label class="form-label" for="family_background_email">Email</label>
                                      <input type="email" class="form-control" id="family_background_email" name="family_background_email" maxlength="100" autocomplete="off">
                                    </div>
                                  </div>
                                  <div class="form-group row">
                                    <div class="col-lg-6">
                                      <label class="form-label" for="family_background_mobile">Mobile</label>
                                      <input type="text" class="form-control" id="family_background_mobile" name="family_background_mobile" maxlength="20" autocomplete="off">
                                    </div>
                                    <div class="col-lg-6 mt-3 mt-lg-0">
                                      <label class="form-label" for="family_background_telephone">Telephone</label>
                                      <input type="text" class="form-control" id="family_background_telephone" name="family_background_telephone" maxlength="20" autocomplete="off">
                                    </div>
                                  </div>
                                </form>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-lg-12">
                                <button type="submit" class="btn btn-primary" id="submit-contact-family-background" form="contact-family-background-form">Submit</button>
                                <button class="btn btn-light-danger" data-bs-dismiss="offcanvas"> Close </button>
                              </div>
                            </div>
                          </div>
                        </div>';
                }

                if($addEmployeeEmergencyContact['total'] > 0 || $updateEmployeeEmergencyContact['total'] > 0){
                  echo '<div class="offcanvas offcanvas-end" tabindex="-1" id="contact-emergency-contact-offcanvas" aria-labelledby="contact-emergency-contact-offcanvas-label">
                          <div class="offcanvas-header">
                            <h2 id="contact-emergency-contact-offcanvas-label" style="margin-bottom:-0.5rem">Emergency Contact</h2>
                            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                          </div>
                          <div class="offcanvas-body">
                            <div class="alert alert-success alert-dismissible mb-4" role="alert">
                              The Emergency Contact is used to collect essential information about an employee\'s designated emergency contact, including their name, relation to the employee, contact email, mobile number, and telephone number. This information ensures quick and effective communication in case of an emergency situation involving the employee.
                              <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                            <div class="row">
                              <div class="col-lg-12">
                                <form id="contact-emergency-contact-form" method="post" action="#">
                                  <div class="form-group row">
                                    <div class="col-lg-12">
                                      <label class="form-label" for="emergency_contact_name">Emergency Contact Name <span class="text-danger">*</span></label>
                                      <input type="hidden" id="contact_emergency_contact_id" name="contact_emergency_contact_id">
                                      <input type="text" class="form-control" id="emergency_contact_name" name="emergency_contact_name" maxlength="500" autocomplete="off">
                                    </div>
                                  </div>
                                  <div class="form-group row">
                                    <div class="col-lg-12">
                                      <label class="form-label">Relation <span class="text-danger">*</span></label>
                                      <select class="form-control offcanvas-select2" name="emergency_contact_relation_id" id="emergency_contact_relation_id">
                                        <option value="">--</option>
                                        '. $relationModel->generateRelationOptions() .'
                                      </select>
                                    </div>
                                  </div>
                                  <div class="form-group row">
                                    <div class="col-lg-12">
                                      <label class="form-label" for="emergency_contact_email">Email</label>
                                      <input type="email" class="form-control" id="emergency_contact_email" name="emergency_contact_email" maxlength="100" autocomplete="off">
                                    </div>
                                  </div>
                                  <div class="form-group row">
                                    <div class="col-lg-6">
                                      <label class="form-label" for="emergency_contact_mobile">Mobile</label>
                                      <input type="text" class="form-control" id="emergency_contact_mobile" name="emergency_contact_mobile" maxlength="20" autocomplete="off">
                                    </div>
                                    <div class="col-lg-6 mt-3 mt-lg-0">
                                      <label class="form-label" for="emergency_contact_telephone">Telephone</label>
                                      <input type="text" class="form-control" id="emergency_contact_telephone" name="emergency_contact_telephone" maxlength="20" autocomplete="off">
                                    </div>
                                  </div>
                                </form>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-lg-12">
                                <button type="submit" class="btn btn-primary" id="submit-contact-emergency-contact" form="contact-emergency-contact-form">Submit</button>
                                <button class="btn btn-light-danger" data-bs-dismiss="offcanvas"> Close </button>
                              </div>
                            </div>
                          </div>
                        </div>';
                }

                if($addEmployeeTrainingsSeminars['total'] > 0 || $updateEmployeeTrainingsSeminars['total'] > 0){
                  echo '<div class="offcanvas offcanvas-end" tabindex="-1" id="contact-training-offcanvas" aria-labelledby="contact-training-offcanvas-label">
                          <div class="offcanvas-header">
                            <h2 id="contact-training-offcanvas-label" style="margin-bottom:-0.5rem">Trainings & Seminars</h2>
                            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                          </div>
                          <div class="offcanvas-body">
                            <div class="alert alert-success alert-dismissible mb-4" role="alert">
                              The Trainings & Seminars captures essential details of training events, including the Training Name, Training Date, Training Location, and Training Provider. This information is used for record-keeping, tracking employee development, and ensuring compliance with organizational training requirements.
                              <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                            <div class="row">
                              <div class="col-lg-12">
                                <form id="contact-training-form" method="post" action="#">
                                  <div class="form-group row">
                                    <div class="col-lg-12">
                                      <label class="form-label" for="training_name">Training Name <span class="text-danger">*</span></label>
                                      <input type="hidden" id="contact_training_id" name="contact_training_id">
                                      <input type="text" class="form-control" id="training_name" name="training_name" maxlength="500" autocomplete="off">
                                    </div>
                                  </div>
                                  <div class="form-group row">
                                    <div class="col-lg-12">
                                      <label class="form-label">Training Date <span class="text-danger">*</span></label>
                                      <div class="input-group date">
                                        <input type="text" class="form-control regular-datepicker" id="training_date" name="training_date" autocomplete="off">
                                        <span class="input-group-text">
                                          <i class="feather icon-calendar"></i>
                                        </span>
                                      </div>
                                    </div>
                                  </div>
                                  <div class="form-group row">
                                    <div class="col-lg-12">
                                      <label class="form-label" for="training_location">Training Location</label>
                                      <input type="text" class="form-control" id="training_location" name="training_location" maxlength="500" autocomplete="off">
                                    </div>
                                  </div>
                                  <div class="form-group row">
                                    <div class="col-lg-12">
                                      <label class="form-label" for="training_provider">Training Provider</label>
                                      <input type="text" class="form-control" id="training_provider" name="training_provider" maxlength="500" autocomplete="off">
                                    </div>
                                  </div>
                                </form>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-lg-12">
                                <button type="submit" class="btn btn-primary" id="submit-contact-training" form="contact-training-form">Submit</button>
                                <button class="btn btn-light-danger" data-bs-dismiss="offcanvas"> Close </button>
                              </div>
                            </div>
                          </div>
                        </div>';
                }

                if($addEmployeeSkills['total'] > 0 || $updateEmployeeSkills['total'] > 0){
                  echo '<div class="offcanvas offcanvas-end" tabindex="-1" id="contact-skills-offcanvas" aria-labelledby="contact-skills-offcanvas-label">
                          <div class="offcanvas-header">
                            <h2 id="contact-skills-offcanvas-label" style="margin-bottom:-0.5rem">Skills</h2>
                            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                          </div>
                          <div class="offcanvas-body">
                            <div class="alert alert-success alert-dismissible mb-4" role="alert">
                              The Skills is used to record and manage the skills of an employee within an organization. It includes fields for documenting the name of the skill possessed by the employee.
                              <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                            <div class="row">
                              <div class="col-lg-12">
                                <form id="contact-skills-form" method="post" action="#">
                                  <div class="form-group row">
                                    <div class="col-lg-12">
                                      <label class="form-label" for="skills_name">Skill Name <span class="text-danger">*</span></label>
                                      <input type="hidden" id="contact_skills_id" name="contact_skills_id">
                                      <input type="text" class="form-control" id="skill_name" name="skill_name" maxlength="500" autocomplete="off">
                                    </div>
                                  </div>
                                </form>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-lg-12">
                                <button type="submit" class="btn btn-primary" id="submit-contact-skills" form="contact-skills-form">Submit</button>
                                <button class="btn btn-light-danger" data-bs-dismiss="offcanvas"> Close </button>
                              </div>
                            </div>
                          </div>
                        </div>';
                }

                if($addEmployeeTalents['total'] > 0 || $updateEmployeeTalents['total'] > 0){
                  echo '<div class="offcanvas offcanvas-end" tabindex="-1" id="contact-talents-offcanvas" aria-labelledby="contact-talents-offcanvas-label">
                          <div class="offcanvas-header">
                            <h2 id="contact-talents-offcanvas-label" style="margin-bottom:-0.5rem">Talents</h2>
                            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                          </div>
                          <div class="offcanvas-body">
                            <div class="alert alert-success alert-dismissible mb-4" role="alert">
                              The Talents is used to record and track the unique talents of each employee within an organization. It includes fields for Talent Name, allowing the company to identify and leverage individual talents effectively for team and project assignments.
                              <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                            <div class="row">
                              <div class="col-lg-12">
                                <form id="contact-talents-form" method="post" action="#">
                                  <div class="form-group row">
                                    <div class="col-lg-12">
                                      <label class="form-label" for="talents_name">Talent Name <span class="text-danger">*</span></label>
                                      <input type="hidden" id="contact_talents_id" name="contact_talents_id">
                                      <input type="text" class="form-control" id="talent_name" name="talent_name" maxlength="500" autocomplete="off">
                                    </div>
                                  </div>
                                </form>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-lg-12">
                                <button type="submit" class="btn btn-primary" id="submit-contact-talents" form="contact-talents-form">Submit</button>
                                <button class="btn btn-light-danger" data-bs-dismiss="offcanvas"> Close </button>
                              </div>
                            </div>
                          </div>
                        </div>';
                }

                if($addEmployeeHobby['total'] > 0 || $updateEmployeeHobby['total'] > 0){
                  echo '<div class="offcanvas offcanvas-end" tabindex="-1" id="contact-hobby-offcanvas" aria-labelledby="contact-hobby-offcanvas-label">
                          <div class="offcanvas-header">
                            <h2 id="contact-hobby-offcanvas-label" style="margin-bottom:-0.5rem">Hobby</h2>
                            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                          </div>
                          <div class="offcanvas-body">
                            <div class="alert alert-success alert-dismissible mb-4" role="alert">
                              This form is used to collect and record an employee\'s hobbies and interests, with a primary field for "Hobby Name" to identify and document their recreational activities.
                            </div>
                            <div class="row">
                              <div class="col-lg-12">
                                <form id="contact-hobby-form" method="post" action="#">
                                  <div class="form-group row">
                                    <div class="col-lg-12">
                                      <label class="form-label" for="hobby_name">Hobby Name <span class="text-danger">*</span></label>
                                      <input type="hidden" id="contact_hobby_id" name="contact_hobby_id">
                                      <input type="text" class="form-control" id="hobby_name" name="hobby_name" maxlength="500" autocomplete="off">
                                    </div>
                                  </div>
                                </form>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-lg-12">
                                <button type="submit" class="btn btn-primary" id="submit-contact-hobby" form="contact-hobby-form">Submit</button>
                                <button class="btn btn-light-danger" data-bs-dismiss="offcanvas"> Close </button>
                              </div>
                            </div>
                          </div>
                        </div>';
                }

                if($addEmploymentHistory['total'] > 0 || $updateEmploymentHistory['total'] > 0){
                  echo '<div class="offcanvas offcanvas-end" tabindex="-1" id="contact-employment-history-offcanvas" aria-labelledby="contact-employment-history-offcanvas-label">
                          <div class="offcanvas-header">
                            <h2 id="contact-employment-history-offcanvas-label" style="margin-bottom:-0.5rem">Employment History</h2>
                            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                          </div>
                          <div class="offcanvas-body">
                            <div class="alert alert-success alert-dismissible mb-4" role="alert">
                              This form serves as a vital tool for gathering and meticulously documenting an employee\'s complete employment history. It captures essential information such as previous job positions, company names, dates of employment, responsibilities, and achievements, enabling employers to gain valuable insights into the individual\'s professional journey.
                            </div>
                            <div class="row">
                              <div class="col-lg-12">
                                <form id="contact-employment-history-form" method="post" action="#">
                                  <div class="form-group row">
                                    <div class="col-lg-12">
                                      <label class="form-label" for="employment_history_last_position_held">Last Position Held <span class="text-danger">*</span></label>
                                      <input type="hidden" id="contact_employment_history_id" name="contact_employment_history_id">
                                      <input type="text" class="form-control" id="employment_history_last_position_held" name="employment_history_last_position_held" maxlength="500" autocomplete="off">
                                    </div>
                                  </div>
                                  <div class="form-group row">
                                    <div class="col-lg-12">
                                      <label class="form-label" for="employment_history_company">Company Name <span class="text-danger">*</span></label>
                                      <input type="text" class="form-control" id="employment_history_company" name="employment_history_company" maxlength="500" autocomplete="off">
                                    </div>
                                  </div>
                                  <div class="form-group row">
                                    <div class="col-lg-12">
                                      <label class="form-label" for="employment_history_address">Company Address <span class="text-danger">*</span></label>
                                      <input type="text" class="form-control" id="employment_history_address" name="employment_history_address" maxlength="500" autocomplete="off">
                                    </div>
                                  </div>
                                  <div class="form-group row mb-0">
                                    <div class="col-lg-12">
                                      <label class="form-label">Started <span class="text-danger">*</span></label>
                                    </div>
                                  </div>
                                  <div class="form-group row">
                                    <div class="col-lg-6">
                                      <select class="form-control offcanvas-select2" name="employment_history_start_month" id="employment_history_start_month">
                                        <option value="">--</option>
                                        '. $systemModel->generateMonthOptions() .'
                                      </select>
                                    </div>
                                    <div class="col-lg-6 mt-3 mt-lg-0">
                                      <select class="form-control offcanvas-select2" name="employment_history_start_year" id="employment_history_start_year">
                                        <option value="">--</option>
                                        '. $systemModel->generateYearOptions(date('Y', strtotime('-100 years')), date('Y')) .'
                                      </select>
                                    </div>
                                  </div>
                                  <div class="form-group row mb-0">
                                    <div class="col-lg-12">
                                      <label class="form-label">Ended</label>
                                    </div>
                                  </div>
                                  <div class="form-group row">
                                    <div class="col-lg-6">
                                      <select class="form-control offcanvas-select2" name="employment_history_end_month" id="employment_history_end_month">
                                        <option value="">--</option>
                                        '. $systemModel->generateMonthOptions() .'
                                      </select>
                                    </div>
                                    <div class="col-lg-6 mt-3 mt-lg-0">
                                      <select class="form-control offcanvas-select2" name="employment_history_end_year" id="employment_history_end_year">
                                        <option value="">--</option>
                                        '. $systemModel->generateYearOptions(date('Y', strtotime('-100 years')), date('Y')) .'
                                      </select>
                                    </div>
                                  </div>
                                  <div class="form-group row">
                                    <div class="col-lg-6 mt-3 mt-lg-0">
                                      <label class="form-label">Starting Salary <span class="text-danger">*</span></label>
                                      <div class="input-group">
                                        <input type="number" min="0" step="0.01" class="form-control" id="starting_salary" name="starting_salary">
                                        <span class="input-group-text">Php</span>
                                      </div>
                                    </div>
                                    <div class="col-lg-6 mt-3 mt-lg-0">
                                      <label class="form-label">Final Salary</label>
                                      <div class="input-group">
                                        <input type="number" min="0" step="0.01" class="form-control" id="final_salary" name="final_salary">
                                        <span class="input-group-text">Php</span>
                                      </div>
                                    </div>
                                  </div>
                                  <div class="form-group row">
                                    <div class="col-lg-12">
                                      <label class="form-label">Basic Function</label>
                                      <textarea class="form-control" id="basic_function" name="basic_function" maxlength="5000" rows="5"></textarea>
                                    </div>
                                  </div>
                                </form>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-lg-12">
                                <button type="submit" class="btn btn-primary" id="submit-contact-employment-history" form="contact-employment-history-form">Submit</button>
                                <button class="btn btn-light-danger" data-bs-dismiss="offcanvas"> Close </button>
                              </div>
                            </div>
                          </div>
                        </div>';
                }

                if($addEmployeeLicense['total'] > 0 || $updateEmployeeLicense['total'] > 0){
                  echo '<div class="offcanvas offcanvas-end" tabindex="-1" id="contact-employee-license-offcanvas" aria-labelledby="contact-employee-license-offcanvas-label">
                          <div class="offcanvas-header">
                            <h2 id="contact-employee-license-offcanvas-label" style="margin-bottom:-0.5rem">Licenses & Certifications</h2>
                            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                          </div>
                          <div class="offcanvas-body">
                            <div class="alert alert-success alert-dismissible mb-4" role="alert">
                              This form is utilized to gather and document an employee\'s licenses and certifications, ensuring a comprehensive overview of their qualifications and credentials.
                            </div>
                            <div class="row">
                              <div class="col-lg-12">
                                <form id="contact-employee-license-form" method="post" action="#">
                                  <div class="form-group row">
                                    <div class="col-lg-12">
                                      <label class="form-label" for="license_name">License Name <span class="text-danger">*</span></label>
                                      <input type="hidden" id="contact_license_id" name="contact_license_id">
                                      <input type="text" class="form-control" id="license_name" name="license_name" maxlength="500" autocomplete="off">
                                    </div>
                                  </div>
                                  <div class="form-group row">
                                    <div class="col-lg-12">
                                      <label class="form-label" for="issuing_organization">Issuing Orgranization <span class="text-danger">*</span></label>
                                      <input type="text" class="form-control" id="issuing_organization" name="issuing_organization" maxlength="500" autocomplete="off">
                                    </div>
                                  </div>
                                  <div class="form-group row mb-0">
                                    <div class="col-lg-12">
                                      <label class="form-label">Issue Date <span class="text-danger">*</span></label>
                                    </div>
                                  </div>
                                  <div class="form-group row">
                                    <div class="col-lg-6">
                                      <select class="form-control offcanvas-select2" name="license_start_month" id="license_start_month">
                                        <option value="">--</option>
                                        '. $systemModel->generateMonthOptions() .'
                                      </select>
                                    </div>
                                    <div class="col-lg-6 mt-3 mt-lg-0">
                                      <select class="form-control offcanvas-select2" name="license_start_year" id="license_start_year">
                                        <option value="">--</option>
                                        '. $systemModel->generateYearOptions(date('Y', strtotime('-100 years')), date('Y')) .'
                                      </select>
                                    </div>
                                  </div>
                                  <div class="form-group row mb-0">
                                    <div class="col-lg-12">
                                      <label class="form-label">Expiry Date</label>
                                    </div>
                                  </div>
                                  <div class="form-group row">
                                    <div class="col-lg-6">
                                      <select class="form-control offcanvas-select2" name="license_end_month" id="license_end_month">
                                        <option value="">--</option>
                                        '. $systemModel->generateMonthOptions() .'
                                      </select>
                                    </div>
                                    <div class="col-lg-6 mt-3 mt-lg-0">
                                      <select class="form-control offcanvas-select2" name="license_end_year" id="license_end_year">
                                        <option value="">--</option>
                                        '. $systemModel->generateYearOptions(date('Y', strtotime('-100 years')), date('Y', strtotime('+20 years'))) .'
                                      </select>
                                    </div>
                                  </div>
                                  <div class="form-group row">
                                    <div class="col-lg-12">
                                      <label class="form-label">Description</label>
                                      <textarea class="form-control" id="contact_license_description" name="contact_license_description" maxlength="500" rows="5"></textarea>
                                    </div>
                                  </div>
                                </form>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-lg-12">
                                <button type="submit" class="btn btn-primary" id="submit-contact-employee-license" form="contact-employee-license-form">Submit</button>
                                <button class="btn btn-light-danger" data-bs-dismiss="offcanvas"> Close </button>
                              </div>
                            </div>
                          </div>
                        </div>';
                }

                if($addEmployeeLanguage['total'] > 0 || $updateEmployeeLanguage['total'] > 0){
                  echo '<div class="offcanvas offcanvas-end" tabindex="-1" id="contact-employee-language-offcanvas" aria-labelledby="contact-employee-language-offcanvas-label">
                          <div class="offcanvas-header">
                            <h2 id="contact-employee-language-offcanvas-label" style="margin-bottom:-0.5rem">Language</h2>
                            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                          </div>
                          <div class="offcanvas-body">
                            <div class="alert alert-success alert-dismissible mb-4" role="alert">
                              This form is employed to gather and document an employee\'s linguistic skills and proficiency, providing valuable insights into their language capabilities.
                            </div>
                            <div class="row">
                              <div class="col-lg-12">
                                <form id="contact-employee-language-form" method="post" action="#">
                                  <div class="form-group row">
                                    <div class="col-lg-12">
                                      <label class="form-label">Language <span class="text-danger">*</span></label>
                                      <input type="hidden" id="contact_language_id" name="contact_language_id">
                                      <select class="form-control offcanvas-select2" name="language_id" id="language_id">
                                        <option value="">--</option>
                                        '. $languageModel->generateLanguageOptions() .'
                                      </select>
                                    </div>
                                  </div>
                                  <div class="form-group row">
                                    <div class="col-lg-12">
                                      <label class="form-label">Language Proficiency <span class="text-danger">*</span></label>
                                      <select class="form-control offcanvas-select2" name="language_proficiency_id" id="language_proficiency_id">
                                        <option value="">--</option>
                                        '. $languageProficiencyModel->generateLanguageProficiencyOptions() .'
                                      </select>
                                    </div>
                                  </div>
                                </form>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-lg-12">
                                <button type="submit" class="btn btn-primary" id="submit-contact-employee-language" form="contact-employee-language-form">Submit</button>
                                <button class="btn btn-light-danger" data-bs-dismiss="offcanvas"> Close </button>
                              </div>
                            </div>
                          </div>
                        </div>';
                }

                if($addEmployeeBank['total'] > 0 || $updateEmployeeBank['total'] > 0){
                  echo '<div class="offcanvas offcanvas-end" tabindex="-1" id="contact-employee-bank-offcanvas" aria-labelledby="contact-employee-bank-offcanvas-label">
                          <div class="offcanvas-header">
                            <h2 id="contact-employee-bank-offcanvas-label" style="margin-bottom:-0.5rem">Bank Account</h2>
                            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                          </div>
                          <div class="offcanvas-body">
                            <div class="alert alert-success alert-dismissible mb-4" role="alert">
                            This form plays a crucial role in securely collecting and recording an employee\'s banking information, including account numbers, bank account types. These details are essential for facilitating payroll processing, direct deposit, and other financial transactions, ensuring efficient and accurate financial interactions between the employer and the employee.
                            </div>
                            <div class="row">
                              <div class="col-lg-12">
                                <form id="contact-employee-bank-form" method="post" action="#">
                                  <div class="form-group row">
                                    <div class="col-lg-12">
                                      <label class="form-label">Bank <span class="text-danger">*</span></label>
                                      <input type="hidden" id="contact_bank_id" name="contact_bank_id">
                                      <select class="form-control offcanvas-select2" name="bank_id" id="bank_id">
                                        <option value="">--</option>
                                        '. $bankModel->generateBankOptions() .'
                                      </select>
                                    </div>
                                  </div>
                                  <div class="form-group row">
                                    <div class="col-lg-12">
                                      <label class="form-label">Bank Account Type <span class="text-danger">*</span></label>
                                      <select class="form-control offcanvas-select2" name="bank_account_type_id" id="bank_account_type_id">
                                        <option value="">--</option>
                                        '. $bankAccountTypeModel->generateBankAccountTypeOptions() .'
                                      </select>
                                    </div>
                                  </div>
                                  <div class="form-group row">
                                    <div class="col-lg-12">
                                      <label class="form-label" for="account_number">Account Number <span class="text-danger">*</span></label>
                                      <input type="text" class="form-control" id="account_number" name="account_number" maxlength="30" autocomplete="off">
                                    </div>
                                  </div>
                                </form>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-lg-12">
                                <button type="submit" class="btn btn-primary" id="submit-contact-employee-bank" form="contact-employee-bank-form">Submit</button>
                                <button class="btn btn-light-danger" data-bs-dismiss="offcanvas"> Close </button>
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

<div class="offcanvas offcanvas-end" tabindex="-1" id="archive-employee-offcanvas" aria-labelledby="archive-employee-offcanvas-label">
                          <div class="offcanvas-header">
                            <h2 id="archive-employee-offcanvas-label" style="margin-bottom:-0.5rem">Archive Employee</h2>
                            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                          </div>
                          <div class="offcanvas-body">
                            <div class="row">
                              <div class="col-lg-12">
                                <form id="archive-employee-form" method="post" action="#">
                                  <div class="form-group row mb-0">
                                    <div class="col-lg-12">
                                      <label class="form-label">Departure Reason <span class="text-danger">*</span></label>
                                    </div>
                                  </div>
                                  <div class="form-group row">
                                    <div class="col-lg-12">
                                      <select class="form-control offcanvas-select2" name="departure_reason_id" id="departure_reason_id">
                                        <option value="">--</option>
                                        <?php echo $departureReasonModel->generateDepartureReasonOptions(); ?>
                                      </select>
                                    </div>
                                  </div>
                                  <div class="form-group row">
                                    <div class="col-lg-12 mt-3 mt-lg-0">
                                      <label class="form-label">Offboard Date <span class="text-danger">*</span></label>
                                      <div class="input-group date">
                                        <input type="text" class="form-control regular-datepicker" id="offboard_date" name="offboard_date" autocomplete="off">
                                        <span class="input-group-text">
                                          <i class="feather icon-calendar"></i>
                                        </span>
                                      </div>
                                    </div>
                                  </div>
                                  <div class="form-group row">
                                    <div class="col-lg-12">
                                      <label class="form-label">Detailed Departure Reason <span class="text-danger">*</span></label>
                                      <textarea class="form-control" id="detailed_departure_reason" name="detailed_departure_reason" maxlength="5000" rows="5"></textarea>
                                    </div>
                                  </div>
                                </form>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-lg-12">
                                <button type="submit" class="btn btn-primary" id="submit-archive-employee" form="archive-employee-form">Submit</button>
                                <button class="btn btn-light-danger" data-bs-dismiss="offcanvas"> Close </button>
                              </div>
                            </div>
                          </div>
                        </div>