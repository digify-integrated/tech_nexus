(function($) {
    'use strict';

    $(function() {
        if($('#collections-table').length){
            collectionsTable('#collections-table');
        }
        if($('#payment-advice-table').length){
            paymentAdviceTable('#payment-advice-table');
        }

        if($('#transaction-history-table').length){
            transactionHistoryTable('#transaction-history-table');
        }

        if($('#collections-form').length){
            collectionsForm();
        }

        if($('#collections-cancel-form').length){
            collectionsCancelForm();
        }

        if($('#mass-collections-cancel-form').length){
            massCollectionCancelForm();
        }

        if($('#collections-reverse-form').length){
            collectionsReverseForm();
        }

        if($('#mass-collections-reverse-form').length){
            massCollectionReverseForm();
        }

        $(document).on('change','#pdc_type',function() {
            var pdc_type = $(this).val();

            $('.field').addClass('d-none');

            checkOptionExist('#sales_proposal_id', '', '');
            checkOptionExist('#product_id', '', '');
            checkOptionExist('#customer_id', '', '');
            $('#collected_from').val('');

            switch (pdc_type) {
                case 'Loan':
                    $('#loan_field').removeClass('d-none');
                    checkOptionExist('#product_id', '', '');
                    checkOptionExist('#customer_id', '', '');
                    checkOptionExist('#leasing_id', '', '');
                    $('#collected_from').val('');
                    break;
                case 'Product':
                    $('#product_field').removeClass('d-none');
                    checkOptionExist('#sales_proposal_id', '', '');
                    checkOptionExist('#customer_id', '', '');
                    checkOptionExist('#leasing_id', '', '');
                    $('#collected_from').val('');
                    break;
                case 'Customer':
                    $('#customer_field').removeClass('d-none');
                    checkOptionExist('#sales_proposal_id', '', '');
                    checkOptionExist('#product_id', '', '');
                    checkOptionExist('#leasing_id', '', '');
                    $('#collected_from').val('');
                    break;
                case 'Miscellaneous':
                    $('#miscellaneous_field').removeClass('d-none');
                    checkOptionExist('#sales_proposal_id', '', '');
                    checkOptionExist('#product_id', '', '');
                    checkOptionExist('#customer_id', '', '');
                    checkOptionExist('#leasing_id', '', '');
                    break;
                case 'Leasing':
                    $('#leasing_field').removeClass('d-none');
                    checkOptionExist('#sales_proposal_id', '', '');
                    checkOptionExist('#product_id', '', '');
                    checkOptionExist('#customer_id', '', '');
                    $('#collected_from').val('');
                    break;
            }
        });

        if($('#loan-collection-id').length){
            displayDetails('get collections details');
        }

        $(document).on('click','.delete-collections',function() {
            const loan_collection_id = $(this).data('collections-id');
            const transaction = 'delete collection';
    
            Swal.fire({
                title: 'Confirm Collection Deletion',
                text: 'Are you sure you want to delete this collection?',
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
                        url: 'controller/collections-controller.php',
                        dataType: 'json',
                        data: {
                            loan_collection_id : loan_collection_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                showNotification('Delete Collection Success', 'The collection has been deleted successfully.', 'success');
                                reloadDatatable('#collections-table');
                            }
                            else {
                                if (response.isInactive) {
                                    setNotification('User Inactive', response.message, 'danger');
                                    window.location = 'logout.php?logout';
                                }
                                else if (response.notExist) {
                                    showNotification('Delete Collection Error', 'The collection does not exist.', 'danger');
                                    reloadDatatable('#collections-table');
                                }
                                else {
                                    showNotification('Delete Collection Error', response.message, 'danger');
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

        $(document).on('click','#delete-collections',function() {
            let loan_collection_id = [];
            const transaction = 'delete multiple collections';

            $('.datatable-checkbox-children').each((index, element) => {
                if ($(element).is(':checked')) {
                    loan_collection_id.push(element.value);
                }
            });
    
            if(loan_collection_id.length > 0){
                Swal.fire({
                    title: 'Confirm Multiple Collection Deletion',
                    text: 'Are you sure you want to delete these collection?',
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
                            url: 'controller/collections-controller.php',
                            dataType: 'json',
                            data: {
                                loan_collection_id: loan_collection_id,
                                transaction : transaction
                            },
                            success: function (response) {
                                if (response.success) {
                                    showNotification('Delete Collection Success', 'The selected collection have been deleted successfully.', 'success');
                                        reloadDatatable('#collections-table');
                                }
                                else {
                                    if (response.isInactive) {
                                        setNotification('User Inactive', response.message, 'danger');
                                        window.location = 'logout.php?logout';
                                    }
                                    else {
                                        showNotification('Delete Collection Error', response.message, 'danger');
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
                showNotification('Deletion Multiple Collection Error', 'Please select the collection you wish to delete.', 'danger');
            }
        });

        $(document).on('click','#delete-collections-details',function() {
            const loan_collection_id = $('#loan-collection-id').text();
            const transaction = 'delete collections';
    
            Swal.fire({
                title: 'Confirm Collection Deletion',
                text: 'Are you sure you want to delete this collection?',
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
                        url: 'controller/collections-controller.php',
                        dataType: 'json',
                        data: {
                            loan_collection_id : loan_collection_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                setNotification('Deleted Collection Success', 'The collection has been deleted successfully.', 'success');
                                window.location = 'collections.php';
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
                                    showNotification('Delete Collection Error', response.message, 'danger');
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
            discardCreate('collections.php');
        });

        $(document).on('click','#discard-payment-advice-create',function() {
            discardCreate('payment-advice.php');
        });

        $(document).on('click','#edit-form',function() {
            displayDetails('get Collection management details');

            enableForm();
        });

        $(document).on('click','#apply-filter',function() {
            collectionsTable('#collections-table');
        });

        $(document).on('click','#tag-collections-as-cleared-details',function() {
            const loan_collection_id = $('#loan-collection-id').text();
            const transaction = 'tag collection as cleared';
    
            Swal.fire({
                title: 'Confirm Collection As Cleared',
                text: 'Are you sure you want to tag this collection as cleared?',
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
                        url: 'controller/collections-controller.php',
                        dataType: 'json',
                        data: {
                            loan_collection_id : loan_collection_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                setNotification('Tag Collection As Cleared Success', 'The collections have been tagged as cleared successfully.', 'success');
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
                                    showNotification('Tag Collection As Cleared Error', response.message, 'danger');
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

        $(document).on('click','#tag-collections-as-cleared',function() {
            let loan_collection_id = [];
            const transaction = 'tag multiple collection as cleared';

            $('.datatable-checkbox-children').each((index, element) => {
                if ($(element).is(':checked')) {
                    loan_collection_id.push(element.value);
                }
            });
    
            if(loan_collection_id.length > 0){
                Swal.fire({
                    title: 'Confirm Multiple Collection As Cleared',
                    text: 'Are you sure you want to tag these collection as cleared?',
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
                            url: 'controller/collections-controller.php',
                            dataType: 'json',
                            data: {
                                loan_collection_id: loan_collection_id,
                                transaction : transaction
                            },
                            success: function (response) {
                                if (response.success) {
                                    showNotification('Tag Collection As Cleared Success', 'The selected collection have been tagged as cleared successfully.', 'success');
                                    reloadDatatable('#collections-table');
                                }
                                else {
                                    if (response.isInactive) {
                                        setNotification('User Inactive', response.message, 'danger');
                                        window.location = 'logout.php?logout';
                                    }
                                    else {
                                        showNotification('Tag Collection As Cleared Error', response.message, 'danger');
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
                showNotification('Tagging Multiple Collection As Cleared Error', 'Please select the Collection you wish to tag as cleared.', 'danger');
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
                window.open('collection-print.php?id=' + checkedBoxes, '_blank');
            }
            else{
                showNotification('Print Collections Error', 'No selected collection.', 'danger');
            }
        });
    });
})(jQuery);

function collectionsTable(datatable_name, buttons = false, show_all = false){
    const type = 'collections table';
    var filter_transaction_date_start_date = $('#filter_transaction_date_start_date').val();
    var filter_transaction_date_end_date = $('#filter_transaction_date_end_date').val();

    var filter_or_date_start_date = $('#filter_or_date_start_date').val();
    var filter_or_date_end_date = $('#filter_or_date_end_date').val();

    var filter_payment_date_start_date = $('#filter_payment_date_start_date').val();
    var filter_payment_date_end_date = $('#filter_payment_date_end_date').val();

    var filter_reversed_date_start_date = $('#filter_reversed_date_start_date').val();
    var filter_reversed_date_end_date = $('#filter_reversed_date_end_date').val();

    var filter_cancellation_date_start_date = $('#filter_cancellation_date_start_date').val();
    var filter_cancellation_date_end_date = $('#filter_cancellation_date_end_date').val();

    var filter_collections_status = $('.collections-status-filter:checked').val();
    var filter_payment_advice = $('.payment-advice-filter:checked').val();
    var settings;

    const column = [ 
        { 'data' : 'CHECK_BOX' },
        { 'data' : 'ACTION' },
        { 'data' : 'PAYMENT_DATE' },
        { 'data' : 'TRANSACTION_DATE' },
        { 'data' : 'MODE_OF_PAYMENT' },
        { 'data' : 'PAYMENT_AMOUNT' },
        { 'data' : 'OR_NUMBER' },
        { 'data' : 'OR_DATE' },
        { 'data' : 'PAYMENT_DETAILS' },
        { 'data' : 'STATUS' },
        { 'data' : 'PAYMENT_ADVICE_BADGE' },
        { 'data' : 'LOAN_NUMBER' },
        { 'data' : 'CUSTOMER' },
        { 'data' : 'COLLECTED_FROM' },
    ];

    const column_definition = [
        { 'width': '1%','bSortable': false, 'aTargets': 0 },
        { 'width': '15%','bSortable': false, 'aTargets': 1 },
        { 'width': 'auto', 'type': 'date', 'aTargets': 2 },
        { 'width': 'auto', 'type': 'date', 'aTargets': 3 },
        { 'width': 'auto', 'aTargets': 4 },
        { 'width': 'auto', 'aTargets': 5 },
        { 'width': 'auto', 'aTargets': 6 },
        { 'width': 'auto', 'type': 'date', 'aTargets': 7 },
        { 'width': 'auto', 'aTargets': 8 },
        { 'width': 'auto', 'aTargets': 9 },
        { 'width': 'auto', 'aTargets': 10 },
        { 'width': 'auto', 'aTargets': 11 },
        { 'width': 'auto', 'aTargets': 12 },
        { 'width': 'auto', 'aTargets': 13 },
    ];

    const length_menu = show_all ? [[-1], ['All']] : [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']];

    settings = {
        'ajax': { 
            'url' : 'view/_collections_generation.php',
            'method' : 'POST',
            'dataType': 'json',
            'data': {'type' : type, 
                'filter_transaction_date_start_date' : filter_transaction_date_start_date, 
                'filter_transaction_date_end_date' : filter_transaction_date_end_date, 
                'filter_or_date_start_date' : filter_or_date_start_date, 
                'filter_or_date_end_date' : filter_or_date_end_date, 
                'filter_payment_date_start_date' : filter_payment_date_start_date, 
                'filter_payment_date_end_date' : filter_payment_date_end_date, 
                'filter_reversed_date_start_date' : filter_reversed_date_start_date, 
                'filter_reversed_date_end_date' : filter_reversed_date_end_date, 
                'filter_cancellation_date_start_date' : filter_cancellation_date_start_date, 
                'filter_cancellation_date_end_date' : filter_cancellation_date_end_date, 
                'filter_collections_status' : filter_collections_status,
                'filter_payment_advice' : filter_payment_advice
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
        'order': [[ 3, 'desc' ]],
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

function paymentAdviceTable(datatable_name, buttons = false, show_all = false){
    const type = 'payment advice table';
    var filter_transaction_date_start_date = $('#filter_transaction_date_start_date').val();
    var filter_transaction_date_end_date = $('#filter_transaction_date_end_date').val();

    var filter_or_date_start_date = $('#filter_or_date_start_date').val();
    var filter_or_date_end_date = $('#filter_or_date_end_date').val();

    var filter_payment_date_start_date = $('#filter_payment_date_start_date').val();
    var filter_payment_date_end_date = $('#filter_payment_date_end_date').val();

    var filter_reversed_date_start_date = $('#filter_reversed_date_start_date').val();
    var filter_reversed_date_end_date = $('#filter_reversed_date_end_date').val();

    var filter_cancellation_date_start_date = $('#filter_cancellation_date_start_date').val();
    var filter_cancellation_date_end_date = $('#filter_cancellation_date_end_date').val();

    var filter_collections_status = $('.collections-status-filter:checked').val();
    var settings;

    const column = [ 
        { 'data' : 'CHECK_BOX' },
        { 'data' : 'ACTION' },
        { 'data' : 'PAYMENT_DATE' },
        { 'data' : 'TRANSACTION_DATE' },
        { 'data' : 'MODE_OF_PAYMENT' },
        { 'data' : 'PAYMENT_AMOUNT' },
        { 'data' : 'OR_NUMBER' },
        { 'data' : 'OR_DATE' },
        { 'data' : 'PAYMENT_DETAILS' },
        { 'data' : 'STATUS' },
        { 'data' : 'LOAN_NUMBER' },
        { 'data' : 'CUSTOMER' },
        { 'data' : 'COLLECTED_FROM' },
    ];

    const column_definition = [
        { 'width': '1%','bSortable': false, 'aTargets': 0 },
        { 'width': '15%','bSortable': false, 'aTargets': 1 },
        { 'width': 'auto', 'type': 'date', 'aTargets': 2 },
        { 'width': 'auto', 'type': 'date', 'aTargets': 3 },
        { 'width': 'auto', 'aTargets': 4 },
        { 'width': 'auto', 'aTargets': 5 },
        { 'width': 'auto', 'aTargets': 6 },
        { 'width': 'auto', 'type': 'date', 'aTargets': 7 },
        { 'width': 'auto', 'aTargets': 8 },
        { 'width': 'auto', 'aTargets': 9 },
        { 'width': 'auto', 'aTargets': 10 },
        { 'width': 'auto', 'aTargets': 11 },
        { 'width': 'auto', 'aTargets': 12 },
    ];

    const length_menu = show_all ? [[-1], ['All']] : [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']];

    settings = {
        'ajax': { 
            'url' : 'view/_collections_generation.php',
            'method' : 'POST',
            'dataType': 'json',
            'data': {'type' : type, 
                'filter_transaction_date_start_date' : filter_transaction_date_start_date, 
                'filter_transaction_date_end_date' : filter_transaction_date_end_date, 
                'filter_or_date_start_date' : filter_or_date_start_date, 
                'filter_or_date_end_date' : filter_or_date_end_date, 
                'filter_payment_date_start_date' : filter_payment_date_start_date, 
                'filter_payment_date_end_date' : filter_payment_date_end_date, 
                'filter_reversed_date_start_date' : filter_reversed_date_start_date, 
                'filter_reversed_date_end_date' : filter_reversed_date_end_date, 
                'filter_cancellation_date_start_date' : filter_cancellation_date_start_date, 
                'filter_cancellation_date_end_date' : filter_cancellation_date_end_date, 
                'filter_collections_status' : filter_collections_status
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
        'order': [[ 3, 'desc' ]],
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

function transactionHistoryTable(datatable_name, buttons = false, show_all = false){
    const loan_collection_id = $('#loan-collection-id').text();
    const type = 'transaction history table';
    var settings;

    const column = [ 
        { 'data' : 'TRANSACTION_TYPE' },
        { 'data' : 'TRANSACTION_DATE' },
        { 'data' : 'REFERENCE_NUMBER' },
        { 'data' : 'REFERENCE_DATE' },
        { 'data' : 'TRANSACTION_BY' }
    ];

    const column_definition = [
        { 'width': 'auto', 'aTargets': 0 },
        { 'width': 'auto', 'type': 'date', 'aTargets': 1 },
        { 'width': 'auto', 'aTargets': 2 },
        { 'width': 'auto', 'type': 'date', 'aTargets': 3},
        { 'width': 'auto', 'aTargets': 4}
    ];

    const length_menu = show_all ? [[-1], ['All']] : [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']];

    settings = {
        'ajax': { 
            'url' : 'view/_pdc_management_generation.php',
            'method' : 'POST',
            'dataType': 'json',
            'data': {'type' : type, 'loan_collection_id' : loan_collection_id},
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

function collectionsForm(){
    $('#collections-form').validate({
        rules: {
            mode_of_payment: {
                required: true
            },
            company_id: {
                required: true
            },
            or_number: {
                required: {
                    depends: function(element) {
                        return $("#payment_advice").val() === 'No';
                    }
                }
            },
            or_date: {
                required: true
            },
            payment_date: {
                required: true
            },
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
            miscellaneous_client_id: {
                required: {
                    depends: function(element) {
                        return $("select[name='pdc_type']").val() === 'Miscellaneous';
                    }
                }
            },
            leasing_id: {
                required: {
                    depends: function(element) {
                        return $("select[name='pdc_type']").val() === 'Leasing';
                    }
                }
            },
            deposited_to: {
                required: {
                    depends: function(element) {
                        var modeOfPayment = $("select[name='mode_of_payment']").val();
                        return modeOfPayment === 'Cash' || modeOfPayment === 'Online Deposit';
                    }
                }
            },
            payment_details: {
                required: true
            },
            payment_amount: {
                required: true
            }
        },
        messages: {
            mode_of_payment: {
                required: 'Please choose the mode of payment'
            },
            pdc_type: {
                required: 'Please choose the collection type'
            },
            company_id: {
                required: 'Please choose the company'
            },
            or_number: {
                required: 'Please choose the OR number'
            },
            or_date: {
                required: 'Please choose the OR date'
            },
            payment_date: {
                required: 'Please choose the payment date'
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
            miscellaneous_client_id: {
                required: 'Please enter the collected from'
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
            deposited_to: {
                required: 'Please choose the deposited to'
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
            const payment_advice = $('#payment_advice').val();
            const transaction = 'save collections';
        
            $.ajax({
                type: 'POST',
                url: 'controller/collections-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction + '&loan_collection_id=' + loan_collection_id,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-data');
                },
                success: function (response) {
                    if (response.success) {
                        const notificationMessage = response.insertRecord ? 'Insert Collection Management Success' : 'Update Collection Management Success';
                        const notificationDescription = response.insertRecord ? 'The Collection management has been inserted successfully.' : 'The Collection management has been updated successfully.';
                        
                        setNotification(notificationMessage, notificationDescription, 'success');
                       

                        if(payment_advice === 'No'){
                            window.location = 'collections.php?id=' + response.loanCollectionID;
                        }
                        else{
                            window.location = 'payment-advice.php?id=' + response.loanCollectionID;
                        }
                    }
                    else {
                        if (response.isInactive) {
                            setNotification('User Inactive', response.message, 'danger');
                            window.location = 'logout.php?logout';
                        }
                        else if (response.checkConflict) {
                            showNotification('Insert Collection Management Error', 'The check number you entered conflicts to the existing check number on this loan.', 'danger');
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

function massCollectionCancelForm(){
    $('#mass-collections-cancel-form').validate({
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

            const transaction = 'tag multiple collections as cancelled';
        
            $.ajax({
                type: 'POST',
                url: 'controller/collections-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction + '&loan_collection_id=' + loan_collection_id,
                dataType: 'json',
                success: function (response) {
                    if (response.success) {
                        const notificationMessage = 'Collection Cancel Success';
                        const notificationDescription = 'The Collection has been tag as cancelled successfully.';
                        
                        showNotification(notificationMessage, notificationDescription, 'success');
                        reloadDatatable('#collections-table');
                        $('#collections-cancel-offcanvas').offcanvas('hide');
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

function collectionsCancelForm(){
    $('#collections-cancel-form').validate({
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
            const transaction = 'tag collection as cancelled';
        
            $.ajax({
                type: 'POST',
                url: 'controller/collections-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction + '&loan_collection_id=' + loan_collection_id,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-collections-cancel');
                },
                success: function (response) {
                    if (response.success) {
                        const notificationMessage = 'Collection Cancel Success';
                        const notificationDescription = 'The Collection has been tag as cancelled successfully.';
                        
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
                    enableFormSubmitButton('submit-collections-cancel', 'Submit');
                }
            });
        
            return false;
        }
    });
}

function collectionsReverseForm(){
    $('#collections-reverse-form').validate({
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
            const transaction = 'tag collection as reversed';
        
            $.ajax({
                type: 'POST',
                url: 'controller/collections-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction + '&loan_collection_id=' + loan_collection_id,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-collections-reverse');
                },
                success: function (response) {
                    if (response.success) {
                        const notificationMessage = 'Collection Reversed Success';
                        const notificationDescription = 'The Collection has been tag as reversed successfully.';
                        
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
                    enableFormSubmitButton('submit-collections-reverse', 'Submit');
                }
            });
        
            return false;
        }
    });
}

function massCollectionReverseForm(){
    $('#mass-collections-reverse-form').validate({
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

            const transaction = 'tag multiple collections as reversed';
        
            $.ajax({
                type: 'POST',
                url: 'controller/collections-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction + '&loan_collection_id=' + loan_collection_id,
                dataType: 'json',
                success: function (response) {
                    if (response.success) {
                        const notificationMessage = 'Collection Reversed Success';
                        const notificationDescription = 'The Collection has been tag as reversed successfully.';
                        
                        showNotification(notificationMessage, notificationDescription, 'success');
                        reloadDatatable('#collections-table');
                        $('#collections-reverse-offcanvas').offcanvas('hide');
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

function displayDetails(transaction){
    switch (transaction) {
        case 'get collections details':
            const loan_collection_id = $('#loan-collection-id').text();
            
            $.ajax({
                url: 'controller/collections-controller.php',
                method: 'POST',
                dataType: 'json',
                data: {
                    loan_collection_id : loan_collection_id, 
                    transaction : transaction
                },
                beforeSend: function() {
                    resetForm('collections-form');
                },
                success: function(response) {
                    if (response.success) {
                        $('#or_number').val(response.orNumber);
                        $('#or_date').val(response.orDate);
                        $('#payment_date').val(response.paymentDate);
                        $('#payment_amount').val(response.paymentAmount);
                        $('#remarks').val(response.remarks);
                        $('#reference_number').val(response.referenceNumber);

                        checkOptionExist('#mode_of_payment', response.modeOfPayment, '');
                        checkOptionExist('#company_id', response.companyID, '');
                        checkOptionExist('#pdc_type', response.pdcType, '');
                        checkOptionExist('#sales_proposal_id', response.salesProposalID, '');
                        checkOptionExist('#product_id', response.productID, '');
                        checkOptionExist('#customer_id', response.customerID, '');
                        checkOptionExist('#leasing_id', response.leasingID, '');
                        checkOptionExist('#payment_details', response.paymentDetails, '');
                        checkOptionExist('#deposited_to', response.depositedTo, '');
                        checkOptionExist('#miscellaneous_client_id', response.miscellaneousClientID, '');
                    } 
                    else {
                        if(response.isInactive){
                            window.location = 'logout.php?logout';
                        }
                        else{
                            showNotification('Get Collection Management Details Error', response.message, 'danger');
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