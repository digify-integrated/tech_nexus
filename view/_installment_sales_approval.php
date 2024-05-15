<div class="row">
  <div class="col-lg-12">
    <div class="ecom-wrapper">
      <div class="ecom-content w-100">
        <div class="card table-card">
          <div class="card-header">
            <div class="row align-items-center">
              <div class="col-sm-6">
                <h5>Installment Sales Approval List</h5>
              </div>
            </div>
          </div>
          <div class="card-body">
            <div class="table-responsive dt-responsive">
              <input type="hidden" id="customer_id" value="<?php echo $customerID; ?>">
              <table id="installment-sales-approval-table" class="table table-hover nowrap w-100 text-uppercase">
                <thead>
                  <tr>
                    <th>OS Number</th>
                    <th>Customer</th>
                    <th>Product Type</th>
                    <th>Stock</th>
                    <th>For CI Date</th>
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