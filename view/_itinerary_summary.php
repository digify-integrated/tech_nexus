<div class="row">
  <div class="col-lg-12">
    <div class="ecom-wrapper">
      <div class="offcanvas offcanvas-start ecom-offcanvas" tabindex="-1" id="filter-canvas">
        <div class="offcanvas-body p-0 sticky-xxl-top">
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
                        <a class="btn border-0 px-0 text-start w-100" data-bs-toggle="collapse" href="#itinerary-date-filter-collapse"><div class="float-end"><i class="ti ti-chevron-down"></i></div>
                          Itinerary Date
                        </a>
                        <div class="collapse " id="itinerary-date-filter-collapse">
                          <div class="row py-3">
                            <div class="col-12">
                              <input type="text" class="form-control filter-datepicker mb-3" autocomplete="off" name="filter_itinerary_start_date" id="filter_itinerary_start_date" placeholder="Start Date" value="<?php echo date('m/d/Y'); ?>">
                              <input type="text" class="form-control filter-datepicker" autocomplete="off" name="filter_itinerary_end_date" id="filter_itinerary_end_date" placeholder="End Date"  value="<?php echo date('m/d/Y'); ?>">
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
                <h5>Itinerary List</h5>
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
              <input type="hidden" id="customer_id" value="<?php echo $customerID; ?>">
              <table id="itinerary-summary-table" class="table table-hover nowrap w-100 text-uppercase">
                <thead>
                  <tr>
                    <th>Date</th>
                    <th>Name of Client</th>
                    <th>Destination</th>
                    <th>Purpose</th>
                    <th>Expected Time of Departure (ETD)</th>
                    <th>Expected Time of Arrival (ETA)</th>
                    <th>Remarks</th>
                    <th>Created By</th>
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