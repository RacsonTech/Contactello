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

function closestParent (el, cls) {
  while ((el = el.parentElement) && !el.classList.contains(cls));
  return el;
}

function setCookie(name, value, lifetimeDays) {
  var cookie = name + "=" + encodeURIComponent(value);
  const date = new Date()

  date.setTime(date.getTime() + lifetimeDays*24*60*60*1000)
  cookie += ";expires=" + date.toUTCString() + ";path=/";
  
  document.cookie = cookie;
}

function getCookie(name) {
  let cookies = document.cookie.split(";");
  
  for(let i = 0; i < cookies.length; i++) {
      let nvp = cookies[i].split("=");
      
      if(name == nvp[0].trim())
          return decodeURIComponent(nvp[1]);
  }
  return null;
}