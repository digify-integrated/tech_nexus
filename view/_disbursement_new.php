<div class="row">
  <div class="col-lg-12">
    <div class="card">
      <div class="card-header">
        <div class="row align-items-center">
          <div class="col-md-6">
            <h5>Disbursement</h5>
          </div>
          <?php
            if ($disbursementCreateAccess['total'] > 0) {
               echo '<div class="col-md-6 text-sm-end mt-3 mt-sm-0">
                      <button type="submit" form="disbursement-form" class="btn btn-success form-edit" id="submit-data">Save</button>
                      <button type="button" id="discard-create" class="btn btn-outline-danger form-edit">Discard</button>
                    </div>';
            }
          ?>
        </div>
      </div>
      <div class="card-body">
        <form id="disbursement-form" method="post" action="#">
          <div class="form-group row">
            <label class="col-lg-2 col-form-label">Payable Type <span class="text-danger">*</span></label>
            <div class="col-lg-4">
                <select class="form-control select2" name="payable_type" id="payable_type">
                  <option value="Customer" selected>Customer</option>
                  <option value="Miscellaneous">Miscellaneous</option>
                </select>
            </div>
            <label class="col-lg-2 col-form-label">Customer <span class="text-danger">*</span></label>
            <div class="col-lg-4" id="customer-select">
                <select class="form-control select2" name="customer_id" id="customer_id">
                  <option value="">--</option>
                  <?php echo $customerModel->generateAllContactsOptions(); ?>
                </select>
            </div>
            <div class="col-lg-4 d-none" id="misc-select">
                <select class="form-control select2" name="misc_id" id="misc_id">
                  <option value="">--</option>
                  <?php echo $miscellaneousClientModel->generateMiscellaneousClientOptions(); ?>
                </select>
            </div>
          </div>
          <div class="form-group row">
            <label class="col-lg-2 col-form-label">Department</label>
            <div class="col-lg-4">
            <select class="form-control select2" name="department_id" id="department_id">
                  <option value="">--</option>
                  <?php echo $departmentModel->generateDepartmentOptions(); ?>
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
          <div class="form-group row">
            <label class="col-lg-2 col-form-label">Transaction Type <span class="text-danger">*</span></label>
            <div class="col-lg-4">
              <select class="form-control select2" name="transaction_type" id="transaction_type">
                <option value="">--</option>
                <option value="Replenishment">Replenishment</option>
                <option value="Disbursement">Disbursement</option>
               </select>
            </div>
            <label class="col-lg-2 col-form-label">Fund Source <span class="text-danger">*</span></label>
            <div class="col-lg-4">
              <select class="form-control select2" name="fund_source" id="fund_source">
                <option value="">--</option>
                <option value="Petty Cash">Petty Cash</option>
                <option value="Revolving Fund">Revolving Fund</option>
                <option value="Check">Check</option>
               </select>
            </div>
          </div>
          <div class="form-group row">
            <label class="col-lg-2 col-form-label">Particulars <span class="text-danger">*</span></label>
            <div class="col-lg-10">
              <textarea class="form-control" id="particulars" name="particulars" maxlength="500"></textarea>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>