<div class="card">
        <div class="card-header">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h5>Job Order</h5>
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
                    <table class="table mb-0" id="job-order-table">
                      <thead>
                        <tr>
                          <th>OS Number</th>
                          <th>Job Order</th>
                          <th>Contractor</th>
                          <th>Work Center</th>
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
                          <th>Contractor</th>
                          <th>Work Center</th>
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
                <table class="table mb-0" id="additional-job-order-table">
                  <thead>
                    <tr>
                      <th>OS Number</th>
                      <th>Job Order</th>
                      <th>Contractor</th>
                      <th>Work Center</th>
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
                      <th>Contractor</th>
                      <th>Work Center</th>
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