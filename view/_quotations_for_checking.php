<div class="row">
  <div class="col-lg-12">
    <div class="card table-card">
      <div class="card-header">
        <div class="row align-items-center">
          <div class="col-sm-6">
            <h5>Quotations For Checking List</h5>
          </div>         
          <div class="col-md-6 text-sm-end mt-3 mt-sm-0 d-none">
              <button type="button" class="btn btn-warning" data-bs-toggle="offcanvas" data-bs-target="#filter-offcanvas">
                Filter
              </button>
          </div>
        </div>
      </div>
      <div class="card-body">
        <div class="table-responsive dt-responsive">
          <table id="quotations-for-checking-table" class="table table-hover text-wrap w-100">
            <thead>
              <tr>
                <th>Reference No.</th>
                <th>Product</th>
                <th>Price</th>
                <th>Status</th>
                <th>Company</th>
                <th>Created By</th>
                <th>Expiration Date</th>
                <th>Created Date</th>
                <th>Remarks</th>
                <th class="text-end">Action</th>
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
  <div class="offcanvas offcanvas-end" tabindex="-1" id="filter-offcanvas" aria-labelledby="filter-offcanvas-label">
    <div class="offcanvas-header">
      <h2 id="filter-offcanvas-label" style="margin-bottom:-0.5rem">Filter</h2>
      <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
      <div class="row">
        <div class="col-lg-12">
            <div class="form-group row">
             <div class="col-12">
                <label class="form-label">Status</label>
                <div class="form-check my-2 d-none">
                  <input class="form-check-input quotations-status-checkbox" type="checkbox" id="quotations-status-draft" value="Draft"/>
                  <label class="form-check-label" for="quotations-status-draft">Draft</label>
                </div>
                <div class="form-check my-2">
                  <input class="form-check-input quotations-status-checkbox" type="checkbox" id="quotations-status-for-approval" value="For Approval" checked/>
                  <label class="form-check-label" for="quotations-status-for-approval">For Approval</label>
                </div>
                <div class="form-check my-2">
                  <input class="form-check-input quotations-status-checkbox" type="checkbox" id="quotations-status-approved" value="Approved"/>
                  <label class="form-check-label" for="quotations-status-approved">Approved</label>
                </div>
                <div class="form-check my-2">
                  <input class="form-check-input quotations-status-checkbox" type="checkbox" id="quotations-status-cancelled" value="Cancelled"/>
                  <label class="form-check-label" for="quotations-status-cancelled">Cancelled</label>
                </div>
              </div>
            </div>
        </div>
      </div>
      <div class="row">
        <div class="col-lg-12">
          <button type="submit" class="btn btn-primary" id="submit-filter">Apply</button>
          <button class="btn btn-light-danger" data-bs-dismiss="offcanvas"> Close </button>
        </div>
      </div>
    </div>
  </div>
</div>

<div>
  <div class="offcanvas offcanvas-end" tabindex="-1" id="receive-unit-offcanvas" aria-labelledby="receive-unit-offcanvas-label">
    <div class="offcanvas-header">
      <h2 id="receive-unit-offcanvas-label" style="margin-bottom:-0.5rem">Unit Receive</h2>
      <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
      <div class="row">
        <div class="col-lg-12">
          <form id="receive-unit-form" method="post" action="#">
            <div class="form-group row">
              <div class="col-lg-12 mt-3 mt-lg-0">
                <label class="form-label">Incoming Checklist <span class="text-danger">*</span></label>
                <input type="file" class="form-control" id="incoming_checklist" name="incoming_checklist">
              </div>
            </div>
          </form>
        </div>
      </div>
      <div class="row">
        <div class="col-lg-12">
          <button type="submit" class="btn btn-primary" id="submit-receive-unit" form="receive-unit-form">Submit</button>
          <button class="btn btn-light-danger" data-bs-dismiss="offcanvas"> Close </button>
        </div>
      </div>
    </div>
  </div>
</div>