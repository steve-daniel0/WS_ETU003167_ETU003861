<?php
namespace App\Controllers;

use App\Models\Student;
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
}
