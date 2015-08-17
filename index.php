<?php
session_start();

// Check user login
if (!isset($_SESSION['user_login'])) {
    $url = sprintf(
        // C.A.S URL format.
        'http://cas.hunghau.vn/auth/login?redirect=%s&sid=%d',

        // Redirect URL format after user login successful.
        'http://develop.hunghau.vn/login-process.php',

        // client ID
        4
    );

    header("location: $url");
}

var_dump($_SESSION['user_login']);