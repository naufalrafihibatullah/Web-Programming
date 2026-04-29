<?php
$host = "127.0.0.1"; 
$user = "root";
$pass = "";          
$db   = "db_blog";
$port = 3307;        


$conn = new mysqli($host, $user, $pass, $db, $port);

if ($conn->connect_error) {
    die("Koneksi gagal bosku: " . $conn->connect_error);
}

$conn->set_charset("utf8mb4");

?>