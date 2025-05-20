<?php
  $addProductExpense = $userModel->checkSystemActionAccessRights($user_id, 173);
?>
<div class="row">
  <div class="col-lg-12">
    <div class="ecom-wrapper">
      <div class="ecom-content w-100">
        <div class="card table-card">
          <div class="card-header">
            <div class="row align-items-center">
              <div class="col-sm-6">
                <h5>Product Expense List</h5>
              </div>
              <div class="col-sm-6 text-sm-end mt-3 mt-sm-0">
                <?php
                  if($addProductExpense['total'] > 0){
                    $action = '';

                    if($addProductExpense['total'] > 0){
                      $action .= '<button class="btn btn-warning" type="button" data-bs-toggle="offcanvas" data-bs-target="#product-expense-offcanvas" aria-controls="product-expense-offcanvas" id="product-expense">Add Expense</button>';
                    }
                                  
                    echo $action;
                    }
                ?>
                <button type="button" class="btn btn-warning" data-bs-toggle="offcanvas" data-bs-target="#filter-canvas">
                  Filter
                </button>
              </div>
            </div>
          </div>
          <div class="card-body">
            <div class="table-responsive dt-responsive">
              <table id="product-expense-table" class="table table-hover text-wrap w-100">
                <thead>
                  <tr>
                    <th>Product</th>
                    <th>Date</th>
                    <th>Reference Type</th>
                    <th>Reference Number</th>
                    <th>Amount</th>
                    <th>Particulars</th>
                    <th>Type</th>
                    <th>Actions</th>
                  </tr>
                </thead>
                <tbody class="text-wrap"></tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div>
  <div class="offcanvas offcanvas-end" tabindex="-1" id="product-expense-offcanvas" aria-labelledby="product-expense-offcanvas-label">
    <div class="offcanvas-header">
      <h2 id="product-expense-offcanvas-label" style="margin-bottom:-0.5rem">Add Expense</h2>
      <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
      <div class="row">
        <div class="col-lg-12">
          <form id="product-expense-form" method="post" action="#">
            <div class="form-group row">
              <div class="col-lg-12 mt-3 mt-lg-0 mb-3">
                <label class="form-label">Product <span class="text-danger">*</span></label>
                <select class="form-control offcanvas-select2" name="product_id" id="product_id">
                  <option value="">--</option>
                  <?php echo $productModel->generateNotDraftProductOptions(); ?>
                </select>
              </div>
              <div class="col-lg-6 mt-3 mt-lg-0">
                <label class="form-label">Reference Type <span class="text-danger">*</span></label>
                <select class="form-control offcanvas-select2" name="reference_type" id="reference_type">
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
            <div class="form-group row">
              <div class="col-lg-12 mt-3 mt-lg-0">
                <label class="form-label">Particulars <span class="text-danger">*</span></label>
                <textarea class="form-control" id="particulars" name="particulars" maxlength="2000"></textarea>
              </div>
            </div>
          </form>
        </div>
      </div>
      <div class="row">
        <div class="col-lg-12">
          <button type="submit" class="btn btn-primary" id="submit-product-expense" form="product-expense-form">Submit</button>
          <button class="btn btn-light-danger" data-bs-dismiss="offcanvas"> Close </button>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="offcanvas offcanvas-end" tabindex="-1" id="filter-canvas">
        <div class="offcanvas-body p-0 sticky-xxl-top">
          <div id="ecom-filter" class="show collapse collapse-horizontal">
            <div class="ecom-filter">
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
        </div>
</div>