<div class="row">
  <div class="col-lg-12">
    <div class="card">
      <div class="card-header">
        <div class="row align-items-center">
          <div class="col-md-6">
            <h5>Country</h5>
          </div>
          <?php
            if ($countryCreateAccess['total'] > 0) {
               echo '<div class="col-md-6 text-sm-end mt-3 mt-sm-0">
                      <button type="submit" form="country-form" class="btn btn-success form-edit" id="submit-data">Save</button>
                      <button type="button" id="discard-create" class="btn btn-outline-danger form-edit">Discard</button>
                    </div>';
            }
          ?>
        </div>
      </div>
      <div class="card-body">
        <form id="country-form" method="post" action="#">
          <div class="form-group row">
            <label class="col-lg-2 col-form-label">Country Name <span class="text-danger">*</span></label>
            <div class="col-lg-4">
              <input type="text" class="form-control" id="country_name" name="country_name" maxlength="100" autocomplete="off">
            </div>
            <label class="col-lg-2 col-form-label">Country Code <span class="text-danger">*</span></label>
            <div class="col-lg-4">
                <input type="text" class="form-control" id="country_code" name="country_code" maxlength="5" autocomplete="off">
            </div>
          </div>
          <div class="form-group row">
            <label class="col-lg-2 col-form-label">Phone Code <span class="text-danger">*</span></label>
            <div class="col-lg-4">
                <input type="text" class="form-control" id="phone_code" name="phone_code" maxlength="20" autocomplete="off">
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>