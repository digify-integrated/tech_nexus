<div class="row">
  <div class="col-lg-12">
    <div class="card">
      <div class="card-header">
        <div class="row align-items-center">
          <div class="col-md-6">
            <h5>Attendance Record</h5>
          </div>
          <?php
            if ($attendanceRecordCreateAccess['total'] > 0) {
               echo '<div class="col-md-6 text-sm-end mt-3 mt-sm-0">
                      <button type="submit" form="attendance-record-form" class="btn btn-success form-edit" id="submit-data">Save</button>
                      <button type="button" id="discard-create" class="btn btn-outline-danger form-edit">Discard</button>
                    </div>';
            }
          ?>
        </div>
      </div>
      <div class="card-body">
        <form id="attendance-record-form" method="post" action="#">
          <div class="form-group row">
            <label class="col-lg-2 col-form-label">Employee <span class="text-danger">*</span></label>
            <div class="col-lg-10">
                <select class="form-control select2" name="employee_id" id="employee_id">
                    <option value="">--</option>
                    <?php echo $employeeModel->generateEmployeeOptions('all', null); ?>
                </select>
            </div>
          </div>
          <div class="form-group row">
            <label class="col-lg-2 col-form-label">Check In Date <span class="text-danger">*</span></label>
            <div class="col-lg-4">
                <div class="input-group date">
                    <input type="text" class="form-control future-date-restricted-datepicker" id="check_in_date" name="check_in_date" autocomplete="off">
                    <span class="input-group-text">
                        <i class="feather icon-calendar"></i>
                    </span>
                </div>
            </div>
            <label class="col-lg-2 col-form-label">Check In Time <span class="text-danger">*</span></label>
            <div class="col-lg-4">
                <input class="form-control" id="check_in_time" name="check_in_time" type="time">
            </div>
          </div>
          <div class="form-group row">
            <label class="col-lg-2 col-form-label">Check In Notes</label>
            <div class="col-lg-10">
              <textarea class="form-control" id="check_in_notes" name="check_in_notes" maxlength="1000" rows="3"></textarea>
            </div>
          </div>
          <div class="form-group row">
            <label class="col-lg-2 col-form-label">Check Out Date </label>
            <div class="col-lg-4">
                <div class="input-group date">
                    <input type="text" class="form-control future-date-restricted-datepicker" id="check_out_date" name="check_out_date" autocomplete="off">
                    <span class="input-group-text">
                        <i class="feather icon-calendar"></i>
                    </span>
                </div>
            </div>
            <label class="col-lg-2 col-form-label">Check Out Time</label>
            <div class="col-lg-4">
                <input class="form-control" id="check_out_time" name="check_out_time" type="time">
            </div>
          </div>
          <div class="form-group row">
            <label class="col-lg-2 col-form-label">Check Out Notes</label>
            <div class="col-lg-10">
              <textarea class="form-control" id="check_out_notes" name="check_out_notes" maxlength="1000" rows="3"></textarea>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>