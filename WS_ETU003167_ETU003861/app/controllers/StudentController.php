<?php
namespace App\Controllers;

use App\Models\Student;
use App\Models\Grade;
use Flight;

class StudentController {

    public static function getAll() {
        $model = new Student(Flight::db());
        Flight::json([   
            "status" => "success",
            "data" => $model->getAll(),
            "error" => null
        ]);

    }

    /**
     * Retourne les moyennes d’un étudiant pour S1, S2, S3 et S4
     * Inclut les options si elles existent
     */
    public static function getSemesterAveragesByStudent($idStudent)
    {
        $student = new Student(Flight::db());

        Flight::json([   
            "status" => "success",
            "data" => $student->getSemesterAveragesByStudent($idStudent),
            "error" => null
        ]);

    }

    public static function getAllWithAverages() {
        $db = Flight::db();
        $studentModel = new Student($db);

        $students = $studentModel->getAll(); // récupère tous les étudiants
        $result = [];

        foreach ($students as $student) {
            $studentId = $student['id'];

            // Réutilisation de la fonction précédente
            $result[] =  $studentModel->getSemesterAveragesByStudent($studentId);
        }

        Flight::json([
            "status" => "success",
            "data"   => $result,
            "error"  => null
        ]);
    }

}
