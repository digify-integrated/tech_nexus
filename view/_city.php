<div class="row">
  <div class="col-lg-12">
    <div class="card table-card">
      <div class="card-header">
        <div class="row align-items-center">
          <div class="col-sm-6">
            <h5>City List</h5>
          </div>
          <div class="col-sm-6 text-sm-end mt-3 mt-sm-0">
            <?php
                if($cityCreateAccess['total'] > 0 || $cityDeleteAccess['total'] > 0){
                $action = ' ';
                            
                if($cityDeleteAccess['total'] > 0){
                    $action .= '<div class="btn-group m-r-10">
                                <button type="button" class="btn btn-outline-secondary dropdown-toggle d-none action-dropdown" data-bs-toggle="dropdown" aria-expanded="false">Action</button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                    <li><button class="dropdown-item" type="button" id="delete-city">Delete City</button></li>
                                    </ul>
                                    </div>';
                }

                if($cityCreateAccess['total'] > 0){
                    $action .= '<a href="city.php?new" class="btn btn-success">Create</a>';
                }

                $action .= '';
                            
                echo $action;
                }
            ?>
           <button class="btn btn-primary" type="button" data-bs-toggle="offcanvas" data-bs-target="#filter-canvas" aria-controls="filter-canvas">Filters</button>
           </div>
        </div>
      </div>
      <div class="card-body">
        <div class="table-responsive dt-responsive">
          <table id="city-table" class="table table-hover nowrap w-100">
            <thead>
              <tr>
                <th class="all">
                  <div class="form-check">
                    <input class="form-check-input" id="datatable-checkbox" type="checkbox">
                  </div>
                </th>
                <th>City</th>
                <th>State</th>
                <th>Country</th>
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

<div class="offcanvas offcanvas-end" tabindex="-1" id="filter-canvas" aria-labelledby="filter-canvas-label">
  <div class="offcanvas-header">
    <h5 class="offcanvas-title">Filters</h5>
    <button type="button" class="btn btn-icon btn-link-danger" data-bs-dismiss="offcanvas" aria-label="Close"><i class="ti ti-x"></i></button>
  </div>
  <div class="offcanvas-body">
    <div class="form-group mb-4">
      <label class="form-label" for="filter_state">State</label>
      <select class="form-control filter-select2" name="filter_state" id="filter_state">
        <option value="">--</option>
        <?php echo $stateModel->generateStateOptions(); ?>
      </select>
    </div>

    <button type="button" class="btn btn-primary" id="filter-datatable" data-bs-dismiss="offcanvas">Apply</button>
  </div>
</div>