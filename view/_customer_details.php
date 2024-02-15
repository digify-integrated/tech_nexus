<?php
if($customerWriteAccess['total'] > 0){
    $customerCustomerInformationUpdate = '<button class="btn btn-icon btn-link-success" type="button" data-bs-toggle="offcanvas" data-bs-target="#customer-information-offcanvas" aria-controls="customer-information-offcanvas" id="update-customer-information"><i class="ti ti-pencil"></i></button>';

}
?>

<div class="row">
    <div class="col-lg-3">
        <div class="card">
            <div class="card-body position-relative">
                <div class="position-absolute end-0 top-0 p-3"><?php echo $customerStatus; ?></div>
                <div class="mt-3">
                    <div class="chat-avtar d-inline-flex mx-auto">
                        <?php
                        if($customerWriteAccess['total'] > 0){
                            echo '<form class="user-upload mb-4">
                                    <img src="'. DEFAULT_AVATAR_IMAGE .'" alt="Customer Image" id="emp_image" class="rounded-circle img-fluid wid-70 hei-70">
                                    <label for="customer_image" class="img-avtar-upload"><i class="ti ti-camera f-24 mb-1"></i><span>Upload</span></label>
                                    <input type="file" id="customer_image" name="customer_image" class="d-none">
                                </form>';
                        }
                        else{
                            echo '<img src="'. DEFAULT_AVATAR_IMAGE .'" alt="Customer Image" id="emp_image" class="rounded-circle img-fluid wid-70 hei-70">';
                        }
                        ?>
                    </div>
                    <h5 class="mb-1 text-primary"><?php echo $customerName; ?></h5>
                    <p class="text-sm"><?php echo $customerUniqueID; ?></p>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <div class="d-flex align-items-center justify-content-between">
                    <h5>Customer Information</h5>
                    <?php echo $customerCustomerInformationUpdate; ?>
                </div>
            </div>
            <div class="card-body">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item px-0 pt-0">
                        <div class="col-md-12">
                            <p class="mb-1 text-primary"><b>Nickname</b></p>
                            <p class="mb-0"><?php echo $nickname; ?></p>
                        </div>
                    </li>
                    <li class="list-group-item px-0">
                        <div class="row">
                            <div class="col-md-12">
                                <p class="mb-1 text-primary"><b>Birthday</b></p>
                                <p class="mb-0"><?php echo $birthday; ?></p>
                            </div>
                        </div>
                    </li>
                    <li class="list-group-item px-0">
                        <div class="col-md-12">
                            <p class="mb-1 text-primary"><b>Birth Place</b></p>
                            <p class="mb-0"><?php echo $birthPlace; ?></p>
                        </div>
                    </li>
                    <li class="list-group-item px-0">
                        <div class="row">
                            <div class="col-md-12">
                                <p class="mb-1 text-primary"><b>Gender</b></p>
                                <p class="mb-0"><?php echo $genderName; ?></p>
                            </div>
                        </div>
                    </li>
                    <li class="list-group-item px-0">
                        <div class="col-md-12">
                            <p class="mb-1 text-primary"><b>Civil Status</b></p>
                            <p class="mb-0"><?php echo $civilStatusName; ?></p>
                        </div>
                    </li>
                    <li class="list-group-item px-0">
                        <div class="row">
                            <div class="col-md-12">
                                <p class="mb-1 text-primary"><b>Blood type</b></p>
                                <p class="mb-0"><?php echo $bloodTypeName; ?></p>
                            </div>
                        </div>
                    </li>
                    <li class="list-group-item px-0">
                        <div class="col-md-12">
                            <p class="mb-1 text-primary"><b>Religion</b></p>
                            <p class="mb-0"><?php echo $religionName; ?></p>
                        </div>
                    </li>
                    <li class="list-group-item px-0">
                        <div class="row">
                            <div class="col-md-12">
                                <p class="mb-1 text-primary"><b>Height</b></p>
                                <p class="mb-0"><?php echo $height; ?></p>
                            </div>
                        </div>
                    </li>
                    <li class="list-group-item px-0">
                        <div class="col-md-12">
                            <p class="mb-1 text-primary"><b>Weight</b></p>
                            <p class="mb-0"><?php echo $weight; ?></p>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <div class="col-lg-9">
        <div class="row">
            <div class="col-md-4 col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <ul class="nav flex-column nav-pills" id="v-customer-profile-tab" role="tablist" aria-orientation="vertical">
                                    <li><a class="nav-link active" id="v-customer-profile-address-tab" data-bs-toggle="pill" href="#v-customer-profile-address" role="tab" aria-controls="v-customer-profile-address" aria-selected="false">Address</a></li>
                                    <li><a class="nav-link" id="v-customer-profile-contact-information-tab" data-bs-toggle="pill" href="#v-customer-profile-contact-information" role="tab" aria-controls="v-customer-profile-contact-information" aria-selected="false">Contact Information</a></li>
                                    <li><a class="nav-link" id="v-customer-profile-customer-identification-tab" data-bs-toggle="pill" href="#v-customer-profile-customer-identification" role="tab" aria-controls="v-customer-profile-customer-identification" aria-selected="false">Customer Identification</a></li>
                                    <li><a class="nav-link" id="v-customer-profile-family-background-tab" data-bs-toggle="pill" href="#v-customer-profile-family-background" role="tab" aria-controls="v-customer-profile-family-background" aria-selected="false">Family Background</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-8 col-sm-12">
                <div class="tab-content" id="v-pills-customer-profile-basic-information">
                    <div class="tab-pane fade show active" id="v-customer-profile-address" role="tabpanel" aria-labelledby="v-customer-profile-address-tab">
                        <div class="card">
                            <div class="card-header">
                                <div class="d-flex align-items-center justify-content-between">
                                    <h5>Address</h5>
                                    <?php #echo $customerAddressAdd; ?>
                                </div>
                            </div>
                            <div class="card-body">
                                <ul class="list-group list-group-flush border-top-0" id="contact-address-summary">
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="v-customer-profile-contact-information" role="tabpanel" aria-labelledby="v-customer-profile-contact-information-tab">
                        <div class="card">
                            <div class="card-header">
                                <div class="d-flex align-items-center justify-content-between">
                                    <h5>Contact Information</h5>
                                    <?php #echo $contactInformationAdd; ?>
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
                                    <?php #echo $customerIdentificationAdd; ?>
                                </div>
                            </div>
                            <div class="card-body">
                                <ul class="list-group list-group-flush" id="contact-identification-summary">
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="v-customer-profile-family-background" role="tabpanel" aria-labelledby="v-customer-profile-family-background-tab">
                        <div class="card">
                            <div class="card-header">
                                <div class="d-flex align-items-center justify-content-between">
                                    <h5>Family Background</h5>
                                    <?php #echo $customerFamilyBackgroundAdd; ?>
                                </div>
                            </div>
                            <div class="card-body">
                                <ul class="list-group list-group-flush" id="contact-family-background-summary">
                                </ul>
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
          echo '<div class="offcanvas offcanvas-end" tabindex="-1" id="customer-information-offcanvas" aria-labelledby="customer-information-offcanvas-label">
                  <div class="offcanvas-header">
                    <h2 id="customer-information-offcanvas-label" style="margin-bottom:-0.5rem">Customer Information</h2>
                    <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                  </div>
                  <div class="offcanvas-body">
                    <div class="alert alert-success alert-dismissible mb-4" role="alert">
                      This form is used to collect and record essential personal details, ensuring accuracy and completeness in an individual\'s profile within an organization or database. Users can update information such as name, date of birth, and physical attributes.
                      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    <div class="row">
                      <div class="col-lg-12">
                        <form id="customer-information-form" method="post" action="#">
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
                        <button type="submit" class="btn btn-primary" id="submit-customer-information-data" form="customer-information-form">Submit</button>
                        <button class="btn btn-light-danger" data-bs-dismiss="offcanvas"> Close </button>
                      </div>
                    </div>
                  </div>
                </div>';
        }
?>