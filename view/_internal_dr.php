<div class="row">
  <div class="col-lg-12">
    <div class="card table-card">
      <div class="card-header">
        <div class="row align-items-center">
          <div class="col-sm-6">
            <h5>Internal DR List</h5>
          </div>
          <div class="col-sm-6 text-sm-end mt-3 mt-sm-0">
            <?php
              if($internalDRCreateAccess['total'] > 0 || $internalDRDeleteAccess['total'] > 0){
                $action = '';
                          
                if($internalDRDeleteAccess['total'] > 0){
                  $action .= '<div class="btn-group m-r-10">
                                <button type="button" class="btn btn-outline-secondary dropdown-toggle d-none action-dropdown" data-bs-toggle="dropdown" aria-expanded="false">Action</button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                  <li><button class="dropdown-item" type="button" id="delete-internal-dr">Delete internal DR</button></li>
                                </ul>
                              </div>';
                }

                if($internalDRCreateAccess['total'] > 0){
                  $action .= '<a href="internal-dr.php?new" class="btn btn-success">Create</a>';
                }
                            
                echo $action;
              }
            ?>
          </div>
        </div>
      </div>
      <div class="card-body">
        <div class="table-responsive dt-responsive">
          <table id="internal-dr-table" class="table table-hover nowrap w-100">
            <thead>
              <tr>
                <th class="all">
                  <div class="form-check">
                    <input class="form-check-input" id="datatable-checkbox" type="checkbox">
                  </div>
                </th>
                <th>Internal DR Number</th>
                <th>Release To</th>
                <th>DR Type</th>
                <th>DR Number</th>
                <th>Stock No.</th>
                <th>Interal DR Status</th>
                <th>Released Date</th>
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