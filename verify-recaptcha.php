<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $recaptchaResponse = $_POST['recaptchaResponse'];
    $secret = '6LdaIuUpAAAAAOMNA33djdq5WFktrmJ8MbbvTChV'; // Clave secreta del reCAPTCHA

    $response = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=$secret&response=$recaptchaResponse");
    $responseKeys = json_decode($response, true);

    if ($responseKeys['success']) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error-codes' => $responseKeys['error-codes']]);
    }
}
?>
