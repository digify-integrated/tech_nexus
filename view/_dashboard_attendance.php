<div class="row">
  <div class="col-lg-5">
    <div class="card">
      <div class="card-header">
        <div class="row align-items-center">
          <div class="col-sm-12">
            <h5>Attendance</h5>
          </div>
        </div>
      </div>
      <div class="card-body">
        <div class="d-flex align-items-center mb-3">
            <div class="flex-shrink-0">
              <img src="<?php echo $userAccountProfileImage; ?>" alt="user-image" class="user-avtar wid-40 hei-40 rounded-circle" />
            </div>
            <div class="flex-grow-1 ms-3 me-2">
              <h6 class="mb-0 text-primary"><?php echo $fileAs; ?></h6>
              <p class="f-12 mb-0"><?php echo $employeeDepartmentName; ?></p>
            </div>
        </div>
        <p class="mb-1 text-primary"><b>Today (<?php echo date('D, d M Y'); ?>)</b></p>
        <p class="f-12 mb-4">Shift: <?php echo $employeeWorkScheduleName . ' [' . $currentShift . ']'; ?></p>
        <div class="row g-3 mb-3">
          <div class="col-6">
            <div class="d-flex align-items-center mb-2">
                <div class="flex-shrink-0">
                  <img src="<?php echo $userAccountProfileImage; ?>" alt="user-image" class="user-avtar wid-40 hei-40 rounded-circle" />
                </div>
                <div class="flex-grow-1 ms-3 me-2">
                  <p class="f-12 mb-0">Check-In</p>
                  <h6 class="mb-0 text-primary"><?php echo $userCheckIn; ?></h6>
                </div>
            </div>
          </div>
          <div class="col-6 border border-top-0 border-bottom-0 border-end-0">
            <div class="d-flex align-items-center mb-2">
                <div class="flex-shrink-0">
                  <img src="<?php echo $userAccountProfileImage; ?>" alt="user-image" class="user-avtar wid-40 hei-40 rounded-circle" />
                </div>
                <div class="flex-grow-1 ms-3 me-2">
                  <p class="f-12 mb-0">Check-Out</p>
                  <h6 class="mb-0 text-primary"><?php echo $userCheckOut; ?></h6>
                </div>
            </div>
          </div>
        </div>
        <?php
          if($recordAttendance['total'] > 0 && !empty($contact_id) && $userAttendanceRecordCount < $maxAttendanceRecord){
            echo '<button type="button" class="btn btn-warning w-100 text-center mb-0" id="record-attendance">Record Time</button>';
          }
        ?>
      </div>
      <div class="card-footer text-center">
        <a href="attendance-record.php">View Attendance Records</a>
      </div>
    </div>
  </div>
</div>

<?php
  if($recordAttendance['total'] > 0 && !empty($contact_id)){
    echo '<div id="record-attendance-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="record-attendance-title" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="record-attendance-title">Record Attendance</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                      <form id="record-attendance-form" method="post" action="#">
                        <div class="row d-flex justify-content-center">
                          <div class="col-lg-6 d-flex justify-content-center">
                            <input type="hidden" id="attendance_id" name="attendance_id" value="'. $currentAttendanceID .'">
                            <input type="hidden" id="location" name="location">
                            <input type="hidden" id="ip_address" name="ip_address">
                            <div id="video-container" class="d-none" style="width: 225px; height: 225px; position: relative; overflow: hidden; border-radius: 50%;">
                              <video id="attendance-video" style="position: absolute; top: 50%; left: 50%; min-width: 100%; min-height: 100%; width: auto; height: auto; transform: translate(-50%, -50%);" autoplay></video>
                              <canvas id="attendance-image" class="d-none" style="position: absolute; top: 50%; left: 50%; min-width: 100%; min-height: 100%; width: auto; height: auto; transform: translate(-50%, -50%);"></canvas>
                            </div>
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
                        <button type="button" class="btn btn-primary d-none" id="capture-attendance">Take Photo</button>
                        <button type="submit" class="btn btn-primary d-none" id="submit-attendance" form="record-attendance-form">Submit</button>
                    </div>
                </div>
            </div>
        </div>';
  }
?>
