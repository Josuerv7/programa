<?php

// Get the reCAPTCHA response
$recaptchaResponse = $_POST['recaptchaResponse'];

// Verify the reCAPTCHA response
$secretKey = '6LdaIuUpAAAAAOMNA33djdq5WFktrmJ8MbbvTChV';
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