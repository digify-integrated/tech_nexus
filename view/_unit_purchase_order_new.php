<div class="row">
  <div class="col-lg-12">
    <div class="card">
      <div class="card-header">
        <div class="row align-items-center">
          <div class="col-md-6">
            <h5>Purchase Order</h5>
          </div>
          <?php
            if ($purchaseOrderCreateAccess['total'] > 0) {
               echo '<div class="col-md-6 text-sm-end mt-3 mt-sm-0">
                      <button type="submit" form="purchase-order-form" class="btn btn-success form-edit" id="submit-data">Save</button>
                      <button type="button" id="discard-create" class="btn btn-outline-danger form-edit">Discard</button>
                    </div>';
            }

            
          ?>
        </div>
      </div>
      <div class="card-body">
        <form id="purchase-order-form" method="post" action="#">
          <div class="form-group row">
            <label class="col-lg-2 col-form-label">Supplier <span class="text-danger">*</span></label>
            <div class="col-lg-4">
              <select class="form-control select2" name="supplier_id" id="supplier_id">
                <option value="">--</option>
                <?php echo $supplierModel->generateSupplierOptions(); ?>
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