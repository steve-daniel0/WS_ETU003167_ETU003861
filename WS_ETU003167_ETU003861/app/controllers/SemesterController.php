<?php
namespace App\Controllers;

use App\Models\Semester;
use Flight;

class SemesterController {

    public static function getAll() {
        $model = new Semester(Flight::db());
        Flight::json([
            "status" => "success",
            "data" => $model->getAll(),
            "error" => null
        ]);
    }

    public static function getStudents($id) {
        $model = new Semester(Flight::db());
        $students = $model->getStudentsBySemester($id);

        Flight::json([
            "status" => "success",
            "data" => $students,
            "error" => null
        ]);
    }

}
