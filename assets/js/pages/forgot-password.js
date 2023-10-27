$(document).ready(function () {  
  $('#forgot-password-form').validate({
    rules: {
      email: {
        required: true,
      }
    },
     messages: {
      email: {
        required: 'Please enter email address'
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
      const transaction = 'forgot password';
  
      $.ajax({
        type: 'POST',
        url: 'controller/user-controller.php',
        data: $(form).serialize() + '&transaction=' + transaction,
        dataType: 'json',
        beforeSend: function() {
          disableFormSubmitButton('forgot-password');
        },
        success: function(response) {
          if (response.success) {
            setNotification('Password Reset Success', 'The password reset link has been sent to your registered email address. Please check your inbox and follow the instructions in the email to securely reset your password. If you don’t receive the email within a few minutes, please check your spam folder as well.', 'success');
            window.location.href = 'index.php';
          } 
          else {
            showNotification('Password Reset Error', response.message, 'danger');
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
          enableFormSubmitButton('forgot-password', 'Send Password Reset Email');
        }
      });
  
      return false;
    }
  });
});