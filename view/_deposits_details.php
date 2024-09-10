<div class="row">
  <div class="col-lg-12">
    <div class="card">
      <div class="card-header">
        <div class="row align-items-center">
          <div class="col-md-6">
            <h5>Deposits</h5>
          </div>
          <div class="col-md-6 text-sm-end mt-3 mt-sm-0">
            <?php                            
                 $dropdown = '<div class="btn-group m-r-5">
                 <button type="button" class="btn btn-outline-secondary dropdown-toggle form-details" data-bs-toggle="dropdown" aria-expanded="false">
                     Action
                 </button>
                 <ul class="dropdown-menu dropdown-menu-end">';
             
                    if ($depositsDeleteAccess['total'] > 0 && strtotime($transactionDate) == strtotime(date('Y-m-d'))) {
                        $dropdown .= '<li><button class="dropdown-item" type="button" id="delete-deposits-details">Delete Deposits</button></li>';
                    }
                          
                  $dropdown .= '</ul>
                              </div>';
                      
                  echo $dropdown;

                  if ($depositsWriteAccess['total'] > 0 && strtotime($transactionDate) == strtotime(date('Y-m-d'))) {
                    echo '<button type="button" id="discard-create" class="btn btn-outline-danger me-2">Discard</button>';
                  }

                  if ($depositsCreateAccess['total'] > 0) {
                      echo '<a class="btn btn-success m-r-5 form-details" href="deposits.php?new">Create</a>';
                  }
            ?>
          </div>
        </div>
      </div>
      <div class="card-body">
        <form id="deposits-form" method="post" action="#">
          <div class="form-group row">
            <label class="col-lg-2 col-form-label">Deposit Amount <span class="text-danger">*</span></label>
            <div class="col-lg-4">
                <input type="number" class="form-control" id="deposit_amount" name="deposit_amount" min="0" step="0.01" disabled>
            </div>
            <label class="col-lg-2 col-form-label">Company <span class="text-danger">*</span></label>
              <div class="col-lg-4">
                <select class="form-control select2" name="company_id" id="company_id">
                  <option value="">--</option>
                  <option value="1">Christian General Motors Inc.</option>
                  <option value="2">NE Truck Builders</option>
                  <option value="3">FUSO Tarlac</option>
                </select>
              </div> 
          </div>
          <div class="form-group row">
            <label class="col-lg-2 col-form-label">Reference Number</label>
            <div class="col-lg-4">
              <input type="text" class="form-control" id="reference_number" name="reference_number" maxlength="200" autocomplete="off" disabled>
            </div>
            <label class="col-lg-2 col-form-label">Deposit Date <span class="text-danger">*</span></label>
            <div class="col-lg-4">
                <div class="input-group date">
                    <input type="text" class="form-control regular-datepicker" id="deposit_date" name="deposit_date" autocomplete="off" disabled>
                    <span class="input-group-text">
                        <i class="feather icon-calendar"></i>
                    </span>
                </div>
            </div>
          </div>
          <div class="form-group row">
            <label class="col-lg-2 col-form-label">Deposited To <span class="text-danger">*</span></label>
            <div class="col-lg-10">
              <select class="form-control select2" name="deposited_to" id="deposited_to" disabled>
                <option value="">--</option>
                <?php echo $bankModel->generateBankOptions(); ?>
               </select>
            </div>
          </div>
          <div class="form-group row">
            <label class="col-lg-2 col-form-label">Remarks</label>
            <div class="col-lg-10">
              <textarea class="form-control" id="remarks" name="remarks" maxlength="500" disabled></textarea>
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
                '. $userModel->generateLogNotes('deposits', $depositsID) .'
              </div>
            </div>
          </div>
        </div>';
?>
</div>