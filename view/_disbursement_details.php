<div class="row">
  <div class="col-lg-12">
    <div class="card">
      <div class="card-header">
        <div class="row align-items-center">
          <div class="col-md-6">
            <h5>Disbursements</h5>
          </div>
          <div class="col-md-6 text-sm-end mt-3 mt-sm-0">
            <?php                            
                 $dropdown = '<div class="btn-group m-r-5">
                 <button type="button" class="btn btn-outline-secondary dropdown-toggle form-details" data-bs-toggle="dropdown" aria-expanded="false">
                     Action
                 </button>
                 <ul class="dropdown-menu dropdown-menu-end">
                 ';
             
                    if (($disbursementStatus == 'Draft' || $disbursementStatus == 'Posted')  && ($fund_source != 'Check' && $fund_source != 'Journal Voucher')) {
                      $dropdown .= '<li><button class="dropdown-item print" target="_blank">Print Voucher</button></li>';
                    }
             
                    if (($disbursementStatus == 'Draft' || $disbursementStatus == 'Posted') && ($fund_source === 'Check' || $fund_source === 'Journal Voucher')) {
                      $dropdown .= '<li><button class="dropdown-item print" target="_blank">Print Voucher</button></li>';
                    }
             
                    if (($disbursementStatus == 'Draft' || $disbursementStatus == 'Posted')) {
                      $dropdown .= '<li><a href="print-bir-2307.php?id='. $disbursementID .'" class="dropdown-item" target="_blank">Print BIR Form 2307</a></li>';
                      $dropdown .= '<li><button class="dropdown-item" type="button" data-bs-toggle="offcanvas" data-bs-target="#bir-offcanvas" aria-controls="bir-offcanvas">Print BIR Form 2307 (Manual)</button></li>';
                    }
             
                    if ($postDisbursement['total'] > 0 && $disbursementStatus == 'Draft') {
                      $dropdown .= '<li><button class="dropdown-item" type="button" id="post-disbursement-details">Post Disbursement</button></li>';
                    }
             
                    if ($cancelDisbursement['total'] > 0 && $disbursementStatus == 'Draft') {
                      $dropdown .= '<li><button class="dropdown-item" type="button" data-bs-toggle="offcanvas" data-bs-target="#cancel-disbursement-offcanvas" aria-controls="cancel-disbursement-offcanvas" id="cancel-disbursement">Cancel Disbursement</button></li>';
                    }
             
                    if ($replenishmentDisbursement['total'] > 0 && $disbursementStatus == 'Posted'  && ($fund_source != 'Check' && $fund_source != 'Journal Voucher')) {
                      $dropdown .= '<li><button class="dropdown-item" type="button" id="replenish-disbursement-details">Replenish Disbursement</button></li>';
                    }
             
                    if ($reverseDisbursement['total'] > 0 && $disbursementStatus == 'Posted') {
                      $dropdown .= '<li><button class="dropdown-item" type="button" data-bs-toggle="offcanvas" data-bs-target="#reverse-disbursement-offcanvas" aria-controls="reverse-disbursement-offcanvas" id="reverse-disbursement">Reverse Disbursement</button></li>';
                    }
             
                    if ($disbursementDeleteAccess['total'] > 0) {
                        $dropdown .= '<li><button class="dropdown-item" type="button" id="delete-disbursement-details">Delete Disbursement</button></li>';
                    }
              
                          
                  $dropdown .= '</ul>
                              </div>';
                      
                  echo $dropdown;

                  if (($disbursementWriteAccess['total'] > 0 && $disbursementStatus == 'Draft') || $noRestriction['total'] > 0) {
                    echo '<button type="submit" form="disbursement-form" class="btn btn-success" id="submit-data">Save</button>
                          <button type="button" id="discard-create" class="btn btn-outline-danger me-2">Discard</button>';
                  }

                  if ($disbursementCreateAccess['total'] > 0 && $disbursementCategory == 'disbursement petty cash') {
                      echo '<a class="btn btn-success m-r-5 form-details" href="disbursement.php?new">Create</a>';
                  }

                  if ($disbursementCreateAccess['total'] > 0 && $disbursementCategory == 'disbursement check') {
                      echo '<a class="btn btn-success m-r-5 form-details" href="check-disbursement.php?new">Create</a>';
                  }
            ?>
          </div>
        </div>
      </div>
      <div class="card-body">
        <form id="disbursement-form" method="post" action="#">
          
        <input type="hidden" id="disbursement_category" name="disbursement_category" value="<?php echo $disbursementCategory; ?>">
          <?php
            $disabled = 'disabled';
            if (($disbursementWriteAccess['total'] > 0 && $disbursementStatus == 'Draft') || $noRestriction['total'] > 0) {
              $disabled = '';
            }
          ?>
          <div class="form-group row">
            <label class="col-lg-2 col-form-label">CDV Number <span class="text-danger">*</span></label>
            <div class="col-lg-4">
              <input type="text" class="form-control" id="transaction_number" name="transaction_number" maxlength="200" autocomplete="off" readonly>
            </div>
            <label class="col-lg-2 col-form-label">Transaction Date <span class="text-danger">*</span></label>
            <div class="col-lg-4">
                <div class="input-group date">
                    <input type="text" class="form-control future-date-restricted-datepicker" id="transaction_date" name="transaction_date" autocomplete="off" value="<?php echo date('m/d/Y'); ?>" <?php echo $disabled; ?>>
                    <span class="input-group-text">
                        <i class="feather icon-calendar"></i>
                    </span>
                </div>
            </div>
          </div>
          <div class="form-group row">
            <label class="col-lg-2 col-form-label">Payable Type <span class="text-danger">*</span></label>
            <div class="col-lg-4">
                <select class="form-control select2" name="payable_type" id="payable_type" <?php echo $disabled; ?>>
                  <option value="Customer" <?php echo $payableClient; ?>>Customer</option>
                  <option value="Miscellaneous" <?php echo $payableMisc; ?>>Miscellaneous</option>
                </select>
            </div>
            <label class="col-lg-2 col-form-label">Customer <span class="text-danger">*</span></label>
            <?php
                  if($disbursementCategory === 'disbursement petty cash'){
                   $customerhide = '';
                   $mischide = 'd-none';
                  }
                  else{
                    $customerhide = 'd-none';
                    $mischide = '';
                  }
                ?>
            <div class="col-lg-4 <?php echo $customerhide; ?>" id="customer-select">
                <select class="form-control select2" name="customer_id" id="customer_id">
                  <option value="">--</option>
                  <?php echo $customerModel->generateAllContactsOptions(); ?>
                </select>
            </div>
            <div class="col-lg-4 <?php echo $mischide; ?>" id="misc-select">
                <select class="form-control select2" name="misc_id" id="misc_id">
                  <option value="">--</option>
                  <?php echo $miscellaneousClientModel->generateMiscellaneousClientOptions(); ?>
                </select>
            </div>
          </div>
          <div class="form-group row">
            <label class="col-lg-2 col-form-label">Department</label>
            <div class="col-lg-4">
            <select class="form-control select2" name="department_id" id="department_id" <?php echo $disabled; ?>>
                  <option value="">--</option>
                  <?php echo $departmentModel->generateDepartmentOptions(); ?>
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
          <div class="form-group row">
            <label class="col-lg-2 col-form-label">Transaction Type <span class="text-danger">*</span></label>
            <div class="col-lg-4">
              <select class="form-control select2" name="transaction_type" id="transaction_type" <?php echo $disabled; ?>>
                <option value="">--</option>
                <option value="Replenishment">Replenishment</option>
                <option value="Disbursement" selected>Disbursement</option>
               </select>
            </div>
            <label class="col-lg-2 col-form-label">Fund Source <span class="text-danger">*</span></label>
            <div class="col-lg-4">
              <select class="form-control select2" name="fund_source" id="fund_source" <?php echo $disabled; ?>>
                <option value="">--</option>
                <?php
                  if($disbursementCategory === 'disbursement petty cash'){
                    echo ' <option value="Petty Cash" selected>Petty Cash</option>
                    <option value="Revolving Fund">Revolving Fund</option>';
                  }
                  else if($disbursementCategory === 'disbursement voucher'){
                    echo '<option value="Journal Voucher" selected>Journal Voucher</option>';
                  }
                  else{
                    echo ' <option value="Check" selected>Check</option>';
                  }
                ?>
               </select>
            </div>
          </div>
          <div class="form-group row">
            <label class="col-lg-2 col-form-label">Particulars <span class="text-danger">*</span></label>
            <div class="col-lg-10">
              <textarea class="form-control" id="particulars" name="particulars" maxlength="2000" <?php echo $disabled; ?>></textarea>
            </div>
          </div>
        </form>
      </div>
    </div>

    <div class="card table-card">
      <div class="card-header">
        <div class="row align-items-center">
          <div class="col-sm-6">
            <h5>Particulars</h5>
          </div>
          <div class="col-md-6 text-sm-end mt-3 mt-sm-0">
              <?php 
                if (($disbursementWriteAccess['total'] > 0 && $disbursementStatus == 'Draft') || $noRestriction['total'] > 0) {
                  echo '<button class="btn btn-warning" type="button" data-bs-toggle="offcanvas" data-bs-target="#particulars-offcanvas" aria-controls="particulars-offcanvas" id="add-particulars">Add Particulars</button>';
                }
              ?>
          </div>
        </div>
      </div>
      <div class="card-body">
        <div class="dt-responsive table-responsive">
          <table id="particulars-table" class="table table-hover nowrap w-100 dataTable">
            <thead>
              <tr>
                <th>Particulars</th>
                <th class="all">Company</th>
                <th class="all">Amount</th>
                <th class="all">Remarks</th>
                <th class="all">Actions</th>
              </tr>
            </thead>
            <tbody></tbody>
          </table>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-lg-12">
        <div class="card">
          <div class="card-body">
            <div class="row align-items-center">
              <div class="col-9">
                <h3 class="mb-1 text-end">Total Particulars:</h3>
              </div>
              <div class="col-3">
                <h3 class="mb-1 text-end" id="total-particulars">0.00 Php</h3>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <?php
      $check_hidden = 'd-none';
      if($fund_source === 'Check' || $fund_source === 'Journal Voucher'){
        $check_hidden = '';
      }
    ?>

    <div class="card table-card <?php echo $check_hidden; ?>">
      <div class="card-header">
        <div class="row align-items-center">
          <div class="col-sm-6">
            <h5>Issued Check</h5>
          </div>
          <div class="col-md-6 text-sm-end mt-3 mt-sm-0">
              <?php 
                if (($disbursementWriteAccess['total'] > 0 && $disbursementStatus === 'Draft') || $noRestriction['total'] > 0) {
                  echo '<button class="btn btn-warning" type="button" data-bs-toggle="offcanvas" data-bs-target="#check-offcanvas" aria-controls="check-offcanvas" id="add-check">Add Check</button>';
                }
              ?>
          </div>
        </div>
      </div>
      <div class="card-body">
        <div class="dt-responsive table-responsive">
          <table id="check-table" class="table table-hover nowrap w-100 dataTable">
            <thead>
              <tr>
                <th>Bank/Branch</th>
                <th>Check Name</th>
                <th class="all">Check Date</th>
                <th class="all">Check No.</th>
                <th class="all">Check Amount</th>
                <th>Reversal Date</th>
                <th>Transmitted Date</th>
                <th>Outstanding Date</th>
                <th>Negotiated Date</th>
                <th class="all">Check Status</th>
                <th class="all">Actions</th>
              </tr>
            </thead>
            <tbody></tbody>
          </table>
        </div>
      </div>
    </div>
    
    <div class="row <?php echo $check_hidden; ?>">
      <div class="col-lg-12">
        <div class="card">
          <div class="card-body">
            <div class="row align-items-center">
              <div class="col-9">
                <h3 class="mb-1 text-end">Total Check:</h3>
              </div>
              <div class="col-3">
                <h3 class="mb-1 text-end" id="total-check">0.00 Php</h3>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div>
    <div class="offcanvas offcanvas-end" tabindex="-1" id="cancel-disbursement-offcanvas" aria-labelledby="cancel-disbursement-offcanvas-label">
      <div class="offcanvas-header">
        <h2 id="cancel-disbursement-offcanvas-label" style="margin-bottom:-0.5rem">Cancel Disbursement</h2>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
      </div>
    <div class="offcanvas-body">
      <div class="row">
        <div class="col-lg-12">
          <form id="cancel-disbursement-form" method="post" action="#">
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
          <button type="submit" class="btn btn-primary" id="submit-cancel-disbursement" form="cancel-disbursement-form">Submit</button>
          <button class="btn btn-light-danger" data-bs-dismiss="offcanvas"> Close </button>
        </div>
      </div>
    </div>
  </div>

  <div>
    <div class="offcanvas offcanvas-end" tabindex="-1" id="cancel-disbursement-check-offcanvas" aria-labelledby="cancel-disbursement-check-offcanvas-label">
      <div class="offcanvas-header">
        <h2 id="cancel-disbursement-check-offcanvas-label" style="margin-bottom:-0.5rem">Cancel Check</h2>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
      </div>
    <div class="offcanvas-body">
      <div class="row">
        <div class="col-lg-12">
          <form id="cancel-disbursement-check-form" method="post" action="#">
            <div class="form-group row">
              <div class="col-lg-12 mt-3 mt-lg-0">
                <label class="form-label">Cancellation Reason <span class="text-danger">*</span></label>
                <textarea class="form-control" id="check_cancellation_reason" name="check_cancellation_reason" maxlength="500"></textarea>
              </div>
            </div>
          </form>
        </div>
      </div>
      <div class="row">
        <div class="col-lg-12">
          <button type="submit" class="btn btn-primary" id="submit-cancel-disbursement-check" form="cancel-disbursement-check-form">Submit</button>
          <button class="btn btn-light-danger" data-bs-dismiss="offcanvas"> Close </button>
        </div>
      </div>
    </div>
  </div>

  <div>
    <div class="offcanvas offcanvas-end" tabindex="-1" id="negotiated-disbursement-check-offcanvas" aria-labelledby="negotiated-disbursement-check-offcanvas-label">
      <div class="offcanvas-header">
        <h2 id="negotiated-disbursement-check-offcanvas-label" style="margin-bottom:-0.5rem">Negotiated Check</h2>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
      </div>
    <div class="offcanvas-body">
      <div class="row">
        <div class="col-lg-12">
          <form id="negotiated-disbursement-check-form" method="post" action="#">
            <div class="form-group row">
              <div class="col-lg-12 mt-3 mt-lg-0">
                <label class="form-label">Negotiated Date <span class="text-danger">*</span></label>
                <div class="input-group date">
                  <input type="text" class="form-control regular-datepicker" id="negotiated_date" name="negotiated_date" autocomplete="off">
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
          <button type="submit" class="btn btn-primary" id="submit-negotiated-disbursement-check" form="negotiated-disbursement-check-form">Submit</button>
          <button class="btn btn-light-danger" data-bs-dismiss="offcanvas"> Close </button>
        </div>
      </div>
    </div>
  </div>

  <div>
    <div class="offcanvas offcanvas-end" tabindex="-1" id="particulars-offcanvas" aria-labelledby="particulars-offcanvas-label">
      <div class="offcanvas-header">
        <h2 id="particulars-offcanvas-label" style="margin-bottom:-0.5rem">Particulars</h2>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
      </div>
    <div class="offcanvas-body">
      <div class="row">
        <div class="col-lg-12">
          <form id="particulars-form" method="post" action="#">
            <input type="hidden" id="disbursement_particulars_id" name="disbursement_particulars_id">
            <div class="form-group row">
              <div class="col-lg-12 mt-3 mt-lg-0">
                <label class="form-label">Particulars <span class="text-danger">*</span></label>
                <select class="form-control offcanvas-select2" name="chart_of_account_id" id="chart_of_account_id">
                  <option value="">--</option>
                  <?php echo $chartOfAccountModel->generateActiveChartOfAccountDisbursementOptions(); ?>
                </select>
              </div>
            </div>
            <div class="form-group row">
              <div class="col-lg-12 mt-3 mt-lg-0">
                <label class="form-label">Company <span class="text-danger">*</span></label>
                <select class="form-control offcanvas-select2" name="particulars_company_id" id="particulars_company_id">
                  <option value="">--</option>
                  <?php echo $companyModel->generateCompanyOptions(); ?>
                </select>
              </div>
            </div>
            <div class="form-group row">
              <div class="col-lg-6 mt-3 mt-lg-0">
                <label class="form-label">Invoice/Particulars Amount <span class="text-danger">*</span></label>
                <input type="number" class="form-control" id="particulars_amount" name="particulars_amount" step="0.01">
              </div>
              <div class="col-lg-6 mt-3 mt-lg-0">
                <label class="form-label">Base Amount</label>
                <input type="number" class="form-control" id="base_amount" name="base_amount" step="0.01" readonly>
              </div>
            </div>
            <div class="form-group row">
              <div class="col-lg-4 mt-3 mt-lg-0">
                <label class="form-label">With VAT? <span class="text-danger">*</span></label>
                <select class="form-control" name="with_vat" id="with_vat">
                  <option value="No" selected>No</option>
                  <option value="Yes">Yes</option>
                </select>
              </div>
              <div class="col-lg-4 mt-3 mt-lg-0">
                <label class="form-label">With Withholding? <span class="text-danger">*</span></label>
                <select class="form-control" name="with_withholding" id="with_withholding">
                  <option value="No" selected>--</option>
                  <option value="1">Goods (1%)</option>
                  <option value="2">Services (2%)</option>
                  <option value="5">5%</option>
                </select>
              </div>
              <div class="col-lg-4 mt-3 mt-lg-0">
                <label class="form-label">Tax Quarter</label>
                <select class="form-control" name="tax_quarter" id="tax_quarter">
                  <option value="">--</option>
                  <option value="1">1st Quarter</option>
                  <option value="2">2nd Quarter</option>
                  <option value="3">3rd Quarter</option>
                </select>
              </div>
            </div>
            <div class="form-group row">
              <div class="col-lg-4 mt-3 mt-lg-0">
                <label class="form-label">VAT Amount</label>
                <input type="number" class="form-control" id="vat_amount" name="vat_amount" step="0.01" readonly>
              </div>
              <div class="col-lg-4 mt-3 mt-lg-0">
                <label class="form-label">Withholding Amount</label>
                <input type="number" class="form-control" id="withholding_amount" name="withholding_amount" step="0.01" readonly>
              </div>
              <div class="col-lg-4 mt-3 mt-lg-0">
                <label class="form-label">Total Amount</label>
                <input type="number" class="form-control" id="total_amount" name="total_amount" step="0.01" readonly>
              </div>
            </div>
            <div class="form-group row">
              <div class="col-lg-12 mt-3 mt-lg-0">
                <label class="form-label">Remarks</label>
                <textarea class="form-control" id="remarks" name="remarks" maxlength="5000"></textarea>
              </div>
            </div>
          </form>
        </div>
      </div>
      <div class="row">
        <div class="col-lg-12">
          <button type="submit" class="btn btn-primary" id="submit-particulars" form="particulars-form">Submit</button>
          <button class="btn btn-light-danger" data-bs-dismiss="offcanvas"> Close </button>
        </div>
      </div>
    </div>
  </div>

   <div>
    <div class="offcanvas offcanvas-end" tabindex="-1" id="bir-offcanvas" aria-labelledby="bir-offcanvas-label">
      <div class="offcanvas-header">
        <h2 id="bir-offcanvas-label" style="margin-bottom:-0.5rem">Print BIR 2307 Manual Values</h2>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
      </div>
    <div class="offcanvas-body">
      <div class="row">
        <div class="col-lg-12">
          <div class="form-group row">
            <div class="col-lg-6 mt-3 mt-lg-0">
              <label class="form-label">Coverage Start Date <span class="text-danger">*</span></label>
              <div class="input-group date">
                <input type="text" class="form-control regular-datepicker" id="coverage_start_date" name="coverage_start_date" autocomplete="off">
                <span class="input-group-text">
                  <i class="feather icon-calendar"></i>
                </span>
              </div>
            </div>
            <div class="col-lg-6 mt-3 mt-lg-0">
              <label class="form-label">Coverage End Date <span class="text-danger">*</span></label>
              <div class="input-group date">
                <input type="text" class="form-control regular-datepicker" id="coverage_end_date" name="coverage_end_date" autocomplete="off">
                <span class="input-group-text">
                  <i class="feather icon-calendar"></i>
                </span>
              </div>
            </div>
          </div>
          <div class="form-group row">
            <div class="col-lg-12 mt-3 mt-lg-0">
              <label class="form-label">Withholding Amount <span class="text-danger">*</span></label>
              <input type="number" class="form-control" id="witholding_amount" name="witholding_amount" step="0.01">
            </div>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-lg-12">
          <button type="button" class="btn btn-primary" id="submit-print-bir">Print</button>
          <button class="btn btn-light-danger" data-bs-dismiss="offcanvas"> Close </button>
        </div>
      </div>
    </div>
  </div>

  <div>
    <div class="offcanvas offcanvas-end" tabindex="-1" id="check-offcanvas" aria-labelledby="check-offcanvas-label">
      <div class="offcanvas-header">
        <h2 id="check-offcanvas-label" style="margin-bottom:-0.5rem">Check</h2>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
      </div>
    <div class="offcanvas-body">
      <div class="row">
        <div class="col-lg-12">
          <form id="check-form" method="post" action="#">
            <input type="hidden" id="disbursement_check_id" name="disbursement_check_id">
            <div class="form-group row update-hidden">
              <div class="col-lg-12 mt-3 mt-lg-0">
                <label class="form-label">Bank/Branch <span class="text-danger">*</span></label>
                <select class="form-control offcanvas-select2" name="bank_branch" id="bank_branch">
                  <option value="">--</option>
                  <option value="BPI-MELENCIO">BPI-MELENCIO</option>
                </select>
              </div>
            </div>
            <div class="form-group row">
              <div class="col-lg-12 mt-3 mt-lg-0">
                <label class="form-label">Check Name</label>
                <input type="text" class="form-control" id="check_name" name="check_name" maxlength="5000" autocomplete="off">
              </div>
            </div>
            <div class="form-group row">
              <div class="col-lg-12 mt-3 mt-lg-0">
                <label class="form-label">Check Date <span class="text-danger">*</span></label>
                <div class="input-group date">
                  <input type="text" class="form-control regular-datepicker" id="check_date" name="check_date" autocomplete="off">
                  <span class="input-group-text">
                    <i class="feather icon-calendar"></i>
                  </span>
                </div>
              </div>
            </div>
            <div class="form-group row">
              <div class="col-lg-12 mt-3 mt-lg-0">
                <label class="form-label">Amount <span class="text-danger">*</span></label>
                <input type="number" class="form-control" id="check_amount" name="check_amount" min="0" step="0.01">
              </div>
            </div>
          </form>
        </div>
      </div>
      <div class="row">
        <div class="col-lg-12">
          <button type="submit" class="btn btn-primary" id="submit-check" form="check-form">Submit</button>
          <button class="btn btn-light-danger" data-bs-dismiss="offcanvas"> Close </button>
        </div>
      </div>
    </div>
  </div>

<div>
    <div class="offcanvas offcanvas-end" tabindex="-1" id="reverse-disbursement-offcanvas" aria-labelledby="reverse-disbursement-offcanvas-label">
      <div class="offcanvas-header">
        <h2 id="reverse-disbursement-offcanvas-label" style="margin-bottom:-0.5rem">Reverse Disbursement</h2>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
      </div>
    <div class="offcanvas-body">
      <div class="row">
        <div class="col-lg-12">
          <form id="reverse-disbursement-form" method="post" action="#">
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
          <button type="submit" class="btn btn-primary" id="submit-reverse-disbursement" form="reverse-disbursement-form">Submit</button>
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
                '. $userModel->generateLogNotes('disbursement', $disbursementID) .'
              </div>
            </div>
          </div>
        </div>';
?>
</div>