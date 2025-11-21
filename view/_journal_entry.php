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
                        <a class="btn border-0 px-0 text-start w-100" data-bs-toggle="collapse" href="#journal-entry-date-filter-collapse"><div class="float-end"><i class="ti ti-chevron-down"></i></div>
                          Journal Entry Date
                        </a>
                        <div class="collapse" id="journal-entry-date-filter-collapse">
                          <div class="row py-3">
                            <div class="col-12">
                              <input type="text" class="form-control filter-datepicker mb-3" autocomplete="off" name="filter_journal_entry_date_start_date" id="filter_journal_entry_date_start_date" placeholder="Start Date" value="<?php echo date('m/d/Y'); ?>">
                              <input type="text" class="form-control filter-datepicker" autocomplete="off" name="filter_journal_entry_date_end_date" id="filter_journal_entry_date_end_date" placeholder="End Date" value="<?php echo date('m/d/Y'); ?>">
                            </div>
                          </div>
                        </div>
                      </li>
                      <li class="list-group-item px-0 py-2">
                        <a class="btn border-0 px-0 text-start w-100" data-bs-toggle="collapse" href="#journal-id-filter-collapse"><div class="float-end"><i class="ti ti-chevron-down"></i></div>
                          Journal ID
                        </a>
                        <div class="collapse" id="journal-id-filter-collapse">
                          <div class="row py-3">
                            <div class="col-12">
                              <div class="form-check my-2">
                                <input class="form-check-input journal-id-checkbox" type="checkbox" id="journal-id-1" value="Miscellaneous Operations" checked/>
                                <label class="form-check-label" for="journal-id-1">Miscellaneous Operations</label>
                              </div>
                              <div class="form-check my-2">
                                <input class="form-check-input journal-id-checkbox" type="checkbox" id="journal-id-2" value="Check Disbursement Operations" checked/>
                                <label class="form-check-label" for="journal-id-2">Check Disbursement Operations</label>
                              </div>
                              <div class="form-check my-2">
                                <input class="form-check-input journal-id-checkbox" type="checkbox" id="journal-id-3" value="Disbursement Operations" checked/>
                                <label class="form-check-label" for="journal-id-3">Disbursement Operations</label>
                              </div>
                              <div class="form-check my-2">
                                <input class="form-check-input journal-id-checkbox" type="checkbox" id="journal-id-4" value="Liquidation Operations" checked/>
                                <label class="form-check-label" for="journal-id-4">Liquidation Operations</label>
                              </div>
                              <div class="form-check my-2">
                                <input class="form-check-input journal-id-checkbox" type="checkbox" id="journal-id-5" value="Parts Purchase" checked/>
                                <label class="form-check-label" for="journal-id-5">Parts Purchase</label>
                              </div>
                              <div class="form-check my-2">
                                <input class="form-check-input journal-id-checkbox" type="checkbox" id="journal-id-6" value="Parts Issuance" checked/>
                                <label class="form-check-label" for="journal-id-6">Parts Issuance</label>
                              </div>
                              <div class="form-check my-2">
                                <input class="form-check-input journal-id-checkbox" type="checkbox" id="journal-id-7" value="Job Order" checked/>
                                <label class="form-check-label" for="journal-id-7">Job Order</label>
                              </div>
                              <div class="form-check my-2">
                                <input class="form-check-input journal-id-checkbox" type="checkbox" id="journal-id-8" value="Additional Job Order" checked/>
                                <label class="form-check-label" for="journal-id-8">Additional Job Order</label>
                              </div>
                              <div class="form-check my-2">
                                <input class="form-check-input journal-id-checkbox" type="checkbox" id="journal-id-9" value="Internal Job Order" checked/>
                                <label class="form-check-label" for="journal-id-9">Internal Job Order</label>
                              </div>
                              <div class="form-check my-2">
                                <input class="form-check-input journal-id-checkbox" type="checkbox" id="journal-id-10" value="Internal Additional Job Order" checked/>
                                <label class="form-check-label" for="journal-id-10">Internal Additional Job Order</label>
                              </div>
                              <div class="form-check my-2">
                                <input class="form-check-input journal-id-checkbox" type="checkbox" id="journal-id-11" value="Stock Transfer Advice" checked/>
                                <label class="form-check-label" for="journal-id-11">Stock Transfer Advice</label>
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
                <h5>Journal Entry List</h5>
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
              <table id="journal-entry-table" class="table table-hover text-wrap w-100">
                <thead>
                  <tr>
                    <th>Loan Number</th>
                    <th>Date</th>
                    <th>Ref</th>
                    <th>Journal ID</th>
                    <th>Journal Items/Account</th>
                    <th>Line_IDs/Debit</th>
                    <th>Line_IDs/Credit</th>
                    <th>Journal Items/Label</th>
                    <th>Journal Items/Analytic lines/Name</th>
                    <th>Journal Items/Analytic Distribution</th>
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