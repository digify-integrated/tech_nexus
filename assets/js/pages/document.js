(function($) {
    'use strict';

    $(function() {
        var page = 1;
        var is_loading = false;
        var documentCard = $('#document-card');
        var loadContent = $('#load-content');
        var documentSearch = $('#document_search');
        var lastSearchValue = '';

        var debounceTimeout;

        function debounce(func, delay) {
            clearTimeout(debounceTimeout);
            debounceTimeout = setTimeout(func, delay);
        }

        function loanDocumentCard(current_page, is_loading, clearExisting) {
            if (is_loading) return;

            var document_search = documentSearch.val();
            var filter_publish_date_start_date = $('#filter_publish_date_start_date').val();
            var filter_publish_date_end_date = $('#filter_publish_date_end_date').val();
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
        
            lastSearchValue = document_search;

            is_loading = true;
            const type = 'document card';

            if (clearExisting) {
                documentCard.empty();
            }

            loadContent.removeClass('d-none');

            $.ajax({
                url: 'view/_document_generation.php',
                method: 'POST',
                dataType: 'json',
                data: {
                    current_page: current_page,
                    document_search: document_search,
                    filter_publish_date_start_date: filter_publish_date_start_date,
                    filter_publish_date_end_date: filter_publish_date_end_date,
                    document_category_filter: document_category_filter,
                    department_filter: department_filter,
                    type: type
                },
                success: function(response) {
                    is_loading = false;

                    loadContent.addClass('d-none');

                    if (response.length === 0) {
                        if (current_page === 1) {
                            documentCard.html('<div class="col-lg-12 text-center">No document found.</div>');
                        }
                        return;
                    }

                    response.forEach(function(item) {
                        documentCard.append(item.documentCard);
                    });

                    documentCard.find('.no-search-result').remove();
                },
                error: function(xhr, status, error) {
                    is_loading = false;

                    loadContent.addClass('d-none');

                    var fullErrorMessage = `XHR status: ${status}, Error: ${error}`;
                    if (xhr.responseText) {
                        fullErrorMessage += `, Response: ${xhr.responseText}`;
                    }
                    showErrorDialog(fullErrorMessage);
                }
            });
        }

        function resetAndLoadDocumentCard() {
            page = 1;
            loanDocumentCard(page, false, true);
        }

        function debounceAndReset() {
            debounce(function() {
                resetAndLoadDocumentCard();
            }, 300);
        }

        if (documentCard.length) {
            loanDocumentCard(page, is_loading, true);
            documentSearch.on('keyup', function() {
                debounceAndReset();
            });

            $(document).on('click','#apply-filter',function() {
                debounceAndReset();
            });
        }

        documentSearch.val(lastSearchValue);
        
        if($('#add-document-form').length){
            addDocumentForm();
        }
        
        if($('#document-file-update-form').length){
            updateDocumentFileForm();
        }
        
        if($('#document-update-form').length){
            updateDocumentForm();
        }

        if($('#document-id').length){
            displayDetails('get document details');
        }

        if($('#document-version-history-summary').length){
            documentVersionHistorySummary();
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
                                reloadDatatable('#document-table');
                            }
                            else {
                                if (response.isInactive) {
                                    setNotification('User Inactive', response.message, 'danger');
                                    window.location = 'logout.php?logout';
                                }
                                else if (response.notExist) {
                                    showNotification('Delete Document Error', 'The document does not exist.', 'danger');
                                    reloadDatatable('#document-table');
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
                                        reloadDatatable('#document-table');
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
                                window.location = 'document.php';
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
    });
})(jQuery);

function documentCard(current_page, is_loading){
    if (is_loading) return;
    var document_search = $('#document_search').val();

    is_loading = true;

    const type = 'document card';
            
    $.ajax({
        url: 'view/_document_generation.php',
        method: 'POST',
        dataType: 'json',
        data: {
            current_page : current_page,
            document_search : document_search,
            type : type
        },
        beforeSend: function(){
            $('#load-content').removeClass('d-none');
            if(current_page == 1){
                $('#document-card').html('');
            }
        },
        success: function(response) {
            is_loading = false;

            if (response.length == 0) {
                if(current_page == 1){
                    document.getElementById('document-card').innerHTML = '<div class="col-lg-12 text-center">No document found.</div>';
                }
                return;
            }
            
            response.forEach(function(item) {
                $('#document-card').append(item.documentCard);
            });

            current_page++;
            $('#document-card').find('.no-search-result').remove();
        },
        complete: function(){
            $('#load-content').addClass('d-none');
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

function addDocumentForm(){
    $('#add-document-form').validate({
        rules: {
            document_name: {
                required: true
            },
            document_category_id: {
                required: true
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
                        window.location = 'document.php?id=' + response.documentID;
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
                        window.location = 'document.php?id=' + response.documentID;
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

function updateDocumentForm(){
    $('#document-update-form').validate({
        rules: {
            document_name: {
                required: true
            },
            document_category_id: {
                required: true
            }
        },
        messages: {
            document_name: {
                required: 'Please enter the document name'
            },
            document_category_id: {
                required: 'Please choose the document category'
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
                        window.location = 'document.php?id=' + response.documentID;
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

function documentVersionHistorySummary(){
    const type = 'document version history summary';
    var document_id = $('#document-id').text();
            
    $.ajax({
        url: 'view/_document_generation.php',
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

                        $('#document_name_label').text(response.documentName);
                        $('#document_category_id_label').text(response.documentCategoryName);
                        $('#document_description_label').text(response.documentDescription);
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