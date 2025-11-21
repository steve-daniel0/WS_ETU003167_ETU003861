<?php
use App\Models\Helpers;

try {
    // Enregistrement de la connexion PDO dans Flight
    Flight::register('db', 'PDO', array(
        'mysql:host=' . getenv('DB_HOST') . ';dbname=' . getenv('DB_NAME'),
        getenv('DB_USER'),
        getenv('DB_PASS')
    ), function ($db) {
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    });

} catch (PDOException $e) {
    Helpers::jsonError('DB_CONNECTION_ERROR', 'Impossible de se connecter à la base de données : ' . $e->getMessage(), 500);
}
