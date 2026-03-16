<div class="row">
  <div class="col-lg-12">
    <div class="ecom-wrapper">
      <div class="ecom-content w-100">
        <div class="card table-card">
          <div class="card-header">
            <div class="row align-items-center">
              <div class="col-sm-6">
                <h5>Liquidation List</h5>
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
              <table id="liquidation-table" class="table table-hover text-wrap w-100 text-uppercase">
                <thead>
                  <tr>
                    <th>Particulars</th>
                    <th>CDV Number</th>
                    <th>Customer</th>
                    <th>Transaction Date</th>
                    <th>Balance</th>
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
            <div>
              <div class="card-body">
                <ul class="list-group list-group-flush">
                  <li class="list-group-item px-0 py-2 d-none">
                    <a class="btn border-0 px-0 text-start w-100" data-bs-toggle="collapse" href="#payroll-deduction-filter-collapse"><div class="float-end"><i class="ti ti-chevron-down"></i></div>
                      Is Payroll Deduction?
                    </a>
                    <div class="collapse show" id="payroll-deduction-filter-collapse">
                      <div class="row py-3">
                        <div class="col-12">
                          <select class="form-control" id="payroll-deduction-filter">
                            <option value="">--</option>
                            <option value="Yes">Yes</option>
                            <option value="No" selected>No</option>
                          </select>
                        </div>
                      </div>
                    </div>
                  </li>
                  <li class="list-group-item px-0 py-2">
                    <a class="btn border-0 px-0 text-start w-100" data-bs-toggle="collapse" href="#transaction-date-filter-collapse"><div class="float-end"><i class="ti ti-chevron-down"></i></div>
                      Transaction Date
                    </a>
                    <div class="collapse show" id="transaction-date-filter-collapse">
                      <div class="row py-3">
                        <div class="col-12">
                          <input type="text" class="form-control filter-datepicker mb-3" autocomplete="off" name="filter_transaction_date_start_date" id="filter_transaction_date_start_date" placeholder="Start Date">
                          <input type="text" class="form-control filter-datepicker" autocomplete="off" name="filter_transaction_date_end_date" id="filter_transaction_date_end_date" placeholder="End Date" >
                        </div>
                      </div>
                    </div>
                  </li>
                  <li class="list-group-item px-0 py-2">
                    <a class="btn border-0 px-0 text-start w-100" data-bs-toggle="collapse" href="#balance-filter-collapse"><div class="float-end"><i class="ti ti-chevron-down"></i></div>
                      Balance
                    </a>
                    <div class="collapse show" id="balance-filter-collapse">
                      <div class="row py-3">
                        <div class="col-12">
                          <select class="form-control" id="balance-filter">
                            <option value="">--</option>
                            <option value="With Balance" selected>With Balance</option>
                            <option value="Without Balance">Without Balance</option>
                          </select>
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
</div>