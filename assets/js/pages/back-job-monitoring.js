(function($) {
    'use strict';

    $(function() {
        if($('#backjob-monitoring-table').length){
            backJobMonitoringTable('#backjob-monitoring-table');
        }

        if($('#job-order-progress-table').length){
            jobOrderProgress('#job-order-progress-table');
        }

        if($('#additional-job-order-progress-table').length){
            additionalJobOrderProgress('#additional-job-order-progress-table');
        }

        if($('#backjob-monitoring-form').length){
            backJobMonitoringForm();
        }

        if($('#backjob-monitoring-id').length){
            displayDetails('get backjob monitoring details');

            if($('#status').val() != 'Draft'){
                disableFormAndSelect2('backjob-monitoring-form');
            }
        }

        if($('#job-order-progress-form').length){
            JobOrderProgressForm();
        }

        if($('#additional-job-order-progress-form').length){
            AdditionalJobOrderProgressForm();
        }

        if($('#tag-as-released-form').length){
            ReleaseForm();
        }

        if($('#tag-as-cancelled-form').length){
            CancelForm();
        }

        if($('#unit-image-form').length){
            UnitImageForm();
        }

        if($('#outgoing-checklist-form').length){
            OutgoingChecklistForm();
        }

        if($('#quality-control-form-form').length){
            QualityControlFormForm();
        }

        $(document).on('click','.delete-backjob-monitoring',function() {
            const backjob_monitoring_id = $(this).data('backjob-monitoring-id');
            const transaction = 'delete backjob monitoring';
    
            Swal.fire({
                title: 'Confirm Backjob Monitoring Deletion',
                text: 'Are you sure you want to delete this backjob monitoring?',
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
                        url: 'controller/backjob-monitoring-controller.php',
                        dataType: 'json',
                        data: {
                            backjob_monitoring_id : backjob_monitoring_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                showNotification('Delete Backjob Monitoring Success', 'The backjob monitoring has been deleted successfully.', 'success');
                                reloadDatatable('#backjob-monitoring-table');
                            }
                            else {
                                if (response.isInactive) {
                                    setNotification('User Inactive', response.message, 'danger');
                                    window.location = 'logout.php?logout';
                                }
                                else if (response.notExist) {
                                    showNotification('Delete Backjob Monitoring Error', 'The backjob monitoring does not exist.', 'danger');
                                    reloadDatatable('#backjob-monitoring-table');
                                }
                                else {
                                    showNotification('Delete Backjob Monitoring Error', response.message, 'danger');
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

        $(document).on('click','#delete-backjob-monitoring',function() {
            let backjob_monitoring_id = [];
            const transaction = 'delete multiple backjob monitoring';

            $('.datatable-checkbox-children').each((index, element) => {
                if ($(element).is(':checked')) {
                    backjob_monitoring_id.push(element.value);
                }
            });
    
            if(backjob_monitoring_id.length > 0){
                Swal.fire({
                    title: 'Confirm Multiple Backjob Monitorings Deletion',
                    text: 'Are you sure you want to delete these backjob monitorings?',
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
                            url: 'controller/backjob-monitoring-controller.php',
                            dataType: 'json',
                            data: {
                                backjob_monitoring_id: backjob_monitoring_id,
                                transaction : transaction
                            },
                            success: function (response) {
                                if (response.success) {
                                    showNotification('Delete Backjob Monitoring Success', 'The selected backjob monitorings have been deleted successfully.', 'success');
                                    reloadDatatable('#backjob-monitoring-table');
                                }
                                else {
                                    if (response.isInactive) {
                                        setNotification('User Inactive', response.message, 'danger');
                                        window.location = 'logout.php?logout';
                                    }
                                    else {
                                        showNotification('Delete Backjob Monitoring Error', response.message, 'danger');
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
                showNotification('Deletion Multiple Backjob Monitoring Error', 'Please select the backjob monitorings you wish to delete.', 'danger');
            }
        });

        $(document).on('click','#delete-backjob-monitoring-details',function() {
            const backjob_monitoring_id = $('#backjob-monitoring-id').text();
            const transaction = 'delete backjob monitoring';
    
            Swal.fire({
                title: 'Confirm Backjob Monitoring Deletion',
                text: 'Are you sure you want to delete this backjob monitoring?',
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
                        url: 'controller/backjob-monitoring-controller.php',
                        dataType: 'json',
                        data: {
                            backjob_monitoring_id : backjob_monitoring_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                setNotification('Deleted Backjob Monitoring Success', 'The backjob monitoring has been deleted successfully.', 'success');
                                window.location = 'backjob-monitoring.php';
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
                                    showNotification('Delete Backjob Monitoring Error', response.message, 'danger');
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
        
        $(document).on('change','#type',function() {
            let type = $(this).val();

            if(type === 'Backjob'){
                $('#sales-row').removeClass('d-none');
                $('#product-row').addClass('d-none');
                $('#warranty-row').addClass('d-none');
            }
            else if(type === 'Internal Repair'){
                $('#sales-row').addClass('d-none');
                $('#warranty-row').addClass('d-none');
                $('#product-row').removeClass('d-none');
            }
            else if(type === 'Warranty'){
                $('#sales-row').addClass('d-none');
                $('#product-row').addClass('d-none');
                $('#warranty-row').removeClass('d-none');
            }
            else{
                $('#sales-row').addClass('d-none');
                $('#product-row').addClass('d-none');
                $('#warranty-row').addClass('d-none');
            }
        });

        $(document).on('click','#discard-create',function() {
            discardCreate('backjob-monitoring.php');
        });

        $(document).on('click','#tag-as-on-process',function() {
            const backjob_monitoring_id = $('#backjob-monitoring-id').text();
            const transaction = 'tag as on process';
    
            Swal.fire({
                title: 'Confirm Tagging Backjob On Process',
                text: 'Are you sure you want to tag this Backjob on process?',
                icon: 'warning',
                showCancelButton: !0,
                confirmButtonText: 'On Process',
                cancelButtonText: 'Cancel',
                confirmButtonClass: 'btn btn-success mt-2',
                cancelButtonClass: 'btn btn-secondary ms-2 mt-2',
                buttonsStyling: !1
            }).then(function(result) {
                if (result.value) {
                    $.ajax({
                        type: 'POST',
                        url: 'controller/backjob-monitoring-controller.php',
                        dataType: 'json',
                        data: {
                            backjob_monitoring_id : backjob_monitoring_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                setNotification('Tagging Backjob On Process Success', 'This has been tagged successfully.', 'success');
                                window.location.reload();
                            }
                            else {
                                if (response.isInactive) {
                                    setNotification('User Inactive', response.message, 'danger');
                                    window.location = 'logout.php?logout';
                                }
                                else if (response.noJobOrder) {
                                    showNotification('UsTagging Backjob On Process Error', 'There is no job order loaded.', 'danger');
                                }
                                else if (response.notExist) {
                                    window.location = '404.php';
                                }
                                else {
                                    showNotification('Tagging Backjob On Process Error', response.message, 'danger');
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
        
        $(document).on('click','#tag-as-ready-for-release',function() {
            const backjob_monitoring_id = $('#backjob-monitoring-id').text();
            const transaction = 'tag as ready for release';
    
            Swal.fire({
                title: 'Confirm Tagging Backjob Ready For Release',
                text: 'Are you sure you want to tag this Backjob ready for release?',
                icon: 'warning',
                showCancelButton: !0,
                confirmButtonText: 'Ready For Release',
                cancelButtonText: 'Cancel',
                confirmButtonClass: 'btn btn-success mt-2',
                cancelButtonClass: 'btn btn-secondary ms-2 mt-2',
                buttonsStyling: !1
            }).then(function(result) {
                if (result.value) {
                    $.ajax({
                        type: 'POST',
                        url: 'controller/backjob-monitoring-controller.php',
                        dataType: 'json',
                        data: {
                            backjob_monitoring_id : backjob_monitoring_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                setNotification('Tagging Backjob Ready For Release Success', 'This has been tagged successfully.', 'success');
                                window.location.reload();
                            }
                            else {
                                if (response.isInactive) {
                                    setNotification('User Inactive', response.message, 'danger');
                                    window.location = 'logout.php?logout';
                                }
                                else if (response.jobOrderUnfinished) {
                                    showNotification('Tagging Backjob Ready For Release Error', 'There are still on going job order.', 'danger');
                                }
                                else if (response.imageNotUploaded) {
                                    showNotification('Tagging Backjob Ready For Release Error', 'There are unit image or outgoing checklist or the quality control form has not been uploaded yet.', 'danger');
                                }
                                else if (response.notExist) {
                                    window.location = '404.php';
                                }
                                else {
                                    showNotification('Tagging Backjob Ready For Release Error', response.message, 'danger');
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
        
        $(document).on('click','#tag-as-for-dr',function() {
            const backjob_monitoring_id = $('#backjob-monitoring-id').text();
            const transaction = 'tag as for dr';
    
            Swal.fire({
                title: 'Confirm Tagging For DR',
                text: 'Are you sure you want to tag this for DR?',
                icon: 'warning',
                showCancelButton: !0,
                confirmButtonText: 'For DR',
                cancelButtonText: 'Cancel',
                confirmButtonClass: 'btn btn-success mt-2',
                cancelButtonClass: 'btn btn-secondary ms-2 mt-2',
                buttonsStyling: !1
            }).then(function(result) {
                if (result.value) {
                    $.ajax({
                        type: 'POST',
                        url: 'controller/backjob-monitoring-controller.php',
                        dataType: 'json',
                        data: {
                            backjob_monitoring_id : backjob_monitoring_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                setNotification('Tagging For DR Success', 'This has been tagged successfully.', 'success');
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
                                    showNotification('Tagging For DR Error', response.message, 'danger');
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

        $(document).on('click','.update-backjob-monitoring-job-order',function() {
            const backjob_monitoring_job_order_id = $(this).data('backjob-monitoring-job-order-id');
    
            sessionStorage.setItem('backjob_monitoring_job_order_id', backjob_monitoring_job_order_id  );
            
            displayDetails('get job order details');
        });

        $(document).on('click','.update-backjob-monitoring-additional-job-order',function() {
            const backjob_monitoring_additional_job_order_id  = $(this).data('backjob-monitoring-additional-job-order-id');
    
            sessionStorage.setItem('backjob_monitoring_additional_job_order_id', backjob_monitoring_additional_job_order_id );
            
            displayDetails('get additional job order details');
        });

        $(document).on('click','.delete-backjob-monitoring-job-order',function() {
            const backjob_monitoring_job_order_id = $(this).data('backjob-monitoring-job-order-id');
            const transaction = 'delete job order';
    
            Swal.fire({
                title: 'Confirm Job Order Deletion',
                text: 'Are you sure you want to delete this job order?',
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
                        url: 'controller/backjob-monitoring-controller.php',
                        dataType: 'json',
                        data: {
                            backjob_monitoring_job_order_id : backjob_monitoring_job_order_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                showNotification('Delete Job Order Success', 'The job order has been deleted successfully.', 'success');
                                reloadDatatable('#job-order-progress-table');
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
                                    showNotification('Delete Job Order Error', response.message, 'danger');
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

        $(document).on('click','.delete-backjob-monitoring-additional-job-order',function() {
            const backjob_monitoring_additional_job_order_id = $(this).data('backjob-monitoring-additional-job-order-id');
            const transaction = 'delete additional job order';
    
            Swal.fire({
                title: 'Confirm Additional Job Order Deletion',
                text: 'Are you sure you want to delete this additional job order?',
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
                        url: 'controller/backjob-monitoring-controller.php',
                        dataType: 'json',
                        data: {
                            backjob_monitoring_additional_job_order_id : backjob_monitoring_additional_job_order_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                showNotification('Delete Additional Job Order Success', 'The additional job order has been deleted successfully.', 'success');
                                
                                reloadDatatable('#additional-job-order-progress-table');
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
                                    showNotification('Delete Additional Job Order Error', response.message, 'danger');
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

        $(document).on('click','#print-job-order2',function() {
            var checkedBoxes = [];
            var product_id = $('#product_id').val();

            $('.job-order-checkbox-children').each((index, element) => {
                if ($(element).is(':checked')) {
                    checkedBoxes.push(element.value);
                }
            });

            if(checkedBoxes != ''){
                window.open('print-job-order-list2.php?id=' + checkedBoxes + '&product_id=' + product_id, '_blank');
            }
            else{
                showNotification('Print Job Order Error', 'No selected job order.', 'danger');
            }
        });

        $(document).on('click','#print-additional-job-order2',function() {
            var checkedBoxes = [];
            var product_id = $('#product_id').val();

            $('.additional-job-order-checkbox-children').each((index, element) => {
                if ($(element).is(':checked')) {
                    checkedBoxes.push(element.value);
                }
            });

            if(checkedBoxes != ''){
                window.open('print-additional-job-order-list2.php?id=' + checkedBoxes + '&product_id=' + product_id, '_blank');
            }
            else{
                showNotification('Print Additional Job Order Error', 'No selected additional job order.', 'danger');
            }
        });
    });
})(jQuery);

function backJobMonitoringTable(datatable_name, buttons = false, show_all = false){
    const type = 'backjob monitoring table';
    var settings;

    const column = [ 
        { 'data' : 'TYPE' },
        { 'data' : 'SALES_PROPOSAL' },
        { 'data' : 'PRODUCT' },
        { 'data' : 'STATUS' },
        { 'data' : 'CREATED_DATE' },
        { 'data' : 'ACTION' }
    ];

    const column_definition = [
        { 'width': 'auto', 'aTargets': 0 },
        { 'width': 'auto', 'aTargets': 1 },
        { 'width': 'auto', 'aTargets': 2 },
        { 'width': 'auto', 'aTargets': 3 },
        { 'width': 'auto', 'type': 'date', 'aTargets': 4 },
        { 'width': '15%','bSortable': false, 'aTargets': 5 }
    ];

    const length_menu = show_all ? [[-1], ['All']] : [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']];

    settings = {
        'ajax': { 
            'url' : 'view/_back_job_monitoring_generation.php',
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

function jobOrderProgress(datatable_name, buttons = false, show_all = false){
    const backjob_monitoring_id = $('#backjob-monitoring-id').text();
    const type = 'job order monitoring table';
    var settings;

    const column = [ 
        { 'data' : 'CHECK_BOX' },
        { 'data' : 'JOB_ORDER' },
        { 'data' : 'CONTRACTOR' },
        { 'data' : 'WORK_CENTER' },
        { 'data' : 'COST' },
        { 'data' : 'PLANNED_START_DATE' },
        { 'data' : 'PLANNED_FINISH_DATE' },
        { 'data' : 'DATE_STARTED' },
        { 'data' : 'COMPLETION_DATE' },
        { 'data' : 'PROGRESS' },
        { 'data' : 'ACTION' }
    ];

    const column_definition = [
        { 'width': '1%','bSortable': false, 'aTargets': 0 },
        { 'width': 'auto', 'aTargets': 1 },
        { 'width': 'auto', 'aTargets': 2 },
        { 'width': 'auto', 'aTargets': 3 },
        { 'width': 'auto', 'aTargets': 4 },
        { 'width': 'auto', 'type': 'date', 'aTargets': 5 },
        { 'width': 'auto', 'type': 'date', 'aTargets': 6 },
        { 'width': 'auto', 'type': 'date', 'aTargets': 7 },
        { 'width': 'auto', 'type': 'date', 'aTargets': 8 },
        { 'width': 'auto', 'aTargets': 9 },
        { 'width': '15%','bSortable': false, 'aTargets': 10 }
    ];

    const length_menu = show_all ? [[-1], ['All']] : [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']];

    settings = {
        'ajax': { 
            'url' : 'view/_back_job_monitoring_generation.php',
            'method' : 'POST',
            'dataType': 'json',
            'data': {'type' : type, 'backjob_monitoring_id' : backjob_monitoring_id},
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
    }

    if (buttons) {
        settings.dom = "<'row'<'col-sm-3'l><'col-sm-6 text-center mb-2'B><'col-sm-3'f>>" +  "<'row'<'col-sm-12'tr>>" + "<'row'<'col-sm-5'i><'col-sm-7'p>>";
        settings.buttons = ['csv', 'excel', 'pdf'];
    }

    destroyDatatable(datatable_name);

    $(datatable_name).dataTable(settings);
}

function additionalJobOrderProgress(datatable_name, buttons = false, show_all = false){
    const backjob_monitoring_id = $('#backjob-monitoring-id').text();
    const type = 'additional job order monitoring table';
    var settings;

    const column = [ 
        { 'data' : 'CHECK_BOX' },
        { 'data' : 'JOB_ORDER_NUMBER' },
        { 'data' : 'JOB_ORDER_DATE' },
        { 'data' : 'PARTICULARS' },
        { 'data' : 'COST' },
        { 'data' : 'CONTRACTOR' },
        { 'data' : 'WORK_CENTER' },
        { 'data' : 'PROGRESS' },
        { 'data' : 'PLANNED_START_DATE' },
        { 'data' : 'PLANNED_FINISH_DATE' },
        { 'data' : 'DATE_STARTED' },
        { 'data' : 'COMPLETION_DATE' },
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
        { 'width': 'auto', 'type': 'date', 'aTargets': 8 },
        { 'width': 'auto', 'type': 'date', 'aTargets': 9 },
        { 'width': 'auto', 'type': 'date', 'aTargets': 10 },
        { 'width': 'auto', 'type': 'date', 'aTargets': 11 },
        { 'width': '15%','bSortable': false, 'aTargets': 12 }
    ];

    const length_menu = show_all ? [[-1], ['All']] : [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']];

    settings = {
        'ajax': { 
            'url' : 'view/_back_job_monitoring_generation.php',
            'method' : 'POST',
            'dataType': 'json',
            'data': {'type' : type, 'backjob_monitoring_id' : backjob_monitoring_id},
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
    }

    if (buttons) {
        settings.dom = "<'row'<'col-sm-3'l><'col-sm-6 text-center mb-2'B><'col-sm-3'f>>" +  "<'row'<'col-sm-12'tr>>" + "<'row'<'col-sm-5'i><'col-sm-7'p>>";
        settings.buttons = ['csv', 'excel', 'pdf'];
    }

    destroyDatatable(datatable_name);

    $(datatable_name).dataTable(settings);
}

function backJobMonitoringForm(){
    $('#backjob-monitoring-form').validate({
        rules: {
            type: {
                required: true
            },
            sales_proposal_id: {
                required: function () {
                    return $('#type').val() == 'Backjob';
                }
            },
            product_id: {
                required: function () {
                    return $('#type').val() == 'Internal Repair';
                }
            },
            product_id2: {
                required: function () {
                    return $('#type').val() == 'Warranty';
                }
            },
        },
        messages: {
            type: {
                required: 'Please choose the type'
            },
            sales_proposal_id: {
                required: 'Please choose the sales proposal'
            },
            product_id: {
                required: 'Please choose the product'
            },
            product_id2: {
                required: 'Please choose the product'
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
            const backjob_monitoring_id = $('#backjob-monitoring-id').text();
            const transaction = 'save backjob monitoring';
        
            $.ajax({
                type: 'POST',
                url: 'controller/backjob-monitoring-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction + '&backjob_monitoring_id=' + backjob_monitoring_id,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-data');
                },
                success: function (response) {
                    if (response.success) {
                        const notificationMessage = response.insertRecord ? 'Insert Backjob Monitoring Success' : 'Update Backjob Monitoring Success';
                        const notificationDescription = response.insertRecord ? 'The backjob monitoring has been inserted successfully.' : 'The backjob monitoring has been updated successfully.';
                        
                        setNotification(notificationMessage, notificationDescription, 'success');
                        window.location = 'back-job-monitoring.php?id=' + response.backjobMonitoringID;
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

function ReleaseForm(){
    $('#tag-as-released-form').validate({
        rules: {
            release_remarks: {
                required: true
            },
        },
        messages: {
            release_remarks: {
                required: 'Please enter the release remarks'
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
            const backjob_monitoring_id = $('#backjob-monitoring-id').text();
            const transaction = 'tag for release';
        
            $.ajax({
                type: 'POST',
                url: 'controller/backjob-monitoring-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction + '&backjob_monitoring_id=' + backjob_monitoring_id,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-tag-as-released');
                },
                success: function (response) {
                    if (!response.success) {
                        if (response.isInactive) {
                            setNotification('User Inactive', response.message, 'danger');
                            window.location = 'logout.php?logout';
                        } else {
                            showNotification('Transaction Error', response.message, 'danger');
                        }
                    }
                    else{
                        window.location.reload();
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
                    enableFormSubmitButton('submit-tag-as-released', 'Submit');
                }
            });
        
            return false;
        }
    });
}

function CancelForm(){
    $('#tag-as-cancelled-form').validate({
        rules: {
            cancellation_reason: {
                required: true
            },
        },
        messages: {
            cancellation_reason: {
                required: 'Please eneter the cancellation remarks'
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
            const backjob_monitoring_id = $('#backjob-monitoring-id').text();
            const transaction = 'tag for cancelled';
        
            $.ajax({
                type: 'POST',
                url: 'controller/backjob-monitoring-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction + '&backjob_monitoring_id=' + backjob_monitoring_id,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-tag-as-cancelled');
                },
                success: function (response) {
                    if (!response.success) {
                        if (response.isInactive) {
                            setNotification('User Inactive', response.message, 'danger');
                            window.location = 'logout.php?logout';
                        } else {
                            showNotification('Transaction Error', response.message, 'danger');
                        }
                    }
                    else{
                        window.location.reload();
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
                    enableFormSubmitButton('submit-tag-as-cancelled', 'Submit');
                }
            });
        
            return false;
        }
    });
}

function UnitImageForm(){
    $('#unit-image-form').validate({
        rules: {
            unit_image_image: {
                required: true
            },
        },
        messages: {
            unit_image_image: {
                required: 'Please choose the unit image'
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
            const backjob_monitoring_id = $('#backjob-monitoring-id').text();
            const transaction = 'save unit image';
    
            var formData = new FormData(form);
            formData.append('backjob_monitoring_id', backjob_monitoring_id);
            formData.append('transaction', transaction);
        
            $.ajax({
                type: 'POST',
                url: 'controller/backjob-monitoring-controller.php',
                data: formData,
                processData: false,
                contentType: false,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-unit-image');
                },
                success: function (response) {
                    if (response.success) {
                        setNotification('Unit Image Upload Success', 'The unit image has been uploaded successfully', 'success');
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
                    enableFormSubmitButton('submit-unit-image', 'Submit');
                }
            });
        
            return false;
        }
    });
}

function OutgoingChecklistForm(){
    $('#outgoing-checklist-form').validate({
        rules: {
            outgoing_checklist_image: {
                required: true
            },
        },
        messages: {
            outgoing_checklist_image: {
                required: 'Please choose the outgoing checklist image'
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
            const backjob_monitoring_id = $('#backjob-monitoring-id').text();
            const transaction = 'save outgoing checklist';
    
            var formData = new FormData(form);
            formData.append('backjob_monitoring_id', backjob_monitoring_id);
            formData.append('transaction', transaction);
        
            $.ajax({
                type: 'POST',
                url: 'controller/backjob-monitoring-controller.php',
                data: formData,
                processData: false,
                contentType: false,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-outgoing-checklist');
                },
                success: function (response) {
                    if (response.success) {
                        setNotification('Outgoing Checklist Upload Success', 'The outgoing checklist has been uploaded successfully', 'success');
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
                    enableFormSubmitButton('submit-outgoing-checklist', 'Submit');
                }
            });
        
            return false;
        }
    });
}

function QualityControlFormForm(){
    $('#quality-control-form-form').validate({
        rules: {
            quality_control_form: {
                required: true
            },
        },
        messages: {
            quality_control_form: {
                required: 'Please choose the quality control form'
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
            const backjob_monitoring_id = $('#backjob-monitoring-id').text();
            const transaction = 'save quality control form';
    
            var formData = new FormData(form);
            formData.append('backjob_monitoring_id', backjob_monitoring_id);
            formData.append('transaction', transaction);
        
            $.ajax({
                type: 'POST',
                url: 'controller/backjob-monitoring-controller.php',
                data: formData,
                processData: false,
                contentType: false,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-quality-control-form');
                },
                success: function (response) {
                    if (response.success) {
                        setNotification('Quality Control Form Upload Success', 'The quality control form has been uploaded successfully', 'success');
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
                    enableFormSubmitButton('submit-quality-control-form', 'Submit');
                }
            });
        
            return false;
        }
    });
}

function JobOrderProgressForm(){
    $('#job-order-progress-form').validate({
        rules: {
            job_order_progress: {
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
            const transaction = 'save progress job order';
            const backjob_monitoring_id = $('#backjob-monitoring-id').text();
        
            $.ajax({
                type: 'POST',
                url: 'controller/backjob-monitoring-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction + '&backjob_monitoring_id=' + backjob_monitoring_id,
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
                    
                    $('#job-order-monitoring-offcanvas').offcanvas('hide');
                }
            });
        
            return false;
        }
    });
}

function AdditionalJobOrderProgressForm(){
    $('#additional-job-order-progress-form').validate({
        rules: {
            additional_job_order_progress: {
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
            const transaction = 'save progress additional job order';
            const backjob_monitoring_id = $('#backjob-monitoring-id').text();
        
            $.ajax({
                type: 'POST',
                url: 'controller/backjob-monitoring-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction + '&backjob_monitoring_id=' + backjob_monitoring_id,
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
                    $('#additional-job-order-monitoring-offcanvas').offcanvas('hide');
                }
            });
        
            return false;
        }
    });
}

function displayDetails(transaction){
    switch (transaction) {
        case 'get backjob monitoring details':
            const backjob_monitoring_id = $('#backjob-monitoring-id').text();
            
            $.ajax({
                url: 'controller/backjob-monitoring-controller.php',
                method: 'POST',
                dataType: 'json',
                data: {
                    backjob_monitoring_id : backjob_monitoring_id, 
                    transaction : transaction
                },
                beforeSend: function() {
                    resetForm('backjob-monitoring-form');
                },
                success: function(response) {
                    if (response.success) {
                        checkOptionExist('#type', response.type, '');
                        checkOptionExist('#sales_proposal_id', response.sales_proposal_id, '');
                        checkOptionExist('#product_id', response.product_id, '');
                        checkOptionExist('#product_id2', response.product_id, '');

                        if($('#unit-img').length){
                            document.getElementById('unit-img').src = response.unitImage;
                        }

                        if($('#outgoing-checklst').length){
                            document.getElementById('outgoing-checklst').src = response.outgoingChecklist;
                        }

                        if($('#quality-control-frm').length){
                            document.getElementById('quality-control-frm').src = response.qualityControlForm;
                        }
                    } 
                    else {
                        if(response.isInactive){
                            window.location = 'logout.php?logout';
                        }
                        else{
                            showNotification('Get Backjob Monitoring Details Error', response.message, 'danger');
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
            case 'get job order details':
                var backjob_monitoring_job_order_id = sessionStorage.getItem('backjob_monitoring_job_order_id');
                    
                $.ajax({
                    url: 'controller/backjob-monitoring-controller.php',
                    method: 'POST',
                    dataType: 'json',
                    data: {
                        backjob_monitoring_job_order_id : backjob_monitoring_job_order_id, 
                        transaction : transaction
                    },
                    success: function(response) {
                        if (response.success) {                        
                            $('#backjob_monitoring_job_order_id').val(backjob_monitoring_job_order_id);
                            $('#job_order_progress').val(response.progress);
                            $('#job_order_cost').val(response.cost);
                            $('#job_order_completion_date').val(response.completionDate);
                            $('#job_order').val(response.jobOrder);
                            
                            $('#job_order_planned_start_date').val(response.plannedStartDate);
                            $('#job_order_planned_finish_date').val(response.plannedFinishDate);
                            $('#job_order_date_started').val(response.dateStarted);
    
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
            case 'get additional job order details':
                var backjob_monitoring_additional_job_order_id = sessionStorage.getItem('backjob_monitoring_additional_job_order_id');
                
                $.ajax({
                    url: 'controller/backjob-monitoring-controller.php',
                    method: 'POST',
                    dataType: 'json',
                    data: {
                        backjob_monitoring_additional_job_order_id : backjob_monitoring_additional_job_order_id, 
                        transaction : transaction
                    },
                    success: function(response) {
                        if (response.success) {
                            
                            $('#backjob_monitoring_additional_job_order_id').val(backjob_monitoring_additional_job_order_id);
                            $('#additional_job_order_progress').val(response.progress);
                            $('#additional_job_order_cost').val(response.cost);
                            $('#additional_job_order_completion_date').val(response.completionDate);
                            $('#job_order_number').val(response.jobOrderNumber);
                            $('#job_order_date').val(response.jobOrderDate);
                            $('#particulars').val(response.particulars);
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