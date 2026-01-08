<?php
  $purchaseOrderDetails = $purchaseOrderModel->getPurchaseOrder($purchaseOrderID);
  $purchase_order_status = $purchaseOrderDetails['purchase_order_status'] ?? 'Draft';
  $purchase_order_type = $purchaseOrderDetails['purchase_order_type'] ?? '';
    
  $approvePurchaseOrder = $userModel->checkSystemActionAccessRights($user_id, 201);
  $releasePurchaseOrder = $userModel->checkSystemActionAccessRights($user_id, 202);
  $checkPurchaseOrder = $userModel->checkSystemActionAccessRights($user_id, 219);

  $disabled = '';
  if($purchase_order_status != 'Draft'){
    $disabled = 'disabled';
  }
?>

<div class="row">
  <div class="col-lg-12">
    <div class="card">
      <div class="card-header">
        <div class="row align-items-center">
          <div class="col-md-6">
            <h5>Purchase Order</h5>
          </div>
          <div class="col-sm-6 text-sm-end mt-3 mt-sm-0">
          <?php
              if($purchase_order_status == 'Draft'){
                echo '<button class="btn btn-info ms-2" type="button" id="for-approval">For Approval</button>';
              }

              if($purchase_order_status == 'Approved'){
                echo '<button class="btn btn-info ms-2" type="button" id="on-process">On-Process</button>';
              }

              if($purchase_order_status == 'On-Process'){
                echo '<button class="btn btn-success ms-2" type="button" id="complete">Complete</button>';
              }

              if($purchase_order_status == 'For Approval' && $approvePurchaseOrder['total'] > 0){
                echo '<button class="btn btn-success ms-2" type="button" data-bs-toggle="offcanvas" data-bs-target="#approve-purchase-order-offcanvas" aria-controls="approve-purchase-order-offcanvas" id="approved">Approve</button>';
              }

              if($purchase_order_status == 'Draft' || $purchase_order_status == 'For Approval' || $purchase_order_status == 'Approved' || $purchase_order_status == 'On-Process'){
                echo '<button class="btn btn-warning ms-2" type="button" data-bs-toggle="offcanvas" data-bs-target="#cancel-purchase-order-offcanvas" aria-controls="cancel-purchase-order-offcanvas" id="cancelled">Cancel</button>';
              }

              if($purchase_order_status == 'For Approval' || $purchase_order_status == 'Approved' || $purchase_order_status == 'On-Process'){
                echo '<button class="btn btn-dark ms-2" type="button" data-bs-toggle="offcanvas" data-bs-target="#draft-purchase-order-offcanvas" aria-controls="draft-purchase-order-offcanvas" id="draft">Set To Draft</button>';
              }

            if ($purchaseOrderCreateAccess['total'] > 0 && $purchase_order_status == 'Draft') {
              echo '<button type="submit" form="purchase-order-form" class="btn btn-success form-edit ms-2" id="submit-data">Save</button>
                        <button type="button" id="discard-create" class="btn btn-outline-danger form-edit">Discard</button>';
            }
          ?>
          </div>
        </div>
      </div>
      <div class="card-body">
        <form id="purchase-order-form" method="post" action="#">
          <div class="form-group row">
            <label class="col-lg-2 col-form-label">Reference No.</label>
            <div class="col-lg-4">
              <input type="text" class="form-control" id="reference_no" name="reference_no" maxlength="100" autocomplete="off" readonly>
            </div>
            <label class="col-lg-2 col-form-label">Supplier <span class="text-danger">*</span></label>
            <div class="col-lg-4">
              <select class="form-control select2" name="supplier_id" id="supplier_id" <?php echo $disabled; ?>>
                <option value="">--</option>
                <?php echo $supplierModel->generateSupplierOptions(); ?>
              </select>
            </div>
          </div>
          <div class="form-group row">
            <label class="col-lg-2 col-form-label">Purchase Order Type <span class="text-danger">*</span></label>
            <div class="col-lg-4">
              <select class="form-control select2" name="purchase_order_type" id="purchase_order_type" <?php echo $disabled; ?>>
                <option value="">--</option>
                <option value="Product">Product</option>
                <option value="Parts">Parts</option>
                <option value="Supplies">Supplies</option>
                <option value="Others">Others</option>
              </select>
            </div>
            <label class="col-lg-2 col-form-label">Company <span class="text-danger">*</span></label>
            <div class="col-lg-4">
              <select class="form-control select2" name="company_id" id="company_id" <?php echo $disabled; ?>>
                <option value="">--</option>
                <?php echo $companyModel->generateCompanyOptions(); ?>
              </select>
            </div>
          </div>
          <div class="form-group row">
            <label class="col-lg-2 col-form-label">Remarks</label>
            <div class="col-lg-10">
              <textarea class="form-control" id="remarks" name="remarks" maxlength="2000" <?php echo $disabled; ?>></textarea>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<?php
  $card = '';
    $table = '';
    $button = '';

    if($purchase_order_type == 'Product'){
      $table = '<table class="table mb-0 w-100" id="unit-order-item-table">
                  <thead>
                    <tr>
                      <th class="text-end"></th>
                      <th>Unit</th>
                      <th>Request</th>
                      <th class="text-center">Qty.</th>
                      <th class="text-center">Actual Qty.</th>
                      <th class="text-center">Cancelled Qty.</th>
                      <th class="text-center">Price</th>
                      <th class="text-center">Remarks</th>
                    </tr>
                  </thead>
                  <tbody></tbody>
                </table>';

      $button = $purchase_order_status == 'Draft' ?'<button class="btn btn-success" type="button" data-bs-toggle="offcanvas" data-bs-target="#add-item-unit-offcanvas" aria-controls="add-item-unit-offcanvas" id="add-item-unit">Add Item</button>' : '';
    }
    else if($purchase_order_type == 'Parts'){
      $table = '<table class="table mb-0 w-100" id="part-order-item-table">
                  <thead>
                    <tr>
                      <th class="text-end"></th>
                      <th>Part</th>
                      <th>Request</th>
                      <th class="text-center">Qty.</th>
                      <th class="text-center">Actual Qty.</th>
                      <th class="text-center">Cancelled Qty.</th>
                      <th class="text-center">Price</th>
                      <th class="text-center">Remarks</th>
                    </tr>
                  </thead>
                  <tbody></tbody>
                </table>';

      $button =  $purchase_order_status == 'Draft' ?'<button class="btn btn-success" type="button" data-bs-toggle="offcanvas" data-bs-target="#add-item-part-offcanvas" aria-controls="add-item-part-offcanvas" id="add-item-part">Add Item</button>' : '';
    }
    else if($purchase_order_type == 'Supplies'){
      $table = '<table class="table mb-0 w-100" id="supply-order-item-table">
                  <thead>
                    <tr>
                      <th class="text-end"></th>
                      <th>Supply</th>
                      <th>Request</th>
                      <th class="text-center">Qty.</th>
                      <th class="text-center">Actual Qty.</th>
                      <th class="text-center">Cancelled Qty.</th>
                      <th class="text-center">Price</th>
                      <th class="text-center">Remarks</th>
                    </tr>
                  </thead>
                  <tbody></tbody>
                </table>';

      $button =  $purchase_order_status == 'Draft' ?'<button class="btn btn-success" type="button" data-bs-toggle="offcanvas" data-bs-target="#add-item-supply-offcanvas" aria-controls="add-item-supply-offcanvas" id="add-item-supply">Add Item</button>' : '';
    }
    else{
      $table = '<table class="table mb-0 w-100" id="others-order-item-table">
                  <thead>
                    <tr>
                      <th class="text-end"></th>
                      <th>Order</th>
                      <th>Request</th>
                      <th class="text-center">Qty.</th>
                      <th class="text-center">Actual Qty.</th>
                      <th class="text-center">Cancelled Qty.</th>
                      <th class="text-center">Price</th>
                      <th class="text-center">Remarks</th>
                    </tr>
                  </thead>
                  <tbody></tbody>
                </table>';

      $button =  $purchase_order_status == 'Draft' ?'<button class="btn btn-success" type="button" data-bs-toggle="offcanvas" data-bs-target="#add-item-others-offcanvas" aria-controls="add-item-others-offcanvas" id="add-item-others">Add Item</button>' : '';
    }
?>

<div class="row">
  <div class="col-lg-12">
    <div class="card">
      <div class="card-header">
        <div class="row align-items-center">
          <div class="col-md-6">
            <h5>Purchase Order Item</h5>
          </div>
          <div class="col-sm-6 text-sm-end mt-3 mt-sm-0">
            <?php echo $button; ?>
          </div>
        </div>
      </div>
      <div class="card-body p-0">
        <div class="dt-responsive table-responsive">
          <?php echo $table; ?>
        </div>
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
                  '. $userModel->generateLogNotes('parts_transaction', $purchaseOrderID) .'
                </div>
              </div>
            </div>
          </div>
        </div>';
?>

<div>
  <div class="offcanvas offcanvas-end" tabindex="-1" id="add-item-unit-offcanvas" aria-labelledby="add-item-unit-offcanvas-label">
    <div class="offcanvas-header">
      <h2 id="add-item-unit-offcanvas-label" style="margin-bottom:-0.5rem">Purchase Order Item</h2>
      <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
      <div class="row">
        <div class="col-lg-12">
          <form id="add-item-unit-form" method="post" action="#">
            <input type="hidden" id="purchase_order_unit_id" name="purchase_order_unit_id">
            <div class="form-group row">
              <div class="col-lg-12 mt-3 mt-lg-0">
                <label class="form-label">Link Purchase Order <span class="text-danger">*</span></label>
                <select class="form-control offcanvas-select2" name="purchase_request_cart_id" id="purchase_request_cart_id">
                  <option value="">--</option>
                  <?php echo $purchaseRequestModel->generatePurchaseRequestCartOptions('Product'); ?>
                </select>
              </div>
            </div>
            <div class="form-group row">
              <div class="col-lg-6 mt-3 mt-lg-0">
                <label class="form-label">Product Category <span class="text-danger">*</span></label>
                <select class="form-control offcanvas-select2" name="product_subcategory_id" id="product_subcategory_id">
                  <option value="">--</option>
                  <?php echo $productSubcategoryModel->generateGroupedProductSubcategoryOptions(); ?>
                </select>
              </div>
              <div class="col-lg-6 mt-3 mt-lg-0">
                <label class="form-label">Brand <span class="text-danger">*</span></label>
                <select class="form-control offcanvas-select2" name="brand_id" id="brand_id">
                  <option value="">--</option>
                  <?php echo $brandModel->generateBrandOptions(); ?>
                </select>
              </div>
            </div>
            <div class="form-group row">
              <div class="col-lg-6 mt-3 mt-lg-0">
                <label class="form-label">Model <span class="text-danger">*</span></label>
                <select class="form-control offcanvas-select2" name="model_id" id="model_id">
                  <option value="">--</option>
                  <?php echo $modelModel->generateModelOptions(); ?>
                </select>
              </div>
              <div class="col-lg-6 mt-3 mt-lg-0">
                <label class="form-label">Length</label>
                <div class="input-group">
                  <input type="number" class="form-control" id="length" name="length" min="0" step="0.01">
                  <select class="form-control" name="length_unit" id="length_unit">
                    <?php echo $unitModel->generateUnitByShortNameOptions(1); ?>
                  </select>
                </div>
              </div>
            </div>
            <div class="form-group row">
              <div class="col-lg-6 mt-3 mt-lg-0">
                <label class="form-label">Body Type</label>
                <select class="form-control offcanvas-select2" name="body_type_id" id="body_type_id">
                  <option value="">--</option>
                  <?php echo $bodyTypeModel->generateBodyTypeOptions(); ?>
                </select>
              </div>
              <div class="col-lg-6 mt-3 mt-lg-0">
                <label class="form-label">Class</label>
                <select class="form-control offcanvas-select2" name="class_id" id="class_id">
                  <option value="">--</option>
                  <?php echo $classModel->generateClassOptions(); ?>
                </select>
              </div>
            </div>
            <div class="form-group row">
              <div class="col-lg-6 mt-3 mt-lg-0">
                <label class="form-label">Color</label>
                <select class="form-control offcanvas-select2" name="color_id" id="color_id">
                  <option value="">--</option>
                  <?php echo $colorModel->generateColorOptions(); ?>
                </select>
              </div>
              <div class="col-lg-6 mt-3 mt-lg-0">
                <label class="form-label">Make</label>
                <select class="form-control offcanvas-select2" name="make_id" id="make_id">
                  <option value="">--</option>
                  <?php echo $makeModel->generateMakeOptions(); ?>
                </select>
              </div>
            </div>
            <div class="form-group row">
              <div class="col-lg-6 mt-3 mt-lg-0">
                <label class="form-label">Year Model</label>
                <input type="text" class="form-control" id="year_model" name="year_model" maxlength="10" autocomplete="off">
              </div>
              <div class="col-lg-6 mt-3 mt-lg-0">
                <label class="form-label">Cabin</label>
                <select class="form-control offcanvas-select2" name="cabin_id" id="cabin_id">
                  <option value="">--</option>
                  <?php echo $cabinModel->generateCabinOptions(); ?>
                </select>
              </div>
            </div>
            <div class="form-group row">
              <div class="col-lg-6 mt-3 mt-lg-0">
                <label class="form-label">Quantity <span class="text-danger">*</span></label>
                <input type="number" class="form-control" id="quantity_unit" name="quantity_unit" value="0" min="0.01" step="0.01">
              </div>
              <div class="col-lg-6 mt-3 mt-lg-0">
                <label class="form-label">Unit <span class="text-danger">*</span></label>
                <select class="form-control offcanvas-select2" name="quantity_unit_id" id="quantity_unit_id">
                  <option value="">--</option>
                  <?php echo $unitModel->generateUnitOptions(); ?>
                </select>
              </div>
            </div>
            <div class="form-group row">
              <div class="col-lg-6 mt-3 mt-lg-0">
                <label class="form-label">Price <span class="text-danger">*</span></label>
                <input type="number" class="form-control" id="price_unit" name="price_unit" value="0" min="0.01" step="0.01">
              </div>
            </div>
            <div class="form-group row">
              <div class="col-lg-12 mt-3 mt-lg-0">
                <label class="form-label">Remarks</label>
                <textarea class="form-control" id="unit_remarks" name="unit_remarks" maxlength="2000"></textarea>
              </div>
            </div>
          </form>
        </div>
      </div>
      <div class="row">
        <div class="col-lg-12">
          <button type="submit" class="btn btn-primary" id="submit-add-item" form="add-item-unit-form">Submit</button>
          <button class="btn btn-light-danger" data-bs-dismiss="offcanvas"> Close </button>
        </div>
      </div>
    </div>
  </div>
</div>

<div>
  <div class="offcanvas offcanvas-end" tabindex="-1" id="add-item-part-offcanvas" aria-labelledby="add-item-part-offcanvas-label">
    <div class="offcanvas-header">
      <h2 id="add-item-part-offcanvas-label" style="margin-bottom:-0.5rem">Purchase Order Item</h2>
      <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
      <div class="row">
        <div class="col-lg-12">
          <form id="add-item-part-form" method="post" action="#">
            <input type="hidden" id="purchase_order_part_id" name="purchase_order_part_id">
            <div class="form-group row">
              <div class="col-lg-12 mt-3 mt-lg-0">
                <label class="form-label">Link Purchase Order <span class="text-danger">*</span></label>
                <select class="form-control offcanvas-select2" name="purchase_request_cart_part_id" id="purchase_request_cart_part_id">
                  <option value="">--</option>
                  <?php echo $purchaseRequestModel->generatePurchaseRequestCartOptions('Parts'); ?>
                </select>
              </div>
            </div>
            <div class="form-group row">
              <div class="col-lg-6 mt-3 mt-lg-0">
                <label class="form-label">Part <span class="text-danger">*</span></label>
                <select class="form-control offcanvas-select2" name="part_id" id="part_id">
                  <option value="">--</option>
                  <?php echo $partsModel->generateAllPartsOptions(); ?>
                </select>
              </div>
              <div class="col-lg-6 mt-3 mt-lg-0">
                <label class="form-label">Price <span class="text-danger">*</span></label>
                <input type="number" class="form-control" id="price_part" name="price_part" value="0" min="0.01" step="0.01">
              </div>
            </div>
            <div class="form-group row">
              <div class="col-lg-6 mt-3 mt-lg-0">
                <label class="form-label">Quantity <span class="text-danger">*</span></label>
                <input type="number" class="form-control" id="quantity_part" name="quantity_part" value="0" min="0.01" step="0.01">
              </div>
              <div class="col-lg-6 mt-3 mt-lg-0">
                <label class="form-label">Unit <span class="text-danger">*</span></label>
                <select class="form-control offcanvas-select2" name="quantity_part_id" id="quantity_part_id">
                  <option value="">--</option>
                  <?php echo $unitModel->generateUnitOptions(); ?>
                </select>
              </div>
            </div>
            <div class="form-group row">
              <div class="col-lg-12 mt-3 mt-lg-0">
                <label class="form-label">Remarks</label>
                <textarea class="form-control" id="part_remarks" name="part_remarks" maxlength="2000"></textarea>
              </div>
            </div>
          </form>
        </div>
      </div>
      <div class="row">
        <div class="col-lg-12">
          <button type="submit" class="btn btn-primary" id="submit-add-item-part" form="add-item-part-form">Submit</button>
          <button class="btn btn-light-danger" data-bs-dismiss="offcanvas"> Close </button>
        </div>
      </div>
    </div>
  </div>
</div>

<div>
  <div class="offcanvas offcanvas-end" tabindex="-1" id="add-item-supply-offcanvas" aria-labelledby="add-item-supply-offcanvas-label">
    <div class="offcanvas-header">
      <h2 id="add-item-supply-offcanvas-label" style="margin-bottom:-0.5rem">Purchase Order Item</h2>
      <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
      <div class="row">
        <div class="col-lg-12">
          <form id="add-item-supply-form" method="post" action="#">
            <input type="hidden" id="purchase_order_supply_id" name="purchase_order_supply_id">
            <div class="form-group row">
              <div class="col-lg-12 mt-3 mt-lg-0">
                <label class="form-label">Link Purchase Order <span class="text-danger">*</span></label>
                <select class="form-control offcanvas-select2" name="purchase_request_cart_supply_id" id="purchase_request_cart_supply_id">
                  <option value="">--</option>
                  <?php echo $purchaseRequestModel->generatePurchaseRequestCartOptions('Supplies'); ?>
                </select>
              </div>
            </div>
            <div class="form-group row">
              <div class="col-lg-6 mt-3 mt-lg-0">
                <label class="form-label">Part <span class="text-danger">*</span></label>
                <select class="form-control offcanvas-select2" name="supply_id" id="supply_id">
                  <option value="">--</option>
                  <?php echo $partsModel->generateAllPartsOptions(); ?>
                </select>
              </div>
              <div class="col-lg-6 mt-3 mt-lg-0">
                <label class="form-label">Price <span class="text-danger">*</span></label>
                <input type="number" class="form-control" id="price_supply" name="price_supply" value="0" min="0.01" step="0.01">
              </div>
            </div>
            <div class="form-group row">
              <div class="col-lg-6 mt-3 mt-lg-0">
                <label class="form-label">Quantity <span class="text-danger">*</span></label>
                <input type="number" class="form-control" id="quantity_supply" name="quantity_supply" value="0" min="0.01" step="0.01">
              </div>
              <div class="col-lg-6 mt-3 mt-lg-0">
                <label class="form-label">Unit <span class="text-danger">*</span></label>
                <select class="form-control offcanvas-select2" name="quantity_supply_id" id="quantity_supply_id">
                  <option value="">--</option>
                  <?php echo $unitModel->generateUnitOptions(); ?>
                </select>
              </div>
            </div>
            <div class="form-group row">
              <div class="col-lg-12 mt-3 mt-lg-0">
                <label class="form-label">Remarks</label>
                <textarea class="form-control" id="supply_remarks" name="supply_remarks" maxlength="2000"></textarea>
              </div>
            </div>
          </form>
        </div>
      </div>
      <div class="row">
        <div class="col-lg-12">
          <button type="submit" class="btn btn-primary" id="submit-add-item-supply" form="add-item-supply-form">Submit</button>
          <button class="btn btn-light-danger" data-bs-dismiss="offcanvas"> Close </button>
        </div>
      </div>
    </div>
  </div>
</div>

 <div>
    <div class="offcanvas offcanvas-end" tabindex="-1" id="cancel-purchase-order-offcanvas" aria-labelledby="cancel-purchase-order-offcanvas-label">
      <div class="offcanvas-header">
        <h2 id="cancel-purchase-order-offcanvas-label" style="margin-bottom:-0.5rem">Cancel Request</h2>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
      </div>
    <div class="offcanvas-body">
      <div class="row">
        <div class="col-lg-12">
          <form id="cancel-purchase-order-form" method="post" action="#">
            <div class="form-group row">
              <div class="col-lg-12 mt-3 mt-lg-0">
                <label class="form-label">Cancellation Reason <span class="text-danger">*</span></label>
                <textarea class="form-control" id="cancellation_reason" name="cancellation_reason" maxlength="500"></textarea>
              </div>
            </div>
          </form>
        </div>
      </div>
      <div class="row">
        <div class="col-lg-12">
          <button type="submit" class="btn btn-primary" id="submit-cancel-transaction" form="cancel-purchase-order-form">Submit</button>
          <button class="btn btn-light-danger" data-bs-dismiss="offcanvas"> Close </button>
        </div>
      </div>
    </div>
  </div>

 <div>
    <div class="offcanvas offcanvas-end" tabindex="-1" id="draft-purchase-order-offcanvas" aria-labelledby="draft-purchase-order-offcanvas-label">
      <div class="offcanvas-header">
        <h2 id="draft-purchase-order-offcanvas-label" style="margin-bottom:-0.5rem">Set To Draft</h2>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
      </div>
    <div class="offcanvas-body">
      <div class="row">
        <div class="col-lg-12">
          <form id="draft-purchase-order-form" method="post" action="#">
            <div class="form-group row">
              <div class="col-lg-12 mt-3 mt-lg-0">
                <label class="form-label">Set To Draft Reason <span class="text-danger">*</span></label>
                <textarea class="form-control" id="draft_reason" name="draft_reason" maxlength="500"></textarea>
              </div>
            </div>
          </form>
        </div>
      </div>
      <div class="row">
        <div class="col-lg-12">
          <button type="submit" class="btn btn-primary" id="submit-draft-transaction" form="draft-purchase-order-form">Submit</button>
          <button class="btn btn-light-danger" data-bs-dismiss="offcanvas"> Close </button>
        </div>
      </div>
    </div>
  </div>

 <div>
    <div class="offcanvas offcanvas-end" tabindex="-1" id="approve-purchase-order-offcanvas" aria-labelledby="approve-purchase-order-offcanvas-label">
      <div class="offcanvas-header">
        <h2 id="approve-purchase-order-offcanvas-label" style="margin-bottom:-0.5rem">Approve Request</h2>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
      </div>
    <div class="offcanvas-body">
      <div class="row">
        <div class="col-lg-12">
          <form id="approve-purchase-order-form" method="post" action="#">
            <div class="form-group row">
              <div class="col-lg-12 mt-3 mt-lg-0">
                <label class="form-label">Approval Remarks <span class="text-danger">*</span></label>
                <textarea class="form-control" id="approval_remarks" name="approval_remarks" maxlength="500"></textarea>
              </div>
            </div>
          </form>
        </div>
      </div>
      <div class="row">
        <div class="col-lg-12">
          <button type="submit" class="btn btn-primary" id="submit-approve-transaction" form="approve-purchase-order-form">Submit</button>
          <button class="btn btn-light-danger" data-bs-dismiss="offcanvas"> Close </button>
        </div>
      </div>
    </div>
  </div>

  
<div>
    <div class="offcanvas offcanvas-end" tabindex="-1" id="receive-item-offcanvas" aria-labelledby="receive-item-offcanvas-label">
      <div class="offcanvas-header">
        <h2 id="receive-item-offcanvas-label" style="margin-bottom:-0.5rem">Receive Item</h2>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
      </div>
    <div class="offcanvas-body">
      <div class="row">
        <div class="col-lg-12">
          <form id="receive-item-form" method="post" action="#">
            <div class="form-group row">
              <div class="col-lg-6 mt-3 mt-lg-0">
                <label class="form-label">Received Quantity <span class="text-danger">*</span></label>
                <input type="number" class="form-control" id="received_quantity" name="received_quantity" min="0.01" step="0.01">
              </div>
              <div class="col-lg-6 mt-3 mt-lg-0">
                <label class="form-label">Remaining Quantity</label>
                <input type="number" class="form-control" id="remaining_quantity" name="remaining_quantity" min="0.01" step="0.01" readonly>
              </div>
            </div>
          </form>
        </div>
      </div>
      <div class="row">
        <div class="col-lg-12">
          <button type="submit" class="btn btn-primary" id="submit-receive" form="receive-item-form">Submit</button>
          <button class="btn btn-light-danger" data-bs-dismiss="offcanvas"> Close </button>
        </div>
      </div>
    </div>
  </div>

<div>
    <div class="offcanvas offcanvas-end" tabindex="-1" id="cancel-receive-item-offcanvas" aria-labelledby="cancel-receive-item-offcanvas-label">
      <div class="offcanvas-header">
        <h2 id="cancel-receive-item-offcanvas-label" style="margin-bottom:-0.5rem">Cancel Remaining Quantity</h2>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
      </div>
    <div class="offcanvas-body">
      <div class="row">
        <div class="col-lg-12">
          <form id="cancel-receive-item-form" method="post" action="#">
            <div class="form-group row">
              <div class="col-lg-6 mt-3 mt-lg-0">
                <label class="form-label">Cancel Quantity <span class="text-danger">*</span></label>
                <input type="number" class="form-control" id="cancel_received_quantity" name="cancel_received_quantity" min="0.01" step="0.01">
              </div>
              <div class="col-lg-6 mt-3 mt-lg-0">
                <label class="form-label">Remaining Quantity</label>
                <input type="number" class="form-control" id="cancel_remaining_quantity" name="cancel_remaining_quantity" min="0.01" step="0.01" readonly>
              </div>
            </div>
          </form>
        </div>
      </div>
      <div class="row">
        <div class="col-lg-12">
          <button type="submit" class="btn btn-primary" id="submit-cancel-receive" form="cancel-receive-item-form">Submit</button>
          <button class="btn btn-light-danger" data-bs-dismiss="offcanvas"> Close </button>
        </div>
      </div>
    </div>
  </div>