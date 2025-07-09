<?php
$updatePartsButton = '';
$updatePartsImageButton = '';
$deletePartsButton = '';

$partsDetails = $partsModel->getParts($partID);
$partsStatus = $partsDetails['part_status'];
$updateLandedCost = $userModel->checkSystemActionAccessRights($user_id, 171);
$updatePartsDisabled = $userModel->checkSystemActionAccessRights($user_id, 175);

$disabledPartsForm = 'disabled';
$disabledLandedCostForm = 'readonly';
$disabledLandedCostForm2 = 'readonly';

if ($updateLandedCost['total'] > 0 && $partsStatus == 'Draft') {
  $disabledLandedCostForm = '';
  $disabledLandedCostForm2 = '';
} elseif ($updatePartsDisabled['total'] > 0 && $partsStatus != 'Draft' && $partsStatus != 'Sold') {
  $disabledLandedCostForm = 'readonly';
  $disabledLandedCostForm2 = '';
} else {
  $disabledLandedCostForm = 'readonly';
  $disabledLandedCostForm2 = 'readonly';
}


if(($partsWriteAccess['total'] > 0 && $partsStatus == 'Draft') || $updatePartsDisabled['total'] > 0 && $partsStatus != 'Draft'){
  $disabledPartsForm = '';
}

if($partsWriteAccess['total'] > 0 && $partsStatus == 'Draft'){
    $updatePartsButton = '<div class="col-4">
                                <div class="d-grid">
                                    <button class="btn btn-info" type="button" data-bs-toggle="offcanvas" data-bs-target="#update-parts-offcanvas" aria-controls="update-parts-offcanvas" id="update-parts">Update Parts</button>
                                </div>
                            </div>';
}

if($updatePartImage['total'] > 0 && $partsStatus == 'Draft'){
  $updatePartsImageButton = '<div class="col-4">
                                  <div class="d-grid">
                                    <button class="btn btn-warning" type="button" data-bs-toggle="offcanvas" data-bs-target="#update-parts-image-offcanvas" aria-controls="update-parts-image-offcanvas" id="update-parts-image">Update Image</button>
                                  </div>
                              </div>';
}

if($partsDeleteAccess['total'] > 0){
    $deletePartsButton = '<div class="col-4">
                                <div class="d-grid">
                                    <button class="btn btn-outline-danger" id="delete-parts-details">Delete Parts</button>
                                </div>
                            </div>';
}

if($addPartExpense['total'] > 0){
  $addPartsExpenseButton = '<button class="btn btn-warning" type="button" data-bs-toggle="offcanvas" data-bs-target="#parts-expense-offcanvas" aria-controls="parts-expense-offcanvas" id="parts-expense">Add Expense</button>';
}
?>

<div class="row">
  <div class="col-md-12">
    <div class="card">
      <div class="card-header">
        <div class="row align-items-center">
          <div class="col-sm-4">
            <h5>Parts Details</h5>
          </div>
          <div class="col-md-8 text-sm-end mt-3 mt-sm-0">
            <?php
              $dropdown = '<div class="btn-group m-r-5">
                            <button type="button" class="btn btn-outline-secondary dropdown-toggle form-details" data-bs-toggle="dropdown" aria-expanded="false">
                              Action
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">';

                        if ($tagForSale['total'] > 0 && $partsStatus == 'Draft') {
                            $dropdown .= '<li><button class="dropdown-item" type="button" id="tag-parts-for-sale">Tag For Sale</button></li>';
                        }

                        if ($partsDeleteAccess['total'] > 0) {
                            $dropdown .= '<li><button class="dropdown-item" type="button" id="delete-parts-details">Delete Parts</button></li>';
                        }
                                  
                        $dropdown .= '</ul>
                                      </div>';
                              
                        echo $dropdown;

                        if(($partsWriteAccess['total'] > 0 && $partsStatus == 'Draft') || $updatePartsDisabled['total'] > 0 && $partsStatus != 'Draft'){
                          echo '<button type="submit" form="parts-details-form" class="btn btn-success me-1" id="submit-parts-details-data">Save</button>';
                        }

                        if ($partsCreateAccess['total'] > 0) {
                          echo '<a class="btn btn-success m-r-5 form-details" href="parts.php?new">Create</a>';
                        }
            ?>
          </div>
        </div>
      </div>
      <div class="card-body">
        <div class="row">
          <div class="col-md-6">
            <div class="form-group row">
                <h5 class="col-lg-12">Parts Thumbnail</h5>
                <hr/>
            </div>
            <div class="text-center mb-3">
              <img src="<?php echo DEFAULT_AVATAR_IMAGE; ?>" alt="User Image" id="parts_thumbnail" class="img-fluid wid-100 hei-100">
            </div>
            <div class="text-center mb-0">
              <label class="btn btn-outline-secondary" for="parts_image"><i class="ti ti-upload me-2"></i> Click to Upload</label> 
              <input type="file" id="parts_image" name="parts_image" class="d-none">
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group row">
                <h5 class="col-lg-12">Other Parts Image</h5>
                <hr/>
            </div>
            <div class="row" id="parts_other_images"></div>
            <div class="row">
              <div class="text-center mt-2">
                <label class="btn btn-outline-secondary" for="parts_other_image"><i class="ti ti-upload me-2"></i> Click to Upload</label> 
                <input type="file" id="parts_other_image" name="parts_other_image" class="d-none">
                </div>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-12">
            <form id="parts-details-form" method="post" action="#">
              <div class="form-group row">
                <h5 class="col-lg-12">Basic Information</h5>
                <hr/>
              </div>
              <div class="form-group row">
                <label class="col-lg-3 col-form-label">Brand <span class="text-danger">*</span></label>
                <div class="col-lg-3">
                  <select class="form-control select2" name="brand_id" id="brand_id" <?php echo $disabledPartsForm; ?>>
                    <option value="">--</option>
                    <?php echo $brandModel->generateBrandOptions(); ?>
                  </select>
                </div>
                <label class="col-lg-3 col-form-label">Part Number <span class="text-danger">*</span></label>
                <div class="col-lg-3">
                  <input type="text" class="form-control" id="part_number" name="part_number" maxlength="100" autocomplete="off" <?php echo $disabledPartsForm; ?>>
                </div>
              </div>
              <div class="form-group row">
                <label class="col-lg-3 col-form-label">Bar Code</label>
                <div class="col-lg-3">
                  <input type="text" class="form-control" id="bar_code" name="bar_code" maxlength="100" autocomplete="off" <?php echo $disabledPartsForm; ?>>
                </div>
                <label class="col-lg-3 col-form-label">Category <span class="text-danger">*</span></label>
                <div class="col-lg-3">
                  <select class="form-control select2" name="part_category_id" id="part_category_id" <?php echo $disabledPartsForm; ?>>
                    <option value="">--</option>
                    <?php echo $partsCategoryModel->generatePartsCategoryOptions(); ?>
                  </select>
                </div>
              </div>
              <div class="form-group row">
                <label class="col-lg-3 col-form-label">Class <span class="text-danger">*</span></label>
                <div class="col-lg-3">
                  <select class="form-control select2" name="part_class_id" id="part_class_id" <?php echo $disabledPartsForm; ?>>
                  <option value="">--</option>
                    <?php echo $partsClassModel->generatePartsClassOptions(); ?>
                  </select>
                </div>
                <label class="col-lg-3 col-form-label">Subclass <span class="text-danger">*</span></label>
                <div class="col-lg-3">
                  <select class="form-control select2" name="part_subclass_id" id="part_subclass_id" <?php echo $disabledPartsForm; ?>>
                  <option value="">--</option>
                    <?php echo $partsSubclassModel->generatePartsSubclassOptions(); ?>
                  </select>
                </div>
              </div>
              <div class="form-group row">
                <label class="col-lg-3 col-form-label">Company <span class="text-danger">*</span></label>
                <div class="col-lg-3">
                  <select class="form-control select2" name="company_id" id="company_id" <?php echo $disabledPartsForm; ?>>
                    <option value="">--</option>
                    <?php echo $companyModel->generateCompanyOptions(); ?>
                  </select>
                </div>
                <label class="col-lg-3 col-form-label d-none">Quantity <span class="text-danger">*</span></label>
                <div class="col-lg-3 d-none">
                  <input type="number" class="form-control" id="quantity" name="quantity" value="0" min="0" step="0.01" <?php echo $disabledPartsForm; ?>>
                </div>
                <label class="col-lg-3 col-form-label">Unit Sale <span class="text-danger">*</span></label>
                <div class="col-lg-3">
                  <select class="form-control select2" name="unit_sale" id="unit_sale" <?php echo $disabledPartsForm; ?>>
                    <option value="">--</option>
                    <?php echo $unitModel->generateUnitOptions(); ?>
                  </select>
                </div>
              </div>
              <div class="form-group row">
                <label class="col-lg-3 col-form-label">Stock Alert <span class="text-danger">*</span></label>
                <div class="col-lg-3">
                  <input type="number" class="form-control" id="stock_alert" name="stock_alert" value="0" min="0" step="1" <?php echo $disabledPartsForm; ?>>
                </div>
                <label class="col-lg-3 col-form-label">Part Price (SRP) <span class="text-danger">*</span></label>
                <div class="col-lg-3">
                  <input type="number" class="form-control" id="part_price" name="part_price" min="0" step="0.01" <?php echo $disabledLandedCostForm2; ?>>
                </div>
              </div>
              <div class="form-group row">
                <label class="col-lg-3 col-form-label">Description <span class="text-danger">*</span></label>
                <div class="col-lg-9">
                  <textarea class="form-control" id="description" name="description" maxlength="2000" rows="3" <?php echo $disabledPartsForm; ?>></textarea>
                </div>
              </div>
              <div class="form-group row">
                <label class="col-lg-3 col-form-label">Remarks</label>
                <div class="col-lg-9">
                  <textarea class="form-control" id="remarks" name="remarks" maxlength="1000" rows="3" <?php echo $disabledPartsForm; ?>></textarea>
                </div>
              </div>
                    
              <div class="form-group row mt-4">
                <h5 class="col-lg-12">Warehouse & Delivery Information</h5>
                <hr/>
              </div>
              <div class="form-group row">
                <label class="col-lg-3 col-form-label">Warehouse <span class="text-danger">*</span></label>
                <div class="col-lg-3">
                  <select class="form-control select2" name="warehouse_id" id="warehouse_id" <?php echo $disabledPartsForm; ?>>
                    <option value="">--</option>
                    <?php echo $warehouseModel->generateWarehouseOptions(); ?>
                  </select>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="row">
  <div class="col-lg-6">
    <div class="card table-card">
      <div class="card-header">
        <div class="row align-items-center">
          <div class="col-sm-6">
            <h5>Parts Incoming</h5>
          </div>
          <div class="col-sm-6 text-sm-end mt-3 mt-sm-0">
            <button type="button" class="btn btn-warning" data-bs-toggle="offcanvas" data-bs-target="#parts-incoming-filter-offcanvas">
              Filter
            </button>
          </div>
        </div>
      </div>
      <div class="card-body">
        <div class="table-responsive w-100">
          <table class="table mb-0" id="parts-incoming-table">
            <thead>
              <tr>
                <th class="text-center">Reference Number</th>
                <th class="text-center">Quantity</th>
                <th class="text-center">Received Quantity</th>
                <?php
                  if($viewPartCost['total'] > 0){
                    echo '<th class="text-center">Cost</th>
                    <th class="text-center">Total Cost</th>';
                  }
                ?>
                <th class="text-center">Remarks</th>
              </tr>
            </thead>
            <tbody></tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
  <div class="col-lg-6">
    <div class="card table-card">
      <div class="card-header">
        <div class="row align-items-center">
          <div class="col-sm-6">
            <h5>Parts Transaction</h5>
          </div>
          <div class="col-sm-6 text-sm-end mt-3 mt-sm-0">
            <button type="button" class="btn btn-warning" data-bs-toggle="offcanvas" data-bs-target="#parts-transaction-filter-offcanvas">
              Filter
            </button>
          </div>
        </div>
      </div>
      <div class="card-body">
        <div class="table-responsive w-100">
          <table class="table mb-0" id="parts-transaction-table">
            <thead>
              <tr>
                <th class="text-end">Transaction No.</th>
                <th class="text-center">Quantity</th>
                <th class="text-center">Add-On</th>
                <th class="text-center">Discount</th>
                <th class="text-center">Total Discount</th>
                <th class="text-end">Sub-Total</th>
                <th class="text-end">Total</th>
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
    <div class="card table-card">
      <div class="card-header">
        <div class="row align-items-center">
          <div class="col-sm-6">
            <h5>Parts Document</h5>
          </div>
          <div class="col-md-6 text-sm-end mt-3 mt-sm-0">
            <button class="btn btn-warning" type="button" data-bs-toggle="offcanvas" data-bs-target="#parts-document-offcanvas" aria-controls="parts-document-offcanvas" id="parts-document">Add Document</button>
          </div>
        </div>
      </div>
      <div class="card-body">
        <div class="table-responsive w-100">
          <table id="parts-document-table" class="table table-hover nowrap w-100 dataTable">
            <thead>
              <tr>
                <th class="w-100">Document Type</th>
                <th class="w-100">Actions</th>
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
  <div class="offcanvas offcanvas-end" tabindex="-1" id="parts-transaction-filter-offcanvas" aria-labelledby="parts-transaction-filter-offcanvas-label">
    <div class="offcanvas-header">
      <h2 id="parts-transaction-filter-offcanvas-label" style="margin-bottom:-0.5rem">Filter</h2>
      <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
      <div class="row">
        <div class="col-lg-12">
            <div class="form-group row">
              <div class="col-lg-12 mt-3 mt-lg-0">
                <label class="form-label">Transaction Date</label>
                <div class="input-group date">
                  <input type="text" class="form-control regular-datepicker" id="parts_transaction_start_date" name="parts_transaction_start_date" autocomplete="off">
                  <span class="input-group-text">
                    <i class="feather icon-calendar"></i>
                  </span>
                </div>
                <div class="input-group date mt-3">
                  <input type="text" class="form-control regular-datepicker" id="parts_transaction_end_date" name="parts_transaction_end_date" autocomplete="off">
                  <span class="input-group-text">
                    <i class="feather icon-calendar"></i>
                  </span>
                </div>
              </div>
            </div>
        </div>
      </div>
      <div class="row">
        <div class="col-lg-12">
          <button type="submit" class="btn btn-primary" id="submit-parts-transaction-filter">Apply</button>
          <button class="btn btn-light-danger" data-bs-dismiss="offcanvas"> Close </button>
        </div>
      </div>
    </div>
  </div>
</div>

<div>
  <div class="offcanvas offcanvas-end" tabindex="-1" id="parts-incoming-filter-offcanvas" aria-labelledby="parts-incoming-filter-offcanvas-label">
    <div class="offcanvas-header">
      <h2 id="parts-incoming-filter-offcanvas-label" style="margin-bottom:-0.5rem">Filter</h2>
      <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
      <div class="row">
        <div class="col-lg-12">
            <div class="form-group row">
              <div class="col-lg-12 mt-3 mt-lg-0">
                <label class="form-label">Transaction Date</label>
                <div class="input-group date">
                  <input type="text" class="form-control regular-datepicker" id="parts_incoming_start_date" name="parts_incoming_start_date" autocomplete="off">
                  <span class="input-group-text">
                    <i class="feather icon-calendar"></i>
                  </span>
                </div>
                <div class="input-group date mt-3">
                  <input type="text" class="form-control regular-datepicker" id="parts_incoming_end_date" name="parts_incoming_end_date" autocomplete="off">
                  <span class="input-group-text">
                    <i class="feather icon-calendar"></i>
                  </span>
                </div>
              </div>
            </div>
        </div>
      </div>
      <div class="row">
        <div class="col-lg-12">
          <button type="submit" class="btn btn-primary" id="submit-parts-incoming-filter">Apply</button>
          <button class="btn btn-light-danger" data-bs-dismiss="offcanvas"> Close </button>
        </div>
      </div>
    </div>
  </div>
</div>

<div>
  <div class="offcanvas offcanvas-end" tabindex="-1" id="parts-document-offcanvas" aria-labelledby="parts-document-offcanvas-label">
    <div class="offcanvas-header">
      <h2 id="parts-document-offcanvas-label" style="margin-bottom:-0.5rem">Add Document</h2>
      <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
      <div class="row">
        <div class="col-lg-12">
          <form id="parts-document-form" method="post" action="#">
            <div class="form-group row">
              <div class="col-lg-12 mt-3 mt-lg-0">
                <label class="form-label">Document Type <span class="text-danger">*</span></label>
                <select class="form-control offcanvas-select2" name="document_type" id="document_type">
                  <option value="">--</option>
                  <option value="Certificate of Registration (CR)">Certificate of Registration (CR)</option>
                  <option value="Incoming Checklist">Incoming Checklist</option>
                  <option value="Official Receipt (OR)">Official Receipt (OR)</option>
                  <option value="LTO Registration">LTO Registration</option>
                  <option value="Insurance Certificate">Insurance Certificate</option>
                  <option value="Emission Test Certificate">Emission Test Certificate</option>
                  <option value="Certificate of Roadworthiness">Certificate of Roadworthiness</option>
                  <option value="TAX Certificate">TAX Certificate</option>
                  <option value="Tare Weight Certificate">Tare Weight Certificate</option>
                  <option value="Chassis Number">Chassis Number</option>
                  <option value="Engine Number">Engine Number</option>
                </select>
              </div>
              <div class="col-lg-12 mt-3 mt-lg-0">
                <label class="form-label">Document <span class="text-danger">*</span></label>
                <input type="file" id="parts_document" name="parts_document" class="form-control">
              </div>
            </div>
          </form>
        </div>
      </div>
      <div class="row">
        <div class="col-lg-12">
          <button type="submit" class="btn btn-primary" id="submit-parts-document" form="parts-document-form">Submit</button>
          <button class="btn btn-light-danger" data-bs-dismiss="offcanvas"> Close </button>
        </div>
      </div>
    </div>
  </div>
</div>

<div>
  <div class="offcanvas offcanvas-end" tabindex="-1" id="parts-expense-offcanvas" aria-labelledby="parts-expense-offcanvas-label">
    <div class="offcanvas-header">
      <h2 id="parts-expense-offcanvas-label" style="margin-bottom:-0.5rem">Add Expense</h2>
      <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
      <div class="row">
        <div class="col-lg-12">
          <form id="parts-expense-form" method="post" action="#">
            <div class="form-group row">
              <div class="col-lg-6 mt-3 mt-lg-0">
                <label class="form-label">Reference Type <span class="text-danger">*</span></label>
                <select class="form-control offcanvas-select2" name="reference_type" id="reference_type">
                  <option value="">--</option>
                  <option value="Check Voucher">Check Voucher</option>
                  <option value="Cash Disbursement Voucher">Cash Disbursement Voucher</option>
                  <option value="Issuance Slip">Issuance Slip</option>
                  <option value="Contractor Report">Contractor Report</option>
                  <option value="Adjustment">Adjustment</option>
                  <option value="Stock Transfer Advice">Stock Transfer Advice</option>
                </select>
              </div>
              <div class="col-lg-6 mt-3 mt-lg-0">
                <label class="form-label">Reference Number <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="reference_number" name="reference_number" maxlength="200" autocomplete="off">
              </div>
            </div>
            <div class="form-group row">
              <div class="col-lg-6 mt-3 mt-lg-0">
                <label class="form-label">Amount <span class="text-danger">*</span></label>
                <input type="number" class="form-control" id="expense_amount" name="expense_amount" value="0" step="0.01">
              </div>
              <div class="col-lg-6 mt-3 mt-lg-0">
                <label class="form-label">Expense Type <span class="text-danger">*</span></label>
                <select class="form-control offcanvas-select2" name="expense_type" id="expense_type">
                  <option value="">--</option>
                  <option value="Assemble / Conversion">Assemble / Conversion</option>
                  <option value="Body Builder">Body Builder</option>
                  <option value="Commission">Commission</option>
                  <option value="Delivery">Delivery</option>
                  <option value="Insurance">Insurance</option>
                  <option value="Landed Cost">Landed Cost</option>
                  <option value="Latero">Latero</option>
                  <option value="Painting">Painting</option>
                  <option value="Parts & ACC">Parts & ACC</option>
                  <option value="Registration">Registration</option>
                  <option value="Registration C/O Customer">Registration C/O Customer</option>
                  <option value="Repairs & Maintenance">Repairs & Maintenance</option>
                  <option value="Supplies">Supplies</option>
                  <option value="Journal Voucher">Journal Voucher</option>
                </select>
              </div>
            </div>
            <div class="form-group row">
              <div class="col-lg-12 mt-3 mt-lg-0">
                <label class="form-label">Particulars <span class="text-danger">*</span></label>
                <textarea class="form-control" id="particulars" name="particulars" maxlength="500"></textarea>
              </div>
            </div>
          </form>
        </div>
      </div>
      <div class="row">
        <div class="col-lg-12">
          <button type="submit" class="btn btn-primary" id="submit-parts-expense" form="parts-expense-form">Submit</button>
          <button class="btn btn-light-danger" data-bs-dismiss="offcanvas"> Close </button>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="offcanvas offcanvas-end" tabindex="-1" id="filter-canvas">
  <div class="offcanvas-body p-0 sticky-xxl-top">
    <div class="card">
          <div class="card-header d-flex align-items-center justify-content-between">
            <h5>Filter</h5>
            <a href="#" class="avtar avtar-s btn-link-danger btn-pc-default" data-bs-dismiss="offcanvas" data-bs-target="#filter-canvas">
              <i class="ti ti-x f-20"></i>
            </a>
          </div>
          <div class="scroll-block">
            <div class="card-body">
              <ul class="list-group list-group-flush">
                <li class="list-group-item px-0 py-2">
                  <a class="btn border-0 px-0 text-start w-100" data-bs-toggle="collapse" href="#reference-type-filter-collapse"><div class="float-end"><i class="ti ti-chevron-down"></i></div>
                    Reference Type
                  </a>
                  <div class="collapse show" id="reference-type-filter-collapse">
                    <div class="py-3">
                      <select class="form-control" id="reference_type_filter">
                        <option value="">--</option>
                        <option value="Check Voucher">Check Voucher</option>
                        <option value="Cash Disbursement Voucher">Cash Disbursement Voucher</option>
                        <option value="Journal Voucher">Journal Voucher</option>
                        <option value="Issuance Slip">Issuance Slip</option>
                        <option value="Contractor Report">Contractor Report</option>
                        <option value="Adjustment">Adjustment</option>
                        <option value="Stock Transfer Advice">Stock Transfer Advice</option>
                      </select>
                    </div>
                  </div>
                </li>
                <li class="list-group-item px-0 py-2">
                  <a class="btn border-0 px-0 text-start w-100" data-bs-toggle="collapse" href="#expense-type-filter-collapse"><div class="float-end"><i class="ti ti-chevron-down"></i></div>
                    Expense Type
                  </a>
                  <div class="collapse show" id="expense-type-filter-collapse">
                    <div class="py-3">
                      <select class="form-control" id="expense_type_filter">
                        <option value="">--</option>
                        <option value="Assemble / Conversion">Assemble / Conversion</option>
                        <option value="Body Builder">Body Builder</option>
                        <option value="Commission">Commission</option>
                        <option value="Delivery">Delivery</option>
                        <option value="Insurance">Insurance</option>
                        <option value="Landed Cost">Landed Cost</option>
                        <option value="Latero">Latero</option>
                        <option value="Painting">Painting</option>
                        <option value="Parts & ACC">Parts & ACC</option>
                        <option value="Registration">Registration</option>
                        <option value="Registration C/O Customer">Registration C/O Customer</option>
                        <option value="Repairs & Maintenance">Repairs & Maintenance</option>
                        <option value="Supplies">Supplies</option>
                      </select>
                    </div>
                  </div>
                </li>
                <li class="list-group-item px-0 py-2">
                  <button type="button" class="btn btn-light-success w-100" id="apply-filter">Apply</a>
                </li>
              </ul>
            </div>
          </div>
        </div>
  </div>
</div>

<div class="offcanvas offcanvas-end" tabindex="-1" id="parts-qr-code-offcanvas" aria-labelledby="parts-qr-code-offcanvas-label">
  <div class="offcanvas-header">
    <h2 id="parts-qr-code-offcanvas-label" style="margin-bottom:-0.5rem">QR Code</h2>
    <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
  </div>
  <div class="offcanvas-body">
    <div class="alert alert-success alert-dismissible mb-4" role="alert">
      This QR code serves as a secure and efficient means of identity verification and access control within our organization. Its primary purpose is to enhance the overall security and streamline various operational processes.
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <div class="row mb-4 text-center">
      <div class="col-lg-12" id="parts-qr-code-container"></div>
    </div>
    <div class="row">
      <div class="col-lg-12">
        <button class="btn btn-light-success" id="print-qr"> Print </button>
        <button class="btn btn-light-danger" data-bs-dismiss="offcanvas"> Close </button>
      </div>
    </div>
  </div>
</div>

<?php
if($viewPartLogNotes['total'] > 0){
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
          '. $userModel->generateLogNotes('parts', $partID) .'
        </div>
      </div>
    </div>
  </div>
</div>';
}
  
?>