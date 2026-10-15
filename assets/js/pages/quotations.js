(function($) {
    'use strict';

    $(function() {
        if($('#quotations-table').length){
            quotationsTable('#quotations-table');
        }

        if($('#receive-unit-form').length){
            receiveUnitForm();
        }

        $(document).on('click','.receive-unit',function() {
            const quotations_id = $(this).data('quotations-id');

            resetModalForm("receive-unit-form");

            sessionStorage.setItem('quotations_id', quotations_id);
        });

        $(document).on('click','#submit-filter',function() {
            if($('#quotations-table').length){
                quotationsTable('#quotations-table');
            }

            $('#filter-offcanvas').offcanvas('hide');
        });

        $(document).on('click', '.approve-quotation', function(){
            const quotation_id = $(this).data('quotation-id');

            Swal.fire({
                title: 'Approve Quotation',
                text: 'Are you sure you want to tag this quotation as approved?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Approve',
                cancelButtonText: 'Cancel',
            }).then((result) => {

                if(result.isConfirmed){

                    $.ajax({
                        type: 'POST',
                        url: 'controller/lead-controller.php',
                        data: {
                            transaction: 'approve quotation',
                            quotation_id: quotation_id
                        },
                        dataType: 'json',

                        success: function(response){

                            if(response.success){
                                showNotification(
                                    'Success',
                                    'The quotation has been tagged as approved.',
                                    'success'
                                );

                                reloadDatatable('#quotations-table');
                            }
                            else{

                                showNotification(
                                    'Error',
                                    response.message,
                                    'danger'
                                );
                            }
                        },

                        error: function(){

                            showNotification(
                                'Error',
                                'Unable to tag quotation for approval.',
                                'danger'
                            );
                        }
                    });
                }
            });
        });

        $(document).on('click', '.cancel-quotation', function(){
            const quotation_id = $(this).data('quotation-id');

            Swal.fire({
                title: 'Cancel Quotation',
                text: 'Are you sure you want to tag this quotation as cancel?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Cancel',
                cancelButtonText: 'Cancel',
            }).then((result) => {

                if(result.isConfirmed){

                    $.ajax({
                        type: 'POST',
                        url: 'controller/lead-controller.php',
                        data: {
                            transaction: 'cancel quotation',
                            quotation_id: quotation_id
                        },
                        dataType: 'json',

                        success: function(response){

                            if(response.success){
                                showNotification(
                                    'Success',
                                    'The quotation has been tagged as cancelled.',
                                    'success'
                                );

                                reloadDatatable('#quotations-table');
                            }
                            else{

                                showNotification(
                                    'Error',
                                    response.message,
                                    'danger'
                                );
                            }
                        },

                        error: function(){

                            showNotification(
                                'Error',
                                'Unable to tag quotation for approval.',
                                'danger'
                            );
                        }
                    });
                }
            });
        });
    });
})(jQuery);

function quotationsTable(datatable_name, buttons = false, show_all = false){
    const type = 'quotations table';

     var quotations_status_filter = [];

    $('.quotations-status-checkbox:checked').each(function() {
        quotations_status_filter.push($(this).val());
    });

    var filter_quotations_status = quotations_status_filter.join(', ');

    var settings;

    const column = [ 
        { 'data' : 'REFERENCE_NO' },
        { 'data' : 'PRODUCT' },
        { 'data' : 'PRICE' },
        { 'data' : 'STATUS' },
        { 'data' : 'COMPANY' },
        { 'data' : 'CREATED_BY' },
        { 'data' : 'EXPIRATION_DATE' },
        { 'data' : 'CREATED_DATE' },
        { 'data' : 'REMARKS' },
        { 'data' : 'ACTION' }
    ];

    const column_definition = [
        { 'width': '5%','bSortable': false, 'aTargets': 9 }
    ];

    const length_menu = show_all ? [[-1], ['All']] : [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']];

    settings = {
        'ajax': { 
            'url' : 'view/_lead_generation.php',
            'method' : 'POST',
            'dataType': 'json',
            'data': {
                'type' : type,
                'filter_quotations_status' : filter_quotations_status,
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
        'order': [[ 2, 'desc' ]],
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

function receiveUnitForm(){
    $('#receive-unit-form').validate({
        rules: {
            incoming_checklist: {
                required: true
            },
        },
        messages: {
            incoming_checklist: {
                required: 'Please choose the incoming checklist'
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
            var quotations_id = sessionStorage.getItem('quotations_id');
            const transaction = 'receive unit';
    
            var formData = new FormData(form);
            formData.append('quotations_id', quotations_id);
            formData.append('transaction', transaction);
        
            $.ajax({
                type: 'POST',
                url: 'controller/internal-dr-controller.php',
                data: formData,
                processData: false,
                contentType: false,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-receive-unit');
                },
                success: function (response) {
                    if (response.success) {
                        showNotification('Receive Unit Success', 'The unit has been received successfully', 'success');
                        reloadDatatable('#quotations-table');
                        $('#receive-unit-offcanvas').offcanvas('hide');
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
                    enableFormSubmitButton('submit-receive-unit', 'Submit');
                }
            });
        
            return false;
        }
    });
}