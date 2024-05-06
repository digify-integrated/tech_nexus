<div class="row">
  <div class="col-xl-12">
    <div class="card">
      <div class="card-body border-bottom">
        <div class="row align-items-center">
          <div class="col">
            <h5 class="mb-0">Details</h5>
          </div>
          <div class="col-auto">
            <button type="submit" form="add-leasing-application-form" class="btn btn-success" id="submit-data">Submit</button>
          </div>
        </div>
      </div>
      <div class="card-body">
        <form id="add-leasing-application-form" method="post" action="#">
          <div class="form-group row">
            <label class="col-lg-4 col-form-label">Renewal Tag : <span class="text-danger">*</span></label>
            <div class="col-lg-8">
              <select class="form-control select2" name="renewal_tag" id="renewal_tag">
                <option value="">--</option>
                <option value="New">New</option>
                <option value="Renewal">Renewal</option>
              </select>
            </div>
          </div>
          <div class="form-group row">
            <label class="col-lg-4 col-form-label">Tenant : <span class="text-danger">*</span></label>
            <div class="col-lg-8">
              <select class="form-control select2" name="tenant_id" id="tenant_id">
                <option value="">--</option>
                <?php echo $tenantModel->generateTenantOptions(); ?>
              </select>
            </div>
          </div>
          <div class="form-group row">
            <label class="col-lg-4 col-form-label">Property : <span class="text-danger">*</span></label>
            <div class="col-lg-8">
              <select class="form-control select2" name="property_id" id="property_id">
                <option value="">--</option>
                <?php echo $propertyModel->generatePropertyOptions(); ?>
              </select>
            </div>
          </div>
          <div class="form-group row">
            <label class="col-lg-4 col-form-label">Term : <span class="text-danger">*</span></label>
            <div class="col-lg-2">
              <input type="number" class="form-control" id="term_length" name="term_length" step="1" value="1" min="1">
            </div>
            <div class="col-lg-6">
              <select class="form-control select2" name="term_type" id="term_type">
                <option value="">--</option>
                <option value="Months">Months</option>
              </select>
            </div>
          </div>
          <div class="form-group row">
            <label class="col-lg-4 col-form-label">Payment Frequency : <span class="text-danger">*</span></label>
            <div class="col-lg-8">
              <select class="form-control select2" name="payment_frequency" id="payment_frequency">
                <option value="">--</option>
                <option value="Monthly">Monthly</option>
              </select>
            </div>
          </div>
          <div class="form-group row">
            <label class="col-lg-4 col-form-label">Contract Effectivity Date : <span class="text-danger">*</span></label>
            <div class="col-lg-8">
              <div class="input-group date">
                <input type="text" class="form-control regular-datepicker" id="contract_date" name="contract_date" autocomplete="off">
                <span class="input-group-text">
                  <i class="feather icon-calendar"></i>
                </span>
              </div>
            </div>
          </div>
          <div class="form-group row">
            <label class="col-lg-4 col-form-label">First Rental Due : <span class="text-danger">*</span></label>
            <div class="col-lg-8">
              <div class="input-group date">
                <input type="text" class="form-control regular-datepicker" id="start_date" name="start_date" autocomplete="off">
                <span class="input-group-text">
                  <i class="feather icon-calendar"></i>
                </span>
              </div>
            </div>
          </div>
          <div class="form-group row">
            <label class="col-lg-4 col-form-label">Maturity Date : <span class="text-danger">*</span></label>
            <div class="col-lg-8">
              <input type="text" class="form-control text-uppercase" id="maturity_date" name="maturity_date" readonly>
            </div>
          </div>
          <div class="form-group row">
            <label class="col-lg-4 col-form-label">Initial Basic Rental : <span class="text-danger">*</span></label>
            <div class="col-lg-8">
              <input type="number" class="form-control" id="initial_basic_rental" name="initial_basic_rental" step="0.01" min="0">
            </div>
          </div>
          <div class="form-group row">
            <label class="col-lg-4 col-form-label">Escalation Rate : <span class="text-danger">*</span></label>
            <div class="col-lg-8">
              <input type="number" class="form-control" id="escalation_rate" name="escalation_rate" step="0.01" min="0">
            </div>
          </div>
          <div class="form-group row">
            <label class="col-lg-4 col-form-label">VAT : <span class="text-danger">*</span></label>
            <div class="col-lg-8">
              <select class="form-control select2" name="vat" id="vat">
                <option value="">--</option>
                <option value="Yes">Yes</option>
                <option value="No">No</option>
              </select>
            </div>
          </div>
          <div class="form-group row">
            <label class="col-lg-4 col-form-label">Witholding Tax : <span class="text-danger">*</span></label>
            <div class="col-lg-8">
              <select class="form-control select2" name="witholding_tax" id="witholding_tax">
                <option value="">--</option>
                <option value="Yes">Yes</option>
                <option value="No">No</option>
              </select>
            </div>
          </div>
          <div class="form-group row">
            <label class="col-lg-4 col-form-label">Security Deposit : <span class="text-danger">*</span></label>
            <div class="col-lg-8">
              <input type="number" class="form-control" id="security_deposit" name="security_deposit" step="0.01" min="0">
            </div>
          </div>
          <div class="form-group row">
            <label class="col-lg-4 col-form-label">Floor Area : <span class="text-danger">*</span></label>
            <div class="col-lg-8">
              <input type="number" class="form-control" id="floor_area" name="floor_area" step="0.01" min="0">
            </div>
          </div>
          <div class="form-group row">
            <label class="col-lg-4 col-form-label">Remarks :</label>
            <div class="col-lg-8">
              <textarea class="form-control" id="remarks" name="remarks" maxlength="500"></textarea>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>