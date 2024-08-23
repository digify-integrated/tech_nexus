(function($) {
    'use strict';

    $(function() {
        if($('#pdc-management-table').length){
            pdcManagementTable('#pdc-management-table');
        }

        if($('#pdc-management-form').length){
            pdcManagementForm();
        }

        if($('#pdc-on-hold-form').length){
            pdcOnHoldForm();
        }

        if($('#pdc-cancel-form').length){
            pdcCancelForm();
        }

        if($('#mass-pdc-cancel-form').length){
            massPDCCancelForm();
        }

        if($('#pdc-pulled-out-form').length){
            pdcPulledOutForm();
        }

        if($('#pdc-redeposit-form').length){
            pdcRedepositForm();
        }

        if($('#pdc-reverse-form').length){
            pdcReverseForm();
        }

        if($('#mass-pdc-reverse-form').length){
            massPDCReverseForm();
        }

        if($('#import-form').length){
            importForm();
        }

        if($('#loan-collection-id').length){
            displayDetails('get pdc management details');
        }

        $(document).on('click','.delete-pdc-management',function() {
            const loan_collection_id = $(this).data('pdc-management-id');
            const transaction = 'delete PDC management';
    
            Swal.fire({
                title: 'Confirm PDC Management Deletion',
                text: 'Are you sure you want to delete this PDC management?',
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
                        url: 'controller/pdc-management-controller.php',
                        dataType: 'json',
                        data: {
                            loan_collection_id : loan_collection_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                showNotification('Delete PDC Management Success', 'The PDC management has been deleted successfully.', 'success');
                                reloadDatatable('#pdc-management-table');
                            }
                            else {
                                if (response.isInactive) {
                                    setNotification('User Inactive', response.message, 'danger');
                                    window.location = 'logout.php?logout';
                                }
                                else if (response.notExist) {
                                    showNotification('Delete PDC Management Error', 'The PDC management does not exist.', 'danger');
                                    reloadDatatable('#pdc-management-table');
                                }
                                else {
                                    showNotification('Delete PDC Management Error', response.message, 'danger');
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

        $(document).on('click','#delete-pdc-management',function() {
            let loan_collection_id = [];
            const transaction = 'delete multiple PDC management';

            $('.datatable-checkbox-children').each((index, element) => {
                if ($(element).is(':checked')) {
                    loan_collection_id.push(element.value);
                }
            });
    
            if(loan_collection_id.length > 0){
                Swal.fire({
                    title: 'Confirm Multiple PDC Managements Deletion',
                    text: 'Are you sure you want to delete these PDC managements?',
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
                            url: 'controller/pdc-management-controller.php',
                            dataType: 'json',
                            data: {
                                loan_collection_id: loan_collection_id,
                                transaction : transaction
                            },
                            success: function (response) {
                                if (response.success) {
                                    showNotification('Delete PDC Management Success', 'The selected PDC managements have been deleted successfully.', 'success');
                                        reloadDatatable('#pdc-management-table');
                                }
                                else {
                                    if (response.isInactive) {
                                        setNotification('User Inactive', response.message, 'danger');
                                        window.location = 'logout.php?logout';
                                    }
                                    else {
                                        showNotification('Delete PDC Management Error', response.message, 'danger');
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
                            complete: function(){
                                toggleHideActionDropdown();
                            }
                        });
                        
                        return false;
                    }
                });
            }
            else{
                showNotification('Deletion Multiple PDC Management Error', 'Please select the PDC managements you wish to delete.', 'danger');
            }
        });

        $(document).on('click','#delete-pdc-management-details',function() {
            const loan_collection_id = $('#loan-collection-id').text();
            const transaction = 'delete PDC management';
    
            Swal.fire({
                title: 'Confirm PDC Management Deletion',
                text: 'Are you sure you want to delete this PDC management?',
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
                        url: 'controller/pdc-management-controller.php',
                        dataType: 'json',
                        data: {
                            loan_collection_id : loan_collection_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                setNotification('Deleted PDC Management Success', 'The PDC management has been deleted successfully.', 'success');
                                window.location = 'pdc-management.php';
                            }
                            else {
                                if (response.isInactive) {
                                    setNotification('User Inactive', response.message, 'danger');
                                    window.location = 'logout.php?logout';
                                }
                                else if (response.notExist) {
                                    window.location = '404.php';
                                }
                                else {
                                    showNotification('Delete PDC Management Error', response.message, 'danger');
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

        $(document).on('click','#discard-create',function() {
            discardCreate('pdc-management.php');
        });

        $(document).on('click','#edit-form',function() {
            displayDetails('get PDC management details');

            enableForm();
        });

        $(document).on('click','#apply-filter',function() {
            pdcManagementTable('#pdc-management-table');
        });

        $(document).on('click','#tag-pdc-as-deposited-details',function() {
            const loan_collection_id = $('#loan-collection-id').text();
            const transaction = 'tag pdc as deposited';
    
            Swal.fire({
                title: 'Confirm PDC As Deposited',
                text: 'Are you sure you want to tag these PDC as deposited?',
                icon: 'warning',
                showCancelButton: !0,
                confirmButtonText: 'Deposited',
                cancelButtonText: 'Cancel',
                confirmButtonClass: 'btn btn-success mt-2',
                cancelButtonClass: 'btn btn-secondary ms-2 mt-2',
                buttonsStyling: !1
            }).then(function(result) {
                if (result.value) {
                    $.ajax({
                        type: 'POST',
                        url: 'controller/pdc-management-controller.php',
                        dataType: 'json',
                        data: {
                            loan_collection_id : loan_collection_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                setNotification('Tag PDC As Deposited Success', 'The PDC have been tagged as deposited successfully.', 'success');
                                window.location.reload();
                            }
                            else {
                                if (response.isInactive) {
                                    setNotification('User Inactive', response.message, 'danger');
                                    window.location = 'logout.php?logout';
                                }
                                else if (response.notExist) {
                                    window.location = '404.php';
                                }
                                else {
                                    showNotification('Tag PDC As Deposited Error', response.message, 'danger');
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

        $(document).on('click','#tag-pdc-as-deposited',function() {
            let loan_collection_id = [];
            const transaction = 'tag multiple pdc as deposited';

            $('.datatable-checkbox-children').each((index, element) => {
                if ($(element).is(':checked')) {
                    loan_collection_id.push(element.value);
                }
            });
    
            if(loan_collection_id.length > 0){
                Swal.fire({
                    title: 'Confirm Multiple PDC As Deposited',
                    text: 'Are you sure you want to tag these PDC as deposited?',
                    icon: 'info',
                    showCancelButton: !0,
                    confirmButtonText: 'Deposited',
                    cancelButtonText: 'Cancel',
                    confirmButtonClass: 'btn btn-success mt-2',
                    cancelButtonClass: 'btn btn-secondary ms-2 mt-2',
                    buttonsStyling: !1
                }).then(function(result) {
                    if (result.value) {
                        $.ajax({
                            type: 'POST',
                            url: 'controller/pdc-management-controller.php',
                            dataType: 'json',
                            data: {
                                loan_collection_id: loan_collection_id,
                                transaction : transaction
                            },
                            success: function (response) {
                                if (response.success) {
                                    showNotification('Tag PDC As Deposited Success', 'The selected PDC have been tagged as deposited successfully.', 'success');
                                    reloadDatatable('#pdc-management-table');
                                }
                                else {
                                    if (response.isInactive) {
                                        setNotification('User Inactive', response.message, 'danger');
                                        window.location = 'logout.php?logout';
                                    }
                                    else {
                                        showNotification('Tag PDC As Deposited Error', response.message, 'danger');
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
                            complete: function(){
                                toggleHideActionDropdown();
                            }
                        });
                        
                        return false;
                    }
                });
            }
            else{
                showNotification('Tagging Multiple PDC As Deposited Error', 'Please select the PDC you wish to tag as desposited.', 'danger');
            }
        });

        $(document).on('click','#tag-pdc-as-for-deposit-details',function() {
            const loan_collection_id = $('#loan-collection-id').text();
            const transaction = 'tag pdc as for deposit';
    
            Swal.fire({
                title: 'Confirm PDC As For Deposit',
                text: 'Are you sure you want to tag this PDC as for deposit?',
                icon: 'warning',
                showCancelButton: !0,
                confirmButtonText: 'For Deposit',
                cancelButtonText: 'Cancel',
                confirmButtonClass: 'btn btn-success mt-2',
                cancelButtonClass: 'btn btn-secondary ms-2 mt-2',
                buttonsStyling: !1
            }).then(function(result) {
                if (result.value) {
                    $.ajax({
                        type: 'POST',
                        url: 'controller/pdc-management-controller.php',
                        dataType: 'json',
                        data: {
                            loan_collection_id : loan_collection_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                setNotification('Tag PDC As For Deposit Success', 'The PDC have been tagged as for deposit successfully.', 'success');
                                window.location.reload();
                            }
                            else {
                                if (response.isInactive) {
                                    setNotification('User Inactive', response.message, 'danger');
                                    window.location = 'logout.php?logout';
                                }
                                else if (response.notExist) {
                                    window.location = '404.php';
                                }
                                else {
                                    showNotification('Tag PDC As For Deposit Error', response.message, 'danger');
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
        
        $(document).on('click','#tag-pdc-as-for-deposit',function() {
            let loan_collection_id = [];
            const transaction = 'tag multiple pdc as for deposit';

            $('.datatable-checkbox-children').each((index, element) => {
                if ($(element).is(':checked')) {
                    loan_collection_id.push(element.value);
                }
            });
    
            if(loan_collection_id.length > 0){
                Swal.fire({
                    title: 'Confirm Multiple PDC As For Deposit',
                    text: 'Are you sure you want to tag these PDC as for deposit?',
                    icon: 'info',
                    showCancelButton: !0,
                    confirmButtonText: 'For Deposit',
                    cancelButtonText: 'Cancel',
                    confirmButtonClass: 'btn btn-success mt-2',
                    cancelButtonClass: 'btn btn-secondary ms-2 mt-2',
                    buttonsStyling: !1
                }).then(function(result) {
                    if (result.value) {
                        $.ajax({
                            type: 'POST',
                            url: 'controller/pdc-management-controller.php',
                            dataType: 'json',
                            data: {
                                loan_collection_id: loan_collection_id,
                                transaction : transaction
                            },
                            success: function (response) {
                                if (response.success) {
                                    showNotification('Tag PDC As For Deposit Success', 'The selected PDC have been tagged as for deposit successfully.', 'success');
                                    reloadDatatable('#pdc-management-table');
                                }
                                else {
                                    if (response.isInactive) {
                                        setNotification('User Inactive', response.message, 'danger');
                                        window.location = 'logout.php?logout';
                                    }
                                    else {
                                        showNotification('Tag PDC As For Deposit Error', response.message, 'danger');
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
                            complete: function(){
                                toggleHideActionDropdown();
                            }
                        });
                        
                        return false;
                    }
                });
            }
            else{
                showNotification('Tagging Multiple PDC As For Deposit Error', 'Please select the PDC you wish to tag as for deposit.', 'danger');
            }
        }); 

        $(document).on('click','#tag-pdc-as-cleared-details',function() {
            const loan_collection_id = $('#loan-collection-id').text();
            const transaction = 'tag pdc as cleared';
    
            Swal.fire({
                title: 'Confirm PDC As Cleared',
                text: 'Are you sure you want to tag this PDC as cleared?',
                icon: 'warning',
                showCancelButton: !0,
                confirmButtonText: 'Cleared',
                cancelButtonText: 'Cancel',
                confirmButtonClass: 'btn btn-success mt-2',
                cancelButtonClass: 'btn btn-secondary ms-2 mt-2',
                buttonsStyling: !1
            }).then(function(result) {
                if (result.value) {
                    $.ajax({
                        type: 'POST',
                        url: 'controller/pdc-management-controller.php',
                        dataType: 'json',
                        data: {
                            loan_collection_id : loan_collection_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                setNotification('Tag PDC As Cleared Success', 'The PDC have been tagged as cleared successfully.', 'success');
                                window.location.reload();
                            }
                            else {
                                if (response.isInactive) {
                                    setNotification('User Inactive', response.message, 'danger');
                                    window.location = 'logout.php?logout';
                                }
                                else if (response.notExist) {
                                    window.location = '404.php';
                                }
                                else {
                                    showNotification('Tag PDC As Cleared Error', response.message, 'danger');
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

        $(document).on('click','#tag-pdc-as-cleared',function() {
            let loan_collection_id = [];
            const transaction = 'tag multiple pdc as cleared';

            $('.datatable-checkbox-children').each((index, element) => {
                if ($(element).is(':checked')) {
                    loan_collection_id.push(element.value);
                }
            });
    
            if(loan_collection_id.length > 0){
                Swal.fire({
                    title: 'Confirm Multiple PDC As Cleared',
                    text: 'Are you sure you want to tag these PDC as cleared?',
                    icon: 'info',
                    showCancelButton: !0,
                    confirmButtonText: 'Cleared',
                    cancelButtonText: 'Cancel',
                    confirmButtonClass: 'btn btn-success mt-2',
                    cancelButtonClass: 'btn btn-secondary ms-2 mt-2',
                    buttonsStyling: !1
                }).then(function(result) {
                    if (result.value) {
                        $.ajax({
                            type: 'POST',
                            url: 'controller/pdc-management-controller.php',
                            dataType: 'json',
                            data: {
                                loan_collection_id: loan_collection_id,
                                transaction : transaction
                            },
                            success: function (response) {
                                if (response.success) {
                                    showNotification('Tag PDC As Cleared Success', 'The selected PDC have been tagged as cleared successfully.', 'success');
                                    reloadDatatable('#pdc-management-table');
                                }
                                else {
                                    if (response.isInactive) {
                                        setNotification('User Inactive', response.message, 'danger');
                                        window.location = 'logout.php?logout';
                                    }
                                    else {
                                        showNotification('Tag PDC As Cleared Error', response.message, 'danger');
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
                            complete: function(){
                                toggleHideActionDropdown();
                            }
                        });
                        
                        return false;
                    }
                });
            }
            else{
                showNotification('Tagging Multiple PDC As Cleared Error', 'Please select the PDC you wish to tag as cleared.', 'danger');
            }
        });

        $(document).on('click','#duplicate-cancelled-pdc',function() {
            let loan_collection_id = [];
            const transaction = 'duplicate multiple cancelled pdc';

            $('.datatable-checkbox-children').each((index, element) => {
                if ($(element).is(':checked')) {
                    loan_collection_id.push(element.value);
                }
            });
    
            if(loan_collection_id.length > 0){
                Swal.fire({
                    title: 'Confirm Multiple PDC Duplication',
                    text: 'Are you sure you want to duplicate these PDC?',
                    icon: 'info',
                    showCancelButton: !0,
                    confirmButtonText: 'Duplicate',
                    cancelButtonText: 'Cancel',
                    confirmButtonClass: 'btn btn-success mt-2',
                    cancelButtonClass: 'btn btn-secondary ms-2 mt-2',
                    buttonsStyling: !1
                }).then(function(result) {
                    if (result.value) {
                        $.ajax({
                            type: 'POST',
                            url: 'controller/pdc-management-controller.php',
                            dataType: 'json',
                            data: {
                                loan_collection_id: loan_collection_id,
                                transaction : transaction
                            },
                            success: function (response) {
                                if (response.success) {
                                    showNotification('Duplication PDC Success', 'The selected PDC have been duplicated successfully.', 'success');
                                    reloadDatatable('#pdc-management-table');
                                }
                                else {
                                    if (response.isInactive) {
                                        setNotification('User Inactive', response.message, 'danger');
                                        window.location = 'logout.php?logout';
                                    }
                                    else {
                                        showNotification('Duplication PDC Error', response.message, 'danger');
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
                            complete: function(){
                                toggleHideActionDropdown();
                            }
                        });
                        
                        return false;
                    }
                });
            }
            else{
                showNotification('Multiple PDC Duplication Error', 'Please select the PDC you wish to duplicate.', 'danger');
            }
        });

        $(document).on('change','#pdc_type',function() {
            var pdc_type = $(this).val();

            $('.field').addClass('d-none');

            checkOptionExist('#sales_proposal_id', '', '');
            checkOptionExist('#product_id', '', '');
            checkOptionExist('#customer_id', '', '');

            switch (pdc_type) {
                case 'Loan':
                    $('#loan_field').removeClass('d-none');
                    checkOptionExist('#product_id', '', '');
                    checkOptionExist('#customer_id', '', '');
                    break;
                case 'Product':
                    $('#product_field').removeClass('d-none');
                    checkOptionExist('#sales_proposal_id', '', '');
                    checkOptionExist('#customer_id', '', '');
                    break;
                case 'Customer':
                    $('#customer_field').removeClass('d-none');
                    checkOptionExist('#sales_proposal_id', '', '');
                    checkOptionExist('#product_id', '', '');
                    break;
            }
        });

        $(document).on('click','#print',function() {
            var checkedBoxes = [];

            $('.datatable-checkbox-children').each((index, element) => {
                if ($(element).is(':checked')) {
                    checkedBoxes.push(element.value);
                }
            });

            if(checkedBoxes != ''){
                window.open('pdc-print.php?id=' + checkedBoxes, '_blank');
            }
            else{
                showNotification('Print PDC Error', 'No selected pdc.', 'danger');
            }
        });

        $(document).on('click','#print-acknowledgement',function() {
            var checkedBoxes = [];

            $('.datatable-checkbox-children').each((index, element) => {
                if ($(element).is(':checked')) {
                    checkedBoxes.push(element.value);
                }
            });

            if(checkedBoxes != ''){
                window.open('pdc-acknowledgement-print.php?id=' + checkedBoxes, '_blank');
            }
            else{
                showNotification('Print PDC Acknowledgement Error', 'No selected pdc.', 'danger');
            }
        });

        $(document).on('click','#print-check',function() {
            var checkedBoxes = [];

            $('.datatable-checkbox-children').each((index, element) => {
                if ($(element).is(':checked')) {
                    checkedBoxes.push(element.value);
                }
            });

            if(checkedBoxes != ''){
                window.open('check_print.php?id=' + checkedBoxes, '_blank');
            }
            else{
                showNotification('Print Check Error', 'No selected pdc.', 'danger');
            }
        });

        $(document).on('click','#print-check-details',function() {
            const loan_collection_id = $('#loan-collection-id').text();
                window.open('check_print.php?id=' + loan_collection_id, '_blank');
        });
    });
})(jQuery);

function pdcManagementTable(datatable_name, buttons = false, show_all = false){
    const type = 'pdc management table';
    var filter_check_date_start_date = $('#filter_check_date_start_date').val();
    var filter_check_date_end_date = $('#filter_check_date_end_date').val();

    var filter_redeposit_date_start_date = $('#filter_redeposit_date_start_date').val();
    var filter_redeposit_date_end_date = $('#filter_redeposit_date_end_date').val();

    var filter_onhold_date_start_date = $('#filter_onhold_date_start_date').val();
    var filter_onhold_date_end_date = $('#filter_onhold_date_end_date').val();

    var filter_for_deposit_date_start_date = $('#filter_for_deposit_date_start_date').val();
    var filter_for_deposit_date_end_date = $('#filter_for_deposit_date_end_date').val();

    var filter_deposit_date_start_date = $('#filter_deposit_date_start_date').val();
    var filter_deposit_date_end_date = $('#filter_deposit_date_end_date').val();

    var filter_reversed_date_start_date = $('#filter_reversed_date_start_date').val();
    var filter_reversed_date_end_date = $('#filter_reversed_date_end_date').val();

    var filter_pulled_out_date_start_date = $('#filter_pulled_out_date_start_date').val();
    var filter_pulled_out_date_end_date = $('#filter_pulled_out_date_end_date').val();

    var filter_cancellation_date_start_date = $('#filter_cancellation_date_start_date').val();
    var filter_cancellation_date_end_date = $('#filter_cancellation_date_end_date').val();

    var filter_clear_date_start_date = $('#filter_clear_date_start_date').val();
    var filter_clear_date_end_date = $('#filter_clear_date_end_date').val();

    var pdc_filter_values = [];

    $('.pdc-management-status-filter:checked').each(function() {
        pdc_filter_values.push($(this).val());
    });

    var filter_pdc_management_status = pdc_filter_values.join(', ');
    var settings;

    const column = [ 
        { 'data' : 'CHECK_BOX' },
        { 'data' : 'ACTION' },
        { 'data' : 'CHECK_DATE' },
        { 'data' : 'REDEPOSIT_DATE' },
        { 'data' : 'CHECK_NUMBER' },
        { 'data' : 'PAYMENT_AMOUNT' },
        { 'data' : 'BANK_BRANCH' },
        { 'data' : 'PAYMENT_DETAILS' },
        { 'data' : 'STATUS' },
        { 'data' : 'LOAN_NUMBER' },
        { 'data' : 'CUSTOMER' },
        { 'data' : 'PRODUCT' }
    ];

    const column_definition = [
        { 'width': '1%','bSortable': false, 'aTargets': 0 },
        { 'width': '15%','bSortable': false, 'aTargets': 1 },
        { 'width': 'auto', 'type': 'date', 'aTargets': 2 },
        { 'width': 'auto', 'type': 'date', 'aTargets': 3 },
        { 'width': 'auto', 'aTargets': 4 },
        { 'width': '5%', 'aTargets': 5 },
        { 'width': 'auto', 'aTargets': 6 },
        { 'width': 'auto', 'aTargets': 7 },
        { 'width': 'auto', 'aTargets': 8 },
        { 'width': 'auto', 'aTargets': 9 },
        { 'width': 'auto', 'aTargets': 10 },
        { 'width': 'auto', 'aTargets': 11 }
    ];

    const length_menu = show_all ? [[-1], ['All']] : [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']];

    settings = {
        'ajax': { 
            'url' : 'view/_pdc_management_generation.php',
            'method' : 'POST',
            'dataType': 'json',
            'data': {'type' : type, 
                'filter_check_date_start_date' : filter_check_date_start_date, 
                'filter_check_date_end_date' : filter_check_date_end_date, 
                'filter_redeposit_date_start_date' : filter_redeposit_date_start_date, 
                'filter_redeposit_date_end_date' : filter_redeposit_date_end_date, 
                'filter_onhold_date_start_date' : filter_onhold_date_start_date, 
                'filter_onhold_date_end_date' : filter_onhold_date_end_date, 
                'filter_for_deposit_date_start_date' : filter_for_deposit_date_start_date, 
                'filter_for_deposit_date_end_date' : filter_for_deposit_date_end_date, 
                'filter_deposit_date_start_date' : filter_deposit_date_start_date, 
                'filter_deposit_date_end_date' : filter_deposit_date_end_date, 
                'filter_reversed_date_start_date' : filter_reversed_date_start_date, 
                'filter_reversed_date_end_date' : filter_reversed_date_end_date, 
                'filter_pulled_out_date_start_date' : filter_pulled_out_date_start_date, 
                'filter_pulled_out_date_end_date' : filter_pulled_out_date_end_date, 
                'filter_cancellation_date_start_date' : filter_cancellation_date_start_date, 
                'filter_cancellation_date_end_date' : filter_cancellation_date_end_date, 
                'filter_clear_date_start_date' : filter_clear_date_start_date, 
                'filter_clear_date_end_date' : filter_clear_date_end_date, 
                'filter_pdc_management_status' : filter_pdc_management_status
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
        'order': [[ 2, 'asc' ]],
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

function pdcManagementForm(){
    $('#pdc-management-form').validate({
        rules: {
            pdc_type: {
                required: true
            },
            sales_proposal_id: {
                required: {
                    depends: function(element) {
                        return $("select[name='pdc_type']").val() === 'Loan';
                    }
                }
            },
            product_id: {
                required: {
                    depends: function(element) {
                        return $("select[name='pdc_type']").val() === 'Product';
                    }
                }
            },
            customer_id: {
                required: {
                    depends: function(element) {
                        return $("select[name='pdc_type']").val() === 'Customer';
                    }
                }
            },
            payment_details: {
                required: true
            },
            check_number: {
                required: true
            },
            check_date: {
                required: true
            },
            payment_amount: {
                required: true
            },
            company_id: {
                required: true
            },
            bank_branch: {
                required: true
            }
        },
        messages: {
            pdc_type: {
                required: 'Please choose the PDC type'
            },
            sales_proposal_id: {
                required: 'Please choose the loan'
            },
            product_id: {
                required: 'Please choose the product'
            },
            customer_id: {
                required: 'Please choose the customer'
            },
            payment_details: {
                required: 'Please choose the payment details'
            },
            check_number: {
                required: 'Please enter the check number'
            },
            check_date: {
                required: 'Please enter the check date'
            },
            payment_amount: {
                required: 'Please enter the payment amount'
            },
            company_id: {
                required: 'Please choose the company'
            },
            bank_branch: {
                required: 'Please enter the bank/branch'
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
            const loan_collection_id = $('#loan-collection-id').text();
            const transaction = 'save pdc management';
        
            $.ajax({
                type: 'POST',
                url: 'controller/pdc-management-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction + '&loan_collection_id=' + loan_collection_id,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-data');
                },
                success: function (response) {
                    if (response.success) {
                        const notificationMessage = response.insertRecord ? 'Insert PDC Management Success' : 'Update PDC Management Success';
                        const notificationDescription = response.insertRecord ? 'The PDC management has been inserted successfully.' : 'The PDC management has been updated successfully.';
                        
                        setNotification(notificationMessage, notificationDescription, 'success');
                        window.location = 'pdc-management.php?id=' + response.loanCollectionID;
                    }
                    else {
                        if (response.isInactive) {
                            setNotification('User Inactive', response.message, 'danger');
                            window.location = 'logout.php?logout';
                        }
                        else if (response.checkConflict) {
                            showNotification('Insert PDC Management Error', 'The check number you entered conflicts to the existing check number on this loan.', 'danger');
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
                }
            });
        
            return false;
        }
    });
}

function pdcOnHoldForm(){
    $('#pdc-on-hold-form').validate({
        rules: {
            on_hold_reason: {
                required: true
            },
            onhold_attachment: {
                required: true
            },
        },
        messages: {
            on_hold_reason: {
                required: 'Please enter the on-hold reason'
            },
            onhold_attachment: {
                required: 'Please choose the on-hold attachment'
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
            const loan_collection_id = $('#loan-collection-id').text();
            const transaction = 'tag pdc as on-hold';

            var formData = new FormData(form);
            formData.append('loan_collection_id', loan_collection_id);
            formData.append('transaction', transaction);
        
            $.ajax({
                type: 'POST',
                url: 'controller/pdc-management-controller.php',
                data: formData,
                processData: false,
                contentType: false,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-pdc-on-hold');
                },
                success: function (response) {
                    if (response.success) {
                        const notificationMessage = 'PDC On-Hold Success';
                        const notificationDescription = 'The PDC has been tag as on-hold successfully.';
                        
                        setNotification(notificationMessage, notificationDescription, 'success');
                        window.location.reload();
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
                    enableFormSubmitButton('submit-pdc-on-hold', 'Submit');
                }
            });
        
            return false;
        }
    });
}

function massPDCCancelForm(){
    $('#mass-pdc-cancel-form').validate({
        rules: {
            cancellation_reason: {
                required: true
            },
        },
        messages: {
            cancellation_reason: {
                required: 'Please enter the cancellation reason'
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
            let loan_collection_id = [];

            $('.datatable-checkbox-children').each((index, element) => {
                if ($(element).is(':checked')) {
                    loan_collection_id.push(element.value);
                }
            });

            const transaction = 'tag multiple pdc as cancelled';
        
            $.ajax({
                type: 'POST',
                url: 'controller/pdc-management-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction + '&loan_collection_id=' + loan_collection_id,
                dataType: 'json',
                success: function (response) {
                    if (response.success) {
                        const notificationMessage = 'PDC Cancel Success';
                        const notificationDescription = 'The PDC has been tag as cancelled successfully.';
                        
                        showNotification(notificationMessage, notificationDescription, 'success');
                        reloadDatatable('#pdc-management-table');
                        $('#pdc-cancel-offcanvas').offcanvas('hide');
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
                complete: function(){
                    toggleHideActionDropdown();
                }
            });
        
            return false;
        }
    });
}

function pdcCancelForm(){
    $('#pdc-cancel-form').validate({
        rules: {
            cancellation_reason: {
                required: true
            },
        },
        messages: {
            cancellation_reason: {
                required: 'Please enter the cancellation reason'
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
            const loan_collection_id = $('#loan-collection-id').text();
            const transaction = 'tag pdc as cancelled';
        
            $.ajax({
                type: 'POST',
                url: 'controller/pdc-management-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction + '&loan_collection_id=' + loan_collection_id,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-pdc-cancel');
                },
                success: function (response) {
                    if (response.success) {
                        const notificationMessage = 'PDC Cancel Success';
                        const notificationDescription = 'The PDC has been tag as cancelled successfully.';
                        
                        setNotification(notificationMessage, notificationDescription, 'success');
                        window.location.reload();
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
                    enableFormSubmitButton('submit-pdc-cancel', 'Submit');
                }
            });
        
            return false;
        }
    });
}

function pdcPulledOutForm(){
    $('#pdc-pulled-out-form').validate({
        rules: {
            pulled_out_reason: {
                required: true
            },
        },
        messages: {
            pulled_out_reason: {
                required: 'Please enter the pulled-out reason'
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
            const loan_collection_id = $('#loan-collection-id').text();
            const transaction = 'tag pdc as pulled-out';
        
            $.ajax({
                type: 'POST',
                url: 'controller/pdc-management-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction + '&loan_collection_id=' + loan_collection_id,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-pdc-pulled-out');
                },
                success: function (response) {
                    if (response.success) {
                        const notificationMessage = 'PDC Pulled-Out Success';
                        const notificationDescription = 'The PDC has been tag as pulled-out successfully.';
                        
                        setNotification(notificationMessage, notificationDescription, 'success');
                        window.location.reload();
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
                    enableFormSubmitButton('submit-pdc-pulled-out', 'Submit');
                }
            });
        
            return false;
        }
    });
}

function pdcRedepositForm(){
    $('#pdc-redeposit-form').validate({
        rules: {
            redeposit_date: {
                required: true
            },
        },
        messages: {
            redeposit_date: {
                required: 'Please enter the redeposit date'
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
            const loan_collection_id = $('#loan-collection-id').text();
            const transaction = 'tag pdc as redeposit';
        
            $.ajax({
                type: 'POST',
                url: 'controller/pdc-management-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction + '&loan_collection_id=' + loan_collection_id,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-pdc-redeposit');
                },
                success: function (response) {
                    if (response.success) {
                        const notificationMessage = 'PDC Redeposit Success';
                        const notificationDescription = 'The PDC has been tag as redeposit successfully.';
                        
                        setNotification(notificationMessage, notificationDescription, 'success');
                        window.location.reload();
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
                    enableFormSubmitButton('submit-pdc-redeposit', 'Submit');
                }
            });
        
            return false;
        }
    });
}

function pdcReverseForm(){
    $('#pdc-reverse-form').validate({
        rules: {
            reversal_reason: {
                required: true
            },
            reversal_remarks: {
                required: true
            },
        },
        messages: {
            reversal_reason: {
                required: 'Please enter the reversal reason'
            },
            reversal_remarks: {
                required: 'Please enter the reversal remarks'
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
            const loan_collection_id = $('#loan-collection-id').text();
            const transaction = 'tag pdc as reversed';
        
            $.ajax({
                type: 'POST',
                url: 'controller/pdc-management-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction + '&loan_collection_id=' + loan_collection_id,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-pdc-reverse');
                },
                success: function (response) {
                    if (response.success) {
                        const notificationMessage = 'PDC Reversed Success';
                        const notificationDescription = 'The PDC has been tag as reversed successfully.';
                        
                        setNotification(notificationMessage, notificationDescription, 'success');
                        window.location.reload();
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
                    enableFormSubmitButton('submit-pdc-reverse', 'Submit');
                }
            });
        
            return false;
        }
    });
}

function massPDCReverseForm(){
    $('#mass-pdc-reverse-form').validate({
        rules: {
            reversal_reason: {
                required: true
            },
            reversal_remarks: {
                required: true
            },
        },
        messages: {
            reversal_reason: {
                required: 'Please enter the reversal reason'
            },
            reversal_remarks: {
                required: 'Please enter the reversal remarks'
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
            let loan_collection_id = [];

            $('.datatable-checkbox-children').each((index, element) => {
                if ($(element).is(':checked')) {
                    loan_collection_id.push(element.value);
                }
            });

            const transaction = 'tag multiple pdc as reversed';
        
            $.ajax({
                type: 'POST',
                url: 'controller/pdc-management-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction + '&loan_collection_id=' + loan_collection_id,
                dataType: 'json',
                success: function (response) {
                    if (response.success) {
                        const notificationMessage = 'PDC Reversed Success';
                        const notificationDescription = 'The PDC has been tag as reversed successfully.';
                        
                        showNotification(notificationMessage, notificationDescription, 'success');
                        reloadDatatable('#pdc-management-table');
                        $('#pdc-reverse-offcanvas').offcanvas('hide');
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
                complete: function(){
                    toggleHideActionDropdown();
                }
            });
        
            return false;
        }
    });
}

function importForm(){
    $('#import-form').validate({
        rules: {
            import_file: {
                required: true
            }
        },
        messages: {
            import_file: {
                required: 'Please choose the import file'
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
            const transaction = 'save pdc import';
            var formData = new FormData(form);
            formData.append('transaction', transaction);
        
            $.ajax({
                type: 'POST',
                url: 'controller/pdc-management-controller.php',
                data: formData,
                processData: false,
                contentType: false,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-import');
                },
                success: function (response) {
                    if (response.success) {
                        setNotification('PDC Import Success', 'The PDCs have been imported successfully.', 'success');
                        window.location.reload();
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
                    enableFormSubmitButton('submit-import', 'Submit');
                }
            });
        
            return false;
        }
    });
}

function displayDetails(transaction){
    switch (transaction) {
        case 'get pdc management details':
            const loan_collection_id = $('#loan-collection-id').text();
            
            $.ajax({
                url: 'controller/pdc-management-controller.php',
                method: 'POST',
                dataType: 'json',
                data: {
                    loan_collection_id : loan_collection_id, 
                    transaction : transaction
                },
                beforeSend: function() {
                    resetForm('pdc-management-form');
                },
                success: function(response) {
                    if (response.success) {
                        $('#check_number').val(response.checkNumber);
                        $('#check_date').val(response.checkDate);
                        $('#payment_amount').val(response.paymentAmount);
                        $('#bank_branch').val(response.bankBranch);
                        $('#remarks').val(response.remarks);
                        $('#account_number').val(response.accountNumber);

                        $('#cancellation_reason_remarks').val(response.cancellationReason);
                        $('#pulled_out_reason_remarks').val(response.pulledOutReason);
                        $('#reversal_reason_remarks').val(response.reversalReason);
                        $('#onhold_reason_remarks').val(response.onholdReason);

                        document.getElementById('onhold_attachment_file').src = response.onholdAttachment;

                        checkOptionExist('#pdc_type', response.pdcType, '');
                        checkOptionExist('#sales_proposal_id', response.salesProposalID, '');
                        checkOptionExist('#product_id', response.productID, '');
                        checkOptionExist('#customer_id', response.customerID, '');
                        checkOptionExist('#payment_details', response.paymentDetails, '');
                        checkOptionExist('#company_id', response.companyID, '');
                    } 
                    else {
                        if(response.isInactive){
                            window.location = 'logout.php?logout';
                        }
                        else{
                            showNotification('Get PDC Management Details Error', response.message, 'danger');
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