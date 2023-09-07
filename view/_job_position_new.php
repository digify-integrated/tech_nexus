<div class="row">
  <div class="col-lg-12">
    <div class="card">
      <div class="card-header">
        <div class="row align-items-center">
          <div class="col-md-6">
            <h5>Job Position</h5>
          </div>
          <?php
            if ($jobPositionCreateAccess['total'] > 0) {
               echo '<div class="col-md-6 text-sm-end mt-3 mt-sm-0">
                      <button type="submit" form="job-position-form" class="btn btn-success form-edit" id="submit-data">Save</button>
                      <button type="button" id="discard-create" class="btn btn-outline-danger form-edit">Discard</button>
                    </div>';
            }
          ?>
        </div>
      </div>
      <div class="card-body">
        <form id="job-position-form" method="post" action="#">
          <div class="form-group row">
            <label class="col-lg-2 col-form-label">Name <span class="text-danger">*</span></label>
            <div class="col-lg-4">
              <input type="text" class="form-control" id="job_position_name" name="job_position_name" maxlength="100" autocomplete="off">
            </div>
            <label class="col-lg-2 col-form-label">Department</label>
            <div class="col-lg-4">
                <select class="form-control select2" name="department_id" id="department_id">
                    <option value="">--</option>
                    <?php echo $departmentModel->generateDepartmentOptions(); ?>
                </select>
            </div>
          </div>
          <div class="form-group row">
            <label class="col-lg-2 col-form-label">Expected New Employees</label>
            <div class="col-lg-4">
                <input type="number" class="form-control" id="expected_new_employees" name="expected_new_employees" min="0">
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>