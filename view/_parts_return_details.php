<?php
    $partReturnDetails = $partsReturnModel->getPartsReturn($partsReturnID);
  $releasePartsReturn = $userModel->checkSystemActionAccessRights($user_id, 226);
    $part_return_status = $partReturnDetails['part_return_status'] ?? 'Draft';

    $disabled = '';
    if($part_return_status != 'Draft'){
      $disabled = 'disabled';
    }

    $releaseReturnParts = $userModel->checkSystemActionAccessRights($user_id, 200);
?>

<div class="row">
  <div class="col-lg-12">
    <div class="card">
      <div class="card-header">
        <div class="row align-items-center">
          <div class="col-md-6">
            <h5><?php echo $cardLabel; ?> Return</h5>
          </div>
          <div class="col-md-6 text-sm-end mt-3 mt-sm-0">
            <?php
                if($part_return_status == 'Draft'){
                  echo '<button class="btn btn-info ms-2" type="button" id="for-approval">For Validation</button>';
                }

                if($part_return_status == 'For Validation' && $approvePartsReturn['total'] > 0){
                  echo '<button class="btn btn-success ms-2" type="button" data-bs-toggle="offcanvas" data-bs-target="#approve-return-offcanvas" aria-controls="approve-return-offcanvas" id="approved">Validate</button>';
                }

                if($part_return_status == 'Validated' && $releasePartsReturn['total'] > 0){
                  echo '<button class="btn btn-success ms-2" type="button" id="release">Release</button>';
                }

                if($part_return_status == 'Draft' || $part_return_status == 'For Validation' || $part_return_status == 'For Approval'){
                  echo '<button class="btn btn-warning ms-2" type="button" data-bs-toggle="offcanvas" data-bs-target="#cancel-return-offcanvas" aria-controls="cancel-return-offcanvas" id="cancelled">Cancel</button>';
                }

                if($part_return_status == 'For Validation' || $part_return_status == 'For Approval' || $part_return_status == 'On-Process'){
                  echo '<button class="btn btn-dark ms-2" type="button" data-bs-toggle="offcanvas" data-bs-target="#draft-return-offcanvas" aria-controls="draft-return-offcanvas" id="draft">Set To Draft</button>';
                }

                if(($part_return_status == 'Validated' || $part_return_status == 'Released' || $part_return_status == 'Cancelled') && ($company == '2' || $company == '1')){
                  echo '<a href="parts-return-slip.php?id='. $partsReturnID .'" class="button btn btn-info ms-2 me-2" target="_blank">Print Return Slip</a>';
                }

                if ($partsReturnWriteAccess['total'] > 0 && $part_return_status == 'Draft') {
                    echo '<button type="submit" form="parts-return-form" class="btn btn-success ms-2 me-2" id="submit-data">Save</button>';
                }

                if ($partsReturnCreateAccess['total'] > 0) {
                    echo '<a class="btn btn-success m-r-5 form-details" href="parts-return.php?new">Create</a>';
                }
            ?>
          </div>
        </div>
      </div>
      <div class="card-body">
        <form id="parts-return-form" method="post" action="#">
          <div class="form-group row">
            <label class="col-lg-2 col-form-label">Return Type <span class="text-danger">*</span></label>
            <div class="col-lg-4">
              <select class="form-control select2" name="return_type" id="return_type" <?php echo $disabled; ?>>
                <option value="">--</option>
                <option value="Stock to Supplier">Stock to Supplier</option>
                <option value="Issuance to Supplier">Issuance to Supplier</option>
                <option value="Issuance to Stock">Issuance to Stock</option>
              </select>
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
            <label class="col-lg-2 col-form-label">Purchase Date <span class="text-danger">*</span></label>
            <div class="col-lg-4">
              <div class="input-group date">
                <input type="text" class="form-control regular-datepicker" id="purchase_date" name="purchase_date" autocomplete="off" <?php echo $disabled; ?>>
                <span class="input-group-text">
                  <i class="feather icon-calendar"></i>
                </span>
              </div>
            </div>
            <label class="col-lg-2 col-form-label">Ref. Invoice No. <span class="text-danger">*</span></label>
            <div class="col-lg-4">
              <input type="text" class="form-control" id="ref_invoice_number" name="ref_invoice_number" maxlength="100" autocomplete="off" <?php echo $disabled; ?>>
            </div>
          </div>
          <div class="form-group row">
            <label class="col-lg-2 col-form-label">Ref. PO No. <span class="text-danger">*</span></label>
            <div class="col-lg-4">
              <input type="text" class="form-control" id="ref_po_number" name="ref_po_number" maxlength="100" autocomplete="off" <?php echo $disabled; ?>>
            </div>
            <label class="col-lg-2 col-form-label">Ref. PO Date <span class="text-danger">*</span></label>
            <div class="col-lg-4">
              <div class="input-group date">
                <input type="text" class="form-control regular-datepicker" id="ref_po_date" name="ref_po_date" autocomplete="off" <?php echo $disabled; ?>>
                <span class="input-group-text">
                  <i class="feather icon-calendar"></i>
                </span>
              </div>
            </div>   
          </div>
          <div class="form-group row">          
            <label class="col-lg-2 col-form-label">Previous Total Billing</label>
            <div class="col-lg-4">
                <input type="number" class="form-control" id="prev_total_billing" name="prev_total_billing" min="0" step="0.01" <?php echo $disabled; ?>>
            </div>     
            <label class="col-lg-2 col-form-label">Adusted Total Billing</label>
            <div class="col-lg-4">
                <input type="number" class="form-control" id="adjusted_total_billing" name="adjusted_total_billing" min="0" step="0.01" <?php echo $disabled; ?>>
            </div>
          </div>
          <div class="form-group row">
            <label class="col-lg-2 col-form-label">Remarks</label>
            <div class="col-lg-10">
              <textarea class="form-control" id="remarks" name="remarks" maxlength="1000" <?php echo $disabled; ?>></textarea>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<div class="row">
  <div class="col-lg-8">
    <div class="card">
        <div class="card-header">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h5><?php echo $cardLabel; ?> Return</h5>
                </div>
                <div class="col-sm-6 text-sm-end mt-3 mt-sm-0">
                    <?php
                        if($part_return_status == 'Draft'){
                          echo '<button class="btn btn-success" type="button" data-bs-toggle="offcanvas" data-bs-target="#add-part-offcanvas" aria-controls="add-part-offcanvas" id="add-part">Add '.$cardLabel.'</button>';
                        }
                    ?>
                </div>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="dt-responsive table-responsive">
                <table class="table mb-0" id="parts-item-table">
                    <thead>
                        <tr>
                            <th class="text-end"></th>
                            <th>Issuance No.</th>
                            <th><?php echo $cardLabel; ?></th>
                            <th>Cost</th>
                            <th class="text-center">Return Qty.</th>
                            <th class="text-center">Available Return Qty.</th>
                            <th class="text-center">Remarks</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
  </div>
  <div class="col-lg-4">
    <div class="card">
        <div class="card-body py-2">
            <ul class="list-group list-group-flush">
                <li class="list-group-item px-0">
                    <h5 class="mb-0">Return Summary</h5>
                </li>
                <li class="list-group-item px-0">
                    <div class="float-end">
                        <h5 class="mb-0" id="total-cost-summary">0.00 PHP</h5>
                    </div><span class="text-muted">Total Cost</span></li>
                <li class="list-group-item px-0">
                    <div class="float-end">
                        <h5 class="mb-0" id="total-item-summary">0</h5>
                    </div><span class="text-muted">Total Item Lines</span></li>
                <li class="list-group-item px-0">
                    <div class="float-end">
                        <h5 class="mb-0" id="total-quantity-summary">0</h5>
                    </div><span class="text-muted">Total Item Quantity</span></li>
            </ul>
        </div>
    </div>
  </div>
</div>

<div class="offcanvas offcanvas-end" tabindex="-1" id="add-part-offcanvas" aria-labelledby="add-part-offcanvas-label">
    <div class="offcanvas-header">
        <h2 id="add-part-offcanvas-label" style="margin-bottom:-0.5rem">Add <?php echo $cardLabel; ?> Issuance</h2>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
        <div class="row mb-4">
            <form id="add-part-form" method="post" action="#"></form>
                <table id="add-part-table" class="table table-hover nowrap w-100 dataTable">
                    <thead>
                        <tr>
                            <th>Issuance No.</th>
                            <th><?php echo $cardLabel; ?></th>
                            <th>Cost</th>
                            <th>Available Qty.</th>
                            <th class="all">Add</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </form> 
        </div>
        <div class="row">
            <div class="col-lg-12">
                <button type="submit" class="btn btn-primary" id="submit-add-part" form="add-part-form">Submit</button>
                <button class="btn btn-light-danger" data-bs-dismiss="offcanvas"> Close </button>
            </div>
        </div>
    </div>
</div>

<div>
  <div class="offcanvas offcanvas-end" tabindex="-1" id="cancel-return-offcanvas" aria-labelledby="cancel-return-offcanvas-label">
      <div class="offcanvas-header">
        <h2 id="cancel-return-offcanvas-label" style="margin-bottom:-0.5rem">Cancel Return</h2>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
      </div>
    <div class="offcanvas-body">
      <div class="row">
        <div class="col-lg-12">
          <form id="cancel-return-form" method="post" action="#">
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
          <button type="submit" class="btn btn-primary" id="submit-cancel-return" form="cancel-return-form">Submit</button>
          <button class="btn btn-light-danger" data-bs-dismiss="offcanvas"> Close </button>
        </div>
      </div>
  </div>
</div>

<div>
  <div class="offcanvas offcanvas-end" tabindex="-1" id="draft-return-offcanvas" aria-labelledby="draft-return-offcanvas-label">
      <div class="offcanvas-header">
        <h2 id="draft-return-offcanvas-label" style="margin-bottom:-0.5rem">Set To Draft Return</h2>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
      </div>
    <div class="offcanvas-body">
      <div class="row">
        <div class="col-lg-12">
          <form id="draft-return-form" method="post" action="#">
            <div class="form-group row">
              <div class="col-lg-12 mt-3 mt-lg-0">
                <label class="form-label">Set to Draft Reason <span class="text-danger">*</span></label>
                <textarea class="form-control" id="set_to_draft_reason" name="set_to_draft_reason" maxlength="500"></textarea>
              </div>
            </div>
          </form>
        </div>
      </div>
      <div class="row">
        <div class="col-lg-12">
          <button type="submit" class="btn btn-primary" id="submit-draft-return" form="draft-return-form">Submit</button>
          <button class="btn btn-light-danger" data-bs-dismiss="offcanvas"> Close </button>
        </div>
      </div>
  </div>
</div>
<?php
  $readOnly = '';
  if(($part_return_status == 'On-Process' && $viewPartCost['total'] > 0 && $updatePartCost['total'] > 0) || 
     ($part_return_status == 'Completed' && $viewPartCost['total'] > 0 && $updatePartReturnCompletedCost['total'] > 0)){
    $readOnly = 'readonly';
  }
?>

 <div>
    <div class="offcanvas offcanvas-end" tabindex="-1" id="approve-return-offcanvas" aria-labelledby="approve-return-offcanvas-label">
      <div class="offcanvas-header">
        <h2 id="approve-return-offcanvas-label" style="margin-bottom:-0.5rem">Validate Return</h2>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
      </div>
    <div class="offcanvas-body">
      <div class="row">
        <div class="col-lg-12">
          <form id="approve-return-form" method="post" action="#">
            <div class="form-group row">
              <div class="col-lg-12 mt-3 mt-lg-0">
                <label class="form-label">Validation Remarks <span class="text-danger">*</span></label>
                <textarea class="form-control" id="approval_remarks" name="approval_remarks" maxlength="500"></textarea>
              </div>
            </div>
          </form>
        </div>
      </div>
      <div class="row">
        <div class="col-lg-12">
          <button type="submit" class="btn btn-primary" id="submit-approve-return" form="approve-return-form">Submit</button>
          <button class="btn btn-light-danger" data-bs-dismiss="offcanvas"> Close </button>
        </div>
      </div>
    </div>
  </div>


<div>
    <div class="offcanvas offcanvas-end" tabindex="-1" id="part-cart-offcanvas" aria-labelledby="part-cart-offcanvas-label">
      <div class="offcanvas-header">
        <h2 id="part-cart-offcanvas-label" style="margin-bottom:-0.5rem"><?php echo $cardLabel; ?> Return</h2>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
      </div>
    <div class="offcanvas-body">
      <div class="row">
        <div class="col-lg-12">
          <form id="part-item-form" method="post" action="#">
            <input type="hidden" id="part_return_cart_id" name="part_return_cart_id">
            <input type="hidden" id="part_id" name="part_id">
            <div class="form-group row">
              <div class="col-lg-6 mt-3 mt-lg-0">
                <label class="form-label">Issuance No.</label>
                <input type="text" class="form-control" id="issuance_no_description" name="issuance_no_description" readonly>
              </div>
              <div class="col-lg-6 mt-3 mt-lg-0">
                <label class="form-label"><?php echo $cardLabel; ?></label>
                <input type="text" class="form-control" id="part_description" name="part_description" readonly>
              </div>
            </div>
            <div class="form-group row">
              <div class="col-lg-6 mt-3 mt-lg-0">
                <label class="form-label">Available For Return</label>
                <input type="number" class="form-control" id="available_quantity" name="available_quantity" min="0" readonly>
              </div>
              <div class="col-lg-6 mt-3 mt-lg-0">
                <label class="form-label">Return Quantity <span class="text-danger">*</span></label>
                <input type="number" class="form-control" id="return_quantity" name="return_quantity" min="0.01" step="0.01" <?php echo $readOnly; ?>>
              </div>
            </div>
            <div class="form-group row">
              <div class="col-lg-12 mt-3 mt-lg-0">
                <label class="form-label">Remarks</label>
                <textarea class="form-control" id="part_remarks" name="part_remarks" maxlength="1000" <?php echo $readOnly; ?>></textarea>
              </div>
            </div>
          </form>
        </div>
      </div>
      <div class="row">
        <div class="col-lg-12">
          <button type="submit" class="btn btn-primary" id="submit-part-item" form="part-item-form">Submit</button>
          <button class="btn btn-light-danger" data-bs-dismiss="offcanvas"> Close </button>
        </div>
      </div>
    </div>
  </div>