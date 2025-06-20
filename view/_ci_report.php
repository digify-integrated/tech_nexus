<div class="row">
  <div class="col-lg-12">
    <div class="ecom-wrapper">
      <div class="offcanvas offcanvas-start ecom-offcanvas" tabindex="-1" id="filter-canvas">
        <div class="offcanvas-body p-0">
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
                        <a class="btn border-0 px-0 text-start w-100" data-bs-toggle="collapse" href="#ci-report-status-filter-collapse"><div class="float-end"><i class="ti ti-chevron-down"></i></div>
                          CI Report Status
                        </a>
                        <div class="collapse" id="ci-report-status-filter-collapse">
                          <div class="row py-3">
                            <div class="col-12">
                              <div class="form-check my-2">
                                <input class="form-check-input ci-report-status-filter" type="checkbox" id="ci-report-status-draft" value="Draft"/>
                                <label class="form-check-label" for="ci-report-status-draft">Draft</label>
                              </div>
                              <div class="form-check my-2">
                                <input class="form-check-input ci-report-status-filter" type="checkbox" id="ci-report-status-for-completion" value="For Completion"/>
                                <label class="form-check-label" for="ci-report-status-for-completion">For Completion</label>
                              </div>
                              <div class="form-check my-2">
                                <input class="form-check-input ci-report-status-filter" type="checkbox" id="ci-report-status-completed" value="Completed"/>
                                <label class="form-check-label" for="ci-report-status-completed">Completed</label>
                              </div>
                            </div>
                          </div>
                        </div>
                      </li>
                      <li class="list-group-item px-0 py-2">
                        <a class="btn border-0 px-0 text-start w-100" data-bs-toggle="collapse" href="#created-date-filter-collapse"><div class="float-end"><i class="ti ti-chevron-down"></i></div>
                          Start Date
                        </a>
                        <div class="collapse" id="created-date-filter-collapse">
                          <div class="row py-3">
                            <div class="col-12">
                              <input type="text" class="form-control filter-datepicker mb-3" autocomplete="off" name="filter_created_date_start_date" id="filter_created_date_start_date" placeholder="Start Date">
                              <input type="text" class="form-control filter-datepicker" autocomplete="off" name="filter_created_date_end_date" id="filter_created_date_end_date" placeholder="End Date" >
                            </div>
                          </div>
                        </div>
                      </li>
                      <li class="list-group-item px-0 py-2">
                        <a class="btn border-0 px-0 text-start w-100" data-bs-toggle="collapse" href="#completed-date-filter-collapse"><div class="float-end"><i class="ti ti-chevron-down"></i></div>
                          Completed Date
                        </a>
                        <div class="collapse" id="completed-date-filter-collapse">
                          <div class="row py-3">
                            <div class="col-12">
                              <input type="text" class="form-control filter-datepicker mb-3" autocomplete="off" name="filter_completed_date_start_date" id="filter_completed_date_start_date" placeholder="Start Date">
                              <input type="text" class="form-control filter-datepicker" autocomplete="off" name="filter_completed_date_end_date" id="filter_completed_date_end_date" placeholder="End Date" >
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
                <h5>CI Report List</h5>
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
              <table id="ci-report-table" class="table table-hover nowrap w-100 text-uppercase">
                <thead>
                  <tr>
                    <th>Customer</th>
                    <th>Sales Proposal</th>
                    <th>Appraiser</th>
                    <th>Investigator</th>
                    <th>Status</th>
                    <th>Date Started</th>
                    <th>Date Completed</th>
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