<?php
    $hidden = '';
    $disabled = 'disabled';
    $saveButton = '';
    $releaseButton = '';
    $jobOrderButton = '';
    $cancelInternalDRButton = '';
    if($drStatus == 'Draft'){
        $hidden = 'd-none';
        $disabled = '';
        
        
       
        $saveButton = '<div class="me-2"><button type="submit" form="internal-dr-form" class="btn btn-success" id="submit-data">Save</button></div>';        
    }

    if($drType === 'Backjob' && ($drStatus === 'Draft')){
        $jobOrderButton = '<div class="previous me-2">
                                <button class="btn btn-success" type="button" data-bs-toggle="offcanvas" data-bs-target="#job-order-offcanvas" aria-controls="job-order-offcanvas">Load Job Order</button>
                            </div>';
    }

    $jobOrderHidden = '';
    if($drType != 'Backjob'){
        $jobOrderHidden = 'd-none';

        if($drStatus == 'Draft'){
            $releaseButton = '<div class="previous me-2">
                <button class="btn btn-info" type="button" data-bs-toggle="offcanvas" data-bs-target="#internal-dr-tag-as-released-offcanvas" aria-controls="internal-dr-tag-as-released-offcanvas">Tag As Released</button>
            </div>';
        }        
    }
    else{
        if($drStatus == 'For DR'){
            $releaseButton = '<div class="previous me-2">
                <button class="btn btn-info" type="button" data-bs-toggle="offcanvas" data-bs-target="#internal-dr-tag-as-released-offcanvas" aria-controls="internal-dr-tag-as-released-offcanvas">Tag As Released</button>
            </div>';
        }  

        if($drStatus == 'Draft'){
            $releaseButton = '<div class="previous me-2">
                <button class="btn btn-info" type="button" id="tag-as-on-process">Tag As On-Process</button>
            </div>';
        }  

        if($drStatus == 'On-Process'){
            $releaseButton = '<div class="previous me-2">
                <button class="btn btn-info" type="button" id="tag-as-ready-for-release">Tag As Ready For Release</button>
            </div>';
        }  

        if($drStatus == 'Ready For Release'){
            $releaseButton = '<div class="previous me-2">
                <button class="btn btn-info" type="button" id="tag-as-for-dr">Tag As For DR</button>
            </div>';
        }  
    }

    if($cancelInternalDR['total'] > 0 && ($drStatus != 'Cancelled' && $drStatus != 'Released')){
        $cancelInternalDRButton = '<div class="previous me-2">
                                <button class="btn btn-warning" type="button" data-bs-toggle="offcanvas" data-bs-target="#internal-dr-tag-as-cancelled-offcanvas" aria-controls="internal-dr-tag-as-cancelled-offcanvas">Tag As Cancelled</button>
                            </div>';
    }
?>

<div class="row">
    <div class="col-md-3">
        <div class="card">
            <div class="card-body">
                <ul class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                    <li><a class="nav-link active" id="internal-dr-tab-1" data-bs-toggle="pill" href="#v-basic-details" role="tab" aria-controls="v-basic-details" aria-selected="true">Basic Details</a></li>
                    <li><a class="nav-link" id="internal-dr-tab-2" data-bs-toggle="pill" href="#v-unit-image" role="tab" aria-controls="v-unit-image" aria-selected="false">Unit Image</a></li>
                    <li><a class="nav-link <?php echo $jobOrderHidden; ?>" id="internal-dr-tab-2" data-bs-toggle="pill" href="#v-job-order" role="tab" aria-controls="v-job-order" aria-selected="false">Finished Job Order</a></li>
                    <li><a class="nav-link <?php echo $hidden; ?>" id="internal-dr-tab-3" data-bs-toggle="pill" href="#v-gatepass" role="tab" aria-controls="v-gatepass" aria-selected="false">Gate Pass</a></li>
                </ul>
            </div>
        </div>
    </div>
    <div class="col-md-9">
        <div class="card">
            <div class="card-body">
                <div class="tab-content">
                    <div class="d-flex wizard justify-content-between mb-3">
                        <div class="d-flex">                     
                            <?php echo $releaseButton; ?>    
                            <?php echo $cancelInternalDRButton; ?>
                            <?php echo $jobOrderButton; ?>
                            <div class="previous me-2" >
                                <a href="internal-dr-receipt-dr-print.php?id=<?php echo $internalDRID; ?>" target="_blank" class="btn btn-outline-warning me-1">Print DR Receipt</a>
                            </div>      
                            <div class="previous me-2 d-none" id="gatepass-print-button">
                                <a href="javascript:window.print()" class="btn btn-outline-info me-1" id="print">Print</a>
                            </div>          
                        </div>
                    </div>
                    <div class="tab-pane show active" id="v-basic-details">
                        <form id="internal-dr-form" method="post" action="#">
                            <input type="hidden" id="internal_dr_id">
                            <input type="hidden" id="internal_dr_status" value="<?php echo $drStatus; ?>">
                            <div class="form-group row">
                                <label class="col-lg-2 col-form-label">Release To <span class="text-danger">*</span></label>
                                <div class="col-lg-4">
                                    <input type="text" class="form-control" id="release_to" name="release_to" maxlength="1000" autocomplete="off" <?php echo $disabled; ?>>
                                </div>
                                <label class="col-lg-2 col-form-label">Release To Mobile <span class="text-danger">*</span></label>
                                <div class="col-lg-4">
                                    <input type="text" class="form-control" id="release_mobile" name="release_mobile" maxlength="50" autocomplete="off" <?php echo $disabled; ?>>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-2 col-form-label">Release To Address <span class="text-danger">*</span></label>
                                <div class="col-lg-10">
                                    <textarea class="form-control" id="release_address" name="release_address" maxlength="1000" row="3" <?php echo $disabled; ?>></textarea>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-2 col-form-label">DR Number <span class="text-danger">*</span></label>
                                <div class="col-lg-4">
                                    <input type="text" class="form-control" id="dr_number" name="dr_number" maxlength="50" autocomplete="off" <?php echo $disabled; ?>>
                                </div>
                                <label class="col-lg-2 col-form-label">DR Type <span class="text-danger">*</span></label>
                                <div class="col-lg-4">
                                    <select class="form-control select2" name="dr_type" id="dr_type" <?php echo $disabled; ?>>
                                        <option value="">--</option>
                                        <option value="Unit">Unit</option>
                                        <option value="Fuel">Fuel</option>
                                        <option value="Repair">Repair</option>
                                        <option value="Backjob">Backjob</option>
                                        <option value="Parts">Parts</option>
                                        <option value="Tools">Tools</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-2 col-form-label">Stock Number </label>
                                <div class="col-lg-4">
                                    <input type="text" class="form-control" id="stock_number" name="stock_number" maxlength="100" autocomplete="off" <?php echo $disabled; ?>>
                                </div>
                                <label class="col-lg-2 col-form-label">Engine Number </label>
                                <div class="col-lg-4">
                                    <input type="text" class="form-control" id="engine_number" name="engine_number" maxlength="100" autocomplete="off" <?php echo $disabled; ?>>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-2 col-form-label">Chassis Number </label>
                                <div class="col-lg-4">
                                    <input type="text" class="form-control" id="chassis_number" name="chassis_number" maxlength="100" autocomplete="off" <?php echo $disabled; ?>>
                                </div>
                                <label class="col-lg-2 col-form-label">Plate Number </label>
                                <div class="col-lg-4">
                                    <input type="text" class="form-control" id="plate_number" name="plate_number" maxlength="100" autocomplete="off" <?php echo $disabled; ?>>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-2 col-form-label">Product Description <span class="text-danger">*</span></label>
                                <div class="col-lg-10">
                                    <textarea class="form-control" id="product_description" name="product_description" maxlength="1000" row="3" <?php echo $disabled; ?>></textarea>
                                </div>
                            </div>
                        </form>
                        
                        <?php echo $saveButton; ?>       
                    </div>
                    <div class="tab-pane" id="v-job-order">
                        <div class="row">
                            <div class="col-xl-12">
                                <div class="card">
                                    <div class="card-body py-2">
                                        <div class="table-responsive">
                                            <table id="job-order-progress-table" class="table table-hover nowrap w-100">
                                                <thead>
                                                <tr>
                                                    <th>Job Order</th>
                                                    <th>Contactor</th>
                                                    <th>Work Center</th>
                                                    <th>Progress</th>
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
                        <div class="row">
                            <div class="col-xl-12">
                                <div class="card">
                                    <div class="card-body py-2">
                                        <div class="table-responsive">
                                            <table id="additional-job-order-progress-table" class="table table-hover nowrap w-100">
                                                <thead>
                                                <tr>
                                                    <th>Job Order Number</th>
                                                    <th>Job Order Date</th>
                                                    <th>Particulars</th>
                                                    <th>Contactor</th>
                                                    <th>Work Center</th>
                                                    <th>Progress</th>
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
                    <div class="tab-pane" id="v-unit-image">
                        <div class="row">
                            <div class="col-xl-6">
                                <div class="card">
                                    <div class="card-body py-2">
                                        <ul class="list-group list-group-flush">
                                            <li class="list-group-item px-0 d-flex align-items-center justify-content-between">
                                                <h5 class="mb-0">Unit Image </h5>
                                                <?php
                                                    if($drStatus == 'Draft'){
                                                        echo '<button class="btn btn-info" type="button" data-bs-toggle="offcanvas" data-bs-target="#internal-dr-unit-image-offcanvas" aria-controls="internal-dr-unit-image-offcanvas" id="internal-dr-unit-image">Unit Image</button>';
                                                    }
                                                ?>
                                            </li>
                                            <li class="list-group-item px-0">
                                                <div class="row align-items-center mb-3">
                                                    <div class="col-sm-12 mb-sm-0">
                                                        <img src="<?php echo DEFAULT_PLACEHOLDER_IMAGE; ?>" alt="Unit Image" id="unit-image" class="img-fluid rounded">
                                                    </div>                      
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-6">
                                <div class="card">
                                    <div class="card-body py-2">
                                        <ul class="list-group list-group-flush">
                                            <li class="list-group-item px-0 d-flex align-items-center justify-content-between">
                                                <h5 class="mb-0">Outgoing Checklist </h5>
                                                <?php
                                                    if($drStatus == 'Ready For Release' || $drStatus == 'For DR'){
                                                        echo '<button class="btn btn-info" type="button" data-bs-toggle="offcanvas" data-bs-target="#internal-dr-outgoing-checklist-offcanvas" aria-controls="internal-dr-outgoing-checklist-offcanvas" id="internal-dr-outgoing-checklist">Outgoing Checklist Image</button>';
                                                    }
                                                ?>
                                            </li>
                                            <li class="list-group-item px-0">
                                                <div class="row align-items-center mb-3">
                                                    <div class="col-sm-12 mb-sm-0">
                                                        <img src="<?php echo DEFAULT_PLACEHOLDER_IMAGE; ?>" alt="Outgoing Checklist" id="outgoing-checklist" class="img-fluid rounded">
                                                    </div>                      
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-6">
                                <div class="card">
                                    <div class="card-body py-2">
                                        <ul class="list-group list-group-flush">
                                            <li class="list-group-item px-0 d-flex align-items-center justify-content-between">
                                                <h5 class="mb-0">Quality Control Form </h5>
                                                <?php
                                                    if($drStatus == 'Ready For Release' || $drStatus == 'For DR'){
                                                        echo '<button class="btn btn-info" type="button" data-bs-toggle="offcanvas" data-bs-target="#internal-dr-quality-control-form-offcanvas" aria-controls="internal-dr-quality-control-form-offcanvas" id="internal-dr-quality-control-form">Quality Control Form</button>';
                                                    }
                                                ?>
                                            </li>
                                            <li class="list-group-item px-0">
                                                <div class="row align-items-center mb-3">
                                                    <div class="col-sm-12 mb-sm-0">
                                                        <img src="<?php echo DEFAULT_PLACEHOLDER_IMAGE; ?>" alt="Quality Control Form" id="quality-control-form" class="img-fluid rounded">
                                                    </div>                      
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane" id="v-gatepass">
                        <div class="print-area-gatepass-internal">
                            <div class="row">
                                <div class="col-4">
                                    <h5 class="text-center"><b>GATE PASS - UNIT</b></h5>
                                </div>
                                <div class="col-4">
                                    <h6 class="text-center"><b>NO : <?php echo $drNumber; ?></b></h6>
                                </div>
                                <div class="col-4">
                                    <h6 class="text-center"><b>DATE : <?php echo date('d-M-Y'); ?></b></h6>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-lg-12">
                                    <div class="table-border-style mw-100">
                                        <div class="table-border-style mw-100">
                                            <div class="table-responsive">
                                                <table class="table table-bordered table-xs text-uppercase">
                                                    <tbody>
                                                        <tr>
                                                            <td colspan="2"><small>Customer Name</small></td>
                                                            <td colspan="3"><small><?php echo $releaseTo; ?></small></td>
                                                            <td rowspan="4" class="text-center">
                                                                <img src="<?php echo $unitImage; ?>" width="100">
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td colspan="2"><small>Contact Number</small></td>
                                                            <td><small><?php echo $releaseMobile; ?></small></td>
                                                            <td><small>Stock Number</small></td>
                                                            <td><small><?php echo $stockNumber; ?></small></td>
                                                        </tr>
                                                        <tr>
                                                            <td colspan="2"><small>Address</small></td>
                                                            <td colspan="3"><small><?php echo strtoupper($releaseAddress); ?></small></td>
                                                        </tr>
                                                        <tr>
                                                            <td colspan="2"><small>DR No.</small></td>
                                                            <td><small><?php echo $drNumber; ?></small></td>
                                                            <td><small>DR Date</small></td>
                                                            <td><small><?php echo date('d-M-Y'); ?></small></td>
                                                        </tr>
                                                        <tr>
                                                            <td colspan="6">
                                                                <p class="text-center"><small>PARTICULARS</small></p>
                                                                <p class="text-wrap"><b><small><?php echo $productDescription; ?></small></b></p>
                                                                    <small class="text-danger">REMINDER:</small><br/>
                                                                    <small >GATE PASS SHALL ALWAYS BE ACCOMPANIED BY DULY APPROVED DELIVERY RECEIPT</small>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                            <td colspan="3" style="vertical-align: top !important;" class="text-nowrap">
                                                                <small>RELEASED BY:</small><br/><br/><br/>
                                                                <small>CUSTOMER/AUTHORIZED REPRESENTATIVE</small>
                                                            </td>
                                                            <td colspan="2" style="vertical-align: top !important;" class="text-nowrap">
                                                                <small>RELEASED APPROVED BY:</small><br/><br/><br/>
                                                                <small>CHRISTIAN EDWARD C. BAGUISA &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; PAOLO EDUARDO C. BAGUISA</small>
                                                            </td>
                                                            <td style="vertical-align: top !important;" class="text-wrap">
                                                                <small>INSPECTED BY/DATE:</small><br/><br/><br/>
                                                                <small>GUARD ON DUTY</small>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table><br/>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-4">
                                    <h5 class="text-center"><b>GATE PASS - UNIT</b></h5>
                                </div>
                                <div class="col-4">
                                    <h6 class="text-center"><b>NO : <?php echo $drNumber; ?></b></h6>
                                </div>
                                <div class="col-4">
                                    <h6 class="text-center"><b>DATE : <?php echo date('d-M-Y'); ?></b></h6>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-lg-12">
                                    <div class="table-border-style mw-100">
                                        <div class="table-border-style mw-100">
                                            <div class="table-responsive">
                                                <table class="table table-bordered table-xs text-uppercase">
                                                    <tbody>
                                                        <tr>
                                                            <td colspan="2"><small>Customer Name</small></td>
                                                            <td colspan="3"><small><?php echo $releaseTo; ?></small></td>
                                                            <td rowspan="4" class="text-center">
                                                                <img src="<?php echo $unitImage; ?>" width="100">
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td colspan="2"><small>Contact Number</small></td>
                                                            <td colspan="3"><small><?php echo $releaseMobile; ?></small></td>
                                                        </tr>
                                                        <tr>
                                                            <td colspan="2"><small>Address</small></td>
                                                            <td colspan="3"><small><?php echo strtoupper($releaseAddress); ?></small></td>
                                                        </tr>
                                                        <tr>
                                                            <td colspan="2"><small>DR No.</small></td>
                                                            <td><small><?php echo $drNumber; ?></small></td>
                                                            <td><small>DR Date</small></td>
                                                            <td><small><?php echo date('d-M-Y'); ?></small></td>
                                                        </tr>
                                                        <tr>
                                                            <td colspan="6">
                                                                <p class="text-center"><small>PARTICULARS</small></p>
                                                                <p class="text-wrap"><b><small><?php echo $productDescription; ?></small></b></p>
                                                                    <small class="text-danger">REMINDER:</small><br/>
                                                                    <small >GATE PASS SHALL ALWAYS BE ACCOMPANIED BY DULY APPROVED DELIVERY RECEIPT</small>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                            <td colspan="3" style="vertical-align: top !important;" class="text-nowrap">
                                                                <small>RELEASED BY:</small><br/><br/><br/>
                                                                <small>CUSTOMER/AUTHORIZED REPRESENTATIVE</small>
                                                            </td>
                                                            <td colspan="2" style="vertical-align: top !important;" class="text-nowrap">
                                                                <small>RELEASED APPROVED BY:</small><br/><br/><br/>
                                                                <small>CHRISTIAN EDWARD C. BAGUISA &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; PAOLO EDUARDO C. BAGUISA</small>
                                                            </td>
                                                            <td style="vertical-align: top !important;" class="text-wrap">
                                                                <small>INSPECTED BY/DATE:</small><br/><br/><br/>
                                                                <small>GUARD ON DUTY</small>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table><br/>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div>
    <div class="offcanvas offcanvas-end" tabindex="-1" id="internal-dr-tag-as-released-offcanvas" aria-labelledby="internal-dr-tag-as-released-offcanvas-label">
        <div class="offcanvas-header">
            <h2 id="internal-dr-tag-as-released-offcanvas-label" style="margin-bottom:-0.5rem">Tag As Released </h2>
            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            <div class="row">
                <div class="col-lg-12">
                    <form id="internal-dr-tag-as-released-form" method="post" action="#">
                        <div class="form-group row">
                            <div class="col-lg-12 mt-3 mt-lg-0">
                                <label class="form-label" for="release_remarks">Release Remarks <span class="text-danger">*</span></label>
                                <textarea class="form-control" id="release_remarks" name="release_remarks" maxlength="500"></textarea>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <button type="submit" class="btn btn-primary" id="submit-internal-dr-tag-as-released" form="internal-dr-tag-as-released-form">Submit</button>
                    <button class="btn btn-light-danger" data-bs-dismiss="offcanvas"> Close </button>
                </div>
            </div>
        </div>
    </div>
</div>

<div>
    <div class="offcanvas offcanvas-end" tabindex="-1" id="internal-dr-tag-as-cancelled-offcanvas" aria-labelledby="internal-dr-tag-as-cancelled-offcanvas-label">
        <div class="offcanvas-header">
            <h2 id="internal-dr-tag-as-cancelled-offcanvas-label" style="margin-bottom:-0.5rem">Tag As Cancelled </h2>
            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            <div class="row">
                <div class="col-lg-12">
                    <form id="internal-dr-tag-as-cancelled-form" method="post" action="#">
                        <div class="form-group row">
                            <div class="col-lg-12 mt-3 mt-lg-0">
                                <label class="form-label" for="cancellation_reason">Cancellation Remarks <span class="text-danger">*</span></label>
                                <textarea class="form-control" id="cancellation_reason" name="cancellation_reason" maxlength="500"></textarea>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <button type="submit" class="btn btn-primary" id="submit-internal-dr-tag-as-cancelled" form="internal-dr-tag-as-cancelled-form">Submit</button>
                    <button class="btn btn-light-danger" data-bs-dismiss="offcanvas"> Close </button>
                </div>
            </div>
        </div>
    </div>
</div>

<div>
    <div class="offcanvas offcanvas-end" tabindex="-1" id="job-order-offcanvas" aria-labelledby="job-order-offcanvas-label">
        <div class="offcanvas-header">
            <h2 id="job-order-offcanvas-label" style="margin-bottom:-0.5rem">Load Job Order </h2>
            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            <div class="row">
                <div class="col-lg-12">
                    <form id="job-order-form" method="post" action="#">
                        <div class="form-group row">
                            <div class="col-lg-12 mt-3 mt-lg-0">
                                <label class="form-label" for="cancellation_reason">Sales Proposal <span class="text-danger">*</span></label>
                                <select class="form-control select2" name="sales_proposal_id" id="sales_proposal_id">
                                    <option value="">--</option>
                                    <?php echo $salesProposalModel->generateJobOrderBackjobOptions(); ?>
                                </select>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <button type="submit" class="btn btn-primary" id="submit-job-order" form="job-order-form">Submit</button>
                    <button class="btn btn-light-danger" data-bs-dismiss="offcanvas"> Close </button>
                </div>
            </div>
        </div>
    </div>
</div>

<div>
  <div class="offcanvas offcanvas-end" tabindex="-1" id="internal-dr-unit-image-offcanvas" aria-labelledby="internal-dr-unit-image-offcanvas-label">
    <div class="offcanvas-header">
      <h2 id="internal-dr-unit-image-offcanvas-label" style="margin-bottom:-0.5rem">Unit Image</h2>
      <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
      <div class="row">
        <div class="col-lg-12">
          <form id="internal-dr-unit-image-form" method="post" action="#">
            <div class="form-group row">
              <div class="col-lg-12 mt-3 mt-lg-0">
                <label class="form-label">Unit Image <span class="text-danger">*</span></label>
                <input type="file" class="form-control" id="unit_image_image" name="unit_image_image">
              </div>
            </div>
          </form>
        </div>
      </div>
      <div class="row">
        <div class="col-lg-12">
          <button type="submit" class="btn btn-primary" id="submit-internal-dr-unit-image" form="internal-dr-unit-image-form">Submit</button>
          <button class="btn btn-light-danger" data-bs-dismiss="offcanvas"> Close </button>
        </div>
      </div>
    </div>
  </div>
</div>

<div>
  <div class="offcanvas offcanvas-end" tabindex="-1" id="internal-dr-outgoing-checklist-offcanvas" aria-labelledby="internal-dr-outgoing-checklist-offcanvas-label">
    <div class="offcanvas-header">
      <h2 id="internal-dr-outgoing-checklist-offcanvas-label" style="margin-bottom:-0.5rem">Outgoing Checklist</h2>
      <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
      <div class="row">
        <div class="col-lg-12">
          <form id="internal-dr-outgoing-checklist-form" method="post" action="#">
            <div class="form-group row">
              <div class="col-lg-12 mt-3 mt-lg-0">
                <label class="form-label">Outgoing Checklist <span class="text-danger">*</span></label>
                <input type="file" class="form-control" id="outgoing_checklist_image" name="outgoing_checklist_image">
              </div>
            </div>
          </form>
        </div>
      </div>
      <div class="row">
        <div class="col-lg-12">
          <button type="submit" class="btn btn-primary" id="submit-internal-dr-outgoing-checklist" form="internal-dr-outgoing-checklist-form">Submit</button>
          <button class="btn btn-light-danger" data-bs-dismiss="offcanvas"> Close </button>
        </div>
      </div>
    </div>
  </div>
</div>

<div>
  <div class="offcanvas offcanvas-end" tabindex="-1" id="internal-dr-quality-control-form-offcanvas" aria-labelledby="internal-dr-quality-control-form-offcanvas-label">
    <div class="offcanvas-header">
      <h2 id="internal-dr-quality-control-form-offcanvas-label" style="margin-bottom:-0.5rem">Quality Control Form</h2>
      <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
      <div class="row">
        <div class="col-lg-12">
          <form id="internal-dr-quality-control-form-form" method="post" action="#">
            <div class="form-group row">
              <div class="col-lg-12 mt-3 mt-lg-0">
                <label class="form-label">Quality Control Form <span class="text-danger">*</span></label>
                <input type="file" class="form-control" id="quality_control_form" name="quality_control_form">
              </div>
            </div>
          </form>
        </div>
      </div>
      <div class="row">
        <div class="col-lg-12">
          <button type="submit" class="btn btn-primary" id="submit-internal-dr-quality-control-form" form="internal-dr-quality-control-form-form">Submit</button>
          <button class="btn btn-light-danger" data-bs-dismiss="offcanvas"> Close </button>
        </div>
      </div>
    </div>
  </div>
</div>

<div>
    <div class="offcanvas offcanvas-end" tabindex="-1" id="job-order-monitoring-offcanvas" aria-labelledby="job-order-monitoring-offcanvas-label">
      <div class="offcanvas-header">
        <h2 id="job-order-offcanvas-label" style="margin-bottom:-0.5rem">Job Order Progress</h2>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
      </div>
    <div class="offcanvas-body">
      <div class="row">
        <div class="col-lg-12">
          <form id="job-order-progress-form" method="post" action="#">
            <input type="hidden" id="internal_dr_job_order_id" name="internal_dr_job_order_id">
            <div class="form-group row">
              <div class="col-lg-6 mt-3 mt-lg-0">
                <label class="form-label">Progress (%) <span class="text-danger">*</span></label>
                <input type="number" class="form-control" id="job_order_progress" name="job_order_progress" min="0" max="100" step="0.01">
              </div>
              <div class="col-lg-6 mt-3 mt-lg-0">
                <label class="form-label">Contractor</label>
                <select class="form-control offcanvas-select2" name="job_order_contractor_id" id="job_order_contractor_id">
                  <option value="">--</option>
                  <?php echo $contractorModel->generateContractorOptions(); ?>
                </select>
              </div>
            </div>
            <div class="form-group row">
              <div class="col-lg-6 mt-3 mt-lg-0">
                <label class="form-label">Work Center</label>
                <select class="form-control offcanvas-select2" name="job_order_work_center_id" id="job_order_work_center_id">
                  <option value="">--</option>
                  <?php echo $workCenterModel->generateWorkCenterOptions(); ?>
                </select>
              </div>
            </div>
          </form>
        </div>
      </div>
      <div class="row">
        <div class="col-lg-12">
          <button type="submit" class="btn btn-primary" id="submit-job-order-progress" form="job-order-progress-form">Submit</button>
          <button class="btn btn-light-danger" data-bs-dismiss="offcanvas"> Close </button>
        </div>
      </div>
    </div>
  </div>
  <div>
    <div class="offcanvas offcanvas-end" tabindex="-1" id="additional-job-order-monitoring-offcanvas" aria-labelledby="additional-job-order-monitoring-offcanvas-label">
      <div class="offcanvas-header">
        <h2 id="additional-job-order-offcanvas-label" style="margin-bottom:-0.5rem">Additional Job Order Progress</h2>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
      </div>
    <div class="offcanvas-body">
      <div class="row">
        <div class="col-lg-12">
          <form id="additional-job-order-progress-form" method="post" action="#">
            <input type="hidden" id="internal_dr_additional_job_order_id" name="internal_dr_additional_job_order_id">
            <div class="form-group row">
              <div class="col-lg-12 mt-3 mt-lg-0">
                <label class="form-label">Progress (%) <span class="text-danger">*</span></label>
                <input type="number" class="form-control" id="additional_job_order_progress" name="additional_job_order_progress" min="0" max="100" step="0.01">
              </div>
              <div class="col-lg-6 mt-3 mt-lg-0">
                <label class="form-label">Contractor</label>
                <select class="form-control offcanvas-select2" name="additional_job_order_contractor_id" id="additional_job_order_contractor_id">
                  <option value="">--</option>
                  <?php echo $contractorModel->generateContractorOptions(); ?>
                </select>
              </div>
            </div>
            <div class="form-group row">
              <div class="col-lg-6 mt-3 mt-lg-0">
                <label class="form-label">Work Center</label>
                <select class="form-control offcanvas-select2" name="additional_job_order_work_center_id" id="additional_job_order_work_center_id">
                  <option value="">--</option>
                  <?php echo $workCenterModel->generateWorkCenterOptions(); ?>
                </select>
              </div>
            </div>
          </form>
        </div>
      </div>
      <div class="row">
        <div class="col-lg-12">
          <button type="submit" class="btn btn-primary" id="submit-additional-job-order-progress" form="additional-job-order-progress-form">Submit</button>
          <button class="btn btn-light-danger" data-bs-dismiss="offcanvas"> Close </button>
        </div>
      </div>
    </div>
  </div>