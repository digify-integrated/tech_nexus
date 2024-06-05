<div class="row">
  <div class="col-lg-12">
    <div class="card">
      <div class="card-header">
        <div class="row align-items-center">
          <div class="col-md-6">
            <h5>Property</h5>
          </div>
          <?php
            if ($propertyCreateAccess['total'] > 0) {
               echo '<div class="col-md-6 text-sm-end mt-3 mt-sm-0">
                      <button type="submit" form="property-form" class="btn btn-success form-edit" id="submit-data">Save</button>
                      <button type="button" id="discard-create" class="btn btn-outline-danger form-edit">Discard</button>
                    </div>';
            }
          ?>
        </div>
      </div>
      <div class="card-body">
        <form id="property-form" method="post" action="#">
            <div class="form-group row">
                <label class="col-lg-2 col-form-label">Name <span class="text-danger">*</span></label>
                <div class="col-lg-4">
                    <input type="text" class="form-control" id="property_name" name="property_name" maxlength="100" autocomplete="off">
                </div>
                <label class="col-lg-2 col-form-label">Company <span class="text-danger">*</span></label>
                <div class="col-lg-4">
                    <select class="form-control select2" name="company_id" id="company_id">
                        <option value="">--</option>
                        <?php echo $companyModel->generateCompanyOptions(); ?>
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-lg-2 col-form-label">Address <span class="text-danger">*</span></label>
                <div class="col-lg-4">
                    <input type="text" class="form-control" id="address" name="address" maxlength="1000" autocomplete="off">
                </div>
                <label class="col-lg-2 col-form-label">City <span class="text-danger">*</span></label>
                <div class="col-lg-4">
                    <select class="form-control select2" name="city_id" id="city_id">
                        <option value="">--</option>
                        <?php echo $cityModel->generateCityOptions(); ?>
                    </select>
                </div>
            </div>
        </form>
      </div>
    </div>
  </div>
</div>