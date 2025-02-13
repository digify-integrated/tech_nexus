<div class="row">
  <div class="col-lg-12">
    <div class="card">
      <div class="card-header">
        <div class="row align-items-center">
          <div class="col-md-6">
            <h5>Product Inventory Report</h5>
          </div>
          <div class="col-md-6 text-sm-end mt-3 mt-sm-0">
            <?php 

              if ($scanProductInventory['total'] > 0) {
                echo '<button data-pc-animate="fade-in-scale" type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#scanModal" id="scan-modal">Scan</button>';
              }
            ?>
          </div>
        </div>
      </div>
      <div class="card-body">
        <div class="table-responsive dt-responsive">
          <table id="inventory-report-batch-table" class="table table-hover nowrap w-100">
            <thead>
              <tr>
                <th>Product</th>
                <th>Scan Status</th>
                <th>Scan Date</th>
                <th>Scan By</th>
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

<div class="modal fade modal-animate" id="scanModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Scan Product</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
            <div class="modal-body">
              <div id="reader" class="w-full"></div>
            </div>
            <audio id="scanSound" src="./assets/audio/scan.mp3"></audio>
            <div class="modal-footer">
              <button type="button" id="closeScannerBtn" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        </div>
    </div>
</div>