(function($) {
    'use strict';

    $(function() {
        if($('#supplier-table').length){
            supplierTable('#supplier-table');
        }

        if($('#supplier-form').length){
            supplierForm();
        }

        if($('#supplier-id').length){
            displayDetails('get supplier details');
        }

        $(document).on('click','.delete-supplier',function() {
            const supplier_id = $(this).data('supplier-id');
            const transaction = 'delete supplier';
    
            Swal.fire({
                title: 'Confirm Supplier Deletion',
                text: 'Are you sure you want to delete this supplier?',
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
                        url: 'controller/supplier-controller.php',
                        dataType: 'json',
                        data: {
                            supplier_id : supplier_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                showNotification('Delete Supplier Success', 'The supplier has been deleted successfully.', 'success');
                                reloadDatatable('#supplier-table');
                            }
                            else {
                                if (response.isInactive) {
                                    setNotification('User Inactive', response.message, 'danger');
                                    window.location = 'logout.php?logout';
                                }
                                else if (response.notExist) {
                                    showNotification('Delete Supplier Error', 'The supplier does not exist.', 'danger');
                                    reloadDatatable('#supplier-table');
                                }
                                else {
                                    showNotification('Delete Supplier Error', response.message, 'danger');
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

        $(document).on('click','#delete-supplier',function() {
            let supplier_id = [];
            const transaction = 'delete multiple supplier';

            $('.datatable-checkbox-children').each((index, element) => {
                if ($(element).is(':checked')) {
                    supplier_id.push(element.value);
                }
            });
    
            if(supplier_id.length > 0){
                Swal.fire({
                    title: 'Confirm Multiple Suppliers Deletion',
                    text: 'Are you sure you want to delete these suppliers?',
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
                            url: 'controller/supplier-controller.php',
                            dataType: 'json',
                            data: {
                                supplier_id: supplier_id,
                                transaction : transaction
                            },
                            success: function (response) {
                                if (response.success) {
                                    showNotification('Delete Supplier Success', 'The selected suppliers have been deleted successfully.', 'success');
                                    reloadDatatable('#supplier-table');
                                }
                                else {
                                    if (response.isInactive) {
                                        setNotification('User Inactive', response.message, 'danger');
                                        window.location = 'logout.php?logout';
                                    }
                                    else {
                                        showNotification('Delete Supplier Error', response.message, 'danger');
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
                showNotification('Deletion Multiple Supplier Error', 'Please select the suppliers you wish to delete.', 'danger');
            }
        });

        $(document).on('click','#delete-supplier-details',function() {
            const supplier_id = $('#supplier-id').text();
            const transaction = 'delete supplier';
    
            Swal.fire({
                title: 'Confirm Supplier Deletion',
                text: 'Are you sure you want to delete this supplier?',
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
                        url: 'controller/supplier-controller.php',
                        dataType: 'json',
                        data: {
                            supplier_id : supplier_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                setNotification('Deleted Supplier Success', 'The supplier has been deleted successfully.', 'success');
                                window.location = 'supplier.php';
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
                                    showNotification('Delete Supplier Error', response.message, 'danger');
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
            discardCreate('supplier.php');
        });

        $(document).on('click','#edit-form',function() {
            displayDetails('get supplier details');

            enableForm();
        });

        $(document).on('click','#duplicate-supplier',function() {
            const supplier_id = $('#supplier-id').text();
            const transaction = 'duplicate supplier';
    
            Swal.fire({
                title: 'Confirm Supplier Duplication',
                text: 'Are you sure you want to duplicate this supplier?',
                icon: 'info',
                showCancelButton: !0,
                confirmButtonText: 'Duplicate',
                cancelButtonText: 'Cancel',
                confirmButtonClass: 'btn btn-info mt-2',
                cancelButtonClass: 'btn btn-secondary ms-2 mt-2',
                buttonsStyling: !1
            }).then(function(result) {
                if (result.value) {
                    $.ajax({
                        type: 'POST',
                        url: 'controller/supplier-controller.php',
                        dataType: 'json',
                        data: {
                            supplier_id : supplier_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                setNotification('Duplicate Supplier Success', 'The supplier has been duplicated successfully.', 'success');
                                window.location = 'supplier.php?id=' + response.supplierID;
                            }
                            else {
                                if (response.isInactive) {
                                    setNotification('User Inactive', response.message, 'danger');
                                    window.location = 'logout.php?logout';
                                }
                                else if (response.notExist) {
                                    showNotification('Duplicate Supplier Error', 'The supplier does not exist.', 'danger');
                                    reloadDatatable('#supplier-table');
                                }
                                else {
                                    showNotification('Duplicate Supplier Error', response.message, 'danger');
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

function supplierTable(datatable_name, buttons = false, show_all = false){
    const type = 'supplier table';
    var settings;

    const column = [ 
        { 'data' : 'CHECK_BOX' },
        { 'data' : 'SUPPLIER_NAME' },
        { 'data' : 'ACTION' }
    ];

    const column_definition = [
        { 'width': '1%','bSortable': false, 'aTargets': 0 },
        { 'width': '84%', 'aTargets': 1 },
        { 'width': '15%','bSortable': false, 'aTargets': 2 }
    ];

    const length_menu = show_all ? [[-1], ['All']] : [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']];

    settings = {
        'ajax': { 
            'url' : 'view/_supplier_generation.php',
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

function supplierForm(){
    $('#supplier-form').validate({
        rules: {
            supplier_name: {
                required: true
            },
        },
        messages: {
            supplier_name: {
                required: 'Please enter the supplier name'
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
            const supplier_id = $('#supplier-id').text();
            const transaction = 'save supplier';
        
            $.ajax({
                type: 'POST',
                url: 'controller/supplier-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction + '&supplier_id=' + supplier_id,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-data');
                },
                success: function (response) {
                    if (response.success) {
                        const notificationMessage = response.insertRecord ? 'Insert Supplier Success' : 'Update Supplier Success';
                        const notificationDescription = response.insertRecord ? 'The supplier has been inserted successfully.' : 'The supplier has been updated successfully.';
                        
                        setNotification(notificationMessage, notificationDescription, 'success');
                        window.location = 'supplier.php?id=' + response.supplierID;
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
                }
            });
        
            return false;
        }
    });
}

function displayDetails(transaction){
    switch (transaction) {
        case 'get supplier details':
            const supplier_id = $('#supplier-id').text();
            
            $.ajax({
                url: 'controller/supplier-controller.php',
                method: 'POST',
                dataType: 'json',
                data: {
                    supplier_id : supplier_id, 
                    transaction : transaction
                },
                beforeSend: function() {
                    resetForm('supplier-form');
                },
                success: function(response) {
                    if (response.success) {
                        $('#supplier_id').val(supplier_id);
                        $('#supplier_name').val(response.supplierName);

                        $('#supplier_name_label').text(response.supplierName);
                    } 
                    else {
                        if(response.isInactive){
                            window.location = 'logout.php?logout';
                        }
                        else{
                            showNotification('Get Supplier Details Error', response.message, 'danger');
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