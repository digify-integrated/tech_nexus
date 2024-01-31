<div class="row">
  <div class="col-lg-12">
    <div class="card">
      <div class="card-header">
        <div class="row align-items-center">
          <div class="col-md-6">
            <h5>Attendance Record</h5>
          </div>
          <div class="col-md-6 text-sm-end mt-3 mt-sm-0">
            <?php                            
              $dropdown = '<div class="btn-group m-r-5">
                              <button type="button" class="btn btn-outline-secondary dropdown-toggle form-details" data-bs-toggle="dropdown" aria-expanded="false">
                                Action
                              </button>
                              <ul class="dropdown-menu dropdown-menu-end">';
                                         
              if ($attendanceRecordDeleteAccess['total'] > 0) {
                $dropdown .= '<li><button class="dropdown-item" type="button" id="delete-attendance-record-details">Delete Attendance Record</button></li>';
              }
                      
              $dropdown .= '</ul>
                          </div>';
                  
              echo $dropdown;

              if ($attendanceRecordWriteAccess['total'] > 0) {
                echo '<button type="submit" class="btn btn-info form-details" id="edit-form">Edit</button>
                      <button type="submit" form="attendance-record-form" class="btn btn-success form-edit d-none" id="submit-data">Save</button>
                      <button type="button" id="discard-update" class="btn btn-outline-danger form-edit d-none">Discard</button>';
              }

              if ($attendanceRecordCreateAccess['total'] > 0) {
                echo '<a class="btn btn-success m-r-5 form-details" href="attendance-record.php?new">Create</a>';
              }
            ?>
          </div>
        </div>
      </div>
      <div class="card-body">
        <form id="attendance-record-form" method="post" action="#">
          <?php
            if($attendanceRecordWriteAccess['total'] > 0){
              echo '<div class="form-group row">
                        <label class="col-lg-2 col-form-label">Employee <span class="text-danger d-none form-edit">*</span></label>
                        <div class="col-lg-10">
                            <label class="col-form-label form-details fw-normal" id="employee_id_label"></label>
                            <div class="d-none form-edit">
                                <select class="form-control select2" name="employee_id" id="employee_id">
                                    <option value="">--</option>
                                    '. $employeeModel->generateEmployeeOptions('all', null) .'
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                      <label class="col-lg-2 col-form-label">Check In Date <span class="text-danger d-none form-edit">*</span></label>
                      <div class="col-lg-4">
                        <label class="col-form-label form-details fw-normal" id="check_in_date_label"></label>
                        <div class="input-group date d-none form-edit">
                        <input type="text" class="form-control regular-datepicker" id="check_in_date" name="check_in_date">
                            <span class="input-group-text">
                                <i class="feather icon-calendar"></i>
                            </span>
                        </div>
                      </div>
                      <label class="col-lg-2 col-form-label">Check In Time <span class="text-danger d-none form-edit">*</span></label>
                      <div class="col-lg-4">
                        <label class="col-form-label form-details fw-normal" id="check_in_time_label"></label>
                        <input class="form-control d-none form-edit id="check_in_time" name="check_in_time" type="time">
                      </div>
                    </div>
                    <div class="form-group row">
                      <label class="col-lg-2 col-form-label">Check Out Date</label>
                      <div class="col-lg-4">
                        <label class="col-form-label form-details fw-normal" id="check_out_date_label"></label>
                        <div class="input-group date d-none form-edit">
                        <input type="text" class="form-control regular-datepicker" id="check_out_date" name="check_out_date">
                            <span class="input-group-text">
                                <i class="feather icon-calendar"></i>
                            </span>
                        </div>
                      </div>
                      <label class="col-lg-2 col-form-label">Check Out Time</label>
                      <div class="col-lg-4">
                        <label class="col-form-label form-details fw-normal" id="check_out_time_label"></label>
                        <input class="form-control d-none form-edit id="check_out_time" name="check_out_time" type="time">
                      </div>
                    </div>';
            }
            else{
              echo '<div class="form-group row">
                        <label class="col-lg-2 col-form-label">Employee</label>
                        <div class="col-lg-10">
                            <label class="col-form-label form-details fw-normal" id="employee_id_label"></label>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-2 col-form-label">Check In Date</label>
                        <div class="col-lg-4">
                            <label class="col-form-label form-details fw-normal" id="check_in_date_label"></label>
                        </div>
                        <label class="col-lg-2 col-form-label">Check In Time</label>
                        <div class="col-lg-4">
                            <label class="col-form-label form-details fw-normal" id="check_in_time_label"></label>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-2 col-form-label">Check Out Date</label>
                        <div class="col-lg-4">
                            <label class="col-form-label form-details fw-normal" id="check_out_date_label"></label>
                        </div>
                        <label class="col-lg-2 col-form-label">Check Out Time</label>
                        <div class="col-lg-4">
                            <label class="col-form-label form-details fw-normal" id="check_out_time_label"></label>
                        </div>
                    </div>';
            }
          ?>
        </form>
      </div>
    </div>
  </div>
<?php
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
              '. $userModel->generateLogNotes('attendance', $attendanceID) .'
            </div>
          </div>
        </div>
      </div>';
?>
</div>