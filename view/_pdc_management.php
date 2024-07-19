<div class="row">
  <div class="col-lg-12">
    <div class="ecom-wrapper">
      <div class="offcanvas-xxl offcanvas-start ecom-offcanvas" tabindex="-1" id="filter-canvas">
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
                        <a class="btn border-0 px-0 text-start w-100" data-bs-toggle="collapse" href="#check-date-filter-collapse"><div class="float-end"><i class="ti ti-chevron-down"></i></div>
                          Check Date
                        </a>
                        <div class="collapse show" id="check-date-filter-collapse">
                          <div class="row py-3">
                            <div class="col-12">
                              <input type="text" class="form-control filter-datepicker mb-3" autocomplete="off" name="filter_check_date_start_date" id="filter_check_date_start_date" placeholder="Start Date">
                              <input type="text" class="form-control filter-datepicker" autocomplete="off" name="filter_check_date_end_date" id="filter_check_date_end_date" placeholder="End Date">
                            </div>
                          </div>
                        </div>
                      </li>
                      <li class="list-group-item px-0 py-2">
                        <a class="btn border-0 px-0 text-start w-100" data-bs-toggle="collapse" href="#pdc-management-status-filter-collapse"><div class="float-end"><i class="ti ti-chevron-down"></i></div>
                          PDC Status
                        </a>
                        <div class="collapse show" id="pdc-management-status-filter-collapse">
                          <div class="row py-3">
                            <div class="col-12">
                              <div class="form-check my-2">
                                <input class="form-check-input pdc-management-status-filter" type="radio" name="pdc-management-status-filter" id="pdc-management-status-all" value="" checked />
                                <label class="form-check-label" for="pdc-management-status-all">All</label>
                              </div>
                              <div class="form-check my-2">
                                <input class="form-check-input pdc-management-status-filter" type="radio" name="pdc-management-status-filter" id="pdc-management-status-pending" value="Pending" />
                                <label class="form-check-label" for="pdc-management-status-pending">Pending</label>
                              </div>
                              <div class="form-check my-2">
                                <input class="form-check-input pdc-management-status-filter" type="radio" name="pdc-management-status-filter" id="pdc-management-cleared" value="Cleared" />
                                <label class="form-check-label" for="pdc-management-cleared">Cleared</label>
                              </div>
                              <div class="form-check my-2">
                                <input class="form-check-input pdc-management-status-filter" type="radio" name="pdc-management-status-filter" id="pdc-management-on-hold" value="On-Hold" />
                                <label class="form-check-label" for="pdc-management-on-hold">On-Hold</label>
                              </div>
                              <div class="form-check my-2">
                                <input class="form-check-input pdc-management-status-filter" type="radio" name="pdc-management-status-filter" id="pdc-management-reversed" value="Reversed" />
                                <label class="form-check-label" for="pdc-management-reversed">Reversed</label>
                              </div>
                              <div class="form-check my-2">
                                <input class="form-check-input pdc-management-status-filter" type="radio" name="pdc-management-status-filter" id="pdc-management-cancelled" value="Cancelled" />
                                <label class="form-check-label" for="pdc-management-cancelled">Cancelled</label>
                              </div>
                              <div class="form-check my-2">
                                <input class="form-check-input pdc-management-status-filter" type="radio" name="pdc-management-status-filter" id="pdc-management-redeposit" value="Redeposit" />
                                <label class="form-check-label" for="pdc-management-redeposit">Redeposit</label>
                              </div>
                              <div class="form-check my-2">
                                <input class="form-check-input pdc-management-status-filter" type="radio" name="pdc-management-status-filter" id="pdc-management-for-deposit" value="For Deposit" />
                                <label class="form-check-label" for="pdc-management-for-deposit">For Deposit</label>
                              </div>
                              <div class="form-check my-2">
                                <input class="form-check-input pdc-management-status-filter" type="radio" name="pdc-management-status-filter" id="pdc-management-deposited" value="Deposited" />
                                <label class="form-check-label" for="pdc-management-deposited">Deposited</label>
                              </div>
                              <div class="form-check my-2">
                                <input class="form-check-input pdc-management-status-filter" type="radio" name="pdc-management-status-filter" id="pdc-management-pulled-out" value="Pulled-Out" />
                                <label class="form-check-label" for="pdc-management-pulled-out">Pulled-Out</label>
                              </div>
                            </div>
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
      <div class="ecom-content w-100">
        <div class="card table-card">
          <div class="card-header">
            <div class="row align-items-center">
              <div class="col-sm-6">
                <h5>PDC Management List</h5>
              </div>
              <div class="col-sm-6 text-sm-end mt-3 mt-sm-0">
                <?php                 
                  if($pdcManagementCreateAccess['total'] > 0 || $pdcManagementDeleteAccess['total'] > 0 || $tagPDCAsDeposited['total'] > 0 || $tagPDCAsForDeposit['total'] > 0 || $tagPDCAsCleared['total'] > 0 || $pdcManagementCreateAccess['total'] > 0){
                    $action = '<div class="btn-group m-r-10">
                                      <button type="button" class="btn btn-outline-secondary dropdown-toggle d-none action-dropdown" data-bs-toggle="dropdown" aria-expanded="false">Action</button>
                                      <ul class="dropdown-menu dropdown-menu-end">';
                                  
                    if($pdcManagementDeleteAccess['total'] > 0){
                      $action .= '<li><button class="dropdown-item" type="button" id="delete-pdc">Delete PDC</button></li>';
                    }

                    if($tagPDCAsDeposited['total'] > 0){
                      $action .= '<li><button class="dropdown-item" type="button" id="tag-pdc-as-deposited">Tag As Deposited</button></li>';
                    }

                    if($tagPDCAsForDeposit['total'] > 0){
                      $action .= '<li><button class="dropdown-item" type="button" id="tag-pdc-as-for-deposit">Tag As For Deposit</button></li>';
                    }

                    if($tagPDCAsCleared['total'] > 0){
                      $action .= '<li><button class="dropdown-item" type="button" id="tag-pdc-as-cleared">Tag As Cleared</button></li>';
                    }

                    $action .= '</ul>
                                    </div>';

                    if($pdcManagementCreateAccess['total'] > 0){
                      $action .= '<a href="pdc-management.php?new" class="btn btn-success">Create</a>';
                    }
                                  
                    echo $action;
                  }
                ?>
                <button type="button" class="btn btn-info" data-bs-toggle="offcanvas" data-bs-target="#import-offcanvas" aria-controls="import-offcanvas">
                  Import
                </button>
                <button type="button" class="d-xxl-none btn btn-warning" data-bs-toggle="offcanvas" data-bs-target="#filter-canvas">
                  Filter
                </button>
              </div>
            </div>
          </div>
          <div class="card-body">
            <div class="table-responsive dt-responsive">
              <table id="pdc-management-table" class="table table-hover nowrap w-100 text-uppercase">
                <thead>
                  <tr>
                    <th class="all">
                      <div class="form-check">
                        <input class="form-check-input" id="datatable-checkbox" type="checkbox">
                      </div>
                    </th>
                    <th>Loan Number</th>
                    <th>Customer</th>
                    <th>Product</th>
                    <th>Payment Details</th>
                    <th>Check Number</th>
                    <th>Check Date</th>
                    <th>Redeposit Date</th>
                    <th>Payment Amount</th>
                    <th>Bank/Branch</th>
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
  </div>
</div>

<div>
    <div class="offcanvas offcanvas-end" tabindex="-1" id="import-offcanvas" aria-labelledby="import-offcanvas-label">
      <div class="offcanvas-header">
        <h2 id="import-offcanvas-label" style="margin-bottom:-0.5rem">Import PDC</h2>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
      </div>
    <div class="offcanvas-body">
      <div class="row">
        <div class="col-lg-12">
          <form id="import-form" method="post" action="#">
            <div class="form-group row">
              <div class="col-lg-12 mt-3 mt-lg-0">
                <label class="form-label">Import File <span class="text-danger">*</span></label>
                <input type="file" class="form-control" id="import_file" name="import_file">
              </div>
            </div>
          </form>
        </div>
      </div>
      <div class="row">
        <div class="col-lg-12">
          <button type="submit" class="btn btn-primary" id="submit-import" form="import-form">Submit</button>
          <button class="btn btn-light-danger" data-bs-dismiss="offcanvas"> Close </button>
        </div>
      </div>
    </div>
  </div>