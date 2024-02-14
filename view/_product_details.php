<?php
$updateProductButton = '';
$deleteProductButton = '';
if($productWriteAccess['total'] > 0){
    $updateProductButton = '<div class="col-4">
                                <div class="d-grid">
                                    <button class="btn btn-info" type="button" data-bs-toggle="offcanvas" data-bs-target="#update-product-offcanvas" aria-controls="update-product-offcanvas" id="update-product">Update Product</button>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="d-grid">
                                  <button class="btn btn-warning" type="button" data-bs-toggle="offcanvas" data-bs-target="#update-product-image-offcanvas" aria-controls="update-product-image-offcanvas" id="update-product-image">Update Image</button>
                                </div>
                            </div>';
}

if($productDeleteAccess['total'] > 0){
    $deleteProductButton = '<div class="col-4">
                                <div class="d-grid">
                                    <button class="btn btn-outline-danger" id="delete-product-details">Delete Product</button>
                                </div>
                            </div>';
}
?>
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="sticky-md-top product-sticky">
                          <img src="<?php echo $productImage; ?>" class="d-block w-100" alt="Product images" />
                        </div>
                    </div>
                    <div class="col-md-6">
                        <?php echo $productStatus; ?>
                        <h4 class="my-3 text-primary"><b><?php echo $description; ?></b></h4>
                        <h5 class="mt-4 mb-3 f-w-500">About this product</h5>
                        <ul class="mb-4">
                            <li class="mb-2">Stock Number: <?php echo $stockNumber; ?></li>
                            <li class="mb-2">Product Category: <?php echo $productCategoryName; ?></li>
                            <li class="mb-2">Product Subcategory:  <?php echo $productSubcategoryName; ?></li>
                            <li class="mb-2">Company:  <?php echo $companyName; ?></li>
                        </ul>
                        <h5 class="mb-2"><b>Price: <?php echo $productPrice; ?> Php</b></h5>
                        <div class="row">
                            <?php echo $updateProductButton . $deleteProductButton; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <div class="row align-items-center">
                    <div class="col-sm-6">
                        <h5>Product Specifications</h5>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-borderless">
                        <tbody>
                            <tr>
                                <td class="text-muted py-1">Engine Number :</td>
                                <td class="py-1"><?php echo $engineNumber; ?></td>
                            </tr>
                            <tr>
                                <td class="text-muted py-1">Chassis Number :</td>
                                <td class="py-1"><?php echo $chassisNumber; ?></td>
                            </tr>
                            <tr>
                                <td class="text-muted py-1">Warehouse :</td>
                                <td class="py-1"><?php echo $warehouseName; ?></td>
                            </tr>
                            <tr>
                                <td class="text-muted py-1">Body Type :</td>
                                <td class="py-1"><?php echo $bodyTypeName; ?></td>
                            </tr>
                            <tr>
                                <td class="text-muted py-1">Length :</td>
                                <td class="py-1"><?php echo $length . ' ' . $shortName ?></td>
                            </tr>
                            <tr>
                                <td class="text-muted py-1">Running Hours :</td>
                                <td class="py-1"><?php echo $runningHours; ?></td>
                            </tr>
                            <tr>
                                <td class="text-muted py-1">Mileage :</td>
                                <td class="py-1"><?php echo $mileage; ?></td>
                            </tr>
                            <tr>
                                <td class="text-muted py-1">Color :</td>
                                <td class="py-1"><?php echo $colorName; ?></td>
                            </tr>
                            <tr>
                                <td class="text-muted py-1">Remarks :</td>
                                <td class="py-1"><?php echo $remarks; ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
    if($productWriteAccess['total'] > 0){
        echo '<div class="offcanvas offcanvas-end" tabindex="-1" id="update-product-offcanvas" aria-labelledby="update-product-offcanvas-label">
        <div class="offcanvas-header">
          <h2 id="update-product-offcanvas-label" style="margin-bottom:-0.5rem">Product Information</h2>
          <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
          <div class="row">
            <div class="col-lg-12">
              <form id="product-form" method="post" action="#">
                <div class="form-group row">
                  <div class="col-lg-6">
                    <label class="form-label">Company <span class="text-danger">*</span></label>
                    <select class="form-control offcanvas-select2" name="company_id" id="company_id">
                      <option value="">--</option>
                      '. $companyModel->generateCompanyOptions() .'
                    </select>
                  </div>
                  <div class="col-lg-6">
                    <label class="form-label">Document Category <span class="text-danger">*</span></label>
                    <select class="form-control offcanvas-select2" name="product_subcategory_id" id="product_subcategory_id">
                      <option value="">--</option>
                      '. $productSubcategoryModel->generateGroupedProductSubcategoryOptions() .'
                    </select>
                  </div>
                </div>
                <div class="form-group row">
                  <div class="col-lg-6">
                    <label class="form-label">Description <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="description" name="description" maxlength="500" autocomplete="off">
                  </div>
                  <div class="col-lg-6">
                    <label class="form-label">Stock Number <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="stock_number" name="stock_number" maxlength="50" autocomplete="off">
                  </div>
                </div>
                <div class="form-group row">
                  <div class="col-lg-6">
                    <label class="form-label">Engine Number</label>
                    <input type="text" class="form-control" id="engine_number" name="engine_number" maxlength="100" autocomplete="off">
                  </div>
                  <div class="col-lg-6">
                    <label class="form-label">Chassis Number</label>
                    <input type="text" class="form-control" id="chassis_number" name="chassis_number" maxlength="100" autocomplete="off">
                  </div>
                </div>
                <div class="form-group row">
                  <div class="col-lg-6">
                    <label class="form-label">Warehouse <span class="text-danger">*</span></label>
                    <select class="form-control offcanvas-select2" name="warehouse_id" id="warehouse_id">
                        <option value="">--</option>
                        '. $warehouseModel->generateWarehouseOptions() .'
                    </select>
                  </div>
                  <div class="col-lg-6">
                    <label class="form-label">Body Type</label>
                    <select class="form-control offcanvas-select2" name="body_type_id" id="body_type_id">
                        <option value="">--</option>
                        '. $bodyTypeModel->generateBodyTypeOptions() .'
                    </select>
                  </div>
                </div>
                <div class="form-group row">
                  <div class="col-lg-6">
                    <label class="form-label">Color</label>
                    <select class="form-control offcanvas-select2" name="color_id" id="color_id">
                        <option value="">--</option>
                        '. $colorModel->generateColorOptions() .'
                    </select>
                  </div>
                  <div class="col-lg-6">
                    <label class="form-label">Running Hours</label>
                    <input type="number" class="form-control" id="running_hours" name="running_hours" min="0" step="0.01">
                  </div>
                </div>
                <div class="form-group row">
                  <div class="col-lg-6">
                    <label class="form-label">Mileage</label>
                    <input type="number" class="form-control" id="mileage" name="mileage" min="0" step="0.01">
                  </div>
                  <div class="col-lg-6">
                    <label class="form-label">Length</label>
                    <div class="input-group">
                      <input type="number" class="form-control" id="length" name="length" min="0" step="0.01">
                      <select class="form-control" name="length_unit" id="length_unit">
                        '. $unitModel->generateUnitByShortNameOptions(1) .'
                      </select>
                    </div>
                  </div>
                </div>
                <div class="form-group row">
                  <div class="col-lg-6">
                    <label class="form-label">Product Price</label>
                    <input type="number" class="form-control" id="product_price" name="product_price" min="0" step="0.01">
                  </div>
                  <div class="col-lg-6">
                    <label class="form-label">Product Cost</label>
                    <input type="number" class="form-control" id="product_cost" name="product_cost" min="0" step="0.01">
                  </div>
                </div>
                <div class="form-group row">
                  <div class="col-lg-12">
                    <label class="form-label">Remarks</label>
                    <textarea class="form-control" id="remarks" name="remarks" maxlength="1000" rows="3"></textarea>
                  </div>
                </div>
              </form>
            </div>
          </div>
          <div class="row">
            <div class="col-lg-12">
              <button type="submit" class="btn btn-primary" id="submit-data" form="product-form">Save</button>
              <button class="btn btn-light-danger" data-bs-dismiss="offcanvas"> Close </button>
            </div>
          </div>
        </div>
      </div>
      <div class="offcanvas offcanvas-end" tabindex="-1" id="update-product-image-offcanvas" aria-labelledby="update-product-image-offcanvas-label">
            <div class="offcanvas-header">
              <h2 id="update-product-image-offcanvas-label" style="margin-bottom:-0.5rem">Update Product Image</h2>
              <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body">
              <div class="row">
                <div class="col-lg-12">
                  <form id="product-image-form" method="post" action="#">
                    <div class="form-group row">
                      <div class="col-lg-12">
                        <label class="form-label">Product Image <span class="text-danger">*</span></label>
                        <input type="file" class="form-control" id="product_image" name="product_image">
                      </div>
                    </div>
                  </form>
                </div>
              </div>
              <div class="row">
                <div class="col-lg-12">
                  <button type="submit" class="btn btn-primary" id="submit-product-image-data" form="product-image-form">Save</button>
                  <button class="btn btn-light-danger" data-bs-dismiss="offcanvas"> Close </button>
                </div>
              </div>
            </div>
          </div>';
  }
  echo '<div class="row">
        <div class="col-lg-12">
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
                '. $userModel->generateLogNotes('product', $productID) .'
              </div>
            </div>
          </div>
        </div>
    </div>';
?>