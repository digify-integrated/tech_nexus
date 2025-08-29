(function($) {
    'use strict';

    $(function() {
        if($('#parts-purchased-monitoring-table').length){
            partsPurchasedMonitoringTable('#parts-purchased-monitoring-table');
        }
        if($('#parts-purchased-monitoring-item-table').length){
            partsPurchasedMonitoringItemTable('#parts-purchased-monitoring-item-table');
        }

        if($('#parts-purchased-monitoring-form').length){
            partsPurchasedMonitoringForm();
        }

         if($('#cancel-part-purchased-form').length){
            partsPurchasedMonitoringCancel();
        }

         if($('#issue-part-purchased-form').length){
            partsPurchasedMonitoringIssue();
        }

        $(document).on('click','.issue-part-purchased',function() {
            const parts_purchase_monitoring_item_id = $(this).data('parts-purchase-monitoring-item-id');
            sessionStorage.setItem('parts_purchase_monitoring_item_id', parts_purchase_monitoring_item_id);

           displayDetails('get parts purchased monitoring item details');
        });

        $(document).on('click','.cancel-part-purchased',function() {
            const parts_purchase_monitoring_item_id = $(this).data('parts-purchase-monitoring-item-id');
            sessionStorage.setItem('parts_purchase_monitoring_item_id', parts_purchase_monitoring_item_id);
        });

         $(document).on('click','.tag-part-purchased-issue',function() {
            const parts_purchase_monitoring_item_id = $(this).data('parts-purchase-monitoring-item-id');
            const transaction = 'tag as issued';
    
            Swal.fire({
                title: 'Tag As Issued',
                text: 'Are you sure you want to tag this as issued?',
                icon: 'warning',
                showCancelButton: !0,
                confirmButtonText: 'Issued',
                cancelButtonText: 'Cancel',
                confirmButtonClass: 'btn btn-success mt-2',
                cancelButtonClass: 'btn btn-secondary ms-2 mt-2',
                buttonsStyling: !1
            }).then(function(result) {
                if (result.value) {
                    $.ajax({
                        type: 'POST',
                        url: 'controller/parts-purchased-monitoring-controller.php',
                        dataType: 'json',
                        data: {
                            parts_purchase_monitoring_item_id : parts_purchase_monitoring_item_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                showNotification('Tag As Issued Success', 'This has been tagged as issued successfully.', 'success');
                                reloadDatatable('#parts-purchased-monitoring-item-table');
                            }
                            else {
                                if (response.isInactive) {
                                    setNotification('User Inactive', response.message, 'danger');
                                    window.location = 'logout.php?logout';
                                }
                                else {
                                    showNotification('Tag As Issued Error', response.message, 'danger');
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

function partsPurchasedMonitoringTable(datatable_name, buttons = false, show_all = false){
    const type = 'parts purchased monitoring table';
    var settings;

    const column = [ 
        { 'data' : 'PRODUCT' },
        { 'data' : 'ACTION' }
    ];

    const column_definition = [
        { 'width': '85%', 'aTargets': 0 },
        { 'width': '15%','bSortable': false, 'aTargets': 1 }
    ];

    const length_menu = show_all ? [[-1], ['All']] : [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']];

    settings = {
        'ajax': { 
            'url' : 'view/_parts_purchased_monitoring_generation.php',
            'method' : 'POST',
            'dataType': 'json',
            'data': {'type' : type},
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

function partsPurchasedMonitoringItemTable(datatable_name, buttons = false, show_all = false){
    var product_id = $('#product-id').text();
    const type = 'parts purchased monitoring item table';
    var settings;

    const column = [ 
        { 'data' : 'PART' },
        { 'data' : 'PRODUCT' },
        { 'data' : 'PART_INCOMING' },
        { 'data' : 'REFERENCE_NUMBER' },
        { 'data' : 'ISSUANCE_DATE' },
        { 'data' : 'QUANTITY' },
        { 'data' : 'QUANTITY_ISSUED' },
        { 'data' : 'QUANTITY_NOT_ISSUED' },
        { 'data' : 'STATUS' },
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
        { 'width': '15%','bSortable': false, 'aTargets': 10 }
    ];

    const length_menu = show_all ? [[-1], ['All']] : [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']];

    settings = {
        'ajax': { 
            'url' : 'view/_parts_purchased_monitoring_generation.php',
            'method' : 'POST',
            'dataType': 'json',
            'data': {'type' : type, 'product_id' : product_id},
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

function partsPurchasedMonitoringCancel(){
    $('#cancel-part-purchased-form').validate({
        rules: {
            cancellation_reason: {
                required: true
            },
        },
        messages: {
            cancellation_reason: {
                required: 'Please enter the cancellation reason'
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
            let parts_purchase_monitoring_item_id = sessionStorage.getItem('parts_purchase_monitoring_item_id');
            const transaction = 'tag parts purchase as cancelled';
        
            $.ajax({
                type: 'POST',
                url: 'controller/parts-purchased-monitoring-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction + '&parts_purchase_monitoring_item_id=' + parts_purchase_monitoring_item_id,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-cancel-part-purchased');
                },
                success: function (response) {
                    if (response.success) {
                        const notificationMessage = 'Cancel Parts Issuance Success';
                        const notificationDescription = 'The parts issuance has been cancelled successfully.';
                        
                        showNotification(notificationMessage, notificationDescription, 'success');
                        $('#cancel-part-purchased-offcanvas').offcanvas('hide');
                        reloadDatatable('#parts-purchased-monitoring-item-table');
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
                    enableFormSubmitButton('submit-cancel-part-purchased', 'Submit');
                }
            });
        
            return false;
        }
    });
}


$.validator.addMethod("notGreaterThanRemaining", function(value, element, params) {
    var remaining = parseFloat($(params).val());
    var received = parseFloat(value);
    return received <= remaining;
}, "Issued quantity cannot be greater than purchase quantity.");

function partsPurchasedMonitoringIssue(){
    $('#issue-part-purchased-form').validate({
        rules: {
            reference_number: {
                required: true
            },
            quantity_issued: {
                required: true,
                notGreaterThanRemaining: "#purchased_quantity"
            },
        },
        messages: {
            reference_number: {
                required: 'Please enter the reference number'
            },
            quantity_issued: {
                required: 'Please enter the issued quantity'
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
            let parts_purchase_monitoring_item_id = sessionStorage.getItem('parts_purchase_monitoring_item_id');
            const transaction = 'tag parts purchase as issued';
        
            $.ajax({
                type: 'POST',
                url: 'controller/parts-purchased-monitoring-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction + '&parts_purchase_monitoring_item_id=' + parts_purchase_monitoring_item_id,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-issue-part-purchased');
                },
                success: function (response) {
                    if (response.success) {
                        const notificationMessage = 'Issue Parts Success';
                        const notificationDescription = 'The parts has been issued successfully.';
                        
                        showNotification(notificationMessage, notificationDescription, 'success');
                        $('#issue-part-purchased-offcanvas').offcanvas('hide');
                        reloadDatatable('#parts-purchased-monitoring-item-table');
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
                    enableFormSubmitButton('submit-issue-part-purchased', 'Submit');
                }
            });
        
            return false;
        }
    });
}

function displayDetails(transaction){
    switch (transaction) {
        case 'get parts purchased monitoring item details':
            let parts_purchase_monitoring_item_id = sessionStorage.getItem('parts_purchase_monitoring_item_id');
            
            $.ajax({
                url: 'controller/parts-purchased-monitoring-controller.php',
                method: 'POST',
                dataType: 'json',
                data: {
                    parts_purchase_monitoring_item_id : parts_purchase_monitoring_item_id, 
                    transaction : transaction
                },
                success: function(response) {
                    if (response.success) {
                        $('#reference_number').val(response.reference_number);
                        $('#issuance_date').val(response.issuance_date);
                        $('#quantity_issued').val(response.quantity_issued);
                        $('#remarks').val(response.remarks);
                        $('#purchased_quantity').val(response.purchased_quantity);
                    } 
                    else {
                        if(response.isInactive){
                            window.location = 'logout.php?logout';
                        }
                        else{
                            showNotification('Get Parts Purchased Monitoring Details Error', response.message, 'danger');
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