<div class="row g-4">

  <!-- ==========================================
       TOP SECTION: LEAD DETAILS & INTERNAL NOTES
  =========================================== -->
  <div class="col-lg-8">
    <div class="card shadow-sm border-0 h-100">

      <!-- HEADER -->
      <div class="card-header py-3 border-bottom d-flex justify-content-between align-items-center">
        <div>
          <h5 class="mb-0 fw-semibold">Lead Details</h5>
          <small class="text-muted">Manage lead information and contact profile</small>
        </div>
        <div class="d-flex gap-2">
          <?php if ($leadWriteAccess['total'] > 0): ?>
            <button type="submit" form="lead-form" id="submit-data" class="btn btn-success">
              <i class="feather icon-save me-1"></i> Save
            </button>
            <button type="button" id="discard-update" class="btn btn-outline-secondary">
              Discard
            </button>
          <?php endif; ?>

          <?php if ($leadCreateAccess['total'] > 0): ?>
            <a href="lead-monitoring.php?new" class="btn btn-primary">
              <i class="feather icon-plus me-1"></i> New
            </a>
          <?php endif; ?>
        </div>
      </div>

      <!-- FORM BODY -->
      <div class="card-body">
        <?php $isReadOnly = ($leadWriteAccess['total'] == 0) ? 'disabled' : ''; ?>

        <form id="lead-form" method="post">
          
          <!-- SECTION 1: LEAD METADATA -->
          <div class="d-flex align-items-center mb-3">
            <span class="badge bg-warning text-dark me-2">1</span>
            <h6 class="mb-0 fw-semibold text-uppercase text-muted fs-7">Lead Overview</h6>
          </div>
          <div class="row g-3">
            <div class="col-md-6">
              <label for="inquiry_type_id" class="form-label small fw-medium">Inquiry Type</label>
              <select class="form-select select2" name="inquiry_type_id" id="inquiry_type_id" <?= $isReadOnly ?>>
                <option value="">Select Inquiry Type</option>
                <?= $inquiryTypeModel->generateInquiryTypeOptions(); ?>
              </select>
            </div>

            <div class="col-md-6">
              <label for="inquiry_date" class="form-label small fw-medium">Inquiry Date</label>
              <div class="input-group">
                <input type="text" class="form-control future-date-restricted-datepicker" id="inquiry_date" name="inquiry_date" placeholder="YYYY-MM-DD" autocomplete="off" <?= $isReadOnly ?>>
                <span class="input-group-text">
                  <i class="feather icon-calendar"></i>
                </span>
              </div>
            </div>

            <div class="col-md-4">
              <label for="lead_status_id" class="form-label small fw-medium">Status</label>
              <select class="form-select select2" name="lead_status_id" id="lead_status_id" <?= $isReadOnly ?>>
                <option value="">Select Status</option>
                <?= $leadStatusModel->generateLeadStatusOptions('Lead'); ?>
              </select>
            </div>

            <div class="col-md-4">
              <label for="lead_source_id" class="form-label small fw-medium">Source</label>
              <select class="form-select select2" name="lead_source_id" id="lead_source_id" <?= $isReadOnly ?>>
                <option value="">Select Lead Source</option>
                <?= $leadSourceModel->generateLeadSourceOptions(); ?>
              </select>
            </div>

            <div class="col-md-4">
              <label for="lead_priority" class="form-label small fw-medium">Priority</label>
              <select class="form-select select2" name="lead_priority" id="lead_priority" <?= $isReadOnly ?>>
                <option value="">Select Priority</option>
                <option value="Hot">🔥 Hot</option>
                <option value="Warm">🌤️ Warm</option>
                <option value="Cold">❄️ Cold</option>
              </select>
            </div>
          </div>

          <!-- SECTION 2: CUSTOMER & CONTACT INFO -->
          <div class="d-flex align-items-center mt-4 mb-3">
            <span class="badge bg-primary me-2">2</span>
            <h6 class="mb-0 fw-semibold text-uppercase text-muted fs-7">Customer & Contact Information</h6>
          </div>
          <div class="row g-3">
            <div class="col-md-4">
              <label for="first_name" class="form-label small fw-medium">First Name</label>
              <input class="form-control" name="first_name" id="first_name" placeholder="First Name" <?= $isReadOnly ?>>
            </div>

            <div class="col-md-4">
              <label for="middle_name" class="form-label small fw-medium">Middle Name</label>
              <input class="form-control" name="middle_name" id="middle_name" placeholder="Middle Name" <?= $isReadOnly ?>>
            </div>

            <div class="col-md-4">
              <label for="last_name" class="form-label small fw-medium">Last Name</label>
              <input class="form-control" name="last_name" id="last_name" placeholder="Last Name" <?= $isReadOnly ?>>
            </div>

            <div class="col-md-6">
              <label for="corporate_name" class="form-label small fw-medium">Company Name</label>
              <input class="form-control" name="corporate_name" id="corporate_name" placeholder="Company Name (Optional)" <?= $isReadOnly ?>>
            </div>

            <div class="col-md-6">
              <label for="gender_id" class="form-label small fw-medium">Gender</label>
              <select class="form-select select2" name="gender_id" id="gender_id" <?= $isReadOnly ?>>
                <option value="">Select Gender</option>
                <?= $genderModel->generateGenderOptions(); ?>
              </select>
            </div>

            <div class="col-md-4">
              <label for="email" class="form-label small fw-medium">Email Address</label>
              <input type="email" class="form-control" name="email" id="email" placeholder="name@example.com" <?= $isReadOnly ?>>
            </div>

            <div class="col-md-4">
              <label for="phone" class="form-label small fw-medium">Phone Number</label>
              <input class="form-control" name="phone" id="phone" placeholder="+123456789" <?= $isReadOnly ?>>
            </div>

            <div class="col-md-4">
              <label for="social_media_account" class="form-label small fw-medium">Social Media</label>
              <input class="form-control" name="social_media_account" id="social_media_account" placeholder="Handle / Profile Link" <?= $isReadOnly ?>>
            </div>

            <div class="col-md-6">
              <label for="city_id" class="form-label small fw-medium">City</label>
              <select class="form-select select2" name="city_id" id="city_id" <?= $isReadOnly ?>>
                <option value="">Select City</option>
                <?= $cityModel->generateCityOptions(); ?>
              </select>
            </div>

            <div class="col-md-6">
              <label for="address" class="form-label small fw-medium">Full Address</label>
              <input class="form-control" name="address" id="address" placeholder="Street, Building, Unit" <?= $isReadOnly ?>>
            </div>

            <div class="col-12">
              <label for="remarks" class="form-label small fw-medium">Lead Remarks</label>
              <textarea class="form-control" name="remarks" id="remarks" rows="2" placeholder="General remarks about this lead..." <?= $isReadOnly ?>></textarea>
            </div>
          </div>

        </form>
      </div>
    </div>
  </div>

  <!-- RIGHT SIDEBAR: INTERNAL NOTES -->
  <div class="col-lg-4">
    <div class="card shadow-sm border-0 h-100">
      <div class="card-header py-3 d-flex justify-content-between align-items-center border-bottom">
        <h6 class="mb-0 fw-semibold">Internal Notes</h6>
        <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#update-lead-status-modal">
          <i class="feather icon-plus me-1"></i> Add Note
        </button>
      </div>

      <div class="card-body overflow-auto" style="max-height: 550px;">
        <div id="lead-notes-container" class="vstack gap-3"></div>
        <div class="text-center text-muted py-5 d-none" id="no-lead-notes">
          <i class="feather icon-message-square display-6 text-light mb-2 d-block"></i>
          No internal notes yet
        </div>
      </div>
    </div>
  </div>

  <!-- ==========================================
       CARD 1: PRODUCT SELECTION & INPUT
  =========================================== -->
  <div class="col-lg-12">
    <div class="card shadow-sm border-0">
      <div class="card-header py-3 border-bottom">
        <h5 class="mb-0 fw-semibold">Select or Input Product</h5>
        <small class="text-muted">Choose a stock unit or enter unit specifications to create an inquiry</small>
      </div>

      <div class="card-body">
        <form id="product-inquiry-form" method="post">
          <div class="row g-3">
            <div class="col-md-4">
              <label for="stock_number" class="form-label small fw-medium">Stock Unit</label>
              <select class="form-control select2" id="stock_number" name="stock_number" <?= $isReadOnly ?>>
                <option value="">Select Stock Unit</option>
                <?= $productModel->generateForSaleProductOptions(); ?>
              </select>
            </div>

            <div class="col-md-4">
              <label for="product_subcategory_id" class="form-label small fw-medium">Product Category</label>
              <select class="form-control select2" id="product_subcategory_id" name="product_subcategory_id" <?= $isReadOnly ?>>
                <option value="">Select Product Category</option>
                <?= $productSubcategoryModel->generateGroupedProductSubcategoryOptions(); ?>
              </select>
            </div>

            <div class="col-md-4">
              <label for="unit_description" class="form-label small fw-medium">Unit Description</label>
              <input type="text" class="form-control" id="unit_description" name="unit_description" placeholder="Description" <?= $isReadOnly ?>>
            </div>

            <div class="col-md-4">
              <label for="transmission_type" class="form-label small fw-medium">Transmission Type</label>
              <select class="form-control select2" id="transmission_type" name="transmission_type" <?= $isReadOnly ?>>
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

            <div class="col-md-4">
              <label for="engine_no" class="form-label small fw-medium">Length</label>
              <div class="input-group">
                <input type="number" class="form-control" id="length" name="length" min="0" step="0.01" <?= $isReadOnly ?>>
                <select class="form-control" name="length_unit" id="length_unit" <?= $isReadOnly ?>>
                  <?php echo $unitModel->generateUnitByShortNameOptions(1); ?>
                </select>
              </div>
            </div>

            <div class="col-md-4">
              <label for="engine_no" class="form-label small fw-medium">Engine No.</label>
              <input type="text" class="form-control" id="engine_no" name="engine_no" placeholder="Engine No." <?= $isReadOnly ?>>
            </div>

            <div class="col-md-4">
              <label for="chassis_no" class="form-label small fw-medium">Chassis No.</label>
              <input type="text" class="form-control" id="chassis_no" name="chassis_no" placeholder="Chassis No." <?= $isReadOnly ?>>
            </div>

            <div class="col-md-4">
              <label for="color" class="form-label small fw-medium">Color</label>
              <input type="text" class="form-control" id="color" name="color" placeholder="Color" <?= $isReadOnly ?>>
            </div>

            <div class="col-md-4">
              <label for="year_model" class="form-label small fw-medium">Year Model</label>
              <select class="form-control select2" id="year_model" name="year_model" <?= $isReadOnly ?>>
                <option value="">Select Year</option>
                <?= $systemModel->generateYearOptions(date('Y', strtotime('-100 years')), date('Y', strtotime('+1 year'))); ?>
              </select>
            </div>

            <div class="col-12 text-end mt-3">
              <button type="submit" class="btn btn-primary" form="product-inquiry-form" id="submit-product-inquiry">
                <i class="feather icon-plus me-1"></i> Add Product Inquiry
              </button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- ==========================================
       CARD 2: PRODUCT INQUIRIES LIST
  =========================================== -->
  <div class="col-lg-12">
    <div class="card shadow-sm border-0 mb-0">
      <div class="card-header py-3 border-bottom">
        <h5 class="mb-0 fw-semibold">Product Inquiries</h5>
        <small class="text-muted">Recorded products associated with this lead</small>
      </div>

      <div class="card-body p-0">
        <div class="table-responsive">
          <table id="product-inquiry-table" class="table table-hover align-middle mb-0 w-100">
            <thead class="table-light">
              <tr>
                <th class="w-100px">Product</th>
                <th>Category</th>
                <th>Transmission Type</th>
                <th>Length</th>
                <th>Engine No.</th>
                <th>Chassis No.</th>
                <th>Color</th>
                <th>Year Model</th>
                <th>Inquiry Date</th>
                <th class="text-end">Action</th>
              </tr>
            </thead>
            <tbody></tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <!-- ==========================================
       CARD 3: CIF
  =========================================== -->
  <div class="col-lg-12">
    <div class="card shadow-sm border-0 mb-0">
      <div class="card-header py-3 d-flex justify-content-between align-items-center border-bottom">
        <div>
          <h5 class="mb-0 fw-semibold">CIF</h5>
          <small class="text-muted">Manage generated CIF records</small>
        </div>
      </div>

      <div class="card-body p-0">
        <div class="table-responsive">
          <table id="cif-table" class="table table-hover align-middle mb-0 w-100">
            <thead class="table-light">
              <tr>
                <th>Reference No.</th>
                <th>Product</th>
                <th>Company</th>
                <th>Supplier</th>
                <th>Add-on</th>
                <th>Status</th>
                <th>Remarks</th>
                <th class="text-end">Action</th>
              </tr>
            </thead>
            <tbody></tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <!-- ==========================================
       CARD 4: QUOTATIONS
  =========================================== -->
  <div class="col-lg-12">
    <div class="card shadow-sm border-0 mb-0">
      <div class="card-header py-3 d-flex justify-content-between align-items-center border-bottom">
        <div>
          <h5 class="mb-0 fw-semibold">Quotations</h5>
          <small class="text-muted">Manage generated price quotes and attachments</small>
        </div>
      </div>

      <div class="card-body p-0">
        <div class="table-responsive">
          <table id="quotation-table" class="table table-hover align-middle mb-0 w-100">
            <thead class="table-light">
              <tr>
                <th>Product</th>
                <th>Quantity</th>
                <th>Price</th>
                <th>Discount</th>
                <th>Total</th>
                <th>Status</th>
                <th>Company</th>
                <th>Inclusions</th>
                <th>Delivery Address</th>
                <th>Expiration Date</th>
                <th>Created Date</th>
                <th>Approval Confirmation</th>
                <th>Remarks</th>
                <th class="text-end">Action</th>
              </tr>
            </thead>
            <tbody></tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <!-- ==========================================
       BOTTOM SECTION: LOG NOTES
  =========================================== -->
  <div class="col-lg-12">
    <div class="card shadow-sm border-0 mb-0">
      <div class="card-header py-3 border-bottom">
        <h5 class="mb-0 fw-semibold">Activity Log</h5>
      </div>
      <div class="card-body overflow-auto p-3" style="max-height: 350px;">
        <?= $userModel->generateLogNotes('leads', $leadID); ?>
      </div>
    </div>
  </div>

</div>

<!-- ==========================================
     MODALS
=========================================== -->
<!-- Add Internal Note Modal -->
<div class="modal fade" id="add-internal-note-modal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 shadow">
      <div class="modal-header border-bottom">
        <div>
          <h5 class="modal-title mb-0">Add Internal Note</h5>
          <small class="text-muted">Visible only to internal team members</small>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <form id="lead-note-form">
          <input type="hidden" name="lead_id" id="modal-lead-id">
          <div class="mb-3">
            <label for="lead_note" class="form-label fw-medium">Note</label>
            <textarea class="form-control" name="lead_note" id="lead_note" rows="4" placeholder="Write internal notes..."></textarea>
          </div>
        </form>
      </div>
      <div class="modal-footer border-top">
        <button type="button" class="btn btn-light border" data-bs-dismiss="modal">Cancel</button>
        <button type="submit" form="lead-note-form" id="submit-lead-note" class="btn btn-warning">Save Note</button>
      </div>
    </div>
  </div>
</div>

<!-- Update Status Modal -->
<div class="modal fade" id="update-lead-status-modal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 shadow">
      <div class="modal-header border-bottom">
        <div>
          <h5 class="modal-title mb-0">Update Lead Status</h5>
          <small class="text-muted">Update status prior to creating internal notes</small>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <form id="lead-status-form">
          <input type="hidden" name="lead_id" id="modal-lead-id2">
          <div class="mb-3">
            <label for="lead_status_id2" class="form-label fw-medium">Lead Status</label>
            <select class="form-select select2" name="lead_status_id2" id="lead_status_id2">
              <option value="">Select Status</option>
              <?= $leadStatusModel->generateLeadStatusOptions('Lead'); ?>
            </select>
          </div>
        </form>
      </div>
      <div class="modal-footer border-top">
        <button type="button" class="btn btn-light border" data-bs-dismiss="modal">Cancel</button>
        <button type="submit" form="lead-status-form" id="submit-lead-status" class="btn btn-warning">Save Status</button>
      </div>
    </div>
  </div>
</div>

<!-- CIF Modal -->
<div class="modal fade" id="cif-modal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable modal-xl">
    <div class="modal-content border-0 shadow">
      
      <div class="modal-header border-bottom">
        <h5 class="modal-title fw-bold mb-0">Create / Edit CIF</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <div class="modal-body p-4">
        <form id="cif-form">
          <input type="hidden" name="cif_id" id="cif_id">
          <input type="hidden" name="leads_product_inquiry_id2" id="leads_product_inquiry_id2">

          <!-- Section 1: Core Details -->
          <div class="rounded-2 border mb-4">
            <h6 class="text-uppercase text-muted fw-bold small mb-3">CIF Details</h6>
            
            <div class="row g-3">

              <div class="col-md-12">
                <label for="cif_image" class="form-label fw-medium">CIF Document / Image</label>
                <input type="file" class="form-control" id="cif_image" name="cif_image">
              </div>

              <div class="col-md-6">
                <label for="company_id2" class="form-label fw-medium">Company</label>
                <select class="form-select modal-select2" name="company_id2" id="company_id2">
                  <option value="">Select Company</option>
                  <?= $companyModel->generateCompanyOptions(); ?>
                </select>
              </div>

              <div class="col-md-6">
                  <label for="supplier_id" class="form-label fw-medium">Supplier</label>
                  <select class="form-select modal-select2" multiple="multiple" name="supplier_id[]" id="supplier_id">
                      <?= $supplierModel->generateSupplierOptions(); ?>
                  </select>
              </div>

              <div class="col-12">
                <label for="add_ons" class="form-label fw-medium">Add Ons</label>
                <textarea class="form-control" name="add_ons" id="add_ons" rows="2" placeholder="Specify add-ons..."></textarea>
              </div>

              <div class="col-12">
                <label for="remarks_3" class="form-label fw-medium">Remarks</label>
                <textarea class="form-control" name="remarks_3" id="remarks_3" rows="2" placeholder="Specify remarks..."></textarea>
              </div>
            </div>
          </div>

        </form>
      </div>

      <div class="modal-footer border-top">
        <button type="button" class="btn btn-outline-secondary px-4" data-bs-dismiss="modal">Cancel</button>
        <button type="submit" form="cif-form" id="submit-cif" class="btn btn-primary px-4">
          <i class="feather icon-check me-1"></i> Save CIF
        </button>
      </div>

    </div>
  </div>
</div>

<div class="modal fade" id="quotation-modal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable modal-xl">
    <div class="modal-content border-0 shadow">
      
      <div class="modal-header border-bottom">
        <h5 class="modal-title fw-bold mb-0">Create / Edit Quotation</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <div class="modal-body p-4">
        <form id="quotation-form">
          <input type="hidden" name="quotation_id" id="quotation_id">
          <input type="hidden" name="leads_product_inquiry_id" id="leads_product_inquiry_id">

          <!-- Section 1: Core Details -->
          <div class="rounded-2 border mb-4">
            <h6 class="text-uppercase text-muted fw-bold small mb-3">Quotation Details</h6>
            
            <div class="row g-3">

              <div class="col-md-12">
                <label for="quotation_image" class="form-label fw-medium">Quotation Document / Image</label>
                <input type="file" class="form-control" id="quotation_image" name="quotation_image">
              </div>

              <div class="col-md-6">
                <label for="company_id" class="form-label fw-medium">Company</label>
                <select class="form-select modal-select2" name="company_id" id="company_id">
                  <option value="">Select Company</option>
                  <?= $companyModel->generateCompanyOptions(); ?>
                </select>
              </div>

              <div class="col-md-6">
                <label for="expiration_date" class="form-label fw-medium">Quotation Valid Until</label>
                <div class="input-group date">
                  <input type="text" class="form-control back-date-restricted-datepicker" id="expiration_date" name="expiration_date" placeholder="Select date" autocomplete="off">
                  <span class="input-group-text">
                    <i class="feather icon-calendar text-muted"></i>
                  </span>
                </div>
              </div>

              <div class="col-md-2">
                <label for="quantity" class="form-label fw-medium">Quantity</label>
                <input type="number" step="0.01" class="form-control" id="quantity" name="quantity" min="1" value="1">
              </div>

              <div class="col-md-5">
                <label for="price" class="form-label fw-medium">Quoted Price</label>
                <div class="input-group">
                  <span class="input-group-text">₱</span>
                  <input type="number" step="0.01" class="form-control" id="price" name="price" value="0.00">
                </div>
              </div>

              <div class="col-md-5">
                <label for="discount" class="form-label fw-medium">Discount</label>
                <div class="input-group">
                  <span class="input-group-text">₱</span>
                  <input type="number" step="0.01" class="form-control" id="discount" name="discount" value="0.00">
                </div>
              </div>

              <div class="col-12">
                <label for="inclusions" class="form-label fw-medium">Inclusions</label>
                <textarea class="form-control" name="inclusions" id="inclusions" rows="2" placeholder="Specify inclusions..."></textarea>
              </div>
            </div>
          </div>

          <!-- Section 2: Delivery & Remarks -->
          <div class="rounded-2 border">
            <h6 class="text-uppercase text-muted fw-bold small mb-3">Delivery & Additional Information</h6>
            
            <div class="row g-3">
              <div class="col-md-6">
                <label for="delivery_address" class="form-label fw-medium">Delivery Address</label>
                <textarea class="form-control" name="delivery_address" id="delivery_address" rows="2" placeholder="Specify delivery address..."></textarea>
              </div>

              <div class="col-md-6">
                <label for="delivery_instructions" class="form-label fw-medium">Delivery Instructions</label>
                <textarea class="form-control" name="delivery_instructions" id="delivery_instructions" rows="2" placeholder="Specify delivery instructions..."></textarea>
              </div>

              <div class="col-md-6">
                <label for="delivery_conditions" class="form-label fw-medium">Delivery Conditions</label>
                <textarea class="form-control" name="delivery_conditions" id="delivery_conditions" rows="2" placeholder="Specify delivery conditions..."></textarea>
              </div>

              <div class="col-md-6">
                <label for="remarks2" class="form-label fw-medium">Remarks</label>
                <textarea class="form-control" name="remarks2" id="remarks2" rows="2" placeholder="Remarks..."></textarea>
              </div>
            </div>
          </div>

          <div class="rounded-2 border mt-4">
            <h6 class="text-uppercase text-muted fw-bold small mb-3">Pre-Approval</h6>
            
            <div class="row g-3">
              <div class="col-md-4">
                <label for="pre_approve" class="form-label fw-medium">Is Pre-Approve?</label>
                <select class="form-select modal-select2" name="pre_approve" id="pre_approve">
                  <option value="Yes">Yes</option>
                  <option value="No" selected>No</option>
                </select>
              </div>
              <div class="col-md-8">
                <label for="approval_confirmation" class="form-label fw-medium">Approval Confirmation</label>
                <input type="file" class="form-control" id="approval_confirmation" name="approval_confirmation">
              </div>
            </div>
          </div>

        </form>
      </div>

      <div class="modal-footer border-top">
        <button type="button" class="btn btn-outline-secondary px-4" data-bs-dismiss="modal">Cancel</button>
        <button type="submit" form="quotation-form" id="submit-quotation" class="btn btn-primary px-4">
          <i class="feather icon-check me-1"></i> Save Quotation
        </button>
      </div>

    </div>
  </div>
</div>