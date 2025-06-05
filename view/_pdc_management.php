<div class="row">
  <div class="col-lg-12">
    <div class="ecom-wrapper">
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
                        <a class="btn border-0 px-0 text-start w-100" data-bs-toggle="collapse" href="#pdc-management-default-filter-collapse"><div class="float-end"><i class="ti ti-chevron-down"></i></div>
                          Default Group Filter
                        </a>
                        <div class="collapse show" id="pdc-management-default-filter-collapse">
                          <div class="row py-3">
                            <div class="col-12">
                              <select class="form-control" id="default-filter">
                                <option value="">--</option>
                                <option value="For Deposit">For Deposit</option>
                                <option value="Deposited">Deposited</option>
                              </select>
                            </div>
                          </div>
                        </div>
                      </li>
                      <li class="list-group-item px-0 py-2">
                        <a class="btn border-0 px-0 text-start w-100" data-bs-toggle="collapse" href="#pdc-management-default-filter-collapse"><div class="float-end"><i class="ti ti-chevron-down"></i></div>
                          Loan Number
                        </a>
                        <div class="collapse" id="pdc-management-default-filter-collapse">
                          <div class="row py-3">
                            <div class="col-12">
                              <input type="text" class="form-control" id="loan_number" name="loan_number" maxlength="200" autocomplete="off">
                            </div>
                          </div>
                        </div>
                      </li>
                      <li class="list-group-item px-0 py-2">
                        <a class="btn border-0 px-0 text-start w-100" data-bs-toggle="collapse" href="#pdc-management-status-filter-collapse"><div class="float-end"><i class="ti ti-chevron-down"></i></div>
                          PDC Status
                        </a>
                        <div class="collapse " id="pdc-management-status-filter-collapse">
                          <div class="row py-3">
                            <div class="col-12">
                              <div class="form-check my-2">
                                  <input class="form-check-input pdc-management-status-filter" type="checkbox" id="pdc-management-status-pending" value="Pending" checked/>
                                  <label class="form-check-label" for="pdc-management-status-pending">Pending</label>
                              </div>
                              <div class="form-check my-2">
                                  <input class="form-check-input pdc-management-status-filter" type="checkbox" id="pdc-management-status-redeposit" value="Redeposit" checked/>
                                  <label class="form-check-label" for="pdc-management-status-redeposit">Redeposit</label>
                              </div>
                              <div class="form-check my-2">
                                  <input class="form-check-input pdc-management-status-filter" type="checkbox" id="pdc-management-status-cleared" value="Cleared" />
                                  <label class="form-check-label" for="pdc-management-status-cleared">Cleared</label>
                              </div>
                              <div class="form-check my-2">
                                  <input class="form-check-input pdc-management-status-filter" type="checkbox" id="pdc-management-status-reversed" value="Reversed" />
                                  <label class="form-check-label" for="pdc-management-status-reversed">Reversed</label>
                              </div>
                              <div class="form-check my-2">
                                  <input class="form-check-input pdc-management-status-filter" type="checkbox" id="pdc-management-status-cancelled" value="Cancelled" />
                                  <label class="form-check-label" for="pdc-management-status-cancelled">Cancelled</label>
                              </div>
                              <div class="form-check my-2">
                                  <input class="form-check-input pdc-management-status-filter" type="checkbox" id="pdc-management-status-for-deposit" value="For Deposit" />
                                  <label class="form-check-label" for="pdc-management-status-for-deposit">For Deposit</label>
                              </div>
                              <div class="form-check my-2">
                                  <input class="form-check-input pdc-management-status-filter" type="checkbox" id="pdc-management-status-deposited" value="Deposited" />
                                  <label class="form-check-label" for="pdc-management-status-deposited">Deposited</label>
                              </div>
                              <div class="form-check my-2">
                                  <input class="form-check-input pdc-management-status-filter" type="checkbox" id="pdc-management-status-pulled-out" value="Pulled-Out" />
                                  <label class="form-check-label" for="pdc-management-status-pulled-out">Pulled-Out</label>
                              </div>
                              <div class="form-check my-2">
                                  <input class="form-check-input pdc-management-status-filter" type="checkbox" id="pdc-management-status-on-hold" value="On-Hold" />
                                  <label class="form-check-label" for="pdc-management-status-on-hold">On-Hold</label>
                              </div>
                            </div>
                          </div>
                        </div>
                      </li>
                      <li class="list-group-item px-0 py-2 d-none">
                        <a class="btn border-0 px-0 text-start w-100" data-bs-toggle="collapse" href="#company-filter-collapse"><div class="float-end"><i class="ti ti-chevron-down"></i></div>
                          Company
                        </a>
                        <div class="collapse" id="company-filter-collapse">
                          <div class="form-group row">
                            <div class="col-lg-12 mt-3 mt-lg-0">
                              <div class="col-12">
                                <div class="form-check my-2">
                                  <input class="form-check-input company-checkbox" type="checkbox" id="company-cgmi" value="1"/>
                                  <label class="form-check-label" for="company-cgmi">Christian General Motors Inc.</label>
                                </div>
                                <div class="form-check my-2">
                                  <input class="form-check-input company-checkbox" type="checkbox" id="company-ne-truck" value="2"/>
                                  <label class="form-check-label" for="company-ne-truck">NE Truck Builders</label>
                                </div>
                                <div class="form-check my-2">
                                  <input class="form-check-input company-checkbox" type="checkbox" id="company-fuso-tarlac" value="3"/>
                                  <label class="form-check-label" for="company-nefuso-tarlac">FUSO Tarlac</label>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </li>
                      <li class="list-group-item px-0 py-2">
                        <a class="btn border-0 px-0 text-start w-100" data-bs-toggle="collapse" href="#check-date-filter-collapse"><div class="float-end"><i class="ti ti-chevron-down"></i></div>
                          Check Date
                        </a>
                        <div class="collapse" id="check-date-filter-collapse">
                          <div class="row py-3">
                            <div class="col-12">
                              <input type="text" class="form-control filter-datepicker mb-3" autocomplete="off" name="filter_check_date_start_date" id="filter_check_date_start_date" placeholder="Start Date" value="<?php echo date('m/01/Y'); ?>">
                              <input type="text" class="form-control filter-datepicker" autocomplete="off" name="filter_check_date_end_date" id="filter_check_date_end_date" placeholder="End Date" value="<?php echo date('m/d/Y'); ?>">
                            </div>
                          </div>
                        </div>
                      </li>
                      <li class="list-group-item px-0 py-2">
                        <a class="btn border-0 px-0 text-start w-100" data-bs-toggle="collapse" href="#redeposit-date-filter-collapse"><div class="float-end"><i class="ti ti-chevron-down"></i></div>
                          Redeposit Date
                        </a>
                        <div class="collapse" id="redeposit-date-filter-collapse">
                          <div class="row py-3">
                            <div class="col-12">
                              <input type="text" class="form-control filter-datepicker mb-3" autocomplete="off" name="filter_redeposit_date_start_date" id="filter_redeposit_date_start_date" placeholder="Start Date" value="<?php echo date('m/01/Y'); ?>">
                              <input type="text" class="form-control filter-datepicker" autocomplete="off" name="filter_redeposit_date_end_date" id="filter_redeposit_date_end_date" placeholder="End Date" value="<?php echo date('m/d/Y'); ?>">
                            </div>
                          </div>
                        </div>
                      </li>
                      <li class="list-group-item px-0 py-2 d-none">
                        <a class="btn border-0 px-0 text-start w-100" data-bs-toggle="collapse" href="#onhold-date-filter-collapse"><div class="float-end"><i class="ti ti-chevron-down"></i></div>
                          On-Hold Date
                        </a>
                        <div class="collapse " id="onhold-date-filter-collapse">
                          <div class="row py-3">
                            <div class="col-12">
                              <input type="text" class="form-control filter-datepicker mb-3" autocomplete="off" name="filter_onhold_date_start_date" id="filter_onhold_date_start_date" placeholder="Start Date">
                              <input type="text" class="form-control filter-datepicker" autocomplete="off" name="filter_onhold_date_end_date" id="filter_onhold_date_end_date" placeholder="End Date">
                            </div>
                          </div>
                        </div>
                      </li>
                      <li class="list-group-item px-0 py-2 d-none">
                        <a class="btn border-0 px-0 text-start w-100" data-bs-toggle="collapse" href="#for-deposit-date-filter-collapse"><div class="float-end"><i class="ti ti-chevron-down"></i></div>
                          For Deposit Date
                        </a>
                        <div class="collapse " id="for-deposit-date-filter-collapse">
                          <div class="row py-3">
                            <div class="col-12">
                              <input type="text" class="form-control filter-datepicker mb-3" autocomplete="off" name="filter_for_deposit_date_start_date" id="filter_for_deposit_date_start_date" placeholder="Start Date">
                              <input type="text" class="form-control filter-datepicker" autocomplete="off" name="filter_for_deposit_date_end_date" id="filter_for_deposit_date_end_date" placeholder="End Date">
                            </div>
                          </div>
                        </div>
                      </li>
                      <li class="list-group-item px-0 py-2 d-none">
                        <a class="btn border-0 px-0 text-start w-100" data-bs-toggle="collapse" href="#deposit-date-filter-collapse"><div class="float-end"><i class="ti ti-chevron-down"></i></div>
                          Deposit Date
                        </a>
                        <div class="collapse " id="deposit-date-filter-collapse">
                          <div class="row py-3">
                            <div class="col-12">
                              <input type="text" class="form-control filter-datepicker mb-3" autocomplete="off" name="filter_deposit_date_start_date" id="filter_deposit_date_start_date" placeholder="Start Date">
                              <input type="text" class="form-control filter-datepicker" autocomplete="off" name="filter_deposit_date_end_date" id="filter_deposit_date_end_date" placeholder="End Date">
                            </div>
                          </div>
                        </div>
                      </li>
                      <li class="list-group-item px-0 py-2 d-none">
                        <a class="btn border-0 px-0 text-start w-100" data-bs-toggle="collapse" href="#reversed-date-filter-collapse"><div class="float-end"><i class="ti ti-chevron-down"></i></div>
                          Reversed Date
                        </a>
                        <div class="collapse " id="reversed-date-filter-collapse">
                          <div class="row py-3">
                            <div class="col-12">
                              <input type="text" class="form-control filter-datepicker mb-3" autocomplete="off" name="filter_reversed_date_start_date" id="filter_reversed_date_start_date" placeholder="Start Date">
                              <input type="text" class="form-control filter-datepicker" autocomplete="off" name="filter_reversed_date_end_date" id="filter_reversed_date_end_date" placeholder="End Date">
                            </div>
                          </div>
                        </div>
                      </li>
                      <li class="list-group-item px-0 py-2 d-none">
                        <a class="btn border-0 px-0 text-start w-100" data-bs-toggle="collapse" href="#pulled-out-date-filter-collapse"><div class="float-end"><i class="ti ti-chevron-down"></i></div>
                          Pulled-Out Date
                        </a>
                        <div class="collapse " id="pulled-out-date-filter-collapse">
                          <div class="row py-3">
                            <div class="col-12">
                              <input type="text" class="form-control filter-datepicker mb-3" autocomplete="off" name="filter_pulled_out_date_start_date" id="filter_pulled_out_date_start_date" placeholder="Start Date">
                              <input type="text" class="form-control filter-datepicker" autocomplete="off" name="filter_pulled_out_date_end_date" id="filter_pulled_out_date_end_date" placeholder="End Date">
                            </div>
                          </div>
                        </div>
                      </li>
                      <li class="list-group-item px-0 py-2 d-none">
                        <a class="btn border-0 px-0 text-start w-100" data-bs-toggle="collapse" href="#cancelled-date-filter-collapse"><div class="float-end"><i class="ti ti-chevron-down"></i></div>
                          Cancellation Date
                        </a>
                        <div class="collapse " id="cancelled-date-filter-collapse">
                          <div class="row py-3">
                            <div class="col-12">
                              <input type="text" class="form-control filter-datepicker mb-3" autocomplete="off" name="filter_cancellation_date_start_date" id="filter_cancellation_date_start_date" placeholder="Start Date">
                              <input type="text" class="form-control filter-datepicker" autocomplete="off" name="filter_cancellation_date_end_date" id="filter_cancellation_date_end_date" placeholder="End Date">
                            </div>
                          </div>
                        </div>
                      </li>
                      <li class="list-group-item px-0 py-2 d-none">
                        <a class="btn border-0 px-0 text-start w-100" data-bs-toggle="collapse" href="#clear-date-filter-collapse"><div class="float-end"><i class="ti ti-chevron-down"></i></div>
                          Clear Date
                        </a>
                        <div class="collapse " id="clear-date-filter-collapse">
                          <div class="row py-3">
                            <div class="col-12">
                              <input type="text" class="form-control filter-datepicker mb-3" autocomplete="off" name="filter_clear_date_start_date" id="filter_clear_date_start_date" placeholder="Start Date">
                              <input type="text" class="form-control filter-datepicker" autocomplete="off" name="filter_clear_date_end_date" id="filter_clear_date_end_date" placeholder="End Date">
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
                <h5>PDC Management List</h5>
              </div>
              <div class="col-sm-6 text-sm-end mt-3 mt-sm-0">
                <?php                 
                  if($pdcManagementCreateAccess['total'] > 0 || $pdcManagementDeleteAccess['total'] > 0 || $tagPDCAsDeposited['total'] > 0 || $tagPDCAsForDeposit['total'] > 0 || $tagPDCAsCleared['total'] > 0 || $pdcManagementCreateAccess['total'] > 0){
                    $action = '<div class="btn-group m-r-10">
                                      <button type="button" class="btn btn-outline-secondary dropdown-toggle d-none action-dropdown" data-bs-toggle="dropdown" aria-expanded="false">Action</button>
                                      <ul class="dropdown-menu dropdown-menu-end">';
                                  
                    if($pdcManagementDeleteAccess['total'] > 0){
                      $action .= '<li><button class="dropdown-item" type="button" id="delete-pdc">Delete PDC</button></li>';
                    }

                    if($tagPDCAsDeposited['total'] > 0){
                      $action .= '<li><button class="dropdown-item" type="button" id="tag-pdc-as-deposited-details" data-bs-toggle="offcanvas" data-bs-target="#pdc-deposited-offcanvas" aria-controls="pdc-deposited-offcanvas">Tag As Deposited</button></li>';
                    }

                    if($tagPDCAsForDeposit['total'] > 0){
                      $action .= '<li><button class="dropdown-item" type="button" id="tag-pdc-as-for-deposit">Tag As For Deposit</button></li>';
                    }

                    if($tagPDCAsCleared['total'] > 0){
                      $action .= '<li><button class="dropdown-item" type="button" id="tag-pdc-as-cleared">Tag As Cleared</button></li>';
                    }

                    if($massTagPDCAsCancelled['total'] > 0){
                      $action .= '<li><button class="dropdown-item" type="button" id="tag-pdc-as-cancel-details" data-bs-toggle="offcanvas" data-bs-target="#pdc-cancel-offcanvas" aria-controls="pdc-cancel-offcanvas">Tag As Cancelled</button></li>';
                    }

                    if($massTagPDCAsPulledOut['total'] > 0){
                      $action .= '<li><button class="dropdown-item" type="button" id="tag-pdc-as-pulled-out-details" data-bs-toggle="offcanvas" data-bs-target="#pdc-pulled-out-offcanvas" aria-controls="pdc-pulled-out-offcanvas">Tag As Pulled Out</button></li>';
                    }

                    if($massTagPDCAsReturned['total'] > 0){
                      $action .= '<li><button class="dropdown-item" type="button" id="tag-pdc-as-reversed-details" data-bs-toggle="offcanvas" data-bs-target="#pdc-reverse-offcanvas" aria-controls="pdc-reverse-offcanvas">Tag As Returned</button></li>';
                    }

                    if($duplicateCancelledPDC['total'] > 0){
                      $action .= '<li><button class="dropdown-item" type="button" id="duplicate-cancelled-pdc">Duplicate PDC</button></li>';
                    }

                    $action .= '</ul>
                                    </div>';

                    if($pdcManagementCreateAccess['total'] > 0){
                      $action .= '<a href="pdc-management.php?new" class="btn btn-success">Create</a>';
                    }
                                  
                    echo $action;
                  }
                ?>
                <button class="btn btn-danger dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="true">Print</button>
                  <div class="dropdown-menu" style="position: absolute; inset: 0px auto auto 0px; margin: 0px; transform: translate(0px, 43px);" data-popper-placement="bottom-start">
                    <a class="dropdown-item" href="javascript:void(0);" id="print-acknowledgement">Print Acknowledgment</a> 
                    <a class="dropdown-item" href="javascript:void(0);" id="print-pulledout">Print Pulled-Out</a> 
                    <a class="dropdown-item" href="javascript:void(0);" id="print">Print Report</a> 
                    <a class="dropdown-item" href="javascript:void(0);" id="print-reversal">Print Reversal</a>
                    <a class="dropdown-item" href="javascript:void(0);" id="print-check">Print Check</a>
                  </div>
                <button type="button" class="btn btn-info" data-bs-toggle="offcanvas" data-bs-target="#import-offcanvas" aria-controls="import-offcanvas">
                  Import
                </button>
                <button type="button" class="btn btn-warning" data-bs-toggle="offcanvas" data-bs-target="#filter-canvas">
                  Filter
                </button>
              </div>
            </div>
          </div>
          <div class="card-body">
            <div class="table-responsive dt-responsive">
              <table id="pdc-management-table" class="table table-hover text-wrap w-100 text-uppercase">
                <thead>
                  <tr>
                    <th class="all">
                      <div class="form-check">
                        <input class="form-check-input" id="datatable-checkbox" type="checkbox">
                      </div>
                    </th>
                    <th>Actions</th>
                    <th>Check Date</th>
                    <th>Redeposit Date</th>
                    <th>Check Number</th>
                    <th>Payment Amount</th>
                    <th>Bank/Branch</th>
                    <th>Payment Details</th>
                    <th>Status</th>
                    <th>Loan Number</th>
                    <th>Customer</th>
                    <th>Product</th>
                    <th>Reversal Date</th>
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

<div>
    <div class="offcanvas offcanvas-end" tabindex="-1" id="import-offcanvas" aria-labelledby="import-offcanvas-label">
      <div class="offcanvas-header">
        <h2 id="import-offcanvas-label" style="margin-bottom:-0.5rem">Import PDC</h2>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
      </div>
    <div class="offcanvas-body">
      <div class="row">
        <div class="col-lg-12">
          <form id="import-form" method="post" action="#">
            <div class="form-group row">
              <div class="col-lg-12 mt-3 mt-lg-0">
                <label class="form-label">Import File <span class="text-danger">*</span></label>
                <input type="file" class="form-control" id="import_file" name="import_file">
              </div>
            </div>
          </form>
        </div>
      </div>
      <div class="row">
        <div class="col-lg-12">
          <button type="submit" class="btn btn-primary" id="submit-import" form="import-form">Submit</button>
          <button class="btn btn-light-danger" data-bs-dismiss="offcanvas"> Close </button>
        </div>
      </div>
    </div>
  </div>

  <div>
    <div class="offcanvas offcanvas-end" tabindex="-1" id="pdc-reverse-offcanvas" aria-labelledby="pdc-reverse-offcanvas-label">
      <div class="offcanvas-header">
        <h2 id="pdc-reverse-offcanvas-label" style="margin-bottom:-0.5rem">Reverse PDC</h2>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
      </div>
    <div class="offcanvas-body">
      <div class="row">
        <div class="col-lg-12">
          <form id="mass-pdc-reverse-form" method="post" action="#">
            <div class="form-group row">
                <div class="col-lg-12 mt-3 mt-lg-0">
                    <label class="form-label">Reversal Date <span class="text-danger">*</span></label>
                    <div class="input-group date">
                      <input type="text" class="form-control regular-datepicker" id="reversal_date" name="reversal_date" autocomplete="off">
                      <span class="input-group-text">
                          <i class="feather icon-calendar"></i>
                      </span>
                  </div>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-lg-12 mt-3 mt-lg-0">
                    <label class="form-label">Reversal Reason <span class="text-danger">*</span></label>
                    <select class="form-control offcanvas-select2" name="reversal_reason" id="reversal_reason">
                        <option value="">--</option>
                        <option value="Incorrect Amount">Incorrect Amount</option>
                        <option value="Duplicate Entry">Duplicate Entry</option>
                        <option value="Customer Request">Customer Request</option>
                        <option value="Fraudulent Transaction">Fraudulent Transaction</option>
                        <option value="DAIF">DAIF</option>
                        <option value="DAUD">DAUD</option>
                        <option value="Stop Payment">Stop Payment</option>
                        <option value="Account Closed">Account Closed</option>
                        <option value="Unauthorized Transaction">Unauthorized Transaction</option>
                        <option value="Error in Entry">Error in Entry</option>
                        <option value="Service Issue">Service Issue</option>
                        <option value="Payment Reversal">Payment Reversal</option>
                        <option value="PDC Bounced">PDC Bounced</option>
                        <option value="PDC Lost">PDC Lost</option>
                        <option value="PDC Cancelled">PDC Cancelled</option>
                        <option value="PDC Stolen">PDC Stolen</option>
                        <option value="Others">Others</option>
                    </select>
                </div>
            </div>
            <div class="form-group row">
              <div class="col-lg-12 mt-3 mt-lg-0">
                <label class="form-label">Reversal Remarks <span class="text-danger">*</span></label>
                <textarea class="form-control" id="reversal_remarks" name="reversal_remarks" maxlength="500"></textarea>
              </div>
            </div>
          </form>
        </div>
      </div>
      <div class="row">
        <div class="col-lg-12">
          <button type="submit" class="btn btn-primary" id="submit-pdc-reverse" form="mass-pdc-reverse-form">Submit</button>
          <button class="btn btn-light-danger" data-bs-dismiss="offcanvas"> Close </button>
        </div>
      </div>
    </div>
  </div>

  <div>
    <div class="offcanvas offcanvas-end" tabindex="-1" id="pdc-deposited-offcanvas" aria-labelledby="pdc-deposited-offcanvas-label">
      <div class="offcanvas-header">
        <h2 id="pdc-deposited-offcanvas-label" style="margin-bottom:-0.5rem">For Deposit PDC</h2>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
      </div>
    <div class="offcanvas-body">
      <div class="row">
        <div class="col-lg-12">
          <form id="mass-pdc-deposited-form" method="post" action="#">
            <div class="form-group row">
                <div class="col-lg-12 mt-3 mt-lg-0">
                    <label class="form-label">Deposit To <span class="text-danger">*</span></label>
                    <select class="form-control offcanvas-select2" name="deposit_to" id="deposit_to">
                      <option value="">--</option>
                      <?php echo $bankModel->generateBankOptions(); ?>
                    </select>
                </div>
            </div>
          </form>
        </div>
      </div>
      <div class="row">
        <div class="col-lg-12">
          <button type="submit" class="btn btn-primary" id="submit-pdc-deposited" form="mass-pdc-deposited-form">Submit</button>
          <button class="btn btn-light-danger" data-bs-dismiss="offcanvas"> Close </button>
        </div>
      </div>
    </div>
  </div>

  <div>
    <div class="offcanvas offcanvas-end" tabindex="-1" id="pdc-cancel-offcanvas" aria-labelledby="pdc-cancel-offcanvas-label">
      <div class="offcanvas-header">
        <h2 id="pdc-cancel-offcanvas-label" style="margin-bottom:-0.5rem">Cancel PDC</h2>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
      </div>
    <div class="offcanvas-body">
      <div class="row">
        <div class="col-lg-12">
          <form id="mass-pdc-cancel-form" method="post" action="#">
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
          <button type="submit" class="btn btn-primary" id="submit-pdc-cancel" form="mass-pdc-cancel-form">Submit</button>
          <button class="btn btn-light-danger" data-bs-dismiss="offcanvas"> Close </button>
        </div>
      </div>
    </div>
  </div>

  <div>
    <div class="offcanvas offcanvas-end" tabindex="-1" id="pdc-pulled-out-offcanvas" aria-labelledby="pdc-pulled-out-offcanvas-label">
      <div class="offcanvas-header">
        <h2 id="pdc-pulled-out-offcanvas-label" style="margin-bottom:-0.5rem">Pulled Out PDC</h2>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
      </div>
    <div class="offcanvas-body">
      <div class="row">
        <div class="col-lg-12">
          <form id="mass-pdc-pulled-out-form" method="post" action="#">
            <div class="form-group row">
              <div class="col-lg-12 mt-3 mt-lg-0">
                <label class="form-label">Pulled Out Reason <span class="text-danger">*</span></label>
                <textarea class="form-control" id="pulled_out_reason" name="pulled_out_reason" maxlength="500"></textarea>
              </div>
            </div>
          </form>
        </div>
      </div>
      <div class="row">
        <div class="col-lg-12">
          <button type="submit" class="btn btn-primary" id="submit-pdc-pulled-out" form="mass-pdc-pulled-out-form">Submit</button>
          <button class="btn btn-light-danger" data-bs-dismiss="offcanvas"> Close </button>
        </div>
      </div>
    </div>
  </div>