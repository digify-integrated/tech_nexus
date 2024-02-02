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
                        <a class="btn border-0 px-0 text-start w-100" data-bs-toggle="collapse" href="#attendance-record-date-date-filter-collapse"><div class="float-end"><i class="ti ti-chevron-down"></i></div>
                          Attendance Record Date
                        </a>
                        <div class="collapse show" id="attendance-record-date-date-filter-collapse">
                          <div class="row py-3">
                            <div class="col-12">
                              <input type="text" class="form-control filter-datepicker mb-3" autocomplete="off" name="filter_attendance_record_date_start_date" id="filter_attendance_record_date_start_date" placeholder="Start Date">
                              <input type="text" class="form-control filter-datepicker" autocomplete="off" name="filter_attendance_record_date_end_date" id="filter_attendance_record_date_end_date" placeholder="End Date">
                            </div>
                          </div>
                        </div>
                      </li>
                      <li class="list-group-item px-0 py-2">
                        <a class="btn border-0 px-0 text-start w-100" data-bs-toggle="collapse" href="#check-in-mode-filter-collapse"><div class="float-end"><i class="ti ti-chevron-down"></i></div>
                          Check In Mode
                        </a>
                        <div class="collapse show" id="check-in-mode-filter-collapse">
                          <div class="py-3">
                            <div class="form-check my-2">
                                <input class="form-check-input check-in-mode-filter" type="checkbox" id="check-in-mode-regular" value="Regular">
                                <label class="form-check-label" for="check-in-mode-regular">Regular</label>
                            </div>
                            <div class="form-check my-2">
                                <input class="form-check-input check-in-mode-filter" type="checkbox" id="check-in-mode-biometrics" value="Biometrics">
                                <label class="form-check-label" for="check-in-mode-biometrics">Biometrics</label>
                            </div>
                            <div class="form-check my-2">
                                <input class="form-check-input check-in-mode-filter" type="checkbox" id="check-in-mode-kiosk" value="Kiosk">
                                <label class="form-check-label" for="check-in-mode-kiosk">Kiosk</label>
                            </div>
                            <div class="form-check my-2">
                                <input class="form-check-input check-in-mode-filter" type="checkbox" id="check-in-mode-manual" value="Manual">
                                <label class="form-check-label" for="check-in-mode-manual">Manual</label>
                            </div>
                            <div class="form-check my-2">
                                <input class="form-check-input check-in-mode-filter" type="checkbox" id="check-in-mode-pin" value="Pin">
                                <label class="form-check-label" for="check-in-mode-pin">Pin</label>
                            </div>
                          </div>
                        </div>
                      </li>
                      <li class="list-group-item px-0 py-2">
                        <a class="btn border-0 px-0 text-start w-100" data-bs-toggle="collapse" href="#check-out-mode-filter-collapse"><div class="float-end"><i class="ti ti-chevron-down"></i></div>
                          Check Out Mode
                        </a>
                        <div class="collapse show" id="check-out-mode-filter-collapse">
                          <div class="py-3">
                            <div class="form-check my-2">
                                <input class="form-check-input check-out-mode-filter" type="checkbox" id="check-out-mode-regular" value="Regular">
                                <label class="form-check-label" for="check-out-mode-regular">Regular</label>
                            </div>
                            <div class="form-check my-2">
                                <input class="form-check-input check-out-mode-filter" type="checkbox" id="check-out-mode-biometrics" value="Biometrics">
                                <label class="form-check-label" for="check-out-mode-biometrics">Biometrics</label>
                            </div>
                            <div class="form-check my-2">
                                <input class="form-check-input check-out-mode-filter" type="checkbox" id="check-out-mode-kiosk" value="Kiosk">
                                <label class="form-check-label" for="check-out-mode-kiosk">Kiosk</label>
                            </div>
                            <div class="form-check my-2">
                                <input class="form-check-input check-out-mode-filter" type="checkbox" id="check-out-mode-manual" value="Manual">
                                <label class="form-check-label" for="check-out-mode-manual">Manual</label>
                            </div>
                            <div class="form-check my-2">
                                <input class="form-check-input check-out-mode-filter" type="checkbox" id="check-out-mode-pin" value="Pin">
                                <label class="form-check-label" for="check-out-mode-pin">Pin</label>
                            </div>
                          </div>
                        </div>
                      </li>
                      <li class="list-group-item px-0 py-2">
                        <a class="btn border-0 px-0 text-start w-100" data-bs-toggle="collapse" href="#employment-status-filter-collapse"><div class="float-end"><i class="ti ti-chevron-down"></i></div>
                          Employment Status
                        </a>
                        <div class="collapse show" id="employment-status-filter-collapse">
                          <div class="row py-3">
                            <div class="col-12">
                              <div class="form-check my-2">
                                <input class="form-check-input employment-status-filter" type="radio" name="employment-status-filter" id="employment-status-all" value="all" checked />
                                <label class="form-check-label" for="employment-status-all">All</label>
                              </div>
                              <div class="form-check my-2">
                                <input class="form-check-input employment-status-filter" type="radio" name="employment-status-filter" id="employment-status-active" value="active" />
                                <label class="form-check-label" for="employment-status-active">Active</label>
                              </div>
                              <div class="form-check my-2">
                                <input class="form-check-input employment-status-filter" type="radio" name="employment-status-filter" id="employment-status-inactive" value="inactive" />
                                <label class="form-check-label" for="employment-status-inactive">Inactive</label>
                              </div>
                            </div>
                          </div>
                        </div>
                      </li>
                      <li class="list-group-item px-0 py-2">
                        <a class="btn border-0 px-0 text-start w-100" data-bs-toggle="collapse" href="#company-filter-collapse"><div class="float-end"><i class="ti ti-chevron-down"></i></div>
                          Company
                        </a>
                        <div class="collapse show" id="company-filter-collapse">
                          <div class="py-3">
                            <?php
                              echo $companyModel->generateCompanyCheckBox();
                            ?>
                          </div>
                        </div>
                      </li>
                      <li class="list-group-item px-0 py-2">
                        <a class="btn border-0 px-0 text-start w-100" data-bs-toggle="collapse" href="#department-filter-collapse"><div class="float-end"><i class="ti ti-chevron-down"></i></div>
                          Department
                        </a>
                        <div class="collapse show" id="department-filter-collapse">
                          <div class="py-3">
                            <?php
                              echo $departmentModel->generateDepartmentCheckBox();
                            ?>
                          </div>
                        </div>
                      </li>
                      <li class="list-group-item px-0 py-2">
                        <a class="btn border-0 px-0 text-start w-100" data-bs-toggle="collapse" href="#job-position-filter-collapse"><div class="float-end"><i class="ti ti-chevron-down"></i></div>
                          Job Position
                        </a>
                        <div class="collapse show" id="job-position-filter-collapse">
                          <div class="py-3">
                            <?php
                              echo $jobPositionModel->generateJobPositionCheckBox();
                            ?>
                          </div>
                        </div>
                      </li>
                      <li class="list-group-item px-0 py-2">
                        <a class="btn border-0 px-0 text-start w-100" data-bs-toggle="collapse" href="#branch-filter-collapse"><div class="float-end"><i class="ti ti-chevron-down"></i></div>
                          Branch
                        </a>
                        <div class="collapse show" id="branch-filter-collapse">
                          <div class="py-3">
                            <?php
                              echo $branchModel->generateBranchCheckBox();
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
      <div class="ecom-content w-100">
        <div class="card table-card">
          <div class="card-header">
            <div class="row align-items-center">
              <div class="col-sm-6">
                <h5>Attendance Record List</h5>
              </div>
              <div class="col-sm-6 text-sm-end mt-3 mt-sm-0">
                <?php
                  if($attendanceRecordCreateAccess['total'] > 0 || $attendanceRecordDeleteAccess['total'] > 0 || $importAttendance['total'] > 0){
                    $action = '';
                                
                    if($attendanceRecordDeleteAccess['total'] > 0){
                      $action .= '<div class="btn-group m-r-10">
                                    <button type="button" class="btn btn-outline-secondary dropdown-toggle d-none action-dropdown" data-bs-toggle="dropdown" aria-expanded="false">Action</button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                      <li><button class="dropdown-item" type="button" id="delete-attendance-record">Delete Attendance Record</button></li>
                                    </ul>
                                  </div>';
                    }

                    if($attendanceRecordCreateAccess['total'] > 0){
                      $action .= '<a href="attendance-record.php?new" class="btn btn-success">Create</a>';
                    }
                                
                    echo $action;
                  }

                  if($importAttendance['total'] > 0){
                    echo ' <a href="attendance-record.php?import" class="btn btn-info">Import</a>';
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
              <table id="attendance-record-table" class="table table-hover nowrap w-100">
                <thead>
                  <tr>
                    <th class="all">
                      <div class="form-check">
                        <input class="form-check-input" id="datatable-checkbox" type="checkbox">
                      </div>
                    </th>
                    <th>Employee</th>
                    <th>Check In</th>
                    <th>Check In Mode</th>
                    <th>Check Out</th>
                    <th>Check Out Mode</th>
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