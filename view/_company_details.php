<div class="row">
  <div class="col-lg-12">
    <div class="card">
      <div class="card-header">
        <div class="row align-items-center">
          <div class="col-md-6">
            <h5>Company</h5>
          </div>
          <div class="col-md-6 text-sm-end mt-3 mt-sm-0">
            <?php                            
              $dropdown = '<div class="btn-group m-r-5">
                              <button type="button" class="btn btn-outline-secondary dropdown-toggle form-details" data-bs-toggle="dropdown" aria-expanded="false">
                                Action
                              </button>
                              <ul class="dropdown-menu dropdown-menu-end">';
                 
              if ($companyDuplicateAccess['total'] > 0) {
                $dropdown .= '<li><button class="dropdown-item" type="button" id="duplicate-company">Duplicate Company</button></li>';
              }
                        
              if ($companyDeleteAccess['total'] > 0) {
                $dropdown .= '<li><button class="dropdown-item" type="button" id="delete-company-details">Delete Company</button></li>';
              }
                      
              $dropdown .= '</ul>
                          </div>';
                  
              echo $dropdown;

              if ($companyWriteAccess['total'] > 0) {
                echo '<button type="submit" class="btn btn-info form-details" id="edit-form">Edit</button>
                      <button type="submit" form="company-form" class="btn btn-success form-edit d-none" id="submit-data">Save</button>
                      <button type="button" id="discard-update" class="btn btn-outline-danger form-edit d-none">Discard</button>';
              }

              if ($companyCreateAccess['total'] > 0) {
                echo '<a class="btn btn-success m-r-5 form-details" href="company.php?new">Create</a>';
              }
            ?>
          </div>
        </div>
      </div>
      <div class="card-body">
        <div class="row">
          <div class="col-md-10">
            <form id="company-form" method="post" action="#">
              <?php
                if($companyWriteAccess['total'] > 0){
                  echo '<div class="form-group row">
                          <label class="col-lg-2 col-form-label">Name <span class="text-danger d-none form-edit">*</span></label>
                          <div class="col-lg-4">
                            <label class="col-form-label form-details fw-normal" id="company_name_label"></label>
                            <input type="text" class="form-control d-none form-edit" id="company_name" name="company_name" maxlength="100" autocomplete="off">
                          </div>
                          <label class="col-lg-2 col-form-label">Address <span class="text-danger d-none form-edit">*</span></label>
                          <div class="col-lg-4">
                              <label class="col-form-label form-details fw-normal" id="address_label"></label>
                              <input type="text" class="form-control d-none form-edit" id="address" name="address" maxlength="1000" autocomplete="off">
                          </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-2 col-form-label">City <span class="text-danger d-none form-edit">*</span></label>
                            <div class="col-lg-4">
                              <label class="col-form-label form-details fw-normal" id="city_id_label"></label>
                              <div class="d-none form-edit">
                                  <select class="form-control select2" name="city_id" id="city_id">
                                  <option value="">--</option>
                                  '. $cityModel->generateCityOptions() .'
                                  </select>
                              </div>
                            </div>
                            <label class="col-lg-2 col-form-label">Tax ID</label>
                            <div class="col-lg-4">
                                <label class="col-form-label form-details fw-normal" id="tax_id_label"></label>
                                <input type="text" class="form-control d-none form-edit" id="tax_id" name="tax_id" maxlength="500" autocomplete="off">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-2 col-form-label">Currency <span class="text-danger d-none form-edit">*</span></label>
                            <div class="col-lg-4">
                              <label class="col-form-label form-details fw-normal" id="currency_id_label"></label>
                              <div class="d-none form-edit">
                                  <select class="form-control select2" name="currency_id" id="currency_id">
                                  <option value="">--</option>
                                  '. $currencyModel->generateCurrencyOptions() .'
                                  </select>
                              </div>
                            </div>
                            <label class="col-lg-2 col-form-label">Phone</label>
                            <div class="col-lg-4">
                                <label class="col-form-label form-details fw-normal" id="phone_label"></label>
                                <input type="text" class="form-control d-none form-edit" id="phone" name="phone" maxlength="20" autocomplete="off">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-2 col-form-label">Mobile</label>
                            <div class="col-lg-4">
                                <label class="col-form-label form-details fw-normal" id="mobile_label"></label>
                                <input type="text" class="form-control d-none form-edit" id="mobile" name="mobile" maxlength="20" autocomplete="off">
                            </div>
                            <label class="col-lg-2 col-form-label">Telephone</label>
                            <div class="col-lg-4">
                                <label class="col-form-label form-details fw-normal" id="telephone_label"></label>
                                <input type="text" class="form-control d-none form-edit" id="telephone" name="telephone" maxlength="20" autocomplete="off">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-2 col-form-label">Email</label>
                            <div class="col-lg-4">
                                <label class="col-form-label form-details fw-normal" id="email_label"></label>
                                <input type="text" class="form-control d-none form-edit" id="email" name="email" maxlength="100" autocomplete="off">
                            </div>
                            <label class="col-lg-2 col-form-label">Website</label>
                            <div class="col-lg-4">
                                <label class="col-form-label form-details fw-normal" id="website_label"></label>
                                <input type="text" class="form-control d-none form-edit" id="website" name="website" maxlength="500" autocomplete="off">
                            </div>
                        </div>';
                }
                else{
                  echo '<div class="form-group row">
                            <label class="col-lg-2 col-form-label">Name</label>
                            <div class="col-lg-4">
                                <label class="col-form-label form-details fw-normal" id="company_name_label"></label>
                            </div>
                            <label class="col-lg-2 col-form-label">Address</label>
                            <div class="col-lg-4">
                                <label class="col-form-label form-details fw-normal" id="address_label"></label>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-2 col-form-label">City</label>
                            <div class="col-lg-4">
                                <label class="col-form-label form-details fw-normal" id="city_id_label"></label>
                            </div>
                            <label class="col-lg-2 col-form-label">Tax ID</label>
                            <div class="col-lg-4">
                                <label class="col-form-label form-details fw-normal" id="tax_id_label"></label>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-2 col-form-label">Currency</label>
                            <div class="col-lg-4">
                                <label class="col-form-label form-details fw-normal" id="currency_id_label"></label>
                            </div>
                            <label class="col-lg-2 col-form-label">Phone</label>
                            <div class="col-lg-4">
                                <label class="col-form-label form-details fw-normal" id="phone_label"></label>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-2 col-form-label">Mobile</label>
                            <div class="col-lg-4">
                                <label class="col-form-label form-details fw-normal" id="mobile_label"></label>
                            </div>
                            <label class="col-lg-2 col-form-label">Telephone</label>
                            <div class="col-lg-4">
                                <label class="col-form-label form-details fw-normal" id="telephone_label"></label>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-2 col-form-label">Email</label>
                            <div class="col-lg-4">
                                <label class="col-form-label form-details fw-normal" id="email_label"></label>
                            </div>
                            <label class="col-lg-2 col-form-label">Website</label>
                            <div class="col-lg-4">
                                <label class="col-form-label form-details fw-normal" id="website_label"></label>
                            </div>
                        </div>';
                }
              ?>
            </form>
          </div>
          <div class="col-md-2">
            <?php
              if($companyWriteAccess['total'] > 0){
                echo '<form class="user-upload mb-4 rounded-0">
                        <img src="'. DEFAULT_COMPANY_LOGO_IMAGE .'" id="company_logo_image" class="img-fluid wid-100 hei-100" alt="Company Logo">
                        <label for="company_logo" class="img-avtar-upload">
                          <i class="ti ti-camera f-24 mb-1"></i>
                          <span>Upload</span>
                        </label>
                        <input type="file" id="company_logo" name="company_logo" class="d-none">
                      </form>';
              }
              else{
                echo '<div class="chat-avtar d-inline-flex mx-auto mb-4">
                        <img class="img-fluid wid-100 hei-100" id="company_logo_image" src="'. DEFAULT_COMPANY_LOGO_IMAGE .'" alt="Company Logo">
                      </div>';
              }
            ?>
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
              '. $userModel->generateLogNotes('company', $companyID) .'
            </div>
          </div>
        </div>
      </div>';
?>
</div>