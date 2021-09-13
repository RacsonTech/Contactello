<?php
include('headers.php');
?>
<!DOCTYPE html>
<html>
  <!---Title of the WebPage--->
  <head>
    <!---BootStrap?--->
    <link rel="stylesheet" href="public/css/bootstrap.min.css">
    <!---things to learn-->
    <!---columns, padding, margins, containers, rows-->
    <title>
      SmallProjectCOP4331
    </title>
  </head>

  <!---all the functions--->
  <style>
    h1 {text-align: center;}
    h2 {text-align: center;}
    p {text-align: center;} 

    .img-container {
      text-align: center;
      display: block;

    }

    .borderexample {
     text-align: center;
     border-style:solid;
     border-color: black;
     height: fit-content;
     width: fit-content;
    }

    .center {
    display: flex;
    justify-content: center;
    align-items: center;
    }

    .button {
    background-color: #4CAF50; /* Green */
    border: none;
    color: white;
    padding: 16px 32px;
    text-align: center;
    text-decoration: none;
    display: inline-block;
    font-size: 16px;
    margin: 4px 2px;
    transition-duration: 0.4s;
    cursor: pointer;
    }

    .button2 {
    background-color: white; 
    color: black; 
    border: 2px solid #008CBA;
    }

    .button2:hover {
    background-color: #008CBA;
    color: white;
    }

  </style>

  <!---Here starts the Body Part--->
  <body style = "background-color: rgba(0, 153, 255, 0.589);">
    <div class="img-container"> 
    <!---Logo--->
      <span class="mx-auto d-block"> 
        <img class="img-fluid" src="/Users/ridwan/Desktop/Contactello/Logo 2 - (Background removed) Looka-com.png" >
      </span>
      
      <h1 style="font-style: oblique;"> Making Contact Mangement Easier </h1>     
      <p class="mb-3" style="font-size: x-large; font-style:unset;"> Personal Contact Manager </p>
      
    
    <!---This is maybe to send the info to BackEnd--->
    <div class="container">

      <?php
      if(isset($_SESSION["FirstName"])){
        echo("<h1>Logged in as: " . $_SESSION["FirstName"] . "</h1>");
      }
      ?>

      <form action="LAMPAPI/Login.php" class="was-validated" id="formId" method="POST"> <!---not sure about this-->
        <div class="mb-4">
        <div class="form-group">
          <label for="email"> User ID:</label>
          <input type="text" class="form-control" id="email" placeholder="Enter email" name="Login">
          <div class="valid-feedback">Valid.</div>
          <div class="invalid-feedback"> Please enter valid User ID</div>
        </div>
      </div>

      <div class="mb-4">
        <div class="form-group">
          <label for="pwd">Password:</label>
          <input type="password" class="form-control" id="pwd" placeholder="Enter password" name="Password">
          <div class="valid-feedback">Valid.</div>
          <div class="invalid-feedback"> Please enter valid Password </div>
        </div>
      </div>

      <div class="mb-3">
        <button type="submit" id="submitButton" class="button button2" style="color: black;" >
          Log In
        </button>
      </form>
      </div>
    </div>

    <!---Redirect to Register Page--->
    <p> Don't have an account? </p>
    <div class="center">
      <form method = "POST" action="something.php">
      <input type="submit" value="Register">
      </form>
    </div>
    
    
     <!---icon start--->
    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-chat-quote" viewBox="0 0 16 16">
      <path d="M2.678 11.894a1 1 0 0 1 .287.801 10.97 10.97 0 0 1-.398 2c1.395-.323 2.247-.697 2.634-.893a1 1 0 0 1 .71-.074A8.06 8.06 0 0 0 8 14c3.996 0 7-2.807 7-6 0-3.192-3.004-6-7-6S1 4.808 1 8c0 1.468.617 2.83 1.678 3.894zm-.493 3.905a21.682 21.682 0 0 1-.713.129c-.2.032-.352-.176-.273-.362a9.68 9.68 0 0 0 .244-.637l.003-.01c.248-.72.45-1.548.524-2.319C.743 11.37 0 9.76 0 8c0-3.866 3.582-7 8-7s8 3.134 8 7-3.582 7-8 7a9.06 9.06 0 0 1-2.347-.306c-.52.263-1.639.742-3.468 1.105z"/>
      <path d="M7.066 6.76A1.665 1.665 0 0 0 4 7.668a1.667 1.667 0 0 0 2.561 1.406c-.131.389-.375.804-.777 1.22a.417.417 0 0 0 .6.58c1.486-1.54 1.293-3.214.682-4.112zm4 0A1.665 1.665 0 0 0 8 7.668a1.667 1.667 0 0 0 2.561 1.406c-.131.389-.375.804-.777 1.22a.417.417 0 0 0 .6.58c1.486-1.54 1.293-3.214.682-4.112z"/>
    </svg>
    <!---icon end--->

    <!---Redirect Contact Us Page--->
    <p style="text-align:center; font-variant: small-caps;" >Have Questions?</p>

    <!---Email address--->
    <p style="text-align:center">
      <a href="https://mail.google.com/mail/?view=cm&fs=1&tf=1&to=contactello01@gmail.com" target="_blank">Contact Us</a>
    </p>
    <!---Email End--->

  </div>
  <script href= "public/js/bootstrap.bundle.min.js"></script>
  <script src="public/js/jquery-3.6.0.min.js"></script>
  <script src="public/js/jquery-validation-min.js"></script>
  <script src="public/js/main.js"></script>
  <script>
    $('#formId').on('submit', function(e){
      e.preventDefault();
      console.log(formToJsonString($(this)))
      $.ajax({
        url: 'LAMPAPI/Login',
        type : "POST",
        dataType : 'json',
        contentType: 'application/json;charset=UTF-8',
        data : formToJsonString($(this)),
        success : function(result) {
          console.log("success!")
          console.log(result)
          location.reload()
        }
      })
    })
    
  </script>
  </body>
  </html>
