<?php
if(isset($_COOKIE["UserID"])) {
  setcookie("UserID", "-1", time() - 3600);
  setcookie("FirstName", "-1", time() - 3600);
  setcookie("LastName", "-1", time() - 3600);
}
header("Location: https://contactello.com/login");
?>