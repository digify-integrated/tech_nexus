<div class="row">
  <div class="col-lg-12">
    <div class="card">
      <div class="card-header">
        <div class="row align-items-center">
          <div class="col-sm-6">
            <h5>Educational Stage List</h5>
          </div>
          <div class="col-sm-6 text-sm-end mt-3 mt-sm-0">
            <?php
                if($educationalStageCreateAccess['total'] > 0 || $educationalStageDeleteAccess['total'] > 0){
                  $action = ' ';
                              
                  if($educationalStageDeleteAccess['total'] > 0){
                      $action .= '<div class="btn-group m-r-10">
                                  <button type="button" class="btn btn-outline-secondary dropdown-toggle d-none action-dropdown" data-bs-toggle="dropdown" aria-expanded="false">Action</button>
                                      <ul class="dropdown-menu dropdown-menu-end">
                                      <li><button class="dropdown-item" type="button" id="delete-educational-stage">Delete Educational Stage</button></li>
                                      </ul>
                                      </div>';
                  }

                  if($educationalStageCreateAccess['total'] > 0){
                      $action .= '<a href="educational-stage.php?new" class="btn btn-success">Create</a>';
                  }

                  $action .= '';
                              
                  echo $action;
                }
            ?>
           </div>
        </div>
      </div>
      <div class="card-body">
        <div class="table-responsive dt-responsive">
          <table id="educational-stage-table" class="table table-striped table-hover table-bordered nowrap w-100">
            <thead>
              <tr>
                <th class="all">
                  <div class="form-check">
                    <input class="form-check-input" id="datatable-checkbox" type="checkbox">
                  </div>
                </th>
                <th>Educational Stage</th>
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