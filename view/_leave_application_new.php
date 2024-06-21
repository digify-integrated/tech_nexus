<div class="row">
  <div class="col-lg-12">
    <div class="card">
      <div class="card-header">
        <div class="row align-items-center">
          <div class="col-md-6">
            <h5>Leave Application</h5>
          </div>
          <?php
            if ($leaveApplicationCreateAccess['total'] > 0) {
               echo '<div class="col-md-6 text-sm-end mt-3 mt-sm-0">
                      <button type="submit" form="leave-application-form" class="btn btn-success form-edit" id="submit-data">Save</button>
                      <button type="button" id="discard-create" class="btn btn-outline-danger form-edit">Discard</button>
                    </div>';
            }
          ?>
        </div>
      </div>
      <div class="card-body">
        <form id="leave-application-form" method="post" action="#">
          <div class="form-group row">
            <label class="col-lg-2 col-form-label">Leave Type <span class="text-danger">*</span></label>
            <div class="col-lg-4">
                <select class="form-control select2" name="leave_type_id" id="leave_type_id">
                    <option value="">--</option>
                    <?php echo $leaveTypeModel->generateLeaveTypeOptions(); ?>
                </select>
            </div>
            <label class="col-lg-2 col-form-label">Leave Date <span class="text-danger">*</span></label>
            <div class="col-lg-4">
                <div class="input-group date">
                    <input type="text" class="form-control regular-datepicker" id="leave_date" name="leave_date" autocomplete="off">
                    <span class="input-group-text">
                        <i class="feather icon-calendar"></i>
                    </span>
                </div>
            </div>
          </div>
          <div class="form-group row">
            <label class="col-lg-2 col-form-label">Start Time <span class="text-danger">*</span></label>
            <div class="col-lg-4">
                <input class="form-control" id="leave_start_time" name="leave_start_time" type="time">
            </div>
            <label class="col-lg-2 col-form-label">End Time <span class="text-danger">*</span></label>
            <div class="col-lg-4">
                <input class="form-control" id="leave_end_time" name="leave_end_time" type="time">
            </div>
          </div>
          <div class="form-group row">
            <label class="col-lg-2 col-form-label">Leave Reason <span class="text-danger">*</span></label>
            <div class="col-lg-10">
              <textarea class="form-control" id="reason" name="reason" maxlength="500"></textarea>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
