<div class="row">
  <div class="col-lg-12">
    <div class="ecom-wrapper">
      <div class="offcanvas-xxl offcanvas-start ecom-offcanvas" tabindex="-1" id="filter-canvas">
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
                        <a class="btn border-0 px-0 text-start w-100" data-bs-toggle="collapse" href="#user-account-status-filter-collapse"><div class="float-end"><i class="ti ti-chevron-down"></i></div>
                          User Account Status
                        </a>
                        <div class="collapse show" id="user-account-status-filter-collapse">
                          <div class="row py-3">
                            <div class="col-12">
                              <div class="form-check my-2">
                                <input class="form-check-input user-account-status-filter" type="radio" name="user-account-status-filter" id="user-account-status-all" value="all" checked />
                                <label class="form-check-label" for="user-account-status-all">All</label>
                              </div>
                              <div class="form-check my-2">
                                <input class="form-check-input user-account-status-filter" type="radio" name="user-account-status-filter" id="user-account-status-active" value="active" />
                                <label class="form-check-label" for="user-account-status-active">Active</label>
                              </div>
                              <div class="form-check my-2">
                                <input class="form-check-input user-account-status-filter" type="radio" name="user-account-status-filter" id="user-account-status-inactive" value="inactive" />
                                <label class="form-check-label" for="user-account-status-inactive">Inactive</label>
                              </div>
                            </div>
                          </div>
                        </div>
                      </li>
                      <li class="list-group-item px-0 py-2">
                        <a class="btn border-0 px-0 text-start w-100" data-bs-toggle="collapse" href="#user-account-status-lock-filter-collapse"><div class="float-end"><i class="ti ti-chevron-down"></i></div>
                          User Account Lock Status
                        </a>
                        <div class="collapse show" id="user-account-status-lock-filter-collapse">
                          <div class="row py-3">
                            <div class="col-12">
                              <div class="form-check my-2">
                                <input class="form-check-input user-account-status-lock-filter" type="radio" name="user-account-status-lock-filter" id="user-account-status-all" value="all" checked />
                                <label class="form-check-label" for="user-account-status-lock-all">All</label>
                              </div>
                              <div class="form-check my-2">
                                <input class="form-check-input user-account-status-lock-filter" type="radio" name="user-account-status-lock-filter" id="user-account-status-active" value="active" />
                                <label class="form-check-label" for="user-account-status-lock-active">Active</label>
                              </div>
                              <div class="form-check my-2">
                                <input class="form-check-input user-account-status-lock-filter" type="radio" name="user-account-status-lock-filter" id="user-account-status-inactive" value="inactive" />
                                <label class="form-check-label" for="user-account-status-lock-inactive">Inactive</label>
                              </div>
                            </div>
                          </div>
                        </div>
                      </li>
                      <li class="list-group-item px-0 py-2">
                        <a class="btn border-0 px-0 text-start w-100" data-bs-toggle="collapse" href="#password-expiry-date-filter-collapse"><div class="float-end"><i class="ti ti-chevron-down"></i></div>
                          Password Expiry Date
                        </a>
                        <div class="collapse show" id="password-expiry-date-filter-collapse">
                          <div class="row py-3">
                            <div class="col-12">
                              <input type="text" class="form-control filter-datepicker mb-3" autocomplete="off" name="filter_password_expiry_date_start_date" id="filter_password_expiry_date_start_date" placeholder="Start Date">
                              <input type="text" class="form-control filter-datepicker" autocomplete="off" name="filter_password_expiry_date_end_date" id="filter_password_expiry_date_end_date" placeholder="End Date">
                            </div>
                          </div>
                        </div>
                      </li>
                      <li class="list-group-item px-0 py-2">
                        <a class="btn border-0 px-0 text-start w-100" data-bs-toggle="collapse" href="#last-connection-date-filter-collapse"><div class="float-end"><i class="ti ti-chevron-down"></i></div>
                          Last Connection Date
                        </a>
                        <div class="collapse show" id="last-connection-date-filter-collapse">
                          <div class="row py-3">
                            <div class="col-12">
                              <input type="text" class="form-control filter-datepicker mb-3" autocomplete="off" name="filter_last_connection_date_start_date" id="filter_last_connection_date_start_date" placeholder="Start Date">
                              <input type="text" class="form-control filter-datepicker" autocomplete="off" name="filter_last_connection_date_end_date" id="filter_last_connection_date_end_date" placeholder="End Date">
                            </div>
                          </div>
                        </div>
                      </li>
                      <li class="list-group-item px-0 py-2">
                        <a class="btn border-0 px-0 text-start w-100" data-bs-toggle="collapse" href="#last-password-reset-date-filter-collapse"><div class="float-end"><i class="ti ti-chevron-down"></i></div>
                          Last Password Reset Date
                        </a>
                        <div class="collapse show" id="last-password-reset-date-filter-collapse">
                          <div class="row py-3">
                            <div class="col-12">
                              <input type="text" class="form-control filter-datepicker mb-3" autocomplete="off" name="filter_last_password_reset_start_date" id="filter_last_password_reset_start_date" placeholder="Start Date">
                              <input type="text" class="form-control filter-datepicker" autocomplete="off" name="filter_last_password_reset_end_date" id="filter_last_password_reset_end_date" placeholder="End Date">
                            </div>
                          </div>
                        </div>
                      </li>
                      <li class="list-group-item px-0 py-2">
                        <a class="btn border-0 px-0 text-start w-100" data-bs-toggle="collapse" href="#last-failed-login-attempt-filter-collapse"><div class="float-end"><i class="ti ti-chevron-down"></i></div>
                          Last Failed Login Attempt Date
                        </a>
                        <div class="collapse show" id="last-failed-login-attempt-filter-collapse">
                          <div class="row py-3">
                            <div class="col-12">
                              <input type="text" class="form-control filter-datepicker mb-3" autocomplete="off" name="filter_last_failed_login_attempt_start_date" id="filter_last_failed_login_attempt_start_date" placeholder="Start Date">
                              <input type="text" class="form-control filter-datepicker" autocomplete="off" name="filter_last_failed_login_attempt_end_date" id="filter_last_failed_login_attempt_end_date" placeholder="End Date">
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
                <h5>User Account List</h5>
              </div>
              <div class="col-sm-6 text-sm-end mt-3 mt-sm-0">
                <?php
                  if($userAccountCreateAccess['total'] > 0 || $userAccountDeleteAccess['total'] > 0 || $activateUserAccount['total'] > 0 || $deactivateUserAccount['total'] > 0 || $lockUserAccount['total'] > 0 || $unlockUserAccount['total'] > 0){
                    $action = '<div class="btn-group m-r-10">
                                <button type="button" class="btn btn-outline-secondary dropdown-toggle d-none action-dropdown" data-bs-toggle="dropdown" aria-expanded="false">Action</button>
                                <ul class="dropdown-menu dropdown-menu-end">';

                    if($activateUserAccount['total'] > 0){
                      $action .= '<li><button class="dropdown-item" type="button" id="activate-user-account">Activate User Account</button></li>';
                    }

                    if($deactivateUserAccount['total'] > 0){
                      $action .= '<li><button class="dropdown-item" type="button" id="deactivate-user-account">Deactivate User Account</button></li>';
                    }

                    if($lockUserAccount['total'] > 0){
                      $action .= '<li><button class="dropdown-item" type="button" id="lock-user-account">Lock User Account</button></li>';
                    }

                    if($unlockUserAccount['total'] > 0){
                      $action .= '<li><button class="dropdown-item" type="button" id="unlock-user-account">Unlock User Account</button></li>';
                      }

                    if($userAccountDeleteAccess['total'] > 0){
                      $action .= '<li><button class="dropdown-item" type="button" id="delete-user-account">Delete User Account</button></li>';
                    }

                    $action .= '</ul>
                            </div>';

                    if($userAccountCreateAccess['total'] > 0){
                      $action .= '<a href="user-account.php?new" class="btn btn-success">Create</a>';
                    }
                                
                    echo $action;
                  }
                ?>
                <button type="button" class="d-xxl-none btn btn-warning" data-bs-toggle="offcanvas" data-bs-target="#filter-canvas">
                  Filter
                </button>
                <button type="button" class="d-none d-xxl-inline-flex btn btn-warning" data-bs-toggle="collapse" data-bs-target="#filter-canvas">
                  Filter
                </button>
              </div>
            </div>
          </div>
          <div class="card-body">
            <div class="table-responsive dt-responsive">
              <table id="user-account-table" class="table table-hover nowrap w-100">
                <thead>
                  <tr>
                    <th class="all">
                      <div class="form-check">
                        <input class="form-check-input" id="datatable-checkbox" type="checkbox">
                      </div>
                    </th>
                    <th>User Account</th>
                    <th class="all">Status</th>
                    <th class="all">Locked</th>
                    <th class="all">Password Expiry Date</th>
                    <th class="all">Last Connection</th>
                    <th class="all">Last Password Reset</th>
                    <th class="all">Last Failed Login Attempt</th>
                    <th class="all">Actions</th>
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