<?php
    $partTransactionDetails = $stockTransferAdviceModel->getStockTransferAdvice($stockTransferAdviceID);
    $sta_status = $partTransactionDetails['sta_status'] ?? 'Draft';
    $sta_type = $partTransactionDetails['sta_type'] ?? 'Transfer';
    $transferred_from = $partTransactionDetails['transferred_from'] ?? null;
    $transferred_to = $partTransactionDetails['transferred_to']  ?? null;

    $productDetails1 = $productModel->getProduct($transferred_from);
    $productName1 = $productDetails1['description'] ?? null;

    $productDetails2 = $productModel->getProduct($transferred_to);
    $productName2 = $productDetails2['description'] ?? null;   
    
    $approveStockTransferAdvice = $userModel->checkSystemActionAccessRights($user_id, 201);
    $releaseStockTransferAdvice = $userModel->checkSystemActionAccessRights($user_id, 202);
    $checkStockTransferAdvice = $userModel->checkSystemActionAccessRights($user_id, 219);

    $disabled = '';
    if($sta_status != 'Draft'){
      $disabled = 'disabled';
    }
?>

<input type="hidden" id="part-from-source">
<div class="row">
  <div class="col-lg-12">
    <div class="card">
      <div class="card-header">
        <div class="row align-items-center">
          <div class="col-md-6">
            <h5>Stock Transfer Advice</h5>
          </div>
           <div class="col-sm-6 text-sm-end mt-3 mt-sm-0">
          <?php
            if($sta_status == 'For Approval' && $approveSTA['total'] > 0){
              echo '<button class="btn btn-info ms-2" type="button" id="on-process">Approve</button>';
            }

            if($sta_status == 'Draft'){
              echo '<button class="btn btn-info ms-2" type="button" id="for-approval">For Approval</button>';
            }

            if($sta_status == 'On-Process' || $sta_status == 'For Approval'){
                echo '<button class="btn btn-dark ms-2" type="button" data-bs-toggle="offcanvas" data-bs-target="#draft-transaction-offcanvas" aria-controls="draft-transaction-offcanvas" id="draft">Set To Draft</button>';
            }

            if($sta_status == 'On-Process'){
              echo '<button class="btn btn-success ms-2" type="button" id="complete">Complete</button>';
            }

            if($sta_status == 'Draft' || $sta_status == 'On-Process' || $sta_status == 'For Approval'){
              echo '<button class="btn btn-warning ms-2" type="button" data-bs-toggle="offcanvas" data-bs-target="#cancel-transaction-offcanvas" aria-controls="cancel-transaction-offcanvas" id="cancelled">Cancel</button>';
            }

            if($sta_status == 'On-Process' || $sta_status == 'Completed' || $sta_status == 'Posted'){
              echo '<a href="stock-transfer-advice-form.php?id='. $stockTransferAdviceID .'" class="button btn btn-info ms-2" target="_blank">Print STA Form</a>';
            }

            if($sta_status == 'Completed' && $postSTA['total'] > 0){
              echo '<button class="btn btn-success ms-2" type="button" id="posted">Posted</button>';
            }

            if ($stockTransferAdviceWriteAccess['total'] > 0 && $sta_status == 'Draft') {
              echo '<button type="submit" form="stock-transfer-advice-form" class="btn btn-success form-edit ms-2" id="submit-data">Save</button>
                        <button type="button" id="discard-create" class="btn btn-outline-danger form-edit">Discard</button>';
            }
          ?>
          </div>
        </div>
      </div>
      <div class="card-body">
        <form id="stock-transfer-advice-form" method="post" action="#">
          <div class="form-group row">
            <label class="col-lg-2 col-form-label">Reference No.</label>
            <div class="col-lg-10">
              <input type="text" class="form-control" id="reference_no" name="reference_no" maxlength="100" autocomplete="off" readonly>
            </div>
          </div>
          <div class="form-group row">
            <label class="col-lg-2 col-form-label">STA Type <span class="text-danger">*</span></label>
            <div class="col-lg-4" id="internal-select">
              <select class="form-control select2" name="sta_type" id="sta_type" <?php echo $disabled; ?>>
                <option value="">--</option>
                <option value="Transfer">Transfer</option>
                <option value="Swap">Swap</option>
              </select>
            </div>
            <label class="col-lg-2 col-form-label">Company <span class="text-danger">*</span></label>
            <div class="col-lg-4" id="internal-select">
              <select class="form-control select2" name="company_id" id="company_id" <?php echo $disabled; ?>>
              <option value="">--</option>
                <option value="2">NE Truck Builders</option>
                <option value="3">FUSO Tarlac</option>
              </select>
            </div>
          </div>
          <div class="form-group row">
            <label class="col-lg-2 col-form-label">Transferred From <span class="text-danger">*</span></label>
            <div class="col-lg-4" id="internal-select">
              <select class="form-control select2" name="transferred_from" id="transferred_from" <?php echo $disabled; ?>>
                <option value="">--</option>
                <?php echo $productModel->generateAllProductWithStockNumberOptions(); ?>
              </select>
            </div>
            <label class="col-lg-2 col-form-label">Transferred To <span class="text-danger">*</span></label>
            <div class="col-lg-4" id="internal-select">
              <select class="form-control select2" name="transferred_to" id="transferred_to" <?php echo $disabled; ?>>
                <option value="">--</option>
                <?php echo $productModel->generateAllProductWithStockNumberOptions(); ?>
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
          <div class="col-md-9">
            <h5>Parts Transfer</h5>
          </div>
          <div class="col-sm-3 text-sm-end mt-3 mt-sm-0">
            <?php
                if($sta_status == 'Draft' && $sta_type == 'Swap'){
                  echo '<div class="btn-group m-r-10">
                                    <button type="button" class="btn btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">Add Part</button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                      <li><button class="dropdown-item" type="button" data-bs-toggle="offcanvas" data-bs-target="#add-part-offcanvas" aria-controls="add-part-offcanvas" id="add-part-from">From -> To</button></li>
                                      <li><button class="dropdown-item" type="button" data-bs-toggle="offcanvas" data-bs-target="#add-part-offcanvas" aria-controls="add-part-offcanvas" id="add-part-to">To -> From</button></li>
                                    </ul>
                                  </div>';
                }
                else if($sta_status == 'Draft' && $sta_type == 'Transfer'){
                  echo '<div class="btn-group m-r-10">
                                    <button type="button" class="btn btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">Add Part</button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                      <li><button class="dropdown-item" type="button" data-bs-toggle="offcanvas" data-bs-target="#add-part-offcanvas" aria-controls="add-part-offcanvas" id="add-part-from">From -> To</button></li>
                                    </ul>
                                  </div>';
                }
            ?>
          </div>
        </div>
      </div>
      <div class="card-body p-0">
        <div class="dt-responsive table-responsive">
          <table class="table mb-0 w-100" id="parts-item-table">
            <thead>
              <tr>
                <th class="text-center"></th>
                <th>Transferred From</th>
                <th>Transferred To</th>
                <th>Part</th>
                <th class="text-center">Quantity</th>
                <th class="text-center">Price</th>
              </tr>
            </thead>
            <tbody></tbody>
          </table>
        </div>
      </div>
    </div>
    <div class="card">
      <div class="card-body py-2">
        <ul class="list-group list-group-flush">
          <li class="list-group-item px-0">
            <div class="float-end">
              <h5 class="mb-0" id="total_summary_from">0.00 PHP</h5>
            </div>
            <h5 class="mb-0 d-inline-block">Total</h5>
          </li>
        </ul>
      </div>
    </div>
  </div>
</div>

<div class="row">
  <div class="col-lg-12">
    <div class="card">
      <div class="card-header">
        <div class="row align-items-center">
          <div class="col-md-9">
            <h5>Parts Replacement</h5>
          </div>
        </div>
      </div>
    <div class="card-body p-0">
      <div class="dt-responsive table-responsive">
         <table class="table mb-0 w-100" id="parts-replacement-table">
            <thead>
              <tr>
                <th class="text-center"></th>
                <th>Product</th>
                <th>Part</th>
                <th>Replacement Status</th>
              </tr>
            </thead>
            <tbody></tbody>
          </table>
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
                <div class="col-md-6">
                    <h5>Job Order</h5>
                </div>
                <div class="col-sm-6 text-sm-end mt-3 mt-sm-0">
                    <?php
                       if($sta_status == 'Draft'){
                            echo '<button type="button" class="btn btn-outline-secondary dropdown-toggle form-details" data-bs-toggle="dropdown" aria-expanded="false">
                                Action
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                              <li><button class="dropdown-item" type="button" data-bs-toggle="offcanvas"  data-bs-target="#add-job-order-offcanvas" aria-controls="add-job-order-offcanvas" id="add-job-order">Link Job Order</button></li>
                              <li><button class="dropdown-item" type="button" data-bs-toggle="offcanvas"  data-bs-target="#add-job-order-offcanvas" aria-controls="add-job-order-offcanvas" id="add-internal-job-order">Link Internal Job Order</button></li>
                            </ul>';
                        }
                    ?>
                </div>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="dt-responsive table-responsive">
                <input type="hidden" id="generate-job-order">
                <ul class="nav nav-tabs analytics-tab" id="myTab" role="tablist">
                    <li class="nav-item" role="presentation"><button class="nav-link active" id="job-order-sales-proposal" data-bs-toggle="tab" data-bs-target="#job-order-sales-proposal-pane" type="button" role="tab" aria-controls="job-order-sales-proposal-pane" aria-selected="true">Sales Proposal</button></li>
                    <li class="nav-item" role="presentation"><button class="nav-link" id="internal-job-order" data-bs-toggle="tab" data-bs-target="#internal-job-order-pane" type="button" role="tab" aria-controls="internal-job-order-pane" aria-selected="false" tabindex="-1">Internal Job Order</button></li>
                </ul>
                <div class="tab-content" id="myTabContent">
                  <div class="tab-pane fade active show" id="job-order-sales-proposal-pane" role="tabpanel" aria-labelledby="job-order-sales-proposal" tabindex="0">
                    <table class="table mb-0" id="job-order-table">
                      <thead>
                        <tr>
                          <th>OS Number</th>
                          <th>Job Order</th>
                          <th>Contractor</th>
                          <th>Work Center</th>
                          <th class="text-end"></th>
                        </tr>
                      </thead>
                      <tbody></tbody>
                    </table>
                  </div>
                  <div class="tab-pane fade" id="internal-job-order-pane" role="tabpanel" aria-labelledby="internal-job-order" tabindex="0">
                    <table class="table w-100 mb-0" id="internal-job-order-table">
                      <thead>
                        <tr>
                          <th>Type</th>
                          <th>OS Number</th>
                          <th>Job Order</th>
                          <th>Contractor</th>
                          <th>Work Center</th>
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
    <div class="card">
        <div class="card-header">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h5>Additional Job Order</h5>
                </div>
                <div class="col-sm-6 text-sm-end mt-3 mt-sm-0">
                    <?php
                        if($sta_status == 'Draft'){
                            echo '<button type="button" class="btn btn-outline-secondary dropdown-toggle form-details" data-bs-toggle="dropdown" aria-expanded="false">
                                Action
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                              <li><button class="dropdown-item" type="button" data-bs-toggle="offcanvas"  data-bs-target="#add-additional-job-order-offcanvas" aria-controls="add-additional-job-order-offcanvas" id="add-additional-job-order">Link Additional Job Order</button></li>
                              <li><button class="dropdown-item" type="button" data-bs-toggle="offcanvas"  data-bs-target="#add-additional-job-order-offcanvas" aria-controls="add-additional-job-order-offcanvas" id="add-internal-additional-job-order">Link Internal Additional Job Order</button></li>
                            </ul>';
                        }
                    ?>
                </div>
            </div>
        </div>
        <div class="card-body p-0">
          <input type="hidden" id="generate-additional-job-order">
          <ul class="nav nav-tabs analytics-tab" id="myTab2" role="tablist">
            <li class="nav-item" role="presentation"><button class="nav-link active" id="additional-job-order-sales-proposal" data-bs-toggle="tab" data-bs-target="#additional-job-order-sales-proposal-pane" type="button" role="tab" aria-controls="additional-job-order-sales-proposal-pane" aria-selected="true">Sales Proposal</button></li>
            <li class="nav-item" role="presentation"><button class="nav-link" id="internal-additional-job-order" data-bs-toggle="tab" data-bs-target="#internal-additional-job-order-pane" type="button" role="tab" aria-controls="internal-additional-job-order-pane" aria-selected="false" tabindex="-1">Internal Job Order</button></li>
          </ul>
          <div class="tab-content" id="myTabContent2">
            <div class="tab-pane fade active show" id="additional-job-order-sales-proposal-pane" role="tabpanel" aria-labelledby="additional-job-order-sales-proposal" tabindex="0">
              <div class="dt-responsive table-responsive">
                <table class="table mb-0" id="additional-job-order-table">
                  <thead>
                    <tr>
                      <th>OS Number</th>
                      <th>Job Order</th>
                      <th>Contractor</th>
                      <th>Work Center</th>
                      <th class="text-end"></th>
                    </tr>
                  </thead>
                  <tbody></tbody>
                </table>
              </div>
            </div>
            <div class="tab-pane fade" id="internal-additional-job-order-pane" role="tabpanel" aria-labelledby="internal-additional-job-order" tabindex="0">
              <div class="dt-responsive table-responsive w-100">
                <table class="table w-100 mb-0" id="internal-additional-job-order-table">
                  <thead>
                    <tr>
                      <th>Type</th>
                      <th>OS Number</th>
                      <th>Job Order</th>
                      <th>Contractor</th>
                      <th>Work Center</th>
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
                '. $userModel->generateLogNotes('stock_transfer_advice', $stockTransferAdviceID) .'
              </div>
            </div>
          </div>
        </div>
        </div>';
?>

<div class="offcanvas offcanvas-end" tabindex="-1" id="add-part-offcanvas" aria-labelledby="add-part-offcanvas-label">
    <div class="offcanvas-header">
        <h2 id="add-part-offcanvas-label" style="margin-bottom:-0.5rem">Add Part</h2>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
        <div class="row mb-4 text-center">
            <form id="add-part-form" method="post" action="#">
                <table id="add-part-table" class="table table-hover nowrap w-100 dataTable">
                    <thead>
                        <tr>
                            <th>Part</th>
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

<div class="offcanvas offcanvas-end" tabindex="-1" id="add-job-order-offcanvas" aria-labelledby="add-job-order-offcanvas-label">
    <div class="offcanvas-header">
        <h2 id="add-job-order-offcanvas-label" style="margin-bottom:-0.5rem">Link Job Order</h2>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
        <div class="row mb-4">
            <form id="add-job-order-form" method="post" action="#">
                <table id="add-job-order-table" class="table table-hover nowrap w-100 dataTable">
                    <thead>
                        <tr>
                            <th>Customer</th>
                            <th>Reference ID</th>
                            <th>Job Order</th>
                            <th class="all">Link</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </form> 
        </div>
        <div class="row">
            <div class="col-lg-12">
                <button type="submit" class="btn btn-primary" id="submit-add-job-order" form="add-job-order-form">Submit</button>
                <button class="btn btn-light-danger" data-bs-dismiss="offcanvas"> Close </button>
            </div>
        </div>
    </div>
</div>

<div class="offcanvas offcanvas-end" tabindex="-1" id="add-additional-job-order-offcanvas" aria-labelledby="add-additional-job-order-offcanvas-label">
    <div class="offcanvas-header">
        <h2 id="add-additional-job-order-offcanvas-label" style="margin-bottom:-0.5rem">Link Additional Job Order</h2>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
        <div class="row mb-4">
            <form id="add-additional-job-order-form" method="post" action="#">
                <table id="add-additional-job-order-table" class="table table-hover nowrap w-100 dataTable">
                    <thead>
                        <tr>
                            <th>Customer</th>
                            <th>Reference ID</th>
                            <th>Additional Job Order</th>
                            <th class="all">Link</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </form> 
        </div>
        <div class="row">
            <div class="col-lg-12">
                <button type="submit" class="btn btn-primary" id="submit-additional-job-order" form="add-additional-job-order-form">Submit</button>
                <button class="btn btn-light-danger" data-bs-dismiss="offcanvas"> Close </button>
            </div>
        </div>
    </div>
</div>

 <div>
    <div class="offcanvas offcanvas-end" tabindex="-1" id="cancel-transaction-offcanvas" aria-labelledby="cancel-transaction-offcanvas-label">
      <div class="offcanvas-header">
        <h2 id="cancel-transaction-offcanvas-label" style="margin-bottom:-0.5rem">Cancel Transaction</h2>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
      </div>
    <div class="offcanvas-body">
      <div class="row">
        <div class="col-lg-12">
          <form id="cancel-transaction-form" method="post" action="#">
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
          <button type="submit" class="btn btn-primary" id="submit-cancel-transaction" form="cancel-transaction-form">Submit</button>
          <button class="btn btn-light-danger" data-bs-dismiss="offcanvas"> Close </button>
        </div>
      </div>
    </div>
  </div>

 <div>
    <div class="offcanvas offcanvas-end" tabindex="-1" id="draft-transaction-offcanvas" aria-labelledby="draft-transaction-offcanvas-label">
      <div class="offcanvas-header">
        <h2 id="draft-transaction-offcanvas-label" style="margin-bottom:-0.5rem">Set To Draft Transaction</h2>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
      </div>
    <div class="offcanvas-body">
      <div class="row">
        <div class="col-lg-12">
          <form id="draft-transaction-form" method="post" action="#">
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
          <button type="submit" class="btn btn-primary" id="submit-draft-transaction" form="draft-transaction-form">Submit</button>
          <button class="btn btn-light-danger" data-bs-dismiss="offcanvas"> Close </button>
        </div>
      </div>
    </div>
  </div>

 <div>
    <div class="offcanvas offcanvas-end" tabindex="-1" id="approve-transaction-offcanvas" aria-labelledby="approve-transaction-offcanvas-label">
      <div class="offcanvas-header">
        <h2 id="approve-transaction-offcanvas-label" style="margin-bottom:-0.5rem">Validate Transaction</h2>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
      </div>
    <div class="offcanvas-body">
      <div class="row">
        <div class="col-lg-12">
          <form id="approve-transaction-form" method="post" action="#">
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
          <button type="submit" class="btn btn-primary" id="submit-approve-transaction" form="approve-transaction-form">Submit</button>
          <button class="btn btn-light-danger" data-bs-dismiss="offcanvas"> Close </button>
        </div>
      </div>
    </div>
  </div>

<div>
    <div class="offcanvas offcanvas-end" tabindex="-1" id="part-cart-offcanvas" aria-labelledby="part-cart-offcanvas-label">
      <div class="offcanvas-header">
        <h2 id="part-cart-offcanvas-label" style="margin-bottom:-0.5rem">Stock Transfer Advice Item</h2>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
      </div>
    <div class="offcanvas-body">
      <div class="row">
        <div class="col-lg-12">
          <form id="part-item-form" method="post" action="#">
            <input type="hidden" id="stock_transfer_advice_cart_id" name="stock_transfer_advice_cart_id">
            <input type="hidden" id="part_id" name="part_id">
            <div class="form-group row">
              <div class="col-lg-12 mt-3 mt-lg-0">
                <label class="form-label">Part</label>
                <input type="text" class="form-control" id="part_name" name="part_name" readonly>
              </div>
            </div>
            <div class="form-group row">
              <div class="col-lg-6 mt-3 mt-lg-0">
                <label class="form-label">Price <span class="text-danger">*</span></label>
                <input type="number" class="form-control" id="part_price" name="part_price" min="0.01" step="0.01">
              </div>
                <div class="col-lg-6 mt-3 mt-lg-0">
                    <label class="form-label">Quantity <span class="text-danger">*</span></label>
                    <input type="number" class="form-control" id="quantity" name="quantity" min="0.01" step="0.01">
                </div>
            </div>
            <div class="form-group row">
              <div class="col-lg-12 mt-3 mt-lg-0">
                <label class="form-label">Remarks</label>
                <textarea class="form-control" id="item_remarks" name="item_remarks" maxlength="1000"></textarea>
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

<div>
    <div class="offcanvas offcanvas-end" tabindex="-1" id="replacement-offcanvas" aria-labelledby="replacement-offcanvas-label">
      <div class="offcanvas-header">
        <h2 id="replacement-offcanvas-label" style="margin-bottom:-0.5rem">Replacement</h2>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
      </div>
    <div class="offcanvas-body">
      <div class="row">
        <div class="col-lg-12">
          <form id="replacement-form" method="post" action="#">
            <input type="hidden" id="stock_transfer_advice_replacement_id" name="stock_transfer_advice_replacement_id">
            <div class="form-group row">
              <div class="col-lg-12 mt-3 mt-lg-0">
                <label class="form-label">Product</label>
                <select class="form-control offcanvas-select2" name="part_replace" id="part_replace">
                  <option value="">--</option>
                  <option value="From">From - <?php echo $productName1; ?></option>
                  <option value="To">To - <?php echo $productName2; ?></option>
                </select>
              </div>
            </div>
            
            <div class="form-group row">
              <div class="col-lg-12 mt-3 mt-lg-0">
                <label class="form-label">Remarks</label>
                <textarea class="form-control" id="replacement_remarks" name="replacement_remarks" maxlength="1000"></textarea>
              </div>
            </div>
          </form>
        </div>
      </div>
      <div class="row">
        <div class="col-lg-12">
          <button type="submit" class="btn btn-primary" id="submit-replacement" form="replacement-form">Submit</button>
          <button class="btn btn-light-danger" data-bs-dismiss="offcanvas"> Close </button>
        </div>
      </div>
    </div>
  </div>