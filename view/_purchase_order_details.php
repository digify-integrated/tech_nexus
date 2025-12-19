<?php
  $purchaseOrderDetails = $purchaseOrderModel->getPurchaseOrder($purchaseOrderID);
  $purchase_order_status = $purchaseOrderDetails['purchase_order_status'] ?? 'Draft';
    
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

              if($purchase_order_status == 'For Approval' && $approvePurchaseOrder['total'] > 0){
                echo '<button class="btn btn-success ms-2" type="button" data-bs-toggle="offcanvas" data-bs-target="#approve-purchase-order-offcanvas" aria-controls="approve-purchase-order-offcanvas" id="approved">Approve</button>';
              }

              if($purchase_order_status == 'Draft' || $purchase_order_status == 'For Approval'){
                echo '<button class="btn btn-warning ms-2" type="button" data-bs-toggle="offcanvas" data-bs-target="#cancel-purchase-order-offcanvas" aria-controls="cancel-purchase-order-offcanvas" id="cancelled">Cancel</button>';
              }

              if($purchase_order_status == 'For Approval'){
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
            <div class="col-lg-10">
              <input type="text" class="form-control" id="reference_no" name="reference_no" maxlength="100" autocomplete="off" readonly>
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

<div class="row">
  <div class="col-lg-12">
    <div class="card">
      <div class="card-header">
        <div class="row align-items-center">
          <div class="col-md-6">
            <h5>Purchase Order Item</h5>
          </div>
          <div class="col-sm-6 text-sm-end mt-3 mt-sm-0">
            <?php
              if($purchase_order_status == 'Draft'){
                echo '<button class="btn btn-success" type="button" data-bs-toggle="offcanvas" data-bs-target="#add-item-offcanvas" aria-controls="add-item-offcanvas" id="add-item">Add Item</button>';
              }
            ?>
          </div>
        </div>
      </div>
      <div class="card-body p-0">
        <div class="dt-responsive table-responsive">
          <table class="table mb-0" id="purchase-order-item-table">
            <thead>
              <tr>
                <th class="text-end"></th>
                <th>Item</th>
                <th class="text-center">Quantity</th>
                <th class="text-center">Remarks</th>
              </tr>
            </thead>
            <tbody></tbody>
          </table>
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
  <div class="offcanvas offcanvas-end" tabindex="-1" id="add-item-offcanvas" aria-labelledby="add-item-offcanvas-label">
    <div class="offcanvas-header">
      <h2 id="add-item-offcanvas-label" style="margin-bottom:-0.5rem">Purchase Order Item</h2>
      <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
      <div class="row">
        <div class="col-lg-12">
          <form id="add-item-form" method="post" action="#">
            <input type="hidden" id="purchase_order_cart_id" name="purchase_order_cart_id">
            <div class="form-group row">
              <div class="col-lg-12 mt-3 mt-lg-0">
                <label class="form-label">Item <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="description" name="description" maxlength="200" autocomplete="off">
              </div>
            </div>
            <div class="form-group row">
              <div class="col-lg-6 mt-3 mt-lg-0">
                <label class="form-label">Quantity <span class="text-danger">*</span></label>
                <input type="number" class="form-control" id="quantity" name="quantity" value="0" min="0.01" step="0.01">
              </div>
              <div class="col-lg-6 mt-3 mt-lg-0">
                <label class="form-label">Unit <span class="text-danger">*</span></label>
                <select class="form-control offcanvas-select2" name="unit_id" id="unit_id">
                  <option value="">--</option>
                  <?php echo $unitModel->generateUnitOptions(); ?>
                </select>
              </div>
            </div>
            <div class="form-group row">
              <div class="col-lg-12 mt-3 mt-lg-0">
                <label class="form-label">Remarks</label>
                <textarea class="form-control" id="item_remarks" name="item_remarks" maxlength="2000"></textarea>
              </div>
            </div>
          </form>
        </div>
      </div>
      <div class="row">
        <div class="col-lg-12">
          <button type="submit" class="btn btn-primary" id="submit-add-item" form="add-item-form">Submit</button>
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