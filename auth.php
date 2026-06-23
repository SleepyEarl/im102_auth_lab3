<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function requireLogin() {
    if (!isLoggedIn()) {
        header('Location: login.php');
        exit;
    }
}

function isAdmin() {
    return (isset($_SESSION['role']) && $_SESSION['role'] === 'admin');
}

function requireAdmin() {
    if (!isAdmin()) {
        die("Access Denied: You do not have permission to view this resource.");
    }
}

function getUsername() {
    return isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : 'User';
}