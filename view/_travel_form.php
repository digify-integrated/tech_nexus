<div class="row">
  <div class="col-lg-12">
    <div class="ecom-wrapper">
      <div class="ecom-content w-100">
        <div class="card table-card">
          <div class="card-header">
            <div class="row align-items-center">
              <div class="col-sm-6">
                <h5>Travel Form</h5>
              </div>
              <div class="col-sm-6 text-sm-end mt-3 mt-sm-0">
                <?php
                  if($travelFormCreateAccess['total'] > 0 || $travelFormDeleteAccess['total'] > 0){
                    $action = '';
                                
                    if($travelFormDeleteAccess['total'] > 0){
                      $action .= '<div class="btn-group m-r-10">
                                    <button type="button" class="btn btn-outline-secondary dropdown-toggle d-none action-dropdown" data-bs-toggle="dropdown" aria-expanded="false">Action</button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                      <li><button class="dropdown-item" type="button" id="delete-travel-form">Delete Travel Form</button></li>
                                    </ul>
                                  </div>';
                    }

                    if($travelFormCreateAccess['total'] > 0){
                      $action .= '<a href="travel-form.php?new" class="btn btn-success">Create</a>';
                    }
                                
                    echo $action;
                  }
                ?>
              </div>
            </div>
          </div>
          <div class="card-body">
            <div class="row">
              <div class="col-xl-12">
                <div class="table-responsive dt-responsive">
                  <table id="travel-form-table" class="table table-hover nowrap w-100 text-uppercase">
                    <thead>
                      <tr>
                        <th>Employee</th>
                        <th>Leave Type</th>
                        <th>Entitlement</th>
                        <th>Period Covered</th>
                        <th>Actions</th>
                      </tr>
                    </thead>
                    <tbody></tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>