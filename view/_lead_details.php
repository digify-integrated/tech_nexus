<div class="row">
  <div class="col-lg-12">
    <div class="card">
      <div class="card-header">
        <div class="row align-items-center">
          <div class="col-md-6">
            <h5>Lead</h5>
          </div>
          <div class="col-md-6 text-sm-end mt-3 mt-sm-0">
            <?php                            
              $dropdown = '<div class="btn-group m-r-5">
                              <button type="button" class="btn btn-outline-secondary dropdown-toggle form-details" data-bs-toggle="dropdown">
                                Action
                              </button>
                              <ul class="dropdown-menu dropdown-menu-end">';

              if ($leadDuplicateAccess['total'] > 0) {
                $dropdown .= '<li><button class="dropdown-item" type="button" id="duplicate-lead">Duplicate Lead</button></li>';
              }

              if ($leadDeleteAccess['total'] > 0) {
                $dropdown .= '<li><button class="dropdown-item" type="button" id="delete-lead-details">Delete Lead</button></li>';
              }

              $dropdown .= '</ul></div>';

              echo $dropdown;

              if ($leadWriteAccess['total'] > 0) {
                echo '<button type="button" class="btn btn-info form-details" id="edit-form">Edit</button>
                      <button type="submit" form="lead-form" class="btn btn-success form-edit d-none" id="submit-data">Save</button>
                      <button type="button" id="discard-update" class="btn btn-outline-danger form-edit d-none">Discard</button>';
              }

              if ($leadCreateAccess['total'] > 0) {
                echo '<a class="btn btn-success form-details" href="lead-monitoring.php?new">Create</a>';
              }
            ?>
          </div>
        </div>
      </div>

      <div class="card-body">
        <form id="lead-form">
          <div class="form-group row">
            <label class="col-lg-2 col-form-label">Lead Name</label>
            <div class="col-lg-4">
              <label id="lead_name_label" class="form-details fw-normal"></label>
              <input type="text" id="lead_name" name="lead_name" class="form-control d-none form-edit">
            </div>

            <label class="col-lg-2 col-form-label">Email</label>
            <div class="col-lg-4">
              <label id="email_label" class="form-details fw-normal"></label>
              <input type="text" id="email" name="email" class="form-control d-none form-edit">
            </div>
          </div>

          <div class="form-group row mt-2">
            <label class="col-lg-2 col-form-label">Phone</label>
            <div class="col-lg-4">
              <label id="phone_label" class="form-details fw-normal"></label>
              <input type="text" id="phone" name="phone" class="form-control d-none form-edit">
            </div>

            <label class="col-lg-2 col-form-label">Status</label>
            <div class="col-lg-4">
              <label id="lead_status_label" class="form-details fw-normal"></label>
              <div class="d-none form-edit">
                <select class="form-control select2" id="lead_status_id" name="lead_status_id">
                  <option value="">--</option>
                  <?php echo $leadStatusModel->generateLeadStatusOptions(); ?>
                </select>
              </div>
            </div>
          </div>

          <div class="form-group row mt-2">
            <label class="col-lg-2 col-form-label">Assigned To</label>
            <div class="col-lg-4">
              <label id="assigned_to_label" class="form-details fw-normal"></label>
              <div class="d-none form-edit">
                <select class="form-control select2" id="assigned_to" name="assigned_to">
                  <option value="">--</option>
                  <?php echo $employeeModel->generateEmployeeOptions('active employee'); ?>
                </select>
              </div>
            </div>

            <label class="col-lg-2 col-form-label">Remarks</label>
            <div class="col-lg-4">
              <label id="remarks_label" class="form-details fw-normal"></label>
              <textarea id="remarks" name="remarks" class="form-control d-none form-edit"></textarea>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>

<?php
echo '<div class="col-lg-12">
        <div class="card">
          <div class="card-header">
            <h5>Log Notes</h5>
          </div>
          <div class="log-notes-scroll" style="max-height: 450px;">
            <div class="card-body p-b-0">
              '. $userModel->generateLogNotes('lead', $leadID) .'
            </div>
          </div>
        </div>
      </div>';
?>
</div>