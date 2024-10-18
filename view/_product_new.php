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
            <h5 class="col-lg-12">Basic Information</h5>
            <hr/>
          </div>
          <div class="form-group row">
            <label class="col-lg-3 col-form-label">Brand</label>
            <div class="col-lg-3">
              <select class="form-control select2" name="brand_id" id="brand_id">
                <option value="">--</option>
                <?php echo $brandModel->generateBrandOptions(); ?>
              </select>
            </div>
            <label class="col-lg-3 col-form-label">Product Category <span class="text-danger">*</span></label>
            <div class="col-lg-3">
              <select class="form-control select2" name="product_subcategory_id" id="product_subcategory_id">
                <option value="">--</option>
                <?php echo $productSubcategoryModel->generateGroupedProductSubcategoryOptions(); ?>
              </select>
            </div>
          </div>
          <div class="form-group row">            
            <label class="col-lg-3 col-form-label">Company <span class="text-danger">*</span></label>
            <div class="col-lg-3">
              <select class="form-control select2" name="company_id" id="company_id">
                <option value="">--</option>
                <?php echo $companyModel->generateCompanyOptions(); ?>
              </select>
            </div>
            <label class="col-lg-3 col-form-label">Quantity <span class="text-danger">*</span></label>
            <div class="col-lg-3">
              <input type="number" class="form-control" id="quantity" name="quantity" value="0" min="0" step="1">
            </div>
          </div>
          <div class="form-group row">
                <label class="col-lg-3 col-form-label">Pre-Order? <span class="text-danger">*</span></label>
                <div class="col-lg-3">
                  <select class="form-control select2" name="preorder" id="preorder">
                    <option value="">--</option>
                    <option value="Yes">Yes</option>
                    <option value="No">No</option>
                  </select>
                </div>
              </div>
          <div class="form-group row">
            <label class="col-lg-3 col-form-label">Remarks</label>
            <div class="col-lg-9">
              <textarea class="form-control" id="remarks" name="remarks" maxlength="1000" rows="3"></textarea>
            </div>
          </div>


          <div class="form-group row mt-4">
            <h5 class="col-lg-12">Supplier & Acquisition Information</h5>
            <hr/>
          </div>
          <div class="form-group row">      
            <label class="col-lg-3 col-form-label">Supplier</label>
            <div class="col-lg-3">
              <select class="form-control select2" name="supplier_id" id="supplier_id">
                <option value="">--</option>
                <?php echo $supplierModel->generateSupplierOptions(); ?>
              </select>
            </div>     
            <label class="col-lg-3 col-form-label">Ref No.</label>
            <div class="col-lg-3">
              <input type="text" class="form-control" id="ref_no" name="ref_no" maxlength="200" autocomplete="off">
            </div>
          </div>
          <div class="form-group row">
            <label class="col-lg-3 col-form-label">Name of Broker</label>
            <div class="col-lg-3">
              <input type="text" class="form-control" id="broker" name="broker" maxlength="200" autocomplete="off">
            </div>
            <label class="col-lg-3 col-form-label">Mode of Acquisition</label>
            <div class="col-lg-3">
              <select class="form-control select2" name="mode_of_acquisition_id" id="mode_of_acquisition_id">
                <option value="">--</option>
                <?php echo $modeOfAcquisitionModel->generateModeOfAcquisitionOptions(); ?>
              </select>
            </div>
          </div>
          <div class="form-group row">
            <label class="col-lg-3 col-form-label">Received From</label>
            <div class="col-lg-3">
              <input type="text" class="form-control" id="received_from" name="received_from" maxlength="500" autocomplete="off">
            </div>
            <label class="col-lg-3 col-form-label">Received From Address</label>
            <div class="col-lg-3">
              <input type="text" class="form-control" id="received_from_address" name="received_from_address" maxlength="1000" autocomplete="off">
            </div>
          </div>
          <div class="form-group row">
            <label class="col-lg-3 col-form-label">Received From ID Type</label>
            <div class="col-lg-3">
              <select class="form-control select2" name="received_from_id_type" id="received_from_id_type">
                <option value="">--</option>
                <?php echo $idTypeModel->generateIDTypeOptions(); ?>
              </select>
            </div>
            <label class="col-lg-3 col-form-label">Received From ID No.</label>
            <div class="col-lg-3">
              <input type="text" class="form-control" id="received_from_id_number" name="received_from_id_number" maxlength="200" autocomplete="off">
            </div>
          </div>


          <div class="form-group row mt-4">
            <h5 class="col-lg-12">Vehicle Information</h5>
            <hr/>
          </div>
          <div class="form-group row">
            <label class="col-lg-3 col-form-label">Model</label>
            <div class="col-lg-3">
              <select class="form-control select2" name="model_id" id="model_id">
                <option value="">--</option>
                <?php echo $modelModel->generateModelOptions(); ?>
              </select>
            </div>
            <label class="col-lg-3 col-form-label">Make</label>
            <div class="col-lg-3">
              <select class="form-control select2" name="make_id" id="make_id">
                <option value="">--</option>
                <?php echo $makeModel->generateMakeOptions(); ?>
              </select>
            </div>
          </div>
          <div class="form-group row">
            <label class="col-lg-3 col-form-label">Body Type</label>
            <div class="col-lg-3">
              <select class="form-control select2" name="body_type_id" id="body_type_id">
                <option value="">--</option>
                <?php echo $bodyTypeModel->generateBodyTypeOptions(); ?>
              </select>
            </div>
            <label class="col-lg-3 col-form-label">Length</label>
            <div class="col-lg-3">
              <div class="input-group">
                <input type="number" class="form-control" id="length" name="length" min="0" step="0.01">
                <select class="form-control" name="length_unit" id="length_unit">
                  <?php echo $unitModel->generateUnitByShortNameOptions(1); ?>
                </select>
              </div>
            </div>
          </div>
          <div class="form-group row">
            <label class="col-lg-3 col-form-label">Class</label>
            <div class="col-lg-3">
              <select class="form-control select2" name="class_id" id="class_id">
                <option value="">--</option>
                <?php echo $classModel->generateClassOptions(); ?>
              </select>
            </div>
            <label class="col-lg-3 col-form-label">Cabin</label>
            <div class="col-lg-3">
              <select class="form-control select2" name="cabin_id" id="cabin_id">
                <option value="">--</option>
                <?php echo $cabinModel->generateCabinOptions(); ?>
              </select>
            </div>
          </div>
          <div class="form-group row">
            <label class="col-lg-3 col-form-label">Year Model</label>
            <div class="col-lg-3">
              <input type="text" class="form-control" id="year_model" name="year_model" maxlength="10" autocomplete="off">
            </div>
          </div>


          <div class="form-group row mt-4">
            <h5 class="col-lg-12">Identification Numbers</h5>
            <hr/>
          </div>
          <div class="form-group row">
            <label class="col-lg-3 col-form-label">Engine Number</label>
            <div class="col-lg-3">
              <input type="text" class="form-control" id="engine_number" name="engine_number" maxlength="100" autocomplete="off">
            </div>
            <label class="col-lg-3 col-form-label">Chassis Number</label>
            <div class="col-lg-3">
              <input type="text" class="form-control" id="chassis_number" name="chassis_number" maxlength="100" autocomplete="off">
            </div>
          </div>
          <div class="form-group row">
            <label class="col-lg-3 col-form-label">Plate Number</label>
            <div class="col-lg-3">
              <input type="text" class="form-control" id="plate_number" name="plate_number" maxlength="100" autocomplete="off">
            </div>
            <label class="col-lg-3 col-form-label">OR/CR Number</label>
            <div class="col-lg-3">
              <input type="text" class="form-control" id="orcr_no" name="orcr_no" maxlength="200" autocomplete="off">
            </div>
          </div>

          
          <div class="form-group row mt-4">
            <h5 class="col-lg-12">Ownership & Registration Information</h5>
            <hr/>
          </div>
          <div class="form-group row">
            <label class="col-lg-3 col-form-label">Registered Owner</label>
            <div class="col-lg-3">
              <input type="text" class="form-control" id="registered_owner" name="registered_owner" maxlength="300" autocomplete="off">
            </div>
            <label class="col-lg-3 col-form-label">Color</label>
            <div class="col-lg-3">
              <select class="form-control select2" name="color_id" id="color_id">
                <option value="">--</option>
                <?php echo $colorModel->generateColorOptions(); ?>
              </select>
            </div>
          </div>
          <div class="form-group row">
            <label class="col-lg-3 col-form-label">Mode of Registration</label>
            <div class="col-lg-3">
              <input type="text" class="form-control" id="mode_of_registration" name="mode_of_registration" maxlength="300" autocomplete="off">
            </div> <label class="col-lg-3 col-form-label">OR/CR Date</label>
            <div class="col-lg-3">
              <div class="input-group date">
                <input type="text" class="form-control regular-datepicker" id="orcr_date" name="orcr_date" autocomplete="off">
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
                <input type="text" class="form-control regular-datepicker" id="orcr_expiry_date" name="orcr_expiry_date" autocomplete="off">
                <span class="input-group-text">
                  <i class="feather icon-calendar"></i>
                </span>
              </div>
            </div>
            <label class="col-lg-3 col-form-label">With CR</label>
            <div class="col-lg-3">
              <select class="form-control select2" name="with_cr" id="with_cr">
                <option value="No">No</option>
                <option value="Yes">Yes</option> 
              </select>
            </div>
          </div>
          <div class="form-group row">
            <label class="col-lg-3 col-form-label">With Plate Number</label>
            <div class="col-lg-3">
              <select class="form-control select2" name="with_plate" id="with_plate">
              <option value="No">No</option>
                <option value="Yes">Yes</option>
              </select>
            </div>
            <label class="col-lg-3 col-form-label">Returned to Supplier</label>
            <div class="col-lg-3">
              <input type="text" class="form-control" id="returned_to_supplier" name="returned_to_supplier" maxlength="500" autocomplete="off">
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
            <label class="col-lg-3 col-form-label">Arrival Date</label>
            <div class="col-lg-3">
              <div class="input-group date">
                <input type="text" class="form-control regular-datepicker" id="arrival_date" name="arrival_date" autocomplete="off">
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
                <input type="text" class="form-control regular-datepicker" id="checklist_date" name="checklist_date" autocomplete="off">
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
              <input type="number" class="form-control" id="running_hours" name="running_hours" min="0" step="0.01">
            </div>
            <label class="col-lg-3 col-form-label">Mileage</label>
            <div class="col-lg-3">
              <input type="number" class="form-control" id="mileage" name="mileage" min="0" step="0.01">
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>