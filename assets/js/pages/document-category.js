(function($) {
    'use strict';

    $(function() {
        if($('#document-category-table').length){
            documentCategoryTable('#document-category-table');
        }

        if($('#document-category-form').length){
            documentCategoryForm();
        }

        if($('#document-category-id').length){
            displayDetails('get document category details');
        }

        $(document).on('click','.delete-document-category',function() {
            const document_category_id = $(this).data('document-category-id');
            const transaction = 'delete document category';
    
            Swal.fire({
                title: 'Confirm Document Category Deletion',
                text: 'Are you sure you want to delete this document category?',
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
                        url: 'controller/document-category-controller.php',
                        dataType: 'json',
                        data: {
                            document_category_id : document_category_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                showNotification('Delete Document Category Success', 'The document category has been deleted successfully.', 'success');
                                reloadDatatable('#document-category-table');
                            }
                            else {
                                if (response.isInactive) {
                                    setNotification('User Inactive', response.message, 'danger');
                                    window.location = 'logout.php?logout';
                                }
                                else if (response.notExist) {
                                    showNotification('Delete Document Category Error', 'The document category does not exist.', 'danger');
                                    reloadDatatable('#document-category-table');
                                }
                                else {
                                    showNotification('Delete Document Category Error', response.message, 'danger');
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

        $(document).on('click','#delete-document-category',function() {
            let document_category_id = [];
            const transaction = 'delete multiple document category';

            $('.datatable-checkbox-children').each((index, element) => {
                if ($(element).is(':checked')) {
                    document_category_id.push(element.value);
                }
            });
    
            if(document_category_id.length > 0){
                Swal.fire({
                    title: 'Confirm Multiple Document Categorys Deletion',
                    text: 'Are you sure you want to delete these document categorys?',
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
                            url: 'controller/document-category-controller.php',
                            dataType: 'json',
                            data: {
                                document_category_id: document_category_id,
                                transaction : transaction
                            },
                            success: function (response) {
                                if (response.success) {
                                    showNotification('Delete Document Category Success', 'The selected document categorys have been deleted successfully.', 'success');
                                    reloadDatatable('#document-category-table');
                                }
                                else {
                                    if (response.isInactive) {
                                        setNotification('User Inactive', response.message, 'danger');
                                        window.location = 'logout.php?logout';
                                    }
                                    else {
                                        showNotification('Delete Document Category Error', response.message, 'danger');
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
                showNotification('Deletion Multiple Document Category Error', 'Please select the document categorys you wish to delete.', 'danger');
            }
        });

        $(document).on('click','#delete-document-category-details',function() {
            const document_category_id = $('#document-category-id').text();
            const transaction = 'delete document category';
    
            Swal.fire({
                title: 'Confirm Document Category Deletion',
                text: 'Are you sure you want to delete this document category?',
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
                        url: 'controller/document-category-controller.php',
                        dataType: 'json',
                        data: {
                            document_category_id : document_category_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                setNotification('Deleted Document Category Success', 'The document category has been deleted successfully.', 'success');
                                window.location = 'document-category.php';
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
                                    showNotification('Delete Document Category Error', response.message, 'danger');
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
            discardCreate('document-category.php');
        });

        $(document).on('click','#edit-form',function() {
            displayDetails('get document category details');

            enableForm();
        });

        $(document).on('click','#duplicate-document-category',function() {
            const document_category_id = $('#document-category-id').text();
            const transaction = 'duplicate document category';
    
            Swal.fire({
                title: 'Confirm Document Category Duplication',
                text: 'Are you sure you want to duplicate this document category?',
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
                        url: 'controller/document-category-controller.php',
                        dataType: 'json',
                        data: {
                            document_category_id : document_category_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                setNotification('Duplicate Document Category Success', 'The document category has been duplicated successfully.', 'success');
                                window.location = 'document-category.php?id=' + response.documentCategoryID;
                            }
                            else {
                                if (response.isInactive) {
                                    setNotification('User Inactive', response.message, 'danger');
                                    window.location = 'logout.php?logout';
                                }
                                else if (response.notExist) {
                                    showNotification('Duplicate Document Category Error', 'The document category does not exist.', 'danger');
                                    reloadDatatable('#document-category-table');
                                }
                                else {
                                    showNotification('Duplicate Document Category Error', response.message, 'danger');
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

function documentCategoryTable(datatable_name, buttons = false, show_all = false){
    const type = 'document category table';
    var settings;

    const column = [ 
        { 'data' : 'CHECK_BOX' },
        { 'data' : 'DOCUMENT_CATEGORY_NAME' },
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
            'url' : 'view/_document_category_generation.php',
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

function documentCategoryForm(){
    $('#document-category-form').validate({
        rules: {
            document_category_name: {
                required: true
            },
        },
        messages: {
            document_category_name: {
                required: 'Please enter the document category name'
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
            const document_category_id = $('#document-category-id').text();
            const transaction = 'save document category';
        
            $.ajax({
                type: 'POST',
                url: 'controller/document-category-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction + '&document_category_id=' + document_category_id,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-data');
                },
                success: function (response) {
                    if (response.success) {
                        const notificationMessage = response.insertRecord ? 'Insert Document Category Success' : 'Update Document Category Success';
                        const notificationDescription = response.insertRecord ? 'The document category has been inserted successfully.' : 'The document category has been updated successfully.';
                        
                        setNotification(notificationMessage, notificationDescription, 'success');
                        window.location = 'document-category.php?id=' + response.documentCategoryID;
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
        case 'get document category details':
            const document_category_id = $('#document-category-id').text();
            
            $.ajax({
                url: 'controller/document-category-controller.php',
                method: 'POST',
                dataType: 'json',
                data: {
                    document_category_id : document_category_id, 
                    transaction : transaction
                },
                beforeSend: function() {
                    resetForm('document-category-form');
                },
                success: function(response) {
                    if (response.success) {
                        $('#document_category_id').val(document_category_id);
                        $('#document_category_name').val(response.documentCategoryName);

                        $('#document_category_name_label').text(response.documentCategoryName);
                    } 
                    else {
                        if(response.isInactive){
                            window.location = 'logout.php?logout';
                        }
                        else{
                            showNotification('Get Document Category Details Error', response.message, 'danger');
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