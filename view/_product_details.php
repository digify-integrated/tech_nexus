<?php
$updateProductButton = '';
$updateProductImageButton = '';
$deleteProductButton = '';

$productDetails = $productModel->getProduct($productID);
$productStatus = $productDetails['product_status'];

$updateLandedCost = $userModel->checkSystemActionAccessRights($user_id, 171);
$updateProductDisabled = $userModel->checkSystemActionAccessRights($user_id, 175);
$tagProductAsReturned = $userModel->checkSystemActionAccessRights($user_id, 176);
$tagProductAsROPA = $userModel->checkSystemActionAccessRights($user_id, 177);
$tagProductAsSold = $userModel->checkSystemActionAccessRights($user_id, 178);
$tagProductAsRepossessed = $userModel->checkSystemActionAccessRights($user_id, 179);

$checkSalesProposalProduct = $productModel->checkSalesProposalProduct($productID);
$disabledProductForm = 'disabled';
$disabledLandedCostForm = 'readonly';
$disabledLandedCostForm2 = 'readonly';

if ($updateLandedCost['total'] > 0 && $productStatus == 'Draft') {
  $disabledLandedCostForm = '';
  $disabledLandedCostForm2 = '';
} elseif ($updateProductDisabled['total'] > 0 && $productStatus != 'Draft' && $productStatus != 'Sold') {
  $disabledLandedCostForm = 'readonly';
  $disabledLandedCostForm2 = '';
} else {
  $disabledLandedCostForm = 'readonly';
  $disabledLandedCostForm2 = 'readonly';
}

if(($productWriteAccess['total'] > 0 && $productStatus == 'Draft') || $updateProductDisabled['total'] > 0 && $productStatus != 'Draft' &&  $productStatus != 'Sold'){
  $disabledProductForm = '';
}

if($productWriteAccess['total'] > 0 && $checkSalesProposalProduct['total'] == 0 && $productStatus == 'Draft'){
    $updateProductButton = '<div class="col-4">
                                <div class="d-grid">
                                    <button class="btn btn-info" type="button" data-bs-toggle="offcanvas" data-bs-target="#update-product-offcanvas" aria-controls="update-product-offcanvas" id="update-product">Update Product</button>
                                </div>
                            </div>';
}

if($updateProductImage['total'] > 0 && $checkSalesProposalProduct['total'] == 0 && $productStatus == 'Draft'){
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

if($addProductExpense['total'] > 0){
  $addProductExpenseButton = '<button class="btn btn-warning" type="button" data-bs-toggle="offcanvas" data-bs-target="#product-expense-offcanvas" aria-controls="product-expense-offcanvas" id="product-expense">Add Expense</button>';
}
?>

<div class="row">
  <div class="col-md-12">
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

                        if ($tagForSale['total'] > 0 && $productStatus == 'Draft') {
                            $dropdown .= '<li><button class="dropdown-item" type="button" id="tag-product-for-sale">Tag For Sale</button></li>';
                        }

                        if ($tagProductAsSold['total'] > 0 && ($productStatus == 'For Sale' || $productStatus == 'Consigned')) {
                            $dropdown .= '<li><button class="dropdown-item" type="button" id="tag-product-as-sold">Tag As Sold</button></li>';
                        }

                        if ($tagProductAsRepossessed['total'] > 0 && $productStatus == 'Sold') {
                            $dropdown .= '<li><button class="dropdown-item" type="button" id="tag-product-as-repossessed">Tag As Repossessed</button></li>';
                        }

                        if ($tagProductAsReturned['total'] > 0 && ($productStatus == 'Rented' || $productStatus == 'Consigned')) {
                            $dropdown .= '<li><button class="dropdown-item" type="button" id="tag-product-as-returned">Tag As Returned</button></li>';
                        }

                        if ($tagProductAsReturned['total'] > 0 && $productStatus == 'Repossessed') {
                            $dropdown .= '<li><button class="dropdown-item" type="button" id="tag-product-as-ropa">Tag As ROPA</button></li>';
                        }

                        if($printRecevingReport['total'] > 0){
                          $dropdown .= '<li><a href="print-receiving-report.php?id='. $productID .'" class="dropdown-item" type="button" target="_blank">Print Receiving Report</a></li>';
                        }

                       
                        $dropdown .= '<li><a href="print-incoming-checklist.php?id='. $productID .'" class="dropdown-item" type="button" target="_blank">Print Incoming Checklist</a></li>';
                        $dropdown .= '<li><a href="print-incoming-checklist-fuso.php?id='. $productID .'" class="dropdown-item" type="button" target="_blank">Print Incoming Checklist (Fuso)</a></li>';
                        $dropdown .= '<li><a href="javascript:void(0);" class="dropdown-item" data-bs-toggle="offcanvas" data-bs-target="#product-qr-code-offcanvas" aria-controls="product-qr-code-offcanvas" id="generate-qr-code">Print QR Code</a></li>';

                        if ($productDeleteAccess['total'] > 0) {
                            $dropdown .= '<li><button class="dropdown-item" type="button" id="delete-product-details">Delete Product</button></li>';
                        }
                                  
                        $dropdown .= '</ul>
                                      </div>';
                              
                        echo $dropdown;

                        if(($productWriteAccess['total'] > 0 && $productStatus == 'Draft') || $updateProductDisabled['total'] > 0 && $productStatus != 'Draft' &&  $productStatus != 'Sold'){
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
          <div class="col-md-6">
            <div class="form-group row">
                <h5 class="col-lg-12">Product Thumbnail</h5>
                <hr/>
            </div>
            <div class="text-center mb-3">
              <img src="<?php echo DEFAULT_AVATAR_IMAGE; ?>" alt="User Image" id="product_thumbnail" class="img-fluid wid-100 hei-100">
            </div>
            <div class="text-center mb-0">
              <label class="btn btn-outline-secondary" for="product_image"><i class="ti ti-upload me-2"></i> Click to Upload</label> 
              <input type="file" id="product_image" name="product_image" class="d-none">
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group row">
                <h5 class="col-lg-12">Other Product Image</h5>
                <hr/>
            </div>
            <div class="row" id="product_other_images"></div>
            <div class="row">
              <div class="text-center mt-2">
                <label class="btn btn-outline-secondary" for="product_other_image"><i class="ti ti-upload me-2"></i> Click to Upload</label> 
                <input type="file" id="product_other_image" name="product_other_image" class="d-none">
                </div>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-12">
            <div class="form-group row">
                <h5 class="col-lg-12">Product Details</h5>
                <hr/>
            </div>
            <div class="form-group row">
              <label class="col-lg-3 col-form-label">Stock Number</label>
              <div class="col-lg-3">
                <input type="text" class="form-control" id="stock_number" autocomplete="off" readonly>
              </div>
              <label class="col-lg-3 col-form-label">Product Status <span class="text-danger">*</span></label>
              <div class="col-lg-3">
                <input type="text" class="form-control" id="product_status" autocomplete="off" readonly>
              </div>
            </div>
            <div class="form-group row">
              <label class="col-lg-3 col-form-label">RR Number <span class="text-danger">*</span></label>
              <div class="col-lg-3">
                <input type="text" class="form-control" id="rr_no" autocomplete="off" readonly>
              </div>
              <label class="col-lg-3 col-form-label">RR Date <span class="text-danger">*</span></label>
              <div class="col-lg-3">
                <input type="text" class="form-control" id="rr_date" autocomplete="off" readonly>
              </div>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-12">
            <form id="product-details-form" method="post" action="#">
              <div class="form-group row">
                <h5 class="col-lg-12">Basic Information</h5>
                <hr/>
              </div>
              <div class="form-group row">
                <label class="col-lg-3 col-form-label">Brand</label>
                <div class="col-lg-3">
                  <select class="form-control select2" name="brand_id" id="brand_id" <?php echo $disabledProductForm; ?>>
                    <option value="">--</option>
                    <?php echo $brandModel->generateBrandOptions(); ?>
                  </select>
                </div>
                <label class="col-lg-3 col-form-label">Product Category <span class="text-danger">*</span></label>
                <div class="col-lg-3">
                  <select class="form-control select2" name="product_subcategory_id" id="product_subcategory_id" <?php echo $disabledProductForm; ?>>
                    <option value="">--</option>
                    <?php echo $productSubcategoryModel->generateGroupedProductSubcategoryOptions(); ?>
                  </select>
                </div>
              </div>
              <div class="form-group row">            
                <label class="col-lg-3 col-form-label">Company <span class="text-danger">*</span></label>
                <div class="col-lg-3">
                  <select class="form-control select2" name="company_id" id="company_id" <?php echo $disabledProductForm; ?>>
                    <option value="">--</option>
                    <?php echo $companyModel->generateCompanyOptions(); ?>
                  </select>
                </div>
                <label class="col-lg-3 col-form-label">Quantity <span class="text-danger">*</span></label>
                <div class="col-lg-3">
                  <input type="number" class="form-control" id="quantity" name="quantity" value="0" min="0" step="1" <?php echo $disabledProductForm; ?>>
                </div>
              </div>
              <div class="form-group row">
                <label class="col-lg-3 col-form-label">Pre-Order? <span class="text-danger">*</span></label>
                <div class="col-lg-3">
                  <select class="form-control select2" name="preorder" id="preorder" <?php echo $disabledProductForm; ?>>
                    <option value="">--</option>
                    <option value="Yes">Yes</option>
                    <option value="No">No</option>
                  </select>
                </div>
              </div>
              <div class="form-group row">
                <label class="col-lg-3 col-form-label">Remarks</label>
                <div class="col-lg-9">
                  <textarea class="form-control" id="remarks" name="remarks" maxlength="1000" rows="3" <?php echo $disabledProductForm; ?>></textarea>
                </div>
              </div>


              <div class="form-group row mt-4">
                <h5 class="col-lg-12">Supplier & Acquisition Information</h5>
                <hr/>
              </div>
              <div class="form-group row">      
                <label class="col-lg-3 col-form-label">Supplier</label>
                <div class="col-lg-3">
                  <select class="form-control select2" name="supplier_id" id="supplier_id" <?php echo $disabledProductForm; ?>>
                    <option value="">--</option>
                    <?php echo $supplierModel->generateSupplierOptions(); ?>
                  </select>
                </div>     
                <label class="col-lg-3 col-form-label">Ref No.</label>
                <div class="col-lg-3">
                  <input type="text" class="form-control" id="ref_no" name="ref_no" maxlength="200" autocomplete="off" <?php echo $disabledProductForm; ?>>
                </div>
              </div>
              <div class="form-group row">
                <label class="col-lg-3 col-form-label">Name of Broker</label>
                <div class="col-lg-3">
                  <input type="text" class="form-control" id="broker" name="broker" maxlength="200" autocomplete="off" <?php echo $disabledProductForm; ?>>
                </div>
                <label class="col-lg-3 col-form-label">Mode of Acquisition</label>
                <div class="col-lg-3">
                  <select class="form-control select2" name="mode_of_acquisition_id" id="mode_of_acquisition_id" <?php echo $disabledProductForm; ?>>
                    <option value="">--</option>
                    <?php echo $modeOfAcquisitionModel->generateModeOfAcquisitionOptions(); ?>
                  </select>
                </div>
              </div>
              <div class="form-group row">
                <label class="col-lg-3 col-form-label">Received From</label>
                <div class="col-lg-3">
                  <input type="text" class="form-control" id="received_from" name="received_from" maxlength="500" autocomplete="off" <?php echo $disabledProductForm; ?>>
                </div>
                <label class="col-lg-3 col-form-label">Received From Address</label>
                <div class="col-lg-3">
                  <input type="text" class="form-control" id="received_from_address" name="received_from_address" maxlength="1000" autocomplete="off" <?php echo $disabledProductForm; ?>>
                </div>
              </div>
              <div class="form-group row">
                <label class="col-lg-3 col-form-label">Received From ID Type</label>
                <div class="col-lg-3">
                  <select class="form-control select2" name="received_from_id_type" id="received_from_id_type" <?php echo $disabledProductForm; ?>>
                    <option value="">--</option>
                    <?php echo $idTypeModel->generateIDTypeOptions(); ?>
                  </select>
                </div>
                <label class="col-lg-3 col-form-label">Received From ID No.</label>
                <div class="col-lg-3">
                  <input type="text" class="form-control" id="received_from_id_number" name="received_from_id_number" maxlength="200" autocomplete="off" <?php echo $disabledProductForm; ?>>
                </div>
              </div>


              <div class="form-group row mt-4">
                <h5 class="col-lg-12">Vehicle Information</h5>
                <hr/>
              </div>
              <div class="form-group row">
                <label class="col-lg-3 col-form-label">Model</label>
                <div class="col-lg-3">
                  <select class="form-control select2" name="model_id" id="model_id" <?php echo $disabledProductForm; ?>>
                    <option value="">--</option>
                    <?php echo $modelModel->generateModelOptions(); ?>
                  </select>
                </div>
                <label class="col-lg-3 col-form-label">Make</label>
                <div class="col-lg-3">
                  <select class="form-control select2" name="make_id" id="make_id" <?php echo $disabledProductForm; ?>>
                    <option value="">--</option>
                    <?php echo $makeModel->generateMakeOptions(); ?>
                  </select>
                </div>
              </div>
              <div class="form-group row">
                <label class="col-lg-3 col-form-label">Body Type</label>
                <div class="col-lg-3">
                  <select class="form-control select2" name="body_type_id" id="body_type_id" <?php echo $disabledProductForm; ?>>
                    <option value="">--</option>
                    <?php echo $bodyTypeModel->generateBodyTypeOptions(); ?>
                  </select>
                </div>
                <label class="col-lg-3 col-form-label">Length</label>
                <div class="col-lg-3">
                  <div class="input-group">
                    <input type="number" class="form-control" id="length" name="length" min="0" step="0.01" <?php echo $disabledProductForm; ?>>
                    <select class="form-control" name="length_unit" id="length_unit" <?php echo $disabledProductForm; ?>>
                      <?php echo $unitModel->generateUnitByShortNameOptions(1); ?>
                    </select>
                  </div>
                </div>
              </div>
              <div class="form-group row">
                <label class="col-lg-3 col-form-label">Class</label>
                <div class="col-lg-3">
                  <select class="form-control select2" name="class_id" id="class_id" <?php echo $disabledProductForm; ?>>
                    <option value="">--</option>
                    <?php echo $classModel->generateClassOptions(); ?>
                  </select>
                </div>
                <label class="col-lg-3 col-form-label">Cabin</label>
                <div class="col-lg-3">
                  <select class="form-control select2" name="cabin_id" id="cabin_id" <?php echo $disabledProductForm; ?>>
                    <option value="">--</option>
                    <?php echo $cabinModel->generateCabinOptions(); ?>
                  </select>
                </div>
              </div>
              <div class="form-group row">
                <label class="col-lg-3 col-form-label">Year Model</label>
                <div class="col-lg-3">
                  <input type="text" class="form-control" id="year_model" name="year_model" maxlength="10" autocomplete="off" <?php echo $disabledProductForm; ?>>
                </div>
              </div>


              <div class="form-group row mt-4">
                <h5 class="col-lg-12">Identification Numbers</h5>
                <hr/>
              </div>
              <div class="form-group row">
                <label class="col-lg-3 col-form-label">Engine Number</label>
                <div class="col-lg-3">
                  <input type="text" class="form-control" id="engine_number" name="engine_number" maxlength="100" autocomplete="off" <?php echo $disabledProductForm; ?>>
                </div>
                <label class="col-lg-3 col-form-label">Chassis Number</label>
                <div class="col-lg-3">
                  <input type="text" class="form-control" id="chassis_number" name="chassis_number" maxlength="100" autocomplete="off" <?php echo $disabledProductForm; ?>>
                </div>
              </div>
              <div class="form-group row">
                <label class="col-lg-3 col-form-label">Plate Number</label>
                <div class="col-lg-3">
                  <input type="text" class="form-control" id="plate_number" name="plate_number" maxlength="100" autocomplete="off" <?php echo $disabledProductForm; ?>>
                </div>
                <label class="col-lg-3 col-form-label">OR/CR Number</label>
                <div class="col-lg-3">
                  <input type="text" class="form-control" id="orcr_no" name="orcr_no" maxlength="200" autocomplete="off" <?php echo $disabledProductForm; ?>>
                </div>
              </div>

              
              <div class="form-group row mt-4">
                <h5 class="col-lg-12">Ownership & Registration Information</h5>
                <hr/>
              </div>
              <div class="form-group row">
                <label class="col-lg-3 col-form-label">Registered Owner</label>
                <div class="col-lg-3">
                  <input type="text" class="form-control" id="registered_owner" name="registered_owner" maxlength="300" autocomplete="off" <?php echo $disabledProductForm; ?>>
                </div>
                <label class="col-lg-3 col-form-label">Color</label>
                <div class="col-lg-3">
                  <select class="form-control select2" name="color_id" id="color_id" <?php echo $disabledProductForm; ?>>
                    <option value="">--</option>
                    <?php echo $colorModel->generateColorOptions(); ?>
                  </select>
                </div>
              </div>
              <div class="form-group row">
                <label class="col-lg-3 col-form-label">Mode of Registration</label>
                <div class="col-lg-3">
                  <input type="text" class="form-control" id="mode_of_registration" name="mode_of_registration" maxlength="300" autocomplete="off" <?php echo $disabledProductForm; ?>>
                </div>
                <label class="col-lg-3 col-form-label">OR/CR Date</label>
                <div class="col-lg-3">
                  <div class="input-group date">
                    <input type="text" class="form-control regular-datepicker" id="orcr_date" name="orcr_date" autocomplete="off" <?php echo $disabledProductForm; ?>>
                    <span class="input-group-text">
                      <i class="feather icon-calendar"></i>
                    </span>
                  </div>
                </div>
              </div>
              <div class="form-group row">
                <label class="col-lg-3 col-form-label">OR/CR Expiry Date</label>
                <div class="col-lg-3">
                  <div class="input-group date">
                    <input type="text" class="form-control regular-datepicker" id="orcr_expiry_date" name="orcr_expiry_date" autocomplete="off" <?php echo $disabledProductForm; ?>>
                    <span class="input-group-text">
                      <i class="feather icon-calendar"></i>
                    </span>
                  </div>
                </div>
                <label class="col-lg-3 col-form-label">With CR</label>
                <div class="col-lg-3">
                  <select class="form-control select2" name="with_cr" id="with_cr" <?php echo $disabledProductForm; ?>>
                    <option value="No">No</option>
                    <option value="Yes">Yes</option> 
                  </select>
                </div>
              </div>
              <div class="form-group row">
                <label class="col-lg-3 col-form-label">With Plate Number</label>
                <div class="col-lg-3">
                  <select class="form-control select2" name="with_plate" id="with_plate" <?php echo $disabledProductForm; ?>>
                  <option value="No">No</option>
                    <option value="Yes">Yes</option>
                  </select>
                </div>
                <label class="col-lg-3 col-form-label">Returned to Supplier</label>
                <div class="col-lg-3">
                  <input type="text" class="form-control" id="returned_to_supplier" name="returned_to_supplier" maxlength="500" autocomplete="off" <?php echo $disabledProductForm; ?>>
                </div>
              </div>

              
              <div class="form-group row mt-4">
                <h5 class="col-lg-12">Warehouse & Delivery Information</h5>
                <hr/>
              </div>
              <div class="form-group row">
                <label class="col-lg-3 col-form-label">Warehouse <span class="text-danger">*</span></label>
                <div class="col-lg-3">
                  <select class="form-control select2" name="warehouse_id" id="warehouse_id" <?php echo $disabledProductForm; ?>>
                    <option value="">--</option>
                    <?php echo $warehouseModel->generateWarehouseOptions(); ?>
                  </select>
                </div>
                <label class="col-lg-3 col-form-label">Arrival Date</label>
                <div class="col-lg-3">
                  <div class="input-group date">
                    <input type="text" class="form-control regular-datepicker" id="arrival_date" name="arrival_date" autocomplete="off" <?php echo $disabledProductForm; ?>>
                    <span class="input-group-text">
                      <i class="feather icon-calendar"></i>
                    </span>
                  </div>
                </div>
              </div>
              <div class="form-group row">
                <label class="col-lg-3 col-form-label">Checklist Date</label>
                <div class="col-lg-3">
                  <div class="input-group date">
                    <input type="text" class="form-control regular-datepicker" id="checklist_date" name="checklist_date" autocomplete="off" <?php echo $disabledProductForm; ?>>
                    <span class="input-group-text">
                      <i class="feather icon-calendar"></i>
                    </span>
                  </div>
                </div>
              </div>


              <div class="form-group row mt-4">
                <h5 class="col-lg-12">Usage Information</h5>
                <hr/>
              </div>
              <div class="form-group row">
                <label class="col-lg-3 col-form-label">Running Hours</label>
                <div class="col-lg-3">
                  <input type="number" class="form-control" id="running_hours" name="running_hours" min="0" step="0.01" <?php echo $disabledProductForm; ?>>
                </div>
                <label class="col-lg-3 col-form-label">Mileage</label>
                <div class="col-lg-3">
                  <input type="number" class="form-control" id="mileage" name="mileage" min="0" step="0.01" <?php echo $disabledProductForm; ?>>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<?php
  $classHidden = 'd-none';
  $expenseHidden = 'd-none';

  if($viewProductCost['total'] > 0){
    $classHidden = '';
  }

  if($viewProductCost['total'] > 0 && $productStatus != 'Draft'){
    $expenseHidden = '';
  }
?>

<div class="row <?php echo $classHidden; ?>">
  <div class="col-md-12">
    <div class="card">
      <div class="card-header">
        <div class="row align-items-center">
          <div class="col-sm-4">
            <h5>Landed Cost</h5>
          </div>
          <div class="col-md-8 text-sm-end mt-3 mt-sm-0">
            <?php
            if(($updateLandedCost['total'] > 0 && $productStatus == 'Draft') || $updateProductDisabled['total'] > 0 && $productStatus != 'Draft' &&  $productStatus != 'Sold'){
                echo '<button type="submit" form="landed-cost-form" class="btn btn-success" id="submit-landed-cost-data">Save</button>';
              }
            ?>
          </div>
        </div>
      </div>
      <div class="card-body">
        <form id="landed-cost-form" method="post" action="#">
          <div class="form-group row">
            <label class="col-lg-3 col-form-label">Payment Ref No.</label>
            <div class="col-lg-3">
              <input type="text" class="form-control" id="payment_ref_no" name="payment_ref_no" maxlength="100" autocomplete="off" <?php echo $disabledLandedCostForm; ?>>
            </div>
            <label class="col-lg-3 col-form-label">Payment Ref Date</label>
            <div class="col-lg-3">
              <div class="input-group date">
                <input type="text" class="form-control regular-datepicker" id="payment_ref_date" name="payment_ref_date" autocomplete="off" <?php echo $disabledLandedCostForm; ?>>
                <span class="input-group-text">
                  <i class="feather icon-calendar"></i>
                </span>
              </div>
            </div>
          </div>
          <div class="form-group row">
            <label class="col-lg-3 col-form-label">Payment Ref Amount</label>
            <div class="col-lg-3">
              <input type="number" class="form-control" id="payment_ref_amount" name="payment_ref_amount" min="0" step="0.01" <?php echo $disabledLandedCostForm; ?>>
            </div>
            <label class="col-lg-3 col-form-label">Product Price (SRP)</label>
            <div class="col-lg-3">
              <input type="number" class="form-control" id="product_price" name="product_price" min="0" step="0.01" <?php echo $disabledLandedCostForm2; ?>>
            </div>
          </div>
          <div class="form-group row">
            <label class="col-lg-3 col-form-label">Unit Cost (Currency)</label>
            <div class="col-lg-3">
              <input type="number" class="form-control" id="unit_cost" name="unit_cost" min="0" step="0.01" <?php echo $disabledLandedCostForm; ?>>
            </div>
            <label class="col-lg-3 col-form-label">FX Rate</label>
            <div class="col-lg-3">
              <input type="number" class="form-control" id="fx_rate" name="fx_rate" min="0.0001" value="1" step="0.0001" <?php echo $disabledLandedCostForm; ?>>
            </div>
          </div>
          <div class="form-group row">
            <label class="col-lg-3 col-form-label">Converted Amount</label>
            <div class="col-lg-3">
              <input type="number" class="form-control" id="converted_amount" name="converted_amount" min="0" step="0.01" readonly>
            </div>
            <label class="col-lg-3 col-form-label">Package Deal</label>
            <div class="col-lg-3">
              <input type="number" class="form-control" id="package_deal" name="package_deal" min="0" step="0.01" <?php echo $disabledLandedCostForm; ?>>
            </div>
          </div>
          <div class="form-group row">
            <label class="col-lg-3 col-form-label">Tax Duties</label>
            <div class="col-lg-3">
              <input type="number" class="form-control" id="taxes_duties" name="taxes_duties" min="0" step="0.01" <?php echo $disabledLandedCostForm; ?>>
            </div>
            <label class="col-lg-3 col-form-label">Freight</label>
            <div class="col-lg-3">
              <input type="number" class="form-control" id="freight" name="freight" min="0" step="0.01" <?php echo $disabledLandedCostForm; ?>>
            </div>
          </div>
          <div class="form-group row">
            <label class="col-lg-3 col-form-label">LTO Registration</label>
            <div class="col-lg-3">
              <input type="number" class="form-control" id="lto_registration" name="lto_registration" min="0" step="0.01" <?php echo $disabledLandedCostForm; ?>>
            </div>
            <label class="col-lg-3 col-form-label">Royalties</label>
            <div class="col-lg-3">
              <input type="number" class="form-control" id="royalties" name="royalties" min="0" step="0.01" <?php echo $disabledLandedCostForm; ?>>
            </div>
          </div>
          <div class="form-group row">
            <label class="col-lg-3 col-form-label">Conversion</label>
            <div class="col-lg-3">
              <input type="number" class="form-control" id="conversion" name="conversion" min="0" step="0.01" <?php echo $disabledLandedCostForm; ?>>
            </div>
            <label class="col-lg-3 col-form-label">Arrastre</label>
            <div class="col-lg-3">
              <input type="number" class="form-control" id="arrastre" name="arrastre" min="0" step="0.01" <?php echo $disabledLandedCostForm; ?>>
            </div>
          </div>
          <div class="form-group row">
            <label class="col-lg-3 col-form-label">Wharrfage</label>
            <div class="col-lg-3">
              <input type="number" class="form-control" id="wharrfage" name="wharrfage" min="0" step="0.01" <?php echo $disabledLandedCostForm; ?>>
            </div>
            <label class="col-lg-3 col-form-label">Insurance</label>
            <div class="col-lg-3">
              <input type="number" class="form-control" id="insurance" name="insurance" min="0" step="0.01" <?php echo $disabledLandedCostForm; ?>>
            </div>
          </div>
          <div class="form-group row">
            <label class="col-lg-3 col-form-label">Aircon</label>
            <div class="col-lg-3">
              <input type="number" class="form-control" id="aircon" name="aircon" min="0" step="0.01" <?php echo $disabledLandedCostForm; ?>>
            </div>
            <label class="col-lg-3 col-form-label">Import Permit</label>
            <div class="col-lg-3">
              <input type="number" class="form-control" id="import_permit" name="import_permit" min="0" step="0.01" <?php echo $disabledLandedCostForm; ?>>
            </div>
          </div>
          <div class="form-group row mb-0">
            <label class="col-lg-3 col-form-label">Others</label>
            <div class="col-lg-3">
              <input type="number" class="form-control" id="others" name="others" min="0" step="0.01" <?php echo $disabledLandedCostForm; ?>>
            </div>
            <label class="col-lg-3 col-form-label">Total Landed Cost</label>
            <div class="col-lg-3">
              <input type="number" class="form-control" id="total_landed_cost" name="total_landed_cost" min="0" step="0.01" readonly>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<div class="card table-card <?php echo $expenseHidden; ?>">
  <div class="card-header">
    <div class="row align-items-center">
      <div class="col-sm-6">
        <h5>Expense</h5>
      </div>
      <div class="col-md-6 text-sm-end mt-3 mt-sm-0">
          <button type="button" class="btn btn-warning" data-bs-toggle="offcanvas" data-bs-target="#filter-canvas">
            Filter
          </button>
          <?php echo $addProductExpenseButton; ?>
      </div>
    </div>
  </div>
  <div class="card-body">
    <div class="dt-responsive table-responsive">
      <table id="product-expense-table" class="table table-hover nowrap w-100 dataTable">
        <thead>
          <tr>
            <th>Date</th>
            <th class="all">Reference Type</th>
            <th class="all">Reference Number</th>
            <th class="all">Particulars</th>
            <th class="all">Type</th>
            <th class="all">Amount</th>
            <th class="all">Actions</th>
          </tr>
        </thead>
        <tbody></tbody>
        <tfoot>
          <tr>
              <td class="text-end" colspan="5"><b>TOTAL</b></td>
              <td></td> 
              <td></td>
          </tr>
      </tfoot>
        </table>
    </div>
  </div>
</div>

<div class="card table-card">
  <div class="card-header">
    <div class="row align-items-center">
      <div class="col-sm-6">
        <h5>Product Document</h5>
      </div>
      <div class="col-md-6 text-sm-end mt-3 mt-sm-0">
        <button class="btn btn-warning" type="button" data-bs-toggle="offcanvas" data-bs-target="#product-document-offcanvas" aria-controls="product-document-offcanvas" id="product-document">Add Document</button>
      </div>
    </div>
  </div>
  <div class="card-body">
    <div class="dt-responsive table-responsive">
      <table id="product-document-table" class="table table-hover nowrap w-100 dataTable">
        <thead>
          <tr>
            <th class="all">Document Type</th>
            <th class="all">Actions</th>
          </tr>
        </thead>
        <tbody></tbody>
        </table>
    </div>
  </div>
</div>

<div>
  <div class="offcanvas offcanvas-end" tabindex="-1" id="product-document-offcanvas" aria-labelledby="product-document-offcanvas-label">
    <div class="offcanvas-header">
      <h2 id="product-document-offcanvas-label" style="margin-bottom:-0.5rem">Add Expense</h2>
      <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
      <div class="row">
        <div class="col-lg-12">
          <form id="product-document-form" method="post" action="#">
            <div class="form-group row">
              <div class="col-lg-12 mt-3 mt-lg-0">
                <label class="form-label">Document Type <span class="text-danger">*</span></label>
                <select class="form-control offcanvas-select2" name="document_type" id="document_type">
                  <option value="">--</option>
                  <option value="Certificate of Registration (CR)">Certificate of Registration (CR)</option>
                  <option value="Incoming Checklist">Incoming Checklist</option>
                  <option value="Official Receipt (OR)">Official Receipt (OR)</option>
                  <option value="LTO Registration">LTO Registration</option>
                  <option value="Insurance Certificate">Insurance Certificate</option>
                  <option value="Emission Test Certificate">Emission Test Certificate</option>
                  <option value="Certificate of Roadworthiness">Certificate of Roadworthiness</option>
                  <option value="TAX Certificate">TAX Certificate</option>
                  <option value="Tare Weight Certificate">Tare Weight Certificate</option>
                  <option value="Chassis Number">Chassis Number</option>
                  <option value="Engine Number">Engine Number</option>
                </select>
              </div>
              <div class="col-lg-12 mt-3 mt-lg-0">
                <label class="form-label">Document <span class="text-danger">*</span></label>
                <input type="file" id="product_document" name="product_document" class="form-control">
              </div>
            </div>
          </form>
        </div>
      </div>
      <div class="row">
        <div class="col-lg-12">
          <button type="submit" class="btn btn-primary" id="submit-product-document" form="product-document-form">Submit</button>
          <button class="btn btn-light-danger" data-bs-dismiss="offcanvas"> Close </button>
        </div>
      </div>
    </div>
  </div>
</div>

<div>
  <div class="offcanvas offcanvas-end" tabindex="-1" id="product-expense-offcanvas" aria-labelledby="product-expense-offcanvas-label">
    <div class="offcanvas-header">
      <h2 id="product-expense-offcanvas-label" style="margin-bottom:-0.5rem">Add Expense</h2>
      <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
      <div class="row">
        <div class="col-lg-12">
          <form id="product-expense-form" method="post" action="#">
            <div class="form-group row">
              <div class="col-lg-6 mt-3 mt-lg-0">
                <label class="form-label">Reference Type <span class="text-danger">*</span></label>
                <select class="form-control offcanvas-select2" name="reference_type" id="reference_type">
                  <option value="">--</option>
                  <option value="Check Voucher">Check Voucher</option>
                  <option value="Cash Disbursement Voucher">Cash Disbursement Voucher</option>
                  <option value="Issuance Slip">Issuance Slip</option>
                  <option value="Contractor Report">Contractor Report</option>
                  <option value="Adjustment">Adjustment</option>
                  <option value="Stock Transfer Advice">Stock Transfer Advice</option>
                </select>
              </div>
              <div class="col-lg-6 mt-3 mt-lg-0">
                <label class="form-label">Reference Number <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="reference_number" name="reference_number" maxlength="200" autocomplete="off">
              </div>
            </div>
            <div class="form-group row">
              <div class="col-lg-6 mt-3 mt-lg-0">
                <label class="form-label">Amount <span class="text-danger">*</span></label>
                <input type="number" class="form-control" id="expense_amount" name="expense_amount" value="0" step="0.01">
              </div>
              <div class="col-lg-6 mt-3 mt-lg-0">
                <label class="form-label">Expense Type <span class="text-danger">*</span></label>
                <select class="form-control offcanvas-select2" name="expense_type" id="expense_type">
                  <option value="">--</option>
                  <option value="Assemble / Conversion">Assemble / Conversion</option>
                  <option value="Body Builder">Body Builder</option>
                  <option value="Commission">Commission</option>
                  <option value="Delivery">Delivery</option>
                  <option value="Insurance">Insurance</option>
                  <option value="Landed Cost">Landed Cost</option>
                  <option value="Latero">Latero</option>
                  <option value="Painting">Painting</option>
                  <option value="Parts & ACC">Parts & ACC</option>
                  <option value="Registration">Registration</option>
                  <option value="Registration C/O Customer">Registration C/O Customer</option>
                  <option value="Repairs & Maintenance">Repairs & Maintenance</option>
                  <option value="Supplies">Supplies</option>
                  <option value="Journal Voucher">Journal Voucher</option>
                </select>
              </div>
            </div>
            <div class="form-group row">
              <div class="col-lg-12 mt-3 mt-lg-0">
                <label class="form-label">Particulars <span class="text-danger">*</span></label>
                <textarea class="form-control" id="particulars" name="particulars" maxlength="500"></textarea>
              </div>
            </div>
          </form>
        </div>
      </div>
      <div class="row">
        <div class="col-lg-12">
          <button type="submit" class="btn btn-primary" id="submit-product-expense" form="product-expense-form">Submit</button>
          <button class="btn btn-light-danger" data-bs-dismiss="offcanvas"> Close </button>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="offcanvas offcanvas-end" tabindex="-1" id="filter-canvas">
        <div class="offcanvas-body p-0 sticky-xxl-top">
          <div id="ecom-filter" class="show collapse collapse-horizontal">
            <div class="ecom-filter">
              <div class="card">
                <div class="card-header d-flex align-items-center justify-content-between">
                  <h5>Filter</h5>
                  <a href="#" class="avtar avtar-s btn-link-danger btn-pc-default" data-bs-dismiss="offcanvas" data-bs-target="#filter-canvas">
                    <i class="ti ti-x f-20"></i>
                  </a>
                </div>
                <div class="scroll-block">
                  <div class="card-body">
                    <ul class="list-group list-group-flush">
                      <li class="list-group-item px-0 py-2">
                        <a class="btn border-0 px-0 text-start w-100" data-bs-toggle="collapse" href="#reference-type-filter-collapse"><div class="float-end"><i class="ti ti-chevron-down"></i></div>
                          Reference Type
                        </a>
                        <div class="collapse show" id="reference-type-filter-collapse">
                          <div class="py-3">
                            <select class="form-control" id="reference_type_filter">
                              <option value="">--</option>
                              <option value="Check Voucher">Check Voucher</option>
                              <option value="Cash Disbursement Voucher">Cash Disbursement Voucher</option>
                              <option value="Issuance Slip">Issuance Slip</option>
                              <option value="Contractor Report">Contractor Report</option>
                              <option value="Adjustment">Adjustment</option>
                              <option value="Stock Transfer Advice">Stock Transfer Advice</option>
                            </select>
                          </div>
                        </div>
                      </li>
                      <li class="list-group-item px-0 py-2">
                        <a class="btn border-0 px-0 text-start w-100" data-bs-toggle="collapse" href="#expense-type-filter-collapse"><div class="float-end"><i class="ti ti-chevron-down"></i></div>
                          Expense Type
                        </a>
                        <div class="collapse show" id="expense-type-filter-collapse">
                          <div class="py-3">
                            <select class="form-control" id="expense_type_filter">
                              <option value="">--</option>
                              <option value="Assemble / Conversion">Assemble / Conversion</option>
                              <option value="Body Builder">Body Builder</option>
                              <option value="Commission">Commission</option>
                              <option value="Delivery">Delivery</option>
                              <option value="Insurance">Insurance</option>
                              <option value="Landed Cost">Landed Cost</option>
                              <option value="Latero">Latero</option>
                              <option value="Painting">Painting</option>
                              <option value="Parts & ACC">Parts & ACC</option>
                              <option value="Registration">Registration</option>
                              <option value="Registration C/O Customer">Registration C/O Customer</option>
                              <option value="Repairs & Maintenance">Repairs & Maintenance</option>
                              <option value="Supplies">Supplies</option>
                            </select>
                          </div>
                        </div>
                      </li>
                      <li class="list-group-item px-0 py-2">
                        <button type="button" class="btn btn-light-success w-100" id="apply-filter">Apply</a>
                      </li>
                    </ul>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
</div>

<div class="offcanvas offcanvas-end" tabindex="-1" id="product-qr-code-offcanvas" aria-labelledby="product-qr-code-offcanvas-label">
                  <div class="offcanvas-header">
                    <h2 id="product-qr-code-offcanvas-label" style="margin-bottom:-0.5rem">QR Code</h2>
                    <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                  </div>
                  <div class="offcanvas-body">
                    <div class="alert alert-success alert-dismissible mb-4" role="alert">
                      This QR code serves as a secure and efficient means of identity verification and access control within our organization. Its primary purpose is to enhance the overall security and streamline various operational processes.
                      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    <div class="row mb-4 text-center">
                      <div class="col-lg-12" id="product-qr-code-container"></div>
                    </div>
                    <div class="row">
                      <div class="col-lg-12">
                        <button class="btn btn-light-success" id="print-qr"> Print </button>
                        <button class="btn btn-light-danger" data-bs-dismiss="offcanvas"> Close </button>
                      </div>
                    </div>
                  </div>
                </div>

<?php
if($viewProductLogNotes['total'] > 0){
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
}
  
?>