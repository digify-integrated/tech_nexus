<div class="row">
  <div class="col-lg-12">
    <div class="card">
      <div class="card-header">
        <div class="row align-items-center">
          <div class="col-md-6">
            <h5>Chart of Account</h5>
          </div>
          <?php
            if ($chartOfAccountCreateAccess['total'] > 0) {
               echo '<div class="col-md-6 text-sm-end mt-3 mt-sm-0">
                      <button type="submit" form="chart-of-account-form" class="btn btn-success form-edit" id="submit-data">Save</button>
                      <button type="button" id="discard-create" class="btn btn-outline-danger form-edit">Discard</button>
                    </div>';
            }
          ?>
        </div>
      </div>
      <div class="card-body">
        <form id="chart-of-account-form" method="post" action="#">
          <div class="form-group row">
            <label class="col-lg-2 col-form-label">Code <span class="text-danger">*</span></label>
            <div class="col-lg-4">
              <input type="text" class="form-control" id="code" name="code" maxlength="100" autocomplete="off">
            </div>
            <label class="col-lg-2 col-form-label">Name <span class="text-danger">*</span></label>
            <div class="col-lg-4">
              <input type="text" class="form-control" id="code_name" name="code_name" maxlength="500" autocomplete="off">
            </div>
          </div>
          <div class="form-group row">
            <label class="col-lg-2 col-form-label">Account Type <span class="text-danger">*</span></label>
            <div class="col-lg-4">
                <select class="form-control select2" name="account_type" id="account_type">
                    <option value="">--</option>
                    <option value="Current Assets">Current Assets</option>
                    <option value="Bank and Cash">Bank and Cash</option>
                    <option value="Receivable">Receivable</option>
                    <option value="Non-current Assets">Non-current Assets</option>
                    <option value="Fixed Assets">Fixed Assets</option>
                    <option value="Prepayments">Prepayments</option>
                    <option value="Current Liabilities">Current Liabilities</option>
                    <option value="Payable">Payable</option>
                    <option value="Non-current Liabilities">Non-current Liabilities</option>
                    <option value="Equity">Equity</option>
                    <option value="Income">Income</option>
                    <option value="Expenses">Expenses</option>
                    <option value="Other Income">Other Income</option>
                    <option value="Current Year Earnings">Current Year Earnings</option>
                    <option value="Cost of Revenue">Cost of Revenue</option>
                    <option value="Depreciation">Depreciation</option>
                </select>
            </div>
            <label class="col-lg-2 col-form-label">Archive <span class="text-danger">*</span></label>
            <div class="col-lg-4">
                <select class="form-control select2" name="archived" id="archived">
                    <option value="">--</option>
                    <option value="Yes">Yes</option>
                    <option value="No">No</option>
                </select>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>