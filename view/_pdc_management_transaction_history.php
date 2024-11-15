<div class="row">
  <div class="col-lg-12">
    <div class="ecom-wrapper">
      <div class="offcanvas offcanvas-start ecom-offcanvas" tabindex="-1" id="filter-canvas">
        <div class="offcanvas-body p-0 sticky-top">
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
                        <a class="btn border-0 px-0 text-start w-100" data-bs-toggle="collapse" href="#transaction-type-filter-collapse"><div class="float-end"><i class="ti ti-chevron-down"></i></div>
                          Transaction Type
                        </a>
                        <div class="collapse " id="transaction-type-filter-collapse">
                          <div class="row py-3">
                            <div class="col-12">
                              <div class="form-check my-2">
                                  <input class="form-check-input transaction-type-filter" type="checkbox" id="transaction-type-posted" value="Posted" />
                                  <label class="form-check-label" for="transaction-type-posted">Posted</label>
                              </div>
                              <div class="form-check my-2">
                                  <input class="form-check-input transaction-type-filter" type="checkbox" id="transaction-type-deposited" value="Deposited" />
                                  <label class="form-check-label" for="transaction-type-deposited">Deposited</label>
                              </div>
                              <div class="form-check my-2">
                                  <input class="form-check-input transaction-type-filter" type="checkbox" id="transaction-type-reversed" value="Reversed" />
                                  <label class="form-check-label" for="transaction-type-reversed">Reversed</label>
                              </div>
                            </div>
                          </div>
                        </div>
                      </li>
                      <li class="list-group-item px-0 py-2">
                        <a class="btn border-0 px-0 text-start w-100" data-bs-toggle="collapse" href="#mode-of-payment-filter-collapse"><div class="float-end"><i class="ti ti-chevron-down"></i></div>
                          Mode of Payment
                        </a>
                        <div class="collapse " id="mode-of-payment-filter-collapse">
                          <div class="row py-3">
                            <div class="col-12">
                              <div class="form-check my-2">
                                  <input class="form-check-input mode-of-payment-filter" type="checkbox" id="mode-of-payment-check" value="Check" />
                                  <label class="form-check-label" for="mode-of-payment-check">Check</label>
                              </div>
                              <div class="form-check my-2">
                                  <input class="form-check-input mode-of-payment-filter" type="checkbox" id="mode-of-payment-cash" value="Cash" />
                                  <label class="form-check-label" for="mode-of-payment-cash">Cash</label>
                              </div>
                              <div class="form-check my-2">
                                  <input class="form-check-input mode-of-payment-filter" type="checkbox" id="mode-of-payment-online-deposit" value="Online Deposit" />
                                  <label class="form-check-label" for="mode-of-payment-online-deposit">Online Deposit</label>
                              </div>
                              <div class="form-check my-2">
                                  <input class="form-check-input mode-of-payment-filter" type="checkbox" id="mode-of-payment-gcash" value="GCash" />
                                  <label class="form-check-label" for="mode-of-payment-gcash">GCash</label>
                              </div>
                            </div>
                          </div>
                        </div>
                      </li>
                      <li class="list-group-item px-0 py-2">
                        <a class="btn border-0 px-0 text-start w-100" data-bs-toggle="collapse" href="#transaction-date-filter-collapse"><div class="float-end"><i class="ti ti-chevron-down"></i></div>
                          Transaction Date
                        </a>
                        <div class="collapse" id="transaction-date-filter-collapse">
                          <div class="row py-3">
                            <div class="col-12">
                              <input type="text" class="form-control filter-datepicker mb-3" autocomplete="off" name="filter_transaction_date_start_date" id="filter_transaction_date_start_date" placeholder="Start Date" >
                              <input type="text" class="form-control filter-datepicker" autocomplete="off" name="filter_transaction_date_end_date" id="filter_transaction_date_end_date" placeholder="End Date" >
                            </div>
                          </div>
                        </div>
                      </li>
                      <li class="list-group-item px-0 py-2">
                        <a class="btn border-0 px-0 text-start w-100" data-bs-toggle="collapse" href="#reference-date-filter-collapse"><div class="float-end"><i class="ti ti-chevron-down"></i></div>
                          Reference Date
                        </a>
                        <div class="collapse" id="reference-date-filter-collapse">
                          <div class="row py-3">
                            <div class="col-12">
                              <input type="text" class="form-control filter-datepicker mb-3" autocomplete="off" name="filter_reference_date_start_date" id="filter_reference_date_start_date" placeholder="Start Date" >
                              <input type="text" class="form-control filter-datepicker" autocomplete="off" name="filter_reference_date_end_date" id="filter_reference_date_end_date" placeholder="End Date">
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
                <h5>Transaction History List</h5>
              </div>
              <div class="col-sm-6 text-sm-end mt-3 mt-sm-0">
                <button type="button" class="btn btn-warning" data-bs-toggle="offcanvas" data-bs-target="#filter-canvas">
                  Filter
                </button>
              </div>
            </div>
          </div>
          <div class="card-body">
            <div class="table-responsive dt-responsive">
                <table id="transaction-history-table" class="table table-hover nowrap w-100">
                    <thead>
                    <tr>
                        <th>Loan Number</th>
                        <th>Customer</th>
                        <th>Product</th>
                        <th>Payment Details</th>
                        <th>Check Number</th>
                        <th>Amount</th>
                        <th>Mode of Payment</th>
                        <th>Transaction Type</th>
                        <th>Transaction Date</th>
                        <th>Reference Number</th>
                        <th>Reference Date</th>
                        <th>Transaction By</th>
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
