<div class="row">
  <div class="col-lg-12">
    <div class="card">
      <div class="card-header">
        <div class="row align-items-center">
          <div class="col-md-6">
            <h5>Lead</h5>
          </div>
          <?php
            if ($leadCreateAccess['total'] > 0) {
               echo '<div class="col-md-6 text-sm-end mt-3 mt-sm-0">
                      <button type="submit" form="lead-form" class="btn btn-success form-edit" id="submit-data">Save</button>
                      <button type="button" id="discard-create" class="btn btn-outline-danger form-edit">Discard</button>
                    </div>';
            }
          ?>
        </div>
      </div>
      <div class="card-body">
        <form id="lead-form" method="post" action="#">
          <div class="form-group row">
            <label class="col-lg-2 col-form-label">Lead Name <span class="text-danger">*</span></label>
            <div class="col-lg-4">
              <input type="text" class="form-control" id="lead_name" name="lead_name" maxlength="255">
            </div>

            <label class="col-lg-2 col-form-label">Email</label>
            <div class="col-lg-4">
              <input type="email" class="form-control" id="email" name="email">
            </div>
          </div>

          <div class="form-group row mt-2">
            <label class="col-lg-2 col-form-label">Phone</label>
            <div class="col-lg-4">
              <input type="text" class="form-control" id="phone" name="phone">
            </div>

            <label class="col-lg-2 col-form-label">Status</label>
            <div class="col-lg-4">
              <select class="form-control select2" id="lead_status_id" name="lead_status_id">
                <option value="">--</option>
                <?php echo $leadStatusModel->generateLeadStatusOptions(); ?>
              </select>
            </div>
          </div>

          <div class="form-group row mt-2">
            <label class="col-lg-2 col-form-label">Assigned To</label>
            <div class="col-lg-4">
              <select class="form-control select2" id="assigned_to" name="assigned_to">
                <option value="">--</option>
                <?php echo $employeeModel->generateEmployeeOptions('active employee'); ?>
              </select>
            </div>

            <label class="col-lg-2 col-form-label">Remarks</label>
            <div class="col-lg-4">
              <textarea class="form-control" id="remarks" name="remarks"></textarea>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>