<div class="row">
  <div class="col-lg-12">
    <div class="card table-card">
      <div class="card-header">
        <div class="row align-items-center">
          <div class="col-sm-6">
            <h5>SPAF List</h5>
          </div>
          <div class="col-sm-6 text-sm-end mt-3 mt-sm-0">
            <?php
              if($spafCreateAccess['total'] > 0 || $spafDeleteAccess['total'] > 0){
                $action = '';
                              
                  if($spafDeleteAccess['total'] > 0){
                    $action .= '<div class="btn-group m-r-10">
                                  <button type="button" class="btn btn-outline-secondary dropdown-toggle d-none action-dropdown" data-bs-toggle="dropdown" aria-expanded="false">Action</button>
                                  <ul class="dropdown-menu dropdown-menu-end">
                                    <li><button class="dropdown-item" type="button" id="delete-spaf">Delete SPAF</button></li>
                                  </ul>
                                </div>';
                  }
                              
                echo $action;
              }
            ?>
           </div>
        </div>
      </div>
      <div class="card-body">
        <div class="table-responsive dt-responsive">
          <table id="spaf-table" class="table table-hover nowrap w-100">
            <thead>
              <tr>
                <th class="all">
                  <div class="form-check">
                    <input class="form-check-input" id="datatable-checkbox" type="checkbox">
                  </div>
                </th>
                <th>SPAF Reference No.</th>
                <th>Customer</th>
                <th>Unit</th>
                <th>Unit Price</th>
                <th>Dealer's Discount</th>
                <th>Total</th>
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

              <div class="col-md-12">
                <input type="hidden" name="spaf_id" id="spaf_id">
                <label for="cif_id" class="form-label fw-medium">CIF</label>
                <select class="form-select modal-select2" name="cif_id" id="cif_id">
                  <option value="">Select CIF</option>
                   <?= $leadModel->generateCIFOptions(); ?>
                </select>
              </div>

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

<div>
  <div class="offcanvas offcanvas-end" tabindex="-1" id="filter-offcanvas" aria-labelledby="filter-offcanvas-label">
    <div class="offcanvas-header">
      <h2 id="filter-offcanvas-label" style="margin-bottom:-0.5rem">Filter</h2>
      <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
      <div class="row">
        <div class="col-lg-12">
          <ul class="list-group list-group-flush mb-3">
            <li class="list-group-item px-2 py-2">
              <a class="btn border-0 px-0 text-start w-100" data-bs-toggle="collapse" href="#status-filter-collapse"><div class="float-end"><i class="ti ti-chevron-down"></i></div>
                Status
              </a>
              <div class="collapse " id="status-filter-collapse">
                <div class="py-3">
                  <div class="form-check my-2">
                    <input class="form-check-input status-filter" type="checkbox" id="status-draft" value="Draft" checked/>
                    <label class="form-check-label" for="status-draft">Draft</label>
                  </div>
                  <div class="form-check my-2">
                    <input class="form-check-input status-filter" type="checkbox" id="status-for-approval" value="For Approval"/>
                    <label class="form-check-label" for="status-for-approval">For Approval</label>
                  </div>
                  <div class="form-check my-2">
                    <input class="form-check-input status-filter" type="checkbox" id="status-approved" value="Approved"/>
                    <label class="form-check-label" for="status-approved">Approved</label>
                  </div>
                  <div class="form-check my-2">
                    <input class="form-check-input status-filter" type="checkbox" id="status-cancelled" value="Cancelled"/>
                    <label class="form-check-label" for="status-cancelled">Cancelled</label>
                  </div>
                </div>
              </div>
            </li>
          </ul>
        </div>
      </div>
      <div class="row">
        <div class="col-lg-12">
          <button type="submit" class="btn btn-primary" id="submit-filter">Apply</button>
          <button class="btn btn-light-danger" data-bs-dismiss="offcanvas"> Close </button>
        </div>
      </div>
    </div>
  </div>
</div>