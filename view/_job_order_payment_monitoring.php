<div class="card">
        <div class="card-header">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h5>Job Order</h5>
                </div>
                 <div class="col-sm-6 text-sm-end mt-3 mt-sm-0">
                  <button type="button" class="btn btn-warning" data-bs-toggle="offcanvas" data-bs-target="#filter-canvas">
                    Filter
                  </button>
                 </div>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="dt-responsive table-responsive">
                <input type="hidden" id="generate-job-order">
                <ul class="nav nav-tabs analytics-tab" id="myTab" role="tablist">
                    <li class="nav-item" role="presentation"><button class="nav-link active" id="job-order-sales-proposal" data-bs-toggle="tab" data-bs-target="#job-order-sales-proposal-pane" type="button" role="tab" aria-controls="job-order-sales-proposal-pane" aria-selected="true">Sales Proposal</button></li>
                    <li class="nav-item" role="presentation"><button class="nav-link" id="internal-job-order" data-bs-toggle="tab" data-bs-target="#internal-job-order-pane" type="button" role="tab" aria-controls="internal-job-order-pane" aria-selected="false" tabindex="-1">Internal Job Order</button></li>
                </ul>
                <div class="tab-content" id="myTabContent">
                  <div class="tab-pane fade active show" id="job-order-sales-proposal-pane" role="tabpanel" aria-labelledby="job-order-sales-proposal" tabindex="0">
                    <table class="table mb-0 w-100" id="job-order-table">
                      <thead>
                        <tr>
                          <th>OS Number</th>
                          <th>Job Order</th>
                        <th>Cost</th>
                          <th>Contractor</th>
                          <th>Work Center</th>
                          <th>Status</th>
                          <th class="text-end"></th>
                        </tr>
                      </thead>
                      <tbody></tbody>
                    </table>
                  </div>
                  <div class="tab-pane fade" id="internal-job-order-pane" role="tabpanel" aria-labelledby="internal-job-order" tabindex="0">
                    <table class="table w-100 mb-0" id="internal-job-order-table">
                      <thead>
                        <tr>
                          <th>Type</th>
                          <th>OS Number</th>
                          <th>Job Order</th>
                          <th>Cost</th>
                          <th>Contractor</th>
                          <th>Work Center</th>
                          <th>Status</th>
                          <th class="text-end"></th>
                        </tr>
                      </thead>
                      <tbody></tbody>
                    </table>
                  </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-header">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h5>Additional Job Order</h5>
                </div>
            </div>
        </div>
        <div class="card-body p-0">
          <input type="hidden" id="generate-additional-job-order">
          <ul class="nav nav-tabs analytics-tab" id="myTab2" role="tablist">
            <li class="nav-item" role="presentation"><button class="nav-link active" id="additional-job-order-sales-proposal" data-bs-toggle="tab" data-bs-target="#additional-job-order-sales-proposal-pane" type="button" role="tab" aria-controls="additional-job-order-sales-proposal-pane" aria-selected="true">Sales Proposal</button></li>
            <li class="nav-item" role="presentation"><button class="nav-link" id="internal-additional-job-order" data-bs-toggle="tab" data-bs-target="#internal-additional-job-order-pane" type="button" role="tab" aria-controls="internal-additional-job-order-pane" aria-selected="false" tabindex="-1">Internal Job Order</button></li>
          </ul>
          <div class="tab-content" id="myTabContent2">
            <div class="tab-pane fade active show" id="additional-job-order-sales-proposal-pane" role="tabpanel" aria-labelledby="additional-job-order-sales-proposal" tabindex="0">
              <div class="dt-responsive table-responsive">
                <table class="table mb-0 w-100" id="additional-job-order-table">
                  <thead>
                    <tr>
                      <th>OS Number</th>
                      <th>Job Order</th>
                      <th>Cost</th>
                      <th>Contractor</th>
                      <th>Work Center</th>
                          <th>Status</th>
                      <th class="text-end"></th>
                    </tr>
                  </thead>
                  <tbody></tbody>
                </table>
              </div>
            </div>
            <div class="tab-pane fade" id="internal-additional-job-order-pane" role="tabpanel" aria-labelledby="internal-additional-job-order" tabindex="0">
              <div class="dt-responsive table-responsive w-100">
                <table class="table w-100 mb-0" id="internal-additional-job-order-table">
                  <thead>
                    <tr>
                      <th>Type</th>
                      <th>OS Number</th>
                      <th>Job Order</th>
                      <th>Cost</th>
                      <th>Contractor</th>
                      <th>Work Center</th>
                      <th>Status</th>
                      <th class="text-end"></th>
                    </tr>
                  </thead>
                  <tbody></tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
    </div>

<div>

<div>
    <div class="offcanvas offcanvas-end" tabindex="-1" id="paid-offcanvas" aria-labelledby="paid-offcanvas-label">
        <div class="offcanvas-header">
            <h2 id="paid-offcanvas-label" style="margin-bottom:-0.5rem">Tag As Paid </h2>
            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            <div class="row">
                <div class="col-lg-12">
                    <form id="paid-form" method="post" action="#">
                      <input type="hidden" id="job_order_id" name="job_order_id">
                        <div class="form-group row">
                          <div class="col-lg-12 mt-3 mt-lg-0"> 
                            <label class="form-label">Reference Number <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="reference_number" name="reference_number" maxlength="200" autocomplete="off">
                          </div>
                        </div>
                        <div class="form-group row">
                          <div class="col-lg-12 mt-3 mt-lg-0"> 
                            <label class="form-label">Payment Date <span class="text-danger">*</span></label>
                            <div class="input-group date">
                              <input type="text" class="form-control regular-datepicker" id="payment_date" name="payment_date" autocomplete="off">
                              <span class="input-group-text">
                                <i class="feather icon-calendar"></i>
                              </span>
                            </div>
                          </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <button type="submit" class="btn btn-primary" id="submit-paid" form="paid-form">Submit</button>
                    <button class="btn btn-light-danger" data-bs-dismiss="offcanvas"> Close </button>
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
                <div class="scroll-block">
                  <div class="card-body">
                    <ul class="list-group list-group-flush">
                      <li class="list-group-item px-0 py-2">
                        <a class="btn border-0 px-0 text-start w-100" data-bs-toggle="collapse" href="#payment-status-filter-collapse"><div class="float-end"><i class="ti ti-chevron-down"></i></div>
                          Payment Status
                        </a>
                        <div class="collapse" id="payment-status-filter-collapse">
                          <div class="row py-3">
                            <div class="col-12">
                              <div class="form-check my-2">
                                <input class="form-check-input payment-status-checkbox" type="checkbox" id="payment-status-unpaid" value="Unpaid" checked/>
                                <label class="form-check-label" for="payment-status-unpaid">Unpaid</label>
                              </div>
                              <div class="form-check my-2">
                                <input class="form-check-input payment-status-checkbox" type="checkbox" id="payment-status-paid" value="Paid"/>
                                <label class="form-check-label" for="payment-status-paid">Paid</label>
                              </div>
                              <div class="form-check my-2">
                                <input class="form-check-input payment-status-checkbox" type="checkbox" id="payment-status-cancelled" value="Cancelled"/>
                                <label class="form-check-label" for="payment-status-cancelled">Cancelled</label>
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
</div>