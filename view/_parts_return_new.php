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
          <div class="form-group row">
            <label class="col-lg-2 col-form-label">Supplier <span class="text-danger">*</span></label>
            <div class="col-lg-4">
              <select class="form-control select2" name="supplier_id" id="supplier_id">
                <option value="">--</option>
                <?php echo $supplierModel->generateSupplierOptions(); ?>
              </select>
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
            <label class="col-lg-2 col-form-label">Ref. Invoice No. <span class="text-danger">*</span></label>
            <div class="col-lg-4">
              <input type="text" class="form-control" id="ref_invoice_number" name="ref_invoice_number" maxlength="100" autocomplete="off">
            </div>
            <label class="col-lg-2 col-form-label">Ref. PO No. <span class="text-danger">*</span></label>
            <div class="col-lg-4">
              <input type="text" class="form-control" id="ref_po_number" name="ref_po_number" maxlength="100" autocomplete="off">
            </div>
          </div>
          <div class="form-group row">
            <label class="col-lg-2 col-form-label">Ref. PO Date <span class="text-danger">*</span></label>
            <div class="col-lg-4">
              <div class="input-group date">
                <input type="text" class="form-control regular-datepicker" id="ref_po_date" name="ref_po_date" autocomplete="off">
                <span class="input-group-text">
                  <i class="feather icon-calendar"></i>
                </span>
              </div>
            </div>            
            <label class="col-lg-2 col-form-label">Previous Total Billing</label>
            <div class="col-lg-4">
                <input type="number" class="form-control" id="prev_total_billing" name="prev_total_billing" min="0" step="0.01">
            </div>
          </div>
          <div class="form-group row">      
            <label class="col-lg-2 col-form-label">Adusted Total Billing</label>
            <div class="col-lg-4">
                <input type="number" class="form-control" id="adjusted_total_billing" name="adjusted_total_billing" min="0" step="0.01">
            </div>
          </div>
          <div class="form-group row">
            <label class="col-lg-2 col-form-label">Remarks</label>
            <div class="col-lg-10">
              <textarea class="form-control" id="remarks" name="remarks" maxlength="1000"></textarea>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>