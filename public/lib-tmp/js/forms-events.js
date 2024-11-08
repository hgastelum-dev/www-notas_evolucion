function preventDoubleSubmit(formId, submitId, submitText)
{
  var form = document.getElementById(formId);
  var submitButton = document.getElementById(submitId);

  form.addEventListener('submit', function() {

  // Disable the submit button
  submitButton.setAttribute('disabled', 'disabled');

  // Change the "Submit" text
  submitButton.innerHTML = 'Procesando... Por favor, espere un momento';

  setTimeout( function() {
    document.getElementById(submitId).disabled = false;
    submitButton.innerHTML = submitText;
  }, 3000);
          
  }, false);  
}

function noEnter() 
{
  return ! (window.event && window.event.keyCode == 13); 
}