 <div class="row">
          <div class="col-lg-12">
            <div class="card">
              <div class="card-header">
                <div class="row align-items-center">
                  <div class="col-md-6">
                    <h5>File Extension</h5>
                  </div>
                  <div class="col-md-6 text-sm-end mt-3 mt-sm-0">
                    <?php                            
                       if (!empty($fileExtensionID)) {
                          $dropdown = '<div class="btn-group m-r-5">
                                  <button type="button" class="btn btn-outline-secondary dropdown-toggle form-details" data-bs-toggle="dropdown" aria-expanded="false">
                                      Action
                                  </button>
                                  <ul class="dropdown-menu dropdown-menu-end">';
                            
                            if ($fileExtensionDuplicateAccess['total'] > 0) {
                                $dropdown .= '<li><button class="dropdown-item" type="button" data-file-extension-id="' . $fileExtensionID . '" id="duplicate-file-extension">Duplicate File Extension</button></li>';
                            }
                            
                            if ($fileExtensionDeleteAccess['total'] > 0) {
                                $dropdown .= '<li><button class="dropdown-item" type="button" data-file-extension-id="' . $fileExtensionID . '" id="delete-file-extension-details">Delete File Extension</button></li>';
                            }
                          
                            $dropdown .= '</ul>
                              </div>';
                      
                          echo $dropdown;
                      }

                      if (!empty($fileExtensionID) && $fileExtensionWriteAccess['total'] > 0) {
                        echo '<button type="submit" class="btn btn-info form-details" id="edit-form">Edit</button>
                              <button type="submit" form="file-extension-form" class="btn btn-success form-edit d-none" id="submit-data">Save</button>
                              <button type="button" id="discard-update" class="btn btn-outline-danger form-edit d-none">Discard</button>';
                      }

                      if (!empty($fileExtensionID) && $fileExtensionCreateAccess['total'] > 0) {
                        echo '<a class="btn btn-success m-r-5 form-details" href="file-extension.php?new">Create</a>';
                      }
                    ?>
                  </div>
                </div>
              </div>
              <div class="card-body">
                <form id="file-extension-form" method="post" action="#">
                      <?php
                        if(!empty($fileExtensionID) && $fileExtensionWriteAccess['total'] > 0){
                           echo '<div class="form-group row">
                                    <label class="col-lg-2 col-form-label">File Extension Name <span class="text-danger d-none form-edit">*</span></label>
                                    <div class="col-lg-4">
                                        <label class="col-form-label form-details fw-normal" id="file_extension_name_label"></label>
                                        <input type="text" class="form-control d-none form-edit" id="file_extension_name" name="file_extension_name" maxlength="100" autocomplete="off">
                                    </div>
                                    <label class="col-lg-2 col-form-label">File Type <span class="text-danger d-none form-edit">*</span></label>
                                    <div class="col-lg-4">
                                        <div class="col-form-label form-details fw-normal" id="file_type_id_label"></div>
                                        <div class="d-none form-edit">
                                            <select class="form-control select2" name="file_type_id" id="file_type_id">
                                                <option value="">--</option>
                                                '. $fileTypeModel->generateFileTypeOptions() .'
                                            </select>
                                        </div>
                                    </div>
                                </div>';
                        }
                        else{
                          echo '<div class="form-group row">
                                    <label class="col-lg-2 col-form-label">File Extension Name</label>
                                    <div class="col-lg-4">
                                        <label class="col-form-label form-details fw-normal" id="file_extension_label"></label>
                                    </div>
                                    <label class="col-lg-2 col-form-label">File Type</label>
                                    <div class="col-lg-4">
                                        <div class="col-form-label form-details fw-normal" id="file_type_id_label"></div>
                                    </div>
                                </div>';
                        }
                      ?>
                </form>
            </div>
          </div>
          <?php
          if(!empty($fileExtensionID)){
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
                            '. $userModel->generateLogNotes('file_extension', $fileExtensionID) .'
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>';
           
            }
        ?>