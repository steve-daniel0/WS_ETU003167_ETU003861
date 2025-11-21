<?php
namespace App\Models;

use PDO;

class Student {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function getAll() {
        $stmt = $this->db->query("SELECT * FROM student ORDER BY id DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findByCode($id): array
    {
        $stmt = $this->db->prepare("SELECT * FROM student WHERE id = ?");
        $stmt->execute([$id]);
        $student = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$student) {
            Helpers::jsonResponse(
                null,
                'error',
                404,
                [
                    'code' => 'STUDENT_NOT_FOUND',
                    'message' => "L'étudiant avec le code '$id' n'existe pas."
                ]
            );
        }

        return $student;
    }

    public function getSemesterAveragesByStudent($idStudent)
    {
        $grade = new Grade($this->db);
        $semesters = [1, 2, 3, 4];
        $result = [];

        foreach ($semesters as $semId) {
            // Moyenne des matières communes
            $avgCommon = $grade->getAverage($semId, null, $idStudent);

            // Moyenne par option
            $options = $grade->getOptionsBySemester($semId);
            $optionsResult = [];

            foreach ($options as $opt) {
                $avg = $grade->getAverage($semId, $opt["option_id"], $idStudent);
                $optionsResult[] = [
                    "option_id"   => $opt["option_id"],
                    "option_name" => $opt["option_name"],
                    "average"     => $avg
                ];
            }

            $result[$semId] = [
                "average_common" => $avgCommon,
                "options"        => $optionsResult
            ];
        }

        return [
            "Student"=>$this->findByCode($idStudent),
            "s"=>$result, 
        ];

    }
}