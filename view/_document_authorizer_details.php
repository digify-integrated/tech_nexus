<div class="row">
  <div class="col-lg-12">
    <div class="card">
      <div class="card-header">
        <div class="row align-items-center">
          <div class="col-md-6">
            <h5>Document Authorizer</h5>
          </div>
          <div class="col-md-6 text-sm-end mt-3 mt-sm-0">
            <?php                            
                $dropdown = '<div class="btn-group m-r-5">
                                <button type="button" class="btn btn-outline-secondary dropdown-toggle form-details" data-bs-toggle="dropdown" aria-expanded="false">
                                    Action
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end">';
                            
                if ($documentAuthorizerDeleteAccess['total'] > 0) {
                    $dropdown .= '<li><button class="dropdown-item" type="button" id="delete-document-authorizer-details">Delete Document Authorizer</button></li>';
                }
                        
                $dropdown .= '</ul>
                            </div>';
                    
                echo $dropdown;

                if ($documentAuthorizerCreateAccess['total'] > 0) {
                    echo '<a class="btn btn-success m-r-5 form-details" href="document-authorizer.php?new">Create</a>';
                }
            ?>
          </div>
        </div>
      </div>
      <div class="card-body">
        <div class="form-group row">
            <label class="col-lg-2 col-form-label">Authorizer</label>
            <div class="col-lg-4">
                <label class="col-form-label form-details fw-normal" id="authorizer_id_label"></label>
            </div>
            <label class="col-lg-2 col-form-label">Department</label>
            <div class="col-lg-4">
                <label class="col-form-label form-details fw-normal" id="department_id_label"></label>
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
                '. $userModel->generateLogNotes('document_authorizer', $documentAuthorizerID) .'
              </div>
            </div>
          </div>
        </div>';
?>
</div>