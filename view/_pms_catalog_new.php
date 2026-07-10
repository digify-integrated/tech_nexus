<div class="row">
  <div class="col-lg-12">
    <div class="card">
      <div class="card-header">
        <div class="row align-items-center">
          <div class="col-md-6">
            <h5>PMS Catalog</h5>
          </div>
          <?php
            if ($pmsCatalogCreateAccess['total'] > 0) {
               echo '<div class="col-md-6 text-sm-end mt-3 mt-sm-0">
                      <button type="submit" form="pms-catalog-form" class="btn btn-success form-edit" id="submit-data">Save</button>
                      <button type="button" id="discard-create" class="btn btn-outline-danger form-edit">Discard</button>
                    </div>';
            }
          ?>
        </div>
      </div>
      <div class="card-body">
        <form id="pms-catalog-form" method="post" action="#">
          <div class="form-group row">
            <label class="col-lg-2 col-form-label">Name <span class="text-danger">*</span></label>
            <div class="col-lg-10">
              <input type="text" class="form-control" id="pms_catalog_name" name="pms_catalog_name" maxlength="100" autocomplete="off">
            </div>
          </div>
          <div class="form-group row">
            <label class="col-lg-2 col-form-label">Description <span class="text-danger">*</span></label>
            <div class="col-lg-10">
                <input type="text" class="form-control" id="description" name="description" maxlength="200" autocomplete="off">
            </div>
          </div>
          <div class="form-group row">
            <label class="col-lg-2 col-form-label">Company <span class="text-danger">*</span></label>
              <div class="col-lg-10">
                <select class="form-control select2" name="company_id" id="company_id">
                  <option value="">--</option>
                  <?php echo $companyModel->generateCompanyOptions(); ?>
                </select>
              </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>