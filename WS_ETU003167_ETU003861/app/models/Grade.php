<?php
namespace App\Models;

use PDO;

class Grade
{
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    

    /**
     * Notes d’un étudiant sur un semestre donné
     */
    public function getGradesBySemester($idSemester, $idStudent)
    {
        $sql = "
            SELECT 
                sj.id AS subject_id,
                sj.name AS subject_name,
                ss.credit,
                so.label AS option_name,
                sg.grade,
                es.date AS exam_date
            FROM student_semester stsem
            JOIN semester_year sy ON stsem.semester_year_id = sy.id
            JOIN semester_subject ss ON ss.semester_id = sy.semester_id
            JOIN subject sj ON sj.id = ss.subject_id
            LEFT JOIN student_option so ON so.id = ss.option_id
            LEFT JOIN student_grade sg ON sg.student_semester_id = stsem.id 
                AND sg.subject_id = sj.id
            LEFT JOIN exam_session es ON es.id = sg.exam_session_id
            WHERE sy.semester_id = ?
              AND stsem.student_id = ?
            ORDER BY sj.name ASC
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$idSemester, $idStudent]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Notes d’un étudiant sur une année donnée
     */
    public function getGradesByYear($year, $idStudent)
    {
        $sql = "
            SELECT 
                sj.id AS subject_id,
                sj.name AS subject_name,
                ss.credit,
                so.label AS option_name,
                sg.grade,
                es.date AS exam_date
            FROM student_semester stsem
            JOIN semester_year sy ON stsem.semester_year_id = sy.id
            JOIN semester_subject ss ON ss.semester_id = sy.semester_id
            JOIN subject sj ON sj.id = ss.subject_id
            LEFT JOIN student_option so ON so.id = ss.option_id
            LEFT JOIN student_grade sg ON sg.student_semester_id = stsem.id 
                AND sg.subject_id = sj.id
            LEFT JOIN exam_session es ON es.id = sg.exam_session_id
            JOIN semester s ON sg.semester_id=s.id
            WHERE s.year = ?
                AND stsem.student_id = ?
            ORDER BY sj.name ASC
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$year, $idStudent]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
