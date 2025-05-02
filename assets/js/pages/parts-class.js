(function($) {
    'use strict';

    $(function() {
        if($('#parts-class-table').length){
            partsClassTable('#parts-class-table');
        }

        if($('#parts-class-form').length){
            partsClassForm();
        }

        if($('#parts-class-id').length){
            displayDetails('get parts class details');
        }

        $(document).on('click','.delete-parts-class',function() {
            const parts_class_id = $(this).data('parts-class-id');
            const transaction = 'delete parts class';
    
            Swal.fire({
                title: 'Confirm Parts Class Deletion',
                text: 'Are you sure you want to delete this parts class?',
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
                        url: 'controller/parts-class-controller.php',
                        dataType: 'json',
                        data: {
                            parts_class_id : parts_class_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                showNotification('Delete Parts Class Success', 'The parts class has been deleted successfully.', 'success');
                                reloadDatatable('#parts-class-table');
                            }
                            else {
                                if (response.isInactive) {
                                    setNotification('User Inactive', response.message, 'danger');
                                    window.location = 'logout.php?logout';
                                }
                                else if (response.notExist) {
                                    showNotification('Delete Parts Class Error', 'The parts class does not exist.', 'danger');
                                    reloadDatatable('#parts-class-table');
                                }
                                else {
                                    showNotification('Delete Parts Class Error', response.message, 'danger');
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

        $(document).on('click','#delete-parts-class',function() {
            let parts_class_id = [];
            const transaction = 'delete multiple parts class';

            $('.datatable-checkbox-children').each((index, element) => {
                if ($(element).is(':checked')) {
                    parts_class_id.push(element.value);
                }
            });
    
            if(parts_class_id.length > 0){
                Swal.fire({
                    title: 'Confirm Multiple Part Classes Deletion',
                    text: 'Are you sure you want to delete these parts classes?',
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
                            url: 'controller/parts-class-controller.php',
                            dataType: 'json',
                            data: {
                                parts_class_id: parts_class_id,
                                transaction : transaction
                            },
                            success: function (response) {
                                if (response.success) {
                                    showNotification('Delete Parts Class Success', 'The selected parts classes have been deleted successfully.', 'success');
                                    reloadDatatable('#parts-class-table');
                                }
                                else {
                                    if (response.isInactive) {
                                        setNotification('User Inactive', response.message, 'danger');
                                        window.location = 'logout.php?logout';
                                    }
                                    else {
                                        showNotification('Delete Parts Class Error', response.message, 'danger');
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
                showNotification('Deletion Multiple Parts Class Error', 'Please select the parts classes you wish to delete.', 'danger');
            }
        });

        $(document).on('click','#delete-parts-class-details',function() {
            const parts_class_id = $('#parts-class-id').text();
            const transaction = 'delete parts class';
    
            Swal.fire({
                title: 'Confirm Parts Class Deletion',
                text: 'Are you sure you want to delete this parts class?',
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
                        url: 'controller/parts-class-controller.php',
                        dataType: 'json',
                        data: {
                            parts_class_id : parts_class_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                setNotification('Deleted Parts Class Success', 'The parts class has been deleted successfully.', 'success');
                                window.location = 'parts-class.php';
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
                                    showNotification('Delete Parts Class Error', response.message, 'danger');
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
            discardCreate('parts-class.php');
        });

        $(document).on('click','#edit-form',function() {
            displayDetails('get parts class details');

            enableForm();
        });

        $(document).on('click','#duplicate-parts-class',function() {
            const parts_class_id = $('#parts-class-id').text();
            const transaction = 'duplicate parts class';
    
            Swal.fire({
                title: 'Confirm Parts Class Duplication',
                text: 'Are you sure you want to duplicate this parts class?',
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
                        url: 'controller/parts-class-controller.php',
                        dataType: 'json',
                        data: {
                            parts_class_id : parts_class_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                setNotification('Duplicate Parts Class Success', 'The parts class has been duplicated successfully.', 'success');
                                window.location = 'parts-class.php?id=' + response.partsClassID;
                            }
                            else {
                                if (response.isInactive) {
                                    setNotification('User Inactive', response.message, 'danger');
                                    window.location = 'logout.php?logout';
                                }
                                else if (response.notExist) {
                                    showNotification('Duplicate Parts Class Error', 'The parts class does not exist.', 'danger');
                                    reloadDatatable('#parts-class-table');
                                }
                                else {
                                    showNotification('Duplicate Parts Class Error', response.message, 'danger');
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

function partsClassTable(datatable_name, buttons = false, show_all = false){
    const type = 'parts class table';
    var settings;

    const column = [ 
        { 'data' : 'CHECK_BOX' },
        { 'data' : 'PARTS_CLASS' },
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
            'url' : 'view/_parts_class_generation.php',
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

function partsClassForm(){
    $('#parts-class-form').validate({
        rules: {
            parts_class_name: {
                required: true
            },
        },
        messages: {
            parts_class_name: {
                required: 'Please enter the parts class name'
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
            const parts_class_id = $('#parts-class-id').text();
            const transaction = 'save parts class';
        
            $.ajax({
                type: 'POST',
                url: 'controller/parts-class-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction + '&parts_class_id=' + parts_class_id,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-data');
                },
                success: function (response) {
                    if (response.success) {
                        const notificationMessage = response.insertRecord ? 'Insert Parts Class Success' : 'Update Parts Class Success';
                        const notificationDescription = response.insertRecord ? 'The parts class has been inserted successfully.' : 'The parts class has been updated successfully.';
                        
                        setNotification(notificationMessage, notificationDescription, 'success');
                        window.location = 'parts-class.php?id=' + response.partsClassID;
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
        case 'get parts class details':
            const parts_class_id = $('#parts-class-id').text();
            
            $.ajax({
                url: 'controller/parts-class-controller.php',
                method: 'POST',
                dataType: 'json',
                data: {
                    parts_class_id : parts_class_id, 
                    transaction : transaction
                },
                beforeSend: function() {
                    resetForm('parts-class-form');
                },
                success: function(response) {
                    if (response.success) {
                        $('#parts_class_id').val(parts_class_id);
                        $('#parts_class_name').val(response.partsClassName);

                        $('#parts_class_name_label').text(response.partsClassName);
                    } 
                    else {
                        if(response.isInactive){
                            window.location = 'logout.php?logout';
                        }
                        else{
                            showNotification('Get Parts Class Details Error', response.message, 'danger');
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