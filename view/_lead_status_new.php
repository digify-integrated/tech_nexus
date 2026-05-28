<div class="row">
  <div class="col-lg-12">
    <div class="card">
      <div class="card-header">
        <div class="row align-items-center">
          <div class="col-md-6">
            <h5>Lead Status</h5>
          </div>
          <?php
            if ($leadStatusCreateAccess['total'] > 0) {
               echo '<div class="col-md-6 text-sm-end mt-3 mt-sm-0">
                      <button type="submit" form="lead-status-form" class="btn btn-success form-edit" id="submit-data">Save</button>
                      <button type="button" id="discard-create" class="btn btn-outline-danger form-edit">Discard</button>
                    </div>';
            }
          ?>
        </div>
      </div>
      <div class="card-body">
        <form id="lead-status-form" method="post" action="#">
          <div class="form-group row">
            <label class="col-lg-2 col-form-label">Name <span class="text-danger">*</span></label>
            <div class="col-lg-10">
              <input type="text" class="form-control" id="lead_status_name" name="lead_status_name" maxlength="100" autocomplete="off">
            </div>
          </div>
          <div class="form-group row">
            <label class="col-lg-2 col-form-label">Description <span class="text-danger">*</span></label>
            <div class="col-lg-10">
              <input type="text" class="form-control" id="description" name="description" maxlength="500" autocomplete="off">
            </div>
          </div>
          <div class="form-group row">
            <label class="col-lg-2 col-form-label">Type <span class="text-danger">*</span></label>
            <div class="col-lg-10">
              <select class="form-control select2" id="lead_status_type" name="lead_status_type">
                <option value="">--</option>
                <option value="Lead">Lead</option>
                <option value="Hiring">Hiring</option>
              </select>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

