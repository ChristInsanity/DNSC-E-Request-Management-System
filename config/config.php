<?php
$host = 'localhost';
$db = 'dnsc_E-Request';
$user = 'root';
$pass = '';

$conn = new mysqli($host, $user, $pass, $db);
if ($conn ->connect_error){
    die("connection failed" . $conn -> connect_error);
}