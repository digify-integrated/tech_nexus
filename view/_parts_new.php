<div class="row">
  <div class="col-lg-12">
    <div class="card">
      <div class="card-header">
        <div class="row align-items-center">
          <div class="col-md-6">
            <h5>Parts</h5>
          </div>
          <?php
            if ($partsCreateAccess['total'] > 0) {
               echo '<div class="col-md-6 text-sm-end mt-3 mt-sm-0">
                      <button type="submit" form="parts-form" class="btn btn-success form-edit" id="submit-data">Save</button>
                      <button type="button" id="discard-create" class="btn btn-outline-danger form-edit">Discard</button>
                    </div>';
            }
          ?>
        </div>
      </div>
      <div class="card-body">
        <form id="parts-form" method="post" action="#">
          <div class="form-group row">
            <h5 class="col-lg-12">Basic Information</h5>
            <hr/>
          </div>
          <div class="form-group row">
            <label class="col-lg-3 col-form-label">Brand <span class="text-danger">*</span></label>
            <div class="col-lg-3">
              <select class="form-control select2" name="brand_id" id="brand_id">
                <option value="">--</option>
                <?php echo $brandModel->generateBrandOptions(); ?>
              </select>
            </div>
            <label class="col-lg-3 col-form-label">Part Number <span class="text-danger">*</span></label>
            <div class="col-lg-3">
              <input type="text" class="form-control" id="part_number" name="part_number" maxlength="100" autocomplete="off">
            </div>
          </div>
          <div class="form-group row">
            <label class="col-lg-3 col-form-label">Bar Code <span class="text-danger">*</span></label>
            <div class="col-lg-3">
              <input type="text" class="form-control" id="bar_code" name="bar_code" maxlength="100" autocomplete="off">
            </div>
            <label class="col-lg-3 col-form-label">Category <span class="text-danger">*</span></label>
            <div class="col-lg-3">
              <select class="form-control select2" name="part_category_id" id="part_category_id">
                <option value="">--</option>
                <?php echo $partsCategoryModel->generatePartsCategoryOptions(); ?>
              </select>
            </div>
          </div>
          <div class="form-group row">
            <label class="col-lg-3 col-form-label">Class <span class="text-danger">*</span></label>
            <div class="col-lg-3">
              <select class="form-control select2" name="part_class_id" id="part_class_id">
              <option value="">--</option>
                <?php echo $partsClassModel->generatePartsClassOptions(); ?>
              </select>
            </div>
            <label class="col-lg-3 col-form-label">Subclass <span class="text-danger">*</span></label>
            <div class="col-lg-3">
              <select class="form-control select2" name="part_subclass_id" id="part_subclass_id">
              <option value="">--</option>
                <?php echo $partsSubclassModel->generatePartsSubclassOptions(); ?>
              </select>
            </div>
          </div>
          <div class="form-group row">
            <label class="col-lg-3 col-form-label d-none">Quantity <span class="text-danger">*</span></label>
            <div class="col-lg-3 d-none">
              <input type="number" class="form-control" id="quantity" name="quantity" value="0" min="0" step="0.01">
            </div>
            <label class="col-lg-3 col-form-label">Unit Sale <span class="text-danger">*</span></label>
            <div class="col-lg-3">
              <select class="form-control select2" name="unit_sale" id="unit_sale">
                <option value="">--</option>
                <?php echo $unitModel->generateUnitOptions(); ?>
              </select>
            </div>
            <label class="col-lg-3 col-form-label">Stock Alert <span class="text-danger">*</span></label>
            <div class="col-lg-3">
              <input type="number" class="form-control" id="stock_alert" name="stock_alert" value="0" min="0" step="1">
            </div>
          </div>
          <div class="form-group row">
            <label class="col-lg-3 col-form-label">Description <span class="text-danger">*</span></label>
            <div class="col-lg-9">
              <textarea class="form-control" id="description" name="description" maxlength="2000" rows="3"></textarea>
            </div>
          </div>
          <div class="form-group row">
            <label class="col-lg-3 col-form-label">Remarks</label>
            <div class="col-lg-9">
              <textarea class="form-control" id="remarks" name="remarks" maxlength="1000" rows="3"></textarea>
            </div>
          </div>
                
          <div class="form-group row mt-4">
            <h5 class="col-lg-12">Warehouse & Delivery Information</h5>
            <hr/>
          </div>
          <div class="form-group row">
            <label class="col-lg-3 col-form-label">Warehouse <span class="text-danger">*</span></label>
            <div class="col-lg-3">
              <select class="form-control select2" name="warehouse_id" id="warehouse_id">
                <option value="">--</option>
                <?php echo $warehouseModel->generateWarehouseOptions(); ?>
              </select>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>