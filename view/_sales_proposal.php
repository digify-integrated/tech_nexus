<div class="row">
  <div class="col-lg-12">
    <div class="card table-card">
      <div class="card-header">
        <div class="row align-items-center">
          <div class="col-sm-6">
            <h5>Sales Proposal List</h5>
          </div>
          <div class="col-sm-6 text-sm-end mt-3 mt-sm-0">
            <?php
              if($addSalesProposal['total'] > 0 || $deleteSalesProposal['total'] > 0){
                $action = '';
                              
                  if($deleteSalesProposal['total'] > 0){
                    $action .= '<div class="btn-group m-r-10">
                                  <button type="button" class="btn btn-outline-secondary dropdown-toggle d-none action-dropdown" data-bs-toggle="dropdown" aria-expanded="false">Action</button>
                                  <ul class="dropdown-menu dropdown-menu-end">
                                    <li><button class="dropdown-item" type="button" id="delete-sales-proposal">Delete Sales Proposal</button></li>
                                  </ul>
                                </div>';
                  }

                if($addSalesProposal['total'] > 0){
                  $action .= '<a href="sales-proposal.php?new&customer='. $securityModel->encryptData($customerID) .'" class="btn btn-success">Create</a>';
                }
                              
                echo $action;
              }
            ?>
           </div>
        </div>
      </div>
      <div class="card-body">
        <div class="table-responsive dt-responsive">
          <table id="sales-proposal-table" class="table table-hover nowrap w-100">
            <thead>
              <tr>
                <th class="all">
                  <div class="form-check">
                    <input class="form-check-input" id="datatable-checkbox" type="checkbox">
                  </div>
                </th>
                <th>Sales Proposal Number</th>
                <th>Customer</th>
                <th>Product</th>
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