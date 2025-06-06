<div class="row">
  <div class="col-lg-12">
    <div class="card">
      <div class="card-header">
        <div class="row align-items-center">
          <div class="col-md-6">
            <h5>Disbursement</h5>
          </div>
          <?php
            if ($disbursementCreateAccess['total'] > 0) {
               echo '<div class="col-md-6 text-sm-end mt-3 mt-sm-0">
                      <button type="submit" form="disbursement-form" class="btn btn-success form-edit" id="submit-data">Save</button>
                      <button type="button" id="discard-create" class="btn btn-outline-danger form-edit">Discard</button>
                    </div>';
            }

            
          ?>
        </div>
      </div>
      <div class="card-body">
        <form id="disbursement-form" method="post" action="#">
        <input type="hidden" id="disbursement_category" name="disbursement_category" value="<?php echo $disbursementCategory; ?>">
          <div class="form-group row">
            <label class="col-lg-2 col-form-label">Payable Type <span class="text-danger">*</span></label>
            <div class="col-lg-4">
                <select class="form-control select2" name="payable_type" id="payable_type">
                  <option value="Customer" <?php echo $payableClient; ?>>Customer</option>
                  <option value="Miscellaneous" <?php echo $payableMisc; ?>>Miscellaneous</option>
                </select>
            </div>
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
            <label class="col-lg-2 col-form-label">Customer <span class="text-danger">*</span></label>
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
            <select class="form-control select2" name="department_id" id="department_id">
                  <option value="">--</option>
                  <?php echo $departmentModel->generateDepartmentOptions(); ?>
                </select>
            </div>
            <label class="col-lg-2 col-form-label">Company <span class="text-danger">*</span></label>
            <div class="col-lg-4">
            <select class="form-control select2" name="company_id" id="company_id">
                  <option value="">--</option>
                  <?php echo $companyModel->generateCompanyOptions(); ?>
                </select>
            </div>
          </div>
          <div class="form-group row">
            <label class="col-lg-2 col-form-label">Transaction Type <span class="text-danger">*</span></label>
            <div class="col-lg-4">
              <select class="form-control select2" name="transaction_type" id="transaction_type">
                <option value="">--</option>
                <option value="Replenishment">Replenishment</option>
                <option value="Disbursement" selected>Disbursement</option>
               </select>
            </div>
            <label class="col-lg-2 col-form-label">Fund Source <span class="text-danger">*</span></label>
            <div class="col-lg-4">
              <select class="form-control select2" name="fund_source" id="fund_source">
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
            <label class="col-lg-2 col-form-label">Transaction Date <span class="text-danger">*</span></label>
            <div class="col-lg-4">
                <div class="input-group date">
                    <input type="text" class="form-control future-date-restricted-datepicker" id="transaction_date" name="transaction_date" autocomplete="off" value="<?php echo date('m/d/Y'); ?>">
                    <span class="input-group-text">
                        <i class="feather icon-calendar"></i>
                    </span>
                </div>
            </div>
          </div>
          <div class="form-group row">
            <label class="col-lg-2 col-form-label">Particulars <span class="text-danger">*</span></label>
            <div class="col-lg-10">
              <textarea class="form-control" id="particulars" name="particulars" maxlength="2000"></textarea>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>