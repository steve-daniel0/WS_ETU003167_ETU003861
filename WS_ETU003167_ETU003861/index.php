<?php

require_once 'flight/Flight.php';
require 'flight/autoload.php';

// require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/config/database.php';

use App\Controllers\GradeController;
use App\Controllers\StudentController;
use App\Models\Helpers;

// Erreurs globales
Flight::map('notFound', function () {
    Helpers::jsonError('NOT_FOUND', 'Route inconnue', 404);
});

Flight::map('error', function (Exception $ex) {
    // Si erreur de base de données
    if ($ex instanceof PDOException) {
        $message = $ex->getMessage();

        if (strpos($message, '1045') !== false) {
            Helpers::jsonError('DB_ACCESS_DENIED', 'Login ou mot de passe incorrect');
        } elseif (strpos($message, '2002') !== false) {
            Helpers::jsonError('DB_HOST_UNREACHABLE', 'Serveur MySQL inaccessible');
        } elseif (strpos($message, '1049') !== false) {
            Helpers::jsonError('DB_NOT_FOUND', 'Base de données inexistante');
        } elseif (strpos($message, '1062') !== false) {
            Helpers::jsonError('DUPLICATE_ENTRY', 'Valeur déjà existante');
        } else {
            Helpers::jsonError('DB_ERROR', 'Erreur SQL : ' . $message);
        }
    } else {
        // Si autre erreur
        Helpers::jsonError('UNKNOWN_ERROR', $ex->getMessage(), 500);
    }
});

// Routes REST
Flight::route('GET /Student', [StudentController::class, 'getAll']);

Flight::route('GET /grade/S/@id_semester/@id_student', [
    GradeController::class, 'getBySemester'
]);

Flight::route('GET /grade/L/@year/@id_student', [
    GradeController::class, 'getByYear'
]);

Flight::start();
