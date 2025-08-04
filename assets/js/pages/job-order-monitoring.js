(function($) {
    'use strict';

    $(function() {
        if($('#job-order-monitoring-table').length){
            jobOrderMonitoring('#job-order-monitoring-table');
        }
        
        if($('#job-order-progress-table').length){
            jobOrderProgress('#job-order-progress-table');
        }

        if($('#additional-job-order-progress-table').length){
            additionalJobOrderProgress('#additional-job-order-progress-table');
        }

        if($('#job-order-progress-form').length){
            salesProposalJobOrderProgressForm();
        }

        if($('#additional-job-order-progress-form').length){
            salesProposalAdditionalJobOrderProgressForm();
        }

        if($('#sales-proposal-job-order-cancel-form').length){
            salesProposalJobOrderCancelForm();
        }

        if($('#sales-proposal-additional-job-order-cancel-form').length){
            salesProposalAdditionalJobOrderCancelForm();
        }

        $(document).on('click','.update-sales-proposal-job-order-monitoring',function() {
            const sales_proposal_job_order_id = $(this).data('sales-proposal-job-order-id');
    
            sessionStorage.setItem('sales_proposal_job_order_id', sales_proposal_job_order_id);
            
            displayDetails('get sales proposal job order details');
        });

        $(document).on('click','.update-sales-proposal-additional-job-order-monitoring',function() {
            const sales_proposal_additional_job_order_id = $(this).data('sales-proposal-additional-job-order-id');
    
            sessionStorage.setItem('sales_proposal_additional_job_order_id', sales_proposal_additional_job_order_id);
            
            displayDetails('get sales proposal additional job order details');
        });

        $(document).on('click','.cancel-sales-proposal-job-order-monitoring',function() {
            const sales_proposal_job_order_id = $(this).data('sales-proposal-job-order-id');
    
            sessionStorage.setItem('sales_proposal_job_order_id', sales_proposal_job_order_id);

            resetModalForm('sales-proposal-job-order-cancel-form');
        });

        $(document).on('click','.cancel-sales-proposal-additional-job-order-monitoring',function() {
            const sales_proposal_additional_job_order_id = $(this).data('sales-proposal-additional-job-order-id');
    
            sessionStorage.setItem('sales_proposal_additional_job_order_id', sales_proposal_additional_job_order_id);

            resetModalForm('sales-proposal-additional-job-order-cancel-form');
        });

        $(document).on('click','#print-job-order',function() {
            var checkedBoxes = [];
            var sales_proposal_id = $('#sales_proposal_id').val();

            $('.job-order-checkbox-children').each((index, element) => {
                if ($(element).is(':checked')) {
                    checkedBoxes.push(element.value);
                }
            });

            if(checkedBoxes != ''){
                window.open('print-job-order-list.php?id=' + checkedBoxes + '&sales_proposal_id=' + sales_proposal_id, '_blank');
            }
            else{
                showNotification('Print Job Order Error', 'No selected job order.', 'danger');
            }
        });

        $(document).on('click','#print-job-order-detailed',function() {
            var checkedBoxes = [];
            var sales_proposal_id = $('#sales_proposal_id').val();

            $('.job-order-checkbox-children').each((index, element) => {
                if ($(element).is(':checked')) {
                    checkedBoxes.push(element.value);
                }
            });

            if(checkedBoxes != ''){
                window.open('print-job-order-list-detailed.php?id=' + checkedBoxes + '&sales_proposal_id=' + sales_proposal_id, '_blank');
            }
            else{
                showNotification('Print Job Order Error', 'No selected job order.', 'danger');
            }
        });

        $(document).on('click','#print-additional-job-order',function() {
            var checkedBoxes = [];
            var sales_proposal_id = $('#sales_proposal_id').val();

            $('.additional-job-order-checkbox-children').each((index, element) => {
                if ($(element).is(':checked')) {
                    checkedBoxes.push(element.value);
                }
            });

            if(checkedBoxes != ''){
                window.open('print-additional-job-order-list.php?id=' + checkedBoxes + '&sales_proposal_id=' + sales_proposal_id, '_blank');
            }
            else{
                showNotification('Print Additional Job Order Error', 'No selected additional job order.', 'danger');
            }
        });

        $(document).on('click','#print-additional-job-order-detailed',function() {
            var checkedBoxes = [];
            var sales_proposal_id = $('#sales_proposal_id').val();

            $('.additional-job-order-checkbox-children').each((index, element) => {
                if ($(element).is(':checked')) {
                    checkedBoxes.push(element.value);
                }
            });

            if(checkedBoxes != ''){
                window.open('print-additional-job-order-list-detailed.php?id=' + checkedBoxes + '&sales_proposal_id=' + sales_proposal_id, '_blank');
            }
            else{
                showNotification('Print Additional Job Order Error', 'No selected additional job order.', 'danger');
            }
        });
    });
})(jQuery);

function jobOrderMonitoring(datatable_name, buttons = false, show_all = false){
    const type = 'job order monitoring table';
    var settings;

    const column = [ 
        { 'data' : 'SALES_PROPOSAL_NUMBER' },
        { 'data' : 'CUSTOMER' },
        { 'data' : 'PRODUCT_TYPE' },
        { 'data' : 'PRODUCT' },
        { 'data' : 'PROGRESS' },
        { 'data' : 'STATUS' },
        { 'data' : 'ACTION' }
    ];

    const column_definition = [
        { 'width': 'auto', 'aTargets': 0 },
        { 'width': 'auto', 'aTargets': 1 },
        { 'width': 'auto', 'aTargets': 2 },
        { 'width': 'auto', 'aTargets': 3 },
        { 'width': '10%', 'aTargets': 4 },
        { 'width': '10%', 'aTargets': 5 },
        { 'width': '10%','bSortable': false, 'aTargets': 6 }
    ];

    const length_menu = show_all ? [[-1], ['All']] : [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']];

    settings = {
        'ajax': { 
            'url' : 'view/_sales_proposal_generation.php',
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

function jobOrderProgress(datatable_name, buttons = false, show_all = false){
    const sales_proposal_id = $('#sales-proposal-id').text();
    const type = 'sales proposal job order monitoring table';
    var settings;

    const column = [ 
        { 'data' : 'CHECK_BOX' },
        { 'data' : 'JOB_ORDER' },
        { 'data' : 'COST' },
        { 'data' : 'JOB_COST' },
        { 'data' : 'CONTRACTOR' },
        { 'data' : 'WORK_CENTER' },
        { 'data' : 'PROGRESS' },
        { 'data' : 'PLANNED_START_DATE' },
        { 'data' : 'PLANNED_FINISH_DATE' },
        { 'data' : 'DATE_STARTED' },
        { 'data' : 'COMPLETION_DATE' },
        { 'data' : 'CANCELLATION_DATE' },
        { 'data' : 'CANCELLATION_REASON' },
        { 'data' : 'CANCELLATION_CONFIRMATION' },
        { 'data' : 'BACKJOB' },
        { 'data' : 'REMARKS' },
        { 'data' : 'ACTION' }
    ];

    const column_definition = [
        { 'width': '1%','bSortable': false, 'aTargets': 0 },
        { 'width': 'auto', 'aTargets': 1 },
        { 'width': 'auto', 'aTargets': 2 },
        { 'width': 'auto', 'aTargets': 3 },
        { 'width': 'auto', 'aTargets': 4 },
        { 'width': 'auto', 'aTargets': 5 },
        { 'width': 'auto', 'aTargets': 6 },
        { 'width': 'auto', 'type': 'date', 'aTargets': 7 },
        { 'width': 'auto', 'type': 'date', 'aTargets': 8 },
        { 'width': 'auto', 'type': 'date', 'aTargets': 9 },
        { 'width': 'auto', 'type': 'date', 'aTargets': 10 },
        { 'width': 'auto', 'aTargets': 11 },
        { 'width': 'auto', 'aTargets': 12 },
        { 'width': 'auto', 'aTargets': 13 },
        { 'width': 'auto', 'aTargets': 14 },
        { 'width': 'auto', 'aTargets': 15 },
        { 'width': '15%','bSortable': false, 'aTargets': 16 }
    ];

    const length_menu = show_all ? [[-1], ['All']] : [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']];

    settings = {
        'ajax': { 
            'url' : 'view/_sales_proposal_generation.php',
            'method' : 'POST',
            'dataType': 'json',
            'data': {'type' : type, 'sales_proposal_id' : sales_proposal_id},
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
    }

    if (buttons) {
        settings.dom = "<'row'<'col-sm-3'l><'col-sm-6 text-center mb-2'B><'col-sm-3'f>>" +  "<'row'<'col-sm-12'tr>>" + "<'row'<'col-sm-5'i><'col-sm-7'p>>";
        settings.buttons = ['csv', 'excel', 'pdf'];
    }

    destroyDatatable(datatable_name);

    $(datatable_name).dataTable(settings);
}

function additionalJobOrderProgress(datatable_name, buttons = false, show_all = false){
    const sales_proposal_id = $('#sales-proposal-id').text();
    const type = 'sales proposal additional job order monitoring table';
    var settings;

    const column = [ 
        { 'data' : 'CHECK_BOX' },
        { 'data' : 'JOB_ORDER_NUMBER' },
        { 'data' : 'JOB_ORDER_DATE' },
        { 'data' : 'PARTICULARS' },
        { 'data' : 'COST' },
        { 'data' : 'JOB_COST' },
        { 'data' : 'CONTRACTOR' },
        { 'data' : 'WORK_CENTER' },
        { 'data' : 'PROGRESS' },
        { 'data' : 'PLANNED_START_DATE' },
        { 'data' : 'PLANNED_FINISH_DATE' },
        { 'data' : 'DATE_STARTED' },
        { 'data' : 'COMPLETION_DATE' },
        { 'data' : 'CANCELLATION_DATE' },
        { 'data' : 'CANCELLATION_REASON' },
        { 'data' : 'CANCELLATION_CONFIRMATION' },
        { 'data' : 'BACKJOB' },
        { 'data' : 'REMARKS' },
        { 'data' : 'ACTION' }
    ];

    const column_definition = [
        { 'width': '1%','bSortable': false, 'aTargets': 0 },
        { 'width': 'auto', 'aTargets': 1 },
        { 'width': 'auto', 'type': 'date', 'aTargets': 2 },
        { 'width': 'auto', 'aTargets': 3 },
        { 'width': 'auto', 'aTargets': 4 },
        { 'width': 'auto', 'aTargets': 5 },
        { 'width': 'auto', 'aTargets': 6 },
        { 'width': 'auto', 'aTargets': 7 },
        { 'width': 'auto', 'aTargets': 8 },
        { 'width': 'auto', 'type': 'date', 'aTargets': 9 },
        { 'width': 'auto', 'type': 'date', 'aTargets': 10 },
        { 'width': 'auto', 'type': 'date', 'aTargets': 11 },
        { 'width': 'auto', 'type': 'date', 'aTargets': 12 },
        { 'width': 'auto', 'aTargets': 13 },
        { 'width': 'auto', 'aTargets': 14 },
        { 'width': 'auto', 'aTargets': 15 },
        { 'width': 'auto', 'aTargets': 16 },
        { 'width': 'auto', 'aTargets': 17 },
        { 'width': '15%','bSortable': false, 'aTargets': 18 }
    ];

    const length_menu = show_all ? [[-1], ['All']] : [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']];

    settings = {
        'ajax': { 
            'url' : 'view/_sales_proposal_generation.php',
            'method' : 'POST',
            'dataType': 'json',
            'data': {'type' : type, 'sales_proposal_id' : sales_proposal_id},
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
    }

    if (buttons) {
        settings.dom = "<'row'<'col-sm-3'l><'col-sm-6 text-center mb-2'B><'col-sm-3'f>>" +  "<'row'<'col-sm-12'tr>>" + "<'row'<'col-sm-5'i><'col-sm-7'p>>";
        settings.buttons = ['csv', 'excel', 'pdf'];
    }

    destroyDatatable(datatable_name);

    $(datatable_name).dataTable(settings);
}

function salesProposalJobOrderProgressForm(){
    $('#job-order-progress-form').validate({
        rules: {
            job_order_progress: {
                required: true
            },
            job_order_cost: {
                required: true
            },
            job_cost: {
                required: true
            },
            job_order_backjob: {
                required: true
            },
            job_order_completion_date: {
                required: function () {
                    return $('#job_order_progress').val() == '100';
                }
            }
        },
        messages: {
            job_order_progress: {
                required: 'Please enter the progress'
            },
            job_cost: {
                required: 'Please enter the charge to customer'
            },
            job_order_cost: {
                required: 'Please enter the job order cost'
            },
            job_order_completion_date: {
                required: 'Completion date is required when progress is 100%'
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
            const transaction = 'save sales proposal progress job order';
        
            $.ajax({
                type: 'POST',
                url: 'controller/sales-proposal-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-job-order-progress');
                },
                success: function (response) {
                    if (response.success) {
                        const notificationMessage = 'Update Job Order Progress Success';
                        const notificationDescription = 'The job order progress has been updated successfully';
                        
                        showNotification(notificationMessage, notificationDescription, 'success');
                        reloadDatatable('#job-order-progress-table');
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
                    enableFormSubmitButton('submit-job-order-progress', 'Save');
                    
                    $('#sales-proposal-job-order-monitoring-offcanvas').offcanvas('hide');
                }
            });
        
            return false;
        }
    });
}

function salesProposalAdditionalJobOrderProgressForm(){
    $('#additional-job-order-progress-form').validate({
        rules: {
            additional_job_order_progress: {
                required: true
            },
            additional_job_order_cost: {
                required: true
            },
            additional_job_cost: {
                required: true
            },
            additional_job_order_backjob: {
                required: true
            },
            additional_job_order_completion_date: {
                required: function () {
                    return $('#job_order_progress').val() == '100';
                }
            }
        },
        messages: {
            additional_job_order_progress: {
                required: 'Please enter the progress'
            },
            additional_job_order_cost: {
                required: 'Please enter the charge to customer'
            },
            additional_job_cost: {
                required: 'Please enter the additional job order cost'
            },
            additional_job_order_completion_date: {
                required: 'Completion date is required when progress is 100%'
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
            const transaction = 'save sales proposal progress additional job order';

            var formData = new FormData(form);
            formData.append('sales_proposal_id', sales_proposal_id);
            formData.append('transaction', transaction);

            $.ajax({
                type: 'POST',
                url: 'controller/sales-proposal-controller.php',
                data: formData,
                processData: false,
                contentType: false,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-additional-job-order-progress');
                },
                success: function (response) {
                    if (response.success) {
                        const notificationMessage = 'Update Additional Job Order Progress Success';
                        const notificationDescription = 'The additional job order progress has been updated successfully';
                        
                        showNotification(notificationMessage, notificationDescription, 'success');
                        reloadDatatable('#additional-job-order-progress-table');
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
                    enableFormSubmitButton('submit-additional-job-order-progress', 'Save');
                    $('#sales-proposal-additional-job-order-monitoring-offcanvas').offcanvas('hide');
                }
            });
        
            return false;
        }
    });
}

function displayDetails(transaction){
    switch (transaction) {
        case 'get sales proposal job order details':
            var sales_proposal_job_order_id = sessionStorage.getItem('sales_proposal_job_order_id');
                
            $.ajax({
                url: 'controller/sales-proposal-controller.php',
                method: 'POST',
                dataType: 'json',
                data: {
                    sales_proposal_job_order_id : sales_proposal_job_order_id, 
                    transaction : transaction
                },
                success: function(response) {
                    if (response.success) {                        
                        $('#sales_proposal_job_order_id').val(sales_proposal_job_order_id);
                        $('#job_order_progress').val(response.progress);
                        $('#job_order_cost').val(response.cost);
                        $('#job_cost').val(response.jobCost);
                        $('#job_order_completion_date').val(response.completionDate);
                        $('#job_order_planned_start_date').val(response.plannedStartDate);
                        $('#job_order_planned_finish_date').val(response.plannedFinishDate);
                        $('#job_order_date_started').val(response.dateStarted);
                        $('#job_order_remarks').val(response.remarks);

                        checkOptionExist('#job_order_contractor_id', response.contractorID, '');
                        checkOptionExist('#job_order_work_center_id', response.workCenterID, '');
                    } 
                    else {
                        if(response.isInactive){
                            window.location = 'logout.php?logout';
                        }
                        else{
                            showNotification('Get Sales Proposal Job Order Details Error', response.message, 'danger');
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
            break;
        case 'get sales proposal additional job order details':
            var sales_proposal_additional_job_order_id = sessionStorage.getItem('sales_proposal_additional_job_order_id');
            
            $.ajax({
                url: 'controller/sales-proposal-controller.php',
                method: 'POST',
                dataType: 'json',
                data: {
                    sales_proposal_additional_job_order_id : sales_proposal_additional_job_order_id, 
                    transaction : transaction
                },
                success: function(response) {
                    if (response.success) {
                        
                        $('#sales_proposal_additional_job_order_id').val(sales_proposal_additional_job_order_id);
                        $('#additional_job_order_progress').val(response.progress);
                        $('#additional_job_order_cost').val(response.cost);
                        $('#additional_job_cost').val(response.jobCost);
                        $('#additional_job_remarks').val(response.remarks);
                        $('#additional_job_order_completion_date').val(response.completionDate);
                        $('#additional_job_order_planned_start_date').val(response.plannedStartDate);
                        $('#additional_job_order_planned_finish_date').val(response.plannedFinishDate);
                        $('#additional_job_order_date_started').val(response.dateStarted);

                        checkOptionExist('#additional_job_order_contractor_id', response.contractorID, '');
                        checkOptionExist('#additional_job_order_work_center_id', response.workCenterID, '');
                    } 
                    else {
                        if(response.isInactive){
                            window.location = 'logout.php?logout';
                        }
                        else{
                            showNotification('Get Sales Proposal Additional Job Order Details Error', response.message, 'danger');
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
            break;
    }
}

function salesProposalJobOrderCancelForm(){
    $('#sales-proposal-job-order-cancel-form').validate({
        rules: {
            job_order_cancellation_reason: {
                required: true
            },
            job_order_cancellation_confirmation_image: {
                required: true
            },
        },
        messages: {
            job_order_cancellation_reason: {
                required: 'Please enter the cancellation reason'
            },
            job_order_cancellation_confirmation_image: {
                required: 'Please choose the cancellation confirmation'
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
            var sales_proposal_job_order_id = sessionStorage.getItem('sales_proposal_job_order_id');
            const transaction = 'sales proposal job order cancel';

            var formData = new FormData(form);
            formData.append('sales_proposal_job_order_id', sales_proposal_job_order_id);
            formData.append('transaction', transaction);
        
            $.ajax({
                type: 'POST',
                url: 'controller/sales-proposal-controller.php',
                data: formData,
                processData: false,
                contentType: false,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-sales-proposal-job-order-cancel');
                },
                success: function (response) {
                    if (response.success) {
                        showNotification('Cancel Job Order Success', 'The job order has been cancelled successfully.', 'success');
                        reloadDatatable('#job-order-progress-table');
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
                    enableFormSubmitButton('submit-sales-proposal-job-order-cancel', 'Submit');
                    $('#sales-proposal-job-order-cancel-offcanvas').offcanvas('hide');
                }
            });
        
            return false;
        }
    });
}

function salesProposalAdditionalJobOrderCancelForm(){
    $('#sales-proposal-additional-job-order-cancel-form').validate({
        rules: {
            additional_job_order_cancellation_reason: {
                required: true
            },
            additional_job_order_cancellation_confirmation_image: {
                required: true
            },
        },
        messages: {
            additional_job_order_cancellation_reason: {
                required: 'Please enter the cancellation reason'
            },
            job_order_cancellation_confirmation_image: {
                required: 'Please choose the cancellation confirmation'
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
            var sales_proposal_additional_job_order_id = sessionStorage.getItem('sales_proposal_additional_job_order_id');
            const transaction = 'sales proposal additional job order cancel';
         
            var formData = new FormData(form);
            formData.append('sales_proposal_additional_job_order_id', sales_proposal_additional_job_order_id);
            formData.append('transaction', transaction);
        
            $.ajax({
                type: 'POST',
                url: 'controller/sales-proposal-controller.php',
                data: formData,
                processData: false,
                contentType: false,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-sales-proposal-additional-job-order-cancel');
                },
                success: function (response) {
                    if (response.success) {
                        showNotification('Cancel Additional Job Order Success', 'The additional job order has been cancelled successfully.', 'success');
                        reloadDatatable('#additional-job-order-progress-table');
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
                    enableFormSubmitButton('submit-sales-proposal-additional-job-order-cancel', 'Submit');
                    $('#sales-proposal-additional-job-order-cancel-offcanvas').offcanvas('hide');
                }
            });
        
            return false;
        }
    });
}