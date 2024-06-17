<div class="row">
  <div class="col-lg-12">
    <div class="card table-card">
      <div class="card-header">
        <div class="row align-items-center">
          <div class="col-sm-6">
            <h5>Parts Inquiry List</h5>
          </div>
          <div class="col-sm-6 text-sm-end mt-3 mt-sm-0">
            <?php
              if($partsInquiryCreateAccess['total'] > 0 || $partsInquiryDeleteAccess['total'] > 0){
                $action = '';
                          
                if($partsInquiryDeleteAccess['total'] > 0){
                  $action .= '<div class="btn-group m-r-10">
                                <button type="button" class="btn btn-outline-secondary dropdown-toggle d-none action-dropdown" data-bs-toggle="dropdown" aria-expanded="false">Action</button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                  <li><button class="dropdown-item" type="button" id="delete-parts-inquiry">Delete Parts Inquiry</button></li>
                                </ul>
                              </div>';
                }

                if($partsInquiryCreateAccess['total'] > 0){
                  $action .= '<a href="parts-inquiry.php?new" class="btn btn-success">Create</a>
                    <button class="btn btn-primary" type="button" data-bs-toggle="offcanvas" data-bs-target="#load-file-offcanvas" aria-controls="load-file-offcanvas" id="add-load-file">Import</button>';
                }
              
                            
                echo $action;
              }
            ?>
          </div>
        </div>
      </div>
      <div class="card-body">
        <div class="table-responsive dt-responsive">
          <table id="parts-inquiry-table" class="table table-hover nowrap w-100">
            <thead>
              <tr>
                <th class="all">
                  <div class="form-check">
                    <input class="form-check-input" id="datatable-checkbox" type="checkbox">
                  </div>
                </th>
                <th>Part Number</th>
                <th>Stock</th>
                <th>Price</th>
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

<div class="offcanvas offcanvas-end" tabindex="-1" id="load-file-offcanvas" aria-labelledby="load-file-offcanvas-label">
            <div class="offcanvas-header">
              <h2 id="load-file-offcanvas-label" style="margin-bottom:-0.5rem">Load Parts Inquiry</h2>
              <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body">
              <div class="row mb-2">
                <div class="col-lg-12">
                  <form id="import-parts-inquiry-form" method="post" action="#">
                    <div class="form-group row">
                        <div class="col-lg-12">
                            <label class="form-label">Import File <span class="text-danger">*</span></label>
                            <input type="file" class="form-control" id="import_file" name="import_file">
                        </div>
                    </div>
                  </form>
                </div>
              </div>
              <div class="row">
                <div class="col-lg-12">
                  <button type="submit" class="btn btn-primary" id="submit-load-file" form="import-parts-inquiry-form">Submit</button>
                  <button class="btn btn-light-danger" data-bs-dismiss="offcanvas"> Close </button>
                </div>
              </div>
            </div>
          </div>