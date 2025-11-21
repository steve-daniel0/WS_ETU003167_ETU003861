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

    public static function getAllNoteEtu()
    {
        $results = [
            [
                "ETU" => [
                    "id" => 2,
                    "name" => "Johnson",
                    "first_name" => "Emma",
                    "birth" => "2001-08-20",
                    "student_code" => "ETU002"
                ],
                "S1" => 18,
                "S2" => 17,
                "S3" => 16,
                "S4" => [
                    [
                        "option_id" => 3,
                        "option_name" => "db",
                        "average" => 16,
                        "mention" => "Bien"
                    ],
                    [
                        "option_id" => 1,
                        "option_name" => "dev",
                        "average" => 15.9,
                        "mention" => "Bien"
                    ],
                    [
                        "option_id" => 2,
                        "option_name" => "web",
                        "average" => 14,
                        "mention" => "Assez bien"
                    ]
                ]
            ],
            [
                "ETU" => [
                    "id" => 3,
                    "name" => "Smith",
                    "first_name" => "Lucas",
                    "birth" => "2002-03-15",
                    "student_code" => "ETU003"
                ],
                "S1" => 12,
                "S2" => 14,
                "S3" => 13,
                "S4" => [
                    [
                        "option_id" => 3,
                        "option_name" => "db",
                        "average" => 11,
                        "mention" => "Passable"
                    ],
                    [
                        "option_id" => 1,
                        "option_name" => "dev",
                        "average" => 12.5,
                        "mention" => "Assez bien"
                    ],
                    [
                        "option_id" => 2,
                        "option_name" => "web",
                        "average" => 13.7,
                        "mention" => "Assez bien"
                    ]
                ]
            ]
        ];

        Flight::json([
            "status" => "success",
            "data" => $results,
            "error" => null
        ]);
    }

    public static function getEtuDetails($id)
    { 

        $results = [
            "ETU" => [
                "id" => 2,
                "name" => "Johnson",
                "first_name" => "Emma",
                "birth" => "2001-08-20",
                "student_code" => "ETU002"
            ],
            "S1" => 18,
            "S2" => 17,
            "S3" => 16,
            "S4" => [
                [
                    "option_id" => 3,
                    "option_name" => "db",
                    "average" => 16,
                    "mention" => "Bien"
                ],
                [
                    "option_id" => 1,
                    "option_name" => "dev",
                    "average" => 15.9,
                    "mention" => "Bien"
                ],
                [
                    "option_id" => 2,
                    "option_name" => "web",
                    "average" => 14,
                    "mention" => "Assez bien"
                ]
            ]
        ];

        Flight::json([
            "status" => "success",
            "data" => $results,
            "error" => null
        ]); 
    }

}
