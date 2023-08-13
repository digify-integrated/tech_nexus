<div class="row">
  <div class="col-lg-12">
    <div class="card">
      <div class="card-header">
        <div class="row align-items-center">
          <div class="col-md-6">
            <h5>System Action</h5>
          </div>
          <?php
            if ($systemActionCreateAccess['total'] > 0) {
              echo '<div class="col-md-6 text-sm-end mt-3 mt-sm-0">
                      <button type="submit" form="system-action-form" class="btn btn-success form-edit" id="submit-data">Save</button>
                      <button type="button" id="discard-create" class="btn btn-outline-danger form-edit">Discard</button>
                    </div>';
              }
          ?>
        </div>
      </div>
      <div class="card-body">
        <form id="system-action-form" method="post" action="#">
          <div class="form-group row">
            <label class="col-lg-2 col-form-label">System Action Name <span class="text-danger">*</span></label>
            <div class="col-lg-10">
              <input type="text" class="form-control" id="system_action_name" name="system_action_name" maxlength="100" autocomplete="off">
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>