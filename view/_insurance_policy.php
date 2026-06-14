<div class="row">
  <div class="col-lg-12">
    <div class="card table-card">
      <div class="card-header">
        <div class="row align-items-center">
          <div class="col-sm-6">
            <h5>Insurance Policy List</h5>
          </div>
          <div class="col-sm-6 text-sm-end mt-3 mt-sm-0">
            <?php
              if($insurancePolicyCreateAccess['total'] > 0 || $insurancePolicyDeleteAccess['total'] > 0){
                $action = '';
                              
                if($insurancePolicyDeleteAccess['total'] > 0){
                  $action .= '<div class="btn-group m-r-10">
                                <button type="button" class="btn btn-outline-secondary dropdown-toggle d-none action-dropdown" data-bs-toggle="dropdown" aria-expanded="false">Action</button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                  <li><button class="dropdown-item" type="button" id="delete-insurance-request">Delete Insurance Policy</button></li>
                                </ul>
                              </div>';
                }

                if($insurancePolicyCreateAccess['total'] > 0){
                  $action .= '<a href="insurance-request.php?new" class="btn btn-success">Create</a>';
                }
                              
                echo $action;
              }
            ?>
          </div>
        </div>
      </div>
      <div class="card-body">
        <div class="table-responsive dt-responsive">
          <table id="insurance-request-table" class="table table-hover nowrap w-100">
            <thead>
              <tr>
                <th class="all">
                  <div class="form-check">
                    <input class="form-check-input" id="datatable-checkbox" type="checkbox">
                  </div>
                </th>
                <th>Policy Number</th>
                <th>Customer</th>
                <th>Insurance Type</th>
                <th>Insurance Provider</th>
                <th>Premium Amount</th>
                <th>Inception Date</th>
                <th>Expiry Date</th>
                <th>Status</th>
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