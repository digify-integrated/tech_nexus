(function($) {
    'use strict';

    $(function() {
        if($('#ci-report-table').length){
            ciReportTable('#ci-report-table');
        }

        $(document).on('click','#apply-filter',function() {
           if($('#ci-report-table').length){
            ciReportTable('#ci-report-table');
        }
        });

        if($('#ci-residence-table').length){
            ciReportResidenceTable('#ci-residence-table');
        }

        if($('#ci-dependents-table').length){
            ciReportDependentsTable('#ci-dependents-table');
        }

        if($('#ci-business-table').length){
            ciReportBusinessTable('#ci-business-table');
        }

        if($('#ci-employment-table').length){
            ciReportEmploymentTable('#ci-employment-table');
        }

        if($('#ci-bank-table').length){
            ciReportBankTable('#ci-bank-table');
        }

        if($('#ci-loan-table').length){
            ciReportLoanTable('#ci-loan-table');
        }

        if($('#ci-asset-table').length){
            ciReportAssetTable('#ci-asset-table');
        }

        if($('#ci-cmap-table').length){
            ciReportCMAPTable('#ci-cmap-table');
        }

        if($('#ci-collateral-table').length){
            ciReportCollateralTable('#ci-collateral-table');
        }

        if($('#ci-files-table').length){
            ciReportFileTable('#ci-files-table');
        }

        if($('#ci-report-form').length){
            ciReportForm();
        }

        if($('#ci-report-form').length){
            ciReportForm();
        }

        if($('#ci-report-recommendation-form').length){
            ciReportRecommendationForm();
        }

        if($('#personal-information-summary').length){
            personalInformationSummary();
        }

        if($('#ci-report-id').length){
            displayDetails('get ci report details');
            displayDetails('get ci report residence total details');
            displayDetails('get ci report business total details');
            displayDetails('get ci report employment total details');
            displayDetails('get ci report asset total details');
            displayDetails('get ci report loans total details');
            displayDetails('get ci report summary details');
            displayDetails('get ci report recommendation details');
        }

        $(document).on('click','#discard-create',function() {
            discardCreate('ci-report.php');
        });

        $(document).on('click','#tag-for-completion',function() {
            const ci_report_id = $('#ci-report-id').text();
            const transaction = 'tag ci report for completion';
    
            Swal.fire({
                title: 'Confirm CI Report For Completion',
                text: 'Are you sure you want to tag this CI report for completion?',
                icon: 'warning',
                showCancelButton: !0,
                confirmButtonText: 'For Completion',
                cancelButtonText: 'Cancel',
                confirmButtonClass: 'btn btn-warning mt-2',
                cancelButtonClass: 'btn btn-secondary ms-2 mt-2',
                buttonsStyling: !1
            }).then(function(result) {
                if (result.value) {
                    $.ajax({
                        type: 'POST',
                        url: 'controller/ci-report-controller.php',
                        dataType: 'json',
                        data: {
                            ci_report_id : ci_report_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                setNotification('Tag CI Report For Completion Success', 'The CI report has been tagged for completion successfully.', 'success');
                                window.location.reload();
                            }
                            else {
                                if (response.isInactive) {
                                    setNotification('User Inactive', response.message, 'danger');
                                    window.location = 'logout.php?logout';
                                }
                                else {
                                    showNotification('Tag CI Report For Completion Error', response.message, 'danger');
                                }
                            }
                        },
                        error: function(xhr, status, error) {
                            var fullErrorMessage = `XHR status: ${status}, Error: ${error}`;
                            if (xhr.responseText) {
                                fullErrorMessage += `, Response: ${xhr.responseText}`;
                            }
                            showErrorDialog(fullErrorMessage);
                        }
                    });
                    return false;
                }
            });
        });

        $(document).on('click','#tag-as-completed',function() {
            const ci_report_id = $('#ci-report-id').text();
            const transaction = 'tag ci report as completed';
    
            Swal.fire({
                title: 'Confirm CI Report As Completed',
                text: 'Are you sure you want to tag this CI report as completed?',
                icon: 'warning',
                showCancelButton: !0,
                confirmButtonText: 'Complete',
                cancelButtonText: 'Cancel',
                confirmButtonClass: 'btn btn-warning mt-2',
                cancelButtonClass: 'btn btn-secondary ms-2 mt-2',
                buttonsStyling: !1
            }).then(function(result) {
                if (result.value) {
                    $.ajax({
                        type: 'POST',
                        url: 'controller/ci-report-controller.php',
                        dataType: 'json',
                        data: {
                            ci_report_id : ci_report_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                setNotification('Tag CI Report As Complete Success', 'The CI report has been tagged as complete successfully.', 'success');
                                window.location.reload();
                            }
                            else {
                                if (response.isInactive) {
                                    setNotification('User Inactive', response.message, 'danger');
                                    window.location = 'logout.php?logout';
                                }
                                else {
                                    showNotification('Tag CI Report As Complete Error', response.message, 'danger');
                                }
                            }
                        },
                        error: function(xhr, status, error) {
                            var fullErrorMessage = `XHR status: ${status}, Error: ${error}`;
                            if (xhr.responseText) {
                                fullErrorMessage += `, Response: ${xhr.responseText}`;
                            }
                            showErrorDialog(fullErrorMessage);
                        }
                    });
                    return false;
                }
            });
        });

        if($('#ci-report-set-to-draft-form').length){
            ciReportSetToDraftForm();
        }

        // ------------------------------------------------------------------------------
        
        if($('#ci-residence-form').length){
            ciReportResidenceForm();
        }

        $(document).on('click','#add-ci-report-residence',function() {
            resetModalForm('ci-residence-form');
        });

        $(document).on('click','.update-ci-report-residence',function() {
            const ci_report_residence_id = $(this).data('ci-report-residence-id');
    
            sessionStorage.setItem('ci_report_residence_id', ci_report_residence_id);
            
            displayDetails('get ci report residence details');
        });

        $(document).on('click','.delete-ci-report-residence',function() {
            const ci_report_residence_id = $(this).data('ci-report-residence-id');
            const transaction = 'delete ci report residence';
    
            Swal.fire({
                title: 'Confirm Residence Deletion',
                text: 'Are you sure you want to delete this residence?',
                icon: 'warning',
                showCancelButton: !0,
                confirmButtonText: 'Delete',
                cancelButtonText: 'Cancel',
                confirmButtonClass: 'btn btn-danger mt-2',
                cancelButtonClass: 'btn btn-secondary ms-2 mt-2',
                buttonsStyling: !1
            }).then(function(result) {
                if (result.value) {
                    $.ajax({
                        type: 'POST',
                        url: 'controller/ci-report-controller.php',
                        dataType: 'json',
                        data: {
                            ci_report_residence_id : ci_report_residence_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                showNotification('Delete Residence Success', 'The residence has been deleted successfully.', 'success');
                                reloadDatatable('#ci-residence-table');
                                
                                displayDetails('get ci report residence total details');
                                displayDetails('get ci report summary details');
                            }
                            else {
                                if (response.isInactive) {
                                    setNotification('User Inactive', response.message, 'danger');
                                    window.location = 'logout.php?logout';
                                }
                                else if (response.notExist) {
                                    showNotification('Delete Residence Error', 'The residence does not exists.', 'danger');
                                    reloadDatatable('#ci-residence-table');
                                }
                                else {
                                    showNotification('Delete Residence Error', response.message, 'danger');
                                }
                            }
                        },
                        error: function(xhr, status, error) {
                            var fullErrorMessage = `XHR status: ${status}, Error: ${error}`;
                            if (xhr.responseText) {
                                fullErrorMessage += `, Response: ${xhr.responseText}`;
                            }
                            showErrorDialog(fullErrorMessage);
                        }
                    });
                    return false;
                }
            });
        });

        $(document).on('change','#ci_residence_personal_expense',function() {
            calculateResidenceExpenseTotal();
        });

        $(document).on('change','#ci_residence_utilities_expense',function() {
            calculateResidenceExpenseTotal();
        });

        $(document).on('change','#ci_residence_other_expense',function() {
            calculateResidenceExpenseTotal();
        });

        // ------------------------------------------------------------------------------

        if($('#ci-dependents-form').length){
            ciReportDependentsForm();
        }

        $(document).on('click','#add-ci-report-dependents',function() {
            resetModalForm('ci-dependents-form');
        });

        $(document).on('click','.update-ci-report-dependents',function() {
            const ci_report_dependents_id = $(this).data('ci-report-dependents-id');
    
            sessionStorage.setItem('ci_report_dependents_id', ci_report_dependents_id);
            
            displayDetails('get ci report dependents details');
        });

        $(document).on('click','.delete-ci-report-dependents',function() {
            const ci_report_dependents_id = $(this).data('ci-report-dependents-id');
            const transaction = 'delete ci report dependents';
    
            Swal.fire({
                title: 'Confirm Dependents Deletion',
                text: 'Are you sure you want to delete this dependents?',
                icon: 'warning',
                showCancelButton: !0,
                confirmButtonText: 'Delete',
                cancelButtonText: 'Cancel',
                confirmButtonClass: 'btn btn-danger mt-2',
                cancelButtonClass: 'btn btn-secondary ms-2 mt-2',
                buttonsStyling: !1
            }).then(function(result) {
                if (result.value) {
                    $.ajax({
                        type: 'POST',
                        url: 'controller/ci-report-controller.php',
                        dataType: 'json',
                        data: {
                            ci_report_dependents_id : ci_report_dependents_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                showNotification('Delete Dependents Success', 'The dependents has been deleted successfully.', 'success');
                                reloadDatatable('#ci-dependents-table');
                            }
                            else {
                                if (response.isInactive) {
                                    setNotification('User Inactive', response.message, 'danger');
                                    window.location = 'logout.php?logout';
                                }
                                else if (response.notExist) {
                                    showNotification('Delete Dependents Error', 'The dependents does not exists.', 'danger');
                                    reloadDatatable('#ci-dependents-table');
                                }
                                else {
                                    showNotification('Delete Dependents Error', response.message, 'danger');
                                }
                            }
                        },
                        error: function(xhr, status, error) {
                            var fullErrorMessage = `XHR status: ${status}, Error: ${error}`;
                            if (xhr.responseText) {
                                fullErrorMessage += `, Response: ${xhr.responseText}`;
                            }
                            showErrorDialog(fullErrorMessage);
                        }
                    });
                    return false;
                }
            });
        });

        // ------------------------------------------------------------------------------

        if($('#ci-business-form').length){
            ciReportBusinessForm();
        }

        $(document).on('click','#add-ci-report-business',function() {
            resetModalForm('ci-business-form');

            $('.trade-reference-hide').addClass('d-none');
        });

        $(document).on('click','.update-ci-report-business',function() {
            const ci_report_business_id = $(this).data('ci-report-business-id');
    
            sessionStorage.setItem('ci_report_business_id', ci_report_business_id);
            
            displayDetails('get ci report business details');

            if($('#ci-trade-reference-table').length){
                ciReportTradeReferenceTable('#ci-trade-reference-table');
            }

            $('.trade-reference-hide').removeClass('d-none');
        });

        $(document).on('click','.delete-ci-report-business',function() {
            const ci_report_business_id = $(this).data('ci-report-business-id');
            const transaction = 'delete ci report business';
    
            Swal.fire({
                title: 'Confirm Business Deletion',
                text: 'Are you sure you want to delete this business?',
                icon: 'warning',
                showCancelButton: !0,
                confirmButtonText: 'Delete',
                cancelButtonText: 'Cancel',
                confirmButtonClass: 'btn btn-danger mt-2',
                cancelButtonClass: 'btn btn-secondary ms-2 mt-2',
                buttonsStyling: !1
            }).then(function(result) {
                if (result.value) {
                    $.ajax({
                        type: 'POST',
                        url: 'controller/ci-report-controller.php',
                        dataType: 'json',
                        data: {
                            ci_report_business_id : ci_report_business_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                showNotification('Delete Business Success', 'The business has been deleted successfully.', 'success');
                                reloadDatatable('#ci-business-table');
                                
                                displayDetails('get ci report business total details');
                                displayDetails('get ci report summary details');
                            }
                            else {
                                if (response.isInactive) {
                                    setNotification('User Inactive', response.message, 'danger');
                                    window.location = 'logout.php?logout';
                                }
                                else if (response.notExist) {
                                    showNotification('Delete Business Error', 'The business does not exists.', 'danger');
                                    reloadDatatable('#ci-business-table');
                                }
                                else {
                                    showNotification('Delete Business Error', response.message, 'danger');
                                }
                            }
                        },
                        error: function(xhr, status, error) {
                            var fullErrorMessage = `XHR status: ${status}, Error: ${error}`;
                            if (xhr.responseText) {
                                fullErrorMessage += `, Response: ${xhr.responseText}`;
                            }
                            showErrorDialog(fullErrorMessage);
                        }
                    });
                    return false;
                }
            });
        });

         $(document).on('change','#ci_business_inventory',function() {
            calculateBusinessCapital();
        });

         $(document).on('change','#ci_business_receivable',function() {
            calculateBusinessCapital();
        });

         $(document).on('change','#ci_business_fixed_asset',function() {
            calculateBusinessCapital();
        });

         $(document).on('change','#ci_business_fixed_asset',function() {
            calculateBusinessCapital();
        });

         $(document).on('change','#ci_business_liabilities',function() {
            calculateBusinessCapital();
        });

        // ------------------------------------------------------------------------------

        if($('#ci-employment-form').length){
            ciReportEmploymentForm();
        }

        $(document).on('click','#add-ci-report-employment',function() {
            resetModalForm('ci-employment-form');
        });

        $(document).on('click','.update-ci-report-employment',function() {
            const ci_report_employment_id = $(this).data('ci-report-employment-id');
    
            sessionStorage.setItem('ci_report_employment_id', ci_report_employment_id);
            
            displayDetails('get ci report employment details');
        });

        $(document).on('click','.delete-ci-report-employment',function() {
            const ci_report_employment_id = $(this).data('ci-report-employment-id');
            const transaction = 'delete ci report employment';
    
            Swal.fire({
                title: 'Confirm Employment Deletion',
                text: 'Are you sure you want to delete this employment?',
                icon: 'warning',
                showCancelButton: !0,
                confirmButtonText: 'Delete',
                cancelButtonText: 'Cancel',
                confirmButtonClass: 'btn btn-danger mt-2',
                cancelButtonClass: 'btn btn-secondary ms-2 mt-2',
                buttonsStyling: !1
            }).then(function(result) {
                if (result.value) {
                    $.ajax({
                        type: 'POST',
                        url: 'controller/ci-report-controller.php',
                        dataType: 'json',
                        data: {
                            ci_report_employment_id : ci_report_employment_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                showNotification('Delete Employment Success', 'The employment has been deleted successfully.', 'success');
                                reloadDatatable('#ci-employment-table');
                                displayDetails('get ci report employment total details');
                                displayDetails('get ci report summary details');
                            }
                            else {
                                if (response.isInactive) {
                                    setNotification('User Inactive', response.message, 'danger');
                                    window.location = 'logout.php?logout';
                                }
                                else if (response.notExist) {
                                    showNotification('Delete Employment Error', 'The employment does not exists.', 'danger');
                                    reloadDatatable('#ci-employment-table');
                                }
                                else {
                                    showNotification('Delete Employment Error', response.message, 'danger');
                                }
                            }
                        },
                        error: function(xhr, status, error) {
                            var fullErrorMessage = `XHR status: ${status}, Error: ${error}`;
                            if (xhr.responseText) {
                                fullErrorMessage += `, Response: ${xhr.responseText}`;
                            }
                            showErrorDialog(fullErrorMessage);
                        }
                    });
                    return false;
                }
            });
        });

        $(document).on('change','#ci_employment_net_salary',function() {
            calculateEmploymentIncomeTotal();
        });

        $(document).on('change','#ci_employment_commission',function() {
            calculateEmploymentIncomeTotal();
        });

        $(document).on('change','#ci_employment_allowance',function() {
            calculateEmploymentIncomeTotal();
        });

        $(document).on('change','#ci_employment_other_income',function() {
            calculateEmploymentIncomeTotal();
        });

        // ------------------------------------------------------------------------------

        if($('#ci-bank-deposits-form').length){
            ciReportBankDepositsForm();
        }

        $(document).on('click','#add-ci-report-bank-deposits',function() {
            resetModalForm('ci-bank-deposits-form');
        });

        $(document).on('click','.update-ci-report-bank-deposits',function() {
            const ci_report_bank_deposits_id = $(this).data('ci-report-bank-deposits-id');
    
            sessionStorage.setItem('ci_report_bank_deposits_id', ci_report_bank_deposits_id);
            
            displayDetails('get ci report bank deposits details');
        });

        $(document).on('click','.delete-ci-report-bank-deposits',function() {
            const ci_report_bank_deposits_id = $(this).data('ci-report-bank-deposits-id');
            const transaction = 'delete ci report bank deposits';
    
            Swal.fire({
                title: 'Confirm Deposit Deletion',
                text: 'Are you sure you want to delete this deposit?',
                icon: 'warning',
                showCancelButton: !0,
                confirmButtonText: 'Delete',
                cancelButtonText: 'Cancel',
                confirmButtonClass: 'btn btn-danger mt-2',
                cancelButtonClass: 'btn btn-secondary ms-2 mt-2',
                buttonsStyling: !1
            }).then(function(result) {
                if (result.value) {
                    $.ajax({
                        type: 'POST',
                        url: 'controller/ci-report-controller.php',
                        dataType: 'json',
                        data: {
                            ci_report_bank_deposits_id : ci_report_bank_deposits_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                showNotification('Delete Deposit Success', 'The deposit has been deleted successfully.', 'success');
                                reloadDatatable('#ci-bank-deposits-table');
                            }
                            else {
                                if (response.isInactive) {
                                    setNotification('User Inactive', response.message, 'danger');
                                    window.location = 'logout.php?logout';
                                }
                                else if (response.notExist) {
                                    showNotification('Delete Deposit Error', 'The deposit does not exists.', 'danger');
                                    reloadDatatable('#ci-bank-deposits-table');
                                }
                                else {
                                    showNotification('Delete Deposit Error', response.message, 'danger');
                                }
                            }
                        },
                        error: function(xhr, status, error) {
                            var fullErrorMessage = `XHR status: ${status}, Error: ${error}`;
                            if (xhr.responseText) {
                                fullErrorMessage += `, Response: ${xhr.responseText}`;
                            }
                            showErrorDialog(fullErrorMessage);
                        }
                    });
                    return false;
                }
            });
        });

        
        // ------------------------------------------------------------------------------


        if($('#ci-bank-form').length){
            ciReportBankForm();
        }

        $(document).on('click','#add-ci-report-bank',function() {
            resetModalForm('ci-bank-form');

            $('.deposit-hide').addClass('d-none');
        });

        $(document).on('click','.update-ci-report-bank',function() {
            const ci_report_bank_id = $(this).data('ci-report-bank-id');
    
            sessionStorage.setItem('ci_report_bank_id', ci_report_bank_id);
            
            displayDetails('get ci report bank details');

            if($('#ci-bank-deposits-table').length){
                ciReportBankDepositsTable('#ci-bank-deposits-table');
            }

            $('.deposit-hide').removeClass('d-none');
        });

        $(document).on('click','.delete-ci-report-bank',function() {
            const ci_report_bank_id = $(this).data('ci-report-bank-id');
            const transaction = 'delete ci report bank';
    
            Swal.fire({
                title: 'Confirm Bank Deletion',
                text: 'Are you sure you want to delete this bank?',
                icon: 'warning',
                showCancelButton: !0,
                confirmButtonText: 'Delete',
                cancelButtonText: 'Cancel',
                confirmButtonClass: 'btn btn-danger mt-2',
                cancelButtonClass: 'btn btn-secondary ms-2 mt-2',
                buttonsStyling: !1
            }).then(function(result) {
                if (result.value) {
                    $.ajax({
                        type: 'POST',
                        url: 'controller/ci-report-controller.php',
                        dataType: 'json',
                        data: {
                            ci_report_bank_id : ci_report_bank_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                showNotification('Delete Bank Success', 'The bank has been deleted successfully.', 'success');
                                reloadDatatable('#ci-bank-table');
                            }
                            else {
                                if (response.isInactive) {
                                    setNotification('User Inactive', response.message, 'danger');
                                    window.location = 'logout.php?logout';
                                }
                                else if (response.notExist) {
                                    showNotification('Delete Bank Error', 'The bank does not exists.', 'danger');
                                    reloadDatatable('#ci-bank-table');
                                }
                                else {
                                    showNotification('Delete Bank Error', response.message, 'danger');
                                }
                            }
                        },
                        error: function(xhr, status, error) {
                            var fullErrorMessage = `XHR status: ${status}, Error: ${error}`;
                            if (xhr.responseText) {
                                fullErrorMessage += `, Response: ${xhr.responseText}`;
                            }
                            showErrorDialog(fullErrorMessage);
                        }
                    });
                    return false;
                }
            });
        });

        // ------------------------------------------------------------------------------

        if($('#ci-loan-form').length){
            ciReportLoanForm();
        }

        $(document).on('click','#add-ci-report-loan',function() {
            resetModalForm('ci-loan-form');
        });

        $(document).on('click','.update-ci-report-loan',function() {
            const ci_report_loan_id = $(this).data('ci-report-loan-id');
    
            sessionStorage.setItem('ci_report_loan_id', ci_report_loan_id);
            
            displayDetails('get ci report loan details');
        });

        $(document).on('click','.delete-ci-report-loan',function() {
            const ci_report_loan_id = $(this).data('ci-report-loan-id');
            const transaction = 'delete ci report loan';
    
            Swal.fire({
                title: 'Confirm Loan Deletion',
                text: 'Are you sure you want to delete this loan?',
                icon: 'warning',
                showCancelButton: !0,
                confirmButtonText: 'Delete',
                cancelButtonText: 'Cancel',
                confirmButtonClass: 'btn btn-danger mt-2',
                cancelButtonClass: 'btn btn-secondary ms-2 mt-2',
                buttonsStyling: !1
            }).then(function(result) {
                if (result.value) {
                    $.ajax({
                        type: 'POST',
                        url: 'controller/ci-report-controller.php',
                        dataType: 'json',
                        data: {
                            ci_report_loan_id : ci_report_loan_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                showNotification('Delete Loan Success', 'The loan has been deleted successfully.', 'success');
                                reloadDatatable('#ci-loan-table');
                                displayDetails('get ci report summary details');
                                displayDetails('get ci report loans total details');
                            }
                            else {
                                if (response.isInactive) {
                                    setNotification('User Inactive', response.message, 'danger');
                                    window.location = 'logout.php?logout';
                                }
                                else if (response.notExist) {
                                    showNotification('Delete Loan Error', 'The loan does not exists.', 'danger');
                                    reloadDatatable('#ci-loan-table');
                                }
                                else {
                                    showNotification('Delete Loan Error', response.message, 'danger');
                                }
                            }
                        },
                        error: function(xhr, status, error) {
                            var fullErrorMessage = `XHR status: ${status}, Error: ${error}`;
                            if (xhr.responseText) {
                                fullErrorMessage += `, Response: ${xhr.responseText}`;
                            }
                            showErrorDialog(fullErrorMessage);
                        }
                    });
                    return false;
                }
            });
        });

        // ------------------------------------------------------------------------------

        if($('#ci-cmap-form').length){
            ciReportCMAPForm();
        }

        $(document).on('click','#add-ci-report-cmap',function() {
            resetModalForm('ci-cmap-form');
        });

        $(document).on('click','.update-ci-report-cmap',function() {
            const ci_report_cmap_id = $(this).data('ci-report-cmap-id');
    
            sessionStorage.setItem('ci_report_cmap_id', ci_report_cmap_id);
            
            displayDetails('get ci report cmap details');
        });

        $(document).on('click','.delete-ci-report-cmap',function() {
            const ci_report_cmap_id = $(this).data('ci-report-cmap-id');
            const transaction = 'delete ci report cmap';
    
            Swal.fire({
                title: 'Confirm CMAP Deletion',
                text: 'Are you sure you want to delete this cmap?',
                icon: 'warning',
                showCancelButton: !0,
                confirmButtonText: 'Delete',
                cancelButtonText: 'Cancel',
                confirmButtonClass: 'btn btn-danger mt-2',
                cancelButtonClass: 'btn btn-secondary ms-2 mt-2',
                buttonsStyling: !1
            }).then(function(result) {
                if (result.value) {
                    $.ajax({
                        type: 'POST',
                        url: 'controller/ci-report-controller.php',
                        dataType: 'json',
                        data: {
                            ci_report_cmap_id : ci_report_cmap_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                showNotification('Delete CMAP Success', 'The cmap has been deleted successfully.', 'success');
                                reloadDatatable('#ci-cmap-table');
                            }
                            else {
                                if (response.isInactive) {
                                    setNotification('User Inactive', response.message, 'danger');
                                    window.location = 'logout.php?logout';
                                }
                                else if (response.notExist) {
                                    showNotification('Delete CMAP Error', 'The cmap does not exists.', 'danger');
                                    reloadDatatable('#ci-cmap-table');
                                }
                                else {
                                    showNotification('Delete CMAP Error', response.message, 'danger');
                                }
                            }
                        },
                        error: function(xhr, status, error) {
                            var fullErrorMessage = `XHR status: ${status}, Error: ${error}`;
                            if (xhr.responseText) {
                                fullErrorMessage += `, Response: ${xhr.responseText}`;
                            }
                            showErrorDialog(fullErrorMessage);
                        }
                    });
                    return false;
                }
            });
        });

        // ------------------------------------------------------------------------------

        if($('#ci-collateral-form').length){
            ciReportCollateralForm();
        }

        $(document).on('click','#add-ci-report-collateral',function() {
            resetModalForm('ci-collateral-form');

            $('.appraisal-source-hide').addClass('d-none');
        });

        $(document).on('click','.update-ci-report-collateral',function() {
            const ci_report_collateral_id = $(this).data('ci-report-collateral-id');
    
            sessionStorage.setItem('ci_report_collateral_id', ci_report_collateral_id);
            
            displayDetails('get ci report collateral details');

            if($('#ci-appraisal-source-table').length){
                ciReportAppraisalSourceTable('#ci-appraisal-source-table');
            }

            $('.appraisal-source-hide').removeClass('d-none');
        });

        $(document).on('click','.delete-ci-report-collateral',function() {
            const ci_report_collateral_id = $(this).data('ci-report-collateral-id');
            const transaction = 'delete ci report collateral';
    
            Swal.fire({
                title: 'Confirm Collateral Deletion',
                text: 'Are you sure you want to delete this collateral?',
                icon: 'warning',
                showCancelButton: !0,
                confirmButtonText: 'Delete',
                cancelButtonText: 'Cancel',
                confirmButtonClass: 'btn btn-danger mt-2',
                cancelButtonClass: 'btn btn-secondary ms-2 mt-2',
                buttonsStyling: !1
            }).then(function(result) {
                if (result.value) {
                    $.ajax({
                        type: 'POST',
                        url: 'controller/ci-report-controller.php',
                        dataType: 'json',
                        data: {
                            ci_report_collateral_id : ci_report_collateral_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                showNotification('Delete Collateral Success', 'The collateral has been deleted successfully.', 'success');
                                reloadDatatable('#ci-collateral-table');
                            }
                            else {
                                if (response.isInactive) {
                                    setNotification('User Inactive', response.message, 'danger');
                                    window.location = 'logout.php?logout';
                                }
                                else if (response.notExist) {
                                    showNotification('Delete Collateral Error', 'The collateral does not exists.', 'danger');
                                    reloadDatatable('#ci-collateral-table');
                                }
                                else {
                                    showNotification('Delete Collateral Error', response.message, 'danger');
                                }
                            }
                        },
                        error: function(xhr, status, error) {
                            var fullErrorMessage = `XHR status: ${status}, Error: ${error}`;
                            if (xhr.responseText) {
                                fullErrorMessage += `, Response: ${xhr.responseText}`;
                            }
                            showErrorDialog(fullErrorMessage);
                        }
                    });
                    return false;
                }
            });
        });

        // ------------------------------------------------------------------------------

        if($('#ci-appraisal-source-form').length){
            ciReportAppraisalSourceForm();
        }

        $(document).on('click','#add-ci-report-appraisal-source',function() {
            resetModalForm('ci-appraisal-source-form');
        });

        $(document).on('click','.update-ci-report-appraisal-source',function() {
            const ci_report_appraisal_source_id = $(this).data('ci-report-appraisal-source-id');
    
            sessionStorage.setItem('ci_report_appraisal_source_id', ci_report_appraisal_source_id);
            
            displayDetails('get ci report appraisal source details');
        });

        $(document).on('click','.delete-ci-report-appraisal-source',function() {
            const ci_report_appraisal_source_id = $(this).data('ci-report-appraisal-source-id');
            const transaction = 'delete ci report appraisal source';
    
            Swal.fire({
                title: 'Confirm Appraisal Source Deletion',
                text: 'Are you sure you want to delete this appraisal source?',
                icon: 'warning',
                showCancelButton: !0,
                confirmButtonText: 'Delete',
                cancelButtonText: 'Cancel',
                confirmButtonClass: 'btn btn-danger mt-2',
                cancelButtonClass: 'btn btn-secondary ms-2 mt-2',
                buttonsStyling: !1
            }).then(function(result) {
                if (result.value) {
                    $.ajax({
                        type: 'POST',
                        url: 'controller/ci-report-controller.php',
                        dataType: 'json',
                        data: {
                            ci_report_appraisal_source_id : ci_report_appraisal_source_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                showNotification('Delete Appraisal Source Success', 'The appraisal source has been deleted successfully.', 'success');
                                reloadDatatable('#ci-appraisal-source-table');
                            }
                            else {
                                if (response.isInactive) {
                                    setNotification('User Inactive', response.message, 'danger');
                                    window.location = 'logout.php?logout';
                                }
                                else if (response.notExist) {
                                    showNotification('Delete Appraisal Source Error', 'The appraisal source does not exists.', 'danger');
                                    reloadDatatable('#ci-appraisal-source-table');
                                }
                                else {
                                    showNotification('Delete Appraisal Source Error', response.message, 'danger');
                                }
                            }
                        },
                        error: function(xhr, status, error) {
                            var fullErrorMessage = `XHR status: ${status}, Error: ${error}`;
                            if (xhr.responseText) {
                                fullErrorMessage += `, Response: ${xhr.responseText}`;
                            }
                            showErrorDialog(fullErrorMessage);
                        }
                    });
                    return false;
                }
            });
        });

        // ------------------------------------------------------------------------------

        if($('#ci-asset-form').length){
            ciReportAssetForm();
        }

        $(document).on('click','#add-ci-report-asset',function() {
            resetModalForm('ci-asset-form');
        });

        $(document).on('click','.update-ci-report-asset',function() {
            const ci_report_asset_id = $(this).data('ci-report-asset-id');
    
            sessionStorage.setItem('ci_report_asset_id', ci_report_asset_id);
            
            displayDetails('get ci report asset details');
        });

        $(document).on('click','.delete-ci-report-asset',function() {
            const ci_report_asset_id = $(this).data('ci-report-asset-id');
            const transaction = 'delete ci report asset';
    
            Swal.fire({
                title: 'Confirm Asset Deletion',
                text: 'Are you sure you want to delete this asset?',
                icon: 'warning',
                showCancelButton: !0,
                confirmButtonText: 'Delete',
                cancelButtonText: 'Cancel',
                confirmButtonClass: 'btn btn-danger mt-2',
                cancelButtonClass: 'btn btn-secondary ms-2 mt-2',
                buttonsStyling: !1
            }).then(function(result) {
                if (result.value) {
                    $.ajax({
                        type: 'POST',
                        url: 'controller/ci-report-controller.php',
                        dataType: 'json',
                        data: {
                            ci_report_asset_id : ci_report_asset_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                showNotification('Delete Asset Success', 'The asset has been deleted successfully.', 'success');
                                reloadDatatable('#ci-asset-table');
                                displayDetails('get ci report asset total details');
                            }
                            else {
                                if (response.isInactive) {
                                    setNotification('User Inactive', response.message, 'danger');
                                    window.location = 'logout.php?logout';
                                }
                                else if (response.notExist) {
                                    showNotification('Delete Asset Error', 'The asset does not exists.', 'danger');
                                    reloadDatatable('#ci-asset-table');
                                }
                                else {
                                    showNotification('Delete Asset Error', response.message, 'danger');
                                }
                            }
                        },
                        error: function(xhr, status, error) {
                            var fullErrorMessage = `XHR status: ${status}, Error: ${error}`;
                            if (xhr.responseText) {
                                fullErrorMessage += `, Response: ${xhr.responseText}`;
                            }
                            showErrorDialog(fullErrorMessage);
                        }
                    });
                    return false;
                }
            });
        });

        // ------------------------------------------------------------------------------

        if($('#ci-trade-reference-form').length){
            ciReportTradeReferenceForm();
        }

        $(document).on('click','#add-ci-report-trade-reference',function() {
            resetModalForm('ci-trade-reference-form');             
        });

        $(document).on('click','.update-ci-report-trade-reference',function() {
            const ci_report_trade_reference_id = $(this).data('ci-report-trade-reference-id');
    
            sessionStorage.setItem('ci_report_trade_reference_id', ci_report_trade_reference_id);
            
            displayDetails('get ci report trade reference details');
        });

        $(document).on('click','.delete-ci-report-trade-reference',function() {
            const ci_report_trade_reference_id = $(this).data('ci-report-trade-reference-id');
            const transaction = 'delete ci report trade reference';
    
            Swal.fire({
                title: 'Confirm Trade Reference Deletion',
                text: 'Are you sure you want to delete this trade reference?',
                icon: 'warning',
                showCancelButton: !0,
                confirmButtonText: 'Delete',
                cancelButtonText: 'Cancel',
                confirmButtonClass: 'btn btn-danger mt-2',
                cancelButtonClass: 'btn btn-secondary ms-2 mt-2',
                buttonsStyling: !1
            }).then(function(result) {
                if (result.value) {
                    $.ajax({
                        type: 'POST',
                        url: 'controller/ci-report-controller.php',
                        dataType: 'json',
                        data: {
                            ci_report_trade_reference_id : ci_report_trade_reference_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                showNotification('Delete Trade Reference Success', 'The trade reference has been deleted successfully.', 'success');
                                reloadDatatable('#ci-trade-reference-table');
                            }
                            else {
                                if (response.isInactive) {
                                    setNotification('User Inactive', response.message, 'danger');
                                    window.location = 'logout.php?logout';
                                }
                                else if (response.notExist) {
                                    showNotification('Delete Trade Reference Error', 'The trade reference does not exists.', 'danger');
                                    reloadDatatable('#ci-trade-reference-table');
                                }
                                else {
                                    showNotification('Delete Trade Reference Error', response.message, 'danger');
                                }
                            }
                        },
                        error: function(xhr, status, error) {
                            var fullErrorMessage = `XHR status: ${status}, Error: ${error}`;
                            if (xhr.responseText) {
                                fullErrorMessage += `, Response: ${xhr.responseText}`;
                            }
                            showErrorDialog(fullErrorMessage);
                        }
                    });
                    return false;
                }
            });
        });

        // ------------------------------------------------------------------------------

        if($('#ci-report-files-form').length){
            ciFileForm();
        }

        $(document).on('click','#add-ci-report-files',function() {
            resetModalForm('ci-report-files-form');
        });

        $(document).on('click','.delete-ci-report-file',function() {
            const ci_report_files_id = $(this).data('ci-report-files-id');
            const transaction = 'delete ci report file';
    
            Swal.fire({
                title: 'Confirm File Deletion',
                text: 'Are you sure you want to delete this file?',
                icon: 'warning',
                showCancelButton: !0,
                confirmButtonText: 'Delete',
                cancelButtonText: 'Cancel',
                confirmButtonClass: 'btn btn-danger mt-2',
                cancelButtonClass: 'btn btn-secondary ms-2 mt-2',
                buttonsStyling: !1
            }).then(function(result) {
                if (result.value) {
                    $.ajax({
                        type: 'POST',
                        url: 'controller/ci-report-controller.php',
                        dataType: 'json',
                        data: {
                            ci_report_files_id : ci_report_files_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                showNotification('Delete File Success', 'The file has been deleted successfully.', 'success');
                                reloadDatatable('#ci-files-table');
                            }
                            else {
                                if (response.isInactive) {
                                    setNotification('User Inactive', response.message, 'danger');
                                    window.location = 'logout.php?logout';
                                }
                                else if (response.notExist) {
                                    showNotification('Delete File Error', 'The file does not exists.', 'danger');
                                    reloadDatatable('#ci-files-table');
                                }
                                else {
                                    showNotification('Delete File Error', response.message, 'danger');
                                }
                            }
                        },
                        error: function(xhr, status, error) {
                            var fullErrorMessage = `XHR status: ${status}, Error: ${error}`;
                            if (xhr.responseText) {
                                fullErrorMessage += `, Response: ${xhr.responseText}`;
                            }
                            showErrorDialog(fullErrorMessage);
                        }
                    });
                    return false;
                }
            });
        });
    });
})(jQuery);

function ciReportTable(datatable_name, buttons = false, show_all = false){
    const type = 'ci report table';

    var ci_report_status_filter = [];

    $('.ci-report-status-filter:checked').each(function() {
        ci_report_status_filter.push($(this).val());
    });

    var filter_ci_report_status = ci_report_status_filter.join(', ');

    
    var filter_created_date_start_date = $('#filter_created_date_start_date').val();
    var filter_created_date_end_date = $('#filter_created_date_end_date').val();
    var filter_completed_date_start_date = $('#filter_completed_date_start_date').val();
    var filter_completed_date_end_date = $('#filter_completed_date_end_date').val();

    var settings;

    const column = [ 
        { 'data' : 'CUSTOMER' },
        { 'data' : 'SALES_PROPOSAL' },
        { 'data' : 'APPRAISER' },
        { 'data' : 'INVESTIGATOR' },
        { 'data' : 'STATUS' },
        { 'data' : 'DATE_STARTED' },
        { 'data' : 'RELEASED_DATE' },
        { 'data' : 'ACTION' }
    ];

    const column_definition = [
        { 'width': 'auto', 'aTargets': 0 },
        { 'width': 'auto', 'aTargets': 1 },
        { 'width': 'auto', 'aTargets': 2 },
        { 'width': 'auto', 'aTargets': 3 },
        { 'width': 'auto', 'aTargets': 4 },
        { 'width': 'auto', 'type': 'date', 'aTargets': 5 },
        { 'width': 'auto', 'type': 'date', 'aTargets': 6 },
        { 'width': '10%','bSortable': false, 'aTargets': 7 }
    ];

    const length_menu = show_all ? [[-1], ['All']] : [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']];

    settings = {
        'ajax': { 
            'url' : 'view/_ci_report_generation.php',
            'method' : 'POST',
            'dataType': 'json',
            'data': {
                'type' : type,
                'filter_ci_report_status' : filter_ci_report_status,
                'filter_created_date_start_date' : filter_created_date_start_date,
                'filter_created_date_end_date' : filter_created_date_end_date,
                'filter_completed_date_start_date' : filter_completed_date_start_date,
                'filter_completed_date_end_date' : filter_completed_date_end_date,
            },
            'dataSrc' : '',
            'error': function(xhr, status, error) {
                var fullErrorMessage = `XHR status: ${status}, Error: ${error}`;
                if (xhr.responseText) {
                    fullErrorMessage += `, Response: ${xhr.responseText}`;
                }
                showErrorDialog(fullErrorMessage);
            }
        },
        'order': [[ 1, 'desc' ]],
        'columns' : column,
        'columnDefs': column_definition,
        'lengthMenu': length_menu,
        'searching': true, // Enable searching
        'search': {
            'search': '',
            'placeholder': 'Search...', // Placeholder text for the search input
            'position': 'top', // Position the search bar to the left
        },
        'language': {
            'emptyTable': 'No data found',
            'searchPlaceholder': 'Search...',
            'search': '',
            'loadingRecords': 'Just a moment while we fetch your data...'
        }
    }

    if (buttons) {
        settings.dom = "<'row'<'col-sm-3'l><'col-sm-6 text-center mb-2'B><'col-sm-3'f>>" +  "<'row'<'col-sm-12'tr>>" + "<'row'<'col-sm-5'i><'col-sm-7'p>>";
        settings.buttons = ['csv', 'excel', 'pdf'];
    }

    destroyDatatable(datatable_name);

    $(datatable_name).dataTable(settings);
}

function ciReportResidenceTable(datatable_name, buttons = false, show_all = false){
    const type = 'ci report residence table';
    const ci_report_id = $('#ci-report-id').text();

    var settings;

    const column = [
        { 'data' : 'ADDRESS' },
        { 'data' : 'ACTION' }
    ];

    const column_definition = [
        { 'width': 'auto', 'aTargets': 0 },
        { 'width': '15%','bSortable': false, 'aTargets': 1 },
    ];

    const length_menu = show_all ? [[-1], ['All']] : [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']];

    settings = {
        'ajax': { 
            'url' : 'view/_ci_report_generation.php',
            'method' : 'POST',
            'dataType': 'json',
            'data': {
                'type' : type, 
                'ci_report_id' : ci_report_id
            },
            'dataSrc' : '',
            'error': function(xhr, status, error) {
                var fullErrorMessage = `XHR status: ${status}, Error: ${error}`;
                if (xhr.responseText) {
                    fullErrorMessage += `, Response: ${xhr.responseText}`;
                }
                showErrorDialog(fullErrorMessage);
            }
        },
        'order': [[ 0, 'asc' ]],
        'columns' : column,
        'columnDefs': column_definition,
        'lengthMenu': length_menu,
        'language': {
            'emptyTable': 'No data found',
            'searchPlaceholder': 'Search...',
            'search': '',
            'loadingRecords': 'Just a moment while we fetch your data...'
        }
    };

    if (buttons) {
        settings.dom = "<'row'<'col-sm-3'l><'col-sm-6 text-center mb-2'B><'col-sm-3'f>>" +  "<'row'<'col-sm-12'tr>>" + "<'row'<'col-sm-5'i><'col-sm-7'p>>";
        settings.buttons = ['csv', 'excel', 'pdf'];
    }

    destroyDatatable(datatable_name);

    $(datatable_name).dataTable(settings);
}

function ciReportDependentsTable(datatable_name, buttons = false, show_all = false){
    const type = 'ci report dependents table';
    const ci_report_id = $('#ci-report-id').text();

    var settings;

    const column = [
        { 'data' : 'NAME' },
        { 'data' : 'AGE' },
        { 'data' : 'SCHOOL' },
        { 'data' : 'EMPLOYMENT' },
        { 'data' : 'REMARKS' },
        { 'data' : 'ACTION' }
    ];

    const column_definition = [
        { 'width': 'auto', 'aTargets': 0 },
        { 'width': 'auto', 'aTargets': 1 },
        { 'width': 'auto', 'aTargets': 2 },
        { 'width': 'auto', 'aTargets': 3 },
        { 'width': 'auto', 'aTargets': 4 },
        { 'width': '15%','bSortable': false, 'aTargets': 5 },
    ];

    const length_menu = show_all ? [[-1], ['All']] : [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']];

    settings = {
        'ajax': { 
            'url' : 'view/_ci_report_generation.php',
            'method' : 'POST',
            'dataType': 'json',
            'data': {
                'type' : type, 
                'ci_report_id' : ci_report_id
            },
            'dataSrc' : '',
            'error': function(xhr, status, error) {
                var fullErrorMessage = `XHR status: ${status}, Error: ${error}`;
                if (xhr.responseText) {
                    fullErrorMessage += `, Response: ${xhr.responseText}`;
                }
                showErrorDialog(fullErrorMessage);
            }
        },
        'order': [[ 0, 'asc' ]],
        'columns' : column,
        'columnDefs': column_definition,
        'lengthMenu': length_menu,
        'language': {
            'emptyTable': 'No data found',
            'searchPlaceholder': 'Search...',
            'search': '',
            'loadingRecords': 'Just a moment while we fetch your data...'
        }
    };

    if (buttons) {
        settings.dom = "<'row'<'col-sm-3'l><'col-sm-6 text-center mb-2'B><'col-sm-3'f>>" +  "<'row'<'col-sm-12'tr>>" + "<'row'<'col-sm-5'i><'col-sm-7'p>>";
        settings.buttons = ['csv', 'excel', 'pdf'];
    }

    destroyDatatable(datatable_name);

    $(datatable_name).dataTable(settings);
}

function ciReportBusinessTable(datatable_name, buttons = false, show_all = false){
    const type = 'ci report business table';
    const ci_report_id = $('#ci-report-id').text();

    var settings;

    const column = [
        { 'data' : 'BUSINESS_NAME' },
        { 'data' : 'ADDRESS' },
        { 'data' : 'ACTION' }
    ];

    const column_definition = [
        { 'width': 'auto', 'aTargets': 0 },
        { 'width': 'auto', 'aTargets': 1 },
        { 'width': '15%','bSortable': false, 'aTargets': 2 },
    ];

    const length_menu = show_all ? [[-1], ['All']] : [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']];

    settings = {
        'ajax': { 
            'url' : 'view/_ci_report_generation.php',
            'method' : 'POST',
            'dataType': 'json',
            'data': {
                'type' : type, 
                'ci_report_id' : ci_report_id
            },
            'dataSrc' : '',
            'error': function(xhr, status, error) {
                var fullErrorMessage = `XHR status: ${status}, Error: ${error}`;
                if (xhr.responseText) {
                    fullErrorMessage += `, Response: ${xhr.responseText}`;
                }
                showErrorDialog(fullErrorMessage);
            }
        },
        'order': [[ 0, 'asc' ]],
        'columns' : column,
        'columnDefs': column_definition,
        'lengthMenu': length_menu,
        'language': {
            'emptyTable': 'No data found',
            'searchPlaceholder': 'Search...',
            'search': '',
            'loadingRecords': 'Just a moment while we fetch your data...'
        }
    };

    if (buttons) {
        settings.dom = "<'row'<'col-sm-3'l><'col-sm-6 text-center mb-2'B><'col-sm-3'f>>" +  "<'row'<'col-sm-12'tr>>" + "<'row'<'col-sm-5'i><'col-sm-7'p>>";
        settings.buttons = ['csv', 'excel', 'pdf'];
    }

    destroyDatatable(datatable_name);

    $(datatable_name).dataTable(settings);
}

function ciReportEmploymentTable(datatable_name, buttons = false, show_all = false){
    const type = 'ci report employment table';
    const ci_report_id = $('#ci-report-id').text();

    var settings;

    const column = [
        { 'data' : 'EMPLOYMENT_NAME' },
        { 'data' : 'ADDRESS' },
        { 'data' : 'DEPARTMENT' },
        { 'data' : 'RANK' },
        { 'data' : 'POSITION' },
        { 'data' : 'ACTION' }
    ];

    const column_definition = [
        { 'width': 'auto', 'aTargets': 0 },
        { 'width': 'auto', 'aTargets': 1 },
        { 'width': 'auto', 'aTargets': 2 },
        { 'width': 'auto', 'aTargets': 3  },
        { 'width': 'auto', 'aTargets': 4  },
        { 'width': '15%','bSortable': false, 'aTargets': 5 },
    ];

    const length_menu = show_all ? [[-1], ['All']] : [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']];

    settings = {
        'ajax': { 
            'url' : 'view/_ci_report_generation.php',
            'method' : 'POST',
            'dataType': 'json',
            'data': {
                'type' : type, 
                'ci_report_id' : ci_report_id
            },
            'dataSrc' : '',
            'error': function(xhr, status, error) {
                var fullErrorMessage = `XHR status: ${status}, Error: ${error}`;
                if (xhr.responseText) {
                    fullErrorMessage += `, Response: ${xhr.responseText}`;
                }
                showErrorDialog(fullErrorMessage);
            }
        },
        'order': [[ 0, 'asc' ]],
        'columns' : column,
        'columnDefs': column_definition,
        'lengthMenu': length_menu,
        'language': {
            'emptyTable': 'No data found',
            'searchPlaceholder': 'Search...',
            'search': '',
            'loadingRecords': 'Just a moment while we fetch your data...'
        }
    };

    if (buttons) {
        settings.dom = "<'row'<'col-sm-3'l><'col-sm-6 text-center mb-2'B><'col-sm-3'f>>" +  "<'row'<'col-sm-12'tr>>" + "<'row'<'col-sm-5'i><'col-sm-7'p>>";
        settings.buttons = ['csv', 'excel', 'pdf'];
    }

    destroyDatatable(datatable_name);

    $(datatable_name).dataTable(settings);
}

function ciReportBankTable(datatable_name, buttons = false, show_all = false){
    const type = 'ci report bank table';
    const ci_report_id = $('#ci-report-id').text();

    var settings;

    const column = [
        { 'data' : 'BANK' },
        { 'data' : 'BANK_ACCOUNT_TYPE' },
        { 'data' : 'ACCOUNT_NAME' },
        { 'data' : 'ACCOUNT_NUMBER' },
        { 'data' : 'CURRENCY' },
        { 'data' : 'DATE_OPEN' },
        { 'data' : 'ADB' },
        { 'data' : 'AVERAGE' },
        { 'data' : 'ACTION' }
    ];

    const column_definition = [
        { 'width': 'auto', 'aTargets': 0 },
        { 'width': 'auto', 'aTargets': 1 },
        { 'width': 'auto', 'aTargets': 2 },
        { 'width': 'auto', 'aTargets': 3  },
        { 'width': 'auto', 'aTargets': 4  },
        { 'width': 'auto', 'aTargets': 5  },
        { 'width': 'auto', 'aTargets': 6  },
        { 'width': 'auto', 'aTargets': 7  },
        { 'width': '15%','bSortable': false, 'aTargets': 8 },
    ];

    const length_menu = show_all ? [[-1], ['All']] : [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']];

    settings = {
        'ajax': { 
            'url' : 'view/_ci_report_generation.php',
            'method' : 'POST',
            'dataType': 'json',
            'data': {
                'type' : type, 
                'ci_report_id' : ci_report_id
            },
            'dataSrc' : '',
            'error': function(xhr, status, error) {
                var fullErrorMessage = `XHR status: ${status}, Error: ${error}`;
                if (xhr.responseText) {
                    fullErrorMessage += `, Response: ${xhr.responseText}`;
                }
                showErrorDialog(fullErrorMessage);
            }
        },
        'order': [[ 0, 'asc' ]],
        'columns' : column,
        'columnDefs': column_definition,
        'lengthMenu': length_menu,
        'language': {
            'emptyTable': 'No data found',
            'searchPlaceholder': 'Search...',
            'search': '',
            'loadingRecords': 'Just a moment while we fetch your data...'
        }
    };

    if (buttons) {
        settings.dom = "<'row'<'col-sm-3'l><'col-sm-6 text-center mb-2'B><'col-sm-3'f>>" +  "<'row'<'col-sm-12'tr>>" + "<'row'<'col-sm-5'i><'col-sm-7'p>>";
        settings.buttons = ['csv', 'excel', 'pdf'];
    }

    destroyDatatable(datatable_name);

    $(datatable_name).dataTable(settings);
}

function ciReportBankDepositsTable(datatable_name, buttons = false, show_all = false){
    const type = 'ci report bank deposits table';
    var ci_report_bank_id = sessionStorage.getItem('ci_report_bank_id');

    var settings;

    const column = [
        { 'data' : 'DEPOSIT_MONTH' },
        { 'data' : 'AMOUNT' },
        { 'data' : 'REMARKS' },
        { 'data' : 'ACTION' }
    ];

    const column_definition = [
        { 'width': 'auto', 'aTargets': 0 },
        { 'width': 'auto', 'aTargets': 1 },
        { 'width': 'auto', 'aTargets': 2 },
        { 'width': '15%','bSortable': false, 'aTargets': 3 },
    ];

    const length_menu = show_all ? [[-1], ['All']] : [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']];

    settings = {
        'ajax': { 
            'url' : 'view/_ci_report_generation.php',
            'method' : 'POST',
            'dataType': 'json',
            'data': {
                'type' : type, 
                'ci_report_bank_id' : ci_report_bank_id
            },
            'dataSrc' : '',
            'error': function(xhr, status, error) {
                var fullErrorMessage = `XHR status: ${status}, Error: ${error}`;
                if (xhr.responseText) {
                    fullErrorMessage += `, Response: ${xhr.responseText}`;
                }
                showErrorDialog(fullErrorMessage);
            }
        },
        'order': [[ 0, 'asc' ]],
        'columns' : column,
        'columnDefs': column_definition,
        'lengthMenu': length_menu,
        'language': {
            'emptyTable': 'No data found',
            'searchPlaceholder': 'Search...',
            'search': '',
            'loadingRecords': 'Just a moment while we fetch your data...'
        }
    };

    if (buttons) {
        settings.dom = "<'row'<'col-sm-3'l><'col-sm-6 text-center mb-2'B><'col-sm-3'f>>" +  "<'row'<'col-sm-12'tr>>" + "<'row'<'col-sm-5'i><'col-sm-7'p>>";
        settings.buttons = ['csv', 'excel', 'pdf'];
    }

    destroyDatatable(datatable_name);

    $(datatable_name).dataTable(settings);
}

function ciReportLoanTable(datatable_name, buttons = false, show_all = false){
    const type = 'ci report loan table';
    const ci_report_id = $('#ci-report-id').text();

    var settings;

    const column = [
        { 'data' : 'COMPANY' },
        { 'data' : 'INFORMANT' },
        { 'data' : 'ACCOUNT_NAME' },
        { 'data' : 'LOAN_SOURCE' },
        { 'data' : 'LOAN_TYPE' },
        { 'data' : 'AVAILED_DATE' },
        { 'data' : 'MATURITY_DATE' },
        { 'data' : 'TERM' },
        { 'data' : 'PN_AMOUNT' },
        { 'data' : 'OUTSTANDING_BALANCE' },
        { 'data' : 'REPAYMENT' },
        { 'data' : 'HANDLING' },
        { 'data' : 'REMARKS' },
        { 'data' : 'ACTION' }
    ];

    const column_definition = [
        { 'width': 'auto', 'aTargets': 0 },
        { 'width': 'auto', 'aTargets': 1 },
        { 'width': 'auto', 'aTargets': 2 },
        { 'width': 'auto', 'aTargets': 3  },
        { 'width': 'auto', 'aTargets': 4  },
        { 'width': 'auto', 'aTargets': 5  },
        { 'width': 'auto', 'aTargets': 6  },
        { 'width': 'auto', 'aTargets': 7  },
        { 'width': 'auto', 'aTargets': 8  },
        { 'width': 'auto', 'aTargets': 9  },
        { 'width': 'auto', 'aTargets': 10  },
        { 'width': 'auto', 'aTargets': 11  },
        { 'width': 'auto', 'aTargets': 12  },
        { 'width': '15%','bSortable': false, 'aTargets': 13 },
    ];

    const length_menu = show_all ? [[-1], ['All']] : [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']];

    settings = {
        'ajax': { 
            'url' : 'view/_ci_report_generation.php',
            'method' : 'POST',
            'dataType': 'json',
            'data': {
                'type' : type, 
                'ci_report_id' : ci_report_id
            },
            'dataSrc' : '',
            'error': function(xhr, status, error) {
                var fullErrorMessage = `XHR status: ${status}, Error: ${error}`;
                if (xhr.responseText) {
                    fullErrorMessage += `, Response: ${xhr.responseText}`;
                }
                showErrorDialog(fullErrorMessage);
            }
        },
        'order': [[ 0, 'asc' ]],
        'columns' : column,
        'columnDefs': column_definition,
        'lengthMenu': length_menu,
        'language': {
            'emptyTable': 'No data found',
            'searchPlaceholder': 'Search...',
            'search': '',
            'loadingRecords': 'Just a moment while we fetch your data...'
        }
    };

    if (buttons) {
        settings.dom = "<'row'<'col-sm-3'l><'col-sm-6 text-center mb-2'B><'col-sm-3'f>>" +  "<'row'<'col-sm-12'tr>>" + "<'row'<'col-sm-5'i><'col-sm-7'p>>";
        settings.buttons = ['csv', 'excel', 'pdf'];
    }

    destroyDatatable(datatable_name);

    $(datatable_name).dataTable(settings);
}

function ciReportAssetTable(datatable_name, buttons = false, show_all = false){
    const type = 'ci report asset table';
    const ci_report_id = $('#ci-report-id').text();

    var settings;

    const column = [
        { 'data' : 'ASSET_TYPE' },
        { 'data' : 'DESCRIPTION' },
        { 'data' : 'VALUE' },
        { 'data' : 'ACTION' }
    ];

    const column_definition = [
        { 'width': 'auto', 'aTargets': 0 },
        { 'width': 'auto', 'aTargets': 1 },
        { 'width': 'auto', 'aTargets': 2 },
        { 'width': '15%','bSortable': false, 'aTargets': 3 },
    ];

    const length_menu = show_all ? [[-1], ['All']] : [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']];

    settings = {
        'ajax': { 
            'url' : 'view/_ci_report_generation.php',
            'method' : 'POST',
            'dataType': 'json',
            'data': {
                'type' : type, 
                'ci_report_id' : ci_report_id
            },
            'dataSrc' : '',
            'error': function(xhr, status, error) {
                var fullErrorMessage = `XHR status: ${status}, Error: ${error}`;
                if (xhr.responseText) {
                    fullErrorMessage += `, Response: ${xhr.responseText}`;
                }
                showErrorDialog(fullErrorMessage);
            }
        },
        'order': [[ 0, 'asc' ]],
        'columns' : column,
        'columnDefs': column_definition,
        'lengthMenu': length_menu,
        'language': {
            'emptyTable': 'No data found',
            'searchPlaceholder': 'Search...',
            'search': '',
            'loadingRecords': 'Just a moment while we fetch your data...'
        }
    };

    if (buttons) {
        settings.dom = "<'row'<'col-sm-3'l><'col-sm-6 text-center mb-2'B><'col-sm-3'f>>" +  "<'row'<'col-sm-12'tr>>" + "<'row'<'col-sm-5'i><'col-sm-7'p>>";
        settings.buttons = ['csv', 'excel', 'pdf'];
    }

    destroyDatatable(datatable_name);

    $(datatable_name).dataTable(settings);
}

function ciReportCMAPTable(datatable_name, buttons = false, show_all = false){
    const type = 'ci report cmap table';
    const ci_report_id = $('#ci-report-id').text();

    var settings;

    const column = [
        { 'data' : 'REPORT_TYPE' },
        { 'data' : 'DEFENDANTS' },
        { 'data' : 'PLAINTIFF' },
        { 'data' : 'NATURE_OF_CASE' },
        { 'data' : 'TRIAL_COURT' },
        { 'data' : 'SALA_NO' },
        { 'data' : 'CASE_NO' },
        { 'data' : 'REPORT_DATE' },
        { 'data' : 'REMARKS' },
        { 'data' : 'ACTION' }
    ];

    const column_definition = [
        { 'width': 'auto', 'aTargets': 0 },
        { 'width': 'auto', 'aTargets': 1 },
        { 'width': 'auto', 'aTargets': 2 },
        { 'width': 'auto', 'aTargets': 3 },
        { 'width': 'auto', 'aTargets': 4 },
        { 'width': 'auto', 'aTargets': 5 },
        { 'width': 'auto', 'aTargets': 6 },
        { 'width': 'auto', 'aTargets': 7 },
        { 'width': 'auto', 'aTargets': 8 },
        { 'width': '15%','bSortable': false, 'aTargets': 9 },
    ];

    const length_menu = show_all ? [[-1], ['All']] : [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']];

    settings = {
        'ajax': { 
            'url' : 'view/_ci_report_generation.php',
            'method' : 'POST',
            'dataType': 'json',
            'data': {
                'type' : type, 
                'ci_report_id' : ci_report_id
            },
            'dataSrc' : '',
            'error': function(xhr, status, error) {
                var fullErrorMessage = `XHR status: ${status}, Error: ${error}`;
                if (xhr.responseText) {
                    fullErrorMessage += `, Response: ${xhr.responseText}`;
                }
                showErrorDialog(fullErrorMessage);
            }
        },
        'order': [[ 0, 'asc' ]],
        'columns' : column,
        'columnDefs': column_definition,
        'lengthMenu': length_menu,
        'language': {
            'emptyTable': 'No data found',
            'searchPlaceholder': 'Search...',
            'search': '',
            'loadingRecords': 'Just a moment while we fetch your data...'
        }
    };

    if (buttons) {
        settings.dom = "<'row'<'col-sm-3'l><'col-sm-6 text-center mb-2'B><'col-sm-3'f>>" +  "<'row'<'col-sm-12'tr>>" + "<'row'<'col-sm-5'i><'col-sm-7'p>>";
        settings.buttons = ['csv', 'excel', 'pdf'];
    }

    destroyDatatable(datatable_name);

    $(datatable_name).dataTable(settings);
}

function ciReportCollateralTable(datatable_name, buttons = false, show_all = false){
    const type = 'ci report collateral table';
    const ci_report_id = $('#ci-report-id').text();

    var settings;

    const column = [
        { 'data' : 'APPRAISAL_DATE' },
        { 'data' : 'BRAND' },
        { 'data' : 'APPRAISED_VALUE' },
        { 'data' : 'LOANNABLE_VALUE' },
        { 'data' : 'COLOR' },
        { 'data' : 'YEAR_MODEL' },
        { 'data' : 'PLATE_NO' },
        { 'data' : 'MOTOR_NO' },
        { 'data' : 'SERIAL_NO' },
        { 'data' : 'MVR_FILE_NO' },
        { 'data' : 'CR_NO' },
        { 'data' : 'OR_NO' },
        { 'data' : 'REGISTERED_OWNER' },
        { 'data' : 'REMARKS' },
        { 'data' : 'ACTION' }
    ];

    const column_definition = [
        { 'width': 'auto', 'aTargets': 0 },
        { 'width': 'auto', 'aTargets': 1 },
        { 'width': 'auto', 'aTargets': 2 },
        { 'width': 'auto', 'aTargets': 3 },
        { 'width': 'auto', 'aTargets': 4 },
        { 'width': 'auto', 'aTargets': 5 },
        { 'width': 'auto', 'aTargets': 6 },
        { 'width': 'auto', 'aTargets': 7 },
        { 'width': 'auto', 'aTargets': 8 },
        { 'width': 'auto', 'aTargets': 9 },
        { 'width': 'auto', 'aTargets': 10 },
        { 'width': 'auto', 'aTargets': 11 },
        { 'width': 'auto', 'aTargets': 12 },
        { 'width': 'auto', 'aTargets': 13 },
        { 'width': '15%','bSortable': false, 'aTargets': 14 },
    ];

    const length_menu = show_all ? [[-1], ['All']] : [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']];

    settings = {
        'ajax': { 
            'url' : 'view/_ci_report_generation.php',
            'method' : 'POST',
            'dataType': 'json',
            'data': {
                'type' : type, 
                'ci_report_id' : ci_report_id
            },
            'dataSrc' : '',
            'error': function(xhr, status, error) {
                var fullErrorMessage = `XHR status: ${status}, Error: ${error}`;
                if (xhr.responseText) {
                    fullErrorMessage += `, Response: ${xhr.responseText}`;
                }
                showErrorDialog(fullErrorMessage);
            }
        },
        'order': [[ 0, 'asc' ]],
        'columns' : column,
        'columnDefs': column_definition,
        'lengthMenu': length_menu,
        'language': {
            'emptyTable': 'No data found',
            'searchPlaceholder': 'Search...',
            'search': '',
            'loadingRecords': 'Just a moment while we fetch your data...'
        }
    };

    if (buttons) {
        settings.dom = "<'row'<'col-sm-3'l><'col-sm-6 text-center mb-2'B><'col-sm-3'f>>" +  "<'row'<'col-sm-12'tr>>" + "<'row'<'col-sm-5'i><'col-sm-7'p>>";
        settings.buttons = ['csv', 'excel', 'pdf'];
    }

    destroyDatatable(datatable_name);

    $(datatable_name).dataTable(settings);
}

function ciReportAppraisalSourceTable(datatable_name, buttons = false, show_all = false){
    const type = 'ci report appraisal source table';
    var ci_report_collateral_id = sessionStorage.getItem('ci_report_collateral_id');

    var settings;

    const column = [
        { 'data' : 'SOURCE' },
        { 'data' : 'AMOUNT' },
        { 'data' : 'REMARKS' },
        { 'data' : 'ACTION' }
    ];

    const column_definition = [
        { 'width': 'auto', 'aTargets': 0 },
        { 'width': 'auto', 'aTargets': 1 },
        { 'width': 'auto', 'aTargets': 2 },
        { 'width': '15%','bSortable': false, 'aTargets': 3 },
    ];

    const length_menu = show_all ? [[-1], ['All']] : [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']];

    settings = {
        'ajax': { 
            'url' : 'view/_ci_report_generation.php',
            'method' : 'POST',
            'dataType': 'json',
            'data': {
                'type' : type, 
                'ci_report_collateral_id' : ci_report_collateral_id
            },
            'dataSrc' : '',
            'error': function(xhr, status, error) {
                var fullErrorMessage = `XHR status: ${status}, Error: ${error}`;
                if (xhr.responseText) {
                    fullErrorMessage += `, Response: ${xhr.responseText}`;
                }
                showErrorDialog(fullErrorMessage);
            }
        },
        'order': [[ 0, 'asc' ]],
        'columns' : column,
        'columnDefs': column_definition,
        'lengthMenu': length_menu,
        'language': {
            'emptyTable': 'No data found',
            'searchPlaceholder': 'Search...',
            'search': '',
            'loadingRecords': 'Just a moment while we fetch your data...'
        }
    };

    if (buttons) {
        settings.dom = "<'row'<'col-sm-3'l><'col-sm-6 text-center mb-2'B><'col-sm-3'f>>" +  "<'row'<'col-sm-12'tr>>" + "<'row'<'col-sm-5'i><'col-sm-7'p>>";
        settings.buttons = ['csv', 'excel', 'pdf'];
    }

    destroyDatatable(datatable_name);

    $(datatable_name).dataTable(settings);
}

function ciReportTradeReferenceTable(datatable_name, buttons = false, show_all = false){
    const type = 'ci report trade reference table';
    const ci_report_id = $('#ci-report-id').text();
    var ci_report_business_id = sessionStorage.getItem('ci_report_business_id');

    var settings;

    const column = [
        { 'data' : 'SUPPLIER' },
        { 'data' : 'CONTACT_PERSON' },
        { 'data' : 'YEARS_OF_TRANSACTION' },
        { 'data' : 'REMARKS' },
        { 'data' : 'ACTION' }
    ];

    const column_definition = [
        { 'width': 'auto', 'aTargets': 0 },
        { 'width': 'auto', 'aTargets': 1 },
        { 'width': 'auto', 'aTargets': 2 },
        { 'width': 'auto', 'aTargets': 3 },
        { 'width': '15%','bSortable': false, 'aTargets': 4 },
    ];

    const length_menu = show_all ? [[-1], ['All']] : [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']];

    settings = {
        'ajax': { 
            'url' : 'view/_ci_report_generation.php',
            'method' : 'POST',
            'dataType': 'json',
            'data': {
                'type' : type, 
                'ci_report_id' : ci_report_id, 
                'ci_report_business_id' : ci_report_business_id
            },
            'dataSrc' : '',
            'error': function(xhr, status, error) {
                var fullErrorMessage = `XHR status: ${status}, Error: ${error}`;
                if (xhr.responseText) {
                    fullErrorMessage += `, Response: ${xhr.responseText}`;
                }
                showErrorDialog(fullErrorMessage);
            }
        },
        'order': [[ 0, 'asc' ]],
        'columns' : column,
        'columnDefs': column_definition,
        'lengthMenu': length_menu,
        'language': {
            'emptyTable': 'No data found',
            'searchPlaceholder': 'Search...',
            'search': '',
            'loadingRecords': 'Just a moment while we fetch your data...'
        }
    };

    if (buttons) {
        settings.dom = "<'row'<'col-sm-3'l><'col-sm-6 text-center mb-2'B><'col-sm-3'f>>" +  "<'row'<'col-sm-12'tr>>" + "<'row'<'col-sm-5'i><'col-sm-7'p>>";
        settings.buttons = ['csv', 'excel', 'pdf'];
    }

    destroyDatatable(datatable_name);

    $(datatable_name).dataTable(settings);
}

function ciReportFileTable(datatable_name, buttons = false, show_all = false){
    const type = 'ci report files table';
    const ci_report_id = $('#ci-report-id').text();

    var settings;

    const column = [
        { 'data' : 'CI_FILE_TYPE' },
        { 'data' : 'REMARKS' },
        { 'data' : 'ACTION' }
    ];

    const column_definition = [
        { 'width': 'auto', 'aTargets': 0 },
        { 'width': 'auto', 'aTargets': 1 },
        { 'width': '15%','bSortable': false, 'aTargets': 2 },
    ];

    const length_menu = show_all ? [[-1], ['All']] : [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']];

    settings = {
        'ajax': { 
            'url' : 'view/_ci_report_generation.php',
            'method' : 'POST',
            'dataType': 'json',
            'data': {
                'type' : type, 
                'ci_report_id' : ci_report_id
            },
            'dataSrc' : '',
            'error': function(xhr, status, error) {
                var fullErrorMessage = `XHR status: ${status}, Error: ${error}`;
                if (xhr.responseText) {
                    fullErrorMessage += `, Response: ${xhr.responseText}`;
                }
                showErrorDialog(fullErrorMessage);
            }
        },
        'order': [[ 0, 'asc' ]],
        'columns' : column,
        'columnDefs': column_definition,
        'lengthMenu': length_menu,
        'language': {
            'emptyTable': 'No data found',
            'searchPlaceholder': 'Search...',
            'search': '',
            'loadingRecords': 'Just a moment while we fetch your data...'
        }
    };

    if (buttons) {
        settings.dom = "<'row'<'col-sm-3'l><'col-sm-6 text-center mb-2'B><'col-sm-3'f>>" +  "<'row'<'col-sm-12'tr>>" + "<'row'<'col-sm-5'i><'col-sm-7'p>>";
        settings.buttons = ['csv', 'excel', 'pdf'];
    }

    destroyDatatable(datatable_name);

    $(datatable_name).dataTable(settings);
}

function ciReportForm(){
    $('#ci-report-form').validate({
        rules: {
            appraiser: {
                required: true
            },
            investigator: {
                required: true
            },
            narrative_summary: {
                required: true
            }
        },
        messages: {
            appraiser: {
                required: 'Please choose the appraiser'
            },
            investigator: {
                required: 'Please choose the investigator'
            },
            narrative_summary: {
                required: 'Please enter the narrative summary'
            }
        },
        errorPlacement: function (error, element) {
            if (element.hasClass('select2') || element.hasClass('modal-select2') || element.hasClass('offcanvas-select2')) {
              error.insertAfter(element.next('.select2-container'));
            }
            else if (element.parent('.input-group').length) {
              error.insertAfter(element.parent());
            }
            else {
              error.insertAfter(element);
            }
        },
        highlight: function(element) {
            var inputElement = $(element);
            if (inputElement.hasClass('select2-hidden-accessible')) {
              inputElement.next().find('.select2-selection__rendered').addClass('is-invalid');
            }
            else {
              inputElement.addClass('is-invalid');
            }
        },
        unhighlight: function(element) {
            var inputElement = $(element);
            if (inputElement.hasClass('select2-hidden-accessible')) {
              inputElement.next().find('.select2-selection__rendered').removeClass('is-invalid');
            }
            else {
              inputElement.removeClass('is-invalid');
            }
        },
        submitHandler: function(form) {
            const ci_report_id = $('#ci-report-id').text();
            const transaction = 'save ci report';
        
            $.ajax({
                type: 'POST',
                url: 'controller/ci-report-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction + '&ci_report_id=' + ci_report_id,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-data');
                },
                success: function (response) {
                    if (response.success) {                        
                        showNotification('Update CI Report Success', 'The CI report has been updated successfully.', 'success');
                    }
                    else {
                        if (response.isInactive) {
                            setNotification('User Inactive', response.message, 'danger');
                            window.location = 'logout.php?logout';
                        }
                        else {
                            showNotification('Transaction Error', response.message, 'danger');
                        }
                    }
                },
                error: function(xhr, status, error) {
                    var fullErrorMessage = `XHR status: ${status}, Error: ${error}`;
                    if (xhr.responseText) {
                        fullErrorMessage += `, Response: ${xhr.responseText}`;
                    }
                    showErrorDialog(fullErrorMessage);
                },
                complete: function() {
                    enableFormSubmitButton('submit-data', 'Save');
                    displayDetails('get ci report summary details');
                }
            });
        
            return false;
        }
    });
}

function ciReportRecommendationForm(){
    $('#ci-report-recommendation-form').validate({
        rules: {
            ci_character: {
                required: true
            },
            ci_capacity: {
                required: true
            },
            ci_capital: {
                required: true
            },
            ci_collateral: {
                required: true
            },
            ci_condition: {
                required: true
            },
            acceptability: {
                required: true
            },
            loanability: {
                required: true
            },
            cmap_result: {
                required: true
            },
            crif_result: {
                required: true
            },
            adverse: {
                required: true
            },
            times_accomodated: {
                required: true
            },
            cgmi_client_since: {
                required: true
            },
            recommendation: {
                required: true
            },
        },
        messages: {
            recommendation: {
                required: 'Please enter the recommendation'
            },
        },
        errorPlacement: function (error, element) {
            if (element.hasClass('select2') || element.hasClass('modal-select2') || element.hasClass('offcanvas-select2')) {
              error.insertAfter(element.next('.select2-container'));
            }
            else if (element.parent('.input-group').length) {
              error.insertAfter(element.parent());
            }
            else {
              error.insertAfter(element);
            }
        },
        highlight: function(element) {
            var inputElement = $(element);
            if (inputElement.hasClass('select2-hidden-accessible')) {
              inputElement.next().find('.select2-selection__rendered').addClass('is-invalid');
            }
            else {
              inputElement.addClass('is-invalid');
            }
        },
        unhighlight: function(element) {
            var inputElement = $(element);
            if (inputElement.hasClass('select2-hidden-accessible')) {
              inputElement.next().find('.select2-selection__rendered').removeClass('is-invalid');
            }
            else {
              inputElement.removeClass('is-invalid');
            }
        },
        submitHandler: function(form) {
            const ci_report_id = $('#ci-report-id').text();
            const transaction = 'save ci report recommendation';
        
            $.ajax({
                type: 'POST',
                url: 'controller/ci-report-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction + '&ci_report_id=' + ci_report_id,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-recommendation-data');
                },
                success: function (response) {
                    if (response.success) {                        
                        showNotification('Update Recommendation Success', 'The recommendation has been updated successfully.', 'success');
                    }
                    else {
                        if (response.isInactive) {
                            setNotification('User Inactive', response.message, 'danger');
                            window.location = 'logout.php?logout';
                        }
                        else {
                            showNotification('Transaction Error', response.message, 'danger');
                        }
                    }
                },
                error: function(xhr, status, error) {
                    var fullErrorMessage = `XHR status: ${status}, Error: ${error}`;
                    if (xhr.responseText) {
                        fullErrorMessage += `, Response: ${xhr.responseText}`;
                    }
                    showErrorDialog(fullErrorMessage);
                },
                complete: function() {
                    enableFormSubmitButton('submit-recommendation-data', 'Save');
                }
            });
        
            return false;
        }
    });
}

function ciReportResidenceForm(){
    $('#ci-residence-form').validate({
        rules: {
            ci_residence_contact_address: {
                required: true
            },
             ci_residence_rented_from: {
                required: {
                    depends: function(element) {
                        return $('#ci_residence_structure_type_id').val() === '3';
                    }
                }
            },
            ci_residence_tct_no: {
                required: {
                    depends: function(element) {
                        return $('#ci_residence_structure_type_id').val() === '1';
                    }
                }
            },
            ci_residence_city_id: {
                required: true
            },
        },
        messages: {
            ci_residence_contact_address: {
                required: 'Please enter the address'
            },
            ci_residence_rented_from: {
                required: 'Please enter the rented from'
            },
            ci_residence_tct_no: {
                required: 'Please enter the TCT No.'
            },
            ci_residence_city_id: {
                required: 'Please choose the city'
            },
        },
        errorPlacement: function (error, element) {
            if (element.hasClass('select2') || element.hasClass('modal-select2') || element.hasClass('offcanvas-select2')) {
              error.insertAfter(element.next('.select2-container'));
            }
            else if (element.parent('.input-group').length) {
              error.insertAfter(element.parent());
            }
            else {
              error.insertAfter(element);
            }
        },
        highlight: function(element) {
            var inputElement = $(element);
            if (inputElement.hasClass('select2-hidden-accessible')) {
              inputElement.next().find('.select2-selection__rendered').addClass('is-invalid');
            }
            else {
              inputElement.addClass('is-invalid');
            }
        },
        unhighlight: function(element) {
            var inputElement = $(element);
            if (inputElement.hasClass('select2-hidden-accessible')) {
              inputElement.next().find('.select2-selection__rendered').removeClass('is-invalid');
            }
            else {
              inputElement.removeClass('is-invalid');
            }
        },
        submitHandler: function(form) {
            const ci_report_id = $('#ci-report-id').text();
            const transaction = 'save ci report residence';
        
            $.ajax({
                type: 'POST',
                url: 'controller/ci-report-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction + '&ci_report_id=' + ci_report_id,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-ci-residence');
                },
                success: function (response) {
                    if (response.success) {                        
                        const notificationMessage = response.insertRecord ? 'Insert Residence Success' : 'Update Residence Success';
                        const notificationDescription = response.insertRecord ? 'The residence has been inserted successfully.' : 'The residence has been updated successfully.';
                        
                        showNotification(notificationMessage, notificationDescription, 'success');

                        ciReportResidenceTable('#ci-residence-table');
                        $('#ci-residence-modal').modal('hide');
                        
                        displayDetails('get ci report residence total details');
                        displayDetails('get ci report summary details');
                    }
                    else {
                        if (response.isInactive) {
                            setNotification('User Inactive', response.message, 'danger');
                            window.location = 'logout.php?logout';
                        }
                        else {
                            showNotification('Transaction Error', response.message, 'danger');
                        }
                    }
                },
                error: function(xhr, status, error) {
                    var fullErrorMessage = `XHR status: ${status}, Error: ${error}`;
                    if (xhr.responseText) {
                        fullErrorMessage += `, Response: ${xhr.responseText}`;
                    }
                    showErrorDialog(fullErrorMessage);
                },
                complete: function() {
                    enableFormSubmitButton('submit-ci-residence', 'Submit');
                }
            });
        
            return false;
        }
    });
}

function ciReportDependentsForm(){
    $('#ci-dependents-form').validate({
        rules: {
            ci_dependents_name: {
                required: true
            },
        },
        messages: {
            ci_dependents_name: {
                required: 'Please enter the name'
            },
        },
        errorPlacement: function (error, element) {
            if (element.hasClass('select2') || element.hasClass('modal-select2') || element.hasClass('offcanvas-select2')) {
              error.insertAfter(element.next('.select2-container'));
            }
            else if (element.parent('.input-group').length) {
              error.insertAfter(element.parent());
            }
            else {
              error.insertAfter(element);
            }
        },
        highlight: function(element) {
            var inputElement = $(element);
            if (inputElement.hasClass('select2-hidden-accessible')) {
              inputElement.next().find('.select2-selection__rendered').addClass('is-invalid');
            }
            else {
              inputElement.addClass('is-invalid');
            }
        },
        unhighlight: function(element) {
            var inputElement = $(element);
            if (inputElement.hasClass('select2-hidden-accessible')) {
              inputElement.next().find('.select2-selection__rendered').removeClass('is-invalid');
            }
            else {
              inputElement.removeClass('is-invalid');
            }
        },
        submitHandler: function(form) {
            const ci_report_id = $('#ci-report-id').text();
            const transaction = 'save ci report dependents';
        
            $.ajax({
                type: 'POST',
                url: 'controller/ci-report-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction + '&ci_report_id=' + ci_report_id,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-ci-dependents');
                },
                success: function (response) {
                    if (response.success) {                        
                        const notificationMessage = response.insertRecord ? 'Insert Dependent Success' : 'Update Dependent Success';
                        const notificationDescription = response.insertRecord ? 'The dependent has been inserted successfully.' : 'The dependent has been updated successfully.';
                        
                        showNotification(notificationMessage, notificationDescription, 'success');

                        ciReportDependentsTable('#ci-dependents-table');
                        $('#ci-dependents-modal').modal('hide');
                    }
                    else {
                        if (response.isInactive) {
                            setNotification('User Inactive', response.message, 'danger');
                            window.location = 'logout.php?logout';
                        }
                        else {
                            showNotification('Transaction Error', response.message, 'danger');
                        }
                    }
                },
                error: function(xhr, status, error) {
                    var fullErrorMessage = `XHR status: ${status}, Error: ${error}`;
                    if (xhr.responseText) {
                        fullErrorMessage += `, Response: ${xhr.responseText}`;
                    }
                    showErrorDialog(fullErrorMessage);
                },
                complete: function() {
                    enableFormSubmitButton('submit-ci-dependents', 'Submit');
                }
            });
        
            return false;
        }
    });
}

function ciReportBusinessForm(){
    $('#ci-business-form').validate({
        rules: {
            ci_business_business_name: {
                required: true
            },
            ci_business_description: {
                required: true
            },
            ci_business_contact_address: {
                required: true
            },
            ci_business_city_id: {
                required: true
            },
        },
        messages: {
            ci_business_business_name: {
                required: 'Please enter the business name'
            },
            ci_business_description: {
                required: 'Please enter the description'
            },
            ci_business_contact_address: {
                required: 'Please enter the address'
            },
            ci_business_city_id: {
                required: 'Please choose the city'
            },
        },
        errorPlacement: function (error, element) {
            if (element.hasClass('select2') || element.hasClass('modal-select2') || element.hasClass('offcanvas-select2')) {
              error.insertAfter(element.next('.select2-container'));
            }
            else if (element.parent('.input-group').length) {
              error.insertAfter(element.parent());
            }
            else {
              error.insertAfter(element);
            }
        },
        highlight: function(element) {
            var inputElement = $(element);
            if (inputElement.hasClass('select2-hidden-accessible')) {
              inputElement.next().find('.select2-selection__rendered').addClass('is-invalid');
            }
            else {
              inputElement.addClass('is-invalid');
            }
        },
        unhighlight: function(element) {
            var inputElement = $(element);
            if (inputElement.hasClass('select2-hidden-accessible')) {
              inputElement.next().find('.select2-selection__rendered').removeClass('is-invalid');
            }
            else {
              inputElement.removeClass('is-invalid');
            }
        },
        submitHandler: function(form) {
            const ci_report_id = $('#ci-report-id').text();
            const transaction = 'save ci report business';
        
            $.ajax({
                type: 'POST',
                url: 'controller/ci-report-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction + '&ci_report_id=' + ci_report_id,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-ci-business');
                },
                success: function (response) {
                    if (response.success) {                        
                        const notificationMessage = response.insertRecord ? 'Insert Business Success' : 'Update Business Success';
                        const notificationDescription = response.insertRecord ? 'The business has been inserted successfully.' : 'The business has been updated successfully.';
                        
                        showNotification(notificationMessage, notificationDescription, 'success');

                        ciReportBusinessTable('#ci-business-table');
                        $('#ci-business-modal').modal('hide');
                        displayDetails('get ci report business total details');
                        displayDetails('get ci report summary details');
                    }
                    else {
                        if (response.isInactive) {
                            setNotification('User Inactive', response.message, 'danger');
                            window.location = 'logout.php?logout';
                        }
                        else {
                            showNotification('Transaction Error', response.message, 'danger');
                        }
                    }
                },
                error: function(xhr, status, error) {
                    var fullErrorMessage = `XHR status: ${status}, Error: ${error}`;
                    if (xhr.responseText) {
                        fullErrorMessage += `, Response: ${xhr.responseText}`;
                    }
                    showErrorDialog(fullErrorMessage);
                },
                complete: function() {
                    enableFormSubmitButton('submit-ci-business', 'Submit');
                }
            });
        
            return false;
        }
    });
}

function ciReportEmploymentForm(){
    $('#ci-employment-form').validate({
        rules: {
            ci_employment_employment_name: {
                required: true
            },
            ci_employment_description: {
                required: true
            },
            ci_employment_contact_address: {
                required: true
            },
            ci_employment_city_id: {
                required: true
            },
        },
        messages: {
            ci_employment_employment_name: {
                required: 'Please enter the employment name'
            },
            ci_employment_description: {
                required: 'Please enter the description'
            },
            ci_employment_contact_address: {
                required: 'Please enter the address'
            },
            ci_employment_city_id: {
                required: 'Please choose the city'
            },
        },
        errorPlacement: function (error, element) {
            if (element.hasClass('select2') || element.hasClass('modal-select2') || element.hasClass('offcanvas-select2')) {
              error.insertAfter(element.next('.select2-container'));
            }
            else if (element.parent('.input-group').length) {
              error.insertAfter(element.parent());
            }
            else {
              error.insertAfter(element);
            }
        },
        highlight: function(element) {
            var inputElement = $(element);
            if (inputElement.hasClass('select2-hidden-accessible')) {
              inputElement.next().find('.select2-selection__rendered').addClass('is-invalid');
            }
            else {
              inputElement.addClass('is-invalid');
            }
        },
        unhighlight: function(element) {
            var inputElement = $(element);
            if (inputElement.hasClass('select2-hidden-accessible')) {
              inputElement.next().find('.select2-selection__rendered').removeClass('is-invalid');
            }
            else {
              inputElement.removeClass('is-invalid');
            }
        },
        submitHandler: function(form) {
            const ci_report_id = $('#ci-report-id').text();
            const transaction = 'save ci report employment';
        
            $.ajax({
                type: 'POST',
                url: 'controller/ci-report-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction + '&ci_report_id=' + ci_report_id,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-ci-employment');
                },
                success: function (response) {
                    if (response.success) {                        
                        const notificationMessage = response.insertRecord ? 'Insert Employment Success' : 'Update Employment Success';
                        const notificationDescription = response.insertRecord ? 'The employment has been inserted successfully.' : 'The employment has been updated successfully.';
                        
                        showNotification(notificationMessage, notificationDescription, 'success');

                        ciReportEmploymentTable('#ci-employment-table');
                        $('#ci-employment-modal').modal('hide');
                        displayDetails('get ci report employment total details');
                        displayDetails('get ci report summary details');
                    }
                    else {
                        if (response.isInactive) {
                            setNotification('User Inactive', response.message, 'danger');
                            window.location = 'logout.php?logout';
                        }
                        else {
                            showNotification('Transaction Error', response.message, 'danger');
                        }
                    }
                },
                error: function(xhr, status, error) {
                    var fullErrorMessage = `XHR status: ${status}, Error: ${error}`;
                    if (xhr.responseText) {
                        fullErrorMessage += `, Response: ${xhr.responseText}`;
                    }
                    showErrorDialog(fullErrorMessage);
                },
                complete: function() {
                    enableFormSubmitButton('submit-ci-employment', 'Submit');
                }
            });
        
            return false;
        }
    });
}

function ciReportBankForm(){
    $('#ci-bank-form').validate({
        rules: {
            ci_bank_bank_id: {
                required: true
            },
            ci_bank_bank_account_type_id: {
                required: true
            },
            ci_bank_account_name: {
                required: true
            },
            ci_bank_account_number: {
                required: true
            },
        },
        messages: {
            ci_bank_bank_id: {
                required: 'Please enter the bank'
            },
            ci_bank_bank_account_type_id: {
                required: 'Please choose the account type'
            },
            ci_bank_account_name: {
                required: 'Please enter the account name'
            },
            ci_bank_account_number: {
                required: 'Please enter the account number'
            },
        },
        errorPlacement: function (error, element) {
            if (element.hasClass('select2') || element.hasClass('modal-select2') || element.hasClass('offcanvas-select2')) {
              error.insertAfter(element.next('.select2-container'));
            }
            else if (element.parent('.input-group').length) {
              error.insertAfter(element.parent());
            }
            else {
              error.insertAfter(element);
            }
        },
        highlight: function(element) {
            var inputElement = $(element);
            if (inputElement.hasClass('select2-hidden-accessible')) {
              inputElement.next().find('.select2-selection__rendered').addClass('is-invalid');
            }
            else {
              inputElement.addClass('is-invalid');
            }
        },
        unhighlight: function(element) {
            var inputElement = $(element);
            if (inputElement.hasClass('select2-hidden-accessible')) {
              inputElement.next().find('.select2-selection__rendered').removeClass('is-invalid');
            }
            else {
              inputElement.removeClass('is-invalid');
            }
        },
        submitHandler: function(form) {
            const ci_report_id = $('#ci-report-id').text();
            const transaction = 'save ci report bank';
        
            $.ajax({
                type: 'POST',
                url: 'controller/ci-report-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction + '&ci_report_id=' + ci_report_id,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-ci-bank');
                },
                success: function (response) {
                    if (response.success) {                        
                        const notificationMessage = response.insertRecord ? 'Insert Bank Success' : 'Update Bank Success';
                        const notificationDescription = response.insertRecord ? 'The bank has been inserted successfully.' : 'The bank has been updated successfully.';
                        
                        showNotification(notificationMessage, notificationDescription, 'success');

                        ciReportBankTable('#ci-bank-table');
                        $('#ci-bank-modal').modal('hide');
                    }
                    else {
                        if (response.isInactive) {
                            setNotification('User Inactive', response.message, 'danger');
                            window.location = 'logout.php?logout';
                        }
                        else {
                            showNotification('Transaction Error', response.message, 'danger');
                        }
                    }
                },
                error: function(xhr, status, error) {
                    var fullErrorMessage = `XHR status: ${status}, Error: ${error}`;
                    if (xhr.responseText) {
                        fullErrorMessage += `, Response: ${xhr.responseText}`;
                    }
                    showErrorDialog(fullErrorMessage);
                },
                complete: function() {
                    enableFormSubmitButton('submit-ci-bank', 'Submit');
                }
            });
        
            return false;
        }
    });
}

function ciReportBankDepositsForm(){
    $('#ci-bank-deposits-form').validate({
        rules: {
            ci_bank_deposits_deposit_month: {
                required: true
            },
            ci_bank_deposits_amount: {
                required: true
            },
        },
        messages: {
            ci_bank_deposits_deposit_month: {
                required: 'Please choose the deposit date'
            },
            ci_bank_deposits_amount: {
                required: 'Please enter the amount'
            },
        },
        errorPlacement: function (error, element) {
            if (element.hasClass('select2') || element.hasClass('modal-select2') || element.hasClass('offcanvas-select2')) {
              error.insertAfter(element.next('.select2-container'));
            }
            else if (element.parent('.input-group').length) {
              error.insertAfter(element.parent());
            }
            else {
              error.insertAfter(element);
            }
        },
        highlight: function(element) {
            var inputElement = $(element);
            if (inputElement.hasClass('select2-hidden-accessible')) {
              inputElement.next().find('.select2-selection__rendered').addClass('is-invalid');
            }
            else {
              inputElement.addClass('is-invalid');
            }
        },
        unhighlight: function(element) {
            var inputElement = $(element);
            if (inputElement.hasClass('select2-hidden-accessible')) {
              inputElement.next().find('.select2-selection__rendered').removeClass('is-invalid');
            }
            else {
              inputElement.removeClass('is-invalid');
            }
        },
        submitHandler: function(form) {
            const ci_report_id = $('#ci-report-id').text();
            var ci_report_bank_id = sessionStorage.getItem('ci_report_bank_id');
            const transaction = 'save ci report bank deposits';
        
            $.ajax({
                type: 'POST',
                url: 'controller/ci-report-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction + '&ci_report_id=' + ci_report_id + '&ci_report_bank_id=' + ci_report_bank_id,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-ci-bank-deposits');
                },
                success: function (response) {
                    if (response.success) {                        
                        const notificationMessage = response.insertRecord ? 'Insert Deposit Success' : 'Update Deposit Success';
                        const notificationDescription = response.insertRecord ? 'The deposit has been inserted successfully.' : 'The deposit has been updated successfully.';
                        
                        showNotification(notificationMessage, notificationDescription, 'success');

                        ciReportBankDepositsTable('#ci-bank-deposits-table');
                        $('#ci-bank-deposits-modal').modal('hide');
                        $('#ci-bank-modal').modal('show');
                    }
                    else {
                        if (response.isInactive) {
                            setNotification('User Inactive', response.message, 'danger');
                            window.location = 'logout.php?logout';
                        }
                        else {
                            showNotification('Transaction Error', response.message, 'danger');
                        }
                    }
                },
                error: function(xhr, status, error) {
                    var fullErrorMessage = `XHR status: ${status}, Error: ${error}`;
                    if (xhr.responseText) {
                        fullErrorMessage += `, Response: ${xhr.responseText}`;
                    }
                    showErrorDialog(fullErrorMessage);
                },
                complete: function() {
                    enableFormSubmitButton('submit-ci-bank-deposits', 'Submit');
                }
            });
        
            return false;
        }
    });
}

function ciReportAppraisalSourceForm(){
    $('#ci-appraisal-source-form').validate({
        rules: {
            ci_appraisal_source_deposit_month: {
                required: true
            },
            ci_appraisal_source_amount: {
                required: true
            },
        },
        messages: {
            ci_appraisal_source_deposit_month: {
                required: 'Please choose the deposit date'
            },
            ci_appraisal_source_amount: {
                required: 'Please enter the amount'
            },
        },
        errorPlacement: function (error, element) {
            if (element.hasClass('select2') || element.hasClass('modal-select2') || element.hasClass('offcanvas-select2')) {
              error.insertAfter(element.next('.select2-container'));
            }
            else if (element.parent('.input-group').length) {
              error.insertAfter(element.parent());
            }
            else {
              error.insertAfter(element);
            }
        },
        highlight: function(element) {
            var inputElement = $(element);
            if (inputElement.hasClass('select2-hidden-accessible')) {
              inputElement.next().find('.select2-selection__rendered').addClass('is-invalid');
            }
            else {
              inputElement.addClass('is-invalid');
            }
        },
        unhighlight: function(element) {
            var inputElement = $(element);
            if (inputElement.hasClass('select2-hidden-accessible')) {
              inputElement.next().find('.select2-selection__rendered').removeClass('is-invalid');
            }
            else {
              inputElement.removeClass('is-invalid');
            }
        },
        submitHandler: function(form) {
            const ci_report_id = $('#ci-report-id').text();
            var ci_report_collateral_id = sessionStorage.getItem('ci_report_collateral_id');
            const transaction = 'save ci report appraisal source';
        
            $.ajax({
                type: 'POST',
                url: 'controller/ci-report-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction + '&ci_report_id=' + ci_report_id + '&ci_report_collateral_id=' + ci_report_collateral_id,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-ci-appraisal-source');
                },
                success: function (response) {
                    if (response.success) {                        
                        const notificationMessage = response.insertRecord ? 'Insert Deposit Success' : 'Update Deposit Success';
                        const notificationDescription = response.insertRecord ? 'The deposit has been inserted successfully.' : 'The deposit has been updated successfully.';
                        
                        showNotification(notificationMessage, notificationDescription, 'success');

                        ciReportAppraisalSourceTable('#ci-appraisal-source-table');
                        $('#ci-appraisal-source-modal').modal('hide');
                        $('#ci-collateral-modal').modal('show');
                    }
                    else {
                        if (response.isInactive) {
                            setNotification('User Inactive', response.message, 'danger');
                            window.location = 'logout.php?logout';
                        }
                        else {
                            showNotification('Transaction Error', response.message, 'danger');
                        }
                    }
                },
                error: function(xhr, status, error) {
                    var fullErrorMessage = `XHR status: ${status}, Error: ${error}`;
                    if (xhr.responseText) {
                        fullErrorMessage += `, Response: ${xhr.responseText}`;
                    }
                    showErrorDialog(fullErrorMessage);
                },
                complete: function() {
                    enableFormSubmitButton('submit-ci-appraisal-source', 'Submit');
                }
            });
        
            return false;
        }
    });
}

function ciReportLoanForm(){
    $('#ci-loan-form').validate({
        rules: {
            ci_loan_company: {
                required: true
            },
            ci_loan_loan_source: {
                required: true
            },
        },
        messages: {
            ci_loan_company: {
                required: 'Please enter the company'
            },
            ci_loan_loan_source: {
                required: 'Please choose the loan source'
            },
        },
        errorPlacement: function (error, element) {
            if (element.hasClass('select2') || element.hasClass('modal-select2') || element.hasClass('offcanvas-select2')) {
              error.insertAfter(element.next('.select2-container'));
            }
            else if (element.parent('.input-group').length) {
              error.insertAfter(element.parent());
            }
            else {
              error.insertAfter(element);
            }
        },
        highlight: function(element) {
            var inputElement = $(element);
            if (inputElement.hasClass('select2-hidden-accessible')) {
              inputElement.next().find('.select2-selection__rendered').addClass('is-invalid');
            }
            else {
              inputElement.addClass('is-invalid');
            }
        },
        unhighlight: function(element) {
            var inputElement = $(element);
            if (inputElement.hasClass('select2-hidden-accessible')) {
              inputElement.next().find('.select2-selection__rendered').removeClass('is-invalid');
            }
            else {
              inputElement.removeClass('is-invalid');
            }
        },
        submitHandler: function(form) {
            const ci_report_id = $('#ci-report-id').text();
            const transaction = 'save ci report loan';
        
            $.ajax({
                type: 'POST',
                url: 'controller/ci-report-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction + '&ci_report_id=' + ci_report_id,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-ci-loan');
                },
                success: function (response) {
                    if (response.success) {                        
                        const notificationMessage = response.insertRecord ? 'Insert Loan Success' : 'Update Loan Success';
                        const notificationDescription = response.insertRecord ? 'The loan has been inserted successfully.' : 'The loan has been updated successfully.';
                        
                        showNotification(notificationMessage, notificationDescription, 'success');

                        ciReportLoanTable('#ci-loan-table');
                        $('#ci-loan-modal').modal('hide');
                        displayDetails('get ci report summary details');
                                displayDetails('get ci report loans total details');
                    }
                    else {
                        if (response.isInactive) {
                            setNotification('User Inactive', response.message, 'danger');
                            window.location = 'logout.php?logout';
                        }
                        else {
                            showNotification('Transaction Error', response.message, 'danger');
                        }
                    }
                },
                error: function(xhr, status, error) {
                    var fullErrorMessage = `XHR status: ${status}, Error: ${error}`;
                    if (xhr.responseText) {
                        fullErrorMessage += `, Response: ${xhr.responseText}`;
                    }
                    showErrorDialog(fullErrorMessage);
                },
                complete: function() {
                    enableFormSubmitButton('submit-ci-loan', 'Submit');
                }
            });
        
            return false;
        }
    });
}

function ciReportCMAPForm(){
    $('#ci-cmap-form').validate({
        rules: {
            ci_cmap_cmap_report_type_id: {
                required: true
            },
            ci_cmap_defendant: {
                required: true
            },
            ci_cmap_plaintiff: {
                required: true
            },
        },
        messages: {
            ci_cmap_cmap_report_type_id: {
                required: 'Please choose the report type'
            },
            ci_cmap_defendant: {
                required: 'Please enter the defendant'
            },
            ci_cmap_plaintiff: {
                required: 'Please enter the plaintiff'
            },
        },
        errorPlacement: function (error, element) {
            if (element.hasClass('select2') || element.hasClass('modal-select2') || element.hasClass('offcanvas-select2')) {
              error.insertAfter(element.next('.select2-container'));
            }
            else if (element.parent('.input-group').length) {
              error.insertAfter(element.parent());
            }
            else {
              error.insertAfter(element);
            }
        },
        highlight: function(element) {
            var inputElement = $(element);
            if (inputElement.hasClass('select2-hidden-accessible')) {
              inputElement.next().find('.select2-selection__rendered').addClass('is-invalid');
            }
            else {
              inputElement.addClass('is-invalid');
            }
        },
        unhighlight: function(element) {
            var inputElement = $(element);
            if (inputElement.hasClass('select2-hidden-accessible')) {
              inputElement.next().find('.select2-selection__rendered').removeClass('is-invalid');
            }
            else {
              inputElement.removeClass('is-invalid');
            }
        },
        submitHandler: function(form) {
            const ci_report_id = $('#ci-report-id').text();
            const transaction = 'save ci report cmap';
        
            $.ajax({
                type: 'POST',
                url: 'controller/ci-report-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction + '&ci_report_id=' + ci_report_id,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-ci-cmap');
                },
                success: function (response) {
                    if (response.success) {                        
                        const notificationMessage = response.insertRecord ? 'Insert CMAP Success' : 'Update CMAP Success';
                        const notificationDescription = response.insertRecord ? 'The cmap has been inserted successfully.' : 'The cmap has been updated successfully.';
                        
                        showNotification(notificationMessage, notificationDescription, 'success');

                        ciReportCMAPTable('#ci-cmap-table');
                        $('#ci-cmap-modal').modal('hide');
                    }
                    else {
                        if (response.isInactive) {
                            setNotification('User Inactive', response.message, 'danger');
                            window.location = 'logout.php?logout';
                        }
                        else {
                            showNotification('Transaction Error', response.message, 'danger');
                        }
                    }
                },
                error: function(xhr, status, error) {
                    var fullErrorMessage = `XHR status: ${status}, Error: ${error}`;
                    if (xhr.responseText) {
                        fullErrorMessage += `, Response: ${xhr.responseText}`;
                    }
                    showErrorDialog(fullErrorMessage);
                },
                complete: function() {
                    enableFormSubmitButton('submit-ci-cmap', 'Submit');
                }
            });
        
            return false;
        }
    });
}

function ciReportCollateralForm(){
    $('#ci-collateral-form').validate({
        rules: {
            ci_collateral_appraisal_date: {
                required: true
            },
            ci_collateral_brand_id: {
                required: true
            },
            ci_collateral_description: {
                required: true
            },
        },
        messages: {
            ci_collateral_appraisal_date: {
                required: 'Please choose the appraisal date'
            },
            ci_collateral_brand_id: {
                required: 'Please choose the brand'
            },
            ci_collateral_description: {
                required: 'Please enter the description'
            },
        },
        errorPlacement: function (error, element) {
            if (element.hasClass('select2') || element.hasClass('modal-select2') || element.hasClass('offcanvas-select2')) {
              error.insertAfter(element.next('.select2-container'));
            }
            else if (element.parent('.input-group').length) {
              error.insertAfter(element.parent());
            }
            else {
              error.insertAfter(element);
            }
        },
        highlight: function(element) {
            var inputElement = $(element);
            if (inputElement.hasClass('select2-hidden-accessible')) {
              inputElement.next().find('.select2-selection__rendered').addClass('is-invalid');
            }
            else {
              inputElement.addClass('is-invalid');
            }
        },
        unhighlight: function(element) {
            var inputElement = $(element);
            if (inputElement.hasClass('select2-hidden-accessible')) {
              inputElement.next().find('.select2-selection__rendered').removeClass('is-invalid');
            }
            else {
              inputElement.removeClass('is-invalid');
            }
        },
        submitHandler: function(form) {
            const ci_report_id = $('#ci-report-id').text();
            const transaction = 'save ci report collateral';
        
            $.ajax({
                type: 'POST',
                url: 'controller/ci-report-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction + '&ci_report_id=' + ci_report_id,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-ci-collateral');
                },
                success: function (response) {
                    if (response.success) {                        
                        const notificationMessage = response.insertRecord ? 'Insert Collateral Success' : 'Update Collateral Success';
                        const notificationDescription = response.insertRecord ? 'The collateral has been inserted successfully.' : 'The collateral has been updated successfully.';
                        
                        showNotification(notificationMessage, notificationDescription, 'success');

                        ciReportCollateralTable('#ci-collateral-table');
                        $('#ci-collateral-modal').modal('hide');
                    }
                    else {
                        if (response.isInactive) {
                            setNotification('User Inactive', response.message, 'danger');
                            window.location = 'logout.php?logout';
                        }
                        else {
                            showNotification('Transaction Error', response.message, 'danger');
                        }
                    }
                },
                error: function(xhr, status, error) {
                    var fullErrorMessage = `XHR status: ${status}, Error: ${error}`;
                    if (xhr.responseText) {
                        fullErrorMessage += `, Response: ${xhr.responseText}`;
                    }
                    showErrorDialog(fullErrorMessage);
                },
                complete: function() {
                    enableFormSubmitButton('submit-ci-collateral', 'Submit');
                }
            });
        
            return false;
        }
    });
}

function ciReportAssetForm(){
    $('#ci-asset-form').validate({
        rules: {
            ci_asset_asset_type_id: {
                required: true
            },
            ci_asset_description: {
                required: true
            },
        },
        messages: {
            ci_asset_asset_type_id: {
                required: 'Please choose the asset type'
            },
            ci_asset_description: {
                required: 'Please choose the brand'
            },
        },
        errorPlacement: function (error, element) {
            if (element.hasClass('select2') || element.hasClass('modal-select2') || element.hasClass('offcanvas-select2')) {
              error.insertAfter(element.next('.select2-container'));
            }
            else if (element.parent('.input-group').length) {
              error.insertAfter(element.parent());
            }
            else {
              error.insertAfter(element);
            }
        },
        highlight: function(element) {
            var inputElement = $(element);
            if (inputElement.hasClass('select2-hidden-accessible')) {
              inputElement.next().find('.select2-selection__rendered').addClass('is-invalid');
            }
            else {
              inputElement.addClass('is-invalid');
            }
        },
        unhighlight: function(element) {
            var inputElement = $(element);
            if (inputElement.hasClass('select2-hidden-accessible')) {
              inputElement.next().find('.select2-selection__rendered').removeClass('is-invalid');
            }
            else {
              inputElement.removeClass('is-invalid');
            }
        },
        submitHandler: function(form) {
            const ci_report_id = $('#ci-report-id').text();
            const transaction = 'save ci report asset';
        
            $.ajax({
                type: 'POST',
                url: 'controller/ci-report-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction + '&ci_report_id=' + ci_report_id,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-ci-asset');
                },
                success: function (response) {
                    if (response.success) {                        
                        const notificationMessage = response.insertRecord ? 'Insert Asset Success' : 'Update Asset Success';
                        const notificationDescription = response.insertRecord ? 'The asset has been inserted successfully.' : 'The asset has been updated successfully.';
                        
                        showNotification(notificationMessage, notificationDescription, 'success');

                        ciReportAssetTable('#ci-asset-table');
                        $('#ci-asset-modal').modal('hide');
                        
                        displayDetails('get ci report asset total details');
                    }
                    else {
                        if (response.isInactive) {
                            setNotification('User Inactive', response.message, 'danger');
                            window.location = 'logout.php?logout';
                        }
                        else {
                            showNotification('Transaction Error', response.message, 'danger');
                        }
                    }
                },
                error: function(xhr, status, error) {
                    var fullErrorMessage = `XHR status: ${status}, Error: ${error}`;
                    if (xhr.responseText) {
                        fullErrorMessage += `, Response: ${xhr.responseText}`;
                    }
                    showErrorDialog(fullErrorMessage);
                },
                complete: function() {
                    enableFormSubmitButton('submit-ci-asset', 'Submit');
                }
            });
        
            return false;
        }
    });
}

function ciReportSetToDraftForm(){
    $('#ci-report-set-to-draft-form').validate({
        rules: {
            set_to_draft_reason: {
                required: true
            }
        },
        messages: {
            set_to_draft_reason: {
                required: 'Please enter the set to draft reason'
            }
        },
        errorPlacement: function (error, element) {
            if (element.hasClass('select2') || element.hasClass('modal-select2') || element.hasClass('offcanvas-select2')) {
              error.insertAfter(element.next('.select2-container'));
            }
            else if (element.parent('.input-group').length) {
              error.insertAfter(element.parent());
            }
            else {
              error.insertAfter(element);
            }
        },
        highlight: function(element) {
            var inputElement = $(element);
            if (inputElement.hasClass('select2-hidden-accessible')) {
              inputElement.next().find('.select2-selection__rendered').addClass('is-invalid');
            }
            else {
              inputElement.addClass('is-invalid');
            }
        },
        unhighlight: function(element) {
            var inputElement = $(element);
            if (inputElement.hasClass('select2-hidden-accessible')) {
              inputElement.next().find('.select2-selection__rendered').removeClass('is-invalid');
            }
            else {
              inputElement.removeClass('is-invalid');
            }
        },
        submitHandler: function(form) {
            const ci_report_id = $('#ci-report-id').text();
            const transaction = 'ci report set to draft';
        
            $.ajax({
                type: 'POST',
                url: 'controller/ci-report-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction + '&ci_report_id=' + ci_report_id,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-ci-report-set-to-draft');
                },
                success: function (response) {
                    if (response.success) {
                        setNotification('Set CI Report To Draft Success', 'The CI report has been set to draft successfully.', 'success');
                        window.location.reload();
                    }
                    else{
                        if (response.isInactive) {
                            setNotification('User Inactive', response.message, 'danger');
                            window.location = 'logout.php?logout';
                        } else {
                            showNotification('Transaction Error', response.message, 'danger');
                        }
                    }
                },
                error: function(xhr, status, error) {
                    var fullErrorMessage = `XHR status: ${status}, Error: ${error}`;
                    if (xhr.responseText) {
                        fullErrorMessage += `, Response: ${xhr.responseText}`;
                    }
                    showErrorDialog(fullErrorMessage);
                },
                complete: function() {
                    enableFormSubmitButton('submit-ci-report-set-to-draft', 'Submit');
                    $('#ci-report-set-to-draft-offcanvas').offcanvas('hide');
                }
            });
        
            return false;
        }
    });
}

function ciReportTradeReferenceForm(){
    $('#ci-trade-reference-form').validate({
        rules: {
            ci_report_trade_reference_supplier: {
                required: true
            },
        },
        messages: {
            ci_report_trade_reference_supplier: {
                required: 'Please enter the supplier'
            },
        },
        errorPlacement: function (error, element) {
            if (element.hasClass('select2') || element.hasClass('modal-select2') || element.hasClass('offcanvas-select2')) {
              error.insertAfter(element.next('.select2-container'));
            }
            else if (element.parent('.input-group').length) {
              error.insertAfter(element.parent());
            }
            else {
              error.insertAfter(element);
            }
        },
        highlight: function(element) {
            var inputElement = $(element);
            if (inputElement.hasClass('select2-hidden-accessible')) {
              inputElement.next().find('.select2-selection__rendered').addClass('is-invalid');
            }
            else {
              inputElement.addClass('is-invalid');
            }
        },
        unhighlight: function(element) {
            var inputElement = $(element);
            if (inputElement.hasClass('select2-hidden-accessible')) {
              inputElement.next().find('.select2-selection__rendered').removeClass('is-invalid');
            }
            else {
              inputElement.removeClass('is-invalid');
            }
        },
        submitHandler: function(form) {
            const ci_report_id = $('#ci-report-id').text();
            var ci_report_business_id = sessionStorage.getItem('ci_report_business_id');
            const transaction = 'save ci report trade reference';
        
            $.ajax({
                type: 'POST',
                url: 'controller/ci-report-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction + '&ci_report_id=' + ci_report_id + '&ci_report_business_id=' + ci_report_business_id,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-ci-trade-reference');
                },
                success: function (response) {
                    if (response.success) {                        
                        const notificationMessage = response.insertRecord ? 'Insert Trade Reference Success' : 'Update Trade Reference Success';
                        const notificationDescription = response.insertRecord ? 'The trade reference has been inserted successfully.' : 'The trade reference has been updated successfully.';
                        
                        showNotification(notificationMessage, notificationDescription, 'success');

                        ciReportTradeReferenceTable('#ci-trade-reference-table');
                        $('#ci-trade-reference-modal').modal('hide');
                        $('#ci-business-modal').modal('show');
                    }
                    else {
                        if (response.isInactive) {
                            setNotification('User Inactive', response.message, 'danger');
                            window.location = 'logout.php?logout';
                        }
                        else {
                            showNotification('Transaction Error', response.message, 'danger');
                        }
                    }
                },
                error: function(xhr, status, error) {
                    var fullErrorMessage = `XHR status: ${status}, Error: ${error}`;
                    if (xhr.responseText) {
                        fullErrorMessage += `, Response: ${xhr.responseText}`;
                    }
                    showErrorDialog(fullErrorMessage);
                },
                complete: function() {
                    enableFormSubmitButton('submit-ci-trade-reference', 'Submit');
                }
            });
        
            return false;
        }
    });
}

function ciFileForm(){
    $('#ci-report-files-form').validate({
        rules: {
            ci_file_type_id: {
                required: true
            },
            ci_file: {
                required: true
            },
        },
        messages: {
            ci_file_type_id: {
                required: 'Please choose the file type'
            },
            ci_file: {
                required: 'Please choose the file'
            },
        },
        errorPlacement: function (error, element) {
            if (element.hasClass('select2') || element.hasClass('modal-select2') || element.hasClass('offcanvas-select2')) {
              error.insertAfter(element.next('.select2-container'));
            }
            else if (element.parent('.input-group').length) {
              error.insertAfter(element.parent());
            }
            else {
              error.insertAfter(element);
            }
        },
        highlight: function(element) {
            var inputElement = $(element);
            if (inputElement.hasClass('select2-hidden-accessible')) {
              inputElement.next().find('.select2-selection__rendered').addClass('is-invalid');
            }
            else {
              inputElement.addClass('is-invalid');
            }
        },
        unhighlight: function(element) {
            var inputElement = $(element);
            if (inputElement.hasClass('select2-hidden-accessible')) {
              inputElement.next().find('.select2-selection__rendered').removeClass('is-invalid');
            }
            else {
              inputElement.removeClass('is-invalid');
            }
        },
        submitHandler: function(form) {
            const ci_report_id = $('#ci-report-id').text();
            const transaction = 'save ci report file';
    
            var formData = new FormData(form);
            formData.append('ci_report_id', ci_report_id);
            formData.append('transaction', transaction);
        
            $.ajax({
                type: 'POST',
                url: 'controller/ci-report-controller.php',
                data: formData,
                processData: false,
                contentType: false,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-ci-files');
                },
                success: function (response) {
                    if (response.success) {
                        showNotification('Inser CI Report File Success', 'The CI report has been inserted successfully', 'success');
                        reloadDatatable('#ci-files-table');
                        $('#ci-files-modal').modal('hide');
                    }
                    else {
                        if (response.isInactive) {
                            setNotification('User Inactive', response.message, 'danger');
                            window.location = 'logout.php?logout';
                        }
                        else {
                            showNotification('Transaction Error', response.message, 'danger');
                        }
                    }
                },
                error: function(xhr, status, error) {
                    var fullErrorMessage = `XHR status: ${status}, Error: ${error}`;
                    if (xhr.responseText) {
                        fullErrorMessage += `, Response: ${xhr.responseText}`;
                    }
                    showErrorDialog(fullErrorMessage);
                },
                complete: function() {
                    enableFormSubmitButton('submit-ci-files', 'Submit');
                }
            });
        
            return false;
        }
    });
}

function displayDetails(transaction){
    switch (transaction) {
        case 'get ci report details':
            var ci_report_id = $('#ci-report-id').text();
            
            $.ajax({
                url: 'controller/ci-report-controller.php',
                method: 'POST',
                dataType: 'json',
                data: {
                    ci_report_id : ci_report_id, 
                    transaction : transaction
                },
                beforeSend: function() {
                    resetForm('ci-report-form');
                },
                success: function(response) {
                    if (response.success) {
                        $('#narrative_summary').val(response.narrative_summary);
                        $('#purpose_of_loan').val(response.purpose_of_loan);
                        $('#customer_name').val(response.customer_name);
                        $('#ci_type').val(response.ci_type);
                        $('#summary-narrative-summary').text(response.narrative_summary);
                        $('#summary-purpose-of-loan').text(response.purpose_of_loan);
                        $('#summary-appraiser').text(response.appraiserName);
                        $('#summary-ci').text(response.investigatorName);

                        checkOptionExist('#appraiser', response.appraiser, '');
                        checkOptionExist('#investigator', response.investigator, '');
                    } 
                    else {
                        if(response.isInactive){
                            window.location = 'logout.php?logout';
                        }
                        else{
                            showNotification('Get CI Report Details Error', response.message, 'danger');
                        }
                    }
                },
                error: function(xhr, status, error) {
                    var fullErrorMessage = `XHR status: ${status}, Error: ${error}`;
                    if (xhr.responseText) {
                        fullErrorMessage += `, Response: ${xhr.responseText}`;
                    }
                    showErrorDialog(fullErrorMessage);
                }
            });
            break;
        case 'get ci report recommendation details':
            var ci_report_id = $('#ci-report-id').text();
            
            $.ajax({
                url: 'controller/ci-report-controller.php',
                method: 'POST',
                dataType: 'json',
                data: {
                    ci_report_id : ci_report_id, 
                    transaction : transaction
                },
                beforeSend: function() {
                    resetForm('ci-report-recommendation-form');
                },
                success: function(response) {
                    if (response.success) {
                        $('#recommendation').val(response.recommendation);
                        $('#times_accomodated').val(response.times_accomodated);
                        $('#cgmi_client_since').val(response.cgmi_client_since);

                        checkOptionExist('#ci_character', response.ci_character, '');
                        checkOptionExist('#ci_capacity', response.ci_capacity, '');
                        checkOptionExist('#ci_capital', response.ci_capital, '');
                        checkOptionExist('#ci_collateral', response.ci_collateral, '');
                        checkOptionExist('#ci_condition', response.ci_condition, '');
                        checkOptionExist('#acceptability', response.acceptability, '');
                        checkOptionExist('#loanability', response.loanability, '');
                        checkOptionExist('#cmap_result', response.cmap_result, '');
                        checkOptionExist('#crif_result', response.crif_result, '');
                        checkOptionExist('#adverse', response.adverse, '');
                    } 
                    else {
                        if(response.isInactive){
                            window.location = 'logout.php?logout';
                        }
                        else{
                            showNotification('Get CI Report Details Error', response.message, 'danger');
                        }
                    }
                },
                error: function(xhr, status, error) {
                    var fullErrorMessage = `XHR status: ${status}, Error: ${error}`;
                    if (xhr.responseText) {
                        fullErrorMessage += `, Response: ${xhr.responseText}`;
                    }
                    showErrorDialog(fullErrorMessage);
                }
            });
            break;
        case 'get ci report residence details':
            var ci_report_residence_id = sessionStorage.getItem('ci_report_residence_id');
            
            $.ajax({
                url: 'controller/ci-report-controller.php',
                method: 'POST',
                dataType: 'json',
                data: {
                    ci_report_residence_id : ci_report_residence_id, 
                    transaction : transaction
                },
                beforeSend: function() {
                    resetForm('ci-residence-form');
                },
                success: function(response) {
                    if (response.success) {
                        $('#ci_report_residence_id').val(ci_report_residence_id);
                        $('#ci_residence_contact_address').val(response.address);
                        $('#ci_residence_prev_address').val(response.prev_address);
                        $('#ci_residence_length_stay_year').val(response.length_stay_year);
                        $('#ci_residence_length_stay_month').val(response.length_stay_month);
                        $('#ci_residence_rented_from').val(response.rented_from);
                        $('#ci_residence_rent_amount').val(response.rent_amount);
                        $('#ci_residence_estimated_value').val(response.estimated_value);
                        $('#ci_residence_residence_age').val(response.residence_age);
                        $('#ci_residence_lot_area').val(response.lot_area);
                        $('#ci_residence_floor_area').val(response.floor_area);
                        $('#ci_residence_furnishing_appliance').val(response.furnishing_appliance);
                        $('#ci_residence_vehicle_owned').val(response.vehicle_owned);
                        $('#ci_residence_real_estate_owned').val(response.real_estate_owned);
                        $('#ci_residence_accessible_to').val(response.accessible_to);
                        $('#ci_residence_nearest_corner').val(response.nearest_corner);
                        $('#ci_residence_informant').val(response.informant);
                        $('#ci_residence_informant_address').val(response.informant_address);
                        $('#ci_residence_personal_expense').val(response.personal_expense);
                        $('#ci_residence_utilities_expense').val(response.utilities_expense);
                        $('#ci_residence_other_expense').val(response.other_expense);
                        $('#ci_residence_total_expense').val(response.total);
                        $('#ci_residence_tct_no').val(response.tct_no);
                        $('#ci_residence_remarks').val(response.remarks);

                        checkOptionExist('#ci_residence_city_id', response.city_id, '');
                        checkOptionExist('#ci_residence_prev_city_id', response.prev_city_id, '');
                        checkOptionExist('#ci_residence_residence_type_id', response.residence_type_id, '');
                        checkOptionExist('#ci_residence_structure_type_id', response.structure_type_id, '');
                        checkOptionExist('#ci_residence_building_make_id', response.building_make_id, '');
                        checkOptionExist('#ci_residence_neighborhood_type_id', response.neighborhood_type_id, '');
                        checkOptionExist('#ci_residence_income_level_id', response.income_level_id, '');
                    } 
                    else {
                        if(response.isInactive){
                            window.location = 'logout.php?logout';
                        }
                        else{
                            showNotification('Get CI Report Details Error', response.message, 'danger');
                        }
                    }
                },
                error: function(xhr, status, error) {
                    var fullErrorMessage = `XHR status: ${status}, Error: ${error}`;
                    if (xhr.responseText) {
                        fullErrorMessage += `, Response: ${xhr.responseText}`;
                    }
                    showErrorDialog(fullErrorMessage);
                }
            });
            break;
        case 'get ci report residence total details':
            var ci_report_id = $('#ci-report-id').text();
            
            $.ajax({
                url: 'controller/ci-report-controller.php',
                method: 'POST',
                dataType: 'json',
                data: {
                    ci_report_id : ci_report_id, 
                    transaction : transaction
                },
                success: function(response) {
                    if (response.success) {
                        $('#ci-residence-rental-amount-summary').text(response.rentalAmountTotal);
                        $('#ci-residence-personal-expense-summary').text(response.personalExpenseTotal);
                        $('#ci-residence-utilities-expense-summary').text(response.utilitiesExpenseTotal);
                        $('#ci-residence-other-expense-summary').text(response.otherExpenseTotal);
                        $('#ci-residence-expense-total-summary').text(response.totalExpenseTotal);
                    } 
                    else {
                        if(response.isInactive){
                            window.location = 'logout.php?logout';
                        }
                        else{
                            showNotification('Get CI Report Details Error', response.message, 'danger');
                        }
                    }
                },
                error: function(xhr, status, error) {
                    var fullErrorMessage = `XHR status: ${status}, Error: ${error}`;
                    if (xhr.responseText) {
                        fullErrorMessage += `, Response: ${xhr.responseText}`;
                    }
                    showErrorDialog(fullErrorMessage);
                }
            });
            break;
        case 'get ci report business details':
            var ci_report_business_id = sessionStorage.getItem('ci_report_business_id');
            
            $.ajax({
                url: 'controller/ci-report-controller.php',
                method: 'POST',
                dataType: 'json',
                data: {
                    ci_report_business_id : ci_report_business_id, 
                    transaction : transaction
                },
                beforeSend: function() {
                    resetForm('ci-business-form');
                },
                success: function(response) {
                    if (response.success) {
                        $('#ci_report_business_id').val(ci_report_business_id);
                        $('#ci_business_business_name').val(response.business_name);
                        $('#ci_business_description').val(response.description);
                        $('#ci_business_contact_address').val(response.address);
                        $('#ci_business_length_stay_year').val(response.length_stay_year);
                        $('#ci_business_length_stay_month').val(response.length_stay_month);
                        $('#ci_business_registered_with').val(response.registered_with);
                        $('#ci_business_organization').val(response.organization);
                        $('#ci_business_date_organized').val(response.date_organized);
                        $('#ci_business_no_employee').val(response.no_employee);
                        $('#ci_business_customer').val(response.customer);
                        $('#ci_business_contact_person').val(response.contact_person);
                        $('#ci_business_landlord').val(response.landlord);
                        $('#ci_business_rental_amount').val(response.rental_amount);
                        $('#ci_business_machineries').val(response.machineries);
                        $('#ci_business_branches').val(response.branch);
                        $('#ci_business_fixtures').val(response.fixtures);
                        $('#ci_business_vehicle').val(response.vehicle);
                        $('#ci_business_trade_reference').val(response.trade_reference);
                        $('#ci_business_gross_monthly_sale').val(response.gross_monthly_sale);
                        $('#ci_business_monthly_income').val(response.monthly_income);
                        $('#ci_business_inventory').val(response.inventory);
                        $('#ci_business_receivable').val(response.receivable);
                        $('#ci_business_fixed_asset').val(response.fixed_asset);
                        $('#ci_business_liabilities').val(response.liabilities);
                        $('#ci_business_capital').val(response.capital);
                        $('#ci_business_remarks').val(response.remarks);
                        $('#ci_business_major_bank_id').val(response.major_bank_id);

                        checkOptionExist('#ci_business_city_id', response.city_id, '');
                        checkOptionExist('#ci_business_business_location_type_id', response.business_location_type_id, '');
                        checkOptionExist('#ci_business_building_make_id', response.building_make_id, '');
                        checkOptionExist('#ci_business_business_premises_id', response.business_premises_id, '');
                        checkOptionExist('#ci_business_facility_condition', response.facility_condition, '');

                        calculateBusinessCapital();
                    } 
                    else {
                        if(response.isInactive){
                            window.location = 'logout.php?logout';
                        }
                        else{
                            showNotification('Get CI Report Details Error', response.message, 'danger');
                        }
                    }
                },
                error: function(xhr, status, error) {
                    var fullErrorMessage = `XHR status: ${status}, Error: ${error}`;
                    if (xhr.responseText) {
                        fullErrorMessage += `, Response: ${xhr.responseText}`;
                    }
                    showErrorDialog(fullErrorMessage);
                }
            });
            break;
        case 'get ci report business total details':
            var ci_report_id = $('#ci-report-id').text();
            
            $.ajax({
                url: 'controller/ci-report-controller.php',
                method: 'POST',
                dataType: 'json',
                data: {
                    ci_report_id : ci_report_id, 
                    transaction : transaction
                },
                success: function(response) {
                    if (response.success) {
                        $('#ci-business-gross-monthly-sales-summary').text(response.grossMonthlySaleTotal);
                        $('#ci-business-monthly-income-summary').text(response.monthlyIncomeTotal);
                        $('#ci-business-inventory-summary').text(response.InventoryTotal);
                        $('#ci-business-receivable-summary').text(response.receivableTotal);
                        $('#ci-business-fixed-asset-summary').text(response.fixedAssetTotal);
                        $('#ci-business-liability-summary').text(response.liabilitiesTotal);
                        $('#ci-business-capital-summary').text(response.capitalTotal);
                    } 
                    else {
                        if(response.isInactive){
                            window.location = 'logout.php?logout';
                        }
                        else{
                            showNotification('Get CI Report Details Error', response.message, 'danger');
                        }
                    }
                },
                error: function(xhr, status, error) {
                    var fullErrorMessage = `XHR status: ${status}, Error: ${error}`;
                    if (xhr.responseText) {
                        fullErrorMessage += `, Response: ${xhr.responseText}`;
                    }
                    showErrorDialog(fullErrorMessage);
                }
            });
            break;
        case 'get ci report employment details':
            var ci_report_employment_id = sessionStorage.getItem('ci_report_employment_id');
            
            $.ajax({
                url: 'controller/ci-report-controller.php',
                method: 'POST',
                dataType: 'json',
                data: {
                    ci_report_employment_id : ci_report_employment_id, 
                    transaction : transaction
                },
                beforeSend: function() {
                    resetForm('ci-employment-form');
                },
                success: function(response) {
                    if (response.success) {
                        $('#ci_report_employment_id').val(ci_report_employment_id);
                        $('#ci_employment_employment_name').val(response.employment_name);
                        $('#ci_employment_description').val(response.description);
                        $('#ci_employment_contact_address').val(response.address);
                        $('#ci_employment_department').val(response.department);
                        $('#ci_employment_rank').val(response.rank);
                        $('#ci_employment_position').val(response.position);
                        $('#ci_employment_status').val(response.status);
                        $('#ci_employment_length_stay_year').val(response.length_stay_year);
                        $('#ci_employment_length_stay_month').val(response.length_stay_month);
                        $('#ci_employment_pres_length_stay_year').val(response.pres_length_stay_year);
                        $('#ci_employment_pres_length_stay_month').val(response.pres_length_stay_month);
                        $('#ci_employment_informant').val(response.informant);
                        $('#ci_employment_informant_address').val(response.informant_address);
                        $('#ci_employment_net_salary').val(response.net_salary);
                        $('#ci_employment_commission').val(response.commission);
                        $('#ci_employment_allowance').val(response.allowance);
                        $('#ci_employment_other_income').val(response.other_income);
                        $('#ci_employment_grand_total').val(response.grand_total);
                        $('#ci_employment_remarks').val(response.remarks);

                        checkOptionExist('#ci_employment_city_id', response.city_id, '');
                    } 
                    else {
                        if(response.isInactive){
                            window.location = 'logout.php?logout';
                        }
                        else{
                            showNotification('Get CI Report Details Error', response.message, 'danger');
                        }
                    }
                },
                error: function(xhr, status, error) {
                    var fullErrorMessage = `XHR status: ${status}, Error: ${error}`;
                    if (xhr.responseText) {
                        fullErrorMessage += `, Response: ${xhr.responseText}`;
                    }
                    showErrorDialog(fullErrorMessage);
                }
            });
            break;
        case 'get ci report employment total details':
            var ci_report_id = $('#ci-report-id').text();
            
            $.ajax({
                url: 'controller/ci-report-controller.php',
                method: 'POST',
                dataType: 'json',
                data: {
                    ci_report_id : ci_report_id, 
                    transaction : transaction
                },
                success: function(response) {
                    if (response.success) {
                        $('#ci-employment-net-salary-summary').text(response.netSalaryTotal);
                        $('#ci-employment-commission-summary').text(response.commissionTotal);
                        $('#ci-employment-allowance-summary').text(response.allowanceTotal);
                        $('#ci-employment-other-income-summary').text(response.otherIncomeTotal);
                        $('#ci-employment-grand-total-summary').text(response.grandTotal);
                    } 
                    else {
                        if(response.isInactive){
                            window.location = 'logout.php?logout';
                        }
                        else{
                            showNotification('Get CI Report Details Error', response.message, 'danger');
                        }
                    }
                },
                error: function(xhr, status, error) {
                    var fullErrorMessage = `XHR status: ${status}, Error: ${error}`;
                    if (xhr.responseText) {
                        fullErrorMessage += `, Response: ${xhr.responseText}`;
                    }
                    showErrorDialog(fullErrorMessage);
                }
            });
            break;
        case 'get ci report bank details':
            var ci_report_bank_id = sessionStorage.getItem('ci_report_bank_id');
            
            $.ajax({
                url: 'controller/ci-report-controller.php',
                method: 'POST',
                dataType: 'json',
                data: {
                    ci_report_bank_id : ci_report_bank_id, 
                    transaction : transaction
                },
                beforeSend: function() {
                    resetForm('ci-bank-form');
                },
                success: function(response) {
                    if (response.success) {
                        $('#ci_report_bank_id').val(ci_report_bank_id);
                        $('#ci_bank_account_name').val(response.account_name);
                        $('#ci_bank_account_number').val(response.account_number);
                        $('#ci_bank_date_open').val(response.date_open);
                        $('#ci_bank_bank_adb_id').val(response.bank_adb_id);
                        $('#ci_bank_informant').val(response.informant);
                        $('#ci_bank_remarks').val(response.remarks);
                        $('#ci_bank_bank_id').val(response.bank_id);

                        checkOptionExist('#ci_bank_bank_account_type_id', response.bank_account_type_id, '');
                        checkOptionExist('#ci_bank_currency_id', response.currency_id, '');
                        checkOptionExist('#ci_bank_bank_handling_type_id', response.bank_handling_type_id, '');
                    } 
                    else {
                        if(response.isInactive){
                            window.location = 'logout.php?logout';
                        }
                        else{
                            showNotification('Get CI Report Details Error', response.message, 'danger');
                        }
                    }
                },
                error: function(xhr, status, error) {
                    var fullErrorMessage = `XHR status: ${status}, Error: ${error}`;
                    if (xhr.responseText) {
                        fullErrorMessage += `, Response: ${xhr.responseText}`;
                    }
                    showErrorDialog(fullErrorMessage);
                }
            });
            break;
        case 'get ci report bank deposits details':
            var ci_report_bank_deposits_id = sessionStorage.getItem('ci_report_bank_deposits_id');
            
            $.ajax({
                url: 'controller/ci-report-controller.php',
                method: 'POST',
                dataType: 'json',
                data: {
                    ci_report_bank_deposits_id : ci_report_bank_deposits_id, 
                    transaction : transaction
                },
                beforeSend: function() {
                    resetForm('ci-bank-form');
                },
                success: function(response) {
                    if (response.success) {
                        $('#ci_report_bank_deposits_id').val(ci_report_bank_deposits_id);
                        $('#ci_bank_deposits_deposit_month').val(response.deposit_month);
                        $('#ci_bank_deposits_amount').val(response.amount);
                        $('#ci_bank_deposits_remarks').val(response.remarks);
                    } 
                    else {
                        if(response.isInactive){
                            window.location = 'logout.php?logout';
                        }
                        else{
                            showNotification('Get CI Report Details Error', response.message, 'danger');
                        }
                    }
                },
                error: function(xhr, status, error) {
                    var fullErrorMessage = `XHR status: ${status}, Error: ${error}`;
                    if (xhr.responseText) {
                        fullErrorMessage += `, Response: ${xhr.responseText}`;
                    }
                    showErrorDialog(fullErrorMessage);
                }
            });
            break;
        case 'get ci report loan details':
            var ci_report_loan_id = sessionStorage.getItem('ci_report_loan_id');
            
            $.ajax({
                url: 'controller/ci-report-controller.php',
                method: 'POST',
                dataType: 'json',
                data: {
                    ci_report_loan_id : ci_report_loan_id, 
                    transaction : transaction
                },
                beforeSend: function() {
                    resetForm('ci-loan-form');
                },
                success: function(response) {
                    if (response.success) {
                        $('#ci_report_loan_id').val(ci_report_loan_id);
                        $('#ci_loan_company').val(response.company);
                        $('#ci_loan_informant').val(response.informant);
                        $('#ci_loan_account_name').val(response.account_name);
                        $('#ci_loan_availed_date').val(response.availed_date);
                        $('#ci_loan_maturity_date').val(response.maturity_date);
                        $('#ci_loan_term').val(response.term);
                        $('#ci_loan_pn_amount').val(response.pn_amount);
                        $('#ci_loan_outstanding_balance').val(response.outstanding_balance);
                        $('#ci_loan_repayment').val(response.repayment);
                        $('#ci_loan_remarks').val(response.remarks);

                        checkOptionExist('#ci_loan_loan_source', response.loan_source, '');
                        checkOptionExist('#ci_loan_loan_type_id', response.loan_type_id, '');
                        checkOptionExist('#ci_loan_handling', response.handling, '');
                    } 
                    else {
                        if(response.isInactive){
                            window.location = 'logout.php?logout';
                        }
                        else{
                            showNotification('Get CI Report Details Error', response.message, 'danger');
                        }
                    }
                },
                error: function(xhr, status, error) {
                    var fullErrorMessage = `XHR status: ${status}, Error: ${error}`;
                    if (xhr.responseText) {
                        fullErrorMessage += `, Response: ${xhr.responseText}`;
                    }
                    showErrorDialog(fullErrorMessage);
                }
            });
            break;
        case 'get ci report cmap details':
            var ci_report_cmap_id = sessionStorage.getItem('ci_report_cmap_id');
            
            $.ajax({
                url: 'controller/ci-report-controller.php',
                method: 'POST',
                dataType: 'json',
                data: {
                    ci_report_cmap_id : ci_report_cmap_id, 
                    transaction : transaction
                },
                beforeSend: function() {
                    resetForm('ci-cmap-form');
                },
                success: function(response) {
                    if (response.success) {
                        $('#ci_report_cmap_id').val(ci_report_cmap_id);
                        $('#ci_cmap_defendant').val(response.defendants);
                        $('#ci_cmap_plaintiff').val(response.plaintiff);
                        $('#ci_cmap_nature_of_case').val(response.nature_of_case);
                        $('#ci_cmap_trial_court').val(response.trial_court);
                        $('#ci_cmap_sala_no').val(response.sala_no);
                        $('#ci_cmap_case_no').val(response.case_no);
                        $('#ci_cmap_reported_date').val(response.reported_date);
                        $('#ci_cmap_remarks').val(response.remarks);

                        checkOptionExist('#ci_cmap_cmap_report_type_id', response.cmap_report_type_id, '');
                    } 
                    else {
                        if(response.isInactive){
                            window.location = 'logout.php?logout';
                        }
                        else{
                            showNotification('Get CI Report Details Error', response.message, 'danger');
                        }
                    }
                },
                error: function(xhr, status, error) {
                    var fullErrorMessage = `XHR status: ${status}, Error: ${error}`;
                    if (xhr.responseText) {
                        fullErrorMessage += `, Response: ${xhr.responseText}`;
                    }
                    showErrorDialog(fullErrorMessage);
                }
            });
            break;
        case 'get ci report collateral details':
            var ci_report_collateral_id = sessionStorage.getItem('ci_report_collateral_id');
            
            $.ajax({
                url: 'controller/ci-report-controller.php',
                method: 'POST',
                dataType: 'json',
                data: {
                    ci_report_collateral_id : ci_report_collateral_id, 
                    transaction : transaction
                },
                beforeSend: function() {
                    resetForm('ci-collateral-form');
                },
                success: function(response) {
                    if (response.success) {
                        $('#ci_report_collateral_id').val(ci_report_collateral_id);
                        $('#ci_collateral_appraisal_date').val(response.appraisal_date);
                        $('#ci_collateral_description').val(response.description);
                        $('#ci_collateral_year_model').val(response.year_model);
                        $('#ci_collateral_plate_no').val(response.plate_no);
                        $('#ci_collateral_motor_no').val(response.motor_no);
                        $('#ci_collateral_serial_no').val(response.serial_no);
                        $('#ci_collateral_mvr_file_no').val(response.mvr_file_no);
                        $('#ci_collateral_cr_no').val(response.cr_no);
                        $('#ci_collateral_or_no').val(response.or_no);
                        $('#ci_collateral_registered_owner').val(response.registered_owner);
                        $('#ci_collateral_appraised_value').val(response.appraised_value);
                        $('#ci_collateral_loannable_value').val(response.loannable_value);
                        $('#ci_collateral_remarks').val(response.remarks);
                        $('#ci_collateral_color_id').val(response.color_id);

                        checkOptionExist('#ci_collateral_brand_id', response.brand_id, '');
                    } 
                    else {
                        if(response.isInactive){
                            window.location = 'logout.php?logout';
                        }
                        else{
                            showNotification('Get CI Report Details Error', response.message, 'danger');
                        }
                    }
                },
                error: function(xhr, status, error) {
                    var fullErrorMessage = `XHR status: ${status}, Error: ${error}`;
                    if (xhr.responseText) {
                        fullErrorMessage += `, Response: ${xhr.responseText}`;
                    }
                    showErrorDialog(fullErrorMessage);
                }
            });
            break;
        case 'get ci report appraisal source details':
            var ci_report_appraisal_source_id = sessionStorage.getItem('ci_report_appraisal_source_id');
            
            $.ajax({
                url: 'controller/ci-report-controller.php',
                method: 'POST',
                dataType: 'json',
                data: {
                    ci_report_appraisal_source_id : ci_report_appraisal_source_id, 
                    transaction : transaction
                },
                beforeSend: function() {
                    resetForm('ci-bank-form');
                },
                success: function(response) {
                    if (response.success) {
                        $('#ci_report_appraisal_source_id').val(ci_report_appraisal_source_id);
                        $('#ci_appraisal_source_source').val(response.source);
                        $('#ci_appraisal_source_amount').val(response.amount);
                        $('#ci_appraisal_source_remarks').val(response.remarks);
                    } 
                    else {
                        if(response.isInactive){
                            window.location = 'logout.php?logout';
                        }
                        else{
                            showNotification('Get CI Report Details Error', response.message, 'danger');
                        }
                    }
                },
                error: function(xhr, status, error) {
                    var fullErrorMessage = `XHR status: ${status}, Error: ${error}`;
                    if (xhr.responseText) {
                        fullErrorMessage += `, Response: ${xhr.responseText}`;
                    }
                    showErrorDialog(fullErrorMessage);
                }
            });
            break;
        case 'get ci report asset details':
            var ci_report_asset_id = sessionStorage.getItem('ci_report_asset_id');
            
            $.ajax({
                url: 'controller/ci-report-controller.php',
                method: 'POST',
                dataType: 'json',
                data: {
                    ci_report_asset_id : ci_report_asset_id, 
                    transaction : transaction
                },
                beforeSend: function() {
                    resetForm('ci-asset-form');
                },
                success: function(response) {
                    if (response.success) {
                        $('#ci_report_asset_id').val(ci_report_asset_id);
                        $('#ci_asset_description').val(response.description);
                        $('#ci_asset_value').val(response.value);
                        $('#ci_asset_remarks').val(response.remarks);

                        checkOptionExist('#ci_asset_asset_type_id', response.asset_type_id, '');
                    } 
                    else {
                        if(response.isInactive){
                            window.location = 'logout.php?logout';
                        }
                        else{
                            showNotification('Get CI Report Details Error', response.message, 'danger');
                        }
                    }
                },
                error: function(xhr, status, error) {
                    var fullErrorMessage = `XHR status: ${status}, Error: ${error}`;
                    if (xhr.responseText) {
                        fullErrorMessage += `, Response: ${xhr.responseText}`;
                    }
                    showErrorDialog(fullErrorMessage);
                }
            });
            break;
        case 'get ci report asset total details':
            var ci_report_id = $('#ci-report-id').text();
            
            $.ajax({
                url: 'controller/ci-report-controller.php',
                method: 'POST',
                dataType: 'json',
                data: {
                    ci_report_id : ci_report_id, 
                    transaction : transaction
                },
                success: function(response) {
                    if (response.success) {
                        $('#ci-asset-total-summary').text(response.assetTotal);
                    } 
                    else {
                        if(response.isInactive){
                            window.location = 'logout.php?logout';
                        }
                        else{
                            showNotification('Get CI Report Details Error', response.message, 'danger');
                        }
                    }
                },
                error: function(xhr, status, error) {
                    var fullErrorMessage = `XHR status: ${status}, Error: ${error}`;
                    if (xhr.responseText) {
                        fullErrorMessage += `, Response: ${xhr.responseText}`;
                    }
                    showErrorDialog(fullErrorMessage);
                }
            });
            break;
        case 'get ci report loans total details':
            var ci_report_id = $('#ci-report-id').text();
            
            $.ajax({
                url: 'controller/ci-report-controller.php',
                method: 'POST',
                dataType: 'json',
                data: {
                    ci_report_id : ci_report_id, 
                    transaction : transaction
                },
                success: function(response) {
                    if (response.success) {
                        $('#ci-loans-pn-amount-summary').text(response.pnAmount);
                        $('#ci-loans-repayment-summary').text(response.repayment);
                        $('#ci-loans-oustanding-balance-summary').text(response.outstandingBalance);
                    } 
                    else {
                        if(response.isInactive){
                            window.location = 'logout.php?logout';
                        }
                        else{
                            showNotification('Get CI Report Details Error', response.message, 'danger');
                        }
                    }
                },
                error: function(xhr, status, error) {
                    var fullErrorMessage = `XHR status: ${status}, Error: ${error}`;
                    if (xhr.responseText) {
                        fullErrorMessage += `, Response: ${xhr.responseText}`;
                    }
                    showErrorDialog(fullErrorMessage);
                }
            });
            break;
        case 'get ci report dependents details':
            var ci_report_dependents_id = sessionStorage.getItem('ci_report_dependents_id');
            
            $.ajax({
                url: 'controller/ci-report-controller.php',
                method: 'POST',
                dataType: 'json',
                data: {
                    ci_report_dependents_id : ci_report_dependents_id, 
                    transaction : transaction
                },
                beforeSend: function() {
                    resetForm('ci-dependents-form');
                },
                success: function(response) {
                    if (response.success) {
                        $('#ci_report_dependents_id').val(ci_report_dependents_id);
                        $('#ci_dependents_name').val(response.name);
                        $('#ci_dependents_age').val(response.age);
                        $('#ci_dependents_school').val(response.school);
                        $('#ci_dependents_employment').val(response.employment);
                        $('#ci_dependents_remarks').val(response.remarks);
                    } 
                    else {
                        if(response.isInactive){
                            window.location = 'logout.php?logout';
                        }
                        else{
                            showNotification('Get CI Report Details Error', response.message, 'danger');
                        }
                    }
                },
                error: function(xhr, status, error) {
                    var fullErrorMessage = `XHR status: ${status}, Error: ${error}`;
                    if (xhr.responseText) {
                        fullErrorMessage += `, Response: ${xhr.responseText}`;
                    }
                    showErrorDialog(fullErrorMessage);
                }
            });
            break;
        case 'get ci report trade reference details':
            var ci_report_trade_reference_id = sessionStorage.getItem('ci_report_trade_reference_id');
            
            $.ajax({
                url: 'controller/ci-report-controller.php',
                method: 'POST',
                dataType: 'json',
                data: {
                    ci_report_trade_reference_id : ci_report_trade_reference_id, 
                    transaction : transaction
                },
                beforeSend: function() {
                    resetForm('ci-asset-form');
                },
                success: function(response) {
                    if (response.success) {
                        $('#ci_report_trade_reference_id').val(ci_report_trade_reference_id);
                        $('#ci_report_trade_reference_supplier').val(response.supplier);
                        $('#ci_report_trade_reference_contact_person').val(response.contact_person);
                        $('#ci_report_trade_reference_years_of_transaction').val(response.years_of_transaction);
                        $('#ci_report_trade_reference_remarks').val(response.remarks);

                        checkOptionExist('#ci_asset_asset_type_id', response.asset_type_id, '');
                    } 
                    else {
                        if(response.isInactive){
                            window.location = 'logout.php?logout';
                        }
                        else{
                            showNotification('Get CI Report Details Error', response.message, 'danger');
                        }
                    }
                },
                error: function(xhr, status, error) {
                    var fullErrorMessage = `XHR status: ${status}, Error: ${error}`;
                    if (xhr.responseText) {
                        fullErrorMessage += `, Response: ${xhr.responseText}`;
                    }
                    showErrorDialog(fullErrorMessage);
                }
            });
            break;
    }
}

function calculateResidenceExpenseTotal() {
    const personal = parseFloat($('#ci_residence_personal_expense').val()) || 0;
    const utilities = parseFloat($('#ci_residence_utilities_expense').val()) || 0;
    const other = parseFloat($('#ci_residence_other_expense').val()) || 0;

    const total = personal + utilities + other;

    $('#ci_residence_total_expense').val(total.toFixed(2));
}

function calculateEmploymentIncomeTotal() {
    const net_salary = parseFloat($('#ci_employment_net_salary').val()) || 0;
    const commission = parseFloat($('#ci_employment_commission').val()) || 0;
    const allowance = parseFloat($('#ci_employment_allowance').val()) || 0;
    const other = parseFloat($('#ci_employment_other_income').val()) || 0;

    const total = net_salary + commission + allowance + other;

    $('#ci_employment_grand_total').val(total.toFixed(2));
}

function calculateBusinessCapital(){
    const ci_business_inventory = parseFloat($('#ci_business_inventory').val()) || 0;
    const ci_business_receivable = parseFloat($('#ci_business_receivable').val()) || 0;
    const ci_business_fixed_asset = parseFloat($('#ci_business_fixed_asset').val()) || 0;
    const ci_business_liabilities = parseFloat($('#ci_business_liabilities').val()) || 0;

    const total =(ci_business_inventory + ci_business_receivable + ci_business_fixed_asset)  - ci_business_liabilities;

    $('#ci_business_capital').val(total.toFixed(2));
}

function personalInformationSummary(){
    const type = 'personal information summary';
    var customer_id = $('#customer-id').val();
            
    $.ajax({
        url: 'view/_customer_generation.php',
        method: 'POST',
        dataType: 'json',
        data: {
            customer_id : customer_id, 
            type : type
        },
        success: function(response) {
            document.getElementById('personal-information-summary').innerHTML = response[0].personalInformationSummary;
        },
        error: function(xhr, status, error) {
            var fullErrorMessage = `XHR status: ${status}, Error: ${error}`;
            if (xhr.responseText) {
                fullErrorMessage += `, Response: ${xhr.responseText}`;
            }
            showErrorDialog(fullErrorMessage);
        }
    });
}