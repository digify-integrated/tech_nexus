<div class="row">
  <div class="col-lg-12">
    <div class="card">
      <div class="card-header">
        <div class="row align-items-center">
          <div class="col-md-6">
            <h5>Work Schedule</h5>
          </div>
          <?php
            if ($workScheduleCreateAccess['total'] > 0) {
               echo '<div class="col-md-6 text-sm-end mt-3 mt-sm-0">
                      <button type="submit" form="work-schedule-form" class="btn btn-success form-edit" id="submit-data">Save</button>
                      <button type="button" id="discard-create" class="btn btn-outline-danger form-edit">Discard</button>
                    </div>';
            }
          ?>
        </div>
      </div>
      <div class="card-body">
        <form id="work-schedule-form" method="post" action="#">
          <div class="form-group row">
            <label class="col-lg-2 col-form-label">Name <span class="text-danger">*</span></label>
            <div class="col-lg-4">
              <input type="text" class="form-control" id="work_schedule_name" name="work_schedule_name" maxlength="100" autocomplete="off">
            </div>
            <label class="col-lg-2 col-form-label">Work Schedule Type <span class="text-danger">*</span></label>
            <div class="col-lg-4">
                <select class="form-control select2" name="work_schedule_type_id" id="work_schedule_type_id">
                    <option value="">--</option>
                    <?php echo $workScheduleTypeModel->generateWorkScheduleTypeOptions(); ?>
                </select>
            </div>
          </div>
          <div class="form-group row">
            <label class="col-lg-2 col-form-label">Description <span class="text-danger">*</span></label>
            <div class="col-lg-4">
              <input type="text" class="form-control" id="work_schedule_description" name="work_schedule_description" maxlength="500" autocomplete="off">
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>