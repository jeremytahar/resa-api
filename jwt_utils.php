<?php
require 'vendor/autoload.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

define('SECRET_KEY', 'test');
define('ALGORITHM', 'HS256');

function generateToken($user_id) {
    $payload = [
        'iat' => time(),
        'iss' => 'your_issuer',
        'sub' => $user_id,
        'exp' => time() + (60 * 60) 
    ];

    return JWT::encode($payload, SECRET_KEY, ALGORITHM);
}


function verifyToken($token) {
    try {
        $decoded = JWT::decode($token, new Key(SECRET_KEY, ALGORITHM));
        return $decoded;
    } catch (Exception $e) {
        return false;
    }
}
?>
