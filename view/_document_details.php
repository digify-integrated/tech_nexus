<?php
$checkIfDocumentAuthorizer = $documentModel->checkIfDocumentAuthorizer($contact_id);

if($unpublishDocument['total'] > 0 && ($author == $contact_id || $checkIfDocumentAuthorizer['total'] > 0)){
    $unpublishButton = '<button type="button" class="btn btn-icon btn-danger" id="unpublish-document"><i class="ti ti-x"></i></button>';
}
?>

<div class="row">
    <div class="col-lg-4">
        <div class="card">
            <div class="card-body position-relative">
                <div class="position-absolute end-0 top-0 p-3">
                    <div class="d-flex flex-wrap gap-2 mb-2">
                        <a href="<?php echo $documentPath; ?>" class="btn btn-icon btn-primary" target="_blank"><i class="ti ti-eye"></i></a>
                        <a href="<?php echo $documentPath; ?>" class="btn btn-icon btn-warning" target="_blank" download="<?php echo $documentName . '.' . $documentExtension ?>"><i class="ti ti-download"></i></a>
                        <?php echo $unpublishButton; ?>
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
?>