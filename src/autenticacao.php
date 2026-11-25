<?php

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

function exigirLogin(): void
{
    if (!isset($_SESSION['usuario_id'])) {
        header('Location: login.php');
        exit;
    }
}
