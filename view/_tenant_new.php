<div class="row">
  <div class="col-lg-12">
    <div class="card">
      <div class="card-header">
        <div class="row align-items-center">
          <div class="col-md-6">
            <h5>Tenant</h5>
          </div>
          <?php
            if ($tenantCreateAccess['total'] > 0) {
               echo '<div class="col-md-6 text-sm-end mt-3 mt-sm-0">
                      <button type="submit" form="tenant-form" class="btn btn-success form-edit" id="submit-data">Save</button>
                      <button type="button" id="discard-create" class="btn btn-outline-danger form-edit">Discard</button>
                    </div>';
            }
          ?>
        </div>
      </div>
      <div class="card-body">
        <form id="tenant-form" method="post" action="#">
          <div class="form-group row">
                <label class="col-lg-2 col-form-label">Name <span class="text-danger">*</span></label>
                <div class="col-lg-10">
                    <input type="text" class="form-control" id="tenant_name" name="tenant_name" maxlength="100" autocomplete="off">
                </div>
            </div>
            <div class="form-group row">
                <label class="col-lg-2 col-form-label">Contact Person <span class="text-danger">*</span></label>
                <div class="col-lg-10">
                    <input type="text" class="form-control" id="contact_person" name="contact_person" maxlength="500" autocomplete="off">
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
            <div class="form-group row">
                <label class="col-lg-2 col-form-label">Phone</label>
                <div class="col-lg-4">
                    <input type="text" class="form-control" id="phone" name="phone" maxlength="20" autocomplete="off">
                </div>
                <label class="col-lg-2 col-form-label">Mobile</label>
                <div class="col-lg-4">
                    <input type="text" class="form-control" id="mobile" name="mobile" maxlength="20" autocomplete="off">
                </div>
            </div>
            <div class="form-group row">
                <label class="col-lg-2 col-form-label">Telephone</label>
                <div class="col-lg-4">
                    <input type="text" class="form-control" id="telephone" name="telephone" maxlength="20" autocomplete="off">
                </div>
                <label class="col-lg-2 col-form-label">Email</label>
                <div class="col-lg-4">
                    <input type="email" class="form-control" id="email" name="email" maxlength="100" autocomplete="off">
                </div>
            </div>
        </form>
      </div>
    </div>
  </div>
</div>