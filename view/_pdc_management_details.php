<div class="row">
  <div class="col-lg-12">
    <div class="card">
      <div class="card-header">
        <div class="row align-items-center">
          <div class="col-md-6">
            <h5>PDC Management</h5>
          </div>
          <div class="col-md-6 text-sm-end mt-3 mt-sm-0">
            <?php                            
                 $dropdown = '<div class="btn-group m-r-5">
                 <button type="button" class="btn btn-outline-secondary dropdown-toggle form-details" data-bs-toggle="dropdown" aria-expanded="false">
                     Action
                 </button>
                 <ul class="dropdown-menu dropdown-menu-end">';
             
                  if ($pdcManagementDeleteAccess['total'] > 0 && $collectionStatus == 'Pending') {
                      $dropdown .= '<li><button class="dropdown-item" type="button" id="delete-pdc-management-details">Delete PDC</button></li>';
                  }
                              
                  if ($tagPDCAsCleared['total'] > 0 && ($collectionStatus == 'Deposited' || $collectionStatus == 'Redeposit')) {
                      $dropdown .= '<li><button class="dropdown-item" type="button" id="tag-pdc-as-cleared-details">Cleared PDC</button></li>';
                  }

                  if ($tagPDCOnHold['total'] > 0 && ($collectionStatus == 'Pending' || $collectionStatus == 'For Deposit' || $collectionStatus == 'Redeposit')) {
                      $dropdown .= '<li><button class="dropdown-item" type="button" id="tag-pdc-as-on-hold-details" data-bs-toggle="offcanvas" data-bs-target="#pdc-on-hold-offcanvas" aria-controls="pdc-on-hold-offcanvas">On-Hold PDC</button></li>';
                  }

                  if (($tagPDCAsReversed['total'] > 0 && $collectionStatus == 'Deposited') || ($tagClearedPDCAsReturned['total'] > 0 && $collectionStatus == 'Cleared')) {
                      $dropdown .= '<li><button class="dropdown-item" type="button" id="tag-pdc-as-reversed-details" data-bs-toggle="offcanvas" data-bs-target="#pdc-reverse-offcanvas" aria-controls="pdc-reverse-offcanvas">Reverse PDC</button></li>';
                  }

                  if ($tagPDCAsCancelled['total'] > 0 && ($collectionStatus == 'Pending' || $collectionStatus == 'Redeposit')) {
                      $dropdown .= '<li><button class="dropdown-item" type="button" id="tag-pdc-as-cancel-details" data-bs-toggle="offcanvas" data-bs-target="#pdc-cancel-offcanvas" aria-controls="pdc-cancel-offcanvas">Cancel PDC</button></li>';
                  }

                  if ($tagPDCAsRedeposit['total'] > 0 && ($collectionStatus == 'On-Hold' || $collectionStatus == 'Reversed')) {
                      $dropdown .= '<li><button class="dropdown-item" type="button" id="tag-pdc-as-redeposit-details" data-bs-toggle="offcanvas" data-bs-target="#pdc-redeposit-offcanvas" aria-controls="pdc-redeposit-offcanvas">Redeposit PDC</button></li>';
                  }

                  $currentDate = date('m-d-Y');
                  if ($tagPDCAsForDeposit['total'] > 0 && $collectionStatus == 'Pending' && $checkDate <= $currentDate) {
                      $dropdown .= '<li><button class="dropdown-item" type="button" id="tag-pdc-as-for-deposit-details">For Deposit PDC</button></li>';
                  }

                  if ($tagPDCAsPulledOut['total'] > 0 && ($collectionStatus == 'Pending'|| $collectionStatus == 'Cancelled' || $collectionStatus == 'Redeposit' || $collectionStatus == 'On-Hold' )) {
                      $dropdown .= '<li><button class="dropdown-item" type="button" id="tag-pdc-as-pulled-out-details" data-bs-toggle="offcanvas" data-bs-target="#pdc-pulled-out-offcanvas" aria-controls="pdc-pulled-out-offcanvas">Pulled-Out PDC</button></li>';
                  }

                  if ($tagPDCAsDeposited['total'] > 0 && $collectionStatus == 'For Deposit') {
                      $dropdown .= '<li><button class="dropdown-item" type="button" id="tag-pdc-as-deposited-details" data-bs-toggle="offcanvas" data-bs-target="#pdc-deposited-offcanvas" aria-controls="pdc-deposited-offcanvas">Deposited PDC</button></li>';
                  }

                  $dropdown .= '<li><button class="dropdown-item" type="button" id="print-check-details">Print Check</button></li>';
                          
                  $dropdown .= '</ul>
                              </div>';
                      
                  echo $dropdown;

                  if ($pdcManagementWriteAccess['total'] > 0 && $collectionStatus == 'Pending') {
                    echo '<button type="submit" form="pdc-management-form" class="btn btn-success" id="submit-data">Save</button>
                      <button type="button" id="discard-create" class="btn btn-outline-danger form-edit">Discard</button>';
                  }

                  if ($pdcManagementCreateAccess['total'] > 0) {
                      echo '<a class="btn btn-success m-r-5 form-details" href="pdc-management.php?new">Create</a>';
                  }
            ?>
          </div>
        </div>
      </div>
      <div class="card-body">
        <form id="pdc-management-form" method="post" action="#">
            <?php
                $disabled = 'disabled';
                if($pdcManagementWriteAccess['total'] > 0 && $collectionStatus == 'Pending'){
                    $disabled = '';
                }
            ?>
           <div class="form-group row">
            <label class="col-lg-2 col-form-label">PDC Type</label>
            <div class="col-lg-4">
              <select class="form-control select2" name="pdc_type" id="pdc_type" <?php echo $disabled; ?>>
                <option value="">--</option>
                <option value="Loan">Loan</option>
                <option value="Product">Product</option>
                <option value="Customer">Customer</option>
                <option value="Leasing">Leasing</option>
               </select>
            </div>
            <label class="col-lg-2 col-form-label">Company <span class="text-danger">*</span></label>
              <div class="col-lg-4">
                <select class="form-control select2" name="company_id" id="company_id" <?php echo $disabled; ?>>
                  <option value="">--</option>
                  <?php echo $companyModel->generateCompanyOptions(); ?>
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
                  <?php echo $customerModel->generateCustomerOptions('active customer'); ?>
                </select>
              </div>
            </div>
            <div id="leasing_field" class="form-group row field d-none">
              <label class="col-lg-2 col-form-label">Leasing Application <span class="text-danger">*</span></label>
              <div class="col-lg-10">
                <select class="form-control select2" name="leasing_id" id="leasing_id">+
                <option value="">--</option>
                <?php echo $leasingApplicationModel->generateLeasingApplicationOptions(); ?>
                </select>
              </div>
            </div>
          <div class="form-group row">
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
                    <option value="Rental">Rental</option>
                </select>
            </div>
            <label class="col-lg-2 col-form-label">Check Number <span class="text-danger">*</span></label>
            <div class="col-lg-4">
              <input type="text" class="form-control" id="check_number" name="check_number" maxlength="100" autocomplete="off" <?php echo $disabled; ?>>
            </div>
          </div>
          <div class="form-group row">
            <label class="col-lg-2 col-form-label">Check Date <span class="text-danger">*</span></label>
            <div class="col-lg-4">
                <div class="input-group date">
                    <input type="text" class="form-control regular-datepicker" id="check_date" name="check_date" autocomplete="off" <?php echo $disabled; ?>>
                    <span class="input-group-text">
                        <i class="feather icon-calendar"></i>
                    </span>
                </div>
            </div>
            <label class="col-lg-2 col-form-label">Payment Amount <span class="text-danger">*</span></label>
            <div class="col-lg-4">
                <input type="number" class="form-control" id="payment_amount" name="payment_amount" min="1" step="0.01" <?php echo $disabled; ?>>
            </div>
          </div>
          <div class="form-group row">
            <label class="col-lg-2 col-form-label">Bank/Branch <span class="text-danger">*</span></label>
            <div class="col-lg-4">
              <input type="text" class="form-control" id="bank_branch" name="bank_branch" maxlength="200" autocomplete="off" <?php echo $disabled; ?>>
            </div>
            <label class="col-lg-2 col-form-label">Account Number</label>
            <div class="col-lg-4">
              <input type="text" class="form-control" id="account_number" name="account_number" maxlength="100" autocomplete="off">
            </div>
          </div>
          <div class="form-group row">
            <label class="col-lg-2 col-form-label">Remarks</label>
            <div class="col-lg-10">
              <textarea class="form-control" id="remarks" name="remarks" maxlength="500" <?php echo $disabled; ?>></textarea>
            </div>
          </div>
          <div class="form-group row">
            <label class="col-lg-2 col-form-label">Reversal Reason</label>
            <div class="col-lg-10">
            <textarea class="form-control" id="reversal_reason_remarks" readonly></textarea>
            </div>
          </div>
          <div class="form-group row">
            <label class="col-lg-2 col-form-label">Cancellation Reason</label>
            <div class="col-lg-10">
            <textarea class="form-control" id="cancellation_reason_remarks" readonly></textarea>
            </div>
          </div>
          <div class="form-group row">
            <label class="col-lg-2 col-form-label">Pulled Out Reason</label>
            <div class="col-lg-10">
            <textarea class="form-control" id="pulled_out_reason_remarks" readonly></textarea>
            </div>
          </div>
          <div class="form-group row">
            <label class="col-lg-2 col-form-label">On-hold Reason</label>
            <div class="col-lg-10">
            <textarea class="form-control" id="onhold_reason_remarks" readonly></textarea>
            </div>
          </div>
          <div class="form-group row">
            <label class="col-lg-2 col-form-label">On-hold Attachment</label>
            <div class="col-lg-10">
            <img src="<?php echo DEFAULT_PLACEHOLDER_IMAGE; ?>" alt="On-hold Attachment" id="onhold_attachment_file" class="img-fluid rounded">
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
    <div class="offcanvas offcanvas-end" tabindex="-1" id="pdc-on-hold-offcanvas" aria-labelledby="pdc-on-hold-offcanvas-label">
      <div class="offcanvas-header">
        <h2 id="pdc-on-hold-offcanvas-label" style="margin-bottom:-0.5rem">On-Hold PDC</h2>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
      </div>
    <div class="offcanvas-body">
      <div class="row">
        <div class="col-lg-12">
          <form id="pdc-on-hold-form" method="post" action="#">
            <div class="form-group row">
              <div class="col-lg-12 mt-3 mt-lg-0">
                <label class="form-label">On-Hold Reason <span class="text-danger">*</span></label>
                <textarea class="form-control" id="on_hold_reason" name="on_hold_reason" maxlength="500"></textarea>
              </div>
            </div>
            <div class="form-group row">
              <div class="col-lg-12 mt-3 mt-lg-0">
                <label class="form-label">On-Hold Attachment <span class="text-danger">*</span></label>
                <input type="file" class="form-control" id="onhold_attachment" name="onhold_attachment">
              </div>
            </div>
          </form>
        </div>
      </div>
      <div class="row">
        <div class="col-lg-12">
          <button type="submit" class="btn btn-primary" id="submit-pdc-on-hold" form="pdc-on-hold-form">Submit</button>
          <button class="btn btn-light-danger" data-bs-dismiss="offcanvas"> Close </button>
        </div>
      </div>
    </div>
  </div>

  <div>
    <div class="offcanvas offcanvas-end" tabindex="-1" id="pdc-cancel-offcanvas" aria-labelledby="pdc-cancel-offcanvas-label">
      <div class="offcanvas-header">
        <h2 id="pdc-cancel-offcanvas-label" style="margin-bottom:-0.5rem">Cancel PDC</h2>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
      </div>
    <div class="offcanvas-body">
      <div class="row">
        <div class="col-lg-12">
          <form id="pdc-cancel-form" method="post" action="#">
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
          <button type="submit" class="btn btn-primary" id="submit-pdc-cancel" form="pdc-cancel-form">Submit</button>
          <button class="btn btn-light-danger" data-bs-dismiss="offcanvas"> Close </button>
        </div>
      </div>
    </div>
  </div>

  <div>
    <div class="offcanvas offcanvas-end" tabindex="-1" id="pdc-pulled-out-offcanvas" aria-labelledby="pdc-pulled-out-offcanvas-label">
      <div class="offcanvas-header">
        <h2 id="pdc-pulled-out-offcanvas-label" style="margin-bottom:-0.5rem">Pull-Out PDC</h2>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
      </div>
    <div class="offcanvas-body">
      <div class="row">
        <div class="col-lg-12">
          <form id="pdc-pulled-out-form" method="post" action="#">
            <div class="form-group row">
              <div class="col-lg-12 mt-3 mt-lg-0">
                <label class="form-label">Pulled Out Reason <span class="text-danger">*</span></label>
                <textarea class="form-control" id="pulled_out_reason" name="pulled_out_reason" maxlength="500"></textarea>
              </div>
            </div>
          </form>
        </div>
      </div>
      <div class="row">
        <div class="col-lg-12">
          <button type="submit" class="btn btn-primary" id="submit-pdc-pulled-out" form="pdc-pulled-out-form">Submit</button>
          <button class="btn btn-light-danger" data-bs-dismiss="offcanvas"> Close </button>
        </div>
      </div>
    </div>
  </div>

  <div>
    <div class="offcanvas offcanvas-end" tabindex="-1" id="pdc-reverse-offcanvas" aria-labelledby="pdc-reverse-offcanvas-label">
      <div class="offcanvas-header">
        <h2 id="pdc-reverse-offcanvas-label" style="margin-bottom:-0.5rem">Reverse PDC</h2>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
      </div>
    <div class="offcanvas-body">
      <div class="row">
        <div class="col-lg-12">
          <form id="pdc-reverse-form" method="post" action="#">
            <div class="form-group row">
                <div class="col-lg-12 mt-3 mt-lg-0">
                    <label class="form-label">Reversal Date <span class="text-danger">*</span></label>
                    <div class="input-group date">
                      <input type="text" class="form-control regular-datepicker" id="reversal_date" name="reversal_date" autocomplete="off">
                      <span class="input-group-text">
                          <i class="feather icon-calendar"></i>
                      </span>
                  </div>
                </div>
            </div>
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
                        <option value="PDC Bounced">PDC Bounced</option>
                        <option value="PDC Lost">PDC Lost</option>
                        <option value="PDC Cancelled">PDC Cancelled</option>
                        <option value="PDC Stolen">PDC Stolen</option>
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
          <button type="submit" class="btn btn-primary" id="submit-pdc-reverse" form="pdc-reverse-form">Submit</button>
          <button class="btn btn-light-danger" data-bs-dismiss="offcanvas"> Close </button>
        </div>
      </div>
    </div>
  </div>

  <div>
    <div class="offcanvas offcanvas-end" tabindex="-1" id="pdc-deposited-offcanvas" aria-labelledby="pdc-deposited-offcanvas-label">
      <div class="offcanvas-header">
        <h2 id="pdc-deposited-offcanvas-label" style="margin-bottom:-0.5rem">Deposit PDC</h2>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
      </div>
    <div class="offcanvas-body">
      <div class="row">
        <div class="col-lg-12">
          <form id="pdc-deposited-form" method="post" action="#">
            <div class="form-group row">
                <div class="col-lg-12 mt-3 mt-lg-0">
                    <label class="form-label">Deposit To <span class="text-danger">*</span></label>
                    <select class="form-control offcanvas-select2" name="deposit_to" id="deposit_to">
                      <option value="">--</option>
                      <?php echo $bankModel->generateBankOptions(); ?>
                    </select>
                </div>
            </div>
          </form>
        </div>
      </div>
      <div class="row">
        <div class="col-lg-12">
          <button type="submit" class="btn btn-primary" id="submit-pdc-deposited" form="pdc-deposited-form">Submit</button>
          <button class="btn btn-light-danger" data-bs-dismiss="offcanvas"> Close </button>
        </div>
      </div>
    </div>
  </div>

  <div>
    <div class="offcanvas offcanvas-end" tabindex="-1" id="pdc-redeposit-offcanvas" aria-labelledby="pdc-redeposit-offcanvas-label">
      <div class="offcanvas-header">
        <h2 id="pdc-redeposit-offcanvas-label" style="margin-bottom:-0.5rem">Redeposit PDC</h2>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
      </div>
    <div class="offcanvas-body">
      <div class="row">
        <div class="col-lg-12">
          <form id="pdc-redeposit-form" method="post" action="#">
            <div class="form-group row">
              <div class="col-lg-12 mt-3 mt-lg-0">
                <label class="form-label">Redeposit Date <span class="text-danger">*</span></label>
                <div class="input-group date">
                    <input type="text" class="form-control regular-datepicker" id="redeposit_date" name="redeposit_date" autocomplete="off">
                    <span class="input-group-text">
                        <i class="feather icon-calendar"></i>
                    </span>
                </div>
              </div>
            </div>
          </form>
        </div>
      </div>
      <div class="row">
        <div class="col-lg-12">
          <button type="submit" class="btn btn-primary" id="submit-pdc-redeposit" form="pdc-redeposit-form">Submit</button>
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
                '. $userModel->generateLogNotes('loan_collections', $pdcManagementID) .'
              </div>
            </div>
          </div>
        </div>';
?>
</div>