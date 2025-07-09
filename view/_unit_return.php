<div class="row">
  <div class="col-lg-12">
    <div class="card table-card">
      <div class="card-header">
        <div class="row align-items-center">
          <div class="col-sm-6">
            <h5>Unit Return List</h5>
          </div>         
          <div class="col-md-6 text-sm-end mt-3 mt-sm-0">
              <button type="button" class="btn btn-warning" data-bs-toggle="offcanvas" data-bs-target="#filter-offcanvas">
                Filter
              </button>
          </div>
        </div>
      </div>
      <div class="card-body">
        <div class="table-responsive dt-responsive">
          <table id="unit-return-table" class="table table-hover text-wrap w-100">
            <thead>
              <tr>
                <th>Released To</th>
                <th>Product</th>
                <th>Estimated Return Date</th>
                <th>Return Date</th>
                <th>Days Overdue</th>
                <th>Incoming Checklist</th>
                <th>Status</th>
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
                <div class="form-check my-2">
                  <input class="form-check-input unit-return-status-checkbox" type="checkbox" id="unit-return-status-on-going" value="On-going" checked/>
                  <label class="form-check-label" for="unit-return-status-on-going">On-going</label>
                </div>
                <div class="form-check my-2">
                  <input class="form-check-input unit-return-status-checkbox" type="checkbox" id="unit-return-status-overdue" value="Overdue" checked/>
                  <label class="form-check-label" for="unit-return-status-overdue">Overdue</label>
                </div>
                <div class="form-check my-2">
                  <input class="form-check-input unit-return-status-checkbox" type="checkbox" id="unit-return-status-returned" value="Returned"/>
                  <label class="form-check-label" for="unit-return-status-returned">Returned</label>
                </div>
              </div>
            </div>
            <div class="form-group row">
              <div class="col-lg-12 mt-3 mt-lg-0">
                <label class="form-label">Estimated Return Date</label>
                <div class="input-group date">
                  <input type="text" class="form-control regular-datepicker" id="estimated_return_date_start_date" name="estimated_return_date_start_date" autocomplete="off">
                  <span class="input-group-text">
                    <i class="feather icon-calendar"></i>
                  </span>
                </div>
                <div class="input-group date mt-3">
                  <input type="text" class="form-control regular-datepicker" id="estimated_return_date_end_date" name="estimated_return_date_end_date" autocomplete="off">
                  <span class="input-group-text">
                    <i class="feather icon-calendar"></i>
                  </span>
                </div>
              </div>
            </div>
            <div class="form-group row">
              <div class="col-lg-12 mt-3 mt-lg-0">
                <label class="form-label">Return Date</label>
                <div class="input-group date">
                  <input type="text" class="form-control regular-datepicker" id="return_date_start_date" name="return_date_start_date" autocomplete="off">
                  <span class="input-group-text">
                    <i class="feather icon-calendar"></i>
                  </span>
                </div>
                <div class="input-group date mt-3">
                  <input type="text" class="form-control regular-datepicker" id="return_date_end_date" name="return_date_end_date" autocomplete="off">
                  <span class="input-group-text">
                    <i class="feather icon-calendar"></i>
                  </span>
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