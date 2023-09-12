<div class="row">
  <div class="col-lg-12">
    <div class="card">
      <div class="card-header">
        <div class="row align-items-center">
          <div class="col-md-6">
            <h5>Job Level</h5>
          </div>
          <div class="col-md-6 text-sm-end mt-3 mt-sm-0">
            <?php                            
                $dropdown = '<div class="btn-group m-r-5">
                                <button type="button" class="btn btn-outline-secondary dropdown-toggle form-details" data-bs-toggle="dropdown" aria-expanded="false">
                                    Action
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end">';

                if ($jobLevelDuplicateAccess['total'] > 0) {
                    $dropdown .= '<li><button class="dropdown-item" type="button" id="duplicate-job-level">Duplicate Job Level</button></li>';
                }
                            
                if ($jobLevelDeleteAccess['total'] > 0) {
                    $dropdown .= '<li><button class="dropdown-item" type="button" id="delete-job-level-details">Delete Job Level</button></li>';
                }
                        
                $dropdown .= '</ul>
                            </div>';
                    
                echo $dropdown;

                if ($jobLevelWriteAccess['total'] > 0) {
                    echo '<button type="submit" class="btn btn-info form-details" id="edit-form">Edit</button>
                        <button type="submit" form="job-level-form" class="btn btn-success form-edit d-none" id="submit-data">Save</button>
                        <button type="button" id="discard-update" class="btn btn-outline-danger form-edit d-none">Discard</button>';
                }

                if ($jobLevelCreateAccess['total'] > 0) {
                    echo '<a class="btn btn-success m-r-5 form-details" href="job-level.php?new">Create</a>';
                }
            ?>
          </div>
        </div>
      </div>
      <div class="card-body">
        <form id="job-level-form" method="post" action="#">
          <?php
            if($jobLevelWriteAccess['total'] > 0){
              echo '<div class="form-group row">
                      <label class="col-lg-2 col-form-label">Current Level <span class="text-danger d-none form-edit">*</span></label>
                      <div class="col-lg-4">
                        <label class="col-form-label form-details fw-normal" id="current_level_label"></label>
                        <input type="text" class="form-control d-none form-edit" id="current_level" name="current_level" maxlength="10" autocomplete="off">
                      </div>
                      <label class="col-lg-2 col-form-label">Functional Level <span class="text-danger d-none form-edit">*</span></label>
                      <div class="col-lg-4">
                        <label class="col-form-label form-details fw-normal" id="functional_level_label"></label>
                        <input type="text" class="form-control d-none form-edit" id="functional_level" name="functional_level" maxlength="100" autocomplete="off">
                      </div>
                    </div>
                    <div class="form-group row">
                      <label class="col-lg-2 col-form-label">Rank <span class="text-danger d-none form-edit">*</span></label>
                      <div class="col-lg-4">
                        <label class="col-form-label form-details fw-normal" id="rank_label"></label>
                        <input type="text" class="form-control d-none form-edit" id="rank" name="rank" maxlength="100" autocomplete="off">
                      </div>
                    </div>';
            }
            else{
              echo '<div class="form-group row">
                      <label class="col-lg-2 col-form-label">Current Level</label>
                      <div class="col-lg-4">
                        <label class="col-form-label form-details fw-normal" id="current_level_label"></label>
                      </div>
                      <label class="col-lg-2 col-form-label">Functional Level</label>
                      <div class="col-lg-4">
                        <label class="col-form-label form-details fw-normal" id="functional_level_label"></label>
                      </div>
                    </div>
                    <div class="form-group row">
                      <label class="col-lg-2 col-form-label">Rank</label>
                      <div class="col-lg-4">
                        <label class="col-form-label form-details fw-normal" id="rank_label"></label>
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
                '. $userModel->generateLogNotes('job_level', $jobLevelID) .'
              </div>
            </div>
          </div>
        </div>';
?>
</div>