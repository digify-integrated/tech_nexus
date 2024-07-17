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
                        <a class="btn border-0 px-0 text-start w-100" data-bs-toggle="collapse" href="#loan-repayments-status-filter-collapse"><div class="float-end"><i class="ti ti-chevron-down"></i></div>
                          PDC Status
                        </a>
                        <div class="collapse show" id="loan-repayments-status-filter-collapse">
                          <div class="row py-3">
                            <div class="col-12">
                              <div class="form-check my-2">
                                <input class="form-check-input loan-repayments-status-filter" type="radio" name="loan-repayments-status-filter" id="loan-repayments-status-all" value="" checked />
                                <label class="form-check-label" for="loan-repayments-status-all">All</label>
                              </div>
                              <div class="form-check my-2">
                                <input class="form-check-input loan-repayments-status-filter" type="radio" name="loan-repayments-status-filter" id="loan-repayments-status-pending" value="Pending" />
                                <label class="form-check-label" for="loan-repayments-status-pending">Pending</label>
                              </div>
                              <div class="form-check my-2">
                                <input class="form-check-input loan-repayments-status-filter" type="radio" name="loan-repayments-status-filter" id="loan-repayments-cleared" value="Cleared" />
                                <label class="form-check-label" for="loan-repayments-cleared">Cleared</label>
                              </div>
                              <div class="form-check my-2">
                                <input class="form-check-input loan-repayments-status-filter" type="radio" name="loan-repayments-status-filter" id="loan-repayments-on-hold" value="On-Hold" />
                                <label class="form-check-label" for="loan-repayments-on-hold">On-Hold</label>
                              </div>
                              <div class="form-check my-2">
                                <input class="form-check-input loan-repayments-status-filter" type="radio" name="loan-repayments-status-filter" id="loan-repayments-reversed" value="Reversed" />
                                <label class="form-check-label" for="loan-repayments-reversed">Reversed</label>
                              </div>
                              <div class="form-check my-2">
                                <input class="form-check-input loan-repayments-status-filter" type="radio" name="loan-repayments-status-filter" id="loan-repayments-cancelled" value="Cancelled" />
                                <label class="form-check-label" for="loan-repayments-cancelled">Cancelled</label>
                              </div>
                              <div class="form-check my-2">
                                <input class="form-check-input loan-repayments-status-filter" type="radio" name="loan-repayments-status-filter" id="loan-repayments-redeposit" value="Redeposit" />
                                <label class="form-check-label" for="loan-repayments-redeposit">Redeposit</label>
                              </div>
                              <div class="form-check my-2">
                                <input class="form-check-input loan-repayments-status-filter" type="radio" name="loan-repayments-status-filter" id="loan-repayments-for-deposit" value="For Deposit" />
                                <label class="form-check-label" for="loan-repayments-for-deposit">For Deposit</label>
                              </div>
                              <div class="form-check my-2">
                                <input class="form-check-input loan-repayments-status-filter" type="radio" name="loan-repayments-status-filter" id="loan-repayments-pulled-out" value="Pulled-Out" />
                                <label class="form-check-label" for="loan-repayments-pulled-out">Pulled-Out</label>
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
                <h5>PDC Monitoring List</h5>
              </div>
              <div class="col-sm-6 text-sm-end mt-3 mt-sm-0">
                <?php 
                  if($pdcMonitoringCreateAccess['total'] > 0){
                    echo '<a href="pdc-monitoring.php?new" class="btn btn-success">Create</a>';
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
              <table id="pdc-monitoring-table" class="table table-hover nowrap w-100 text-uppercase">
                <thead>
                  <tr>
                    <th>Loan Number</th>
                    <th>Payment Details</th>
                    <th>Check Number</th>
                    <th>Check Date</th>
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