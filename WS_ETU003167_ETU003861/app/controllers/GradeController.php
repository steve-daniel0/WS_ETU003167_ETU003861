<?php
namespace App\Controllers;

use App\Models\Grade;
use App\Models\Student;
use Flight;

class GradeController
{
    public static function getBySemester($idSemester, $idStudent)
    {

        $model = new Grade(Flight::db());

        $Student = new Student(Flight::db());
        $Student->findByCode($idStudent);

        $results = $model->getGradesBySemester($idSemester, $idStudent);

        Flight::json([
            "status" => "success",
            "data" => [
                "semester_id" => $idSemester,
                "student_id" => $idStudent,
                "count" => count($results),
                "grades" => $results
            ],
            "error" => null
        ]);
    }

    public static function getByYear($year, $idStudent)
    {
        $model = new Grade(Flight::db());
        $results = $model->getGradesByYear($year, $idStudent);
        
        $Student = new Student(Flight::db());
        $Student->findByCode($idStudent);
        
        Flight::json([
            "status" => "success",
            "data" => [
                 "academic_year" => $year,
                "student_id" => $idStudent,
                "count" => count($results),
                "grades" => $results
            ],
            "error" => null
        ]);
    }

}
