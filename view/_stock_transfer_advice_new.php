<div class="row">
  <div class="col-lg-12">
    <div class="card">
      <div class="card-header">
        <div class="row align-items-center">
          <div class="col-md-6">
            <h5>Stock Transfer Advice</h5>
          </div>
          <?php
            if ($stockTransferAdviceCreateAccess['total'] > 0) {
               echo '<div class="col-md-6 text-sm-end mt-3 mt-sm-0">
                      <button type="submit" form="stock-transfer-advice-form" class="btn btn-success form-edit" id="submit-data">Save</button>
                      <button type="button" id="discard-create" class="btn btn-outline-danger form-edit">Discard</button>
                    </div>';
            }

            
          ?>
        </div>
      </div>
      <div class="card-body">
        <form id="stock-transfer-advice-form" method="post" action="#">
          <div class="form-group row">
              <label class="col-lg-2 col-form-label">Customer Reference</label>
              <div class="col-lg-10" id="internal-select">
                <select class="form-control select2" name="customer_id" id="customer_id">
                  <option value="">--</option>
                  <?php echo $customerModel->generateAllContactsOptions(); ?>
                </select>
              </div>
          </div>
          <div class="form-group row">
            <label class="col-lg-2 col-form-label">Transferred From <span class="text-danger">*</span></label>
            <div class="col-lg-4" id="internal-select">
                <select class="form-control select2" name="transferred_from" id="transferred_from">
                  <option value="">--</option>
                  <?php echo $productModel->generateAllProductWithStockNumberOptions(); ?>
                </select>
            </div>
            <label class="col-lg-2 col-form-label">Transferred To <span class="text-danger">*</span></label>
            <div class="col-lg-4" id="internal-select">
                <select class="form-control select2" name="transferred_to" id="transferred_to">
                  <option value="">--</option>
                  <?php echo $productModel->generateAllProductWithStockNumberOptions(); ?>
                </select>
            </div>
          </div>
          <div class="form-group row">
            <label class="col-lg-2 col-form-label">STA Type <span class="text-danger">*</span></label>
            <div class="col-lg-4" id="internal-select">
                <select class="form-control select2" name="sta_type" id="sta_type">
                  <option value="">--</option>
                  <option value="Transfer">Transfer</option>
                  <option value="Swap">Swap</option>
                </select>
            </div>
            <label class="col-lg-2 col-form-label">Company <span class="text-danger">*</span></label>
            <div class="col-lg-4" id="internal-select">
                <select class="form-control select2" name="company_id" id="company_id">
                <option value="">--</option>
                  <option value="2">NE Truck Builders</option>
                  <option value="3">FUSO Tarlac</option>
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