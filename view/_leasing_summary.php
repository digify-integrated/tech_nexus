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
                          Leasing Status
                        </a>
                        <div class="collapse show" id="leasing-application-status-filter-collapse">
                          <div class="row py-3">
                            <div class="col-12">
                              <div class="form-check my-2">
                                <input class="form-check-input leasing-application-status-filter" type="radio" name="leasing-application-status-filter" id="leasing-application-status-all" value="" checked />
                                <label class="form-check-label" for="leasing-application-status-all">All</label>
                              </div>
                              <div class="form-check my-2">
                                <input class="form-check-input leasing-application-status-filter" type="radio" name="leasing-application-status-filter" id="leasing-application-status-active" value="Active" checked/>
                                <label class="form-check-label" for="leasing-application-status-active">Active</label>
                              </div>
                              <div class="form-check my-2">
                                <input class="form-check-input leasing-application-status-filter" type="radio" name="leasing-application-status-filter" id="leasing-application-status-closed" value="Closed" />
                                <label class="form-check-label" for="leasing-application-status-closed">Closed</label>
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
                <h5>Leasing List</h5>
              </div>
              <div class="col-sm-6 text-sm-end mt-3 mt-sm-0">
                <button type="button" class="d-xxl-none btn btn-warning" data-bs-toggle="offcanvas" data-bs-target="#filter-canvas">
                  Filter
                </button>
              </div>
            </div>
          </div>
          <div class="card-body">
            <div class="row">
              <div class="col-xl-12">
                <div class="table-responsive dt-responsive">
                  <table id="leasing-summary-table" class="table table-hover nowrap w-100 text-uppercase">
                    <thead>
                      <tr>
                        <th>Tenant</th>
                        <th>Property</th>
                        <th>Total Amount Due</th>
                        <th>Unpaid Rental</th>
                        <th>Unpaid Electricity</th>
                        <th>Unpaid Water</th>
                        <th>Unpaid Charges</th>
                        <th>Floor Area</th>
                        <th>Term</th>
                        <th>Inception Date</th>
                        <th>Maturity</th>
                        <th>Security Deposit</th>
                        <th>Esc. Rate</th>
                        <th>Status</th>
                        <th>Initial Basic Rental</th>
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
  </div>
</div>