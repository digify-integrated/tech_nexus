<?php
$customerCustomerInformationUpdate = '';
$customerAddressAdd = '';
$contactInformationAdd = '';
$customerIdentificationAdd = '';
$customerFamilyBackgroundAdd = '';
$changeCustomerStatusButton = '';

if($customerWriteAccess['total'] > 0 && ($customerStatus == 'Draft' || $customerStatus == 'For Updating')){
  $customerCustomerInformationUpdate = '<button class="btn btn-icon btn-link-success" type="button" data-bs-toggle="offcanvas" data-bs-target="#personal-information-offcanvas" aria-controls="personal-information-offcanvas" id="update-personal-information"><i class="ti ti-pencil"></i></button>';

  if($addCustomerAddress['total'] > 0){
    $customerAddressAdd = '<button class="btn btn-icon btn-link-success" type="button" data-bs-toggle="offcanvas" data-bs-target="#contact-address-offcanvas" aria-controls="contact-address-offcanvas" id="add-contact-address"><i class="ti ti-plus"></i></button>';
  }

  if($addCustomerContactInformation['total'] > 0){
    $contactInformationAdd = '<button class="btn btn-icon btn-link-success" type="button" data-bs-toggle="offcanvas" data-bs-target="#contact-information-offcanvas" aria-controls="contact-information-offcanvas" id="add-contact-information"><i class="ti ti-plus"></i></button>';
  }

  if($addCustomerIdentification['total'] > 0){
    $customerIdentificationAdd = '<button class="btn btn-icon btn-link-success" type="button" data-bs-toggle="offcanvas" data-bs-target="#contact-identification-offcanvas" aria-controls="contact-identification-offcanvas" id="add-contact-identification"><i class="ti ti-plus"></i></button>';
  }

  if($addCustomerFamilyBackground['total'] > 0){
    $customerFamilyBackgroundAdd = '<button class="btn btn-icon btn-link-success" type="button" data-bs-toggle="offcanvas" data-bs-target="#contact-family-background-offcanvas" aria-controls="contact-family-background-offcanvas" id="add-contact-family-background"><i class="ti ti-plus"></i></button>';
  }
}

if($changeCustomerStatusToActive['total'] > 0 && ($customerStatus == 'Draft' || $customerStatus == 'For Updating')){
  $changeCustomerStatusButton = '<button type="button" class="btn btn-success" id="change-status-to-active">Active</button>';
}
else if($changeCustomerStatusToForUpdating['total'] && $customerStatus == 'Active'){
  $changeCustomerStatusButton = '<button type="button" class="btn btn-warning" id="change-status-to-for-updating">For Updating</button>';
}
?>

<div class="row">
  <div class="col-sm-12"> 
    <div class="card social-profile">
      <div class="card-body pt-0">
        <div class="row align-items-end">
          <div class="col-md-auto text-md-start">
            <img class="img-fluid img-profile-avtar wid-100 hei-90" src="<?php echo DEFAULT_AVATAR_IMAGE; ?>>" id="emp_image" alt="Customer image" />
          </div>
          <div class="col">
            <div class="row justify-content-between align-items-end">
              <div class="col-md-auto soc-profile-data">
                <h5 class="mb-1 text-primary" id="customer_name"></h5>
                <?php echo $customerStatusBadge; ?>
              </div>
              <div class="col-md-auto">
                <?php
                  if($customerWriteAccess['total'] > 0 && ($customerStatus == 'Draft' || $customerStatus == 'For Updating')){
                    echo '<button type="button" class="btn btn-outline-secondary me-1" data-bs-toggle="offcanvas" data-bs-target="#contact-image-offcanvas" aria-controls="contact-image-offcanvas" for="customer_image">Update Customer Image</button>';
                  }

                  echo $changeCustomerStatusButton;
                ?>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-lg-12">
      <div class="row">
        <div class="col-md-12">
          <div class="card">
            <div class="card-body">
              <div class="row">
                <div class="col-md-12">
                  <ul class="nav nav-tabs profile-tabs" id="v-customer-profile-ta" role="tablist">
                    <li class="nav-item"><a class="nav-link active" id="profile-tab-1" id="v-customer-profile-tab" data-bs-toggle="tab" href="#v-customer-profile" role="tab" aria-controls="v-customer-profile">Personal Information</a></li>
                    <li class="nav-item"><a class="nav-link" id="v-customer-profile-address-tab" data-bs-toggle="tab" href="#v-customer-profile-address" role="tab" aria-controls="v-customer-profile-address">Address</a></li>
                    <li class="nav-item"><a class="nav-link" id="v-customer-profile-contact-information-tab" data-bs-toggle="tab" href="#v-customer-profile-contact-information" role="tab" aria-controls="v-customer-profile-contact-information">Contact Information</a></li>
                    <li class="nav-item"><a class="nav-link" id="v-customer-profile-customer-identification-tab" data-bs-toggle="tab" href="#v-customer-profile-customer-identification">Contact Identification</a></li>
                    <li class="nav-item"><a class="nav-link" id="v-customer-profile-family-background-tab" data-bs-toggle="tab" href="#v-customer-profile-family-background" role="tab" aria-controls="v-customer-profile-family-background">Family Background</a></li>
                  </ul>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-md-12">
          <div class="tab-content" id="v-pills-customer-profile-basic-information">
            <div class="tab-pane fade show active" id="v-customer-profile" role="tabpanel" aria-labelledby="v-customer-profile-tab">
              <div class="card">
                <div class="card-header">
                  <div class="d-flex align-items-center justify-content-between">
                    <h5>Personal Information</h5>
                    <?php echo $customerCustomerInformationUpdate; ?>
                  </div>
                </div>
                <div class="card-body" id="personal-information-summary"></div>
              </div>
            </div>
            <div class="tab-pane fade" id="v-customer-profile-address" role="tabpanel" aria-labelledby="v-customer-profile-address-tab">
              <div class="card">
                <div class="card-header">
                  <div class="d-flex align-items-center justify-content-between">
                    <h5>Address</h5>
                    <?php echo $customerAddressAdd; ?>
                  </div>
                </div>
                <div class="card-body">
                  <ul class="list-group list-group-flush border-top-0" id="contact-address-summary"></ul>
                </div>
              </div>
            </div>
            <div class="tab-pane fade" id="v-customer-profile-contact-information" role="tabpanel" aria-labelledby="v-customer-profile-contact-information-tab">
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
            <div class="tab-pane fade" id="v-customer-profile-customer-identification" role="tabpanel" aria-labelledby="v-customer-profile-customer-identification-tab">
              <div class="card">
                <div class="card-header">
                  <div class="d-flex align-items-center justify-content-between">
                    <h5>Customer Identification</h5>
                    <?php echo $customerIdentificationAdd; ?>
                  </div>
                </div>
                <div class="card-body">
                  <ul class="list-group list-group-flush" id="contact-identification-summary"></ul>
                </div>
              </div>
            </div>
            <div class="tab-pane fade" id="v-customer-profile-family-background" role="tabpanel" aria-labelledby="v-customer-profile-family-background-tab">
              <div class="card">
                <div class="card-header">
                  <div class="d-flex align-items-center justify-content-between">
                    <h5>Family Background</h5>
                    <?php echo $customerFamilyBackgroundAdd; ?>
                  </div>
                </div>
                <div class="card-body">
                  <ul class="list-group list-group-flush" id="contact-family-background-summary"></ul>
                </div>
              </div>
            </div>
        </div>
      </div>
    </div>
  </div>
</div>
<?php 
  if($customerWriteAccess['total'] > 0){
    echo '<div class="offcanvas offcanvas-end" tabindex="-1" id="personal-information-offcanvas" aria-labelledby="personal-information-offcanvas-label">
            <div class="offcanvas-header">
              <h2 id="personal-information-offcanvas-label" style="margin-bottom:-0.5rem">Customer Information</h2>
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
          </div>';

          if($addCustomerContactInformation['total'] > 0 || $updateCustomerContactInformation ['total'] > 0){
            echo '<div class="offcanvas offcanvas-end" tabindex="-1" id="contact-information-offcanvas" aria-labelledby="contact-information-offcanvas-label">
                    <div class="offcanvas-header">
                      <h2 id="contact-information-offcanvas-label" style="margin-bottom:-0.5rem">Contact Information</h2>
                      <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                    </div>
                    <div class="offcanvas-body">
                      <div class="alert alert-success alert-dismissible mb-4" role="alert">
                        The Customer Contact Information collects essential contact details, including Email, Mobile, and Telephone numbers, to maintain accurate and up-to-date communication records for customers.
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
                                <label class="form-label" for="contact_information_email">Email</label>
                                <input type="email" class="form-control" id="contact_information_email" name="contact_information_email" maxlength="100" autocomplete="off">
                              </div>
                            </div>
                            <div class="form-group row">
                              <div class="col-lg-6">
                                <label class="form-label" for="contact_information_mobile">Mobile</label>
                                <input type="text" class="form-control" id="contact_information_mobile" name="contact_information_mobile" maxlength="20" autocomplete="off">
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

          if($addCustomerAddress['total'] > 0 || $updateCustomerAddress['total'] > 0){
            echo '<div class="offcanvas offcanvas-end" tabindex="-1" id="contact-address-offcanvas" aria-labelledby="contact-address-offcanvas-label">
                    <div class="offcanvas-header">
                      <h2 id="contact-address-offcanvas-label" style="margin-bottom:-0.5rem">Address</h2>
                      <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                    </div>
                    <div class="offcanvas-body">
                      <div class="alert alert-success alert-dismissible mb-4" role="alert">
                        The Customer Address collects essential address information, including Address Type (e.g., home or work), the Address itself, and the associated City, to maintain accurate records for customer address.
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

          if($addCustomerIdentification['total'] > 0 || $updateCustomerIdentification['total'] > 0){
            echo '<div class="offcanvas offcanvas-end" tabindex="-1" id="contact-identification-offcanvas" aria-labelledby="contact-identification-offcanvas-label">
                    <div class="offcanvas-header">
                      <h2 id="contact-identification-offcanvas-label" style="margin-bottom:-0.5rem">Customer Identification</h2>
                      <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                    </div>
                    <div class="offcanvas-body">
                      <div class="alert alert-success alert-dismissible mb-4" role="alert">
                        The Customer Identification captures essential customer information by requesting their ID type and corresponding ID number, ensuring accurate record-keeping and identification within the organization.
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                      </div>
                      <div class="row">
                        <div class="col-lg-12">
                          <form id="contact-identification-form" method="post" action="#">
                            <div class="form-group row">
                              <div class="col-lg-12">
                                <label class="form-label">ID Type <span class="text-danger">*</span></label>
                                <input type="hidden" id="contact_identification_id" name="contact_identification_id">
                                <select class="form-control offcanvas-select2" name="id_type_id" id="id_type_id">
                                  <option value="">--</option>
                                  '. $idTypeModel->generateIDTypeOptions() .'
                                </select>
                              </div>
                            </div>
                            <div class="form-group row">
                              <div class="col-lg-12">
                                <label class="form-label" for="id_number">ID Number <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="id_number" name="id_number" maxlength="100" autocomplete="off">
                              </div>
                            </div>
                          </form>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-lg-12">
                          <button type="submit" class="btn btn-primary" id="submit-contact-identification" form="contact-identification-form">Submit</button>
                          <button class="btn btn-light-danger" data-bs-dismiss="offcanvas"> Close </button>
                        </div>
                      </div>
                    </div>
                  </div>';
          }

          if($addCustomerFamilyBackground['total'] > 0 || $updateCustomerFamilyBackground['total'] > 0){
            echo '<div class="offcanvas offcanvas-end" tabindex="-1" id="contact-family-background-offcanvas" aria-labelledby="contact-family-background-offcanvas-label">
                    <div class="offcanvas-header">
                      <h2 id="contact-family-background-offcanvas-label" style="margin-bottom:-0.5rem">Family Background</h2>
                      <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                    </div>
                    <div class="offcanvas-body">
                      <div class="alert alert-success alert-dismissible mb-4" role="alert">
                        The Family Background collects essential information about an individual\'s family members, including their names, relationships, birthdays, and contact details. This helps organizations and institutions better understand an customer or individual\'s personal background and enables efficient communication and emergency contact.
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

          echo '<div class="offcanvas offcanvas-end" tabindex="-1" id="contact-image-offcanvas" aria-labelledby="contact-image-offcanvas-label">
                  <div class="offcanvas-header">
                    <h2 id="contact-image-offcanvas-label" style="margin-bottom:-0.5rem">Customer Image</h2>
                    <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                  </div>
                  <div class="offcanvas-body">
                    <div class="row">
                      <div class="col-lg-12">
                        <form id="contact-image-form" method="post" action="#">
                          <div class="form-group row">
                            <div class="col-lg-12">
                              <label class="form-label">Customer Image <span class="text-danger">*</span></label>
                              <input type="file" class="form-control" id="customer_image" name="customer_image">
                            </div>
                        </form>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-lg-12">
                        <button type="submit" class="btn btn-primary" id="submit-contact-image-data" form="contact-image-form">Submit</button>
                        <button class="btn btn-light-danger" data-bs-dismiss="offcanvas"> Close </button>
                      </div>
                    </div>
                  </div>
                </div>';
  }
?>