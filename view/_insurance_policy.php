<div class="row">
  <div class="col-lg-12">
    <div class="card table-card">
      <div class="card-header">
        <div class="row align-items-center">
          <div class="col-sm-6">
            <h5>Insurance Policy List</h5>
          </div>
          <div class="col-sm-6 text-sm-end mt-3 mt-sm-0">
            <div class="btn-group m-r-10">
              <button type="button" 
                        class="btn btn-outline-secondary dropdown-toggle" 
                        data-bs-toggle="dropdown">
                    Export
                </button>

                <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                        <button class="dropdown-item" type="button" id="export-csv">
                            Export As CSV
                        </button>
                    </li>

                    <li>
                        <button class="dropdown-item" type="button" id="export-excel">
                            Export As Excel
                        </button>
                    </li>

                    <li>
                        <button class="dropdown-item" type="button" id="export-pdf">
                            Export As PDF
                        </button>
                    </li>
                </ul>
            </div>
            <?php
              if($insurancePolicyCreateAccess['total'] > 0 || $insurancePolicyDeleteAccess['total'] > 0){
                $action = '';
                              
                if($insurancePolicyDeleteAccess['total'] > 0){
                  $action .= '<div class="btn-group m-r-10">
                                <button type="button" class="btn btn-outline-secondary dropdown-toggle d-none action-dropdown" data-bs-toggle="dropdown" aria-expanded="false">Action</button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                  <li><button class="dropdown-item" type="button" id="delete-insurance-policy">Delete Insurance Policy</button></li>
                                </ul>
                              </div>';
                }

                if($insurancePolicyCreateAccess['total'] > 0){
                  $action .= '<a href="insurance-policy.php?new" class="btn btn-success">Create</a>';
                }
                              
                echo $action;
              }
            ?>

            <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#filter-modal">
              Filter
            </button>
          </div>
        </div>
      </div>
      <div class="card-body">
        <div class="table-responsive dt-responsive">
          <table id="insurance-policy-table" class="table table-hover nowrap w-100">
            <thead>
              <tr>
                <th class="all">
                  <div class="form-check">
                    <input class="form-check-input" id="datatable-checkbox" type="checkbox">
                  </div>
                </th>
                <th>Policy Number</th>
                <th>Customer</th>
                <th>Insurance Type</th>
                <th>Insurance Provider</th>
                <th>Gross</th>
                <th>Net Premium</th>
                <th>Inception Date</th>
                <th>Expiry Date</th>
                <th>Remittance Status</th>
                <th>Payment Status</th>
                <th>Status</th>
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

<div class="modal fade modal-animate" id="filter-modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Filter</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="ci-appraisal-source-form" method="post" action="#">
                    <input type="hidden" id="ci_report_appraisal_source_id" name="ci_report_appraisal_source_id">
                    <div class="form-group row">
                        <div class="col-lg-12">
                            <label class="form-label">Status</label>
                            <div class="row py-3">
                              <div class="col-12">
                                <select class="form-control" id="status_filter">
                                  <option value="">--</option>
                                  <option value="Active" selected>Active</option>
                                  <option value="Expired">Expired</option>
                                  <option value="Cancelled">Cancelled</option>
                                </select>
                              </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-12">
                            <label class="form-label">Remittance Status</label>
                            <div class="row py-3">
                              <div class="col-12">
                                <select class="form-control" id="remittance_status_filter">
                                  <option value="">--</option>
                                  <option value="Remitted">Remitted</option>
                                  <option value="Pending">Pending</option>
                                </select>
                              </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-12">
                            <label class="form-label">Payment Status</label>
                            <div class="row py-3">
                              <div class="col-12">
                                <select class="form-control" id="payment_status_filter">
                                  <option value="">--</option>
                                  <option value="Unpaid">Unpaid</option>
                                  <option value="Paid">Paid</option>
                                </select>
                              </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-12">
                            <label class="form-label">Inception Date</label>
                            <div class="row py-3">
                              <div class="col-12">
                                <input type="text" class="form-control filter-datepicker mb-3" autocomplete="off" name="filter_inception_start_date" id="filter_inception_start_date" placeholder="Start Date">
                                <input type="text" class="form-control filter-datepicker" autocomplete="off" name="filter_inception_end_date" id="filter_inception_end_date" placeholder="End Date">
                              </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-12">
                            <label class="form-label">Expiry Date</label>
                            <div class="row py-3">
                              <div class="col-12">
                                <input type="text" class="form-control filter-datepicker mb-3" autocomplete="off" name="filter_expiry_start_date" id="filter_expiry_start_date" placeholder="Start Date">
                                <input type="text" class="form-control filter-datepicker" autocomplete="off" name="filter_expiry_end_date" id="filter_expiry_end_date" placeholder="End Date">
                              </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#ci-collateral-modal" data-bs-dismiss="modal">Close</button>
                <button class="btn btn-primary" id="apply-filter">Apply</button>
            </div>
        </div>
    </div>
</div>