<div class="row">
  <div class="col-lg-12">
    <div class="card">
      <div class="card-header">
        <div class="row align-items-center">
          <div class="col-md-6">
            <h5>City</h5>
          </div>
          <div class="col-md-6 text-sm-end mt-3 mt-sm-0">
            <?php                            
              $dropdown = '<div class="btn-group m-r-5">
                              <button type="button" class="btn btn-outline-secondary dropdown-toggle form-details" data-bs-toggle="dropdown" aria-expanded="false">
                                Action
                              </button>
                              <ul class="dropdown-menu dropdown-menu-end">';
                 
              if ($cityDuplicateAccess['total'] > 0) {
                $dropdown .= '<li><button class="dropdown-item" type="button" id="duplicate-city">Duplicate City</button></li>';
              }
                        
              if ($cityDeleteAccess['total'] > 0) {
                $dropdown .= '<li><button class="dropdown-item" type="button" id="delete-city-details">Delete City</button></li>';
              }
                      
              $dropdown .= '</ul>
                          </div>';
                  
              echo $dropdown;

              if ($cityWriteAccess['total'] > 0) {
                echo '<button type="submit" class="btn btn-info form-details" id="edit-form">Edit</button>
                      <button type="submit" form="city-form" class="btn btn-success form-edit d-none" id="submit-data">Save</button>
                      <button type="button" id="discard-update" class="btn btn-outline-danger form-edit d-none">Discard</button>';
              }

              if ($cityCreateAccess['total'] > 0) {
                echo '<a class="btn btn-success m-r-5 form-details" href="city.php?new">Create</a>';
              }
            ?>
          </div>
        </div>
      </div>
      <div class="card-body">
        <form id="city-form" method="post" action="#">
          <?php
            if($cityWriteAccess['total'] > 0){
              echo '<div class="form-group row">
                      <label class="col-lg-2 col-form-label">City Name <span class="text-danger d-none form-edit">*</span></label>
                      <div class="col-lg-4">
                        <label class="col-form-label form-details fw-normal" id="city_name_label"></label>
                        <input type="text" class="form-control d-none form-edit" id="city_name" name="city_name" maxlength="100" autocomplete="off">
                      </div>
                      <label class="col-lg-2 col-form-label">State <span class="text-danger d-none form-edit">*</span></label>
                      <div class="col-lg-4">
                        <label class="col-form-label form-details fw-normal" id="state_id_label"></label>
                        <div class="d-none form-edit">
                            <select class="form-control select2" name="state_id" id="state_id">
                            <option value="">--</option>
                            '. $stateModel->generateStateOptions() .'
                            </select>
                        </div>
                      </div>
                    </div>';
            }
            else{
              echo '<div class="form-group row">
                      <label class="col-lg-2 col-form-label">City Name</label>
                      <div class="col-lg-4">
                        <label class="col-form-label form-details fw-normal" id="city_name_label"></label>
                      </div>
                      <label class="col-lg-2 col-form-label">State</label>
                      <div class="col-lg-4">
                        <label class="col-form-label form-details fw-normal" id="state_id_label"></label>
                      </div>
                    </div>';
            }
          ?>
        </form>
      </div>
    </div>
  </div>
</div>
<?php
echo '<div class="row">
        <div class="col-lg-12">
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
                '. $userModel->generateLogNotes('city', $cityID) .'
              </div>
            </div>
          </div>
        </div>
      </div>';
?>