<?php
$checkIfDocumentAuthorizer = $documentModel->checkIfDocumentAuthorizer($contact_id);

$unpublishButton = '';
if($unpublishDocument['total'] > 0 && ($author == $contact_id || $checkIfDocumentAuthorizer['total'] > 0 || $fullAccessToDocuments['total'] > 0)){
    $unpublishButton = '<button type="button" class="btn btn-icon btn-danger" id="unpublish-document"><i class="ti ti-x"></i></button>';
}

if($isConfidential == 'Yes' || !empty($documentPassword)){
    $previewButton = '<button type="button" class="btn btn-icon btn-primary" data-bs-toggle="offcanvas" data-bs-target="#preview-protected-document-offcanvas" aria-controls="preview-protected-document-offcanvas" id="preview-protected-document" id="preview-protected-document"><i class="ti ti-eye"></i></button>';
}
else{
    $previewButton = '<a href="'. $documentPath .'" class="btn btn-icon btn-primary" target="_blank"><i class="ti ti-eye"></i></a>
    <a href="'. $documentPath .'" class="btn btn-icon btn-warning" target="_blank" download="'. $documentPath .'.'. $documentExtension .'"><i class="ti ti-download"></i></a>';
}

?>

<div class="row">
    <div class="col-lg-4">
        <div class="card">
            <div class="card-body position-relative">
                <div class="position-absolute end-0 top-0 p-3">
                    <div class="d-flex flex-wrap gap-2 mb-2">
                        
                        <?php echo $previewButton . $unpublishButton; ?>
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

    if($isConfidential == 'Yes' || !empty($documentPassword)){
        echo '<div class="offcanvas offcanvas-end" tabindex="-1" id="preview-protected-document-offcanvas" aria-labelledby="preview-protected-document-offcanvas-label">
                <div class="offcanvas-header">
                  <h2 id="preview-protected-document-offcanvas-label" style="margin-bottom:-0.5rem">Preview Document</h2>
                  <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                </div>
                <div class="offcanvas-body">
                  <div class="row">
                    <div class="col-lg-12">
                      <form id="preview-protected-document-form" method="post" action="#">
                        <div class="form-group row">
                          <div class="col-lg-12">
                            <label class="form-label">Document Password <span class="text-danger">*</span></label>
                            <input type="password" class="form-control" id="document_password" name="document_password">
                          </div>
                        </div>
                      </form>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-lg-12">
                      <button type="submit" class="btn btn-primary" id="submit-preview-protected-document-data" form="preview-protected-document-form">Preview Document</button>
                      <button class="btn btn-light-danger" data-bs-dismiss="offcanvas"> Close </button>
                    </div>
                  </div>
                </div>
              </div>';
    }
?>