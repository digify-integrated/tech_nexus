(function($) {
    'use strict';

    $(function() {
        getUISettings();
        checkNotification();
        intializeMaxLength();

        if($('.log-notes-scroll').length){
            new SimpleBar(document.querySelector('.log-notes-scroll'));
        }

        if($('.select2').length){
            $('.select2').select2({}).on("change", function (e) {
                $(this).valid()
            });
        }

        if($('.modal-select2').length){
            $('.modal-select2').each(function() {
                $(this).select2({
                  dropdownParent: $(this).closest('.modal')
                }).on("select2:close", function () {
                    $(this).valid();
                });
            });
        }

        if($('.filter-select2').length){
            $('.filter-select2').select2({
                dropdownParent: $('#filter-canvas')
            });
        }

        if($('#edit-form').length){
            $(document).on('click','#edit-form',function() {
                $('.form-details').addClass('d-none');
                $('.form-edit').removeClass('d-none');
            });
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

        $(document).on('click','#copy-error-message',function() {
            copyToClipboard("error-dialog");
        });
    });
})(jQuery);

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

function resetModalForm(form_id){
    var form = document.getElementById(form_id);

    form.reset();

    var errorMessages = document.querySelectorAll('.error');

    errorMessages.forEach(function(errorMessage) {
      errorMessage.parentNode.removeChild(errorMessage);
    });

    var invalidInputs = form.querySelectorAll('.is-invalid');
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

function intializeMaxLength(){
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
        data: {transaction : transaction, type : type, customizationValue : customizationValue},
        dataType: 'JSON',
        success: function (response) {
            if (response.success) {
                showNotification('Update UI Settings Success', 'The UI settings has been updated successfully', 'success');
            } 
            else {
                if(response.isInactive){
                    window.location = 'logout.php?logout';
                }
                else{
                    showNotification('Update UI Settings Error', response.message, 'danger');
                }
            }
        },
        error: function(xhr, status, error) {
            var fullErrorMessage = 'XHR status: ' + status + ', Error: ' + error;
  
            var response = xhr.responseText;
            fullErrorMessage += ', Response: ' + response;
          
            showErrorDialog(fullErrorMessage);
        }
    });
}

function getUISettings(){
    const transaction = 'get ui customization';

    $.ajax({
        type: 'POST',
        url: './controller/user-controller.php',
        data: {transaction : transaction},
        dataType: 'JSON',
        success: function (response) {
            if (response.success) {
                layout_change(response.darkLayout);
                layout_sidebar_change(response.themeContrast);
                change_box_container(response.boxContainer);
                layout_caption_change(response.captionShow);
                layout_rtl_change(response.rtlLayout);
                preset_change(response.presetTheme);
            } 
            else {
                showNotification('UI Settings Error', response.message, 'danger');
            }
        },
        error: function(xhr, status, error) {
            var fullErrorMessage = 'XHR status: ' + status + ', Error: ' + error;
  
            var response = xhr.responseText;
            fullErrorMessage += ', Response: ' + response;
          
            showErrorDialog(fullErrorMessage);
        }
    });
}