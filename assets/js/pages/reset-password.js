$(document).ready(function () {  
  $('#reset-password-form').validate({
    rules: {
      password: {
        required: true,
        password_strength: true
      },
      confirm_password: {
        required: true,
        equalTo: '#password'
      }
    },
    messages: {
      password: {
        required: 'Please enter password'
      },
      confirm_password: {
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
      const transaction = 'reset password';

      $.ajax({
        type: 'POST',
        url: 'controllers/administrator-controller.php',
        data: $(form).serialize() + '&transaction=' + transaction,
        dataType: 'TEXT',
        beforeSend: function() {
          disableFormSubmitButton('reset-password');
        },
        success: function(response) {
          switch (response) {
            case 'Updated':
            setNotification('Reset Password Success', 'Your password has been successfully updated. For security reasons, please use your new password to log in.', 'success');
            window.location = 'index.php';
            break;
          case 'Inactive':
            showNotification('Reset Password Error', 'Your user account is currently inactive. Please contact your administrator for assistance.', 'danger');
            break;
          case 'Password Exist':
            showNotification('Reset Password Error', 'Your new password must not match your previous one. Please choose a different password.', 'danger');
            break;
          default:
            showNotification('Reset Password Error', response, 'danger');
            break;
          }
        },
        error: function(xhr, status, error) {
          showNotification('Reset Password Error', 'An error occurred while processing your request. Please try again later.', 'danger');
        },
        complete: function() {
          enableFormSubmitButton('reset-password', 'Reset Password');
        }
      });

      return false;
    }
  });
});