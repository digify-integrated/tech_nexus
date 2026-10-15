<?php
$cifDetails = $leadModel->getCif($cifID);
$status = $cifDetails['status'];

?>

<input type="hidden" id="cif_status" value="<?php echo $status ?>">

<div class="row">
  <div class="col-lg-12">
    <div class="card">
      <div class="card-header">
        <div class="row align-items-center">
          <div class="col-md-6">
            <h5>CIF</h5>
          </div>
          <div class="col-md-6 text-sm-end mt-3 mt-sm-0">
            <?php          
              if ($status == 'On-Process' || $status == 'Completed' || $status == 'Cancelled') {                  
                $dropdown = '<div class="btn-group m-r-5">
                                <button type="button" class="btn btn-outline-secondary dropdown-toggle form-details" data-bs-toggle="dropdown" aria-expanded="false">
                                    Action
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end">';
                            
                                  if ($status == 'On-Process') {
                                      $dropdown .= '<li><button class="dropdown-item" type="button" id="cancel-cif">Cancel CIF</button></li>';
                                      $dropdown .= '<li><button class="dropdown-item" type="button" id="submit-cif">Submit CIF</button></li>';
                                      $dropdown .= '<li><a href="print-inquiry-cif.php?id=' . $cifID . '"" class="dropdown-item" target="_blank">Print CIF</a></li>';
                                  }
                            
                                  if ($status == 'Completed' || $status == 'Cancelled') {
                                      $dropdown .= '<li><a href="print-inquiry-cif.php?id=' . $cifID . '"" class="dropdown-item" target="_blank">Print CIF</a></li>';
                                  }
                        
                $dropdown .= '</ul>
                            </div>';
                    
                echo $dropdown;
              }
            ?>
          </div>
        </div>
      </div>
      <div class="card-body">
        <div class="form-group row">
          <label class="col-lg-2 col-form-label">Reference No</label>
          <div class="col-lg-4">
            <label class="col-form-label form-details fw-normal" id="reference_no_label"></label>
          </div>
          <label class="col-lg-2 col-form-label">Customer</label>
          <div class="col-lg-4">
            <label class="col-form-label form-details fw-normal" id="customer_label"></label>
          </div>
          <label class="col-lg-2 col-form-label">Product</label>
          <div class="col-lg-4">
            <label class="col-form-label form-details fw-normal" id="product_label"></label>
          </div>
          <label class="col-lg-2 col-form-label">Company</label>
          <div class="col-lg-4">
            <label class="col-form-label form-details fw-normal" id="company_label"></label>
          </div>
          <label class="col-lg-2 col-form-label">Add-On</label>
          <div class="col-lg-4">
            <label class="col-form-label form-details fw-normal" id="add_on_label"></label>
          </div>
          <label class="col-lg-2 col-form-label">Remarks</label>
          <div class="col-lg-4">
            <label class="col-form-label form-details fw-normal" id="remarks_label"></label>
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
          <div class="col-md-6">
            <h5>Suppliers</h5>
          </div>
          <div class="col-md-6 text-sm-end mt-3 mt-sm-0">
            <?php
              if($status == 'On-Process'){
                echo '<button class="btn btn-warning" type="button" data-bs-toggle="modal" data-bs-target="#supplier-modal" aria-controls="supplier-modal" id="supplier">Add Supplier</button>';
              }
            ?>
            
          </div>
        </div>
      </div>
      <div class="card-body">
        <form id="quotation-form" enctype="multipart/form-data">
          <div id="supplier-quotation-container"></div>
        </form>
      </div>
      <div class="card-footer">
        <?php
              if($status == 'On-Process'){
                echo '<button type="submit" form="quotation-form" id="submit-quotation" class="btn btn-primary px-4">
                  Save
                </button>';
              }
            ?>
           
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="supplier-modal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable modal-lg">
    <div class="modal-content border-0 shadow">
      
      <div class="modal-header border-bottom">
        <h5 class="modal-title fw-bold mb-0">Add Supplier</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <div class="modal-body p-4">
        <form id="supplier-form">
          <!-- Section 1: Core Details -->
          <div class="rounded-2 border mb-4">

              <div class="row g-3">

                <div class="col-md-12">
                  <label for="supplier_id" class="form-label fw-medium">Supplier</label>
                  <select class="form-select modal-select2" name="supplier_id" id="supplier_id">
                    <option value="">Select Supplier</option>
                    <?= $supplierModel->generateSupplierOptions(); ?>
                  </select>
                </div>
            </div>
          </div>

        </form>
      </div>

      <div class="modal-footer border-top">
        <button type="button" class="btn btn-outline-secondary px-4" data-bs-dismiss="modal">Cancel</button>
        <button type="submit" form="supplier-form" id="submit-supplier" class="btn btn-primary px-4">
          Save
        </button>
      </div>

    </div>
  </div>
</div>

<div class="modal fade" id="spaf-modal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable modal-md">
    <div class="modal-content border-0 shadow">
      
      <div class="modal-header border-bottom">
        <h5 class="modal-title fw-bold mb-0">SPAF</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <div class="modal-body p-4">
        <form id="spaf-form">
          <!-- Section 1: Core Details -->
          <div class="rounded-2 border mb-4">
            <h6 class="text-uppercase text-muted fw-bold small mb-3">SPAF Details</h6>

            <div class="row g-3">
              <input type="hidden" name="spaf_id" id="spaf_id">
              <input type="hidden" name="spaf_supplier_id" id="spaf_supplier_id">

              <div class="col-md-6">
                <label for="unit_price" class="form-label fw-medium">Unit Price</label>
                <div class="input-group">
                  <span class="input-group-text">₱</span>
                  <input type="number" step="0.01" class="form-control" id="unit_price" name="unit_price" value="0.00">
                </div>
              </div>

              <div class="col-md-6">
                <label for="dealer_discount" class="form-label fw-medium">Dealer Discount</label>
                <div class="input-group">
                  <span class="input-group-text">₱</span>
                  <input type="number" step="0.01" class="form-control" id="dealer_discount" name="dealer_discount" value="0.00">
                </div>
              </div>

              <div class="col-md-12">
                <label for="net_price" class="form-label fw-medium">Total</label>
                <div class="input-group">
                  <span class="input-group-text">₱</span>
                  <input type="number" step="0.01" class="form-control" id="net_price" name="net_price" value="0.00" readonly>
                </div>
              </div>

              <div class="col-md-12">
                <label for="remarks" class="form-label fw-medium">Remarks</label>
                <textarea class="form-control" id="remarks" name="remarks"></textarea>
              </div>
            </div>
          </div>

        </form>
      </div>

      <div class="modal-footer border-top">
        <button type="button" class="btn btn-outline-secondary px-4" data-bs-dismiss="modal">Cancel</button>
        <button type="submit" form="spaf-form" id="submit-spaf" class="btn btn-primary px-4">
          Save
        </button>
      </div>

    </div>
  </div>
</div>