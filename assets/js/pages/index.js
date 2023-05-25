$(document).ready(function () {  
  $('#signin-form').validate({
    rules: {
      email: {
        required: true,
        email: true
      },
      choices_single_default: {
        required: true
      },
      password: {
        required: true
      }
    },
    messages: {
      email: {
        required: 'Please enter your email address',
        email: 'Please enter a valid email address'
      },
      choices_single_default: {
        required: 'Please enter your choice'
      },
      password: {
        required: 'Please enter your password'
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
      const transaction = 'authenticate';
  
      $.ajax({
        type: 'POST',
        url: 'controller.php',
        data: $(form).serialize() + '&transaction=' + transaction,
        dataType: 'JSON',
        beforeSend: function() {
          disableFormSubmitButton('signin');
        },
        success: function(response) {
          switch (response[0]['RESPONSE']) {
            case 'Authenticated':
              const email = $('#email').val();
              sessionStorage.setItem('email', email);
  
              window.location = 'dashboard.php';
              break;
            case 'Incorrect':
              showNotification('Authentication Error', 'The username or password you entered is incorrect. Please double-check your credentials and try again.', 'danger');
              break;
            case 'Locked':
              showNotification('Authentication Error', 'Your account has been locked. Please contact your administrator for assistance.', 'danger');
              break;
            case 'Inactive':
              showNotification('Authentication Error', 'Your user account is currently inactive. Please contact your administrator for assistance.', 'danger');
              break;
            case 'Password Expired':
              setNotification('Authentication Error', 'Your password has expired. To keep your account secure, please create a new password to continue.', 'warning');
              window.location = 'reset-password.php?id=' + response[0]['EMAIL'];
              break;
            default:
              showNotification('Authentication Error', response, 'danger');
              break;
          }
        },
        complete: function() {
          enableFormSubmitButton('signin', 'Login');
        }
      });
  
      return false;
    }
  });
  
});