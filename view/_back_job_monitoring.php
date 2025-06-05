<div class="row">
  <div class="col-lg-12">
    <div class="card table-card">
      <div class="card-header">
        <div class="row align-items-center">
          <div class="col-sm-6">
            <h5>Internal Job Order List</h5>
          </div>
          <div class="col-sm-6 text-sm-end mt-3 mt-sm-0">
            <?php
              if($backJobMonitoringCreateAccess['total'] > 0){
                $action = '';

                if($backJobMonitoringCreateAccess['total'] > 0){
                  $action .= '<a href="back-job-monitoring.php?new" class="btn btn-success">Create</a>';
                }
                              
                echo $action;
              }
            ?>
           </div>
        </div>
      </div>
      <div class="card-body">
        <div class="table-responsive dt-responsive">
        <table id="backjob-monitoring-table" class="table table-hover nowrap w-100">
            <thead>
              <tr>
                <th>Type</th>
                <th>Sales Proposal</th>
                <th>Product</th>
                <th>Status</th>
                <th>Created Date</th>
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