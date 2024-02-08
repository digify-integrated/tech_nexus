(function($) {
    'use strict';

    $(function() {
        if($('#document-authorizer-table').length){
            documentAuthorizerTable('#document-authorizer-table');
        }

        if($('#document-authorizer-form').length){
            documentAuthorizerForm();
        }

        if($('#document-authorizer-id').length){
            displayDetails('get document authorizer details');
        }

        $(document).on('click','.delete-document-authorizer',function() {
            const document_authorizer_id = $(this).data('document-authorizer-id');
            const transaction = 'delete document authorizer';
    
            Swal.fire({
                title: 'Confirm Document Authorizer Deletion',
                text: 'Are you sure you want to delete this document authorizer?',
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
                        url: 'controller/document-authorizer-controller.php',
                        dataType: 'json',
                        data: {
                            document_authorizer_id : document_authorizer_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                showNotification('Delete Document Authorizer Success', 'The document authorizer has been deleted successfully.', 'success');
                                reloadDatatable('#document-authorizer-table');
                            }
                            else {
                                if (response.isInactive) {
                                    setNotification('User Inactive', response.message, 'danger');
                                    window.location = 'logout.php?logout';
                                }
                                else if (response.notExist) {
                                    showNotification('Delete Document Authorizer Error', 'The document authorizer does not exist.', 'danger');
                                    reloadDatatable('#document-authorizer-table');
                                }
                                else {
                                    showNotification('Delete Document Authorizer Error', response.message, 'danger');
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

        $(document).on('click','#delete-document-authorizer',function() {
            let document_authorizer_id = [];
            const transaction = 'delete multiple document authorizer';

            $('.datatable-checkbox-children').each((index, element) => {
                if ($(element).is(':checked')) {
                    document_authorizer_id.push(element.value);
                }
            });
    
            if(document_authorizer_id.length > 0){
                Swal.fire({
                    title: 'Confirm Multiple Document Authorizers Deletion',
                    text: 'Are you sure you want to delete these document authorizers?',
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
                            url: 'controller/document-authorizer-controller.php',
                            dataType: 'json',
                            data: {
                                document_authorizer_id: document_authorizer_id,
                                transaction : transaction
                            },
                            success: function (response) {
                                if (response.success) {
                                    showNotification('Delete Document Authorizer Success', 'The selected document authorizers have been deleted successfully.', 'success');
                                    reloadDatatable('#document-authorizer-table');
                                }
                                else {
                                    if (response.isInactive) {
                                        setNotification('User Inactive', response.message, 'danger');
                                        window.location = 'logout.php?logout';
                                    }
                                    else {
                                        showNotification('Delete Document Authorizer Error', response.message, 'danger');
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
                showNotification('Deletion Multiple Document Authorizer Error', 'Please select the document authorizers you wish to delete.', 'danger');
            }
        });

        $(document).on('click','#delete-document-authorizer-details',function() {
            const document_authorizer_id = $('#document-authorizer-id').text();
            const transaction = 'delete document authorizer';
    
            Swal.fire({
                title: 'Confirm Document Authorizer Deletion',
                text: 'Are you sure you want to delete this document authorizer?',
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
                        url: 'controller/document-authorizer-controller.php',
                        dataType: 'json',
                        data: {
                            document_authorizer_id : document_authorizer_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                setNotification('Deleted Document Authorizer Success', 'The document authorizer has been deleted successfully.', 'success');
                                window.location = 'document-authorizer.php';
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
                                    showNotification('Delete Document Authorizer Error', response.message, 'danger');
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
            discardCreate('document-authorizer.php');
        });
    });
})(jQuery);

function documentAuthorizerTable(datatable_name, buttons = false, show_all = false){
    const type = 'document authorizer table';
    var settings;

    const column = [ 
        { 'data' : 'CHECK_BOX' },
        { 'data' : 'AUTHORIZER_NAME' },
        { 'data' : 'DEPARTMENT_NAME' },
        { 'data' : 'ACTION' }
    ];

    const column_definition = [
        { 'width': '1%','bSortable': false, 'aTargets': 0 },
        { 'width': '42%', 'aTargets': 1 },
        { 'width': '42%', 'aTargets': 2 },
        { 'width': '15%','bSortable': false, 'aTargets': 3 }
    ];

    const length_menu = show_all ? [[-1], ['All']] : [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']];

    settings = {
        'ajax': { 
            'url' : 'view/_document_authorizer_generation.php',
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

function documentAuthorizerForm(){
    $('#document-authorizer-form').validate({
        rules: {
            department_id: {
                required: true
            },
            authorizer_id: {
                required: true
            },
        },
        messages: {
            department_id: {
                required: 'Please choose the department'
            },
            authorizer_id: {
                required: 'Please choose the employee'
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
            const document_authorizer_id = $('#document-authorizer-id').text();
            const transaction = 'save document authorizer';
        
            $.ajax({
                type: 'POST',
                url: 'controller/document-authorizer-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction + '&document_authorizer_id=' + document_authorizer_id,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-data');
                },
                success: function (response) {
                    if (response.success) {
                        const notificationMessage = 'Insert Document Authorizer Success';
                        const notificationDescription = 'The document authorizer has been inserted successfully.';
                        
                        setNotification(notificationMessage, notificationDescription, 'success');
                        window.location = 'document-authorizer.php?id=' + response.documentAuthorizerID;
                    }
                    else {
                        if (response.isInactive) {
                            setNotification('User Inactive', response.message, 'danger');
                            window.location = 'logout.php?logout';
                        }
                        else if (response.exist) {
                            showNotification('Transaction Error', 'The document authorizer already exists.', 'danger');
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
        case 'get document authorizer details':
            const document_authorizer_id = $('#document-authorizer-id').text();
            
            $.ajax({
                url: 'controller/document-authorizer-controller.php',
                method: 'POST',
                dataType: 'json',
                data: {
                    document_authorizer_id : document_authorizer_id, 
                    transaction : transaction
                },
                beforeSend: function() {
                    resetForm('document-authorizer-form');
                },
                success: function(response) {
                    if (response.success) {
                        $('#document_authorizer_id').val(document_authorizer_id);

                        $('#authorizer_id_label').text(response.authorizerName);
                        $('#department_id_label').text(response.departmentName);
                    } 
                    else {
                        if(response.isInactive){
                            window.location = 'logout.php?logout';
                        }
                        else{
                            showNotification('Get Document Authorizer Details Error', response.message, 'danger');
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