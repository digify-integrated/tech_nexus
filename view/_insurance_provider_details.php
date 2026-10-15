<div class="row">
  <div class="col-lg-12">
    <div class="card">
      <div class="card-header">
        <div class="row align-items-center">
          <div class="col-md-6">
            <h5>Insurance Provider</h5>
          </div>
          <div class="col-md-6 text-sm-end mt-3 mt-sm-0">
            <?php                                           
              $dropdown = '<div class="btn-group m-r-5">
                              <button type="button" class="btn btn-outline-secondary dropdown-toggle form-details" data-bs-toggle="dropdown" aria-expanded="false">
                                Action
                              </button>
                              <ul class="dropdown-menu dropdown-menu-end">';
                 
              if ($insuranceProviderDuplicateAccess['total'] > 0) {
                $dropdown .= '<li><button class="dropdown-item" type="button" id="duplicate-insurance-provider">Duplicate Insurance Provider</button></li>';
              }
                        
              if ($insuranceProviderDeleteAccess['total'] > 0) {
                $dropdown .= '<li><button class="dropdown-item" type="button" id="delete-insurance-provider-details">Delete Insurance Provider</button></li>';
              }
                      
              $dropdown .= '</ul>
                          </div>';
                  
              echo $dropdown;

              if ($insuranceProviderWriteAccess['total'] > 0) {
                echo '<button type="submit" class="btn btn-info form-details" id="edit-form">Edit</button>
                      <button type="submit" form="insurance-provider-form" class="btn btn-success form-edit d-none" id="submit-data">Save</button>
                      <button type="button" id="discard-update" class="btn btn-outline-danger form-edit d-none">Discard</button>';
              }

              if ($insuranceProviderCreateAccess['total'] > 0) {
                echo '<a class="btn btn-success m-r-5 form-details" href="insurance-provider.php?new">Create</a>';
              }
            ?>
          </div>
        </div>
      </div>
      <div class="card-body">
        <div class="row">
          <div class="col-md-12">
            <form id="insurance-provider-form" method="post" action="#">
              <?php
                if($insuranceProviderWriteAccess['total'] > 0){
                  echo '<div class="form-group row">
                          <label class="col-lg-2 col-form-label">Name <span class="text-danger d-none form-edit">*</span></label>
                          <div class="col-lg-4">
                            <label class="col-form-label form-details fw-normal" id="provider_name_label"></label>
                            <input type="text" class="form-control d-none form-edit" id="provider_name" name="provider_name" maxlength="100" autocomplete="off">
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
                            <label class="col-lg-2 col-form-label">Currency</label>
                            <div class="col-lg-4">
                              <label class="col-form-label form-details fw-normal" id="currency_id_label"></label>
                              <div class="d-none form-edit">
                                  <select class="form-control select2" name="currency_id" id="currency_id">
                                  <option value="">--</option>
                                  '. $currencyModel->generateCurrencyOptions() .'
                                  </select>
                              </div>
                            </div>
                            <label class="col-lg-2 col-form-label">Mobile</label>
                            <div class="col-lg-4">
                                <label class="col-form-label form-details fw-normal" id="mobile_label"></label>
                                <input type="text" class="form-control d-none form-edit" id="mobile" name="mobile" maxlength="20" autocomplete="off">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-2 col-form-label">Telephone</label>
                            <div class="col-lg-4">
                                <label class="col-form-label form-details fw-normal" id="telephone_label"></label>
                                <input type="text" class="form-control d-none form-edit" id="telephone" name="telephone" maxlength="20" autocomplete="off">
                            </div>
                            <label class="col-lg-2 col-form-label">Email</label>
                            <div class="col-lg-4">
                                <label class="col-form-label form-details fw-normal" id="email_label"></label>
                                <input type="text" class="form-control d-none form-edit" id="email" name="email" maxlength="100" autocomplete="off">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-2 col-form-label">Website</label>
                            <div class="col-lg-4">
                                <label class="col-form-label form-details fw-normal" id="website_label"></label>
                                <input type="text" class="form-control d-none form-edit" id="website" name="website" maxlength="500" autocomplete="off">
                            </div>
                            <label class="col-lg-2 col-form-label">Local Govt. Tax Rate</label>
                            <div class="col-lg-4">
                                <label class="col-form-label form-details fw-normal" id="local_government_tax_rate_label"></label>
                                <input type="number" class="form-control d-none form-edit" id="local_government_tax_rate" name="local_government_tax_rate" value="0" min="0" step="0.01">
                            </div>
                        </div>
                        
                        <div class="row mt-4 mb-2">
                          <div class="col-12">
                            <h5 class="text-primary">Contact Person Details</h5>
                            <hr>
                          </div>
                        </div>
                        
                        <div class="form-group row">
                            <label class="col-lg-2 col-form-label">Contact Name</label>
                            <div class="col-lg-4">
                                <label class="col-form-label form-details fw-normal" id="contact_person_name_label"></label>
                                <input type="text" class="form-control d-none form-edit" id="contact_person_name" name="contact_person_name" maxlength="500" autocomplete="off">
                            </div>
                            <label class="col-lg-2 col-form-label">Contact Mobile</label>
                            <div class="col-lg-4">
                                <label class="col-form-label form-details fw-normal" id="contact_person_mobile_label"></label>
                                <input type="text" class="form-control d-none form-edit" id="contact_person_mobile" name="contact_person_mobile" maxlength="20" autocomplete="off">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-2 col-form-label">Contact Telephone</label>
                            <div class="col-lg-4">
                                <label class="col-form-label form-details fw-normal" id="contact_person_telephone_label"></label>
                                <input type="text" class="form-control d-none form-edit" id="contact_person_telephone" name="contact_person_telephone" maxlength="20" autocomplete="off">
                            </div>
                            <label class="col-lg-2 col-form-label">Contact Email</label>
                            <div class="col-lg-4">
                                <label class="col-form-label form-details fw-normal" id="contact_person_email_label"></label>
                                <input type="text" class="form-control d-none form-edit" id="contact_person_email" name="contact_person_email" maxlength="100" autocomplete="off">
                            </div>
                        </div>';
                }
                else{
                  echo '<div class="form-group row">
                            <label class="col-lg-2 col-form-label">Name</label>
                            <div class="col-lg-4">
                                <label class="col-form-label form-details fw-normal" id="provider_name_label"></label>
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
                            <label class="col-lg-2 col-form-label">Mobile</label>
                            <div class="col-lg-4">
                                <label class="col-form-label form-details fw-normal" id="mobile_label"></label>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-2 col-form-label">Telephone</label>
                            <div class="col-lg-4">
                                <label class="col-form-label form-details fw-normal" id="telephone_label"></label>
                            </div>
                            <label class="col-lg-2 col-form-label">Email</label>
                            <div class="col-lg-4">
                                <label class="col-form-label form-details fw-normal" id="email_label"></label>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-2 col-form-label">Website</label>
                            <div class="col-lg-4">
                                <label class="col-form-label form-details fw-normal" id="website_label"></label>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-2 col-form-label">Local Govt. Tax Rate</label>
                            <div class="col-lg-4">
                                <label class="col-form-label form-details fw-normal" id="local_government_tax_rate_label"></label>
                            </div>
                        </div>
                        
                        <div class="row mt-4 mb-2">
                          <div class="col-12">
                            <h5 class="text-primary">Contact Person Details</h5>
                            <hr>
                          </div>
                        </div>
                        
                        <div class="form-group row">
                            <label class="col-lg-2 col-form-label">Contact Name</label>
                            <div class="col-lg-4">
                                <label class="col-form-label form-details fw-normal" id="contact_person_name_label"></label>
                            </div>
                            <label class="col-lg-2 col-form-label">Contact Mobile</label>
                            <div class="col-lg-4">
                                <label class="col-form-label form-details fw-normal" id="contact_person_mobile_label"></label>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-2 col-form-label">Contact Telephone</label>
                            <div class="col-lg-4">
                                <label class="col-form-label form-details fw-normal" id="contact_person_telephone_label"></label>
                            </div>
                            <label class="col-lg-2 col-form-label">Contact Email</label>
                            <div class="col-lg-4">
                                <label class="col-form-label form-details fw-normal" id="contact_person_email_label"></label>
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
  <div class="col-lg-6">
    <?php
      $addCoverage = '';
      if($insuranceProviderWriteAccess['total'] > 0){
        $addCoverage = '<button class="btn btn-warning" type="button" data-bs-toggle="offcanvas" data-bs-target="#coverage-offcanvas" aria-controls="coverage-offcanvas" id="add-coverage">Add Coverage</button>';
      }
    ?>

    <div class="card table-card">
      <div class="card-header">
        <div class="row align-items-center">
          <div class="col-sm-6">
            <h5>Coverage</h5>
          </div>
          <div class="col-md-6 text-sm-end mt-3 mt-sm-0">
              <?php echo $addCoverage; ?>
          </div>
        </div>
      </div>
      <div class="card-body">
        <div class="table-responsive dt-responsive">
          <table id="coverage-table" class="table table-hover wrap w-100">
            <thead>
              <tr>
                <th>Coverage</th>
                <th>BI</th>
                <th>PD</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody class="text-wrap"></tbody>
            </table>
        </div>
      </div>
    </div>
  </div>
  <div class="col-lg-6">
    <div class="card table-card">
      <div class="card-header">
        <div class="row align-items-center">
          <div class="col-sm-12">
            <h5>Coverage</h5>
          </div>
        </div>
      </div>
      <div class="card-body">
        <div class="row">
          <div class="col-md-12">
            <form id="category-rate-form">
              <div id="category-rates-container" class="p-3"></div>
              <button type="submit" id="submit-category-rate" class="btn btn-primary ms-3 ">Save Category Rates</button>
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
              '. $userModel->generateLogNotes('insurance_provider', $providerID) .'
            </div>
          </div>
        </div>
      </div>';
?>
</div>

<div>
  <div class="offcanvas offcanvas-end" tabindex="-1" id="coverage-offcanvas" aria-labelledby="coverage-offcanvas-label">
    <div class="offcanvas-header">
      <h2 id="coverage-offcanvas-label" style="margin-bottom:-0.5rem">Coverage</h2>
      <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
      <div class="row">
        <div class="col-lg-12">
          <form id="coverage-form" method="post" action="#">
             <input type="hidden" id="insurance_coverage_rate_id" name="insurance_coverage_rate_id">
            <div class="form-group row">
              <div class="col-lg-12">
                <label class="form-label">Coverage Range <span class="text-danger">*</span></label>
              </div>
            </div>
            <div class="form-group row">
              <div class="col-lg-6">
                <input type="number" class="form-control" id="coverage_amount_start" name="coverage_amount_start" value="0" step="0.01">
              </div>
              <div class="col-lg-6">
                <input type="number" class="form-control" id="coverage_amount_end" name="coverage_amount_end" value="0" step="0.01">
              </div>
            </div>
            <div class="form-group row">
              <div class="col-lg-6 mt-3 mt-lg-0">
                <label class="form-label">BI <span class="text-danger">*</span></label>
                <input type="number" class="form-control" id="bi_rate" name="bi_rate" value="0" step="0.01">
              </div>
              <div class="col-lg-6 mt-3 mt-lg-0">
                <label class="form-label">PD <span class="text-danger">*</span></label>
                <input type="number" class="form-control" id="pd_amount" name="pd_amount" value="0" step="0.01">
              </div>
            </div>
          </form>
        </div>
      </div>
      <div class="row">
        <div class="col-lg-12">
          <button type="submit" class="btn btn-primary" id="submit-coverage" form="coverage-form">Submit</button>
          <button class="btn btn-light-danger" data-bs-dismiss="offcanvas"> Close </button>
        </div>
      </div>
    </div>
  </div>
</div>