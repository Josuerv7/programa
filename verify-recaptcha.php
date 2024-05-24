<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $recaptchaResponse = $_POST['g-recaptcha-response'];
    $secret = '6LdaIuUpAAAAAOMNA33djdq5WFktrmJ8MbbvTChV'; // Clave secreta del reCAPTCHA

    // Validar la respuesta del reCAPTCHA
    $response = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=$secret&response=$recaptchaResponse");
    $responseKeys = json_decode($response, true);

    if ($responseKeys['success']) {
        // Aquí va tu lógica de autenticación del usuario
        // Por ejemplo, verificar el usuario y la contraseña en tu base de datos

        // Si la autenticación es exitosa
        echo json_encode(['status' => 'ok']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Error de reCAPTCHA']);
    }
} else {
    // Si no es una solicitud POST
    header('Location: /');
}
?>
