(function($) {
    'use strict';

    $(function() {
        if($('#bank-adb-table').length){
            bankADBTable('#bank-adb-table');
        }

        if($('#bank-adb-form').length){
            bankADBForm();
        }

        if($('#bank-adb-id').length){
            displayDetails('get bank adb details');
        }

        $(document).on('click','.delete-bank-adb',function() {
            const bank_adb_id = $(this).data('bank-adb-id');
            const transaction = 'delete bank adb';
    
            Swal.fire({
                title: 'Confirm Bank ADB Deletion',
                text: 'Are you sure you want to delete this bank adb?',
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
                        url: 'controller/bank-adb-controller.php',
                        dataType: 'json',
                        data: {
                            bank_adb_id : bank_adb_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                showNotification('Delete Bank ADB Success', 'The bank adb has been deleted successfully.', 'success');
                                reloadDatatable('#bank-adb-table');
                            }
                            else {
                                if (response.isInactive) {
                                    setNotification('User Inactive', response.message, 'danger');
                                    window.location = 'logout.php?logout';
                                }
                                else if (response.notExist) {
                                    showNotification('Delete Bank ADB Error', 'The bank adb does not exist.', 'danger');
                                    reloadDatatable('#bank-adb-table');
                                }
                                else {
                                    showNotification('Delete Bank ADB Error', response.message, 'danger');
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

        $(document).on('click','#delete-bank-adb',function() {
            let bank_adb_id = [];
            const transaction = 'delete multiple bank adb';

            $('.datatable-checkbox-children').each((index, element) => {
                if ($(element).is(':checked')) {
                    bank_adb_id.push(element.value);
                }
            });
    
            if(bank_adb_id.length > 0){
                Swal.fire({
                    title: 'Confirm Multiple Bank ADBs Deletion',
                    text: 'Are you sure you want to delete these bank adbs?',
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
                            url: 'controller/bank-adb-controller.php',
                            dataType: 'json',
                            data: {
                                bank_adb_id: bank_adb_id,
                                transaction : transaction
                            },
                            success: function (response) {
                                if (response.success) {
                                    showNotification('Delete Bank ADB Success', 'The selected bank adbs have been deleted successfully.', 'success');
                                    reloadDatatable('#bank-adb-table');
                                }
                                else {
                                    if (response.isInactive) {
                                        setNotification('User Inactive', response.message, 'danger');
                                        window.location = 'logout.php?logout';
                                    }
                                    else {
                                        showNotification('Delete Bank ADB Error', response.message, 'danger');
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
                showNotification('Deletion Multiple Bank ADB Error', 'Please select the bank adbs you wish to delete.', 'danger');
            }
        });

        $(document).on('click','#delete-bank-adb-details',function() {
            const bank_adb_id = $('#bank-adb-id').text();
            const transaction = 'delete bank adb';
    
            Swal.fire({
                title: 'Confirm Bank ADB Deletion',
                text: 'Are you sure you want to delete this bank adb?',
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
                        url: 'controller/bank-adb-controller.php',
                        dataType: 'json',
                        data: {
                            bank_adb_id : bank_adb_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                setNotification('Deleted Bank ADB Success', 'The bank adb has been deleted successfully.', 'success');
                                window.location = 'bank-adb.php';
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
                                    showNotification('Delete Bank ADB Error', response.message, 'danger');
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
            discardCreate('bank-adb.php');
        });

        $(document).on('click','#edit-form',function() {
            displayDetails('get bank adb details');

            enableForm();
        });

        $(document).on('click','#duplicate-bank-adb',function() {
            const bank_adb_id = $('#bank-adb-id').text();
            const transaction = 'duplicate bank adb';
    
            Swal.fire({
                title: 'Confirm Bank ADB Duplication',
                text: 'Are you sure you want to duplicate this bank adb?',
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
                        url: 'controller/bank-adb-controller.php',
                        dataType: 'json',
                        data: {
                            bank_adb_id : bank_adb_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                setNotification('Duplicate Bank ADB Success', 'The bank adb has been duplicated successfully.', 'success');
                                window.location = 'bank-adb.php?id=' + response.bankADBID;
                            }
                            else {
                                if (response.isInactive) {
                                    setNotification('User Inactive', response.message, 'danger');
                                    window.location = 'logout.php?logout';
                                }
                                else if (response.notExist) {
                                    showNotification('Duplicate Bank ADB Error', 'The bank adb does not exist.', 'danger');
                                    reloadDatatable('#bank-adb-table');
                                }
                                else {
                                    showNotification('Duplicate Bank ADB Error', response.message, 'danger');
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

function bankADBTable(datatable_name, buttons = false, show_all = false){
    const type = 'bank adb table';
    var settings;

    const column = [ 
        { 'data' : 'CHECK_BOX' },
        { 'data' : 'BLOOD_TYPE_NAME' },
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
            'url' : 'view/_bank_adb_generation.php',
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

function bankADBForm(){
    $('#bank-adb-form').validate({
        rules: {
            bank_adb_name: {
                required: true
            },
        },
        messages: {
            bank_adb_name: {
                required: 'Please enter the bank adb name'
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
            const bank_adb_id = $('#bank-adb-id').text();
            const transaction = 'save bank adb';
        
            $.ajax({
                type: 'POST',
                url: 'controller/bank-adb-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction + '&bank_adb_id=' + bank_adb_id,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-data');
                },
                success: function (response) {
                    if (response.success) {
                        const notificationMessage = response.insertRecord ? 'Insert Bank ADB Success' : 'Update Bank ADB Success';
                        const notificationDescription = response.insertRecord ? 'The bank adb has been inserted successfully.' : 'The bank adb has been updated successfully.';
                        
                        setNotification(notificationMessage, notificationDescription, 'success');
                        window.location = 'bank-adb.php?id=' + response.bankADBID;
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
        case 'get bank adb details':
            const bank_adb_id = $('#bank-adb-id').text();
            
            $.ajax({
                url: 'controller/bank-adb-controller.php',
                method: 'POST',
                dataType: 'json',
                data: {
                    bank_adb_id : bank_adb_id, 
                    transaction : transaction
                },
                beforeSend: function() {
                    resetForm('bank-adb-form');
                },
                success: function(response) {
                    if (response.success) {
                        $('#bank_adb_id').val(bank_adb_id);
                        $('#bank_adb_name').val(response.bankADBName);

                        $('#bank_adb_name_label').text(response.bankADBName);
                    } 
                    else {
                        if(response.isInactive){
                            window.location = 'logout.php?logout';
                        }
                        else{
                            showNotification('Get Bank ADB Details Error', response.message, 'danger');
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