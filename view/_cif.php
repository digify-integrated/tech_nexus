<div class="row">
  <div class="col-lg-12">
    <div class="card table-card">
      <div class="card-header">
        <div class="row align-items-center">
          <div class="col-sm-6">
            <h5>CIF List</h5>
          </div>
          <div class="col-sm-6 text-sm-end mt-3 mt-sm-0">
            <?php
              if($cifCreateAccess['total'] > 0 || $cifDeleteAccess['total'] > 0){
                $action = '';
                              
                  if($cifDeleteAccess['total'] > 0){
                    $action .= '<div class="btn-group m-r-10">
                                  <button type="button" class="btn btn-outline-secondary dropdown-toggle d-none action-dropdown" data-bs-toggle="dropdown" aria-expanded="false">Action</button>
                                  <ul class="dropdown-menu dropdown-menu-end">
                                    <li><button class="dropdown-item" type="button" id="delete-cif">Delete CIF</button></li>
                                  </ul>
                                </div>';
                  }

                if($cifCreateAccess['total'] > 0){
                  $action .= '<button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#cif-modal">
                                Create
                              </button>';
                }
                              
                echo $action;
              }
            ?>
              <button type="button" class="btn btn-warning" data-bs-toggle="offcanvas" data-bs-target="#filter-offcanvas">
                Filter
              </button>
           </div>
        </div>
      </div>
      <div class="card-body">
        <div class="table-responsive dt-responsive">
          <table id="cif-table" class="table table-hover nowrap w-100">
            <thead>
              <tr>
                <th class="all">
                  <div class="form-check">
                    <input class="form-check-input" id="datatable-checkbox" type="checkbox">
                  </div>
                </th>
                <th>Reference No.</th>
                <th>Customer</th>
                <th>Product</th>
                <th>Company</th>
                <th>Supplier</th>
                <th>Add-on</th>
                <th>Status</th>
                <th>Remarks</th>
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

<div>
  <div class="offcanvas offcanvas-end" tabindex="-1" id="filter-offcanvas" aria-labelledby="filter-offcanvas-label">
    <div class="offcanvas-header">
      <h2 id="filter-offcanvas-label" style="margin-bottom:-0.5rem">Filter</h2>
      <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
      <div class="row">
        <div class="col-lg-12">
          <ul class="list-group list-group-flush mb-3">
            <li class="list-group-item px-2 py-2">
              <a class="btn border-0 px-0 text-start w-100" data-bs-toggle="collapse" href="#status-filter-collapse"><div class="float-end"><i class="ti ti-chevron-down"></i></div>
                Status
              </a>
              <div class="collapse " id="status-filter-collapse">
                <div class="py-3">
                  <div class="form-check my-2">
                    <input class="form-check-input status-filter" type="checkbox" id="status-on-process" value="On-Process" checked/>
                    <label class="form-check-label" for="status-on-process">On-Process</label>
                  </div>
                  <div class="form-check my-2">
                    <input class="form-check-input status-filter" type="checkbox" id="status-completed" value="Completed"/>
                    <label class="form-check-label" for="status-completed">Completed</label>
                  </div>
                  <div class="form-check my-2">
                    <input class="form-check-input status-filter" type="checkbox" id="status-cancelled" value="Cancelled"/>
                    <label class="form-check-label" for="status-cancelled">Cancelled</label>
                  </div>
                </div>
              </div>
            </li>
          </ul>
        </div>
      </div>
      <div class="row">
        <div class="col-lg-12">
          <button type="submit" class="btn btn-primary" id="submit-filter">Apply</button>
          <button class="btn btn-light-danger" data-bs-dismiss="offcanvas"> Close </button>
        </div>
      </div>
    </div>
  </div>
</div>