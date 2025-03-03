<div class="row">
  <div class="col-lg-12">
    <div class="card table-card">
      <div class="card-header">
        <div class="row align-items-center">
          <div class="col-sm-6">
            <h5>Product Inventory List</h5>
          </div>
          <div class="col-sm-6 text-sm-end mt-3 mt-sm-0">
            <?php
              if($openProductInventory['total'] > 0){
                $action = '';

                if($openProductInventory['total'] > 0 && $getInventoryReportClosed['total'] == 0){
                  $action .= '<button id="open-inventory-report" class="btn btn-success">Open Inventory Report</button>';
                }

                if($closeProductInventory['total'] > 0 && $getInventoryReportClosed['total'] > 0){
                  $action .= '<button id="close-inventory-report" class="btn btn-warning">Close Inventory Report</button>';
                }
                            
                echo $action;
              }
            ?>
        </div>
      </div>
      <div class="card-body">
        <div class="table-responsive dt-responsive w-100">
          <table id="inventory-report-table" class="table table-hover nowrap w-100">
            <thead>
              <tr>
                <th class="all">
                  <div class="form-check">
                    <input class="form-check-input" id="datatable-checkbox" type="checkbox">
                  </div>
                </th>
                <th>Batch Number</th>
                <th>Open Date</th>
                <th>Close Date</th>
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