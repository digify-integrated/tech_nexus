(function($) {
    'use strict';

    $(function() {
        if($('#unit-return-table').length){
            unitReturnTable('#unit-return-table');
        }

        if($('#receive-unit-form').length){
            receiveUnitForm();
        }

        $(document).on('click','.receive-unit',function() {
            const unit_return_id = $(this).data('unit-return-id');

            resetModalForm("receive-unit-form");

            sessionStorage.setItem('unit_return_id', unit_return_id);
        });

        $(document).on('click','#submit-filter',function() {
            if($('#unit-return-table').length){
                unitReturnTable('#unit-return-table');
            }

            $('#filter-offcanvas').offcanvas('hide');
        });
    });
})(jQuery);

function unitReturnTable(datatable_name, buttons = false, show_all = false){
    const type = 'unit return table';
    var estimated_return_date_start_date = $('#estimated_return_date_start_date').val();
    var estimated_return_date_end_date = $('#estimated_return_date_end_date').val();
    var return_date_start_date = $('#return_date_start_date').val();
    var return_date_end_date = $('#return_date_end_date').val();

     var unit_return_status_filter = [];

    $('.unit-return-status-checkbox:checked').each(function() {
        unit_return_status_filter.push($(this).val());
    });

    var filter_unit_return_status = unit_return_status_filter.join(', ');

    var settings;

    const column = [ 
        { 'data' : 'RELEASED_TO' },
        { 'data' : 'PRODUCT' },
        { 'data' : 'ESTIMATED_RETURN_DATE' },
        { 'data' : 'RETURN_DATE' },
        { 'data' : 'OVERDUE' },
        { 'data' : 'STATUS' },
        { 'data' : 'INCOMING_CHECKLIST' },
        { 'data' : 'ACTION' }
    ];

    const column_definition = [
        { 'width': '20%', 'aTargets': 0 },
        { 'width': '25%', 'aTargets': 1 },
        { 'width': '10%', 'type': 'date', 'aTargets': 2 },
        { 'width': '10%', 'type': 'date', 'aTargets': 3 },
        { 'width': '10%', 'aTargets': 4 },
        { 'width': '10%', 'aTargets': 5 },
        { 'width': '10%', 'aTargets': 6 },
        { 'width': '15%','bSortable': false, 'aTargets': 7 }
    ];

    const length_menu = show_all ? [[-1], ['All']] : [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']];

    settings = {
        'ajax': { 
            'url' : 'view/_internal_dr_generation.php',
            'method' : 'POST',
            'dataType': 'json',
            'data': {
                'type' : type,
                'filter_unit_return_status' : filter_unit_return_status,
                'estimated_return_date_start_date' : estimated_return_date_start_date,
                'estimated_return_date_end_date' : estimated_return_date_end_date,
                'return_date_start_date' : return_date_start_date,
                'return_date_end_date' : return_date_end_date,
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
            var unit_return_id = sessionStorage.getItem('unit_return_id');
            const transaction = 'receive unit';
    
            var formData = new FormData(form);
            formData.append('unit_return_id', unit_return_id);
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
                        reloadDatatable('#unit-return-table');
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