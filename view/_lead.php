<div class="row">
  <div class="col-lg-12">
    <div class="card table-card">
      <div class="card-header">
        <div class="row align-items-center">
          <div class="col-sm-6">
            <h5>Lead List</h5>
          </div>
          <div class="col-sm-6 text-sm-end mt-3 mt-sm-0">
            <div class="btn-group m-r-10">
              <button type="button" 
                        class="btn btn-outline-secondary dropdown-toggle" 
                        data-bs-toggle="dropdown">
                    Export
                </button>

                <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                        <button class="dropdown-item" type="button" id="export-csv">
                            Export As CSV
                        </button>
                    </li>

                    <li>
                        <button class="dropdown-item" type="button" id="export-excel">
                            Export As Excel
                        </button>
                    </li>

                    <li>
                        <button class="dropdown-item" type="button" id="export-pdf">
                            Export As PDF
                        </button>
                    </li>
                </ul>
            </div>
            <?php
              if($leadCreateAccess['total'] > 0 || $leadDeleteAccess['total'] > 0){
                $action = '';
                              
                if($leadDeleteAccess['total'] > 0){
                  $action .= '<div class="btn-group m-r-10">
                                <button type="button" class="btn btn-outline-secondary dropdown-toggle d-none action-dropdown" data-bs-toggle="dropdown">
                                  Action
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                  <li><button class="dropdown-item" type="button" id="delete-lead">Delete Lead</button></li>
                                </ul>
                              </div>';
                }

                if($leadCreateAccess['total'] > 0){
                  $action .= '<a href="lead-monitoring.php?new" class="btn btn-success">Create</a>';
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
          <table id="lead-table" class="table table-hover nowrap w-100">
            <thead>
              <tr>
                <th class="all">
                  <div class="form-check">
                    <input class="form-check-input" id="datatable-checkbox" type="checkbox">
                  </div>
                </th>
                <th>Lead Name</th>
                <th>Phone</th>
                <th>Inquiry Type</th>
                <th>Inquiry Date</th>
                <th>Product</th>
                <th>Status</th>
                <th>Created By</th>
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
                    Inquiry Date Date
                  </a>
                  <div class="collapse" id="on-process-date-filter-collapse">
                    <div class="row py-3">
                      <div class="col-12">
                        <input type="text" class="form-control filter-datepicker mb-3" autocomplete="off" name="filter_inquiry_date_start_date" id="filter_inquiry_date_start_date" placeholder="Start Date">
                        <input type="text" class="form-control filter-datepicker" autocomplete="off" name="filter_inquiry_date_end_date" id="filter_inquiry_date_end_date" placeholder="End Date" >
                      </div>
                    </div>
                  </div>
                </li>
                 <li class="list-group-item px-0 py-2">
                    <a class="btn border-0 px-0 text-start w-100" data-bs-toggle="collapse" href="#lead-status-filter-collapse"><div class="float-end"><i class="ti ti-chevron-down"></i></div>
                      Lead Status
                    </a>
                    <div class="collapse" id="lead-status-filter-collapse">
                      <div class="py-3">
                        <?php
                          echo $leadStatusModel->generateLeadStatusCheckBox('Lead');
                        ?>
                      </div>
                    </div>
                </li>
                 <li class="list-group-item px-0 py-2">
                    <a class="btn border-0 px-0 text-start w-100" data-bs-toggle="collapse" href="#inquiry-type-filter-collapse"><div class="float-end"><i class="ti ti-chevron-down"></i></div>
                      Inquiry Type
                    </a>
                    <div class="collapse" id="inquiry-type-filter-collapse">
                      <div class="py-3">
                        <?php
                          echo $inquiryTypeModel->generateInquiryTypeCheckBox();
                        ?>
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