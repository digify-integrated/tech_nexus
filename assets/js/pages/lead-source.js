(function($) {
    'use strict';

    $(function() {
        if($('#lead-source-table').length){
            leadSourceTable('#lead-source-table');
        }

        if($('#lead-source-form').length){
            leadSourceForm();
        }

        if($('#lead-source-id').length){
            displayDetails('get lead source details');
        }

        $(document).on('click','.delete-lead-source',function() {
            const lead_source_id = $(this).data('lead-source-id');
            const transaction = 'delete lead source';
    
            Swal.fire({
                title: 'Confirm Lead Source Deletion',
                text: 'Are you sure you want to delete this lead source?',
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
                        url: 'controller/lead-source-controller.php',
                        dataType: 'json',
                        data: {
                            lead_source_id : lead_source_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                showNotification('Delete Lead Source Success', 'The lead source has been deleted successfully.', 'success');
                                reloadDatatable('#lead-source-table');
                            }
                            else {
                                if (response.isInactive) {
                                    setNotification('User Inactive', response.message, 'danger');
                                    window.location = 'logout.php?logout';
                                }
                                else if (response.notExist) {
                                    showNotification('Delete Lead Source Error', 'The lead source does not exist.', 'danger');
                                    reloadDatatable('#lead-source-table');
                                }
                                else {
                                    showNotification('Delete Lead Source Error', response.message, 'danger');
                                }
                            }
                        },
                        error: function(xhr, source, error) {
                            var fullErrorMessage = `XHR source: ${source}, Error: ${error}`;
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

        $(document).on('click','#delete-lead-source',function() {
            let lead_source_id = [];
            const transaction = 'delete multiple lead source';

            $('.datatable-checkbox-children').each((index, element) => {
                if ($(element).is(':checked')) {
                    lead_source_id.push(element.value);
                }
            });
    
            if(lead_source_id.length > 0){
                Swal.fire({
                    title: 'Confirm Multiple Lead Source Deletion',
                    text: 'Are you sure you want to delete these lead source?',
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
                            url: 'controller/lead-source-controller.php',
                            dataType: 'json',
                            data: {
                                lead_source_id: lead_source_id,
                                transaction : transaction
                            },
                            success: function (response) {
                                if (response.success) {
                                    showNotification('Delete Lead Source Success', 'The selected lead source have been deleted successfully.', 'success');
                                        reloadDatatable('#lead-source-table');
                                }
                                else {
                                    if (response.isInactive) {
                                        setNotification('User Inactive', response.message, 'danger');
                                        window.location = 'logout.php?logout';
                                    }
                                    else {
                                        showNotification('Delete Lead Source Error', response.message, 'danger');
                                    }
                                }
                            },
                            error: function(xhr, source, error) {
                                var fullErrorMessage = `XHR source: ${source}, Error: ${error}`;
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
                showNotification('Deletion Multiple Lead Source Error', 'Please select the lead source you wish to delete.', 'danger');
            }
        });

        $(document).on('click','#delete-lead-source-details',function() {
            const lead_source_id = $('#lead-source-id').text();
            const transaction = 'delete lead source';
    
            Swal.fire({
                title: 'Confirm Lead Source Deletion',
                text: 'Are you sure you want to delete this lead source?',
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
                        url: 'controller/lead-source-controller.php',
                        dataType: 'json',
                        data: {
                            lead_source_id : lead_source_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                setNotification('Deleted Lead Source Success', 'The lead source has been deleted successfully.', 'success');
                                window.location = 'lead-source.php';
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
                                    showNotification('Delete Lead Source Error', response.message, 'danger');
                                }
                            }
                        },
                        error: function(xhr, source, error) {
                            var fullErrorMessage = `XHR source: ${source}, Error: ${error}`;
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
            discardCreate('lead-source.php');
        });

        $(document).on('click','#edit-form',function() {
            displayDetails('get lead source details');

            enableForm();
        });

        $(document).on('click','#duplicate-lead-source',function() {
            const lead_source_id = $('#lead-source-id').text();
            const transaction = 'duplicate lead source';
    
            Swal.fire({
                title: 'Confirm Lead Source Duplication',
                text: 'Are you sure you want to duplicate this lead source?',
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
                        url: 'controller/lead-source-controller.php',
                        dataType: 'json',
                        data: {
                            lead_source_id : lead_source_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                setNotification('Duplicate Lead Source Success', 'The lead source has been duplicated successfully.', 'success');
                                window.location = 'lead-source.php?id=' + response.leadSourceID;
                            }
                            else {
                                if (response.isInactive) {
                                    setNotification('User Inactive', response.message, 'danger');
                                    window.location = 'logout.php?logout';
                                }
                                else if (response.notExist) {
                                    showNotification('Duplicate Lead Source Error', 'The lead source does not exist.', 'danger');
                                    reloadDatatable('#lead-source-table');
                                }
                                else {
                                    showNotification('Duplicate Lead Source Error', response.message, 'danger');
                                }
                            }
                        },
                        error: function(xhr, source, error) {
                            var fullErrorMessage = `XHR source: ${source}, Error: ${error}`;
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

function leadSourceTable(datatable_name, buttons = false, show_all = false){
    const type = 'lead source table';
    var settings;

    const column = [ 
        { 'data' : 'CHECK_BOX' },
        { 'data' : 'LEAD_SOURCE_NAME' },
        { 'data' : 'ACTION' }
    ];

    const column_definition = [
        { 'width': '1%','bSortable': false, 'aTargets': 0 },
        { 'width': 'auto', 'aTargets': 1 },
        { 'width': '15%','bSortable': false, 'aTargets': 2 }
    ];

    const length_menu = show_all ? [[-1], ['All']] : [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']];

    settings = {
        'ajax': { 
            'url' : 'view/_lead_source_generation.php',
            'method' : 'POST',
            'dataType': 'json',
            'data': {'type' : type},
            'dataSrc' : '',
            'error': function(xhr, source, error) {
                var fullErrorMessage = `XHR source: ${source}, Error: ${error}`;
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

function leadSourceForm(){
    $('#lead-source-form').validate({
        rules: {
            lead_source_name: {
                required: true
            },
        },
        messages: {
            lead_source_name: {
                required: 'Please enter the lead source name'
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
            const lead_source_id = $('#lead-source-id').text();
            const transaction = 'save lead source';
        
            $.ajax({
                type: 'POST',
                url: 'controller/lead-source-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction + '&lead_source_id=' + lead_source_id,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-data');
                },
                success: function (response) {
                    if (response.success) {
                        const notificationMessage = response.insertRecord ? 'Insert Lead Source Success' : 'Update Lead Source Success';
                        const notificationDescription = response.insertRecord ? 'The lead source has been inserted successfully.' : 'The lead source has been updated successfully.';
                        
                        setNotification(notificationMessage, notificationDescription, 'success');
                        window.location = 'lead-source.php?id=' + response.leadSourceID;
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
                error: function(xhr, source, error) {
                    var fullErrorMessage = `XHR source: ${source}, Error: ${error}`;
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
        case 'get lead source details':
            const lead_source_id = $('#lead-source-id').text();
            
            $.ajax({
                url: 'controller/lead-source-controller.php',
                method: 'POST',
                dataType: 'json',
                data: {
                    lead_source_id : lead_source_id, 
                    transaction : transaction
                },
                beforeSend: function() {
                    resetForm('lead-source-form');
                },
                success: function(response) {
                    if (response.success) {
                        $('#lead_source_id').val(lead_source_id);
                        $('#lead_source_name').val(response.leadSourceName);

                        $('#lead_source_name_label').text(response.leadSourceName);
                    } 
                    else {
                        if(response.isInactive){
                            window.location = 'logout.php?logout';
                        }
                        else{
                            showNotification('Get Lead Source Details Error', response.message, 'danger');
                        }
                    }
                },
                error: function(xhr, source, error) {
                    var fullErrorMessage = `XHR source: ${source}, Error: ${error}`;
                    if (xhr.responseText) {
                        fullErrorMessage += `, Response: ${xhr.responseText}`;
                    }
                    showErrorDialog(fullErrorMessage);
                }
            });
            break;
    }
}