<div class="row">
  <div class="col-lg-12">
    <div class="card">
      <div class="card-header">
        <div class="row align-items-center">
          <div class="col-md-6">
            <h5>Transmittal</h5>
          </div>
          <?php
            if ($transmittalCreateAccess['total'] > 0) {
               echo '<div class="col-md-6 text-sm-end mt-3 mt-sm-0">
                      <button type="submit" form="transmittal-form" class="btn btn-success form-edit" id="submit-data">Save</button>
                      <button type="button" id="discard-create" class="btn btn-outline-danger form-edit">Discard</button>
                    </div>';
            }
          ?>
        </div>
      </div>
      <div class="card-body">
        <form id="transmittal-form" method="post" action="#">
          <div class="form-group row">
            <label class="col-lg-2 col-form-label">Department <span class="text-danger">*</span></label>
            <div class="col-lg-4">
                <select class="form-control select2" name="receiver_department" id="receiver_department">
                    <option value="">--</option>
                    <?php echo $departmentModel->generateDepartmentOptions(); ?>
                </select>
            </div>
            <label class="col-lg-2 col-form-label">Specific Person</label>
            <div class="col-lg-4">
                <select class="form-control select2" name="receiver_id" id="receiver_id">
                    <option value="">--</option>
                </select>
            </div>
          </div>
          <div class="form-group row">
            <label class="col-lg-2 col-form-label">Transmittal Image</label>
            <div class="col-lg-10">
              <input type="file" class="form-control" id="transmittal_file" name="transmittal_file">
            </div>
          </div>
          <div class="form-group row">
            <label class="col-lg-2 col-form-label">Description <span class="text-danger">*</span></label>
            <div class="col-lg-10">
              <textarea class="form-control" id="transmittal_description" name="transmittal_description" maxlength="500" rows="3"></textarea>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>