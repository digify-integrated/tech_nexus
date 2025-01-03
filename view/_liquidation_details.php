<div class="row">
  <div class="col-lg-12">
    <div class="card">
      <div class="card-header">
        <div class="row align-items-center">
          <div class="col-md-6">
            <h5>Liquidations</h5>
          </div>
          <div class="col-md-6 text-sm-end mt-3 mt-sm-0">
            <?php                            
                 $dropdown = '<div class="btn-group m-r-5">
                 <button type="button" class="btn btn-outline-secondary dropdown-toggle form-details" data-bs-toggle="dropdown" aria-expanded="false">
                     Action
                 </button>
                 <ul class="dropdown-menu dropdown-menu-end">';
             
                    if ($liquidationDeleteAccess['total'] > 0) {
                        $dropdown .= '<li><button class="dropdown-item" type="button" id="delete-liquidation-details">Delete Liquidation</button></li>';
                    }
              
                          
                  $dropdown .= '</ul>
                              </div>';
                      
                  echo $dropdown;
            ?>
          </div>
        </div>
      </div>
      <div class="card-body">
        <form id="liquidation-form" method="post" action="#">
        <div class="form-group row">
            <label class="col-lg-2 col-form-label">CDV Number</label>
            <div class="col-lg-4">
              <input type="text" class="form-control" id="transaction_number" name="transaction_number" maxlength="200" autocomplete="off" readonly>
            </div>
            <label class="col-lg-2 col-form-label">Remaining Balance</label>
            <div class="col-lg-4">
              <input type="text" class="form-control" id="remaining_balance" name="remaining_balance" autocomplete="off" readonly>
            </div>
          </div>
          <div class="form-group row">
            <label class="col-lg-2 col-form-label">Payable Type</label>
            <div class="col-lg-4">
                <select class="form-control select2" name="payable_type" id="payable_type" disabled>
                  <option value="Customer" selected>Customer</option>
                  <option value="Miscellaneous">Miscellaneous</option>
                </select>
            </div>
            <label class="col-lg-2 col-form-label">Customer</label>
            <div class="col-lg-4" id="customer-select">
                <select class="form-control select2" name="customer_id" id="customer_id" disabled>
                  <option value="">--</option>
                  <?php echo $customerModel->generateAllContactsOptions(); ?>
                </select>
            </div>
            <div class="col-lg-4 d-none" id="misc-select">
                <select class="form-control select2" name="misc_id" id="misc_id" disabled>
                  <option value="">--</option>
                  <?php echo $miscellaneousClientModel->generateMiscellaneousClientOptions(); ?>
                </select>
            </div>
          </div>
          <div class="form-group row">
            <label class="col-lg-2 col-form-label">Department</label>
            <div class="col-lg-4">
            <select class="form-control select2" name="department_id" id="department_id" disabled>
                  <option value="">--</option>
                  <?php echo $departmentModel->generateDepartmentOptions(); ?>
                </select>
            </div>
            <label class="col-lg-2 col-form-label">Company</label>
            <div class="col-lg-4">
            <select class="form-control select2" name="company_id" id="company_id" disabled>
                  <option value="">--</option>
                  <?php echo $companyModel->generateCompanyOptions(); ?>
                </select>
            </div>
          </div>
          <div class="form-group row">
            <label class="col-lg-2 col-form-label">Transaction Type</label>
            <div class="col-lg-4">
              <select class="form-control select2" name="transaction_type" id="transaction_type" disabled>
                <option value="">--</option>
                <option value="Replenishment">Replenishment</option>
                <option value="Disbursement">Disbursement</option>
               </select>
            </div>
            <label class="col-lg-2 col-form-label">Fund Source</label>
            <div class="col-lg-4">
              <select class="form-control select2" name="fund_source" id="fund_source" disabled>
                <option value="">--</option>
                <option value="Petty Cash">Petty Cash</option>
                <option value="Revolving Fund">Revolving Fund</option>
                <option value="Check">Check</option>
               </select>
            </div>
          </div>
          <div class="form-group row">
            <label class="col-lg-2 col-form-label">Particulars</label>
            <div class="col-lg-10">
              <textarea class="form-control" id="particulars" name="particulars" maxlength="500" disabled></textarea>
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
                if ($liquidationWriteAccess['total'] > 0) {
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
                <th>Liquidation Details</th>
                <th>Amount</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody></tbody>
          </table>
        </div>
      </div>
    </div>
    
    <div class="card table-card">
      <div class="card-header">
        <div class="row align-items-center">
          <div class="col-sm-6">
            <h5>Excess/Deficit</h5>
          </div>
          <div class="col-md-6 text-sm-end mt-3 mt-sm-0">
              <?php 
                if ($liquidationWriteAccess['total'] > 0) {
                  echo '<button class="btn btn-warning" type="button" data-bs-toggle="offcanvas" data-bs-target="#particulars-offcanvas" aria-controls="particulars-offcanvas" id="add-excess">Add Excess/Deficit</button>';
                }
              ?>
          </div>
        </div>
      </div>
      <div class="card-body">
        <div class="dt-responsive table-responsive">
          <table id="addon-table" class="table table-hover nowrap w-100 dataTable">
            <thead>
              <tr>
                <th>Liquidation Details</th>
                <th>Reference Type</th>
                <th>Reference Number</th>
                <th>Amount</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody></tbody>
          </table>
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
            <input type="hidden" id="liquidation_type" name="liquidation_type">
            <input type="hidden" id="liquidation_particulars_id" name="liquidation_particulars_id">
            <div class="form-group row">
              <div class="col-lg-12 mt-3 mt-lg-0">
                <label class="form-label">Particulars</label>
                <textarea class="form-control" id="particulars" name="particulars" maxlength="500"></textarea>
              </div>
            </div>
            <div class="form-group row">
              <div class="col-lg-12 mt-3 mt-lg-0">
                <label class="form-label">Amount</label>
                <input type="number" class="form-control" id="particulars_amount" name="particulars_amount" min="0" step="0.01">
              </div>
            </div>
            <div class="form-group row addon-hidden d-none">
              <div class="col-lg-12 mt-3 mt-lg-0">
                <label class="form-label">Reference Type</label>
                <select class="form-control offcanvas-select2" name="reference_type" id="reference_type">
                  <option value="">--</option>
                  <option value="OR">OR</option>
                  <option value="CDV">CDV</option>
                </select>
              </div>
            </div>
            <div class="form-group row addon-hidden d-none">
              <div class="col-lg-12 mt-3 mt-lg-0">
                <label class="form-label">Reference Number</label>
                <input type="text" class="form-control" id="reference_number" name="reference_number" maxlength="100" autocomplete="off">
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
                '. $userModel->generateLogNotes('liquidation', $liquidationID) .'
              </div>
            </div>
          </div>
        </div>';
?>
</div>