<div class="row">
  <div class="col-lg-12">
    <div class="card">
      <div class="card-header">
        <div class="row align-items-center">
          <div class="col-md-6">
            <h5>Product</h5>
          </div>
          <?php
            if ($productCreateAccess['total'] > 0) {
               echo '<div class="col-md-6 text-sm-end mt-3 mt-sm-0">
                      <button type="submit" form="product-form" class="btn btn-success form-edit" id="submit-data">Save</button>
                      <button type="button" id="discard-create" class="btn btn-outline-danger form-edit">Discard</button>
                    </div>';
            }
          ?>
        </div>
      </div>
      <div class="card-body">
        <form id="product-form" method="post" action="#">
            <div class="form-group row">
                <label class="col-lg-2 col-form-label">Company <span class="text-danger">*</span></label>
                <div class="col-lg-4">
                  <select class="form-control select2" name="company_id" id="company_id">
                      <option value="">--</option>
                      <?php echo $companyModel->generateCompanyOptions(); ?>
                  </select>
                </div>
                <label class="col-lg-2 col-form-label">Product Category <span class="text-danger">*</span></label>
                <div class="col-lg-4">
                  <select class="form-control select2" name="product_subcategory_id" id="product_subcategory_id">
                      <option value="">--</option>
                      <?php echo $productSubcategoryModel->generateGroupedProductSubcategoryOptions(); ?>
                  </select>
                </div>
            </div>
            <div class="form-group row">
              <label class="col-lg-2 col-form-label">Description <span class="text-danger">*</span></label>
              <div class="col-lg-4">
                <input type="text" class="form-control" id="description" name="description" maxlength="500" autocomplete="off">
              </div>
              <label class="col-lg-2 col-form-label">Stock Number <span class="text-danger">*</span></label>
                <div class="col-lg-4">
                    <input type="text" class="form-control" id="stock_number" name="stock_number" maxlength="50" autocomplete="off">
                </div>
            </div>
            <div class="form-group row">
                <label class="col-lg-2 col-form-label">Engine Number</label>
                <div class="col-lg-4">
                    <input type="text" class="form-control" id="engine_number" name="engine_number" maxlength="100" autocomplete="off">
                </div>
                <label class="col-lg-2 col-form-label">Chassis Number</label>
                <div class="col-lg-4">
                    <input type="text" class="form-control" id="chassis_number" name="chassis_number" maxlength="100" autocomplete="off">
                </div>
            </div>
            <div class="form-group row">
                <label class="col-lg-2 col-form-label">Plate Number</label>
                <div class="col-lg-4">
                    <input type="text" class="form-control" id="plate_number" name="plate_number" maxlength="100" autocomplete="off">
                </div>
                <label class="col-lg-2 col-form-label">Warehouse <span class="text-danger">*</span></label>
                <div class="col-lg-4">
                  <select class="form-control select2" name="warehouse_id" id="warehouse_id">
                      <option value="">--</option>
                      <?php echo $warehouseModel->generateWarehouseOptions(); ?>
                  </select>
                </div>
            </div>
            <div class="form-group row align-items-center">
              <label class="col-lg-2 col-form-label">Body Type</label>
                <div class="col-lg-4">
                  <select class="form-control select2" name="body_type_id" id="body_type_id">
                      <option value="">--</option>
                      <?php echo $bodyTypeModel->generateBodyTypeOptions(); ?>
                  </select>
                </div>
                <label class="col-lg-2 col-form-label">Color</label>
                <div class="col-lg-4">
                  <select class="form-control select2" name="color_id" id="color_id">
                      <option value="">--</option>
                      <?php echo $colorModel->generateColorOptions(); ?>
                  </select>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-lg-2 col-form-label">Running Hours</label>
                <div class="col-lg-4">
                    <input type="number" class="form-control" id="running_hours" name="running_hours" min="0" step="0.01">
                </div>
                <label class="col-lg-2 col-form-label">Mileage</label>
                <div class="col-lg-4">
                    <input type="number" class="form-control" id="mileage" name="mileage" min="0" step="0.01">
                </div>
            </div>
            <div class="form-group row align-items-center">
                <label class="col-lg-2 col-form-label">Length</label>
                <div class="col-lg-4">
                    <div class="input-group">
                        <input type="number" class="form-control" id="length" name="length" min="0" step="0.01">
                        <select class="form-control" name="length_unit" id="length_unit">
                            <?php echo $unitModel->generateUnitByShortNameOptions(1); ?>
                        </select>
                    </div>
                </div>
                <label class="col-lg-2 col-form-label">Product Price</label>
                <div class="col-lg-4">
                    <input type="number" class="form-control" id="product_price" name="product_price" min="0" step="0.01">
                </div>
            </div>
            <div class="form-group row">
                <label class="col-lg-2 col-form-label">Product Cost</label>
                <div class="col-lg-4">
                    <input type="number" class="form-control" id="product_cost" name="product_cost" min="0" step="0.01">
                </div>
            </div>
            <div class="form-group row">
              <label class="col-lg-2 col-form-label">Remarks</label>
              <div class="col-lg-10">
                <textarea class="form-control" id="remarks" name="remarks" maxlength="1000" rows="3"></textarea>
              </div>
            </div>
        </form>
      </div>
    </div>
  </div>
</div>