<?php
    if($documentWriteAccess['total'] > 0){
        $updateDocumentButton = '<button class="btn btn-icon btn-link-success" type="button" data-bs-toggle="offcanvas" data-bs-target="#update-document-offcanvas" aria-controls="update-document-offcanvas" id="update-document"><i class="ti ti-pencil"></i></button>';
    }

    if($updateDocumentFile['total'] > 0){
        $updateDocumentFileButton = '<button type="button" class="btn btn-icon btn-success" id="grant-portal-access" data-bs-toggle="offcanvas" data-bs-target="#update-document-file-offcanvas" aria-controls="update-document-file-offcanvas"><i class="ti ti-upload"></i></button>';
    }
?>
<div class="row">
    <div class="col-lg-4">
        <div class="card">
            <div class="card-body position-relative">
                <div class="position-absolute end-0 top-0 p-3">
                    <div class="d-flex flex-wrap gap-2 mb-2">
                        <a href="<?php echo $documentPath; ?>" class="btn btn-icon btn-primary"><i class="ti ti-eye"></i></a>
                        <a href="<?php echo $documentPath; ?>" class="btn btn-icon btn-warning" target="_blank" download="<?php echo $documentName . '.' . $documentExtension ?>"><i class="ti ti-download"></i></a>
                        <?php echo $updateDocumentFileButton; ?>
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
          <div class="col-md-4 col-sm-12">
            <div class="card">
              <div class="card-body">
                <div class="row">
                  <div class="col-md-12">
                    <ul class="nav flex-column nav-pills" role="tablist" aria-orientation="vertical">
                      <li><a class="nav-link active" id="v-document-version-history-tab" data-bs-toggle="pill" href="#v-document-version-history" role="tab" aria-controls="v-document-version-history" aria-selected="true">Version History</a></li>
                      <li><a class="nav-link" id="v-document-comment-tab" data-bs-toggle="pill" href="#v-document-comment" role="tab" aria-controls="v-document-comment" aria-selected="true">Comment</a></li>
                    </ul>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-md-8 col-sm-12">
            <div class="tab-content" id="v-pills-employee-profile-basic-information">
              <div class="tab-pane fade show active" id="v-document-version-history" role="tabpanel" aria-labelledby="v-document-version-history-tab">
                <div class="row">
                  <div class="col-md-12">
                    <div class="card">
                      <div class="card-header">
                        <div class="d-flex align-items-center justify-content-between">
                          <h5>Version History</h5>
                        </div>
                      </div>
                      <div class="card-body" id="document-version-history-summary"></div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="tab-pane fade" id="v-document-comment" role="tabpanel" aria-labelledby="v-document-comment-tab">
                <div class="row">
                  <div class="col-md-12">
                    <div class="card">
                      <div class="card-header">
                        <div class="d-flex align-items-center justify-content-between">
                          <h5>Comment</h5>
                        </div>
                      </div>
                      <div class="card-body" id="comment-summary"></div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
    </div>
</div>

<?php
    if($documentWriteAccess['total'] > 0){
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
                  <div class="col-lg-6">
                    <label class="form-label">Document Name <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="document_name" name="document_name" maxlength="100" autocomplete="off">
                  </div>
                  <div class="col-lg-6 mt-3 mt-lg-0">
                    <label class="form-label">Document Category <span class="text-danger">*</span></label>
                    <select class="form-control offcanvas-select2" name="document_category_id" id="document_category_id">
                      <option value="">--</option>
                      '. $documentCategoryModel->generateDocumentCategoryOptions() .'
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
?>