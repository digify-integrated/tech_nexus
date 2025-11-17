<?php

    $ciReportDetails = $ciReportModel->getCIReport($ciReportID);
    $ci_status = $ciReportDetails['ci_status'] ?? 'Draft';
    $customer_id = $ciReportDetails['contact_id'] ?? null;

    $disabled = '';
    $loanProposal = '';

    if($ci_status == 'Completed'){
        $disabled = 'disabled';
    }
?>
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <h5>CI Report</h5>
                    </div>
                    <div class="col-md-6 text-sm-end mt-3 mt-sm-0">
                        <?php
                            if(($tagForCompletion['total'] > 0 && ($ci_status == 'Draft' || $ci_status == 'For Completion')) || ($tagForCompleted['total'] > 0 && $ci_status == 'For Completion') || ($ci_status == 'For Completion' || $ci_status == 'Completed')){
                                $dropdown = '<div class="btn-group m-r-5">
                                                <button type="button" class="btn btn-outline-secondary dropdown-toggle form-details" data-bs-toggle="dropdown" aria-expanded="false">
                                                    Action
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end">';

                                if ($tagForCompletion['total'] > 0 && ($ci_status == 'Draft' || $ci_status == 'For Completion')) {
                                    $dropdown .= '<li><button class="dropdown-item" type="button" id="tag-for-completion">Tag For Completion</button></li>';
                                }

                                if ($tagForCompleted['total'] > 0 && $ci_status == 'For Completion') {
                                    $dropdown .= '<li><button class="dropdown-item" type="button" id="tag-as-completed">Tag As Completed</button></li>';
                                }

                                if ($ci_status == 'For Completion' || $ci_status == 'Completed') {
                                    $dropdown .= '<li><button class="dropdown-item" type="button" data-bs-toggle="offcanvas" data-bs-target="#ci-report-set-to-draft-offcanvas" aria-controls="ci-report-set-to-draft-offcanvas">Set to Draft</button></li>';
                                }

                                $dropdown .= '</ul>
                                            </div>';
                                    
                                echo $dropdown;
                            }                           

                            if ($ciReportWriteAccess['total'] > 0 && ($ci_status == 'Draft' || $ci_status == 'For Completion')) {
                                echo '<button type="submit" form="ci-report-form" class="btn btn-success" id="submit-data">Save</button>';
                            }
                        ?>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <form id="ci-report-form" method="post" action="#">
                    <div class="form-group row">
                        <label class="col-lg-2 col-form-label">Customer Name</label>
                        <div class="col-lg-4">
                            <input type="text" class="form-control" id="customer_name" name="customer_name" maxlength="5000" autocomplete="off" readonly>
                        </div>
                        <label class="col-lg-2 col-form-label">CI Type</label>
                        <div class="col-lg-4">
                             <input type="text" class="form-control" id="ci_type" name="ci_type" maxlength="100" autocomplete="off" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-2 col-form-label">Appraiser <span class="text-danger">*</span></label>
                        <div class="col-lg-4">
                            <select class="form-control select2" name="appraiser" id="appraiser" <?php echo $disabled; ?>>
                                <option value="">--</option>
                                <?php echo $userModel->generateCIInvestigatorOptions('active'); ?>
                            </select>
                        </div>
                        <label class="col-lg-2 col-form-label">Investigator <span class="text-danger">*</span></label>
                        <div class="col-lg-4">
                            <select class="form-control select2" name="investigator" id="investigator" <?php echo $disabled; ?>>
                                <option value="">--</option>
                                <?php echo $userModel->generateCIInvestigatorOptions('active'); ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-2 col-form-label">Purpose of Loan</label>
                        <div class="col-lg-4">
                            <textarea class="form-control" id="purpose_of_loan" name="purpose_of_loan" maxlength="3000" rows="3" <?php echo $disabled; ?>></textarea>
                        </div>
                        <label class="col-lg-2 col-form-label">Narrative Summary <span class="text-danger">*</span></label>
                        <div class="col-lg-4">
                            <textarea class="form-control" id="narrative_summary" name="narrative_summary" maxlength="3000" rows="3" <?php echo $disabled; ?>></textarea>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<div class="row">
  <div class="col-lg-12">
    <ul class="nav nav-tabs analytics-tab" id="myTab" role="tablist">
        <li class="nav-item" role="presentation"><button class="nav-link active" id="customer" data-bs-toggle="tab" data-bs-target="#customer-pane" type="button" role="tab" aria-controls="customer-pane" aria-selected="false" tabindex="-1">Customer Details</button></li>
        <li class="nav-item" role="presentation"><button class="nav-link" id="contact-information" data-bs-toggle="tab" data-bs-target="#contact-information-pane" type="button" role="tab" aria-controls="contact-information-pane" aria-selected="false" tabindex="-1">Contact Information</button></li>
        <li class="nav-item" role="presentation"><button class="nav-link" id="residence" data-bs-toggle="tab" data-bs-target="#residence-pane" type="button" role="tab" aria-controls="residence-pane" aria-selected="false" tabindex="-1">Residence</button></li>
        <li class="nav-item" role="presentation"><button class="nav-link" id="dependents" data-bs-toggle="tab" data-bs-target="#dependents-pane" type="button" role="tab" aria-controls="dependents-pane" aria-selected="false" tabindex="-1">Dependents</button></li>
        <li class="nav-item" role="presentation"><button class="nav-link" id="business" data-bs-toggle="tab" data-bs-target="#business-pane" type="button" role="tab" aria-controls="business-pane" aria-selected="false" tabindex="-1">Business</button></li>
        <li class="nav-item" role="presentation"><button class="nav-link" id="employment" data-bs-toggle="tab" data-bs-target="#employment-pane" type="button" role="tab" aria-controls="employment-pane" aria-selected="false" tabindex="-1">Employment</button></li>
        <li class="nav-item" role="presentation"><button class="nav-link" id="banks" data-bs-toggle="tab" data-bs-target="#banks-pane" type="button" role="tab" aria-controls="banks-pane" aria-selected="false" tabindex="-1">Banks</button></li>
        <li class="nav-item" role="presentation"><button class="nav-link" id="loans" data-bs-toggle="tab" data-bs-target="#loans-pane" type="button" role="tab" aria-controls="loans-pane" aria-selected="false" tabindex="-1">Loans</button></li>
        <li class="nav-item" role="presentation"><button class="nav-link" id="assets" data-bs-toggle="tab" data-bs-target="#assets-pane" type="button" role="tab" aria-controls="assets-pane" aria-selected="false" tabindex="-1">Assets</button></li>
        <li class="nav-item" role="presentation"><button class="nav-link" id="cmap" data-bs-toggle="tab" data-bs-target="#cmap-pane" type="button" role="tab" aria-controls="cmap-pane" aria-selected="false" tabindex="-1">CMAP</button></li>
        <li class="nav-item" role="presentation"><button class="nav-link" id="collateral" data-bs-toggle="tab" data-bs-target="#collateral-pane" type="button" role="tab" aria-controls="collateral-pane" aria-selected="false" tabindex="-1">Collateral</button></li>
        <li class="nav-item" role="presentation"><button class="nav-link" id="recommendation-tab" data-bs-toggle="tab" data-bs-target="#recommendation-pane" type="button" role="tab" aria-controls="recommendation-pane" aria-selected="false" tabindex="-1">Recommendation</button></li>
        <li class="nav-item" role="presentation"><button class="nav-link" id="ci-files" data-bs-toggle="tab" data-bs-target="#ci-files-pane" type="button" role="tab" aria-controls="ci-files-pane" aria-selected="false" tabindex="-1">CI Files</button></li>
        <li class="nav-item" role="presentation"><a href="loan-proposal.php?id=<?php echo $ciReportID; ?>" class="nav-link" target="_blank" tabindex="-1">Summary</a></li>
    </ul>
    <div class="tab-content" id="ciTabContent">

        <div class="tab-pane fade active show" id="customer-pane" role="tabpanel" aria-labelledby="customer" tabindex="0">
            <input type="hidden" id="customer-id" value="<?php echo $customer_id; ?>">
            <div class="card">
                <div class="card-body" id="personal-information-summary"></div>
            </div>
        </div>
        
        <div class="tab-pane fade" id="contact-information-pane" role="tabpanel" aria-labelledby="contact-information" tabindex="0">
            <div class="card">
                <div class="card-body">
                    <ul class="list-group list-group-flush" id="contact-information-summary"></ul>
                </div>
            </div>
        </div>

        <div class="tab-pane fade" id="residence-pane" role="tabpanel" aria-labelledby="residence" tabindex="0">
            <div class="row">
                <div class="col-lg-8">
                    <div class="card table-card">
                        <div class="card-header">
                            <div class="row align-items-center">
                                <div class="col-sm-6">
                                    <h5>Residence List</h5>
                                </div>
                                <div class="col-sm-6 text-sm-end mt-3 mt-sm-0">
                                    <?php
                                        if($ci_status == 'Draft' || $ci_status == 'For Completion'){
                                            echo '<button class="btn btn-warning" type="button" data-bs-toggle="modal" data-bs-target="#ci-residence-modal" id="add-ci-report-residence">Add Residence</button>';
                                        }                                        
                                    ?>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive dt-responsive">
                                <table id="ci-residence-table" class="table table-hover nowrap w-100">
                                    <thead>
                                    <tr>
                                        <th>Address</th>
                                        <th>Actions</th>
                                    </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-body py-2">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item px-0">
                                    <h5 class="mb-0">Expense Total</h5>
                                </li>
                                <li class="list-group-item px-0">
                                    <div class="float-end">
                                        <h5 class="mb-0" id="ci-residence-rental-amount-summary">0.00 PHP</h5>
                                    </div>
                                    <span class="text-muted">Rental Amount Total</span>
                                </li>
                                <li class="list-group-item px-0">
                                    <div class="float-end">
                                        <h5 class="mb-0" id="ci-residence-personal-expense-summary">0.00 PHP</h5>
                                    </div>
                                    <span class="text-muted">Personal Expense Total</span>
                                </li>
                                <li class="list-group-item px-0">
                                    <div class="float-end">
                                        <h5 class="mb-0" id="ci-residence-utilities-expense-summary">0.00 PHP</h5>
                                    </div>
                                    <span class="text-muted">Utilities Expense Total</span>
                                </li>
                                <li class="list-group-item px-0">
                                    <div class="float-end">
                                        <h5 class="mb-0" id="ci-residence-other-expense-summary">0.00 PHP</h5>
                                    </div>
                                    <span class="text-muted">Other Expense Total</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-body py-2">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item px-0">
                                    <div class="float-end">
                                        <h5 class="mb-0" id="ci-residence-expense-total-summary">0.00 PHP</h5>
                                    </div>
                                    <h5 class="mb-0 d-inline-block">Total</h5>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="tab-pane fade" id="dependents-pane" role="tabpanel" aria-labelledby="dependents" tabindex="0">
             <div class="row">
                <div class="col-lg-12">
                    <div class="card table-card">
                        <div class="card-header">
                            <div class="row align-items-center">
                                <div class="col-sm-6">
                                    <h5>Dependents List</h5>
                                </div>
                                <div class="col-sm-6 text-sm-end mt-3 mt-sm-0">
                                    <?php
                                        if($ci_status == 'Draft' || $ci_status == 'For Completion'){
                                            echo '<button class="btn btn-warning" type="button" data-bs-toggle="modal" data-bs-target="#ci-dependents-modal" id="add-ci-report-dependents">Add Dependents</button>';
                                        }                                        
                                    ?>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive dt-responsive">
                                <table id="ci-dependents-table" class="table table-hover nowrap w-100">
                                    <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Age</th>
                                        <th>School</th>
                                        <th>Employment</th>
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
        </div>

        <div class="tab-pane fade" id="business-pane" role="tabpanel" aria-labelledby="business" tabindex="0">
             <div class="row">
                <div class="col-lg-7">
                    <div class="card table-card">
                        <div class="card-header">
                            <div class="row align-items-center">
                                <div class="col-sm-6">
                                    <h5>Business List</h5>
                                </div>
                                <div class="col-sm-6 text-sm-end mt-3 mt-sm-0">
                                    <?php
                                        if($ci_status == 'Draft' || $ci_status == 'For Completion'){
                                            echo '<button class="btn btn-warning" type="button" data-bs-toggle="modal" data-bs-target="#ci-business-modal" id="add-ci-report-business">Add Business</button>';
                                        }                                        
                                    ?>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive dt-responsive">
                                <table id="ci-business-table" class="table table-hover nowrap w-100">
                                    <thead>
                                    <tr>
                                        <th>Business Name</th>
                                        <th>Address</th>
                                        <th>Actions</th>
                                    </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-5">
                    <div class="card">
                        <div class="card-body py-2">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item px-0">
                                    <h5 class="mb-0">Financial Total</h5>
                                </li>
                                <li class="list-group-item px-0">
                                    <div class="float-end">
                                        <h5 class="mb-0" id="ci-business-gross-monthly-sales-summary">0.00 PHP</h5>
                                    </div>
                                    <span class="text-muted">Gross Monthly Income Total</span>
                                </li>
                                <li class="list-group-item px-0">
                                    <div class="float-end">
                                        <h5 class="mb-0" id="ci-business-monthly-income-summary">0.00 PHP</h5>
                                    </div>
                                    <span class="text-muted">Monthly Sales Total</span>
                                </li>
                                <li class="list-group-item px-0">
                                    <div class="float-end">
                                        <h5 class="mb-0" id="ci-business-inventory-summary">0.00 PHP</h5>
                                    </div>
                                    <span class="text-muted">Inventory Total</span>
                                </li>
                                <li class="list-group-item px-0">
                                    <div class="float-end">
                                        <h5 class="mb-0" id="ci-business-receivable-summary">0.00 PHP</h5>
                                    </div>
                                    <span class="text-muted">Receivable Total</span>
                                </li>
                                <li class="list-group-item px-0">
                                    <div class="float-end">
                                        <h5 class="mb-0" id="ci-business-fixed-asset-summary">0.00 PHP</h5>
                                    </div>
                                    <span class="text-muted">Asset Total</span>
                                </li>
                                <li class="list-group-item px-0">
                                    <div class="float-end">
                                        <h5 class="mb-0" id="ci-business-liability-summary">0.00 PHP</h5>
                                    </div>
                                    <span class="text-muted">Liability Total</span>
                                </li>
                                <li class="list-group-item px-0">
                                    <div class="float-end">
                                        <h5 class="mb-0" id="ci-business-capital-summary">0.00 PHP</h5>
                                    </div>
                                    <span class="text-muted">Capital Total</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="tab-pane fade" id="employment-pane" role="tabpanel" aria-labelledby="employment" tabindex="0">
            <div class="row">
                <div class="col-lg-8">
                    <div class="card table-card">
                        <div class="card-header">
                            <div class="row align-items-center">
                                <div class="col-sm-6">
                                    <h5>Employment List</h5>
                                </div>
                                <div class="col-sm-6 text-sm-end mt-3 mt-sm-0">
                                    <?php
                                        if($ci_status == 'Draft' || $ci_status == 'For Completion'){
                                            echo '<button class="btn btn-warning" type="button" data-bs-toggle="modal" data-bs-target="#ci-employment-modal" id="add-ci-report-employment">Add Employment</button>';
                                        }                                        
                                    ?>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive dt-responsive">
                                <table id="ci-employment-table" class="table table-hover nowrap w-100">
                                    <thead>
                                    <tr>
                                        <th>Company</th>
                                        <th>Address</th>
                                        <th>Position</th>
                                        <th>Department</th>
                                        <th>Rank</th>
                                        <th>Actions</th>
                                    </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-body py-2">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item px-0">
                                    <h5 class="mb-0">Financial Total</h5>
                                </li>
                                <li class="list-group-item px-0">
                                    <div class="float-end">
                                        <h5 class="mb-0" id="ci-employment-net-salary-summary">0.00 PHP</h5>
                                    </div>
                                    <span class="text-muted">Net Salary Total</span>
                                </li>
                                <li class="list-group-item px-0">
                                    <div class="float-end">
                                        <h5 class="mb-0" id="ci-employment-commission-summary">0.00 PHP</h5>
                                    </div>
                                    <span class="text-muted">Commission Total</span>
                                </li>
                                <li class="list-group-item px-0">
                                    <div class="float-end">
                                        <h5 class="mb-0" id="ci-employment-allowance-summary">0.00 PHP</h5>
                                    </div>
                                    <span class="text-muted">Allowance Total</span>
                                </li>
                                <li class="list-group-item px-0">
                                    <div class="float-end">
                                        <h5 class="mb-0" id="ci-employment-other-income-summary">0.00 PHP</h5>
                                    </div>
                                    <span class="text-muted">Other Income Total</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-body py-2">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item px-0">
                                    <div class="float-end">
                                        <h5 class="mb-0" id="ci-employment-grand-total-summary">0.00 PHP</h5>
                                    </div>
                                    <h5 class="mb-0 d-inline-block">Total</h5>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="tab-pane fade" id="banks-pane" role="tabpanel" aria-labelledby="banks" tabindex="0">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card table-card">
                        <div class="card-header">
                            <div class="row align-items-center">
                                <div class="col-sm-6">
                                    <h5>Bank List</h5>
                                </div>
                                <div class="col-sm-6 text-sm-end mt-3 mt-sm-0">
                                    <?php
                                        if($ci_status == 'Draft' || $ci_status == 'For Completion'){
                                            echo '<button class="btn btn-warning" type="button" data-bs-toggle="modal" data-bs-target="#ci-bank-modal" id="add-ci-report-bank">Add Bank</button>';
                                        }
                                    ?>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive dt-responsive">
                                <table id="ci-bank-table" class="table table-hover nowrap w-100">
                                    <thead>
                                    <tr>
                                        <th>Bank</th>
                                        <th>Bank Account Type</th>
                                        <th>Account Name</th>
                                        <th>Account Number</th>
                                        <th>Currency</th>
                                        <th>Date Open</th>
                                        <th>ADB</th>
                                        <th>Average Deposit</th>
                                        <th>Handling</th>
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

        <div class="tab-pane fade" id="loans-pane" role="tabpanel" aria-labelledby="loans" tabindex="0">
            <div class="row">
                <div class="col-lg-8">
                    <div class="card table-card">
                        <div class="card-header">
                            <div class="row align-items-center">
                                <div class="col-sm-6">
                                    <h5>Loans List</h5>
                                </div>
                                <div class="col-sm-6 text-sm-end mt-3 mt-sm-0">
                                    <?php
                                        if($ci_status == 'Draft' || $ci_status == 'For Completion'){
                                            echo '<button class="btn btn-warning" type="button" data-bs-toggle="modal" data-bs-target="#ci-loan-modal" id="add-ci-report-loan">Add Loan</button>';
                                        }
                                    ?>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive dt-responsive">
                                <table id="ci-loan-table" class="table table-hover nowrap w-100">
                                    <thead>
                                    <tr>
                                        <th>Company</th>
                                        <th>Informant</th>
                                        <th>Account Name/Loan Number</th>
                                        <th>Loan Source</th>
                                        <th>Loan Type</th>
                                        <th>Availed Date</th>
                                        <th>Maturity</th>
                                        <th>Term</th>
                                        <th>PN Amount</th>
                                        <th>Outstanding Balance</th>
                                        <th>Repayment Amount</th>
                                        <th>Handling</th>
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
                
                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-body py-2">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item px-0">
                                    <h5 class="mb-0">Loan Total</h5>
                                </li>
                                <li class="list-group-item px-0">
                                    <div class="float-end">
                                        <h5 class="mb-0" id="ci-loans-pn-amount-summary">0.00 PHP</h5>
                                    </div>
                                    <span class="text-muted">PN Amount Total</span>
                                </li>
                                <li class="list-group-item px-0">
                                    <div class="float-end">
                                        <h5 class="mb-0" id="ci-loans-repayment-summary">0.00 PHP</h5>
                                    </div>
                                    <span class="text-muted">Repayment Total</span>
                                </li>
                                <li class="list-group-item px-0">
                                    <div class="float-end">
                                        <h5 class="mb-0" id="ci-loans-oustanding-balance-summary">0.00 PHP</h5>
                                    </div>
                                    <span class="text-muted">Outstanding Balance Total</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="tab-pane fade" id="assets-pane" role="tabpanel" aria-labelledby="assets" tabindex="0">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card table-card">
                        <div class="card-header">
                            <div class="row align-items-center">
                                <div class="col-sm-6">
                                    <h5>Asset List</h5>
                                </div>
                                <div class="col-sm-6 text-sm-end mt-3 mt-sm-0">
                                    <?php
                                        if($ci_status == 'Draft' || $ci_status == 'For Completion'){
                                            echo '<button class="btn btn-warning" type="button" data-bs-toggle="modal" data-bs-target="#ci-asset-modal" id="add-ci-report-asset">Add Asset</button>';
                                        }
                                    ?>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive dt-responsive">
                                <table id="ci-asset-table" class="table table-hover nowrap w-100">
                                    <thead>
                                    <tr>
                                        <th>Asset Type</th>
                                        <th>Description</th>
                                        <th>Value</th>
                                        <th>Actions</th>
                                    </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-body py-2">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item px-0">
                                    <div class="float-end">
                                        <h5 class="mb-0" id="ci-asset-total-summary">0.00 PHP</h5>
                                    </div>
                                    <h5 class="mb-0 d-inline-block">Total</h5>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="tab-pane fade" id="cmap-pane" role="tabpanel" aria-labelledby="cmap" tabindex="0">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card table-card">
                        <div class="card-header">
                            <div class="row align-items-center">
                                <div class="col-sm-6">
                                    <h5>CMAP List</h5>
                                </div>
                                <div class="col-sm-6 text-sm-end mt-3 mt-sm-0">
                                    <?php
                                        if($ci_status == 'Draft' || $ci_status == 'For Completion'){
                                            echo '<button class="btn btn-warning" type="button" data-bs-toggle="modal" data-bs-target="#ci-cmap-modal" id="add-ci-report-cmap">Add CMAP</button>';
                                        }
                                    ?>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive dt-responsive">
                                <table id="ci-cmap-table" class="table table-hover nowrap w-100">
                                    <thead>
                                    <tr>
                                        <th>Report Type</th>
                                        <th>Defendants</th>
                                        <th>Plaintiff</th>
                                        <th>Nature of Case</th>
                                        <th>Trial Court</th>
                                        <th>SALA No.</th>
                                        <th>Case No.</th>
                                        <th>Report Date</th>
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
        </div>

        <div class="tab-pane fade" id="collateral-pane" role="tabpanel" aria-labelledby="collateral" tabindex="0">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card table-card">
                        <div class="card-header">
                            <div class="row align-items-center">
                                <div class="col-sm-6">
                                    <h5>Collateral List</h5>
                                </div>
                                <div class="col-sm-6 text-sm-end mt-3 mt-sm-0">
                                    <?php
                                        if($ci_status == 'Draft' || $ci_status == 'For Completion'){
                                            echo '<button class="btn btn-warning" type="button" data-bs-toggle="modal" data-bs-target="#ci-collateral-modal" id="add-ci-report-collateral">Add Collateral</button>';
                                        }
                                    ?>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive dt-responsive">
                                <table id="ci-collateral-table" class="table table-hover nowrap w-100">
                                    <thead>
                                    <tr>
                                        <th>Appraisal Date</th>
                                        <th>Brand</th>
                                        <th>Appraised Value</th>
                                        <th>Loanable Value</th>
                                        <th>Color</th>
                                        <th>Year Model</th>
                                        <th>Plate No.</th>
                                        <th>Motor No.</th>
                                        <th>Serial No.</th>
                                        <th>MVR File No.</th>
                                        <th>CR No.</th>
                                        <th>OR No.</th>
                                        <th>Registered Owner</th>
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
        </div>

        <div class="tab-pane fade" id="ci-files-pane" role="tabpanel" aria-labelledby="ci-files" tabindex="0">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card table-card">
                        <div class="card-header">
                            <div class="row align-items-center">
                                <div class="col-sm-6">
                                    <h5>Files List</h5>
                                </div>
                                <div class="col-sm-6 text-sm-end mt-3 mt-sm-0">
                                    <?php
                                        if($ci_status == 'Draft' || $ci_status == 'For Completion'){
                                            echo '<button class="btn btn-warning" type="button" data-bs-toggle="modal" data-bs-target="#ci-files-modal" id="add-ci-report-files">Add Files</button>';
                                        }
                                    ?>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive dt-responsive">
                                <table id="ci-files-table" class="table table-hover nowrap w-100">
                                    <thead>
                                    <tr>
                                        <th>File Type</th>
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
        </div>

        <div class="tab-pane fade" id="recommendation-pane" role="tabpanel" aria-labelledby="ci-recommendation" tabindex="0">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="row align-items-center">
                                <div class="col-sm-6">
                                    <h5>Recommendation</h5>
                                </div>
                                <div class="col-sm-6 text-sm-end mt-3 mt-sm-0">
                                    <?php
                                        if ($ciReportWriteAccess['total'] > 0 && ($ci_status == 'Draft' || $ci_status == 'For Completion')) {
                                            echo '<button type="submit" form="ci-report-recommendation-form" class="btn btn-success" id="submit-recommendation-data">Save</button>';
                                        }
                                    ?>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <form id="ci-report-recommendation-form" method="post" action="#">
                                <div class="form-group row">
                                    <label class="col-lg-2 col-form-label">Character <span class="text-danger">*</span></label>
                                    <div class="col-lg-4">
                                        <select class="form-control select2" name="ci_character" id="ci_character" <?php echo $disabled; ?>>
                                            <option value="">--</option>
                                            <option value="Passed">Passed</option>
                                            <option value="Failed">Failed</option>
                                        </select>
                                    </div>
                                    <label class="col-lg-2 col-form-label">Capacity <span class="text-danger">*</span></label>
                                    <div class="col-lg-4">
                                        <select class="form-control select2" name="ci_capacity" id="ci_capacity" <?php echo $disabled; ?>>
                                            <option value="">--</option>
                                            <option value="Passed">Passed</option>
                                            <option value="Failed">Failed</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-lg-2 col-form-label">Capital <span class="text-danger">*</span></label>
                                    <div class="col-lg-4">
                                        <select class="form-control select2" name="ci_capital" id="ci_capital" <?php echo $disabled; ?>>
                                            <option value="">--</option>
                                            <option value="Passed">Passed</option>
                                            <option value="Failed">Failed</option>
                                        </select>
                                    </div>
                                    <label class="col-lg-2 col-form-label">Collateral <span class="text-danger">*</span></label>
                                    <div class="col-lg-4">
                                        <select class="form-control select2" name="ci_collateral" id="ci_collateral" <?php echo $disabled; ?>>
                                            <option value="">--</option>
                                            <option value="Passed">Passed</option>
                                            <option value="Failed">Failed</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-lg-2 col-form-label">Condition <span class="text-danger">*</span></label>
                                    <div class="col-lg-4">
                                        <select class="form-control select2" name="ci_condition" id="ci_condition" <?php echo $disabled; ?>>
                                            <option value="">--</option>
                                            <option value="Passed">Passed</option>
                                            <option value="Failed">Failed</option>
                                        </select>
                                    </div>
                                    <label class="col-lg-2 col-form-label">Acceptability <span class="text-danger">*</span></label>
                                    <div class="col-lg-4">
                                        <select class="form-control select2" name="acceptability" id="acceptability" <?php echo $disabled; ?>>
                                            <option value="">--</option>
                                            <option value="Acceptable">Acceptable</option>
                                            <option value="Not Acceptable">Not Acceptable</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-lg-2 col-form-label">Loanability <span class="text-danger">*</span></label>
                                    <div class="col-lg-4">
                                        <select class="form-control select2" name="loanability" id="loanability" <?php echo $disabled; ?>>
                                            <option value="">--</option>
                                            <option value="Within Loanable Amount">Within Loanable Amount</option>
                                            <option value="Not Within Loanable Amount">Not Within Loanable Amount</option>
                                        </select>
                                    </div>
                                    <label class="col-lg-2 col-form-label">CMAP Result <span class="text-danger">*</span></label>
                                    <div class="col-lg-4">
                                        <select class="form-control select2" name="cmap_result" id="cmap_result" <?php echo $disabled; ?>>
                                            <option value="">--</option>
                                            <option value="Positive">Positive</option>
                                            <option value="Negative">Negative</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-lg-2 col-form-label">CRIF Result <span class="text-danger">*</span></label>
                                    <div class="col-lg-4">
                                        <select class="form-control select2" name="crif_result" id="crif_result" <?php echo $disabled; ?>>
                                            <option value="">--</option>
                                            <option value="Positive">Positive</option>
                                            <option value="Negative">Negative</option>
                                        </select>
                                    </div>
                                    <label class="col-lg-2 col-form-label">Adverse <span class="text-danger">*</span></label>
                                    <div class="col-lg-4">
                                        <select class="form-control select2" name="adverse" id="adverse" <?php echo $disabled; ?>>
                                            <option value="">--</option>
                                            <option value="Adverse">Adverse</option>
                                            <option value="Nothing Adverse">Nothing Adverse</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-lg-2 col-form-label">Times Accomodated <span class="text-danger">*</span></label>
                                    <div class="col-lg-4">
                                        <input type="number" class="form-control" id="times_accomodated" name="times_accomodated" min="0" step="1" <?php echo $disabled; ?>>
                                    </div>
                                    <label class="col-lg-2 col-form-label">CGMI Client Since <span class="text-danger">*</span></label>
                                    <div class="col-lg-4">
                                       <input type="text" class="form-control" id="cgmi_client_since" name="cgmi_client_since" maxlength="100" autocomplete="off" <?php echo $disabled; ?>>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-lg-2 col-form-label">Recommendation <span class="text-danger">*</span></label>
                                    <div class="col-lg-10">
                                        <textarea class="form-control" id="recommendation" name="recommendation" maxlength="3000" rows="3" <?php echo $disabled; ?>></textarea>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
  </div>
</div>

<div class="modal fade modal-animate" id="ci-residence-modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Residence</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="ci-residence-form" method="post" action="#">
                    <input type="hidden" id="ci_report_residence_id" name="ci_report_residence_id">
                    <div class="form-group row">
                        <div class="col-lg-12">
                            <h5>Current Address</h5>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-6">
                            <label class="form-label" for="ci_residence_contact_address">Address <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="ci_residence_contact_address" name="ci_residence_contact_address" maxlength="1000" autocomplete="off" <?php echo $disabled; ?>>
                        </div>
                        <div class="col-lg-6">
                            <label class="form-label" for="ci_residence_city_id">City <span class="text-danger">*</span></label>
                            <select class="form-control modal-select2" name="ci_residence_city_id" id="ci_residence_city_id" <?php echo $disabled; ?>>
                                <option value="">--</option>
                                <?php echo $cityModel->generateCityOptions() ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-12">
                            <h5>Previous Address</h5>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-6">
                            <label class="form-label" for="ci_residence_prev_address">Previous Address</label>
                            <input type="text" class="form-control" id="ci_residence_prev_address" name="ci_residence_prev_address" maxlength="1000" autocomplete="off" <?php echo $disabled; ?>>
                        </div>
                        <div class="col-lg-6">
                            <label class="form-label" for="ci_residence_prev_city_id">Previous City</label>
                            <select class="form-control modal-select2" name="ci_residence_prev_city_id" id="ci_residence_prev_city_id" <?php echo $disabled; ?>>
                                <option value="">--</option>
                                <?php echo $cityModel->generateCityOptions() ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-12">
                            <h5>Duration of Stay</h5>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-6">
                            <label class="form-label" for="ci_residence_length_stay_year">Length Stay (Year)</label>
                            <input type="number" class="form-control" id="ci_residence_length_stay_year" name="ci_residence_length_stay_year" min="0" step="1" <?php echo $disabled; ?>>
                        </div>
                        <div class="col-lg-6">
                            <label class="form-label" for="ci_residence_length_stay_month">Length Stay (Month)</label>
                            <input type="number" class="form-control" id="ci_residence_length_stay_month" name="ci_residence_length_stay_month" min="0" step="1" <?php echo $disabled; ?>>
                        </div>
                    </div>
                    
                    <div class="form-group row">
                        <div class="col-lg-12">
                            <h5>Residence Details</h5>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-4">
                            <label class="form-label" for="ci_residence_residence_type_id">Residence Type</label>
                            <select class="form-control modal-select2" name="ci_residence_residence_type_id" id="ci_residence_residence_type_id" <?php echo $disabled; ?>>
                                <option value="">--</option>
                                <?php echo $residenceTypeModel->generateResidenceTypeOptions() ?>
                            </select>
                        </div>
                        <div class="col-lg-4">
                            <label class="form-label" for="ci_residence_rented_from">Rented From</label>
                            <input type="text" class="form-control" id="ci_residence_rented_from" name="ci_residence_rented_from" maxlength="1000" autocomplete="off" <?php echo $disabled; ?>>
                        </div>
                        <div class="col-lg-4">
                            <label class="form-label" for="ci_residence_rent_amount">Rent Amount</label>
                            <input type="number" class="form-control" id="ci_residence_rent_amount" name="ci_residence_rent_amount" min="0" step="0.01" <?php echo $disabled; ?>>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-4">
                            <label class="form-label" for="ci_residence_estimated_value">Estimated Value</label>
                            <input type="number" class="form-control" id="ci_residence_estimated_value" name="ci_residence_estimated_value" min="0" step="0.01" <?php echo $disabled; ?>>
                        </div>
                        <div class="col-lg-4">
                            <label class="form-label" for="ci_residence_structure_type_id">Ownership Type</label>
                            <select class="form-control modal-select2" name="ci_residence_structure_type_id" id="ci_residence_structure_type_id" <?php echo $disabled; ?>>
                                <option value="">--</option>
                                <?php echo $structureTypeModel->generateStructureTypeOptions() ?>
                            </select>
                        </div>
                        <div class="col-lg-4">
                            <label class="form-label" for="ci_residence_tct_no">TCT No.</label>
                            <input type="text" class="form-control" id="ci_residence_tct_no" name="ci_residence_tct_no" maxlength="100" autocomplete="off" <?php echo $disabled; ?>>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-4">
                            <label class="form-label" for="ci_residence_residence_age">Residence Age</label>
                            <input type="number" class="form-control" id="ci_residence_residence_age" name="ci_residence_residence_age" min="0" step="1" <?php echo $disabled; ?>>
                        </div>
                        <div class="col-lg-4">
                            <label class="form-label" for="ci_residence_building_make_id">Building Make</label>
                            <select class="form-control modal-select2" name="ci_residence_building_make_id" id="ci_residence_building_make_id" <?php echo $disabled; ?>>
                                <option value="">--</option>
                                <?php echo $buildingMakeModel->generateBuildingMakeOptions() ?>
                            </select>
                        </div>
                        <div class="col-lg-4">
                            <label class="form-label" for="ci_residence_lot_area">Lot Area (SQM)</label>
                            <input type="number" class="form-control" id="ci_residence_lot_area" name="ci_residence_lot_area" min="0" step="0.01" <?php echo $disabled; ?>>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-4">
                            <label class="form-label" for="ci_residence_floor_area">Floor Area (SQM)</label>
                            <input type="number" class="form-control" id="ci_residence_floor_area" name="ci_residence_floor_area" min="0" step="0.01" <?php echo $disabled; ?>>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-4">
                            <label class="form-label" for="ci_residence_furnishing_appliance">Furnishing Appliance</label>
                            <textarea class="form-control" id="ci_residence_furnishing_appliance" name="ci_residence_furnishing_appliance" maxlength="3000" rows="3" <?php echo $disabled; ?>></textarea>
                        </div>
                        <div class="col-lg-4">
                            <label class="form-label" for="ci_residence_vehicle_owned">Vehicle Owned</label>
                            <textarea class="form-control" id="ci_residence_vehicle_owned" name="ci_residence_vehicle_owned" maxlength="3000" rows="3" <?php echo $disabled; ?>></textarea>
                        </div>
                        <div class="col-lg-4">
                            <label class="form-label" for="ci_residence_real_estate_owned">Real Estate Owned</label>
                            <textarea class="form-control" id="ci_residence_real_estate_owned" name="ci_residence_real_estate_owned" maxlength="3000" rows="3" <?php echo $disabled; ?>></textarea>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-12">
                            <h5>Neighborhood Information</h5>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-lg-4">
                            <label class="form-label" for="ci_residence_neighborhood_type_id">Neighborhood Type</label>
                            <select class="form-control modal-select2" name="ci_residence_neighborhood_type_id" id="ci_residence_neighborhood_type_id" <?php echo $disabled; ?>>
                                <option value="">--</option>
                                <?php echo $neighborhoodTypeModel->generateNeighborhoodTypeOptions() ?>
                            </select>
                        </div>
                        <div class="col-lg-4">
                            <label class="form-label" for="ci_residence_accessible_to">Accessible To</label>
                            <input type="text" class="form-control" id="ci_residence_accessible_to" name="ci_residence_accessible_to" maxlength="1000" autocomplete="off" <?php echo $disabled; ?>>
                        </div>
                        <div class="col-lg-4">
                            <label class="form-label" for="ci_residence_nearest_corner">Nearest Corner</label>
                            <input type="text" class="form-control" id="ci_residence_nearest_corner" name="ci_residence_nearest_corner" maxlength="1000" autocomplete="off" <?php echo $disabled; ?>>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-lg-4">
                            <label class="form-label" for="ci_residence_income_level_id">Income Level</label>
                            <select class="form-control modal-select2" name="ci_residence_income_level_id" id="ci_residence_income_level_id" <?php echo $disabled; ?>>
                                <option value="">--</option>
                                <?php echo $incomeLevelModel->generateIncomeLevelOptions() ?>
                            </select>
                        </div>
                        <div class="col-lg-4">
                            <label class="form-label" for="ci_residence_informant">Informant</label>
                            <input type="text" class="form-control" id="ci_residence_informant" name="ci_residence_informant" maxlength="1000" autocomplete="off" <?php echo $disabled; ?>>
                        </div>
                        <div class="col-lg-4">
                            <label class="form-label" for="ci_residence_informant_address">Informant Address</label>
                            <input type="text" class="form-control" id="ci_residence_informant_address" name="ci_residence_informant_address" maxlength="1000" autocomplete="off" <?php echo $disabled; ?>>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-lg-12">
                            <h5>Household Expenses</h5>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-4">
                            <label class="form-label" for="ci_residence_personal_expense">Personal Expense</label>
                            <input type="number" class="form-control" id="ci_residence_personal_expense" name="ci_residence_personal_expense" min="0" step="0.01" <?php echo $disabled; ?>>
                        </div>
                        <div class="col-lg-4">
                            <label class="form-label" for="ci_residence_utilities_expense">Utilities Expense</label>
                            <input type="number" class="form-control" id="ci_residence_utilities_expense" name="ci_residence_utilities_expense" min="0" step="0.01" <?php echo $disabled; ?>>
                        </div>
                        <div class="col-lg-4">
                            <label class="form-label" for="ci_residence_other_expense">Other Expense</label>
                            <input type="number" class="form-control" id="ci_residence_other_expense" name="ci_residence_other_expense" min="0" step="0.01" <?php echo $disabled; ?>>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-12">
                            <label class="form-label" for="ci_residence_total_expense">Total</label>
                            <input type="number" class="form-control" id="ci_residence_total_expense" name="ci_residence_total_expense" min="0" step="0.01" readonly>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-lg-12">
                            <h5>Other Information</h5>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-12">
                            <label class="form-label" for="ci_residence_remarks">Remarks</label>
                            <textarea class="form-control" id="ci_residence_remarks" name="ci_residence_remarks" maxlength="3000" rows="3" <?php echo $disabled; ?>></textarea>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <?php
                    if($ci_status == 'Draft' || $ci_status == 'For Completion'){
                        echo '<button type="submit" class="btn btn-primary" id="submit-ci-residence" form="ci-residence-form">Submit</button>';
                    }
                ?>
            </div>
        </div>
    </div>
</div>

<div class="modal fade modal-animate" id="ci-dependents-modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Dependents</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="ci-dependents-form" method="post" action="#">
                    <input type="hidden" id="ci_report_dependents_id" name="ci_report_dependents_id">
                    <div class="form-group row">
                        <div class="col-lg-6">
                            <label class="form-label" for="ci_dependents_name">Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="ci_dependents_name" name="ci_dependents_name" maxlength="500" autocomplete="off" <?php echo $disabled; ?>>
                        </div>
                        <div class="col-lg-6">
                            <label class="form-label" for="ci_dependents_age">Age</label>
                            <input type="number" class="form-control" id="ci_dependents_age" name="ci_dependents_age" min="0" step="1" <?php echo $disabled; ?>>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-6">
                            <label class="form-label" for="ci_dependents_school">School</label>
                            <input type="text" class="form-control" id="ci_dependents_school" name="ci_dependents_school" maxlength="500" autocomplete="off" <?php echo $disabled; ?>>
                        </div>
                        <div class="col-lg-6">
                            <label class="form-label" for="ci_dependents_employment">Employment</label>
                            <input type="text" class="form-control" id="ci_dependents_employment" name="ci_dependents_employment" maxlength="500" autocomplete="off" <?php echo $disabled; ?>>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-12">
                            <label class="form-label" for="ci_dependents_remarks">Remarks</label>
                            <textarea class="form-control" id="ci_dependents_remarks" name="ci_dependents_remarks" maxlength="3000" rows="3" <?php echo $disabled; ?>></textarea>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <?php
                    if($ci_status == 'Draft' || $ci_status == 'For Completion'){
                        echo '<button type="submit" class="btn btn-primary" id="submit-ci-dependents" form="ci-dependents-form">Submit</button>';
                    }
                ?>
            </div>
        </div>
    </div>
</div>

<div class="modal fade modal-animate" id="ci-business-modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Business</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="ci-business-form" method="post" action="#">
                    <input type="hidden" id="ci_report_business_id" name="ci_report_business_id">
                    <div class="form-group row">
                        <div class="col-lg-12">
                            <h5>Business Details</h5>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-6">
                            <label class="form-label" for="ci_business_business_name">Business Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="ci_business_business_name" name="ci_business_business_name" maxlength="1000" autocomplete="off" <?php echo $disabled; ?>>
                        </div>
                        <div class="col-lg-6">
                            <label class="form-label" for="ci_business_description">Description <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="ci_business_description" name="ci_business_description" maxlength="1000" autocomplete="off" <?php echo $disabled; ?>>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-6">
                            <label class="form-label" for="ci_business_contact_address">Address <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="ci_business_contact_address" name="ci_business_contact_address" maxlength="1000" autocomplete="off" <?php echo $disabled; ?>>
                        </div>
                        <div class="col-lg-6">
                            <label class="form-label" for="ci_business_city_id">City <span class="text-danger">*</span></label>
                            <select class="form-control modal-select2" name="ci_business_city_id" id="ci_business_city_id" <?php echo $disabled; ?>>
                                <option value="">--</option>
                                <?php echo $cityModel->generateCityOptions() ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-12">
                            <h5>Duration of Stay</h5>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-6">
                            <label class="form-label" for="ci_business_length_stay_year">Existence (Year)</label>
                            <input type="number" class="form-control" id="ci_business_length_stay_year" name="ci_business_length_stay_year" min="0" step="1" <?php echo $disabled; ?>>
                        </div>
                        <div class="col-lg-6">
                            <label class="form-label" for="ci_business_length_stay_month">Existence (Month)</label>
                            <input type="number" class="form-control" id="ci_business_length_stay_month" name="ci_business_length_stay_month" min="0" step="1" <?php echo $disabled; ?>>
                        </div>
                    </div>
                                        
                    <div class="form-group row">
                        <div class="col-lg-12">
                            <h5>History/Operation</h5>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-4">
                            <label class="form-label" for="ci_business_registered_with">Registered With</label>
                            <input type="text" class="form-control" id="ci_business_registered_with" name="ci_business_registered_with" maxlength="1000" autocomplete="off" <?php echo $disabled; ?>>
                        </div>
                        <div class="col-lg-4">
                            <label class="form-label" for="ci_business_organization">Organization</label>
                            <input type="text" class="form-control" id="ci_business_organization" name="ci_business_organization" maxlength="1000" autocomplete="off" <?php echo $disabled; ?>>
                        </div>
                        <div class="col-lg-4">
                            <label class="form-label" for="ci_business_date_organized">Date Organized</label>
                            <input type="text" class="form-control" id="ci_business_date_organized" name="ci_business_date_organized" maxlength="100" autocomplete="off" <?php echo $disabled; ?>>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-4">
                            <label class="form-label" for="ci_business_no_employee">No. Employees</label>
                            <input type="number" class="form-control" id="ci_business_no_employee" name="ci_business_no_employee" min="0" step="1" <?php echo $disabled; ?>>
                        </div>
                        <div class="col-lg-4">
                            <label class="form-label" for="ci_business_customer">Customer</label>
                            <input type="text" class="form-control" id="ci_business_customer" name="ci_business_customer" maxlength="1000" autocomplete="off" <?php echo $disabled; ?>>
                        </div>
                        <div class="col-lg-4">
                            <label class="form-label" for="ci_business_major_bank_id">Major Bank</label>
                            <input type="text" class="form-control" id="ci_business_major_bank_id" name="ci_business_major_bank_id" maxlength="1000" autocomplete="off" <?php echo $disabled; ?>>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-4">
                            <label class="form-label" for="ci_business_contact_person">Contact Person</label>
                            <input type="text" class="form-control" id="ci_business_contact_person" name="ci_business_contact_person" maxlength="1000" autocomplete="off" <?php echo $disabled; ?>>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-lg-12">
                            <h5>Facilities</h5>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-4">
                            <label class="form-label" for="ci_business_business_location_type_id">Business Location</label>
                            <select class="form-control modal-select2" name="ci_business_business_location_type_id" id="ci_business_business_location_type_id" <?php echo $disabled; ?>>
                                <option value="">--</option>
                                <?php echo $businessLocationTypeModel->generateBusinessLocationTypeOptions() ?>
                            </select>
                        </div>
                        <div class="col-lg-4">
                            <label class="form-label" for="ci_business_building_make_id">Building Make</label>
                            <select class="form-control modal-select2" name="ci_business_building_make_id" id="ci_business_building_make_id" <?php echo $disabled; ?>>
                                <option value="">--</option>
                                <?php echo $buildingMakeModel->generateBuildingMakeOptions() ?>
                            </select>
                        </div>
                        <div class="col-lg-4">
                            <label class="form-label" for="ci_business_business_premises_id">Business Premises</label>
                            <select class="form-control modal-select2" name="ci_business_business_premises_id" id="ci_business_business_premises_id" <?php echo $disabled; ?>>
                                <option value="">--</option>
                                <?php echo $businessPremisesModel->generateBusinessPremisesOptions() ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-4">
                            <label class="form-label" for="ci_business_landlord">Landlord</label>
                            <input type="text" class="form-control" id="ci_business_landlord" name="ci_business_landlord" maxlength="1000" autocomplete="off" <?php echo $disabled; ?>>
                        </div>
                        <div class="col-lg-4">
                            <label class="form-label" for="ci_business_rental_amount">Rental</label>
                            <input type="number" class="form-control" id="ci_business_rental_amount" name="ci_business_rental_amount" min="0" step="0.01" <?php echo $disabled; ?>>
                        </div>
                        <div class="col-lg-4">
                            <label class="form-label" for="ci_business_machineries">Machineries</label>
                            <input type="text" class="form-control" id="ci_business_machineries" name="ci_business_machineries" maxlength="1000" autocomplete="off" <?php echo $disabled; ?>>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-4">
                            <label class="form-label" for="ci_business_fixtures">Fixtures</label>
                            <input type="text" class="form-control" id="ci_business_fixtures" name="ci_business_fixtures" maxlength="1000" autocomplete="off" <?php echo $disabled; ?>>
                        </div>
                        <div class="col-lg-4">
                            <label class="form-label" for="ci_business_facility_condition">Facility Condition</label>
                            <select class="form-control modal-select2" name="ci_business_facility_condition" id="ci_business_facility_condition" <?php echo $disabled; ?>>
                                <option value="">--</option>
                                <option value="Poor">Poor</option>
                                <option value="Good">Good</option>
                                <option value="Average">Average</option>
                                <option value="Very Good">Very Good</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-4">
                            <label class="form-label" for="ci_business_branches">Branch</label>
                            <textarea class="form-control" id="ci_business_branches" name="ci_business_branches" maxlength="3000" rows="3" <?php echo $disabled; ?>></textarea>
                        </div>
                        <div class="col-lg-4">
                            <label class="form-label" for="ci_business_vehicle">Vehicle</label>
                            <textarea class="form-control" id="ci_business_vehicle" name="ci_business_vehicle" maxlength="3000" rows="3" <?php echo $disabled; ?>></textarea>
                        </div>
                        <div class="col-lg-4">
                            <label class="form-label" for="ci_business_trade_reference">Trade Reference</label>
                            <textarea class="form-control" id="ci_business_trade_reference" name="ci_business_trade_reference" maxlength="3000" rows="3" <?php echo $disabled; ?>></textarea>
                        </div>
                    </div>
                           
                    <div class="form-group row">
                        <div class="col-lg-12">
                            <h5>Financial</h5>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-3">
                            <label class="form-label" for="ci_business_gross_monthly_sale">Gross Monthly Income</label>
                            <input type="number" class="form-control" id="ci_business_gross_monthly_sale" name="ci_business_gross_monthly_sale" min="0" step="0.01" <?php echo $disabled; ?>>
                        </div>
                        <div class="col-lg-3">
                            <label class="form-label" for="ci_business_monthly_income">Monthly Sales</label>
                            <input type="number" class="form-control" id="ci_business_monthly_income" name="ci_business_monthly_income" min="0" step="0.01" <?php echo $disabled; ?>>
                        </div>
                        <div class="col-lg-3">
                            <label class="form-label" for="ci_business_inventory">Inventory</label>
                            <input type="number" class="form-control" id="ci_business_inventory" name="ci_business_inventory" min="0" step="0.01" <?php echo $disabled; ?>>
                        </div>
                        <div class="col-lg-3">
                            <label class="form-label" for="ci_business_receivable">Receivable</label>
                            <input type="number" class="form-control" id="ci_business_receivable" name="ci_business_receivable" min="0" step="0.01" <?php echo $disabled; ?>>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-3">
                            <label class="form-label" for="ci_business_fixed_asset">Asset</label>
                            <input type="number" class="form-control" id="ci_business_fixed_asset" name="ci_business_fixed_asset" min="0" step="0.01" <?php echo $disabled; ?>>
                        </div>
                        <div class="col-lg-3">
                            <label class="form-label" for="ci_business_liabilities">Liabilities</label>
                            <input type="number" class="form-control" id="ci_business_liabilities" name="ci_business_liabilities" min="0" step="0.01" <?php echo $disabled; ?>>
                        </div>
                        <div class="col-lg-3">
                            <label class="form-label" for="ci_business_capital">Capital</label>
                            <input type="number" class="form-control" id="ci_business_capital" name="ci_business_capital" step="0.01" readonly>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-lg-12">
                            <h5>Other Information</h5>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-12">
                            <label class="form-label" for="ci_business_remarks">Remarks</label>
                            <textarea class="form-control" id="ci_business_remarks" name="ci_business_remarks" maxlength="3000" rows="3" <?php echo $disabled; ?>></textarea>
                        </div>
                    </div>
                    <div class="row align-items-center trade-reference-hide d-none">
                        <div class="col-sm-6">
                            <h5>Trade Reference</h5>
                        </div>
                        <div class="col-sm-6 text-sm-end mb-3">
                            <?php
                                if($ci_status == 'Draft' || $ci_status == 'For Completion'){
                                    echo '<button class="btn btn-warning" type="button" data-bs-toggle="modal" data-bs-target="#ci-trade-reference-modal" id="add-ci-report-trade-reference">Add Trade Reference</button>';
                                }
                            ?>
                        </div>
                    </div>
                    <div class="form-group row trade-reference-hide d-none">
                        <div class="col-lg-12">
                            <div class="table-responsive dt-responsive">
                                <table id="ci-trade-reference-table" class="table table-hover nowrap w-100">
                                    <thead>
                                    <tr>
                                        <th>Supplier</th>
                                        <th>Contact Person</th>
                                        <th>Years of Transaction</th>
                                        <th>Remarks</th>
                                        <th>Actions</th>
                                    </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <?php
                    if($ci_status == 'Draft' || $ci_status == 'For Completion'){
                        echo '<button type="submit" class="btn btn-primary" id="submit-ci-business" form="ci-business-form">Submit</button>';
                    }
                ?>
            </div>
        </div>
    </div>
</div>

<div class="modal fade modal-animate" id="ci-employment-modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Employment</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="ci-employment-form" method="post" action="#">
                    <input type="hidden" id="ci_report_employment_id" name="ci_report_employment_id">
                    <div class="form-group row">
                        <div class="col-lg-12">
                            <h5>Employment Details</h5>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-6">
                            <label class="form-label" for="ci_employment_employment_name">Employment Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="ci_employment_employment_name" name="ci_employment_employment_name" maxlength="1000" autocomplete="off" <?php echo $disabled; ?>>
                        </div>
                        <div class="col-lg-6">
                            <label class="form-label" for="ci_employment_description">Description <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="ci_employment_description" name="ci_employment_description" maxlength="1000" autocomplete="off" <?php echo $disabled; ?>>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-6">
                            <label class="form-label" for="ci_employment_contact_address">Address <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="ci_employment_contact_address" name="ci_employment_contact_address" maxlength="1000" autocomplete="off" <?php echo $disabled; ?>>
                        </div>
                        <div class="col-lg-6">
                            <label class="form-label" for="ci_employment_city_id">City <span class="text-danger">*</span></label>
                            <select class="form-control modal-select2" name="ci_employment_city_id" id="ci_employment_city_id" <?php echo $disabled; ?>>
                                <option value="">--</option>
                                <?php echo $cityModel->generateCityOptions() ?>
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-group row">
                        <div class="col-lg-6">
                            <label class="form-label" for="ci_employment_department">Department</label>
                            <input type="text" class="form-control" id="ci_employment_department" name="ci_employment_department" maxlength="100" autocomplete="off" <?php echo $disabled; ?>>
                        </div>
                        <div class="col-lg-6">
                            <label class="form-label" for="ci_employment_rank">Rank</label>
                            <input type="text" class="form-control" id="ci_employment_rank" name="ci_employment_rank" maxlength="1000" autocomplete="off" <?php echo $disabled; ?>>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-6">
                            <label class="form-label" for="ci_employment_position">Position</label>
                            <input type="text" class="form-control" id="ci_employment_position" name="ci_employment_position" maxlength="100" autocomplete="off" <?php echo $disabled; ?>>
                        </div>
                        <div class="col-lg-6">
                            <label class="form-label" for="ci_employment_status">Status</label>
                            <input type="text" class="form-control" id="ci_employment_status" name="ci_employment_status" maxlength="100" autocomplete="off" <?php echo $disabled; ?>>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-lg-12">
                            <h5>Duration of Stay</h5>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-6">
                            <label class="form-label" for="ci_employment_length_stay_year">Length Stay (Year)</label>
                            <input type="number" class="form-control" id="ci_employment_length_stay_year" name="ci_employment_length_stay_year" min="0" step="1" <?php echo $disabled; ?>>
                        </div>
                        <div class="col-lg-6">
                            <label class="form-label" for="ci_employment_length_stay_month">Length Stay (Month)</label>
                            <input type="number" class="form-control" id="ci_employment_length_stay_month" name="ci_employment_length_stay_month" min="0" step="1" <?php echo $disabled; ?>>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-6">
                            <label class="form-label" for="ci_employment_pres_length_stay_year">Present Position (Year)</label>
                            <input type="number" class="form-control" id="ci_employment_pres_length_stay_year" name="ci_employment_pres_length_stay_year" min="0" step="1" <?php echo $disabled; ?>>
                        </div>
                        <div class="col-lg-6">
                            <label class="form-label" for="ci_employment_pres_length_stay_month">Present Position (Month)</label>
                            <input type="number" class="form-control" id="ci_employment_pres_length_stay_month" name="ci_employment_pres_length_stay_month" min="0" step="1" <?php echo $disabled; ?>>
                        </div>
                    </div>
                                        
                    <div class="form-group row">
                        <div class="col-lg-6">
                            <label class="form-label" for="ci_employment_informant">Informant</label>
                            <input type="text" class="form-control" id="ci_employment_informant" name="ci_employment_informant" maxlength="1000" autocomplete="off" <?php echo $disabled; ?>>
                        </div>
                        <div class="col-lg-6">
                            <label class="form-label" for="ci_employment_informant_address">Informant Address</label>
                            <input type="text" class="form-control" id="ci_employment_informant_address" name="ci_employment_informant_address" maxlength="1000" autocomplete="off" <?php echo $disabled; ?>>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-lg-12">
                            <h5>Financial</h5>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-lg-3">
                            <label class="form-label" for="ci_employment_net_salary">Net Salary</label>
                            <input type="number" class="form-control" id="ci_employment_net_salary" name="ci_employment_net_salary" min="0" step="0.01" <?php echo $disabled; ?>>
                        </div>
                        <div class="col-lg-3">
                            <label class="form-label" for="ci_employment_commission">Commission</label>
                            <input type="number" class="form-control" id="ci_employment_commission" name="ci_employment_commission" min="0" step="0.01" <?php echo $disabled; ?>>
                        </div>
                        <div class="col-lg-3">
                            <label class="form-label" for="ci_employment_allowance">Allowance</label>
                            <input type="number" class="form-control" id="ci_employment_allowance" name="ci_employment_allowance" min="0" step="0.01" <?php echo $disabled; ?>>
                        </div>
                        <div class="col-lg-3">
                            <label class="form-label" for="ci_employment_other_income">Other Income</label>
                            <input type="number" class="form-control" id="ci_employment_other_income" name="ci_employment_other_income" min="0" step="0.01" <?php echo $disabled; ?>>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-12">
                            <label class="form-label" for="ci_employment_grand_total">Grand Total</label>
                            <input type="number" class="form-control" id="ci_employment_grand_total" name="ci_employment_grand_total" min="0" step="0.01" readonly>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-lg-12">
                            <h5>Other Information</h5>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-12">
                            <label class="form-label" for="ci_employment_remarks">Remarks</label>
                            <textarea class="form-control" id="ci_employment_remarks" name="ci_employment_remarks" maxlength="3000" rows="3" <?php echo $disabled; ?>></textarea>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <?php
                    if($ci_status == 'Draft' || $ci_status == 'For Completion'){
                        echo '<button type="submit" class="btn btn-primary" id="submit-ci-employment" form="ci-employment-form">Submit</button>';
                    }
                ?>
            </div>
        </div>
    </div>
</div>

<div class="modal fade modal-animate" id="ci-bank-modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Bank Branch</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="ci-bank-form" method="post" action="#">
                    <input type="hidden" id="ci_report_bank_id" name="ci_report_bank_id">
                    <div class="form-group row">
                        <div class="col-lg-4">
                            <label class="form-label" for="ci_bank_bank_id">Bank <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="ci_bank_bank_id" name="ci_bank_bank_id" maxlength="500" autocomplete="off" <?php echo $disabled; ?>>
                        </div>
                        <div class="col-lg-4">
                            <label class="form-label" for="ci_bank_bank_account_type_id">Account Type <span class="text-danger">*</span></label>
                            <select class="form-control modal-select2" name="ci_bank_bank_account_type_id" id="ci_bank_bank_account_type_id" <?php echo $disabled; ?>>
                                <option value="">--</option>
                                <?php echo $bankAccountTypeModel->generateBankAccountTypeOptions() ?>
                            </select>
                        </div>
                        <div class="col-lg-4">
                            <label class="form-label" for="ci_bank_account_name">Account Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="ci_bank_account_name" name="ci_bank_account_name" maxlength="500" autocomplete="off" <?php echo $disabled; ?>>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-4">
                            <label class="form-label" for="ci_bank_account_number">Account Number <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="ci_bank_account_number" name="ci_bank_account_number" maxlength="500" autocomplete="off" <?php echo $disabled; ?>>
                        </div>
                        <div class="col-lg-4">
                            <label class="form-label" for="ci_bank_currency_id">Currency</label>
                            <select class="form-control modal-select2" name="ci_bank_currency_id" id="ci_bank_currency_id" <?php echo $disabled; ?>>
                                <option value="">--</option>
                                <?php echo $currencyModel->generateCurrencyOptions() ?>
                            </select>
                        </div>
                        <div class="col-lg-4">
                            <label class="form-label" for="ci_bank_bank_handling_type_id">Handling</label>
                            <select class="form-control modal-select2" name="ci_bank_bank_handling_type_id" id="ci_bank_bank_handling_type_id" <?php echo $disabled; ?>>
                                <option value="">--</option>
                                <?php echo $bankHandlingTypeModel->generateBankHandlingTypeOptions() ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-4">
                            <label class="form-label" for="ci_bank_date_open">Date Open</label>
                            <div class="input-group date">
                                <input type="text" class="form-control regular-datepicker" id="ci_bank_date_open" name="ci_bank_date_open" autocomplete="off" <?php echo $disabled; ?>>
                                <span class="input-group-text">
                                    <i class="feather icon-calendar"></i>
                                </span>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <label class="form-label" for="ci_bank_bank_adb_id">ADB</label>
                            <select class="form-control modal-select2" name="ci_bank_bank_adb_id" id="ci_bank_bank_adb_id" <?php echo $disabled; ?>>
                                <option value="">--</option>
                                <?php echo $bankADBModel->generateBankADBOptions(); ?>
                            </select>
                        </div>
                        <div class="col-lg-4">
                            <label class="form-label" for="ci_bank_informant">Informant</label>
                            <input type="text" class="form-control" id="ci_bank_informant" name="ci_bank_informant" maxlength="1000" autocomplete="off" <?php echo $disabled; ?>>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-lg-12">
                            <label class="form-label" for="ci_bank_remarks">Remarks</label>
                            <textarea class="form-control" id="ci_bank_remarks" name="ci_bank_remarks" maxlength="3000" rows="3" <?php echo $disabled; ?>></textarea>
                        </div>
                    </div>
                    <div class="row align-items-center deposit-hide d-none">
                        <div class="col-sm-6">
                            <h5>Deposits</h5>
                        </div>
                        <div class="col-sm-6 text-sm-end mb-3">
                            <?php
                                if($ci_status == 'Draft' || $ci_status == 'For Completion'){
                                    echo '<button class="btn btn-warning" type="button" data-bs-toggle="modal" data-bs-target="#ci-bank-deposits-modal" id="add-ci-report-bank-deposits">Add Deposits</button>';
                                }
                            ?>
                        </div>
                    </div>
                    <div class="form-group row deposit-hide d-none">
                        <div class="col-lg-12">
                            <div class="table-responsive dt-responsive">
                                <table id="ci-bank-deposits-table" class="table table-hover nowrap w-100">
                                    <thead>
                                    <tr>
                                        <th>Deposit Month</th>
                                        <th>Amount</th>
                                        <th>Remarks</th>
                                        <th>Actions</th>
                                    </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <?php
                    if($ci_status == 'Draft' || $ci_status == 'For Completion'){
                        echo '<button type="submit" class="btn btn-primary" id="submit-ci-bank" form="ci-bank-form">Submit</button>';
                    }
                ?>
            </div>
        </div>
    </div>
</div>

<div class="modal fade modal-animate" id="ci-loan-modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Loan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="ci-loan-form" method="post" action="#">
                    <input type="hidden" id="ci_report_loan_id" name="ci_report_loan_id">
                    <div class="form-group row">
                        <div class="col-lg-12">
                            <h5>Loan Details</h5>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-6">
                            <label class="form-label" for="ci_loan_company">Company <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="ci_loan_company" name="ci_loan_company" maxlength="500" autocomplete="off" <?php echo $disabled; ?>>
                        </div>
                        <div class="col-lg-6">
                            <label class="form-label" for="ci_loan_informant">Informant</label>
                            <input type="text" class="form-control" id="ci_loan_informant" name="ci_loan_informant" maxlength="500" autocomplete="off" <?php echo $disabled; ?>>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-6">
                            <label class="form-label" for="ci_loan_account_name">Account Name/Loan Number</label>
                            <input type="text" class="form-control" id="ci_loan_account_name" name="ci_loan_account_name" maxlength="500" autocomplete="off" <?php echo $disabled; ?>>
                        </div>
                        <div class="col-lg-6">
                            <label class="form-label" for="ci_loan_loan_type_id">Loan Type</label>
                            <select class="form-control modal-select2" name="ci_loan_loan_type_id" id="ci_loan_loan_type_id" <?php echo $disabled; ?>>
                                <option value="">--</option>
                                <?php echo $loanTypeModel->generateLoanTypeOptions() ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-6">
                            <label class="form-label" for="ci_loan_loan_source">Loan Source <span class="text-danger">*</span></label>
                            <select class="form-control modal-select2" name="ci_loan_loan_source" id="ci_loan_loan_source" <?php echo $disabled; ?>>
                                <option value="">--</option>
                                <option value="CGMI">CGMI</option>
                                <option value="Others">Others</option>
                            </select>
                        </div>
                        <div class="col-lg-6">
                            <label class="form-label" for="ci_loan_availed_date">Availed Date</label>
                            <div class="input-group date">
                                <input type="text" class="form-control regular-datepicker" id="ci_loan_availed_date" name="ci_loan_availed_date" autocomplete="off" <?php echo $disabled; ?>>
                                <span class="input-group-text">
                                    <i class="feather icon-calendar"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-6">
                            <label class="form-label" for="ci_loan_maturity_date">Maturity Date</label>
                            <div class="input-group date">
                                <input type="text" class="form-control regular-datepicker" id="ci_loan_maturity_date" name="ci_loan_maturity_date" autocomplete="off" <?php echo $disabled; ?>>
                                <span class="input-group-text">
                                    <i class="feather icon-calendar"></i>
                                </span>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <label class="form-label" for="ci_loan_term">Term</label>
                            <input type="number" class="form-control" id="ci_loan_term" name="ci_loan_term" min="0" step="1" <?php echo $disabled; ?>>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-6">
                            <label class="form-label" for="ci_loan_pn_amount">PN Amount</label>
                            <input type="number" class="form-control" id="ci_loan_pn_amount" name="ci_loan_pn_amount" min="0" step="0.01" <?php echo $disabled; ?>>
                        </div>
                        <div class="col-lg-6">
                            <label class="form-label" for="ci_loan_outstanding_balance">Outstanding Balance</label>
                            <input type="number" class="form-control" id="ci_loan_outstanding_balance" name="ci_loan_outstanding_balance" min="0" step="0.01" <?php echo $disabled; ?>>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-6">
                            <label class="form-label" for="ci_loan_repayment">Repayment</label>
                            <input type="number" class="form-control" id="ci_loan_repayment" name="ci_loan_repayment" min="0" step="0.01" <?php echo $disabled; ?>>
                        </div>
                        <div class="col-lg-6">
                            <label class="form-label" for="ci_loan_handling">Handling</label>
                            <select class="form-control modal-select2" name="ci_loan_handling" id="ci_loan_handling" <?php echo $disabled; ?>>
                                <option value="">--</option>
                                <option value="NDP">NDP</option>
                                <option value="WDP">WDP</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-lg-12">
                            <h5>Other Information</h5>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-12">
                            <label class="form-label" for="ci_loan_remarks">Remarks</label>
                            <textarea class="form-control" id="ci_loan_remarks" name="ci_loan_remarks" maxlength="3000" rows="3" <?php echo $disabled; ?>></textarea>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <?php
                    if($ci_status == 'Draft' || $ci_status == 'For Completion'){
                        echo '<button type="submit" class="btn btn-primary" id="submit-ci-loan" form="ci-loan-form">Submit</button>';
                    }
                ?>
            </div>
        </div>
    </div>
</div>

<div class="modal fade modal-animate" id="ci-trade-reference-modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Trade Reference</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="ci-trade-reference-form" method="post" action="#">
                    <input type="hidden" id="ci_report_trade_reference_id" name="ci_report_trade_reference_id">
                    <div class="form-group row">
                        <div class="col-lg-12">
                            <label class="form-label" for="ci_report_trade_reference_supplier">Supplier <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="ci_report_trade_reference_supplier" name="ci_report_trade_reference_supplier" maxlength="3000" autocomplete="off" <?php echo $disabled; ?>>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-12">
                            <label class="form-label" for="ci_report_trade_reference_contact_person">Contact Person</label>
                            <input type="text" class="form-control" id="ci_report_trade_reference_contact_person" name="ci_report_trade_reference_contact_person" maxlength="3000" autocomplete="off" <?php echo $disabled; ?>>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-12">
                            <label class="form-label" for="ci_report_trade_reference_years_of_transaction">Years of Transaction</label>
                            <input type="text" class="form-control" id="ci_report_trade_reference_years_of_transaction" name="ci_report_trade_reference_years_of_transaction" maxlength="3000" autocomplete="off" <?php echo $disabled; ?>>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-12">
                            <label class="form-label" for="ci_report_trade_reference_remarks">Remarks</label>
                            <textarea class="form-control" id="ci_report_trade_reference_remarks" name="ci_report_trade_reference_remarks" maxlength="3000" rows="3" <?php echo $disabled; ?>></textarea>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#ci-business-modal" data-bs-dismiss="modal">Back</button>
                <?php
                    if($ci_status == 'Draft' || $ci_status == 'For Completion'){
                        echo '<button type="submit" class="btn btn-primary" id="submit-ci-trade-reference" form="ci-trade-reference-form">Submit</button>';
                    }
                ?>
            </div>
        </div>
    </div>
</div>

<div class="modal fade modal-animate" id="ci-asset-modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Asset</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="ci-asset-form" method="post" action="#">
                    <input type="hidden" id="ci_report_asset_id" name="ci_report_asset_id">
                    <div class="form-group row">
                        <div class="col-lg-12">
                            <label class="form-label" for="ci_asset_asset_type_id">Asset Type <span class="text-danger">*</span></label>
                            <select class="form-control modal-select2" name="ci_asset_asset_type_id" id="ci_asset_asset_type_id" <?php echo $disabled; ?>>
                                <option value="">--</option>
                                <?php echo $assetTypeModel->generateAssetTypeOptions() ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-6">
                            <label class="form-label" for="ci_asset_description">Description <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="ci_asset_description" name="ci_asset_description" maxlength="500" autocomplete="off" <?php echo $disabled; ?>>
                        </div>
                        <div class="col-lg-6">
                            <label class="form-label" for="ci_asset_value">Value</label>
                            <input type="number" class="form-control" id="ci_asset_value" name="ci_asset_value" min="0" step="0.01" <?php echo $disabled; ?>>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-12">
                            <label class="form-label" for="ci_asset_remarks">Remarks</label>
                            <textarea class="form-control" id="ci_asset_remarks" name="ci_asset_remarks" maxlength="3000" rows="3" <?php echo $disabled; ?>></textarea>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <?php
                    if($ci_status == 'Draft' || $ci_status == 'For Completion'){
                        echo '<button type="submit" class="btn btn-primary" id="submit-ci-asset" form="ci-asset-form">Submit</button>';
                    }
                ?>
            </div>
        </div>
    </div>
</div>

<div class="modal fade modal-animate" id="ci-cmap-modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">CMAP</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="ci-cmap-form" method="post" action="#">
                    <input type="hidden" id="ci_report_cmap_id" name="ci_report_cmap_id">
                    <div class="form-group row">
                        <div class="col-lg-6">
                            <label class="form-label" for="ci_cmap_cmap_report_type_id">Report Type <span class="text-danger">*</span></label>
                            <select class="form-control modal-select2" name="ci_cmap_cmap_report_type_id" id="ci_cmap_cmap_report_type_id" <?php echo $disabled; ?>>
                                <option value="">--</option>
                                <?php echo $cmapReportTypeModel->generateCMAPReportTypeOptions() ?>
                            </select>
                        </div>
                        <div class="col-lg-6">
                            <label class="form-label" for="ci_cmap_defendant">Defendant <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="ci_cmap_defendant" name="ci_cmap_defendant" maxlength="1000" autocomplete="off" <?php echo $disabled; ?>>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-6">
                            <label class="form-label" for="ci_cmap_plaintiff">Plaintiff <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="ci_cmap_plaintiff" name="ci_cmap_plaintiff" maxlength="1000" autocomplete="off" <?php echo $disabled; ?>>
                        </div>
                        <div class="col-lg-6">
                            <label class="form-label" for="ci_cmap_nature_of_case">Nature of Case</label>
                            <input type="text" class="form-control" id="ci_cmap_nature_of_case" name="ci_cmap_nature_of_case" maxlength="1000" autocomplete="off" <?php echo $disabled; ?>>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-6">
                            <label class="form-label" for="ci_cmap_trial_court">Trial Court</label>
                            <input type="text" class="form-control" id="ci_cmap_trial_court" name="ci_cmap_trial_court" maxlength="1000" autocomplete="off" <?php echo $disabled; ?>>
                        </div>
                        <div class="col-lg-6">
                            <label class="form-label" for="ci_cmap_sala_no">SALA No.</label>
                            <input type="text" class="form-control" id="ci_cmap_sala_no" name="ci_cmap_sala_no" maxlength="1000" autocomplete="off" <?php echo $disabled; ?>>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-6">
                            <label class="form-label" for="ci_cmap_case_no">Case No.</label>
                            <input type="text" class="form-control" id="ci_cmap_case_no" name="ci_cmap_case_no" maxlength="1000" autocomplete="off" <?php echo $disabled; ?>>
                        </div>
                        <div class="col-lg-6">
                            <label class="form-label" for="ci_cmap_reported_date">Reported Date</label>
                            <div class="input-group date">
                                <input type="text" class="form-control regular-datepicker" id="ci_cmap_reported_date" name="ci_cmap_reported_date" autocomplete="off" <?php echo $disabled; ?>>
                                <span class="input-group-text">
                                    <i class="feather icon-calendar"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-12">
                            <label class="form-label" for="ci_cmap_remarks">Remarks</label>
                            <textarea class="form-control" id="ci_cmap_remarks" name="ci_cmap_remarks" maxlength="3000" rows="3" <?php echo $disabled; ?>></textarea>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <?php
                    if($ci_status == 'Draft' || $ci_status == 'For Completion'){
                        echo '<button type="submit" class="btn btn-primary" id="submit-ci-cmap" form="ci-cmap-form">Submit</button>';
                    }
                ?>
            </div>
        </div>
    </div>
</div>

<div class="modal fade modal-animate" id="ci-collateral-modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Collateral</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="ci-collateral-form" method="post" action="#">
                    <input type="hidden" id="ci_report_collateral_id" name="ci_report_collateral_id">
                    <div class="form-group row">
                        <div class="col-lg-3">
                            <label class="form-label" for="ci_collateral_appraisal_date">Appraisal Date <span class="text-danger">*</span></label>
                            <div class="input-group date">
                                <input type="text" class="form-control regular-datepicker" id="ci_collateral_appraisal_date" name="ci_collateral_appraisal_date" autocomplete="off" <?php echo $disabled; ?>>
                                <span class="input-group-text">
                                    <i class="feather icon-calendar"></i>
                                </span>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <label class="form-label" for="ci_collateral_brand_id">Brand <span class="text-danger">*</span></label>
                            <select class="form-control modal-select2" name="ci_collateral_brand_id" id="ci_collateral_brand_id" <?php echo $disabled; ?>>
                                <option value="">--</option>
                                <?php echo $brandModel->generateBrandOptions() ?>
                            </select>
                        </div>
                        <div class="col-lg-3">
                            <label class="form-label" for="ci_collateral_description">Description <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="ci_collateral_description" name="ci_collateral_description" maxlength="1000" autocomplete="off" <?php echo $disabled; ?>>
                        </div>
                        <div class="col-lg-3">
                            <label class="form-label" for="ci_collateral_color_id">Color</label>
                            <input type="text" class="form-control" id="ci_collateral_color_id" name="ci_collateral_color_id" maxlength="200" autocomplete="off" <?php echo $disabled; ?>>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-3">
                            <label class="form-label" for="ci_collateral_year_model">Year Model</label>
                            <input type="text" class="form-control" id="ci_collateral_year_model" name="ci_collateral_year_model" maxlength="10" autocomplete="off" <?php echo $disabled; ?>>
                        </div>
                        <div class="col-lg-3">
                            <label class="form-label" for="ci_collateral_plate_no">Plate No.</label>
                            <input type="text" class="form-control" id="ci_collateral_plate_no" name="ci_collateral_plate_no" maxlength="100" autocomplete="off" <?php echo $disabled; ?>>
                        </div>
                        <div class="col-lg-3">
                            <label class="form-label" for="ci_collateral_motor_no">Motor No.</label>
                            <input type="text" class="form-control" id="ci_collateral_motor_no" name="ci_collateral_motor_no" maxlength="100" autocomplete="off" <?php echo $disabled; ?>>
                        </div>
                        <div class="col-lg-3">
                            <label class="form-label" for="ci_collateral_serial_no">Serial No.</label>
                            <input type="text" class="form-control" id="ci_collateral_serial_no" name="ci_collateral_serial_no" maxlength="100" autocomplete="off" <?php echo $disabled; ?>>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-3">
                            <label class="form-label" for="ci_collateral_mvr_file_no">MVR File No.</label>
                            <input type="text" class="form-control" id="ci_collateral_mvr_file_no" name="ci_collateral_mvr_file_no" maxlength="100" autocomplete="off" <?php echo $disabled; ?>>
                        </div>
                        <div class="col-lg-3">
                            <label class="form-label" for="ci_collateral_cr_no">CR No.</label>
                            <input type="text" class="form-control" id="ci_collateral_cr_no" name="ci_collateral_cr_no" maxlength="100" autocomplete="off" <?php echo $disabled; ?>>
                        </div>
                        <div class="col-lg-3">
                            <label class="form-label" for="ci_collateral_or_no">OR No.</label>
                            <input type="text" class="form-control" id="ci_collateral_or_no" name="ci_collateral_or_no" maxlength="100" autocomplete="off" <?php echo $disabled; ?>>
                        </div>
                        <div class="col-lg-3">
                            <label class="form-label" for="ci_collateral_registered_owner">Registered Owner</label>
                            <input type="text" class="form-control" id="ci_collateral_registered_owner" name="ci_collateral_registered_owner" maxlength="500" autocomplete="off" <?php echo $disabled; ?>>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-3">
                            <label class="form-label" for="ci_collateral_appraised_value">Appraised Value</label>
                            <input type="number" class="form-control" id="ci_collateral_appraised_value" name="ci_collateral_appraised_value" min="0" step="0.01" <?php echo $disabled; ?>>
                        </div>
                        <div class="col-lg-3">
                            <label class="form-label" for="ci_collateral_loannable_value">Loannable Value</label>
                            <input type="number" class="form-control" id="ci_collateral_loannable_value" name="ci_collateral_loannable_value" min="0" step="0.01" <?php echo $disabled; ?>>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-12">
                            <label class="form-label" for="ci_collateral_remarks">Remarks</label>
                            <textarea class="form-control" id="ci_collateral_remarks" name="ci_collateral_remarks" maxlength="3000" rows="3" <?php echo $disabled; ?>></textarea>
                        </div>
                    </div>
                    <div class="row align-items-center appraisal-source-hide d-none">
                        <div class="col-sm-6">
                            <h5>Appraisal Source</h5>
                        </div>
                        <div class="col-sm-6 text-sm-end mb-3">
                            <?php
                                if($ci_status == 'Draft' || $ci_status == 'For Completion'){
                                    echo '<button class="btn btn-warning" type="button" data-bs-toggle="modal" data-bs-target="#ci-appraisal-source-modal" id="add-ci-appraisal-source-deposits">Add Appraisal Source</button>';
                                }
                            ?>
                        </div>
                    </div>
                    <div class="form-group row appraisal-source-hide d-none">
                        <div class="col-lg-12">
                            <div class="table-responsive dt-responsive">
                                <table id="ci-appraisal-source-table" class="table table-hover text-wrap w-100">
                                    <thead>
                                    <tr>
                                        <th>Source</th>
                                        <th>Amount</th>
                                        <th>Remarks</th>
                                        <th>Actions</th>
                                    </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <?php
                    if($ci_status == 'Draft' || $ci_status == 'For Completion'){
                        echo '<button type="submit" class="btn btn-primary" id="submit-ci-collateral" form="ci-collateral-form">Submit</button>';
                    }
                ?>
            </div>
        </div>
    </div>
</div>

<div class="modal fade modal-animate" id="ci-files-modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">File</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="ci-report-files-form" method="post" action="#">
                    <input type="hidden" id="ci_report_files_id" name="ci_report_files_id">
                    <div class="form-group row">
                        <div class="col-lg-12">
                            <label class="form-label" for="ci_file_type_id">File Type <span class="text-danger">*</span></label>
                            <select class="form-control modal-select2" name="ci_file_type_id" id="ci_file_type_id" <?php echo $disabled; ?>>
                                <option value="">--</option>
                                <?php echo $ciFileTypeModel->generateCIFileTypeOptions() ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-12">
                            <label class="form-label" for="ci_file">File <span class="text-danger">*</span></label>
                            <input type="file" class="form-control" id="ci_file" name="ci_file">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-12">
                            <label class="form-label" for="ci_files_remarks">Remarks</label>
                            <textarea class="form-control" id="ci_files_remarks" name="ci_files_remarks" maxlength="3000" rows="3" <?php echo $disabled; ?>></textarea>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <?php
                    if($ci_status == 'Draft' || $ci_status == 'For Completion'){
                        echo '<button type="submit" class="btn btn-primary" id="submit-ci-files" form="ci-report-files-form">Submit</button>';
                    }
                ?>
            </div>
        </div>
    </div>
</div>

<div class="modal fade modal-animate" id="ci-bank-deposits-modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Bank Deposits</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="ci-bank-deposits-form" method="post" action="#">
                    <input type="hidden" id="ci_report_bank_deposits_id" name="ci_report_bank_deposits_id">
                    <div class="form-group row">
                        <div class="col-lg-6">
                            <label class="form-label" for="ci_bank_deposits_deposit_month">Deposit Date <span class="text-danger">*</span></label>
                            <div class="input-group date">
                                <input type="text" class="form-control regular-datepicker" id="ci_bank_deposits_deposit_month" name="ci_bank_deposits_deposit_month" autocomplete="off" <?php echo $disabled; ?>>
                                <span class="input-group-text">
                                    <i class="feather icon-calendar"></i>
                                </span>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <label class="form-label" for="ci_bank_deposits_amount">Amount <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="ci_bank_deposits_amount" name="ci_bank_deposits_amount" min="0" step="0.01" <?php echo $disabled; ?>>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-12">
                            <label class="form-label" for="ci_bank_deposits_remarks">Remarks</label>
                            <textarea class="form-control" id="ci_bank_deposits_remarks" name="ci_bank_deposits_remarks" maxlength="3000" rows="3" <?php echo $disabled; ?>></textarea>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#ci-bank-modal" data-bs-dismiss="modal">Back</button>
                <?php
                    if($ci_status == 'Draft' || $ci_status == 'For Completion'){
                        echo '<button type="submit" class="btn btn-primary" id="submit-ci-bank-deposits" form="ci-bank-deposits-form">Submit</button>';
                    }
                ?>
            </div>
        </div>
    </div>
</div>

<div class="modal fade modal-animate" id="ci-appraisal-source-modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Appraisal Source</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="ci-appraisal-source-form" method="post" action="#">
                    <input type="hidden" id="ci_report_appraisal_source_id" name="ci_report_appraisal_source_id">
                    <div class="form-group row">
                        <div class="col-lg-6">
                            <label class="form-label" for="ci_appraisal_source_source">Appraisal Source</label>
                            <input type="text" class="form-control" id="ci_appraisal_source_source" name="ci_appraisal_source_source" maxlength="3000" autocomplete="off" <?php echo $disabled; ?>>
                        </div>
                        <div class="col-lg-6">
                            <label class="form-label" for="ci_appraisal_source_amount">Amount <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="ci_appraisal_source_amount" name="ci_appraisal_source_amount" min="0" step="0.01" <?php echo $disabled; ?>>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-12">
                            <label class="form-label" for="ci_appraisal_source_remarks">Remarks</label>
                            <textarea class="form-control" id="ci_appraisal_source_remarks" name="ci_appraisal_source_remarks" maxlength="3000" rows="3" <?php echo $disabled; ?>></textarea>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#ci-collateral-modal" data-bs-dismiss="modal">Back</button>
                <?php
                    if($ci_status == 'Draft' || $ci_status == 'For Completion'){
                        echo '<button type="submit" class="btn btn-primary" id="submit-ci-appraisal-source" form="ci-appraisal-source-form">Submit</button>';
                    }
                ?>
            </div>
        </div>
    </div>
</div>

<div>
  <div class="offcanvas offcanvas-end" tabindex="-1" id="ci-report-set-to-draft-offcanvas" aria-labelledby="ci-report-set-to-draft-offcanvas-label">
    <div class="offcanvas-header">
      <h2 id="ci-report-set-to-draft-offcanvas-label" style="margin-bottom:-0.5rem">Set CI Report To Draft</h2>
      <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
      <div class="row">
        <div class="col-lg-12">
          <form id="ci-report-set-to-draft-form" method="post" action="#">
            <div class="form-group row">
              <div class="col-lg-12 mt-3 mt-lg-0">
                <label class="form-label">Set To Draft Reason <span class="text-danger">*</span></label>
                <textarea class="form-control" id="set_to_draft_reason" name="set_to_draft_reason" maxlength="5000"></textarea>
              </div>
            </div>
          </form>
        </div>
      </div>
      <div class="row">
        <div class="col-lg-12">
          <button type="submit" class="btn btn-primary" id="submit-ci-report-set-to-draft" form="ci-report-set-to-draft-form">Submit</button>
          <button class="btn btn-light-danger" data-bs-dismiss="offcanvas"> Close </button>
        </div>
      </div>
    </div>
  </div>
</div>