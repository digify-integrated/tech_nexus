<div class="row">
  <div class="col-lg-12">
    <div class="card">
      <div class="card-header">
        <div class="row align-items-center">
          <div class="col-md-6">
            <h5>Internal DR</h5>
          </div>
          <?php
            if ($internalDRCreateAccess['total'] > 0) {
               echo '<div class="col-md-6 text-sm-end mt-3 mt-sm-0">
                      <button type="submit" form="internal-dr-form" class="btn btn-success form-edit" id="submit-data">Save</button>
                      <button type="button" id="discard-create" class="btn btn-outline-danger form-edit">Discard</button>
                    </div>';
            }
          ?>
        </div>
      </div>
      <div class="card-body">
        <form id="internal-dr-form" method="post" action="#">
          <div class="form-group row">
            <label class="col-lg-2 col-form-label">Release To <span class="text-danger">*</span></label>
            <div class="col-lg-4">
              <input type="text" class="form-control" id="release_to" name="release_to" maxlength="1000" autocomplete="off">
            </div>
            <label class="col-lg-2 col-form-label">Release To Mobile <span class="text-danger">*</span></label>
            <div class="col-lg-4">
                <input type="text" class="form-control" id="release_mobile" name="release_mobile" maxlength="50" autocomplete="off">
            </div>
          </div>
          <div class="form-group row">
            <label class="col-lg-2 col-form-label">Release To Address <span class="text-danger">*</span></label>
            <div class="col-lg-10">
                <textarea class="form-control" id="release_address" name="release_address" maxlength="1000" row="3"></textarea>
            </div>
          </div>
          <div class="form-group row">
            <label class="col-lg-2 col-form-label">DR Number <span class="text-danger">*</span></label>
            <div class="col-lg-4">
                <input type="text" class="form-control" id="dr_number" name="dr_number" maxlength="50" autocomplete="off">
            </div>
            <label class="col-lg-2 col-form-label">DR Type <span class="text-danger">*</span></label>
            <div class="col-lg-4">
                <select class="form-control select2" name="dr_type" id="dr_type">
                    <option value="">--</option>
                    <option value="Unit">Unit</option>
                    <option value="Fuel">Fuel</option>
                    <option value="Repair">Repair</option>
                    <option value="Parts">Parts</option>
                </select>
            </div>
          </div>
            <div class="form-group row">
                <label class="col-lg-2 col-form-label">Stock Number </label>
                <div class="col-lg-4">
                    <input type="text" class="form-control" id="stock_number" name="stock_number" maxlength="100" autocomplete="off">
                </div>
                <label class="col-lg-2 col-form-label">Engine Number </label>
                <div class="col-lg-4">
                    <input type="text" class="form-control" id="engine_number" name="engine_number" maxlength="100" autocomplete="off">
                </div>
            </div>
            <div class="form-group row">
                <label class="col-lg-2 col-form-label">Chassis Number </label>
                <div class="col-lg-4">
                    <input type="text" class="form-control" id="chassis_number" name="chassis_number" maxlength="100" autocomplete="off">
                </div>
                <label class="col-lg-2 col-form-label">Plate Number </label>
                <div class="col-lg-4">
                    <input type="text" class="form-control" id="plate_number" name="plate_number" maxlength="100" autocomplete="off">
                </div>
            </div>
            <div class="form-group row">
                <label class="col-lg-2 col-form-label">Product Description <span class="text-danger">*</span></label>
                <div class="col-lg-10">
                    <textarea class="form-control" id="product_description" name="product_description" maxlength="1000" row="3"></textarea>
                </div>
            </div>
        </form>
      </div>
    </div>
  </div>
</div>