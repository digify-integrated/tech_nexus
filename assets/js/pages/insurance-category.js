(function($) {
    'use strict';

    $(function() {
        if($('#insurance-category-table').length){
            insuranceCategoryTable('#insurance-category-table');
        }

        if($('#insurance-category-form').length){
            insuranceCategoryForm();
        }

        if($('#insurance-category-id').length){
            displayDetails('get insurance category details');
        }

        $(document).on('click','.delete-insurance-category',function() {
            const insurance_category_id = $(this).data('insurance-category-id');
            const transaction = 'delete insurance category';
    
            Swal.fire({
                title: 'Confirm Insurance Category Deletion',
                text: 'Are you sure you want to delete this insurance category?',
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
                        url: 'controller/insurance-category-controller.php',
                        dataType: 'json',
                        data: {
                            insurance_category_id : insurance_category_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                showNotification('Delete Insurance Category Success', 'The insurance category has been deleted successfully.', 'success');
                                reloadDatatable('#insurance-category-table');
                            }
                            else {
                                if (response.isInactive) {
                                    setNotification('User Inactive', response.message, 'danger');
                                    window.location = 'logout.php?logout';
                                }
                                else if (response.notExist) {
                                    showNotification('Delete Insurance Category Error', 'The insurance category does not exist.', 'danger');
                                    reloadDatatable('#insurance-category-table');
                                }
                                else {
                                    showNotification('Delete Insurance Category Error', response.message, 'danger');
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

        $(document).on('click','#delete-insurance-category',function() {
            let insurance_category_id = [];
            const transaction = 'delete multiple insurance category';

            $('.datatable-checkbox-children').each((index, element) => {
                if ($(element).is(':checked')) {
                    insurance_category_id.push(element.value);
                }
            });
    
            if(insurance_category_id.length > 0){
                Swal.fire({
                    title: 'Confirm Multiple Insurance Category Deletion',
                    text: 'Are you sure you want to delete these insurance category?',
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
                            url: 'controller/insurance-category-controller.php',
                            dataType: 'json',
                            data: {
                                insurance_category_id: insurance_category_id,
                                transaction : transaction
                            },
                            success: function (response) {
                                if (response.success) {
                                    showNotification('Delete Insurance Category Success', 'The selected insurance category have been deleted successfully.', 'success');
                                        reloadDatatable('#insurance-category-table');
                                }
                                else {
                                    if (response.isInactive) {
                                        setNotification('User Inactive', response.message, 'danger');
                                        window.location = 'logout.php?logout';
                                    }
                                    else {
                                        showNotification('Delete Insurance Category Error', response.message, 'danger');
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
                showNotification('Deletion Multiple Insurance Category Error', 'Please select the insurance category you wish to delete.', 'danger');
            }
        });

        $(document).on('click','#delete-insurance-category-details',function() {
            const insurance_category_id = $('#insurance-category-id').text();
            const transaction = 'delete insurance category';
    
            Swal.fire({
                title: 'Confirm Insurance Category Deletion',
                text: 'Are you sure you want to delete this insurance category?',
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
                        url: 'controller/insurance-category-controller.php',
                        dataType: 'json',
                        data: {
                            insurance_category_id : insurance_category_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                setNotification('Deleted Insurance Category Success', 'The insurance category has been deleted successfully.', 'success');
                                window.location = 'insurance-category.php';
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
                                    showNotification('Delete Insurance Category Error', response.message, 'danger');
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
            discardCreate('insurance-category.php');
        });

        $(document).on('click','#edit-form',function() {
            displayDetails('get insurance category details');

            enableForm();
        });

        $(document).on('click','#duplicate-insurance-category',function() {
            const insurance_category_id = $('#insurance-category-id').text();
            const transaction = 'duplicate insurance category';
    
            Swal.fire({
                title: 'Confirm Insurance Category Duplication',
                text: 'Are you sure you want to duplicate this insurance category?',
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
                        url: 'controller/insurance-category-controller.php',
                        dataType: 'json',
                        data: {
                            insurance_category_id : insurance_category_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                setNotification('Duplicate Insurance Category Success', 'The insurance category has been duplicated successfully.', 'success');
                                window.location = 'insurance-category.php?id=' + response.insuranceCategoryID;
                            }
                            else {
                                if (response.isInactive) {
                                    setNotification('User Inactive', response.message, 'danger');
                                    window.location = 'logout.php?logout';
                                }
                                else if (response.notExist) {
                                    showNotification('Duplicate Insurance Category Error', 'The insurance category does not exist.', 'danger');
                                    reloadDatatable('#insurance-category-table');
                                }
                                else {
                                    showNotification('Duplicate Insurance Category Error', response.message, 'danger');
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

function insuranceCategoryTable(datatable_name, buttons = false, show_all = false){
    const type = 'insurance category table';
    var settings;

    const column = [ 
        { 'data' : 'CHECK_BOX' },
        { 'data' : 'INSURANCE_CATEGORY_NAME' },
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
            'url' : 'view/_insurance_category_generation.php',
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

function insuranceCategoryForm(){
    $('#insurance-category-form').validate({
        rules: {
            insurance_category_name: {
                required: true
            },
        },
        messages: {
            insurance_category_name: {
                required: 'Please enter the insurance category name'
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
            const insurance_category_id = $('#insurance-category-id').text();
            const transaction = 'save insurance category';
        
            $.ajax({
                type: 'POST',
                url: 'controller/insurance-category-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction + '&insurance_category_id=' + insurance_category_id,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-data');
                },
                success: function (response) {
                    if (response.success) {
                        const notificationMessage = response.insertRecord ? 'Insert Insurance Category Success' : 'Update Insurance Category Success';
                        const notificationDescription = response.insertRecord ? 'The insurance category has been inserted successfully.' : 'The insurance category has been updated successfully.';
                        
                        setNotification(notificationMessage, notificationDescription, 'success');
                        window.location = 'insurance-category.php?id=' + response.insuranceCategoryID;
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
        case 'get insurance category details':
            const insurance_category_id = $('#insurance-category-id').text();
            
            $.ajax({
                url: 'controller/insurance-category-controller.php',
                method: 'POST',
                dataType: 'json',
                data: {
                    insurance_category_id : insurance_category_id, 
                    transaction : transaction
                },
                beforeSend: function() {
                    resetForm('insurance-category-form');
                },
                success: function(response) {
                    if (response.success) {
                        $('#insurance_category_id').val(insurance_category_id);
                        $('#insurance_category_name').val(response.insuranceCategoryName);

                        $('#insurance_category_name_label').text(response.insuranceCategoryName);
                    } 
                    else {
                        if(response.isInactive){
                            window.location = 'logout.php?logout';
                        }
                        else{
                            showNotification('Get Insurance Category Details Error', response.message, 'danger');
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