var contactModal = $('#contactModal')
var contactForm = $('#contact-modal-form')
var modalType = "Add";

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

contactModal.on('show.bs.modal', function (event) {
  let button = $(event.relatedTarget).first()
  modalType = button.data('type')
  $('#contactModalLabel').text(modalType + ' Contact')
  // TODO: Load current contact values into modal
  $('#modal-contact-firstname').val('')
  $('#modal-contact-lastname').val('')
  $('#modal-contact-phone').val('')
  $('#modal-contact-email').val('')
})

contactForm.on('submit', function(event) {
  event.preventDefault();
  $.ajax({
    url: 'LAMPAPI/Add.php',
    type : "POST",
    dataType : 'json',
    contentType: 'application/json;charset=UTF-8',
    data : formToJsonString($(this)),
    success : function(result) {
      console.log(result)
      // location.reload()
    }
  })
})

$(function() {
  $.get("LAMPAPI/SearchContact.php", function(data) {
    let contactContainer = document.querySelector('#contactContainer');
    let row = createRow();
    contactContainer.appendChild(row)
    for(let i = 1; i <= data.results.length; i++){
      // TODO add support for tel and mailto attributes
      let contact = data.results[i-1]; //subtract 1 since we are starting at 1 for modulo (at bottom)
      let contactCard = document.querySelector('#contactTemplate').cloneNode(true);
      let name = contactCard.querySelector('.card-title')
      name.innerText = contact.FirstName + " " + contact.LastName;
      let phone = contactCard.querySelector('.card-phone')
      phone.innerText = contact.PhoneNumber
      let email = contactCard.querySelector('.card-email')
      email.innerText = contact.Email
      row.appendChild(contactCard)
      if(i % 3 == 0){
        row = createRow()
        contactContainer.appendChild(row)
      }
    }
  }, "json")
})

function createRow(){
  let row = document.createElement('div')
  row.classList.add('row')
  return row;
}

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