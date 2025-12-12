<div class="row">
  <div class="col-lg-12">
    <div class="card table-card">
      <div class="card-header">
        <div class="row align-items-center">
          <div class="col-sm-6">
            <h5>Stock Transfer Advice List</h5>
          </div>
          <div class="col-sm-6 text-sm-end mt-3 mt-sm-0">
            <?php
              if($stockTransferAdviceCreateAccess['total'] > 0 || $stockTransferAdviceDeleteAccess['total'] > 0){
                $action = '';
                              
                if($stockTransferAdviceDeleteAccess['total'] > 0){
                  $action .= '<div class="btn-group m-r-10">
                                <button type="button" class="btn btn-outline-secondary dropdown-toggle d-none action-dropdown" data-bs-toggle="dropdown" aria-expanded="false">Action</button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                  <li><button class="dropdown-item" type="button" id="delete-stock-transfer-advice">Delete Stock Transfer Advice</button></li>
                                </ul>
                              </div>';
                  }

                if($stockTransferAdviceCreateAccess['total'] > 0){
                  $action .= '<a href="stock-transfer-advice.php?new" class="btn btn-success">Create</a>';
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
          <table id="stock-transfer-advice-table" class="table table-hover nowrap w-100">
            <thead>
              <tr>
                <th>Reference No.</th>
                <th>Customer Reference</th>
                <th>Transferred From</th>
                <th>Transferred To</th>
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
                  <a class="btn border-0 px-0 text-start w-100" data-bs-toggle="collapse" href="#transaction-date-filter-collapse"><div class="float-end"><i class="ti ti-chevron-down"></i></div>
                    Creation Date
                  </a>
                  <div class="collapse" id="transaction-date-filter-collapse">
                    <div class="row py-3">
                      <div class="col-12">
                        <input type="text" class="form-control filter-datepicker mb-3" autocomplete="off" name="filter_created_date_start_date" id="filter_created_date_start_date" placeholder="Start Date">
                        <input type="text" class="form-control filter-datepicker" autocomplete="off" name="filter_created_date_end_date" id="filter_created_date_end_date" placeholder="End Date" >
                      </div>
                    </div>
                  </div>
                </li>
                <li class="list-group-item px-0 py-2">
                  <a class="btn border-0 px-0 text-start w-100" data-bs-toggle="collapse" href="#on-process-date-filter-collapse"><div class="float-end"><i class="ti ti-chevron-down"></i></div>
                    On-process Date
                  </a>
                  <div class="collapse" id="on-process-date-filter-collapse">
                    <div class="row py-3">
                      <div class="col-12">
                        <input type="text" class="form-control filter-datepicker mb-3" autocomplete="off" name="filter_on_process_date_start_date" id="filter_on_process_date_start_date" placeholder="Start Date">
                        <input type="text" class="form-control filter-datepicker" autocomplete="off" name="filter_on_process_date_end_date" id="filter_on_process_date_end_date" placeholder="End Date" >
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
                  <a class="btn border-0 px-0 text-start w-100" data-bs-toggle="collapse" href="#sta-status-filter-collapse"><div class="float-end"><i class="ti ti-chevron-down"></i></div>
                    Transaction Status
                  </a>
                  <div class="collapse" id="sta-status-filter-collapse">
                    <div class="row py-3">
                      <div class="col-12">
                        <div class="form-check my-2">
                          <input class="form-check-input sta-status-checkbox" type="checkbox" id="sta-status-draft" value="Draft" checked/>
                          <label class="form-check-label" for="sta-status-draft">Draft</label>
                        </div>
                        <div class="form-check my-2">
                          <input class="form-check-input sta-status-checkbox" type="checkbox" id="sta-status-for-approval" value="For Approval"/>
                          <label class="form-check-label" for="sta-status-for-approval">For Approval</label>
                        </div>
                        <div class="form-check my-2">
                          <input class="form-check-input sta-status-checkbox" type="checkbox" id="sta-status-on-process" value="On-Process" checked/>
                          <label class="form-check-label" for="sta-status-on-process">On-Process</label>
                        </div>
                        <div class="form-check my-2">
                          <input class="form-check-input sta-status-checkbox" type="checkbox" id="sta-status-completed" value="Completed"/>
                          <label class="form-check-label" for="sta-status-completed">Completed</label>
                        </div>
                        <div class="form-check my-2">
                          <input class="form-check-input sta-status-checkbox" type="checkbox" id="sta-status-posted" value="Posted"/>
                          <label class="form-check-label" for="sta-status-posted">Posted</label>
                        </div>
                        <div class="form-check my-2">
                          <input class="form-check-input sta-status-checkbox" type="checkbox" id="sta-status-cancelled" value="Cancelled"/>
                          <label class="form-check-label" for="sta-status-cancelled">Cancelled</label>
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