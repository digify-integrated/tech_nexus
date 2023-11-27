<div class="row">
  <div class="col-lg-12">
    <div class="card">
      <div class="card-header">
        <div class="row align-items-center">
          <div class="col-md-6">
            <h5>Attendance Setting</h5>
          </div>
          <?php
            if ($attendanceSettingCreateAccess['total'] > 0) {
               echo '<div class="col-md-6 text-sm-end mt-3 mt-sm-0">
                      <button type="submit" form="attendance-setting-form" class="btn btn-success form-edit" id="submit-data">Save</button>
                      <button type="button" id="discard-create" class="btn btn-outline-danger form-edit">Discard</button>
                    </div>';
            }
          ?>
        </div>
      </div>
      <div class="card-body">
        <form id="attendance-setting-form" method="post" action="#">
          <div class="form-group row">
            <label class="col-lg-2 col-form-label">Name <span class="text-danger">*</span></label>
            <div class="col-lg-4">
              <input type="text" class="form-control" id="attendance_setting_name" name="attendance_setting_name" maxlength="100" autocomplete="off">
            </div>
            <label class="col-lg-2 col-form-label">Value <span class="text-danger">*</span></label>
            <div class="col-lg-4">
                <input type="text" class="form-control" id="value" name="value" maxlength="1000" autocomplete="off">
            </div>
          </div>
          <div class="form-group row">
            <label class="col-lg-2 col-form-label">Description <span class="text-danger">*</span></label>
            <div class="col-lg-4">
                <input type="text" class="form-control" id="attendance_setting_description" name="attendance_setting_description" maxlength="200" autocomplete="off">
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>