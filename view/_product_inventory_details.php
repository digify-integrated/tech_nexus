<?php
  $getProductInventory = $productInventoryReportModel->getProductInventory($productInventoryID);
  $close_date = $systemModel->checkDate('empty', $getProductInventory['close_date'], '', 'm/d/Y h:i:s A', '');
?>
<div class="row">
  <div class="col-lg-12">
    <div class="card">
      <div class="card-header">
        <div class="row align-items-center">
          <div class="col-md-6">
            <h5>Product Inventory Report</h5>
          </div>
          <div class="col-md-6 text-sm-end mt-3 mt-sm-0">
            <?php 
              if ($scanProductInventory['total'] > 0 && empty($close_date)) {
                echo '<button data-pc-animate="fade-in-scale" type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#scanModal" id="scan-modal">Scan</button>';
              }
            ?>
          </div>
        </div>
      </div>
      <div class="card-body">
        <div class="table-responsive dt-responsive">
          <table id="inventory-report-batch-table" class="table table-hover nowrap w-100">
            <thead>
              <tr>
                <th>Product</th>
                <th>Description</th>
                <th>Scan Status</th>
                <th>Scan Date</th>
                <th>Scan By</th>
                <th>Remarks</th>
                <th>Actions</th>
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
  <div class="col-lg-6">
    <div class="card">
      <div class="card-header">
        <div class="row align-items-center">
          <div class="col-md-6">
            <h5>Product Inventory Scan History</h5>
          </div>
        </div>
      </div>
      <div class="card-body">
        <div class="table-responsive dt-responsive">
          <table id="inventory-report-scan-history-table" class="table table-hover nowrap w-100">
            <thead>
              <tr>
                <th>Product</th>
                <th>Scan Date</th>
                <th>Scan By</th>
              </tr>
            </thead>
            <tbody></tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
  <div class="col-lg-6">
    <div class="card">
      <div class="card-header">
        <div class="row align-items-center">
          <div class="col-md-6">
            <h5>Product Inventory Scan Excess</h5>
          </div>
        </div>
      </div>
      <div class="card-body">
        <div class="table-responsive dt-responsive">
          <table id="inventory-report-scan-excess-table" class="table table-hover nowrap w-100">
            <thead>
              <tr>
                <th>Product</th>
                <th>Scan Date</th>
                <th>Scan By</th>
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
            <h5>Product Inventory Unaccounted</h5>
          </div>
          <div class="col-md-6 text-sm-end mt-3 mt-sm-0">
            <?php 
              if(empty($close_date)){
                echo '  <div class="previous me-2" id="add-product-inventory-additional-button">
                          <button class="btn btn-primary me-2" type="button" data-bs-toggle="offcanvas" data-bs-target="#product-inventory-additional-offcanvas" aria-controls="product-inventory-additional-offcanvas" id="add-product-inventory-additional">Add Unaccounted</button>
                        </div>';
              }
            ?>
          </div>
        </div>
      </div>
      <div class="card-body">
        <div class="table-responsive dt-responsive">
          <table id="inventory-report-scan-additional-table" class="table table-hover nowrap w-100">
            <thead>
              <tr>
                <th>Stock Number</th>
                <th>Created By</th>
                <th>Created Date</th>
                <th>Remarks</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody></tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>

<div>
  <div class="offcanvas offcanvas-end" tabindex="-1" id="product-inventory-additional-offcanvas" aria-labelledby="product-inventory-additional-offcanvas-label">
    <div class="offcanvas-header">
      <h2 id="product-inventory-additional-offcanvas-label" style="margin-bottom:-0.5rem">Product Inventory Unaccounted</h2>
      <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
      <div class="row">
        <div class="col-lg-12">
          <form id="product-inventory-additional-form" method="post" action="#">
            <div class="form-group row">
              <div class="col-lg-12">
                <label class="form-label">Stock Number <span class="text-danger">*</span></label>
                <input type="hidden" id="product_inventory_scan_additional_id" name="product_inventory_scan_additional_id">
                <input type="text" class="form-control text-uppercase" id="stock_number" name="stock_number" maxlength="100" autocomplete="off">
              </div>
            </div>
            <div class="form-group row">
              <div class="col-lg-12">
                <label class="form-label">Remarks</label>
                <textarea class="form-control" id="additional_remarks" name="additional_remarks" maxlength="500"></textarea>
              </div>
            </div>
          </form>
        </div>
      </div>
      <div class="row">
        <div class="col-lg-12">
          <button type="submit" class="btn btn-primary" id="submit-product-inventory-additional" form="product-inventory-additional-form">Submit</button>
          <button class="btn btn-light-danger" data-bs-dismiss="offcanvas"> Close </button>
        </div>
      </div>
  </div>
</div>

<div>
  <div class="offcanvas offcanvas-end" tabindex="-1" id="product-inventory-batch-offcanvas" aria-labelledby="product-inventory-batch-offcanvas-label">
    <div class="offcanvas-header">
      <h2 id="product-inventory-batch-offcanvas-label" style="margin-bottom:-0.5rem">Tag As Missing</h2>
      <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
      <div class="row">
        <div class="col-lg-12">
          <form id="product-inventory-batch-form" method="post" action="#">
            <div class="form-group row">
              <div class="col-lg-12">
                <label class="form-label">Remarks</label>
                <input type="hidden" id="product_inventory_batch_id" name="product_inventory_batch_id">
                <textarea class="form-control" id="remarks" name="remarks" maxlength="500"></textarea>
              </div>
            </div>
          </form>
        </div>
      </div>
      <div class="row">
        <div class="col-lg-12">
          <button type="submit" class="btn btn-primary" id="submit-product-inventory-batch" form="product-inventory-batch-form">Submit</button>
          <button class="btn btn-light-danger" data-bs-dismiss="offcanvas"> Close </button>
        </div>
      </div>
  </div>
</div>

<div>
  <div class="offcanvas offcanvas-end" tabindex="-1" id="product-inventory-batch-offcanvas" aria-labelledby="product-inventory-batch-offcanvas-label">
    <div class="offcanvas-header">
      <h2 id="product-inventory-batch-offcanvas-label" style="margin-bottom:-0.5rem">Tag As Missing</h2>
      <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
      <div class="row">
        <div class="col-lg-12">
          <form id="product-inventory-batch-form" method="post" action="#">
            <div class="form-group row">
              <div class="col-lg-12">
                <label class="form-label">Remarks</label>
                <input type="hidden" id="product_inventory_batch_id" name="product_inventory_batch_id">
                <input type="hidden" id="remarks_type" name="remarks_type">
                <textarea class="form-control" id="remarks" name="remarks" maxlength="500"></textarea>
              </div>
            </div>
          </form>
        </div>
      </div>
      <div class="row">
        <div class="col-lg-12">
          <button type="submit" class="btn btn-primary" id="submit-product-inventory-batch" form="product-inventory-batch-form">Submit</button>
          <button class="btn btn-light-danger" data-bs-dismiss="offcanvas"> Close </button>
        </div>
      </div>
  </div>
</div>

<div class="modal fade modal-animate" id="scanModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Scan Product</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
            <div class="modal-body">
              <div id="reader" class="w-full"></div>
            </div>
            <audio id="scanSound" src="./assets/audio/scan.mp3"></audio>
            <div class="modal-footer">
              <button type="button" id="closeScannerBtn" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        </div>
    </div>
</div>

<div class="modal fade modal-animate" id="productModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">Product Details</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <div class="row">
                <div class="col-md-12">
                  <div class="text-center mb-3">
                    <img src="<?php echo DEFAULT_AVATAR_IMAGE; ?>" alt="User Image" id="product_thumbnail" class="img-fluid wid-100 hei-100">
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <div class="form-group row">
                    <label class="col-lg-3 col-form-label">Stock Number</label>
                    <div class="col-lg-9">
                      <input type="text" class="form-control" id="product_stock_number" autocomplete="off" readonly>
                    </div>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <div class="form-group row">
                    <label class="col-lg-3 col-form-label">Description</label>
                    <div class="col-lg-9">
                      <input type="text" class="form-control" id="description" autocomplete="off" readonly>
                    </div>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <div class="form-group row">
                    <label class="col-lg-3 col-form-label">Category</label>
                    <div class="col-lg-9">
                      <input type="text" class="form-control" id="category" autocomplete="off" readonly>
                    </div>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <div class="form-group row">
                    <label class="col-lg-3 col-form-label">Engine No.</label>
                    <div class="col-lg-9">
                      <input type="text" class="form-control" id="engine_no" autocomplete="off" readonly>
                    </div>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <div class="form-group row">
                    <label class="col-lg-3 col-form-label">Chassis No.</label>
                    <div class="col-lg-9">
                      <input type="text" class="form-control" id="chassis_no" autocomplete="off" readonly>
                    </div>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <div class="form-group row">
                    <label class="col-lg-3 col-form-label">Body Type</label>
                    <div class="col-lg-9">
                      <input type="text" class="form-control" id="body_type" autocomplete="off" readonly>
                    </div>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <div class="form-group row">
                    <label class="col-lg-3 col-form-label">Color</label>
                    <div class="col-lg-9">
                      <input type="text" class="form-control" id="color" autocomplete="off" readonly>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        </div>
    </div>
</div>