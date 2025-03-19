<?php
// Database connection
function dbConnect() {
    try {
        $db = new PDO('mysql:host=localhost:3306;dbname=tahar_ag_resa;charset=utf8', 'tahar_ag_resa', '@JeremyAdmin1234+');
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $db;
    } catch (Exception $e) {
        die('Erreur : ' . $e->getMessage());
    }
}

// Verify JWT
function verifyJWT() {
    $headers = apache_request_headers();
    error_log("En-têtes reçus : " . json_encode($headers));
    if (isset($headers['Authorization'])) {
        $token = str_replace('Bearer ', '', $headers['Authorization']);
        error_log("Token reçu : " . $token);
        $decoded = verifyToken($token);
        if ($decoded) {
            error_log("Token décodé : " . json_encode($decoded));
            return $decoded;
        } else {
            error_log("Échec du décodage du token");
        }
    } else {
        error_log("Aucun en-tête Authorization trouvé");
    }
    http_response_code(401);
    echo json_encode(["success" => false, "message" => "Token invalide ou expiré"]);
    exit;
}

// Get all reservations
function getAllReservations() {
    $db = dbConnect();
    $query = $db->query('SELECT * FROM reservation ORDER BY id DESC');
    return $query->fetchAll(PDO::FETCH_ASSOC);
}

// Get last five reservations 
function getLastReservations() {
    $db = dbConnect();
    $query = $db->query('SELECT * FROM reservation ORDER BY id DESC LIMIT 5');
    return $query->fetchAll(PDO::FETCH_ASSOC);
}

// Add a reservation
function addReservation($data) {
    $db = dbConnect();
    $query = $db->prepare('INSERT INTO reservation (last_name, first_name, phone_number, email, date_time, participants, promo_code) 
                           VALUES (:last_name, :first_name, :phone_number, :email, :date_time, :participants, :promo_code)');
    
    $query->bindParam(':last_name', $data['last_name']);
    $query->bindParam(':first_name', $data['first_name']);
    $query->bindParam(':phone_number', $data['phone_number']);
    $query->bindParam(':email', $data['email']);
    $query->bindParam(':date_time', $data['date_time']);
    $query->bindParam(':participants', $data['participants']);
    $query->bindParam(':promo_code', $data['promo_code']);

    if ($query->execute()) {
        return "Réservation ajoutée avec succès";
    } else {
        return "Erreur lors de l'ajout de la réservation";
    }
}

// Delete a reservation
function deleteReservation($id) {
    $db = dbConnect();
    $query = $db->prepare('DELETE FROM reservation WHERE id = :id');
    $query->bindParam(':id', $id);
    if ($query->execute()) {
        return "Réservation supprimée avec succès";
    } else {
        return "Erreur lors de la suppression de la réservation";
    }
}

// Update a reservation
function updateReservation($id, $data) {
    $db = dbConnect();
    $query = $db->prepare('UPDATE reservation 
                           SET last_name = :last_name, 
                               first_name = :first_name, 
                               phone_number = :phone_number, 
                               email = :email, 
                               date_time = :date_time,
                               participants = :participants,
                               promo_code = :promo_code
                           WHERE id = :id');

    $query->bindParam(':id', $id, PDO::PARAM_INT);
    $query->bindParam(':last_name', $data['last_name']);
    $query->bindParam(':first_name', $data['first_name']);
    $query->bindParam(':phone_number', $data['phone_number']);
    $query->bindParam(':email', $data['email']);
    $query->bindParam(':date_time', $data['date_time']);
    $query->bindParam(':participants', $data['participants']);
    $query->bindParam(':promo_code', $data['promo_code']);

    if ($query->execute()) {
        return "Réservation mise à jour avec succès";
    } else {
        return "Erreur lors de la mise à jour de la réservation";
    }
}
?>
