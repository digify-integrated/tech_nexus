(function($) {
    'use strict';

    $(function() {
        if($('#travel-form-table').length){
            travelFormTable('#travel-form-table');
        }

        if($('#travel-form').length){
            travelForm();
        }

        if($('#travel-authorization-form').length){
            travelAuthorizationForm();
        }

        if($('#gate-pass-form').length){
            gatePassForm();
        }

        if($('#travel-form-id').length){
            displayDetails('get travel form details');
            displayDetails('get travel authorization details');
            displayDetails('get gate pass details');
        }

        $(document).on('click','.delete-travel-form',function() {
            const travel_form_id = $(this).data('travel-form-id');
            const transaction = 'delete travel form';
    
            Swal.fire({
                title: 'Confirm Travel Form Deletion',
                text: 'Are you sure you want to delete this travel form?',
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
                        url: 'controller/travel-form-controller.php',
                        dataType: 'json',
                        data: {
                            travel_form_id : travel_form_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                showNotification('Delete Travel Form Success', 'The travel form has been deleted successfully.', 'success');
                                reloadDatatable('#travel-form-table');
                            }
                            else {
                                if (response.isInactive) {
                                    setNotification('User Inactive', response.message, 'danger');
                                    window.location = 'logout.php?logout';
                                }
                                else if (response.notExist) {
                                    showNotification('Delete Travel Form Error', 'The travel form does not exist.', 'danger');
                                    reloadDatatable('#travel-form-table');
                                }
                                else {
                                    showNotification('Delete Travel Form Error', response.message, 'danger');
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

        $(document).on('click','#delete-travel-form',function() {
            let travel_form_id = [];
            const transaction = 'delete multiple travel form';

            $('.datatable-checkbox-children').each((index, element) => {
                if ($(element).is(':checked')) {
                    travel_form_id.push(element.value);
                }
            });
    
            if(travel_form_id.length > 0){
                Swal.fire({
                    title: 'Confirm Multiple Travel Forms Deletion',
                    text: 'Are you sure you want to delete these travel forms?',
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
                            url: 'controller/travel-form-controller.php',
                            dataType: 'json',
                            data: {
                                travel_form_id: travel_form_id,
                                transaction : transaction
                            },
                            success: function (response) {
                                if (response.success) {
                                    showNotification('Delete Travel Form Success', 'The selected travel forms have been deleted successfully.', 'success');
                                    reloadDatatable('#travel-form-table');
                                }
                                else {
                                    if (response.isInactive) {
                                        setNotification('User Inactive', response.message, 'danger');
                                        window.location = 'logout.php?logout';
                                    }
                                    else {
                                        showNotification('Delete Travel Form Error', response.message, 'danger');
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
                showNotification('Deletion Multiple Travel Form Error', 'Please select the travel forms you wish to delete.', 'danger');
            }
        });

        $(document).on('click','#delete-travel-form-details',function() {
            const travel_form_id = $('#travel-form-id').text();
            const transaction = 'delete travel form';
    
            Swal.fire({
                title: 'Confirm Travel Form Deletion',
                text: 'Are you sure you want to delete this travel form?',
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
                        url: 'controller/travel-form-controller.php',
                        dataType: 'json',
                        data: {
                            travel_form_id : travel_form_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                setNotification('Deleted Travel Form Success', 'The travel form has been deleted successfully.', 'success');
                                window.location = 'travel-form.php';
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
                                    showNotification('Delete Travel Form Error', response.message, 'danger');
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
            discardCreate('travel-form.php');
        });

        $(document).on('click','#edit-form',function() {
            displayDetails('get travel form details');

            enableForm();
        });
    });
})(jQuery);

function travelFormTable(datatable_name, buttons = false, show_all = false){
    const type = 'travel form table';
    var settings;

    const column = [ 
        { 'data' : 'CHECK_BOX' },
        { 'data' : 'APPLICATION_SOURCE_NAME' },
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
            'url' : 'view/_travel_form_generation.php',
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

function travelForm(){
    $('#travel-form').validate({
        rules: {
            checked_by: {
                required: true
            },
            approval_by: {
                required: true
            },
        },
        messages: {
            checked_by: {
                required: 'Please choose the checked by'
            },
            approval_by: {
                required: 'Please choose the approval by'
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
            const travel_form_id = $('#travel-form-id').text();
            const transaction = 'save travel form';
        
            $.ajax({
                type: 'POST',
                url: 'controller/travel-form-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction + '&travel_form_id=' + travel_form_id,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-data');
                },
                success: function (response) {
                    if (response.success) {
                        const notificationMessage = response.insertRecord ? 'Insert Travel Form Success' : 'Update Travel Form Success';
                        const notificationDescription = response.insertRecord ? 'The travel form has been inserted successfully.' : 'The travel form has been updated successfully.';
                        
                        setNotification(notificationMessage, notificationDescription, 'success');
                        window.location = 'travel-form.php?id=' + response.travelFormID;
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

function travelAuthorizationForm(){
    $('#travel-authorization-form').validate({
        rules: {
            destination: {
                required: true
            },
            mode_of_transportation: {
                required: true
            },
            purpose_of_travel: {
                required: true
            },
            authorization_departure_date: {
                required: true
            },
            authorization_return_date: {
                required: true
            },
            toll_fee: {
                required: true
            },
            accomodation: {
                required: true
            },
            meals: {
                required: true
            },
            other_expenses: {
                required: true
            },
        },
        messages: {
            destination: {
                required: 'Please enter the destination'
            },
            mode_of_transportation: {
                required: 'Please enter the mode of transportation'
            },
            purpose_of_travel: {
                required: 'Please enter the purpose of travel'
            },
            authorization_departure_date: {
                required: 'Please choose the departure date'
            },
            authorization_return_date: {
                required: 'Please choose the return date'
            },
            toll_fee: {
                required: 'Please enter the toll fee'
            },
            accomodation: {
                required: 'Please enter the accomodation'
            },
            meals: {
                required: 'Please enter the meals'
            },
            other_expenses: {
                required: 'Please enter the other expenses'
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
            const travel_form_id = $('#travel-form-id').text();
            const transaction = 'save travel authorization';
        
            $.ajax({
                type: 'POST',
                url: 'controller/travel-form-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction + '&travel_form_id=' + travel_form_id,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-travel-authorization-data');
                },
                success: function (response) {
                    if (response.success) {
                        const notificationMessage = response.insertRecord ? 'Insert Travel Authorization Success' : 'Update Travel Authorization Success';
                        const notificationDescription = response.insertRecord ? 'The travel authorization has been inserted successfully.' : 'The travel authorization has been updated successfully.';
                        
                        showNotification(notificationMessage, notificationDescription, 'success');
                        displayDetails('get travel authorization details');
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
                    enableFormSubmitButton('submit-travel-authorization-data', 'Save');
                }
            });
        
            return false;
        }
    });
}

function gatePassForm(){
    $('#gate-pass-form').validate({
        rules: {
            name_of_driver: {
                required: true
            },
            contact_number: {
                required: true
            },
            vehicle_type: {
                required: true
            },
            plate_number: {
                required: true
            },
            department_id: {
                required: true
            },
            gate_pass_departure_date: {
                required: true
            },
            odometer_reading: {
                required: true
            },
        },
        messages: {
            name_of_driver: {
                required: 'Please enter the name of driver'
            },
            contact_number: {
                required: 'Please enter the contact number'
            },
            vehicle_type: {
                required: 'Please enter the vehicle type'
            },
            plate_number: {
                required: 'Please enter the plate number'
            },
            department_id: {
                required: 'Please choose the department'
            },
            gate_pass_departure_date: {
                required: 'Please choose the departure date'
            },
            odometer_reading: {
                required: 'Please enter the odometer reading'
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
            const travel_form_id = $('#travel-form-id').text();
            const transaction = 'save gate pass';
        
            $.ajax({
                type: 'POST',
                url: 'controller/travel-form-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction + '&travel_form_id=' + travel_form_id,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-gate-pass-data');
                },
                success: function (response) {
                    if (response.success) {
                        const notificationMessage = response.insertRecord ? 'Insert Gate Pass Success' : 'Update Gate Pass Success';
                        const notificationDescription = response.insertRecord ? 'The gate pass has been inserted successfully.' : 'The gate pass has been updated successfully.';
                        
                        showNotification(notificationMessage, notificationDescription, 'success');
                        displayDetails('get gate pass details');
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
                    enableFormSubmitButton('submit-gate-pass-data', 'Save');
                }
            });
        
            return false;
        }
    });
}

function displayDetails(transaction){
    switch (transaction) {
        case 'get travel form details':
            var travel_form_id = $('#travel-form-id').text();
            
            $.ajax({
                url: 'controller/travel-form-controller.php',
                method: 'POST',
                dataType: 'json',
                data: {
                    travel_form_id : travel_form_id, 
                    transaction : transaction
                },
                beforeSend: function() {
                    resetForm('travel-form');
                },
                success: function(response) {
                    if (response.success) {
                        $('#travel_form_id').val(travel_form_id);

                        checkOptionExist('#checked_by', response.checkedBy, '');
                        checkOptionExist('#approval_by', response.approvalBy, '');
                    } 
                    else {
                        if(response.isInactive){
                            window.location = 'logout.php?logout';
                        }
                        else{
                            showNotification('Get Travel Form Details Error', response.message, 'danger');
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
        case 'get travel authorization details':
            var travel_form_id = $('#travel-form-id').text();
            
            $.ajax({
                url: 'controller/travel-form-controller.php',
                method: 'POST',
                dataType: 'json',
                data: {
                    travel_form_id : travel_form_id, 
                    transaction : transaction
                },
                beforeSend: function() {
                    resetForm('travel-form');
                },
                success: function(response) {
                    if (response.success) {
                        $('#destination').val(response.destination);
                        $('#mode_of_transportation').val(response.modeOfTransportation);
                        $('#purpose_of_travel').val(response.purposeOfTravel);
                        $('#authorization_departure_date').val(response.authorizationDepartureDate);
                        $('#authorization_return_date').val(response.authorizationReturnDate);
                        $('#accomodation_details').val(response.accomodationDetails);
                        $('#toll_fee').val(response.tollFee);
                        $('#accomodation').val(response.accomodation);
                        $('#meals').val(response.meals);
                        $('#other_expenses').val(response.otherExpenses);
                        $('#additional_comments').val(response.additionalComments);
                    } 
                    else {
                        if(response.isInactive){
                            window.location = 'logout.php?logout';
                        }
                        else{
                            showNotification('Get Travel Form Details Error', response.message, 'danger');
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
        case 'get gate pass details':
            var travel_form_id = $('#travel-form-id').text();
            
            $.ajax({
                url: 'controller/travel-form-controller.php',
                method: 'POST',
                dataType: 'json',
                data: {
                    travel_form_id : travel_form_id, 
                    transaction : transaction
                },
                beforeSend: function() {
                    resetForm('travel-form');
                },
                success: function(response) {
                    if (response.success) {
                        $('#name_of_driver').val(response.nameOfDriver);
                        $('#contact_number').val(response.contactNumber);
                        $('#vehicle_type').val(response.vehicleType);
                        $('#plate_number').val(response.plateNumber);
                        $('#gate_pass_departure_date').val(response.gatePassDepartureDate);
                        $('#odometer_reading').val(response.odometerReading);
                        $('#remarks').val(response.remarks);

                        checkOptionExist('#department_id', response.departmentID, '');
                    } 
                    else {
                        if(response.isInactive){
                            window.location = 'logout.php?logout';
                        }
                        else{
                            showNotification('Get Travel Form Details Error', response.message, 'danger');
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