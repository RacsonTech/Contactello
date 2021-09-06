var exampleModal = $('#contactModal')
var contactForm = $('#contact-modal-form')

function formToJson(form) {
  let formArray = form.serializeArray()
  let returnArray = {}
  for (let i = 0; i < formArray.length; i++)
      returnArray[formArray[i]['name']] = formArray[i]['value']
  return returnArray
}

function formToJsonString(form) {
  return JSON.stringify(formToJson(form))
}

exampleModal.on('show.bs.modal', function (event) {
  let button = event.relatedTarget
  // TODO: Load current contact values into modal
  $('#modal-contact-name').val('');
  $('#modal-contact-phone').val('');
  $('#modal-contact-email').val('');
})

$('#modal-contact-phone').on('focusout', function() {
  if(validatePhoneNumber($(this).val())){}
    $(this).val(formatPhoneNumber($(this).val()))
})

function cleanPhoneNumber(text) {
  return text.replace(/\D/g, '');
}

function formatPhoneNumber(text) {
  let phoneNumber = libphonenumber.parsePhoneNumber(text, "US")
  if(text.startsWith("+"))
    return phoneNumber.formatInternational()
  return phoneNumber.formatNational();
}

function validatePhoneNumber(text) {
  return libphonenumber.isPossiblePhoneNumber(text, "US")
}

jQuery.validator.addMethod('validPhone', function(val, elem) {
  return val.length == 0 || validatePhoneNumber(cleanPhoneNumber(val))
}, "Please enter a valid phone number.")

$(function() {
  contactForm.validate({
    rules: {
      name : {
        required: true
      },
      phone: {
        required: false,
        validPhone: true,
      },
      email: {
        required: false,
        email: true
      }
    }
  });
});