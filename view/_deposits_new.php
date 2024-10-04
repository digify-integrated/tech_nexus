<div class="row">
  <div class="col-lg-12">
    <div class="card">
      <div class="card-header">
        <div class="row align-items-center">
          <div class="col-md-6">
            <h5>Deposits</h5>
          </div>
          <?php
            if ($depositsCreateAccess['total'] > 0) {
               echo '<div class="col-md-6 text-sm-end mt-3 mt-sm-0">
                      <button type="submit" form="deposits-form" class="btn btn-success form-edit" id="submit-data">Save</button>
                      <button type="button" id="discard-create" class="btn btn-outline-danger form-edit">Discard</button>
                    </div>';
            }
          ?>
        </div>
      </div>
      <div class="card-body">
        <form id="deposits-form" method="post" action="#">
          <div class="form-group row">
            <label class="col-lg-2 col-form-label">Deposit Amount <span class="text-danger">*</span></label>
            <div class="col-lg-4">
                <input type="number" class="form-control" id="deposit_amount" name="deposit_amount" min="0.01" step="0.01">
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
            <div class="col-lg-10">
              <input type="text" class="form-control" id="reference_number" name="reference_number" maxlength="200" autocomplete="off">
            </div>
          </div>
          <div class="form-group row">
           <label class="col-lg-2 col-form-label">Deposited To <span class="text-danger">*</span></label>
            <div class="col-lg-10">
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