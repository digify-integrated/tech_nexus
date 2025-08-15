<div class="row">
  <div class="col-lg-12">
    <div class="card table-card">
      <div class="card-header">
        <div class="row align-items-center">
          <div class="col-sm-6">
            <h5>Internal Job Order List</h5>
          </div>
          <div class="col-sm-6 text-sm-end mt-3 mt-sm-0">
            <?php
              if($backJobMonitoringCreateAccess['total'] > 0){
                $action = '';

                if($backJobMonitoringCreateAccess['total'] > 0){
                  $action .= '<a href="back-job-monitoring.php?new" class="btn btn-success">Create</a>';
                }
                              
                echo $action;
              }
            ?>
            <button type="button" class="btn btn-warning" data-bs-toggle="offcanvas" data-bs-target="#filter-canvas">
                  Filter
                </button>
           </div>
        </div>
      </div>
      <div class="card-body">
        <div class="table-responsive dt-responsive">
        <table id="backjob-monitoring-table" class="table table-hover nowrap w-100">
            <thead>
              <tr>
                <th>Type</th>
                <th>Sales Proposal</th>
                <th>Product</th>
                <th>Status</th>
                <th>Created Date</th>
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
                  <a class="btn border-0 px-0 text-start w-100" data-bs-toggle="collapse" href="#internal-status-filter-collapse"><div class="float-end"><i class="ti ti-chevron-down"></i></div>
                    Internal Job Order Status
                  </a>
                  <div class="collapse" id="internal-status-filter-collapse">
                    <div class="row py-3">
                      <div class="col-12">
                        <div class="form-check my-2">
                          <input class="form-check-input internal-status-checkbox" type="checkbox" id="internal-status-draft" value="Draft"/>
                          <label class="form-check-label" for="internal-status-draft">Draft</label>
                        </div>
                        <div class="form-check my-2">
                          <input class="form-check-input internal-status-checkbox" type="checkbox" id="internal-status-for-approval" value="For Approval"/>
                          <label class="form-check-label" for="internal-status-for-approval">For Approval</label>
                        </div>
                        <div class="form-check my-2">
                          <input class="form-check-input internal-status-checkbox" type="checkbox" id="internal-status-approved" value="Approved"/>
                          <label class="form-check-label" for="internal-status-approved">Approved</label>
                        </div>
                        <div class="form-check my-2">
                          <input class="form-check-input internal-status-checkbox" type="checkbox" id="internal-status-onprocess" value="On-Process"/>
                          <label class="form-check-label" for="internal-status-onprocess">On-Process</label>
                        </div>
                        <div class="form-check my-2">
                          <input class="form-check-input internal-status-checkbox" type="checkbox" id="internal-status-ready-for-realease" value="Ready For Release"/>
                          <label class="form-check-label" for="internal-status-ready-for-realease">Ready For Release</label>
                        </div>
                        <div class="form-check my-2">
                          <input class="form-check-input internal-status-checkbox" type="checkbox" id="internal-status-for-dr" value="For DR"/>
                          <label class="form-check-label" for="internal-status-for-dr">For DR</label>
                        </div>
                        <div class="form-check my-2">
                          <input class="form-check-input internal-status-checkbox" type="checkbox" id="internal-status-released" value="Released"/>
                          <label class="form-check-label" for="internal-status-released">Released</label>
                        </div>
                        <div class="form-check my-2">
                          <input class="form-check-input internal-status-checkbox" type="checkbox" id="internal-status-cancelled" value="Cancelled"/>
                          <label class="form-check-label" for="internal-status-cancelled">Cancelled</label>
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