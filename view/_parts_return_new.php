<div class="row">
  <div class="col-lg-12">
    <div class="card">
      <div class="card-header">
        <div class="row align-items-center">
          <div class="col-md-6">
            <h5><?php echo $cardLabel; ?> Return</h5>
          </div>
          <?php
            if ($partsReturnCreateAccess['total'] > 0) {
               echo '<div class="col-md-6 text-sm-end mt-3 mt-sm-0">
                      <button type="submit" form="parts-return-form" class="btn btn-success form-edit" id="submit-data">Save</button>
                      <button type="button" id="discard-create" class="btn btn-outline-danger form-edit">Discard</button>
                    </div>';
            }
          ?>
        </div>
      </div>
      <div class="card-body">
        <form id="parts-return-form" method="post" action="#">
          <?php
            $hidden = '';
            $suppliesHidden = '';
            if($company == '2' || $company == '1'){
              $hidden = 'd-none';
            }

            if($company == '1'){
              $suppliesHidden = 'd-none';
            }
          ?>
          <div class="form-group row <?php echo $hidden; ?>">
            <label class="col-lg-2 col-form-label">Reference Number <span class="text-danger">*</span></label>
            <div class="col-lg-10">
              <input type="text" class="form-control" id="reference_number" name="reference_number" maxlength="300" autocomplete="off">
            </div>
          </div>
          <div class="form-group row">
            <label class="col-lg-2 col-form-label">Requested By <span class="text-danger">*</span></label>
            <div class="col-lg-4">
              <input type="text" class="form-control" id="request_by" name="request_by" maxlength="500" autocomplete="off">
            </div>
             <label class="col-lg-2 col-form-label">Purchase Date <span class="text-danger">*</span></label>
            <div class="col-lg-4">
              <div class="input-group date">
                <input type="text" class="form-control regular-datepicker" id="purchase_date" name="purchase_date" autocomplete="off">
                <span class="input-group-text">
                  <i class="feather icon-calendar"></i>
                </span>
              </div>
            </div>
          </div>
          <div class="form-group row">
            <label class="col-lg-2 col-form-label">Supplier <span class="text-danger">*</span></label>
            <div class="col-lg-4">
              <select class="form-control select2" name="supplier_id" id="supplier_id">
                <option value="">--</option>
                <?php echo $supplierModel->generateSupplierOptions(); ?>
              </select>
            </div>
            <label class="col-lg-2 col-form-label <?php echo $suppliesHidden; ?>">Customer Reference <span class="text-danger">*</span></label>
            <div class="col-lg-4 <?php echo $suppliesHidden; ?>">
                <select class="form-control select2" name="customer_ref_id" id="customer_ref_id">
                  <option value="">--</option>
                  <?php echo $customerModel->generateAllContactsOptions(); ?>
                </select>
            </div>
          </div>
          <div class="form-group row <?php echo $suppliesHidden; ?>">
            <label class="col-lg-2 col-form-label">Product <span class="text-danger">*</span></label>
            <div class="col-lg-4">
              <select class="form-control select2" name="product_id" id="product_id">
                <option value="">--</option>
                <?php echo $productModel->generateAllProductWithStockNumberOptions(); ?>
              </select>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>