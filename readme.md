# API

This is a basic PHP API for managing reservations. It includes authentification using JWT. It supports CRUD operations (Create, Read, Update, Delete) on reservations. 

## How to install 

To use our API in your projects:
1. Clone the repository
2. Install dependencies using `composer install`
3. Configure db connection in model.php 
```php 
$db = new PDO('mysql:host=[HOST:PORT];dbname=[DBNAME];charset=utf8', '[USERNAME]', '[PASSWORD]');
```

## How to use 

### Authentification

To authentificate, make a POST request to `/login.php` with your username and password (defined in login.php)

#### Request:
```json
{
  "username": "admin",
  "password": "password"
}
```

#### Response: 
```json
{
  "success": true,
  "token": "your_jwt_token"
}
```

### Get all reservations 
To retrieve all reservations, make a GET request to the `/index.php` endpoint with the JWT token in the Authorization header.

#### Request Headers: 
```json
{
  "Authorization": "Bearer your_jwt_token"
}
```

#### Response: 
```json
[
  {
    "id": 1,
    "last_name": "lastname",
    "first_name": "first_name",
    "phone_number": "0102030405",
    "email": "email@mail.fr",
    "date_time": "2025-10-01 17:00:00"
  }
]
```

### Add a reservation 
To add a new reservation, make a POST request to the `/index.php` endpoint with the reservation details

#### Request body: 
```json 
{
    "last_name": "lastname",
    "first_name": "first_name",
    "phone_number": "0102030405",
    "email": "email@mail.fr",
    "date_time": "2025-10-01 17:00:00"
  }
```

#### Response: 
```json
{
  "success": true,
  "message": "Réservation ajoutée avec succès"
}
```

### Update a reservation
To update an existing reservation, make a PUT request to the `/index.php` endpoint with the reservation details and the JWT token in the Authorization header.

#### Request Headers: 
```json
{
  "Authorization": "Bearer your_jwt_token"
}
```

#### Request body: 
```json 
{
    "id": 1,
    "last_name": "lastname",
    "first_name": "first_name",
    "phone_number": "0102030405",
    "email": "email@mail.fr",
    "date_time": "2025-10-01 17:00:00"
}
```

#### Response: 
```json
{
  "success": true,
  "message": "Réservation mise à jour avec succès"
}
```

### delete a reservation
To delete an existing reservation, make a DELETE request to the `/index.php` endpoint with the reservation ID and the JWT token in the Authorization header.

#### Request Headers: 
```json
{
  "Authorization": "Bearer your_jwt_token"
}
```

#### Request body: 
```json 
{
  "id": 1
}
```

#### Response: 
```json
{
  "success": true,
  "message": "Réservation supprimée avec succès"
}
```

## CORS 

The API supports CORS with the following headers: 
• `Access-Control-Allow-Origin: *`
• `Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS`
• `Access-Control-Allow-Headers: Content-Type, Authorization`

You can replace `*` in `Access-Control-Allow-Origin: *` with your domain to limit API access. 


