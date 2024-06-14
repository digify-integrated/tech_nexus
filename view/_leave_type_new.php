<div class="row">
  <div class="col-lg-12">
    <div class="card">
      <div class="card-header">
        <div class="row align-items-center">
          <div class="col-md-6">
            <h5>Leave Type</h5>
          </div>
          <?php
            if ($leaveTypeCreateAccess['total'] > 0) {
               echo '<div class="col-md-6 text-sm-end mt-3 mt-sm-0">
                      <button type="submit" form="leave-type-form" class="btn btn-success form-edit" id="submit-data">Save</button>
                      <button type="button" id="discard-create" class="btn btn-outline-danger form-edit">Discard</button>
                    </div>';
            }
          ?>
        </div>
      </div>
      <div class="card-body">
        <form id="leave-type-form" method="post" action="#">
          <div class="form-group row">
            <label class="col-lg-2 col-form-label">Name <span class="text-danger">*</span></label>
            <div class="col-lg-4">
              <input type="text" class="form-control" id="leave_type_name" name="leave_type_name" maxlength="100" autocomplete="off">
            </div>
            <label class="col-lg-2 col-form-label">Is Paid?</label>
            <div class="col-lg-4">
                <select class="form-control select2" name="is_paid" id="is_paid">
                    <option value="">--</option>
                    <option value="Yes">Yes</option>
                    <option value="No">No</option>
                </select>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>