(function($) {
    'use strict';

    $(function() {
        if($('#pms-catalog-table').length){
            pmsCatalogTable('#pms-catalog-table');
        }

        if($('#pms-catalog-form').length){
            pmsCatalogForm();
        }

        if($('#pms-matrix-form').length){
            pmsMatrixForm();
        }

        if($('#pms-matrix-parts-form').length){
            pmsMatrixPartsForm();
        }

        if($('#pms-catalog-id').length){
            loadPMSMatrices();
            displayDetails('get pms catalog details');
        }

        $(document).on('click','.delete-pms-catalog',function() {
            const pms_catalog_id = $(this).data('pms-catalog-id');
            const transaction = 'delete pms catalog';
    
            Swal.fire({
                title: 'Confirm PMS Catalog Deletion',
                text: 'Are you sure you want to delete this pms catalog?',
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
                        url: 'controller/pms-catalog-controller.php',
                        dataType: 'json',
                        data: {
                            pms_catalog_id : pms_catalog_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                showNotification('Delete PMS Catalog Success', 'The pms catalog has been deleted successfully.', 'success');
                                reloadDatatable('#pms-catalog-table');
                            }
                            else {
                                if (response.isInactive) {
                                    setNotification('User Inactive', response.message, 'danger');
                                    window.location = 'logout.php?logout';
                                }
                                else if (response.notExist) {
                                    showNotification('Delete PMS Catalog Error', 'The pms catalog does not exist.', 'danger');
                                    reloadDatatable('#pms-catalog-table');
                                }
                                else {
                                    showNotification('Delete PMS Catalog Error', response.message, 'danger');
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

        $(document).on('click','#delete-pms-catalog',function() {
            let pms_catalog_id = [];
            const transaction = 'delete multiple pms catalog';

            $('.datatable-checkbox-children').each((index, element) => {
                if ($(element).is(':checked')) {
                    pms_catalog_id.push(element.value);
                }
            });
    
            if(pms_catalog_id.length > 0){
                Swal.fire({
                    title: 'Confirm Multiple PMS Catalogs Deletion',
                    text: 'Are you sure you want to delete these pms catalogs?',
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
                            url: 'controller/pms-catalog-controller.php',
                            dataType: 'json',
                            data: {
                                pms_catalog_id: pms_catalog_id,
                                transaction : transaction
                            },
                            success: function (response) {
                                if (response.success) {
                                    showNotification('Delete PMS Catalog Success', 'The selected pms catalogs have been deleted successfully.', 'success');
                                        reloadDatatable('#pms-catalog-table');
                                }
                                else {
                                    if (response.isInactive) {
                                        setNotification('User Inactive', response.message, 'danger');
                                        window.location = 'logout.php?logout';
                                    }
                                    else {
                                        showNotification('Delete PMS Catalog Error', response.message, 'danger');
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
                showNotification('Deletion Multiple PMS Catalog Error', 'Please select the pms catalogs you wish to delete.', 'danger');
            }
        });

        $(document).on('click','#delete-pms-catalog-details',function() {
            const pms_catalog_id = $('#pms-catalog-id').text();
            const transaction = 'delete pms catalog';
    
            Swal.fire({
                title: 'Confirm PMS Catalog Deletion',
                text: 'Are you sure you want to delete this pms catalog?',
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
                        url: 'controller/pms-catalog-controller.php',
                        dataType: 'json',
                        data: {
                            pms_catalog_id : pms_catalog_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                setNotification('Deleted PMS Catalog Success', 'The pms catalog has been deleted successfully.', 'success');
                                window.location = 'pms-catalog.php';
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
                                    showNotification('Delete PMS Catalog Error', response.message, 'danger');
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

        $(document).on('click','#lock-pms-catalog',function() {
            const pms_catalog_id = $('#pms-catalog-id').text();
            const transaction = 'lock pms catalog';
    
            Swal.fire({
                title: 'Confirm PMS Catalog Locking',
                text: 'Are you sure you want to lock this pms catalog?',
                icon: 'warning',
                showCancelButton: !0,
                confirmButtonText: 'Lock',
                cancelButtonText: 'Cancel',
                confirmButtonClass: 'btn btn-danger mt-2',
                cancelButtonClass: 'btn btn-secondary ms-2 mt-2',
                buttonsStyling: !1
            }).then(function(result) {
                if (result.value) {
                    $.ajax({
                        type: 'POST',
                        url: 'controller/pms-catalog-controller.php',
                        dataType: 'json',
                        data: {
                            pms_catalog_id : pms_catalog_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                setNotification('Lock PMS Catalog Success', 'The pms catalog has been locked successfully.', 'success');
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
                                    showNotification('Delete PMS Catalog Error', response.message, 'danger');
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

        $(document).on('click','#unlock-pms-catalog',function() {
            const pms_catalog_id = $('#pms-catalog-id').text();
            const transaction = 'unlock pms catalog';
    
            Swal.fire({
                title: 'Confirm PMS Catalog Unlocking',
                text: 'Are you sure you want to unlock this pms catalog?',
                icon: 'warning',
                showCancelButton: !0,
                confirmButtonText: 'Unlock',
                cancelButtonText: 'Cancel',
                confirmButtonClass: 'btn btn-danger mt-2',
                cancelButtonClass: 'btn btn-secondary ms-2 mt-2',
                buttonsStyling: !1
            }).then(function(result) {
                if (result.value) {
                    $.ajax({
                        type: 'POST',
                        url: 'controller/pms-catalog-controller.php',
                        dataType: 'json',
                        data: {
                            pms_catalog_id : pms_catalog_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                setNotification('Unlock PMS Catalog Success', 'The pms catalog has been unlocked successfully.', 'success');
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
                                    showNotification('Delete PMS Catalog Error', response.message, 'danger');
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
            discardCreate('pms-catalog.php');
        });

        $(document).on('click','#edit-form',function() {
            displayDetails('get pms catalog details');

            enableForm();
        });

        $(document).on('click','#duplicate-pms-catalog',function() {
            const pms_catalog_id = $('#pms-catalog-id').text();
            const transaction = 'duplicate pms catalog';
    
            Swal.fire({
                title: 'Confirm PMS Catalog Duplication',
                text: 'Are you sure you want to duplicate this pms catalog?',
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
                        url: 'controller/pms-catalog-controller.php',
                        dataType: 'json',
                        data: {
                            pms_catalog_id : pms_catalog_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                setNotification('Duplicate PMS Catalog Success', 'The pms catalog has been duplicated successfully.', 'success');
                                window.location = 'pms-catalog.php?id=' + response.pmsCatalogID;
                            }
                            else {
                                if (response.isInactive) {
                                    setNotification('User Inactive', response.message, 'danger');
                                    window.location = 'logout.php?logout';
                                }
                                else if (response.notExist) {
                                    showNotification('Duplicate PMS Catalog Error', 'The pms catalog does not exist.', 'danger');
                                    reloadDatatable('#pms-catalog-table');
                                }
                                else {
                                    showNotification('Duplicate PMS Catalog Error', response.message, 'danger');
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

        $(document).on('change','#product_subcategory_id',function() {
            const pms_catalog_id = $('#pms-catalog-id').text();
            const product_subcategory_id = $('#product_subcategory_id').val();
            const transaction = 'save pms catalog subcategory';
    
            $.ajax({
                type: 'POST',
                url: 'controller/pms-catalog-controller.php',
                dataType: 'json',
                data: {
                    pms_catalog_id : pms_catalog_id, 
                    product_subcategory_id : product_subcategory_id,
                    transaction : transaction
                },
                success: function (response) {
                    if (!response.success) {
                        if (response.isInactive) {
                            setNotification('User Inactive', response.message, 'danger');
                            window.location = 'logout.php?logout';
                        }
                        else {
                            showNotification('Save Product Category Error', response.message, 'danger');
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
        });

        $(document).on('click','.add-pms-parts',function() {
            resetModalForm('pms-matrix-parts-form');
            $('#alternate_part_id').val(null).trigger('change');
            
            const pms_matrix_id = $(this).data('id');

            $('#pms_matrix_id2').val(pms_matrix_id);
        });

        $(document).on('click','.edit-pms-matrix',function() {
            const pms_matrix_id = $(this).data('id');

            $('#pms_matrix_id').val(pms_matrix_id);
            displayDetails('get pms matrix details');
        });

        $(document).on('click','.delete-pms-matrix',function() {
            const pms_matrix_id = $(this).data('id');
            const transaction = 'delete pms matrix';

            Swal.fire({
                title: 'Confirm PMS Matrix Deletion',
                text: 'Are you sure you want to delete this pms matrix?',
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
                        url: 'controller/pms-catalog-controller.php',
                        dataType: 'json',
                        data: {
                            pms_matrix_id : pms_matrix_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                showNotification('Deleted PMS Matrix Success', 'The pms matrix has been deleted successfully.', 'success');

                                loadPMSMatrices();
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
                                    showNotification('Delete PMS Catalog Error', response.message, 'danger');
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

        $(document).on('click','.edit-pms-part',function() {
            const pms_matrix_part_id = $(this).data('id');
            
            $('#pms_matrix_part_id').val(pms_matrix_part_id);
            displayDetails('get pms matrix part details');
        });

        $(document).on('click','.delete-pms-part',function() {
            const pms_matrix_part_id = $(this).data('id');
            const transaction = 'delete pms matrix part';

            Swal.fire({
                title: 'Confirm PMS Matrix Part Deletion',
                text: 'Are you sure you want to delete this pms matrix part?',
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
                        url: 'controller/pms-catalog-controller.php',
                        dataType: 'json',
                        data: {
                            pms_matrix_part_id : pms_matrix_part_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                showNotification('Deleted PMS Matrix Part Success', 'The pms matrix part has been deleted successfully.', 'success');

                                loadPMSMatrices();
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
                                    showNotification('Delete PMS Catalog Error', response.message, 'danger');
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

        $(document).on('click','#create-pms-matrix',function() {
            resetModalForm('pms-matrix-form');
        });

        $(document).on('change','#consumables',function() {
            calculateConsumables();
        });

        $(document).on('change','#consumables_quantity',function() {
            calculateConsumables();
        });

        $(document).on('change','#labor_cost',function() {
            calculateLabor();
        });

        $(document).on('change','#labor_quantity',function() {
            calculateLabor();
        });
    });
})(jQuery);

function pmsCatalogTable(datatable_name, buttons = false, show_all = false){
    const type = 'pms catalog table';
    var settings;

    const column = [ 
        { 'data' : 'CHECK_BOX' },
        { 'data' : 'PMS_CATALOG' },
        { 'data' : 'COMPANY' },
        { 'data' : 'ACTION' }
    ];

    const column_definition = [
        { 'width': '1%','bSortable': false, 'aTargets': 0 },
        { 'width': '64%', 'aTargets': 1 },
        { 'width': '20%', 'aTargets': 2 },
        { 'width': '15%','bSortable': false, 'aTargets': 3 }
    ];

    const length_menu = show_all ? [[-1], ['All']] : [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']];

    settings = {
        'ajax': { 
            'url' : 'view/_pms_catalog_generation.php',
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

function pmsCatalogForm(){
    $('#pms-catalog-form').validate({
        rules: {
            pms_catalog_name: {
                required: true
            },
            pms_catalog_description: {
                required: true
            },
            company_id: {
                required: true
            }
        },
        messages: {
            pms_catalog_name: {
                required: 'Please enter the pms catalog name'
            },
            pms_catalog_description: {
                required: 'Please enter the pms catalog description'
            },
            company_id: {
                required: 'Please choose the company'
            }
        },
        errorPlacement: function (error, element) {
            if (element.hasClass('select2') || element.hasClass('modal-select2')) {
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
            const pms_catalog_id = $('#pms-catalog-id').text();
            const transaction = 'save pms catalog';
        
            $.ajax({
                type: 'POST',
                url: 'controller/pms-catalog-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction + '&pms_catalog_id=' + pms_catalog_id,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-data');
                },
                success: function (response) {
                    if (response.success) {
                        const notificationMessage = response.insertRecord ? 'Insert PMS Catalog Success' : 'Update PMS Catalog Success';
                        const notificationDescription = response.insertRecord ? 'The pms catalog has been inserted successfully.' : 'The pms catalog has been updated successfully.';
                        
                        setNotification(notificationMessage, notificationDescription, 'success');
                        window.location = 'pms-catalog.php?id=' + response.pmsCatalogID;
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

function pmsMatrixForm(){
    $('#pms-matrix-form').validate({
        rules: {
            odometer: {
                required: true
            },
            months_interval: {
                required: true
            },
            consumables: {
                required: true
            },
            consumables_quantity: {
                required: true
            },
            labor_cost: {
                required: true
            },
            labor_quantity: {
                required: true
            }
        },
        messages: {
            odometer: {
                required: 'Please enter the odometer reading'
            },
            months_interval: {
                required: 'Please enter the months interval'
            },
            consumables: {
                required: 'Please enter the consumables cost'
            },
            consumables_quantity: {
                required: 'Please enter the consumables quantity'
            },
            labor_cost: {
                required: 'Please enter the labor cost'
            },
            labor_quantity: {
                required: 'Please enter the labor hours'
            },
        },
        errorPlacement: function (error, element) {
            if (element.hasClass('select2') || element.hasClass('modal-select2')) {
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
            const pms_catalog_id = $('#pms-catalog-id').text();
            const transaction = 'save pms matrix';
        
            $.ajax({
                type: 'POST',
                url: 'controller/pms-catalog-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction + '&pms_catalog_id=' + pms_catalog_id,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-pms-matrix');
                },
                success: function (response) {
                    if (response.success) {
                        const notificationMessage = response.insertRecord ? 'Insert PMS Matrix Success' : 'Update PMS Matrix Success';
                        const notificationDescription = response.insertRecord ? 'The pms matrix has been inserted successfully.' : 'The pms matrix has been updated successfully.';

                        $('#pms-matrix-offcanvas').offcanvas('hide');

                        showNotification(notificationMessage, notificationDescription, 'success');
                        
                        loadPMSMatrices();
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
                    enableFormSubmitButton('submit-pms-matrix', 'Submit');
                }
            });
        
            return false;
        }
    });
}

function pmsMatrixPartsForm(){
    $('#pms-matrix-parts-form').validate({
        rules: {
            part_id: {
                required: true
            },
            required_quantity: {
                required: true
            },
            unit_id: {
                required: true
            },
        },
        messages: {
            part_id: {
                required: 'Please select a part'
            },
            required_quantity: {
                required: 'Please enter the required quantity'
            },
            unit_id: {
                required: 'Please select a unit'
            },
        },
        errorPlacement: function (error, element) {
            if (element.hasClass('select2') || element.hasClass('modal-select2')) {
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
            const pms_catalog_id = $('#pms-catalog-id').text();
            const transaction = 'save pms matrix part';
        
            $.ajax({
                type: 'POST',
                url: 'controller/pms-catalog-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction + '&pms_catalog_id=' + pms_catalog_id,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-pms-matrix-parts');
                },
                success: function (response) {
                    if (response.success) {
                        const notificationMessage = response.insertRecord ? 'Insert PMS Matrix Part Success' : 'Update PMS Matrix Part Success';
                        const notificationDescription = response.insertRecord ? 'The pms matrix part has been inserted successfully.' : 'The pms matrix part has been updated successfully.';

                        $('#pms-matrix-parts-offcanvas').offcanvas('hide');

                        showNotification(notificationMessage, notificationDescription, 'success');
                        
                        loadPMSMatrices();
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
                    enableFormSubmitButton('submit-pms-matrix-parts', 'Submit');
                }
            });
        
            return false;
        }
    });
}

function loadPMSMatrices() {
    const pms_catalog_id = $('#pms-catalog-id').text();

    $.ajax({
        url: 'controller/pms-catalog-controller.php',
        type: 'POST',
        data: {
            transaction : 'get pms matrices',
            pms_catalog_id : pms_catalog_id,
        },
        dataType: 'json',
        success: function (response) {

            $('#pms-matrix-container').empty();

            if(response.length == 0){

                $('#pms-matrix-container').html(`
                    <div class="col-lg-12">
                        <div class="alert alert-warning mb-0">
                            No PMS Matrix found.
                        </div>
                    </div>
                `);

                return;
            }

            $.each(response, function(index, matrix){

                const html = generatePMSMatrixCard(matrix);

                console.log(html);

                $('#pms-matrix-container').append(html);

            });

        }
    });

}

function generatePMSMatrixCard(matrix) {

    let partsRows = '';
    let status = $('#status').val();
    let matrixbuttons = '';
    let tableButtons = '';

    if (status === 'open') {

        matrixbuttons = `
            <button
                class="btn btn-sm btn-success me-1 add-pms-parts"
                data-bs-toggle="offcanvas"
                data-bs-target="#pms-matrix-parts-offcanvas"
                data-id="${matrix.pms_matrix_id}">
                Add Parts
            </button>

            <button
                class="btn btn-sm btn-primary me-1 edit-pms-matrix"
                data-bs-toggle="offcanvas"
                data-bs-target="#pms-matrix-offcanvas"
                data-id="${matrix.pms_matrix_id}">
                Update
            </button>

            <button
                class="btn btn-sm btn-danger delete-pms-matrix"
                data-id="${matrix.pms_matrix_id}">
                Delete
            </button>
        `;
    }

    if (matrix.parts && matrix.parts.length > 0) {

        $.each(matrix.parts, function (i, part) {

            let alternateParts = '';
            const alternatePartsList = part.alternate_parts || [];

            if (status === 'open') {
                tableButtons = `
                    <button
                        type="button"
                        class="btn btn-sm btn-icon btn-light-primary edit-pms-part me-1"
                        data-bs-toggle="offcanvas"
                        data-bs-target="#pms-matrix-parts-offcanvas"
                        data-id="${part.pms_matrix_part_id}">
                        <i class="ti ti-eye"></i>
                    </button>

                    <button
                        type="button"
                        class="btn btn-sm btn-icon btn-light-danger delete-pms-part"
                        data-id="${part.pms_matrix_part_id}">
                        <i class="ti ti-trash"></i>
                    </button>
                `;
            }

            if (alternatePartsList.length > 0) {

                $.each(alternatePartsList, function (index, alt) {

                    alternateParts += `
                        <span class="badge badge-light-warning fs-8 py-1 px-2 me-1 mb-1">
                            ${alt.part_name}
                        </span>
                    `;

                });

            } else {

                alternateParts = `<span class="text-muted">-</span>`;

            }

            partsRows += `
                <tr>

                    <td class="fw-semibold">
                        ${part.part_name}
                    </td>

                    <td class="text-center">
                        ${part.required_quantity}
                    </td>

                    <td>
                        ${part.unit_name}
                    </td>

                    <td>
                        ${alternateParts}
                    </td>

                    <td class="text-end">
                        ${tableButtons}
                    </td>

                </tr>
            `;

        });

    } else {

        partsRows = `
            <tr>
                <td colspan="5" class="text-center py-5">

                    <i class="ki-outline ki-package fs-2x text-gray-400 d-block mb-2"></i>

                    <div class="fw-semibold text-gray-700">
                        No required parts added yet.
                    </div>

                </td>
            </tr>
        `;

    }

    return `
        <div class="col-12 mb-1">

            <div class="card shadow-sm border-0">

                <div class="card-header d-flex justify-content-between align-items-center py-3">

                    <div>

                        <h4 class="fw-bold mb-0">
                            ${Number(matrix.odometer).toLocaleString()} KM
                        </h4>

                        <span class="text-muted fs-8">
                            Preventive Maintenance Matrix
                        </span>

                    </div>

                    <div class="card-toolbar">
                        ${matrixbuttons}
                    </div>

                </div>

                <div class="card-body pt-3 pb-4">

                    <div class="row g-2 mb-4">

                        <div class="col-md-2">

                            <div class="border rounded p-3 h-100">

                                <div class="text-muted fs-8">
                                    Odometer
                                </div>

                                <div class="fw-bold fs-5">
                                     ${Number(matrix.odometer).toLocaleString()} KM
                                </div>

                            </div>

                        </div>

                        <div class="col-md-2">

                            <div class="border rounded p-3 h-100">

                                <div class="text-muted fs-8">
                                    Interval
                                </div>

                                <div class="fw-bold fs-5">
                                    ${matrix.months_interval} Month
                                </div>

                            </div>

                        </div>

                        <div class="col-md-2">

                            <div class="border rounded p-3 h-100">

                                <div class="text-muted fs-8">
                                    Consumables Cost
                                </div>

                                <div class="fw-bold fs-5 text-success">
                                    ₱ ${Number(matrix.consumables).toLocaleString()}
                                </div>

                            </div>

                        </div>
                        <div class="col-md-2">

                            <div class="border rounded p-3 h-100">

                                <div class="text-muted fs-8">
                                    Consumables Total
                                </div>

                                <div class="fw-bold fs-5 text-success">
                                    ₱ ${Number(matrix.consumables_total).toLocaleString()}
                                </div>

                            </div>

                        </div>

                        <div class="col-md-2">

                            <div class="border rounded p-3 h-100">

                                <div class="text-muted fs-8">
                                    Labor Cost
                                </div>

                                <div class="fw-bold fs-5 text-primary">
                                    ₱ ${Number(matrix.labor_cost).toLocaleString()}
                                </div>

                            </div>

                        </div>

                        <div class="col-md-2">

                            <div class="border rounded p-3 h-100">

                                <div class="text-muted fs-8">
                                    Labor Total
                                </div>

                                <div class="fw-bold fs-5 text-primary">
                                    ₱ ${Number(matrix.labor_total).toLocaleString()}
                                </div>

                            </div>

                        </div>

                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-3">

                        <h6 class="fw-bold mb-0">
                            Required Parts
                        </h6>

                        <span class="badge badge-light-primary">
                            ${matrix.parts.length} Part${matrix.parts.length !== 1 ? 's' : ''}
                        </span>

                    </div>

                    <div class="table-responsive">

                        <table class="table table-sm table-row-dashed align-middle">

                            <thead>

                                <tr class="fw-bold text-gray-700">

                                    <th>Part Name</th>

                                    <th width="70" class="text-center">
                                        Qty
                                    </th>

                                    <th width="110">
                                        Unit
                                    </th>

                                    <th width="260">
                                        Alternate Parts
                                    </th>

                                    <th width="90" class="text-end">
                                        Actions
                                    </th>

                                </tr>

                            </thead>

                            <tbody>

                                ${partsRows}

                            </tbody>

                        </table>

                    </div>

                </div>

            </div>

        </div>
    `;
}

function displayDetails(transaction){
    switch (transaction) {
        case 'get pms catalog details':
            const pms_catalog_id = $('#pms-catalog-id').text();
            
            $.ajax({
                url: 'controller/pms-catalog-controller.php',
                method: 'POST',
                dataType: 'json',
                data: {
                    pms_catalog_id : pms_catalog_id, 
                    transaction : transaction
                },
                beforeSend: function() {
                    resetModalForm('pms-catalog-form');
                },
                success: function(response) {
                    if (response.success) {
                        $('#pms_catalog_id').val(pms_catalog_id);
                        $('#pms_catalog_name').val(response.pmsCatalogName);
                        $('#description').val(response.description);

                        checkOptionExist('#company_id', response.companyId, '');

                        $('#product_subcategory_id')
                        .val(response.productSubcategoryIds)
                        .trigger('change');
                    } 
                    else {
                        if(response.isInactive){
                            window.location = 'logout.php?logout';
                        }
                        else{
                            showNotification('Get PMS Catalog Details Error', response.message, 'danger');
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
        case 'get pms matrix details':
            const pms_matrix_id = $('#pms_matrix_id').val();

            $.ajax({
                url: 'controller/pms-catalog-controller.php',
                method: 'POST',
                dataType: 'json',
                data: {
                    pms_matrix_id : pms_matrix_id, 
                    transaction : transaction
                },
                beforeSend: function() {
                    resetModalForm('pms-catalog-form');
                },
                success: function(response) {
                    if (response.success) {
                        $('#pms_matrix_id').val(pms_matrix_id);
                        $('#odometer').val(response.odometer);
                        $('#months_interval').val(response.monthsInterval);
                        $('#consumables').val(response.consumables);
                        $('#consumables_quantity').val(response.consumables_quantity);
                        $('#consumables_total').val(response.consumables_total);
                        $('#labor_cost').val(response.laborCost);
                        $('#labor_quantity').val(response.labor_quantity);
                        
                        calculateConsumables();
                        calculateLabor();
                    } 
                    else {
                        if(response.isInactive){
                            window.location = 'logout.php?logout';
                        }
                        else{
                            showNotification('Get PMS Matrix Details Error', response.message, 'danger');
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
        case 'get pms matrix part details':
            const pms_matrix_part_id = $('#pms_matrix_part_id').val();

            $.ajax({
                url: 'controller/pms-catalog-controller.php',
                method: 'POST',
                dataType: 'json',
                data: {
                    pms_matrix_part_id : pms_matrix_part_id,
                    transaction : transaction
                },
                beforeSend: function() {
                    resetModalForm('pms-catalog-form');
                },
                success: function(response) {
                    if (response.success) {
                        $('#required_quantity').val(response.requiredQuantity);
                        
                         checkOptionExist('#part_id', response.partId, '');
                         checkOptionExist('#unit_id', response.unitId, '');

                        $('#alternate_part_id')
                        .val(response.alternateParts)
                        .trigger('change');
                    } 
                    else {
                        if(response.isInactive){
                            window.location = 'logout.php?logout';
                        }
                        else{
                            showNotification('Get PMS Matrix Details Error', response.message, 'danger');
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

function calculateConsumables(){
    let consumable = $('#consumables').val();
    let consumables_quantity = $('#consumables_quantity').val();
    let total = consumable * consumables_quantity;

    $('#consumables_total').val(total);
}

function calculateLabor(){
    let labor_cost = $('#labor_cost').val();
    let labor_quantity = $('#labor_quantity').val();
    let total = labor_cost * labor_quantity;

    $('#labor_total').val(total);
}