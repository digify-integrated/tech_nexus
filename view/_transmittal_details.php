<div class="row">
  <div class="col-lg-8">
    <div class="card">
      <div class="card-header">
        <div class="row align-items-center">
          <div class="col-md-6">
            <h5>Transmittal</h5>
          </div>
          <div class="col-md-6 text-sm-end mt-3 mt-sm-0">
            <?php                            
                $dropdown = '<div class="btn-group m-r-5">
                                <button type="button" class="btn btn-outline-secondary dropdown-toggle form-details" data-bs-toggle="dropdown" aria-expanded="false">
                                    Action
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end">';
                                
                if ($transmitTransmittal['total'] > 0 && $transmittalStatus == 'Draft' && $transmitterID == $contact_id){
                  $dropdown .= '<li><button class="dropdown-item" type="button" id="transmit-transmittal-details">Transmit Transmittal</button></li>';
                }
                                              
                if ($retransmitTransmittal['total'] > 0 && $transmittalStatus == 'Received' && ($transmitterID == $contact_id)){
                  $dropdown .= '<li><button class="dropdown-item" type="button" id="retransmit-transmittal-details">Re-Transmit Transmittal</button></li>';
                }
                                              
                if ($receiveTransmittal['total'] > 0 && ($transmittalStatus == 'Transmitted' || $transmittalStatus == 'Re-Transmitted') && ((!empty($receiverID) && $receiverID == $contact_id) || (empty($receiverID) && $contactDepartment == $receiverDepartment))){
                  $dropdown .= '<li><button class="dropdown-item" type="button" id="receive-transmittal-details">Receive Transmittal</button></li>';
                }
                                              
                if ($fileTransmittal['total'] > 0 && $transmittalStatus == 'Received' && ($receiverID == $contact_id || $contactDepartment == $receiverDepartment)){
                  $dropdown .= '<li><button class="dropdown-item" type="button" id="file-transmittal-details">File Transmittal</button></li>';
                }
                                              
                if ($cancelTransmittal['total'] > 0 && ($transmittalStatus == 'Draft' || $transmittalStatus == 'Transmitted' || $transmittalStatus == 'Re-Transmitted') && $transmitterID == $contact_id){
                  $dropdown .= '<li><button class="dropdown-item" type="button" id="cancel-transmittal-details">Cancel Transmittal</button></li>';
                }

                if ($transmittalDeleteAccess['total'] > 0) {
                  $dropdown .= '<li><button class="dropdown-item" type="button" id="delete-transmittal-details">Delete Transmittal</button></li>';
                }
                        
                $dropdown .= '</ul>
                            </div>';
                    
                echo $dropdown;

                if ($transmittalWriteAccess['total'] > 0 && (($transmittalStatus == 'Draft' && $transmitterID == $contact_id) || $transmittalStatus == 'Received')) {
                    echo '<button type="submit" class="btn btn-info form-details" id="edit-form">Edit</button>
                        <button type="submit" form="transmittal-form" class="btn btn-success form-edit d-none" id="submit-data">Save</button>
                        <button type="button" id="discard-update" class="btn btn-outline-danger form-edit d-none">Discard</button>';
                }

                if ($transmittalCreateAccess['total'] > 0) {
                    echo '<a class="btn btn-success m-r-5 form-details" href="transmittal.php?new">Create</a>';
                }
            ?>
          </div>
        </div>
      </div>
      <div class="card-body pc-component">
        <div class="d-flex flex-wrap gap-2 mb-2" id="transmittal_status">
          
        </div>
        <div class="pc-modal-content">
          <form id="transmittal-form" method="post" action="#">
            <?php
              if($transmittalWriteAccess['total'] > 0){
                echo '<div class="form-group row">
                        <label class="col-lg-2 col-form-label">Department <span class="text-danger d-none form-edit">*</span></label>
                          <div class="col-lg-4">
                              <label class="col-form-label form-details fw-normal" id="receiver_department_label"></label>
                              <div class="d-none form-edit">
                                <select class="form-control select2" name="receiver_department" id="receiver_department">
                                  <option value="">--</option>
                                  '. $departmentModel->generateDepartmentOptions() .'
                                </select>
                              </div>
                          </div>
                        <label class="col-lg-2 col-form-label">Specific Person</label>
                          <div class="col-lg-4">
                              <label class="col-form-label form-details fw-normal" id="receiver_id_label"></label>
                              <div class="d-none form-edit">
                                <select class="form-control select2" name="receiver_id" id="receiver_id">
                                  <option value="">--</option>
                                </select>
                              </div>
                          </div>
                      </div>
                      <div class="form-group row d-none form-edit">
                        <label class="col-lg-2 col-form-label">Transmittal Image</label>
                        <div class="col-lg-10">
                          <input type="file" class="form-control" id="transmittal_file" name="transmittal_file">
                        </div>
                      </div>
                      <div class="form-group row">
                        <label class="col-lg-2 col-form-label">Description <span class="text-danger d-none form-edit">*</span></label>
                          <div class="col-lg-10">
                            <label class="col-form-label form-details fw-normal" id="transmittal_description_label"></label>
                            <textarea class="form-control d-none form-edit" id="transmittal_description" name="transmittal_description" maxlength="500" rows="3"></textarea>
                          </div>
                      </div>';
              }
              else{
                echo '<div class="form-group row">
                        <label class="col-lg-2 col-form-label">Department <span class="text-danger d-none form-edit">*</span></label>
                          <div class="col-lg-4">
                            <label class="col-form-label form-details fw-normal" id="receiver_department_label"></label>
                          </div>
                        <label class="col-lg-2 col-form-label">Specific Person</label>
                          <div class="col-lg-4">
                            <label class="col-form-label form-details fw-normal" id="receiver_id_label"></label>
                          </div>
                      </div>
                      <div class="form-group row">
                        <label class="col-lg-2 col-form-label">Description <span class="text-danger d-none form-edit">*</span></label>
                          <div class="col-lg-10">
                            <label class="col-form-label form-details fw-normal" id="transmittal_description_label"></label>
                          </div>
                      </div>';
              }
            ?>
          </form>
        </div>
      </div>
    </div>
  </div>
  <div class="col-lg-4">
    <div class="card">
      <div class="card-header">
        <div class="row align-items-center">
          <div class="col-sm-6">
            <h5>Tramsmittal Image</h5>
          </div>
        </div>
      </div>
      <div class="card-body">
        <img src="<?php echo DEFAULT_PLACEHOLDER_IMAGE; ?>" alt="Transmittal Image" id="transmittal-image" class="img-fluid rounded">
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
                '. $userModel->generateLogNotes('transmittal', $transmittalID) .'
              </div>
            </div>
          </div>
        </div>';
?>
</div>