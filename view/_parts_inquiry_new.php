<div class="row">
  <div class="col-lg-12">
    <div class="card">
      <div class="card-header">
        <div class="row align-items-center">
          <div class="col-md-6">
            <h5>Parts Inquiry</h5>
          </div>
          <?php
            if ($partsInquiryCreateAccess['total'] > 0) {
               echo '<div class="col-md-6 text-sm-end mt-3 mt-sm-0">
                      <button type="submit" form="parts-inquiry-form" class="btn btn-success form-edit" id="submit-data">Save</button>
                      <button type="button" id="discard-create" class="btn btn-outline-danger form-edit">Discard</button>
                    </div>';
            }
          ?>
        </div>
      </div>
      <div class="card-body">
        <form id="parts-inquiry-form" method="post" action="#">
          <div class="form-group row">
            <label class="col-lg-2 col-form-label">Part Number <span class="text-danger">*</span></label>
            <div class="col-lg-4">
              <input type="text" class="form-control" id="parts_number" name="parts_number" maxlength="500" autocomplete="off">
            </div>
            <label class="col-lg-2 col-form-label">Stock <span class="text-danger">*</span></label>
            <div class="col-lg-4">
                <input type="number" class="form-control" id="stock" name="stock" min="0" step="1">
            </div>
          </div>
          <div class="form-group row">
            <label class="col-lg-2 col-form-label">Description <span class="text-danger">*</span></label>
            <div class="col-lg-4">
                <input type="text" class="form-control" id="parts_description" name="parts_description" maxlength="1000" autocomplete="off">
            </div>
            <label class="col-lg-2 col-form-label">Price <span class="text-danger">*</span></label>
            <div class="col-lg-4">
                <input type="number" class="form-control" id="price" name="price" min="0.01" step="0.01">
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>