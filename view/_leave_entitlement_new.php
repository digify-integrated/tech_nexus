<div class="row">
  <div class="col-lg-12">
    <div class="card">
      <div class="card-header">
        <div class="row align-items-center">
          <div class="col-md-6">
            <h5>Leave Entitlement</h5>
          </div>
          <?php
            if ($leaveEntitlementCreateAccess['total'] > 0) {
               echo '<div class="col-md-6 text-sm-end mt-3 mt-sm-0">
                      <button type="submit" form="leave-entitlement-form" class="btn btn-success form-edit" id="submit-data">Save</button>
                      <button type="button" id="discard-create" class="btn btn-outline-danger form-edit">Discard</button>
                    </div>';
            }
          ?>
        </div>
      </div>
      <div class="card-body">
        <form id="leave-entitlement-form" method="post" action="#">
          <div class="form-group row">
            <label class="col-lg-2 col-form-label">Employee <span class="text-danger">*</span></label>
            <div class="col-lg-10">
                <select class="form-control select2" name="employee_id" id="employee_id">
                    <option value="">--</option>
                    <?php echo $employeeModel->generateEmployeeOptions('active employee'); ?>
                </select>
            </div>
          </div>
          <div class="form-group row">
            <label class="col-lg-2 col-form-label">Leave Type <span class="text-danger">*</span></label>
            <div class="col-lg-4">
                <select class="form-control select2" name="leave_type_id" id="leave_type_id">
                    <option value="">--</option>
                    <?php echo $leaveTypeModel->generateLeaveTypeOptions(); ?>
                </select>
            </div>
            <label class="col-lg-2 col-form-label">Entitlement (in hours) <span class="text-danger">*</span></label>
            <div class="col-lg-4">
                <input type="number" class="form-control" id="entitlement_amount" name="entitlement_amount" min="1">
            </div>
          </div>
          <div class="form-group row">
            <label class="col-lg-2 col-form-label">Coverage Start Date <span class="text-danger">*</span></label>
            <div class="col-lg-4">
                <div class="input-group date">
                    <input type="text" class="form-control regular-datepicker" id="leave_period_start" name="leave_period_start" autocomplete="off">
                    <span class="input-group-text">
                        <i class="feather icon-calendar"></i>
                    </span>
                </div>
            </div>
            <label class="col-lg-2 col-form-label">Coverage End Date <span class="text-danger">*</span></label>
            <div class="col-lg-4">
                <div class="input-group date">
                    <input type="text" class="form-control regular-datepicker" id="leave_period_end" name="leave_period_end" autocomplete="off">
                    <span class="input-group-text">
                        <i class="feather icon-calendar"></i>
                    </span>
                </div>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
