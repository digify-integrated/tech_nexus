<div class="row">
  <div class="col-lg-12">
    <div class="card table-card">
      <div class="card-header">
        <div class="row align-items-center">
          <div class="col-sm-6">
            <h5>Parts Incoming List</h5>
          </div>
          <div class="col-sm-6 text-sm-end mt-3 mt-sm-0">
            <?php
              if($partsIncomingCreateAccess['total'] > 0 || $partsIncomingDeleteAccess['total'] > 0){
                $action = '';
                              
                if($partsIncomingDeleteAccess['total'] > 0){
                  $action .= '<div class="btn-group m-r-10">
                                <button type="button" class="btn btn-outline-secondary dropdown-toggle d-none action-dropdown" data-bs-toggle="dropdown" aria-expanded="false">Action</button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                  <li><button class="dropdown-item" type="button" id="delete-parts-incoming">Delete Parts Incoming</button></li>
                                </ul>
                              </div>';
                }

                if($partsIncomingCreateAccess['total'] > 0){
                  if($company == '2'){
                    $action .= '<a href="netruck-parts-incoming.php?new" class="btn btn-success">Create</a>';
                  }
                  else{
                    $action .= '<a href="parts-incoming.php?new" class="btn btn-success">Create</a>';
                  }
                   
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
          <table id="parts-incoming-table" class="table table-hover nowrap w-100">
            <thead>
              <tr>
                <th>Reference Number</th>
                <th>Item Lines</th>
                <th>Item Quantity</th>
                <th>Received Items</th>
                <th>Remaining Items</th>
                <?php
                  if($viewPartCost['total'] > 0){
                    echo '<th>Total Cost</th>';
                  }
                ?>
                <th>Completion Date</th>
                <th>Delivery Date</th>
                <th>Transaction Date</th>
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
                    Transaction Date
                  </a>
                  <div class="collapse" id="transaction-date-filter-collapse">
                    <div class="row py-3">
                      <div class="col-12">
                        <input type="text" class="form-control filter-datepicker mb-3" autocomplete="off" name="filter_transaction_date_start_date" id="filter_transaction_date_start_date" placeholder="Start Date">
                        <input type="text" class="form-control filter-datepicker" autocomplete="off" name="filter_transaction_date_end_date" id="filter_transaction_date_end_date" placeholder="End Date" >
                      </div>
                    </div>
                  </div>
                </li>
                <li class="list-group-item px-0 py-2">
                  <a class="btn border-0 px-0 text-start w-100" data-bs-toggle="collapse" href="#released-date-filter-collapse"><div class="float-end"><i class="ti ti-chevron-down"></i></div>
                    Released Date
                  </a>
                  <div class="collapse" id="released-date-filter-collapse">
                    <div class="row py-3">
                      <div class="col-12">
                        <input type="text" class="form-control filter-datepicker mb-3" autocomplete="off" name="filter_released_date_start_date" id="filter_released_date_start_date" placeholder="Start Date">
                        <input type="text" class="form-control filter-datepicker" autocomplete="off" name="filter_released_date_end_date" id="filter_released_date_end_date" placeholder="End Date" >
                      </div>
                    </div>
                  </div>
                </li>
                <li class="list-group-item px-0 py-2">
                  <a class="btn border-0 px-0 text-start w-100" data-bs-toggle="collapse" href="#purchased-date-filter-collapse"><div class="float-end"><i class="ti ti-chevron-down"></i></div>
                    Purchased Date
                  </a>
                  <div class="collapse" id="purchased-date-filter-collapse">
                    <div class="row py-3">
                      <div class="col-12">
                        <input type="text" class="form-control filter-datepicker mb-3" autocomplete="off" name="filter_purchased_date_start_date" id="filter_purchased_date_start_date" placeholder="Start Date">
                        <input type="text" class="form-control filter-datepicker" autocomplete="off" name="filter_purchased_date_end_date" id="filter_purchased_date_end_date" placeholder="End Date" >
                      </div>
                    </div>
                  </div>
                </li>
                <li class="list-group-item px-0 py-2">
                  <a class="btn border-0 px-0 text-start w-100" data-bs-toggle="collapse" href="#incoming-status-filter-collapse"><div class="float-end"><i class="ti ti-chevron-down"></i></div>
                    Incoming Status
                  </a>
                  <div class="collapse" id="incoming-status-filter-collapse">
                    <div class="row py-3">
                      <div class="col-12">
                        <div class="form-check my-2">
                          <input class="form-check-input incoming-status-checkbox" type="checkbox" id="incoming-status-draft" value="Draft"/>
                          <label class="form-check-label" for="incoming-status-draft">Draft</label>
                        </div>
                        <div class="form-check my-2">
                          <input class="form-check-input incoming-status-checkbox" type="checkbox" id="incoming-status-for-approval" value="For Approval"/>
                          <label class="form-check-label" for="incoming-status-for-approval">For Approval</label>
                        </div>
                        <div class="form-check my-2">
                          <input class="form-check-input incoming-status-checkbox" type="checkbox" id="incoming-status-onprocess" value="On-Process"/>
                          <label class="form-check-label" for="incoming-status-onprocess">On-Process</label>
                        </div>
                        <div class="form-check my-2">
                          <input class="form-check-input incoming-status-checkbox" type="checkbox" id="incoming-status-completed" value="Completed"/>
                          <label class="form-check-label" for="incoming-status-completed">Completed</label>
                        </div>
                        <div class="form-check my-2">
                          <input class="form-check-input incoming-status-checkbox" type="checkbox" id="incoming-status-cancelled" value="Cancelled"/>
                          <label class="form-check-label" for="incoming-status-cancelled">Cancelled</label>
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