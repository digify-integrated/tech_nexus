<div class="row">
  <div class="col-lg-12">
    <div class="card">
      <div class="card-header">
        <div class="row align-items-center">
          <div class="col-md-6">
            <h5>Miscellaneous Client</h5>
          </div>
          <div class="col-md-6 text-sm-end mt-3 mt-sm-0">
            <?php                            
                $dropdown = '<div class="btn-group m-r-5">
                                <button type="button" class="btn btn-outline-secondary dropdown-toggle form-details" data-bs-toggle="dropdown" aria-expanded="false">
                                    Action
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end">';

                if ($miscellaneousClientDuplicateAccess['total'] > 0) {
                    $dropdown .= '<li><button class="dropdown-item" type="button" id="duplicate-miscellaneous-client">Duplicate Miscellaneous Client</button></li>';
                }
                            
                if ($miscellaneousClientDeleteAccess['total'] > 0) {
                    $dropdown .= '<li><button class="dropdown-item" type="button" id="delete-miscellaneous-client-details">Delete Miscellaneous Client</button></li>';
                }
                        
                $dropdown .= '</ul>
                            </div>';
                    
                echo $dropdown;

                if ($miscellaneousClientWriteAccess['total'] > 0) {
                    echo '<button type="submit" class="btn btn-info form-details" id="edit-form">Edit</button>
                        <button type="submit" form="miscellaneous-client-form" class="btn btn-success form-edit d-none" id="submit-data">Save</button>
                        <button type="button" id="discard-update" class="btn btn-outline-danger form-edit d-none">Discard</button>';
                }

                if ($miscellaneousClientCreateAccess['total'] > 0) {
                    echo '<a class="btn btn-success m-r-5 form-details" href="miscellaneous-client.php?new">Create</a>';
                }
            ?>
          </div>
        </div>
      </div>
      <div class="card-body">
        <form id="miscellaneous-client-form" method="post" action="#">
          <?php
            if($miscellaneousClientWriteAccess['total'] > 0){
              echo '<div class="form-group row">
                      <label class="col-lg-2 col-form-label">Name <span class="text-danger d-none form-edit">*</span></label>
                      <div class="col-lg-10">
                        <label class="col-form-label form-details fw-normal" id="client_name_label"></label>
                        <input type="text" class="form-control d-none form-edit" id="client_name" name="client_name" maxlength="2000" autocomplete="off">
                      </div>
                    </div>
                    <div class="form-group row">
                      <label class="col-lg-2 col-form-label">Address</label>
                      <div class="col-lg-10">
                        <label class="col-form-label form-details fw-normal" id="address_label"></label>
                        <input type="text" class="form-control d-none form-edit" id="address" name="address" maxlength="5000" autocomplete="off">
                      </div>
                    </div>
                    <div class="form-group row">
                      <label class="col-lg-2 col-form-label">TIN</label>
                      <div class="col-lg-10">
                        <label class="col-form-label form-details fw-normal" id="tin_label"></label>
                        <input type="text" class="form-control d-none form-edit" id="tin" name="tin" maxlength="50" autocomplete="off">
                      </div>
                    </div>';
            }
            else{
              echo '<div class="form-group row">
                      <label class="col-lg-2 col-form-label">Name</label>
                      <div class="col-lg-10">
                        <label class="col-form-label form-details fw-normal" id="client_name_label"></label>
                      </div>
                    </div>
                    <div class="form-group row">
                      <label class="col-lg-2 col-form-label">Address</label>
                      <div class="col-lg-10">
                        <label class="col-form-label form-details fw-normal" id="address_label"></label>
                      </div>
                    </div>
                    <div class="form-group row">
                      <label class="col-lg-2 col-form-label">TIN</label>
                      <div class="col-lg-10">
                        <label class="col-form-label form-details fw-normal" id="tin_label"></label>
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
                '. $userModel->generateLogNotes('miscellaneous_client', $miscellaneousClientID) .'
              </div>
            </div>
          </div>
        </div>';
?>
</div>