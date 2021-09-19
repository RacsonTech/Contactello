<?php
  # Redirect to login if UserID cookie not set.
  if(!isset($_COOKIE["UserID"])) {
    header("Location: https://contactello.com/login");
    exit;
  }
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title></title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="public/css/bootstrap.min.css">
    <link rel="stylesheet" href="public/css/fontawesome.min.css">
    <link rel="stylesheet" href="public/css/aos.min.css">
    <link rel="stylesheet" href="public/css/style.css">
  </head>
  <body>
    <nav class="navbar navbar-expand-md navbar-dark bg-dark fixed-top justify-content-center">
      <div class="container">
        <a href="/" class="navbar-brand d-flex w-50 me-auto">Contactello</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#collapsingMainNav">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="navbar-collapse collapse w-100" id="collapsingMainNav">
          <!-- <ul class="navbar-nav w-100 justify-content-center">
            <li class="nav-item active">
              <a class="nav-link" href="#">Home</a>
            </li>
          </ul> -->
          <ul class="nav navbar-nav ms-auto w-100 justify-content-end">
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">Developer</a>
              <ul class="dropdown-menu dropdown-menu-end">
                <li><a class="dropdown-item" href="logout">Logout</a></li>
              </ul>
            </li>
          </ul>
        </div>
      </div>
    </nav>

    <div class="container-fluid">
      <div class="container text-center">
        <div class="row">
          <div class="col-sm-4 mx-auto">
            <div class="input-group mb-3">
              <input type="text" id="search-input" class="form-control" placeholder="Search..." aria-label="Search..." aria-describedby="search-button">
              <button class="btn btn-dark" type="button" id="search-button">Submit</button>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="container-fluid">
      <div id="contactContainer" class="container text-center">
      </div>
      <div id="loader" class="display-5 text-white text-center"><img src="public/images/circles-preloader.gif" style="width:25px;height:25px;"/></div>
    </div>

    <div class="position-fixed bottom-0 end-0">
      <button class="btn btn-warning btn-circle-xl m-1 m-md-2" data-type="Add" data-bs-toggle="modal" data-bs-target="#contactModal">
        <i class="fas fa-plus btn-circle-icon"></i>
      </button>
    </div>

    <div class="modal fade" id="contactModal" tabindex="-1" aria-labelledby="contactModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="contactModalLabel">Edit Contact</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <form id="contact-modal-form" novalidate>
              <div class="mb-3">
                <label for="modal-contact-firstname" class="col-form-label">First Name:</label>
                <input type="text" name="FirstName" class="form-control" id="modal-contact-firstname">
              </div>
              <div class="mb-3">
                <label for="modal-contact-lastname" class="col-form-label">Last Name:</label>
                <input type="text" name="LastName" class="form-control" id="modal-contact-lastname">
              </div>
              <div class="mb-3">
                <label for="modal-contact-phone" class="col-form-label">Phone:</label>
                <input type="tel" name="PhoneNumber" class="form-control" id="modal-contact-phone"></input>
              </div>
              <div class="mb-3">
                <label for="modal-contact-email" class="col-form-label">E-Mail:</label>
                <input type="email" name="Email" class="form-control" id="modal-contact-email"></input>
              </div>
            </form>
          </div>
          <div class="modal-footer">
            <button type="submit" form="contact-modal-form"  class="btn btn-primary">Save Changes</button>
          </div>
        </div>
      </div>
    </div>

    <div class="modal fade" id="confirmationModal" tabindex="-1" aria-labelledby="confirmationModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="confirmationModalLabel">Delete Contact</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <p>Are you sure you want to permanantly delete <strong id="confirmationModalName"></strong> from your contacts?</p>
          </div>
          <div class="modal-footer">
            <button class="confirmationDeleteButton btn btn-danger">Delete Permanantly</button>
          </div>
        </div>
      </div>
    </div>

    <div id="templates">
      <div id="contactTemplate" class="col-sm-4 mb-3">
        <div class="card" data-aos="fade">
          <div class="card-body">
            <a class="card-options-icon contact-delete" data-bs-toggle="modal" data-bs-target="#confirmationModal">
              <i class="far fa-trash-alt" aria-hidden="true"></i>
            </a>
            <h5 class="card-title"></h5>
            <p class="card-text mb-0"><strong>Phone Number:</strong> <a class="card-phone" href="tel:123-456-7890"></a></p>
            <p class="card-text"><strong>E-Mail:</strong> <a class="card-email" href="mailto:user@example.com"></a></p>
            <button type="button" class="btn btn-primary contact-edit" data-type="Edit" data-bs-toggle="modal" data-bs-target="#contactModal">Edit Contact</button>
          </div>
        </div>
      </div>
    </div>

    <script src="public/js/jquery-3.6.0.min.js"></script>
    <script src="public/js/jquery-validation-min.js"></script>
    <script src="public/js/bootstrap.bundle.min.js"></script>
    <script src="public/js/aos.min.js"></script>
    <script src="public/js/libphonenumber-js.min.js"></script>
    <script src="public/js/main.js"></script>
    <script src="public/js/contacts.js"></script>

    <script>
      AOS.init({
        duration: 400,
        once: true,
        mirror: false,
      });
    </script>
  </body>
</html>