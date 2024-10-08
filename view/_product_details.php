<?php
$updateProductButton = '';
$updateProductImageButton = '';
$deleteProductButton = '';

$checkSalesProposalProduct = $productModel->checkSalesProposalProduct($productID);

if($productWriteAccess['total'] > 0 && $checkSalesProposalProduct['total'] == 0 && $productStatus != 'Sold'){
    $updateProductButton = '<div class="col-4">
                                <div class="d-grid">
                                    <button class="btn btn-info" type="button" data-bs-toggle="offcanvas" data-bs-target="#update-product-offcanvas" aria-controls="update-product-offcanvas" id="update-product">Update Product</button>
                                </div>
                            </div>';
}

if($updateProductImage['total'] > 0 && $checkSalesProposalProduct['total'] == 0 && $productStatus != 'Sold'){
  $updateProductImageButton = '<div class="col-4">
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
  <div class="col-md-7">
    <div class="card">
      <div class="card-header">
        <div class="row align-items-center">
          <div class="col-sm-4">
            <h5>Product Details</h5>
          </div>
          <div class="col-md-8 text-sm-end mt-3 mt-sm-0">
            <?php
              $dropdown = '<div class="btn-group m-r-5">
                            <button type="button" class="btn btn-outline-secondary dropdown-toggle form-details" data-bs-toggle="dropdown" aria-expanded="false">
                              Action
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">';

                        if ($productDeleteAccess['total'] > 0) {
                            $dropdown .= '<li><button class="dropdown-item" type="button" id="delete-product-details">Delete Product</button></li>';
                        }
                                  
                        $dropdown .= '</ul>
                                      </div>';
                              
                        echo $dropdown;

                        if ($productWriteAccess['total'] > 0) {
                          echo '<button type="submit" form="product-details-form" class="btn btn-success me-1" id="submit-product-details-data">Save</button>';
                        }

                        if ($productCreateAccess['total'] > 0) {
                          echo '<a class="btn btn-success m-r-5 form-details" href="product.php?new">Create</a>';
                        }
            ?>
          </div>
        </div>
      </div>
      <div class="card-body">
        <div class="row">
          <div class="col-md-12">
            <form id="product-details-form" method="post" action="#">
              <div class="form-group row">
                <label class="col-lg-4 col-form-label">Stock Number <span class="text-danger">*</span></label>
                <div class="col-lg-8">
                  <input type="text" class="form-control" id="stock_number" name="stock_number" maxlength="50" autocomplete="off">
                </div>
              </div>
              <div class="form-group row">
                <label class="col-lg-4 col-form-label">Description <span class="text-danger">*</span></label>
                <div class="col-lg-8">
                  <input type="text" class="form-control" id="description" name="description" maxlength="500" autocomplete="off">
                </div>
              </div>
              <div class="form-group row">
                <label class="col-lg-4 col-form-label">RR Date</label>
                <div class="col-lg-8">
                  <div class="input-group date">
                    <input type="text" class="form-control regular-datepicker" id="rr_date" name="rr_date" autocomplete="off">
                    <span class="input-group-text">
                      <i class="feather icon-calendar"></i>
                    </span>
                  </div>
                </div>
              </div>
              <div class="form-group row">
                <label class="col-lg-4 col-form-label">RR No.</label>
                <div class="col-lg-8">
                  <input type="text" class="form-control" id="rr_no" name="rr_no" maxlength="100" autocomplete="off">
                </div>
              </div>
              <div class="form-group row">
                <label class="col-lg-4 col-form-label">Company <span class="text-danger">*</span></label>
                <div class="col-lg-8">
                  <select class="form-control select2" name="company_id" id="company_id">
                    <option value="">--</option>
                    <?php echo $companyModel->generateCompanyOptions(); ?>
                  </select>
                </div>
              </div>
              <div class="form-group row">
                <label class="col-lg-4 col-form-label">Supplier</label>
                <div class="col-lg-8">
                  <select class="form-control select2" name="supplier_id" id="supplier_id">
                    <option value="">--</option>
                    <?php echo $supplierModel->generateSupplierOptions(); ?>
                  </select>
                </div>
              </div>
              <div class="form-group row">
                <label class="col-lg-4 col-form-label">Ref No.</label>
                <div class="col-lg-8">
                  <input type="text" class="form-control" id="ref_no" name="ref_no" maxlength="200" autocomplete="off">
                </div>
              </div>
              <div class="form-group row">
                <label class="col-lg-4 col-form-label">Brand</label>
                <div class="col-lg-8">
                  <select class="form-control select2" name="brand_id" id="brand_id">
                    <option value="">--</option>
                    <?php echo $brandModel->generateBrandOptions(); ?>
                  </select>
                </div>
              </div>
              <div class="form-group row">
                <label class="col-lg-4 col-form-label">Cabin</label>
                <div class="col-lg-8">
                  <select class="form-control select2" name="cabin_id" id="cabin_id">
                    <option value="">--</option>
                    <?php echo $cabinModel->generateCabinOptions(); ?>
                  </select>
                </div>
              </div>
              <div class="form-group row">
                <label class="col-lg-4 col-form-label">Model</label>
                <div class="col-lg-8">
                  <select class="form-control select2" name="model_id" id="model_id">
                    <option value="">--</option>
                    <?php echo $modelModel->generateModelOptions(); ?>
                  </select>
                </div>
              </div>
              <div class="form-group row">
                <label class="col-lg-4 col-form-label">Make</label>
                <div class="col-lg-8">
                  <select class="form-control select2" name="make_id" id="make_id">
                    <option value="">--</option>
                    <?php echo $makeModel->generateMakeOptions(); ?>
                  </select>
                </div>
              </div>
              <div class="form-group row">
                <label class="col-lg-4 col-form-label">Body Type</label>
                <div class="col-lg-8">
                  <select class="form-control select2" name="body_type_id" id="body_type_id">
                    <option value="">--</option>
                    <?php echo $bodyTypeModel->generateBodyTypeOptions(); ?>
                  </select>
                </div>
              </div>
              <div class="form-group row">
                <label class="col-lg-4 col-form-label">Length</label>
                <div class="col-lg-8">
                  <div class="input-group">
                    <input type="number" class="form-control" id="length" name="length" min="0" step="0.01">
                    <select class="form-control" name="length_unit" id="length_unit">
                      <?php echo $unitModel->generateUnitByShortNameOptions(1); ?>
                    </select>
                  </div>
                </div>
              </div>
              <div class="form-group row">
                <label class="col-lg-4 col-form-label">Class</label>
                <div class="col-lg-8">
                  <select class="form-control select2" name="class_id" id="class_id">
                    <option value="">--</option>
                    <?php echo $classModel->generateClassOptions(); ?>
                  </select>
                </div>
              </div>
              <div class="form-group row">
                <label class="col-lg-4 col-form-label">Mode of Acquisition</label>
                <div class="col-lg-8">
                  <select class="form-control select2" name="mode_of_acquisition_id" id="mode_of_acquisition_id">
                    <option value="">--</option>
                    <?php echo $modeOfAcquisitionModel->generateModeOfAcquisitionOptions(); ?>
                  </select>
                </div>
              </div>
              <div class="form-group row">
                <label class="col-lg-4 col-form-label">Name of Broker</label>
                <div class="col-lg-8">
                  <input type="text" class="form-control" id="broker" name="broker" maxlength="200" autocomplete="off">
                </div>
              </div>
              <div class="form-group row">
                <label class="col-lg-4 col-form-label">Engine Number</label>
                <div class="col-lg-8">
                  <input type="text" class="form-control" id="engine_number" name="engine_number" maxlength="100" autocomplete="off">
                </div>
              </div>
              <div class="form-group row">
                <label class="col-lg-4 col-form-label">Chassis Number</label>
                <div class="col-lg-8">
                  <input type="text" class="form-control" id="chassis_number" name="chassis_number" maxlength="100" autocomplete="off">
                </div>
              </div>
              <div class="form-group row">
                <label class="col-lg-4 col-form-label">Plate Number</label>
                <div class="col-lg-8">
                  <input type="text" class="form-control" id="plate_number" name="plate_number" maxlength="100" autocomplete="off">
                </div>
              </div>
              <div class="form-group row">
                <label class="col-lg-4 col-form-label">Registered Owner</label>
                <div class="col-lg-8">
                  <input type="text" class="form-control" id="registered_owner" name="registered_owner" maxlength="300" autocomplete="off">
                </div>
              </div>
              <div class="form-group row">
                <label class="col-lg-4 col-form-label">Color</label>
                <div class="col-lg-8">
                  <select class="form-control select2" name="color_id" id="color_id">
                    <option value="">--</option>
                    <?php echo $colorModel->generateColorOptions(); ?>
                  </select>
                </div>
              </div>
              <div class="form-group row">
                <label class="col-lg-4 col-form-label">Mode of Registration</label>
                <div class="col-lg-8">
                  <input type="text" class="form-control" id="mode_of_registration" name="mode_of_registration" maxlength="300" autocomplete="off">
                </div>
              </div>
              <div class="form-group row">
                <label class="col-lg-4 col-form-label">Warehouse <span class="text-danger">*</span></label>
                <div class="col-lg-8">
                  <select class="form-control select2" name="warehouse_id" id="warehouse_id">
                    <option value="">--</option>
                    <?php echo $warehouseModel->generateWarehouseOptions(); ?>
                  </select>
                </div>
              </div>
              <div class="form-group row">
                <label class="col-lg-4 col-form-label">Year Model</label>
                <div class="col-lg-8">
                  <input type="text" class="form-control" id="year_model" name="year_model" maxlength="10" autocomplete="off">
                </div>
              </div>
              <div class="form-group row">
                <label class="col-lg-4 col-form-label">Arrival Date</label>
                <div class="col-lg-8">
                  <div class="input-group date">
                    <input type="text" class="form-control regular-datepicker" id="arrival_date" name="arrival_date" autocomplete="off">
                    <span class="input-group-text">
                      <i class="feather icon-calendar"></i>
                    </span>
                  </div>
                </div>
              </div>
              <div class="form-group row">
                <label class="col-lg-4 col-form-label">Checklist Date</label>
                <div class="col-lg-8">
                  <div class="input-group date">
                    <input type="text" class="form-control regular-datepicker" id="checklist_date" name="checklist_date" autocomplete="off">
                    <span class="input-group-text">
                      <i class="feather icon-calendar"></i>
                    </span>
                  </div>
                </div>
              </div>
              <div class="form-group row">
                <label class="col-lg-4 col-form-label">With CR</label>
                <div class="col-lg-8">
                  <select class="form-control select2" name="with_cr" id="with_cr">
                    <option value="No">No</option>
                    <option value="Yes">Yes</option> 
                  </select>
                </div>
              </div>
              <div class="form-group row">
                <label class="col-lg-4 col-form-label">With Plate Number</label>
                <div class="col-lg-8">
                  <select class="form-control select2" name="with_plate" id="with_plate">
                  <option value="No">No</option>
                    <option value="Yes">Yes</option>
                  </select>
                </div>
              </div>
              <div class="form-group row">
                <label class="col-lg-4 col-form-label">Product Category <span class="text-danger">*</span></label>
                <div class="col-lg-8">
                  <select class="form-control select2" name="product_subcategory_id" id="product_subcategory_id">
                    <option value="">--</option>
                    <?php echo $productSubcategoryModel->generateGroupedProductSubcategoryOptions(); ?>
                  </select>
                </div>
              </div>
              <div class="form-group row">
                <label class="col-lg-4 col-form-label">Returned to Supplier</label>
                <div class="col-lg-8">
                  <input type="text" class="form-control" id="returned_to_supplier" name="returned_to_supplier" maxlength="500" autocomplete="off">
                </div>
              </div>
              <div class="form-group row">
                <label class="col-lg-4 col-form-label">Running Hours</label>
                <div class="col-lg-8">
                  <input type="number" class="form-control" id="running_hours" name="running_hours" min="0" step="0.01">
                </div>
              </div>
              <div class="form-group row">
                <label class="col-lg-4 col-form-label">Mileage</label>
                <div class="col-lg-8">
                  <input type="number" class="form-control" id="mileage" name="mileage" min="0" step="0.01">
                </div>
              </div>
              <div class="form-group row">
                <label class="col-lg-4 col-form-label">OR/CR Date</label>
                <div class="col-lg-8">
                  <div class="input-group date">
                    <input type="text" class="form-control regular-datepicker" id="orcr_date" name="orcr_date" autocomplete="off">
                    <span class="input-group-text">
                      <i class="feather icon-calendar"></i>
                    </span>
                  </div>
                </div>
              </div>
              <div class="form-group row">
                <label class="col-lg-4 col-form-label">OR/CR Expiry Date</label>
                <div class="col-lg-8">
                  <div class="input-group date">
                    <input type="text" class="form-control regular-datepicker" id="orcr_expiry_date" name="orcr_expiry_date" autocomplete="off">
                    <span class="input-group-text">
                      <i class="feather icon-calendar"></i>
                    </span>
                  </div>
                </div>
              </div>
              <div class="form-group row">
                <label class="col-lg-4 col-form-label">OR/CR Number</label>
                <div class="col-lg-8">
                  <input type="text" class="form-control" id="orcr_no" name="orcr_no" maxlength="200" autocomplete="off">
                </div>
              </div>
              <div class="form-group row">
                <label class="col-lg-4 col-form-label">Received From</label>
                <div class="col-lg-8">
                  <input type="text" class="form-control" id="received_from" name="received_from" maxlength="500" autocomplete="off">
                </div>
              </div>
              <div class="form-group row">
                <label class="col-lg-4 col-form-label">Received From Address</label>
                <div class="col-lg-8">
                  <input type="text" class="form-control" id="received_from_address" name="received_from_address" maxlength="1000" autocomplete="off">
                </div>
              </div>
              <div class="form-group row">
                <label class="col-lg-4 col-form-label">Received From ID Type</label>
                <div class="col-lg-8">
                  <select class="form-control select2" name="received_from_id_type" id="received_from_id_type">
                    <option value="">--</option>
                    <?php echo $idTypeModel->generateIDTypeOptions(); ?>
                  </select>
                </div>
              </div>
              <div class="form-group row">
                <label class="col-lg-4 col-form-label">Received From ID No.</label>
                <div class="col-lg-8">
                  <input type="text" class="form-control" id="received_from_id_number" name="received_from_id_number" maxlength="200" autocomplete="off">
                </div>
              </div>
              <div class="form-group row">
                <label class="col-lg-4 col-form-label">Unit Description</label>
                <div class="col-lg-8">
                  <textarea class="form-control" id="unit_description" name="unit_description" maxlength="1000" rows="3"></textarea>
                </div>
              </div>
              <div class="form-group row mb-0">
                <label class="col-lg-4 col-form-label">Remarks</label>
                <div class="col-lg-8">
                  <textarea class="form-control" id="remarks" name="remarks" maxlength="1000" rows="3"></textarea>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-md-5">
    <div class="card">
      <div class="card-header">
        <div class="row align-items-center">
          <div class="col-sm-12">
            <h5>Thumbnail</h5>
          </div>
        </div>
      </div>
      <div class="card-body">
        <div class="row">
          <div class="col-md-12">
            <div class="text-center mb-3">
              <img src="<?php echo DEFAULT_AVATAR_IMAGE; ?>" alt="User Image" id="product_thumbnail" class="img-fluid wid-100 hei-100">
            </div>
            <div class="text-center mb-0">
              <label class="btn btn-outline-secondary" for="product_image"><i class="ti ti-upload me-2"></i> Click to Upload</label> 
              <input type="file" id="product_image" name="product_image" class="d-none">
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="card">
      <div class="card-header">
        <div class="row align-items-center">
          <div class="col-sm-12">
            <h5>Other Images</h5>
          </div>
        </div>
      </div>
      <div class="card-body">
        <div class="row" id="product_other_images"></div>
        <div class="row">
          <div class="text-center mt-2">
            <label class="btn btn-outline-secondary" for="product_other_image"><i class="ti ti-upload me-2"></i> Click to Upload</label> 
            <input type="file" id="product_other_image" name="product_other_image" class="d-none">
            </div>
        </div>
      </div>
    </div>

    <div class="card">
      <div class="card-header">
        <div class="row align-items-center">
          <div class="col-sm-4">
            <h5>Landed Cost</h5>
          </div>
          <div class="col-md-8 text-sm-end mt-3 mt-sm-0">
            <?php
              if ($productWriteAccess['total'] > 0) {
                echo '<button type="submit" form="landed-cost-form" class="btn btn-success" id="submit-landed-cost-data">Save</button>';
              }
            ?>
          </div>
        </div>
      </div>
      <div class="card-body">
        <form id="landed-cost-form" method="post" action="#">
          <div class="form-group row">
            <label class="col-lg-6 col-form-label">Product Price</label>
            <div class="col-lg-6">
              <input type="number" class="form-control" id="product_price" name="product_price" min="0" step="0.01">
            </div>
          </div>
          <div class="form-group row">
            <label class="col-lg-6 col-form-label">Product Cost</label>
            <div class="col-lg-6">
              <input type="number" class="form-control" id="product_cost" name="product_cost" min="0" step="0.01">
            </div>
          </div>
          <div class="form-group row">
            <label class="col-lg-6 col-form-label">Unit Cost (Currency)</label>
            <div class="col-lg-6">
              <input type="number" class="form-control" id="unit_cost" name="unit_cost" min="0" step="0.01">
            </div>
          </div>
          <div class="form-group row">
            <label class="col-lg-6 col-form-label">FX Rate</label>
            <div class="col-lg-6">
              <input type="number" class="form-control" id="fx_rate" name="fx_rate" min="0" step="0.01">
            </div>
          </div>
          <div class="form-group row">
            <label class="col-lg-6 col-form-label">Package Deal</label>
            <div class="col-lg-6">
              <input type="number" class="form-control" id="package_deal" name="package_deal" min="0" step="0.01">
            </div>
          </div>
          <div class="form-group row">
            <label class="col-lg-6 col-form-label">Tax Duties</label>
            <div class="col-lg-6">
              <input type="number" class="form-control" id="taxes_duties" name="taxes_duties" min="0" step="0.01">
            </div>
          </div>
          <div class="form-group row">
            <label class="col-lg-6 col-form-label">Freight</label>
            <div class="col-lg-6">
              <input type="number" class="form-control" id="freight" name="freight" min="0" step="0.01">
            </div>
          </div>
          <div class="form-group row">
            <label class="col-lg-6 col-form-label">LTO Registration</label>
            <div class="col-lg-6">
              <input type="number" class="form-control" id="lto_registration" name="lto_registration" min="0" step="0.01">
            </div>
          </div>
          <div class="form-group row">
            <label class="col-lg-6 col-form-label">Royalties</label>
            <div class="col-lg-6">
              <input type="number" class="form-control" id="royalties" name="royalties" min="0" step="0.01">
            </div>
          </div>
          <div class="form-group row">
            <label class="col-lg-6 col-form-label">Conversion</label>
            <div class="col-lg-6">
              <input type="number" class="form-control" id="conversion" name="conversion" min="0" step="0.01">
            </div>
          </div>
          <div class="form-group row">
            <label class="col-lg-6 col-form-label">Arrastre</label>
            <div class="col-lg-6">
              <input type="number" class="form-control" id="arrastre" name="arrastre" min="0" step="0.01">
            </div>
          </div>
          <div class="form-group row">
            <label class="col-lg-6 col-form-label">Wharrfage</label>
            <div class="col-lg-6">
              <input type="number" class="form-control" id="wharrfage" name="wharrfage" min="0" step="0.01">
            </div>
          </div>
          <div class="form-group row">
            <label class="col-lg-6 col-form-label">Insurance</label>
            <div class="col-lg-6">
              <input type="number" class="form-control" id="insurance" name="insurance" min="0" step="0.01">
            </div>
          </div>
          <div class="form-group row">
            <label class="col-lg-6 col-form-label">Aircon</label>
            <div class="col-lg-6">
              <input type="number" class="form-control" id="aircon" name="aircon" min="0" step="0.01">
            </div>
          </div>
          <div class="form-group row">
            <label class="col-lg-6 col-form-label">Import Permit</label>
            <div class="col-lg-6">
              <input type="number" class="form-control" id="import_permit" name="import_permit" min="0" step="0.01">
            </div>
          </div>
          <div class="form-group row">
            <label class="col-lg-6 col-form-label">Others</label>
            <div class="col-lg-6">
              <input type="number" class="form-control" id="others" name="others" min="0" step="0.01">
            </div>
          </div>
          <div class="form-group row">
            <label class="col-lg-6 col-form-label">Sub-Total</label>
            <div class="col-lg-6">
              <input type="number" class="form-control" id="sub_total" name="sub_total" min="0" step="0.01" readonly>
            </div>
          </div>
          <div class="form-group row mb-0">
            <label class="col-lg-6 col-form-label">Total Landed Cost</label>
            <div class="col-lg-6">
              <input type="number" class="form-control" id="total_landed_cost" name="total_landed_cost" min="0" step="0.01" readonly>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<?php
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