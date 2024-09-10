<form id="travel-form" method="post" action="#">
  <div class="row">
    <div class="col-lg-12">
      <div class="card">
        <div class="card-header">
          <div class="row align-items-center">
            <div class="col-md-6">
              <h5>Travel Form</h5>
            </div>
            <?php
              if ($travelFormCreateAccess['total'] > 0) {
                echo '<div class="col-md-6 text-sm-end mt-3 mt-sm-0">
                        <button type="submit" form="travel-form" class="btn btn-success form-edit" id="submit-data">Save</button>
                        <button type="button" id="discard-create" class="btn btn-outline-danger form-edit">Discard</button>
                      </div>';
              }
            ?>
          </div>
        </div>
        <div class="card-body">
          <div class="form-group row">
            <label class="col-lg-2 col-form-label">Checked By</label>
            <div class="col-lg-4">
              <select class="form-control select2" name="checked_by" id="checked_by">
                <option value="">--</option>
                <?php echo $employeeModel->generateEmployeeOptions('all'); ?>
              </select>
            </div>
            <label class="col-lg-2 col-form-label">Recommended By <span class="text-danger">*</span></label>
            <div class="col-lg-4">
              <select class="form-control select2" name="recommended_by" id="recommended_by">
                <option value="">--</option>
                <?php echo $employeeModel->generateEmployeeOptions('all'); ?>
              </select>
            </div>
          </div>
          <div class="form-group row">
            <label class="col-lg-2 col-form-label">Approval By <span class="text-danger">*</span></label>
            <div class="col-lg-4">
              <select class="form-control select2" name="approval_by" id="approval_by">
                <option value="">--</option>
                <?php echo $employeeModel->generateEmployeeOptions('all'); ?>
              </select>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</form>