(function($) {
    'use strict';

    $(function() {
        $.fn.modal.Constructor.prototype.enforceFocus = function() {};
        checkNotification();
        uiCustomization();
        loadUISettings();
        intializeMaxLength();

        if ($('#email_account').length) {
            const email_account = $('#email_account').text();
            getCustomization(email_account);
        }

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
    });
})(jQuery);

function displayDetails(transaction){
    switch (transaction) {
        case 'menu groups details':
            var menu_group_id = $('#menu-group-id').text();
            
            $.ajax({
                url: 'controller.php',
                method: 'POST',
                dataType: 'JSON',
                data: {menu_group_id : menu_group_id, transaction : transaction},
                success: function(response) {
                    $('#menu_group').val(response[0].MENU_GROUP_NAME);
                    $('#menu_group_order_sequence').val(response[0].ORDER_SEQUENCE);
                    
                    $('#menu_group_label').text(response[0].MENU_GROUP_NAME);
                    $('#order_sequence_label').text(response[0].ORDER_SEQUENCE);
                }
            });
            break;
        case 'modal menu item details':
            var menu_item_id = sessionStorage.getItem('menu_item_id');
            
            $.ajax({
                url: 'controller.php',
                method: 'POST',
                dataType: 'JSON',
                data: {menu_item_id : menu_item_id, transaction : transaction},
                beforeSend: function() {
                    resetModalForm('menu-item-form');
                },
                success: function(response) {
                    $('#menu_item_id').val(menu_item_id);
                    $('#menu_item_name').val(response[0].MENU_ITEM_NAME);
                    $('#menu_item_url').val(response[0].MENU_ITEM_URL);
                    $('#menu_item_icon').val(response[0].MENU_ITEM_ICON);
                    $('#menu_item_order_sequence').val(response[0].ORDER_SEQUENCE);
                    
                    checkOptionExist('#parent_id', response[0].PARENT_ID, '');
                }
            });
            break;
            case 'menu item details':
                var menu_item_id = $('#menu-item-id').text();
                
                $.ajax({
                    url: 'controller.php',
                    method: 'POST',
                    dataType: 'JSON',
                    data: {menu_item_id : menu_item_id, transaction : transaction},
                    success: function(response) {
                        $('#menu_item_name').val(response[0].MENU_ITEM_NAME);
                        $('#menu_item_order_sequence').val(response[0].ORDER_SEQUENCE);
                        $('#menu_item_url').val(response[0].MENU_ITEM_URL);
                        $('#menu_item_icon').val(response[0].MENU_ITEM_ICON);
                        
                        $('#menu_item_name_label').text(response[0].MENU_ITEM_NAME);
                        $('#order_sequence_label').text(response[0].ORDER_SEQUENCE);
                        $('#menu_item_icon_label').text(response[0].MENU_ITEM_ICON);
                        
                        document.getElementById("menu_group_id_label").innerHTML = response[0].MENU_GROUP_NAME;
                        document.getElementById("menu_item_url_label").innerHTML = response[0].MENU_ITEM_URL_LINK;
                        document.getElementById("parent_id_label").innerHTML = response[0].PARENT_NAME;

                        checkOptionExist('#menu_group_id', response[0].MENU_GROUP_ID, '');
                        checkOptionExist('#parent_id', response[0].PARENT_ID, '');
                    }
                });
                break;
    }
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

function saveCustomization(type, customization_value){
    const email = $('#email_account').text();
    const transaction = 'submit user ui customization setting';

    $.ajax({
        type: 'POST',
        url: 'controller.php',
        data: {transaction : transaction, email : email, type : type, customization_value : customization_value},
        dataType: 'TEXT',
        success: function (response) {
            switch (response) {
                case 'Updated':
                    showNotification('Update UI Settings Success', 'The UI settings has been updated successfully.', 'success');
                    break;
                case 'User Not Found':
                case 'Inactive User':
                    window.location = 'logout.php?logout';
                    break;
                default:
                    showNotification('Update UI Settings Error', response, 'danger');
                    break;
            }
        }
    });
}

function getCustomization(email){
    const transaction = 'ui customization settings details';

    $.ajax({
        url: 'controller.php',
        method: 'POST',
        dataType: 'JSON',
        data: {email : email, transaction : transaction},
        success: function(response) {
            sessionStorage.setItem('theme_contrast', response[0].THEME_CONTRAST);
            sessionStorage.setItem('caption_show', response[0].CAPTION_SHOW);
            sessionStorage.setItem('preset_theme', response[0].PRESET_THEME);
            sessionStorage.setItem('dark_layout', response[0].DARK_LAYOUT);
            sessionStorage.setItem('rtl_layout', response[0].RTL_LAYOUT);
            sessionStorage.setItem('box_container', response[0].BOX_CONTAINER);
        },
        complete: function(){
            loadUISettings();
        }
    });
}

function uiCustomization(){
    $(document).on('click', '[id^="preset_"], #layout_light, #layout_dark, #light_contrast, #dark_contrast, #show_caption, #hide_caption, #ltr, #rtl, #full_layout, #boxed_layout', function() {
        var id = this.id;
      
        switch (id) {
          case 'layout_light':
            layoutChange('light');
            saveCustomization('dark_layout', 'false');
            sessionStorage.setItem('dark_layout', 'false');
            break;
          case 'layout_dark':
            layoutChange('dark');
            saveCustomization('dark_layout', 'true');
            sessionStorage.setItem('dark_layout', 'true');
            break;
          case 'light_contrast':
            layoutSidebarChange('true');
            saveCustomization('theme_contrast', 'true');
            sessionStorage.setItem('theme_contrast', 'true');
            break;
          case 'dark_contrast':
            layoutSidebarChange('false');
            saveCustomization('theme_contrast', 'false');
            sessionStorage.setItem('theme_contrast', 'false');
            break;
          case 'show_caption':
            layoutCaptionChange('true');
            saveCustomization('caption_show', 'true');
            sessionStorage.setItem('caption_show', 'true');
            break;
          case 'hide_caption':
            layoutCaptionChange('false');
            saveCustomization('caption_show', 'false');
            sessionStorage.setItem('caption_show', 'false');
            break;
          case 'ltr':
            layoutRTLChange('false');
            saveCustomization('rtl_layout', 'false');
            sessionStorage.setItem('rtl_layout', 'false');
            break;
          case 'rtl':
            layoutRTLChange('true');
            saveCustomization('rtl_layout', 'true');
            sessionStorage.setItem('rtl_layout', 'true');
            break;
          case 'full_layout':
            changeBoxContainer('false');
            saveCustomization('box_container', 'false');
            sessionStorage.setItem('box_container', 'false');
            break;
          case 'boxed_layout':
            changeBoxContainer('true');
            saveCustomization('box_container', 'true');
            sessionStorage.setItem('box_container', 'true');
            break;
          default:
            var value = $(this).data('value');
            saveCustomization('preset_theme', value);
            sessionStorage.setItem('preset_theme', value);
        }
    });
}

function loadUISettings(){
    const theme_contrast = sessionStorage.getItem('theme_contrast') || 'false';
    const caption_show = sessionStorage.getItem('caption_show') || 'true';
    const preset_theme = sessionStorage.getItem('preset_theme') || 'preset-1';
    const dark_layout = sessionStorage.getItem('dark_layout') || 'false';
    const rtl_layout = sessionStorage.getItem('rtl_layout') || 'false';
    const box_container = sessionStorage.getItem('box_container') || 'false';
    const version = 'v9.0';

    if (theme_contrast == 'true') {
        layoutSidebarChange('true');
    }
    else {
        layoutSidebarChange('false');
    }
    
    if (caption_show == 'true') {
        layoutCaptionChange('true');
    }
    else {
        layoutCaptionChange('false');
    }
      
    if (preset_theme != "") {
        presetChange(preset_theme);
    }
      
    if (rtl_layout == 'true') {
        layoutRTLChange('true');
    }
    else {
        layoutRTLChange('false');
    }
    
    if(dark_layout == "default"){
        if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
            dark_layout = 'true';
        }
        else{
            dark_layout = 'false';
        }
    }
      
    if (dark_layout == 'true') {
        layoutChange('dark');
    }
    else {
        layoutChange('light');
    }
      
    if (box_container == 'true') {
        changeBoxContainer('true');
    }
    else {
        changeBoxContainer('false');
    }
}

function layoutSidebarChange(value) {
    if (value == 'true') {
        document.getElementsByTagName('body')[0].setAttribute('data-pc-theme_contrast', 'true');
    
        var control = document.querySelector('.theme-contrast .btn.active');
        if (control) {
          document.querySelector('.theme-contrast .btn.active').classList.remove('active');
          document.querySelector(".theme-contrast .btn[data-value='true']").classList.add('active');
        }
      } else {
        document.getElementsByTagName('body')[0].setAttribute('data-pc-theme_contrast', '');
        var control = document.querySelector('.theme-contrast .btn.active');
        if (control) {
          document.querySelector('.theme-contrast .btn.active').classList.remove('active');
          document.querySelector(".theme-contrast .btn[data-value='false']").classList.add('active');
        }
      }
}

function layoutCaptionChange(value) {
    var attributeValue = value === 'true' ? 'true' : 'false';
    document.getElementsByTagName('body')[0].setAttribute('data-pc-sidebar-caption', attributeValue);

    var activeButton = document.querySelector('.theme-nav-caption .btn.active');
    if (activeButton) {
        activeButton.classList.remove('active');
        var buttonSelector = ".theme-nav-caption .btn[data-value='" + attributeValue + "']";
        document.querySelector(buttonSelector).classList.add('active');
    }
}

function presetChange(value) {
    const body = document.getElementsByTagName('body')[0];
    const presetValue = value.toString();
    body.setAttribute('data-pc-preset', presetValue);
    
    const activeLink = document.querySelector('.preset-color > a.active');
    if (activeLink) {
      activeLink.classList.remove('active');
    }
    
    const newActiveLink = document.querySelector(`.preset-color > a[data-value="${presetValue}"]`);
    if (newActiveLink) {
      newActiveLink.classList.add('active');
    }    
}

function layoutRTLChange(value) {
    const body = document.getElementsByTagName('body')[0];
    const html = document.getElementsByTagName('html')[0];
    const control = document.querySelector('.theme-direction .btn.active');

    if (value === 'true') {
    rtl_flag = true;
    body.setAttribute('data-pc-direction', 'rtl');
    html.setAttribute('dir', 'rtl');
    html.setAttribute('lang', 'ar');
    } else {
    rtl_flag = false;
    body.setAttribute('data-pc-direction', 'ltr');
    html.removeAttribute('dir');
    html.removeAttribute('lang');
    }

    if (control) {
    control.classList.remove('active');
    const newControl = document.querySelector(`.theme-direction .btn[data-value='${value}']`);
    if (newControl) {
        newControl.classList.add('active');
    }
    }
}

function layoutChange(layout) {
    const body = document.getElementsByTagName('body')[0];
    const control = document.querySelector('.pct-offcanvas');
    const logoEl = document.querySelector('.pc-sidebar .m-header .logo-lg, .auth-header img');

    body.setAttribute('data-pc-theme', layout);

    const defaultBtn = document.querySelector('.theme-layout .btn[data-value="default"]');
    if (defaultBtn) {
    defaultBtn.classList.remove('active');
    }

    if (layout === 'dark') {
    dark_flag = true;
    if (control && logoEl) {
        logoEl.setAttribute('src', '../assets/images/logo-white.svg');
    }
    document.querySelector('.theme-layout .btn.active')?.classList.remove('active');
    document.querySelector('.theme-layout .btn[data-value="false"]')?.classList.add('active');
    } else {
    dark_flag = false;
    if (control && logoEl) {
        logoEl.setAttribute('src', '../assets/images/logo-dark.svg');
    }
    document.querySelector('.theme-layout .btn.active')?.classList.remove('active');
    document.querySelector('.theme-layout .btn[data-value="true"]')?.classList.add('active');
    }
}

function changeBoxContainer(value) {
    if (document.querySelector('.pc-content')) {
        if (value == 'true') {
          document.querySelector('.pc-content').classList.add('container');
          document.querySelector('.footer-wrapper').classList.add('container');
          document.querySelector('.footer-wrapper').classList.remove('container-fluid');
    
          var control = document.querySelector('.theme-container .btn.active');
          if (control) {
            document.querySelector('.theme-container .btn.active').classList.remove('active');
            document.querySelector(".theme-container .btn[data-value='true']").classList.add('active');
          }
        } else {
          document.querySelector('.pc-content').classList.remove('container');
          document.querySelector('.footer-wrapper').classList.remove('container');
          document.querySelector('.footer-wrapper').classList.add('container-fluid');
          var control = document.querySelector('.theme-container .btn.active');
          if (control) {
            document.querySelector('.theme-container .btn.active').classList.remove('active');
            document.querySelector(".theme-container .btn[data-value='false']").classList.add('active');
          }
        }
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