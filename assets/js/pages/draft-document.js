(function($) {
    'use strict';

    $(function() {
        if($('#draft-document-table').length){
            draftDocumentTable('#draft-document-table');
        }

        if($('#add-document-form').length){
            addDocumentForm();
        }
        
        if($('#document-id').length){
            displayDetails('get document details');

            if($('#document-file-update-form').length){
                updateDocumentFileForm();
            }
            
            if($('#document-update-form').length){
                updateDocumentForm();
            }

            if($('#document-version-history-summary').length){
                documentVersionHistorySummary();
            }

            if($('#department-restriction-table').length){
                departmentRestrictionTable('#department-restriction-table');
            }

            if($('#employee-restriction-table').length){
                employeeRestrictionTable('#employee-restriction-table');
            }

            if($('#add-department-restrictions-form').length){
                addDepartmentRestrictionForm();
            }

            if($('#add-employee-restrictions-form').length){
                addEmployeeRestrictionForm();
            }

            if($('#change-document-password-form').length){
                changeDocumentPasswordForm();
            }

            $(document).on('click','#add-department-restrictions',function() {
                departmentRestrictionExceptionTable('#add-department-restrictions-table');
            });

            $(document).on('click','.delete-department-restrictions',function() {
                const document_restriction_id = $(this).data('document-restriction-id');
                const transaction = 'delete document restrictions';
        
                Swal.fire({
                    title: 'Confirm Department Restriction Deletion',
                    text: 'Are you sure you want to delete this department restriction?',
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
                            url: 'controller/document-controller.php',
                            dataType: 'json',
                            data: {
                                document_restriction_id : document_restriction_id, 
                                transaction : transaction
                            },
                            success: function (response) {
                                if (response.success) {
                                    showNotification('Delete Department Restriction Success', 'The department restriction has been deleted successfully.', 'success');
                                }
                                else {
                                    if (response.isInactive) {
                                        setNotification('User Inactive', response.message, 'danger');
                                        window.location = 'logout.php?logout';
                                    }
                                    else if (response.notExist) {
                                        showNotification('Delete Department Restriction Error', 'The department restriction does not exist.', 'danger');
                                    }
                                    else {
                                        showNotification('Delete Department Restriction Error', response.message, 'danger');
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
                                reloadDatatable('#department-restriction-table');
                            }
                        });
                        return false;
                    }
                });
            });

            $(document).on('click','#add-employee-restrictions',function() {
                employeeRestrictionExceptionTable('#add-employee-restrictions-table');
            });

            $(document).on('click','.delete-employee-restrictions',function() {
                const document_restriction_id = $(this).data('document-restriction-id');
                const transaction = 'delete document restrictions';
        
                Swal.fire({
                    title: 'Confirm Employee Restriction Deletion',
                    text: 'Are you sure you want to delete this employee restriction?',
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
                            url: 'controller/document-controller.php',
                            dataType: 'json',
                            data: {
                                document_restriction_id : document_restriction_id, 
                                transaction : transaction
                            },
                            success: function (response) {
                                if (response.success) {
                                    showNotification('Delete Employee Restriction Success', 'The employee restriction has been deleted successfully.', 'success');
                                }
                                else {
                                    if (response.isInactive) {
                                        setNotification('User Inactive', response.message, 'danger');
                                        window.location = 'logout.php?logout';
                                    }
                                    else if (response.notExist) {
                                        showNotification('Delete Employee Restriction Error', 'The employee restriction does not exist.', 'danger');
                                    }
                                    else {
                                        showNotification('Delete Employee Restriction Error', response.message, 'danger');
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
                                reloadDatatable('#employee-restriction-table');
                            }
                        });
                        return false;
                    }
                });
            });

            $(document).on('click','#publish-document',function() {
                const document_id = $('#document-id').text();
                const transaction = 'publish document';
        
                Swal.fire({
                    title: 'Confirm Document Publish',
                    text: 'Are you sure you want to publish this document?',
                    icon: 'info',
                    showCancelButton: !0,
                    confirmButtonText: 'Publish',
                    cancelButtonText: 'Cancel',
                    confirmButtonClass: 'btn btn-success mt-2',
                    cancelButtonClass: 'btn btn-secondary ms-2 mt-2',
                    buttonsStyling: !1
                }).then(function(result) {
                    if (result.value) {
                        $.ajax({
                            type: 'POST',
                            url: 'controller/document-controller.php',
                            dataType: 'json',
                            data: {
                                document_id : document_id, 
                                transaction : transaction
                            },
                            success: function (response) {
                                if (response.success) {
                                    setNotification('Publish Document Success', 'The document has been published successfully.', 'success');
                                    window.location = 'draft-document.php';
                                }
                                else {
                                    if (response.isInactive) {
                                        setNotification('User Inactive', response.message, 'danger');
                                        window.location = 'logout.php?logout';
                                    }
                                    else if (response.notExist) {
                                        window.location = '404.php';
                                    }
                                    else if (response.isConfidential) {
                                        showNotification('Publish Document Error', 'Please set a document password. This document contains confidential information and requires additional security.', 'danger');
                                    }
                                    else {
                                        showNotification('Publish Document Error', response.message, 'danger');
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

            $(document).on('click','#remove-document-password',function() {
                const document_id = $('#document-id').text();
                const transaction = 'remove document password';
        
                Swal.fire({
                    title: 'Confirm Document Password Removal',
                    text: 'Are you sure you want to remove the password of this document?',
                    icon: 'warning',
                    showCancelButton: !0,
                    confirmButtonText: 'Remove',
                    cancelButtonText: 'Cancel',
                    confirmButtonClass: 'btn btn-warning mt-2',
                    cancelButtonClass: 'btn btn-secondary ms-2 mt-2',
                    buttonsStyling: !1
                }).then(function(result) {
                    if (result.value) {
                        $.ajax({
                            type: 'POST',
                            url: 'controller/document-controller.php',
                            dataType: 'json',
                            data: {
                                document_id : document_id, 
                                transaction : transaction
                            },
                            success: function (response) {
                                if (response.success) {
                                    setNotification('Remove Document Password Success', 'The document password has been removed successfully.', 'success');
                                    window.location.reload();
                                }
                                else {
                                    if (response.isInactive) {
                                        setNotification('User Inactive', response.message, 'danger');
                                        window.location = 'logout.php?logout';
                                    }
                                    else if (response.notExist) {
                                        window.location = '404.php';
                                    }
                                    else if (response.isConfidential) {
                                        showNotification('Remove Document Password Error', 'Unable to remove the document password. This document has been marked as confidential, and a password is required to maintain its security.', 'danger');
                                    }
                                    else {
                                        showNotification('Remove Document Password Error', response.message, 'danger');
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
        }       

        $(document).on('click','.delete-document',function() {
            const document_id = $(this).data('document-id');
            const transaction = 'delete document';
    
            Swal.fire({
                title: 'Confirm Document Deletion',
                text: 'Are you sure you want to delete this document?',
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
                        url: 'controller/document-controller.php',
                        dataType: 'json',
                        data: {
                            document_id : document_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                showNotification('Delete Document Success', 'The document has been deleted successfully.', 'success');
                                reloadDatatable('#draft-document-table');
                            }
                            else {
                                if (response.isInactive) {
                                    setNotification('User Inactive', response.message, 'danger');
                                    window.location = 'logout.php?logout';
                                }
                                else if (response.notExist) {
                                    showNotification('Delete Document Error', 'The document does not exist.', 'danger');
                                    reloadDatatable('#draft-document-table');
                                }
                                else {
                                    showNotification('Delete Document Error', response.message, 'danger');
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

        $(document).on('click','#delete-document',function() {
            let document_id = [];
            const transaction = 'delete multiple document';

            $('.datatable-checkbox-children').each((index, element) => {
                if ($(element).is(':checked')) {
                    document_id.push(element.value);
                }
            });
    
            if(document_id.length > 0){
                Swal.fire({
                    title: 'Confirm Multiple Documents Deletion',
                    text: 'Are you sure you want to delete these documents?',
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
                            url: 'controller/document-controller.php',
                            dataType: 'json',
                            data: {
                                document_id: document_id,
                                transaction : transaction
                            },
                            success: function (response) {
                                if (response.success) {
                                    showNotification('Delete Document Success', 'The selected documents have been deleted successfully.', 'success');
                                        reloadDatatable('#draft-document-table');
                                }
                                else {
                                    if (response.isInactive) {
                                        setNotification('User Inactive', response.message, 'danger');
                                        window.location = 'logout.php?logout';
                                    }
                                    else {
                                        showNotification('Delete Document Error', response.message, 'danger');
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
                showNotification('Deletion Multiple Document Error', 'Please select the documents you wish to delete.', 'danger');
            }
        });

        $(document).on('click','#delete-document-details',function() {
            const document_id = $('#document-id').text();
            const transaction = 'delete document';
    
            Swal.fire({
                title: 'Confirm Document Deletion',
                text: 'Are you sure you want to delete this document?',
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
                        url: 'controller/document-controller.php',
                        dataType: 'json',
                        data: {
                            document_id : document_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                setNotification('Deleted Document Success', 'The document has been deleted successfully.', 'success');
                                window.location = 'draft-document.php';
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
                                    showNotification('Delete Document Error', response.message, 'danger');
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
            discardCreate('document.php');
        });

        $(document).on('click','#edit-form',function() {
            displayDetails('get document details');

            enableForm();
        });

        $(document).on('click','#apply-filter',function() {
            draftDocumentTable('#draft-document-table');
        });
    });
})(jQuery);

function draftDocumentTable(datatable_name, buttons = false, show_all = false){
    const type = 'draft document table';
    var filter_upload_date_start_date = $('#filter_upload_date_start_date').val();
    var filter_upload_date_end_date = $('#filter_upload_date_end_date').val();
    var document_category_filter_values = [];
    var department_filter_values = [];

    $('.document-category-filter:checked').each(function() {
        document_category_filter_values.push($(this).val());
    });

    $('.department-filter:checked').each(function() {
        department_filter_values.push($(this).val());
    });

    var document_category_filter = document_category_filter_values.join(', ');
    var department_filter = department_filter_values.join(', ');
    var settings;

    const column = [ 
        { 'data' : 'CHECK_BOX' },
        { 'data' : 'DOCUMENT_NAME' },
        { 'data' : 'DOCUMENT_CATEGORY' },
        { 'data' : 'AUTHOR' },
        { 'data' : 'UPLOAD_DATE' },
        { 'data' : 'DOCUMENT_STATUS' },
        { 'data' : 'ACTION' }
    ];

    const column_definition = [
        { 'width': '1%','bSortable': false, 'aTargets': 0 },
        { 'width': '24%', 'aTargets': 1 },
        { 'width': '15%', 'aTargets': 2 },
        { 'width': '15%', 'aTargets': 3 },
        { 'width': '15%', 'aTargets': 4 },
        { 'width': '15%', 'aTargets': 5 },
        { 'width': '15%','bSortable': false, 'aTargets': 6 }
    ];

    const length_menu = show_all ? [[-1], ['All']] : [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']];

    settings = {
        'ajax': { 
            'url' : 'view/_draft_document_generation.php',
            'method' : 'POST',
            'dataType': 'json',
            'data': {'type' : type, 'filter_upload_date_start_date' : filter_upload_date_start_date, 'filter_upload_date_end_date' : filter_upload_date_end_date, 'document_category_filter' : document_category_filter, 'department_filter' : department_filter},
            'dataSrc' : '',
            'error': function(xhr, status, error) {
                var fullErrorMessage = `XHR status: ${status}, Error: ${error}`;
                if (xhr.responseText) {
                    fullErrorMessage += `, Response: ${xhr.responseText}`;
                }
                showErrorDialog(fullErrorMessage);
            }
        },
        'order': [[ 2, 'desc' ]],
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

function departmentRestrictionTable(datatable_name, buttons = false, show_all = false){
    const document_id = $('#document-id').text();
    const type = 'document department restriction table';
    var settings;

    const column = [ 
        { 'data' : 'DEPARTMENT_NAME' },
        { 'data' : 'ACTION' }
    ];

    const column_definition = [
        { 'width': '80%', 'aTargets': 0 },
        { 'width': '20%', 'bSortable': false, 'aTargets': 1 }
    ];

    const length_menu = show_all ? [[-1], ['All']] : [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']];

    settings = {
        'ajax': { 
            'url' : 'view/_draft_document_generation.php',
            'method' : 'POST',
            'dataType': 'json',
            'data': {'type' : type, 'document_id' : document_id},
            'dataSrc' : '',
            'error': function(xhr, status, error) {
                var fullErrorMessage = `XHR status: ${status}, Error: ${error}`;
                if (xhr.responseText) {
                    fullErrorMessage += `, Response: ${xhr.responseText}`;
                }
                showErrorDialog(fullErrorMessage);
            }
        },
        'order': [[ 0, 'asc' ]],
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

function departmentRestrictionExceptionTable(datatable_name, buttons = false, show_all = false){
    const document_id = $('#document-id').text();
    const type = 'document department restriction excemption table';
    var settings;

    const column = [ 
        { 'data' : 'DEPARTMENT_NAME' },
        { 'data' : 'ASSIGN' }
    ];

    const column_definition = [
        { 'width': '80%', 'aTargets': 0 },
        { 'width': '20%', 'bSortable': false, 'aTargets': 1 }
    ];

    const length_menu = show_all ? [[-1], ['All']] : [[-1], ['All']];

    settings = {
        'ajax': { 
            'url' : 'view/_draft_document_generation.php',
            'method' : 'POST',
            'dataType': 'json',
            'data': {'type' : type, 'document_id' : document_id},
            'dataSrc' : '',
            'error': function(xhr, status, error) {
                var fullErrorMessage = `XHR status: ${status}, Error: ${error}`;
                if (xhr.responseText) {
                    fullErrorMessage += `, Response: ${xhr.responseText}`;
                }
                showErrorDialog(fullErrorMessage);
            }
        },
        'order': [[ 0, 'asc' ]],
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

function employeeRestrictionTable(datatable_name, buttons = false, show_all = false){
    const document_id = $('#document-id').text();
    const type = 'document employee restriction table';
    var settings;

    const column = [ 
        { 'data' : 'EMPLOYEE_NAME' },
        { 'data' : 'ACTION' }
    ];

    const column_definition = [
        { 'width': '80%', 'aTargets': 0 },
        { 'width': '20%', 'bSortable': false, 'aTargets': 1 }
    ];

    const length_menu = show_all ? [[-1], ['All']] : [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']];

    settings = {
        'ajax': { 
            'url' : 'view/_draft_document_generation.php',
            'method' : 'POST',
            'dataType': 'json',
            'data': {'type' : type, 'document_id' : document_id},
            'dataSrc' : '',
            'error': function(xhr, status, error) {
                var fullErrorMessage = `XHR status: ${status}, Error: ${error}`;
                if (xhr.responseText) {
                    fullErrorMessage += `, Response: ${xhr.responseText}`;
                }
                showErrorDialog(fullErrorMessage);
            }
        },
        'order': [[ 0, 'asc' ]],
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

function employeeRestrictionExceptionTable(datatable_name, buttons = false, show_all = false){
    const document_id = $('#document-id').text();
    const type = 'document employee restriction excemption table';
    var settings;

    const column = [ 
        { 'data' : 'EMPLOYEE_NAME' },
        { 'data' : 'ASSIGN' }
    ];

    const column_definition = [
        { 'width': '80%', 'aTargets': 0 },
        { 'width': '20%', 'bSortable': false, 'aTargets': 1 }
    ];

    const length_menu = show_all ? [[-1], ['All']] : [[-1], ['All']];

    settings = {
        'ajax': { 
            'url' : 'view/_draft_document_generation.php',
            'method' : 'POST',
            'dataType': 'json',
            'data': {'type' : type, 'document_id' : document_id},
            'dataSrc' : '',
            'error': function(xhr, status, error) {
                var fullErrorMessage = `XHR status: ${status}, Error: ${error}`;
                if (xhr.responseText) {
                    fullErrorMessage += `, Response: ${xhr.responseText}`;
                }
                showErrorDialog(fullErrorMessage);
            }
        },
        'order': [[ 0, 'asc' ]],
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

function addDocumentForm(){
    $('#add-document-form').validate({
        rules: {
            document_name: {
                required: true
            },
            document_category_id: {
                required: true
            },
            is_confidential: {
                required: true
            },
            document_password: {
                required: function(element) {
                    return $('#is_confidential').val() === 'Yes';
                },
                password_strength: {
                    depends: function(element) {
                        return ($('#is_confidential').val() === 'Yes') || (($('#is_confidential').val() === 'No') && ($('#document_password').val() !== null));
                    }
                }
            },
            document_file: {
                required: true
            }
        },
        messages: {
            document_name: {
                required: 'Please enter the document name'
            },
            document_category_id: {
                required: 'Please choose the document category'
            },
            is_confidential: {
                required: 'Please choose the confidential status'
            },
            document_password: {
                required: 'Please enter the document password for confidential documents'
            },
            document_file: {
                required: 'Please choose the document'
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
            const transaction = 'add document';
            var formData = new FormData(form);
            formData.append('transaction', transaction);
        
            $.ajax({
                type: 'POST',
                url: 'controller/document-controller.php',
                data: formData,
                processData: false,
                contentType: false,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-data');
                },
                success: function (response) {
                    if (response.success) {
                        const notificationMessage = 'Insert Document Success';
                        const notificationDescription = 'The document has been inserted successfully.';
                        
                        setNotification(notificationMessage, notificationDescription, 'success');
                        window.location = 'draft-document.php?id=' + response.documentID;
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

function updateDocumentFileForm(){
    $('#document-file-update-form').validate({
        rules: {
            document_file: {
                required: true
            }
        },
        messages: {
            document_file: {
                required: 'Please choose the document'
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
            const document_id = $('#document-id').text();
            const transaction = 'update document file';
            var formData = new FormData(form);
            formData.append('document_id', document_id);
            formData.append('transaction', transaction);
        
            $.ajax({
                type: 'POST',
                url: 'controller/document-controller.php',
                data: formData,
                processData: false,
                contentType: false,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-data');
                },
                success: function (response) {
                    if (response.success) {
                        const notificationMessage = 'Update Document File Success';
                        const notificationDescription = 'The document file has been updated successfully.';
                        
                        setNotification(notificationMessage, notificationDescription, 'success');
                        window.location.reload();
                    }
                    else {
                        if (response.isInactive) {
                            setNotification('User Inactive', response.message, 'danger');
                            window.location = 'logout.php?logout';
                        }
                        else if (response.notExist) {
                            setNotification('Transaction Error', 'The document does not exists.', 'danger');
                            window.location = '404.php';
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

function changeDocumentPasswordForm(){
    $('#change-document-password-form').validate({
        rules: {
            new_document_password: {
              required: true,
              password_strength: true
            },
            confirm_document_password: {
              required: true,
              equalTo: '#new_document_password'
            }
        },
        messages: {
            new_document_password: {
              required: 'Please enter the new password'
            },
            confirm_document_password: {
              required: 'Please re-enter the password for confirmation',
              equalTo: 'The passwords you entered do not match. Please make sure to enter the same password in both fields'
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
        if ($(element).hasClass('select2-hidden-accessible')) {
          $(element).next().find('.select2-selection__rendered').addClass('is-invalid');
        } 
        else {
          $(element).addClass('is-invalid');
        }
      },
      unhighlight: function(element) {
        if ($(element).hasClass('select2-hidden-accessible')) {
          $(element).next().find('.select2-selection__rendered').removeClass('is-invalid');
        }
        else {
          $(element).removeClass('is-invalid');
        }
      },
      submitHandler: function(form) {
        const transaction = 'change document password';
        const document_id = $('#document-id').text();
  
        $.ajax({
            type: 'POST',
            url: 'controller/document-controller.php',
            data: $(form).serialize() + '&transaction=' + transaction + '&document_id=' + document_id,
            dataType: 'json',
            beforeSend: function() {
                disableFormSubmitButton('submit-change-document-password-form');
            },
            success: function(response) {
                if (response.success) {
                    setNotification('Document Password Change Success', 'The document password has been successfully updated.', 'success');
                    $('#change-document-password-offcanvas').offcanvas('hide');
                    resetModalForm('change-document-password-form');
                }
                else{
                    if(response.isInactive){
                        window.location = 'logout.php?logout';
                    }
                    else if (response.notExist) {
                        window.location = '404.php';
                    }
                    else{
                        showNotification('Document Password Change Error', response.message, 'danger');
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
                enableFormSubmitButton('submit-change-document-password-form', 'Update Password');
                window.location.reload();
            }
        });
  
        return false;
      }
    });
}

function updateDocumentForm(){
    $('#document-update-form').validate({
        rules: {
            document_name: {
                required: true
            },
            document_category_id: {
                required: true
            },
            is_confidential: {
                required: true
            }
        },
        messages: {
            document_name: {
                required: 'Please enter the document name'
            },
            document_category_id: {
                required: 'Please choose the document category'
            },
            is_confidential: {
                required: 'Please choose the confidential status'
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
            const document_id = $('#document-id').text();
            const transaction = 'update document';
        
            $.ajax({
                type: 'POST',
                url: 'controller/document-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction + '&document_id=' + document_id,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-update-document-file-data');
                },
                success: function (response) {
                    if (response.success) {
                        const notificationMessage = 'Update Document Success';
                        const notificationDescription = 'The document has been updated successfully.';
                        
                        setNotification(notificationMessage, notificationDescription, 'success');
                        window.location = 'draft-document.php?id=' + response.documentID;
                    }
                    else {
                        if (response.isInactive) {
                            setNotification('User Inactive', response.message, 'danger');
                            window.location = 'logout.php?logout';
                        }
                        else if (response.notExist) {
                            setNotification('Transaction Error', 'The document does not exists.', 'danger');
                            window.location = '404.php';
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
                    enableFormSubmitButton('submit-update-document-file-data', 'Submit');
                }
            });
        
            return false;
        }
    });
}

function addDepartmentRestrictionForm(){
    $('#add-department-restrictions-form').validate({
        submitHandler: function(form) {
            const document_id = $('#document-id').text();
            const transaction = 'add department restriction';

            var department_id = [];

            $('.department-excemption').each(function(){
                if ($(this).is(':checked')){  
                    department_id.push(this.value);  
                }
            });

            $.ajax({
                type: 'POST',
                url: 'controller/document-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction + '&document_id=' + document_id + '&department_id=' + department_id,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-add-department-restrictions');
                },
                success: function(response) {
                    if (response.success) {
                        showNotification('Add Department Restriction Success', 'The department restriction has been added successfully.', 'success');
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
                    var fullErrorMessage = 'XHR status: ' + status + ', Error: ' + error;
                    fullErrorMessage += ', Response: ' + xhr.responseText;
                    showErrorDialog(fullErrorMessage);
                },
                complete: function() {
                    enableFormSubmitButton('submit-add-department-restrictions', 'Submit');
                    $('#add-department-restrictions-offcanvas').offcanvas('hide');
                    reloadDatatable('#department-restriction-table');
                }
            });
            return false;
        }
    });
}

function addEmployeeRestrictionForm(){
    $('#add-employee-restrictions-form').validate({
        submitHandler: function(form) {
            const document_id = $('#document-id').text();
            const transaction = 'add employee restriction';

            var employee_id = [];

            $('.employee-excemption').each(function(){
                if ($(this).is(':checked')){  
                    employee_id.push(this.value);  
                }
            });

            $.ajax({
                type: 'POST',
                url: 'controller/document-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction + '&document_id=' + document_id + '&employee_id=' + employee_id,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-add-employee-restrictions');
                },
                success: function(response) {
                    if (response.success) {
                        showNotification('Add Employee Restriction Success', 'The employee restriction has been added successfully.', 'success');
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
                    var fullErrorMessage = 'XHR status: ' + status + ', Error: ' + error;
                    fullErrorMessage += ', Response: ' + xhr.responseText;
                    showErrorDialog(fullErrorMessage);
                },
                complete: function() {
                    enableFormSubmitButton('submit-add-employee-restrictions', 'Submit');
                    $('#add-employee-restrictions-offcanvas').offcanvas('hide');
                    reloadDatatable('#employee-restriction-table');
                }
            });
            return false;
        }
    });
}

function documentVersionHistorySummary(){
    const type = 'document version history summary';
    var document_id = $('#document-id').text();
            
    $.ajax({
        url: 'view/_draft_document_generation.php',
        method: 'POST',
        dataType: 'json',
        data: {
            document_id : document_id, 
            type : type
        },
        success: function(response) {
            document.getElementById('document-version-history-summary').innerHTML = response[0].documentVersionHistorySummary;
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

function displayDetails(transaction){
    switch (transaction) {
        case 'get document details':
            const document_id = $('#document-id').text();
            
            $.ajax({
                url: 'controller/document-controller.php',
                method: 'POST',
                dataType: 'json',
                data: {
                    document_id : document_id, 
                    transaction : transaction
                },
                beforeSend: function() {
                    resetForm('document-form');
                },
                success: function(response) {
                    if (response.success) {
                        $('#document_id').val(document_id);
                        $('#document_name').val(response.documentName);
                        $('#document_description').val(response.documentDescription);

                        checkOptionExist('#document_category_id', response.documentCategoryID, '');
                        checkOptionExist('#is_confidential', response.isConfidential, '');
                    } 
                    else {
                        if(response.isInactive){
                            window.location = 'logout.php?logout';
                        }
                        else{
                            showNotification('Get Document Details Error', response.message, 'danger');
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