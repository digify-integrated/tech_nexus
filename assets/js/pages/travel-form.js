(function($) {
    'use strict';

    $(function() {
        if($('#travel-form-table').length){
            travelFormTable('#travel-form-table');
        }

        if($('#travel-approval-form-table').length){
            travelApprovalFormTable('#travel-approval-form-table');
        }

        if($('#itinerary-table').length){
            itineraryTable('#itinerary-table');
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

        if($('#itinerary-form').length){
            itineraryForm();
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

        $(document).on('click','#add-itinerary',function() {
            resetModalForm("itinerary-form");
        });

        $(document).on('click','.update-itinerary',function() {
            const itinerary_id = $(this).data('itinerary-id');
    
            sessionStorage.setItem('itinerary_id', itinerary_id);
            
            displayDetails('get itinerary details');
        });

        $(document).on('click','.delete-itinerary',function() {
            const itinerary_id = $(this).data('itinerary-id');
            const transaction = 'delete itinerary';
    
            Swal.fire({
                title: 'Confirm Itinerary Deletion',
                text: 'Are you sure you want to delete this itinerary?',
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
                            itinerary_id : itinerary_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                showNotification('Delete Itinerary Success', 'The itinerary has been deleted successfully.', 'success');
                                reloadDatatable('#itinerary-table');
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
                                    showNotification('Delete Itinerary Error', response.message, 'danger');
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

        $(document).on('click','#print-consolidated',function() {
            const travel_form_id = $('#travel-form-id').text();

            window.open('print-travel-consolidated.php?id=' + travel_form_id, '_blank');
        });

        $(document).on('click','#print-travel-authorization',function() {
            const travel_form_id = $('#travel-form-id').text();

            window.open('print-travel-authorization.php?id=' + travel_form_id, '_blank');
        });

        $(document).on('click','#print-gate-pass',function() {
            const travel_form_id = $('#travel-form-id').text();

            window.open('print-gate-pass.php?id=' + travel_form_id, '_blank');
        });

        $(document).on('click','#print-itinerary',function() {
            const travel_form_id = $('#travel-form-id').text();

            window.open('print-itinerary.php?id=' + travel_form_id, '_blank');
        });

        $(document).on('click','#tag-as-for-checking',function() {
            const travel_form_id = $('#travel-form-id').text();
            const transaction = 'tag for checking';
    
            Swal.fire({
                title: 'Confirm Tagging of Travel For Checking',
                text: 'Are you sure you want to tag this travel form for checking?',
                icon: 'info',
                showCancelButton: !0,
                confirmButtonText: 'For Checking',
                cancelButtonText: 'Cancel',
                confirmButtonClass: 'btn btn-info mt-2',
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
                                setNotification('Tag Travel Form For Checking Success', 'The travel form has been tagged for checking successfully.', 'success');
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
                                    showNotification('Tag Travel Form For Checking Error', response.message, 'danger');
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

        $(document).on('click','#tag-as-checked',function() {
            const travel_form_id = $('#travel-form-id').text();
            const transaction = 'tag as checked';
    
            Swal.fire({
                title: 'Confirm Tagging of Travel As Checked',
                text: 'Are you sure you want to tag this travel form for checking?',
                icon: 'info',
                showCancelButton: !0,
                confirmButtonText: 'Checked',
                cancelButtonText: 'Cancel',
                confirmButtonClass: 'btn btn-info mt-2',
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
                                setNotification('Tag Travel Form As Checked Success', 'The travel form has been tagged as checked successfully.', 'success');
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
                                    showNotification('Tag Travel Form As Checked Error', response.message, 'danger');
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

        $(document).on('click','#tag-as-for-recommendation',function() {
            const travel_form_id = $('#travel-form-id').text();
            const transaction = 'tag for recommendation';
    
            Swal.fire({
                title: 'Confirm Tagging of Travel For Recommendation',
                text: 'Are you sure you want to tag this travel form for recommendation?',
                icon: 'info',
                showCancelButton: !0,
                confirmButtonText: 'For Recommendation',
                cancelButtonText: 'Cancel',
                confirmButtonClass: 'btn btn-info mt-2',
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
                                setNotification('Tag Travel Form For Recommendation Success', 'The travel form has been tagged for recommendation successfully.', 'success');
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
                                    showNotification('Tag Travel Form For Recommendation Error', response.message, 'danger');
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

        $(document).on('click','#tag-as-recommended',function() {
            const travel_form_id = $('#travel-form-id').text();
            const transaction = 'tag as recommended';
    
            Swal.fire({
                title: 'Confirm Tagging of Travel As Recommended',
                text: 'Are you sure you want to tag this travel form for recommended?',
                icon: 'info',
                showCancelButton: !0,
                confirmButtonText: 'Recommended',
                cancelButtonText: 'Cancel',
                confirmButtonClass: 'btn btn-info mt-2',
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
                                setNotification('Tag Travel Form As Recommended Success', 'The travel form has been tagged as recommended successfully.', 'success');
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
                                    showNotification('Tag Travel Form As Recommended Error', response.message, 'danger');
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

        $(document).on('click','#tag-as-approved',function() {
            const travel_form_id = $('#travel-form-id').text();
            const transaction = 'tag as approved';
    
            Swal.fire({
                title: 'Confirm Tagging of Travel As Approved',
                text: 'Are you sure you want to tag this travel form for approved?',
                icon: 'info',
                showCancelButton: !0,
                confirmButtonText: 'Approved',
                cancelButtonText: 'Cancel',
                confirmButtonClass: 'btn btn-info mt-2',
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
                                setNotification('Tag Travel Form As Approved Success', 'The travel form has been tagged as approved successfully.', 'success');
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
                                    showNotification('Tag Travel Form As Approved Error', response.message, 'danger');
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

        $(document).on('change','#toll_fee',function() {
            calculateEstimatedCost();
        });

        $(document).on('change','#accomodation',function() {
            calculateEstimatedCost();
        });

        $(document).on('change','#meals',function() {
            calculateEstimatedCost();
        });

        $(document).on('change','#other_expenses',function() {
            calculateEstimatedCost();
        });
    });
})(jQuery);

function travelFormTable(datatable_name, buttons = false, show_all = false){
    const type = 'travel form table';
    var settings;

    const column = [ 
        { 'data' : 'CREATED_BY' },
        { 'data' : 'CHECKED_BY' },
        { 'data' : 'CHECKED_DATE' },
        { 'data' : 'RECOMMENDED_BY' },
        { 'data' : 'RECOMMENDED_DATE' },
        { 'data' : 'APPROVAL_BY' },
        { 'data' : 'APPROVAL_DATE' },
        { 'data' : 'STATUS' },
        { 'data' : 'ACTION' }
    ];

    const column_definition = [
        { 'width': 'auto', 'aTargets': 0 },
        { 'width': 'auto', 'aTargets': 1 },
        { 'width': 'auto', 'aTargets': 2 },
        { 'width': 'auto', 'aTargets': 3 },
        { 'width': 'auto', 'aTargets': 4 },
        { 'width': 'auto', 'aTargets': 5 },
        { 'width': 'auto', 'aTargets': 6 },
        { 'width': 'auto', 'aTargets': 7 },
        { 'width': '15%','bSortable': false, 'aTargets': 8 }
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

function travelApprovalFormTable(datatable_name, buttons = false, show_all = false){
    const type = 'travel approval form table';
    var settings;

    const column = [ 
        { 'data' : 'CREATED_BY' },
        { 'data' : 'CHECKED_BY' },
        { 'data' : 'CHECKED_DATE' },
        { 'data' : 'RECOMMENDED_BY' },
        { 'data' : 'RECOMMENDED_DATE' },
        { 'data' : 'APPROVAL_BY' },
        { 'data' : 'APPROVAL_DATE' },
        { 'data' : 'STATUS' },
        { 'data' : 'ACTION' }
    ];

    const column_definition = [
        { 'width': 'auto', 'aTargets': 0 },
        { 'width': 'auto', 'aTargets': 1 },
        { 'width': 'auto', 'aTargets': 2 },
        { 'width': 'auto', 'aTargets': 3 },
        { 'width': 'auto', 'aTargets': 4 },
        { 'width': 'auto', 'aTargets': 5 },
        { 'width': 'auto', 'aTargets': 6 },
        { 'width': 'auto', 'aTargets': 7 },
        { 'width': '15%','bSortable': false, 'aTargets': 8 }
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

function itineraryTable(datatable_name, buttons = false, show_all = false){
    const type = 'itinerary table';
    const travel_form_id = $('#travel-form-id').text();
    var settings;

    const column = [ 
        { 'data' : 'ITINERARY_DATE' },
        { 'data' : 'CUSTOMER_NAME' },
        { 'data' : 'ITINERARY_DESTINATION' },
        { 'data' : 'ITINERARY_PURPOSE' },
        { 'data' : 'EXPECTED_TIME_OF_DEPARTURE' },
        { 'data' : 'EXPECTED_TIME_OF_ARRIVAL' },
        { 'data' : 'ACTION' }
    ];

    const column_definition = [
        { 'width': 'auto', 'aTargets': 0 },
        { 'width': 'auto', 'aTargets': 1 },
        { 'width': 'auto', 'aTargets': 2 },
        { 'width': 'auto', 'aTargets': 3 },
        { 'width': 'auto', 'aTargets': 4 },
        { 'width': 'auto', 'aTargets': 5 },
        { 'width': '15%','bSortable': false, 'aTargets': 6 }
    ];

    const length_menu = show_all ? [[-1], ['All']] : [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']];

    settings = {
        'ajax': { 
            'url' : 'view/_travel_form_generation.php',
            'method' : 'POST',
            'dataType': 'json',
            'data': {'type' : type, 'travel_form_id' : travel_form_id},
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
            recommended_by: {
                required: true
            },
            approval_by: {
                required: true
            },
        },
        messages: {
            recommended_by: {
                required: 'Please choose the recommended by'
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
            purpose_of_entry_exit: {
                required: true
            },
            department_id: {
                required: true
            },
            gate_pass_departure_date: {
                required: true
            }
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
            purpose_of_entry_exit: {
                required: 'Please enter the purpose of entry/exit'
            },
            department_id: {
                required: 'Please choose the department'
            },
            gate_pass_departure_date: {
                required: 'Please choose the departure date'
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

function itineraryForm(){
    $('#itinerary-form').validate({
        rules: {
            itinerary_date: {
                required: true
            },
            client_id: {
                required: true
            },
            itinerary_destination: {
                required: true
            },
            itinerary_purpose: {
                required: true
            },
            expected_time_of_departure: {
                required: true
            },
            expected_time_of_arrival: {
                required: true
            },
        },
        messages: {
            itinerary_date: {
                required: 'Please choose the itinerary date'
            },
            client_id: {
                required: 'Please choose the client'
            },
            itinerary_destination: {
                required: 'Please enter the destination'
            },
            itinerary_purpose: {
                required: 'Please enter the purpose'
            },
            expected_time_of_departure: {
                required: 'Please enter the expected time of departure'
            },
            expected_time_of_arrival: {
                required: 'Please enter the expected time of arrival'
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
            const transaction = 'save itinerary';
        
            $.ajax({
                type: 'POST',
                url: 'controller/travel-form-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction + '&travel_form_id=' + travel_form_id,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-itinerary-data');
                },
                success: function (response) {
                    if (response.success) {
                        const notificationMessage = response.insertRecord ? 'Insert Itinerary Success' : 'Update Itinerary Success';
                        const notificationDescription = response.insertRecord ? 'The itinerary has been inserted successfully.' : 'The itinerary has been updated successfully.';
                        
                        showNotification(notificationMessage, notificationDescription, 'success');
                        reloadDatatable('#itinerary-table');
                        $('#itinerary-offcanvas').offcanvas('hide');
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
                    enableFormSubmitButton('submit-itinerary-data', 'Save');
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
                        checkOptionExist('#recommended_by', response.recommendedBy, '');
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
                },
                complete: function(){
                    calculateEstimatedCost();
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
                        $('#purpose_of_entry_exit').val(response.purposeOfEntryExit);
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
        case 'get itinerary details':
            var itinerary_id = sessionStorage.getItem('itinerary_id');
            
            $.ajax({
                url: 'controller/travel-form-controller.php',
                method: 'POST',
                dataType: 'json',
                data: {
                    itinerary_id : itinerary_id, 
                    transaction : transaction
                },
                beforeSend: function() {
                    resetForm('travel-form');
                },
                success: function(response) {
                    if (response.success) {
                        $('#itinerary_id').val(itinerary_id);
                        $('#itinerary_date').val(response.itineraryDate);
                        $('#itinerary_destination').val(response.itineraryDestination);
                        $('#itinerary_purpose').val(response.itineraryPurpose);
                        $('#expected_time_of_departure').val(response.expectedTimeOfDeparture);
                        $('#expected_time_of_arrival').val(response.expectedTimeOfArrival);

                        checkOptionExist('#client_id', response.customerID, '');
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

function calculateEstimatedCost() {
    var tollFee = parseFloat($('#toll_fee').val().replace(/[$,]/g, ''));
    var accommodation = parseFloat($('#accomodation').val().replace(/[$,]/g, ''));
    var meals = parseFloat($('#meals').val().replace(/[$,]/g, ''));
    var otherExpenses = parseFloat($('#other_expenses').val().replace(/[$,]/g, ''));

    var total = tollFee + accommodation + meals + otherExpenses;

    $('#total_estimated_cost').val(total.toFixed(2));
}
