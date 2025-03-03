(function($) {
    'use strict';

    $(function() {
        getUISettings();
        checkNotification();
        maxLength();

        if($('#change-password').length){
            $(document).on('click','#change-password',function() {    
                resetModalForm('change-password-shortcut-form');
            });
        }

        if($('.log-notes-scroll').length){
            new SimpleBar(document.querySelector('.log-notes-scroll'));
        }

        if($('.scroll-block').length){
            var tc = document.querySelectorAll('.scroll-block');
            for (var t = 0; t < tc.length; t++) {
                new SimpleBar(tc[t]);
            }
        }

        if($('.filter-datepicker').length){
            var filter_date_pickers = document.querySelectorAll('.filter-datepicker');
            filter_date_pickers.forEach(function(element) {
                var filter_date_picker = new Datepicker(element, {
                    buttonClass: 'btn',
                    todayHighlight: true,
                    todayBtn: true,
                    clearBtn: true,
                    autohide: true
                });
            });
        }

        if ($('.currency').length) {
            $('.currency').each(function() {
                IMask(this, {
                    mask: Number,
                    scale: 2,
                    signed: false,
                    thousandsSeparator: ',',
                    padFractionalZeros: true,
                    normalizeZeros: true,
                    radix: '.',
                    mapToRadix: ['.'],
                    prefix: '$ ',
                    lazy: false
                });
            });
        }

        if ($('.select2').length) {
            $('.select2').select2({}).on("change", function () {
                $(this).valid();
            }).on("select2:open", function () {
                focusSelect2Search();
            });
        }
        
        if ($('.modal-select2').length) {
            $('.modal-select2').each(function () {
                $(this).select2({
                    dropdownParent: $(this).closest('.modal')
                }).on("select2:close", function () {
                    $(this).valid();
                }).on("select2:open", function () {
                    focusSelect2Search();
                });
            });
        }
        
        if ($('.offcanvas-select2').length) {
            $('.offcanvas-select2').each(function () {
                $(this).select2({
                    dropdownParent: $(this).closest('.offcanvas')
                }).on("select2:close", function () {
                    $(this).valid();
                }).on("select2:open", function () {
                    focusSelect2Search();
                });
            });
        }
        
        if ($('.filter-select2').length) {
            $('.filter-select2').select2({
                dropdownParent: $('#filter-canvas')
            }).on("select2:open", function () {
                focusSelect2Search();
            });
        }
        
        if ($('.ecom-filter-select2').length) {
            $('.ecom-filter-select2').select2({
                dropdownParent: $('#ecom-filter')
            }).on("select2:open", function () {
                focusSelect2Search();
            });
        }

        if($('.regular-datepicker').length){
            var regular_datepickers = document.querySelectorAll('.regular-datepicker');
            regular_datepickers.forEach(function(element) {
                var filter_date_picker = new Datepicker(element, {
                    buttonClass: 'btn',
                    todayHighlight: true,
                    autohide: true,
                });
            });
        }

        if ($('.future-date-restricted-datepicker').length) {
            var future_date_restricted_datepickers = document.querySelectorAll('.future-date-restricted-datepicker');
        
            future_date_restricted_datepickers.forEach(function(element) {
                var today = new Date();
                var filter_date_picker = new Datepicker(element, {
                    buttonClass: 'btn',
                    todayHighlight: true,
                    autohide: true,
                    maxDate: today,
                });
            });
        }

        if ($('.tiny-mce').length) {
            initTinyMCE();
        }

        if($('#discard-update').length){
            $(document).on('click','#discard-update',() => {
                Swal.fire({
                    title: 'Discard Changes Confirmation',
                    text: 'Are you sure you want to discard the changes made to this item? The changes will be lost permanently once discarded.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Discard',
                    cancelButtonText: 'Cancel',
                    customClass: {
                        confirmButton: 'btn btn-danger mt-2',
                        cancelButton: 'btn btn-secondary ms-2 mt-2'
                    },
                    buttonsStyling: false
                }).then((result) => {
                    if (result.isConfirmed) {
                        resetForm();
                    }
                });
            });
        }
        
        if($('#change-password-offcanvas').length){
            $('#change-password-shortcut-form').validate({
                rules: {
                    shortcut_old_password: {
                      required: true
                    },
                    shortcut_new_password: {
                      required: true,
                      password_strength: true
                    },
                    shortcut_confirm_password: {
                      required: true,
                      equalTo: '#shortcut_new_password'
                    }
                },
                messages: {
                    shortcut_old_password: {
                      required: 'Please enter your old password'
                    },
                    shortcut_new_password: {
                      required: 'Please enter your new password'
                    },
                    shortcut_confirm_password: {
                      required: 'Please re-enter your password for confirmation',
                      equalTo: 'The passwords you entered do not match. Please make sure to enter the same password in both fields'
                    }
                },
              errorPlacement: function (error, element) {
                if (element.hasClass('select2')) {
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
                if ($(element).hasClass('select2-hidden-accessible')) {
                  $(element).next().find('.select2-selection__rendered').addClass('is-invalid');
                } 
                else {
                  $(element).addClass('is-invalid');
                }
              },
              unhighlight: function(element) {
                if ($(element).hasClass('select2-hidden-accessible')) {
                  $(element).next().find('.select2-selection__rendered').removeClass('is-invalid');
                }
                else {
                  $(element).removeClass('is-invalid');
                }
              },
              submitHandler: function(form) {
                const transaction = 'change password shortcut';
          
                $.ajax({
                    type: 'POST',
                    url: 'controller/user-controller.php',
                    data: $(form).serialize() + '&transaction=' + transaction,
                    dataType: 'json',
                    beforeSend: function() {
                        disableFormSubmitButton('submit-password-form');
                    },
                    success: function(response) {
                        if (response.success) {
                            setNotification('Password Change Success', 'Your password has been successfully updated. For security reasons, please use your new password to log in.', 'success');
                            window.location.href = 'logout.php?logout';
                        }
                        else{
                            if(response.isInactive){
                                window.location = 'logout.php?logout';
                            }
                            else{
                                showNotification('Password Change Error', response.message, 'danger');
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
                        enableFormSubmitButton('submit-password-form', 'Update Password');
                    }
                });
          
                return false;
              }
            });
        }

        $(document).on('click','#datatable-checkbox',function() {
            var status = $(this).is(':checked') ? true : false;
            $('.datatable-checkbox-children').prop('checked',status);
    
            toggleActionDropdown();
        });

        $(document).on('click','.datatable-checkbox-children',function() {
            toggleActionDropdown();
        });

        $(document).on('click','.ui-layout',function() {
            const value = $(this).data('layout');

            saveUICustomization('dark layout', value);
        });

        $(document).on('click','.ui-contrast',function() {
            const value = $(this).data('value');

            saveUICustomization('theme contrast', value);
        });

        $(document).on('click','.ui-preset',function() {
            const value = $(this).data('value');

            saveUICustomization('preset theme', value);
        });

        $(document).on('click','.ui-caption',function() {
            const value = $(this).data('value');

            saveUICustomization('caption show', value);
        });

        $(document).on('click','.ui-rtl',function() {
            const value = $(this).data('value');

            saveUICustomization('rtl layout', value);
        });

        $(document).on('click','.ui-box-container',function() {
            const value = $(this).data('value');

            saveUICustomization('box container', value);
        });

        $(document).on('click','#receive-notification',function() {
            var checkbox = document.getElementById("receive-notification");
            var isChecked = checkbox.checked ? 1 : 0;

            updateNotificationSetting(isChecked);
        });

        $(document).on('click','#enable-two-factor-authentication',function() {
            var checkbox = document.getElementById("enable-two-factor-authentication");
            var isChecked = checkbox.checked ? 1 : 0;

            updateTwoFactorAuthentication(isChecked);
        });

        $(document).on('click','#change-user-password',function() {
            resetModalForm('change-password-shortcut-form');
            $('#change-password-modal').modal('show');
        });

        $(document).on('click','#copy-error-message',function() {
            copyToClipboard("error-dialog");
        });
    });
})(jQuery);

function initTinyMCE(){
    document.addEventListener('focusin', function (e) { if (e.target.closest('.tox-tinymce-aux, .moxman-window, .tam-assetmanager-root') !== null) { e.stopImmediatePropagation(); } });
    
    tinymce.init({
        height: '300',
        selector: '.tiny-mce',
        content_style: 'body { font-family: "Inter", sans-serif; }',
        menubar: false,
        toolbar: [
            'styleselect fontselect fontsizeselect',
            'undo redo | cut copy paste | bold italic | link image | alignleft aligncenter alignright alignjustify',
            'bullist numlist | outdent indent | blockquote subscript superscript | advlist | autolink | lists charmap | print preview |  code'
        ],
        plugins: 'advlist autolink link image lists charmap print preview code'
    });
}

function checkOptionExist(element, option, return_value){
    if ($(element).find('option[value="' + option + '"]').length) {
        $(element).val(option).trigger('change');
    }
    else{
        $(element).val(return_value).trigger('change');
    }
}

function checkEmpty(value, id, type){
    const $element = $(id);

    if (value) {
        switch (type) {
            case 'select':
                $element.val(value).change();
                break;
            case 'text':
                $element.text(value);
                break;
            default:
                $element.val(value);
                break;
        }
    }
}

function enableForm(){
    $('.form-details').addClass('d-none');
    $('.form-edit').removeClass('d-none');
}

function resetForm(){
    var errorMessages = document.querySelectorAll('.error');
  
    errorMessages.forEach(function(errorMessage) {
      errorMessage.parentNode.removeChild(errorMessage);
    });
  
    var invalidInputs = document.querySelectorAll('.is-invalid');
    invalidInputs.forEach(function(invalidInput) {
      invalidInput.classList.remove('is-invalid');
    });
    
    $('.form-details').removeClass('d-none');
    $('.form-edit').addClass('d-none');
}

function resetModalForm(form_id) {
    var form = document.getElementById(form_id);
  
    $(form)
      .find(':input')
      .not(':button, :submit, :reset')
      .val('')
      .trigger('change.select2')
      .removeClass('is-invalid');
  
      var errorMessages = form.querySelectorAll('.error');
      errorMessages.forEach(function(errorMessage) {
          errorMessage.parentNode.removeChild(errorMessage);
      });
      
      var invalidInputs = document.querySelectorAll('.is-invalid');
      invalidInputs.forEach(function(invalidInput) {
          invalidInput.classList.remove('is-invalid');
      });
}

function discardCreate(windows_location){
    Swal.fire({
        title: 'Discard Changes Confirmation',
        text: 'Are you sure you want to discard the changes you have made? Any unsaved changes will be lost permanently.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Discard',
        cancelButtonText: 'Cancel',
        customClass: {
            confirmButton: 'btn btn-danger mt-2',
            cancelButton: 'btn btn-secondary ms-2 mt-2'
        },
        buttonsStyling: false
    }).then(function(result) {
        if (result.value) {
            window.location = windows_location;
        }
    });
}

function disableFormSubmitButton(buttonId) {
    try {
      const submitButton = document.querySelector(`#${buttonId}`);
      const { disabled, innerHTML } = submitButton;
  
      submitButton.disabled = true;
      submitButton.innerHTML = '<div class="spinner-border spinner-border-sm text-light" role="status"><span class="sr-only"></span></div>';
    }
    catch (error) {
      console.error(error);
    }
}
  
function enableFormSubmitButton(buttonId, buttonText) {
    try {
      const submitButton = document.querySelector(`#${buttonId}`);
      const { disabled, innerHTML } = submitButton;
  
      submitButton.disabled = false;
      submitButton.innerHTML = buttonText;
    }
    catch (error) {
      console.error(error);
    }
}

function showNotification(notificationTitle, notificationMessage, notificationType) {
  const notificationIcons = {
    success: './assets/images/notification/ok-48.png',
    danger: './assets/images/notification/high_priority-48.png',
    info: './assets/images/notification/survey-48.png',
    warning: './assets/images/notification/medium_priority-48.png',
    default: './assets/images/notification/clock-48.png'
  };

  const icon = notificationIcons[notificationType] || notificationIcons.default;
  const duration = (notificationType === 'danger' || notificationType === 'warning') ? 6000 : 4000;

  notifier.show(notificationTitle, notificationMessage, notificationType, icon, duration);
}

function setNotification(notificationTitle, notificationMessage, notificationType){
    sessionStorage.setItem('notificationTitle', notificationTitle);
    sessionStorage.setItem('notificationMessage', notificationMessage);
    sessionStorage.setItem('notificationType', notificationType);
}

function checkNotification(){
    const { 
        'notificationTitle': notificationTitle, 
        'notificationMessage': notificationMessage, 
        'notificationType': notificationType 
    } = sessionStorage;
    
    if (notificationTitle && notificationMessage && notificationType) {
        sessionStorage.removeItem('notificationTitle');
        sessionStorage.removeItem('notificationMessage');
        sessionStorage.removeItem('notificationType');

        showNotification(notificationTitle, notificationMessage, notificationType);
    }
}

function reloadDatatable(datatable){
    toggleHideActionDropdown();
    $(datatable).DataTable().ajax.reload();
}

function destroyDatatable(datatable_name){
    $(datatable_name).DataTable().clear().destroy();
}

function clearDatatable(datatable_name){
    $(datatable_name).dataTable().fnClearTable();
}

function readjustDatatableColumn() {
    const adjustDataTable = () => {
        const tables = $.fn.dataTable.tables({ visible: true, api: true });
        tables.columns.adjust();
        tables.fixedColumns().relayout();
    };
  
    $('a[data-bs-toggle="tab"], a[data-bs-toggle="pill"], #System-Modal').on('shown.bs.tab shown.bs.modal', adjustDataTable);
}

function maxLength(){
    if ($('[maxlength]').length) {
        $('[maxlength]').maxlength({
            alwaysShow: true,
            warningClass: "badge rounded-pill bg-primary",
            limitReachedClass: "badge rounded-pill bg-danger",
        });
    }
}

function toggleActionDropdown(){
    const inputElements = Array.from(document.querySelectorAll('.datatable-checkbox-children'));
    const multipleAction = $('.action-dropdown');
    const checkedValue = inputElements.filter(chk => chk.checked).length;

    multipleAction.toggleClass('d-none', checkedValue === 0);
}

function toggleHideActionDropdown(){
    $('.action-dropdown').addClass('d-none');
    $('#datatable-checkbox').prop('checked', false);
}

function showErrorDialog(error){
    document.getElementById("error-dialog").innerHTML = error;
    $('#system-error-modal').modal('show');
}

function copyToClipboard(elementId) {
    var element = document.getElementById(elementId);
    var text = element.innerHTML;
  
    navigator.clipboard.writeText(text)
      .then(function() {
        showNotification('Copy Successful', 'Text copied to clipboard', 'success');
      })
      .catch(function(err) {
        showNotification('Copy Error', err, 'danger');
      });
}

function saveUICustomization(type, customizationValue){
    const transaction = 'save ui customization';

    $.ajax({
        type: 'POST',
        url: './controller/user-controller.php',
        data: {
            transaction : transaction, 
            type : type, 
            customizationValue : customizationValue
        },
        dataType: 'json',
        success: function (response) {
            if (!response.success) {
                if(response.isInactive){
                    window.location = 'logout.php?logout';
                }
                else{
                    showNotification('Update UI Settings Error', response.message, 'danger');
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

function getUISettings(){
    const transaction = 'get ui customization';

    $.ajax({
        type: 'POST',
        url: './controller/user-controller.php',
        data: {
            transaction : transaction
        },
        dataType: 'json',
        success: function (response) {
            if (response.success) {
                layout_change(response.darkLayout);
                layout_sidebar_change(response.themeContrast);
                change_box_container(response.boxContainer);
                layout_caption_change(response.captionShow);
                preset_change(response.presetTheme);
            } 
            else {
                if(response.isInactive){
                    window.location = 'logout.php?logout';
                }
                else{
                    showNotification('UI Settings Error', response.message, 'danger');
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

function updateNotificationSetting(isChecked){
    const transaction = 'update notification setting';

    $.ajax({
        type: 'POST',
        url: './controller/user-controller.php',
        data: {
            transaction : transaction, 
            isChecked : isChecked
        },
        dataType: 'json',
        success: function (response) {
            if (!response.success) {
                if(response.isInactive){
                    window.location = 'logout.php?logout';
                }
                else{
                    showNotification('Update Notification Setting Error', response.message, 'danger');
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

function updateTwoFactorAuthentication(isChecked){
    const transaction = 'update two factor authentication';

    $.ajax({
        type: 'POST',
        url: './controller/user-controller.php',
        data: {
            transaction : transaction, 
            isChecked : isChecked
        },
        dataType: 'json',
        success: function (response) {
            if (!response.success) {
                if(response.isInactive){
                    window.location = 'logout.php?logout';
                }
                else{
                    showNotification('Update Two Factor Authentication Error', response.message, 'danger');
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

function createEmployeeQRCode(container, name, badgeID){
    document.getElementById(container).innerHTML = '';

    var card = 'BEGIN:VCARD\r\n';
    card += 'VERSION:3.0\r\n';
    card += 'FN:'+ name +'\r\n';
    card += 'ID NO:[' + badgeID + ']\r\n';
    
    card += 'END:VCARD';

    var qrcode = new QRCode(document.getElementById(container), {
        width: 400,
        height: 400,
        colorDark : "#000000",
        colorLight : "#ffffff",
        correctLevel : QRCode.CorrectLevel.H,
    });

    qrcode.makeCode(card);
}

function createProductQRCode(container, product_id, description, stock_number) {
    document.getElementById(container).innerHTML = '';

    var card = 'BEGIN:VCARD\r\n';
    card += 'VERSION:3.0\r\n';
    card += 'PRODUCT_ID:' + product_id + '\r\n';
    card += 'DESCRIPTION:' + description + '\r\n';
    card += 'STOCK_NUMBER:' + stock_number + '\r\n';
    card += 'END:VCARD';

    var qrcode = new QRCode(document.getElementById(container), {
        width: 400,
        height: 400,
        colorDark: "#000000", 
        colorLight: "#ffffff",
        correctLevel: QRCode.CorrectLevel.H
    });

    qrcode.makeCode(card);
}

function getLocation(containerID) {
    return new Promise((resolve, reject) => {
      if (!navigator.geolocation) {
        showNotification('Get Location Error', 'Geolocation is not supported by your browser', 'danger');
      } else {
        navigator.permissions.query({ name: 'geolocation' }).then((permissionStatus) => {
          if (permissionStatus.state === 'granted') {
            navigator.geolocation.getCurrentPosition(
              (position) => {
                const locationInput = document.getElementById(containerID);
                const location = `${position.coords.latitude},${position.coords.longitude}`;
                locationInput.value = location;
                resolve({
                  latitude: position.coords.latitude,
                  longitude: position.coords.longitude,
                  location: location
                });
              },
              (error) => {
                showNotification('Get Location Error',`Failed to get user location: ${error.message}`, 'danger');
              },
              { enableHighAccuracy: true }
            );
          } else if (permissionStatus.state === 'prompt') {
            navigator.geolocation.getCurrentPosition(
              (position) => {
                const locationInput = document.getElementById(containerID);
                const location = `${position.coords.latitude},${position.coords.longitude}`;
                locationInput.value = location;
                resolve({
                  latitude: position.coords.latitude,
                  longitude: position.coords.longitude,
                  location: location
                });
              },
              (error) => {
                showNotification('Get Location Error',`Failed to get user location: ${error.message}`, 'danger');
              },
              { enableHighAccuracy: true }
            );
          } else {
            showNotification('Get Location Error','Location permission is required to use this feature', 'danger');
          }
        });
      }
    });
}

function productCategoryReferenceTable(datatable_name, buttons = false, show_all = false){
    const type = 'product category reference table';
    var settings;

    const column = [ 
        { 'data' : 'PRODUCT_CATEGORY_ID' },
        { 'data' : 'PRODUCT_CATEGORY' }
    ];

    const column_definition = [
        { 'width': '10%', 'aTargets': 0 },
        { 'width': '90%', 'aTargets': 1 }
    ];

    const length_menu = show_all ? [[-1], ['All']] : [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']];

    settings = {
        'ajax': { 
            'url' : 'view/_product_category_generation.php',
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

function productSubcategoryReferenceTable(datatable_name, buttons = false, show_all = false){
    const type = 'product subcategory reference table';
    var settings;

    const column = [ 
        { 'data' : 'PRODUCT_SUBCATEGORY_ID' },
        { 'data' : 'PRODUCT_SUBCATEGORY' }
    ];

    const column_definition = [
        { 'width': '10%', 'aTargets': 0 },
        { 'width': '90%', 'aTargets': 1 }
    ];

    const length_menu = show_all ? [[-1], ['All']] : [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']];

    settings = {
        'ajax': { 
            'url' : 'view/_product_subcategory_generation.php',
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

function companyReferenceTable(datatable_name, buttons = false, show_all = false){
    const type = 'company reference table';
    var settings;

    const column = [ 
        { 'data' : 'COMPANY_ID' },
        { 'data' : 'COMPANY' }
    ];

    const column_definition = [
        { 'width': '10%', 'aTargets': 0 },
        { 'width': '90%', 'aTargets': 1 }
    ];

    const length_menu = show_all ? [[-1], ['All']] : [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']];

    settings = {
        'ajax': { 
            'url' : 'view/_company_generation.php',
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

function warehouseReferenceTable(datatable_name, buttons = false, show_all = false){
    const type = 'warehouse reference table';
    var settings;

    const column = [ 
        { 'data' : 'WAREHOUSE_ID' },
        { 'data' : 'WAREHOUSE' }
    ];

    const column_definition = [
        { 'width': '10%', 'aTargets': 0 },
        { 'width': '90%', 'aTargets': 1 }
    ];

    const length_menu = show_all ? [[-1], ['All']] : [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']];

    settings = {
        'ajax': { 
            'url' : 'view/_warehouse_generation.php',
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

function bodyTypeReferenceTable(datatable_name, buttons = false, show_all = false){
    const type = 'body type reference table';
    var settings;

    const column = [ 
        { 'data' : 'BODY_TYPE_ID' },
        { 'data' : 'BODY_TYPE' }
    ];

    const column_definition = [
        { 'width': '10%', 'aTargets': 0 },
        { 'width': '90%', 'aTargets': 1 }
    ];

    const length_menu = show_all ? [[-1], ['All']] : [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']];

    settings = {
        'ajax': { 
            'url' : 'view/_body_type_generation.php',
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

function unitReferenceTable(datatable_name, buttons = false, show_all = false){
    const type = 'unit reference table';
    var settings;

    const column = [ 
        { 'data' : 'UNIT_ID' },
        { 'data' : 'UNIT' },
        { 'data' : 'SHORT_NAME' }
    ];

    const column_definition = [
        { 'width': '10%', 'aTargets': 0 },
        { 'width': '45%', 'aTargets': 1 },
        { 'width': '45%', 'aTargets': 2 }
    ];

    const length_menu = show_all ? [[-1], ['All']] : [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']];

    settings = {
        'ajax': { 
            'url' : 'view/_unit_generation.php',
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

function colorReferenceTable(datatable_name, buttons = false, show_all = false){
    const type = 'color reference table';
    var settings;

    const column = [ 
        { 'data' : 'COLOR_ID' },
        { 'data' : 'COLOR' }
    ];

    const column_definition = [
        { 'width': '10%', 'aTargets': 0 },
        { 'width': '90%', 'aTargets': 1 }
    ];

    const length_menu = show_all ? [[-1], ['All']] : [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']];

    settings = {
        'ajax': { 
            'url' : 'view/_color_generation.php',
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

function parseCurrency(value) {
    return parseFloat(value.replace(/,/g, '')) || 0;
}

function encryptCommission(number) {
    // Define the mapping
    const mapping = {
        '1': 'C',
        '2': 'O',
        '3': 'M',
        '4': 'E',
        '5': 'A',
        '6': 'N',
        '7': 'D',
        '8': 'B',
        '9': 'U',
        '0': 'Y'
    };
    
    // Convert the number to a string to iterate over each digit
    const numberStr = number.toString().replace(/,/g, ''); // Remove commas if any
    let encryptedStr = '';
    
    // Map each digit to its corresponding character
    for (let char of numberStr) {
        encryptedStr += mapping[char];
    }
    
    return encryptedStr;
}

function focusSelect2Search() {
    setTimeout(() => {
        let searchField = document.querySelector('.select2-container--open .select2-search__field');
        if (searchField) {
            searchField.focus();
        }
    }, 100);
}