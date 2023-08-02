<div class="row">
          <div class="col-lg-12">
            <div class="card">
              <div class="card-header">
                <div class="row align-items-center">
                  <div class="col-sm-6">
                    <h5>User Account List</h5>
                  </div>
                  <div class="col-sm-6 text-sm-end mt-3 mt-sm-0">
                  <?php
                    if($userAccountCreateAccess > 0 || $userAccountDeleteAccess > 0){
                      $action = '';

                      if($userAccountDeleteAccess > 0){
                        $action .= '<div class="btn-group m-r-10">
                                          <button type="button" class="btn btn-outline-secondary dropdown-toggle d-none action-dropdown" data-bs-toggle="dropdown" aria-expanded="false">Action</button>
                                          <ul class="dropdown-menu dropdown-menu-end">
                                            <li><button class="dropdown-item" type="button" id="delete-user-account">Delete User Account</button></li>
                                          </ul>
                                          </div>';
                      }

                      if($userAccountCreateAccess > 0){
                        $action .= '<a href="user-account.php?new" class="btn btn-success">Create</a>';
                      }
                          
                      echo $action;
                    }
                  ?>
                  <button class="btn btn-primary" type="button" data-bs-toggle="offcanvas" data-bs-target="#filter-canvas" aria-controls="filter-canvas">Filters</button>
                  </div>
                </div>
              </div>
              <div class="card-body">
                <div class="table-responsive dt-responsive">
                  <table id="user-account-table" class="table table-striped table-hover table-bordered nowrap w-100">
                    <thead>
                      <tr>
                        <th class="all">
                          <div class="form-check">
                            <input class="form-check-input" id="datatable-checkbox" type="checkbox">
                          </div>
                        </th>
                        <th>User Account</th>
                        <th class="all">Status</th>
                        <th class="all">Locked</th>
                        <th class="all">Last Connection</th>
                        <th class="all">Actions</th>
                      </tr>
                    </thead>
                    <tbody></tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="offcanvas offcanvas-end" tabindex="-1" id="filter-canvas" aria-labelledby="filter-canvas-label">
          <div class="offcanvas-header">
            <h5 class="offcanvas-title">Filters</h5>
            <button type="button" class="btn btn-icon btn-link-danger" data-bs-dismiss="offcanvas" aria-label="Close"><i
          class="ti ti-x"></i></button>
          </div>
          <div class="offcanvas-body">
            <div class="form-group mb-4">
              <label class="form-label" for="filter_status">Status</label>
              <select class="form-control filter-select2" name="filter_status" id="filter_status">
                <option value="">--</option>
                <option value="1">Active</option>
                <option value="0">Inactive</option>
              </select>
            </div>
            <div class="form-group mb-4">
              <label class="form-label" for="filter_locked">Locked</label>
              <select class="form-control filter-select2" name="filter_locked" id="filter_locked">
                <option value="">--</option>
                <option value="1">Yes</option>
                <option value="0">No</option>
              </select>
            </div>
            <button type="button" class="btn btn-primary" data-bs-dismiss="offcanvas">Apply</button>
          </div>
        </div>