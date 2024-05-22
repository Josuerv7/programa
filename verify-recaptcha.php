<?php

// Get the reCAPTCHA response
$recaptchaResponse = $_POST['recaptchaResponse'];

// Verify the reCAPTCHA response
$secretKey = 'tu_clave_secreta';
$url = 'https://www.google.com/recaptcha/api/siteverify';
$response = file_get_contents("{$url}?secret={$secretKey}&response={$recaptchaResponse}");
$data = json_decode($response);

if ($data->success) {
  // The reCAPTCHA was verified successfully
  echo json_encode(['success' => true]);
} else {
  // The reCAPTCHA was not verified
  echo json_encode(['success' => false]);
}

?>