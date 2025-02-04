<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

$servername = "localhost";
$username = "root";  // Cambia si usas otro usuario en MySQL
$password = "";      // Cambia si tienes contraseña en MySQL
$database = "cognitive_learning_db";

// Conexión a MySQL
$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die(json_encode(["error" => "Conexión fallida: " . $conn->connect_error]));
}

// Registrar usuario
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $data = json_decode(file_get_contents("php://input"), true);

    if (!isset($data["nombre"]) || !isset($data["edad"]) || empty(trim($data["nombre"]))) {
        echo json_encode(["error" => "Nombre y edad son obligatorios"]);
        exit;
    }

    $nombre = $conn->real_escape_string($data["nombre"]);
    $edad = intval($data["edad"]);

    $sql = "INSERT INTO users (nombre, edad) VALUES ('$nombre', $edad)";

    if ($conn->query($sql) === TRUE) {
        echo json_encode(["message" => "Usuario registrado con éxito", "userId" => $conn->insert_id]);
    } else {
        echo json_encode(["error" => "Error en SQL: " . $conn->error]);
    }
}

$conn->close();
?>
