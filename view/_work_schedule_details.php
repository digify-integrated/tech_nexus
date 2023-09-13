<div class="row">
  <div class="col-lg-12">
    <div class="card">
      <div class="card-header">
        <div class="row align-items-center">
          <div class="col-md-6">
            <h5>Work Schedule</h5>
          </div>
          <div class="col-md-6 text-sm-end mt-3 mt-sm-0">
            <?php                            
                $dropdown = '<div class="btn-group m-r-5">
                                <button type="button" class="btn btn-outline-secondary dropdown-toggle form-details" data-bs-toggle="dropdown" aria-expanded="false">
                                    Action
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end">';

                if ($workScheduleDuplicateAccess['total'] > 0) {
                    $dropdown .= '<li><button class="dropdown-item" type="button" id="duplicate-work-schedule">Duplicate Work Schedule</button></li>';
                }
                            
                if ($workScheduleDeleteAccess['total'] > 0) {
                    $dropdown .= '<li><button class="dropdown-item" type="button" id="delete-work-schedule-details">Delete Work Schedule</button></li>';
                }
                        
                $dropdown .= '</ul>
                            </div>';
                    
                echo $dropdown;

                if ($workScheduleWriteAccess['total'] > 0) {
                    echo '<button type="submit" class="btn btn-info form-details" id="edit-form">Edit</button>
                        <button type="submit" form="work-schedule-form" class="btn btn-success form-edit d-none" id="submit-data">Save</button>
                        <button type="button" id="discard-update" class="btn btn-outline-danger form-edit d-none">Discard</button>';
                }

                if ($workScheduleCreateAccess['total'] > 0) {
                    echo '<a class="btn btn-success m-r-5 form-details" href="work-schedule.php?new">Create</a>';
                }
            ?>
          </div>
        </div>
      </div>
      <div class="card-body">
        <form id="work-schedule-form" method="post" action="#">
          <?php
            if($workScheduleWriteAccess['total'] > 0){
              echo '<div class="form-group row">
                      <label class="col-lg-2 col-form-label">Name <span class="text-danger d-none form-edit">*</span></label>
                      <div class="col-lg-4">
                        <label class="col-form-label form-details fw-normal" id="work_schedule_name_label"></label>
                        <input type="text" class="form-control d-none form-edit" id="work_schedule_name" name="work_schedule_name" maxlength="100" autocomplete="off">
                      </div>
                      <label class="col-lg-2 col-form-label">Work Schedule Type <span class="text-danger d-none form-edit">*</span></label>
                      <div class="col-lg-4">
                          <label class="col-form-label form-details fw-normal" id="work_schedule_type_id_label"></label>
                          <div class="d-none form-edit">
                              <select class="form-control select2" name="work_schedule_type_id" id="work_schedule_type_id">
                                  <option value="">--</option>
                                  '. $workScheduleTypeModel->generateWorkScheduleTypeOptions() .'
                              </select>
                          </div>
                      </div>
                    </div>
                    <div class="form-group row">
                      <label class="col-lg-2 col-form-label">Description <span class="text-danger d-none form-edit">*</span></label>
                      <div class="col-lg-4">
                        <label class="col-form-label form-details fw-normal" id="work_schedule_description_label"></label>
                        <input type="text" class="form-control d-none form-edit" id="work_schedule_description" name="work_schedule_description" maxlength="500" autocomplete="off">
                      </div>
                    </div>';
            }
            else{
              echo '<div class="form-group row">
                      <label class="col-lg-2 col-form-label">Name</label>
                      <div class="col-lg-4">
                        <label class="col-form-label form-details fw-normal" id="work_schedule_name_label"></label>
                      </div>
                      <label class="col-lg-2 col-form-label">Work Schedule Type</label>
                      <div class="col-lg-4">
                        <label class="col-form-label form-details fw-normal" id="work_schedule_type_id_label"></label>
                      </div>
                    </div>
                    <div class="form-group row">
                      <label class="col-lg-2 col-form-label">Description</label>
                      <div class="col-lg-4">
                        <label class="col-form-label form-details fw-normal" id="work_schedule_description_label"></label>
                      </div>
                    </div>';
            }
          ?>
        </form>
      </div>
    </div>
  </div>
<?php
    if(!empty($workScheduleID)){
        if($addWorkingHours['total'] > 0){
            $working_hours_add = '<button type="button" class="btn btn-warning" id="add-working-hours">Add Working Hours</button>';
        }

        echo '<div class="col-lg-12">
                <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                    <div class="col-sm-6">
                        <h5>Working Hours</h5>
                    </div>
                    <div class="col-sm-6 text-sm-end mt-3 mt-sm-0">
                        '. $working_hours_add .'
                    </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="dt-responsive table-responsive">
                    <table id="working-hours-table" class="table table-striped table-hover table-bordered nowrap w-100 dataTable">
                        <thead>
                        <tr>
                            <th>Responsibility</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                    </div>
                </div>
                </div>
            </div>';
  }
  echo '<div class="col-lg-12">
          <div class="card">
            <div class="card-header">
              <div class="row align-items-center">
                <div class="col-sm-6">
                  <h5>Log Notes</h5>
                </div>
              </div>
            </div>
            <div class="log-notes-scroll" style="max-height: 450px; position: relative;">
              <div class="card-body p-b-0">
                '. $userModel->generateLogNotes('work_schedule', $workScheduleID) .'
              </div>
            </div>
          </div>
        </div>';
?>
</div>