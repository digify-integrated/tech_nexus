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
                        <a class="btn border-0 px-0 text-start w-100" data-bs-toggle="collapse" href="#release-date-filter-collapse"><div class="float-end"><i class="ti ti-chevron-down"></i></div>
                          Release Date
                        </a>
                        <div class="collapse" id="release-date-filter-collapse">
                          <div class="row py-3">
                            <div class="col-12">
                              <input type="text" class="form-control filter-datepicker mb-3" autocomplete="off" name="filter_release_date_start_date" id="filter_release_date_start_date" placeholder="Start Date" value="<?php echo date('m/d/Y'); ?>">
                              <input type="text" class="form-control filter-datepicker" autocomplete="off" name="filter_release_date_end_date" id="filter_release_date_end_date" placeholder="End Date" value="<?php echo date('m/d/Y'); ?>">
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
                <h5>Loan Extraction List</h5>
              </div>
              <div class="col-sm-6 text-sm-end mt-3 mt-sm-0" >
                <button type="button" class="btn btn-warning" data-bs-toggle="offcanvas" data-bs-target="#filter-canvas">
                  Filter
                </button>
              </div>
            </div>
          </div>
          <div class="card-body">
            <div class="table-responsive dt-responsive">
              <table id="loan-extraction-table" class="table table-hover text-wrap w-100">
                <thead>
                  <tr>
                    <th>Borrower Unique Number</th>
                    <th>Loan No.</th>
                    <th>Application No.</th>
                    <th>Loan Product</th>
                    <th>Disbursed By</th>
                    <th>Outstanding Balance</th>
                    <th>Actual Release Date</th>
                    <th>First Repayment Date</th>
                    <th>Loan Interest Rate</th>
                    <th>Loan Interest Period</th>
                    <th>Loan Duration</th>
                    <th>Loan Duration Period</th>
                    <th>Repayment Cycle</th>
                    <th>Number of Repayments</th>
                    <th>Total Delivery Price</th>
                    <th>Stock No.</th>
                    <th>Release Remarks</th>
                    <th>Sales Partner</th>
                    <th>Subproduct</th>
                    <th>Downpayment</th>
                    <th>Insurance</th>
                    <th>Registration Fee</th>
                    <th>Handling Fee</th>
                    <th>Transfer Fee</th>
                    <th>Doc. Stamp Tax</th>
                    <th>Transaction Fee</th>
                    <th>Deposit</th>
                    <th>Insurance Schedule</th>
                    <th>Registration Fee Schedule</th>
                    <th>Handling Fee Schedule</th>
                    <th>Transfer Fee Schedule</th>
                    <th>Doc. Stamp Tax Schedule</th>
                    <th>Transfer Fee Schedule</th>
                    <th>Deposit Schedule</th>
                    <th>Product Cost</th>
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