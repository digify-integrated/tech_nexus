<div class="row">
  <div class="col-lg-12">
    <div class="card">
      <div class="card-header">
        <div class="row align-items-center">
          <div class="col-md-6">
            <h5>Purchase Request</h5>
          </div>
          <?php
            if ($purchaseRequestCreateAccess['total'] > 0) {
               echo '<div class="col-md-6 text-sm-end mt-3 mt-sm-0">
                      <button type="submit" form="purchase-request-form" class="btn btn-success form-edit" id="submit-data">Save</button>
                      <button type="button" id="discard-create" class="btn btn-outline-danger form-edit">Discard</button>
                    </div>';
            }

            
          ?>
        </div>
      </div>
      <div class="card-body">
        <form id="purchase-request-form" method="post" action="#">
          <div class="form-group row">
            <label class="col-lg-2 col-form-label">Purchase Request Type <span class="text-danger">*</span></label>
            <div class="col-lg-4">
              <select class="form-control select2" name="purchase_request_type" id="purchase_request_type">
                <option value="">--</option>
                <option value="Product">Product</option>
                <option value="Supplies">Supplies</option>
                <option value="Others">Others</option>
              </select>
            </div>
            <label class="col-lg-2 col-form-label">Company <span class="text-danger">*</span></label>
            <div class="col-lg-4">
              <select class="form-control select2" name="company_id" id="company_id">
                <option value="">--</option>
                <?php echo $companyModel->generateCompanyOptions(); ?>
              </select>
            </div>
          </div>
          <div class="form-group row d-none" id="supply_coverage">
            <label class="col-lg-2 col-form-label">Month <span class="text-danger">*</span></label>
            <div class="col-lg-4">
              <select class="form-control select2" name="month_coverage" id="month_coverage" <?php echo $disabled; ?>>
                <option value="">--</option>
                <?php echo $systemModel->generateMonthOptions(); ?>
              </select>
            </div>
            <label class="col-lg-2 col-form-label">Coverage <span class="text-danger">*</span></label>
            <div class="col-lg-4">
              <select class="form-control select2" name="coverage_period" id="coverage_period" <?php echo $disabled; ?>>
                <option value="">--</option>
                <option value="1st Half">1st Half</option>
                <option value="2nd Half">2nd Half</option>
              </select>
            </div>
          </div>
          <div class="form-group row">
            <label class="col-lg-2 col-form-label">Remarks</label>
            <div class="col-lg-10">
              <textarea class="form-control" id="remarks" name="remarks" maxlength="2000"></textarea>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>