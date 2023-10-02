<div class="row">
  <div class="col-lg-12">
    <div class="card">
      <div class="card-header">
        <div class="row align-items-center">
          <div class="col-sm-6">
            <h5>Employee List</h5>
          </div>
          <div class="col-sm-6 text-sm-end mt-3 mt-sm-0">
            <?php
                if($employeeCreateAccess['total'] > 0 || $employeeDeleteAccess['total'] > 0){
                $action = ' ';
                            
                if($employeeDeleteAccess['total'] > 0){
                    $action .= '<div class="btn-group m-r-10">
                                <button type="button" class="btn btn-outline-secondary dropdown-toggle d-none action-dropdown" data-bs-toggle="dropdown" aria-expanded="false">Action</button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                    <li><button class="dropdown-item" type="button" id="delete-employee">Delete Employee</button></li>
                                    </ul>
                                    </div>';
                }

                if($employeeCreateAccess['total'] > 0){
                    $action .= '<a href="employee.php?new" class="btn btn-success">Create</a>';
                }

                $action .= '';
                            
                echo $action;
                }
            ?>
           <button class="btn btn-primary" type="button" data-bs-toggle="offcanvas" data-bs-target="#filter-canvas" aria-controls="filter-canvas">Filters</button>
           </div>
        </div>
      </div>
      <div class="card-body">
        <div class="table-responsive dt-responsive">
          <table id="employee-table" class="table table-striped table-hover table-bordered nowrap w-100">
            <thead>
              <tr>
                <th class="all">
                  <div class="form-check">
                    <input class="form-check-input" id="datatable-checkbox" type="checkbox">
                  </div>
                </th>
                <th>Employee</th>
                <th>Department</th>
                <th>Branch</th>
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

<div class="offcanvas offcanvas-end" tabindex="-1" id="filter-canvas" aria-labelledby="filter-canvas-label">
  <div class="offcanvas-header">
    <h5 class="offcanvas-title">Filters</h5>
    <button type="button" class="btn btn-icon btn-link-danger" data-bs-dismiss="offcanvas" aria-label="Close"><i class="ti ti-x"></i></button>
  </div>
  <div class="offcanvas-body">
    <div class="form-group mb-4">
      <label class="form-label" for="filter_employee_status">Employee Status</label>
      <select class="form-control filter-select2" name="filter_employee_status" id="filter_employee_status">
        <option value="">--</option>
        <option value="active">Active</option>
        <option value="archived">Archived</option>
      </select>
    </div>
    <div class="form-group mb-4">
      <label class="form-label" for="filter_department">Department</label>
      <select class="form-control filter-select2" name="filter_department" id="filter_department">
        <option value="">--</option>
        <?php echo $departmentModel->generateDepartmentOptions(); ?>
      </select>
    </div>
    <div class="form-group mb-4">
      <label class="form-label" for="filter_job_position">Job Position</label>
      <select class="form-control filter-select2" name="filter_job_position" id="filter_job_position">
        <option value="">--</option>
        <?php echo $jobPositionModel->generateJobPositionOptions(); ?>
      </select>
    </div>
    <div class="form-group mb-4">
      <label class="form-label" for="filter_job_level">Job Level</label>
      <select class="form-control filter-select2" name="filter_job_level" id="filter_job_level">
        <option value="">--</option>
        <?php echo $jobLevelModel->generateJobLevelOptions(); ?>
      </select>
    </div>
    <div class="form-group mb-4">
      <label class="form-label" for="filter_branch">Branch</label>
      <select class="form-control filter-select2" name="filter_branch" id="filter_branch">
        <option value="">--</option>
        <?php echo $branchModel->generateBranchOptions(); ?>
      </select>
    </div>
    <div class="form-group mb-4">
      <label class="form-label" for="filter_employee_type">Employee Type</label>
      <select class="form-control filter-select2" name="filter_employee_type" id="filter_employee_type">
        <option value="">--</option>
        <?php echo $employeeTypeModel->generateEmployeeTypeOptions(); ?>
      </select>
    </div>

    <button type="button" class="btn btn-primary" id="filter-datatable" data-bs-dismiss="offcanvas">Apply</button>
  </div>
</div>