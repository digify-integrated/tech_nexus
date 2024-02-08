<div class="row">
  <div class="col-lg-12">
    <div class="card">
      <div class="card-header">
        <div class="row align-items-center">
          <div class="col-md-6">
            <h5>Document Authorizer</h5>
          </div>
          <?php
            if ($documentAuthorizerCreateAccess['total'] > 0) {
               echo '<div class="col-md-6 text-sm-end mt-3 mt-sm-0">
                      <button type="submit" form="document-authorizer-form" class="btn btn-success form-edit" id="submit-data">Save</button>
                      <button type="button" id="discard-create" class="btn btn-outline-danger form-edit">Discard</button>
                    </div>';
            }
          ?>
        </div>
      </div>
      <div class="card-body">
        <form id="document-authorizer-form" method="post" action="#">
          <div class="form-group row">
            <label class="col-lg-2 col-form-label">Authorizer <span class="text-danger">*</span></label>
            <div class="col-lg-4">
                <select class="form-control select2" name="authorizer_id" id="authorizer_id">
                    <option value="">--</option>
                    <?php echo $employeeModel->generateEmployeeOptions('active employee', null); ?>
                </select>
            </div>
            <label class="col-lg-2 col-form-label">Department <span class="text-danger">*</span></label>
            <div class="col-lg-4">
                <select class="form-control select2" name="department_id" id="department_id">
                    <option value="">--</option>
                    <?php echo $departmentModel->generateDepartmentOptions(); ?>
                </select>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>