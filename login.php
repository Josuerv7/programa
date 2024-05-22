<?php

header("Content-type: application/json; charset=utf-8");

$servername = "localhost";
$username = "id22182335_usuarios_db";
$password = "Usuarios_db1234";
$dbname = "id22182335_usuarios_db";

$usuario = $_POST["usuario"];
$contrasena = $_POST["contrasena"];

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die(json_encode(array("status" => "Connection failed: " . $conn->connect_error)));
}

$sql = $conn->prepare("SELECT * FROM usuarios_db WHERE usuario = ? AND contrasena = ?");
$sql->bind_param("ss", $usuario, $contrasena);
$sql->execute();
$result = $sql->get_result();

if ($result->num_rows > 0) {
    echo json_encode(array("status" => "ok"));
} else {
    echo json_encode(array("status" => "invalid"));
}

$sql->close();
$conn->close();

?>
