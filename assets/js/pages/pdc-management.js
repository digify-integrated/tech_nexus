(function($) {
    'use strict';

    $(function() {
        if($('#pdc-management-table').length){
            pdcManagementTable('#pdc-management-table');
        }

        if($('#pdc-management-form').length){
            pdcManagementForm();
        }

        if($('#pdc-management-id').length){
            displayDetails('get PDC management details');
        }

        $(document).on('click','.delete-pdc-management',function() {
            const pdc_management_id = $(this).data('pdc-management-id');
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
                            pdc_management_id : pdc_management_id, 
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
            let pdc_management_id = [];
            const transaction = 'delete multiple PDC management';

            $('.datatable-checkbox-children').each((index, element) => {
                if ($(element).is(':checked')) {
                    pdc_management_id.push(element.value);
                }
            });
    
            if(pdc_management_id.length > 0){
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
                                pdc_management_id: pdc_management_id,
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
            const pdc_management_id = $('#pdc-management-id').text();
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
                            pdc_management_id : pdc_management_id, 
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
    });
})(jQuery);

function pdcManagementTable(datatable_name, buttons = false, show_all = false){
    const type = 'pdc management table';
    var filter_check_date_start_date = $('#filter_check_date_start_date').val();
    var filter_check_date_end_date = $('#filter_check_date_end_date').val();
    var filter_pdc_management_status = $('.pdc-management-status-filter:checked').val();
    var settings;

    const column = [ 
        { 'data' : 'CHECK_BOX' },
        { 'data' : 'LOAN_NUMBER' },
        { 'data' : 'CUSTOMER' },
        { 'data' : 'PAYMENT_DETAILS' },
        { 'data' : 'CHECK_NUMBER' },
        { 'data' : 'CHECK_DATE' },
        { 'data' : 'PAYMENT_AMOUNT' },
        { 'data' : 'BANK_BRANCH' },
        { 'data' : 'STATUS' },
        { 'data' : 'ACTION' }
    ];

    const column_definition = [
        { 'width': '1%','bSortable': false, 'aTargets': 0 },
        { 'width': 'auto', 'aTargets': 1 },
        { 'width': 'auto', 'aTargets': 2 },
        { 'width': 'auto', 'aTargets': 3 },
        { 'width': 'auto', 'aTargets': 4 },
        { 'width': 'auto', 'aTargets': 5 },
        { 'width': 'auto', 'aTargets': 6 },
        { 'width': 'auto', 'aTargets': 7 },
        { 'width': 'auto', 'aTargets': 8 },
        { 'width': '15%','bSortable': false, 'aTargets': 9 }
    ];

    const length_menu = show_all ? [[-1], ['All']] : [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']];

    settings = {
        'ajax': { 
            'url' : 'view/_pdc_management_generation.php',
            'method' : 'POST',
            'dataType': 'json',
            'data': {'type' : type, 'filter_check_date_start_date' : filter_check_date_start_date, 'filter_check_date_end_date' : filter_check_date_end_date, 'filter_pdc_management_status' : filter_pdc_management_status},
            'dataSrc' : '',
            'error': function(xhr, status, error) {
                var fullErrorMessage = `XHR status: ${status}, Error: ${error}`;
                if (xhr.responseText) {
                    fullErrorMessage += `, Response: ${xhr.responseText}`;
                }
                showErrorDialog(fullErrorMessage);
            }
        },
        'order': [[ 1, 'asc' ]],
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
            loan_number: {
                required: true
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
            bank_branch: {
                required: true
            }
        },
        messages: {
            loan_number: {
                required: 'Please choose the loan'
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
            const pdc_management_id = $('#pdc-management-id').text();
            const transaction = 'save pdc management';
        
            $.ajax({
                type: 'POST',
                url: 'controller/pdc-management-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction + '&pdc_management_id=' + pdc_management_id,
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

function displayDetails(transaction){
    switch (transaction) {
        case 'get PDC management details':
            const pdc_management_id = $('#pdc-management-id').text();
            
            $.ajax({
                url: 'controller/pdc-management-controller.php',
                method: 'POST',
                dataType: 'json',
                data: {
                    pdc_management_id : pdc_management_id, 
                    transaction : transaction
                },
                beforeSend: function() {
                    resetForm('pdc-management-form');
                },
                success: function(response) {
                    if (response.success) {
                        $('#pdc_management_id').val(pdc_management_id);
                        $('#pdc_management_name').val(response.pdcManagementName);

                        checkOptionExist('#is_paid', response.isPaid, '');

                        $('#pdc_management_name_label').text(response.pdcManagementName);
                        $('#is_paid_label').text(response.isPaid);
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