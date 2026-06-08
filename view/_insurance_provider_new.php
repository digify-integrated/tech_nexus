<div class="row">
  <div class="col-lg-12">
    <div class="card">
      <div class="card-header">
        <div class="row align-items-center">
          <div class="col-md-6">
            <h5>Insurance Provider</h5>
          </div>
          <?php
            if ($insuranceProviderCreateAccess['total'] > 0) {
               echo '<div class="col-md-6 text-sm-end mt-3 mt-sm-0">
                      <button type="submit" form="insurance-provider-form" class="btn btn-success form-edit" id="submit-data">Save</button>
                      <button type="button" id="discard-create" class="btn btn-outline-danger form-edit">Discard</button>
                    </div>';
            }
          ?>
        </div>
      </div>
      <div class="card-body">
        <form id="insurance-provider-form" method="post" action="#">
            <div class="form-group row">
                <label class="col-lg-2 col-form-label">Name <span class="text-danger">*</span></label>
                <div class="col-lg-4">
                    <input type="text" class="form-control" id="provider_name" name="provider_name" maxlength="100" autocomplete="off">
                </div>
                <label class="col-lg-2 col-form-label">Address <span class="text-danger">*</span></label>
                <div class="col-lg-4">
                    <input type="text" class="form-control" id="address" name="address" maxlength="1000" autocomplete="off">
                </div>
            </div>
            <div class="form-group row">
                <label class="col-lg-2 col-form-label">City <span class="text-danger">*</span></label>
                <div class="col-lg-4">
                    <select class="form-control select2" name="city_id" id="city_id">
                        <option value="">--</option>
                        <?php echo $cityModel->generateCityOptions(); ?>
                    </select>
                </div>
                <label class="col-lg-2 col-form-label">Tax ID </label>
                <div class="col-lg-4">
                    <input type="text" class="form-control" id="tax_id" name="tax_id" maxlength="500" autocomplete="off">
                </div>
            </div>
            <div class="form-group row">
                <label class="col-lg-2 col-form-label">Currency</label>
                <div class="col-lg-4">
                    <select class="form-control select2" name="currency_id" id="currency_id">
                        <option value="">--</option>
                        <?php echo $currencyModel->generateCurrencyOptions(); ?>
                    </select>
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
            <div class="form-group row">
                <label class="col-lg-2 col-form-label">Website</label>
                <div class="col-lg-4">
                    <input type="url" class="form-control" id="website" name="website" maxlength="500" autocomplete="off">
                </div>
            </div>
            
            <div class="row mt-4 mb-2">
              <div class="col-12">
                <h5 class="text-primary">Contact Person Details</h5>
                <hr>
              </div>
            </div>

            <div class="form-group row">
                <label class="col-lg-2 col-form-label">Contact Name</label>
                <div class="col-lg-4">
                    <input type="text" class="form-control" id="contact_person_name" name="contact_person_name" maxlength="500" autocomplete="off">
                </div>
                <label class="col-lg-2 col-form-label">Contact Mobile</label>
                <div class="col-lg-4">
                    <input type="text" class="form-control" id="contact_person_mobile" name="contact_person_mobile" maxlength="20" autocomplete="off">
                </div>
            </div>
            <div class="form-group row">
                <label class="col-lg-2 col-form-label">Contact Telephone</label>
                <div class="col-lg-4">
                    <input type="text" class="form-control" id="contact_person_telephone" name="contact_person_telephone" maxlength="20" autocomplete="off">
                </div>
                <label class="col-lg-2 col-form-label">Contact Email</label>
                <div class="col-lg-4">
                    <input type="email" class="form-control" id="contact_person_email" name="contact_person_email" maxlength="100" autocomplete="off">
                </div>
            </div>
        </form>
      </div>
    </div>
  </div>
</div>