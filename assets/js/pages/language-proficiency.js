(function($) {
    'use strict';

    $(function() {
        if($('#language-proficiency-table').length){
            languageProficiencyTable('#language-proficiency-table');
        }

        if($('#language-proficiency-form').length){
            languageProficiencyForm();
        }

        if($('#language-proficiency-id').length){
            displayDetails('get language proficiency details');
        }

        $(document).on('click','.delete-language-proficiency',function() {
            const language_proficiency_id = $(this).data('language-proficiency-id');
            const transaction = 'delete language proficiency';
    
            Swal.fire({
                title: 'Confirm Language Proficiency Deletion',
                text: 'Are you sure you want to delete this language proficiency?',
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
                        url: 'controller/language-proficiency-controller.php',
                        dataType: 'json',
                        data: {
                            language_proficiency_id : language_proficiency_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                showNotification('Delete Language Proficiency Success', 'The language proficiency has been deleted successfully.', 'success');
                                reloadDatatable('#language-proficiency-table');
                            }
                            else {
                                if (response.isInactive) {
                                    setNotification('User Inactive', response.message, 'danger');
                                    window.location = 'logout.php?logout';
                                }
                                else if (response.notExist) {
                                    showNotification('Delete Language Proficiency Error', 'The language proficiency does not exist.', 'danger');
                                    reloadDatatable('#language-proficiency-table');
                                }
                                else {
                                    showNotification('Delete Language Proficiency Error', response.message, 'danger');
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

        $(document).on('click','#delete-language-proficiency',function() {
            let language_proficiency_id = [];
            const transaction = 'delete multiple language proficiency';

            $('.datatable-checkbox-children').each((index, element) => {
                if ($(element).is(':checked')) {
                    language_proficiency_id.push(element.value);
                }
            });
    
            if(language_proficiency_id.length > 0){
                Swal.fire({
                    title: 'Confirm Multiple Language Proficiencies Deletion',
                    text: 'Are you sure you want to delete these language proficiencies?',
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
                            url: 'controller/language-proficiency-controller.php',
                            dataType: 'json',
                            data: {
                                language_proficiency_id: language_proficiency_id,
                                transaction : transaction
                            },
                            success: function (response) {
                                if (response.success) {
                                    showNotification('Delete Language Proficiency Success', 'The selected language proficiencies have been deleted successfully.', 'success');
                                        reloadDatatable('#language-proficiency-table');
                                }
                                else {
                                    if (response.isInactive) {
                                        setNotification('User Inactive', response.message, 'danger');
                                        window.location = 'logout.php?logout';
                                    }
                                    else {
                                        showNotification('Delete Language Proficiency Error', response.message, 'danger');
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
                showNotification('Deletion Multiple Language Proficiency Error', 'Please select the language proficiencies you wish to delete.', 'danger');
            }
        });

        $(document).on('click','#delete-language-proficiency-details',function() {
            const language_proficiency_id = $('#language-proficiency-id').text();
            const transaction = 'delete language proficiency';
    
            Swal.fire({
                title: 'Confirm Language Proficiency Deletion',
                text: 'Are you sure you want to delete this language proficiency?',
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
                        url: 'controller/language-proficiency-controller.php',
                        dataType: 'json',
                        data: {
                            language_proficiency_id : language_proficiency_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                setNotification('Deleted Language Proficiency Success', 'The language proficiency has been deleted successfully.', 'success');
                                window.location = 'language-proficiency.php';
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
                                    showNotification('Delete Language Proficiency Error', response.message, 'danger');
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
            discardCreate('language-proficiency.php');
        });
        
        $(document).on('click','#edit-form',function() {
            displayDetails('get language proficiency details');

            enableForm();
        });

        $(document).on('click','#duplicate-language-proficiency',function() {
            const language_proficiency_id = $('#language-proficiency-id').text();
            const transaction = 'duplicate language proficiency';
    
            Swal.fire({
                title: 'Confirm Language Proficiency Duplication',
                text: 'Are you sure you want to duplicate this language proficiency?',
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
                        url: 'controller/language-proficiency-controller.php',
                        dataType: 'json',
                        data: {
                            language_proficiency_id : language_proficiency_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                setNotification('Duplicate Language Proficiency Success', 'The language proficiency has been duplicated successfully.', 'success');
                                window.location = 'language-proficiency.php?id=' + response.languageProficiencyID;
                            }
                            else {
                                if (response.isInactive) {
                                    setNotification('User Inactive', response.message, 'danger');
                                    window.location = 'logout.php?logout';
                                }
                                else if (response.notExist) {
                                    showNotification('Duplicate Language Proficiency Error', 'The language proficiency does not exist.', 'danger');
                                    reloadDatatable('#language-proficiency-table');
                                }
                                else {
                                    showNotification('Duplicate Language Proficiency Error', response.message, 'danger');
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

        $(document).on('click','#create-language-proficiency',function() {
            resetForm("language-proficiency-form");

            $('#language-proficiency-modal').modal('show');
        });
    });
})(jQuery);

function languageProficiencyTable(datatable_name, buttons = false, show_all = false){
    const type = 'language proficiency table';
    var settings;

    const column = [ 
        { 'data' : 'CHECK_BOX' },
        { 'data' : 'LANGUAGE_PROFICIENCY_NAME' },
        { 'data' : 'ACTION' }
    ];

    const column_definition = [
        { 'width': '2%','bSortable': false, 'aTargets': 0 },
        { 'width': '82%', 'aTargets': 1 },
        { 'width': '15%','bSortable': false, 'aTargets': 2 }
    ];

    const length_menu = show_all ? [[-1], ['All']] : [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']];

    settings = {
        'ajax': { 
            'url' : 'view/_language_proficiency_generation.php',
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

function languageProficiencyForm(){
    $('#language-proficiency-form').validate({
        rules: {
            language_proficiency_name: {
                required: true
            },
            description: {
                required: true
            }
        },
        messages: {
            language_proficiency_name: {
                required: 'Please enter the language proficiency name'
            },
            description: {
                required: 'Please enter the description'
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
            const language_proficiency_id = $('#language-proficiency-id').text();
            const transaction = 'save language proficiency';
        
            $.ajax({
                type: 'POST',
                url: 'controller/language-proficiency-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction + '&language_proficiency_id=' + language_proficiency_id,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-data');
                },
                success: function (response) {
                    if (response.success) {
                        const notificationMessage = response.insertRecord ? 'Insert Language Proficiency Success' : 'Update Language Proficiency Success';
                        const notificationDescription = response.insertRecord ? 'The language proficiency has been inserted successfully.' : 'The language proficiency has been updated successfully.';
                        
                        setNotification(notificationMessage, notificationDescription, 'success');
                        window.location = 'language-proficiency.php?id=' + response.languageProficiencyID;
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
                    enableFormSubmitButton('submit-data', 'Submit');
                }
            });
        
            return false;
        }
    });
}

function displayDetails(transaction){
    switch (transaction) {
        case 'get language proficiency details':
            const language_proficiency_id = $('#language-proficiency-id').text();
            
            $.ajax({
                url: 'controller/language-proficiency-controller.php',
                method: 'POST',
                dataType: 'json',
                data: {
                    language_proficiency_id : language_proficiency_id, 
                    transaction : transaction
                },
                success: function(response) {
                    if (response.success) {
                        $('#language_proficiency_id').val(language_proficiency_id);
                        $('#language_proficiency_name').val(response.languageProficiencyName);
                        $('#description').val(response.description);
                        
                        $('#language_proficiency_name_label').text(response.languageProficiencyName);
                        $('#description_label').text(response.description);
                    } 
                    else {
                        if(response.isInactive){
                            window.location = 'logout.php?logout';
                        }
                        else{
                            showNotification('Get Language Proficiency Details Error', response.message, 'danger');
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