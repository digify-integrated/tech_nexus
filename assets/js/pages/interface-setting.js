(function($) {
    'use strict';

    $(function() {
        if($('#interface-setting-table').length){
            interfaceSettingTable('#interface-setting-table');
        }

        if($('#interface-setting-form').length){
            interfaceSettingForm();
        }

        if($('#interface-setting-id').length){
            displayDetails('get interface setting details');

            $('#interface_setting_value').change(function() {
                var selectedFile = $(this)[0].files[0];

                if (selectedFile) {
                    const transaction = 'change interface setting value';
                    const interface_setting_id = $('#interface-setting-id').text();

                    var formData = new FormData();
                    formData.append('interface_setting_id', interface_setting_id);
                    formData.append('transaction', transaction);
                    formData.append('interface_setting_value', selectedFile);
            
                    $.ajax({
                        type: 'POST',
                        url: 'controller/interface-setting-controller.php',
                        data: formData,
                        processData: false,
                        contentType: false,
                        dataType: 'json',
                        success: function(response) {
                            if (response.success) {
                                showNotification('Interface Setting Image Change Success', 'The interface seting image has been successfully updated.', 'success');
                                location.reload();
                            }
                            else{
                                if(response.isInactive){
                                    window.location = 'logout.php?logout';
                                }
                                else{
                                    showNotification('Interface Setting Image Change Error', response.message, 'danger');
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
                }
            });
        }

        $(document).on('click','.delete-interface-setting',function() {
            const interface_setting_id = $(this).data('interface-setting-id');
            const transaction = 'delete interface setting';
    
            Swal.fire({
                title: 'Confirm Interface Setting Deletion',
                text: 'Are you sure you want to delete this interface setting?',
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
                        url: 'controller/interface-setting-controller.php',
                        dataType: 'json',
                        data: {
                            interface_setting_id : interface_setting_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                showNotification('Delete Interface Setting Success', 'The interface setting has been deleted successfully.', 'success');
                                reloadDatatable('#interface-setting-table');
                            }
                            else {
                                if (response.isInactive) {
                                    setNotification('User Inactive', response.message, 'danger');
                                    window.location = 'logout.php?logout';
                                }
                                else if (response.notExist) {
                                    showNotification('Delete Interface Setting Error', 'The interface setting does not exist.', 'danger');
                                    reloadDatatable('#interface-setting-table');
                                }
                                else {
                                    showNotification('Delete Interface Setting Error', response.message, 'danger');
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

        $(document).on('click','#delete-interface-setting',function() {
            let interface_setting_id = [];
            const transaction = 'delete multiple interface setting';

            $('.datatable-checkbox-children').each((index, element) => {
                if ($(element).is(':checked')) {
                    interface_setting_id.push(element.value);
                }
            });
    
            if(interface_setting_id.length > 0){
                Swal.fire({
                    title: 'Confirm Multiple Interface Settings Deletion',
                    text: 'Are you sure you want to delete these interface settings?',
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
                            url: 'controller/interface-setting-controller.php',
                            dataType: 'json',
                            data: {
                                interface_setting_id: interface_setting_id,
                                transaction : transaction
                            },
                            success: function (response) {
                                if (response.success) {
                                    showNotification('Delete Interface Setting Success', 'The selected interface settings have been deleted successfully.', 'success');
                                        reloadDatatable('#interface-setting-table');
                                }
                                else {
                                    if (response.isInactive) {
                                        setNotification('User Inactive', response.message, 'danger');
                                        window.location = 'logout.php?logout';
                                    }
                                    else {
                                        showNotification('Delete Interface Setting Error', response.message, 'danger');
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
                showNotification('Deletion Multiple Interface Setting Error', 'Please select the interface settings you wish to delete.', 'danger');
            }
        });

        $(document).on('click','#delete-interface-setting-details',function() {
            const interface_setting_id = $('#interface-setting-id').text();
            const transaction = 'delete interface setting';
    
            Swal.fire({
                title: 'Confirm Interface Setting Deletion',
                text: 'Are you sure you want to delete this interface setting?',
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
                        url: 'controller/interface-setting-controller.php',
                        dataType: 'json',
                        data: {
                            interface_setting_id : interface_setting_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                setNotification('Deleted Interface Setting Success', 'The interface setting has been deleted successfully.', 'success');
                                window.location = 'interface-setting.php';
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
                                    showNotification('Delete Interface Setting Error', response.message, 'danger');
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
            discardCreate('interface-setting.php');
        });

        $(document).on('click','#edit-form',function() {
            displayDetails('get interface setting details');

            enableForm();
        });

        $(document).on('click','#duplicate-interface-setting',function() {
            const interface_setting_id = $('#interface-setting-id').text();
            const transaction = 'duplicate interface setting';
    
            Swal.fire({
                title: 'Confirm Interface Setting Duplication',
                text: 'Are you sure you want to duplicate this interface setting?',
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
                        url: 'controller/interface-setting-controller.php',
                        dataType: 'json',
                        data: {
                            interface_setting_id : interface_setting_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                setNotification('Duplicate Interface Setting Success', 'The interface setting has been duplicated successfully.', 'success');
                                window.location = 'interface-setting.php?id=' + response.interfaceSettingID;
                            }
                            else {
                                if (response.isInactive) {
                                    setNotification('User Inactive', response.message, 'danger');
                                    window.location = 'logout.php?logout';
                                }
                                else if (response.notExist) {
                                    showNotification('Duplicate Interface Setting Error', 'The interface setting does not exist.', 'danger');
                                    reloadDatatable('#interface-setting-table');
                                }
                                else {
                                    showNotification('Duplicate Interface Setting Error', response.message, 'danger');
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

function interfaceSettingTable(datatable_name, buttons = false, show_all = false){
    const type = 'interface setting table';
    var settings;

    const column = [ 
        { 'data' : 'CHECK_BOX' },
        { 'data' : 'INTERFACE_SETTING_NAME' },
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
            'url' : 'view/_interface_setting_generation.php',
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

function interfaceSettingForm(){
    $('#interface-setting-form').validate({
        rules: {
            interface_setting_name: {
                required: true
            },
            interface_setting_description: {
                required: true
            },
            value: {
                required: true
            }
        },
        messages: {
            interface_setting_name: {
                required: 'Please enter the interface setting name'
            },
            interface_setting_description: {
                required: 'Please enter the interface setting description'
            },
            value: {
                required: 'Please enter the value'
            }
        },
        errorPlacement: function (error, element) {
            if (element.hasClass('select2')) {
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
            const interface_setting_id = $('#interface-setting-id').text();
            const transaction = 'save interface setting';
        
            $.ajax({
                type: 'POST',
                url: 'controller/interface-setting-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction + '&interface_setting_id=' + interface_setting_id,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-data');
                },
                success: function (response) {
                    if (response.success) {
                        const notificationMessage = response.insertRecord ? 'Insert Interface Setting Success' : 'Update Interface Setting Success';
                        const notificationDescription = response.insertRecord ? 'The interface setting has been inserted successfully.' : 'The interface setting has been updated successfully.';
                        
                        setNotification(notificationMessage, notificationDescription, 'success');
                        window.location = 'interface-setting.php?id=' + response.interfaceSettingID;
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
        case 'get interface setting details':
            const interface_setting_id = $('#interface-setting-id').text();
            
            $.ajax({
                url: 'controller/interface-setting-controller.php',
                method: 'POST',
                dataType: 'json',
                data: {
                    interface_setting_id : interface_setting_id, 
                    transaction : transaction
                },
                beforeSend: function() {
                    resetModalForm('interface-setting-form');
                },
                success: function(response) {
                    if (response.success) {
                        $('#interface_setting_id').val(interface_setting_id);
                        $('#interface_setting_name').val(response.interfaceSettingName);
                        $('#interface_setting_description').val(response.interfaceSettingDescription);
                        document.getElementById('interface_setting_image').src = response.value;

                        $('#interface_setting_name_label').text(response.interfaceSettingName);
                        $('#interface_setting_description_label').text(response.interfaceSettingDescription);
                    } 
                    else {
                        if(response.isInactive){
                            window.location = 'logout.php?logout';
                        }
                        else{
                            showNotification('Get Interface Setting Details Error', response.message, 'danger');
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