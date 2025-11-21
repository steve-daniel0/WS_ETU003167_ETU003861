<?php
namespace App\Controllers;

use App\Models\Grade;
use App\Models\Student;
use Flight;

class GradeController
{
    /**
     * Notes + moyenne d’un semestre
     */
    public static function getBySemester($idSemester, $idStudent)
    {
        $model = new Grade(Flight::db());
        $studentModel = new Student(Flight::db());
        $studentModel->findByCode($idStudent);

        // Notes
        $results = $model->getGradesBySemester($idSemester, $idStudent);

        // Options existantes
        $options = $model->getOptionsBySemester($idSemester);

        // Moyenne commune
        $avgCommon = $model->getAverage($idSemester, null, $idStudent);
        $mentionCommon = $avgCommon !== null ? $model->getMentionByAverage($avgCommon) : null;

        // Moyenne par option
        $optionsResult = [];
        foreach ($options as $opt) {
            $avg = $model->getAverage($idSemester, $opt["option_id"], $idStudent);
            $mention = $avg !== null ? $model->getMentionByAverage($avg) : null;

            $optionsResult[] = [
                "option_id"   => $opt["option_id"],
                "option_name" => $opt["option_name"],
                "average"     => $avg,
                "mention"     => $mention
            ];
        }

        Flight::json([
            "status" => "success",
            "data" => [
                "semester_id"      => $idSemester,
                "student_id"       => $idStudent,
                "count"            => count($results),
                "grades"           => $results,
                "average_common"   => $avgCommon,
                "mention_common"   => $mentionCommon,
                "options"          => $optionsResult
            ],
            "error" => null
        ]);
    }

    public static function getS4ByOption( $idOption, $idStudent)
    {
        $model = new Grade(Flight::db());
        $results = $model->getS4ByOption($idStudent, $idOption);

        Flight::json([
            "status" => "success",
            "data" => $results,
            "error" => null
        ]);
    }

    /**
     * Notes + moyenne annuelle
     */
    public static function getByYear($year, $idStudent)
    {
        $model = new Grade(Flight::db());
        $studentModel = new Student(Flight::db());
        $studentModel->findByCode($idStudent);

        // Notes brutes
        $results = $model->getGradesByYear($year, $idStudent);

        // On doit grouper par semestre
        $semesters = [];
        foreach ($results as $row) {
            $sem = $row["semester_id"];
            if (!isset($semesters[$sem])) {
                $semesters[$sem] = [
                    "grades" => [],
                    "average" => null,
                    "mention" => null
                ];
            }
            $semesters[$sem]["grades"][] = $row;
        }

        // Calcul des moyennes par semestre
        $semesterAverages = [];
        foreach ($semesters as $semId => &$semData) {

            // Moyenne commune
            $avgCommon = $model->getAverage($semId, null, $idStudent);
            $mentionCommon = $avgCommon !== null ? $model->getMentionByAverage($avgCommon) : null;

            // Options
            $options = $model->getOptionsBySemester($semId);
            $bestOptionAvg = $avgCommon;
            $bestOptionMention = $mentionCommon;

            foreach ($options as $opt) {
                $avg = $model->getAverage($semId, $opt["option_id"], $idStudent);

                if ($avg !== null && $avg > $bestOptionAvg) {
                    $bestOptionAvg = $avg;
                    $bestOptionMention = $model->getMentionByAverage($avg);
                }
            }

            $semData["average"] = $bestOptionAvg;
            $semData["mention"] = $bestOptionMention;

            $semesterAverages[] = $bestOptionAvg;
        }

        // Moyenne annuelle
        $annualAverage = null;
        $annualMention = null;

        if (!empty($semesterAverages)) {
            $annualAverage = array_sum($semesterAverages) / count($semesterAverages);
            $annualAverage = round($annualAverage, 2);
            $annualMention = $model->getMentionByAverage($annualAverage);
        }

        Flight::json([
            "status" => "success",
            "data" => [
                "academic_year"    => $year,
                "student_id"       => $idStudent,
                // "grades"           => $results,
                "semesters"        => $semesters,
                "annual_average"   => $annualAverage,
                "annual_mention"   => $annualMention
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
