<div class="row">
  <div class="col-lg-12">
    <div class="card">
      <div class="card-header">
        <div class="row align-items-center">
          <div class="col-md-6">
            <h5>State</h5>
          </div>
          <?php
            if ($stateCreateAccess['total'] > 0) {
               echo '<div class="col-md-6 text-sm-end mt-3 mt-sm-0">
                      <button type="submit" form="state-form" class="btn btn-success form-edit" id="submit-data">Save</button>
                      <button type="button" id="discard-create" class="btn btn-outline-danger form-edit">Discard</button>
                    </div>';
            }
          ?>
        </div>
      </div>
      <div class="card-body">
        <form id="state-form" method="post" action="#">
          <div class="form-group row">
            <label class="col-lg-2 col-form-label">State Name <span class="text-danger">*</span></label>
            <div class="col-lg-4">
              <input type="text" class="form-control" id="state_name" name="state_name" maxlength="100" autocomplete="off">
            </div>
            <label class="col-lg-2 col-form-label">State Code <span class="text-danger">*</span></label>
            <div class="col-lg-4">
                <input type="text" class="form-control" id="state_code" name="state_code" maxlength="5" autocomplete="off">
            </div>
          </div>
          <div class="form-group row">
            <label class="col-lg-2 col-form-label">Country <span class="text-danger">*</span></label>
            <div class="col-lg-4">
                <select class="form-control select2" name="country_id" id="country_id">
                    <option value="">--</option>
                    <?php echo $countryModel->generateCountryOptions(); ?>
                </select>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>