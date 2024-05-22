<?php
$servername = "localhost";
$username = "id22182335_usuarios_db";
$password = "User_db1234*";
$dbname = "id22182335_usuarios_db";

// Intentar conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

echo "Connected successfully";
$conn->close();
?>
