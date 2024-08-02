<div class="row">
  <div class="col-lg-12">
    <div class="card">
      <div class="card-header">
        <div class="row align-items-center">
          <div class="col-md-6">
            <h5>Collections</h5>
          </div>
          <?php
            if ($collectionsCreateAccess['total'] > 0) {
               echo '<div class="col-md-6 text-sm-end mt-3 mt-sm-0">
                      <button type="submit" form="collections-form" class="btn btn-success form-edit" id="submit-data">Save</button>
                      <button type="button" id="discard-create" class="btn btn-outline-danger form-edit">Discard</button>
                    </div>';
            }
          ?>
        </div>
      </div>
      <div class="card-body">
        <form id="collections-form" method="post" action="#">
            <div class="form-group row">
            <label class="col-lg-2 col-form-label">Mode of Payment <span class="text-danger">*</span></label>
            <div class="col-lg-4">
              <select class="form-control select2" name="mode_of_payment" id="mode_of_payment">
                <option value="">--</option>
                <option value="Cash">Cash</option>
                <option value="Online Deposit">Online Deposit</option>
                <option value="GCash">GCash</option>
               </select>
            </div>
            <label class="col-lg-2 col-form-label">Collection Type <span class="text-danger">*</span></label>
            <div class="col-lg-4">
              <select class="form-control select2" name="pdc_type" id="pdc_type">
                <option value="">--</option>
                <option value="Loan">Loan</option>
                <option value="Product">Product</option>
                <option value="Customer">Customer</option>
                <option value="Miscellaneous">Miscellaneous</option>
               </select>
            </div>
          </div>
            <div id="loan_field" class="form-group row field d-none">
              <label class="col-lg-2 col-form-label">Loan <span class="text-danger">*</span></label>
              <div class="col-lg-10">
                <select class="form-control select2" name="sales_proposal_id" id="sales_proposal_id">
                  <option value="">--</option>
                  <?php echo $salesProposalModel->generateLoanAccountOptions(); ?>
                </select>
              </div>  
            </div>
            <div id="product_field" class="form-group row field d-none">
              <label class="col-lg-2 col-form-label">Product <span class="text-danger">*</span></label>
              <div class="col-lg-10">
                <select class="form-control select2" name="product_id" id="product_id">
                  <option value="">--</option>
                  <?php echo $productModel->generateInStockProductOptions(); ?>
                </select>
              </div>
            </div>
            <div id="customer_field" class="form-group row field d-none">
              <label class="col-lg-2 col-form-label">Customer <span class="text-danger">*</span></label>
              <div class="col-lg-10">
                <select class="form-control select2" name="customer_id" id="customer_id">
                  <option value="">--</option>
                  <?php echo $customerModel->generateAllContactsOptions(); ?>
                </select>
              </div>
            </div>
            <div id="miscellaneous_field" class="form-group row field d-none">
              <label class="col-lg-2 col-form-label">Collected From <span class="text-danger">*</span></label>
              <div class="col-lg-10">
                <input type="text" class="form-control" id="collected_from" name="collected_from" maxlength="200" autocomplete="off" <?php echo $disabled; ?>>
              </div>
            </div>
          <div class="form-group row">
            <label class="col-lg-2 col-form-label">Company <span class="text-danger">*</span></label>
              <div class="col-lg-4">
                <select class="form-control select2" name="company_id" id="company_id">
                  <option value="">--</option>
                  <option value="1" selected>Christian General Motors Inc.</option>
                  <option value="3">FUSO Tarlac</option>
                </select>
              </div>  
            <label class="col-lg-2 col-form-label">Payment Details <span class="text-danger">*</span></label>
            <div class="col-lg-4">
                <select class="form-control select2" name="payment_details" id="payment_details">
                    <option value="">--</option>
                    <option value="Acct Amort">Acct Amort</option>
                    <option value="Acct Amort W/ OC">Acct Amort W/ OC</option>
                    <option value="Advances">Advances</option>
                    <option value="Collateral Check">Collateral Check</option>
                    <option value="Deposit">Deposit</option>
                    <option value="Downpayment">Downpayment</option>
                    <option value="Insurance">Insurance</option>
                    <option value="Insurance Renewal">Insurance Renewal</option>
                    <option value="Other Charges">Other Charges</option>
                    <option value="Additional Job Order">Additional Job Order</option>
                    <option value="Parts">Parts</option>
                    <option value="Registration">Registration</option>
                    <option value="Registration Renewal">Registration Renewal</option>
                    <option value="Transaction Fee">Transaction Fee</option>
                    <option value="Transfer Fee">Transfer Fee</option>
                </select>
            </div>
          </div>
          
          <div class="form-group row">
            <label class="col-lg-2 col-form-label">Payment Amount <span class="text-danger">*</span></label>
            <div class="col-lg-4">
                <input type="number" class="form-control" id="payment_amount" name="payment_amount" min="1" step="0.01">
            </div>
            <label class="col-lg-2 col-form-label">Reference Number</label>
            <div class="col-lg-4">
              <input type="text" class="form-control" id="reference_number" name="reference_number" maxlength="200" autocomplete="off">
            </div>
          </div>
          <div class="form-group row">
            <label class="col-lg-2 col-form-label">OR Number <span class="text-danger">*</span></label>
            <div class="col-lg-4">
              <input type="text" class="form-control" id="or_number" name="or_number" maxlength="100" autocomplete="off">
            </div>
            <label class="col-lg-2 col-form-label">OR Date <span class="text-danger">*</span></label>
            <div class="col-lg-4">
                <div class="input-group date">
                    <input type="text" class="form-control regular-datepicker" id="or_date" name="or_date" autocomplete="off">
                    <span class="input-group-text">
                        <i class="feather icon-calendar"></i>
                    </span>
                </div>
            </div>
          </div>
          <div class="form-group row">
            <label class="col-lg-2 col-form-label">Payment Date <span class="text-danger">*</span></label>
            <div class="col-lg-4">
                <div class="input-group date">
                    <input type="text" class="form-control regular-datepicker" id="payment_date" name="payment_date" autocomplete="off">
                    <span class="input-group-text">
                        <i class="feather icon-calendar"></i>
                    </span>
                </div>
            </div>
            <label class="col-lg-2 col-form-label">Deposited To <span class="text-danger">*</span></label>
            <div class="col-lg-4">
              <select class="form-control select2" name="deposited_to" id="deposited_to">
                <option value="">--</option>
                <?php echo $bankModel->generateBankOptions(); ?>
               </select>
            </div>
          </div>
          <div class="form-group row">
            <label class="col-lg-2 col-form-label">Remarks</label>
            <div class="col-lg-10">
              <textarea class="form-control" id="remarks" name="remarks" maxlength="500"></textarea>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>