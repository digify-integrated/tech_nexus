<div class="row">
  <div class="col-lg-12">
    <div class="card">
      <div class="card-header">
        <div class="row align-items-center">
          <div class="col-md-6">
            <h5>Approving Officer</h5>
          </div>
          <?php
            if ($approvingOfficerCreateAccess['total'] > 0) {
              echo '<div class="col-md-6 text-sm-end mt-3 mt-sm-0">
                      <button type="submit" form="approving-officer-form" class="btn btn-success form-edit" id="submit-data">Save</button>
                      <button type="button" id="discard-create" class="btn btn-outline-danger form-edit">Discard</button>
                    </div>';
            }
          ?>
        </div>
      </div>
      <div class="card-body">
        <form id="approving-officer-form" method="post" action="#">
            <div class="form-group row">
                <label class="col-lg-2 col-form-label">Approver <span class="text-danger">*</span></label>
                <div class="col-lg-10">
                    <select class="form-control select2" name="contact_id" id="contact_id">
                        <option value="">--</option>
                        <?php echo $employeeModel->generateEmployeeOptions('all', null); ?>
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-lg-2 col-form-label">Approving Officer Type <span class="text-danger">*</span></label>
                <div class="col-lg-10">
                    <select class="form-control select2" name="approving_officer_type" id="approving_officer_type">
                        <option value="">--</option>
                        <option value="Initial">Initial</option>
                        <option value="Final">Final</option>
                    </select>
                </div>
            </div>
        </form>
      </div>
    </div>
  </div>
</div>