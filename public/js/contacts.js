let contactModal = $('#contactModal')
let contactForm = $('#contact-modal-form')
let confirmationModal = $('#confirmationModal')
const loadedContacts = new Map();
let selectedContact = {};

let isLazyLoading = false;
let noResults = false;


function selectContact(id){
  let contact = loadedContacts.get(id.toString());
  if(!contact) return;
  selectedContact.contact = contact;
}

$('#search-button').on('click', function(event){
  let loc = new URL(window.location.href)
  let val = document.getElementById('search-input').value;
  loc.searchParams.set("search", encodeURIComponent(val))
  window.location.search = loc.searchParams.toString();
})

$('#search-input').keydown(function(event) {
  if (event.which === 13) {
      document.getElementById('search-button').click()
  }
});

contactModal.on('show.bs.modal', function (event) {
  let button = $(event.relatedTarget).first()
  selectedContact.actionType = button.data('type')

  $('#contactModalLabel').text(selectedContact.actionType + ' Contact')
  if(selectedContact.actionType == "Edit"){
    selectContact(button.data('contact-id'));
    // TODO: Load current contact values into modal
    $('#modal-contact-firstname').val(selectedContact.contact.FirstName)
    $('#modal-contact-lastname').val(selectedContact.contact.LastName)
    $('#modal-contact-phone').val(selectedContact.contact.PhoneNumber)
    $('#modal-contact-email').val(selectedContact.contact.Email)
  }
  else {
    $('#modal-contact-firstname').val("")
    $('#modal-contact-lastname').val("")
    $('#modal-contact-phone').val("")
    $('#modal-contact-email').val("")
  }
})

contactForm.on('submit', function(event) {
  event.preventDefault();
  let url = "LAMPAPI/"
  let type = "POST"
  let data = formToJson($(this))
  data.UserID = getCookie("UserID")
  data.ContactID = selectedContact.contact.ContactID;
  if(selectedContact.actionType == "Edit"){
    url += "Update"
    type = "PUT"
  }
  else if(selectedContact.actionType == "Add"){
    url += "Add"
  }
  else return;
  data = JSON.stringify(data)
  $.ajax({
    url: url,
    type : type,
    dataType : 'json',
    contentType: 'application/json;charset=UTF-8',
    data : data,
    success : function(result) {
      console.log(result)
      // location.reload()
    }
  })
})

confirmationModal.on('show.bs.modal', function (event) {
  let button = $(event.relatedTarget).first()
  selectedContact.actionType = "Delete";
  selectContact(button.data('contact-id'))
  $("#confirmationModalName").text(`${selectedContact.contact.FirstName} ${selectedContact.contact.LastName}`)
})

$('.confirmationDeleteButton').on('click', function(){
  $.ajax({
    url: `LAMPAPI/DeleteContact?UserID=${getCookie("UserID")}&ContactID=${selectedContact.contact.ContactID}`,
    type : "DELETE",
    dataType : 'json',
    success : function(result) {
      location.reload()
    }
  })
})

function loadContacts(offset = 0){
  let loc = new URL(window.location.href)
  let req = `LAMPAPI/SearchContact?UserID=${getCookie("UserID")}`;
  let search = loc.searchParams.get('search')
  if(search)
    req += '&Search=' + search
  req += `&offset=${offset}`
  $.get(req, function(data) {
    if(data.results == undefined){
      noResults = true;
      document.getElementById("loader").style.display = "none"
      return;
    }
    let contactContainer = document.querySelector('#contactContainer');
    let row = createRow();
    contactContainer.appendChild(row)
    for(let i = 1; i <= data.results.length; i++){
      let contact = data.results[i-1]; //subtract 1 since we are starting at 1 for modulo (at bottom)
      loadedContacts.set(contact.ContactID, contact);

      let contactCard = document.querySelector('#contactTemplate').cloneNode(true);
      let name = contactCard.querySelector('.card-title')
      name.innerText = contact.FirstName + " " + contact.LastName
      let phone = contactCard.querySelector('.card-phone')
      phone.innerText = contact.PhoneNumber
      var cleanPhone = contact.PhoneNumber.replace(/[^0-9\+]/g, '')
      phone.href = "tel:" + cleanPhone;
      let email = contactCard.querySelector('.card-email')
      email.innerText = contact.Email
      email.href = "mailto:" + contact.Email

      let deleteButton = contactCard.querySelector('.contact-delete')
      deleteButton.setAttribute('data-contact-id', contact.ContactID)
      
      let editButton = contactCard.querySelector('.contact-edit')
      editButton.setAttribute('data-contact-id', contact.ContactID)

      row.appendChild(contactCard)
      if(i % 3 == 0){
        row = createRow()
        contactContainer.appendChild(row)
      }
    }
  }, "json")
}

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

$(function() {
  let options = {
    root: null,
    rootMargin: "0px",
    threshold: 0.25
  };

function handleIntersect(entries, observer) {
  entries.forEach((entry) => {
    if (entry.isIntersecting && !isLazyLoading && !noResults) {
      isLazyLoading = true;
      setTimeout(() => {
        loadContacts(loadedContacts.size);
        isLazyLoading = false;
      }, 500)
    }
  });
}

let observer = new IntersectionObserver(handleIntersect, options);
  observer.observe(document.getElementById("loader"));
})