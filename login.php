<?php
require_once 'vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$allowedOrigin = '*';

// Gérer la requête OPTIONS (pré-vérification CORS)
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    header("Access-Control-Allow-Origin: $allowedOrigin");
    header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type, Authorization");
    http_response_code(200);
    exit;
}

// Le reste du code (votre logique existante)
require_once('jwt_utils.php');

header("Access-Control-Allow-Origin: $allowedOrigin");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Content-Type: application/json");

$input = json_decode(file_get_contents("php://input"), true);

if (isset($input['username'], $input['password'])) {
    $username = $input['username'];
    $password = $input['password'];

    // Comparaison avec les valeurs .env
    if ($username === $_ENV['USERNAME'] && $password === $_ENV['PASSWORD']) {
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
