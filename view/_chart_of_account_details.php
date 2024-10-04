<div class="row">
  <div class="col-lg-12">
    <div class="card">
      <div class="card-header">
        <div class="row align-items-center">
          <div class="col-md-6">
            <h5>Chart of Account</h5>
          </div>
          <div class="col-md-6 text-sm-end mt-3 mt-sm-0">
            <?php                            
                $dropdown = '<div class="btn-group m-r-5">
                                <button type="button" class="btn btn-outline-secondary dropdown-toggle form-details" data-bs-toggle="dropdown" aria-expanded="false">
                                    Action
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end">';

                if ($chartOfAccountDuplicateAccess['total'] > 0) {
                    $dropdown .= '<li><button class="dropdown-item" type="button" id="duplicate-chart-of-account">Duplicate Chart of Account</button></li>';
                }
                            
                if ($chartOfAccountDeleteAccess['total'] > 0) {
                    $dropdown .= '<li><button class="dropdown-item" type="button" id="delete-chart-of-account-details">Delete Chart of Account</button></li>';
                }
                        
                $dropdown .= '</ul>
                            </div>';

                if ($chartOfAccountWriteAccess['total'] > 0) {
                    $dropdown .= '<button type="submit" form="chart-of-account-form" class="btn btn-success" id="submit-data">Save</button>
                        <button type="button" id="discard-update" class="btn btn-outline-danger me-1">Discard</button>';
                }

                if ($chartOfAccountCreateAccess['total'] > 0) {
                    $dropdown .= '<a class="btn btn-success m-r-5 form-details" href="chart-of-account.php?new">Create</a>';
                }

                echo $dropdown;
            ?>
          </div>
        </div>
      </div>
      <div class="card-body">
        <?php
          $disabled = '';
          if($chartOfAccountWriteAccess['total'] == 0){
            $disabled = 'disabled';
          }
        ?>
        <form id="chart-of-account-form" method="post" action="#">
          <div class="form-group row">
            <label class="col-lg-2 col-form-label">Code <span class="text-danger">*</span></label>
            <div class="col-lg-4">
              <input type="text" class="form-control" id="code" name="code" maxlength="100" autocomplete="off" <?php echo $disabled;?>>
            </div>
            <label class="col-lg-2 col-form-label">Name <span class="text-danger">*</span></label>
            <div class="col-lg-4">
              <input type="text" class="form-control" id="code_name" name="code_name" maxlength="500" autocomplete="off" <?php echo $disabled;?>>
            </div>
          </div>
          <div class="form-group row">
            <label class="col-lg-2 col-form-label">Account Type <span class="text-danger">*</span></label>
            <div class="col-lg-10">
                <select class="form-control select2" name="account_type" id="account_type" <?php echo $disabled;?>>
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
          </div>
        </form>
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
                '. $userModel->generateLogNotes('chart_of_account', $chartOfAccountID) .'
              </div>
            </div>
          </div>
        </div>';
?>
</div>