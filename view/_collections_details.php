<div class="row">
  <div class="col-lg-12">
    <div class="card">
      <div class="card-header">
        <div class="row align-items-center">
          <div class="col-md-6">
            <h5>Collections</h5>
          </div>
          <div class="col-md-6 text-sm-end mt-3 mt-sm-0">
            <?php                            
                 $dropdown = '<div class="btn-group m-r-5">
                 <button type="button" class="btn btn-outline-secondary dropdown-toggle form-details" data-bs-toggle="dropdown" aria-expanded="false">
                     Action
                 </button>
                 <ul class="dropdown-menu dropdown-menu-end">';
             
                    if ($collectionsDeleteAccess['total'] > 0 && $collectionStatus == 'Posted') {
                        $dropdown .= '<li><button class="dropdown-item" type="button" id="delete-collections-details">Delete Collection</button></li>';
                    }

                    if (($tagCollectionAsReversed['total'] > 0 && $collectionStatus == 'Posted')) {
                        $dropdown .= '<li><button class="dropdown-item" type="button" id="tag-collections-as-reversed-details" data-bs-toggle="offcanvas" data-bs-target="#collections-reverse-offcanvas" aria-controls="collections-reverse-offcanvas">Reverse Collection</button></li>';
                    }
                          
                  $dropdown .= '</ul>
                              </div>';
                      
                  echo $dropdown;

                  if ($collectionsWriteAccess['total'] > 0 && strtotime($transactionDate) == strtotime(date('Y-m-d'))) {
                    echo '<button type="submit" form="collections-form" class="btn btn-success" id="submit-data">Save</button>
                          <button type="button" id="discard-create" class="btn btn-outline-danger me-2">Discard</button>';
                  }

                  if ($collectionsCreateAccess['total'] > 0) {
                      echo '<a class="btn btn-success m-r-5 form-details" href="collections.php?new">Create</a>';
                  }
            ?>
          </div>
        </div>
      </div>
      <div class="card-body">
        <form id="collections-form" method="post" action="#">
            <?php
                $disabled = 'disabled';
                if ($collectionsWriteAccess['total'] > 0 && strtotime($transactionDate) == strtotime(date('Y-m-d'))) {
                    $disabled = '';
                }
            ?>
            <div class="form-group row">
            <label class="col-lg-2 col-form-label">Mode of Payment <span class="text-danger">*</span></label>
            <div class="col-lg-4">
              <select class="form-control select2" name="mode_of_payment" id="mode_of_payment" <?php echo $disabled; ?>>
                <option value="">--</option>
                <option value="Cash">Cash</option>
                <option value="Online Deposit">Online Deposit</option>
                <option value="GCash">GCash</option>
               </select>
            </div>
            <label class="col-lg-2 col-form-label">Collection Type <span class="text-danger">*</span></label>
            <div class="col-lg-4">
              <select class="form-control select2" name="pdc_type" id="pdc_type" <?php echo $disabled; ?>>
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
                <select class="form-control select2" name="sales_proposal_id" id="sales_proposal_id" <?php echo $disabled; ?>>
                  <option value="">--</option>
                  <?php echo $salesProposalModel->generateLoanCollectionsOptions(); ?>
                </select>
              </div>  
            </div>
            <div id="product_field" class="form-group row field d-none">
              <label class="col-lg-2 col-form-label">Product <span class="text-danger">*</span></label>
              <div class="col-lg-10">
                <select class="form-control select2" name="product_id" id="product_id" <?php echo $disabled; ?>>
                  <option value="">--</option>
                  <?php echo $productModel->generateInStockProductOptions(); ?>
                </select>
              </div>
            </div>
            <div id="customer_field" class="form-group row field d-none">
              <label class="col-lg-2 col-form-label">Customer <span class="text-danger">*</span></label>
              <div class="col-lg-10">
                <select class="form-control select2" name="customer_id" id="customer_id" <?php echo $disabled; ?>>
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
                <select class="form-control select2" name="payment_details" id="payment_details" <?php echo $disabled; ?>>
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
                <input type="number" class="form-control" id="payment_amount" name="payment_amount" min="0" step="0.01" <?php echo $disabled; ?>>
            </div>
            <label class="col-lg-2 col-form-label">Online Reference Number</label>
            <div class="col-lg-4">
              <input type="text" class="form-control" id="reference_number" name="reference_number" maxlength="200" autocomplete="off" <?php echo $disabled; ?>>
            </div>
          </div>
          <div class="form-group row">
            <label class="col-lg-2 col-form-label">OR Number <span class="text-danger">*</span></label>
            <div class="col-lg-4">
              <input type="text" class="form-control" id="or_number" name="or_number" maxlength="100" autocomplete="off" <?php echo $disabled; ?>>
            </div>
            <label class="col-lg-2 col-form-label">OR Date <span class="text-danger">*</span></label>
            <div class="col-lg-4">
                <div class="input-group date">
                    <input type="text" class="form-control regular-datepicker" id="or_date" name="or_date" autocomplete="off" <?php echo $disabled; ?>>
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
                    <input type="text" class="form-control regular-datepicker" id="payment_date" name="payment_date" autocomplete="off" <?php echo $disabled; ?>>
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
              <textarea class="form-control" id="remarks" name="remarks" maxlength="500" <?php echo $disabled; ?>></textarea>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>

  <div class="col-lg-12">
    <div class="card">
      <div class="card-header">
        <div class="row align-items-center">
          <div class="col-md-12">
            <h5>Transaction History</h5>
          </div>
        </div>
      </div>
      <div class="card-body">
        <div class="table-responsive">
          <table id="transaction-history-table" class="table table-hover nowrap w-100">
            <thead>
              <tr>
                <th>Transaction Type</th>
                <th>Transaction Date</th>
                <th>Reference Number</th>
                <th>Reference Date</th>
                <th>Transaction By</th>
              </tr>
            </thead>
            <tbody></tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <div>
    <div class="offcanvas offcanvas-end" tabindex="-1" id="collections-cancel-offcanvas" aria-labelledby="collections-cancel-offcanvas-label">
      <div class="offcanvas-header">
        <h2 id="collections-cancel-offcanvas-label" style="margin-bottom:-0.5rem">Cancel Collection</h2>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
      </div>
    <div class="offcanvas-body">
      <div class="row">
        <div class="col-lg-12">
          <form id="collections-cancel-form" method="post" action="#">
            <div class="form-group row">
              <div class="col-lg-12 mt-3 mt-lg-0">
                <label class="form-label">Cancellation Reason <span class="text-danger">*</span></label>
                <textarea class="form-control" id="cancellation_reason" name="cancellation_reason" maxlength="500"></textarea>
              </div>
            </div>
          </form>
        </div>
      </div>
      <div class="row">
        <div class="col-lg-12">
          <button type="submit" class="btn btn-primary" id="submit-collections-cancel" form="collections-cancel-form">Submit</button>
          <button class="btn btn-light-danger" data-bs-dismiss="offcanvas"> Close </button>
        </div>
      </div>
    </div>
  </div>

  <div>
    <div class="offcanvas offcanvas-end" tabindex="-1" id="collections-reverse-offcanvas" aria-labelledby="collections-reverse-offcanvas-label">
      <div class="offcanvas-header">
        <h2 id="collections-reverse-offcanvas-label" style="margin-bottom:-0.5rem">Reverse Collection</h2>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
      </div>
    <div class="offcanvas-body">
      <div class="row">
        <div class="col-lg-12">
          <form id="collections-reverse-form" method="post" action="#">
            <div class="form-group row">
                <div class="col-lg-12 mt-3 mt-lg-0">
                    <label class="form-label">Reversal Reason <span class="text-danger">*</span></label>
                    <select class="form-control offcanvas-select2" name="reversal_reason" id="reversal_reason">
                        <option value="">--</option>
                        <option value="Incorrect Amount">Incorrect Amount</option>
                        <option value="Duplicate Entry">Duplicate Entry</option>
                        <option value="Customer Request">Customer Request</option>
                        <option value="Fraudulent Transaction">Fraudulent Transaction</option>
                        <option value="DAIF">DAIF</option>
                        <option value="DAUD">DAUD</option>
                        <option value="Stop Payment">Stop Payment</option>
                        <option value="Account Closed">Account Closed</option>
                        <option value="Unauthorized Transaction">Unauthorized Transaction</option>
                        <option value="Error in Entry">Error in Entry</option>
                        <option value="Service Issue">Service Issue</option>
                        <option value="Payment Reversal">Payment Reversal</option>
                        <option value="Collection Bounced">Collection Bounced</option>
                        <option value="Collection Lost">Collection Lost</option>
                        <option value="Collection Cancelled">Collection Cancelled</option>
                        <option value="Collection Stolen">Collection Stolen</option>
                        <option value="Others">Others</option>
                    </select>
                </div>
            </div>
            <div class="form-group row">
              <div class="col-lg-12 mt-3 mt-lg-0">
                <label class="form-label">Reversal Remarks <span class="text-danger">*</span></label>
                <textarea class="form-control" id="reversal_remarks" name="reversal_remarks" maxlength="500"></textarea>
              </div>
            </div>
          </form>
        </div>
      </div>
      <div class="row">
        <div class="col-lg-12">
          <button type="submit" class="btn btn-primary" id="submit-collections-reverse" form="collections-reverse-form">Submit</button>
          <button class="btn btn-light-danger" data-bs-dismiss="offcanvas"> Close </button>
        </div>
      </div>
    </div>
  </div>
<?php

  echo '<div class="col-lg-12">
          <div class="card">
            <div class="card-header">
              <div class="row align-items-center">
                <div class="col-sm-6">
                  <h5>Log Notes</h5>
                </div>
              </div>
            </div>
            <div class="log-notes-scroll" style="max-height: 450px; position: relative;">
              <div class="card-body p-b-0">
                '. $userModel->generateLogNotes('loan_collections', $collectionsID) .'
              </div>
            </div>
          </div>
        </div>';
?>
</div>