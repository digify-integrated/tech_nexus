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
                 <li><a href="print-disbursement-voucher.php?id='. $disbursementID .'" class="dropdown-item" target="_blank">Print Voucher</a></li>';
             
                    if ($postDisbursement['total'] > 0 && $disbursementStatus == 'Draft') {
                        $dropdown .= '<li><button class="dropdown-item" type="button" id="post-disbursement">Post Disbursement</button></li>';
                    }
             
                    if ($disbursementDeleteAccess['total'] > 0) {
                        $dropdown .= '<li><button class="dropdown-item" type="button" id="delete-disbursement-details">Delete Disbursement</button></li>';
                    }
              
                          
                  $dropdown .= '</ul>
                              </div>';
                      
                  echo $dropdown;

                  if ($disbursementWriteAccess['total'] > 0 && $disbursementStatus == 'Draft') {
                    echo '<button type="submit" form="disbursement-form" class="btn btn-success" id="submit-data">Save</button>
                          <button type="button" id="discard-create" class="btn btn-outline-danger me-2">Discard</button>';
                  }

                  if ($disbursementCreateAccess['total'] > 0) {
                      echo '<a class="btn btn-success m-r-5 form-details" href="disbursement.php?new">Create</a>';
                  }
            ?>
          </div>
        </div>
      </div>
      <div class="card-body">
        <form id="disbursement-form" method="post" action="#">
            <?php
                $disabled = 'disabled';
                if ($disbursementWriteAccess['total'] > 0 && $disbursementStatus == 'Draft') {
                    $disabled = '';
                }
            ?>
            <div class="form-group row">
              <label class="col-lg-2 col-form-label">CDV Number <span class="text-danger">*</span></label>
              <div class="col-lg-4">
                <input type="text" class="form-control" id="transaction_number" name="transaction_number" maxlength="200" autocomplete="off" readonly>
              </div>
              <label class="col-lg-2 col-form-label">Reference Number</label>
              <div class="col-lg-4">
                <input type="text" class="form-control" id="reference_number" name="reference_number" maxlength="200" autocomplete="off" <?php echo $disabled; ?>>
              </div>
            </div>
            <div class="form-group row">
              <label class="col-lg-2 col-form-label">Transaction Type <span class="text-danger">*</span></label>
              <div class="col-lg-4">
                <select class="form-control select2" name="transaction_type" id="transaction_type" <?php echo $disabled; ?>>
                  <option value="">--</option>
                  <option value="Fund Encashment">Fund Encashment</option>
                  <option value="Fund Replenishment">Fund Replenishment</option>
                  <option value="Replenishment">Replenishment</option>
                  <option value="Encashment">Encashment</option>
                  <option value="Disbursement">Disbursement</option>
                  <option value="Liquidation">Liquidation</option>
                  <option value="Liquidation RF">Liquidation RF</option>
                  <option value="Disbursement - Liquidation">Disbursement - Liquidation</option>
                  <option value="Disbursement - Liquidation RF">Disbursement - Liquidation</option>
                  <option value="Disbursement - Noncash">Disbursement - Noncash</option>
                </select>
              </div>
              <label class="col-lg-2 col-form-label">Customer</label>
              <div class="col-lg-4">
                <select class="form-control select2" name="customer_id" id="customer_id" <?php echo $disabled; ?>>
                  <option value="">--</option>
                  <?php echo $customerModel->generateAllContactsOptions(); ?>
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
            <label class="col-lg-2 col-form-label">Company</label>
            <div class="col-lg-4">
              <select class="form-control select2" name="company_id" id="company_id" <?php echo $disabled; ?>>
                <option value="">--</option>
                <?php echo $companyModel->generateCompanyOptions(); ?>
               </select>
            </div>
          </div>
          <div class="form-group row">
            <label class="col-lg-2 col-form-label">Payment Amount <span class="text-danger">*</span></label>
            <div class="col-lg-4">
                <input type="number" class="form-control" id="disbursement_amount" name="disbursement_amount" min="0" step="0.01" <?php echo $disabled; ?>>
            </div>
          </div>
          <div class="form-group row">
            <label class="col-lg-2 col-form-label">Particulars <span class="text-danger">*</span></label>
            <div class="col-lg-10">
              <textarea class="form-control" id="particulars" name="particulars" maxlength="500" <?php echo $disabled; ?>></textarea>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>

  <div>
    <div class="offcanvas offcanvas-end" tabindex="-1" id="disbursement-cancel-offcanvas" aria-labelledby="disbursement-cancel-offcanvas-label">
      <div class="offcanvas-header">
        <h2 id="disbursement-cancel-offcanvas-label" style="margin-bottom:-0.5rem">Cancel Disbursement</h2>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
      </div>
    <div class="offcanvas-body">
      <div class="row">
        <div class="col-lg-12">
          <form id="disbursement-cancel-form" method="post" action="#">
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
          <button type="submit" class="btn btn-primary" id="submit-disbursement-cancel" form="disbursement-cancel-form">Submit</button>
          <button class="btn btn-light-danger" data-bs-dismiss="offcanvas"> Close </button>
        </div>
      </div>
    </div>
  </div>

  <div>
    <div class="offcanvas offcanvas-end" tabindex="-1" id="disbursement-reverse-offcanvas" aria-labelledby="disbursement-reverse-offcanvas-label">
      <div class="offcanvas-header">
        <h2 id="disbursement-reverse-offcanvas-label" style="margin-bottom:-0.5rem">Reverse Disbursement</h2>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
      </div>
    <div class="offcanvas-body">
      <div class="row">
        <div class="col-lg-12">
          <form id="disbursement-reverse-form" method="post" action="#">
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
          <button type="submit" class="btn btn-primary" id="submit-disbursement-reverse" form="disbursement-reverse-form">Submit</button>
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