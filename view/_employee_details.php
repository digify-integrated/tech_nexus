<div class="col-sm-12">
    <div class="card">
        <div class="card-body py-0">
            <ul class="nav nav-tabs profile-tabs" id="myTab" role="tablist">
                <li class="nav-item">
                  <a class="nav-link active" id="profile-tab-1" data-bs-toggle="tab" href="#profile-1" role="tab"
                    aria-selected="true">
                    <i class="ti ti-user me-2"></i>Profile
                  </a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" id="profile-tab-2" data-bs-toggle="tab" href="#profile-2" role="tab"
                    aria-selected="true">
                    <i class="ti ti-file-text me-2"></i>Personal Details
                  </a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" id="profile-tab-3" data-bs-toggle="tab" href="#profile-3" role="tab"
                    aria-selected="true">
                    <i class="ti ti-id me-2"></i>My Account
                  </a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" id="profile-tab-4" data-bs-toggle="tab" href="#profile-4" role="tab"
                    aria-selected="true">
                    <i class="ti ti-lock me-2"></i>Change Password
                  </a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" id="profile-tab-5" data-bs-toggle="tab" href="#profile-5" role="tab"
                    aria-selected="true">
                    <i class="ti ti-users me-2"></i>Role
                  </a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" id="profile-tab-6" data-bs-toggle="tab" href="#profile-6" role="tab"
                    aria-selected="true">
                    <i class="ti ti-settings me-2"></i>Settings
                  </a>
                </li>
            </ul>
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
                '. $userModel->generateLogNotes('employee', $employeeID) .'
              </div>
            </div>
          </div>
        </div>';
?>
</div>