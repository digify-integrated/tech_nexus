(function($) {
    'use strict';

    $(function() {
        var leasing_application_status = $('#leasing_application_status').val();
        
        if($('#leasing-application-table').length){
            leasingApplicationTable('#leasing-application-table');
        }
        
        if($('#add-leasing-application-form').length){
            addLeasingApplicationForm();
        }

        if($('#leasing-application-contact-form').length){
            leasingApplicationContractImageForm();
        }

        if($('#leasing-application-reject-form').length){
            leasingApplicationRejectForm();
        }
        
        if($('#leasing-application-cancel-form').length){
            leasingApplicationCancelForm();
        }
        
        if($('#leasing-application-set-to-draft-form').length){
            leasingApplicationSetToDraftForm();
        }

        if($('#leasing-application-approval-form').length){
            leasingApplicationApprovalForm();
        }

        if($('#leasing-application-activation-form').length){
            leasingApplicationActivationForm();
        }

        if($('#leasing-application-id').length){
            displayDetails('get leasing application details');
        }

        if(leasing_application_status == 'Draft'){
            if($('#leasing-application-form').length){
                leasingApplicationForm();
            }
        }
        else{
            if($('#leasing-application-form').length){
                disableFormAndSelect2('leasing-application-form');
            }
        }

        $(document).on('blur','#start_date',function() {
            calculateMaturityDate();
        });

        $(document).on('change','#term_length',function() {
            calculateMaturityDate();
        });

        $(document).on('change','#term_type',function() {
            calculateMaturityDate();
        });

        $(document).on('change','#payment_frequency',function() {
            calculateMaturityDate();
        });

        $(document).on('click','#tag-for-approval',function() {
            const leasing_application_id = $('#leasing-application-id').text();
            const transaction = 'tag for approval';
    
            Swal.fire({
                title: 'Confirm Tagging of Leasing Application For Initial Approval',
                text: 'Are you sure you want to tag this leasing application for approval?',
                icon: 'warning',
                showCancelButton: !0,
                confirmButtonText: 'For Approval',
                cancelButtonText: 'Cancel',
                confirmButtonClass: 'btn btn-warning mt-2',
                cancelButtonClass: 'btn btn-secondary ms-2 mt-2',
                buttonsStyling: !1
            }).then(function(result) {
                if (result.value) {
                    $.ajax({
                        type: 'POST',
                        url: 'controller/leasing-application-controller.php',
                        dataType: 'json',
                        data: {
                            leasing_application_id : leasing_application_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                setNotification('Tag Leasing Application For Approval Success', 'The leasing application has been tagged for approval successfully.', 'success');
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
                                else {
                                    showNotification('Tag Leasing Application For Approval Error', response.message, 'danger');
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

        $(document).on('click','#generate-schedule',function() {
            const leasing_application_id = $('#leasing-application-id').text();
            const transaction = 'generate schedule';
    
            Swal.fire({
                title: 'Confirm Leasing Application Schedule Generation',
                text: 'Are you sure you want to generate the schedule of this leasing application?',
                icon: 'warning',
                showCancelButton: !0,
                confirmButtonText: 'Generate',
                cancelButtonText: 'Cancel',
                confirmButtonClass: 'btn btn-success mt-2',
                cancelButtonClass: 'btn btn-secondary ms-2 mt-2',
                buttonsStyling: !1
            }).then(function(result) {
                if (result.value) {
                    $.ajax({
                        type: 'POST',
                        url: 'controller/leasing-application-controller.php',
                        dataType: 'json',
                        data: {
                            leasing_application_id : leasing_application_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                setNotification('Leasing Application Schedule Generation Success', 'The schedule of this leasing application has been generated successfully.', 'success');
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
                                else {
                                    showNotification('Leasing Application Schedule Generation Error', response.message, 'danger');
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

        $(document).on('click','#next-step',function() {
            traverseTabs('next');
        });

        $(document).on('click','#previous-step',function() {
            traverseTabs('previous');
        });

        $(document).on('click','#first-step',function() {
            traverseTabs('first');
        });

        $(document).on('click','#last-step',function() {
            traverseTabs('last');
        });

        $(document).on('click','#last-step2',function() {
            traverseTabs('last');
        });

        $(document).on('click','#apply-filter',function() {
            if($('#leasing-application-table').length){
                leasingApplicationTable('#leasing-application-table');
            }
    
            if($('#all-leasing-application-table').length){
                allLeasingApplicationTable('#all-leasing-application-table');
            }
        });
    });
})(jQuery);

function leasingApplicationTable(datatable_name, buttons = false, show_all = false){
    const type = 'leasing application table';
    var customer_id = $('#customer_id').val();
    var leasing_application_status_filter = $('.leasing-application-status-filter:checked').val();

    var settings;

    const column = [ 
        { 'data' : 'CHECK_BOX' },
        { 'data' : 'LEASING_APPLICATION_NUMBER' },
        { 'data' : 'TENANT_NAME' },
        { 'data' : 'PROPERTY_NAME' },
        { 'data' : 'STATUS' },
        { 'data' : 'ACTION' }
    ];

    const column_definition = [
        { 'width': '1%','bSortable': false, 'aTargets': 0 },
        { 'width': '14%', 'aTargets': 1 },
        { 'width': '25%', 'aTargets': 2 },
        { 'width': '25%', 'aTargets': 3 },
        { 'width': '25%', 'aTargets': 4 },
        { 'width': '10%','bSortable': false, 'aTargets': 5 }
    ];

    const length_menu = show_all ? [[-1], ['All']] : [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']];

    settings = {
        'ajax': { 
            'url' : 'view/_leasing_application_generation.php',
            'method' : 'POST',
            'dataType': 'json',
            'data': {'type' : type, 'customer_id' : customer_id, 'leasing_application_status_filter' : leasing_application_status_filter},
            'dataSrc' : '',
            'error': function(xhr, status, error) {
                var fullErrorMessage = `XHR status: ${status}, Error: ${error}`;
                if (xhr.responseText) {
                    fullErrorMessage += `, Response: ${xhr.responseText}`;
                }
                showErrorDialog(fullErrorMessage);
            }
        },
        'order': [[ 1, 'desc' ]],
        'columns' : column,
        'columnDefs': column_definition,
        'lengthMenu': length_menu,
        'language': {
            'emptyTable': 'No data found',
            'searchPlaceholder': 'Search...',
            'search': '',
            'loadingRecords': 'Just a moment while we fetch your data...'
        }
    }

    if (buttons) {
        settings.dom = "<'row'<'col-sm-3'l><'col-sm-6 text-center mb-2'B><'col-sm-3'f>>" +  "<'row'<'col-sm-12'tr>>" + "<'row'<'col-sm-5'i><'col-sm-7'p>>";
        settings.buttons = ['csv', 'excel', 'pdf'];
    }

    destroyDatatable(datatable_name);

    $(datatable_name).dataTable(settings);
}

function addLeasingApplicationForm(){
    $('#add-leasing-application-form').validate({
        rules: {
            renewal_tag: {
                required: true
            },
            tenant_id: {
                required: true
            },
            property_id: {
                required: true
            },
            term_length: {
                required: true
            },
            term_type: {
                required: true
            },
            payment_frequency: {
                required: true
            },
            contract_date: {
                required: true
            },
            start_date: {
                required: true
            },
            initial_basic_rental: {
                required: true
            },
            escalation_rate: {
                required: true
            },
            security_deposit: {
                required: true
            },
            floor_area: {
                required: true
            },
        },
        messages: {
            renewal_tag: {
                required: 'Please choose the renewal tag'
            },
            tenant_id: {
                required: 'Please choose the tenant'
            },
            property_id: {
                required: 'Please choose the property'
            },
            term_length: {
                required: 'Please enter the term'
            },
            term_type: {
                required: 'Please choose the term type'
            },
            payment_frequency: {
                required: 'Please choose the payment frequency'
            },
            contract_date: {
                required: 'Please choose the contract effectivity date'
            },
            start_date: {
                required: 'Please choose the first rental due date'
            },
            initial_basic_rental: {
                required: 'Please enter the initial basic rental'
            },
            escalation_rate: {
                required: 'Please enter the escalation rate'
            },
            security_deposit: {
                required: 'Please enter the security deposit'
            },
            floor_area: {
                required: 'Please enter the floor area'
            },
        },
        errorPlacement: function (error, element) {
            if (element.hasClass('select2') || element.hasClass('modal-select2')) {
              error.insertAfter(element.next('.select2-container'));
            }
            else if (element.hasClass('form-check-input')) {
                error.insertAfter(element.closest('.form-check-inline'));
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
            const leasing_application_id = $('#leasing-application-id').text();
            const customer_id = $('#customer-id').text();
            const transaction = 'save leasing application';
        
            $.ajax({
                type: 'POST',
                url: 'controller/leasing-application-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction + '&leasing_application_id=' + leasing_application_id + '&customer_id=' + customer_id,
                dataType: 'json',
                success: function (response) {
                    if (response.success) {
                        if(response.insertRecord){
                            setNotification('Insert Leasing Application Success', 'The leasing application has been inserted successfully.', 'success');
                            window.location = 'leasing-application.php?customer='+ response.customerID +'&id=' + response.leasingApplicationID;
                        }
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
                    var fullErrorMessage = 'XHR status: ${status}, Error: ${error}';
                    if (xhr.responseText) {
                        fullErrorMessage += ', Response: ${xhr.responseText}';
                    }
                    showErrorDialog(fullErrorMessage);
                }
            });
        
            return false;
        }
    });
}

function leasingApplicationForm(){
    $('#leasing-application-form').validate({
        rules: {
            renewal_tag: {
                required: true
            },
            tenant_id: {
                required: true
            },
            property_id: {
                required: true
            },
            term_length: {
                required: true
            },
            term_type: {
                required: true
            },
            payment_frequency: {
                required: true
            },
            contract_date: {
                required: true
            },
            start_date: {
                required: true
            },
            initial_basic_rental: {
                required: true
            },
            escalation_rate: {
                required: true
            },
            security_deposit: {
                required: true
            },
            floor_area: {
                required: true
            },
        },
        messages: {
            renewal_tag: {
                required: 'Please choose the renewal tag'
            },
            tenant_id: {
                required: 'Please choose the tenant'
            },
            property_id: {
                required: 'Please choose the property'
            },
            term_length: {
                required: 'Please enter the term'
            },
            term_type: {
                required: 'Please choose the term type'
            },
            payment_frequency: {
                required: 'Please choose the payment frequency'
            },
            contract_date: {
                required: 'Please choose the contract effectivity date'
            },
            start_date: {
                required: 'Please choose the first rental due date'
            },
            initial_basic_rental: {
                required: 'Please enter the initial basic rental'
            },
            escalation_rate: {
                required: 'Please enter the escalation rate'
            },
            security_deposit: {
                required: 'Please enter the security deposit'
            },
            floor_area: {
                required: 'Please enter the floor area'
            },
        },
        errorPlacement: function (error, element) {
            if (element.hasClass('select2') || element.hasClass('modal-select2')) {
              error.insertAfter(element.next('.select2-container'));
            }
            else if (element.hasClass('form-check-input')) {
                error.insertAfter(element.closest('.form-check-inline'));
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
            const leasing_application_id = $('#leasing-application-id').text();
            const customer_id = $('#customer-id').text();
            const transaction = 'save leasing application';
        
            $.ajax({
                type: 'POST',
                url: 'controller/leasing-application-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction + '&leasing_application_id=' + leasing_application_id + '&customer_id=' + customer_id,
                dataType: 'json',
                success: function (response) {
                    if (!response.success) {
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
                    var fullErrorMessage = 'XHR status: ${status}, Error: ${error}';
                    if (xhr.responseText) {
                        fullErrorMessage += ', Response: ${xhr.responseText}';
                    }
                    showErrorDialog(fullErrorMessage);
                },
                complete: function(){
                    displayDetails('get leasing application details');
                }
            });
        
            return false;
        }
    });
}

function leasingApplicationContractImageForm(){
    $('#leasing-application-contact-form').validate({
        rules: {
            contract_image: {
                required: true
            },
        },
        messages: {
            contract_image: {
                required: 'Please choose the contract image'
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
            const leasing_application_id = $('#leasing-application-id').text();
            const transaction = 'save leasing application contract image';
    
            var formData = new FormData(form);
            formData.append('leasing_application_id', leasing_application_id);
            formData.append('transaction', transaction);
        
            $.ajax({
                type: 'POST',
                url: 'controller/leasing-application-controller.php',
                data: formData,
                processData: false,
                contentType: false,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-leasing-application-contact');
                },
                success: function (response) {
                    if (response.success) {
                        setNotification('Contact Image Upload Success', 'The contract image has been uploaded successfully', 'success');
                        window.location.reload();
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
                    enableFormSubmitButton('submit-leasing-application-contact', 'Submit');
                }
            });
        
            return false;
        }
    });
}

function leasingApplicationRejectForm(){
    $('#leasing-application-reject-form').validate({
        rules: {
            rejection_reason: {
                required: true
            }
        },
        messages: {
            rejection_reason: {
                required: 'Please enter the rejection reason'
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
            const leasing_application_id = $('#leasing-application-id').text();
            const transaction = 'leasing application reject';
        
            $.ajax({
                type: 'POST',
                url: 'controller/leasing-application-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction + '&leasing_application_id=' + leasing_application_id,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-leasing-application-reject');
                },
                success: function (response) {
                    if (response.success) {
                        showNotification('Reject Leasing Application Success', 'The leasing application has been rejected successfully.', 'success');
                        window.location.reload();
                    }
                    else{
                        if (response.isInactive) {
                            setNotification('User Inactive', response.message, 'danger');
                            window.location = 'logout.php?logout';
                        } else {
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
                    enableFormSubmitButton('submit-leasing-application-reject', 'Submit');
                    $('#leasing-application-reject-offcanvas').offcanvas('hide');
                }
            });
        
            return false;
        }
    });
}

function leasingApplicationCancelForm(){
    $('#leasing-application-cancel-form').validate({
        rules: {
            cancellation_reason: {
                required: true
            }
        },
        messages: {
            cancellation_reason: {
                required: 'Please enter the cancellation reason'
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
            const leasing_application_id = $('#leasing-application-id').text();
            const transaction = 'leasing application cancel';
        
            $.ajax({
                type: 'POST',
                url: 'controller/leasing-application-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction + '&leasing_application_id=' + leasing_application_id,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-leasing-application-cancel');
                },
                success: function (response) {
                    if (response.success) {
                        showNotification('Cancel Leasing Application Success', 'The leasing application has been cancelled successfully.', 'success');
                        window.location.reload();
                    }
                    else{
                        if (response.isInactive) {
                            setNotification('User Inactive', response.message, 'danger');
                            window.location = 'logout.php?logout';
                        } else {
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
                    enableFormSubmitButton('submit-leasing-application-cancel', 'Submit');
                    $('#leasing-application-cancel-offcanvas').offcanvas('hide');
                }
            });
        
            return false;
        }
    });
}

function leasingApplicationSetToDraftForm(){
    $('#leasing-application-set-to-draft-form').validate({
        rules: {
            set_to_draft_reason: {
                required: true
            }
        },
        messages: {
            set_to_draft_reason: {
                required: 'Please enter the set to draft reason'
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
            const leasing_application_id = $('#leasing-application-id').text();
            const transaction = 'leasing application set to draft';
        
            $.ajax({
                type: 'POST',
                url: 'controller/leasing-application-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction + '&leasing_application_id=' + leasing_application_id,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-leasing-application-set-to-draft');
                },
                success: function (response) {
                    if (response.success) {
                        showNotification('Set Leasing Application To Draft Success', 'The leasing application has been set to draft successfully.', 'success');
                        window.location.reload();
                    }
                    else{
                        if (response.isInactive) {
                            setNotification('User Inactive', response.message, 'danger');
                            window.location = 'logout.php?logout';
                        } else {
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
                    enableFormSubmitButton('submit-leasing-application-set-to-draft', 'Submit');
                    $('#leasing-application-set-to-draft-offcanvas').offcanvas('hide');
                }
            });
        
            return false;
        }
    });
}

function leasingApplicationApprovalForm(){
    $('#leasing-application-approval-form').validate({
        rules: {
            approval_remarks: {
                required: true
            }
        },
        messages: {
            approval_remarks: {
                required: 'Please enter the approval remarks'
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
            const leasing_application_id = $('#leasing-application-id').text();
            const transaction = 'leasing application approval';
        
            $.ajax({
                type: 'POST',
                url: 'controller/leasing-application-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction + '&leasing_application_id=' + leasing_application_id,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-leasing-application-approve');
                },
                success: function (response) {
                    if (response.success) {
                        showNotification('Approve Leasing Application Success', 'The leasing application has been approved successfully.', 'success');
                        window.location.reload();
                    }
                    else{
                        if (response.isInactive) {
                            setNotification('User Inactive', response.message, 'danger');
                            window.location = 'logout.php?logout';
                        } else {
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
                    enableFormSubmitButton('submit-leasing-application-approve', 'Submit');
                    $('#leasing-application-final-approval-offcanvas').offcanvas('hide');
                }
            });
        
            return false;
        }
    });
}

function leasingApplicationActivationForm(){
    $('#leasing-application-activation-form').validate({
        rules: {
            activation_remarks: {
                required: true
            }
        },
        messages: {
            activation_remarks: {
                required: 'Please enter the activation remarks'
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
            const leasing_application_id = $('#leasing-application-id').text();
            const transaction = 'leasing application activation';
        
            $.ajax({
                type: 'POST',
                url: 'controller/leasing-application-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction + '&leasing_application_id=' + leasing_application_id,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-leasing-application-activate');
                },
                success: function (response) {
                    if (response.success) {
                        showNotification('Activate Leasing Application Success', 'The leasing application has been activated successfully.', 'success');
                        window.location.reload();
                    }
                    else{
                        if (response.isInactive) {
                            setNotification('User Inactive', response.message, 'danger');
                            window.location = 'logout.php?logout';
                        } else {
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
                    enableFormSubmitButton('submit-leasing-application-activate', 'Submit');
                    $('#leasing-application-final-activation-offcanvas').offcanvas('hide');
                }
            });
        
            return false;
        }
    });
}

function displayDetails(transaction){
    switch (transaction) {
        case 'get leasing application details':
            var leasing_application_id = $('#leasing-application-id').text();
            
            $.ajax({
                url: 'controller/leasing-application-controller.php',
                method: 'POST',
                dataType: 'json',
                data: {
                    leasing_application_id : leasing_application_id, 
                    transaction : transaction
                },
                success: function(response) {
                    if (response.success) {
                        $('#leasing_application_number').text(response.leasingApplicationNumber);

                        $('#financing_institution').val(response.financingInstitution);
                        $('#term_length').val(response.termLength);
                        $('#remarks').val(response.remarks);
                        $('#security_deposit').val(response.securityDeposit);
                        $('#floor_area').val(response.floorArea);
                        $('#initial_basic_rental').val(response.initialBasicRental);
                        $('#escalation_rate').val(response.escalationRate);
                        $('#contract_date').val(response.contractDate);
                        $('#start_date').val(response.startDate);

                        checkOptionExist('#tenant_id', response.tenantID, '');
                        checkOptionExist('#property_id', response.propertyID, '');
                        checkOptionExist('#term_type', response.termType, '');
                        checkOptionExist('#payment_frequency', response.paymentFrequency, '');
                        checkOptionExist('#renewal_tag', response.renewalTag, '');

                        $('#initial_approval_remarks_label').text(response.approvalRemarks);
                        $('#activation_remarks_label').text(response.activationRemarks);
                        $('#set_to_draft_reason_label').text(response.setToDraftReason);
                        $('#rejection_reason_label').text(response.rejectionReason);
                        $('#cancellation_reason_label').text(response.cancellationReason);

                        if($('#contract-image').length){
                            document.getElementById('contract-image').src = response.contractImage;
                        }
                    } 
                    else {
                        if(response.isInactive){
                            window.location = 'logout.php?logout';
                        }
                        else{
                            showNotification('Get Leasing Application Details Error', response.message, 'danger');
                        }
                    }
                },
                error: function(xhr, status, error) {
                    var fullErrorMessage = 'XHR status: ${status}, Error: ${error}';
                    if (xhr.responseText) {
                        fullErrorMessage += ', Response: ${xhr.responseText}';
                    }
                    showErrorDialog(fullErrorMessage);
                },
                complete: function(){
                    calculateMaturityDate();
                    leasingApplicationRepaymentTable();
                }
            });
            break;
        
    }
}

function calculateMaturityDate(){
    var start_date = $('#start_date').val();
    var term_length = $('#term_length').val();
    var term_type = $('#term_type').val();
    var payment_frequency = $('#payment_frequency').val();

    $.ajax({
        type: 'POST',
        url: './config/calculate_maturity_date.php',
        data: { 
            start_date: start_date, 
            payment_frequency: payment_frequency, 
            term_type: term_type, 
            term_length: term_length 
        },
        success: function (result) {
            $('#maturity_date').val(result);
        }
    });
}

function leasingApplicationRepaymentTable(){
    const leasing_application_id = $('#leasing-application-id').text();
    const type = 'repayment table';

    $.ajax({
        type: "POST",
        url: "view/_leasing_application_generation.php",
        dataType: 'json',
        data: { type: type, leasing_application_id: leasing_application_id },
        success: function (result) {
            if($('#repayment-table').length){
                document.getElementById('repayment-table').innerHTML = result[0].table;
            }
        }
    });
}

function traverseTabs(direction) {
    var activeTab = $('.nav-link.active');
    var currentTabId = activeTab.attr('href');
    var currentIndex = $('.nav-link').index(activeTab);
    var nextIndex;
    var leasing_application_status = $('#leasing_application_status').val();
    var totalTabs = $('.nav-link').length;

    var visibleTabs = $('.nav-link:not(.d-none)').length;

    function findNextVisibleIndex(startIndex, direction) {
        var index = startIndex;
        var increment = direction === 'next' ? 1 : -1;
        while (true) {
            index = (index + increment + totalTabs) % totalTabs;
            if (!$('.nav-link:eq(' + index + ')').hasClass('d-none')) {
                return index;
            }
            if (index === startIndex) {
                break;
            }
        }
        return startIndex;
    }

    if (direction === 'next') {
        nextIndex = findNextVisibleIndex(currentIndex, 'next');
    } else if (direction === 'previous') {
        nextIndex = findNextVisibleIndex(currentIndex, 'previous');
    } else if (direction === 'first') {
        nextIndex = 0;
    } else if (direction === 'last') {
        nextIndex = totalTabs - 1;
    }

    var nextTabId = $('.nav-link:eq(' + nextIndex + ')').attr('href');

    if (nextTabId !== undefined) {
        if (nextIndex === 0) {
            $('#first-step').addClass('disabled');
            $('#previous-step').addClass('disabled');
        } else {
            $('#first-step').removeClass('disabled');
            $('#previous-step').removeClass('disabled');
        }

        if (nextIndex === totalTabs - 1) {
            $('#last-step').addClass('disabled');
            $('#next-step').addClass('disabled');
        } else {
            $('#last-step').removeClass('disabled');
            $('#next-step').removeClass('disabled');
        }

        if(leasing_application_status == 'Draft'){
            if (currentIndex == 0) {
                if ($('#leasing-application-form').valid()) {
                    $('#leasing-application-form').submit();
                } else {
                    return;
                }
            }
        }

        $('.nav-link[href="' + nextTabId + '"]').tab('show');

        // Calculate width of the progress bar based on visible tabs
        var progressBarWidth = ((nextIndex + 1) / visibleTabs) * 100;
        $('#bar .progress-bar').css('width', progressBarWidth + '%');
    }
}

function disableFormAndSelect2(formId) {
    // Disable all form elements
    var form = document.getElementById(formId);
    var elements = form.elements;
    for (var i = 0; i < elements.length; i++) {
        elements[i].disabled = true;
    }

    // Disable Select2 dropdowns
    var select2Dropdowns = form.getElementsByClassName('select2');
    for (var j = 0; j < select2Dropdowns.length; j++) {
        var select2Instance = $(select2Dropdowns[j]);
        select2Instance.select2('destroy');
        select2Instance.prop('disabled', true);
    }
}