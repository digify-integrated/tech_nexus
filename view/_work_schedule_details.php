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
      if($workScheduleTypeID == 1){
        $working_hours_add = '<button type="button" class="btn btn-warning" id="add-fixed-working-hours">Add Working Hours</button>';
        $working_hours_table = '<table id="fixed-working-hours-table" class="table table-striped table-hover table-bordered nowrap w-100 dataTable">
                                  <thead>
                                    <tr>
                                      <th>Day of Week</th>
                                      <th>Day Period</th>
                                      <th>Work From</th>
                                      <th>Work To</th>
                                      <th>Notes</th>
                                      <th>Actions</th>
                                    </tr>
                                  </thead>
                                  <tbody></tbody>
                                </table>';
      }
      else{
        $working_hours_add = '<button type="button" class="btn btn-warning" id="add-flexible-working-hours">Add Working Hours</button>';
        $working_hours_table = '<table id="flexible-working-hours-table" class="table table-striped table-hover table-bordered nowrap w-100 dataTable">
                                  <thead>
                                    <tr>
                                      <th>Work Date</th>
                                      <th>Day Period</th>
                                      <th>Work From</th>
                                      <th>Work To</th>
                                      <th>Notes</th>
                                      <th>Actions</th>
                                    </tr>
                                  </thead>
                                  <tbody></tbody>
                                </table>';
      }
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
                  '. $working_hours_table .'
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

  if($addWorkingHours['total'] > 0){
    if($workScheduleTypeID == 1){
      echo '<div id="fixed-working-hours-modal" class="modal fade modal-animate anim-fade-in-scale" tabindex="-1" role="dialog" aria-labelledby="fixed-working-hours-modal" aria-hidden="true">
              <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-r" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="fixed-working-hours-modal-title">Work Hours</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div class="modal-body" id="modal-body">
                    <form id="fixed-working-hours-form" method="post" action="#">
                      <div class="form-group row">
                        <div class="col-lg-6">
                          <label class="form-label">Day of Week <span class="text-danger">*</span></label>
                          <input type="hidden" id="work_hours_id" name="work_hours_id">
                          <select class="form-control modal-select2" name="day_of_week" id="day_of_week">
                            <option value="">--</option>
                            <option value="Monday">Monday</option>
                            <option value="Tuesday">Tuesday</option>
                            <option value="Wednesday">Wednesday</option>
                            <option value="Thursday">Thursday</option>
                            <option value="Friday">Friday</option>
                            <option value="Saturday">Saturday</option>
                            <option value="Sunday">Sunday</option>
                          </select>
                        </div>
                        <div class="col-lg-6">
                          <label class="form-label">Day Period <span class="text-danger">*</span></label>
                          <select class="form-control modal-select2" name="day_period" id="day_period">
                            <option value="">--</option>
                            <option value="Morning">Morning</option>
                            <option value="Afternoon">Afternoon</option>
                            <option value="Evening">Evening</option>
                          </select>
                        </div>
                      </div>
                      <div class="form-group row">
                        <div class="col-lg-6">
                          <label class="form-label">Work From <span class="text-danger">*</span></label>
                          <input class="form-control" id="work_from" name="work_from" type="time">
                        </div>
                        <div class="col-lg-6">
                          <label class="form-label">Work To <span class="text-danger">*</span></label>
                          <input class="form-control" id="work_to" name="work_to" type="time">
                        </div>
                      </div>
                      <div class="form-group row">
                        <div class="col-lg-12">
                          <label class="form-label">Notes</label>
                          <textarea class="form-control" id="notes" name="notes" maxlength="1000" rows="5"></textarea>
                        </div>
                      </div>
                    </form>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" id="submit-fixed-working-hours-form" form="fixed-working-hours-form">Submit</button>
                  </div>
                </div>
              </div>
            </div>';
    }
    else{
      echo '<div id="flexible-working-hours-modal" class="modal fade modal-animate anim-fade-in-scale" tabindex="-1" role="dialog" aria-labelledby="flexible-working-hours-modal" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-r" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="flexible-working-hours-modal-title">Work Hours</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="modal-body">
                  <form id="flexible-working-hours-form" method="post" action="#">
                    <div class="form-group row">
                      <div class="col-lg-6">
                        <label class="form-label">Work Date <span class="text-danger">*</span></label>
                        <input type="hidden" id="work_hours_id" name="work_hours_id">
                        <div class="input-group date">
                          <input type="text" class="form-control regular-datepicker" id="work_date" name="work_date">
                          <span class="input-group-text">
                            <i class="feather icon-calendar"></i>
                          </span>
                        </div>
                      </div>
                      <div class="col-lg-6">
                        <label class="form-label">Day Period <span class="text-danger">*</span></label>
                        <select class="form-control modal-select2" name="day_period" id="day_period">
                          <option value="">--</option>
                          <option value="Morning">Morning</option>
                          <option value="Afternoon">Afternoon</option>
                          <option value="Evening">Evening</option>
                        </select>
                      </div>
                    </div>
                    <div class="form-group row">
                      <div class="col-lg-6">
                        <label class="form-label">Work From <span class="text-danger">*</span></label>
                        <input class="form-control" id="work_from" name="work_from" type="time">
                      </div>
                      <div class="col-lg-6">
                        <label class="form-label">Work To <span class="text-danger">*</span></label>
                        <input class="form-control" id="work_to" name="work_to" type="time">
                      </div>
                    </div>
                    <div class="form-group row">
                      <div class="col-lg-12">
                        <label class="form-label">Notes</label>
                        <textarea class="form-control" id="notes" name="notes" maxlength="1000" rows="5"></textarea>
                      </div>
                    </div>
                  </form>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                  <button type="submit" class="btn btn-primary" id="submit-flexible-working-hours-form" form="flexible-working-hours-form">Submit</button>
                </div>
              </div>
            </div>
          </div>';
    }
  }
      
?>
</div>