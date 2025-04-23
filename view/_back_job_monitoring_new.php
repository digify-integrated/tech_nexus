<div class="row">
  <div class="col-lg-12">
    <div class="card">
      <div class="card-header">
        <div class="row align-items-center">
          <div class="col-md-6">
            <h5>Internal Job Order</h5>
          </div>
          <?php
            if ($backJobMonitoringCreateAccess['total'] > 0) {
               echo '<div class="col-md-6 text-sm-end mt-3 mt-sm-0">
                      <button type="submit" form="backjob-monitoring-form" class="btn btn-success form-edit" id="submit-data">Save</button>
                      <button type="button" id="discard-create" class="btn btn-outline-danger form-edit">Discard</button>
                    </div>';
            }
          ?>
        </div>
      </div>
      <div class="card-body">
        <form id="backjob-monitoring-form" method="post" action="#">
            <div class="form-group row">
                <label class="col-lg-4 col-form-label">Type <span class="text-danger">*</span></label>
                <div class="col-lg-8">
                    <select class="form-control select2" name="type" id="type">
                        <option value="">--</option>
                        <option value="Backjob">Backjob</option>
                        <option value="Internal Repair">Internal Repair</option>
                    </select>
                </div>
            </div>
            <div class="form-group row d-none" id="sales-row">
                <label class="col-lg-4 col-form-label">Sales Proposal <span class="text-danger">*</span></label>
                <div class="col-lg-8">
                    <select class="form-control select2" name="sales_proposal_id" id="sales_proposal_id">
                        <option value="">--</option>
                        <?php echo $salesProposalModel->generateJobOrderBackjobOptions(); ?>
                    </select>
                </div>
            </div>
            <div class="form-group row d-none" id="product-row">
                <label class="col-lg-4 col-form-label">Product <span class="text-danger">*</span></label>
                <div class="col-lg-8">
                    <select class="form-control select2" name="product_id" id="product_id">
                        <option value="">--</option>
                        <?php echo $productModel->generateInternalRepairProductOptions(); ?>
                    </select>
                </div>
            </div>
        </form>
      </div>
    </div>
  </div>
</div>