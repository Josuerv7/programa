<?php

header("Content-type: application/json; charset=utf-8");

$servername = "localhost";
$username = "id22182335_usuarios_db";
$password = "Usuarios_db1234";
$dbname = "id22182335_usuarios_db";


$nombre = $_POST["nombre"];
$usuario = $_POST["usuario"];
$contrasena = password_hash($_POST["contrasena"], PASSWORD_BCRYPT); // Hash de la contraseña

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die(json_encode(array("status" => "Connection failed: " . $conn->connect_error)));
}

$sql = $conn->prepare("INSERT INTO usuarios_db (usuario, contrasena, nombre) VALUES (?, ?, ?)");
$sql->bind_param("sss", $usuario, $contrasena, $nombre);

if ($sql->execute() === TRUE) {
    echo json_encode(array("status" => "ok"));
} else {
    echo json_encode(array("status" => "Error: " . $sql->error));
}

$sql->close();
$conn->close();

?>
