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

        if(leasing_application_status == 'Draft'){
            if($('#leasing-application-form').length){
                //leasingApplicationForm();
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

        if(leasing_application_status != 'Draft'){
            if($('#last-step').length){
                $("#last-step")[0].click();
            }
        }

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
        { 'data' : 'SALES_PROPOSAL_NUMBER' },
        { 'data' : 'CUSTOMER' },
        { 'data' : 'PRODUCT_TYPE' },
        { 'data' : 'PRODUCT' },
        { 'data' : 'STATUS' },
        { 'data' : 'ACTION' }
    ];

    const column_definition = [
        { 'width': '1%','bSortable': false, 'aTargets': 0 },
        { 'width': '14%', 'aTargets': 1 },
        { 'width': '25%', 'aTargets': 2 },
        { 'width': '15%', 'aTargets': 3 },
        { 'width': '25%', 'aTargets': 4 },
        { 'width': '10%', 'aTargets': 5 },
        { 'width': '10%','bSortable': false, 'aTargets': 6 }
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
            start_date: {
                required: 'Please choose the start date'
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
                },
                complete: function() {
                    displayDetails('get leasing application basic details');
                }
            });
        
            return false;
        }
    });
}

function displayDetails(transaction){
    switch (transaction) {
        case 'get leasing application basic details':
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
                        $('#summary-leasing-application-number').text(response.leasingApplicationNumber);

                        $('#financing_institution').val(response.financingInstitution);
                        $('#referred_by').val(response.referredBy);
                        $('#commission_amount').val(response.commissionAmount);
                        $('#release_date').val(response.releaseDate);
                        $('#start_date').val(response.startDate);
                        $('#term_length').val(response.termLength);
                        $('#number_of_payments').val(response.numberOfPayments);
                        $('#first_due_date').val(response.firstDueDate);
                        $('#remarks').val(response.remarks);                        
                        $('#dr_number').val(response.drNumber);
                        $('#release_to').val(response.releaseTo);
                        $('#actual_start_date').val(response.actualStartDate);

                        checkOptionExist('#renewal_tag', response.renewalTag, '');
                        checkOptionExist('#product_type', response.productType, '');
                        checkOptionExist('#transaction_type', response.transactionType, '');
                        checkOptionExist('#comaker_id', response.comakerID, '');
                        checkOptionExist('#term_type', response.termType, '');
                        checkOptionExist('#payment_frequency', response.paymentFrequency, '');
                        checkOptionExist('#initial_approving_officer', response.initialApprovingOfficer, '');
                        checkOptionExist('#final_approving_officer', response.finalApprovingOfficer, '');

                        $('#summary-referred-by').text(response.referredBy);
                        $('#summary-release-date').text(response.releaseDate);
                        $('#summary-product-type').text(response.productType);
                        $('#summary-transaction-type').text(response.transactionType);
                        $('#summary-term').text(response.termLength + ' ' + response.termType);
                        $('#summary-no-payments').text(response.numberOfPayments);
                        $('#summary-remarks').text(response.remarks);
                        $('#summary-initial-approval-by').text(response.initialApprovingOfficerName);
                        $('#summary-final-approval-by').text(response.finalApprovingOfficerName);
                        $('#summary-created-by').text(response.createdByName);

                        $('#initial_approval_remarks_label').text(response.initialApprovalRemarks);
                        $('#final_approval_remarks_label').text(response.finalApprovalRemarks);
                        $('#rejection_reason_label').text(response.rejectionReason);
                        $('#cancellation_reason_label').text(response.cancellationReason);
                        $('#set_to_draft_reason_label').text(response.setToDraftReason);
                        $('#release_remarks_label').text(response.releaseRemarks);
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
                    displayDetails('get leasing application unit details');
                    displayDetails('get leasing application fuel details');
                    displayDetails('get leasing application refinancing details');

                    if($('#leasing-application-tab-12').length){
                        displayDetails('get comaker details');
                    }

                    displayDetails('get leasing application pricing computation details');
                    displayDetails('get leasing application other charges details');
                    displayDetails('get leasing application renewal amount details');
                    displayDetails('get leasing application confirmation details');
                    
                    calculateRenewalAmount();
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

function traverseTabs(direction) {
    var activeTab = $('.nav-link.active');
    var currentTabId = activeTab.attr('href');
    var currentIndex = $('.nav-link').index(activeTab);
    var nextIndex;
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