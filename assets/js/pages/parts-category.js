(function($) {
    'use strict';

    $(function() {
        if($('#parts-category-table').length){
            partsCategoryTable('#parts-category-table');
        }

        if($('#parts-category-form').length){
            partsCategoryForm();
        }

        if($('#parts-category-id').length){
            displayDetails('get parts category details');
        }

        $(document).on('click','.delete-parts-category',function() {
            const parts_category_id = $(this).data('parts-category-id');
            const transaction = 'delete parts category';
    
            Swal.fire({
                title: 'Confirm Parts Category Deletion',
                text: 'Are you sure you want to delete this parts category?',
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
                        url: 'controller/parts-category-controller.php',
                        dataType: 'json',
                        data: {
                            parts_category_id : parts_category_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                showNotification('Delete Parts Category Success', 'The parts category has been deleted successfully.', 'success');
                                reloadDatatable('#parts-category-table');
                            }
                            else {
                                if (response.isInactive) {
                                    setNotification('User Inactive', response.message, 'danger');
                                    window.location = 'logout.php?logout';
                                }
                                else if (response.notExist) {
                                    showNotification('Delete Parts Category Error', 'The parts category does not exist.', 'danger');
                                    reloadDatatable('#parts-category-table');
                                }
                                else {
                                    showNotification('Delete Parts Category Error', response.message, 'danger');
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

        $(document).on('click','#delete-parts-category',function() {
            let parts_category_id = [];
            const transaction = 'delete multiple parts category';

            $('.datatable-checkbox-children').each((index, element) => {
                if ($(element).is(':checked')) {
                    parts_category_id.push(element.value);
                }
            });
    
            if(parts_category_id.length > 0){
                Swal.fire({
                    title: 'Confirm Multiple Part Classes Deletion',
                    text: 'Are you sure you want to delete these parts categoryes?',
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
                            url: 'controller/parts-category-controller.php',
                            dataType: 'json',
                            data: {
                                parts_category_id: parts_category_id,
                                transaction : transaction
                            },
                            success: function (response) {
                                if (response.success) {
                                    showNotification('Delete Parts Category Success', 'The selected parts categoryes have been deleted successfully.', 'success');
                                    reloadDatatable('#parts-category-table');
                                }
                                else {
                                    if (response.isInactive) {
                                        setNotification('User Inactive', response.message, 'danger');
                                        window.location = 'logout.php?logout';
                                    }
                                    else {
                                        showNotification('Delete Parts Category Error', response.message, 'danger');
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
                showNotification('Deletion Multiple Parts Category Error', 'Please select the parts categoryes you wish to delete.', 'danger');
            }
        });

        $(document).on('click','#delete-parts-category-details',function() {
            const parts_category_id = $('#parts-category-id').text();
            const transaction = 'delete parts category';
    
            Swal.fire({
                title: 'Confirm Parts Category Deletion',
                text: 'Are you sure you want to delete this parts category?',
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
                        url: 'controller/parts-category-controller.php',
                        dataType: 'json',
                        data: {
                            parts_category_id : parts_category_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                setNotification('Deleted Parts Category Success', 'The parts category has been deleted successfully.', 'success');
                                window.location = 'parts-category.php';
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
                                    showNotification('Delete Parts Category Error', response.message, 'danger');
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
            discardCreate('parts-category.php');
        });

        $(document).on('click','#edit-form',function() {
            displayDetails('get parts category details');

            enableForm();
        });

        $(document).on('click','#duplicate-parts-category',function() {
            const parts_category_id = $('#parts-category-id').text();
            const transaction = 'duplicate parts category';
    
            Swal.fire({
                title: 'Confirm Parts Category Duplication',
                text: 'Are you sure you want to duplicate this parts category?',
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
                        url: 'controller/parts-category-controller.php',
                        dataType: 'json',
                        data: {
                            parts_category_id : parts_category_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                setNotification('Duplicate Parts Category Success', 'The parts category has been duplicated successfully.', 'success');
                                window.location = 'parts-category.php?id=' + response.partsCategoryID;
                            }
                            else {
                                if (response.isInactive) {
                                    setNotification('User Inactive', response.message, 'danger');
                                    window.location = 'logout.php?logout';
                                }
                                else if (response.notExist) {
                                    showNotification('Duplicate Parts Category Error', 'The parts category does not exist.', 'danger');
                                    reloadDatatable('#parts-category-table');
                                }
                                else {
                                    showNotification('Duplicate Parts Category Error', response.message, 'danger');
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

function partsCategoryTable(datatable_name, buttons = false, show_all = false){
    const type = 'parts category table';
    var settings;

    const column = [ 
        { 'data' : 'CHECK_BOX' },
        { 'data' : 'PARTS_CATEGORY' },
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
            'url' : 'view/_parts_category_generation.php',
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

function partsCategoryForm(){
    $('#parts-category-form').validate({
        rules: {
            parts_category_name: {
                required: true
            },
        },
        messages: {
            parts_category_name: {
                required: 'Please enter the parts category name'
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
            const parts_category_id = $('#parts-category-id').text();
            const transaction = 'save parts category';
        
            $.ajax({
                type: 'POST',
                url: 'controller/parts-category-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction + '&parts_category_id=' + parts_category_id,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-data');
                },
                success: function (response) {
                    if (response.success) {
                        const notificationMessage = response.insertRecord ? 'Insert Parts Category Success' : 'Update Parts Category Success';
                        const notificationDescription = response.insertRecord ? 'The parts category has been inserted successfully.' : 'The parts category has been updated successfully.';
                        
                        setNotification(notificationMessage, notificationDescription, 'success');
                        window.location = 'parts-category.php?id=' + response.partsCategoryID;
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
        case 'get parts category details':
            const parts_category_id = $('#parts-category-id').text();
            
            $.ajax({
                url: 'controller/parts-category-controller.php',
                method: 'POST',
                dataType: 'json',
                data: {
                    parts_category_id : parts_category_id, 
                    transaction : transaction
                },
                beforeSend: function() {
                    resetForm('parts-category-form');
                },
                success: function(response) {
                    if (response.success) {
                        $('#parts_category_id').val(parts_category_id);
                        $('#parts_category_name').val(response.partsCategoryName);

                        $('#parts_category_name_label').text(response.partsCategoryName);
                    } 
                    else {
                        if(response.isInactive){
                            window.location = 'logout.php?logout';
                        }
                        else{
                            showNotification('Get Parts Category Details Error', response.message, 'danger');
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