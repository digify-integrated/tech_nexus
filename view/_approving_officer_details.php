<div class="row">
  <div class="col-lg-12">
    <div class="card">
      <div class="card-header">
        <div class="row align-items-center">
          <div class="col-md-6">
            <h5>Approving Officer</h5>
          </div>
          <div class="col-md-6 text-sm-end mt-3 mt-sm-0">
            <?php
             $dropdown = '<div class="btn-group m-r-5">
                            <button type="button" class="btn btn-outline-secondary dropdown-toggle form-details" data-bs-toggle="dropdown" aria-expanded="false">Action</button>
                            <ul class="dropdown-menu dropdown-menu-end">';
                      
            if ($approvingOfficerDeleteAccess['total'] > 0) {
              $dropdown .= '<li><button class="dropdown-item" type="button" id="delete-approving-officer-details">Delete Approving Officer</button></li>';
            }
                    
            $dropdown .= '</ul>
                    </div>';
                
            echo $dropdown;

            if ($approvingOfficerCreateAccess['total'] > 0) {
              echo '<a class="btn btn-success m-r-5 form-details" href="approving-officer.php?new">Create</a>';
            }
            ?>
          </div>
        </div>
      </div>
      <div class="card-body">
        <form id="approving-officer-form" method="post" action="#">
          <div class="form-group row">
            <label class="col-lg-2 col-form-label">Approver</label>
            <div class="col-lg-10">
              <label class="col-form-label form-details fw-normal" id="contact_id_label"></label>
            </div>
          </div>
          <div class="form-group row">
            <label class="col-lg-2 col-form-label">Approving Officer Type</label>
            <div class="col-lg-10">
              <label class="col-form-label form-details fw-normal" id="approving_officer_type_label"></label>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>