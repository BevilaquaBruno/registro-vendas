<?php

function validateEmail($email) {
  if(filter_var($email, FILTER_VALIDATE_EMAIL))
    return true;
  else
    return false;
}

?>