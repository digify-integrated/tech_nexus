<div class="row">
  <div class="col-lg-12">
    <div class="card">
      <div class="card-header">
        <div class="row align-items-center">
          <div class="col-md-6">
            <h5>Document</h5>
          </div>
          <?php
            if ($documentCreateAccess['total'] > 0) {
               echo '<div class="col-md-6 text-sm-end mt-3 mt-sm-0">
                      <button type="submit" form="add-document-form" class="btn btn-success form-edit" id="submit-data">Save</button>
                      <button type="button" id="discard-create" class="btn btn-outline-danger form-edit">Discard</button>
                    </div>';
            }
          ?>
        </div>
      </div>
      <div class="card-body">
        <form id="add-document-form" method="post" action="#">
            <div class="form-group row">
                <label class="col-lg-2 col-form-label">Document Name <span class="text-danger">*</span></label>
                <div class="col-lg-4">
                    <input type="text" class="form-control" id="document_name" name="document_name" maxlength="100" autocomplete="off">
                </div>
                <label class="col-lg-2 col-form-label">Document Category <span class="text-danger">*</span></label>
                <div class="col-lg-4">
                    <select class="form-control select2" name="document_category_id" id="document_category_id">
                        <option value="">--</option>
                        <?php echo $documentCategoryModel->generateDocumentCategoryOptions(); ?>
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-lg-2 col-form-label">Document <span class="text-danger">*</span></label>
                <div class="col-lg-10">
                    <input type="file" class="form-control" id="document" name="document">
                </div>
            </div>
            <div class="form-group row">
                <label class="col-lg-2 col-form-label">Description</label>
                <div class="col-lg-10">
                    <textarea class="form-control" id="document_description" name="document_description" maxlength="500" rows="3"></textarea>
                </div>
            </div>
        </form>
      </div>
    </div>
  </div>
</div>