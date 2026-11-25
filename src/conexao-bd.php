<?php

$host = getenv('DB_HOST') ?: 'localhost';
$database = getenv('DB_NAME') ?: 'cafézin_de_cria';
$username = getenv('DB_USER');
$password = getenv('DB_PASSWORD');

if ($username === false || $password === false) {
    throw new RuntimeException('Defina DB_USER e DB_PASSWORD no ambiente.');
}

$pdo = new PDO("mysql:host={$host};dbname={$database}", $username, $password);

?> 