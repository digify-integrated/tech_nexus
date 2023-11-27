(function($) {
    'use strict';

    $(function() {
        if($('#attendance-setting-table').length){
            attendanceSettingTable('#attendance-setting-table');
        }

        if($('#attendance-setting-form').length){
            attendanceSettingForm();
        }

        if($('#attendance-setting-id').length){
            displayDetails('get attendance setting details');
        }

        $(document).on('click','.delete-attendance-setting',function() {
            const attendance_setting_id = $(this).data('attendance-setting-id');
            const transaction = 'delete attendance setting';
    
            Swal.fire({
                title: 'Confirm Attendance Setting Deletion',
                text: 'Are you sure you want to delete this attendance setting?',
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
                        url: 'controller/attendance-setting-controller.php',
                        dataType: 'json',
                        data: {
                            attendance_setting_id : attendance_setting_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                showNotification('Delete Attendance Setting Success', 'The attendance setting has been deleted successfully.', 'success');
                                reloadDatatable('#attendance-setting-table');
                            }
                            else {
                                if (response.isInactive) {
                                    setNotification('User Inactive', response.message, 'danger');
                                    window.location = 'logout.php?logout';
                                }
                                else if (response.notExist) {
                                    showNotification('Delete Attendance Setting Error', 'The attendance setting does not exist.', 'danger');
                                    reloadDatatable('#attendance-setting-table');
                                }
                                else {
                                    showNotification('Delete Attendance Setting Error', response.message, 'danger');
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

        $(document).on('click','#delete-attendance-setting',function() {
            let attendance_setting_id = [];
            const transaction = 'delete multiple attendance setting';

            $('.datatable-checkbox-children').each((index, element) => {
                if ($(element).is(':checked')) {
                    attendance_setting_id.push(element.value);
                }
            });
    
            if(attendance_setting_id.length > 0){
                Swal.fire({
                    title: 'Confirm Multiple Attendance Settings Deletion',
                    text: 'Are you sure you want to delete these attendance settings?',
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
                            url: 'controller/attendance-setting-controller.php',
                            dataType: 'json',
                            data: {
                                attendance_setting_id: attendance_setting_id,
                                transaction : transaction
                            },
                            success: function (response) {
                                if (response.success) {
                                    showNotification('Delete Attendance Setting Success', 'The selected attendance settings have been deleted successfully.', 'success');
                                        reloadDatatable('#attendance-setting-table');
                                }
                                else {
                                    if (response.isInactive) {
                                        setNotification('User Inactive', response.message, 'danger');
                                        window.location = 'logout.php?logout';
                                    }
                                    else {
                                        showNotification('Delete Attendance Setting Error', response.message, 'danger');
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
                showNotification('Deletion Multiple Attendance Setting Error', 'Please select the attendance settings you wish to delete.', 'danger');
            }
        });

        $(document).on('click','#delete-attendance-setting-details',function() {
            const attendance_setting_id = $('#attendance-setting-id').text();
            const transaction = 'delete attendance setting';
    
            Swal.fire({
                title: 'Confirm Attendance Setting Deletion',
                text: 'Are you sure you want to delete this attendance setting?',
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
                        url: 'controller/attendance-setting-controller.php',
                        dataType: 'json',
                        data: {
                            attendance_setting_id : attendance_setting_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                setNotification('Deleted Attendance Setting Success', 'The attendance setting has been deleted successfully.', 'success');
                                window.location = 'attendance-setting.php';
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
                                    showNotification('Delete Attendance Setting Error', response.message, 'danger');
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
            discardCreate('attendance-setting.php');
        });

        $(document).on('click','#edit-form',function() {
            displayDetails('get attendance setting details');

            enableForm();
        });

        $(document).on('click','#duplicate-attendance-setting',function() {
            const attendance_setting_id = $('#attendance-setting-id').text();
            const transaction = 'duplicate attendance setting';
    
            Swal.fire({
                title: 'Confirm Attendance Setting Duplication',
                text: 'Are you sure you want to duplicate this attendance setting?',
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
                        url: 'controller/attendance-setting-controller.php',
                        dataType: 'json',
                        data: {
                            attendance_setting_id : attendance_setting_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                setNotification('Duplicate Attendance Setting Success', 'The attendance setting has been duplicated successfully.', 'success');
                                window.location = 'attendance-setting.php?id=' + response.attendanceSettingID;
                            }
                            else {
                                if (response.isInactive) {
                                    setNotification('User Inactive', response.message, 'danger');
                                    window.location = 'logout.php?logout';
                                }
                                else if (response.notExist) {
                                    showNotification('Duplicate Attendance Setting Error', 'The attendance setting does not exist.', 'danger');
                                    reloadDatatable('#attendance-setting-table');
                                }
                                else {
                                    showNotification('Duplicate Attendance Setting Error', response.message, 'danger');
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

function attendanceSettingTable(datatable_name, buttons = false, show_all = false){
    const type = 'attendance setting table';
    var settings;

    const column = [ 
        { 'data' : 'CHECK_BOX' },
        { 'data' : 'ATTENDANCE_SETTING_NAME' },
        { 'data' : 'VALUE' },
        { 'data' : 'ACTION' }
    ];

    const column_definition = [
        { 'width': '1%','bSortable': false, 'aTargets': 0 },
        { 'width': '64%', 'aTargets': 1 },
        { 'width': '20%', 'aTargets': 2 },
        { 'width': '15%','bSortable': false, 'aTargets': 3 }
    ];

    const length_menu = show_all ? [[-1], ['All']] : [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']];

    settings = {
        'ajax': { 
            'url' : 'view/_attendance_setting_generation.php',
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

function attendanceSettingForm(){
    $('#attendance-setting-form').validate({
        rules: {
            attendance_setting_name: {
                required: true
            },
            attendance_setting_description: {
                required: true
            },
            value: {
                required: true
            }
        },
        messages: {
            attendance_setting_name: {
                required: 'Please enter the attendance setting name'
            },
            attendance_setting_description: {
                required: 'Please enter the attendance setting description'
            },
            value: {
                required: 'Please enter the value'
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
            const attendance_setting_id = $('#attendance-setting-id').text();
            const transaction = 'save attendance setting';
        
            $.ajax({
                type: 'POST',
                url: 'controller/attendance-setting-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction + '&attendance_setting_id=' + attendance_setting_id,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-data');
                },
                success: function (response) {
                    if (response.success) {
                        const notificationMessage = response.insertRecord ? 'Insert Attendance Setting Success' : 'Update Attendance Setting Success';
                        const notificationDescription = response.insertRecord ? 'The attendance setting has been inserted successfully.' : 'The attendance setting has been updated successfully.';
                        
                        setNotification(notificationMessage, notificationDescription, 'success');
                        window.location = 'attendance-setting.php?id=' + response.attendanceSettingID;
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
        case 'get attendance setting details':
            const attendance_setting_id = $('#attendance-setting-id').text();
            
            $.ajax({
                url: 'controller/attendance-setting-controller.php',
                method: 'POST',
                dataType: 'json',
                data: {
                    attendance_setting_id : attendance_setting_id, 
                    transaction : transaction
                },
                beforeSend: function() {
                    resetForm('attendance-setting-form');
                },
                success: function(response) {
                    if (response.success) {
                        $('#attendance_setting_id').val(attendance_setting_id);
                        $('#attendance_setting_name').val(response.attendanceSettingName);
                        $('#attendance_setting_description').val(response.attendanceSettingDescription);
                        $('#value').val(response.value);

                        $('#attendance_setting_name_label').text(response.attendanceSettingName);
                        $('#attendance_setting_description_label').text(response.attendanceSettingDescription);
                        $('#value_label').text(response.value);
                    } 
                    else {
                        if(response.isInactive){
                            window.location = 'logout.php?logout';
                        }
                        else{
                            showNotification('Get Attendance Setting Details Error', response.message, 'danger');
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