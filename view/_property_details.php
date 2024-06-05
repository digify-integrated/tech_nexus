<div class="row">
  <div class="col-lg-12">
    <div class="card">
      <div class="card-header">
        <div class="row align-items-center">
          <div class="col-md-6">
            <h5>Property</h5>
          </div>
          <div class="col-md-6 text-sm-end mt-3 mt-sm-0">
            <?php                            
              $dropdown = '<div class="btn-group m-r-5">
                              <button type="button" class="btn btn-outline-secondary dropdown-toggle form-details" data-bs-toggle="dropdown" aria-expanded="false">
                                Action
                              </button>
                              <ul class="dropdown-menu dropdown-menu-end">';
                        
                if ($propertyDeleteAccess['total'] > 0) {
                    $dropdown .= '<li><button class="dropdown-item" type="button" id="delete-property-details">Delete Property</button></li>';
                }
                        
                $dropdown .= '</ul>
                            </div>';
                  
              echo $dropdown;

              if ($propertyWriteAccess['total'] > 0) {
                echo '<button type="submit" class="btn btn-info form-details" id="edit-form">Edit</button>
                      <button type="submit" form="property-form" class="btn btn-success form-edit d-none" id="submit-data">Save</button>
                      <button type="button" id="discard-update" class="btn btn-outline-danger form-edit d-none">Discard</button>';
              }

              if ($propertyCreateAccess['total'] > 0) {
                echo '<a class="btn btn-success m-r-5 form-details" href="property.php?new">Create</a>';
              }
            ?>
          </div>
        </div>
      </div>
      <div class="card-body">
        <div class="row">
          <div class="col-md-12">
            <form id="property-form" method="post" action="#">
              <?php
                if($propertyWriteAccess['total'] > 0){
                  echo '<div class="form-group row">
                          <label class="col-lg-2 col-form-label">Name <span class="text-danger d-none form-edit">*</span></label>
                          <div class="col-lg-4">
                            <label class="col-form-label form-details fw-normal" id="property_name_label"></label>
                            <input type="text" class="form-control d-none form-edit" id="property_name" name="property_name" maxlength="100" autocomplete="off">
                          </div>
                          <label class="col-lg-2 col-form-label">Company ID <span class="text-danger d-none form-edit">*</span></label>
                          <div class="col-lg-4">
                              <label class="col-form-label form-details fw-normal" id="company_id_label"></label>
                              <div class="d-none form-edit">
                                  <select class="form-control select2" name="company_id" id="company_id">
                                  <option value="">--</option>
                                  '. $companyModel->generateCompanyOptions() .'
                                  </select>
                              </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-2 col-form-label">Address <span class="text-danger d-none form-edit">*</span></label>
                            <div class="col-lg-4">
                                <label class="col-form-label form-details fw-normal" id="address_label"></label>
                                <input type="text" class="form-control d-none form-edit" id="address" name="address" maxlength="1000" autocomplete="off">
                            </div>
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
                        </div>';
                }
                else{
                  echo '<div class="form-group row">
                            <label class="col-lg-2 col-form-label">Name</label>
                            <div class="col-lg-10">
                                <label class="col-form-label form-details fw-normal" id="property_name_label"></label>
                            </div>
                            <label class="col-lg-2 col-form-label">Company</label>
                            <div class="col-lg-4">
                                <label class="col-form-label form-details fw-normal" id="company_id_label"></label>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-2 col-form-label">Address</label>
                            <div class="col-lg-4">
                                <label class="col-form-label form-details fw-normal" id="address_label"></label>
                            </div>
                            <label class="col-lg-2 col-form-label">City</label>
                            <div class="col-lg-4">
                                <label class="col-form-label form-details fw-normal" id="city_id_label"></label>
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
              '. $userModel->generateLogNotes('property', $propertyID) .'
            </div>
          </div>
        </div>
      </div>';
?>
</div>