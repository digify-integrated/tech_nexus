<?php
    $partIncomingDetails = $partsIncomingModel->getPartsIncoming($partsIncomingID);
    $part_incoming_status = $partIncomingDetails['part_incoming_status'] ?? 'Draft';
    $number_of_items = $partIncomingDetails['number_of_items'] ?? 0;

    $disabled = '';
    if($part_incoming_status != 'Draft'){
      $disabled = 'disabled';
    }

    $releaseIncomingParts = $userModel->checkSystemActionAccessRights($user_id, 200);
?>

<div class="row">
  <div class="col-lg-12">
    <div class="card">
      <div class="card-header">
        <div class="row align-items-center">
          <div class="col-md-6">
            <h5>Parts Incoming</h5>
          </div>
          <div class="col-md-6 text-sm-end mt-3 mt-sm-0">
            <?php                            
                $dropdown = '<div class="btn-group m-r-5">
                                <button type="button" class="btn btn-outline-secondary dropdown-toggle form-details" data-bs-toggle="dropdown" aria-expanded="false">
                                    Action
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end">';
                            
                if ($partsIncomingDeleteAccess['total'] > 0) {
                  $dropdown .= '<li><button class="dropdown-item" type="button" id="delete-parts-incoming-details">Delete Parts Incoming</button></li>';
                }

                if($part_incoming_status == 'Draft'){
                  $dropdown .= '<li><button class="dropdown-item" type="button" id="for-approval">For Approval</button></li>';
                }

                if($part_incoming_status == 'For Approval'){
                  $dropdown .= '<li><button class="dropdown-item" type="button" id="on-process">Approve</button></li>';
                }

                if($part_incoming_status == 'Completed' && $postPartsIncoming['total'] > 0){
                  $dropdown .= '<li><button class="dropdown-item" type="button" id="post">Post</button></li>';
                }

                if($part_incoming_status == 'Posted'){
                   $dropdown .= '<li><a href="parts-incoming-receiving-report.php?id='. $partsIncomingID .'" class="dropdown-item"  target="_blank">Print Receiving Report</a></li>';
                }

                if($part_incoming_status == 'Draft' || $part_incoming_status == 'For Approval' || $part_incoming_status == 'On-Process'){
                  $dropdown .= '<li><button class="dropdown-item" type="button" data-bs-toggle="offcanvas" data-bs-target="#cancel-incoming-offcanvas" aria-controls="cancel-incoming-offcanvas" id="cancelled">Cancel</button></li>';
                }

                if($part_incoming_status == 'For Approval' || $part_incoming_status == 'On-Process'){
                  $dropdown .= '<li><button class="dropdown-item" type="button" data-bs-toggle="offcanvas" data-bs-target="#draft-incoming-offcanvas" aria-controls="draft-incoming-offcanvas" id="draft">Set to Draft</button></li>';
                }

                if($releaseIncomingParts['total'] > 0 && $part_incoming_status == 'On-Process'){
                  $dropdown .= '<li><button class="dropdown-item" type="button" data-bs-toggle="offcanvas" data-bs-target="#release-incoming-offcanvas" aria-controls="release-incoming-offcanvas">Complete</button></li>';
                }

                if($part_incoming_status == 'On-Process'){
                   $dropdown .= '<li><a href="parts-incoming-purchase-order.php?id='. $partsIncomingID .'" class="dropdown-item"  target="_blank">Print Purchase Order</a></li>';
                }
                        
                $dropdown .= '</ul>
                            </div>';
                    
                echo $dropdown;

                if ($partsIncomingWriteAccess['total'] > 0 && $part_incoming_status == 'Draft') {
                    echo '<button type="submit" form="parts-incoming-form" class="btn btn-success me-2" id="submit-data">Save</button>';
                }

                if ($partsIncomingCreateAccess['total'] > 0) {
                    echo '<a class="btn btn-success m-r-5 form-details" href="parts-incoming.php?new">Create</a>';
                }
            ?>
          </div>
        </div>
      </div>
      <div class="card-body">
        <form id="parts-incoming-form" method="post" action="#">
          <?php
            $readonly = '';
            if($company == '2'){
              $readonly = 'readonly';
            }
          ?>
          <div class="form-group row">
            <label class="col-lg-2 col-form-label">Reference Number <span class="text-danger">*</span></label>
            <div class="col-lg-10">
              <input type="text" class="form-control" id="reference_number" name="reference_number" maxlength="300" autocomplete="off" <?php echo $disabled; echo $readonly; ?>>
            </div>
          </div>
          <div class="form-group row">
            <label class="col-lg-2 col-form-label">Request By <span class="text-danger">*</span></label>
            <div class="col-lg-4">
              <input type="text" class="form-control" id="request_by" name="request_by" maxlength="500" autocomplete="off" <?php echo $disabled; ?>>
            </div>
             <label class="col-lg-2 col-form-label">Purchase Date <span class="text-danger">*</span></label>
            <div class="col-lg-4">
              <div class="input-group date">
                <input type="text" class="form-control regular-datepicker" id="purchase_date" name="purchase_date" autocomplete="off" <?php echo $disabled; ?>>
                <span class="input-group-text">
                  <i class="feather icon-calendar"></i>
                </span>
              </div>
            </div>
          </div>
          <div class="form-group row">
            <label class="col-lg-2 col-form-label">Supplier <span class="text-danger">*</span></label>
            <div class="col-lg-4">
              <select class="form-control select2" name="supplier_id" id="supplier_id" <?php echo $disabled; ?>>
                <option value="">--</option>
                <?php echo $supplierModel->generateSupplierOptions(); ?>
              </select>
            </div>
            <label class="col-lg-2 col-form-label">Customer Reference <span class="text-danger">*</span></label>
            <div class="col-lg-4">
                <select class="form-control select2" name="customer_ref_id" id="customer_ref_id" <?php echo $disabled; ?>>
                  <option value="">--</option>
                  <?php echo $customerModel->generateAllContactsOptions(); ?>
                </select>
            </div>
          </div>
          <div class="form-group row">
            <label class="col-lg-2 col-form-label">Product <span class="text-danger">*</span></label>
            <div class="col-lg-4">
              <select class="form-control select2" name="product_id" id="product_id" <?php echo $disabled; ?>>
                <option value="">--</option>
                <?php echo $productModel->generateAllProductWithStockNumberOptions(); ?>
              </select>
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
                    <h5>Part Purchased</h5>
                </div>
                <div class="col-sm-6 text-sm-end mt-3 mt-sm-0">
                    <?php
                        if($part_incoming_status == 'Draft'){
                          echo '<button class="btn btn-success" type="button" data-bs-toggle="offcanvas" data-bs-target="#add-part-offcanvas" aria-controls="add-part-offcanvas" id="add-part">Add Parts</button>';
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
                            <th>Part</th>
                            <th class="text-center">Qty.</th>
                            <th class="text-center">Received Qty.</th>
                            <th class="text-center">Remaining Qty.</th>
                            <?php
                              if($viewPartCost['total'] > 0){
                                echo '<th class="text-center">Total Cost</th>';
                              }
                            ?>
                            <th class="text-center">Available Stock</th>
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
                    <h5 class="mb-0">Incoming Summary</h5>
                </li>
                <li class="list-group-item px-0 <?php echo  $viewPartCost['total'] == 0 ? 'd-none' : ''; ?>">
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
                <li class="list-group-item px-0">
                    <div class="float-end">
                        <h5 class="mb-0" id="total-received-quantity-summary">0</h5>
                    </div><span class="text-muted">Total Received Quantity</span></li>
                <li class="list-group-item px-0">
                    <div class="float-end">
                        <h5 class="mb-0" id="total-remaining-quantity-summary">0</h5>
                    </div><span class="text-muted">Total Remaining Quantity</span></li>
            </ul>
        </div>
    </div>
  </div>
  <div class="col-lg-12">
      <div class="card">
        <div class="card-header">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h5>Incoming Document</h5>
                </div>
                <div class="col-sm-6 text-sm-end mt-3 mt-sm-0">
                    <?php
                      if($part_incoming_status != 'Released'){
                        echo '<button class="btn btn-success" type="button" data-bs-toggle="offcanvas" data-bs-target="#add-part-document-offcanvas" aria-controls="add-part-document-offcanvas" id="add-part-document">Add Document</button>';
                      }  
                    ?>                    
                </div>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="dt-responsive table-responsive">
                <table class="table mb-0" id="parts-incoming-document-table">
                    <thead>
                        <tr>
                            <th>Incoming Document</th>
                            <th class="text-end">Upload Date</th>
                            <th class="text-end"></th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
  </div>
</div>

<div class="offcanvas offcanvas-end" tabindex="-1" id="add-part-offcanvas" aria-labelledby="add-part-offcanvas-label">
    <div class="offcanvas-header">
        <h2 id="add-part-offcanvas-label" style="margin-bottom:-0.5rem">Add Part</h2>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
        <div class="row mb-4 text-center">
            <form id="add-part-form" method="post" action="#"></form>
                <table id="add-part-table" class="table table-hover nowrap w-100 dataTable">
                    <thead>
                        <tr>
                            <th>Part</th>
                            <th>Price</th>
                            <th>Stock</th>
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
  <div class="offcanvas offcanvas-end" tabindex="-1" id="add-part-document-offcanvas" aria-labelledby="add-part-document-offcanvas-label">
    <div class="offcanvas-header">
      <h2 id="add-part-document-offcanvas-label" style="margin-bottom:-0.5rem">Add Document</h2>
      <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
      <div class="row">
        <div class="col-lg-12">
          <form id="add-part-document-form" method="post" action="#">
            <div class="form-group row">
              <div class="col-lg-12 mt-3 mt-lg-0">
                <label class="form-label">Document Name <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="document_name" name="document_name" maxlength="200" autocomplete="off">
              </div>
            </div>
            <div class="form-group row">
              <div class="col-lg-12 mt-3 mt-lg-0">
                <label class="form-label">Document <span class="text-danger">*</span></label>
                <input type="file" id="incoming_document" name="incoming_document" class="form-control">
              </div>
            </div>
          </form>
        </div>
      </div>
      <div class="row">
        <div class="col-lg-12">
          <button type="submit" class="btn btn-primary" id="submit-add-part-document" form="add-part-document-form">Submit</button>
          <button class="btn btn-light-danger" data-bs-dismiss="offcanvas"> Close </button>
        </div>
      </div>
    </div>
  </div>
</div>

<div>
  <div class="offcanvas offcanvas-end" tabindex="-1" id="cancel-incoming-offcanvas" aria-labelledby="cancel-incoming-offcanvas-label">
      <div class="offcanvas-header">
        <h2 id="cancel-incoming-offcanvas-label" style="margin-bottom:-0.5rem">Cancel Incoming</h2>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
      </div>
    <div class="offcanvas-body">
      <div class="row">
        <div class="col-lg-12">
          <form id="cancel-incoming-form" method="post" action="#">
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
          <button type="submit" class="btn btn-primary" id="submit-cancel-incoming" form="cancel-incoming-form">Submit</button>
          <button class="btn btn-light-danger" data-bs-dismiss="offcanvas"> Close </button>
        </div>
      </div>
  </div>
</div>

<div>
  <div class="offcanvas offcanvas-end" tabindex="-1" id="draft-incoming-offcanvas" aria-labelledby="draft-incoming-offcanvas-label">
      <div class="offcanvas-header">
        <h2 id="draft-incoming-offcanvas-label" style="margin-bottom:-0.5rem">Set To Draft Incoming</h2>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
      </div>
    <div class="offcanvas-body">
      <div class="row">
        <div class="col-lg-12">
          <form id="draft-incoming-form" method="post" action="#">
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
          <button type="submit" class="btn btn-primary" id="submit-draft-incoming" form="draft-incoming-form">Submit</button>
          <button class="btn btn-light-danger" data-bs-dismiss="offcanvas"> Close </button>
        </div>
      </div>
  </div>
</div>

<div>
  <div class="offcanvas offcanvas-end" tabindex="-1" id="release-incoming-offcanvas" aria-labelledby="release-incoming-offcanvas-label">
      <div class="offcanvas-header">
        <h2 id="release-incoming-offcanvas-label" style="margin-bottom:-0.5rem">Complete Incoming</h2>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
      </div>
    <div class="offcanvas-body">
      <div class="row">
        <div class="col-lg-12">
          <form id="release-incoming-form" method="post" action="#">
            <div class="form-group row">
              <div class="col-lg-12 mt-3 mt-lg-0"> 
                <label class="form-label">Invoice Number <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="invoice_number" name="invoice_number" maxlength="200" autocomplete="off">
              </div>
            </div>
            <div class="form-group row">
              <div class="col-lg-12 mt-3 mt-lg-0"> 
                <label class="form-label">Invoice Price <span class="text-danger">*</span></label>
                <input type="number" class="form-control" id="invoice_price" name="invoice_price" min="0.01" step="0.01">
              </div>
            </div>
            <div class="form-group row">
              <div class="col-lg-12 mt-3 mt-lg-0"> 
                <label class="form-label">Invoice Date <span class="text-danger">*</span></label>
                <div class="input-group date">
                  <input type="text" class="form-control regular-datepicker" id="invoice_date" name="invoice_date" autocomplete="off">
                  <span class="input-group-text">
                    <i class="feather icon-calendar"></i>
                  </span>
                </div>
              </div>
            </div>
            <div class="form-group row">
              <div class="col-lg-12 mt-3 mt-lg-0"> 
                <label class="form-label">Delivery Date <span class="text-danger">*</span></label>
                <div class="input-group date">
                  <input type="text" class="form-control regular-datepicker" id="delivery_date" name="delivery_date" autocomplete="off">
                  <span class="input-group-text">
                    <i class="feather icon-calendar"></i>
                  </span>
                </div>
              </div>
            </div>
          </form>
        </div>
      </div>
      <div class="row">
        <div class="col-lg-12">
          <button type="submit" class="btn btn-primary" id="submit-release-incoming" form="release-incoming-form">Submit</button>
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

<?php
  $readOnly = '';
  if(($part_incoming_status == 'On-Process' && $viewPartCost['total'] > 0 && $updatePartCost['total'] > 0) || 
     ($part_incoming_status == 'Completed' && $viewPartCost['total'] > 0 && $updatePartIncomingCompletedCost['total'] > 0)){
    $readOnly = 'readonly';
  }
?>


<div>
    <div class="offcanvas offcanvas-end" tabindex="-1" id="part-cart-offcanvas" aria-labelledby="part-cart-offcanvas-label">
      <div class="offcanvas-header">
        <h2 id="part-cart-offcanvas-label" style="margin-bottom:-0.5rem">Part Item</h2>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
      </div>
    <div class="offcanvas-body">
      <div class="row">
        <div class="col-lg-12">
          <form id="part-item-form" method="post" action="#">
            <input type="hidden" id="part_incoming_cart_id" name="part_incoming_cart_id">
            <input type="hidden" id="part_id" name="part_id">
            <div class="form-group row">
              <div class="col-lg-6 mt-3 mt-lg-0">
                <label class="form-label">Part</label>
                <input type="text" class="form-control" id="part_description" name="part_description" readonly>
              </div>
              <div class="col-lg-6 mt-3 mt-lg-0">
                <label class="form-label">Available Stock</label>
                <input type="number" class="form-control" id="available_stock" name="available_stock" min="0" readonly>
              </div>
            </div>
            <div class="form-group row">
              <div class="col-lg-12 mt-3 mt-lg-0">
                <label class="form-label">Quantity <span class="text-danger">*</span></label>
                <input type="number" class="form-control" id="quantity" name="quantity" min="0.01" step="0.01" <?php echo $readOnly; ?>>
              </div>
            </div>
            <div class="form-group row" id="cost-row">
              <div class="col-lg-12 mt-3 mt-lg-0">
                <label class="form-label">Total Cost <span class="text-danger">*</span></label>
                <input type="number" class="form-control" id="total_cost" name="total_cost" min="1" step="0.01">
              </div>
            </div>
            <div class="form-group row">
              <div class="col-lg-12 mt-3 mt-lg-0">
                <label class="form-label">Remarks</label>
                <textarea class="form-control" id="remarks" name="remarks" maxlength="1000" <?php echo $readOnly; ?>></textarea>
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