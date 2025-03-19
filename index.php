<?php
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
require_once('model.php');

header("Access-Control-Allow-Origin: $allowedOrigin");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

$request_method = $_SERVER['REQUEST_METHOD'];

switch ($request_method) {
    case 'POST':
        $input = json_decode(file_get_contents("php://input"), true);

        if (isset($input['last_name'], $input['first_name'], $input['phone_number'], $input['email'], $input['date_time'], $input['participants'], $input['promo_code'])) {
            $message = AddReservation($input);
            echo json_encode([
                'success' => true,
                'message' => $message
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Erreur : Tous les champs doivent être renseignés.'
            ]);
        }
        break;

    case 'GET':
        // Vérification JWT
        $user = verifyJWT();
        
        // Si la requête est pour récupérer les 5 dernières réservations
        if (isset($_GET['last5']) && $_GET['last5'] === 'true') {
            error_log("Requête GET pour les 5 dernières réservations reçue");
            $reservations = getLastReservations();
            error_log("5 dernières réservations récupérées : " . json_encode($reservations));
            echo json_encode($reservations);
        } else {
            // Sinon, récupérer toutes les réservations
            error_log("Requête GET reçue pour toutes les réservations");
            $reservations = getAllReservations();
            error_log("Réservations récupérées : " . json_encode($reservations));
            echo json_encode($reservations);
        }
        break;

    case 'DELETE':
        $user = verifyJWT();
        $input = json_decode(file_get_contents("php://input"), true);

        if (isset($input['id'])) {
            $message = deleteReservation($input['id']);
            echo json_encode([
                'success' => true,
                'message' => $message
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Erreur : ID de la réservation manquant.'
            ]);
        }
        break;

    case 'PUT':
        $user = verifyJWT();
        $input = json_decode(file_get_contents("php://input"), true);

        if (isset($input['id'], $input['last_name'], $input['first_name'], $input['phone_number'], $input['email'], $input['date_time'], $input['participants'], $input['promo_code'])) {
            $message = updateReservation($input['id'], $input);
            echo json_encode([
                'success' => true,
                'message' => $message
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Erreur : Tous les champs doivent être renseignés.'
            ]);
        }
        break;

    default:
        http_response_code(405);
        echo json_encode(["success" => false, "message" => "Méthode non autorisée"]);
        break;
}
?>
