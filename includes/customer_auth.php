<?php
// includes/customer_auth.php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function customer_is_logged_in(): bool
{
    return !empty($_SESSION['customer_logged_in']) && !empty($_SESSION['customer_user']);
}

function customer_current_user(): ?array
{
    if (!customer_is_logged_in()) {
        return null;
    }

    return $_SESSION['customer_user'];
}

function customer_login_user(array $user): void
{
    session_regenerate_id(true);

    $_SESSION['customer_logged_in'] = true;
    $_SESSION['customer_user'] = [
        'id' => (int)($user['id'] ?? 0),
        'full_name' => (string)($user['full_name'] ?? ''),
        'email' => (string)($user['email'] ?? ''),
        'phone' => (string)($user['phone'] ?? ''),
        'default_address' => (string)($user['default_address'] ?? ''),
    ];
}

function customer_logout_user(): void
{
    unset($_SESSION['customer_logged_in'], $_SESSION['customer_user']);
}

function customer_require_login(string $login_url): void
{
    if (customer_is_logged_in()) {
        return;
    }

    header('Location: ' . $login_url);
    exit;
}
