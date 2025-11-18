<?php
session_start();

function is_logged_in(): bool {
    // Authentication disabled: always consider the user as logged in
    return true;
}

function require_login(): void {
    // Authentication disabled: do nothing, allow access to all pages
}

function login($user_id, $username): void {
    // Authentication disabled: no session data needed
}

function logout(): void {
    // Authentication disabled: no logout behavior
}
?>
