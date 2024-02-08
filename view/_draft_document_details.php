<?php
    $checkIfDocumentAuthorizer = $documentModel->checkIfDocumentAuthorizer($contact_id);
    $changeDocumentPasswordButton = '';
    $publishButton = '';

    if($draftDocumentWriteAccess['total'] > 0){
        $updateDocumentButton = '<button class="btn btn-icon btn-link-success" type="button" data-bs-toggle="offcanvas" data-bs-target="#update-document-offcanvas" aria-controls="update-document-offcanvas" id="update-document"><i class="ti ti-pencil"></i></button>';
    }

    if($updateDocumentFile['total'] > 0){
        $updateDocumentFileButton = '<button type="button" class="btn btn-icon btn-info" data-bs-toggle="offcanvas" data-bs-target="#update-document-file-offcanvas" aria-controls="update-document-file-offcanvas"><i class="ti ti-upload"></i></button>';
    }

    if($checkIfDocumentAuthorizer['total'] > 0){
        $changeDocumentPasswordButton = '<button type="button" class="btn btn-icon btn-warning" data-bs-toggle="offcanvas" data-bs-target="#change-document-password-offcanvas" aria-controls="change-document-password-offcanvas" id="change-document-password"><i class="ti ti-lock"></i></button>';
    }

    if($publishDocument['total'] > 0 && $checkIfDocumentAuthorizer['total'] > 0){
        $publishButton = '<button type="button" class="btn btn-icon btn-success" id="publish-document"><i class="ti ti-check"></i></button>';
    }

    if($addDocumentDepartmentRestrictions['total'] > 0){
        $addDocumentDepartmentRestrictionsButton = '<button class="btn btn-warning" type="button" data-bs-toggle="offcanvas" data-bs-target="#add-department-restrictions-offcanvas" aria-controls="add-department-restrictions-offcanvas" id="add-department-restrictions">Add Department Restrictions</button>';
    }

    if($addDocumentEmployeeRestrictions['total'] > 0){
        $addDocumentEmployeeRestrictionsButton = '<button class="btn btn-warning" type="button" data-bs-toggle="offcanvas" data-bs-target="#add-employee-restrictions-offcanvas" aria-controls="add-employee-restrictions-offcanvas" id="add-employee-restrictions">Add Employee Restrictions</button>';
    }
?>
<div class="row">
    <div class="col-lg-4">
        <div class="card">
            <div class="card-body position-relative">
                <div class="position-absolute end-0 top-0 p-3">
                    <div class="d-flex flex-wrap gap-2 mb-2">
                        <a href="<?php echo $documentPath; ?>" class="btn btn-icon btn-primary" target="_blank"><i class="ti ti-eye"></i></a>
                        <?php echo $updateDocumentFileButton . $changeDocumentPasswordButton . $publishButton; ?>
                    </div>
                </div>
                <div class="d-flex align-items-end justify-content-between mt-0">
                    <div class="text-truncate">
                        <img src="<?php echo $documentIcon; ?>" alt="img" class="img-fluid mb-4" />
                        <h6 class="mb-1 text-primary"><?php echo $documentName; ?></h6>
                        <p class="mb-0"><?php echo $documentCategoryName; ?></p>
                    </div>
                    <span class="badge bg-info f-12"><?php echo 'Version: ' . $documentVersion; ?></span>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <div class="d-flex align-items-center justify-content-between">
                    <h5>Document Info</h5>
                    <?php echo $updateDocumentButton; ?>
                </div>
            </div>
            <div class="card-body">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item px-0 pt-0">
                        <div class="row">
                            <div class="col-md-12">
                                <p class="mb-1 text-primary"><b>Description</b></p>
                                <p class="mb-0"><?php echo $documentDescription; ?></p>
                            </div> 
                        </div>
                    </li>
                    <li class="list-group-item px-0">
                        <div class="row">
                            <div class="col-md-12">
                                <p class="mb-1 text-primary"><b>Author</b></p>
                                <p class="mb-0"><?php echo $authorName; ?></p>
                            </div> 
                        </div>
                    </li>
                    <li class="list-group-item px-0">
                        <div class="row">
                            <div class="col-md-12">
                                <p class="mb-1 text-primary"><b>Confidential</b></p>
                                <p class="mb-0"><?php echo $confidentialBadge; ?></p>
                            </div> 
                        </div>
                    </li>
                    <li class="list-group-item px-0">
                        <div class="row">
                            <div class="col-md-12">
                                <p class="mb-1 text-primary"><b>Upload Date</b></p>
                                <p class="mb-0"><?php echo $uploadDate; ?></p>
                            </div> 
                        </div>
                    </li>
                    <li class="list-group-item px-0">
                        <div class="row">
                            <div class="col-md-12">
                                <p class="mb-1 text-primary"><b>Publish Date</b></p>
                                <p class="mb-0"><?php echo $publishDate; ?></p>
                            </div> 
                        </div>
                    </li>
                    <li class="list-group-item px-0">
                        <div class="row">
                            <div class="col-md-12">
                                <p class="mb-1 text-primary"><b>Document Size</b></p>
                                <p class="mb-0"><?php echo $systemModel->getFormatBytes($documentSize); ?></p>
                            </div> 
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <div class="col-lg-8">
      <div class="row">
          <div class="col-lg-12">
            <div class="card table-card">
              <div class="card-header">
                <div class="row align-items-center">
                  <div class="col-sm-6">
                    <h5>Department Restrictions</h5>
                  </div>
                  <div class="col-md-6 text-sm-end mt-3 mt-sm-0">
                    <?php echo $addDocumentDepartmentRestrictionsButton; ?>
                  </div>
                </div>
              </div>
              <div class="card-body">
                <div class="dt-responsive table-responsive">
                  <table id="department-restriction-table" class="table table-hover nowrap w-100 dataTable">
                    <thead>
                      <tr>
                        <th>Department</th>
                        <th class="all">Actions</th>
                      </tr>
                    </thead>
                    <tbody></tbody>
                  </table>
                </div>
              </div>
            </div>
            <div class="card table-card">
              <div class="card-header">
                  <div class="row align-items-center">
                    <div class="col-sm-6">
                      <h5>Employee Restrictions</h5>
                    </div>
                    <div class="col-md-6 text-sm-end mt-3 mt-sm-0">
                      <?php echo $addDocumentEmployeeRestrictionsButton; ?>
                    </div>
                  </div>
              </div>
              <div class="card-body">
                <div class="dt-responsive table-responsive">
                  <table id="employee-restriction-table" class="table table-hover nowrap w-100 dataTable">
                    <thead>
                      <tr>
                        <th>Employee</th>
                        <th class="all">Actions</th>
                      </tr>
                    </thead>
                    <tbody></tbody>
                  </table>
                </div>
              </div>
            </div>
            <div class="card">
              <div class="card-header">
                <div class="d-flex align-items-center justify-content-between">
                  <h5>Version History</h5>
                </div>
              </div>
              <div class="card-body">
                <ul class="list-group list-group-flush" id="document-version-history-summary"></ul>
              </div>
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
                    '. $userModel->generateLogNotes('document', $documentID) .'
                  </div>
                </div>
              </div>
            </div>
          </div>';

    if($draftDocumentWriteAccess['total'] > 0){
        echo '<div class="offcanvas offcanvas-end" tabindex="-1" id="update-document-offcanvas" aria-labelledby="update-document-offcanvas-label">
                <div class="offcanvas-header">
                  <h2 id="update-document-offcanvas-label" style="margin-bottom:-0.5rem">Document Information</h2>
                  <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                </div>
                <div class="offcanvas-body">
                  <div class="row">
                    <div class="col-lg-12">
                      <form id="document-update-form" method="post" action="#">
                        <div class="form-group row">
                          <div class="col-lg-4">
                            <label class="form-label">Document Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="document_name" name="document_name" maxlength="100" autocomplete="off">
                          </div>
                          <div class="col-lg-4 mt-3 mt-lg-0">
                            <label class="form-label">Document Category <span class="text-danger">*</span></label>
                            <select class="form-control offcanvas-select2" name="document_category_id" id="document_category_id">
                              <option value="">--</option>
                              '. $documentCategoryModel->generateDocumentCategoryOptions() .'
                            </select>
                          </div>
                          <div class="col-lg-4 mt-3 mt-lg-0">
                            <label class="form-label">Confidential <span class="text-danger">*</span></label>
                            <select class="form-control offcanvas-select2" name="is_confidential" id="is_confidential">
                              <option value="">--</option>
                              <option value="Yes">Yes</option>
                              <option value="No">No</option>
                            </select>
                          </div>
                        </div>
                        <div class="form-group row">
                          <div class="col-lg-12">
                            <label class="form-label">Description</label>
                            <textarea class="form-control" id="document_description" name="document_description" maxlength="500" rows="3"></textarea>
                          </div>
                        </div>
                      </form>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-lg-12">
                      <button type="submit" class="btn btn-primary" id="submit-update-document-data" form="document-update-form">Submit</button>
                      <button class="btn btn-light-danger" data-bs-dismiss="offcanvas"> Close </button>
                    </div>
                  </div>
                </div>
              </div>';
    }
    
    if($updateDocumentFile['total'] > 0){
        echo '<div class="offcanvas offcanvas-end" tabindex="-1" id="update-document-file-offcanvas" aria-labelledby="update-document-file-offcanvas-label">
        <div class="offcanvas-header">
          <h2 id="update-document-file-offcanvas-label" style="margin-bottom:-0.5rem">Update Document File</h2>
          <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
          <div class="row">
            <div class="col-lg-12">
              <form id="document-file-update-form" method="post" action="#">
                <div class="form-group row">
                  <div class="col-lg-12">
                    <label class="form-label">Document File <span class="text-danger">*</span></label>
                    <input type="file" class="form-control" id="document_file" name="document_file">
                  </div>
                </div>
              </form>
            </div>
          </div>
          <div class="row">
            <div class="col-lg-12">
              <button type="submit" class="btn btn-primary" id="submit-update-document-file-data" form="document-file-update-form">Submit</button>
              <button class="btn btn-light-danger" data-bs-dismiss="offcanvas"> Close </button>
            </div>
          </div>
        </div>
      </div>';
    }

    if($addDocumentDepartmentRestrictions['total'] > 0){
      echo '<div class="offcanvas offcanvas-end" tabindex="-1" id="add-department-restrictions-offcanvas" aria-labelledby="add-department-restrictions-offcanvas-label">
              <div class="offcanvas-header">
                <h2 id="add-department-restrictions-offcanvas-label" style="margin-bottom:-0.5rem">Add Department Restriction</h2>
                <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
              </div>
              <div class="offcanvas-body">
                <div class="row">
                  <div class="col-lg-12">
                    <form id="add-department-restrictions-form" method="post" action="#">
                      <div class="row">
                        <div class="col-md-12">
                          <table id="add-department-restrictions-table" class="table table-hover nowrap w-100 dataTable">
                            <thead>
                              <tr>
                                <th>Department</th>
                                <th class="all">Assign</th>
                              </tr>
                            </thead>
                            <tbody></tbody>
                          </table>
                        </div>
                      </div>
                    </form>
                  </div>
                </div>
                <div class="row mt-4">
                  <div class="col-lg-12">
                  <button type="submit" class="btn btn-primary" id="submit-add-department-restrictions" form="add-department-restrictions-form">Submit</button>
                    <button class="btn btn-light-danger" data-bs-dismiss="offcanvas"> Close </button>
                  </div>
                </div>
              </div>
            </div>';
    }

    if($addDocumentEmployeeRestrictions['total'] > 0){
      echo '<div class="offcanvas offcanvas-end" tabindex="-1" id="add-employee-restrictions-offcanvas" aria-labelledby="add-employee-restrictions-offcanvas-label">
              <div class="offcanvas-header">
                <h2 id="add-employee-restrictions-offcanvas-label" style="margin-bottom:-0.5rem">Add Employee Restriction</h2>
                <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
              </div>
              <div class="offcanvas-body">
                <div class="row">
                  <div class="col-lg-12">
                    <form id="add-employee-restrictions-form" method="post" action="#">
                      <div class="row">
                        <div class="col-md-12">
                          <table id="add-employee-restrictions-table" class="table table-hover nowrap w-100 dataTable">
                            <thead>
                              <tr>
                                <th>Employee</th>
                                <th class="all">Assign</th>
                              </tr>
                            </thead>
                            <tbody></tbody>
                          </table>
                        </div>
                      </div>
                    </form>
                  </div>
                </div>
                <div class="row mt-4">
                  <div class="col-lg-12">
                  <button type="submit" class="btn btn-primary" id="submit-add-employee-restrictions" form="add-employee-restrictions-form">Submit</button>
                    <button class="btn btn-light-danger" data-bs-dismiss="offcanvas"> Close </button>
                  </div>
                </div>
              </div>
            </div>';
    }
?>