<?php

require_once 'flight/Flight.php';
require 'flight/autoload.php';

// require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/config/database.php';

use App\Controllers\GradeController;
use App\Controllers\StudentController;
use App\Controllers\SemesterController;
use App\Models\Helpers;

// // Clé secrète pour signer les tokens
// define('SECRET_KEY', 'MA_CLE_SECRETE_123');

// // 👉 Vérification du token pour les routes protégées
// Flight::map('auth', function() {
//     $headers = apache_request_headers();

//     if (!isset($headers['Authorization'])) {
//         Flight::json(["status" => "error", "message" => "Utilisateur non authentifié"], 401);
//         exit;
//     }

//     $auth = $headers['Authorization']; // "Bearer XXXXXX"
//     $token = str_replace("Bearer ", "", $auth);

//     // Vérifier si le token est valide
//     if ($token !== hash('sha256', SECRET_KEY)) {
//         Flight::json(["status" => "error", "message" => "Token invalide"], 401);
//         exit;
//     }
// });

// Flight::route('POST /login', function() {
//     $data = Flight::request()->data;

//     $user = $data->username;
//     $pass = $data->password;

//     // TEST : login simple (tu remplaceras par ta BD)
//     if ($user === 'admin' && $pass === '123') {
//         $token = hash('sha256', SECRET_KEY);

//         Flight::json([
//             "status" => "success",
//             "token" => $token,
//             "error" => null
//         ]);
//     } else {
//         Flight::json(["status" => "error", "data" => "", "error"=>"Login incorrect"], 401);
//     }
// });

// Flight::before('start', function (&$params, &$output) {
//     $public = ['/login']; // Routes accessibles sans token
//     $requested = Flight::request()->url;

//     if (!in_array($requested, $public)) {
//         Flight::auth();
//     }
// });

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

// Ajout des headers CORS globaux
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

// Répondre immédiatement aux requêtes OPTIONS (préflight)
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') { 
    exit(0);
} 

// Alias en minuscule pour compatibilité avec les requêtes frontend
Flight::route('GET /student', [StudentController::class, 'getAll']);

// Routes pour les semestres
Flight::route('GET /Semester', [SemesterController::class, 'getAll']);
Flight::route('GET /semester', [SemesterController::class, 'getAll']);

// Liste des étudiants pour un semestre (par id de semester)
Flight::route('GET /Semester/@id/students', [SemesterController::class, 'getStudents']);
Flight::route('GET /semester/@id/students', [SemesterController::class, 'getStudents']);

Flight::route('GET /grade/S/@id_semester/@id_student', [
    GradeController::class, 'getBySemester'
]);

Flight::route('GET /grade/L/@year/@id_student', [
    GradeController::class, 'getByYear'
]);

Flight::route('GET /note/etu', [
    GradeController::class, 'getAllNoteEtu'
]);

Flight::route('GET /etu/@id/details', [
    GradeController::class, 'getEtuDetails'
]);

Flight::start();