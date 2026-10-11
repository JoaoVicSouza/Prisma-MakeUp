<?php
function requireAdmin(string $redirectTo): void
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if ((string) ($_SESSION['admin'] ?? '') !== '1') {
        header('Location: ' . $redirectTo);
        exit;
    }
}
