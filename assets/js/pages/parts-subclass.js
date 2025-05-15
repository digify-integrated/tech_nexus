(function($) {
    'use strict';

    $(function() {
        if($('#parts-subclass-table').length){
            partsSubclassTable('#parts-subclass-table');
        }

        if($('#parts-subclass-form').length){
            partsSubclassForm();
        }

        if($('#parts-subclass-id').length){
            displayDetails('get parts subclass details');
        }

        $(document).on('click','.delete-parts-subclass',function() {
            const parts_subclass_id = $(this).data('parts-subclass-id');
            const transaction = 'delete parts subclass';
    
            Swal.fire({
                title: 'Confirm Part Subclass Deletion',
                text: 'Are you sure you want to delete this parts subclass?',
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
                        url: 'controller/parts-subclass-controller.php',
                        dataType: 'json',
                        data: {
                            parts_subclass_id : parts_subclass_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                showNotification('Delete Part Subclass Success', 'The parts subclass has been deleted successfully.', 'success');
                                reloadDatatable('#parts-subclass-table');
                            }
                            else {
                                if (response.isInactive) {
                                    setNotification('User Inactive', response.message, 'danger');
                                    window.location = 'logout.php?logout';
                                }
                                else if (response.notExist) {
                                    showNotification('Delete Part Subclass Error', 'The parts subclass does not exist.', 'danger');
                                    reloadDatatable('#parts-subclass-table');
                                }
                                else {
                                    showNotification('Delete Part Subclass Error', response.message, 'danger');
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

        $(document).on('click','#delete-parts-subclass',function() {
            let parts_subclass_id = [];
            const transaction = 'delete multiple parts subclass';

            $('.datatable-checkbox-children').each((index, element) => {
                if ($(element).is(':checked')) {
                    parts_subclass_id.push(element.value);
                }
            });
    
            if(parts_subclass_id.length > 0){
                Swal.fire({
                    title: 'Confirm Multiple Product Subcategories Deletion',
                    text: 'Are you sure you want to delete these product subcategories?',
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
                            url: 'controller/parts-subclass-controller.php',
                            dataType: 'json',
                            data: {
                                parts_subclass_id: parts_subclass_id,
                                transaction : transaction
                            },
                            success: function (response) {
                                if (response.success) {
                                    showNotification('Delete Part Subclass Success', 'The selected product subcategories have been deleted successfully.', 'success');
                                    reloadDatatable('#parts-subclass-table');
                                }
                                else {
                                    if (response.isInactive) {
                                        setNotification('User Inactive', response.message, 'danger');
                                        window.location = 'logout.php?logout';
                                    }
                                    else {
                                        showNotification('Delete Part Subclass Error', response.message, 'danger');
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
                showNotification('Deletion Multiple Product Subcategories Error', 'Please select the product subcategories you wish to delete.', 'danger');
            }
        });

        $(document).on('click','#delete-parts-subclass-details',function() {
            const parts_subclass_id = $('#parts-subclass-id').text();
            const transaction = 'delete parts subclass';
    
            Swal.fire({
                title: 'Confirm Part Subclass Deletion',
                text: 'Are you sure you want to delete this parts subclass?',
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
                        url: 'controller/parts-subclass-controller.php',
                        dataType: 'json',
                        data: {
                            parts_subclass_id : parts_subclass_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                setNotification('Deleted Part Subclass Success', 'The parts subclass has been deleted successfully.', 'success');
                                window.location = 'parts-subclass.php';
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
                                    showNotification('Delete Part Subclass Error', response.message, 'danger');
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
            discardCreate('parts-subclass.php');
        });

        $(document).on('click','#edit-form',function() {
            displayDetails('get parts subclass details');

            enableForm();
        });

        $(document).on('click','#duplicate-parts-subclass',function() {
            const parts_subclass_id = $('#parts-subclass-id').text();
            const transaction = 'duplicate parts subclass';
    
            Swal.fire({
                title: 'Confirm Part Subclass Duplication',
                text: 'Are you sure you want to duplicate this parts subclass?',
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
                        url: 'controller/parts-subclass-controller.php',
                        dataType: 'json',
                        data: {
                            parts_subclass_id : parts_subclass_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                setNotification('Duplicate Part Subclass Success', 'The parts subclass has been duplicated successfully.', 'success');
                                window.location = 'parts-subclass.php?id=' + response.partsSubclassID;
                            }
                            else {
                                if (response.isInactive) {
                                    setNotification('User Inactive', response.message, 'danger');
                                    window.location = 'logout.php?logout';
                                }
                                else if (response.notExist) {
                                    showNotification('Duplicate Part Subclass Error', 'The parts subclass does not exist.', 'danger');
                                    reloadDatatable('#parts-subclass-table');
                                }
                                else {
                                    showNotification('Duplicate Part Subclass Error', response.message, 'danger');
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

function partsSubclassTable(datatable_name, buttons = false, show_all = false){
    const type = 'parts subclass table';
    var settings;

    const column = [ 
        { 'data' : 'CHECK_BOX' },
        { 'data' : 'PARTS_SUBCLASS' },
        { 'data' : 'PARTS_CLASS' },
        { 'data' : 'ACTION' }
    ];

    const column_definition = [
        { 'width': '1%','bSortable': false, 'aTargets': 0 },
        { 'width': '44%', 'aTargets': 1 },
        { 'width': '20%', 'aTargets': 2 },
        { 'width': '15%','bSortable': false, 'aTargets': 3 }
    ];

    const length_menu = show_all ? [[-1], ['All']] : [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']];

    settings = {
        'ajax': { 
            'url' : 'view/_parts_subclass_generation.php',
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

function partsSubclassForm(){
    $('#parts-subclass-form').validate({
        rules: {
            parts_subclass_name: {
                required: true
            },
            parts_class_id: {
                required: true
            },
        },
        messages: {
            parts_subclass_name: {
                required: 'Please enter the part subclass name'
            },
            parts_class_id: {
                required: 'Please choose the part class'
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
            const parts_subclass_id = $('#parts-subclass-id').text();
            const transaction = 'save parts subclass';
        
            $.ajax({
                type: 'POST',
                url: 'controller/parts-subclass-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction + '&parts_subclass_id=' + parts_subclass_id,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-data');
                },
                success: function (response) {
                    if (response.success) {
                        const notificationMessage = response.insertRecord ? 'Insert Part Subclass Success' : 'Update Part Subclass Success';
                        const notificationDescription = response.insertRecord ? 'The parts subclass has been inserted successfully.' : 'The parts subclass has been updated successfully.';
                        
                        setNotification(notificationMessage, notificationDescription, 'success');
                        window.location = 'parts-subclass.php?id=' + response.partsSubclassID;
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
        case 'get parts subclass details':
            const parts_subclass_id = $('#parts-subclass-id').text();
            
            $.ajax({
                url: 'controller/parts-subclass-controller.php',
                method: 'POST',
                dataType: 'json',
                data: {
                    parts_subclass_id : parts_subclass_id, 
                    transaction : transaction
                },
                beforeSend: function() {
                    resetForm('parts-subclass-form');
                },
                success: function(response) {
                    if (response.success) {
                        $('#parts_subclass_id').val(parts_subclass_id);
                        $('#parts_subclass_name').val(response.partsSubclassName);

                        checkOptionExist('#parts_class_id', response.partsClassID, '');

                        $('#parts_subclass_name_label').text(response.partsSubclassName);
                        $('#parts_class_id_label').text(response.partsClassName);
                    } 
                    else {
                        if(response.isInactive){
                            window.location = 'logout.php?logout';
                        }
                        else{
                            showNotification('Get Part Subclass Details Error', response.message, 'danger');
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