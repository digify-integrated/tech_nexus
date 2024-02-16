(function($) {
    'use strict';

    $(function() {
        if($('#search-customer-result-table').length){
            searchResultTable('#search-customer-result-table');
        }

        if($('#search-customer-form').length){
            searchCustomerForm();
        }
    });
})(jQuery);

function searchResultTable(datatable_name, buttons = false, show_all = false){
    const type = 'search result table';
    var first_name = $('#first_name').val();
    var middle_name = $('#middle_name').val();
    var last_name = $('#last_name').val();
    var settings;

    const column = [ 
        { 'data' : 'FILE_AS' },
        { 'data' : 'ACTION' }
    ];

    const column_definition = [
        { 'width': '85%', 'aTargets': 0 },
        { 'width': '15%','bSortable': false, 'aTargets': 1 }
    ];

    const length_menu = show_all ? [[-1], ['All']] : [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']];

    settings = {
        'ajax': { 
            'url' : 'view/_search_customer_generation.php',
            'method' : 'POST',
            'dataType': 'json',
            'data': {'type' : type, 'first_name' : first_name, 'middle_name' : middle_name, 'last_name' : last_name},
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

function searchCustomerForm(){
    $('#search-customer-form').validate({
        rules: {
            first_name: {
                required: true
            },
            last_name: {
                required: true
            }
        },
        messages: {
            first_name: {
                required: 'Please enter the first name'
            },
            last_name: {
                required: 'Please enter the last name'
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
            const transaction = 'search customer';
        
            $.ajax({
                type: 'POST',
                url: 'controller/customer-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-data');
                    $('#search-results').addClass('d-none');
                },
                success: function (response) {
                    if (response.success) {
                        const resultCount = response.resultCount;
                        const notificationTitle = 'Search Customer Success';
                        let notificationMessage;

                        if (resultCount === 0) {
                            notificationMessage = 'No customers found.';
                        } else if (resultCount === 1) {
                            notificationMessage = '1 customer found.';
                        } else {
                            notificationMessage = `${resultCount} customers found.`;
                        }

                        showNotification(notificationTitle, notificationMessage, 'success');
                        searchResultTable('#search-customer-result-table');
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
                    $('#search-results').removeClass('d-none');
                }
            });
        
            return false;
        }
    });
}