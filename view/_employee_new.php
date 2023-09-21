<div class="row">
  <div class="col-lg-12">
    <div class="card">
      <div class="card-header">
        <div class="row align-items-center">
          <div class="col-md-6">
            <h5>Employee</h5>
          </div>
          <?php
            if ($employeeCreateAccess['total'] > 0) {
               echo '<div class="col-md-6 text-sm-end mt-3 mt-sm-0">
                      <button type="submit" form="add-employee-form" class="btn btn-success form-edit" id="submit-data">Save</button>
                      <button type="button" id="discard-create" class="btn btn-outline-danger form-edit">Discard</button>
                    </div>';
            }
          ?>
        </div>
      </div>
      <div class="card-body">
        <form id="add-employee-form" method="post" action="#">
          <div class="form-group row">
            <label class="col-lg-2 col-form-label">First Name <span class="text-danger">*</span></label>
            <div class="col-lg-4">
                <input type="text" class="form-control" id="first_name" name="first_name" maxlength="300" autocomplete="off">
            </div>
            <label class="col-lg-2 col-form-label">Badge ID <span class="text-danger">*</span></label>
            <div class="col-lg-4">
                <input type="text" class="form-control" id="badge_id" name="badge_id" maxlength="500" autocomplete="off">
            </div>
          </div>
          <div class="form-group row">
            <label class="col-lg-2 col-form-label">Middle Name</label>
            <div class="col-lg-4">
              <input type="text" class="form-control" id="middle_name" name="middle_name" maxlength="300" autocomplete="off">
            </div>
            <label class="col-lg-2 col-form-label">Department <span class="text-danger">*</span></label>
            <div class="col-lg-4">
              <select class="form-control select2" name="department_id" id="department_id">
                <option value="">--</option>
                <?php echo $departmentModel->generateDepartmentOptions(); ?>
              </select>
            </div>
          </div>
          <div class="form-group row">
            <label class="col-lg-2 col-form-label">Last Name <span class="text-danger">*</span></label>
            <div class="col-lg-4">
              <input type="text" class="form-control" id="last_name" name="last_name" maxlength="300" autocomplete="off">
            </div>
            <label class="col-lg-2 col-form-label">Job Position <span class="text-danger">*</span></label>
            <div class="col-lg-4">
              <select class="form-control select2" name="job_position_id" id="job_position_id">
                <option value="">--</option>
                <?php echo $jobPositionModel->generateJobPositionOptions(); ?>
              </select>
            </div>
          </div>
          <div class="form-group row">
            <label class="col-lg-2 col-form-label">Suffix</label>
            <div class="col-lg-4">
              <input type="text" class="form-control" id="suffix" name="suffix" maxlength="10" autocomplete="off">
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>