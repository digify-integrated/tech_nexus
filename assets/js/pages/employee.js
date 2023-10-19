(function($) {
    'use strict';

    $(function() {
        if($('#employee-table').length){
            employeeTable('#employee-table');
        }

        if($('#add-employee-form').length){
            addEmployeeForm();
        }

        if($('#personal-information-form').length){
            personalInformationForm();
        }

        if($('#employment-information-form').length){
            employmentInformationForm();
        }

        if($('#employee-id').length){
            displayDetails('get personal information details');
            displayDetails('get employment information details');

            if($('#contact-information-table').length){
                contactInformationTable('#contact-information-table');
            }

            if($('#contact-information-summary').length){
                contactInformationSummary();
            }

            if($('#contact-information-form').length){
                contactInformationForm();
            }

            if($('#contact-address-table').length){
                employeeAddressTable('#contact-address-table');
            }

            if($('#contact-address-summary').length){
                employeeAddressSummary();
            }

            if($('#contact-address-form').length){
                employeeAddressForm();
            }

            if($('#contact-identification-table').length){
                employeeIdentificationTable('#contact-identification-table');
            }

            if($('#contact-identification-summary').length){
                employeeIdentificationSummary();
            }

            if($('#contact-educational-background-form').length){
                employeeEducationalBackgroundForm();
            }

            if($('#contact-educational-background-table').length){
                employeeEducationalBackgroundTable('#contact-educational-background-table');
            }

            if($('#contact-educational-background-summary').length){
                employeeEducationalBackgroundSummary();
            }

            if($('#contact-educational-background-form').length){
                employeeEducationalBackgroundForm();
            }

            if($('#contact-family-background-form').length){
                employeeFamilyBackgroundForm();
            }

            if($('#contact-family-background-table').length){
                employeeFamilyBackgroundTable('#contact-family-background-table');
            }

            if($('#contact-family-background-summary').length){
                employeeFamilyBackgroundSummary();
            }

            if($('#contact-family-background-form').length){
                employeeFamilyBackgroundForm();
            }

            if($('#contact-emergency-contact-form').length){
                employeeEmergencyContactForm();
            }

            if($('#contact-emergency-contact-table').length){
                employeeEmergencyContactTable('#contact-emergency-contact-table');
            }

            if($('#contact-emergency-contact-summary').length){
                employeeEmergencyContactSummary();
            }

            if($('#contact-emergency-contact-form').length){
                employeeEmergencyContactForm();
            }

            $(document).on('click','#add-contact-information',function() {
                resetModalForm("contact-information-form");

                $('#contact-information-modal').modal('show');
            });

            $(document).on('click','.update-contact-information',function() {
                const contact_information_id = $(this).data('contact-information-id');
        
                sessionStorage.setItem('contact_information_id', contact_information_id);
                
                displayDetails('get contact information details');
        
                $('#contact-information-modal').modal('show');
            });

            $(document).on('click','.tag-contact-information-as-primary',function() {
                const contact_information_id = $(this).data('contact-information-id');
                const employee_id = $('#employee-id').text();
                const transaction = 'tag contact information as primary';
        
                Swal.fire({
                    title: 'Confirm Contact Information Tag As Primary',
                    text: 'Are you sure you want to tag this contact information as primary?',
                    icon: 'info',
                    showCancelButton: !0,
                    confirmButtonText: 'Tag As Primary',
                    cancelButtonText: 'Cancel',
                    confirmButtonClass: 'btn btn-warning mt-2',
                    cancelButtonClass: 'btn btn-secondary ms-2 mt-2',
                    buttonsStyling: !1
                }).then(function(result) {
                    if (result.value) {
                        $.ajax({
                            type: 'POST',
                            url: 'controller/employee-controller.php',
                            dataType: 'json',
                            data: {
                                contact_information_id : contact_information_id, 
                                employee_id : employee_id, 
                                transaction : transaction
                            },
                            success: function (response) {
                                if (response.success) {
                                    showNotification('Tag Contact Information As Primary Success', 'The contact information has been tagged as primary successfully.', 'success');
                                    reloadDatatable('#contact-information-table');
                                    contactInformationSummary();
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
                                        showNotification('Tag Contact Information As Primary Error', response.message, 'danger');
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

            $(document).on('click','.delete-contact-information',function() {
                const contact_information_id = $(this).data('contact-information-id');
                const transaction = 'delete contact information';
        
                Swal.fire({
                    title: 'Confirm Contact Information Deletion',
                    text: 'Are you sure you want to delete this contact information?',
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
                            url: 'controller/employee-controller.php',
                            dataType: 'json',
                            data: {
                                contact_information_id : contact_information_id, 
                                transaction : transaction
                            },
                            success: function (response) {
                                if (response.success) {
                                    showNotification('Delete Contact Information Success', 'The contact information has been deleted successfully.', 'success');
                                    reloadDatatable('#contact-information-table');
                                    contactInformationSummary();
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
                                        showNotification('Delete Contact Information Error', response.message, 'danger');
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

            $(document).on('click','#add-contact-address',function() {
                resetModalForm("contact-address-form");

                $('#contact-address-modal').modal('show');
            });

            $(document).on('click','.update-contact-address',function() {
                const contact_address_id = $(this).data('contact-address-id');
        
                sessionStorage.setItem('contact_address_id', contact_address_id);
                
                displayDetails('get contact address details');
        
                $('#contact-address-modal').modal('show');
            });

            $(document).on('click','.tag-contact-address-as-primary',function() {
                const contact_address_id = $(this).data('contact-address-id');
                const employee_id = $('#employee-id').text();
                const transaction = 'tag contact address as primary';
        
                Swal.fire({
                    title: 'Confirm Address Tag As Primary',
                    text: 'Are you sure you want to tag this address as primary?',
                    icon: 'info',
                    showCancelButton: !0,
                    confirmButtonText: 'Tag As Primary',
                    cancelButtonText: 'Cancel',
                    confirmButtonClass: 'btn btn-warning mt-2',
                    cancelButtonClass: 'btn btn-secondary ms-2 mt-2',
                    buttonsStyling: !1
                }).then(function(result) {
                    if (result.value) {
                        $.ajax({
                            type: 'POST',
                            url: 'controller/employee-controller.php',
                            dataType: 'json',
                            data: {
                                contact_address_id : contact_address_id, 
                                employee_id : employee_id, 
                                transaction : transaction
                            },
                            success: function (response) {
                                if (response.success) {
                                    showNotification('Tag Address As Primary Success', 'The address has been tagged as primary successfully.', 'success');
                                    reloadDatatable('#contact-address-table');
                                    employeeAddressSummary();
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
                                        showNotification('Tag Address As Primary Error', response.message, 'danger');
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

            $(document).on('click','.delete-contact-address',function() {
                const contact_address_id = $(this).data('contact-address-id');
                const transaction = 'delete contact address';
        
                Swal.fire({
                    title: 'Confirm Address Deletion',
                    text: 'Are you sure you want to delete this address?',
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
                            url: 'controller/employee-controller.php',
                            dataType: 'json',
                            data: {
                                contact_address_id : contact_address_id, 
                                transaction : transaction
                            },
                            success: function (response) {
                                if (response.success) {
                                    showNotification('Delete Address Success', 'The address has been deleted successfully.', 'success');
                                    reloadDatatable('#contact-address-table');
                                    employeeAddressSummary();
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
                                        showNotification('Delete Address Error', response.message, 'danger');
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

            $(document).on('click','#add-contact-identification',function() {
                resetModalForm("contact-identification-form");

                $('#contact-identification-modal').modal('show');
            });

            $(document).on('click','.update-contact-identification',function() {
                const contact_identification_id = $(this).data('contact-identification-id');
        
                sessionStorage.setItem('contact_identification_id', contact_identification_id);
                
                displayDetails('get contact identification details');
        
                $('#contact-identification-modal').modal('show');
            });

            $(document).on('click','.tag-contact-identification-as-primary',function() {
                const contact_identification_id = $(this).data('contact-identification-id');
                const employee_id = $('#employee-id').text();
                const transaction = 'tag contact identification as primary';
        
                Swal.fire({
                    title: 'Confirm Employee Identification Tag As Primary',
                    text: 'Are you sure you want to tag this employee identification as primary?',
                    icon: 'info',
                    showCancelButton: !0,
                    confirmButtonText: 'Tag As Primary',
                    cancelButtonText: 'Cancel',
                    confirmButtonClass: 'btn btn-warning mt-2',
                    cancelButtonClass: 'btn btn-secondary ms-2 mt-2',
                    buttonsStyling: !1
                }).then(function(result) {
                    if (result.value) {
                        $.ajax({
                            type: 'POST',
                            url: 'controller/employee-controller.php',
                            dataType: 'json',
                            data: {
                                contact_identification_id : contact_identification_id, 
                                employee_id : employee_id, 
                                transaction : transaction
                            },
                            success: function (response) {
                                if (response.success) {
                                    showNotification('Tag Employee Address As Primary Success', 'The employee identification has been tagged as primary successfully.', 'success');
                                    reloadDatatable('#contact-identification-table');
                                    employeeIdentificationSummary();
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
                                        showNotification('Tag Employee Address As Primary Error', response.message, 'danger');
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

            $(document).on('click','.delete-contact-identification',function() {
                const contact_identification_id = $(this).data('contact-identification-id');
                const transaction = 'delete contact identification';
        
                Swal.fire({
                    title: 'Confirm Employee Identification Deletion',
                    text: 'Are you sure you want to delete this employee identification?',
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
                            url: 'controller/employee-controller.php',
                            dataType: 'json',
                            data: {
                                contact_identification_id : contact_identification_id, 
                                transaction : transaction
                            },
                            success: function (response) {
                                if (response.success) {
                                    showNotification('Delete Employee Identification Success', 'The employee identification has been deleted successfully.', 'success');
                                    reloadDatatable('#contact-identification-table');
                                    employeeIdentificationSummary();
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
                                        showNotification('Delete Employee Identification Error', response.message, 'danger');
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

            $(document).on('click','#add-contact-educational-background',function() {
                resetModalForm("contact-educational-background-form");

                $('#contact-educational-background-modal').modal('show');
            });

            $(document).on('click','.update-contact-educational-background',function() {
                const contact_educational_background_id = $(this).data('contact-educational-background-id');
        
                sessionStorage.setItem('contact_educational_background_id', contact_educational_background_id);
                
                displayDetails('get contact educational background details');
        
                $('#contact-educational-background-modal').modal('show');
            });

            $(document).on('click','.delete-contact-educational-background',function() {
                const contact_educational_background_id = $(this).data('contact-educational-background-id');
                const transaction = 'delete contact educational background';
        
                Swal.fire({
                    title: 'Confirm Educational Background Deletion',
                    text: 'Are you sure you want to delete this educational background?',
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
                            url: 'controller/employee-controller.php',
                            dataType: 'json',
                            data: {
                                contact_educational_background_id : contact_educational_background_id, 
                                transaction : transaction
                            },
                            success: function (response) {
                                if (response.success) {
                                    showNotification('Delete Educational Background Success', 'The educational background has been deleted successfully.', 'success');
                                    reloadDatatable('#contact-educational-background-table');
                                    employeeEducationalBackgroundSummary();
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
                                        showNotification('Delete Educational Background Error', response.message, 'danger');
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

            $(document).on('click','#add-contact-family-background',function() {
                resetModalForm("contact-family-background-form");

                $('#contact-family-background-modal').modal('show');
            });

            $(document).on('click','.update-contact-family-background',function() {
                const contact_family_background_id = $(this).data('contact-family-background-id');
        
                sessionStorage.setItem('contact_family_background_id', contact_family_background_id);
                
                displayDetails('get contact family background details');
        
                $('#contact-family-background-modal').modal('show');
            });

            $(document).on('click','.delete-contact-family-background',function() {
                const contact_family_background_id = $(this).data('contact-family-background-id');
                const transaction = 'delete contact family background';
        
                Swal.fire({
                    title: 'Confirm Family Background Deletion',
                    text: 'Are you sure you want to delete this family background?',
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
                            url: 'controller/employee-controller.php',
                            dataType: 'json',
                            data: {
                                contact_family_background_id : contact_family_background_id, 
                                transaction : transaction
                            },
                            success: function (response) {
                                if (response.success) {
                                    showNotification('Delete Family Background Success', 'The family background has been deleted successfully.', 'success');
                                    reloadDatatable('#contact-family-background-table');
                                    employeeFamilyBackgroundSummary();
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
                                        showNotification('Delete Family Background Error', response.message, 'danger');
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

            $(document).on('click','#add-contact-emergency-contact',function() {
                resetModalForm("contact-emergency-contact-form");

                $('#contact-emergency-contact-modal').modal('show');
            });

            $(document).on('click','.update-contact-emergency-contact',function() {
                const contact_emergency_contact_id = $(this).data('contact-emergency-contact-id');
        
                sessionStorage.setItem('contact_emergency_contact_id', contact_emergency_contact_id);
                
                displayDetails('get contact emergency contact details');
        
                $('#contact-emergency-contact-modal').modal('show');
            });

            $(document).on('click','.delete-contact-emergency-contact',function() {
                const contact_emergency_contact_id = $(this).data('contact-emergency-contact-id');
                const transaction = 'delete contact emergency contact';
        
                Swal.fire({
                    title: 'Confirm Emergency Contact Deletion',
                    text: 'Are you sure you want to delete this emergency contact?',
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
                            url: 'controller/employee-controller.php',
                            dataType: 'json',
                            data: {
                                contact_emergency_contact_id : contact_emergency_contact_id, 
                                transaction : transaction
                            },
                            success: function (response) {
                                if (response.success) {
                                    showNotification('Delete Emergency Contact Success', 'The emergency contact has been deleted successfully.', 'success');
                                    reloadDatatable('#contact-emergency-contact-table');
                                    employeeEmergencyContactSummary();
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
                                        showNotification('Delete Emergency Contact Error', response.message, 'danger');
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

            $('#employee_image').change(function() {
                var selectedFile = $(this)[0].files[0];

                if (selectedFile) {
                    const transaction = 'change employee image';
                    const employee_id = $('#employee-id').text();

                    var formData = new FormData();
                    formData.append('employee_id', employee_id);
                    formData.append('transaction', transaction);
                    formData.append('employee_image', selectedFile);
            
                    $.ajax({
                        type: 'POST',
                        url: 'controller/employee-controller.php',
                        data: formData,
                        processData: false,
                        contentType: false,
                        dataType: 'json',
                        success: function(response) {
                            if (response.success) {
                                showNotification('Employee Image Change Success', 'The employee image has been successfully updated.', 'success');
                                displayDetails('get personal information details');
                            }
                            else{
                                if(response.isInactive){
                                    window.location = 'logout.php?logout';
                                }
                                else{
                                    showNotification('Employee Image Change Error', response.message, 'danger');
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
                }
            });
        }

        $(document).on('click','#filter-datatable',function() {
            employeeTable('#employee-table');
        });
    });
})(jQuery);

function employeeTable(datatable_name, buttons = false, show_all = false){
    const type = 'employee table';
    var filter_employment_status = $('#filter_employment_status').val();
    var filter_company = $('#filter_company').val();
    var filter_department = $('#filter_department').val();
    var filter_job_position = $('#filter_job_position').val();
    var filter_job_level = $('#filter_job_level').val();
    var filter_branch = $('#filter_branch').val();
    var filter_employee_type = $('#filter_employee_type').val();

    var settings;

    const column = [ 
        { 'data' : 'CHECK_BOX' },
        { 'data' : 'EMPLOYEE' },
        { 'data' : 'DEPARTMENT' },
        { 'data' : 'BRANCH' },
        { 'data' : 'ACTION' }
    ];

    const column_definition = [
        { 'width': '1%','bSortable': false, 'aTargets': 0 },
        { 'width': '44%', 'aTargets': 1 },
        { 'width': '20%', 'aTargets': 2 },
        { 'width': '20%', 'aTargets': 3 },
        { 'width': '15%','bSortable': false, 'aTargets': 4 }
    ];

    const length_menu = show_all ? [[-1], ['All']] : [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']];

    settings = {
        'ajax': { 
            'url' : 'view/_employee_generation.php',
            'method' : 'POST',
            'dataType': 'json',
            'data': {
                'type' : type,
                'filter_employment_status' : filter_employment_status,
                'filter_company' : filter_company,
                'filter_department' : filter_department,
                'filter_job_position' : filter_job_position,
                'filter_job_level' : filter_job_level,
                'filter_branch' : filter_branch,
                'filter_employee_type' : filter_employee_type,
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

function contactInformationTable(datatable_name, buttons = false, show_all = false){
    const type = 'contact information table';
    const employee_id = $('#employee-id').text();

    var settings;

    const column = [ 
        { 'data' : 'CONTACT_INFORMATION_TYPE' },
        { 'data' : 'EMAIL' },
        { 'data' : 'MOBILE' },
        { 'data' : 'TELEPHONE' },
        { 'data' : 'STATUS' },
        { 'data' : 'ACTION' }
    ];

    const column_definition = [
        { 'width': '15%', 'aTargets': 0 },
        { 'width': '15%', 'aTargets': 1 },
        { 'width': '15%', 'aTargets': 2 },
        { 'width': '15%', 'aTargets': 3 },
        { 'width': '15%', 'aTargets': 4 },
        { 'width': '10%','bSortable': false, 'aTargets': 5 }
    ];

    const length_menu = show_all ? [[-1], ['All']] : [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']];

    settings = {
        'ajax': { 
            'url' : 'view/_employee_generation.php',
            'method' : 'POST',
            'dataType': 'json',
            'data': {
                'type' : type,
                'employee_id' : employee_id,
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

function contactInformationSummary(){
    const type = 'contact information summary';
    var employee_id = $('#employee-id').text();
            
    $.ajax({
        url: 'view/_employee_generation.php',
        method: 'POST',
        dataType: 'json',
        data: {
            employee_id : employee_id, 
            type : type
        },
        success: function(response) {
            document.getElementById('contact-information-summary').innerHTML = response[0].contactInformationSummary;
        },
        error: function(xhr, status, error) {
            var fullErrorMessage = `XHR status: ${status}, Error: ${error}`;
            if (xhr.responseText) {
                fullErrorMessage += `, Response: ${xhr.responseText}`;
            }
            showErrorDialog(fullErrorMessage);
        }
    });
}

function employeeAddressTable(datatable_name, buttons = false, show_all = false){
    const type = 'contact address table';
    const employee_id = $('#employee-id').text();

    var settings;

    const column = [ 
        { 'data' : 'ADDRESS_TYPE' },
        { 'data' : 'ADDRESS' },
        { 'data' : 'STATUS' },
        { 'data' : 'ACTION' }
    ];

    const column_definition = [
        { 'width': '25%', 'aTargets': 0 },
        { 'width': '25%', 'aTargets': 1 },
        { 'width': '25%', 'aTargets': 2 },
        { 'width': '25%','bSortable': false, 'aTargets': 3 }
    ];

    const length_menu = show_all ? [[-1], ['All']] : [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']];

    settings = {
        'ajax': { 
            'url' : 'view/_employee_generation.php',
            'method' : 'POST',
            'dataType': 'json',
            'data': {
                'type' : type,
                'employee_id' : employee_id,
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
        'order': [[ 2, 'desc' ]],
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

function employeeAddressSummary(){
    const type = 'contact address summary';
    var employee_id = $('#employee-id').text();
            
    $.ajax({
        url: 'view/_employee_generation.php',
        method: 'POST',
        dataType: 'json',
        data: {
            employee_id : employee_id, 
            type : type
        },
        success: function(response) {
            document.getElementById('contact-address-summary').innerHTML = response[0].contactAddressSummary;
        },
        error: function(xhr, status, error) {
            var fullErrorMessage = `XHR status: ${status}, Error: ${error}`;
            if (xhr.responseText) {
                fullErrorMessage += `, Response: ${xhr.responseText}`;
            }
            showErrorDialog(fullErrorMessage);
        }
    });
}

function employeeIdentificationTable(datatable_name, buttons = false, show_all = false){
    const type = 'contact identification table';
    const employee_id = $('#employee-id').text();

    var settings;

    const column = [ 
        { 'data' : 'ID_TYPE' },
        { 'data' : 'ID_NUMBER' },
        { 'data' : 'STATUS' },
        { 'data' : 'ACTION' }
    ];

    const column_definition = [
        { 'width': '25%', 'aTargets': 0 },
        { 'width': '25%', 'aTargets': 1 },
        { 'width': '25%', 'aTargets': 2 },
        { 'width': '25%','bSortable': false, 'aTargets': 3 }
    ];

    const length_menu = show_all ? [[-1], ['All']] : [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']];

    settings = {
        'ajax': { 
            'url' : 'view/_employee_generation.php',
            'method' : 'POST',
            'dataType': 'json',
            'data': {
                'type' : type,
                'employee_id' : employee_id,
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
        'order': [[ 2, 'asc' ]],
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

function employeeIdentificationSummary(){
    const type = 'contact identification summary';
    var employee_id = $('#employee-id').text();
            
    $.ajax({
        url: 'view/_employee_generation.php',
        method: 'POST',
        dataType: 'json',
        data: {
            employee_id : employee_id, 
            type : type
        },
        success: function(response) {
            document.getElementById('contact-identification-summary').innerHTML = response[0].contactIdentificationSummary;
        },
        error: function(xhr, status, error) {
            var fullErrorMessage = `XHR status: ${status}, Error: ${error}`;
            if (xhr.responseText) {
                fullErrorMessage += `, Response: ${xhr.responseText}`;
            }
            showErrorDialog(fullErrorMessage);
        }
    });
}

function employeeEducationalBackgroundTable(datatable_name, buttons = false, show_all = false){
    const type = 'contact educational background table';
    const employee_id = $('#employee-id').text();

    var settings;

    const column = [ 
        { 'data' : 'EDUCATIONAL_STAGE' },
        { 'data' : 'INSTITUTION_NAME' },
        { 'data' : 'DEGREE_EARNED' },
        { 'data' : 'FIELD_OF_STUDY' },
        { 'data' : 'YEAR_ATTENDED' },
        { 'data' : 'ACTION' }
    ];

    const column_definition = [
        { 'width': '16%', 'aTargets': 0 },
        { 'width': '16%', 'aTargets': 1 },
        { 'width': '16%', 'aTargets': 2 },
        { 'width': '16%', 'aTargets': 3 },
        { 'width': '16%', 'aTargets': 4 },
        { 'width': '16%','bSortable': false, 'aTargets': 5 }
    ];

    const length_menu = show_all ? [[-1], ['All']] : [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']];

    settings = {
        'ajax': { 
            'url' : 'view/_employee_generation.php',
            'method' : 'POST',
            'dataType': 'json',
            'data': {
                'type' : type,
                'employee_id' : employee_id,
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

function employeeEducationalBackgroundSummary(){
    const type = 'contact educational background summary';
    var employee_id = $('#employee-id').text();
            
    $.ajax({
        url: 'view/_employee_generation.php',
        method: 'POST',
        dataType: 'json',
        data: {
            employee_id : employee_id, 
            type : type
        },
        success: function(response) {
            document.getElementById('contact-educational-background-summary').innerHTML = response[0].contactEducationalBackgroundSummary;
        },
        error: function(xhr, status, error) {
            var fullErrorMessage = `XHR status: ${status}, Error: ${error}`;
            if (xhr.responseText) {
                fullErrorMessage += `, Response: ${xhr.responseText}`;
            }
            showErrorDialog(fullErrorMessage);
        }
    });
}

function employeeFamilyBackgroundTable(datatable_name, buttons = false, show_all = false){
    const type = 'contact family background table';
    const employee_id = $('#employee-id').text();

    var settings;

    const column = [ 
        { 'data' : 'FAMILY_NAME' },
        { 'data' : 'RELATION' },
        { 'data' : 'BIRTHDAY' },
        { 'data' : 'EMAIL' },
        { 'data' : 'MOBILE' },
        { 'data' : 'TELEPHONE' },
        { 'data' : 'ACTION' }
    ];

    const column_definition = [
        { 'width': '14%', 'aTargets': 0 },
        { 'width': '14%', 'aTargets': 1 },
        { 'width': '14%', 'aTargets': 2 },
        { 'width': '14%', 'aTargets': 3 },
        { 'width': '14%', 'aTargets': 4 },
        { 'width': '14%', 'aTargets': 5 },
        { 'width': '14%','bSortable': false, 'aTargets': 6 }
    ];

    const length_menu = show_all ? [[-1], ['All']] : [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']];

    settings = {
        'ajax': { 
            'url' : 'view/_employee_generation.php',
            'method' : 'POST',
            'dataType': 'json',
            'data': {
                'type' : type,
                'employee_id' : employee_id,
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

function employeeFamilyBackgroundSummary(){
    const type = 'contact family background summary';
    var employee_id = $('#employee-id').text();
            
    $.ajax({
        url: 'view/_employee_generation.php',
        method: 'POST',
        dataType: 'json',
        data: {
            employee_id : employee_id, 
            type : type
        },
        success: function(response) {
            document.getElementById('contact-family-background-summary').innerHTML = response[0].contactFamilyBackgroundSummary;
        },
        error: function(xhr, status, error) {
            var fullErrorMessage = `XHR status: ${status}, Error: ${error}`;
            if (xhr.responseText) {
                fullErrorMessage += `, Response: ${xhr.responseText}`;
            }
            showErrorDialog(fullErrorMessage);
        }
    });
}

function employeeEmergencyContactTable(datatable_name, buttons = false, show_all = false){
    const type = 'contact emergency contact table';
    const employee_id = $('#employee-id').text();

    var settings;

    const column = [ 
        { 'data' : 'EMERGENCY_CONTACT_NAME' },
        { 'data' : 'RELATION' },
        { 'data' : 'EMAIL' },
        { 'data' : 'MOBILE' },
        { 'data' : 'TELEPHONE' },
        { 'data' : 'ACTION' }
    ];

    const column_definition = [
        { 'width': '16%', 'aTargets': 0 },
        { 'width': '16%', 'aTargets': 1 },
        { 'width': '16%', 'aTargets': 2 },
        { 'width': '16%', 'aTargets': 3 },
        { 'width': '16%', 'aTargets': 4 },
        { 'width': '16%','bSortable': false, 'aTargets': 5 }
    ];

    const length_menu = show_all ? [[-1], ['All']] : [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']];

    settings = {
        'ajax': { 
            'url' : 'view/_employee_generation.php',
            'method' : 'POST',
            'dataType': 'json',
            'data': {
                'type' : type,
                'employee_id' : employee_id,
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

function employeeEmergencyContactSummary(){
    const type = 'contact emergency contact summary';
    var employee_id = $('#employee-id').text();
            
    $.ajax({
        url: 'view/_employee_generation.php',
        method: 'POST',
        dataType: 'json',
        data: {
            employee_id : employee_id, 
            type : type
        },
        success: function(response) {
            document.getElementById('contact-emergency-contact-summary').innerHTML = response[0].contactEmergencyContactSummary;
        },
        error: function(xhr, status, error) {
            var fullErrorMessage = `XHR status: ${status}, Error: ${error}`;
            if (xhr.responseText) {
                fullErrorMessage += `, Response: ${xhr.responseText}`;
            }
            showErrorDialog(fullErrorMessage);
        }
    });
}

function addEmployeeForm(){
    $('#add-employee-form').validate({
        rules: {
            first_name: {
                required: true
            },
            last_name: {
                required: true
            },
            company_id: {
                required: true
            },
            branch_id: {
                required: true
            },
            department_id: {
                required: true
            },
            job_position_id: {
                required: true
            },
        },
        messages: {
            first_name: {
                required: 'Please enter the first name'
            },
            last_name: {
                required: 'Please enter the last name'
            },
            company_id: {
                required: 'Please choose the company'
            },
            branch_id: {
                required: 'Please choose the branch'
            },
            department_id: {
                required: 'Please choose the department'
            },
            job_position_id: {
                required: 'Please choose the job position'
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
            const employee_id = $('#employee-id').text();
            const transaction = 'add employee';
        
            $.ajax({
                type: 'POST',
                url: 'controller/employee-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction + '&employee_id=' + employee_id,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-data');
                },
                success: function (response) {
                    if (response.success) {
                        const notificationMessage = 'Insert Employee Success';
                        const notificationDescription = 'The employee has been inserted successfully.';
                        
                        setNotification(notificationMessage, notificationDescription, 'success');
                        window.location = 'employee.php?id=' + response.employeeID;
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

function personalInformationForm(){
    $('#personal-information-form').validate({
        rules: {
            first_name: {
                required: true
            },
            last_name: {
                required: true
            },
            birthday: {
                required: true
            },
            gender: {
                required: true
            },
            civil_status: {
                required: true
            },
        },
        messages: {
            first_name: {
                required: 'Please enter the first name'
            },
            last_name: {
                required: 'Please enter the last name'
            },
            gender: {
                required: 'Please choose the gender'
            },
            civil_status: {
                required: 'Please choose the civil status'
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
            const employee_id = $('#employee-id').text();
            const transaction = 'save personal information';
        
            $.ajax({
                type: 'POST',
                url: 'controller/employee-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction + '&employee_id=' + employee_id,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-personal-information-data');
                },
                success: function (response) {
                    if (response.success) {
                        const notificationMessage = 'Update Personal Information Success';
                        const notificationDescription = 'The personal information has been updated successfully.';
                        
                        showNotification(notificationMessage, notificationDescription, 'success');
                        displayDetails('get personal information details');
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
                    enableFormSubmitButton('submit-personal-information-data', 'Save');
                }
            });
        
            return false;
        }
    });
}

function employmentInformationForm(){
    $('#employment-information-form').validate({
        rules: {
            badge_id: {
                required: true
            },
            company_id: {
                required: true
            },
            department_id: {
                required: true
            },
            job_position_id: {
                required: true
            },
            gender: {
                required: true
            },
            employee_type_id: {
                required: true
            },
            job_level_id: {
                required: true
            },
            branch_id: {
                required: true
            },
            onboard_date: {
                required: true
            },
        },
        messages: {
            badge_id: {
                required: 'Please enter the badge ID'
            },
            company_id: {
                required: 'Please choose the company'
            },
            department_id: {
                required: 'Please choose the department'
            },
            job_position_id: {
                required: 'Please choose the job position'
            },
            employee_type_id: {
                required: 'Please choose the employee type'
            },
            job_level_id: {
                required: 'Please choose the job level'
            },
            branch_id: {
                required: 'Please choose the job level'
            },
            onboard_date: {
                required: 'Please choose the on-board date'
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
            const employee_id = $('#employee-id').text();
            const transaction = 'save employment information';
        
            $.ajax({
                type: 'POST',
                url: 'controller/employee-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction + '&employee_id=' + employee_id,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-employment-information-data');
                },
                success: function (response) {
                    if (response.success) {
                        const notificationMessage = 'Update Employment Information Success';
                        const notificationDescription = 'The employment information has been updated successfully.';
                        
                        showNotification(notificationMessage, notificationDescription, 'success');
                        displayDetails('get employment information details');
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
                    enableFormSubmitButton('submit-employment-information-data', 'Save');
                }
            });
        
            return false;
        }
    });
}

function contactInformationForm(){
    $('#contact-information-form').validate({
        rules: {
            contact_information_type_id: {
                required: true
            },
            contact_information_email: {
                contactInformationRequired: true
            },
            contact_information_mobile: {
                contactInformationRequired: true
            },
            contact_information_telephone: {
                contactInformationRequired: true
            }
        },
        messages: {
            contact_information_type_id: {
                required: 'Please choose the contact information type'
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
            const employee_id = $('#employee-id').text();
            const transaction = 'save contact information';
        
            $.ajax({
                type: 'POST',
                url: 'controller/employee-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction + '&employee_id=' + employee_id,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-contact-information');
                },
                success: function (response) {
                    if (response.success) {
                        const notificationMessage = response.insertRecord ? 'Insert Contact Information Success' : 'Update Contact Information Success';
                        const notificationDescription = response.insertRecord ? 'The contact information has been inserted successfully.' : 'The contact information has been updated successfully.';
                        
                        showNotification(notificationMessage, notificationDescription, 'success');
                    }
                    else {
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
                    enableFormSubmitButton('submit-contact-information', 'Submit');
                    $('#contact-information-modal').modal('hide');
                    reloadDatatable('#contact-information-table');
                    contactInformationSummary();
                    resetModalForm('contact-information-form');
                }
            });
        
            return false;
        }
    });
}

function employeeAddressForm(){
    $('#contact-address-form').validate({
        rules: {
            address_type_id: {
                required: true
            },
            contact_address: {
                required: true
            },
            contact_address_city_id: {
                required: true
            },
        },
        messages: {
            address_type_id: {
                required: 'Please choose the address type'
            },
            contact_address: {
                required: 'Please enter the address'
            },
            contact_address_city_id: {
                required: 'Please choose the city'
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
            const employee_id = $('#employee-id').text();
            const transaction = 'save contact address';
        
            $.ajax({
                type: 'POST',
                url: 'controller/employee-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction + '&employee_id=' + employee_id,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-contact-address');
                },
                success: function (response) {
                    if (response.success) {
                        const notificationMessage = response.insertRecord ? 'Insert Address Success' : 'Update Address Success';
                        const notificationDescription = response.insertRecord ? 'The address has been inserted successfully.' : 'The address has been updated successfully.';
                        
                        showNotification(notificationMessage, notificationDescription, 'success');
                    }
                    else {
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
                    enableFormSubmitButton('submit-contact-address', 'Submit');
                    $('#contact-address-modal').modal('hide');
                    reloadDatatable('#contact-address-table');
                    employeeAddressSummary();
                    resetModalForm('contact-address-form');
                }
            });
        
            return false;
        }
    });
}

function employeeIdentificationForm(){
    $('#contact-identification-form').validate({
        rules: {
            id_type_id: {
                required: true
            },
            id_number: {
                required: true
            }
        },
        messages: {
            id_type_id: {
                required: 'Please choose the ID type'
            },
            id_number: {
                required: 'Please enter the ID number'
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
            const employee_id = $('#employee-id').text();
            const transaction = 'save contact identification';
        
            $.ajax({
                type: 'POST',
                url: 'controller/employee-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction + '&employee_id=' + employee_id,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-contact-identification');
                },
                success: function (response) {
                    if (response.success) {
                        const notificationMessage = response.insertRecord ? 'Insert Employee Identification Success' : 'Update Employee Identification Success';
                        const notificationDescription = response.insertRecord ? 'The employee identification has been inserted successfully.' : 'The employee identification has been updated successfully.';
                        
                        showNotification(notificationMessage, notificationDescription, 'success');
                    }
                    else {
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
                    enableFormSubmitButton('submit-contact-identification', 'Submit');
                    $('#contact-identification-modal').modal('hide');
                    reloadDatatable('#contact-identification-table');
                    employeeIdentificationSummary();
                    resetModalForm('contact-identification-form');
                }
            });
        
            return false;
        }
    });
}

function employeeEducationalBackgroundForm(){
    $('#contact-educational-background-form').validate({
        rules: {
            educational_stage_id: {
                required: true
            },
            contact_institution_name: {
                required: true
            },
            contact_start_date_attended: {
                required: true
            }
        },
        messages: {
            educational_stage_id: {
                required: 'Please choose the educational stage'
            },
            contact_institution_name: {
                required: 'Please enter the institution name'
            },
            contact_start_date_attended: {
                required: 'Please choose the start date'
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
            const employee_id = $('#employee-id').text();
            const transaction = 'save contact educational background';
        
            $.ajax({
                type: 'POST',
                url: 'controller/employee-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction + '&employee_id=' + employee_id,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-contact-educational-background');
                },
                success: function (response) {
                    if (response.success) {
                        const notificationMessage = response.insertRecord ? 'Insert Educational Background Success' : 'Update Educational Background Success';
                        const notificationDescription = response.insertRecord ? 'The educational background has been inserted successfully.' : 'The educational background has been updated successfully.';
                        
                        showNotification(notificationMessage, notificationDescription, 'success');
                    }
                    else {
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
                    enableFormSubmitButton('submit-contact-educational-background', 'Submit');
                    $('#contact-educational-background-modal').modal('hide');
                    reloadDatatable('#contact-educational-background-table');
                    employeeEducationalBackgroundSummary();
                    resetModalForm('contact-educational-background-form');
                }
            });
        
            return false;
        }
    });
}

function employeeFamilyBackgroundForm(){
    $('#contact-family-background-form').validate({
        rules: {
            family_name: {
                required: true
            },
            family_background_relation_id: {
                required: true
            },
            family_background_birthday: {
                required: true
            }
        },
        messages: {
            family_name: {
                required: 'Please enter the family name'
            },
            family_background_relation_id: {
                required: 'Please choose the relation'
            },
            family_background_birthday: {
                required: 'Please choose the birthday'
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
            const employee_id = $('#employee-id').text();
            const transaction = 'save contact family background';
        
            $.ajax({
                type: 'POST',
                url: 'controller/employee-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction + '&employee_id=' + employee_id,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-contact-family-background');
                },
                success: function (response) {
                    if (response.success) {
                        const notificationMessage = response.insertRecord ? 'Insert Family Background Success' : 'Update Family Background Success';
                        const notificationDescription = response.insertRecord ? 'The family background has been inserted successfully.' : 'The family background has been updated successfully.';
                        
                        showNotification(notificationMessage, notificationDescription, 'success');
                    }
                    else {
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
                    enableFormSubmitButton('submit-contact-family-background', 'Submit');
                    $('#contact-family-background-modal').modal('hide');
                    reloadDatatable('#contact-family-background-table');
                    employeeFamilyBackgroundSummary();
                    resetModalForm('contact-family-background-form');
                }
            });
        
            return false;
        }
    });
}

function employeeEmergencyContactForm(){
    $('#contact-emergency-contact-form').validate({
        rules: {
            emergency_contact_name: {
                required: true
            },
            emergency_contact_relation_id: {
                required: true
            }
        },
        messages: {
            emergency_contact_name: {
                required: 'Please enter the emergency contract name'
            },
            emergency_contact_relation_id: {
                required: 'Please choose the relation'
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
            const employee_id = $('#employee-id').text();
            const transaction = 'save contact emergency contact';
        
            $.ajax({
                type: 'POST',
                url: 'controller/employee-controller.php',
                data: $(form).serialize() + '&transaction=' + transaction + '&employee_id=' + employee_id,
                dataType: 'json',
                beforeSend: function() {
                    disableFormSubmitButton('submit-contact-emergency-contact');
                },
                success: function (response) {
                    if (response.success) {
                        const notificationMessage = response.insertRecord ? 'Insert Emergency Contact Success' : 'Update Emergency Contact Success';
                        const notificationDescription = response.insertRecord ? 'The emergency contact has been inserted successfully.' : 'The emergency contact has been updated successfully.';
                        
                        showNotification(notificationMessage, notificationDescription, 'success');
                    }
                    else {
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
                    enableFormSubmitButton('submit-contact-emergency-contact', 'Submit');
                    $('#contact-emergency-contact-modal').modal('hide');
                    reloadDatatable('#contact-emergency-contact-table');
                    employeeEmergencyContactSummary();
                    resetModalForm('contact-emergency-contact-form');
                }
            });
        
            return false;
        }
    });
}

function displayDetails(transaction){
    switch (transaction) {
        case 'get personal information details':
            var employee_id = $('#employee-id').text();
            
            $.ajax({
                url: 'controller/employee-controller.php',
                method: 'POST',
                dataType: 'json',
                data: {
                    employee_id : employee_id, 
                    transaction : transaction
                },
                beforeSend: function() {
                    resetForm('personal-information-form');
                },
                success: function(response) {
                    if (response.success) {
                        $('#first_name').val(response.firstName);
                        $('#last_name').val(response.lastName);
                        $('#middle_name').val(response.middleName);
                        $('#suffix').val(response.suffix);
                        $('#nickname').val(response.nickname);
                        $('#birthday').val(response.birthday);
                        $('#birth_place').val(response.birthPlace);
                        $('#height').val(response.height);
                        $('#weight').val(response.weight);
                        $('#bio').val(response.bio);

                        $('#employee_bio').text(response.bio);
                        $('#employee_name').text(response.employeeName);
                        $('#full_name').text(response.employeeName);
                        $('#employee_nickname').text(response.nickname);
                        $('#employee_birthday').text(response.birthday);
                        $('#employee_birth_place').text(response.birthPlace);
                        $('#employee_gender').text(response.genderName);
                        $('#employee_religion').text(response.religionName);
                        $('#employee_blood_type').text(response.bloodTypeName);
                        $('#employee_civil_status').text(response.civilStatusName);
                        $('#employee_height').text(response.height + ' cm');
                        $('#employee_weight').text(response.weight + ' kg');

                        $('#emp_image').attr('src', response.employeeImage);
                        $('#employee_summary_image').attr('src', response.employeeImage);

                        checkOptionExist('#gender', response.genderID, '');
                        checkOptionExist('#civil_status', response.civilStatusID, '');
                        checkOptionExist('#religion', response.religionID, '');
                        checkOptionExist('#blood_type', response.bloodTypeID, '');
                    } 
                    else {
                        if(response.isInactive){
                            window.location = 'logout.php?logout';
                        }
                        else{
                            showNotification('Get Personal Information Details Error', response.message, 'danger');
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
        case 'get employment information details':
            var employee_id = $('#employee-id').text();
            
            $.ajax({
                url: 'controller/employee-controller.php',
                method: 'POST',
                dataType: 'json',
                data: {
                    employee_id : employee_id, 
                    transaction : transaction
                },
                beforeSend: function() {
                    resetForm('employment-information-form');
                },
                success: function(response) {
                    if (response.success) {
                        $('#badge_id').val(response.badgeID);
                        $('#onboard_date').val(response.onboardDate);
                        $('#permanency_date').val(response.permanencyDate);

                        document.getElementById('employee-status-badge').innerHTML = response.isActiveBadge;

                        $('#employee_badge_id').text(response.badgeID);
                        $('#employee_company').text(response.companyName);
                        $('#employee_department').text(response.departmentName);
                        $('#employee_type').text(response.employeeTypeName);
                        $('#employee_job_level').text(response.jobLevelName);
                        $('#employee_branch').text(response.branchName);
                        $('#employee_onboard_date').text(response.onboardDate);
                        $('#employee_permanency_date').text(response.permanencyDate);

                        $('#job_position').text(response.jobPositionName);

                        checkOptionExist('#company_id', response.companyID, '');
                        checkOptionExist('#department_id', response.departmentID, '');
                        checkOptionExist('#job_position_id', response.jobPositionID, '');
                        checkOptionExist('#employee_type_id', response.employeeTypeID, '');
                        checkOptionExist('#job_level_id', response.jobLevelID, '');
                        checkOptionExist('#branch_id', response.branchID, '');
                    } 
                    else {
                        if(response.isInactive){
                            window.location = 'logout.php?logout';
                        }
                        else{
                            showNotification('Get Employment Information Details Error', response.message, 'danger');
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
        case 'get contact information details':
            var contact_information_id = sessionStorage.getItem('contact_information_id');

            $.ajax({
                url: 'controller/employee-controller.php',
                method: 'POST',
                dataType: 'json',
                data: {
                    contact_information_id : contact_information_id, 
                    transaction : transaction
                },
                beforeSend: function() {
                    resetModalForm('contact-information-form');
                },
                success: function(response) {
                    if (response.success) {
                        $('#contact_information_id').val(contact_information_id);
                        $('#contact_information_email').val(response.email);
                        $('#contact_information_mobile').val(response.mobile);
                        $('#contact_information_telephone').val(response.telephone);
                        
                        checkOptionExist('#contact_information_type_id', response.contactInformationTypeID, '');
                    } 
                    else {
                        if(response.isInactive){
                            window.location = 'logout.php?logout';
                        }
                        else{
                            showNotification('Get Contact Information Details Error', response.message, 'danger');
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
        case 'get contact address details':
            var contact_address_id = sessionStorage.getItem('contact_address_id');

            $.ajax({
                url: 'controller/employee-controller.php',
                method: 'POST',
                dataType: 'json',
                data: {
                    contact_address_id : contact_address_id, 
                    transaction : transaction
                },
                beforeSend: function() {
                    resetModalForm('contact-address-form');
                },
                success: function(response) {
                    if (response.success) {
                        $('#contact_address_id').val(contact_address_id);
                        $('#contact_address').val(response.address);
                        
                        checkOptionExist('#address_type_id', response.addressTypeID, '');
                        checkOptionExist('#contact_address_city_id', response.cityID, '');
                    } 
                    else {
                        if(response.isInactive){
                            window.location = 'logout.php?logout';
                        }
                        else{
                            showNotification('Get Address Details Error', response.message, 'danger');
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
        case 'get contact identification details':
            var contact_identification_id = sessionStorage.getItem('contact_identification_id');

            $.ajax({
                url: 'controller/employee-controller.php',
                method: 'POST',
                dataType: 'json',
                data: {
                    contact_identification_id : contact_identification_id, 
                    transaction : transaction
                },
                beforeSend: function() {
                    resetModalForm('contact-identification-form');
                },
                success: function(response) {
                    if (response.success) {
                        $('#contact_identification_id').val(contact_identification_id);
                        $('#contact_id_number').val(response.idNumber);
                        
                        checkOptionExist('#id_type_id', response.idTypeID, '');
                    } 
                    else {
                        if(response.isInactive){
                            window.location = 'logout.php?logout';
                        }
                        else{
                            showNotification('Get Employee Identification Details Error', response.message, 'danger');
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
        case 'get contact educational background details':
            var contact_educational_background_id = sessionStorage.getItem('contact_educational_background_id');

            $.ajax({
                url: 'controller/employee-controller.php',
                method: 'POST',
                dataType: 'json',
                data: {
                    contact_educational_background_id : contact_educational_background_id, 
                    transaction : transaction
                },
                beforeSend: function() {
                    resetModalForm('contact-educational-background-form');
                },
                success: function(response) {
                    if (response.success) {
                        $('#contact_educational_background_id').val(contact_educational_background_id);
                        $('#contact_institution_name').val(response.institutionName);
                        $('#contact_degree_earned').val(response.degreeEarned);
                        $('#contact_field_of_study').val(response.fieldOfStudy);
                        $('#contact_start_date_attended').val(response.startDate);
                        $('#contact_end_date_attended').val(response.endDate);
                        
                        checkOptionExist('#educational_stage_id', response.educationalStageID, '');
                    } 
                    else {
                        if(response.isInactive){
                            window.location = 'logout.php?logout';
                        }
                        else{
                            showNotification('Get Educational Background Details Error', response.message, 'danger');
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
        case 'get contact family background details':
            var contact_family_background_id = sessionStorage.getItem('contact_family_background_id');

            $.ajax({
                url: 'controller/employee-controller.php',
                method: 'POST',
                dataType: 'json',
                data: {
                    contact_family_background_id : contact_family_background_id, 
                    transaction : transaction
                },
                beforeSend: function() {
                    resetModalForm('contact-family-background-form');
                },
                success: function(response) {
                    if (response.success) {
                        $('#contact_family_background_id').val(contact_family_background_id);
                        $('#family_name').val(response.familyName);
                        $('#family_background_birthday').val(response.birthday);
                        $('#family_background_email').val(response.email);
                        $('#family_background_mobile').val(response.mobile);
                        $('#family_background_telephone').val(response.telephone);
                        
                        checkOptionExist('#family_background_relation_id', response.relationID, '');
                    } 
                    else {
                        if(response.isInactive){
                            window.location = 'logout.php?logout';
                        }
                        else{
                            showNotification('Get Family Background Details Error', response.message, 'danger');
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
        case 'get contact emergency contact details':
            var contact_emergency_contact_id = sessionStorage.getItem('contact_emergency_contact_id');

            $.ajax({
                url: 'controller/employee-controller.php',
                method: 'POST',
                dataType: 'json',
                data: {
                    contact_emergency_contact_id : contact_emergency_contact_id, 
                    transaction : transaction
                },
                beforeSend: function() {
                    resetModalForm('contact-emergency-contact-form');
                },
                success: function(response) {
                    if (response.success) {
                        $('#contact_emergency_contact_id').val(contact_emergency_contact_id);
                        $('#emergency_contact_name').val(response.emergencyContactName);
                        $('#emergency_contact_email').val(response.email);
                        $('#emergency_contact_mobile').val(response.mobile);
                        $('#emergency_contact_telephone').val(response.telephone);
                        
                        checkOptionExist('#emergency_contact_relation_id', response.relationID, '');
                    } 
                    else {
                        if(response.isInactive){
                            window.location = 'logout.php?logout';
                        }
                        else{
                            showNotification('Get Emergency Contact Details Error', response.message, 'danger');
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