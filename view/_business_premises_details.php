<div class="row">
  <div class="col-lg-12">
    <div class="card">
      <div class="card-header">
        <div class="row align-items-center">
          <div class="col-md-6">
            <h5>Business Premises</h5>
          </div>
          <div class="col-md-6 text-sm-end mt-3 mt-sm-0">
            <?php                            
                $dropdown = '<div class="btn-group m-r-5">
                                <button type="button" class="btn btn-outline-secondary dropdown-toggle form-details" data-bs-toggle="dropdown" aria-expanded="false">
                                    Action
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end">';

                if ($businessPremisesDuplicateAccess['total'] > 0) {
                    $dropdown .= '<li><button class="dropdown-item" type="button" id="duplicate-business-premises">Duplicate Business Premises</button></li>';
                }
                            
                if ($businessPremisesDeleteAccess['total'] > 0) {
                    $dropdown .= '<li><button class="dropdown-item" type="button" id="delete-business-premises-details">Delete Business Premises</button></li>';
                }
                        
                $dropdown .= '</ul>
                            </div>';
                    
                echo $dropdown;

                if ($businessPremisesWriteAccess['total'] > 0) {
                    echo '<button type="submit" class="btn btn-info form-details" id="edit-form">Edit</button>
                        <button type="submit" form="business-premises-form" class="btn btn-success form-edit d-none" id="submit-data">Save</button>
                        <button type="button" id="discard-update" class="btn btn-outline-danger form-edit d-none">Discard</button>';
                }

                if ($businessPremisesCreateAccess['total'] > 0) {
                    echo '<a class="btn btn-success m-r-5 form-details" href="business-premises.php?new">Create</a>';
                }
            ?>
          </div>
        </div>
      </div>
      <div class="card-body">
        <form id="business-premises-form" method="post" action="#">
          <?php
            if($businessPremisesWriteAccess['total'] > 0){
              echo '<div class="form-group row">
                      <label class="col-lg-2 col-form-label">Name <span class="text-danger d-none form-edit">*</span></label>
                      <div class="col-lg-10">
                        <label class="col-form-label form-details fw-normal" id="business_premises_name_label"></label>
                        <input type="text" class="form-control d-none form-edit" id="business_premises_name" name="business_premises_name" maxlength="100" autocomplete="off">
                      </div>
                    </div>';
            }
            else{
              echo '<div class="form-group row">
                      <label class="col-lg-2 col-form-label">Name</label>
                      <div class="col-lg-10">
                        <label class="col-form-label form-details fw-normal" id="business_premises_name_label"></label>
                      </div>
                    </div>';
            }
          ?>
        </form>
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
                '. $userModel->generateLogNotes('business_premises', $businessPremisesID) .'
              </div>
            </div>
          </div>
        </div>';
?>
</div>