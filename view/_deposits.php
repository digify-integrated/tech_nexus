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
                        <a class="btn border-0 px-0 text-start w-100" data-bs-toggle="collapse" href="#transaction-date-filter-collapse"><div class="float-end"><i class="ti ti-chevron-down"></i></div>
                          Transaction Date
                        </a>
                        <div class="collapse " id="transaction-date-filter-collapse">
                          <div class="row py-3">
                            <div class="col-12">
                              <input type="text" class="form-control filter-datepicker mb-3" autocomplete="off" name="filter_transaction_date_start_date" id="filter_transaction_date_start_date" placeholder="Start Date">
                              <input type="text" class="form-control filter-datepicker" autocomplete="off" name="filter_transaction_date_end_date" id="filter_transaction_date_end_date" placeholder="End Date" >
                            </div>
                          </div>
                        </div>
                      </li>
                      <li class="list-group-item px-0 py-2">
                        <a class="btn border-0 px-0 text-start w-100" data-bs-toggle="collapse" href="#deposit-date-filter-collapse"><div class="float-end"><i class="ti ti-chevron-down"></i></div>
                          Deposit Date
                        </a>
                        <div class="collapse " id="deposit-date-filter-collapse">
                          <div class="row py-3">
                            <div class="col-12">
                              <input type="text" class="form-control filter-datepicker mb-3" autocomplete="off" name="filter_deposit_date_start_date" id="filter_deposit_date_start_date" placeholder="Start Date">
                              <input type="text" class="form-control filter-datepicker" autocomplete="off" name="filter_deposit_date_end_date" id="filter_deposit_date_end_date" placeholder="End Date">
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
                <h5>Deposits List</h5>
              </div>
              <div class="col-sm-6 text-sm-end mt-3 mt-sm-0">
                <?php               
                  if($depositsCreateAccess['total'] > 0){
                   echo '<a href="deposits.php?new" class="btn btn-success">Create</a>';
                  }
                ?>
                <button type="button" class="btn btn-warning" data-bs-toggle="offcanvas" data-bs-target="#print-offcanvas">
                  Print
                </button>
                <button type="button" class="btn btn-warning" data-bs-toggle="offcanvas" data-bs-target="#filter-canvas">
                  Filter
                </button>
              </div>
            </div>
          </div>
          <div class="card-body">
            <div class="table-responsive dt-responsive">
              <table id="deposits-table" class="table table-hover text-wrap w-100 text-uppercase">
                <thead>
                  <tr>
                    <th class="all">
                      <div class="form-check">
                        <input class="form-check-input" id="datatable-checkbox" type="checkbox">
                      </div>
                    </th>
                    <th>Deposit Amount</th>
                    <th>Deposit Date</th>
                    <th>Deposited To</th>
                    <th>Reference Number</th>
                    <th>Transaction Date</th>
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
    <div class="offcanvas offcanvas-end" tabindex="-1" id="print-offcanvas" aria-labelledby="print-offcanvas-label">
      <div class="offcanvas-header">
        <h2 id="print-offcanvas-label" style="margin-bottom:-0.5rem">Print</h2>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
      </div>
    <div class="offcanvas-body">
      <div class="row">
        <div class="col-lg-12">
          <form id="print-form" method="post" action="#">
            <div class="form-group row">
              <div class="col-lg-12 mt-3 mt-lg-0">
                <label class="form-label">Collection Report Date</label>
                <div class="col-12">
                  <input type="text" class="form-control filter-datepicker mb-3" autocomplete="off" name="collection_report_date" id="collection_report_date" placeholder="Collection Report Date">
                </div>
              </div>
            </div>
            <div class="form-group row">
              <div class="col-lg-12 mt-3 mt-lg-0">
                <label class="form-label">Company</label>
                <div class="col-12">
                  <div class="form-check my-2">
                    <input class="form-check-input company-checkbox" type="checkbox" id="company-cgmi" value="1"/>
                    <label class="form-check-label" for="company-cgmi">Christian General Motors Inc.</label>
                  </div>
                  <div class="form-check my-2">
                    <input class="form-check-input company-checkbox" type="checkbox" id="company-ne-truck" value="2"/>
                    <label class="form-check-label" for="company-ne-truck">NE Truck Builders</label>
                  </div>
                  <div class="form-check my-2">
                    <input class="form-check-input company-checkbox" type="checkbox" id="company-fuso-tarlac" value="3"/>
                    <label class="form-check-label" for="company-nefuso-tarlac">FUSO Tarlac</label>
                  </div>
                </div>
              </div>
            </div>
          </form>
        </div>
      </div>
      <div class="row">
        <div class="col-lg-12">
          <button type="button" class="btn btn-success" id="print" form="print-form">Print</button>
          <button class="btn btn-light-danger" data-bs-dismiss="offcanvas"> Close </button>
        </div>
      </div>
    </div>
  </div>