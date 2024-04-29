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
                        <a class="btn border-0 px-0 text-start w-100" data-bs-toggle="collapse" href="#leasing-application-status-filter-collapse"><div class="float-end"><i class="ti ti-chevron-down"></i></div>
                          Leasing Application Status
                        </a>
                        <div class="collapse show" id="leasing-application-status-filter-collapse">
                          <div class="row py-3">
                            <div class="col-12">
                              <div class="form-check my-2">
                                <input class="form-check-input leasing-application-status-filter" type="radio" name="leasing-application-status-filter" id="leasing-application-status-all" value="" checked />
                                <label class="form-check-label" for="leasing-application-status-all">All</label>
                              </div>
                              <div class="form-check my-2">
                                <input class="form-check-input leasing-application-status-filter" type="radio" name="leasing-application-status-filter" id="leasing-application-status-draft" value="Draft" />
                                <label class="form-check-label" for="leasing-application-status-draft">Draft</label>
                              </div>
                              <div class="form-check my-2">
                                <input class="form-check-input leasing-application-status-filter" type="radio" name="leasing-application-status-filter" id="leasing-application-approved" value="Approved" />
                                <label class="form-check-label" for="leasing-application-approved">Approved</label>
                              </div>
                              <div class="form-check my-2">
                                <input class="form-check-input leasing-application-status-filter" type="radio" name="leasing-application-status-filter" id="leasing-application-rejected" value="Rejected" />
                                <label class="form-check-label" for="leasing-application-rejected">Rejected</label>
                              </div>
                              <div class="form-check my-2">
                                <input class="form-check-input leasing-application-status-filter" type="radio" name="leasing-application-status-filter" id="leasing-application-cancelled" value="Cancelled" />
                                <label class="form-check-label" for="leasing-application-cancelled">Cancelled</label>
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
                <h5>Leasing Application List</h5>
              </div>
              <div class="col-sm-6 text-sm-end mt-3 mt-sm-0">
                <?php
                  if($leasingApplicationCreateAccess['total'] > 0 || $leasingApplicationDeleteAccess['total'] > 0){
                    $action = '';
                                  
                      if($leasingApplicationDeleteAccess['total'] > 0){
                        $action .= '<div class="btn-group m-r-10">
                                      <button type="button" class="btn btn-outline-secondary dropdown-toggle d-none action-dropdown" data-bs-toggle="dropdown" aria-expanded="false">Action</button>
                                      <ul class="dropdown-menu dropdown-menu-end">
                                        <li><button class="dropdown-item" type="button" id="delete-leasing-application">Delete Leasing Application</button></li>
                                      </ul>
                                    </div>';
                      }
    
                    if($leasingApplicationCreateAccess['total'] > 0){
                      $action .= '<a href="leasing-application.php?new" class="btn btn-success">Create</a>';
                    }
                                  
                    echo $action;
                  }
                ?>
                <button type="button" class="d-xxl-none btn btn-warning" data-bs-toggle="offcanvas" data-bs-target="#filter-canvas">
                  Filter
                </button>
              </div>
            </div>
          </div>
          <div class="card-body">
            <div class="table-responsive dt-responsive">
              <input type="hidden" id="customer_id" value="<?php echo $customerID; ?>">
              <table id="leasing-application-table" class="table table-hover nowrap w-100 text-uppercase">
                <thead>
                  <tr>
                    <th class="all">
                      <div class="form-check">
                        <input class="form-check-input" id="datatable-checkbox" type="checkbox">
                      </div>
                    </th>
                    <th>Leasing Application Number</th>
                    <th>Tenant</th>
                    <th>Property</th>
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