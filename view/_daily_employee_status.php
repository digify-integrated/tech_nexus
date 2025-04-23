<div class="row">
  <div class="col-lg-6">
    <div class="card">
      <div class="card-body">
        <div class="row align-items-center">
          <div class="col-12">
            <h3 class="mb-1" id="present-count">0</h3>
            <p class="text-muted mb-0">Present</p>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-lg-6">
    <div class="card">
      <div class="card-body">
        <div class="row align-items-center">
          <div class="col-12">
            <h3 class="mb-1" id="late-count">0</h3>
            <p class="text-muted mb-0">Late</p>
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
            <h3 class="mb-1" id="absent-count">0</h3>
            <p class="text-muted mb-0">Absent</p>
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
            <h3 class="mb-1" id="on-leave-count">0</h3>
            <p class="text-muted mb-0">On-Leave</p>
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
            <h3 class="mb-1" id="official-business-count">0</h3>
            <p class="text-muted mb-0">Official Business</p>
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
                          Attendance Date
                        </a>
                        <div class="collapse " id="transaction-date-filter-collapse">
                          <div class="row py-3">
                            <div class="col-12">
                              <input type="text" class="form-control filter-datepicker mb-3" autocomplete="off" name="filter_attendance_date" id="filter_attendance_date" placeholder="Attendance Date" value="<?php echo date('m/d/Y') ?>">
                            </div>
                          </div>
                        </div>
                      </li>
                      <li class="list-group-item px-0 py-2">
                        <a class="btn border-0 px-0 text-start w-100" data-bs-toggle="collapse" href="#status-filter-collapse"><div class="float-end"><i class="ti ti-chevron-down"></i></div>
                          Status
                        </a>
                        <div class="collapse" id="status-filter-collapse">
                            <div class="form-group row">
                                <div class="col-lg-12 mt-3 mt-lg-0">
                                    <div class="col-12">
                                        <div class="form-check my-2">
                                            <input class="form-check-input status-checkbox" type="checkbox" id="status-present" value="Present" checked/>
                                            <label class="form-check-label" for="status-present">Present</label>
                                        </div>
                                        <div class="form-check my-2">
                                            <input class="form-check-input status-checkbox" type="checkbox" id="status-absent" value="Absent" checked/>
                                            <label class="form-check-label" for="status-absent">Absent</label>
                                        </div>
                                        <div class="form-check my-2">
                                            <input class="form-check-input status-checkbox" type="checkbox" id="status-late" value="Late" checked/>
                                            <label class="form-check-label" for="status-late">Late</label>
                                        </div>
                                        <div class="form-check my-2">
                                            <input class="form-check-input status-checkbox" type="checkbox" id="status-on-leave" value="On-Leave" checked/>
                                            <label class="form-check-label" for="status-on-leave">On-Leave</label>
                                        </div>
                                        <div class="form-check my-2">
                                            <input class="form-check-input status-checkbox" type="checkbox" id="status-official-business" value="Official Business" checked/>
                                            <label class="form-check-label" for="status-official-business">Official Business</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                      </li>
                      <li class="list-group-item px-0 py-2">
                        <a class="btn border-0 px-0 text-start w-100" data-bs-toggle="collapse" href="#branch-filter-collapse"><div class="float-end"><i class="ti ti-chevron-down"></i></div>
                          Branch
                        </a>
                        <div class="collapse" id="branch-filter-collapse">
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
                <h5>Employee Daily Attendance</h5>
              </div>
              <div class="col-sm-6 text-sm-end mt-3 mt-sm-0">
                <?php                 
                  if($dailyEmployeeStatusWriteAccess['total'] > 0){
                    $action = '<div class="btn-group m-r-10">
                                      <button type="button" class="btn btn-outline-secondary dropdown-toggle d-none action-dropdown" data-bs-toggle="dropdown" aria-expanded="false">Action</button>
                                      <ul class="dropdown-menu dropdown-menu-end">
                                        <li><button class="dropdown-item" type="button" id="tag-as-present">Tag As Present</button></li>
                                        <li><button class="dropdown-item" type="button" id="tag-as-absent">Tag As Absent</button></li>
                                        <li><button class="dropdown-item" type="button" id="tag-as-late">Tag As Late</button></li>
                                        <li><button class="dropdown-item" type="button" id="tag-as-on-leave">Tag As On-Leave</button></li>
                                        <li><button class="dropdown-item" type="button" id="tag-as-official-business">Tag As Official Business</button></li>
                                    </ul>
                                </div>';
                                  
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
              <table id="daily-employee-status-table" class="table table-hover text-wrap w-100 text-uppercase">
                <thead>
                  <tr>
                    <th class="all">
                      <div class="form-check">
                        <input class="form-check-input" id="datatable-checkbox" type="checkbox">
                      </div>
                    </th>
                    <th>Employee</th>
                    <th>Branch</th>
                    <th>Status</th>
                    <th>Attendance Date</th>
                    <th>Remarks</th>
                    <th>Action</th>
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
    <div class="offcanvas offcanvas-end" tabindex="-1" id="add-remarks-offcanvas" aria-labelledby="add-remarks-offcanvas-label">
      <div class="offcanvas-header">
        <h2 id="add-remarks-offcanvas-label" style="margin-bottom:-0.5rem">Add Remarks</h2>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
      </div>
    <div class="offcanvas-body">
      <div class="row">
        <div class="col-lg-12">
          <form id="add-remarks-form" method="post" action="#">
            <div class="form-group row">
              <div class="col-lg-12 mt-3 mt-lg-0">
                <label class="form-label">Remarks <span class="text-danger">*</span></label>
                <textarea class="form-control" id="remarks" name="remarks" maxlength="500"></textarea>
              </div>
            </div>
          </form>
        </div>
      </div>
      <div class="row">
        <div class="col-lg-12">
          <button type="submit" class="btn btn-primary" id="submit-add-remarks" form="add-remarks-form">Submit</button>
          <button class="btn btn-light-danger" data-bs-dismiss="offcanvas"> Close </button>
        </div>
      </div>
    </div>
  </div>