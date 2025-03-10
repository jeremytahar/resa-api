<?php
// POUR VERSION HÉBERGÉE
// $allowedOrigin = 'https://aliceguy.eu';
// $origin = $_SERVER['HTTP_ORIGIN'] ?? '';

// if ($origin !== $allowedOrigin) {
//     http_response_code(403); // Accès interdit
//     echo json_encode(["success" => false, "message" => "Accès non autorisé"]);
//     exit;
// }

require_once('jwt_utils.php');

header("Access-Control-Allow-Origin: *"); // Remplace * par un domaine spécifique si nécessaire
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Content-Type: application/json");

$input = json_decode(file_get_contents("php://input"), true);

if (isset($input['username'], $input['password'])) {
    $username = $input['username'];
    $password = $input['password'];

    if ($username === 'admin' && $password === 'password') {
        $token = generateToken($username);
        echo json_encode([
            'success' => true,
            'token' => $token
        ]);
    } else {
        error_log("Login failed: incorrect username or password for username = $username");
        http_response_code(401);
        echo json_encode([
            'success' => false,
            'message' => 'Nom d\'utilisateur ou mot de passe incorrect.'
        ]);
    }
} else {
    error_log("Login failed: missing username or password");
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => 'Erreur : Tous les champs doivent être renseignés.'
    ]);
}
?>
