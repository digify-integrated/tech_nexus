(function($) {
    'use strict';

    $(function() {
        if($('#product-expense-table').length){
            productExpenseTable('#product-expense-table');
        }

        if($('#product-expense-form').length){
            productExpenseForm();
        }

        $(document).on('click','.delete-product-expense',function() {
            const product_expense_id = $(this).data('product-expense-id');
            const transaction = 'delete product expense';
    
            Swal.fire({
                title: 'Confirm Expense Deletion',
                text: 'Are you sure you want to delete this expense?',
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
                        url: 'controller/product-controller.php',
                        dataType: 'json',
                        data: {
                            product_expense_id : product_expense_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                showNotification('Delete Expense Success', 'The expense has been deleted successfully.', 'success');
                                reloadDatatable('#product-expense-table');
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
                                    showNotification('Delete Expense Error', response.message, 'danger');
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

        $(document).on('click','#apply-filter',function() {
            productExpenseTable('#product-expense-table');
        });
    });
})(jQuery);

function productExpenseTable(datatable_name, buttons = false, show_all = false){
    const type = 'product expense table2';
    const product_id = $('#product-id').text();
    const reference_type_filter = $('#reference_type_filter').val();
    const expense_type_filter = $('#expense_type_filter').val();
    
    var filter_created_date_start_date = $('#filter_created_date_start_date').val();
    var filter_created_date_end_date = $('#filter_created_date_end_date').val();
    var filter_issuance_date_start_date = $('#filter_issuance_date_start_date').val();
    var filter_issuance_date_end_date = $('#filter_issuance_date_end_date').val();
    var settings;

    const column = [ 
        { 'data' : 'PRODUCT' },
        { 'data' : 'CREATED_DATE' },
        { 'data' : 'ISSUANCE_DATE' },
        { 'data' : 'REFERENCE_TYPE' },
        { 'data' : 'REFERENCE_NUMBER' },
        { 'data' : 'EXPENSE_AMOUNT' },
        { 'data' : 'PARTICULARS' },
        { 'data' : 'EXPENSE_TYPE' },
        { 'data' : 'ACTION' }
    ];
    
    const column_definition = [
        { 'width': '30%', 'aTargets': 0 },                             // PRODUCT (text)
        { 'width': 'auto', 'type': 'date', 'aTargets': 1 },            // CREATED_DATE (date)
        { 'width': 'auto', 'type': 'date', 'aTargets': 2 },            // CREATED_DATE (date)
        { 'width': 'auto', 'aTargets': 3 },                            // REFERENCE_TYPE (text)
        { 'width': 'auto', 'aTargets': 4 },                            // REFERENCE_NUMBER (text)
        { 'width': 'auto', 'type': 'num-fmt', 'aTargets': 5 },         // EXPENSE_AMOUNT (formatted number)
        { 'width': '30%', 'aTargets': 6 },                             // PARTICULARS (text)
        { 'width': 'auto', 'aTargets': 7 },                            // EXPENSE_TYPE (text)
        { 'width': '5%', 'bSortable': false, 'aTargets': 8 }           // ACTION (non-sortable)
    ];

    const length_menu = show_all ? [[-1], ['All']] : [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']];

    settings = {
        'ajax': { 
            'url' : 'view/_product_generation.php',
            'method' : 'POST',
            'dataType': 'json',
            'data': {
                'type' : type,
                'product_id' : product_id,
                'reference_type_filter' : reference_type_filter,
                'expense_type_filter' : expense_type_filter,
                'filter_created_date_start_date' : filter_created_date_start_date,
                'filter_created_date_end_date' : filter_created_date_end_date,
                'filter_issuance_date_start_date' : filter_issuance_date_start_date,
                'filter_issuance_date_end_date' : filter_issuance_date_end_date,
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

function productExpenseForm(){
    $('#product-expense-form').validate({
        rules: {
            product_id: {
                required: true
            },
            issuance_date: {
                required: true
            },
            reference_type: {
                required: true
            },
            reference_number: {
                required: true
            },
            expense_amount: {
                required: true
            },
            expense_type: {
                required: true
            },
            particulars: {
                required: true
            },
        },
        messages: {
            product_id: {
                required: 'Please choose the product'
            },
            issuance_date: {
                required: 'Please choose the reference date'
            },
            reference_type: {
                required: 'Please choose the reference type'
            },
            reference_number: {
                required: 'Please enter the reference number'
            },
            expense_amount: {
                required: 'Please enter the amount'
            },
            expense_type: {
                required: 'Please choose the expense type'
            },
            particulars: {
                required: 'Please enter the particulars'
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
            const transaction = 'save product expense';
        
            $.ajax({
                type: 'POST',
                url: 'controller/product-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-product-expense');
                },
                success: function (response) {
                    if (response.success) {
                        const notificationMessage = response.insertRecord ? 'Insert Expense Success' : 'Update Landed Cost Success';
                        const notificationDescription = response.insertRecord ? 'The expense has been inserted successfully.' : 'The landed cost has been updated successfully.';
                        
                        showNotification(notificationMessage, notificationDescription, 'success');
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
                    enableFormSubmitButton('submit-product-expense', 'Submit');
                    reloadDatatable('#product-expense-table');
                    $('#product-expense-offcanvas').offcanvas('hide');
                    resetModalForm('product-expense-form');
                }
            });
        
            return false;
        }
    });
}