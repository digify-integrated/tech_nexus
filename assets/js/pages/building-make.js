(function($) {
    'use strict';

    $(function() {
        if($('#building-make-table').length){
            buildingMakeTable('#building-make-table');
        }

        if($('#building-make-form').length){
            buildingMakeForm();
        }

        if($('#building-make-id').length){
            displayDetails('get building make details');
        }

        $(document).on('click','.delete-building-make',function() {
            const building_make_id = $(this).data('building-make-id');
            const transaction = 'delete building make';
    
            Swal.fire({
                title: 'Confirm Building Make Deletion',
                text: 'Are you sure you want to delete this building make?',
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
                        url: 'controller/building-make-controller.php',
                        dataType: 'json',
                        data: {
                            building_make_id : building_make_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                showNotification('Delete Building Make Success', 'The building make has been deleted successfully.', 'success');
                                reloadDatatable('#building-make-table');
                            }
                            else {
                                if (response.isInactive) {
                                    setNotification('User Inactive', response.message, 'danger');
                                    window.location = 'logout.php?logout';
                                }
                                else if (response.notExist) {
                                    showNotification('Delete Building Make Error', 'The building make does not exist.', 'danger');
                                    reloadDatatable('#building-make-table');
                                }
                                else {
                                    showNotification('Delete Building Make Error', response.message, 'danger');
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

        $(document).on('click','#delete-building-make',function() {
            let building_make_id = [];
            const transaction = 'delete multiple building make';

            $('.datatable-checkbox-children').each((index, element) => {
                if ($(element).is(':checked')) {
                    building_make_id.push(element.value);
                }
            });
    
            if(building_make_id.length > 0){
                Swal.fire({
                    title: 'Confirm Multiple Building Makes Deletion',
                    text: 'Are you sure you want to delete these building makes?',
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
                            url: 'controller/building-make-controller.php',
                            dataType: 'json',
                            data: {
                                building_make_id: building_make_id,
                                transaction : transaction
                            },
                            success: function (response) {
                                if (response.success) {
                                    showNotification('Delete Building Make Success', 'The selected building makes have been deleted successfully.', 'success');
                                    reloadDatatable('#building-make-table');
                                }
                                else {
                                    if (response.isInactive) {
                                        setNotification('User Inactive', response.message, 'danger');
                                        window.location = 'logout.php?logout';
                                    }
                                    else {
                                        showNotification('Delete Building Make Error', response.message, 'danger');
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
                showNotification('Deletion Multiple Building Make Error', 'Please select the building makes you wish to delete.', 'danger');
            }
        });

        $(document).on('click','#delete-building-make-details',function() {
            const building_make_id = $('#building-make-id').text();
            const transaction = 'delete building make';
    
            Swal.fire({
                title: 'Confirm Building Make Deletion',
                text: 'Are you sure you want to delete this building make?',
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
                        url: 'controller/building-make-controller.php',
                        dataType: 'json',
                        data: {
                            building_make_id : building_make_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                setNotification('Deleted Building Make Success', 'The building make has been deleted successfully.', 'success');
                                window.location = 'building-make.php';
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
                                    showNotification('Delete Building Make Error', response.message, 'danger');
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
            discardCreate('building-make.php');
        });

        $(document).on('click','#edit-form',function() {
            displayDetails('get building make details');

            enableForm();
        });

        $(document).on('click','#duplicate-building-make',function() {
            const building_make_id = $('#building-make-id').text();
            const transaction = 'duplicate building make';
    
            Swal.fire({
                title: 'Confirm Building Make Duplication',
                text: 'Are you sure you want to duplicate this building make?',
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
                        url: 'controller/building-make-controller.php',
                        dataType: 'json',
                        data: {
                            building_make_id : building_make_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                setNotification('Duplicate Building Make Success', 'The building make has been duplicated successfully.', 'success');
                                window.location = 'building-make.php?id=' + response.buildingMakeID;
                            }
                            else {
                                if (response.isInactive) {
                                    setNotification('User Inactive', response.message, 'danger');
                                    window.location = 'logout.php?logout';
                                }
                                else if (response.notExist) {
                                    showNotification('Duplicate Building Make Error', 'The building make does not exist.', 'danger');
                                    reloadDatatable('#building-make-table');
                                }
                                else {
                                    showNotification('Duplicate Building Make Error', response.message, 'danger');
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

function buildingMakeTable(datatable_name, buttons = false, show_all = false){
    const type = 'building make table';
    var settings;

    const column = [ 
        { 'data' : 'CHECK_BOX' },
        { 'data' : 'BUILDING_MAKE_NAME' },
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
            'url' : 'view/_building_make_generation.php',
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

function buildingMakeForm(){
    $('#building-make-form').validate({
        rules: {
            building_make_name: {
                required: true
            },
        },
        messages: {
            building_make_name: {
                required: 'Please enter the building make name'
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
            const building_make_id = $('#building-make-id').text();
            const transaction = 'save building make';
        
            $.ajax({
                type: 'POST',
                url: 'controller/building-make-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction + '&building_make_id=' + building_make_id,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-data');
                },
                success: function (response) {
                    if (response.success) {
                        const notificationMessage = response.insertRecord ? 'Insert Building Make Success' : 'Update Building Make Success';
                        const notificationDescription = response.insertRecord ? 'The building make has been inserted successfully.' : 'The building make has been updated successfully.';
                        
                        setNotification(notificationMessage, notificationDescription, 'success');
                        window.location = 'building-make.php?id=' + response.buildingMakeID;
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
        case 'get building make details':
            const building_make_id = $('#building-make-id').text();
            
            $.ajax({
                url: 'controller/building-make-controller.php',
                method: 'POST',
                dataType: 'json',
                data: {
                    building_make_id : building_make_id, 
                    transaction : transaction
                },
                beforeSend: function() {
                    resetForm('building-make-form');
                },
                success: function(response) {
                    if (response.success) {
                        $('#building_make_id').val(building_make_id);
                        $('#building_make_name').val(response.buildingMakeName);

                        $('#building_make_name_label').text(response.buildingMakeName);
                    } 
                    else {
                        if(response.isInactive){
                            window.location = 'logout.php?logout';
                        }
                        else{
                            showNotification('Get Building Make Details Error', response.message, 'danger');
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