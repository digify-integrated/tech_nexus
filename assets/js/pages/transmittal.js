(function($) {
    'use strict';

    $(function() {
        if($('#transmittal-table').length){
            transmittalTable('#transmittal-table');
        }

        if($('#transmittal-form').length){
            transmittalForm();
        }

        if($('#transmittal-id').length){
            displayDetails('get transmittal details');
        }

        $(document).on('change','#receiver_department',function() {
            $('#receiver_id').empty();
    
            if(this.value != ''){
                generateDepartmentEmployeeOptions(this.value, '');
                document.getElementById('receiver_id').disabled = false;
            }
            else{
                var newOption = new Option('--', '', false, false);
                $('#receiver_id').append(newOption);
                document.getElementById('receiver_id').disabled = true;
            }
        });

        $(document).on('click','.delete-transmittal',function() {
            const transmittal_id = $(this).data('transmittal-id');
            const transaction = 'delete transmittal';
    
            Swal.fire({
                title: 'Confirm Transmittal Deletion',
                text: 'Are you sure you want to delete this transmittal?',
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
                        url: 'controller/transmittal-controller.php',
                        dataType: 'json',
                        data: {
                            transmittal_id : transmittal_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                showNotification('Delete Transmittal Success', 'The transmittal has been deleted successfully.', 'success');
                                reloadDatatable('#transmittal-table');
                            }
                            else {
                                if (response.isInactive) {
                                    setNotification('User Inactive', response.message, 'danger');
                                    window.location = 'logout.php?logout';
                                }
                                else if (response.notExist) {
                                    showNotification('Delete Transmittal Error', 'The transmittal does not exist.', 'danger');
                                    reloadDatatable('#transmittal-table');
                                }
                                else {
                                    showNotification('Delete Transmittal Error', response.message, 'danger');
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

        $(document).on('click','#delete-transmittal',function() {
            let transmittal_id = [];
            const transaction = 'delete multiple transmittal';

            $('.datatable-checkbox-children').each((index, element) => {
                if ($(element).is(':checked')) {
                    transmittal_id.push(element.value);
                }
            });
    
            if(transmittal_id.length > 0){
                Swal.fire({
                    title: 'Confirm Multiple Transmittals Deletion',
                    text: 'Are you sure you want to delete these transmittals?',
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
                            url: 'controller/transmittal-controller.php',
                            dataType: 'json',
                            data: {
                                transmittal_id: transmittal_id,
                                transaction : transaction
                            },
                            success: function (response) {
                                if (response.success) {
                                    showNotification('Delete Transmittal Success', 'The selected transmittals have been deleted successfully.', 'success');
                                        reloadDatatable('#transmittal-table');
                                }
                                else {
                                    if (response.isInactive) {
                                        setNotification('User Inactive', response.message, 'danger');
                                        window.location = 'logout.php?logout';
                                    }
                                    else {
                                        showNotification('Delete Transmittal Error', response.message, 'danger');
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
                showNotification('Deletion Multiple Transmittal Error', 'Please select the transmittals you wish to delete.', 'danger');
            }
        });

        $(document).on('click','#delete-transmittal-details',function() {
            const transmittal_id = $('#transmittal-id').text();
            const transaction = 'delete transmittal';
    
            Swal.fire({
                title: 'Confirm Transmittal Deletion',
                text: 'Are you sure you want to delete this transmittal?',
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
                        url: 'controller/transmittal-controller.php',
                        dataType: 'json',
                        data: {
                            transmittal_id : transmittal_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                setNotification('Deleted Transmittal Success', 'The transmittal has been deleted successfully.', 'success');
                                window.location = 'transmittal.php';
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
                                    showNotification('Delete Transmittal Error', response.message, 'danger');
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

        $(document).on('click','#transmit-transmittal',function() {
            let transmittal_id = [];
            const transaction = 'transmit multiple transmittal';

            $('.datatable-checkbox-children').each((index, element) => {
                if ($(element).is(':checked')) {
                    transmittal_id.push(element.value);
                }
            });
    
            if(transmittal_id.length > 0){
                Swal.fire({
                    title: 'Confirm Multiple Transmittal Transmissions',
                    text: 'Are you sure you want to transmit these transmittals?',
                    icon: 'info',
                    showCancelButton: !0,
                    confirmButtonText: 'Transmit Transmittal',
                    cancelButtonText: 'Cancel',
                    confirmButtonClass: 'btn btn-info mt-2',
                    cancelButtonClass: 'btn btn-secondary ms-2 mt-2',
                    buttonsStyling: !1
                }).then(function(result) {
                    if (result.value) {
                        $.ajax({
                            type: 'POST',
                            url: 'controller/transmittal-controller.php',
                            dataType: 'json',
                            data: {
                                transmittal_id: transmittal_id,
                                transaction : transaction
                            },
                            success: function (response) {
                                if (response.success) {
                                    showNotification('Transmit Transmittal Success', 'The selected transmittals have been transmitted successfully.', 'success');
                                    reloadDatatable('#transmittal-table');
                                }
                                else {
                                    if (response.isInactive) {
                                        setNotification('User Inactive', response.message, 'danger');
                                        window.location = 'logout.php?logout';
                                    }
                                    else {
                                        showNotification('Transmit Transmittal Error', response.message, 'danger');
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
                showNotification('Transmit Multiple Transmittal Error', 'Please select the transmittals you wish to transmit.', 'danger');
            }
        });

        $(document).on('click','#transmit-transmittal-details',function() {
            const transmittal_id = $('#transmittal-id').text();
            const transaction = 'transmit transmittal';
    
            Swal.fire({
                title: 'Confirm Transmittal Transmission',
                text: 'Are you sure you want to transmit this transmittal?',
                icon: 'info',
                showCancelButton: !0,
                confirmButtonText: 'Transmit Transmittal',
                cancelButtonText: 'Cancel',
                confirmButtonClass: 'btn btn-info mt-2',
                cancelButtonClass: 'btn btn-secondary ms-2 mt-2',
                buttonsStyling: !1
            }).then(function(result) {
                if (result.value) {
                    $.ajax({
                        type: 'POST',
                        url: 'controller/transmittal-controller.php',
                        dataType: 'json',
                        data: {
                            transmittal_id : transmittal_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                setNotification('Transmit Transmittal Success', 'The transmittal has been transmitted successfully.', 'success');
                                location.reload();
                            }
                            else {
                                if (response.isInactive) {
                                    setNotification('User Inactive', response.message, 'danger');
                                    window.location = 'logout.php?logout';
                                }
                                else if (response.notDraft) {
                                    setNotification('Transmit Transmittal Error', 'The transmittal cannot be transmitted because its status is not set to draft.', 'danger');
                                    location.reload();
                                }
                                else if (response.notTransmitter) {
                                    setNotification('Transmit Transmittal Error', 'You are not authorized to transmit the transmittal as you are not assigned as the transmitter.', 'danger');
                                    location.reload();
                                }
                                else if (response.notExist) {
                                    window.location = '404.php';
                                }
                                else {
                                    showNotification('Transmit Transmittal Error', response.message, 'danger');
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

        $(document).on('click','#receive-transmittal',function() {
            let transmittal_id = [];
            const transaction = 'receive multiple transmittal';

            $('.datatable-checkbox-children').each((index, element) => {
                if ($(element).is(':checked')) {
                    transmittal_id.push(element.value);
                }
            });
    
            if(transmittal_id.length > 0){
                Swal.fire({
                    title: 'Confirm Receipt of Multiple Transmittals',
                    text: 'Are you sure you want to receive these transmittals?',
                    icon: 'info',
                    showCancelButton: !0,
                    confirmButtonText: 'Receive Transmittal',
                    cancelButtonText: 'Cancel',
                    confirmButtonClass: 'btn btn-success mt-2',
                    cancelButtonClass: 'btn btn-secondary ms-2 mt-2',
                    buttonsStyling: !1
                }).then(function(result) {
                    if (result.value) {
                        $.ajax({
                            type: 'POST',
                            url: 'controller/transmittal-controller.php',
                            dataType: 'json',
                            data: {
                                transmittal_id: transmittal_id,
                                transaction : transaction
                            },
                            success: function (response) {
                                if (response.success) {
                                    showNotification('Receive Transmittal Success', 'The selected transmittals have been received successfully.', 'success');
                                        reloadDatatable('#transmittal-table');
                                }
                                else {
                                    if (response.isInactive) {
                                        setNotification('User Inactive', response.message, 'danger');
                                        window.location = 'logout.php?logout';
                                    }
                                    else {
                                        showNotification('Receive Transmittal Error', response.message, 'danger');
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
                showNotification('Receive Multiple Transmittal Error', 'Please select the transmittals you wish to receive.', 'danger');
            }
        });

        $(document).on('click','#receive-transmittal-details',function() {
            const transmittal_id = $('#transmittal-id').text();
            const transaction = 'receive transmittal';
    
            Swal.fire({
                title: 'Confirm Receipt of Transmittal',
                text: 'Are you sure you want to receive this transmittal?',
                icon: 'info',
                showCancelButton: !0,
                confirmButtonText: 'Receive Transmittal',
                cancelButtonText: 'Cancel',
                confirmButtonClass: 'btn btn-success mt-2',
                cancelButtonClass: 'btn btn-secondary ms-2 mt-2',
                buttonsStyling: !1
            }).then(function(result) {
                if (result.value) {
                    $.ajax({
                        type: 'POST',
                        url: 'controller/transmittal-controller.php',
                        dataType: 'json',
                        data: {
                            transmittal_id : transmittal_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                setNotification('Receive Transmittal Success', 'The transmittal has been received successfully.', 'success');
                                location.reload();
                            }
                            else {
                                if (response.isInactive) {
                                    setNotification('User Inactive', response.message, 'danger');
                                    window.location = 'logout.php?logout';
                                }
                                else if (response.notTransmitted) {
                                    setNotification('Receive Transmittal Error', 'The transmittal cannot be received because its status is not set to transmitted or re-transmitted.', 'danger');
                                    location.reload();
                                }
                                else if (response.notReceiver) {
                                    setNotification('Receive Transmittal Error', 'You are not authorized to receive the transmittal as you are not assigned as the receiver.', 'danger');
                                    location.reload();
                                }
                                else if (response.notExist) {
                                    window.location = '404.php';
                                }
                                else {
                                    showNotification('Receive Transmittal Error', response.message, 'danger');
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

        $(document).on('click','#retransmit-transmittal-details',function() {
            const transmittal_id = $('#transmittal-id').text();
            const transaction = 'retransmit transmittal';
    
            Swal.fire({
                title: 'Confirm Transmittal Re-Transmit',
                text: 'Are you sure you want to re-transmit this transmittal?',
                icon: 'info',
                showCancelButton: !0,
                confirmButtonText: 'Re-Transmit Transmittal',
                cancelButtonText: 'Cancel',
                confirmButtonClass: 'btn btn-success mt-2',
                cancelButtonClass: 'btn btn-secondary ms-2 mt-2',
                buttonsStyling: !1
            }).then(function(result) {
                if (result.value) {
                    $.ajax({
                        type: 'POST',
                        url: 'controller/transmittal-controller.php',
                        dataType: 'json',
                        data: {
                            transmittal_id : transmittal_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                setNotification('Re-Transmit Transmittal Success', 'The transmittal has been re-transmitted successfully.', 'success');
                                location.reload();
                            }
                            else {
                                if (response.isInactive) {
                                    setNotification('User Inactive', response.message, 'danger');
                                    window.location = 'logout.php?logout';
                                }
                                else if (response.notReceived) {
                                    setNotification('Re-Transmit Transmittal Error', 'The transmittal cannot be re-transmitted because its status is not set to received.', 'danger');
                                    location.reload();
                                }
                                else if (response.notTransmitter) {
                                    setNotification('Re-Transmit Transmittal Error', 'You are not authorized to re-transmit the transmittal as you are not assigned as the transmitter.', 'danger');
                                    location.reload();
                                }
                                else if (response.notExist) {
                                    window.location = '404.php';
                                }
                                else {
                                    showNotification('Re-Transmit Transmittal Error', response.message, 'danger');
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


        $(document).on('click','#file-transmittal',function() {
            let transmittal_id = [];
            const transaction = 'file multiple transmittal';

            $('.datatable-checkbox-children').each((index, element) => {
                if ($(element).is(':checked')) {
                    transmittal_id.push(element.value);
                }
            });
    
            if(transmittal_id.length > 0){
                Swal.fire({
                    title: 'Confirm File Multiple Transmittals',
                    text: 'Are you sure you want to file these transmittals?',
                    icon: 'info',
                    showCancelButton: !0,
                    confirmButtonText: 'File Transmittal',
                    cancelButtonText: 'Cancel',
                    confirmButtonClass: 'btn btn-info mt-2',
                    cancelButtonClass: 'btn btn-secondary ms-2 mt-2',
                    buttonsStyling: !1
                }).then(function(result) {
                    if (result.value) {
                        $.ajax({
                            type: 'POST',
                            url: 'controller/transmittal-controller.php',
                            dataType: 'json',
                            data: {
                                transmittal_id: transmittal_id,
                                transaction : transaction
                            },
                            success: function (response) {
                                if (response.success) {
                                    showNotification('File Transmittal Success', 'The selected transmittals have been filed successfully.', 'success');
                                    reloadDatatable('#transmittal-table');
                                }
                                else {
                                    if (response.isInactive) {
                                        setNotification('User Inactive', response.message, 'danger');
                                        window.location = 'logout.php?logout';
                                    }
                                    else {
                                        showNotification('File Transmittal Error', response.message, 'danger');
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
                showNotification('File Multiple Transmittal Error', 'Please select the transmittals you wish to file.', 'danger');
            }
        });

        $(document).on('click','#file-transmittal-details',function() {
            const transmittal_id = $('#transmittal-id').text();
            const transaction = 'file transmittal';
    
            Swal.fire({
                title: 'Confirm Transmittal File',
                text: 'Are you sure you want to file this transmittal?',
                icon: 'info',
                showCancelButton: !0,
                confirmButtonText: 'File Transmittal',
                cancelButtonText: 'Cancel',
                confirmButtonClass: 'btn btn-info mt-2',
                cancelButtonClass: 'btn btn-secondary ms-2 mt-2',
                buttonsStyling: !1
            }).then(function(result) {
                if (result.value) {
                    $.ajax({
                        type: 'POST',
                        url: 'controller/transmittal-controller.php',
                        dataType: 'json',
                        data: {
                            transmittal_id : transmittal_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                setNotification('File Transmittal Success', 'The transmittal has been filed successfully.', 'success');
                                location.reload();
                            }
                            else {
                                if (response.isInactive) {
                                    setNotification('User Inactive', response.message, 'danger');
                                    window.location = 'logout.php?logout';
                                }
                                else if (response.notReceived) {
                                    setNotification('Receive Transmittal Error', 'The transmittal cannot be filed because its status is not set to received.', 'danger');
                                    location.reload();
                                }
                                else if (response.notReceiver) {
                                    setNotification('Receive Transmittal Error', 'You are not authorized to file the transmittal as you are not assigned as the receiver.', 'danger');
                                    location.reload();
                                }
                                else if (response.notExist) {
                                    window.location = '404.php';
                                }
                                else {
                                    showNotification('File Transmittal Error', response.message, 'danger');
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

        $(document).on('click','#cancel-transmittal',function() {
            let transmittal_id = [];
            const transaction = 'cancel multiple transmittal';

            $('.datatable-checkbox-children').each((index, element) => {
                if ($(element).is(':checked')) {
                    transmittal_id.push(element.value);
                }
            });
    
            if(transmittal_id.length > 0){
                Swal.fire({
                    title: 'Confirm Cancellation of Multiple Transmittals',
                    text: 'Are you sure you want to cancel these transmittals?',
                    icon: 'warning',
                    showCancelButton: !0,
                    confirmButtonText: 'Cancel Transmittal',
                    cancelButtonText: 'Cancel',
                    confirmButtonClass: 'btn btn-warning mt-2',
                    cancelButtonClass: 'btn btn-secondary ms-2 mt-2',
                    buttonsStyling: !1
                }).then(function(result) {
                    if (result.value) {
                        $.ajax({
                            type: 'POST',
                            url: 'controller/transmittal-controller.php',
                            dataType: 'json',
                            data: {
                                transmittal_id: transmittal_id,
                                transaction : transaction
                            },
                            success: function (response) {
                                if (response.success) {
                                    showNotification('Cancel Transmittal Success', 'The selected transmittals have been cancelled successfully.', 'success');
                                        reloadDatatable('#transmittal-table');
                                }
                                else {
                                    if (response.isInactive) {
                                        setNotification('User Inactive', response.message, 'danger');
                                        window.location = 'logout.php?logout';
                                    }
                                    else {
                                        showNotification('Cancel Transmittal Error', response.message, 'danger');
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
                showNotification('Cancel Multiple Transmittal Error', 'Please select the transmittals you wish to cancel.', 'danger');
            }
        });

        $(document).on('click','#cancel-transmittal-details',function() {
            const transmittal_id = $('#transmittal-id').text();
            const transaction = 'cancel transmittal';
    
            Swal.fire({
                title: 'Confirm Transmittal Cancel',
                text: 'Are you sure you want to cancel this transmittal?',
                icon: 'warning',
                showCancelButton: !0,
                confirmButtonText: 'Cancel Transmittal',
                cancelButtonText: 'Cancel',
                confirmButtonClass: 'btn btn-warning mt-2',
                cancelButtonClass: 'btn btn-secondary ms-2 mt-2',
                buttonsStyling: !1
            }).then(function(result) {
                if (result.value) {
                    $.ajax({
                        type: 'POST',
                        url: 'controller/transmittal-controller.php',
                        dataType: 'json',
                        data: {
                            transmittal_id : transmittal_id, 
                            transaction : transaction
                        },
                        success: function (response) {
                            if (response.success) {
                                setNotification('Cancel Transmittal Success', 'The transmittal has been cancalled successfully.', 'success');
                                location.reload();
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
                                    showNotification('Cancel Transmittal Error', response.message, 'danger');
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
            discardCreate('transmittal.php');
        });

        $(document).on('click','#edit-form',function() {
            displayDetails('get transmittal details');

            enableForm();
        });

        $(document).on('click','#apply-filter',function() {
            transmittalTable('#transmittal-table');
        });
    });
})(jQuery);

function transmittalTable(datatable_name, buttons = false, show_all = false){
    const type = 'transmittal table';
    var filter_transmittal_date_start_date = $('#filter_transmittal_date_start_date').val();
    var filter_transmittal_date_end_date = $('#filter_transmittal_date_end_date').val();
    var transmittal_status_filter_values = [];
    var settings;

    $('.transmittal-status-filter:checked').each(function() {
        transmittal_status_filter_values.push("'" + $(this).val() + "'");
    });

    var transmittal_status_filter = transmittal_status_filter_values.join(', ');

    const column = [ 
        { 'data' : 'CHECK_BOX' },
        { 'data' : 'TRANSMITTAL_DESCRIPTION' },
        { 'data' : 'TRANSMITTED_FROM' },
        { 'data' : 'TRANSMITTED_TO' },
        { 'data' : 'TRANSMITTAL_DATE' },
        { 'data' : 'STATUS' },
        { 'data' : 'ACTION' }
    ];

    const column_definition = [
        { 'width': '1%','bSortable': false, 'aTargets': 0 },
        { 'width': '20%', 'aTargets': 1 },
        { 'width': '20%', 'aTargets': 2 },
        { 'width': '20%', 'aTargets': 3 },
        { 'width': '19%', 'aTargets': 4 },
        { 'width': '5%', 'aTargets': 5 },
        { 'width': '15%','bSortable': false, 'aTargets': 6 }
    ];

    const length_menu = show_all ? [[-1], ['All']] : [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']];

    settings = {
        'ajax': { 
            'url' : 'view/_transmittal_generation.php',
            'method' : 'POST',
            'dataType': 'json',
            'data': {'type' : type, 'filter_transmittal_date_start_date' : filter_transmittal_date_start_date, 'filter_transmittal_date_end_date' : filter_transmittal_date_end_date, 'transmittal_status_filter' : transmittal_status_filter},
            'dataSrc' : '',
            'error': function(xhr, status, error) {
                var fullErrorMessage = `XHR status: ${status}, Error: ${error}`;
                if (xhr.responseText) {
                    fullErrorMessage += `, Response: ${xhr.responseText}`;
                }
                showErrorDialog(fullErrorMessage);
            }
        },
        'order': [[ 4, 'desc' ]],
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

function transmittalForm(){
    $('#transmittal-form').validate({
        rules: {
            receiver_department: {
                required: true
            },
            transmittal_description: {
                required: true
            }
        },
        messages: {
            receiver_department: {
                required: 'Please choose the department'
            },
            transmittal_description: {
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
            const transmittal_id = $('#transmittal-id').text();
            const transaction = 'save transmittal';
        
            $.ajax({
                type: 'POST',
                url: 'controller/transmittal-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction + '&transmittal_id=' + transmittal_id,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-data');
                },
                success: function (response) {
                    if (response.success) {
                        const notificationMessage = response.insertRecord ? 'Insert Transmittal Success' : 'Update Transmittal Success';
                        const notificationDescription = response.insertRecord ? 'The transmittal has been inserted successfully.' : 'The transmittal has been updated successfully.';
                        
                        setNotification(notificationMessage, notificationDescription, 'success');
                        window.location = 'transmittal.php?id=' + response.transmittalID;
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

function generateDepartmentEmployeeOptions(department_id, selected){
    const type = 'department employee options';

    $.ajax({
        url: 'view/_employee_generation.php',
        method: 'POST',
        dataType: 'json',
        data: { type: type, department_id: department_id },
        success: function (response) {
            $('#receiver_id').empty()
                .append(new Option('--', '', false, false));

            response.forEach(function (item) {
                $('#receiver_id').append(new Option(item.FILE_AS, item.CONTACT_ID, false, false));
            });

            checkOptionExist('#receiver_id', '', '');
        },
        complete: function () {
            if (selected) {
                checkOptionExist('#receiver_id', selected, '');
            }
        }
    });
}

function displayDetails(transaction){
    switch (transaction) {
        case 'get transmittal details':
            const transmittal_id = $('#transmittal-id').text();
            
            $.ajax({
                url: 'controller/transmittal-controller.php',
                method: 'POST',
                dataType: 'json',
                data: {
                    transmittal_id : transmittal_id, 
                    transaction : transaction
                },
                beforeSend: function() {
                    resetForm('transmittal-form');
                },
                success: function(response) {
                    if (response.success) {
                        $('#transmittal_id').val(transmittal_id);
                        $('#transmittal_description').val(response.transmittalDescription);

                        document.getElementById('transmittal_status').innerHTML = response.transmittalStatusBadge;

                        checkOptionExist('#receiver_department', response.receiverDepartment, '');

                        $('#transmittal_description_label').text(response.transmittalDescription);
                        $('#receiver_department_label').text(response.departmentName);
                        $('#receiver_id_label').text(response.receiverName);
                        
                        generateDepartmentEmployeeOptions(response.receiverDepartment, response.receiverID);
                    } 
                    else {
                        if(response.isInactive){
                            window.location = 'logout.php?logout';
                        }
                        else{
                            showNotification('Get Transmittal Details Error', response.message, 'danger');
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