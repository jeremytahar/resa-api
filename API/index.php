<?php
// POUR VERSION HÉBERGÉE
// $allowedOrigin = 'https://aliceguy.eu';
// $origin = $_SERVER['HTTP_ORIGIN'] ?? '';

// if ($origin !== $allowedOrigin) {
//     http_response_code(403); // Accès interdit
//     echo json_encode(["success" => false, "message" => "Accès non autorisé"]);
//     exit;
// }

// Le reste du code (votre logique existante)
require_once('jwt_utils.php');
require_once('model.php');

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

$request_method = $_SERVER['REQUEST_METHOD'];

switch ($request_method) {
    case 'POST':
        $input = json_decode(file_get_contents("php://input"), true);

        if (isset($input['last_name'], $input['first_name'], $input['phone_number'], $input['email'], $input['date_time'])) {
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
        error_log("Requête GET reçue");
        $user = verifyJWT();
        $reservations = getAllReservations();
        error_log("Réservations récupérées : " . json_encode($reservations));
        echo json_encode($reservations);
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

        if (isset($input['id'], $input['last_name'], $input['first_name'], $input['phone_number'], $input['email'], $input['date_time'])) {
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
