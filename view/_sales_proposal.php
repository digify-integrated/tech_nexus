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
                        <a class="btn border-0 px-0 text-start w-100" data-bs-toggle="collapse" href="#sales-proposal-status-filter-collapse"><div class="float-end"><i class="ti ti-chevron-down"></i></div>
                          Sales Proposal Status
                        </a>
                        <div class="collapse show" id="sales-proposal-status-filter-collapse">
                          <div class="row py-3">
                            <div class="col-12">
                              <div class="form-check my-2">
                                <input class="form-check-input sales-proposal-status-filter" type="radio" name="sales-proposal-status-filter" id="sales-proposal-status-all" value="" checked />
                                <label class="form-check-label" for="sales-proposal-status-all">All</label>
                              </div>
                              <div class="form-check my-2">
                                <input class="form-check-input sales-proposal-status-filter" type="radio" name="sales-proposal-status-filter" id="sales-proposal-status-draft" value="Draft" />
                                <label class="form-check-label" for="sales-proposal-status-draft">Draft</label>
                              </div>
                              <div class="form-check my-2">
                                <input class="form-check-input sales-proposal-status-filter" type="radio" name="sales-proposal-status-filter" id="sales-proposal-status-for-review" value="For Review" />
                                <label class="form-check-label" for="sales-proposal-status-for-review">Draft</label>
                              </div>
                              <div class="form-check my-2">
                                <input class="form-check-input sales-proposal-status-filter" type="radio" name="sales-proposal-status-filter" id="sales-proposal-status-for-initial-approval" value="For Initial Approval" />
                                <label class="form-check-label" for="sales-proposal-status-for-initial-approval">For Initial Approval</label>
                              </div>
                              <div class="form-check my-2">
                                <input class="form-check-input sales-proposal-status-filter" type="radio" name="sales-proposal-status-filter" id="sales-proposal-status-for-final-approval" value="For Final Approval" />
                                <label class="form-check-label" for="sales-proposal-status-for-final-approval">For Final Approval</label>
                              </div>
                              <div class="form-check my-2">
                                <input class="form-check-input sales-proposal-status-filter" type="radio" name="sales-proposal-status-filter" id="sales-proposal-status-for-ci" value="For CI" />
                                <label class="form-check-label" for="sales-proposal-status-for-ci">For CI</label>
                              </div>
                              <div class="form-check my-2">
                                <input class="form-check-input sales-proposal-status-filter" type="radio" name="sales-proposal-status-filter" id="sales-proposal-proceed" value="Proceed" />
                                <label class="form-check-label" for="sales-proposal-proceed">Proceed</label>
                              </div>
                              <div class="form-check my-2">
                                <input class="form-check-input sales-proposal-status-filter" type="radio" name="sales-proposal-status-filter" id="sales-proposal-on-process" value="On-Process" />
                                <label class="form-check-label" for="sales-proposal-on-process">On-Process</label>
                              </div>
                              <div class="form-check my-2">
                                <input class="form-check-input sales-proposal-status-filter" type="radio" name="sales-proposal-status-filter" id="sales-proposal-ready-for-release" value="Ready For Release" />
                                <label class="form-check-label" for="sales-proposal-ready-for-release">Ready For Release</label>
                              </div>
                              <div class="form-check my-2">
                                <input class="form-check-input sales-proposal-status-filter" type="radio" name="sales-proposal-status-filter" id="sales-proposal-for-dr" value="For DR" />
                                <label class="form-check-label" for="sales-proposal-for-dr">For DR</label>
                              </div>
                              <div class="form-check my-2">
                                <input class="form-check-input sales-proposal-status-filter" type="radio" name="sales-proposal-status-filter" id="sales-proposal-rejected" value="Rejected" />
                                <label class="form-check-label" for="sales-proposal-rejected">Rejected</label>
                              </div>
                              <div class="form-check my-2">
                                <input class="form-check-input sales-proposal-status-filter" type="radio" name="sales-proposal-status-filter" id="sales-proposal-cancelled" value="Cancelled" />
                                <label class="form-check-label" for="sales-proposal-cancelled">Cancelled</label>
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
                <h5>Sales Proposal List</h5>
              </div>
              <div class="col-sm-6 text-sm-end mt-3 mt-sm-0">
                <?php
                  if($addSalesProposal['total'] > 0 || $deleteSalesProposal['total'] > 0){
                    $action = '';
                                  
                      if($deleteSalesProposal['total'] > 0){
                        $action .= '<div class="btn-group m-r-10">
                                      <button type="button" class="btn btn-outline-secondary dropdown-toggle d-none action-dropdown" data-bs-toggle="dropdown" aria-expanded="false">Action</button>
                                      <ul class="dropdown-menu dropdown-menu-end">
                                        <li><button class="dropdown-item" type="button" id="delete-sales-proposal">Delete Sales Proposal</button></li>
                                      </ul>
                                    </div>';
                      }
    
                    if($addSalesProposal['total'] > 0){
                      $action .= '<a href="sales-proposal.php?new&customer='. $securityModel->encryptData($customerID) .'" class="btn btn-success">Create</a>';
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
              <table id="sales-proposal-table" class="table table-hover nowrap w-100 text-uppercase">
                <thead>
                  <tr>
                    <th class="all">
                      <div class="form-check">
                        <input class="form-check-input" id="datatable-checkbox" type="checkbox">
                      </div>
                    </th>
                    <th>Sales Proposal Number</th>
                    <th>Customer</th>
                    <th>Product Type</th>
                    <th>Stock</th>
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