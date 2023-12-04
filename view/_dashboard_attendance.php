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
        <p class="mb-1"><b>Today (<?php echo date('D, d M Y'); ?>)</b></p>
        <p class="f-12 mb-4">Shift: Regular Shift [8:00am - 5:30pm]</p>
        <div class="row g-3 mb-3">
          <div class="col-6">
            <div class="d-flex align-items-center mb-2">
                <div class="flex-shrink-0">
                  <img src="<?php echo $userAccountProfileImage; ?>" alt="user-image" class="user-avtar wid-40 hei-40 rounded-circle" />
                </div>
                <div class="flex-grow-1 ms-3 me-2">
                  <p class="f-12 mb-0">Start Time</p>
                  <h6 class="mb-0 text-primary">12:59 am</h6>
                </div>
            </div>
          </div>
          <div class="col-6 border border-top-0 border-bottom-0 border-end-0">
            <div class="d-flex align-items-center mb-2">
                <div class="flex-shrink-0">
                  <img src="<?php echo $userAccountProfileImage; ?>" alt="user-image" class="user-avtar wid-40 hei-40 rounded-circle" />
                </div>
                <div class="flex-grow-1 ms-3 me-2">
                  <p class="f-12 mb-0">End Time</p>
                  <h6 class="mb-0 text-primary">12:59 am</h6>
                </div>
            </div>
          </div>
        </div>
        <button type="button" class="btn btn-warning w-100 text-center mb-0">Record Time</button>
      </div>
      <div class="card-footer text-center">
        <a href="attendance-record.php">View Attendance Records</a>
      </div>
    </div>
  </div>
</div>