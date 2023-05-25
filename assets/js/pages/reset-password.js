'use strict';

const bouncer = new Bouncer('[reset-password-form-validate]', {
  disableSubmit: true,
  customValidations: {
    valueMismatch: function (field) {
      var selector = field.getAttribute('data-bouncer-match');
      if (!selector) return false;
      var otherField = field.form.querySelector(selector);
      if (!otherField) return false;
      return otherField.value !== field.value;
    }
  },
  messages: {
    valueMismatch: function (field) {
      var customMessage = field.getAttribute('data-bouncer-mismatch-message');
      return customMessage ? customMessage : 'Please make sure the fields match.';
    }
  }
});

document.addEventListener('bouncerFormInvalid', function (event) {
  window.scrollTo(0, event.detail.errors[0].offsetTop);
}, false);

document.addEventListener('bouncerFormValid', function () {
  const form = document.querySelector('[reset-password-form-validate]');
  const transaction = 'reset password';

  $.ajax({
    type: 'POST',
    url: 'controller.php',
    data: $(form).serialize() + '&transaction=' + transaction,
    dataType: 'TEXT',
    beforeSend: function() {
      disableFormSubmitButton('reset-password');
    },
    success: function (response) {
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
    complete: function() {
      enableFormSubmitButton('reset-password', 'Reset Password');
    }
  });
}, false);