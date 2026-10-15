<div class="row">
  <div class="col-lg-12">
    <div class="card table-card">
      <div class="card-header">
        <div class="row align-items-center">
          <div class="col-sm-6">
            <h5>Pricing Threshold List</h5>
          </div>
          <div class="col-sm-6 text-sm-end mt-3 mt-sm-0">
            <?php
              if($pricingThresholdCreateAccess['total'] > 0 || $pricingThresholdDeleteAccess['total'] > 0){
                $action = '';
                              
                  if($pricingThresholdDeleteAccess['total'] > 0){
                    $action .= '<div class="btn-group m-r-10">
                                  <button type="button" class="btn btn-outline-secondary dropdown-toggle d-none action-dropdown" data-bs-toggle="dropdown" aria-expanded="false">Action</button>
                                  <ul class="dropdown-menu dropdown-menu-end">
                                    <li><button class="dropdown-item" type="button" id="delete-pricing-threshold">Delete Pricing Threshold</button></li>
                                  </ul>
                                </div>';
                  }

                if($pricingThresholdCreateAccess['total'] > 0){
                  $action .= '<button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#pricing-threshold-modal">
                                Create
                              </button>';
                }
                              
                echo $action;
              }
            ?>
              <button type="button" class="btn btn-warning" data-bs-toggle="offcanvas" data-bs-target="#filter-offcanvas">
                Filter
              </button>
           </div>
        </div>
      </div>
      <div class="card-body">
        <div class="table-responsive dt-responsive">
          <table id="pricing-threshold-table" class="table table-hover nowrap w-100">
            <thead>
              <tr>
                <th class="all">
                  <div class="form-check">
                    <input class="form-check-input" id="datatable-checkbox" type="checkbox">
                  </div>
                </th>
                <th>Category</th>
                <th>Year Model</th>
                <th>Transmission Type</th>
                <th>Length</th>
                <th>Price Threshold</th>
                <th>Discount Threshold</th>
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



<div class="modal fade" id="pricing-threshold-modal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable modal-md">
    <div class="modal-content border-0 shadow">
      
      <div class="modal-header border-bottom">
        <h5 class="modal-title fw-bold mb-0">Save Pricing Threshold</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <div class="modal-body p-4">
        <form id="pricing-threshold-form">
          <!-- Section 1: Core Details -->
          <div class="rounded-2 border mb-4">
            <h6 class="text-uppercase text-muted fw-bold small mb-3">Pricing Threshold Details</h6>

            <div class="row g-3">

              <div class="col-md-6">
                <input type="hidden" name="quotation_standard_pricing_id" id="quotation_standard_pricing_id">
                <label for="product_subcategory_id" class="form-label fw-medium">Category</label>
                <select class="form-select modal-select2" name="product_subcategory_id" id="product_subcategory_id">
                  <option value="">Select Category</option>
                  <?= $productSubcategoryModel->generateProductSubcategoryOptions(); ?>
                </select>
              </div>

              <div class="col-md-6">
                <label for="transmission_type" class="form-label fw-medium">Transmission Type</label>
                <select class="form-select modal-select2" name="transmission_type" id="transmission_type">
                  <option value="">Select Transmission Type</option>
                  <option value="Manual">Manual</option>
                  <option value="Automatic">Automatic</option>
                  <option value="Hybrid">Hybrid</option>
                  <option value="CVT">CVT</option>
                  <option value="Tiptronic">Tiptronic</option>
                  <option value="Dual Clutch">Dual Clutch</option>
                  <option value="Semi-Automatic">Semi-Automatic</option>
                  <option value="Electric">Electric</option>
                </select>
              </div>

              <div class="col-md-6">
                <label for="length" class="form-label fw-medium">Length</label>
                <div class="input-group">
                    <input type="number" class="form-control" id="length" name="length" min="0" step="0.01">
                    <select class="form-control" name="length_unit" id="length_unit">
                      <?php echo $unitModel->generateUnitByShortNameOptions(1); ?>
                    </select>
                  </div>
              </div>

              <div class="col-md-6">
                <label for="year_model" class="form-label fw-medium">Year Model</label>
                <select class="form-select modal-select2" name="year_model" id="year_model">
                  <option value="">Select Year Model</option>
                  <?= $systemModel->generateYearOptions(date('Y', strtotime('-100 years')), date('Y', strtotime('+1 year'))); ?>
                </select>
              </div>

              <div class="col-md-6">
                <label for="price_threshold" class="form-label fw-medium">Price Threshold</label>
                <div class="input-group">
                  <span class="input-group-text">₱</span>
                  <input type="number" step="0.01" class="form-control" id="price_threshold" name="price_threshold" value="0.00">
                </div>
              </div>

              <div class="col-md-6">
                <label for="discount_threshold" class="form-label fw-medium">Discount Threshold</label>
                <div class="input-group">
                  <span class="input-group-text">₱</span>
                  <input type="number" step="0.01" class="form-control" id="discount_threshold" name="discount_threshold" value="0.00">
                </div>
              </div>
            </div>
          </div>

        </form>
      </div>

      <div class="modal-footer border-top">
        <button type="button" class="btn btn-outline-secondary px-4" data-bs-dismiss="modal">Cancel</button>
        <button type="submit" form="pricing-threshold-form" id="submit-pricing-threshold" class="btn btn-primary px-4">
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
              <a class="btn border-0 px-0 text-start w-100" data-bs-toggle="collapse" href="#product-subcategory-filter-collapse"><div class="float-end"><i class="ti ti-chevron-down"></i></div>
                Product Subcategory
              </a>
              <div class="collapse " id="product-subcategory-filter-collapse">
                <div class="py-3">
                  <?php echo $productSubcategoryModel->generateProductSubcategoryCheckBox(); ?>
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