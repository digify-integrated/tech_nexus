<div class="row">
  <div class="col-lg-12">
    <div class="card">
      <div class="card-header">
        <div class="row align-items-center">
          <div class="col-md-6">
            <h5><?php echo $cardLabel; ?> Transaction</h5>
          </div>
          <?php
            if ($partsTransactionCreateAccess['total'] > 0) {
               echo '<div class="col-md-6 text-sm-end mt-3 mt-sm-0">
                      <button type="submit" form="parts-transaction-form" class="btn btn-success form-edit" id="submit-data">Save</button>
                      <button type="button" id="discard-create" class="btn btn-outline-danger form-edit">Discard</button>
                    </div>';
            }

            
          ?>
        </div>
      </div>
      <div class="card-body">
        <form id="parts-transaction-form" method="post" action="#">
          <div class="form-group row">
            <label class="col-lg-2 col-form-label">Customer Type <span class="text-danger">*</span></label>
            <div class="col-lg-4">
                <select class="form-control select2" name="customer_type" id="customer_type">
                  <?php
                    if($company == '1'){
                      echo '<option value="Department">Department</option>';
                    }
                    else{
                      echo '<option value="Customer">Customer</option>
                          <option value="Miscellaneous">Miscellaneous</option>
                            <option value="Internal">Internal</option>';
                    }
                    ?>
                </select>
            </div>
            <label class="col-lg-2 col-form-label <?php if($company == '1') echo 'd-none'; ?>" id="customer-label">Customer <span class="text-danger">*</span></label>
            <label class="col-lg-2 col-form-label d-none" id="internal-label">Product <span class="text-danger">*</span></label>
            <label class="col-lg-2 col-form-label <?php if($company == '2' || $company == '3') echo 'd-none'; ?>" id="department-label">Department <span class="text-danger">*</span></label>
            <div class="col-lg-4 <?php if($company == '1') echo 'd-none'; ?>" id="customer-select">
                <select class="form-control select2" name="customer_id" id="customer_id">
                  <option value="">--</option>
                  <?php echo $customerModel->generateAllContactsOptions(); ?>
                </select>
            </div>
            <div class="col-lg-4 d-none" id="misc-select">
                <select class="form-control select2" name="misc_id" id="misc_id">
                  <option value="">--</option>
                  <?php echo $miscellaneousClientModel->generateMiscellaneousClientOptions(); ?>
                </select>
            </div>
            <div class="col-lg-4 d-none" id="internal-select">
                <select class="form-control select2" name="product_id" id="product_id">
                  <option value="">--</option>
                  <?php echo $productModel->generateAllProductWithStockNumberOptions(); ?>
                </select>
            </div>
            <div class="col-lg-4 <?php if($company == '2' || $company == '3') echo 'd-none'; ?>" id="department-select">
                <select class="form-control select2" name="department_id" id="department_id">
                  <option value="">--</option>
                  <?php echo $departmentModel->generateDepartmentOptions(); ?>
                </select>
            </div>
            
          </div>
          <div class="form-group row d-none">
            <label class="col-lg-2 col-form-label">Reference Number</label>
            <div class="col-lg-4">
              <input type="text" class="form-control" id="reference_number" name="reference_number" maxlength="100" autocomplete="off">
            </div>
            <label class="col-lg-2 col-form-label">Reference Date</label>
            <div class="col-lg-4">
              <div class="input-group date">
                <input type="text" class="form-control regular-datepicker" id="reference_date" name="reference_date" autocomplete="off">
                <span class="input-group-text">
                  <i class="feather icon-calendar"></i>
                </span>
              </div>
            </div>
          </div>
          <div class="form-group row ">
            <label class="col-lg-2 col-form-label d-none">Issuance Number</label>
            <div class="col-lg-4 d-none">
              <input type="text" class="form-control" id="issuance_no" name="issuance_no" maxlength="100" autocomplete="off">
            </div>
            <label class="col-lg-2 col-form-label">Request By <span class="text-danger">*</span></label>
            <div class="col-lg-4">
              <input type="text" class="form-control" id="request_by" name="request_by" maxlength="500" autocomplete="off">
            </div>
             <?php
              $suppliesHidden = '';

              if($company == '1'){
                $suppliesHidden = 'd-none';
              }
            ?>
            <label class="col-lg-2 col-form-label <?php echo $suppliesHidden; ?>">Customer Reference <span class="text-danger">*</span></label>
            <div class="col-lg-4 <?php echo $suppliesHidden; ?>">
                <select class="form-control select2" name="customer_ref_id" id="customer_ref_id">
                  <option value="">--</option>
                  <?php echo $customerModel->generateAllContactsOptions(); ?>
                </select>
            </div>
          </div>
          <div class="form-group row <?php if($company == '3') echo 'd-none'; ?>">
            <label class="col-lg-2 col-form-label">Issuance Date</label>
            <div class="col-lg-4">
              <div class="input-group date">
                <input type="text" class="form-control regular-datepicker" id="issuance_date" name="issuance_date" autocomplete="off">
                <span class="input-group-text">
                  <i class="feather icon-calendar"></i>
                </span>
              </div>
            </div>
            <label class="col-lg-2 col-form-label d-none issuance-for-details">Issuance For? <span class="text-danger">*</span></label>
            <div class="col-lg-4 d-none issuance-for-details">
              <select class="form-control select2" name="issuance_for" id="issuance_for">
                <option value="">--</option>
                <option value="Repairs">Repairs</option>
                <option value="Tools">Tools</option>
              </select>
            </div>
          </div>
          <div class="form-group row">
            <label class="col-lg-2 col-form-label">Remarks</label>
            <div class="col-lg-10">
              <textarea class="form-control" id="remarks" name="remarks" maxlength="2000"></textarea>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>