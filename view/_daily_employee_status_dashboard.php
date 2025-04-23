<div class="row">
  
  <div class="col-xl-12 col-md-12">
    <div class="card table-card">
      <div class="card-header">
        <div class="row align-items-center">
          <div class="col-sm-6">
            <h5>Employee Daily Attendance</h5>
          </div>
          <div class="col-sm-6 text-sm-end mt-3 mt-sm-0">
            <button type="button" class="btn btn-warning" data-bs-toggle="offcanvas" data-bs-target="#filter-canvas">
              Filter
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-xl-6 col-md-12">
    <div class="card new-cust-card">
      <div class="card-header">
        <h5>Absent Employees</h5>
      </div>
      <div class="customer-scroll" style="max-height: 415px; position: relative; overflow-y: auto">
        <div class="card-body p-b-0" id="absent-employee-list">
         
        </div>
      </div>
    </div>
  </div>
  <div class="col-xl-6 col-md-12">
    <div class="card new-cust-card">
      <div class="card-header">
        <h5>Late Employees</h5>
      </div>
      <div class="customer-scroll" style="max-height: 415px; position: relative; overflow-y: auto">
        <div class="card-body p-b-0" id="late-employee-list">
          
        </div>
      </div>
    </div>
  </div>
  <div class="col-xl-6 col-md-12">
    <div class="card new-cust-card">
      <div class="card-header">
        <h5>On-Leave Employees</h5>
      </div>
      <div class="customer-scroll" style="max-height: 415px; position: relative; overflow-y: auto" >
        <div class="card-body p-b-0" id="on-leave-employee-list">
          
        </div>
      </div>
    </div>
  </div>
  <div class="col-xl-6 col-md-12">
    <div class="card new-cust-card">
      <div class="card-header">
        <h5>Official Business Employees</h5>
      </div>
      <div class="customer-scroll" style="max-height: 415px; position: relative; overflow-y: auto">
        <div class="card-body p-b-0" id="official-business-employee-list">
          
        </div>
      </div>
    </div>
  </div>
</div>

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
                        <button type="button" class="btn btn-light-success w-100" id="apply-dashboard-filter-attendance-status">Apply</a>
                      </li>
                    </ul>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      </div>