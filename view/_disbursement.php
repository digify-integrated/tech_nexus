<?php
  $pettyCashFundUnreplenished = $disbursementModel->getUnreplishedDisbursement('Petty Cash')['total'] ?? 0;
  $revolvingFundUnreplenished = $disbursementModel->getUnreplishedDisbursement('Revolving Fund')['total'] ?? 0;

  $pettyCashFund = $systemSettingModel->getSystemSetting(20)['value'] ?? 0;
  $revolvingFund = $systemSettingModel->getSystemSetting(21)['value'] ?? 0;

  $maxPettyCashFund = $systemSettingModel->getSystemSetting(22)['value'] ?? 0;
  $maxRevolvingFund = $systemSettingModel->getSystemSetting(23)['value'] ?? 0;

  $pettyCashFundPercent = ($pettyCashFundUnreplenished / $maxPettyCashFund) * 100;
  $revolvingFundPercent = ($revolvingFundUnreplenished / $maxRevolvingFund) * 100;
?>

<div class="row">
  <div class="col-lg-4">
    <div class="card">
      <div class="card-body">
        <div class="row align-items-center">
          <div class="col-12">
            <h3 class="mb-1"><?php echo number_format($pettyCashFund, 2); ?> Php</h3>
            <p class="text-muted mb-0">Petty Cash Fund</p>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-lg-4">
    <div class="card">
      <div class="card-body">
        <div class="row align-items-center">
          <div class="col-12">
            <h3 class="mb-1"><?php echo number_format($pettyCashFundUnreplenished, 2); ?> Php</h3>
            <p class="text-muted mb-0">Unreplenished Petty Cash Fund</p>
          </div>
        </div>
      </div>
    </div>
  </div>   
  <div class="col-lg-4">
    <div class="card">
      <div class="card-body">
        <div class="row align-items-center">
          <div class="col-12">
            <h3 class="mb-1"><?php echo number_format($pettyCashFundPercent, 2); ?> %</h3>
            <p class="text-muted mb-0">Replenishment Percentage</p>
          </div>
        </div>
      </div>
    </div>
  </div>
  

  <div class="col-lg-4">
    <div class="card">
      <div class="card-body">
        <div class="row align-items-center">
          <div class="col-12">
            <h3 class="mb-1"><?php echo number_format($revolvingFund, 2); ?> Php</h3>
            <p class="text-muted mb-0">Revolving Fund</p>
          </div>
        </div>
      </div>
    </div>
  </div>  
  <div class="col-lg-4">
    <div class="card">
      <div class="card-body">
        <div class="row align-items-center">
          <div class="col-12">
            <h3 class="mb-1"><?php echo number_format($revolvingFundUnreplenished, 2); ?> Php</h3>
            <p class="text-muted mb-0">Unreplenished Revolving Fund</p>
          </div>
        </div>
      </div>
    </div>
  </div>   
  <div class="col-lg-4">
    <div class="card">
      <div class="card-body">
        <div class="row align-items-center">
          <div class="col-12">
            <h3 class="mb-1"><?php echo number_format($revolvingFundPercent, 2); ?> %</h3>
            <p class="text-muted mb-0">Replenishment Percentage</p>
          </div>
        </div>
      </div>
    </div>
  </div>   
</div>

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
                        <a class="btn border-0 px-0 text-start w-100" data-bs-toggle="collapse" href="#replenishment-date-filter-collapse"><div class="float-end"><i class="ti ti-chevron-down"></i></div>
                          Replenishment Date
                        </a>
                        <div class="collapse " id="replenishment-date-filter-collapse">
                          <div class="row py-3">
                            <div class="col-12">
                              <input type="text" class="form-control filter-datepicker mb-3" autocomplete="off" name="filter_replenishment_date_start_date" id="filter_replenishment_date_start_date" placeholder="Start Date">
                              <input type="text" class="form-control filter-datepicker" autocomplete="off" name="filter_replenishment_date_end_date" id="filter_replenishment_date_end_date" placeholder="End Date" >
                            </div>
                          </div>
                        </div>
                      </li>
                      <li class="list-group-item px-0 py-2">
                        <a class="btn border-0 px-0 text-start w-100" data-bs-toggle="collapse" href="#fund-source-filter-collapse"><div class="float-end"><i class="ti ti-chevron-down"></i></div>
                          Fund Source
                        </a>
                        <div class="collapse" id="fund-source-filter-collapse">
                          <div class="row py-3">
                            <div class="col-12">
                              <div class="form-check my-2">
                                <input class="form-check-input fund-source-filter" type="radio" name="fund-source-filter" id="fund-source-all" value="" checked />
                                <label class="form-check-label" for="fund-source-all">All</label>
                              </div>
                              <div class="form-check my-2">
                                <input class="form-check-input fund-source-filter" type="radio" name="fund-source-filter" id="fund-source-petty-cash" value="Petty Cash" />
                                <label class="form-check-label" for="fund-source-petty-cash">Petty Cash</label>
                              </div>
                              <div class="form-check my-2">
                                <input class="form-check-input fund-source-filter" type="radio" name="fund-source-filter" id="fund-source-revolving-fund" value="Revolving Fund" />
                                <label class="form-check-label" for="fund-source-revolving-fund">Revolving Fund</label>
                              </div>
                            </div>
                          </div>
                        </div>
                      </li>
                      <li class="list-group-item px-0 py-2">
                        <a class="btn border-0 px-0 text-start w-100" data-bs-toggle="collapse" href="#transaction-type-filter-collapse"><div class="float-end"><i class="ti ti-chevron-down"></i></div>
                          Transaction Type
                        </a>
                        <div class="collapse" id="transaction-type-filter-collapse">
                          <div class="row py-3">
                            <div class="col-12">
                              <div class="form-check my-2">
                                <input class="form-check-input transaction-type-filter" type="radio" name="transaction-type-filter" id="transaction-type-all" value="" />
                                <label class="form-check-label" for="transaction-type-all">All</label>
                              </div>
                              <div class="form-check my-2">
                                <input class="form-check-input transaction-type-filter" type="radio" name="transaction-type-filter" id="transaction-type-petty-cash" value="Replenishment" />
                                <label class="form-check-label" for="transaction-type-petty-cash">Replenishment</label>
                              </div>
                              <div class="form-check my-2">
                                <input class="form-check-input transaction-type-filter" type="radio" name="transaction-type-filter" id="transaction-type-revolving-fund" value="Disbursement" checked />
                                <label class="form-check-label" for="transaction-type-revolving-fund">Disbursement</label>
                              </div>
                            </div>
                          </div>
                        </div>
                      </li>
                      <li class="list-group-item px-0 py-2">
                        <a class="btn border-0 px-0 text-start w-100" data-bs-toggle="collapse" href="#disbursement-status-filter-collapse"><div class="float-end"><i class="ti ti-chevron-down"></i></div>
                          Disbursement Status
                        </a>
                        <div class="collapse" id="disbursement-status-filter-collapse">
                          <div class="row py-3">
                            <div class="col-12">
                              <div class="form-check my-2">
                                <input class="form-check-input disbursement-status-checkbox" type="checkbox" id="disbursement-status-draft" value="Draft"/>
                                <label class="form-check-label" for="disbursement-status-draft">Draft</label>
                              </div>
                              <div class="form-check my-2">
                                <input class="form-check-input disbursement-status-checkbox" type="checkbox" id="disbursement-status-posted" value="Posted" checked/>
                                <label class="form-check-label" for="disbursement-status-posted">Posted</label>
                              </div>
                              <div class="form-check my-2">
                                <input class="form-check-input disbursement-status-checkbox" type="checkbox" id="disbursement-status-replenished" value="Replenished"/>
                                <label class="form-check-label" for="disbursement-status-replenished">Replenished</label>
                              </div>
                              <div class="form-check my-2">
                                <input class="form-check-input disbursement-status-checkbox" type="checkbox" id="disbursement-status-cancelled" value="Cancelled"/>
                                <label class="form-check-label" for="disbursement-status-cancelled">Cancelled</label>
                              </div>
                              <div class="form-check my-2">
                                <input class="form-check-input disbursement-status-checkbox" type="checkbox" id="disbursement-status-reversed" value="Reversed"/>
                                <label class="form-check-label" for="disbursement-status-reversed">Reversed</label>
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
      <div class="ecom-content w-100">
        <div class="card table-card">
          <div class="card-header">
            <div class="row align-items-center">
              <div class="col-sm-6">
                <h5>Disbursement List</h5>
              </div>
              <div class="col-sm-6 text-sm-end mt-3 mt-sm-0">
                <?php                 
                  if($disbursementCreateAccess['total'] > 0 || $disbursementDeleteAccess['total'] > 0 || $disbursementCreateAccess['total'] > 0){
                    $action = '<div class="btn-group m-r-10">
                                      <button type="button" class="btn btn-outline-secondary dropdown-toggle d-none action-dropdown" data-bs-toggle="dropdown" aria-expanded="false">Action</button>
                                      <ul class="dropdown-menu dropdown-menu-end">
                                        <li><a href="javascript:void(0);" id="print-report" class="dropdown-item" target="_blank" id="">Print Report</a></li>';
                                  
                    if($replenishmentDisbursement['total'] > 0){
                      $action .= '<li><button class="dropdown-item" type="button" id="replenish-disbursement">Replenish Disbursement</button></li>';
                    }

                    if ($postDisbursement['total'] > 0) {
                      $action .= '<li><button class="dropdown-item" type="button" id="post-disbursement">Post Disbursement</button></li>';
                    }
                                  
                    if($disbursementDeleteAccess['total'] > 0){
                      $action .= '<li><button class="dropdown-item" type="button" id="delete-disbursement">Delete Disbursement</button></li>';
                    }

                    $action .= '</ul>
                                    </div>';

                    if($disbursementCreateAccess['total'] > 0 ){
                      $action .= '<a href="disbursement.php?new" class="btn btn-success">Create</a>';
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
              <table id="disbursement-table" class="table table-hover text-wrap w-100 text-uppercase">
                <thead>
                  <tr>
                    <th class="all">
                      <div class="form-check">
                        <input class="form-check-input" id="datatable-checkbox" type="checkbox">
                      </div>
                    </th>
                    <th>CDV Date</th>
                    <th>Customer</th>
                    <th>Company</th>
                    <th>CDV No.</th>
                    <th>Amount</th>
                    <th>Transaction Type</th>
                    <th>Particulars</th>
                    <th>Status</th>
                    <th>Department</th>
                    <th>Fund Source</th>
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
  </div>
</div>

<div class="row">
  <div class="col-lg-12">
    <div class="card">
      <div class="card-body">
        <div class="row align-items-center">
          <div class="col-9">
            <h3 class="mb-1 text-end">Total Disbursement:</h3>
          </div>
          <div class="col-3">
            <h3 class="mb-1 text-end" id="total-disbursement">0.00 Php</h3>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>