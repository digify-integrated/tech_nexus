<div class="row">
  <div class="col-lg-12">
    <div class="card">
      <div class="card-header">
        <div class="row align-items-center">
          <div class="col-md-6">
            <h5>Job Level</h5>
          </div>
          <?php
            if ($jobLevelCreateAccess['total'] > 0) {
               echo '<div class="col-md-6 text-sm-end mt-3 mt-sm-0">
                      <button type="submit" form="job-level-form" class="btn btn-success form-edit" id="submit-data">Save</button>
                      <button type="button" id="discard-create" class="btn btn-outline-danger form-edit">Discard</button>
                    </div>';
            }
          ?>
        </div>
      </div>
      <div class="card-body">
        <form id="job-level-form" method="post" action="#">
          <div class="form-group row">
            <label class="col-lg-2 col-form-label">Current Level <span class="text-danger">*</span></label>
            <div class="col-lg-4">
              <input type="text" class="form-control" id="current_level" name="current_level" maxlength="10" autocomplete="off">
            </div>
            <label class="col-lg-2 col-form-label">Functional Level <span class="text-danger">*</span></label>
            <div class="col-lg-4">
              <input type="text" class="form-control" id="functional_level" name="functional_level" maxlength="100" autocomplete="off">
            </div>
          </div>
          <div class="form-group row">
            <label class="col-lg-2 col-form-label">Rank <span class="text-danger">*</span></label>
            <div class="col-lg-4">
              <input type="text" class="form-control" id="rank" name="rank" maxlength="100" autocomplete="off">
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>