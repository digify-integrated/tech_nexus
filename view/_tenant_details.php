<div class="row">
  <div class="col-lg-12">
    <div class="card">
      <div class="card-header">
        <div class="row align-items-center">
          <div class="col-md-6">
            <h5>Tenant</h5>
          </div>
          <div class="col-md-6 text-sm-end mt-3 mt-sm-0">
            <?php                            
              $dropdown = '<div class="btn-group m-r-5">
                              <button type="button" class="btn btn-outline-secondary dropdown-toggle form-details" data-bs-toggle="dropdown" aria-expanded="false">
                                Action
                              </button>
                              <ul class="dropdown-menu dropdown-menu-end">';
                        
                if ($tenantDeleteAccess['total'] > 0) {
                    $dropdown .= '<li><button class="dropdown-item" type="button" id="delete-tenant-details">Delete Tenant</button></li>';
                }
                        
                $dropdown .= '</ul>
                            </div>';
                  
              echo $dropdown;

              if ($tenantWriteAccess['total'] > 0) {
                echo '<button type="submit" class="btn btn-info form-details" id="edit-form">Edit</button>
                      <button type="submit" form="tenant-form" class="btn btn-success form-edit d-none" id="submit-data">Save</button>
                      <button type="button" id="discard-update" class="btn btn-outline-danger form-edit d-none">Discard</button>';
              }

              if ($tenantCreateAccess['total'] > 0) {
                echo '<a class="btn btn-success m-r-5 form-details" href="tenant.php?new">Create</a>';
              }
            ?>
          </div>
        </div>
      </div>
      <div class="card-body">
        <div class="row">
          <div class="col-md-12">
            <form id="tenant-form" method="post" action="#">
              <?php
                if($tenantWriteAccess['total'] > 0){
                  echo '<div class="form-group row">
                          <label class="col-lg-2 col-form-label">Name <span class="text-danger d-none form-edit">*</span></label>
                          <div class="col-lg-4">
                            <label class="col-form-label form-details fw-normal" id="tenant_name_label"></label>
                            <input type="text" class="form-control d-none form-edit" id="tenant_name" name="tenant_name" maxlength="100" autocomplete="off">
                          </div>
                          <label class="col-lg-2 col-form-label">Contact Person <span class="text-danger d-none form-edit">*</span></label>
                          <div class="col-lg-4">
                            <label class="col-form-label form-details fw-normal" id="tenant_name_label"></label>
                            <input type="text" class="form-control d-none form-edit" id="contact_person" name="contact_person" maxlength="500" autocomplete="off">
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
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-2 col-form-label">Phone</label>
                            <div class="col-lg-4">
                                <label class="col-form-label form-details fw-normal" id="phone_label"></label>
                                <input type="text" class="form-control d-none form-edit" id="phone" name="phone" maxlength="20" autocomplete="off">
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
                        </div>';
                }
                else{
                  echo '<div class="form-group row">
                            <label class="col-lg-2 col-form-label">Name</label>
                            <div class="col-lg-4">
                                <label class="col-form-label form-details fw-normal" id="tenant_name_label"></label>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-2 col-form-label">Contact Person</label>
                            <div class="col-lg-4">
                                <label class="col-form-label form-details fw-normal" id="contact_person_label"></label>
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
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-2 col-form-label">Phone</label>
                            <div class="col-lg-4">
                                <label class="col-form-label form-details fw-normal" id="phone_label"></label>
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
              '. $userModel->generateLogNotes('tenant', $tenantID) .'
            </div>
          </div>
        </div>
      </div>';
?>
</div>