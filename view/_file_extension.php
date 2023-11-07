<div class="row">
  <div class="col-lg-12">
    <div class="card">
      <div class="card-header">
        <div class="row align-items-center">
          <div class="col-sm-6">
            <h5>File Extension List</h5>
          </div>
          <?php
            if($fileExtensionCreateAccess['total'] > 0 || $fileExtensionDeleteAccess['total'] > 0){
              $action = ' <div class="col-sm-6 text-sm-end mt-3 mt-sm-0">';
                          
              if($fileExtensionDeleteAccess['total'] > 0){
                $action .= '<div class="btn-group m-r-10">
                              <button type="button" class="btn btn-outline-secondary dropdown-toggle d-none action-dropdown" data-bs-toggle="dropdown" aria-expanded="false">Action</button>
                              <ul class="dropdown-menu dropdown-menu-end">
                                <li><button class="dropdown-item" type="button" id="delete-file-extension">Delete File Extension</button></li>
                              </ul>
                            </div>';
              }

              if($fileExtensionCreateAccess['total'] > 0){
                  $action .= '<a href="file-extension.php?new" class="btn btn-success">Create</a>';
              }

              $action .= '</div>';
                            
              echo $action;
            }
          ?>
        </div>
      </div>
      <div class="card-body">
        <div class="table-responsive dt-responsive">
          <table id="file-extension-table" class="table table-hover table-bordered nowrap w-100">
            <thead>
              <tr>
                <th class="all">
                  <div class="form-check">
                    <input class="form-check-input" id="datatable-checkbox" type="checkbox">
                  </div>
                </th>
                <th>File Extension</th>
                <th>File Type</th>
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