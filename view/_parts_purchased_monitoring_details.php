<div class="row">
  <div class="col-lg-12">
    <div class="card table-card">
      <div class="card-header">
        <div class="row align-items-center">
          <div class="col-sm-6">
            <h5>Parts Purchased Monitoring Item List</h5>
          </div>
        </div>
      </div>
      <div class="card-body">
        <div class="table-responsive dt-responsive">
          <table id="parts-purchased-monitoring-item-table" class="table table-hover nowrap w-100">
            <thead>
              <tr>
                <th>Part</th>
                <th>Reference No.</th>
                <th>Issuance Date</th>
                <th>Qty.</th>
                <th>Issued Qty.</th>
                <th>Not Issued Qty.</th>
                <th>Status</th>
                <th>Remarks</th>
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

<div>
  <div class="offcanvas offcanvas-end" tabindex="-1" id="cancel-part-purchased-offcanvas" aria-labelledby="cancel-part-purchased-offcanvas-label">
      <div class="offcanvas-header">
        <h2 id="cancel-part-purchased-offcanvas-label" style="margin-bottom:-0.5rem">Cancel Parts Purchase Issuance</h2>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
      </div>
    <div class="offcanvas-body">
      <div class="row">
        <div class="col-lg-12">
          <form id="cancel-part-purchased-form" method="post" action="#">
            <div class="form-group row">
              <div class="col-lg-12 mt-3 mt-lg-0">
                <label class="form-label">Cancellation Reason <span class="text-danger">*</span></label>
                <textarea class="form-control" id="cancellation_reason" name="cancellation_reason" maxlength="500"></textarea>
              </div>
            </div>
          </form>
        </div>
      </div>
      <div class="row">
        <div class="col-lg-12">
          <button type="submit" class="btn btn-primary" id="submit-cancel-part-purchased" form="cancel-part-purchased-form">Submit</button>
          <button class="btn btn-light-danger" data-bs-dismiss="offcanvas"> Close </button>
        </div>
      </div>
  </div>
</div>

<div>
  <div class="offcanvas offcanvas-end" tabindex="-1" id="issue-part-purchased-offcanvas" aria-labelledby="issue-part-purchased-offcanvas-label">
      <div class="offcanvas-header">
        <h2 id="issue-part-purchased-offcanvas-label" style="margin-bottom:-0.5rem">Issue Part Purchased</h2>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
      </div>
    <div class="offcanvas-body">
      <div class="row">
        <div class="col-lg-12">
          <form id="issue-part-purchased-form" method="post" action="#">
            <div class="form-group row">
              <div class="col-lg-12 mt-3 mt-lg-0"> 
                <label class="form-label">Reference Number <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="reference_number" name="reference_number" maxlength="500" autocomplete="off">
              </div>
            </div>
            <div class="form-group row d-none">
              <div class="col-lg-12 mt-3 mt-lg-0"> 
                <label class="form-label">Issuance Date <span class="text-danger">*</span></label>
                <div class="input-group date">
                  <input type="text" class="form-control regular-datepicker" id="issuance_date" name="issuance_date" autocomplete="off">
                  <span class="input-group-text">
                    <i class="feather icon-calendar"></i>
                  </span>
                </div>
              </div>
            </div>
            
            <div class="form-group row">
              <div class="col-lg-6 mt-3 mt-lg-0">
                <label class="form-label">Issued Quantity <span class="text-danger">*</span></label>
                <input type="number" class="form-control" id="quantity_issued" name="quantity_issued" min="0.01" step="0.01">
              </div>
              <div class="col-lg-6 mt-3 mt-lg-0">
                <label class="form-label">Purchased Quantity</label>
                <input type="number" class="form-control" id="purchased_quantity" name="purchased_quantity" min="0.01" step="0.01" readonly>
              </div>
            </div>
            <div class="form-group row">
              <div class="col-lg-12 mt-3 mt-lg-0">
                <label class="form-label">Remarks</label>
                <textarea class="form-control" id="remarks" name="remarks" maxlength="500"></textarea>
              </div>
            </div>
          </form>
        </div>
      </div>
      <div class="row">
        <div class="col-lg-12">
          <button type="submit" class="btn btn-primary" id="submit-issue-part-purchased" form="issue-part-purchased-form">Submit</button>
          <button class="btn btn-light-danger" data-bs-dismiss="offcanvas"> Close </button>
        </div>
      </div>
  </div>
</div>