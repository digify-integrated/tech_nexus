<div class="row">
  <div class="col-lg-12">
    <div class="card table-card">
      <div class="card-header">
        <div class="row align-items-center">
          <div class="col-sm-6">
            <h5>Chart of Account List</h5>
          </div>
          <div class="col-sm-6 text-sm-end mt-3 mt-sm-0">
            <?php
              if($chartOfAccountCreateAccess['total'] > 0 || $chartOfAccountDeleteAccess['total'] > 0){
                $action = '';
                              
                if($chartOfAccountDeleteAccess['total'] > 0){
                  $action .= '<div class="btn-group m-r-10">
                                  <button type="button" class="btn btn-outline-secondary dropdown-toggle d-none action-dropdown" data-bs-toggle="dropdown" aria-expanded="false">Action</button>
                                  <ul class="dropdown-menu dropdown-menu-end">
                                    <li><button class="dropdown-item" type="button" id="delete-chart-of-account">Delete Chart of Account</button></li>
                                  </ul>
                                </div>';
                }

                if($chartOfAccountCreateAccess['total'] > 0){
                  $action .= '<a href="chart-of-account.php?new" class="btn btn-success">Create</a>';
                }
                              
                echo $action;
              }
            ?>
           </div>
        </div>
      </div>
      <div class="card-body">
        <div class="table-responsive dt-responsive">
          <table id="chart-of-account-table" class="table table-hover nowrap w-100">
            <thead>
              <tr>
                <th class="all">
                  <div class="form-check">
                    <input class="form-check-input" id="datatable-checkbox" type="checkbox">
                  </div>
                </th>
                <th>Code</th>
                <th>Name</th>
                <th>Account Type</th>
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