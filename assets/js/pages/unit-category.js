(function($) {
    'use strict';

    $(function() {
        if($('#unit-category-table').length){
            unitCategoryTable('#unit-category-table');
        }

        if($('#unit-category-form').length){
            unitCategoryForm();
        }

        if($('#unit-category-id').length){
            displayDetails('get unit category details');
        }

        $(document).on('click','.delete-unit-category',function() {
            const unit_category_id = $(this).data('unit-category-id');
            const transaction = 'delete unit category';
    
            Swal.fire({
                title: 'Confirm Unit Category Deletion',
                text: 'Are you sure you want to delete this unit category?',
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
                        url: 'controller/unit-category-controller.php',
                        dataType: 'json',
                        data: {
                            unit_category_id : unit_category_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                showNotification('Delete Unit Category Success', 'The unit category has been deleted successfully.', 'success');
                                reloadDatatable('#unit-category-table');
                            }
                            else {
                                if (response.isInactive) {
                                    setNotification('User Inactive', response.message, 'danger');
                                    window.location = 'logout.php?logout';
                                }
                                else if (response.notExist) {
                                    showNotification('Delete Unit Category Error', 'The unit category does not exist.', 'danger');
                                    reloadDatatable('#unit-category-table');
                                }
                                else {
                                    showNotification('Delete Unit Category Error', response.message, 'danger');
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

        $(document).on('click','#delete-unit-category',function() {
            let unit_category_id = [];
            const transaction = 'delete multiple unit category';

            $('.datatable-checkbox-children').each((index, element) => {
                if ($(element).is(':checked')) {
                    unit_category_id.push(element.value);
                }
            });
    
            if(unit_category_id.length > 0){
                Swal.fire({
                    title: 'Confirm Multiple Unit Categories Deletion',
                    text: 'Are you sure you want to delete these unit categories?',
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
                            url: 'controller/unit-category-controller.php',
                            dataType: 'json',
                            data: {
                                unit_category_id: unit_category_id,
                                transaction : transaction
                            },
                            success: function (response) {
                                if (response.success) {
                                    showNotification('Delete Unit Category Success', 'The selected unit categories have been deleted successfully.', 'success');
                                    reloadDatatable('#unit-category-table');
                                }
                                else {
                                    if (response.isInactive) {
                                        setNotification('User Inactive', response.message, 'danger');
                                        window.location = 'logout.php?logout';
                                    }
                                    else {
                                        showNotification('Delete Unit Category Error', response.message, 'danger');
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
                showNotification('Deletion Multiple Unit Category Error', 'Please select the unit categories you wish to delete.', 'danger');
            }
        });

        $(document).on('click','#delete-unit-category-details',function() {
            const unit_category_id = $('#unit-category-id').text();
            const transaction = 'delete unit category';
    
            Swal.fire({
                title: 'Confirm Unit Category Deletion',
                text: 'Are you sure you want to delete this unit category?',
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
                        url: 'controller/unit-category-controller.php',
                        dataType: 'json',
                        data: {
                            unit_category_id : unit_category_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                setNotification('Deleted Unit Category Success', 'The unit category has been deleted successfully.', 'success');
                                window.location = 'unit-category.php';
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
                                    showNotification('Delete Unit Category Error', response.message, 'danger');
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
            discardCreate('unit-category.php');
        });

        $(document).on('click','#edit-form',function() {
            displayDetails('get unit category details');

            enableForm();
        });

        $(document).on('click','#duplicate-unit-category',function() {
            const unit_category_id = $('#unit-category-id').text();
            const transaction = 'duplicate unit category';
    
            Swal.fire({
                title: 'Confirm Unit Category Duplication',
                text: 'Are you sure you want to duplicate this unit category?',
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
                        url: 'controller/unit-category-controller.php',
                        dataType: 'json',
                        data: {
                            unit_category_id : unit_category_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                setNotification('Duplicate Unit Category Success', 'The unit category has been duplicated successfully.', 'success');
                                window.location = 'unit-category.php?id=' + response.unitCategoryID;
                            }
                            else {
                                if (response.isInactive) {
                                    setNotification('User Inactive', response.message, 'danger');
                                    window.location = 'logout.php?logout';
                                }
                                else if (response.notExist) {
                                    showNotification('Duplicate Unit Category Error', 'The unit category does not exist.', 'danger');
                                    reloadDatatable('#unit-category-table');
                                }
                                else {
                                    showNotification('Duplicate Unit Category Error', response.message, 'danger');
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

function unitCategoryTable(datatable_name, buttons = false, show_all = false){
    const type = 'unit category table';
    var settings;

    const column = [ 
        { 'data' : 'CHECK_BOX' },
        { 'data' : 'UNIT_CATEGORY_NAME' },
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
            'url' : 'view/_unit_category_generation.php',
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

function unitCategoryForm(){
    $('#unit-category-form').validate({
        rules: {
            unit_category_name: {
                required: true
            },
        },
        messages: {
            unit_category_name: {
                required: 'Please enter the unit category name'
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
            const unit_category_id = $('#unit-category-id').text();
            const transaction = 'save unit category';
        
            $.ajax({
                type: 'POST',
                url: 'controller/unit-category-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction + '&unit_category_id=' + unit_category_id,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-data');
                },
                success: function (response) {
                    if (response.success) {
                        const notificationMessage = response.insertRecord ? 'Insert Unit Category Success' : 'Update Unit Category Success';
                        const notificationDescription = response.insertRecord ? 'The unit category has been inserted successfully.' : 'The unit category has been updated successfully.';
                        
                        setNotification(notificationMessage, notificationDescription, 'success');
                        window.location = 'unit-category.php?id=' + response.unitCategoryID;
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
        case 'get unit category details':
            const unit_category_id = $('#unit-category-id').text();
            
            $.ajax({
                url: 'controller/unit-category-controller.php',
                method: 'POST',
                dataType: 'json',
                data: {
                    unit_category_id : unit_category_id, 
                    transaction : transaction
                },
                beforeSend: function() {
                    resetForm('unit-category-form');
                },
                success: function(response) {
                    if (response.success) {
                        $('#unit_category_id').val(unit_category_id);
                        $('#unit_category_name').val(response.unitCategoryName);

                        $('#unit_category_name_label').text(response.unitCategoryName);
                    } 
                    else {
                        if(response.isInactive){
                            window.location = 'logout.php?logout';
                        }
                        else{
                            showNotification('Get Unit Category Details Error', response.message, 'danger');
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