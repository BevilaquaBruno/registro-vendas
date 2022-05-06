<?php

function validateEmail(String $email) {
  if(filter_var($email, FILTER_VALIDATE_EMAIL))
    return true;
  else
    return false;
}

function brlCurrencyToDb(String $value) {
  return (float) (str_replace(',', '.', str_replace('.', '', $value)));
}
?>