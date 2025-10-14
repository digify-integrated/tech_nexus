<div class="row">
  <div class="col-lg-6">
    <div class="card table-card">
      <div class="card-header">
        <div class="row align-items-center">
          <div class="col-sm-6">
            <h5>Parts Incoming</h5>
          </div>
          <div class="col-sm-6 text-sm-end mt-3 mt-sm-0">
            <button type="button" class="btn btn-warning" data-bs-toggle="offcanvas" data-bs-target="#parts-incoming-filter-offcanvas">
              Filter
            </button>
          </div>
        </div>
      </div>
      <div class="card-body">
        <div class="table-responsive w-100">
          <table class="table mb-0" id="parts-incoming-table">
            <thead>
              <tr>
                <th class="text-center">Ref No.</th>
                <th class="text-center">Product</th>
                <th class="text-center">Part</th>
                <th class="text-center">Qty.</th>
                <th class="text-center">Received Qty.</th>
                <?php
                  if($viewPartCost['total'] > 0){
                    echo '<th class="text-center">Cost</th>
                    <th class="text-center">Total Cost</th>';
                  }
                ?>
                <th class="text-center">Remarks</th>
              </tr>
            </thead>
            <tbody></tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
  <div class="col-lg-6">
    <div class="card table-card">
      <div class="card-header">
        <div class="row align-items-center">
          <div class="col-sm-6">
            <h5>Parts Issuance</h5>
          </div>
          <div class="col-sm-6 text-sm-end mt-3 mt-sm-0">
            <button type="button" class="btn btn-warning" data-bs-toggle="offcanvas" data-bs-target="#parts-transaction-filter-offcanvas">
              Filter
            </button>
          </div>
        </div>
      </div>
      <div class="card-body">
        <div class="table-responsive w-100">
          <table class="table mb-0" id="parts-transaction-table">
            <thead>
              <tr>
                <th class="text-end">Issuance No.</th>
                <th class="text-center">Product</th>
                <th class="text-center">Part</th>
                <th class="text-center">Qty.</th>
                <th class="text-center">Add-On</th>
                <th class="text-center">Discount</th>
                <th class="text-center">Total Discount</th>
                <th class="text-end">Sub-Total</th>
                <th class="text-end">Total</th>
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
  <div class="offcanvas offcanvas-end" tabindex="-1" id="parts-transaction-filter-offcanvas" aria-labelledby="parts-transaction-filter-offcanvas-label">
    <div class="offcanvas-header">
      <h2 id="parts-transaction-filter-offcanvas-label" style="margin-bottom:-0.5rem">Filter</h2>
      <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
      <div class="row">
        <div class="col-lg-12">
            <div class="form-group row">
              <div class="col-lg-12 mt-3 mt-lg-0">
                <label class="form-label">Transaction Date</label>
                <div class="input-group date">
                  <input type="text" class="form-control regular-datepicker" id="parts_transaction_start_date" name="parts_transaction_start_date" autocomplete="off">
                  <span class="input-group-text">
                    <i class="feather icon-calendar"></i>
                  </span>
                </div>
                <div class="input-group date mt-3">
                  <input type="text" class="form-control regular-datepicker" id="parts_transaction_end_date" name="parts_transaction_end_date" autocomplete="off">
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
          <button type="submit" class="btn btn-primary" id="submit-parts-transaction-filter">Apply</button>
          <button class="btn btn-light-danger" data-bs-dismiss="offcanvas"> Close </button>
        </div>
      </div>
    </div>
  </div>
</div>

<div>
  <div class="offcanvas offcanvas-end" tabindex="-1" id="parts-incoming-filter-offcanvas" aria-labelledby="parts-incoming-filter-offcanvas-label">
    <div class="offcanvas-header">
      <h2 id="parts-incoming-filter-offcanvas-label" style="margin-bottom:-0.5rem">Filter</h2>
      <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
      <div class="row">
        <div class="col-lg-12">
            <div class="form-group row">
              <div class="col-lg-12 mt-3 mt-lg-0">
                <label class="form-label">Transaction Date</label>
                <div class="input-group date">
                  <input type="text" class="form-control regular-datepicker" id="parts_incoming_start_date" name="parts_incoming_start_date" autocomplete="off">
                  <span class="input-group-text">
                    <i class="feather icon-calendar"></i>
                  </span>
                </div>
                <div class="input-group date mt-3">
                  <input type="text" class="form-control regular-datepicker" id="parts_incoming_end_date" name="parts_incoming_end_date" autocomplete="off">
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
          <button type="submit" class="btn btn-primary" id="submit-parts-incoming-filter">Apply</button>
          <button class="btn btn-light-danger" data-bs-dismiss="offcanvas"> Close </button>
        </div>
      </div>
    </div>
  </div>
</div>