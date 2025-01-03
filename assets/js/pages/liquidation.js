(function($) {
    'use strict';

    $(function() {
        if($('#liquidation-table').length){
            liquidationTable('#liquidation-table');
        }
        
        if($('#particulars-table').length){
            particularsTable('#particulars-table');
        }
        
        if($('#addon-table').length){
            addOnTable('#addon-table');
        }

        if($('#particulars-form').length){
            particularsForm();
        }

        if($('#liquidation-id').length){
            displayDetails('get liquidation details');
        }

        $(document).on('click','#add-particulars',function() {
            $('.addon-hidden').addClass('d-none');
            $('#liquidation_type').val('particulars');
        });

        $(document).on('click','#add-excess',function() {
            $('.addon-hidden').removeClass('d-none');
            $('#liquidation_type').val('add-on');
        });

        $(document).on('click','.delete-liquidation',function() {
            const liquidation_id = $(this).data('liquidation-id');
            const transaction = 'delete liquidation';
    
            Swal.fire({
                title: 'Confirm Liquidation Deletion',
                text: 'Are you sure you want to delete this liquidation?',
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
                        url: 'controller/liquidation-controller.php',
                        dataType: 'json',
                        data: {
                            liquidation_id : liquidation_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                showNotification('Delete Liquidation Success', 'The liquidation has been deleted successfully.', 'success');
                                reloadDatatable('#liquidation-table');
                            }
                            else {
                                if (response.isInactive) {
                                    setNotification('User Inactive', response.message, 'danger');
                                    window.location = 'logout.php?logout';
                                }
                                else if (response.notExist) {
                                    showNotification('Delete Liquidation Error', 'The liquidation does not exist.', 'danger');
                                    reloadDatatable('#liquidation-table');
                                }
                                else {
                                    showNotification('Delete Liquidation Error', response.message, 'danger');
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

        $(document).on('click','#delete-liquidation',function() {
            let liquidation_id = [];
            const transaction = 'delete multiple liquidation';

            $('.datatable-checkbox-children').each((index, element) => {
                if ($(element).is(':checked')) {
                    liquidation_id.push(element.value);
                }
            });
    
            if(liquidation_id.length > 0){
                Swal.fire({
                    title: 'Confirm Multiple Liquidation Deletion',
                    text: 'Are you sure you want to delete these liquidation?',
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
                            url: 'controller/liquidation-controller.php',
                            dataType: 'json',
                            data: {
                                liquidation_id: liquidation_id,
                                transaction : transaction
                            },
                            success: function (response) {
                                if (response.success) {
                                    showNotification('Delete Liquidation Success', 'The selected liquidation have been deleted successfully.', 'success');
                                        reloadDatatable('#liquidation-table');
                                }
                                else {
                                    if (response.isInactive) {
                                        setNotification('User Inactive', response.message, 'danger');
                                        window.location = 'logout.php?logout';
                                    }
                                    else {
                                        showNotification('Delete Liquidation Error', response.message, 'danger');
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
                showNotification('Deletion Multiple Liquidation Error', 'Please select the liquidation you wish to delete.', 'danger');
            }
        });

        $(document).on('click','#delete-liquidation-details',function() {
            const liquidation_id = $('#liquidation-id').text();
            const transaction = 'delete liquidation';
    
            Swal.fire({
                title: 'Confirm Liquidation Deletion',
                text: 'Are you sure you want to delete this liquidation?',
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
                        url: 'controller/liquidation-controller.php',
                        dataType: 'json',
                        data: {
                            liquidation_id : liquidation_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                setNotification('Deleted Liquidation Success', 'The liquidation has been deleted successfully.', 'success');
                                window.location = 'liquidation.php';
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
                                    showNotification('Delete Liquidation Error', response.message, 'danger');
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

        $(document).on('click','#add-particulars',function() {
            resetModalForm('particulars-form');
        });

        $(document).on('click','.update-liquidation-particulars',function() {
            const liquidation_particulars_id = $(this).data('liquidation-particulars-id');
    
            sessionStorage.setItem('liquidation_particulars_id', liquidation_particulars_id);
            
            displayDetails('get liquidation particulars details');
        });

        $(document).on('click','.delete-liquidation-particulars',function() {
            const liquidation_particulars_id = $(this).data('liquidation-particulars-id');
            const transaction = 'delete liquidation particulars';
    
            Swal.fire({
                title: 'Confirm Liquidation Particulars Deletion',
                text: 'Are you sure you want to delete this liquidation particulars?',
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
                        url: 'controller/disbursement-controller.php',
                        dataType: 'json',
                        data: {
                            liquidation_particulars_id : liquidation_particulars_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                showNotification('Delete Liquidation Particulars Success', 'The liquidation particulars has been deleted successfully.', 'success');
                                reloadDatatable('#particulars-table');
                                displayDetails('get liquidation details');
                            }
                            else {
                                if (response.isInactive) {
                                    setNotification('User Inactive', response.message, 'danger');
                                    window.location = 'logout.php?logout';
                                }
                                else if (response.notExist) {
                                    showNotification('Delete Liquidation Particulars Error', 'The liquidation particulars does not exists.', 'danger');
                                    reloadDatatable('#particulars-table');
                                }
                                else {
                                    showNotification('Delete Liquidation Particulars Error', response.message, 'danger');
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

        $(document).on('change','#payable_type',function() {
            checkOptionExist('#customer_id', '', '');
            checkOptionExist('#misc_id', '', '');

            if($(this).val() === 'Customer'){
                $('#misc-select').addClass('d-none');
                $('#customer-select').removeClass('d-none');
            }
            else{
                $('#customer-select').addClass('d-none');
                $('#misc-select').removeClass('d-none');
            }
        });

    });
})(jQuery);

function liquidationTable(datatable_name, buttons = false, show_all = false){
    const type = 'liquidation table';

    var settings;

    const column = [
        { 'data' : 'PARTICULARS' },
        { 'data' : 'TRANSACTION_NUNMBER' },
        { 'data' : 'REMAINING_BALANCE' },
        { 'data' : 'ACTION' }
    ];

    const column_definition = [
        { 'width': 'auto', 'aTargets': 0 },
        { 'width': 'auto', 'aTargets': 1 },
        { 'width': 'auto', 'aTargets': 2 },
        { 'width': '15%','bSortable': false, 'aTargets': 3 }
    ];

    const length_menu = show_all ? [[-1], ['All']] : [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']];

    settings = {
        'ajax': { 
            'url' : 'view/_liquidation_generation.php',
            'method' : 'POST',
            'dataType': 'json',
            'data': {
                'type' : type,
            },
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
    };

    if (buttons) {
        settings.dom = "<'row'<'col-sm-3'l><'col-sm-6 text-center mb-2'B><'col-sm-3'f>>" +  "<'row'<'col-sm-12'tr>>" + "<'row'<'col-sm-5'i><'col-sm-7'p>>";
        settings.buttons = ['csv', 'excel', 'pdf'];
    }

    destroyDatatable(datatable_name);

    $(datatable_name).dataTable(settings);
}

function particularsTable(datatable_name, buttons = false, show_all = false){
    const type = 'liquidation particulars table';
    const liquidation_id = $('#liquidation-id').text();

    var settings;

    const column = [
        { 'data' : 'PARTICULARS' },
        { 'data' : 'PARTICULAR_AMOUNT' },
        { 'data' : 'ACTION' }
    ];

    const column_definition = [
        { 'width': 'auto', 'aTargets': 0 },
        { 'width': 'auto', 'aTargets': 1 },
        { 'width': '15%','bSortable': false, 'aTargets': 2 }
    ];

    const length_menu = show_all ? [[-1], ['All']] : [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']];

    settings = {
        'ajax': { 
            'url' : 'view/_liquidation_generation.php',
            'method' : 'POST',
            'dataType': 'json',
            'data': {'type' : type, 
                'liquidation_id' : liquidation_id
            },
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

function addOnTable(datatable_name, buttons = false, show_all = false){
    const type = 'liquidation addon particulars table';
    const liquidation_id = $('#liquidation-id').text();

    var settings;

    const column = [
        { 'data' : 'PARTICULARS' },
        { 'data' : 'REFERENCE_TYPE' },
        { 'data' : 'REFERENCE_NUMBER' },
        { 'data' : 'PARTICULAR_AMOUNT' },
        { 'data' : 'ACTION' }
    ];

    const column_definition = [
        { 'width': 'auto', 'aTargets': 0 },
        { 'width': 'auto', 'aTargets': 1 },
        { 'width': 'auto', 'aTargets': 2 },
        { 'width': 'auto', 'aTargets': 3 },
        { 'width': '15%','bSortable': false, 'aTargets': 4 }
    ];

    const length_menu = show_all ? [[-1], ['All']] : [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']];

    settings = {
        'ajax': { 
            'url' : 'view/_liquidation_generation.php',
            'method' : 'POST',
            'dataType': 'json',
            'data': {'type' : type, 
                'liquidation_id' : liquidation_id
            },
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

function particularsForm(){
    $('#particulars-form').validate({
        rules: {
            particulars: {
                required: true
            },
            particulars_amount: {
                required: true
            },
            reference_type: {
                required: true
            },
            reference_type: {
                required: {
                    depends: function(element) {
                        return $("input[name='liquidation_type']").val() === 'add-on';
                    }
                }
            },
            reference_number: {
                required: {
                    depends: function(element) {
                        return $("input[name='liquidation_type']").val() === 'add-on';
                    }
                }
            },
        },
        messages: {
            particulars: {
                required: 'Please enter the particulars'
            },
            particulars_amount: {
                required: 'Please enter the liquidation amount'
            },
            reference_type: {
                required: 'Please choose the reference type'
            },
            reference_number: {
                required: 'Please choose the reference number'
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
            const liquidation_id = $('#liquidation-id').text();
            const transaction = 'save liquidation particulars';
        
            $.ajax({
                type: 'POST',
                url: 'controller/disbursement-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction + '&liquidation_id=' + liquidation_id,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-particulars');
                },
                success: function (response) {
                    if (response.success) {
                        const notificationMessage = response.insertRecord ? 'Insert Particulars Success' : 'Update Particulars Success';
                        const notificationDescription = response.insertRecord ? 'The particulars has been inserted successfully.' : 'The particulars has been updated successfully.';
                        
                        showNotification(notificationMessage, notificationDescription, 'success');

                        particularsTable('#particulars-table');
                        addOnTable('#addon-table');
                        displayDetails('get liquidation details');
                        $('#particulars-offcanvas').offcanvas('hide');
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
                    enableFormSubmitButton('submit-particulars', 'Submit');
                }
            });
        
            return false;
        }
    });
}

function displayDetails(transaction){
    switch (transaction) {
        case 'get liquidation details':
            const liquidation_id = $('#liquidation-id').text();
            
            $.ajax({
                url: 'controller/disbursement-controller.php',
                method: 'POST',
                dataType: 'json',
                data: {
                    liquidation_id : liquidation_id, 
                    transaction : transaction
                },
                beforeSend: function() {
                    resetForm('liquidation-form');
                },
                success: function(response) {
                    if (response.success) {
                        $('#transaction_number').val(response.transactionNumber);
                        $('#particulars').val(response.particulars);

                        checkOptionExist('#payable_type', response.payableType, '');
                        checkOptionExist('#transaction_type', response.transactionType, '');
                        checkOptionExist('#fund_source', response.fundSource, '');
                        checkOptionExist('#department_id', response.departmentID, '');
                        checkOptionExist('#company_id', response.companyID, '');

                        if(response.payableType === 'Customer'){
                            checkOptionExist('#customer_id', response.customerID, '');
                        }
                        else{
                            checkOptionExist('#misc_id', response.customerID, '');
                        }
                        $('#remaining_balance').val(response.remainingBalance);
                    } 
                    else {
                        if(response.isInactive){
                            window.location = 'logout.php?logout';
                        }
                        else{
                            showNotification('Get Liquidation Details Error', response.message, 'danger');
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
        case 'get liquidation particulars details':
            var liquidation_particulars_id = sessionStorage.getItem('liquidation_particulars_id');
                    
            $.ajax({
                url: 'controller/disbursement-controller.php',
                method: 'POST',
                dataType: 'json',
                data: {
                    liquidation_particulars_id : liquidation_particulars_id, 
                    transaction : transaction
                },
                success: function(response) {
                    if (response.success) {
                        $('#liquidation_particulars_id').val(liquidation_particulars_id);
                        $('#particulars').val(response.particulars);
                        $('#particulars_amount').val(response.particulars_amount);
                        $('#reference_number').val(response.reference_number);

                       
                        checkOptionExist('#reference_type', response.reference_type, '');
                    } 
                    else {
                        if(response.isInactive){
                            window.location = 'logout.php?logout';
                        }
                        else{
                            showNotification('Get Liquidation Particulars Details Error', response.message, 'danger');
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