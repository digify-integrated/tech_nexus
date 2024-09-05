<div class="row">
  <div class="col-lg-12">
    <div class="ecom-wrapper">
      <div class="ecom-content w-100">
        <div class="card table-card">
          <div class="card-header">
            <div class="row align-items-center">
              <div class="col-sm-6">
                <h5>Released Sales Proposal List</h5>
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
                                  
                    echo $action;
                  }
                ?>
              </div>
            </div>
          </div>
          <div class="card-body">
            <div class="table-responsive dt-responsive">
              <input type="hidden" id="customer_id" value="<?php echo $customerID; ?>">
              <table id="released-sales-proposal-table" class="table table-hover nowrap w-100 text-uppercase">
                <thead>
                  <tr>
                    <th>OS Number</th>
                    <th>Customer</th>
                    <th>Product Type</th>
                    <th>Stock</th>
                    <th>Proceed Date</th>
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
  </div>
</div>