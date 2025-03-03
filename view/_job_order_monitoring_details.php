<div class="row">
  <div class="col-lg-12">

    <div class="card table-card">
      <div class="card-header">
        <div class="row align-items-center">
          <div class="col-sm-6">
            <h5>Job Order</h5>
          </div>
        </div>
      </div>
      <div class="card-body">
        <div class="dt-responsive table-responsive">
            <table id="job-order-progress-table" class="table table-hover nowrap w-100">
                <thead>
                  <tr>
                    <th>Job Order</th>
                    <th>Cost</th>
                    <th>Progress</th>
                    <th>Actions</th>
                  </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
      </div>
    </div>

    <div class="card table-card">
      <div class="card-header">
        <div class="row align-items-center">
          <div class="col-sm-6">
            <h5>Additional Job Order</h5>
          </div>
        </div>
      </div>
      <div class="card-body">
        <div class="dt-responsive table-responsive">
            <table id="additional-job-order-progress-table" class="table table-hover nowrap w-100">
                <thead>
                  <tr>
                    <th>Job Order Number</th>
                    <th>Job Order Date</th>
                    <th>Particulars</th>
                    <th>Cost</th>
                    <th>Progress</th>
                    <th>Actions</th>
                  </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
      </div>
    </div>
  </div>

  <div>
    <div class="offcanvas offcanvas-end" tabindex="-1" id="sales-proposal-job-order-monitoring-offcanvas" aria-labelledby="sales-proposal-job-order-monitoring-offcanvas-label">
      <div class="offcanvas-header">
        <h2 id="sales-proposal-job-order-offcanvas-label" style="margin-bottom:-0.5rem">Job Order Progress</h2>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
      </div>
    <div class="offcanvas-body">
      <div class="row">
        <div class="col-lg-12">
          <form id="sales-proposal-job-order-progress-form" method="post" action="#">
            <input type="hidden" id="sales_proposal_job_order_id" name="sales_proposal_job_order_id">
            <div class="form-group row">
              <div class="col-lg-12 mt-3 mt-lg-0">
                <label class="form-label">Progress (%) <span class="text-danger">*</span></label>
                <input type="number" class="form-control" id="job_order_progress" name="job_order_progress" min="0" max="100" step="0.01">
              </div>
            </div>
          </form>
        </div>
      </div>
      <div class="row">
        <div class="col-lg-12">
          <button type="submit" class="btn btn-primary" id="submit-sales-proposal-job-order-progress" form="sales-proposal-job-order-progress-form">Submit</button>
          <button class="btn btn-light-danger" data-bs-dismiss="offcanvas"> Close </button>
        </div>
      </div>
    </div>
  </div>
  <div>
    <div class="offcanvas offcanvas-end" tabindex="-1" id="sales-proposal-additional-job-order-monitoring-offcanvas" aria-labelledby="sales-proposal-additional-job-order-monitoring-offcanvas-label">
      <div class="offcanvas-header">
        <h2 id="sales-proposal-additional-job-order-offcanvas-label" style="margin-bottom:-0.5rem">Additional Job Order Progress</h2>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
      </div>
    <div class="offcanvas-body">
      <div class="row">
        <div class="col-lg-12">
          <form id="sales-proposal-additional-job-order-progress-form" method="post" action="#">
            <input type="hidden" id="sales_proposal_additional_job_order_id" name="sales_proposal_additional_job_order_id">
            <div class="form-group row">
              <div class="col-lg-12 mt-3 mt-lg-0">
                <label class="form-label">Progress (%) <span class="text-danger">*</span></label>
                <input type="number" class="form-control" id="additional_job_order_progress" name="additional_job_order_progress" min="0" max="100" step="0.01">
              </div>
            </div>
          </form>
        </div>
      </div>
      <div class="row">
        <div class="col-lg-12">
          <button type="submit" class="btn btn-primary" id="submit-sales-proposal-additional-job-order-progress" form="sales-proposal-additional-job-order-progress-form">Submit</button>
          <button class="btn btn-light-danger" data-bs-dismiss="offcanvas"> Close </button>
        </div>
      </div>
    </div>
  </div>
</div>