<div class="row">
  <div class="col-lg-12">
    <div class="card">
      <div class="card-header">
        <div class="row align-items-center">
          <div class="col-md-6">
            <h5>Contact Directory</h5>
          </div>
          <div class="col-md-6 text-sm-end mt-3 mt-sm-0">
            <?php                            
                $dropdown = '<div class="btn-group m-r-5">
                                <button type="button" class="btn btn-outline-secondary dropdown-toggle form-details" data-bs-toggle="dropdown" aria-expanded="false">
                                    Action
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end">';
                            
                if ($contactDirectoryDeleteAccess['total'] > 0) {
                    $dropdown .= '<li><button class="dropdown-item" type="button" id="delete-contact-directory-details">Delete Contact Directory</button></li>';
                }
                        
                $dropdown .= '</ul>
                            </div>';

                if ($contactDirectoryWriteAccess['total'] > 0) {
                    $dropdown .= '<button type="submit" class="btn btn-info form-details" id="edit-form">Edit</button>
                        <button type="submit" form="contact-directory-form" class="btn btn-success form-edit d-none" id="submit-data">Save</button>
                        <button type="button" id="discard-update" class="btn btn-outline-danger form-edit d-none">Discard</button>';
                }

                if ($contactDirectoryCreateAccess['total'] > 0) {
                    $dropdown .= '<a class="btn btn-success m-r-5 form-details" href="contact-directory.php?new">Create</a>';
                }

                echo $dropdown;
            ?>
          </div>
        </div>
      </div>
      <div class="card-body">
        <form id="contact-directory-form" method="post" action="#">
          <?php
            if($contactDirectoryWriteAccess['total'] > 0){
              echo '<div class="form-group row">
                        <label class="col-lg-2 col-form-label">Name <span class="text-danger">*</span></label>
                        <div class="col-lg-4">
                            <label class="col-form-label form-details fw-normal" id="contact_name_label"></label>
                            <input type="text" class="form-control d-none form-edit" id="contact_name" name="contact_name" maxlength="200" autocomplete="off">
                        </div>
                        <label class="col-lg-2 col-form-label">Position</label>
                        <div class="col-lg-4">
                            <label class="col-form-label form-details fw-normal" id="position_label"></label>
                            <input type="text" class="form-control d-none form-edit" id="position" name="position" maxlength="200" autocomplete="off">
                        </div>
                        </div>
                        <div class="form-group row">
                        <label class="col-lg-2 col-form-label">Location <span class="text-danger">*</span></label>
                        <label class="col-lg-4 col-form-label form-details fw-normal" id="location_label"></label>
                        <div class="col-lg-4 d-none form-edit">
                            <select class="form-control select2" name="location" id="location">
                                <option value="">--</option>
                                <option value="CGMI">CGMI</option>
                                <option value="Fuso Tarlac">Fuso Tarlac</option>
                                <option value="NE Trucks Yard 1">NE Trucks Yard 1</option>
                                <option value="NE Trucks Yard 2">NE Trucks Yard 2</option>
                                <option value="NE Trucks Yard 3">NE Trucks Yard 3</option>
                            </select>
                        </div>
                        <label class="col-lg-2 col-form-label">Directory Type <span class="text-danger">*</span></label>
                        <label class="col-lg-4 col-form-label form-details fw-normal" id="directory_type_label"></label>
                        <div class="col-lg-4 d-none form-edit">
                            <select class="form-control select2" name="directory_type" id="directory_type">
                                <option value="">--</option>
                                <option value="Telephone">Telephone</option>
                                <option value="Mobile">Mobile</option>
                                <option value="Email">Email</option>
                            </select>
                        </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-2 col-form-label">Contact Information <span class="text-danger">*</span></label>
                            <div class="col-lg-10">
                            <label class="col-form-label form-details fw-normal" id="contact_information_label"></label>
                            <input type="text" class="form-control d-none form-edit" id="contact_information" name="contact_information" maxlength="500" autocomplete="off">
                            </div>
                        </div>';
            }
            else{
              echo '<div class="form-group row">
                      <label class="col-lg-2 col-form-label">Name</label>
                      <div class="col-lg-4">
                        <label class="col-form-label form-details fw-normal" id="contact_name_label"></label>
                      </div>
                      <label class="col-lg-2 col-form-label">Position</label>
                      <div class="col-lg-4">
                        <label class="col-form-label form-details fw-normal" id="position_label"></label>
                      </div>
                    </div>
                    <div class="form-group row">
                      <label class="col-lg-2 col-form-label">Location</label>
                      <div class="col-lg-4">
                        <label class="col-form-label form-details fw-normal" id="location_label"></label>
                      </div>
                      <label class="col-lg-2 col-form-label">Directory Type</label>
                      <div class="col-lg-4">
                        <label class="col-form-label form-details fw-normal" id="directory_type_label"></label>
                      </div>
                    </div>
                    <div class="form-group row">
                      <label class="col-lg-2 col-form-label">Contact Information</label>
                      <div class="col-lg-10">
                        <label class="col-form-label form-details fw-normal" id="contact_information_label"></label>
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
                '. $userModel->generateLogNotes('contact_directory', $contactDirectoryID) .'
              </div>
            </div>
          </div>
        </div>';
?>
</div>