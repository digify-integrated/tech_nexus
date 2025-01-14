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
                        <a class="btn border-0 px-0 text-start w-100" data-bs-toggle="collapse" href="#collections-status-filter-collapse"><div class="float-end"><i class="ti ti-chevron-down"></i></div>
                          Collection Status
                        </a>
                        <div class="collapse " id="collections-status-filter-collapse">
                          <div class="row py-3">
                            <div class="col-12">
                              <div class="form-check my-2">
                                <input class="form-check-input collections-status-filter" type="radio" name="collections-status-filter" id="collections-status-all" value="" checked/>
                                <label class="form-check-label" for="collections-status-all">All</label>
                              </div>
                              <div class="form-check my-2">
                                <input class="form-check-input collections-status-filter" type="radio" name="collections-status-filter" id="collections-status-posted" value="Posted" />
                                <label class="form-check-label" for="collections-status-posted">Posted</label>
                              </div>
                              <div class="form-check my-2">
                                <input class="form-check-input collections-status-filter" type="radio" name="collections-status-filter" id="collections-reversed" value="Reversed" />
                                <label class="form-check-label" for="collections-reversed">Reversed</label>
                              </div>
                            </div>
                          </div>
                        </div>
                      </li>
                      <li class="list-group-item px-0 py-2">
                        <a class="btn border-0 px-0 text-start w-100" data-bs-toggle="collapse" href="#transaction-date-filter-collapse"><div class="float-end"><i class="ti ti-chevron-down"></i></div>
                          Transaction Date
                        </a>
                        <div class="collapse " id="transaction-date-filter-collapse">
                          <div class="row py-3">
                            <div class="col-12">
                              <input type="text" class="form-control filter-datepicker mb-3" autocomplete="off" name="filter_transaction_date_start_date" id="filter_transaction_date_start_date" placeholder="Start Date">
                              <input type="text" class="form-control filter-datepicker" autocomplete="off" name="filter_transaction_date_end_date" id="filter_transaction_date_end_date" placeholder="End Date" >
                            </div>
                          </div>
                        </div>
                      </li>
                      <li class="list-group-item px-0 py-2">
                        <a class="btn border-0 px-0 text-start w-100" data-bs-toggle="collapse" href="#payment-date-filter-collapse"><div class="float-end"><i class="ti ti-chevron-down"></i></div>
                          Payment Date
                        </a>
                        <div class="collapse " id="payment-date-filter-collapse">
                          <div class="row py-3">
                            <div class="col-12">
                              <input type="text" class="form-control filter-datepicker mb-3" autocomplete="off" name="filter_payment_date_start_date" id="filter_payment_date_start_date" placeholder="Start Date">
                              <input type="text" class="form-control filter-datepicker" autocomplete="off" name="filter_payment_date_end_date" id="filter_payment_date_end_date" placeholder="End Date">
                            </div>
                          </div>
                        </div>
                      </li>
                      <li class="list-group-item px-0 py-2">
                        <a class="btn border-0 px-0 text-start w-100" data-bs-toggle="collapse" href="#or-date-filter-collapse"><div class="float-end"><i class="ti ti-chevron-down"></i></div>
                          OR Date
                        </a>
                        <div class="collapse " id="or-date-filter-collapse">
                          <div class="row py-3">
                            <div class="col-12">
                              <input type="text" class="form-control filter-datepicker mb-3" autocomplete="off" name="filter_or_date_start_date" id="filter_or_date_start_date" placeholder="Start Date">
                              <input type="text" class="form-control filter-datepicker" autocomplete="off" name="filter_or_date_end_date" id="filter_or_date_end_date" placeholder="End Date">
                            </div>
                          </div>
                        </div>
                      </li>
                      <li class="list-group-item px-0 py-2">
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
                      <li class="list-group-item px-0 py-2">
                        <a class="btn border-0 px-0 text-start w-100" data-bs-toggle="collapse" href="#cancellation-date-filter-collapse"><div class="float-end"><i class="ti ti-chevron-down"></i></div>
                          Cancellation Date
                        </a>
                        <div class="collapse " id="cancellation-date-filter-collapse">
                          <div class="row py-3">
                            <div class="col-12">
                              <input type="text" class="form-control filter-datepicker mb-3" autocomplete="off" name="filter_cancellation_date_start_date" id="filter_cancellation_date_start_date" placeholder="Start Date">
                              <input type="text" class="form-control filter-datepicker" autocomplete="off" name="filter_cancellation_date_end_date" id="filter_cancellation_date_end_date" placeholder="End Date">
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
                <h5>Collection List</h5>
              </div>
              <div class="col-sm-6 text-sm-end mt-3 mt-sm-0">
                <?php                 
                  if($collectionsCreateAccess['total'] > 0 || $collectionsDeleteAccess['total'] > 0 || $collectionsCreateAccess['total'] > 0){
                    $action = '<div class="btn-group m-r-10">
                                      <button type="button" class="btn btn-outline-secondary dropdown-toggle d-none action-dropdown" data-bs-toggle="dropdown" aria-expanded="false">Action</button>
                                      <ul class="dropdown-menu dropdown-menu-end">';
                                  
                    if($collectionsDeleteAccess['total'] > 0){
                      $action .= '<li><button class="dropdown-item" type="button" id="delete-collections">Delete Collection</button></li>';
                    }

                    $action .= '</ul>
                                    </div>';

                    if($collectionsCreateAccess['total'] > 0){
                      $action .= '<a href="payment-advice.php?new" class="btn btn-success">Create</a>';
                    }
                                  
                    echo $action;
                  }
                ?>
                <button type="button" class="btn btn-warning" id="print">
                  Print
                </button>
                <button type="button" class="btn btn-warning" data-bs-toggle="offcanvas" data-bs-target="#filter-canvas">
                  Filter
                </button>
              </div>
            </div>
          </div>
          <div class="card-body">
            <div class="table-responsive dt-responsive">
              <table id="payment-advice-table" class="table table-hover text-wrap w-100 text-uppercase">
                <thead>
                  <tr>
                    <th class="all">
                      <div class="form-check">
                        <input class="form-check-input" id="datatable-checkbox" type="checkbox">
                      </div>
                    </th>
                    <th>Actions</th>
                    <th>Payment Date</th>
                    <th>Transaction Date</th>
                    <th>Mode of Payment</th>
                    <th>Payment Amount</th>
                    <th>OR Number</th>
                    <th>OR Date</th>
                    <th>Payment Details</th>
                    <th>Status</th>
                    <th>Loan Number</th>
                    <th>Customer</th>
                    <th>Collected From</th>
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
    <div class="offcanvas offcanvas-end" tabindex="-1" id="collections-reverse-offcanvas" aria-labelledby="collections-reverse-offcanvas-label">
      <div class="offcanvas-header">
        <h2 id="collections-reverse-offcanvas-label" style="margin-bottom:-0.5rem">Reverse PDC</h2>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
      </div>
    <div class="offcanvas-body">
      <div class="row">
        <div class="col-lg-12">
          <form id="mass-collections-reverse-form" method="post" action="#">
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
          <button type="submit" class="btn btn-primary" id="submit-collections-reverse" form="mass-collections-reverse-form">Submit</button>
          <button class="btn btn-light-danger" data-bs-dismiss="offcanvas"> Close </button>
        </div>
      </div>
    </div>
  </div>

  <div>
    <div class="offcanvas offcanvas-end" tabindex="-1" id="collections-cancel-offcanvas" aria-labelledby="collections-cancel-offcanvas-label">
      <div class="offcanvas-header">
        <h2 id="collections-cancel-offcanvas-label" style="margin-bottom:-0.5rem">Cancel PDC</h2>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
      </div>
    <div class="offcanvas-body">
      <div class="row">
        <div class="col-lg-12">
          <form id="mass-collections-cancel-form" method="post" action="#">
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
          <button type="submit" class="btn btn-primary" id="submit-collections-cancel" form="mass-collections-cancel-form">Submit</button>
          <button class="btn btn-light-danger" data-bs-dismiss="offcanvas"> Close </button>
        </div>
      </div>
    </div>
  </div>