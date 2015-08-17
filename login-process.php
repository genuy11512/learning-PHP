<?php
session_start();

$base_url = 'http://cas.hunghau.vn';
$encrypt_info = [
    'id' => 4,
    'secret_key' => 'jddWSBpIlEhqhrWDjAncPnoswtOwdbgGvQanroATAivmeWl0jEJmwbkmNViSvbDE',
    'encrypt_algo' => 'blowfish',
    'encrypt_mode' => 'ctr'
];

$auth_url = sprintf(
    "{$base_url}/auth/login?redirect=%s&sid=%d",
    'http://develop.hunghau.vn/login-process.php',
    $encrypt_info['id']
);

$key = $_GET['key'];

// Get payload string.
$payload = file_get_contents("{$base_url}/get-payload/{$key}");

if (is_null($key) || strlen($key) != 64 || is_null($payload)) {
    header("location: $auth_url");
}

$token = $key . $encrypt_info['secret_key'];

// decrypt payload data. It has 2 part. IV and data.
$payload = base64_decode($payload);

// Get algorithm.
$algorithm = $encrypt_info['encrypt_algo'];

// get mode
$mode = $encrypt_info['encrypt_mode'];

/* OPEN THE CIPHER */
$td = mcrypt_module_open($algorithm, null, $mode, null);

/* CREATE KEY */
$key = substr($token, 0, mcrypt_enc_get_key_size($td));

/* Get IV size */
$iv_size = mcrypt_enc_get_iv_size($td);

/* Get IV from encrypted data. */
$iv = substr($payload, 0, $iv_size);

/* Get encrypt data. */
$payload = str_replace($iv, '', $payload);

/* Intialize encryption */
@mcrypt_generic_init($td, $key, $iv);

$plaintext = @mdecrypt_generic($td, $payload);

mcrypt_generic_deinit($td);
mcrypt_module_close($td);

$data = @unserialize($plaintext);

if ($data === false) {
    header("location: $auth_url");
}

$_SESSION['user_login'] = $data;

header('location: index.php');