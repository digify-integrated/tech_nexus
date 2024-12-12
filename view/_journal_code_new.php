<div class="row">
  <div class="col-lg-12">
    <div class="card">
      <div class="card-header">
        <div class="row align-items-center">
          <div class="col-md-6">
            <h5>Journal Code</h5>
          </div>
          <?php
            if ($journalCodeCreateAccess['total'] > 0) {
               echo '<div class="col-md-6 text-sm-end mt-3 mt-sm-0">
                      <button type="submit" form="journal-code-form" class="btn btn-success form-edit" id="submit-data">Save</button>
                      <button type="button" id="discard-create" class="btn btn-outline-danger form-edit">Discard</button>
                    </div>';
            }
          ?>
        </div>
      </div>
      <div class="card-body">
        <form id="journal-code-form" method="post" action="#">
          <div class="form-group row">
            <label class="col-lg-2 col-form-label">Company <span class="text-danger">*</span></label>
            <div class="col-lg-4">
              <select class="form-control select2" name="company_id" id="company_id">
                <option value="">--</option>
                <option value="1">CGMI</option>
                <option value="2">NE TRUCK</option>
                <option value="3">FUSO</option>
              </select>
            </div>
            <label class="col-lg-2 col-form-label">Transaction Type <span class="text-danger">*</span></label>
            <div class="col-lg-4">
              <select class="form-control select2" name="transaction_type" id="transaction_type">
                <option value="">--</option>
                <option value="1">COD</option>
                <option value="2">INSTALLMENT SALES</option>
                <option value="3">BANK FINANCING</option>
              </select>
            </div>
          </div>
          <div class="form-group row">
            <label class="col-lg-2 col-form-label">Product <span class="text-danger">*</span></label>
            <div class="col-lg-4">
                <select class="form-control select2" name="product_type_id" id="product_type_id">
                    <option value="">--</option>
                    <option value="1">UNIT</option>
                    <option value="2">PARTS</option>
                    <option value="3">REFINANCING</option>
                    <option value="4">REAL ESTATE</option>
                    <option value="5">RESTRUCTURE</option>
                    <option value="6">RENTAL</option>
                    <option value="7">REPAIR</option>
                    <option value="8">FUEL</option>
                </select>
            </div>
            <label class="col-lg-2 col-form-label">Transaction <span class="text-danger">*</span></label>
            <div class="col-lg-4">
                <select class="form-control select2" name="jtransaction" id="jtransaction">
                    <option value="">--</option>
                    <option value="REL">RELEASE</option>
                    <option value="COL">COLLECTION</option>
                </select>
            </div>
          </div>
          <div class="form-group row">
            <label class="col-lg-2 col-form-label">Item <span class="text-danger">*</span></label>
            <div class="col-lg-4">
                <select class="form-control select2" name="item" id="item">
                    <option value="">--</option>
                    <option value="PRI">PRINCIPAL</option>
                    <option value="INT">INTEREST</option>
                    <option value="INS">INSURANCE</option>
                    <option value="REG">REGISTRATION</option>
                    <option value="DOC">DOCUMENTARY STAMP</option>
                    <option value="TRA">TRANSFER FEE</option>
                    <option value="HAN">HANDLING FEE</option>
                    <option value="DP">DEPOSIT/DOWNPAYMENT</option>
                    <option value="AJO">ADDITIONAL JOB ORDER</option>
                </select>
            </div>
          </div>
          <div class="form-group row">
            <label class="col-lg-2 col-form-label">Debit <span class="text-danger">*</span></label>
            <div class="col-lg-4">
              <select class="form-control select2" name="debit" id="debit">
                    <option value="">--</option>
                    <?php echo $chartOfAccountModel->generateChartOfAccountOptions(); ?>
                </select>
            </div>
            <label class="col-lg-2 col-form-label">Credit <span class="text-danger">*</span></label>
            <div class="col-lg-4">
              <select class="form-control select2" name="credit" id="credit">
              <option value="">--</option>
                  <?php echo $chartOfAccountModel->generateChartOfAccountOptions(); ?>
                </select>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>